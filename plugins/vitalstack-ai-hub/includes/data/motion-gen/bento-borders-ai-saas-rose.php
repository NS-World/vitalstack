<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Rose Bento Grid Glow · AI SaaS</title><style>:root{--bg:#14060c;--surface:#250d18;--ink:#fff0f5;--muted:#cf9fb4;--a1:#fb7185;--a2:#f472b6;--a3:#fda4af;--line:rgba(255,255,255,.12);--on:#ffffff;--font:"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif;--hw:700;--ls:-.025em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}main{max-width:1100px;margin:0 auto;padding:36px 20px 60px}h2{font-size:clamp(28px,4.4vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:0 0 24px;max-width:18ch}.grid{display:grid;grid-template-columns:repeat(4,1fr);grid-auto-rows:170px;gap:14px}.big{grid-column:span 2;grid-row:span 2}.wide{grid-column:span 2}@property --ang{syntax:"<angle>";inherits:false;initial-value:0deg}.bt{position:relative;isolation:isolate;padding:24px;border-radius:22px;display:flex;flex-direction:column;justify-content:flex-end;transition:transform .3s}.bt:hover{transform:translateY(-4px)}.bt::before{content:"";position:absolute;inset:0;border-radius:22px;padding:1.5px;background:conic-gradient(from var(--ang),transparent 0 60%,var(--a1),var(--a2),transparent);-webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);-webkit-mask-composite:xor;mask-composite:exclude;animation:rot 5s linear infinite;animation-delay:var(--d)}.bt::after{content:"";position:absolute;inset:0;border-radius:22px;z-index:-1;background:radial-gradient(circle at 80% 0%,color-mix(in srgb,var(--a1) 22%,transparent),transparent 60%),var(--surface);box-shadow:inset 0 0 0 1px var(--line);transition:filter .3s}.bt:hover::after{filter:brightness(1.15)}@keyframes rot{to{--ang:360deg}}.bt h3{margin:0 0 6px;font-size:20px}.bt p{margin:0;color:var(--muted)}.big strong{font-size:clamp(48px,7vw,88px);letter-spacing:-.05em;order:-1;margin-bottom:auto;color:var(--a1)}@media(max-width:760px){.grid{grid-template-columns:1fr;grid-auto-rows:auto}.big,.wide{grid-column:auto;grid-row:auto}.bt{min-height:150px}}@media(prefers-reduced-motion:reduce){.bt::before{animation:none}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Nimbus AI</b><span class="links"><a href="#">Autocomplete</a><a href="#">Code review</a><a href="#">Test writer</a></span><a class="btn sm" href="#">Start free</a></nav><main><h2>Ship features at the speed of thought</h2><div class="grid"><article class="bt big" style="--d:-0s"><h3>Autocomplete</h3><p>An AI copilot that writes, tests and reviews your code while you focus on the product.</p><strong>10x</strong></article><article class="bt wide" style="--d:-1s"><h3>Code review</h3><p>Developers</p></article><article class="bt " style="--d:-2s"><h3>Test writer</h3><p>Uptime</p></article><article class="bt " style="--d:-3s"><h3>Docs bot</h3><p>Faster reviews</p></article><article class="bt wide" style="--d:-4s"><h3>Bug finder</h3><p>Developers</p></article><article class="bt " style="--d:-5s"><h3>Deploy check</h3><p>Uptime</p></article></div></main></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Rose Bento Grid Glow · AI SaaS — build prompt (VitalStack Motion Studio)

## Goal
Build a cards section for **Nimbus AI**, a ai saas brand: An asymmetric AI SaaS bento grid whose tiles have slowly rotating gradient borders and pop on hover.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Rose Noir" as CSS custom properties: --bg #14060c, --surface #250d18, --ink #fff0f5, --muted #cf9fb4, --a1 #fb7185 (primary), --a2 #f472b6 (secondary), --a3 #fda4af (highlight). Dark theme.
- Type: Humanist system stack (`"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif`), headings weight 700, letter-spacing -.025em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Nimbus AI
- Headline: “Ship features at the speed of thought”
- Sub-copy: “An AI copilot that writes, tests and reviews your code while you focus on the product.”
- Primary button: “Start free”; secondary: “Watch demo”
- Items: Autocomplete, Code review, Test writer, Docs bot, Bug finder, Deploy check
- Stats: 10x faster reviews; 42k developers; 99.9% uptime
- Testimonial: “We cut our review time in half in the first week.” — Priya S., CTO at Loopline

## Layout
- Top navigation: brand on the left, 3 links (Autocomplete, Code review, Test writer) and a small “Start free” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. A CSS grid with spans (2×2, 2×1, 1×1) builds the bento layout; it collapses to one column on phones.
2. Each tile has a ::before layer with a conic-gradient that rotates using a registered @property angle (speed 5s).
3. Hover lifts the tile 4px and brightens its inner gradient.

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

Keywords: bento grid conic border glow features, ai saas
VSAI,
);
