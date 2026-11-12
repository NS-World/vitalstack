<?php
/**
 * Site assistant (4.13.4): a chat helper that answers the common questions
 * about VitalStack: plans, prices and offers, invites, certificates, what is
 * free, payments, devices, and where to start learning.
 *
 * Security by design:
 *   - it runs fully in the browser from public facts printed below (prices,
 *     page links, course names). Nothing a visitor types is sent to the
 *     server or to any AI service, so it can't be used to reach the site,
 *     leak data, run up an API bill or be talked into saying something false;
 *   - no secret, setting or private data is ever included (only the public
 *     plan list), and replies are written into the page as text, never HTML.
 *
 * Prices follow the same rule as the pricing page: ₹ in India, $ elsewhere.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

function vitalstack_helper_enabled() {
	return ! is_admin() && (bool) apply_filters( 'vitalstack_helper_enabled', true );
}

/** Course names and links, cached for 12 hours. */
function vitalstack_helper_courses() {
	$list = get_transient( 'vs_helper_courses' );
	if ( is_array( $list ) ) {
		return $list;
	}
	$list = array();
	if ( function_exists( 'vitalstack_learning_paths' ) ) {
		foreach ( vitalstack_learning_paths() as $t ) {
			$link = get_term_link( $t );
			if ( ! is_wp_error( $link ) ) {
				$list[] = array( 'n' => html_entity_decode( $t->name, ENT_QUOTES, 'UTF-8' ), 's' => $t->slug, 'u' => $link );
			}
		}
	}
	set_transient( 'vs_helper_courses', $list, 12 * HOUR_IN_SECONDS );
	return $list;
}
add_action( 'created_tutorial_category', function () { delete_transient( 'vs_helper_courses' ); } );
add_action( 'edited_tutorial_category', function () { delete_transient( 'vs_helper_courses' ); } );
add_action( 'delete_tutorial_category', function () { delete_transient( 'vs_helper_courses' ); } );

function vitalstack_helper_page( $slug ) {
	$page = get_page_by_path( $slug );
	return ( $page && 'publish' === $page->post_status ) ? get_permalink( $page ) : '';
}

/** Public facts the assistant answers from. */
function vitalstack_helper_data() {
	$plans = array();
	if ( function_exists( 'vitalstack_pay_plans' ) ) {
		$list = function_exists( 'vitalstack_plan_list_prices' ) ? vitalstack_plan_list_prices() : array();
		foreach ( vitalstack_pay_plans() as $id => $p ) {
			$kind = 'lifetime' === $id ? 'life' : ( 'all-month' === $id ? 'month' : ( 'all-year' === $id ? 'year' : ( ! empty( $p['combo'] ) ? 'combo' : ( ! empty( $p['track'] ) ? 'track' : 'other' ) ) ) );
			$plans[] = array(
				'id'     => $id,
				'kind'   => $kind,
				'title'  => wp_strip_all_tags( $p['title'] ),
				'inr'    => (int) $p['inr'],
				'usd'    => (float) $p['usd'],
				'wasInr' => isset( $list[ $id ][0] ) ? (int) $list[ $id ][0] : 0,
				'wasUsd' => isset( $list[ $id ][1] ) ? (float) $list[ $id ][1] : 0,
				'days'   => (int) $p['days'],
				'tracks' => count( (array) ( $p['tracks'] ?? array() ) ),
			);
		}
	}
	$tracks = function_exists( 'vitalstack_premium_tracks' ) ? array_values( wp_list_pluck( vitalstack_premium_tracks(), 'title' ) ) : array();
	$ref    = function_exists( 'vitalstack_ref_settings' ) ? vitalstack_ref_settings() : array();
	$uid    = get_current_user_id();
	$data   = array(
		'site'     => wp_strip_all_tags( get_bloginfo( 'name' ) ),
		'cur'      => function_exists( 'vitalstack_pay_page_currency' ) ? vitalstack_pay_page_currency() : 'inr',
		'open'     => function_exists( 'vitalstack_pay_ready' ) ? (bool) vitalstack_pay_ready() : false,
		'plans'    => $plans,
		'tracks'   => array_map( 'wp_strip_all_tags', $tracks ),
		'refund'   => function_exists( 'vitalstack_pay_settings' ) ? max( 0, (int) ( vitalstack_pay_settings()['refund_days'] ?? 0 ) ) : 0, // only this one public number from settings
		'lifeLeft' => function_exists( 'vitalstack_pay_lifetime_left' ) ? (int) vitalstack_pay_lifetime_left() : null,
		'ref'      => array(
			'on'      => ! empty( $ref['on'] ),
			'percent' => (int) ( $ref['percent'] ?? 0 ),
			'days'    => (int) ( $ref['paid_days'] ?? 0 ),
			'need'    => (int) ( $ref['active_need'] ?? 0 ),
			'aDays'   => (int) ( $ref['active_days'] ?? 0 ),
		),
		'urls'     => array_filter(
			array(
				'pricing'    => function_exists( 'vitalstack_pricing_url' ) ? vitalstack_pricing_url() : '',
				'account'    => function_exists( 'vitalstack_account_url' ) ? vitalstack_account_url() : '',
				'invite'     => function_exists( 'vitalstack_account_url' ) ? vitalstack_account_url() . '#invite' : '',
				'games'      => function_exists( 'vitalstack_games_url' ) ? vitalstack_games_url() : '',
				'tools'      => vitalstack_helper_page( 'tools' ),
				'python'     => function_exists( 'vitalstack_site_page_url' ) ? vitalstack_site_page_url( 'python-online-compiler' ) : '',
				'roadmaps'   => vitalstack_helper_page( 'roadmaps' ),
				'tutorials'  => vitalstack_helper_page( 'tutorials' ),
				'daily'      => vitalstack_helper_page( 'daily-challenge' ),
				'mock'       => vitalstack_helper_page( 'mock-tests' ),
				'cheats'     => vitalstack_helper_page( 'cheat-sheets' ),
				'dictionary' => function_exists( 'vitalstack_ai_dictionary_url' ) ? vitalstack_ai_dictionary_url() : '',
				'music'      => function_exists( 'vitalstack_study_music_url' ) ? vitalstack_study_music_url() : '',
				'contact'    => vitalstack_helper_page( 'contact' ),
				'search'     => home_url( '/' ),
			)
		),
		'courses'  => vitalstack_helper_courses(),
		'me'       => array(
			'in'      => (bool) $uid,
			'premium' => $uid && function_exists( 'vitalstack_reader_has_any_premium' ) ? (bool) vitalstack_reader_has_any_premium( $uid ) : false,
		),
	);
	return apply_filters( 'vitalstack_helper_data', $data );
}

