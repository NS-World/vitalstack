<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sky Shimmer Gradient Text · Restaurant</title><style>:root{--bg:#f0f7ff;--surface:#ffffff;--ink:#0b1b33;--muted:#53657d;--a1:#2563eb;--a2:#0891b2;--a3:#f97316;--line:rgba(0,0,0,.1);--on:#ffffff;--font:Georgia,"Iowan Old Style","Times New Roman",serif;--hw:700;--ls:-.02em;color-scheme:light}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}body{min-height:100vh;display:flex;flex-direction:column}main{flex:1;display:grid;place-items:center;text-align:center;padding:24px}h1{background:linear-gradient(100deg,var(--ink) 20%,var(--a1) 40%,var(--a2) 50%,var(--ink) 70%);background-size:250% 100%;-webkit-background-clip:text;background-clip:text;color:transparent;animation:sh 4s linear infinite}@keyframes sh{from{background-position:120% 0}to{background-position:-120% 0}}.links a{position:relative}.links a::after{content:"";position:absolute;left:0;right:0;bottom:-4px;height:2px;background:var(--a1);transform:scaleX(0);transform-origin:left;transition:transform .3s}.links a:hover::after{transform:scaleX(1)}.chips{display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin-top:28px}.chips span{padding:8px 14px;border-radius:99px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);font-size:14px;opacity:0;animation:in .6s forwards;animation-delay:calc(.4s + var(--i)*.12s)}@keyframes in{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}.sub{margin:0 auto}.row{justify-content:center}@media(prefers-reduced-motion:reduce){h1{animation:none}.chips span{animation:none;opacity:1}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Saffron Table</b><span class="links"><a href="#">Tasting menu</a><a href="#">Seafood</a><a href="#">Tandoor</a></span><a class="btn sm" href="#">Reserve a table</a></nav><main><div><p class="eyebrow">Saffron Table</p><h1>Old recipes, new table</h1><p class="sub">Coastal Indian food cooked over fire, with a menu that changes with the market.</p><div class="row"><a class="btn" href="#">Reserve a table</a></div><div class="chips"><span style="--i:0">Tasting menu</span><span style="--i:1">Seafood</span><span style="--i:2">Tandoor</span><span style="--i:3">Thalis</span><span style="--i:4">Desserts</span><span style="--i:5">Cocktails</span></div></div></main></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Sky Shimmer Gradient Text · Restaurant — build prompt (VitalStack Motion Studio)

## Goal
Build a text effect section for **Saffron Table**, a restaurant brand: A light sweep shimmers across the gradient restaurant headline while nav links get animated underlines.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Sky Daylight" as CSS custom properties: --bg #f0f7ff, --surface #ffffff, --ink #0b1b33, --muted #53657d, --a1 #2563eb (primary), --a2 #0891b2 (secondary), --a3 #f97316 (highlight). Light theme.
- Type: Editorial serif system stack (`Georgia,"Iowan Old Style","Times New Roman",serif`), headings weight 700, letter-spacing -.02em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Saffron Table
- Headline: “Old recipes, new table”
- Sub-copy: “Coastal Indian food cooked over fire, with a menu that changes with the market.”
- Primary button: “Reserve a table”; secondary: “See menu”
- Items: Tasting menu, Seafood, Tandoor, Thalis, Desserts, Cocktails
- Stats: 18 dishes; 1 wood fire; 4.7★ on maps
- Testimonial: “Every plate tastes like a holiday in Goa.” — Food & Travel Weekly

## Layout
- Top navigation: brand on the left, 3 links (Tasting menu, Seafood, Tandoor) and a small “Reserve a table” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. The headline uses background-clip:text with a 200%-wide gradient (--ink → --a1 → --a2 → --ink) animated across in 4s.
2. Navigation links draw an underline from left to right on hover via a scaleX transform on ::after.
3. Feature chips below fade in one by one.

## Requirements
- Responsive from 320 px to 1920 px with no horizontal scroll; test at 375, 768 and 1440 px.
- 60 fps: animate only transform, opacity and filter; batch JS work in requestAnimationFrame; pause canvas loops when the tab is hidden.
- Accessibility: semantic landmarks, real <a>/<button> elements, visible :focus-visible outlines, WCAG AA contrast, decorative layers aria-hidden="true".
- `prefers-reduced-motion: reduce` turns the motion off or into a calm static state with all content visible.
- Keep all colours and sizes in CSS variables so the design can be re-themed in one place.

## Acceptance checklist
- [ ] Looks and moves as described on desktop and phone.
- [ ] Zero console errors; works offline from the single file.
- [ ] Keyboard users can reach and use every control.
- [ ] Reduced-motion mode shows the full content without animation.
- [ ] Lighthouse accessibility score 95+.

Keywords: gradient text shimmer shine animated underline, restaurant
VSAI,
);
