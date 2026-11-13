=== VitalStack Growth Desk ===
Requires at least: 6.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-2.0-or-later

Selling, marketing and testing desk for the VitalStack theme.

== Description ==

* Offer (selling): landing offers in the style of a big sale. A deal card in the home page hero, a deal panel at the top of /pricing/ and a small card that slides in once every few days. A struck-through regular price and "Save X%" appear only for the regular prices you enter, and the countdown only for a real end date. The offer disappears by itself when it ends.
* Sales agent: paid orders, revenue in ₹ and $, started checkouts and the share paid, failed payments, refunds, best plans, coupons and the latest orders (buyer emails masked).
* Marketing agent: sign-ups, subscribers (and how many are confirmed), upcoming festivals from the Festive plugin, a share kit of ready-to-post messages with tracked links, and finding a reader to subscribe or unsubscribe them by hand.
* Testing & Security agent: every day it opens the key pages and checks payments and the webhook, email deliverability, search visibility, AdSense readiness, HTTPS, an "admin" username, two-factor sign-in for administrators, PHP version, updates and the code editor. It emails you only when something is red. Every Monday it sends a report of the week.

The agents are rule-based and run inside WordPress on a schedule. There is no AI service, no outside call (only the site opening its own pages) and no public endpoint: nothing here can be reached or steered by a visitor or a bot. Every screen is for administrators only and every form has a nonce.

Needs the VitalStack theme 4.19.0 or newer for the home and pricing hooks.

== Changelog ==

= 1.0.0 =
* First release.
