<?php
/**
 * Email delivery (4.14.1): keep site emails out of spam.
 *
 *   - Every email the theme sends gets a plain-text part next to the HTML
 *     (HTML-only mail is a spam signal), a Message-ID on the sender's own
 *     domain, and, when no SMTP plugin is active, a Return-Path that matches
 *     the From address (needed for SPF/DMARC alignment).
 *   - Tools → Email check: looks up the domain's SPF, DKIM, DMARC and MX
 *     records from this server, spots a missing SMTP plugin or a From
 *     address on another domain, and sends a test email (for example to
 *     mail-tester.com for a spam score).
 *   - Admins see a dashboard warning while something important is red.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/** Plain text from the theme's email HTML: links become "text (url)". */
function vitalstack_mail_text( $html ) {
	$t = preg_replace( '#<(script|style|head)\b[^>]*>.*?</\1>#is', '', (string) $html );
	$t = preg_replace_callback(
		'#<a\s[^>]*href=("|\')(.*?)\1[^>]*>(.*?)</a>#is',
		function ( $m ) {
			$label = trim( wp_strip_all_tags( $m[3] ) );
			$url   = html_entity_decode( $m[2], ENT_QUOTES, 'UTF-8' );
			return ( '' === $label || $label === $url ) ? $url : $label . ' (' . $url . ')';
		},
		$t
	);
	$t = preg_replace( '#<(br|/p|/li|/h[1-6]|/tr|/table|/div)\b[^>]*>#i', "\n", $t );
	$t = preg_replace( '#<li\b[^>]*>#i', '- ', $t );
	$t = html_entity_decode( wp_strip_all_tags( $t ), ENT_QUOTES, 'UTF-8' );
	$t = preg_replace( "/[ \t]+/", ' ', $t );
	$t = preg_replace( "/\n\s*\n\s*\n+/", "\n\n", $t );
	return trim( implode( "\n", array_map( 'trim', explode( "\n", $t ) ) ) );
}

/** Whether an SMTP plugin (or a mailer service) handles WordPress mail. */
function vitalstack_mail_smtp_plugin() {
	$known = array(
		'wp-mail-smtp/wp_mail_smtp.php'          => 'WP Mail SMTP',
		'wp-mail-smtp-pro/wp_mail_smtp.php'      => 'WP Mail SMTP Pro',
		'fluent-smtp/fluent-smtp.php'            => 'FluentSMTP',
		'post-smtp/postman-smtp.php'             => 'Post SMTP',
		'easy-wp-smtp/easy-wp-smtp.php'          => 'Easy WP SMTP',
		'smtp-mailer/main.php'                   => 'SMTP Mailer',
		'gosmtp/gosmtp.php'                      => 'GoSMTP',
		'mailgun/mailgun.php'                    => 'Mailgun',
		'sendgrid-email-delivery-simplified/wpsendgrid.php' => 'SendGrid',
		'brevo/brevo.php'                        => 'Brevo',
		'mailin/sendinblue.php'                  => 'Brevo (Sendinblue)',
	);
	$active = (array) get_option( 'active_plugins', array() );
	foreach ( $known as $file => $name ) {
		if ( in_array( $file, $active, true ) ) {
			return $name;
		}
	}
	return (string) apply_filters( 'vitalstack_mail_smtp_plugin', '' );
}

function vitalstack_mail_domain_of( $email ) {
	$at = strrpos( (string) $email, '@' );
	return false === $at ? '' : strtolower( substr( $email, $at + 1 ) );
}

function vitalstack_mail_site_domain() {
	return preg_replace( '/^www\./', '', strtolower( (string) wp_parse_url( home_url( '/' ), PHP_URL_HOST ) ) );
}

/** The From address WordPress will use for theme emails. */
function vitalstack_mail_from() {
	return apply_filters( 'wp_mail_from', 'wordpress@' . vitalstack_mail_site_domain() );
}

/**
 * Applied to every theme email: plain-text part, Message-ID and Return-Path.
 * Hooked only while vitalstack_mail() sends, so other plugins' mail is untouched.
 */
