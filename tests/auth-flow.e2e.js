// 4.20.2: deep sign-up / sign-in / sign-out / one-device / 2FA test. node t4202.js
const { chromium } = require('playwright');
const { execFileSync } = require('child_process'); const fs = require('fs');
const S = 'http://localhost:8095';
const SITE = process.env.WP_SITE || '../wp/site'; // path to a local WordPress with the mail-catch mu-plugin
const MAIL = SITE + '/wp-content/mail.log';
const EMAIL = 'authflow@example.test', PW = 'Zq8!vRt3#Lm6yWp', NAME = 'Auth Flow';
let pass = 0, fail = 0; const ok = (c, m) => { c ? pass++ : fail++; console.log((c ? 'PASS ' : 'FAIL ') + m); };
const php = (code) => execFileSync('php', ['-r', 'require "wp-load.php"; ' + code], { cwd: SITE }).toString().trim();
const resetLimits = () => php('global $wpdb; $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE \'%vs_otp_gap_%\' OR option_name LIKE \'%vs_rl_%\'");');
const mailLen = () => (fs.existsSync(MAIL) ? fs.readFileSync(MAIL, 'utf8').length : 0);
const lastCode = (from) => { const m = fs.readFileSync(MAIL, 'utf8').slice(from).trim().split('\n').filter(Boolean).map(JSON.parse).filter(x => String(x.to).includes(EMAIL)); return m.length ? (m.pop().message.match(/>(\d{6})</) || [])[1] : null; };
const notice = async (p) => ((await p.locator('.notice[role=status]').allTextContents()).join(' | '));
const signedIn = (p) => p.evaluate(() => document.body.classList.contains('logged-in'));
const nav = (p, fn) => Promise.all([p.waitForNavigation({ waitUntil: 'load' }), fn()]);
const WIN = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0 Safari/537.36';
const IOS = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Mobile/15E148 Safari/604.1';

async function passwordStep(p) {
  resetLimits();
  await p.goto(S + '/account/?tab=login', { waitUntil: 'load' });
  const f = p.locator('form:has(input[name="log"])').first();
  await f.locator('input[name="log"]').fill(EMAIL);
  await f.locator('input[name="pwd"]').fill(PW);
  await nav(p, () => f.locator('button[type="submit"]').click());
}
async function submitCode(p, code, trust) {
  if (trust === false) { const t = p.locator('input[name=trust]'); if (await t.count()) await t.uncheck(); }
  // The page submits by itself once 6 digits are typed; click only if it did not.
  const url0 = p.url(); const navP = p.waitForNavigation({ waitUntil: 'load', timeout: 4000 }).catch(() => null);
  await p.fill('input[name=code]', code);
  const r = await navP;
  if (!r) await nav(p, () => p.locator('form.otp-verify button[type=submit]').click());
}
async function signOut(p) {
  await p.goto(S + '/account/', { waitUntil: 'load' });
  await nav(p, () => p.locator('a.dash-signout').first().click());
}

