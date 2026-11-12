# What's new in 5.0.0-dev (DEV environment)

5.0 is a **development build** for a separate DEV site. It is built on top of 4.12.0 and keeps
everything from it, including the normal `::viz` animations and the films and 3D stage switched
off by default. Nothing in 4.12 was removed.

Zip: `vitalstack-theme-v5.0.0-dev.zip`

## 1. DEV / UAT / PRODUCTION environments

New in **Customize → Site Identity → "This copy of the site is"**: Automatic, PRODUCTION, UAT or
DEV.

On UAT and DEV copies the theme:

- sends `noindex, nofollow` on every page, as a meta tag and as an `X-Robots-Tag` header;
- serves a robots.txt that blocks all crawlers, and turns off the WordPress sitemap;
- removes the AdSense code and every ad unit;
- shows a small ribbon such as **DEV · 5.0.0-dev** at the top, plus a matching admin-bar label.

This keeps test copies from showing up in Google as duplicate content, which could hurt the real
site's ranking and its AdSense review.

**Automatic** treats these addresses as test copies:

- `*.hostingersite.com`, `localhost`, `*.local` and `*.test`;
- addresses starting with `dev.`, `staging.` or `uat.`;
- `WP_ENVIRONMENT_TYPE` set to `development` or `staging` in wp-config.php.

On the live site, admins now see a red warning if **practice checkout (demo payments)** is still
on.

## 2. Free courses (no premium needed)

| Course | Lessons |
|---|---|
| 🛡️ **Cyber Safety for Everyone** | 10: passwords and 2FA, phishing, UPI/OTP scams, fake calls and "digital arrest", safe browsing and Wi-Fi, phone security, privacy, malware and backups, what to do if you are scammed (1930, cybercrime.gov.in, Sanchar Saathi) |
| 📱 **Social Media: The Hidden Side** | 6: how feeds hook you, what apps know, algorithms and misinformation, mental health (Tele-MANAS 14416), scams and fake influencers, taking back control |

Every lesson has a quiz. The quiz bank now has 1,413 questions, and no lesson is without a quiz.

## 3. Free Datasets Hub (`/datasets/`)

There are 8 practice datasets for data analysts on one page:

- retail sales, HR, student performance and bank transactions (with fraud);
- customer churn, city weather and AQI, food delivery and house prices.

Each dataset has:

- **CSV and JSON download** buttons;
- a column guide, pandas starter code to copy, a data preview and practice questions;
- deliberate real-world mess: duplicates, missing values and outliers.

## 4. Twelve new free tools (`/tools/…`)

All of them run in the browser; nothing typed is sent anywhere. Each has a 250–400 word guide
below it, which also gives AdSense more content.

| Tool | What it does |
|---|---|
| 🧭 Diagram Maker | Type `A -> B` and get a flowchart. Drag boxes, 4 colour themes, 5 templates, export PNG/SVG, copy as Mermaid. |
| 🔐 Password Generator | Passphrases or random passwords, with an honest strength meter. |
| 🪪 JWT Decoder | Header and payload, issue and expiry dates, a sample token. |
| 🔁 Base64 / URL encoder | Unicode-safe. |
| 🧾 Hash Generator | SHA-1, 256, 384 and 512. |
| ⏱️ Timestamp Converter | IST, UTC and ISO. |
| 🗓️ Cron Explainer | Plain English plus the next 5 runs. |
| 🆚 Text Diff | Line-by-line differences. |
| 🔢 Token Counter | AI tokens and cost estimate. |
| 🎨 Colour Contrast Checker | WCAG AA/AAA results. |
| 🆔 UUID Generator | Up to 100 at once. |
| 🛡️ Link Safety Checker | Spots fake bank domains, look-alikes, shorteners, `.apk` downloads and more, without opening the link. |

## 5. Games and Study Music

**Games page:**

- livelier look: animated hero and coloured game tiles;
- category filter: Algorithms, Coding, AI, Data and Safety;
- 3 new games:
  - 🚨 **Scam or Safe?**: 20 real-looking Indian SMS, calls and DMs;
  - 🔎 **Data Detective**: find the missing value, duplicate, outlier or wrong format;
  - ⌨️ **Code Typer**: type real Python, SQL and Java lines and get a WPM score.

**Study Music page (`/study-music/`):**

- 5 stations: Lo-fi Focus, Rainy Night, Deep Space, Café Chill and Brown Noise;
- all music is composed live in the browser (no audio files, no data use);
- visualiser, mixer (music, beat, rain, vinyl), Pomodoro timer (25/5/15) with a chime and a daily
  count;
- a guide to studying with music;
- linked from the topic bar (🎧 Study Music) and from the Games page.

### 🏃 Bug Runner (also in 4.12.1)

An original endless runner starring Byte, the crew's orange cat with green headphones:

- Byte runs along a glowing line of code at night, past a parallax skyline of server towers.
- Jump over 🐞 bugs and **500** server racks, dodge purple flying glitches, and collect ☕
  (+25) and `{ }` (+10).
- **Controls:** tap, Space or ↑ to jump. Hold to jump higher, tap again in the air for a double
  jump, press ↓ to land fast and Esc to pause.
- Sound is off by default. The best score is kept on the device, and the game pauses when the tab
  is hidden.
- **Where it appears:**
  - **Games page:** featured at the top. Scores give XP.
  - **404 page:** Byte says "Meow… I searched every folder", with "Lost? Play while you are here"
    below.
- **Phones and laptops:** works from 320 to 1920px, with touch and keyboard.

## 6. Premium: combo packs and premium themes

