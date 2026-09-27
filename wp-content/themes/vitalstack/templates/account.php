<?php
/**
 * /account/: sign in, sign up, or the reader's dashboard.
 *
 * @package VitalStack
 */

// phpcs:disable WordPress.Security.NonceVerification -- display-only query args
$vs_tab       = isset( $_GET['tab'] ) && 'register' === $_GET['tab'] ? 'register' : 'login';
$vs_subscribe = ! empty( $_GET['subscribe'] );
$vs_redirect  = vitalstack_requested_redirect();
// phpcs:enable

get_header();
?>

<section class="account-section">
	<div class="wrap">
		<?php if ( ! is_user_logged_in() ) : ?>

			<div class="auth-layout">
				<div class="auth-pitch">
					<p class="eyebrow"><?php esc_html_e( 'Free account', 'vitalstack' ); ?></p>
					<h1><?php esc_html_e( 'Learn at your own pace, and never lose your place.', 'vitalstack' ); ?></h1>
					<ul class="auth-benefits">
						<li><?php echo vitalstack_icon( 'trophy', 20 ); // phpcs:ignore ?> <span><strong><?php esc_html_e( 'Track your progress', 'vitalstack' ); ?></strong> <?php esc_html_e( 'across every learning path, on any device.', 'vitalstack' ); ?></span></li>
						<li><?php echo vitalstack_icon( 'bell', 20 ); // phpcs:ignore ?> <span><strong><?php esc_html_e( 'Get new lessons by email', 'vitalstack' ); ?></strong> <?php esc_html_e( 'as soon as they are published.', 'vitalstack' ); ?></span></li>
						<li><?php echo vitalstack_icon( 'lock', 20 ); // phpcs:ignore ?> <span><strong><?php esc_html_e( 'Private and free.', 'vitalstack' ); ?></strong> <?php esc_html_e( 'We never sell or share your email.', 'vitalstack' ); ?></span></li>
					</ul>
				</div>

				<div class="auth-card">
					<?php vitalstack_flash(); ?>
					<div class="tabs" role="tablist">
						<button type="button" role="tab" class="tab" id="tab-login" aria-controls="panel-login" aria-selected="<?php echo 'login' === $vs_tab ? 'true' : 'false'; ?>" data-tab="login"><?php esc_html_e( 'Sign in', 'vitalstack' ); ?></button>
						<?php if ( vitalstack_accounts_enabled() ) : ?>
							<button type="button" role="tab" class="tab" id="tab-register" aria-controls="panel-register" aria-selected="<?php echo 'register' === $vs_tab ? 'true' : 'false'; ?>" data-tab="register"><?php esc_html_e( 'Create account', 'vitalstack' ); ?></button>
						<?php endif; ?>
					</div>

					<form id="panel-login" role="tabpanel" aria-labelledby="tab-login" class="auth-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" <?php echo 'login' === $vs_tab ? '' : 'hidden'; ?>>
						<input type="hidden" name="action" value="vs_login">
						<input type="hidden" name="redirect_to" value="<?php echo esc_url( $vs_redirect ); ?>">
						<?php if ( $vs_subscribe ) : ?>
							<input type="hidden" name="subscribe" value="1">
						<?php endif; ?>
						<?php wp_nonce_field( 'vs_login', '_vsnonce' ); ?>
						<label class="field">
							<span><?php esc_html_e( 'Email or username', 'vitalstack' ); ?></span>
							<input type="text" name="log" autocomplete="username" required>
						</label>
						<label class="field">
							<span><?php esc_html_e( 'Password', 'vitalstack' ); ?> <a class="field-link" href="<?php echo esc_url( wp_lostpassword_url( vitalstack_account_url() ) ); ?>"><?php esc_html_e( 'Forgot?', 'vitalstack' ); ?></a></span>
							<input type="password" name="pwd" autocomplete="current-password" required>
						</label>
						<label class="check"><input type="checkbox" name="rememberme" value="1" checked> <?php esc_html_e( 'Keep me signed in', 'vitalstack' ); ?></label>
						<button type="submit" class="btn btn-primary btn-block"><?php echo $vs_subscribe ? esc_html__( 'Sign in & subscribe', 'vitalstack' ) : esc_html__( 'Sign in', 'vitalstack' ); ?></button>
					</form>

					<?php if ( vitalstack_accounts_enabled() ) : ?>
						<form id="panel-register" role="tabpanel" aria-labelledby="tab-register" class="auth-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" <?php echo 'register' === $vs_tab ? '' : 'hidden'; ?>>
							<input type="hidden" name="action" value="vs_register">
							<input type="hidden" name="redirect_to" value="<?php echo esc_url( $vs_redirect ); ?>">
							<?php wp_nonce_field( 'vs_register', '_vsnonce' ); ?>
							<div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
							<label class="field">
								<span><?php esc_html_e( 'Your name', 'vitalstack' ); ?></span>
								<input type="text" name="display_name" autocomplete="name" maxlength="60" required>
							</label>
							<label class="field">
								<span><?php esc_html_e( 'Email', 'vitalstack' ); ?></span>
								<input type="email" name="email" autocomplete="email" required>
							</label>
							<label class="field">
								<span><?php esc_html_e( 'Password', 'vitalstack' ); ?> <small class="muted"><?php esc_html_e( '(8+ characters)', 'vitalstack' ); ?></small></span>
								<input type="password" name="pwd" autocomplete="new-password" minlength="8" required>
							</label>
							<label class="check"><input type="checkbox" name="subscribe" value="1" <?php checked( $vs_subscribe ); ?>> <?php esc_html_e( 'Email me when new lessons and guides are published', 'vitalstack' ); ?></label>
							<button type="submit" class="btn btn-primary btn-block"><?php esc_html_e( 'Create free account', 'vitalstack' ); ?></button>
							<p class="auth-legal">
								<?php
								printf(
									/* translators: 1: terms link, 2: privacy link */
									esc_html__( 'By creating an account you agree to our %1$s and %2$s.', 'vitalstack' ),
									'<a href="' . esc_url( vitalstack_page_url( 'terms-of-service' ) ) . '">' . esc_html__( 'Terms', 'vitalstack' ) . '</a>',
									'<a href="' . esc_url( vitalstack_page_url( 'privacy-policy' ) ) . '">' . esc_html__( 'Privacy Policy', 'vitalstack' ) . '</a>'
								);
								?>
							</p>
						</form>
					<?php endif; ?>
				</div>
			</div>

		<?php else : ?>
			<?php
			$vs_user     = wp_get_current_user();
			$vs_verified = vitalstack_is_verified( $vs_user->ID ) || $vs_user->has_cap( 'edit_posts' );
			$vs_sub      = vitalstack_is_subscribed( $vs_user->ID );
			$vs_progress = vitalstack_get_user_progress( $vs_user->ID );
			?>
			<div class="dash">
				<header class="dash-head">
					<?php echo vitalstack_monogram( $vs_user->ID, 'lg' ); // phpcs:ignore ?>
					<div>
						<h1><?php echo esc_html( sprintf( /* translators: %s: name */ __( 'Hi, %s', 'vitalstack' ), $vs_user->display_name ) ); ?></h1>
						<p class="muted"><?php echo esc_html( $vs_user->user_email ); ?></p>
					</div>
					<a class="btn btn-ghost btn-sm dash-signout" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php esc_html_e( 'Sign out', 'vitalstack' ); ?></a>
				</header>

				<?php vitalstack_flash(); ?>

				<div class="dash-grid">
					<section class="panel dash-progress">
						<h2><?php esc_html_e( 'Your learning progress', 'vitalstack' ); ?></h2>
						<?php
						$vs_paths = vitalstack_learning_paths();
						foreach ( $vs_paths as $vs_path ) :
							$vs_lessons = vitalstack_path_lessons( $vs_path );
							$vs_done    = isset( $vs_progress[ $vs_path->slug ] ) ? array_intersect( array_map( 'intval', $vs_progress[ $vs_path->slug ] ), $vs_lessons ) : array();
							$vs_next    = 0;
							foreach ( $vs_lessons as $vs_l ) {
								if ( ! in_array( $vs_l, $vs_done, true ) ) {
									$vs_next = $vs_l;
									break;
								}
							}
							$vs_pct = $vs_lessons ? round( count( $vs_done ) / count( $vs_lessons ) * 100 ) : 0;
							?>
							<div class="dash-path" data-path="<?php echo esc_attr( $vs_path->slug ); ?>" data-total="<?php echo count( $vs_lessons ); ?>">
								<div class="dash-path-top">
									<a href="<?php echo esc_url( get_term_link( $vs_path ) ); ?>" class="dash-path-name"><?php echo esc_html( $vs_path->name ); ?></a>
									<span class="muted dash-path-count"><?php echo esc_html( sprintf( /* translators: 1: done, 2: total */ __( '%1$d of %2$d lessons', 'vitalstack' ), count( $vs_done ), count( $vs_lessons ) ) ); ?></span>
								</div>
								<div class="path-progress" aria-hidden="true"><span class="path-progress-bar" style="width:<?php echo (int) $vs_pct; ?>%"></span></div>
								<?php if ( $vs_next ) : ?>
									<a class="dash-next" href="<?php echo esc_url( get_permalink( $vs_next ) ); ?>"><?php echo count( $vs_done ) ? esc_html__( 'Continue:', 'vitalstack' ) : esc_html__( 'Start:', 'vitalstack' ); ?> <?php echo esc_html( vitalstack_short_title( $vs_next ) ); ?> →</a>
								<?php else : ?>
									<span class="dash-next is-done"><?php esc_html_e( 'Completed 🎉', 'vitalstack' ); ?></span>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</section>

					<div class="dash-side">
						<section class="panel">
							<h2><?php esc_html_e( 'Email updates', 'vitalstack' ); ?></h2>
							<?php if ( ! $vs_verified ) : ?>
								<p class="notice notice-info"><?php esc_html_e( 'Please confirm your email address. We sent you a link when you signed up.', 'vitalstack' ); ?></p>
								<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
									<input type="hidden" name="action" value="vs_resend">
									<?php wp_nonce_field( 'vs_resend', '_vsnonce' ); ?>
									<button type="submit" class="btn btn-ghost btn-sm"><?php esc_html_e( 'Resend confirmation email', 'vitalstack' ); ?></button>
								</form>
							<?php endif; ?>
							<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="dash-sub">
								<input type="hidden" name="action" value="vs_subscribe">
								<?php wp_nonce_field( 'vs_subscribe', '_vsnonce' ); ?>
								<p><?php echo $vs_sub ? esc_html__( 'You get an email when a new lesson or guide is published.', 'vitalstack' ) : esc_html__( 'You are not subscribed to new-lesson emails.', 'vitalstack' ); ?></p>
								<?php if ( $vs_sub ) : ?>
									<button type="submit" class="btn btn-ghost btn-sm"><?php esc_html_e( 'Unsubscribe', 'vitalstack' ); ?></button>
								<?php else : ?>
									<input type="hidden" name="subscribe" value="1">
									<button type="submit" class="btn btn-primary btn-sm"><?php esc_html_e( 'Subscribe', 'vitalstack' ); ?></button>
								<?php endif; ?>
							</form>
						</section>

						<section class="panel">
							<h2><?php esc_html_e( 'Profile', 'vitalstack' ); ?></h2>
							<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="auth-form">
								<input type="hidden" name="action" value="vs_profile">
								<?php wp_nonce_field( 'vs_profile', '_vsnonce' ); ?>
								<label class="field">
									<span><?php esc_html_e( 'Display name', 'vitalstack' ); ?></span>
									<input type="text" name="display_name" value="<?php echo esc_attr( $vs_user->display_name ); ?>" maxlength="60" required>
								</label>
								<button type="submit" class="btn btn-ghost btn-sm"><?php esc_html_e( 'Save', 'vitalstack' ); ?></button>
								<p class="muted small"><a href="<?php echo esc_url( wp_lostpassword_url( vitalstack_account_url() ) ); ?>"><?php esc_html_e( 'Change password', 'vitalstack' ); ?></a></p>
							</form>
						</section>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
