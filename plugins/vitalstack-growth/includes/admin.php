<?php
/**
 * Growth Desk admin screens. Administrators only; every form has a nonce.
 *
 * @package VitalStackGrowth
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'admin_menu',
	function () {
		add_menu_page( __( 'Growth Desk', 'vitalstack-growth' ), __( 'Growth Desk', 'vitalstack-growth' ), 'manage_options', 'vitalstack-growth', 'vsg_page', 'dashicons-chart-line', 3 );
	}
);

function vsg_tabs() {
	return array(
		'dash'      => __( 'Dashboard', 'vitalstack-growth' ),
		'offer'     => __( 'Offer (selling)', 'vitalstack-growth' ),
		'sales'     => __( 'Sales agent', 'vitalstack-growth' ),
		'marketing' => __( 'Marketing agent', 'vitalstack-growth' ),
		'testing'   => __( 'Testing & Security agent', 'vitalstack-growth' ),
	);
}

function vsg_tab_url( $tab, $args = array() ) {
	return add_query_arg( array_merge( array( 'page' => 'vitalstack-growth', 'tab' => $tab ), $args ), admin_url( 'admin.php' ) );
}

/* ── Actions ───────────────────────────────────────────────────────────── */

add_action(
	'admin_post_vsg',
	function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'vitalstack-growth' ), 403 );
		}
		check_admin_referer( 'vsg_action' );
		$do  = isset( $_POST['do'] ) ? sanitize_key( $_POST['do'] ) : '';
		$tab = 'dash';
		$msg = '';
		if ( 'save_offer' === $do ) {
			$tab   = 'offer';
			$s     = vsg_settings();
			$ends  = isset( $_POST['ends'] ) ? sanitize_text_field( wp_unslash( $_POST['ends'] ) ) : '';
			$ends  = str_replace( 'T', ' ', $ends );
			$valid = '' === $ends || date_create_immutable_from_format( 'Y-m-d H:i', $ends, wp_timezone() );
			$s['offer_on']      = empty( $_POST['offer_on'] ) ? 0 : 1;
			$s['name']          = isset( $_POST['name'] ) ? mb_substr( sanitize_text_field( wp_unslash( $_POST['name'] ) ), 0, 40 ) : $s['name'];
			$s['name']          = '' === $s['name'] ? 'Launch Sale' : $s['name'];
			$s['ends']          = $valid ? $ends : $s['ends'];
			$s['home']          = empty( $_POST['home'] ) ? 0 : 1;
			$s['pricing']       = empty( $_POST['pricing'] ) ? 0 : 1;
			$s['slide']         = empty( $_POST['slide'] ) ? 0 : 1;
			$s['slide_lessons'] = empty( $_POST['slide_lessons'] ) ? 0 : 1;
			$s['slide_days']    = isset( $_POST['slide_days'] ) ? max( 1, min( 30, absint( $_POST['slide_days'] ) ) ) : 3;
			$reg                = array();
			$in                 = isset( $_POST['regular'] ) && is_array( $_POST['regular'] ) ? wp_unslash( $_POST['regular'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- each value is cast below
			$plans              = function_exists( 'vitalstack_pay_plans' ) ? vitalstack_pay_plans() : array();
			foreach ( $plans as $id => $p ) {
				$inr = isset( $in[ $id ]['inr'] ) ? absint( $in[ $id ]['inr'] ) : 0;
				$usd = isset( $in[ $id ]['usd'] ) ? round( abs( (float) $in[ $id ]['usd'] ), 2 ) : 0;
				// A "regular price" lower than today's price would be a fake saving: ignore it.
				$inr = $inr > (int) $p['inr'] ? $inr : 0;
				$usd = $usd > (float) $p['usd'] ? $usd : 0;
				if ( $inr || $usd ) {
					$reg[ $id ] = array( $inr, $usd );
				}
			}
			$s['regular'] = $reg;
			update_option( 'vsg_settings', $s );
			$msg = $valid ? 'saved' : 'bad_date';
		} elseif ( 'save_agents' === $do ) {
			$tab                = 'testing';
			$s                  = vsg_settings();
			$s['daily_test']    = empty( $_POST['daily_test'] ) ? 0 : 1;
			$s['weekly_report'] = empty( $_POST['weekly_report'] ) ? 0 : 1;
			$email              = isset( $_POST['report_email'] ) ? sanitize_email( wp_unslash( $_POST['report_email'] ) ) : '';
			$s['report_email']  = is_email( $email ) ? $email : '';
			update_option( 'vsg_settings', $s );
			$msg = 'saved';
		} elseif ( 'run_checks' === $do ) {
			$tab = 'testing';
			update_option( 'vsg_last_check', array( time(), vsg_checks() ), false );
			$msg = 'checked';
		} elseif ( 'send_report' === $do ) {
			$tab = 'dash';
			$msg = vsg_send( __( 'Your week: sales, sign-ups and site health', 'vitalstack-growth' ), vsg_weekly_body() ) ? 'sent' : 'not_sent';
		} elseif ( in_array( $do, array( 'sub_on', 'sub_off' ), true ) ) {
			$tab = 'marketing';
			$uid = isset( $_POST['user'] ) ? absint( $_POST['user'] ) : 0;
			if ( $uid && get_userdata( $uid ) && function_exists( 'vitalstack_set_subscribed' ) ) {
				vitalstack_set_subscribed( $uid, 'sub_on' === $do );
				$msg = 'sub_on' === $do ? 'subbed' : 'unsubbed';
			}
		}
		$back = vsg_tab_url( $tab, array( 'msg' => $msg ) );
		if ( 'marketing' === $tab && ! empty( $_POST['q'] ) ) {
			$back = add_query_arg( 'q', rawurlencode( sanitize_text_field( wp_unslash( $_POST['q'] ) ) ), $back );
		}
		wp_safe_redirect( $back );
		exit;
	}
);

