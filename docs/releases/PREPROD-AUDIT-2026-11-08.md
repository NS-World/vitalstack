# Pre-PROD audit (8 Nov 2026) and the 4.12.3 release

**Question:** can the UAT theme go to PROD, with payments and premium on a waitlist?

**Answer: yes, deploy 4.12.3** (4.12.2 from UAT plus the small fixes below), after the site settings in
"Before you deploy".

The waitlist needs no code change. It is a setting: **Settings → VitalStack Payments → Sales: Open**
unticked. UAT already runs this way:

- every plan shows "Join the waitlist";
- there are no buy buttons;
- the order API answers `403 closed`.

## What was tested

| Area | How | Result |
|---|---|---|
| Every page | Crawled all 792 internal pages on UAT | No PHP errors and no 500s. 16 broken links: 3 real (draft legal pages, fixed in 4.12.3), the rest are example links inside code blocks. |
| Responsive | 44 page types × 12 widths (320–1920px) + all 456 lessons at 390 and 1366px, on UAT | 1 bug: homepage AI City island 2–5px too wide at 320–360px (fixed). Lessons: 0 issues. |
| Functions, logged out | 65 checks on UAT, phone and laptop | 65/65:<br>• menu, search, All-courses panel and finder, theme switch;<br>• lesson quiz, Try-it editor, Explain code;<br>• all 10 tools (Python runs `print`, SQL returns a table, and so on);<br>• 13 games, Bug Runner, mock test, daily challenge, AI dictionary, AI City;<br>• 404 and empty search;<br>• pricing in waitlist mode;<br>• no JS errors. |
| Functions, logged in | 25 checks on a real local WordPress running the theme | 25/25:<br>• sign-up with email code;<br>• waitlist join, and a forged join refused;<br>• order API refused while sales are closed;<br>• lesson progress saved, kept after reload and returned by the API;<br>• premium lesson locked;<br>• sign out and generic wrong-password message;<br>• admin waitlist count. |
| Speed | Phone on slow 4G with a 4× slower CPU, and laptop; 10 page types | Phone LCP 1.2–2.0 s, laptop 0.4–0.8 s (good is under 2.5 s). CLS at most 0.061 (good is under 0.1). |
| Security (read-only) | Headers, files, REST, XSS, redirects, premium content | See below. |
| Regression | Earlier suites on 4.12.3 and 5.0 | 1041, 141, 189, 356 browser checks and all PHP unit tests pass. |

## Security

**Already in place:**

- **Private REST routes:** every one answers 401 without login:
  - progress and XP;
  - pay order and verify;
  - security checklist and step-up;
  - lesson and device switch.
- **Webhook and quote:** the webhook and quote reject bad input.
- **Premium content:** locked on the page, in the REST API (teaser only) and in RSS (teaser only).
- **Reflected XSS:** 15 parameters tried with `<script>` and `<svg onload>`. Nothing reflected.
- **Redirects:** no open redirect. `redirect_to` is validated.
- **Blocked:**
  - `xmlrpc` (405);
  - users REST (404);
  - `?author=1` (redirects home);
  - `.env`, `.git`, backups and `debug.log` (403);
  - theme PHP files opened directly return an empty page.
- **Login:** 10 wrong passwords per account per hour locks it. Sign-up uses an emailed code.
- **Headers:** HSTS, X-Frame-Options, nosniff, Referrer-Policy and Permissions-Policy.

**Found and fixed in 4.12.3:**

1. **The admin login name was public.** UAT shows `/author/admin/`, and oEmbed returns `"author_name":"admin"`.
   - Author links for administrators now go to About Us, or the home page.
   - `/author/<login>/` redirects there.
   - If the display name is still the login name, the public site shows "VitalStack Team"
     (bylines, author box, RSS, oEmbed).
2. **CORS:** WordPress echoes any `Origin` on REST. The theme's own `/vitalstack/` routes now refuse
   other sites. This was defence in depth: they already needed a nonce.

## Bugs fixed in 4.12.3

| # | Bug | Fix |
|---|---|---|
| 1 | Sign-up form "Terms / Privacy Policy" and blog "How we write and fact-check" linked to draft pages (`?page_id=20`, `=3`, `=18`), which are 404 for visitors. This was on 139 + 13 pages. | Linked only while the page is published; otherwise shown as plain text. The empty "Legal" footer column is hidden too. |
| 2 | Homepage AI City island was wider than its card on 320–360px phones (page scrolled sideways). | Smaller island and emoji on phones, clipped to the card. Checked at 9 widths. |
| 3 | Admin login name visible (see Security). | As above. |
| 4 | A visitor who taps "Join the waitlist" must sign up, but then lands back **not** on the waitlist. They have to tap again, and most will think they already joined. | After sign-up the track page shows "👉 One last tap: join the waitlist…" with a highlighted button. |

The same fixes are in 5.0.0-dev.

Files: `vitalstack-theme-v4.12.3.zip`. `docs/releases/4.12.3-hotfix.patch` is the whole change from
4.12.0. The zip and the patch were checked to match file for file.

## Before you deploy (site settings, not code)

1. **Never copy the UAT database or settings to PROD.**
   - UAT has *Settings → Reading → Discourage search engines* on. Its pages say `noindex`, and its
     robots.txt blocks Googlebot.
   - Upload only the theme zip. On PROD, check that this box is **off**.
2. **Payments → waitlist:** *Settings → VitalStack Payments*:
   - **Sales: Open**: unticked;
   - **practice checkout**: unticked;
   - Pro Yearly price: check it (UAT has ₹4,999; the theme default is ₹2,999).
3. **Legal pages:** AdSense needs them published.
   - Publish About Us, Privacy Policy, Terms, Disclaimer, Cookie Policy and Editorial Policy.
   - Fill the "[your …]" placeholders.
   - The footer shows only published pages.
4. **Email:** sign-up sends a 6-digit code by email.
   - After deploying, sign up once on PROD with your own email.
   - If no code arrives (check spam), set up SMTP (Hostinger email + an SMTP plugin). Otherwise nobody
     can register.
5. **Your public name:** *Users → Profile → Display name publicly as* → your pen name. Otherwise
   articles show "VitalStack Team". Real author names help AdSense review.
6. **Cache:** HTML is cached in browsers for 7 days (`max-age=604800`).
   - In LiteSpeed/hPanel CDN, keep long browser caching for CSS, JS and images only.
   - Purge everything after the deploy.
7. **Optional:**
   - delete `readme.html` and `license.txt`;
   - turn off `X-Powered-By`;
   - if you don't use Hostinger Reach email marketing, turn it off. Its `cdn-reach.hostinger.com`
     script loads on every page.

## Deploy steps

1. hPanel → Backups → back up PROD.
2. Appearance → Themes → Add New → Upload `vitalstack-theme-v4.12.3.zip` → **Replace current with
   uploaded**.
3. Settings → VitalStack Payments: confirm sales closed (step 2 above).
4. LiteSpeed Cache → Purge All; hPanel → CDN → Purge.
5. **Smoke test, 5 minutes:**
   - **Pricing:** `/pricing/` shows "Join the waitlist".
   - **Waitlist:** tap it, sign up with your email and enter the code. The track page asks "One last
     tap", then says "You're on the waitlist".
   - **Phone checks:**
     - `/tools/python-online-compiler/`: Run prints output;
     - `/games/`: Bug Runner starts;
     - `/abc-xyz/`: shows Byte and the game.
   - **Laptop:** at 1024px, "Sign up" is fully visible.
   - **Robots:** View source on the home page. It must **not** contain `noindex`.
6. Then send the AdSense review request (once the legal pages are live).
