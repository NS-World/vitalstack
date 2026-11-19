=== VitalStack AI Hub ===
Requires at least: 6.2
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv2 or later

A separate, 3D-styled AI section for VitalStack at /ai/.

== What it adds ==
* /ai/ – AI Hub home with a 3D animated orb and the four sections.
* /ai/library/ – AI Library: free legal resources (official sites, open books, free courses) and VitalStack's own guides (free and premium).
* /ai/commands/ – AI Commands & Shortcuts for ChatGPT, Claude, Gemini, GitHub Copilot, Cursor, Midjourney, Stable Diffusion, Perplexity, terminal agents and prompt power moves.
* /ai/prompts/ – Prompt Bazaar: prompts by category and tool with the kind of result you get.
* /ai/motion/ – Motion Studio: 23 animated designs (scroll stories, galleries, heroes) with live previews, full code and detailed build prompts.
* /ai/pricing/ – AI Pro plans.
* An "AI Hub" button first in the main site's practice links (theme 4.20.8 or newer).

== AI Pro (premium) ==
Pro items (crown icon) unlock with an AI Pro plan: Monthly $9 / ₹399, Yearly $59 / ₹1,999, Lifetime $149 / ₹4,999. They are sold through the theme's Razorpay checkout (theme 4.20.9+), and prices can be changed in Settings → VitalStack Payments. The pricing page is /ai/pricing/.
Motion Studio copies: visitors get 2 free copies, free accounts 10 a day, AI Pro unlimited. Code and prompts are fetched from the server per copy and are never in the page.

== Add your own items ==
wp-admin → AI Hub → Add AI Hub item. Pick the section, category, tool, premium on/off. Your items appear above the built-in ones. Image prompts: set a featured image to show the example result. Motion: paste a full HTML document (admins only); it always runs in a sandbox with no network access.

== Install ==
Plugins → Add New → Upload Plugin → vitalstack-ai-hub-1.1.0.zip → Activate. Then visit /ai/.
If /ai/ shows "not found", open Settings → Permalinks and click Save once.
