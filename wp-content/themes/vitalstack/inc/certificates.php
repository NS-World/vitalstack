<?php
/**
 * Certificates: a signed-in reader who completes every lesson of a learning
 * path gets a certificate with a public verify link (/certificate/<id>/),
 * printable as PDF and addable to LinkedIn.
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
 * Issue certificates for every path the user has now fully completed.
 *
 * @param int   $user_id  User.
 * @param array $progress path-slug => lesson ids.
 * @return array Newly issued certificates (with url and course name).
 */
function vitalstack_certs_check( $user_id, $progress ) {
	$have = vitalstack_user_certs( $user_id );
	$new  = array();
	foreach ( vitalstack_learning_paths() as $path ) {
		if ( isset( $have[ $path->slug ] ) || empty( $progress[ $path->slug ] ) ) {
			continue;
		}
		$lessons = vitalstack_path_lessons( $path );
		$done    = array_map( 'intval', (array) $progress[ $path->slug ] );
		if ( ! $lessons || array_diff( $lessons, $done ) ) {
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
 * Dashboard panel: earned certificates and what's left for the others.
 */
function vitalstack_certs_panel( $user_id, $progress ) {
	$certs = vitalstack_user_certs( $user_id );
	?>
	<section class="panel dash-certs">
		<h2><?php esc_html_e( 'Your certificates', 'vitalstack' ); ?></h2>
		<p class="muted"><?php esc_html_e( 'Finish every lesson of a course to get a free certificate you can add to LinkedIn.', 'vitalstack' ); ?></p>
		<ul class="cert-list">
			<?php
			foreach ( vitalstack_learning_paths() as $path ) :
				$lessons = vitalstack_path_lessons( $path );
				$done    = isset( $progress[ $path->slug ] ) ? count( array_intersect( array_map( 'intval', (array) $progress[ $path->slug ] ), $lessons ) ) : 0;
				?>
				<li class="<?php echo isset( $certs[ $path->slug ] ) ? 'is-earned' : ''; ?>">
					<span class="cert-list-icon" aria-hidden="true"><?php echo isset( $certs[ $path->slug ] ) ? '🎓' : '🔒'; ?></span>
					<span class="cert-list-name"><?php echo esc_html( $path->name ); ?></span>
					<?php if ( isset( $certs[ $path->slug ] ) ) : ?>
						<a class="btn btn-primary btn-sm" href="<?php echo esc_url( vitalstack_cert_url( $certs[ $path->slug ]['id'] ) ); ?>"><?php esc_html_e( 'View', 'vitalstack' ); ?></a>
					<?php else : ?>
						<span class="muted small"><?php echo esc_html( sprintf( /* translators: 1: done, 2: total */ __( '%1$d / %2$d', 'vitalstack' ), $done, count( $lessons ) ) ); ?></span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<?php
}
