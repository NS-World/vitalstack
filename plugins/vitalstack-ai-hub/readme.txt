=== VitalStack AI Hub ===
Requires at least: 6.2
Requires PHP: 7.4
Stable tag: 1.2.0
License: GPLv2 or later

A separate, 3D-styled AI section for VitalStack at /ai/.

== What it adds ==
* /ai/ – AI Hub home with a 3D animated orb and the four sections.
* /ai/library/ – AI Library: free legal resources (official sites, open books, free courses) and VitalStack's own guides (free and premium).
* /ai/commands/ – AI Commands & Shortcuts for ChatGPT, Claude, Gemini, GitHub Copilot, Cursor, Midjourney, Stable Diffusion, Perplexity, terminal agents and prompt power moves.
* /ai/prompts/ – Prompt Bazaar: 1,042 prompts in 25 categories (coding, careers, DSA, study, social media, business, image, video, music, data, agents and more), with the tool to use and the result you get. 30 per page, search, category pages.
* /ai/motion/ – Motion Studio: 500 animated designs (scroll stories, heroes, backgrounds, cards, pricing, FAQ, charts, loaders and more) with live previews, full code and detailed build prompts. 24 per page, search, category pages.
* /ai/pricing/ – AI Pro plans.
* An "AI Hub" button first in the main site's practice links (theme 4.20.8 or newer).

== AI Pro (premium) ==
Pro items (crown icon) unlock with an AI Pro plan: Monthly $9 / ₹399, Yearly $59 / ₹1,999, Lifetime $149 / ₹4,999. They are sold through the theme's Razorpay checkout (theme 4.20.9+), and prices can be changed in Settings → VitalStack Payments. The pricing page is /ai/pricing/.
Motion Studio copies: visitors get 2 free copies, free accounts 10 a day, AI Pro unlimited. Code and prompts are fetched from the server per copy and are never in the page.

== How the big catalogue is built ==
tools/aihub/gen_motion.py (34 original motion engines × 24 content themes × 18 palettes × 6 type styles) and tools/aihub/gen_prompts.py write includes/data/motion-gen.php, includes/data/motion-gen/<id>.php and includes/data/prompts-gen.php. Every generated design is browser-checked (no errors, no network, no sideways scroll on phone) by tools/aihub/check_motion.js, which also captures the Pro posters. Design code loads only for the full-screen view or a copy; the files exit when opened directly.

== Add your own items ==
wp-admin → AI Hub → Add AI Hub item. Pick the section, category, tool, premium on/off. Your items appear above the built-in ones. Image prompts: set a featured image to show the example result. Motion: paste a full HTML document (admins only); it always runs in a sandbox with no network access.

== Install ==
Plugins → Add New → Upload Plugin → vitalstack-ai-hub-1.1.0.zip → Activate. Then visit /ai/.
If /ai/ shows "not found", open Settings → Permalinks and click Save once.
