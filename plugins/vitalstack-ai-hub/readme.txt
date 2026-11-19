=== VitalStack AI Hub ===
Requires at least: 6.2
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv2 or later

A separate, 3D-styled AI section for VitalStack at /ai/.

== What it adds ==
* /ai/ – AI Hub home with a 3D animated orb and the four sections.
* /ai/library/ – AI Library: free legal resources (official sites, open books, free courses) and VitalStack's own guides (free and premium).
* /ai/commands/ – AI Commands & Shortcuts for ChatGPT, Claude, Gemini, GitHub Copilot, Cursor, Midjourney, Stable Diffusion, Perplexity, terminal agents and prompt power moves.
* /ai/prompts/ – Prompt Bazaar: 100,000 prompts in 31 categories, stored in the plugin's own database table for fast search. Category grid, search with suggestions, topic / AI tool / type / price filters, and "Fill in & copy" (type your details into a form, the prompt fills itself).
* /ai/motion/ – Motion Studio: 10,000 animated designs from 64 original effect engines (including 3D: cube, ring carousel, wireframe sphere, terrain, tunnel, globe, gyroscope, flipbook, depth fly-through), with a 3D showcase, live previews rendered at real desktop or phone size, full code and detailed build prompts.
* /ai/pricing/ – AI Pro plans.
* An "AI Hub" button first in the main site's practice links (theme 4.20.8 or newer).

== AI Pro (premium) ==
Pro items (crown icon) unlock with an AI Pro plan: Monthly $9 / ₹399, Yearly $59 / ₹1,999, Lifetime $149 / ₹4,999. They are sold through the theme's Razorpay checkout (theme 4.20.9+), and prices can be changed in Settings → VitalStack Payments. The pricing page is /ai/pricing/.
Motion Studio copies: visitors get 2 free copies, free accounts 10 a day, AI Pro unlimited. Code and prompts are fetched from the server per copy and are never in the page.

== First activation: the prompt import ==
The 100,000 prompts ship compressed in data/prompts/ and are imported into the table {prefix}vsai_prompts in 21 small background steps (about 15–70 seconds in total). An admin notice shows progress; browsing wp-admin speeds it up. Until it finishes the hub shows the built-in prompts. MySQL/MariaDB get a full-text index automatically.

== How the big catalogue is built ==
tools/aihub/gen_motion.py (64 original motion engines × 60 brand themes × 30 palettes × 6 type styles) writes includes/data/motion-gen.php (compact list) and data/motion/<shard>.php (compressed code + build prompts). tools/aihub/gen_prompts.py and gen_prompts_v2.py write includes/data/prompts-gen.php and data/prompts/part-NNN.php. Every generated design is browser-checked (no errors, no network, no sideways scroll on phone) by tools/aihub/check_motion.js, which also captures the Pro posters. Design code loads only for the full-screen view or a copy; the files exit when opened directly.

== Add your own items ==
wp-admin → AI Hub → Add AI Hub item. Pick the section, category, tool, premium on/off. Your items appear above the built-in ones. Image prompts: set a featured image to show the example result. Motion: paste a full HTML document (admins only); it always runs in a sandbox with no network access.

== Install ==
Plugins → Add New → Upload Plugin → vitalstack-ai-hub-1.1.0.zip → Activate. Then visit /ai/.
If /ai/ shows "not found", open Settings → Permalinks and click Save once.