(async () => {
  php(`$u = get_user_by("email", "${EMAIL}"); if ($u) { require_once ABSPATH . "wp-admin/includes/user.php"; wp_delete_user($u->ID); } echo "clean";`);
  const b = await chromium.launch(); const errs = [];
  const A = await b.newContext({ userAgent: WIN, viewport: { width: 1280, height: 800 } });
  const p = await A.newPage(); p.on('pageerror', e => errs.push(e.message));

  // ── Sign-up ──
  resetLimits();
  await p.goto(S + '/account/?tab=register', { waitUntil: 'load' });
  const rf = p.locator('form:has(input[name="action"][value="vs_register"])');
  ok(await rf.locator('input[name=subscribe]').isChecked(), 'sign-up: email box starts ticked (auto-subscribe)');
  await rf.locator('input[name=display_name]').fill(NAME);
  await rf.locator('input[name=email]').fill(EMAIL);
  await rf.locator('input[name=pwd]').fill(PW);
  let m0 = mailLen();
  await nav(p, () => rf.locator('button[type=submit]').click());
  ok(await p.locator('#panel-code h2').textContent() === 'Confirm your email', 'sign-up: "Confirm your email" code screen');
  ok(/Almost done/.test(await notice(p)), 'sign-up: success message "Almost done… code emailed"');
  ok(/a•+@example\.test/.test(await p.locator('.otp-sent-to').textContent()), 'sign-up: masked email shown');
  ok(php(`echo email_exists("${EMAIL}") ? "yes" : "no";`) === 'no', 'sign-up: account not created before the code');
  const c1 = lastCode(m0); ok(!!c1, 'sign-up: code email sent');
  await submitCode(p, c1 === '000000' ? '111111' : '000000');
  ok(/isn’t right/.test(await notice(p)) && await p.locator('#panel-code').count() === 1, 'sign-up: wrong code -> error, code screen stays');
  await submitCode(p, c1);
  ok(await signedIn(p), 'sign-up: signed in after the right code');
  ok(/Welcome to VitalStack/.test(await notice(p)), 'sign-up: welcome success message');
  ok(php(`$u=get_user_by("email","${EMAIL}"); echo get_user_meta($u->ID,"vs_subscribed",true).get_user_meta($u->ID,"vs_email_verified",true);`) === '11', 'sign-up: subscribed + email verified');

  // ── Sign out, then sign in: password + code again ──
  await signOut(p);
  ok(!(await signedIn(p)), 'sign-out: signed out');
  ok(!(await A.cookies()).some(c => c.name === 'vs_td'), 'sign-out: trusted-browser cookie removed');
  m0 = mailLen(); await passwordStep(p);
  ok(await p.locator('#panel-code h2').textContent() === 'Two-step sign-in', 'sign-in after sign-out: asks for the email code (bug fixed)');
  ok(/Password correct/.test(await notice(p)), 'sign-in: "Password correct… code emailed" message');
  ok(!(await signedIn(p)), 'sign-in: not signed in before the code');
  await submitCode(p, lastCode(m0)); // trust stays ticked (default)
  ok(await signedIn(p) && /You are signed in/.test(await notice(p)), 'sign-in: right code -> signed in + success message');

  // ── Sign out again with "trust" ticked last time: still needs the code ──
  await signOut(p);
  m0 = mailLen(); await passwordStep(p);
  ok(await p.locator('#panel-code').count() === 1, 'trusted browser + sign-out: code asked again');
  await submitCode(p, lastCode(m0));
  ok(await signedIn(p), 'signed in again');

  // ── Session ran out (no sign-out) on a trusted browser: no code (expected) ──
  await A.clearCookies({ name: /^wordpress_/ }).catch(async () => { const keep = (await A.cookies()).filter(c => !/^wordpress_/.test(c.name)); await A.clearCookies(); await A.addCookies(keep); });
  await passwordStep(p);
  ok(await signedIn(p) && await p.locator('#panel-code').count() === 0, 'session expired on trusted browser (no sign-out): signs in without a code');

  // ── One device at a time: B signs in, A is signed out and must use code ──
  const B = await b.newContext({ userAgent: IOS, viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true });
  const q = await B.newPage(); q.on('pageerror', e => errs.push(e.message));
  m0 = mailLen(); await passwordStep(q);
  ok(await q.locator('#panel-code').count() === 1, 'device B: asks for the code');
  const sw = await q.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth + 1);
  ok(sw, 'device B (phone): code screen fits, no sideways scroll');
  await submitCode(q, lastCode(m0), false);
  ok(await signedIn(q), 'device B: signed in');
  ok(await q.locator('[data-vss="here"]').count() === 1, 'device B: popup says the other device was signed out');
  await p.goto(S + '/account/', { waitUntil: 'load' });
  ok(!(await signedIn(p)), 'device A: signed out by device B');
  ok(await p.locator('[data-vss="out"]').count() === 1, 'device A: popup explains why');
  m0 = mailLen(); await passwordStep(p);
  ok(await p.locator('#panel-code').count() === 1, 'device A signing back in: code asked (its trust was dropped)');
  await submitCode(p, lastCode(m0), false);
  ok(await signedIn(p), 'device A: signed in again');
  await q.goto(S + '/', { waitUntil: 'load' });
  ok(!(await signedIn(q)), 'device B: now signed out');

  // ── Errors ──
  await signOut(p); resetLimits();
  await p.goto(S + '/account/?tab=login', { waitUntil: 'load' });
  let f = p.locator('form:has(input[name="log"])').first();
  await f.locator('input[name=log]').fill(EMAIL); await f.locator('input[name=pwd]').fill('wrong-password-1');
  await nav(p, () => f.locator('button[type=submit]').click());
  ok(/don’t match/.test(await notice(p)) && !(await signedIn(p)), 'wrong password -> clear error, not signed in');
  m0 = mailLen(); await passwordStep(p);
  for (let i = 0; i < 5; i++) await submitCode(p, '00000' + i);
  ok(/expired or was tried too many/.test(await notice(p)), '5 wrong codes -> "expired / too many tries" error');
  m0 = mailLen(); await passwordStep(p);
  await nav(p, () => p.locator('form:has(input[value=vs_otp_reset]) button').click());
  ok(await p.locator('#panel-code').count() === 0 && await p.locator('input[name=log]').count() > 0, 'Cancel -> back to the sign-in form');
  m0 = mailLen(); await passwordStep(p); const old = lastCode(m0);
  resetLimits(); m0 = mailLen();
  await p.evaluate(() => document.querySelectorAll('[data-resend-wait]').forEach(x => { x.disabled = false; })); // skip the 60 s countdown
  await nav(p, () => p.locator('form:has(input[value=vs_code_resend]) button').click());
  ok(/New code sent/.test(await notice(p)), 'resend -> "New code sent" message');
  const neu = lastCode(m0);
  if (old !== neu) { await submitCode(p, old); ok(/isn’t right/.test(await notice(p)), 'old code no longer works after resend'); }
  await submitCode(p, neu, false);
  ok(await signedIn(p), 'newest code signs in');

  // ── wp-login.php refuses a reader's password alone ──
  await signOut(p);
  await p.goto(S + '/wp-login.php', { waitUntil: 'load' });
  await p.fill('#user_login', EMAIL); await p.fill('#user_pass', PW);
  await nav(p, () => p.click('#wp-submit'));
  ok(!(await p.evaluate(() => document.cookie)).includes('vs_auth') && /account page/.test(await p.locator('#login_error').textContent().catch(() => '')), 'wp-login.php: reader is sent to the account page (no code skip)');

  // ── Authenticator app (2FA) ──
  php(`$u=get_user_by("email","${EMAIL}"); $bin=random_bytes(20); update_user_meta($u->ID,"vs_totp_secret",vitalstack_pay_seal(vitalstack_b32_encode($bin))); update_user_meta($u->ID,"vs_totp_on",1); file_put_contents(sys_get_temp_dir()."/vs_totp.bin",$bin);`);
  m0 = mailLen(); await passwordStep(p);
  ok(/authenticator app/.test(await notice(p)) && await p.locator('#panel-code').count() === 1, '2FA: asks for the authenticator code');
  ok(lastCode(m0) === null, '2FA: no email code sent');
  await submitCode(p, '000000');
  ok(/isn’t right/.test(await notice(p)), '2FA: wrong app code -> error');
  const totp = php(`$b=file_get_contents(sys_get_temp_dir()."/vs_totp.bin"); echo vitalstack_totp_at($b, (int) floor(time()/30));`);
  await submitCode(p, totp, false);
  ok(await signedIn(p) && /You are signed in/.test(await notice(p)), '2FA: right app code -> signed in + success message');

  ok(errs.length === 0, 'no JS errors ' + errs.join(' | '));
  await b.close();
  php(`$u=get_user_by("email","${EMAIL}"); if ($u) { require_once ABSPATH . "wp-admin/includes/user.php"; wp_delete_user($u->ID); }`);
  console.log(`${pass}/${pass + fail}`); process.exit(fail ? 1 : 0);
})().catch(e => { console.log('CRASH', e.message); process.exit(2); });
