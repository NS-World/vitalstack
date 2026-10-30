# VitalStack 4.6: what's new and how to switch it on

Existing lessons, pages and features keep working. Two things change behaviour, because you asked for them: the sign-up/sign-in flow (section 2) and the old "Email code" tab, which is gone.

## 1. Topic bar: every course is reachable
- When the bar has more courses than fit the screen, **‹ ›** arrows appear. Mouse-wheel scrolling also moves it sideways.
- The course you are reading is scrolled into view automatically.
- A new **☰ All courses** button (right end of the bar) opens a panel with every course grouped (Web, Programming & DSA, Java backend, Data & AI), with lesson counts, the premium tracks and the pricing page. It closes with Esc, the ✕, or a click outside.

## 2. Email codes at sign-up and sign-in
- **Create account:** name, email and password, then a 6-digit code is emailed. The account is created **only after** the right code is entered. The email is then confirmed, so no confirmation link is needed. The welcome email still includes the free gift PDF.
- **Sign in:** after the right password, a code is emailed and must be entered (two-step sign-in). "Trust this device for 30 days" skips the code on that browser. A password reset forgets all trusted devices.
- Codes: 6 digits, valid 10 minutes, 5 tries, "Send a new code" after 60 s, at most 5 codes per email per hour.
- Readers can no longer skip the code by signing in on `wp-login.php`. Staff (editors and admins) can still use `wp-login.php` as before.
- **Important:** this needs working email. Test it on the live site by creating a test account. If codes don't arrive, go to **Appearance → Customize → VitalStack: Accounts & Email** and untick the two "code" boxes: sign-up and sign-in then work exactly like 4.5.

## 3. Payments (Razorpay) and the /pricing/ page
- New page **/pricing/** with 4 plans in ₹ and $ (switch at the top; India is detected automatically):

| Plan | ₹ | $ | Gives |
|---|---|---|---|
| All-Access Monthly | 499 | 12 | both tracks, 1 month |
| Data Scientist Track | 2,999 | 59 | 12 months |
| Gen AI Developer Track | 2,999 | 59 | 12 months |
| All-Access Yearly (best value) | 4,999 | 99 | both tracks, 12 months |

- One-time payments (no auto-renewal). Buying again before the end adds the time on.
- Prices, the refund window (default 7 days, shown on the page) and keys are in **Settings → VitalStack Payments**.
- **Sales are closed by default.** Until you open them, the page shows the prices with "Join the waitlist" buttons. See `docs/PAYMENTS-SETUP.md` for the Razorpay steps.
- Orders appear under **Orders** in the WordPress menu. Readers see their access on their account page ("⭐ Premium" card).

## 4. First premium module: Statistics and Probability
- 6 lessons, 4 new live animations (mean vs median, Bayes, central limit theorem, p-value), 2 charts and 18 quiz questions. Every code output was produced by really running it.
- It is a new learning path **Statistics**, marked premium for the Data Scientist track. Readers without access see the first two paragraphs and a lock box ("Unlock this lesson" / "See the track"). Search engines see the preview only, and these pages are `noindex`, so they don't count as thin content.
- **To publish:** Appearance → VitalStack Setup → **Run setup** (creates the Statistics path, marked premium), then **Import starter content** (only the 6 new lessons are added).
- You and other staff always see full premium lessons.

## 5. What stays free
All existing free courses stay free and indexable (that is what Google AdSense reviews). Premium only adds the deeper career material on top.
