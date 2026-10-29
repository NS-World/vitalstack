<?php
/**
 * Certificates: a reader who completes every lesson of a learning path gets
 * a certificate with a public verify link (/certificate/<id>/), printable as
 * PDF and addable to LinkedIn. It stays locked until the reader has an
 * account, has confirmed their email and is subscribed to lesson emails;
 * it is issued the moment the last of those is done.
 *
 * Stored as user meta rows "vs_cert" = "<id>|<path-slug>|<Y-m-d>|<name>".
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_cert_rewrite() {
	add_rewrite_rule( '^certificate/([a-z0-9]{12})/?$', 'index.php?vs_cert=$matches[1]', 'top' );
}
add_action( 'init', 'vitalstack_cert_rewrite' );

add_filter(
	'query_vars',
	function ( $vars ) {
		$vars[] = 'vs_cert';
		return $vars;
	}
);

function vitalstack_cert_parse( $raw ) {
	$parts = explode( '|', (string) $raw, 4 );
	if ( count( $parts ) < 4 ) {
		return null;
	}
	return array(
		'id'   => $parts[0],
		'path' => $parts[1],
		'date' => $parts[2],
		'name' => $parts[3],
	);
}

/**
 * Certificates a user has earned, keyed by path slug.
 */
function vitalstack_user_certs( $user_id ) {
	$out = array();
	foreach ( (array) get_user_meta( $user_id, 'vs_cert' ) as $raw ) {
		$c = vitalstack_cert_parse( $raw );
		if ( $c ) {
			$out[ $c['path'] ] = $c;
		}
	}
	return $out;
}

function vitalstack_cert_url( $id ) {
	return home_url( '/certificate/' . $id . '/' );
}

/**
 * Find a certificate by id: array( cert, user_id ) or null.
 */
