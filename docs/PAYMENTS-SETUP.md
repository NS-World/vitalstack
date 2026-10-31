# Taking payments with Razorpay

The theme does the website side: pricing page, orders, signature checks, giving access and emailing receipts. You need a Razorpay account for the money side.

## 1. Create and activate the account
1. Sign up at razorpay.com with your business/individual details and complete **KYC** (PAN, bank account; GST only if you are registered).
2. Razorpay reviews your website before activation. Make sure these pages exist and are linked in the footer: **Privacy Policy, Terms, Contact, Refund/Cancellation policy** and **Pricing** (`/pricing/`). For digital courses, say in the refund page that there is no physical shipping.
3. Wait for "Account activated". Until then you can use **Test mode**.

## 2. Test first (no real money)
1. Razorpay Dashboard → switch to **Test Mode** → Account & Settings → **API Keys** → Generate. You get a *Key ID* (`rzp_test_...`) and a *Key Secret*.
2. WordPress → **Settings → VitalStack Payments**:
   - Mode: **Test**, paste the test Key ID and Key Secret.
   - Tick **Sales open** and Save.
3. Webhook: Razorpay Dashboard → **Webhooks** → Add New:
   - URL: copy it from the settings page (it ends in `/wp-json/vitalstack/v1/pay/webhook`).
   - Secret: type a long random password, and paste the same one into **Webhook Secret** in WordPress.
   - Events: `payment.captured`, `order.paid`, `payment.failed`, `refund.processed`, `payment.dispute.created`.
4. Sign in to your site as a normal reader (not admin), open `/pricing/`, buy a plan with Razorpay's test card or test UPI (`success@razorpay`). You should land on the track page with "Payment received", get a receipt email, and see the order under **Orders**.

## 3. Go live
1. In Razorpay **Live Mode**, generate live API keys.
2. Settings → VitalStack Payments → Mode **Live**, paste the live Key ID and Secret, Save.
3. Add the same webhook again in Live Mode (webhooks are separate for test and live).
4. Buy the cheapest plan yourself once with real money, then refund it from the Razorpay Dashboard.

## 4. US dollar ($) payments
Razorpay accepts international cards only after you enable **International Payments** (Dashboard → Account & Settings → International payments; Razorpay may ask for extra documents). Until then, a $ purchase shows a friendly error suggesting ₹.

## Safest place for the keys (optional)
Instead of the settings page, you can put the keys in `wp-config.php` (above "That's all, stop editing!"):

```php
define( 'VITALSTACK_RZP_KEY_ID', 'rzp_live_xxxxxxxx' );
define( 'VITALSTACK_RZP_KEY_SECRET', 'xxxxxxxxxxxxxxxx' );
define( 'VITALSTACK_RZP_WEBHOOK_SECRET', 'your-webhook-secret' );
```

Keys typed in the settings page are stored encrypted with a key from your `wp-config.php` salts. If you ever change those salts, re-enter the secrets.

## Refunds and giving access by hand
- Refund from the Razorpay Dashboard, then remove access: Users → Edit user → *VitalStack premium access* → untick → Update.
- Give access without payment (a scholarship, a friend): tick the same box.

## How it is kept safe
- Prices come from the server; the browser only names the plan.
- Access is given only after Razorpay's HMAC-SHA256 signature checks out (browser reply and webhook), and only once per order, even if both arrive.
- Only the reader who started an order can complete it. The webhook also checks the amount and currency paid.
- Card and UPI details never touch your site; Razorpay handles them.