function vitalstack_mail_prepare( $phpmailer ) {
	if ( empty( $phpmailer->AltBody ) && ! empty( $phpmailer->Body ) ) {
		$phpmailer->AltBody = vitalstack_mail_text( $phpmailer->Body );
	}
	$domain = vitalstack_mail_domain_of( $phpmailer->From );
	if ( $domain && preg_match( '/^[a-z0-9.-]+$/', $domain ) ) {
		$phpmailer->MessageID = '<' . bin2hex( random_bytes( 12 ) ) . '.' . time() . '@' . $domain . '>';
	}
	// With PHP mail() the envelope sender defaults to the server account; match it to From.
	if ( 'mail' === $phpmailer->Mailer && empty( $phpmailer->Sender ) && is_email( $phpmailer->From ) ) {
		$phpmailer->Sender = $phpmailer->From;
	}
}

/* ── Checks ───────────────────────────────────────────────────────────── */

function vitalstack_mail_txt( $name ) {
	if ( ! function_exists( 'dns_get_record' ) ) {
		return null;
	}
	$out = array();
	$rec = @dns_get_record( $name, DNS_TXT ); // phpcs:ignore WordPress.PHP.NoSilencedErrors -- missing records are normal
	foreach ( (array) $rec as $r ) {
		if ( isset( $r['txt'] ) ) {
			$out[] = $r['txt'];
		} elseif ( isset( $r['entries'] ) ) {
			$out[] = implode( '', $r['entries'] );
		}
	}
	return $out;
}