function vitalstack_helper_markup() {
	if ( ! vitalstack_helper_enabled() ) {
		return;
	}
	$json = wp_json_encode( vitalstack_helper_data(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE );
	?>
	<button type="button" class="vsa-fab" data-vsa-open aria-controls="vsa-panel" aria-expanded="false" hidden>
		<span class="vsa-fab-ico" aria-hidden="true">💬</span><span class="vsa-fab-txt"><?php esc_html_e( 'Ask', 'vitalstack' ); ?></span>
	</button>
	<section class="vsa-panel" id="vsa-panel" role="dialog" aria-modal="false" aria-labelledby="vsa-title" data-vsa-panel hidden>
		<header class="vsa-head">
			<span class="vsa-avatar" aria-hidden="true"><?php echo function_exists( 'vitalstack_mascot' ) ? vitalstack_mascot( 'happy', 36, '', 'dev' ) : '🤖'; // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG ?></span>
			<div>
				<p class="vsa-title" id="vsa-title"><?php esc_html_e( 'VitalStack Assistant', 'vitalstack' ); ?></p>
				<p class="vsa-sub"><span class="vsa-dot" aria-hidden="true"></span><?php esc_html_e( 'Instant answers on courses, prices and offers', 'vitalstack' ); ?></p>
			</div>
			<button type="button" class="vsa-x" data-vsa-close aria-label="<?php esc_attr_e( 'Close chat', 'vitalstack' ); ?>">✕</button>
		</header>
		<div class="vsa-log" data-vsa-log role="log" aria-live="polite"></div>
		<div class="vsa-chips" data-vsa-chips></div>
		<form class="vsa-form" data-vsa-form autocomplete="off">
			<label class="screen-reader-text" for="vsa-input"><?php esc_html_e( 'Type your question', 'vitalstack' ); ?></label>
			<input id="vsa-input" type="text" maxlength="200" placeholder="<?php esc_attr_e( 'Ask about prices, offers, courses…', 'vitalstack' ); ?>" data-vsa-input>
			<button type="submit" class="vsa-send" aria-label="<?php esc_attr_e( 'Send', 'vitalstack' ); ?>">➤</button>
		</form>
		<p class="vsa-legal"><?php esc_html_e( 'Automatic answers from the site’s own facts. What you type stays on your device.', 'vitalstack' ); ?></p>
	</section>
	<script type="application/json" id="vsa-data"><?php echo $json; // phpcs:ignore WordPress.Security.EscapeOutput -- JSON with < > & ' " escaped ?></script>
	<?php
}
add_action( 'wp_footer', 'vitalstack_helper_markup', 6 );

function vitalstack_helper_assets() {
	if ( vitalstack_helper_enabled() ) {
		wp_enqueue_script( 'vitalstack-helper', vitalstack_asset_url( 'assets/js/helper.js' ), array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'vitalstack_helper_assets', 34 );
