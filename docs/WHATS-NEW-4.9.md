# VitalStack 4.9: real premium modules, 3D code stage, keep-learning features

Everything from 4.8 works as before. These are additions, plus one bug fix and three security fixes.

## Bug fixed: Lifetime buyers saw 🔒 on track pages
- **What happened:** after buying Lifetime, only Statistics opened. Every other module on the track pages still showed 🔒.
- **Why:** the plan was given correctly: Lifetime includes all three tracks. But at that point Statistics was the only premium module with written lessons. The track page showed 🔒 on unwritten modules for everyone, buyers included.
- **Now, for buyers:**
  - Ready modules show **✅ Unlocked** with an "Open the module" button.
  - Modules still being written show **🕒 Coming soon · included**, with the note "It unlocks for you automatically when it is published, at no extra cost."
  - The top of the page says how many premium modules are ready, for example "1 of 11 premium modules are ready now."
- **Visitors** still see ⭐ Premium · Ready and 🔒 Premium.

## New premium modules (real lessons, tested code)
| Track | Module | Lessons |
|---|---|---|
| Gen AI Developer | **How LLMs Work** (`llm-basics`) | Tokens and next-token prediction · Embeddings · Attention · Context windows, temperature and sampling · Hallucinations |
| Agentic AI Engineer | **AI Agents: Basics** (`ai-agents`) | The plan → act → observe loop · Workflows vs agents · Your first agent in 40 lines |

- Every track now has at least one ready premium module: Statistics, How LLMs Work and AI Agents.
- Every lesson has:
  - Runnable Python (no API key needed).
  - A line-by-line walkthrough.
  - An interactive animation: the new *attention* and *agent loop* animations.
  - A 3-question quiz per lesson.
- All code outputs are checked by `tools/check_lesson_code.py`. Walkthroughs can now use functions defined earlier in the same lesson.
- **To put them on the site:**
  1. Appearance → VitalStack Setup → **Run setup**. This creates the two new learning paths and marks them premium.
  2. **Import starter content**. Only the 8 new lessons are added; nothing existing changes.
  3. Publish them.

## New: 🧊 3D code stage
- Every line-by-line walkthrough that has variables or printed output gets a **🧊 3D view** button.
- **What the stage shows:**
  - Each variable is a wooden crate with its name and value. A crate that changes drops in and glows.
  - Printed text appears on a 3D monitor.
  - The current line floats on a flip-card.
  - The crew character (Dev, already approved) walks to whatever just changed.
- **How it's built:**
  - Pure CSS 3D. No new library, no AI images.
  - Works in light and dark mode and from 320px phones up.
  - Respects "reduce motion".
- The reader's choice is remembered.

## New: features that keep visitors learning longer
- **🎯 Daily goal (15 minutes):**
  - A small ring on lesson pages counts *active* learning minutes. It only counts while the tab is visible and the reader did something in the last 90 seconds.
  - At 15 minutes the reader gets a celebration and a "come back tomorrow" nudge.
  - It is stored in the browser only, with no server writes.
- **⏭ Up next:**
  - After "Mark complete", a card shows the next lesson with a 10-second countdown. It also works at the end of a course, where it goes to the next course.
  - **Stay here** cancels it. Scrolling or a key press pauses it.
  - It only counts down while the tab is visible.

## Security fixes (found in the 4.9 audit)
1. **Premium quiz answers were in the page.** Quiz questions, answers and explanations of premium lessons were printed for everyone. They now render only for readers with access.
2. **Mock tests for premium paths** showed every question with its answer. They are now empty for readers without access.
3. **Daily challenge** (public) could pick a question from a premium path. It now uses free paths only.

## Audit checklist (all passed)
- **Forms (admin-post):**
  - Every form checks a nonce.
  - Admin actions also check capabilities.
  - Login, sign-up, codes, contact and 2FA are rate-limited.
- **REST API:**
  - Payment order, verify, lesson, step-up and devices need a signed-in user.
  - Quote is public but rate-limited (30/hour per IP), so coupon codes can't be guessed.
  - The webhook checks the HMAC signature and logs bad attempts.
  - XP is capped per day.
- **Premium text never leaves the server for non-buyers:**
  - Page, feed, `/wp-json/wp/v2/tutorials`, quizzes, mock tests and the daily challenge.
  - Meta and OG descriptions use 30 words at most.
- **Cookies:** device, code and trust cookies are HttpOnly + Secure + SameSite=Lax. `vs_auth` (only "1", for cache busting) is readable on purpose.
- **Hardening:**
  - Security headers: nosniff, frame, referrer, permissions and HSTS on HTTPS.
  - XML-RPC off.
  - User enumeration blocked.
  - Redirects validated with `wp_validate_redirect`.

## For AdSense approval
Premium lessons are `noindex` and gated, so AdSense judges the site on the **free** pages. To get past the "low value content" review:
- Keep publishing **free** lessons on a schedule (VitalStack Setup → publish a few per day). Aim for 30+ substantial free pages before re-applying.
- Make sure About, Contact, Privacy Policy, Terms and Refund Policy are published and linked in the footer.
- See docs/ADSENSE-APPROVAL.md for the full checklist.