- **Combo Pack** card on the pricing page with two packs at ₹1,299 / $25 for 12 months (₹2,499 before 4.13's lower prices):
  - AI Builder: Gen AI + Agentic;
  - Data + AI: Data Scientist + Gen AI.

  Buying a combo grants both tracks. You can change the price in Settings → VitalStack Payments
  like any other plan. Pro Yearly (all tracks, ₹1,499 from 4.13) remains the best value.
- **6 premium colour themes**: Midnight Neon, Deep Ocean, Sakura, Forest, Sunset and Hacker
  Terminal. They have an animated header line, which stays still for readers who turn off
  motion. Any premium reader can switch theme from the palette button. Free readers can preview
  a theme for 20 seconds, with an "Unlock" link to pricing.

## 6b. Signature Projects: premium projects you won't find elsewhere

The existing portfolio projects (churn, house prices, chat with PDFs) are useful, but similar
projects are everywhere. **Signature Projects** are new, unique to VitalStack, built on our own
Indian datasets, and every code output is checked by the lesson checker.

**Data Scientist track: `ds-signature`, 4 projects**

1. **UPI Fraud Radar:**
   - 3,000 payments, 1.4% fraud. Shows why accuracy lies on rare events.
   - The alert threshold is chosen by rupee cost: ₹1.75 lakh lost becomes ₹15,699 total cost.
2. **The 30-Minute Promise:**
   - Cleans impossible delivery times.
   - An explainable ETA model: +3.2 min per km, +10 min in rain, +8 min in peak hour.
   - An ETA buffer chosen from past errors.
3. **Clean-Air Alert:**
   - Fills missing AQI readings per city and maps them to the national AQI bands.
   - Explains the winter pattern.
   - Shows honestly that a model does not beat the "same as last reading" baseline, then designs
     an early warning that misses only 5 of 103 bad-air readings.
4. **Fair Pay and Attrition Audit:**
   - Fills missing salaries by peer group.
   - Adjusted pay gap: remote staff are paid fairly. Pay is not linked to performance.
   - Overtime triples attrition.
   - Compares two retention options in rupees, with every assumption written down.

**Gen AI Developer track: `ai-signature`, 2 projects**

5. **Scam SMS Shield with Claude:**
   - Explainable rules score perfectly on their own messages but catch 0 of 4 new Hinglish scams,
     which proves the need for a held-out test set.
   - Personal data is masked before any API call.
   - Claude gives a JSON verdict, with "unsure" as an option and a safe fallback.
6. **Kirana Restock Agent:**
   - Restock maths with lead time and safety stock.
   - Claude gets read-only tools; ordering needs the owner's approval and a budget limit, both
     enforced in code.
   - A dry run without a model to compare against.

Both premium tracks list the new modules. Quizzes are included (1,431 questions; no lesson is
without a quiz). The Claude code was also run end to end against the real `anthropic` SDK using a
local mock server.

## 6c. AdSense readiness notice (admins only)

The Dashboard and Pages screens now list any page AdSense reviewers expect that is:

- missing;
- still a draft;
- still contains a `[your …]` placeholder.

Each item links straight to the page. Legal pages are still created as drafts on purpose, so read
them before publishing. The notice only reminds you; it never publishes anything.

## 7. Fixes

- **Header on laptops (960–1380px)**: the six practice links pushed the header 35–290px past
  the screen edge. They now appear in the top bar only from 1380px; on smaller screens they stay
  in the scrolling topic bar.
- Cron explainer stays fast for rare schedules (it skips whole days and hours that cannot match).
- **From the 8 Nov pre-PROD audit (also in 4.12.3):**
  - Draft legal pages are no longer linked, so there are no 404 links.
  - The admin login name is no longer shown in author links, oEmbed or RSS.
  - The theme's REST routes refuse cross-site CORS.
  - The AI City island fits 320px phones.
  - After signing up from "Join the waitlist", the track page nudges "one last tap".
  - See `docs/releases/PREPROD-AUDIT-2026-11-08.md`.
- **From 4.13 (12 Nov):**
  - Lower launch prices: Monthly ₹199, Track ₹999, Pro Yearly ₹1,499, Lifetime ₹3,999, Combo ₹1,299.
  - Invite friends: friends get 10% off; the inviter earns Pro days.
  - AI training crawlers are blocked in robots.txt, with Google and AdSense allowed.
  - Temporary-email sign-ups are refused.
  - Copied lesson text carries a source link.
  - On phones, the All-courses panel shows one course per row.
  - **4.13.1:**
    - ₹ for India and $ elsewhere, set by the server from the CDN country or the device time zone
      (Settings → Currency);
    - USD prices $9 / $29 / $49 / $129 (combo $39);
    - on phones, every course name fits on one line.
  - **4.13.2:**
    - all sales final (refund window 0, with matching pricing and policy wording);
    - days left on account and track pages, and a renewal note in the last 7 days;
    - launch-offer line on plan cards (earlier price struck, Save %, per-month price).
  - See `docs/releases/4.13-PLANS-INVITES-RAZORPAY.md`.

## After installing on the DEV site

1. Upload `vitalstack-theme-v5.0.0-dev.zip` (Appearance → Themes → Add New → Upload).
2. Customize → Site Identity → set **This copy of the site is: DEV**.
3. Appearance → VitalStack Setup → run it, which creates these pages and paths:
   - `datasets`
   - `study-music`
   - the 12 new tool pages
   - the two free course paths
   - the two Signature Projects paths (`ds-signature`, `ai-signature`)
4. Import `content/import/vitalstack-all-content.xml` (Tools → Import → WordPress) for the 16 new
   lessons.
5. Check: the ribbon shows **DEV · 5.0.0-dev** and the page source contains `noindex`.

Never point the live domain at this build until it has been tested and set to PRODUCTION.
