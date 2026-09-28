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

define( 'VITALSTACK_VERSION', '3.0.0' );
define( 'VITALSTACK_DIR', get_template_directory() );
define( 'VITALSTACK_URI', get_template_directory_uri() );

require VITALSTACK_DIR . '/inc/setup.php';
require VITALSTACK_DIR . '/inc/post-types.php';
require VITALSTACK_DIR . '/inc/customizer.php';
require VITALSTACK_DIR . '/inc/template-tags.php';
require VITALSTACK_DIR . '/inc/content.php';
require VITALSTACK_DIR . '/inc/learning-paths.php';
require VITALSTACK_DIR . '/inc/seo.php';
require VITALSTACK_DIR . '/inc/notifications.php';
require VITALSTACK_DIR . '/inc/accounts.php';
require VITALSTACK_DIR . '/inc/progress-api.php';
require VITALSTACK_DIR . '/inc/ui.php';
require VITALSTACK_DIR . '/inc/contact.php';
