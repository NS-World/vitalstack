<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Deep Retro Horizon Grid · Podcast</title><style>:root{--bg:#03131f;--surface:#0a2233;--ink:#e8f6ff;--muted:#8fb3c9;--a1:#0ea5e9;--a2:#14b8a6;--a3:#a3e635;--line:rgba(255,255,255,.12);--on:#ffffff;--font:Georgia,"Iowan Old Style","Times New Roman",serif;--hw:700;--ls:-.02em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}body{min-height:100vh;overflow:hidden;background:linear-gradient(180deg,var(--bg) 0%,var(--surface) 55%,var(--bg) 56%)}.sun{position:absolute;left:50%;top:max(30vh,300px);width:min(40vmin,380px);aspect-ratio:1;transform:translateX(-50%);border-radius:50%;background:linear-gradient(180deg,var(--a3),var(--a1));-webkit-mask:repeating-linear-gradient(180deg,#000 0 14px,transparent 14px 20px);mask:repeating-linear-gradient(180deg,#000 0 14px,transparent 14px 20px);filter:drop-shadow(0 0 40px var(--a1))}.floor{position:absolute;left:0;right:0;bottom:-10vh;height:56vh;perspective:300px;overflow:hidden}.plane{position:absolute;top:0;bottom:-100%;left:-50%;right:-50%;transform:rotateX(80deg);transform-origin:top;background-image:linear-gradient(var(--a2) 2px,transparent 2px),linear-gradient(90deg,var(--a2) 2px,transparent 2px);background-size:60px 60px;animation:fl 2s linear infinite;opacity:.8}@keyframes fl{to{background-position:0 60px}}.hz{position:absolute;left:0;right:0;top:55vh;height:2px;background:var(--a2);box-shadow:0 0 30px 6px var(--a2)}.copy{padding-top:6vh!important}@media(prefers-reduced-motion:reduce){.plane{animation:none}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.copy{position:relative;z-index:2;max-width:880px;margin:0 auto;text-align:center;padding:clamp(48px,10vh,120px) 20px}.copy .sub{margin:0 auto}.copy .row{justify-content:center}.copy .sub{display:none}</style></head><body><div class="sun" aria-hidden="true"></div><div class="hz"></div><div class="floor" aria-hidden="true"><div class="plane"></div></div><nav class="nav"><b class="logo">Signal &amp; Noise</b><span class="links"><a href="#">Latest episode</a><a href="#">Interviews</a><a href="#">Deep dives</a></span><a class="btn sm" href="#">Listen now</a></nav><div class="copy"><p class="eyebrow">Podcast</p><h1>Big ideas in 30 minutes</h1><p class="sub">A weekly podcast about technology, culture and the people building the future.</p><div class="row"><a class="btn" href="#">Listen now</a><a class="btn ghost" href="#">All episodes</a></div></div></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Deep Retro Horizon Grid · Podcast — build prompt (VitalStack Motion Studio)

## Goal
Build a background section for **Signal & Noise**, a podcast brand: An 80s-style neon grid floor scrolls toward you under a striped sunset, behind a podcast headline.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Deep Ocean" as CSS custom properties: --bg #03131f, --surface #0a2233, --ink #e8f6ff, --muted #8fb3c9, --a1 #0ea5e9 (primary), --a2 #14b8a6 (secondary), --a3 #a3e635 (highlight). Dark theme.
- Type: Editorial serif system stack (`Georgia,"Iowan Old Style","Times New Roman",serif`), headings weight 700, letter-spacing -.02em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Signal & Noise
- Headline: “Big ideas in 30 minutes”
- Sub-copy: “A weekly podcast about technology, culture and the people building the future.”
- Primary button: “Listen now”; secondary: “All episodes”
- Items: Latest episode, Interviews, Deep dives, Q&A, Newsletter, Live shows
- Stats: 180 episodes; 2M listens; Top 10 tech charts
- Testimonial: “My favourite 30 minutes of the week.” — Listener review

## Layout
- Top navigation: brand on the left, 3 links (Latest episode, Interviews, Deep dives) and a small “Listen now” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. The floor is a plane with two repeating-linear-gradients (grid lines) tilted with rotateX(80deg) inside perspective.
2. Background-position animates on Y over 2s so the grid flows toward the viewer forever.
3. The sun is a gradient circle sliced with a repeating mask; a horizon glow blends the two.

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

Keywords: synthwave retro grid perspective floor sun 80s, podcast
VSAI,
);
