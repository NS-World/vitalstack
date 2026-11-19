<?php
/**
 * Plugin Name: VitalStack AI Hub
 * Description: A separate, 3D-styled AI section at /ai/: AI Library, AI Commands & Shortcuts, Prompt Bazaar and Motion Studio (animated sections with code). Free and premium items; premium unlocks with an AI Pro plan (monthly, yearly or lifetime).
 * Version: 1.2.0
 * Requires at least: 6.2
 * Requires PHP: 7.4
 * Author: VitalStack
 * License: GPL-2.0-or-later
 * Text Domain: vitalstack-ai-hub
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

define( 'VSAI_VERSION', '1.2.0' );
define( 'VSAI_DIR', plugin_dir_path( __FILE__ ) );
define( 'VSAI_URL', plugin_dir_url( __FILE__ ) );

require VSAI_DIR . 'includes/catalog.php';
require VSAI_DIR . 'includes/plans.php';
require VSAI_DIR . 'includes/access.php';
require VSAI_DIR . 'includes/copy.php';
require VSAI_DIR . 'includes/routes.php';
require VSAI_DIR . 'includes/render.php';
require VSAI_DIR . 'includes/admin.php';

register_activation_hook(
	__FILE__,
	function () {
		vsai_register_routes();
		vsai_register_cpt();
		flush_rewrite_rules();
	}
);
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
