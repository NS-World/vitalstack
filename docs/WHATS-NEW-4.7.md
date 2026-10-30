# VitalStack 4.7: what's new and how to switch it on

Existing pages and features work as before. Everything below is an addition.

## 1. Premium trailer (25-second animation)
- An animated story on **/pricing/** and in the homepage **Career tracks** band. In it, Dev learns Python, Pandas, statistics and machine learning, then earns a certificate, followed by the price and a button.
- It is made of HTML and CSS, not a video file, so it adds almost nothing to page weight. It plays only while on screen, can be paused, and shows as still pictures for readers who turn animations off.
- **Video files for social media** are in the repository folder `promo/`:
  - `vitalstack-premium-trailer-vertical.mp4`: 720×1280, for Instagram Reels, YouTube Shorts and Stories.
  - `vitalstack-premium-trailer-wide.mp4`: 1280×720, for YouTube and Facebook.
  - Both are silent: add trending music inside Instagram or YouTube when you post.

## 2. Practice checkout (test payments today, without Razorpay)
- **Settings → VitalStack Payments → tick "Practice checkout" → Save.**
- Open `/pricing/` while signed in as admin. You see a 🧪 banner and **Get it now** buttons. Click one and a practice payment window opens with three choices:
  - **Pay (practice):** your access turns on, a receipt email is sent, the order appears under **Orders** marked "practice", and the ⭐ Premium card shows on your account page.
  - **Make it fail:** shows the failure message a real buyer would see.
  - **Cancel:** closes the window.
- To test as a normal reader: create a second account, then put its email in **Test buyer emails**.
- Everyone else sees the normal page. Untick practice checkout when you go live.
- To test real Razorpay in test mode: sign up at razorpay.com. Test keys are available straight away, without KYC. Paste them in the same settings page, choose Test mode, and tick **Sales open** (see `docs/PAYMENTS-SETUP.md`).

## 3. Tools for data and AI learners (free, run in the browser)
- **Python online compiler:** now runs NumPy, Pandas, Matplotlib, Seaborn, SciPy and scikit-learn. Charts appear under the output. A new **Examples** menu has Pandas, NumPy, chart and machine-learning examples.
- **A/B Test Significance Calculator** (`/tools/ab-test-calculator/`): lift, p-value and confidence interval.
- **Sample Size & Confidence Interval Calculator** (`/tools/sample-size-calculator/`).
- **CSV Data Explorer** (`/tools/csv-data-explorer/`): paste or upload a CSV and get `describe()`, missing values, outliers, histograms and a correlation matrix. Data never leaves the browser.
- **AI Prompt Builder** (`/tools/prompt-builder/`): builds and scores prompts for ChatGPT, Claude and Gemini.
- **To create the pages:** Appearance → VitalStack Setup → **Run setup**. Existing pages are skipped.

## 4. Study buddy on lessons
- Halfway through a long lesson, a crew character pops up with a quick recall question about the section just read ("Can you explain *Conditional probability* in one sentence?"). Answering in your own words is one of the most effective ways to remember.
- It shows once per lesson, hides itself after 12 seconds, and readers can choose **Don't show these tips**.
- Switch it off for the whole site in **Customize → VitalStack: Header & Footer → Study buddy**.