/** [ key => [status ok|warn|bad|info, title, detail, fix] ]. */
function vitalstack_mail_checks() {
	$from   = vitalstack_mail_from();
	$fd     = vitalstack_mail_domain_of( $from );
	$site   = vitalstack_mail_site_domain();
	$smtp   = vitalstack_mail_smtp_plugin();
	$checks = array();

	$checks['smtp'] = $smtp
		? array( 'ok', __( 'SMTP plugin', 'vitalstack' ), sprintf( /* translators: %s: plugin */ __( '%s is active: emails go through a real mail server.', 'vitalstack' ), $smtp ), '' )
		: array( 'bad', __( 'SMTP plugin', 'vitalstack' ), __( 'No SMTP plugin found. WordPress sends with PHP mail(), which Gmail and Outlook often put in spam.', 'vitalstack' ), __( 'Create the mailbox in hPanel → Emails (for example contact@ your domain), install "WP Mail SMTP" or "FluentSMTP", choose "Other SMTP": host smtp.hostinger.com, port 465, SSL, your full email as username and its password.', 'vitalstack' ) );

	$same = $fd && ( $fd === $site || substr( $site, -strlen( '.' . $fd ) ) === '.' . $fd );
	$checks['from'] = $same
		? array( 'ok', __( 'From address', 'vitalstack' ), sprintf( /* translators: %s: email */ __( 'Emails come from %s, on this site’s own domain.', 'vitalstack' ), $from ), '' )
		: array( $smtp ? 'warn' : 'bad', __( 'From address', 'vitalstack' ), sprintf( /* translators: 1: email, 2: domain */ __( 'Emails say they come from %1$s, but this site is %2$s. Without an SMTP login for that address, mail providers treat this as spoofing and use spam.', 'vitalstack' ), $from, $site ), __( 'Send through SMTP with a mailbox of the From domain. On a test copy of the site (UAT), expect some mail in spam unless it uses the same SMTP login.', 'vitalstack' ) );

	if ( ! function_exists( 'dns_get_record' ) ) {
		$checks['dns'] = array( 'info', __( 'DNS records', 'vitalstack' ), __( 'This server can’t look up DNS records. Check them with a free tool such as mxtoolbox.com.', 'vitalstack' ), '' );
		return $checks;
	}
	$spf = array_values( array_filter( (array) vitalstack_mail_txt( $fd ), function ( $t ) { return 0 === stripos( $t, 'v=spf1' ); } ) );
	if ( ! $spf ) {
		$checks['spf'] = array( 'bad', 'SPF', sprintf( /* translators: %s: domain */ __( 'No SPF record on %s.', 'vitalstack' ), $fd ), __( 'hPanel → Domains → DNS: add a TXT record, name @, value: v=spf1 include:_spf.mail.hostinger.com ~all (add other senders you use inside the same record).', 'vitalstack' ) );
	} elseif ( count( $spf ) > 1 ) {
		$checks['spf'] = array( 'bad', 'SPF', __( 'More than one SPF record: that makes SPF fail.', 'vitalstack' ) . ' ' . implode( ' | ', $spf ), __( 'Merge them into one TXT record that starts with v=spf1.', 'vitalstack' ) );
	} else {
		$h = false !== stripos( $spf[0], 'hostinger' );
		$checks['spf'] = array( $h || $smtp ? 'ok' : 'warn', 'SPF', $spf[0], $h ? '' : __( 'If you send through Hostinger email, the record should include include:_spf.mail.hostinger.com. Include whichever service your SMTP plugin uses.', 'vitalstack' ) );
	}
	$dkim = array();
	foreach ( array( 'hostingermail-a', 'hostingermail-b', 'hostingermail-c', 'hostingermail1', 'hostingermail2', 'default', 'google', 'k1', 's1', 'mail' ) as $sel ) {
		$name = $sel . '._domainkey.' . $fd;
		$txt  = (array) vitalstack_mail_txt( $name );
		$cn   = @dns_get_record( $name, DNS_CNAME ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
		if ( array_filter( $txt, function ( $t ) { return false !== stripos( $t, 'p=' ); } ) || ! empty( $cn ) ) {
			$dkim[] = $sel;
		}
	}
	$checks['dkim'] = $dkim
		? array( 'ok', 'DKIM', sprintf( /* translators: %s: selectors */ __( 'DKIM key found (selector: %s).', 'vitalstack' ), implode( ', ', $dkim ) ), '' )
		: array( 'bad', 'DKIM', __( 'No DKIM key found for the usual selectors.', 'vitalstack' ), __( 'hPanel → Emails → your domain → DNS settings (or "Email authentication"): add the three DKIM CNAME records Hostinger shows (hostingermail-a/b/c._domainkey). Other providers show their own.', 'vitalstack' ) );
	$dm = array_values( array_filter( (array) vitalstack_mail_txt( '_dmarc.' . $fd ), function ( $t ) { return 0 === stripos( $t, 'v=DMARC1' ); } ) );
	$checks['dmarc'] = $dm
		? array( 'ok', 'DMARC', $dm[0], '' )
		: array( 'bad', 'DMARC', sprintf( /* translators: %s: domain */ __( 'No DMARC record on %s. Gmail and Yahoo require one for bulk senders.', 'vitalstack' ), $fd ), __( 'Add a TXT record, name _dmarc, value: v=DMARC1; p=none; rua=mailto:your-address@your-domain (move to p=quarantine once everything passes).', 'vitalstack' ) );
	$mx = @dns_get_record( $fd, DNS_MX ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
	$checks['mx'] = ! empty( $mx )
		? array( 'ok', __( 'Mailbox (MX)', 'vitalstack' ), implode( ', ', wp_list_pluck( $mx, 'target' ) ), '' )
		: array( 'warn', __( 'Mailbox (MX)', 'vitalstack' ), __( 'No MX record: replies to your emails can’t arrive, and some filters distrust domains that can’t receive mail.', 'vitalstack' ), __( 'Create the mailbox in hPanel → Emails; Hostinger adds the MX records.', 'vitalstack' ) );
	return $checks;
}

/** Cached for the dashboard notice (12 hours). */
function vitalstack_mail_checks_cached() {
	$c = get_transient( 'vs_mail_checks' );
	if ( ! is_array( $c ) ) {
		$c = vitalstack_mail_checks();
		set_transient( 'vs_mail_checks', $c, 12 * HOUR_IN_SECONDS );
	}
	return $c;
}

/* ── Tools → Email check ──────────────────────────────────────────────── */

add_action(
	'admin_menu',
	function () {
		add_management_page( __( 'Email check', 'vitalstack' ), __( 'Email check', 'vitalstack' ), 'manage_options', 'vitalstack-email-check', 'vitalstack_mail_page' );
	}
);

function vitalstack_mail_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$msg = '';
	if ( isset( $_POST['vs_mail_test'] ) ) {
		check_admin_referer( 'vs_mail_test' );
		$to = sanitize_email( wp_unslash( $_POST['vs_mail_to'] ?? '' ) );
		if ( ! is_email( $to ) ) {
			$msg = __( 'Please enter a valid email address.', 'vitalstack' );
		} else {
			$body = '<p>' . esc_html__( 'This is a test email from your VitalStack site.', 'vitalstack' ) . '</p><p>' . esc_html__( 'If it arrived in your inbox (not spam), email delivery is working.', 'vitalstack' ) . '</p>' . vitalstack_email_button( home_url( '/' ), __( 'Open VitalStack', 'vitalstack' ) );
			$ok   = vitalstack_mail( $to, __( 'Test email from VitalStack', 'vitalstack' ), $body );
			/* translators: %s: email */
			$msg = $ok ? sprintf( __( 'Test email sent to %s.', 'vitalstack' ), $to ) : __( 'WordPress could not send the email. Check the SMTP plugin settings.', 'vitalstack' );
		}
	}
	if ( isset( $_GET['recheck'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification -- read-only refresh
		delete_transient( 'vs_mail_checks' );
	}
	$checks = vitalstack_mail_checks();
	set_transient( 'vs_mail_checks', $checks, 12 * HOUR_IN_SECONDS );
	$icon = array( 'ok' => '✅', 'warn' => '⚠️', 'bad' => '❌', 'info' => 'ℹ️' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Email check', 'vitalstack' ); ?></h1>
		<p><?php esc_html_e( 'Sign-up codes, receipts and lesson emails reach the inbox only when all of these are green. Fix the red ones first.', 'vitalstack' ); ?></p>
		<?php if ( $msg ) : ?><div class="notice notice-info is-dismissible"><p><?php echo esc_html( $msg ); ?></p></div><?php endif; ?>
		<table class="widefat striped" style="max-width:980px"><tbody>
			<?php foreach ( $checks as $c ) : ?>
				<tr>
					<td style="width:34px;font-size:18px"><?php echo esc_html( $icon[ $c[0] ] ); ?></td>
					<td style="width:150px"><b><?php echo esc_html( $c[1] ); ?></b></td>
					<td><code style="white-space:normal;word-break:break-all"><?php echo esc_html( $c[2] ); ?></code><?php if ( $c[3] ) : ?><p style="margin:6px 0 0"><b><?php esc_html_e( 'Fix:', 'vitalstack' ); ?></b> <?php echo esc_html( $c[3] ); ?></p><?php endif; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody></table>
		<p><a class="button" href="<?php echo esc_url( add_query_arg( 'recheck', 1 ) ); ?>"><?php esc_html_e( 'Check again', 'vitalstack' ); ?></a> <span class="description"><?php esc_html_e( 'DNS changes can take up to a few hours to show.', 'vitalstack' ); ?></span></p>
		<h2><?php esc_html_e( 'Send a test email', 'vitalstack' ); ?></h2>
		<p><?php esc_html_e( 'Tip: open mail-tester.com, copy the address it shows, send the test there, and it gives a spam score out of 10 with the exact problems. Aim for 9 or more.', 'vitalstack' ); ?></p>
		<form method="post">
			<?php wp_nonce_field( 'vs_mail_test' ); ?>
			<input type="email" name="vs_mail_to" class="regular-text" required placeholder="test-xxxx@srv1.mail-tester.com">
			<?php submit_button( __( 'Send test', 'vitalstack' ), 'primary', 'vs_mail_test', false ); ?>
		</form>
		<h2><?php esc_html_e( 'Also helps', 'vitalstack' ); ?></h2>
		<ul style="list-style:disc;padding-left:20px">
			<li><?php esc_html_e( 'Ask testers to mark your first emails "Not spam" and add the address to contacts.', 'vitalstack' ); ?></li>
			<li><?php esc_html_e( 'Start marketing emails slowly (a few dozen a day) so the new domain builds a good reputation.', 'vitalstack' ); ?></li>
			<li><?php esc_html_e( 'Use Google Postmaster Tools (postmaster.google.com) to watch your domain reputation with Gmail.', 'vitalstack' ); ?></li>
		</ul>
	</div>
	<?php
}

/* ── Dashboard warning while something important is red ───────────────── */

add_action(
	'admin_notices',
	function () {
		if ( ! current_user_can( 'manage_options' ) || ( isset( $_GET['page'] ) && 'vitalstack-email-check' === $_GET['page'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'plugins', 'settings_page_vitalstack-marketing' ), true ) ) {
			return;
		}
		$bad = array();
		foreach ( vitalstack_mail_checks_cached() as $c ) {
			if ( 'bad' === $c[0] ) {
				$bad[] = $c[1];
			}
		}
		if ( ! $bad ) {
			return;
		}
		echo '<div class="notice notice-warning"><p><b>' . esc_html__( 'Your emails may land in spam.', 'vitalstack' ) . '</b> ' . esc_html( sprintf( /* translators: %s: list */ __( 'Needs fixing: %s.', 'vitalstack' ), implode( ', ', $bad ) ) ) . ' <a href="' . esc_url( admin_url( 'tools.php?page=vitalstack-email-check' ) ) . '">' . esc_html__( 'Open Email check', 'vitalstack' ) . ' →</a></p></div>';
	}
);
