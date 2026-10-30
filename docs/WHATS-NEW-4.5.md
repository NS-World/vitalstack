# VitalStack 4.5: what's new and how to switch it on

Everything that existed before works exactly as it did. These are additions.

## 1. Sign in with an email code (OTP)
- /account/ has a third tab, **Email code**. Enter an email, get a 6-digit code, enter it: you're signed in. New email? The same code creates the account (email is confirmed automatically).
- Password sign-in and sign-up are unchanged.
- Codes are sent with the site's normal email (make sure WordPress email works; an SMTP plugin is recommended on Hostinger).

## 2. Focus sounds on lessons
- A **🎧 Focus sounds** button on every lesson. Gamma 40 Hz or Beta 14–30 Hz, headphones (binaural) or speakers (pulse), optional rain noise, 25/50-minute timer.
- Nothing plays until the reader presses Play. No audio files are downloaded.

## 3. New free courses (32 lessons, 96 quiz questions, 5 mock tests)
NumPy (6), Pandas (8), Data Visualization (5, with 17 real charts), Machine Learning (5), Prompt Engineering (8). Every code example was run and its output checked.

**To publish them:** Appearance → VitalStack Setup →
1. **Run setup** once (creates the 5 new mock test pages; existing pages are skipped).
2. **Import starter content** → choose *Schedule* (2–3 per day is good for Google) or *Draft*. Only the 32 new lessons are added; existing lessons are skipped.

## 4. Premium career tracks
- `/premium/data-scientist/` and `/premium/gen-ai-developer/`, linked from a new homepage band.
- Free modules link to the free courses; premium modules are shown locked. Readers can **join the waitlist** (Dashboard → "VitalStack premium waitlist" shows the count).
- **Give someone access:** Users → Edit user → *VitalStack premium access* → tick the track → Update.
- Payments are not connected yet. When you have a Razorpay (or other) account, the purchase can tick that same box automatically.
- When premium lessons are written, put them in a learning path and set that path's term meta `vs_premium` to the track slug; readers without access then see a preview and a lock.
