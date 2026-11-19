# AI Hub 1.2.0: 1,042 prompts and 500 motion designs

## What changed
- **Prompt Bazaar:** 1,000 new prompts (1,042 total) in 25 categories.
  - 300 are Pro (crown icon); the rest are free.
  - Table-driven sections carry accurate details per item:
    - **Coding:** 12 languages × 9 tasks, each with that language's real test framework, doc style, linter and project framework.
    - **Careers:** 30 roles × 5 tasks.
    - **DSA:** 25 patterns × 2.
    - **Study:** 15 subjects and 12 exams, with each exam's real sections.
    - **Social media:** 8 platforms × 6, with platform limits such as 280 characters on X, 500 on Threads, and Pinterest title/description lengths.
    - **Business:** 20 industries × 4.
    - **Image:** 20 styles × 3, with Midjourney, SDXL and ChatGPT Images syntax.
  - Hand-written sections cover sales, marketing, writing, video, music, data, productivity, finance, health, HR, teaching, product, AI agents, travel, YouTube, website builders, cybersecurity and DevOps.
- **Motion Studio:** 477 new designs (500 total).
  - Built from 34 original motion engines combined with 24 brand themes, 18 palettes and 6 type styles.
  - Each design is one HTML file with no network, a reduced-motion version, and a detailed build prompt.
  - Every design is browser-tested on desktop and phone: no errors, no network requests, no sideways scroll.
  - 318 Pro posters.
- **Paged lists:**
  - 30 prompts or 24 designs per page.
  - Search, category and Free/Pro filters live in the URL (`?q=`, `?cat=`, `?price=`, `?pg=`).
  - Search runs on the server and covers all categories.
  - Pro prompt text is never searched or shown to non-members.
- **SEO:**
  - Every category has its own page, title and description (e.g. "Coding AI prompts – Prompt Bazaar"), and all of them are in the sitemap (49 AI Hub URLs).
  - Search and price-filter pages are `noindex, follow`.
  - Canonical URLs keep the category and page number.
  - A page number past the end, or an unknown category, gets a 301 redirect.
- **Performance and security:**
  - Generated design code loads only for the full-screen view or a copy.
  - The design files return nothing when opened directly, so Pro code cannot be downloaded from the plugin folder.
  - The copy limits (2 free copies for visitors, 10 a day for free accounts, unlimited for AI Pro) and the rate ceilings are unchanged.

## Tests
- `tests/aihub.e2e.js`: 46/46. This covers the old 30 checks plus 16 new ones for counts, paging, category pages, server search, noindex, Pro-text protection, end-of-list redirects, generated free and Pro designs, direct file access, the sitemap and phone layout.
- `tools/aihub/check_motion.js`: 477/477 designs pass.
- Semgrep (WordPress rules): 0 findings.

## Deploy
Plugins → upload `vitalstack-ai-hub-1.2.0.zip` (replace) → purge LiteSpeed → open `/ai/prompts/` and `/ai/motion/`. Theme unchanged (4.20.9).