function vsg_form_open( $do ) {
	echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
	echo '<input type="hidden" name="action" value="vsg"><input type="hidden" name="do" value="' . esc_attr( $do ) . '">';
	wp_nonce_field( 'vsg_action' );
}

/* ── Page ──────────────────────────────────────────────────────────────── */

function vsg_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$tab  = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'dash'; // phpcs:ignore WordPress.Security.NonceVerification -- navigation only
	$tab  = isset( vsg_tabs()[ $tab ] ) ? $tab : 'dash';
	$msgs = array(
		'saved'    => __( 'Saved.', 'vitalstack-growth' ),
		'bad_date' => __( 'Saved, but the end date was not a valid date, so the old one was kept.', 'vitalstack-growth' ),
		'checked'  => __( 'Checks done.', 'vitalstack-growth' ),
		'sent'     => __( 'Report sent to your email.', 'vitalstack-growth' ),
		'not_sent' => __( 'The report could not be sent. See Tools → Email check.', 'vitalstack-growth' ),
		'subbed'   => __( 'Subscribed.', 'vitalstack-growth' ),
		'unsubbed' => __( 'Unsubscribed.', 'vitalstack-growth' ),
	);
	$msg  = isset( $_GET['msg'] ) ? sanitize_key( $_GET['msg'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification -- message key only
	?>
	<div class="wrap vsg-admin">
		<h1><?php esc_html_e( 'Growth Desk', 'vitalstack-growth' ); ?></h1>
		<p class="description"><?php esc_html_e( 'Selling, marketing and testing in one place. The agents are rule-based helpers that run inside WordPress on a schedule: no AI service, no outside calls, nothing a visitor can reach.', 'vitalstack-growth' ); ?></p>
		<?php if ( isset( $msgs[ $msg ] ) ) : ?><div class="notice notice-success is-dismissible"><p><?php echo esc_html( $msgs[ $msg ] ); ?></p></div><?php endif; ?>
		<nav class="nav-tab-wrapper">
			<?php foreach ( vsg_tabs() as $k => $label ) : ?>
				<a class="nav-tab<?php echo $k === $tab ? ' nav-tab-active' : ''; ?>" href="<?php echo esc_url( vsg_tab_url( $k ) ); ?>"><?php echo esc_html( $label ); ?></a>
			<?php endforeach; ?>
		</nav>
		<style>.vsg-admin .vsg-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;margin:16px 0}.vsg-admin .vsg-card{background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:14px 16px}.vsg-admin .vsg-card b{display:block;font-size:26px;line-height:1.2}.vsg-admin .vsg-ok{color:#008a20}.vsg-admin .vsg-warn{color:#996800}.vsg-admin .vsg-bad{color:#b32d2e}.vsg-admin td,.vsg-admin th{vertical-align:top}.vsg-admin textarea{width:100%}.vsg-admin .vsg-tip{background:#fff8e5;border-left:4px solid #dba617;padding:8px 12px;margin:6px 0}</style>
		<?php
		switch ( $tab ) {
			case 'offer':
				vsg_tab_offer();
				break;
			case 'sales':
				vsg_tab_sales();
				break;
			case 'marketing':
				vsg_tab_marketing();
				break;
			case 'testing':
				vsg_tab_testing();
				break;
			default:
				vsg_tab_dash();
		}
		?>
	</div>
	<?php
}

function vsg_status_icon( $s ) {
	$map = array( 'ok' => array( 'vsg-ok', '✓' ), 'warn' => array( 'vsg-warn', '!' ), 'bad' => array( 'vsg-bad', '✗' ) );
	$m   = $map[ $s ] ?? $map['warn'];
	return '<b class="' . esc_attr( $m[0] ) . '">' . esc_html( $m[1] ) . '</b>';
}

function vsg_tab_dash() {
	$s7   = vsg_sales( 7 );
	$s30  = vsg_sales( 30 );
	$m    = vsg_marketing();
	$last = get_option( 'vsg_last_check' );
	$bad  = 0;
	$warn = 0;
	if ( is_array( $last ) ) {
		foreach ( $last[1] as $c ) {
			$bad  += 'bad' === $c[0] ? 1 : 0;
			$warn += 'warn' === $c[0] ? 1 : 0;
		}
	}
	?>
	<div class="vsg-cards">
		<div class="vsg-card"><?php esc_html_e( 'Revenue, 7 days', 'vitalstack-growth' ); ?><b><?php echo esc_html( vsg_money( $s7['revenue'] ) ); ?></b><?php echo esc_html( sprintf( /* translators: %d: orders */ _n( '%d paid order', '%d paid orders', $s7['paid'], 'vitalstack-growth' ), $s7['paid'] ) ); ?></div>
		<div class="vsg-card"><?php esc_html_e( 'Revenue, 30 days', 'vitalstack-growth' ); ?><b><?php echo esc_html( vsg_money( $s30['revenue'] ) ); ?></b><?php echo esc_html( sprintf( /* translators: %d: percent */ __( '%d%% of checkouts paid', 'vitalstack-growth' ), vsg_conversion( $s30 ) ) ); ?></div>
		<div class="vsg-card"><?php esc_html_e( 'New sign-ups, 7 days', 'vitalstack-growth' ); ?><b><?php echo (int) $m['signups7']; ?></b><?php echo esc_html( sprintf( /* translators: %d: users */ __( '%d accounts in total', 'vitalstack-growth' ), $m['users'] ) ); ?></div>
		<div class="vsg-card"><?php esc_html_e( 'Subscribers', 'vitalstack-growth' ); ?><b><?php echo (int) $m['subs']; ?></b><?php echo esc_html( sprintf( /* translators: %d: count */ __( '%d confirmed, reachable by email', 'vitalstack-growth' ), $m['subs_ok'] ) ); ?></div>
		<div class="vsg-card"><?php esc_html_e( 'Site health', 'vitalstack-growth' ); ?><b class="<?php echo esc_attr( $bad ? 'vsg-bad' : ( $warn ? 'vsg-warn' : 'vsg-ok' ) ); ?>"><?php echo is_array( $last ) ? esc_html( $bad ? sprintf( /* translators: %d: count */ __( '%d to fix', 'vitalstack-growth' ), $bad ) : ( $warn ? sprintf( /* translators: %d: count */ __( '%d to review', 'vitalstack-growth' ), $warn ) : __( 'All good', 'vitalstack-growth' ) ) ) : '—'; ?></b><a href="<?php echo esc_url( vsg_tab_url( 'testing' ) ); ?>"><?php esc_html_e( 'Open the Testing agent', 'vitalstack-growth' ); ?></a></div>
		<div class="vsg-card"><?php esc_html_e( 'Offer', 'vitalstack-growth' ); ?><b><?php echo esc_html( vsg_offer_running() ? __( 'Running', 'vitalstack-growth' ) : __( 'Off', 'vitalstack-growth' ) ); ?></b><?php echo esc_html( vsg_offer_end() ? sprintf( /* translators: %s: time left */ __( 'Ends in %s', 'vitalstack-growth' ), vsg_left_label( vsg_offer_end() - time() ) ) : __( 'No end date', 'vitalstack-growth' ) ); ?></div>
	</div>
	<?php foreach ( vsg_sales_tips( $s7, $s30 ) as $t ) : ?>
		<p class="vsg-tip"><?php echo esc_html( $t ); ?></p>
	<?php endforeach; ?>
	<?php vsg_form_open( 'send_report' ); ?>
		<p><?php submit_button( __( 'Email me this week’s report now', 'vitalstack-growth' ), 'secondary', 'submit', false ); ?> <span class="description"><?php echo esc_html( sprintf( /* translators: %s: email */ __( 'Goes to %s. It is also sent every Monday morning.', 'vitalstack-growth' ), vsg_report_email() ) ); ?></span></p>
	</form>
	<?php
}

function vsg_tab_offer() {
	$s     = vsg_settings();
	$plans = function_exists( 'vitalstack_pay_plans' ) ? vitalstack_pay_plans() : array();
	$prev  = add_query_arg( 'vsg_preview', '1', home_url( '/' ) );
	?>
	<p><?php esc_html_e( 'Landing offers like a big sale: a deal card in the home page hero, a deal panel at the top of the pricing page and a small card that slides in once every few days. Premium readers never see them.', 'vitalstack-growth' ); ?>
		<a href="<?php echo esc_url( $prev ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Preview the home page as a visitor', 'vitalstack-growth' ); ?> ↗</a></p>
	<?php vsg_form_open( 'save_offer' ); ?>
	<table class="form-table" role="presentation">
		<tr><th><?php esc_html_e( 'Offer', 'vitalstack-growth' ); ?></th><td><label><input type="checkbox" name="offer_on" value="1" <?php checked( $s['offer_on'] ); ?>> <?php esc_html_e( 'Show the offer on the site', 'vitalstack-growth' ); ?></label></td></tr>
		<tr><th><label for="vsg-name"><?php esc_html_e( 'Offer name', 'vitalstack-growth' ); ?></label></th><td><input id="vsg-name" class="regular-text" name="name" maxlength="40" value="<?php echo esc_attr( $s['name'] ); ?>"> <p class="description"><?php esc_html_e( 'For example: Launch Sale, New Year Sale, Diwali Offer.', 'vitalstack-growth' ); ?></p></td></tr>
		<tr><th><label for="vsg-ends"><?php esc_html_e( 'Offer ends', 'vitalstack-growth' ); ?></label></th><td><input id="vsg-ends" type="datetime-local" name="ends" value="<?php echo esc_attr( str_replace( ' ', 'T', $s['ends'] ) ); ?>"> <p class="description"><?php esc_html_e( 'Site time. A live countdown appears only when you set a real end date, and the offer disappears by itself when it ends. Leave empty for no countdown. Only use a date you will keep: a deadline that resets is a dark pattern and against consumer law.', 'vitalstack-growth' ); ?></p></td></tr>
		<tr><th><?php esc_html_e( 'Where', 'vitalstack-growth' ); ?></th><td>
			<label><input type="checkbox" name="home" value="1" <?php checked( $s['home'] ); ?>> <?php esc_html_e( 'Deal card in the home page hero', 'vitalstack-growth' ); ?></label><br>
			<label><input type="checkbox" name="pricing" value="1" <?php checked( $s['pricing'] ); ?>> <?php esc_html_e( 'Deal panel at the top of the pricing page', 'vitalstack-growth' ); ?></label><br>
			<label><input type="checkbox" name="slide" value="1" <?php checked( $s['slide'] ); ?>> <?php esc_html_e( 'Slide-in card on other pages, once every', 'vitalstack-growth' ); ?></label> <input type="number" name="slide_days" min="1" max="30" value="<?php echo (int) $s['slide_days']; ?>" style="width:60px"> <?php esc_html_e( 'days per visitor', 'vitalstack-growth' ); ?><br>
			<label><input type="checkbox" name="slide_lessons" value="1" <?php checked( $s['slide_lessons'] ); ?>> <?php esc_html_e( 'Also on lesson pages', 'vitalstack-growth' ); ?></label>
		</td></tr>
	</table>
	<h2><?php esc_html_e( 'Regular prices (after the offer)', 'vitalstack-growth' ); ?></h2>
	<p class="description"><?php esc_html_e( 'Enter the real price each plan will cost after the offer. The site then shows it struck through with "Save X%". Leave empty to show no saving. A regular price lower than today\'s price is ignored.', 'vitalstack-growth' ); ?></p>
	<table class="widefat striped" style="max-width:720px">
		<thead><tr><th><?php esc_html_e( 'Plan', 'vitalstack-growth' ); ?></th><th><?php esc_html_e( 'Price now', 'vitalstack-growth' ); ?></th><th><?php esc_html_e( 'Regular ₹', 'vitalstack-growth' ); ?></th><th><?php esc_html_e( 'Regular $', 'vitalstack-growth' ); ?></th></tr></thead>
		<tbody>
		<?php foreach ( $plans as $id => $p ) : $r = $s['regular'][ $id ] ?? array( 0, 0 ); ?>
			<tr>
				<td><?php echo esc_html( wp_strip_all_tags( $p['title'] ) ); ?></td>
				<td>₹<?php echo esc_html( number_format_i18n( (int) $p['inr'] ) ); ?> / $<?php echo esc_html( $p['usd'] ); ?></td>
				<td><input type="number" min="0" step="1" name="regular[<?php echo esc_attr( $id ); ?>][inr]" value="<?php echo $r[0] ? (int) $r[0] : ''; ?>" style="width:100px"></td>
				<td><input type="number" min="0" step="0.01" name="regular[<?php echo esc_attr( $id ); ?>][usd]" value="<?php echo $r[1] ? esc_attr( $r[1] ) : ''; ?>" style="width:100px"></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php submit_button( __( 'Save offer', 'vitalstack-growth' ) ); ?>
	</form>
	<?php
}

function vsg_tab_sales() {
	$test = ! empty( $_GET['test'] ); // phpcs:ignore WordPress.Security.NonceVerification -- view filter only
	?>
	<p><?php esc_html_e( 'Live orders only.', 'vitalstack-growth' ); ?> <a href="<?php echo esc_url( vsg_tab_url( 'sales', $test ? array() : array( 'test' => 1 ) ) ); ?>"><?php echo esc_html( $test ? __( 'Hide test orders', 'vitalstack-growth' ) : __( 'Include test orders', 'vitalstack-growth' ) ); ?></a></p>
	<table class="widefat striped" style="max-width:900px">
		<thead><tr><th></th><th><?php esc_html_e( 'Today', 'vitalstack-growth' ); ?></th><th><?php esc_html_e( '7 days', 'vitalstack-growth' ); ?></th><th><?php esc_html_e( '30 days', 'vitalstack-growth' ); ?></th></tr></thead>
		<?php
		$cols = array( vsg_sales( 1, $test ), vsg_sales( 7, $test ), vsg_sales( 30, $test ) );
		$rows = array(
			__( 'Paid orders', 'vitalstack-growth' )                 => function ( $s ) { return (string) $s['paid']; },
			__( 'Revenue', 'vitalstack-growth' )                     => function ( $s ) { return vsg_money( $s['revenue'] ); },
			__( 'Checkouts started', 'vitalstack-growth' )           => function ( $s ) { return (string) $s['created']; },
			__( 'Share paid', 'vitalstack-growth' )                  => function ( $s ) { return vsg_conversion( $s ) . '%'; },
			__( 'Failed payments', 'vitalstack-growth' )             => function ( $s ) { return (string) $s['failed']; },
			__( 'Refunded or disputed', 'vitalstack-growth' )        => function ( $s ) { return (string) $s['refunded']; },
		);
		foreach ( $rows as $label => $fn ) {
			echo '<tr><th>' . esc_html( $label ) . '</th>';
			foreach ( $cols as $s ) {
				echo '<td>' . esc_html( $fn( $s ) ) . '</td>';
			}
			echo '</tr>';
		}
		?>
	</table>
	<h2><?php esc_html_e( 'Best-selling plans (30 days)', 'vitalstack-growth' ); ?></h2>
	<ul>
		<?php foreach ( $cols[2]['plans'] as $plan => $n ) : ?><li><?php echo esc_html( $plan . ': ' . $n ); ?></li><?php endforeach; ?>
		<?php if ( ! $cols[2]['plans'] ) : ?><li><?php esc_html_e( 'No paid orders yet.', 'vitalstack-growth' ); ?></li><?php endif; ?>
	</ul>
	<?php if ( $cols[2]['coupons'] ) : ?>
		<h2><?php esc_html_e( 'Coupons used (30 days)', 'vitalstack-growth' ); ?></h2>
		<ul><?php foreach ( $cols[2]['coupons'] as $c => $n ) : ?><li><?php echo esc_html( $c . ': ' . $n ); ?></li><?php endforeach; ?></ul>
	<?php endif; ?>
	<h2><?php esc_html_e( 'Latest orders', 'vitalstack-growth' ); ?></h2>
	<table class="widefat striped" style="max-width:900px">
		<thead><tr><th><?php esc_html_e( 'Date', 'vitalstack-growth' ); ?></th><th><?php esc_html_e( 'Plan', 'vitalstack-growth' ); ?></th><th><?php esc_html_e( 'Amount', 'vitalstack-growth' ); ?></th><th><?php esc_html_e( 'Status', 'vitalstack-growth' ); ?></th><th><?php esc_html_e( 'Buyer', 'vitalstack-growth' ); ?></th></tr></thead>
		<tbody>
		<?php foreach ( $cols[2]['recent'] as $o ) : ?>
			<tr><td><?php echo esc_html( $o['date'] ); ?></td><td><?php echo esc_html( $o['plan'] ); ?></td><td><?php echo esc_html( $o['amount'] ); ?></td><td><?php echo esc_html( $o['status'] ); ?></td><td><?php echo esc_html( $o['buyer'] ); ?></td></tr>
		<?php endforeach; ?>
		<?php if ( ! $cols[2]['recent'] ) : ?><tr><td colspan="5"><?php esc_html_e( 'No orders in the last 30 days.', 'vitalstack-growth' ); ?></td></tr><?php endif; ?>
		</tbody>
	</table>
	<?php foreach ( vsg_sales_tips( $cols[1], $cols[2] ) as $t ) : ?>
		<p class="vsg-tip"><?php echo esc_html( $t ); ?></p>
	<?php endforeach; ?>
	<?php
}

function vsg_tab_marketing() {
	$m = vsg_marketing();
	$q = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification -- search box only
	?>
	<div class="vsg-cards">
		<div class="vsg-card"><?php esc_html_e( 'Sign-ups, 7 / 30 days', 'vitalstack-growth' ); ?><b><?php echo (int) $m['signups7']; ?> / <?php echo (int) $m['signups30']; ?></b></div>
		<div class="vsg-card"><?php esc_html_e( 'Subscribers', 'vitalstack-growth' ); ?><b><?php echo (int) $m['subs']; ?></b><?php echo esc_html( sprintf( /* translators: %d: count */ __( '%d confirmed and reachable', 'vitalstack-growth' ), $m['subs_ok'] ) ); ?></div>
		<div class="vsg-card"><?php esc_html_e( 'Offer emails', 'vitalstack-growth' ); ?><b><?php echo esc_html( null === $m['vsm'] ? __( 'Plugin off', 'vitalstack-growth' ) : ( $m['vsm'] ? __( 'On', 'vitalstack-growth' ) : __( 'Paused', 'vitalstack-growth' ) ) ); ?></b><?php if ( null !== $m['vsm'] ) : ?><a href="<?php echo esc_url( admin_url( 'options-general.php?page=vitalstack-marketing' ) ); ?>"><?php esc_html_e( 'VitalStack Marketing', 'vitalstack-growth' ); ?></a><?php endif; ?></div>
	</div>
	<?php if ( $m['festivals'] ) : ?>
		<h2><?php esc_html_e( 'Festivals coming up (plan an offer a week before)', 'vitalstack-growth' ); ?></h2>
		<ul><?php foreach ( $m['festivals'] as $f ) : ?><li><?php echo esc_html( $f[1] . ' · ' . $f[0] ); ?></li><?php endforeach; ?></ul>
	<?php endif; ?>
	<h2><?php esc_html_e( 'Share kit: ready-to-post messages with tracked links', 'vitalstack-growth' ); ?></h2>
	<p class="description"><?php esc_html_e( 'Copy into WhatsApp, LinkedIn, Instagram, X or Threads. The links carry utm tags, so Google Analytics shows which posts bring visitors and buyers.', 'vitalstack-growth' ); ?></p>
	<?php foreach ( vsg_share_kit() as $k ) : ?>
		<p><strong><?php echo esc_html( $k[0] ); ?></strong></p>
		<textarea rows="3" readonly onclick="this.select()"><?php echo esc_textarea( $k[1] . "\n" . $k[2] ); ?></textarea>
	<?php endforeach; ?>
	<h2><?php esc_html_e( 'Subscribers: find a reader', 'vitalstack-growth' ); ?></h2>
	<p class="description"><?php esc_html_e( 'Subscribe or unsubscribe one reader by hand (for example when someone asks you by email). Readers can also do it themselves, and every email has a one-click unsubscribe link, as the law and Gmail require.', 'vitalstack-growth' ); ?></p>
	<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
		<input type="hidden" name="page" value="vitalstack-growth"><input type="hidden" name="tab" value="marketing">
		<input type="search" name="q" value="<?php echo esc_attr( $q ); ?>" placeholder="<?php esc_attr_e( 'Email or name', 'vitalstack-growth' ); ?>"> <?php submit_button( __( 'Search', 'vitalstack-growth' ), 'secondary', '', false ); ?>
	</form>
	<?php
	if ( '' === $q ) {
		return;
	}
	$users = get_users( array( 'search' => '*' . $q . '*', 'search_columns' => array( 'user_email', 'user_login', 'display_name' ), 'number' => 20 ) );
	?>
	<table class="widefat striped" style="max-width:900px;margin-top:10px">
		<thead><tr><th><?php esc_html_e( 'Reader', 'vitalstack-growth' ); ?></th><th><?php esc_html_e( 'Joined', 'vitalstack-growth' ); ?></th><th><?php esc_html_e( 'Email confirmed', 'vitalstack-growth' ); ?></th><th><?php esc_html_e( 'Subscribed', 'vitalstack-growth' ); ?></th><th></th></tr></thead>
		<tbody>
		<?php foreach ( $users as $u ) : $on = function_exists( 'vitalstack_is_subscribed' ) && vitalstack_is_subscribed( $u->ID ); ?>
			<tr>
				<td><?php echo esc_html( $u->display_name ); ?><br><span class="description"><?php echo esc_html( $u->user_email ); ?></span></td>
				<td><?php echo esc_html( mysql2date( 'Y-m-d', $u->user_registered ) ); ?></td>
				<td><?php echo get_user_meta( $u->ID, 'vs_email_verified', true ) ? '✓' : '—'; ?></td>
				<td><?php echo $on ? '✓' : '—'; ?></td>
				<td><?php vsg_form_open( $on ? 'sub_off' : 'sub_on' ); ?><input type="hidden" name="user" value="<?php echo (int) $u->ID; ?>"><input type="hidden" name="q" value="<?php echo esc_attr( $q ); ?>"><?php submit_button( $on ? __( 'Unsubscribe', 'vitalstack-growth' ) : __( 'Subscribe', 'vitalstack-growth' ), 'small', '', false ); ?></form></td>
			</tr>
		<?php endforeach; ?>
		<?php if ( ! $users ) : ?><tr><td colspan="5"><?php esc_html_e( 'No reader found.', 'vitalstack-growth' ); ?></td></tr><?php endif; ?>
		</tbody>
	</table>
	<?php
}

function vsg_tab_testing() {
	$s    = vsg_settings();
	$last = get_option( 'vsg_last_check' );
	?>
	<p><?php esc_html_e( 'Every day the Testing & Security agent opens the key pages and checks payments, email, AdSense and security settings. It emails you only when something is red.', 'vitalstack-growth' ); ?></p>
	<?php vsg_form_open( 'run_checks' ); ?><?php submit_button( __( 'Run all checks now', 'vitalstack-growth' ), 'primary', 'submit', false ); ?></form>
	<?php if ( is_array( $last ) ) : ?>
		<p class="description"><?php echo esc_html( sprintf( /* translators: %s: date */ __( 'Last run: %s', 'vitalstack-growth' ), wp_date( 'Y-m-d H:i', (int) $last[0] ) ) ); ?></p>
		<table class="widefat striped" style="max-width:1000px">
			<thead><tr><th></th><th><?php esc_html_e( 'Area', 'vitalstack-growth' ); ?></th><th><?php esc_html_e( 'Check', 'vitalstack-growth' ); ?></th><th><?php esc_html_e( 'Result', 'vitalstack-growth' ); ?></th><th><?php esc_html_e( 'How to fix', 'vitalstack-growth' ); ?></th></tr></thead>
			<tbody>
			<?php foreach ( $last[1] as $c ) : ?>
				<tr><td><?php echo vsg_status_icon( $c[0] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in vsg_status_icon ?></td><td><?php echo esc_html( $c[1] ); ?></td><td><?php echo esc_html( $c[2] ); ?></td><td><?php echo esc_html( $c[3] ); ?></td><td><?php echo esc_html( $c[4] ); ?></td></tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
	<h2><?php esc_html_e( 'Agent settings', 'vitalstack-growth' ); ?></h2>
	<?php vsg_form_open( 'save_agents' ); ?>
	<table class="form-table" role="presentation">
		<tr><th><?php esc_html_e( 'Daily check', 'vitalstack-growth' ); ?></th><td><label><input type="checkbox" name="daily_test" value="1" <?php checked( $s['daily_test'] ); ?>> <?php esc_html_e( 'Email me when a check fails', 'vitalstack-growth' ); ?></label></td></tr>
		<tr><th><?php esc_html_e( 'Monday report', 'vitalstack-growth' ); ?></th><td><label><input type="checkbox" name="weekly_report" value="1" <?php checked( $s['weekly_report'] ); ?>> <?php esc_html_e( 'Sales, sign-ups and site health every Monday', 'vitalstack-growth' ); ?></label></td></tr>
		<tr><th><label for="vsg-mail"><?php esc_html_e( 'Send reports to', 'vitalstack-growth' ); ?></label></th><td><input id="vsg-mail" type="email" class="regular-text" name="report_email" value="<?php echo esc_attr( $s['report_email'] ); ?>" placeholder="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>"></td></tr>
	</table>
	<?php submit_button( __( 'Save', 'vitalstack-growth' ) ); ?>
	</form>
	<?php
}
