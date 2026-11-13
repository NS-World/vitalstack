<?php
/**
 * Plugin Name: VitalStack Festive
 * Description: Dresses the VitalStack site up for festivals: Diwali, Holi, Christmas, New Year, Independence Day and more. Festival colours, a decorated header garland, a greeting on the home page, small festive touches on the characters, and a festival name on the offer strip. Turns itself on and off by date. Light, responsive, respects "reduce motion".
 * Version: 1.0.0
 * Author: VitalStack
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * Text Domain: vitalstack-festive
 *
 * @package VitalStackFestive
 */

defined( 'ABSPATH' ) || exit;

define( 'VSF_VERSION', '1.0.0' );

/**
 * The festivals. Dates are "MM-DD" for fixed festivals, or full
 * "YYYY-MM-DD" windows for festivals that move every year (check these each
 * year in Settings → VitalStack Festive; moon-sighted dates can shift a day).
 */
function vsf_festivals() {
	return array(
		'newyear'     => array( 'name' => __( 'New Year', 'vitalstack-festive' ), 'greet' => __( 'Happy New Year! New year, new skills 🎉', 'vitalstack-festive' ), 'sale' => __( 'New Year Sale', 'vitalstack-festive' ), 'icon' => '🎉', 'acc' => '🎉', 'c1' => '#ffd166', 'c2' => '#7c4dff', 'c3' => '#1b1147', 'bits' => array( '🎉', '✨', '🎆', '🥳' ), 'dates' => array( '12-30', '01-02' ) ),
		'sankranti'   => array( 'name' => __( 'Makar Sankranti', 'vitalstack-festive' ), 'greet' => __( 'Happy Makar Sankranti, Pongal and Lohri! Let your skills fly high 🪁', 'vitalstack-festive' ), 'sale' => __( 'Sankranti Sale', 'vitalstack-festive' ), 'icon' => '🪁', 'acc' => '🪁', 'c1' => '#ffb703', 'c2' => '#219ebc', 'c3' => '#023047', 'bits' => array( '🪁', '☀️', '🪁' ), 'dates' => array( '01-13', '01-15' ) ),
		'republic'    => array( 'name' => __( 'Republic Day', 'vitalstack-festive' ), 'greet' => __( 'Happy Republic Day! Proud to build India’s tech future 🇮🇳', 'vitalstack-festive' ), 'sale' => __( 'Republic Day Sale', 'vitalstack-festive' ), 'icon' => '🇮🇳', 'acc' => '🇮🇳', 'c1' => '#ff9933', 'c2' => '#138808', 'c3' => '#000080', 'bits' => array( '🇮🇳', '🧡', '🤍', '💚' ), 'dates' => array( '01-25', '01-26' ) ),
		'holi'        => array( 'name' => __( 'Holi', 'vitalstack-festive' ), 'greet' => __( 'Happy Holi! Add some colour to your learning 🎨', 'vitalstack-festive' ), 'sale' => __( 'Holi Sale', 'vitalstack-festive' ), 'icon' => '🎨', 'acc' => '🎨', 'c1' => '#ff4fa3', 'c2' => '#00c2ff', 'c3' => '#7a2bff', 'bits' => array( '🟣', '🟡', '🟢', '🔵', '🌸' ), 'dates' => array( '2027-03-21', '2027-03-23' ) ),
		'eid'         => array( 'name' => __( 'Eid', 'vitalstack-festive' ), 'greet' => __( 'Eid Mubarak! Wishing you joy, peace and success 🌙', 'vitalstack-festive' ), 'sale' => __( 'Eid Sale', 'vitalstack-festive' ), 'icon' => '🌙', 'acc' => '🌙', 'c1' => '#2a9d8f', 'c2' => '#e9c46a', 'c3' => '#0b3d3a', 'bits' => array( '🌙', '⭐', '🏮' ), 'dates' => array( '2027-03-09', '2027-03-11' ) ),
		'independence' => array( 'name' => __( 'Independence Day', 'vitalstack-festive' ), 'greet' => __( 'Happy Independence Day! Freedom to learn anything 🇮🇳', 'vitalstack-festive' ), 'sale' => __( 'Independence Day Sale', 'vitalstack-festive' ), 'icon' => '🇮🇳', 'acc' => '🇮🇳', 'c1' => '#ff9933', 'c2' => '#138808', 'c3' => '#000080', 'bits' => array( '🇮🇳', '🧡', '🤍', '💚' ), 'dates' => array( '08-14', '08-15' ) ),
		'rakhi'       => array( 'name' => __( 'Raksha Bandhan', 'vitalstack-festive' ), 'greet' => __( 'Happy Raksha Bandhan! Gift your sibling a new skill 🎁', 'vitalstack-festive' ), 'sale' => __( 'Rakhi Sale', 'vitalstack-festive' ), 'icon' => '🎁', 'acc' => '🎁', 'c1' => '#f72585', 'c2' => '#ffbe0b', 'c3' => '#4a0d2b', 'bits' => array( '🎁', '💛', '✨' ), 'dates' => array( '2027-08-16', '2027-08-17' ) ),
		'ganesh'      => array( 'name' => __( 'Ganesh Chaturthi', 'vitalstack-festive' ), 'greet' => __( 'Ganpati Bappa Morya! May every bug in your code disappear 🌺', 'vitalstack-festive' ), 'sale' => __( 'Ganesh Chaturthi Sale', 'vitalstack-festive' ), 'icon' => '🌺', 'acc' => '🌺', 'c1' => '#ff7b00', 'c2' => '#e5383b', 'c3' => '#5a1300', 'bits' => array( '🌺', '🌼', '✨' ), 'dates' => array( '2027-09-04', '2027-09-05' ) ),
		'programmers' => array( 'name' => __( 'Programmers’ Day', 'vitalstack-festive' ), 'greet' => __( 'Happy Programmers’ Day! Day 256 of the year, made for coders 👩‍💻', 'vitalstack-festive' ), 'sale' => __( 'Programmers’ Day Sale', 'vitalstack-festive' ), 'icon' => '👩‍💻', 'acc' => '💻', 'c1' => '#34c987', 'c2' => '#1d5fd1', 'c3' => '#08141f', 'bits' => array( '💻', '{ }', '🐞', '⚡' ), 'dates' => array( '09-13', '09-13' ) ),
		'navratri'    => array( 'name' => __( 'Navratri and Dussehra', 'vitalstack-festive' ), 'greet' => __( 'Happy Navratri and Dussehra! Let good code win over bugs 🏹', 'vitalstack-festive' ), 'sale' => __( 'Navratri Sale', 'vitalstack-festive' ), 'icon' => '🪔', 'acc' => '🏹', 'c1' => '#ff006e', 'c2' => '#fb5607', 'c3' => '#3a0ca3', 'bits' => array( '🌸', '✨', '🪔' ), 'dates' => array( '2027-09-30', '2027-10-09' ) ),
		'diwali'      => array( 'name' => __( 'Diwali', 'vitalstack-festive' ), 'greet' => __( 'Happy Diwali! May your knowledge shine as bright as diyas 🪔', 'vitalstack-festive' ), 'sale' => __( 'Diwali Sale', 'vitalstack-festive' ), 'icon' => '🪔', 'acc' => '🪔', 'c1' => '#ffb000', 'c2' => '#ff5400', 'c3' => '#3b0a00', 'bits' => array( '🪔', '✨', '🎆', '🌼' ), 'dates' => array( '2027-10-26', '2027-11-01' ) ),
		'christmas'   => array( 'name' => __( 'Christmas', 'vitalstack-festive' ), 'greet' => __( 'Merry Christmas! Unwrap a new skill this season 🎄', 'vitalstack-festive' ), 'sale' => __( 'Christmas Sale', 'vitalstack-festive' ), 'icon' => '🎄', 'acc' => '🎄', 'c1' => '#e63946', 'c2' => '#2a9d8f', 'c3' => '#0b2b26', 'bits' => array( '❄️', '🎄', '⭐', '🎁' ), 'dates' => array( '12-20', '12-26' ) ),
	);
}

