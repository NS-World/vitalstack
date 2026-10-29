# VitalStack security checklist

## Already handled by the theme (v4.3.0+)

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
