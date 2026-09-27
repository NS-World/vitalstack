# Theme v3 (tutorial-site layout + accounts): setup

v3 builds on v2. Everything in [THEME-V2-SETUP.md](THEME-V2-SETUP.md)
(pen name, fake authors, lesson order, About/Editorial pages, News
switches) still applies. Do that first, then the steps below.

## What's new in v3
- **Tutorial-site layout**: dark topic bar (HTML, CSS, JavaScript…), lessons
  sidebar on the left, big ❮ Previous / Next ❯ buttons, "On this page" on
  the right.
- **Homepage**: "Learn to Code" + search, then one coloured section per
  learning path with an example and **Try it Yourself** button.
- **Try it Yourself editor**: every runnable HTML/JavaScript example gets
  a button that opens a live editor. Code runs in a sandboxed frame that
  cannot touch the site, cookies or accounts.
- **Reader accounts** at `/account/`: sign up, sign in, dashboard with
  learning progress, email settings, profile.
- **Progress sync**: completed lessons are saved to the reader's account
  and appear on every device.
- **Email subscription**: subscribing requires an account. Readers must
  confirm their email before they get any mail.
- **New-post emails**: when you publish a post or tutorial, confirmed
  subscribers get an email (sent in batches of 40 every 5 minutes, with a
  one-click unsubscribe link).

## 1. Upload (5 min)
Take a Hostinger backup, then upload `vitalstack-theme-v3.zip` the same
way as before (Appearance → Themes → Add New → Upload → Replace). Then
**Settings → Permalinks → Save** once so `/account/` works.

## 2. Make email delivery reliable (15 min): required
WordPress's default mail often lands in spam or is blocked on shared
hosting. Without this, verification and new-post emails won't arrive.

1. hPanel → **Emails** → create `hello@vitalstack.co.in` (included in your plan).
2. WordPress → Plugins → Add New → install **WP Mail SMTP** (or *FluentSMTP*).
3. Mailer: **Other SMTP**. Host `smtp.hostinger.com`, port `465`, encryption
   `SSL`, username/password = the mailbox from step 1. From email = the same address.
4. Send the plugin's test email to your Gmail and check it arrives in the inbox.
5. hPanel → Domains → DNS: make sure **SPF**, **DKIM** and **DMARC** records
   exist for the domain (Hostinger adds SPF/DKIM automatically for its mail;
   add DMARC `v=DMARC1; p=none;` if missing).

Hostinger's mailbox has a daily sending limit. With 40 emails every 5
minutes, 1,000 subscribers take about 2 hours. When you pass a few
thousand subscribers, switch the SMTP plugin to a sending service like
Brevo or Amazon SES.

## 3. WP-Cron (5 min): recommended
New-post emails are sent by WP-Cron, which only runs when someone visits
the site. For reliable timing:
1. Add to `wp-config.php`: `define( 'DISABLE_WP_CRON', true );`
2. hPanel → Advanced → **Cron Jobs** → every 5 minutes:
   `wget -q -O - https://vitalstack.co.in/wp-cron.php?doing_wp_cron >/dev/null 2>&1`

## 4. Customizer (2 min)
Appearance → Customize → **VitalStack: Accounts & Email**:
- ✅ Allow readers to create accounts
- ✅ Email subscribers when a new post or tutorial is published

When publishing a post you *don't* want to announce (e.g. a small update),
tick **"Don't email subscribers"** in the post's sidebar box before publishing.
Updating an already-published post never sends emails.

## 5. Topic bar (optional)
By default the dark bar lists every tutorial (HTML, CSS, JavaScript…)
plus AI Guides / AI Tools / Careers. To control it yourself: Appearance →
Menus → create a menu → location **Topic Bar**. You can also rename a
tutorial's label in the bar with a custom field `subject_label` on that tutorial.

## 6. Caching (LiteSpeed Cache on Hostinger)
The theme marks `/account/` as non-cacheable, and signed-in readers are
never served cached pages. As a safety net, add `/account` to LiteSpeed
Cache → Excludes → **Do Not Cache URIs**.

## 7. Privacy Policy: add a paragraph
> **Accounts.** If you create an account we store your name, email address,
> hashed password, your email preferences and which lessons you have
> completed. We use your email only to confirm your address and, if you
> subscribe, to tell you about new lessons and guides. You can unsubscribe
> from any email in one click, or ask us to delete your account.

## 8. Test it yourself
1. In a private window: open a tutorial → **Subscribe free** → create an account.
2. Check your inbox for the confirmation email → click it.
3. Mark a lesson complete; open the site on your phone, sign in, and check it shows as done.
4. Publish a test tutorial → within ~5 minutes you should get the "New lesson" email.
   Then delete the test tutorial.

## Where readers show up
- **Users** list: readers have the *Subscriber* role; the **Email updates**
  column shows who is subscribed and confirmed.
- **Dashboard** widget "VitalStack subscribers" shows the confirmed count.
- Readers can never open wp-admin; they are sent to `/account/`.