/* ── Settings ─────────────────────────────────────────────────────────── */

function vsf_settings() {
	return wp_parse_args(
		(array) get_option( 'vsf_settings', array() ),
		array(
			'on'     => 1,
			'mode'   => 'auto',  // auto | force | off
			'force'  => 'diwali',
			'dates'  => array(),  // key => [start, end] overrides
			'greet'  => array(),  // key => custom greeting
			'burst'  => 1,        // a short festive burst on the home page
			'bar'    => 1,        // festival name on the offer strip
			'link'   => '',       // where the greeting button goes (default: pricing)
		)
	);
}

/** Whether a date window (MM-DD or YYYY-MM-DD) covers today (site time). */
function vsf_in_window( $start, $end, $today = null ) {
	$today = $today ? $today : wp_date( 'Y-m-d' );
	if ( preg_match( '/^\d{4}-\d{2}-\d{2}$/', $start ) && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $end ) ) {
		return $today >= $start && $today <= $end;
	}
	if ( preg_match( '/^\d{2}-\d{2}$/', $start ) && preg_match( '/^\d{2}-\d{2}$/', $end ) ) {
		$md = substr( $today, 5 );
		return $start <= $end ? ( $md >= $start && $md <= $end ) : ( $md >= $start || $md <= $end ); // wraps the new year
	}
	return false;
}

