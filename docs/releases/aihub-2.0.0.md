# AI Hub 2.0.0: 100,000 prompts and 10,000 motion designs

## Fixed
- **Pricing showed only "₹0".** If the active theme is older than 4.20.9 (or a stale cache serves an old page), the AI Pro plans were not registered with the checkout, so only the Free card appeared.
  - Plans now always show with their real prices: Monthly ₹399 / $9, Yearly ₹1,999 / $59, Lifetime ₹4,999 / $149.
  - A blank or zero saved price never displays as ₹0.
  - Admins see a notice if the theme cannot sell yet; buyers see "Opening soon" instead of a broken button.
- **Motion previews looked wrong on many screens.** Previews are now rendered at a real desktop size (1280×800) and scaled to fit each tile and the detail panel, so every screen sees the design as it was made. The detail panel has a Desktop / Phone toggle.

## Prompt Bazaar: 100,000 prompts
- **Catalogue:** 98,958 new prompts (100,000 total) in 31 categories.
  - Each prompt is a task template written for one job, applied to a specific subject: 30 programming languages with their real tooling, 60 frameworks, 180 roles, 366 industries, 230 social niches across 8 platforms, 440 destinations, 665 study topics, 118 image styles × 164 subjects, and more.
  - About 30% are Pro.
- **Storage:** the prompts live in their own database table (`{prefix}vsai_prompts`).
  - MySQL and MariaDB get a full-text index; other databases fall back to indexed LIKE search.
  - Measured on MariaDB with 100,000 rows: list pages ≈ 0.05–0.09 s, search ≈ 0.05 s, suggestions ≈ 0.03 s.
- **Import:** 21 background steps on first activation (about 12 s on MariaDB, 66 s on SQLite).
  - An admin notice shows progress.
  - The hub keeps working with the built-in prompts meanwhile.
- **Finding a prompt:**
  - a big search box with live title suggestions;
  - a category grid with counts;
  - topic and AI-tool filters, type chips (Text, Image, Video, Audio, Code) and Free/Pro;
  - "Clear filters".
- **Fill in & copy:** every [PLACEHOLDER] in a prompt becomes a text box; the preview fills itself as the reader types, then copies the finished prompt.
- **Pro protection:** Pro prompt text is never sent to non-members and is never searchable.

## Motion Studio: 10,000 designs
- **Designs:** 9,977 new designs (10,000 total) from 64 original effect engines, 60 brand themes, 30 palettes and 6 type styles.
- **30 new engines:**
  - 3D: cube, ring carousel, wireframe sphere, wave terrain, light tunnel, dotted globe, floating shapes, isometric blocks, gyroscope, flipbook, depth fly-through;
  - effects: liquid goo, spinning text badge, expanding strips, line-draw icons, slot-machine numbers;
  - navigation and UI: full-screen menu morph, shrinking header, toast stack, animated buttons, skeleton loader, loader collection, testimonial wall, sliding tabs, before/after slider, event countdown;
  - extras: cursor trail, typing terminal, sparkle headline, conic aurora.
- **Every design:** one HTML file with no network requests, a reduced-motion version and a detailed build prompt.
  - All designs are browser-checked on desktop and phone: no errors, no network requests, no sideways scroll.
  - 1,274 Pro poster sets.
- **Storage:** code and prompts are compressed in 128 shards (about 6 MB) and loaded only for the full-screen view or a copy. The data files return nothing when opened in a browser.
- **New page features:**
  - a 3D showcase ring of featured designs;
  - stats ("10,000 animated designs");
  - one AI Pro band in the list for non-members.

## Selling (honest, no dark patterns)
- Pro locks and the AI Pro band show the real price ("from ₹399/month, no auto-renewal").
- A career-track band at the end of the Home, Prompts and Motion pages sells the premium courses with their live prices (Agentic AI Engineer, Gen AI Developer, Data Scientist).

## SEO and AdSense safety
- **Indexed:** only category landing pages (page 1).
- **Not indexed:** searches, narrow filters (topic, tool, type, price) and deeper pages are `noindex, follow`.
- **Sitemap:** the 31 prompt categories and the motion categories.
- **No pages per prompt or design:** none were created, so the site does not publish tens of thousands of templated pages, which Google's spam policies and AdSense reviewers treat as low-value.

## Security
- Every database query is prepared; filter values are validated against known lists.
- The suggest API is read-only, returns titles only and is rate-limited (600 requests per hour per visitor).
- Data files exit when opened directly.
- Designs run in a sandboxed iframe with a strict CSP.
- Semgrep (WordPress rules): 0 findings.

## Tests
- `tests/aihub.e2e.js`: 58 checks, run on both SQLite and MariaDB.
- `tools/aihub/check_motion.js`: every generated design.
