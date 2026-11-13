<?php
/**
 * Plugin Name: VitalStack Growth Desk
 * Description: Selling, marketing and testing desk for VitalStack. Landing offers like a big sale (regular price, savings, a real countdown), a Sales agent, a Marketing agent with a share kit, and a Testing &amp; Security agent that checks the site every day and emails you only when something needs you. Admin-only, no outside services.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: VitalStack
 * License: GPL-2.0-or-later
 * Text Domain: vitalstack-growth
 *
 * @package VitalStackGrowth
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VSG_VERSION', '1.0.0' );
define( 'VSG_DIR', __DIR__ );
define( 'VSG_URL', plugin_dir_url( __FILE__ ) );

require VSG_DIR . '/includes/settings.php';
require VSG_DIR . '/includes/offer.php';
require VSG_DIR . '/includes/agents.php';
if ( is_admin() ) {
	require VSG_DIR . '/includes/admin.php';
}

register_activation_hook( __FILE__, 'vsg_schedule' );
register_deactivation_hook( __FILE__, 'vsg_unschedule' );