/** The festival key showing now, or ''. */
function vsf_current() {
	static $cur = null;
	if ( null !== $cur ) {
		return $cur;
	}
	$s    = vsf_settings();
	$list = vsf_festivals();
	$cur  = '';
	// Admins can preview any festival with ?vs_festive=<key> (only they see it).
	if ( isset( $_GET['vs_festive'] ) && current_user_can( 'manage_options' ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- read-only preview for admins
		$k = sanitize_key( wp_unslash( $_GET['vs_festive'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $list[ $k ] ) ) {
			return $cur = $k;
		}
	}
	if ( ! $s['on'] || 'off' === $s['mode'] ) {
		return $cur;
	}
	if ( 'force' === $s['mode'] ) {
		return $cur = isset( $list[ $s['force'] ] ) ? $s['force'] : '';
	}
	foreach ( $list as $k => $f ) {
		$d = isset( $s['dates'][ $k ] ) && 2 === count( (array) $s['dates'][ $k ] ) ? $s['dates'][ $k ] : $f['dates'];
		if ( vsf_in_window( $d[0], $d[1] ) ) {
			return $cur = $k;
		}
	}
	return $cur;
}

function vsf_fest() {
	$k = vsf_current();
	if ( ! $k ) {
		return null;
	}
	$f        = vsf_festivals()[ $k ];
	$f['key'] = $k;
	$custom   = vsf_settings()['greet'][ $k ] ?? '';
	if ( '' !== trim( (string) $custom ) ) {
		$f['greet'] = $custom;
	}
	return $f;
}

/* ── Front end ────────────────────────────────────────────────────────── */

add_filter(
	'body_class',
	function ( $c ) {
		$f = is_admin() ? null : vsf_fest();
		if ( $f ) {
			$c[] = 'vs-fest';
			$c[] = 'vs-fest-' . $f['key'];
		}
		return $c;
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		$f = vsf_fest();
		if ( ! $f ) {
			return;
		}
		$url = plugin_dir_url( __FILE__ );
		wp_enqueue_style( 'vitalstack-festive', $url . 'assets/festive.css', array(), VSF_VERSION );
		wp_add_inline_style( 'vitalstack-festive', ':root{--fest-1:' . sanitize_hex_color( $f['c1'] ) . ';--fest-2:' . sanitize_hex_color( $f['c2'] ) . ';--fest-3:' . sanitize_hex_color( $f['c3'] ) . '}' );
		wp_enqueue_script( 'vitalstack-festive', $url . 'assets/festive.js', array(), VSF_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
		wp_localize_script(
			'vitalstack-festive',
			'vsFestive',
			array(
				'key'   => $f['key'],
				'acc'   => $f['acc'],
				'bits'  => array_values( $f['bits'] ),
				'burst' => vsf_settings()['burst'] && is_front_page() ? 1 : 0,
			)
		);
	}
);

/** Greeting band on the home page, under the offer strip, above the header. */
add_action(
	'wp_body_open',
	function () {
		$f = vsf_fest();
		if ( ! $f || ! is_front_page() ) {
			return;
		}
		$s    = vsf_settings();
		$link = $s['link'] ? $s['link'] : ( function_exists( 'vitalstack_pricing_url' ) ? vitalstack_pricing_url() : home_url( '/' ) );
		$link = add_query_arg( array( 'utm_source' => 'site', 'utm_medium' => 'festive', 'utm_campaign' => $f['key'] ), $link );
		$prem = is_user_logged_in() && function_exists( 'vitalstack_reader_has_any_premium' ) && vitalstack_reader_has_any_premium( get_current_user_id() );
		?>
		<div class="vsf-band" data-vsf-band role="region" aria-label="<?php echo esc_attr( $f['name'] ); ?>">
			<span class="vsf-band-icon" aria-hidden="true"><?php echo esc_html( $f['icon'] ); ?></span>
			<p class="vsf-band-text"><?php echo esc_html( $f['greet'] ); ?></p>
			<?php if ( ! $prem ) : ?>
				<a class="vsf-band-cta" href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $f['sale'] ); ?> →</a>
			<?php endif; ?>
			<button type="button" class="vsf-band-x" data-vsf-close aria-label="<?php esc_attr_e( 'Close greeting', 'vitalstack-festive' ); ?>">✕</button>
		</div>
		<?php
	},
	8
);

/** The festival name leads the theme's offer strip. */
add_filter(
	'vitalstack_offer_bar_items',
	function ( $items ) {
		$f = vsf_fest();
		if ( $f && vsf_settings()['bar'] ) {
			array_unshift( $items, '<span class="obar-hot vsf-obar-hot">' . esc_html( $f['icon'] . ' ' . $f['sale'] ) . '</span>' );
			// The plain "Launch sale" chip would repeat the idea.
			$items = array_values( array_filter( $items, function ( $i ) { return false === strpos( $i, 'class="obar-hot"' ); } ) );
		}
		return $items;
	}
);
add_filter(
	'vitalstack_offer_bar_class',
	function ( $c ) {
		$f = vsf_fest();
		return $f && vsf_settings()['bar'] ? $c . ' vsf-obar' : $c;
	}
);

/** Marketing plugin: offer the festival name for campaign subjects. */
function vitalstack_festive_current() {
	return vsf_fest();
}

/* ── Admin ────────────────────────────────────────────────────────────── */

add_action(
	'admin_menu',
	function () {
		add_options_page( __( 'VitalStack Festive', 'vitalstack-festive' ), __( 'VitalStack Festive', 'vitalstack-festive' ), 'manage_options', 'vitalstack-festive', 'vsf_admin_page' );
	}
);

add_filter(
	'plugin_action_links_' . plugin_basename( __FILE__ ),
	function ( $links ) {
		array_unshift( $links, '<a href="' . esc_url( admin_url( 'options-general.php?page=vitalstack-festive' ) ) . '">' . esc_html__( 'Settings', 'vitalstack-festive' ) . '</a>' );
		return $links;
	}
);

function vsf_valid_date( $v ) {
	$v = trim( (string) $v );
	return preg_match( '/^(\d{4}-)?\d{2}-\d{2}$/', $v ) ? $v : '';
}

function vsf_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$list = vsf_festivals();
	$msg  = '';
	if ( ! empty( $_POST['vsf_save'] ) ) {
		check_admin_referer( 'vsf_admin' );
		$in  = wp_unslash( $_POST );
		$new = array(
			'on'    => empty( $in['on'] ) ? 0 : 1,
			'mode'  => in_array( $in['mode'] ?? '', array( 'auto', 'force', 'off' ), true ) ? $in['mode'] : 'auto',
			'force' => isset( $list[ sanitize_key( $in['force'] ?? '' ) ] ) ? sanitize_key( $in['force'] ) : 'diwali',
			'burst' => empty( $in['burst'] ) ? 0 : 1,
			'bar'   => empty( $in['bar'] ) ? 0 : 1,
			'link'  => esc_url_raw( $in['link'] ?? '' ),
			'dates' => array(),
			'greet' => array(),
		);
		foreach ( $list as $k => $f ) {
			$a = vsf_valid_date( $in['start'][ $k ] ?? '' );
			$b = vsf_valid_date( $in['end'][ $k ] ?? '' );
			if ( $a && $b && ( $a !== $f['dates'][0] || $b !== $f['dates'][1] ) ) {
				$new['dates'][ $k ] = array( $a, $b );
			}
			$g = sanitize_text_field( $in['greet'][ $k ] ?? '' );
			if ( '' !== $g ) {
				$new['greet'][ $k ] = $g;
			}
		}
		update_option( 'vsf_settings', $new, false );
		$msg = __( 'Saved.', 'vitalstack-festive' );
	}
	$s   = vsf_settings();
	$now = vsf_current();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'VitalStack Festive', 'vitalstack-festive' ); ?></h1>
		<?php if ( $msg ) : ?><div class="notice notice-success is-dismissible"><p><?php echo esc_html( $msg ); ?></p></div><?php endif; ?>
		<p><?php echo $now ? esc_html( sprintf( /* translators: %s: festival */ __( 'Showing now: %s', 'vitalstack-festive' ), $list[ $now ]['icon'] . ' ' . $list[ $now ]['name'] ) ) : esc_html__( 'No festival is showing today.', 'vitalstack-festive' ); ?></p>
		<form method="post">
			<?php wp_nonce_field( 'vsf_admin' ); ?>
			<table class="form-table" role="presentation">
				<tr><th><?php esc_html_e( 'Festive look', 'vitalstack-festive' ); ?></th><td><label><input type="checkbox" name="on" value="1" <?php checked( $s['on'] ); ?>> <?php esc_html_e( 'On', 'vitalstack-festive' ); ?></label></td></tr>
				<tr><th><?php esc_html_e( 'When', 'vitalstack-festive' ); ?></th><td>
					<label><input type="radio" name="mode" value="auto" <?php checked( 'auto', $s['mode'] ); ?>> <?php esc_html_e( 'Automatically, by the dates below', 'vitalstack-festive' ); ?></label><br>
					<label><input type="radio" name="mode" value="force" <?php checked( 'force', $s['mode'] ); ?>> <?php esc_html_e( 'Always show:', 'vitalstack-festive' ); ?></label>
					<select name="force"><?php foreach ( $list as $k => $f ) : ?><option value="<?php echo esc_attr( $k ); ?>" <?php selected( $k, $s['force'] ); ?>><?php echo esc_html( $f['icon'] . ' ' . $f['name'] ); ?></option><?php endforeach; ?></select><br>
					<label><input type="radio" name="mode" value="off" <?php checked( 'off', $s['mode'] ); ?>> <?php esc_html_e( 'Never', 'vitalstack-festive' ); ?></label>
				</td></tr>
				<tr><th><?php esc_html_e( 'Extras', 'vitalstack-festive' ); ?></th><td>
					<label><input type="checkbox" name="bar" value="1" <?php checked( $s['bar'] ); ?>> <?php esc_html_e( 'Put the festival sale name on the offer strip', 'vitalstack-festive' ); ?></label><br>
					<label><input type="checkbox" name="burst" value="1" <?php checked( $s['burst'] ); ?>> <?php esc_html_e( 'A short festive burst when the home page opens (about 5 seconds)', 'vitalstack-festive' ); ?></label>
				</td></tr>
				<tr><th><label for="vsf-link"><?php esc_html_e( 'Greeting button goes to', 'vitalstack-festive' ); ?></label></th><td><input id="vsf-link" type="url" name="link" class="regular-text" value="<?php echo esc_attr( $s['link'] ); ?>" placeholder="<?php echo esc_attr( function_exists( 'vitalstack_pricing_url' ) ? vitalstack_pricing_url() : home_url( '/' ) ); ?>"></td></tr>
			</table>
			<h2><?php esc_html_e( 'Festivals and dates', 'vitalstack-festive' ); ?></h2>
			<p class="description"><?php esc_html_e( 'Fixed festivals use MM-DD (every year). Festivals that move each year use YYYY-MM-DD: check them every year (moon-sighted dates can shift by a day).', 'vitalstack-festive' ); ?></p>
			<table class="widefat striped" style="max-width:980px"><thead><tr><th><?php esc_html_e( 'Festival', 'vitalstack-festive' ); ?></th><th><?php esc_html_e( 'From', 'vitalstack-festive' ); ?></th><th><?php esc_html_e( 'To', 'vitalstack-festive' ); ?></th><th><?php esc_html_e( 'Greeting (optional)', 'vitalstack-festive' ); ?></th><th><?php esc_html_e( 'Preview', 'vitalstack-festive' ); ?></th></tr></thead><tbody>
				<?php
				foreach ( $list as $k => $f ) :
					$d = $s['dates'][ $k ] ?? $f['dates'];
					?>
					<tr>
						<td><?php echo esc_html( $f['icon'] . ' ' . $f['name'] ); ?></td>
						<td><input name="start[<?php echo esc_attr( $k ); ?>]" value="<?php echo esc_attr( $d[0] ); ?>" size="11"></td>
						<td><input name="end[<?php echo esc_attr( $k ); ?>]" value="<?php echo esc_attr( $d[1] ); ?>" size="11"></td>
						<td><input name="greet[<?php echo esc_attr( $k ); ?>]" value="<?php echo esc_attr( $s['greet'][ $k ] ?? '' ); ?>" placeholder="<?php echo esc_attr( $f['greet'] ); ?>" class="regular-text"></td>
						<td><a href="<?php echo esc_url( add_query_arg( 'vs_festive', $k, home_url( '/' ) ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Preview', 'vitalstack-festive' ); ?> ↗</a></td>
					</tr>
				<?php endforeach; ?>
			</tbody></table>
			<?php submit_button( __( 'Save', 'vitalstack-festive' ), 'primary', 'vsf_save' ); ?>
		</form>
	</div>
	<?php
}
