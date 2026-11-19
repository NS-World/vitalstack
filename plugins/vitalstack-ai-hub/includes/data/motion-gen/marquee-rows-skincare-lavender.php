<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Lavender Infinite Marquee · Skincare</title><style>:root{--bg:#f6f4ff;--surface:#ffffff;--ink:#1b1640;--muted:#625c8a;--a1:#7c3aed;--a2:#06b6d4;--a3:#f43f5e;--line:rgba(0,0,0,.1);--on:#ffffff;--font:"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif;--hw:700;--ls:-.025em;color-scheme:light}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}body{min-height:100vh;display:flex;flex-direction:column}main{flex:1;display:flex;flex-direction:column;justify-content:center;gap:22px;padding:40px 0}.lead{text-align:center;padding:0 20px;margin-bottom:10px}.lead h2{font-size:clamp(26px,4vw,48px);letter-spacing:var(--ls);font-weight:var(--hw);margin:0 0 8px}.lead p{color:var(--muted)}.track{overflow:hidden;-webkit-mask-image:linear-gradient(90deg,transparent,#000 12%,#000 88%,transparent);mask-image:linear-gradient(90deg,transparent,#000 12%,#000 88%,transparent)}.belt{display:flex;width:max-content;align-items:center;gap:28px;animation:mq 22s linear infinite}.track:hover .belt{animation-play-state:paused}.track.rev .belt{animation-direction:reverse}.belt span{font-size:clamp(28px,6vw,72px);font-weight:var(--hw);letter-spacing:var(--ls);white-space:nowrap}.track.rev .belt span{-webkit-text-stroke:1.5px var(--ink);color:transparent}.belt i{font-style:normal;color:var(--a1);font-size:clamp(18px,3vw,32px)}@keyframes mq{to{transform:translateX(-50%)}}@media(prefers-reduced-motion:reduce){.belt{animation:none}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.cta{text-align:center;margin-top:18px}</style></head><body><nav class="nav"><b class="logo">Dew Lab</b><span class="links"><a href="#">Cleanser</a><a href="#">Serum</a><a href="#">Moisturiser</a></span><a class="btn sm" href="#">Find my routine</a></nav><main><div class="lead"><h2>Skincare that keeps it simple</h2><p>Three-step routines with clinically tested ingredients and nothing you do not need.</p></div><div class="track"><div class="belt"><span>Cleanser</span><i>／</i><span>Serum</span><i>／</i><span>Moisturiser</span><i>／</i><span>Sunscreen</span><i>／</i><span>Night repair</span><i>／</i><span>Kits</span><i>／</i><span>Cleanser</span><i>／</i><span>Serum</span><i>／</i><span>Moisturiser</span><i>／</i><span>Sunscreen</span><i>／</i><span>Night repair</span><i>／</i><span>Kits</span><i>／</i></div></div><div class="track rev"><div class="belt"><span>Night repair</span><i>／</i><span>Kits</span><i>／</i><span>softer</span><i>／</i><span>calmer</span><i>／</i><span>brighter</span><i>／</i><span>simple</span><i>／</i><span>Night repair</span><i>／</i><span>Kits</span><i>／</i><span>softer</span><i>／</i><span>calmer</span><i>／</i><span>brighter</span><i>／</i><span>simple</span><i>／</i></div></div><div class="cta"><a class="btn" href="#">Find my routine</a></div></main></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Lavender Infinite Marquee · Skincare — build prompt (VitalStack Motion Studio)

## Goal
Build a interactive section for **Dew Lab**, a skincare brand: Two endless rows of skincare keywords glide in opposite directions and slow down on hover.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Lavender Air" as CSS custom properties: --bg #f6f4ff, --surface #ffffff, --ink #1b1640, --muted #625c8a, --a1 #7c3aed (primary), --a2 #06b6d4 (secondary), --a3 #f43f5e (highlight). Light theme.
- Type: Humanist system stack (`"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif`), headings weight 700, letter-spacing -.025em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Dew Lab
- Headline: “Skincare that keeps it simple”
- Sub-copy: “Three-step routines with clinically tested ingredients and nothing you do not need.”
- Primary button: “Find my routine”; secondary: “Ingredients”
- Items: Cleanser, Serum, Moisturiser, Sunscreen, Night repair, Kits
- Stats: 3 steps; 28 days to results; 0% fragrance
- Testimonial: “My skin has never been this calm.” — Tanvi, verified buyer

## Layout
- Top navigation: brand on the left, 3 links (Cleanser, Serum, Moisturiser) and a small “Find my routine” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. Each row duplicates its content once and animates translateX(0 → -50%) linearly in 22s, so the loop is seamless.
2. The second row runs in reverse; hovering a row sets animation-play-state to paused.
3. Edges fade out with a CSS mask-image linear-gradient.

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

Keywords: marquee ticker infinite scroll logos words, skincare
VSAI,
);
