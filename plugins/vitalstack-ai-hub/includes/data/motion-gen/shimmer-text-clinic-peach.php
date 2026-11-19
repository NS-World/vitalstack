<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Peach Shimmer Gradient Text · Healthcare clinic</title><style>:root{--bg:#fff5ef;--surface:#ffffff;--ink:#2b1610;--muted:#7d5a4e;--a1:#f97316;--a2:#ec4899;--a3:#8b5cf6;--line:rgba(0,0,0,.1);--on:#ffffff;--font:ui-rounded,"SF Pro Rounded","Nunito","Segoe UI",system-ui,sans-serif;--hw:800;--ls:-.02em;color-scheme:light}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}body{min-height:100vh;display:flex;flex-direction:column}main{flex:1;display:grid;place-items:center;text-align:center;padding:24px}h1{background:linear-gradient(100deg,var(--ink) 20%,var(--a1) 40%,var(--a2) 50%,var(--ink) 70%);background-size:250% 100%;-webkit-background-clip:text;background-clip:text;color:transparent;animation:sh 3s linear infinite}@keyframes sh{from{background-position:120% 0}to{background-position:-120% 0}}.links a{position:relative}.links a::after{content:"";position:absolute;left:0;right:0;bottom:-4px;height:2px;background:var(--a1);transform:scaleX(0);transform-origin:left;transition:transform .3s}.links a:hover::after{transform:scaleX(1)}.chips{display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin-top:28px}.chips span{padding:8px 14px;border-radius:99px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);font-size:14px;opacity:0;animation:in .6s forwards;animation-delay:calc(.4s + var(--i)*.12s)}@keyframes in{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}.sub{margin:0 auto}.row{justify-content:center}@media(prefers-reduced-motion:reduce){h1{animation:none}.chips span{animation:none;opacity:1}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">CareWell Clinic</b><span class="links"><a href="#">General care</a><a href="#">Dental</a><a href="#">Pediatrics</a></span><a class="btn sm" href="#">Book appointment</a></nav><main><div><p class="eyebrow">CareWell Clinic</p><h1>Care that listens first</h1><p class="sub">Same-day appointments, kind doctors and your reports on your phone.</p><div class="row"><a class="btn" href="#">Book appointment</a></div><div class="chips"><span style="--i:0">General care</span><span style="--i:1">Dental</span><span style="--i:2">Pediatrics</span><span style="--i:3">Lab tests</span><span style="--i:4">Physio</span><span style="--i:5">Teleconsult</span></div></div></main></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Peach Shimmer Gradient Text · Healthcare clinic — build prompt (VitalStack Motion Studio)

## Goal
Build a text effect section for **CareWell Clinic**, a healthcare clinic brand: A light sweep shimmers across the gradient healthcare clinic headline while nav links get animated underlines.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Peach Sorbet" as CSS custom properties: --bg #fff5ef, --surface #ffffff, --ink #2b1610, --muted #7d5a4e, --a1 #f97316 (primary), --a2 #ec4899 (secondary), --a3 #8b5cf6 (highlight). Light theme.
- Type: Friendly rounded system stack (`ui-rounded,"SF Pro Rounded","Nunito","Segoe UI",system-ui,sans-serif`), headings weight 800, letter-spacing -.02em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: CareWell Clinic
- Headline: “Care that listens first”
- Sub-copy: “Same-day appointments, kind doctors and your reports on your phone.”
- Primary button: “Book appointment”; secondary: “Our doctors”
- Items: General care, Dental, Pediatrics, Lab tests, Physio, Teleconsult
- Stats: 15 min avg. wait; 24/7 teleconsult; 30+ doctors
- Testimonial: “They explained everything and never rushed us.” — Meera, mother of two

## Layout
- Top navigation: brand on the left, 3 links (General care, Dental, Pediatrics) and a small “Book appointment” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. The headline uses background-clip:text with a 200%-wide gradient (--ink → --a1 → --a2 → --ink) animated across in 3s.
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

Keywords: gradient text shimmer shine animated underline, healthcare clinic
VSAI,
);
