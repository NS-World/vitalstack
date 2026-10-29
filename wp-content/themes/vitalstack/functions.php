<?php
/**
 * VitalStack theme bootstrap.
 *
 * Each concern lives in its own file under inc/ so the theme stays easy to
 * navigate as it grows.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VITALSTACK_VERSION', '4.3.1' );
define( 'VITALSTACK_DIR', get_template_directory() );
define( 'VITALSTACK_URI', get_template_directory_uri() );

require VITALSTACK_DIR . '/inc/security.php';
require VITALSTACK_DIR . '/inc/setup.php';
require VITALSTACK_DIR . '/inc/post-types.php';
require VITALSTACK_DIR . '/inc/customizer.php';
require VITALSTACK_DIR . '/inc/appearance.php';
require VITALSTACK_DIR . '/inc/brand.php';
require VITALSTACK_DIR . '/inc/performance.php';
require VITALSTACK_DIR . '/inc/template-tags.php';
require VITALSTACK_DIR . '/inc/content.php';
require VITALSTACK_DIR . '/inc/learning-paths.php';
require VITALSTACK_DIR . '/inc/seo.php';
require VITALSTACK_DIR . '/inc/notifications.php';
require VITALSTACK_DIR . '/inc/accounts.php';
require VITALSTACK_DIR . '/inc/gift.php';
require VITALSTACK_DIR . '/inc/progress-api.php';
require VITALSTACK_DIR . '/inc/xp.php';
require VITALSTACK_DIR . '/inc/quiz.php';
require VITALSTACK_DIR . '/inc/schema.php';
require VITALSTACK_DIR . '/inc/certificates.php';
require VITALSTACK_DIR . '/inc/challenge.php';
require VITALSTACK_DIR . '/inc/site-pages.php';
require VITALSTACK_DIR . '/inc/mock-tests.php';
require VITALSTACK_DIR . '/inc/pwa.php';
require VITALSTACK_DIR . '/inc/digest.php';
require VITALSTACK_DIR . '/inc/mascot.php';
require VITALSTACK_DIR . '/inc/fun.php';
require VITALSTACK_DIR . '/inc/ui.php';
require VITALSTACK_DIR . '/inc/contact.php';
require VITALSTACK_DIR . '/inc/ads.php';
require VITALSTACK_DIR . '/inc/setup-wizard.php';
require VITALSTACK_DIR . '/inc/starter-content.php';