function vitalstack_cert_find( $id ) {
	global $wpdb;
	if ( ! preg_match( '/^[a-z0-9]{12}$/', $id ) ) {
		return null;
	}
	$row = $wpdb->get_row( $wpdb->prepare( "SELECT user_id, meta_value FROM {$wpdb->usermeta} WHERE meta_key = 'vs_cert' AND meta_value LIKE %s LIMIT 1", $wpdb->esc_like( $id . '|' ) . '%' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	if ( ! $row ) {
		return null;
	}
	$cert = vitalstack_cert_parse( $row->meta_value );
	return $cert ? array( $cert, (int) $row->user_id ) : null;
}

/**
 * The steps that unlock certificates, each true once done.
 */
function vitalstack_cert_steps( $user_id ) {
	$user_id = (int) $user_id;
	return array(
		'account'    => $user_id > 0,
		'verified'   => $user_id > 0 && vitalstack_is_verified( $user_id ),
		'subscribed' => $user_id > 0 && vitalstack_is_subscribed( $user_id ),
	);
}

function vitalstack_cert_unlocked( $user_id ) {
	return ! in_array( false, vitalstack_cert_steps( $user_id ), true );
}

/**
 * Paths whose lessons are all done, keyed by slug.
 */
function vitalstack_completed_paths( $progress ) {
	$out = array();
	foreach ( vitalstack_learning_paths() as $path ) {
		if ( empty( $progress[ $path->slug ] ) ) {
			continue;
		}
		$lessons = vitalstack_path_lessons( $path );
		if ( $lessons && ! array_diff( $lessons, array_map( 'intval', (array) $progress[ $path->slug ] ) ) ) {
			$out[ $path->slug ] = $path;
		}
	}
	return $out;
}

/**
 * Issue certificates for every path the user has now fully completed.
 *
 * @param int   $user_id  User.
 * @param array $progress path-slug => lesson ids.
 * @return array Newly issued certificates (with url and course name).
 */
function vitalstack_certs_check( $user_id, $progress ) {
	if ( ! vitalstack_cert_unlocked( $user_id ) ) {
		return array();
	}
	$have = vitalstack_user_certs( $user_id );
	$new  = array();
	foreach ( vitalstack_completed_paths( $progress ) as $path ) {
		if ( isset( $have[ $path->slug ] ) ) {
			continue;
		}
		$user = get_userdata( $user_id );
		$name = trim( str_replace( '|', ' ', $user->display_name ) );
		$id   = strtolower( wp_generate_password( 12, false, false ) );
		add_user_meta( $user_id, 'vs_cert', $id . '|' . $path->slug . '|' . wp_date( 'Y-m-d' ) . '|' . $name );
		if ( function_exists( 'vitalstack_xp_award' ) ) {
			vitalstack_xp_award( $user_id, 100, 'cert:' . $path->slug );
		}
		$new[] = array(
			'id'     => $id,
			'url'    => vitalstack_cert_url( $id ),
			'course' => $path->name,
		);
	}
	return $new;
}

/**
 * Courses the user just finished while certificates are still locked, so
 * the page can tell them what's left. Each course is reported once.
 */
function vitalstack_certs_locked( $user_id, $progress ) {
	if ( vitalstack_cert_unlocked( $user_id ) ) {
		return array();
	}
	$told = (array) get_user_meta( $user_id, 'vs_cert_told', true );
	$out  = array();
	foreach ( vitalstack_completed_paths( $progress ) as $slug => $path ) {
		if ( in_array( $slug, $told, true ) ) {
			continue;
		}
		$told[] = $slug;
		$out[]  = array(
			'course' => $path->name,
			'steps'  => vitalstack_cert_steps( $user_id ),
			'url'    => vitalstack_account_url() . '#certs',
		);
	}
	if ( $out ) {
		update_user_meta( $user_id, 'vs_cert_told', $told );
	}
	return $out;
}

/**
 * Confirming the email or subscribing can be the last step: issue any
 * certificates that were waiting and keep them for the next page view's popup.
 */
function vitalstack_certs_on_unlock( $meta_id, $user_id, $key, $value ) {
	if ( ! in_array( $key, array( 'vs_subscribed', 'vs_email_verified' ), true ) || ! $value || ! function_exists( 'vitalstack_get_user_progress' ) ) {
		return;
	}
	$new = vitalstack_certs_check( $user_id, vitalstack_get_user_progress( $user_id ) );
	if ( $new ) {
		update_user_meta( $user_id, 'vs_cert_pending', array_merge( (array) get_user_meta( $user_id, 'vs_cert_pending', true ), $new ) );
	}
}
add_action( 'added_user_meta', 'vitalstack_certs_on_unlock', 10, 4 );
add_action( 'updated_user_meta', 'vitalstack_certs_on_unlock', 10, 4 );

/**
 * Certificates issued since the last page view (for the popup), then cleared.
 */
function vitalstack_certs_take_pending( $user_id ) {
	$pending = get_user_meta( $user_id, 'vs_cert_pending', true );
	if ( ! $pending ) {
		return array();
	}
	delete_user_meta( $user_id, 'vs_cert_pending' );
	return array_values( array_filter( (array) $pending, 'is_array' ) );
}

/**
 * LinkedIn "Add to profile" link for a certificate.
 */
function vitalstack_cert_linkedin_url( $cert, $course ) {
	$time = strtotime( $cert['date'] );
	return add_query_arg(
		array(
			'startTask'        => 'CERTIFICATION_NAME',
			'name'             => rawurlencode( $course . ' Course' ),
			'organizationName' => rawurlencode( get_bloginfo( 'name' ) ),
			'issueYear'        => gmdate( 'Y', $time ),
			'issueMonth'       => gmdate( 'n', $time ),
			'certUrl'          => rawurlencode( vitalstack_cert_url( $cert['id'] ) ),
			'certId'           => $cert['id'],
		),
		'https://www.linkedin.com/profile/add'
	);
}

/**
 * Render /certificate/<id>/.
 */
function vitalstack_cert_template() {
	$id = get_query_var( 'vs_cert' );
	if ( ! $id ) {
		return;
	}
	$found = vitalstack_cert_find( $id );
	if ( ! $found ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		return;
	}
	list( $cert, $user_id ) = $found;
	$term   = get_term_by( 'slug', $cert['path'], 'tutorial_category' );
	$course = $term ? $term->name : ucfirst( $cert['path'] );
	$count  = $term ? count( vitalstack_path_lessons( $term ) ) : 0;
	$mine   = get_current_user_id() === $user_id;
	$url    = vitalstack_cert_url( $cert['id'] );
	$share  = rawurlencode( sprintf( 'I just completed the %s course on %s! %s', $course, get_bloginfo( 'name' ), $url ) );

	add_filter(
		'pre_get_document_title',
		function () use ( $cert, $course ) {
			/* translators: 1: name, 2: course */
			return sprintf( __( '%1$s – %2$s certificate', 'vitalstack' ), $cert['name'], $course ) . ' | ' . get_bloginfo( 'name' );
		}
	);
	add_filter(
		'wp_robots',
		function ( $robots ) {
			$robots['noindex'] = true;
			return $robots;
		}
	);
	status_header( 200 );
	get_header();
	?>
	<section class="cert-page">
		<div class="wrap">
			<div class="cert" id="certificate">
				<div class="cert-inner">
					<div class="cert-top">
						<span class="cert-mark"><?php echo vitalstack_logo_mark( 56 ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
						<span class="cert-brand"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
					</div>
					<p class="cert-kicker"><?php esc_html_e( 'Certificate of Completion', 'vitalstack' ); ?></p>
					<p class="cert-presented"><?php esc_html_e( 'This certifies that', 'vitalstack' ); ?></p>
					<h1 class="cert-name"><?php echo esc_html( $cert['name'] ); ?></h1>
					<p class="cert-text">
						<?php
						/* translators: 1: course, 2: number of lessons */
						echo esc_html( sprintf( _n( 'has successfully completed all %2$d lesson of the %1$s course.', 'has successfully completed all %2$d lessons of the %1$s course.', $count, 'vitalstack' ), $course, $count ) );
						?>
					</p>
					<div class="cert-foot">
						<div><span class="cert-label"><?php esc_html_e( 'Date', 'vitalstack' ); ?></span><b><?php echo esc_html( wp_date( get_option( 'date_format' ), strtotime( $cert['date'] ) ) ); ?></b></div>
						<div class="cert-seal" aria-hidden="true">🎓</div>
						<div><span class="cert-label"><?php esc_html_e( 'Certificate ID', 'vitalstack' ); ?></span><b><?php echo esc_html( strtoupper( $cert['id'] ) ); ?></b></div>
					</div>
					<p class="cert-verify"><?php esc_html_e( 'Verify at', 'vitalstack' ); ?> <?php echo esc_html( preg_replace( '#^https?://#', '', $url ) ); ?></p>
				</div>
			</div>

			<div class="cert-actions">
				<button type="button" class="btn btn-primary" onclick="window.print()"><?php esc_html_e( 'Download PDF / Print', 'vitalstack' ); ?></button>
				<?php if ( $mine ) : ?>
					<a class="btn btn-dark" href="<?php echo esc_url( vitalstack_cert_linkedin_url( $cert, $course ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Add to LinkedIn profile', 'vitalstack' ); ?></a>
				<?php endif; ?>
				<a class="btn btn-ghost" href="<?php echo esc_url( 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( $url ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Share on LinkedIn', 'vitalstack' ); ?></a>
				<a class="btn btn-ghost" href="<?php echo esc_url( 'https://wa.me/?text=' . $share ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Share on WhatsApp', 'vitalstack' ); ?></a>
			</div>
			<p class="cert-note muted">
				<?php
				if ( $mine ) {
					esc_html_e( 'Tip: in the print dialog choose "Save as PDF" and landscape. Anyone with this link can verify your certificate.', 'vitalstack' );
				} else {
					/* translators: %s: site name */
					echo esc_html( sprintf( __( 'This certificate was issued by %s and is valid.', 'vitalstack' ), get_bloginfo( 'name' ) ) );
				}
				?>
				<a href="<?php echo esc_url( $term ? get_term_link( $term ) : vitalstack_tutorials_url() ); ?>"><?php echo esc_html( sprintf( /* translators: %s: course */ __( 'Take the free %s course', 'vitalstack' ), $course ) ); ?> ❯</a>
			</p>
		</div>
	</section>
	<?php
	get_footer();
	exit;
}
add_action( 'template_redirect', 'vitalstack_cert_template', 5 );

/**
 * The small certificate preview drawn on cards (no image file needed).
 */
function vitalstack_cert_mini( $course ) {
	?>
	<div class="cert-mini" aria-hidden="true">
		<span class="cert-mini-kicker"><?php esc_html_e( 'Certificate', 'vitalstack' ); ?></span>
		<span class="cert-mini-course"><?php echo esc_html( $course ); ?></span>
		<span class="cert-mini-line"></span>
		<span class="cert-mini-seal">🎓</span>
	</div>
	<?php
}

/**
 * "Earn your certificate" card for a course: what unlocks it, with the
 * reader's own progress ticked off (lessons are ticked by main.js).
 *
 * @param WP_Term $path    Learning path.
 * @param string  $variant "course" (course page) or "lesson" (end of a lesson).
 */
function vitalstack_cert_card( $path, $variant = 'course' ) {
	if ( ! $path || ! function_exists( 'vitalstack_accounts_enabled' ) || ! vitalstack_accounts_enabled() ) {
		return;
	}
	$lessons = vitalstack_path_lessons( $path );
	if ( ! $lessons ) {
		return;
	}
	$uid   = get_current_user_id();
	$certs = $uid ? vitalstack_user_certs( $uid ) : array();
	$here  = is_singular() ? get_permalink() : get_term_link( $path );
	$here  = is_wp_error( $here ) ? home_url( '/' ) : $here;
	$total = count( $lessons );

	if ( isset( $certs[ $path->slug ] ) ) {
		?>
		<aside class="cert-card cert-card-<?php echo esc_attr( $variant ); ?> is-earned">
			<?php vitalstack_cert_mini( $path->name ); ?>
			<div class="cert-card-body">
				<p class="cert-card-kicker"><?php esc_html_e( 'Certificate earned', 'vitalstack' ); ?></p>
				<h2 class="cert-card-title"><?php echo esc_html( sprintf( /* translators: %s: course */ __( 'You completed %s 🎉', 'vitalstack' ), $path->name ) ); ?></h2>
				<a class="btn btn-primary btn-sm" href="<?php echo esc_url( vitalstack_cert_url( $certs[ $path->slug ]['id'] ) ); ?>"><?php esc_html_e( 'View my certificate', 'vitalstack' ); ?> ❯</a>
			</div>
		</aside>
		<?php
		return;
	}

	$steps  = vitalstack_cert_steps( $uid );
	$labels = array(
		'account'    => __( 'Create a free account', 'vitalstack' ),
		'verified'   => __( 'Confirm your email', 'vitalstack' ),
		'subscribed' => __( 'Subscribe to lesson emails', 'vitalstack' ),
	);
	?>
	<aside class="cert-card cert-card-<?php echo esc_attr( $variant ); ?>" data-cert-path="<?php echo esc_attr( $path->slug ); ?>" data-cert-total="<?php echo (int) $total; ?>">
		<?php vitalstack_cert_mini( $path->name ); ?>
		<div class="cert-card-body">
			<p class="cert-card-kicker">🔒 <?php esc_html_e( 'Free certificate', 'vitalstack' ); ?></p>
			<h2 class="cert-card-title"><?php echo esc_html( sprintf( /* translators: %s: course */ __( 'Earn your %s certificate', 'vitalstack' ), $path->name ) ); ?></h2>
			<ol class="cert-steps">
				<?php foreach ( $labels as $key => $label ) : ?>
					<li class="<?php echo $steps[ $key ] ? 'is-done' : ''; ?>"><?php echo esc_html( $label ); ?></li>
				<?php endforeach; ?>
				<li data-cert-step="lessons">
					<?php echo esc_html( sprintf( /* translators: %d: number of lessons */ _n( 'Finish the %d lesson', 'Finish all %d lessons', $total, 'vitalstack' ), $total ) ); ?>
					<span class="cert-card-count">0 / <?php echo (int) $total; ?></span>
				</li>
			</ol>
			<div class="path-progress" aria-hidden="true"><span class="path-progress-bar cert-card-bar"></span></div>
			<div class="cert-card-cta">
				<?php if ( ! $uid ) : ?>
					<a class="btn btn-primary btn-sm" href="<?php echo esc_url( vitalstack_account_url( array( 'tab' => 'register', 'subscribe' => 1, 'redirect_to' => $here ) ) ); ?>"><?php esc_html_e( 'Sign up free and unlock', 'vitalstack' ); ?></a>
					<a class="cert-card-alt" href="<?php echo esc_url( vitalstack_account_url( array( 'tab' => 'login', 'subscribe' => 1, 'redirect_to' => $here ) ) ); ?>"><?php esc_html_e( 'I have an account', 'vitalstack' ); ?></a>
				<?php elseif ( ! $steps['subscribed'] ) : ?>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="vs_subscribe">
						<input type="hidden" name="subscribe" value="1">
						<input type="hidden" name="redirect_to" value="<?php echo esc_url( $here ); ?>">
						<?php wp_nonce_field( 'vs_subscribe', '_vsnonce' ); ?>
						<button type="submit" class="btn btn-primary btn-sm"><?php esc_html_e( 'Subscribe and unlock', 'vitalstack' ); ?></button>
					</form>
				<?php elseif ( ! $steps['verified'] ) : ?>
					<a class="btn btn-primary btn-sm" href="<?php echo esc_url( vitalstack_account_url() . '#certs' ); ?>"><?php esc_html_e( 'Confirm my email', 'vitalstack' ); ?></a>
				<?php else : ?>
					<p class="cert-card-ready"><?php esc_html_e( 'Unlocked! Your certificate is issued the moment you finish the last lesson.', 'vitalstack' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</aside>
	<?php
}

/**
 * Homepage band that shows off the certificates.
 */
function vitalstack_home_certs() {
	if ( ! function_exists( 'vitalstack_accounts_enabled' ) || ! vitalstack_accounts_enabled() ) {
		return;
	}
	$paths = array_filter(
		vitalstack_learning_paths(),
		function ( $p ) {
			return (bool) vitalstack_path_lessons( $p );
		}
	);
	if ( ! $paths ) {
		return;
	}
	$first = reset( $paths );
	?>
	<section class="home-certs">
		<div class="wrap home-certs-inner">
			<div class="home-certs-text">
				<p class="eyebrow">🎓 <?php esc_html_e( 'Free certificates', 'vitalstack' ); ?></p>
				<h2><?php esc_html_e( 'Finish a course, earn a certificate', 'vitalstack' ); ?></h2>
				<p><?php esc_html_e( 'Every learning path ends with a free certificate of completion with your name, a public verify link, and one click to add it to LinkedIn.', 'vitalstack' ); ?></p>
				<ol class="home-certs-steps">
					<li><b>1</b> <?php esc_html_e( 'Sign up free', 'vitalstack' ); ?></li>
					<li><b>2</b> <?php esc_html_e( 'Subscribe to lesson emails', 'vitalstack' ); ?></li>
					<li><b>3</b> <?php esc_html_e( 'Finish every lesson', 'vitalstack' ); ?></li>
					<li><b>🎓</b> <?php esc_html_e( 'Certificate unlocked', 'vitalstack' ); ?></li>
				</ol>
				<p class="home-certs-cta">
					<?php if ( is_user_logged_in() ) : ?>
						<a class="btn btn-primary" href="<?php echo esc_url( vitalstack_account_url() . '#certs' ); ?>"><?php esc_html_e( 'See my certificates', 'vitalstack' ); ?> ❯</a>
					<?php else : ?>
						<a class="btn btn-primary" href="<?php echo esc_url( vitalstack_account_url( array( 'tab' => 'register', 'subscribe' => 1 ) ) ); ?>"><?php esc_html_e( 'Unlock certificates free', 'vitalstack' ); ?> ❯</a>
					<?php endif; ?>
				</p>
			</div>
			<div class="home-certs-art">
				<div class="cert-preview" aria-hidden="true">
					<span class="cert-preview-brand"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
					<span class="cert-preview-kicker"><?php esc_html_e( 'Certificate of Completion', 'vitalstack' ); ?></span>
					<span class="cert-preview-name"><?php esc_html_e( 'Your Name', 'vitalstack' ); ?></span>
					<span class="cert-preview-course"><?php echo esc_html( $first->name ); ?></span>
					<span class="cert-preview-seal">🎓</span>
				</div>
				<ul class="home-certs-paths">
					<?php foreach ( array_slice( $paths, 0, 10 ) as $path ) : ?>
						<li><a href="<?php echo esc_url( get_term_link( $path ) ); ?>"><?php echo esc_html( $path->name ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Dashboard panel: earned certificates and what's left for the others.
 */
function vitalstack_certs_panel( $user_id, $progress ) {
	$steps = vitalstack_cert_steps( $user_id );
	$open  = vitalstack_cert_unlocked( $user_id );
	if ( $open ) {
		vitalstack_certs_check( $user_id, $progress ); // catch up on anything finished before unlocking
	}
	$certs  = vitalstack_user_certs( $user_id );
	$labels = array(
		'account'    => __( 'Free account', 'vitalstack' ),
		'verified'   => __( 'Email confirmed', 'vitalstack' ),
		'subscribed' => __( 'Subscribed to lesson emails', 'vitalstack' ),
	);
	?>
	<section class="panel dash-certs" id="certs">
		<h2><?php esc_html_e( 'Your certificates', 'vitalstack' ); ?></h2>
		<?php if ( $open ) : ?>
			<p class="muted"><?php esc_html_e( 'Certificates are unlocked. Finish every lesson of a course to get a free certificate you can add to LinkedIn.', 'vitalstack' ); ?></p>
		<?php else : ?>
			<p class="muted"><?php esc_html_e( 'Certificates are locked. Complete these steps to unlock them, then finish every lesson of a course:', 'vitalstack' ); ?></p>
			<ol class="cert-steps">
				<?php foreach ( $labels as $key => $label ) : ?>
					<li class="<?php echo $steps[ $key ] ? 'is-done' : ''; ?>"><?php echo esc_html( $label ); ?></li>
				<?php endforeach; ?>
			</ol>
			<?php if ( ! $steps['subscribed'] ) : ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="dash-cert-unlock">
					<input type="hidden" name="action" value="vs_subscribe">
					<input type="hidden" name="subscribe" value="1">
					<input type="hidden" name="redirect_to" value="<?php echo esc_url( vitalstack_account_url() . '#certs' ); ?>">
					<?php wp_nonce_field( 'vs_subscribe', '_vsnonce' ); ?>
					<button type="submit" class="btn btn-primary btn-sm"><?php esc_html_e( 'Subscribe and unlock', 'vitalstack' ); ?></button>
				</form>
			<?php elseif ( ! $steps['verified'] ) : ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="dash-cert-unlock">
					<input type="hidden" name="action" value="vs_resend">
					<?php wp_nonce_field( 'vs_resend', '_vsnonce' ); ?>
					<p class="small"><?php esc_html_e( 'Click the link in the email we sent you to confirm your address.', 'vitalstack' ); ?></p>
					<button type="submit" class="btn btn-ghost btn-sm"><?php esc_html_e( 'Resend confirmation email', 'vitalstack' ); ?></button>
				</form>
			<?php endif; ?>
		<?php endif; ?>
		<ul class="cert-list">
			<?php
			foreach ( vitalstack_learning_paths() as $path ) :
				$lessons = vitalstack_path_lessons( $path );
				$done    = isset( $progress[ $path->slug ] ) ? count( array_intersect( array_map( 'intval', (array) $progress[ $path->slug ] ), $lessons ) ) : 0;
				$earned  = isset( $certs[ $path->slug ] );
				$ready   = ! $earned && $lessons && $done >= count( $lessons );
				?>
				<li class="<?php echo $earned ? 'is-earned' : ( $ready ? 'is-ready' : '' ); ?>">
					<span class="cert-list-icon" aria-hidden="true"><?php echo $earned ? '🎓' : ( $ready ? '🔓' : '🔒' ); ?></span>
					<span class="cert-list-name"><?php echo esc_html( $path->name ); ?></span>
					<?php if ( $earned ) : ?>
						<a class="btn btn-primary btn-sm" href="<?php echo esc_url( vitalstack_cert_url( $certs[ $path->slug ]['id'] ) ); ?>"><?php esc_html_e( 'View', 'vitalstack' ); ?></a>
					<?php elseif ( $ready ) : ?>
						<span class="cert-list-ready small"><?php esc_html_e( 'Ready: unlock above', 'vitalstack' ); ?></span>
					<?php else : ?>
						<span class="muted small"><?php echo esc_html( sprintf( /* translators: 1: done, 2: total */ __( '%1$d / %2$d', 'vitalstack' ), $done, count( $lessons ) ) ); ?></span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php
}
