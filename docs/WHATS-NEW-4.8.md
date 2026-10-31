# VitalStack 4.8: premium security design, phase 1–3 (+ code walkthroughs)

Built from the "Premium plans: secure payments and protected content" design (v1.0). Free lessons, tools, games and every existing page work as before.

## 1. Line-by-line code walkthroughs (new animation, alongside the old ones)
- A **▶ Line by line** player under code examples:
  - Each line types itself in and a pointer moves to it.
  - A crew character explains the line, with a **Simple / Deep dive** switch.
  - A variables panel lights up what changed, and printed output appears in a console.
  - Tap any line to jump to it, use ←/→ on a keyboard, and choose the speed.
- Already in 3 Statistics lessons (standard deviation, Bayes, a p-value by simulation) and as a demo on /pricing/.
- Writers add one with `::walk python | Title … ::notes … ::end` in a lesson file (see `tools/lessons_to_wxr.py`). The code checker runs the walkthrough and refuses it if the "out:" notes don't match what the code really prints.

## 2. Plans, prices and payments (§3, §8)
| Plan | ₹ | $ | Gives |
|---|---|---|---|
| Pro Monthly | 399 | 9 | every track, 1 month |
| Career Track (pick one) | 1,999 | 39 | one track, 12 months |
| Pro Yearly ★ | 2,999 | 69 | every track, 12 months |
| Founder Lifetime | 6,999 | 149 | every track for life, first 200 buyers |

- **New track: Agentic AI Engineer** (13 modules, syllabus from §4). All-access plans include every track automatically.
- **Coupons** (Settings → VitalStack Payments → Coupons). One per line: `CODE | plans | 20% or fixed ₹ | max uses | expiry`. Examples:
  - `LAUNCH | all-year | 1999 | 0 | 2026-12-31`
  - `STUDENT20 | * | 20% | 500 |`
  - Coupons are checked only on the server.
- **Invoice numbers** (`VS/2026-27/0001`) on every receipt.
- **Refunds and disputes:** when Razorpay reports a refund or dispute, the access from that order is removed automatically and you get an email. Add the events `refund.processed` and `payment.dispute.created` to your Razorpay webhook.
- **Orders list** shows how many premium lessons each buyer opened, so the 7-day / 30% refund rule is easy to check.
- **No ads** for premium readers, anywhere on the site.
- Not built yet: UPI AutoPay monthly subscriptions, GST tax-invoice PDF, and daily reconciliation. These need your Razorpay account (Subscriptions) and a CA's GST advice.

## 3. Account security (§7, §9)
- **Buy checklist.** Before checkout opens, the reader must have:
  - Confirmed their email.
  - Subscribed to course updates.
  - Passed a security check in the last 15 minutes.

  Each missing step is fixed in one tap in the window that opens.
- **Authenticator app 2FA:**
  - Set up on the account page (Two-step security), with Google or Microsoft Authenticator.
  - 10 one-time backup codes.
  - Sign-in asks for the app code instead of emailing one, and "Email me a code instead" still works.
  - 5 wrong codes give a 15-minute pause and an email to the owner.
- **Staff:** if an admin turns the app on, wp-login.php asks for its code. Every staff sign-in from a new device emails an alert.
- **My devices** (account page): see devices, sign one out, or sign out everywhere.

## 4. Premium content protection (§10)
- **Paying readers:**
  - The lesson page no longer contains the premium text. The body is fetched from a protected API that checks plan, suspension, device and reading speed every time, and is sent with no-store caching.
  - **1 phone + 1 computer** per account. A third device shows "Use this device instead?" and a quick security check, and the old one is signed out (at most 3 switches in 30 days).
  - **Speed limit:** more than 40 different premium lessons in an hour, or 200 in a day, pauses premium until a security check. You get an alert.
- **Watermarks.** The visible one shows the reader's name, ID and date faintly across the lesson. The invisible one is zero-width characters hidden in the text that carry the reader ID, and they survive copy-paste.
- **Copy, select, right-click and print** are off for premium lesson text. Code blocks keep their Copy button.
- **Tools → VitalStack Security:**
  - Activity log (180 days).
  - **Find who leaked a lesson:** paste leaked text and it names the account.
  - **Suspend** an account in one click, which signs out every device.
- Staff always see lessons directly, with no limits.

## 5. Popups that never cover each other
- Bottom popups now stack neatly instead of overlapping, from 320px phones to big monitors: cookie notice, install-app pill, focus-sounds pill, end-of-lesson nudge, celebration, toast and study buddy.
- The install-app pill waits until the cookie notice is answered.

## Fixes in 4.8.1 and 4.8.2
- 4.8.1: the site logo grew to fill the whole page on some screens. The premium watermark had reused the logo's CSS class name; the watermark now has its own name. The new data-tool tables got their own class too.
- 4.8.2: premium lessons failed to load for paying readers on PHP 8 (the lesson API's ID check called a PHP function with the wrong number of arguments).

## After uploading
1. Appearance → VitalStack Setup → **Run setup**. This creates the draft **Refund and Cancellation Policy** page. Review and publish it, and link it in the footer menu.
2. Paste the Terms and Privacy additions below into your live pages.
3. Settings → VitalStack Payments: check prices and add coupons. Practice checkout lets you test everything, including the checklist and the security check.
4. Account page → Two-step security → set up your authenticator app (strongly recommended for the admin account).

## Add to your Terms of Service (§14)
> **Premium licence.** A Premium plan is a personal, non-transferable licence for one person. Sharing your account, or copying, recording or republishing premium content, is not allowed. Premium lessons carry visible and invisible marks that identify the account, and we keep an access log. Accounts that share access or content may be closed without a refund.

## Add to your Privacy Policy (DPDP Act 2023)
> **Security data.** To protect accounts and premium content we keep: a random device identifier (a cookie), the type of device and browser, IP address, and a log of sign-ins and premium lessons opened. We use them only for security, fraud prevention and refunds, and delete the log after 180 days. You can ask to see, correct or delete your data by emailing us.
