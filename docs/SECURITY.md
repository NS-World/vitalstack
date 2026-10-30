# VitalStack security checklist

## Already handled by the theme (v4.3.3+)

| Protection | What it does |
|---|---|
| Login lockout | 10 wrong passwords for one account in an hour locks that account for an hour (wp-login.php, the theme's sign-in form and app passwords). A password reset unlocks it straight away. |
| Same error for wrong user / wrong password | Attackers can't use the login form to find out which usernames exist. |
| No user listing | `/wp-json/wp/v2/users` is hidden from visitors, `?author=1` scans are redirected, oEmbed no longer shows the author URL. |
| XML-RPC off | `xmlrpc.php` (a favourite brute-force target) is switched off. It stays on automatically if Jetpack is active. |
| File editor off | Appearance → Theme File Editor and Plugins → Plugin File Editor are disabled, so a stolen admin session can't run PHP. |
| Security headers | `X-Content-Type-Options`, `X-Frame-Options` (no clickjacking), `Referrer-Policy`, `Permissions-Policy`, `Cross-Origin-Opener-Policy`, and HSTS on HTTPS. |
| Spoof-proof rate limits | Sign-up and contact-form limits also count the real connecting IP, so a faked `X-Forwarded-For` header can't bypass them. Cloudflare is detected properly. |
| Forms | Every form has a nonce, every database query is prepared, unsubscribe links are signed. |
| Sign in with an email code (OTP) | 6-digit code, valid 10 minutes, used once, 5 tries max; only a keyed hash is stored; 5 codes per email per hour, 60 s apart; the reply never reveals whether an email has an account; administrators must use their password. |
| Premium lessons | Readers without access get a short preview only, on the page, in feeds and in the REST API; locked lessons are kept out of search. |
| Strong sign-up passwords | At least 8 characters; common passwords (12345678, password123…) and the email name are refused. |
| Password-reset flood limit | At most 3 reset emails per account per hour. |
| No application passwords | Scripts can't sign in with long-lived keys that skip the login form. |
| HTTPS-only content | `Content-Security-Policy: upgrade-insecure-requests` and no framing by other sites. |
| Readers kept out of wp-admin | Reader accounts are sent to /account/ and never see the admin bar. |
| XP can't be farmed | Visualizer/tool XP has a daily cap, so the leaderboard stays fair. |

## Encryption: what protects what

| Data | How it is protected |
|---|---|
| Everything between visitor and site | HTTPS (TLS) from Hostinger's SSL, plus HSTS so browsers never fall back to http. |
| Passwords | Never stored. WordPress keeps only a one-way bcrypt hash (WordPress 6.8+). |
| Email-confirmation links | Random 32-character token; only its SHA-256 hash is stored, expires after 7 days. |
| Unsubscribe links | HMAC-SHA256 signature with the site's secret keys. |
| Login cookies | Signed with the secret keys in wp-config.php, HttpOnly, Secure on HTTPS. |

To make every existing login cookie invalid (for example after a suspected break-in), change the secret keys: hPanel → WordPress → Security, or paste new keys from https://api.wordpress.org/secret-key/1.1/salt/ into wp-config.php. Everyone just signs in again.

## Do these once in Hostinger / WordPress (10 minutes)

1. **Two-factor login for admins.** Install *Two Factor* (by WordPress.org contributors) and turn on an authenticator app for every admin.
2. **Strong, unique admin password**, and don't use `admin` as a username. Delete old admin/editor accounts you don't use.
3. **Backups.** hPanel → Websites → Backups: check that daily backups are on. Download one copy a month.
4. **HTTPS everywhere.** hPanel → Security → SSL: make sure SSL is active, then turn on **Force HTTPS**.
5. **Updates.** Dashboard → Updates: keep WordPress core and plugins up to date (turn on auto-updates for plugins). Delete plugins and themes you don't use.
6. **Malware scanner.** hPanel → Security → Malware Scanner: keep it on.
7. **Block PHP in uploads.** In File Manager, create `wp-content/uploads/.htaccess` containing:

   ```
   <FilesMatch "\.(php|phtml|php\d)$">
     Require all denied
   </FilesMatch>
   ```
8. **No directory listing.** Add `Options -Indexes` at the top of the main `.htaccess` (public_html).
9. **wp-config.php** permissions: 600 or 640 (File Manager → right-click → Permissions).

## Screenshots and copying

No website can stop screenshots: they are taken by the phone or computer, outside the browser. Tricks that block right-click or text selection annoy real readers, break code copying, and are a negative signal for Google. VitalStack does the useful part instead:

- When someone copies a long paragraph of lesson text, the copied text automatically ends with `Source: <lesson title> – <link>`, so reposted content points back to you. Code blocks copy clean.
- Certificates and the PDF gift carry the site name and a verify link.
- If someone republishes your lessons, file a DMCA removal with Google: https://support.google.com/legal/answer/3110420

## Added in 4.6

| Area | Protection |
|---|---|
| Sign-up | Account is created only after a 6-digit email code. Until then the password is held only as a WordPress hash, for at most 10 minutes. |
| Sign-in | Two-step: password, then an emailed code. Codes are never stored (only an HMAC keyed with the site's secret salt), work once, expire in 10 min, 5 tries, 5 codes/email/hour, 60 s between codes. |
| Trusted devices | Random 40-character token in an HttpOnly, Secure, SameSite cookie; only its HMAC is stored. 30 days, max 5 per account, all forgotten on password change/reset. |
| wp-login.php | Readers can't use it to skip the code; staff still can (keep a 2FA plugin for admins). |
| Payments | Price set by the server; access only after Razorpay's HMAC-SHA256 signature checks out; webhook signed with its own secret and amount/currency checked; one grant per order (database-unique lock); only the buyer can complete their order; rate limits on order and verify. |
| Payment keys | Best in wp-config.php. If typed in the settings page, secrets are encrypted (libsodium secretbox, or AES-256-GCM) with a key derived from the wp-config salts, and never shown again. |
| Browser permissions | `Permissions-Policy` allows the Payment Request API only for the site itself and Razorpay's checkout. |
