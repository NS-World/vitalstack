<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Deep Cursor Spotlight Grid · Real estate</title><style>:root{--bg:#03131f;--surface:#0a2233;--ink:#e8f6ff;--muted:#8fb3c9;--a1:#0ea5e9;--a2:#14b8a6;--a3:#a3e635;--line:rgba(255,255,255,.12);--on:#ffffff;--font:ui-rounded,"SF Pro Rounded","Nunito","Segoe UI",system-ui,sans-serif;--hw:800;--ls:-.02em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}body{min-height:100vh}main{position:relative;max-width:1100px;margin:0 auto;padding:30px 20px 60px}.head{text-align:center;margin-bottom:26px}.head h2{font-size:clamp(28px,4.4vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:10px 0}.grid{position:relative;display:grid;grid-template-columns:repeat(auto-fill,minmax(min(100%,200px),1fr));gap:10px}.tl{position:relative;padding:26px 20px;border-radius:16px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);display:flex;flex-direction:column;gap:6px;min-height:120px}.tl span{color:var(--muted);font-size:13px}.grid::after{content:"";position:absolute;inset:-2px;border-radius:18px;pointer-events:none;background:radial-gradient(340px circle at var(--x,50%) var(--y,50%),color-mix(in srgb,var(--a1) 40%,transparent),transparent 70%);mix-blend-mode:screen}.grid .tl{transition:box-shadow .2s}.lit{box-shadow:inset 0 0 0 1px var(--a2),0 0 30px -10px var(--a2)!important}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Keystone Homes</b><span class="links"><a href="#">Apartments</a><a href="#">Villas</a><a href="#">Studios</a></span><a class="btn sm" href="#">Browse homes</a></nav><main><div class="head"><p class="eyebrow">Real estate</p><h2>Find the home that fits your life</h2></div><div class="grid" id="g"><div class="tl"><b>Apartments</b><span>8,400</span></div><div class="tl"><b>Villas</b><span>100%</span></div><div class="tl"><b>Studios</b><span>21 days</span></div><div class="tl"><b>Rentals</b><span>8,400</span></div><div class="tl"><b>New launches</b><span>100%</span></div><div class="tl"><b>Commercial</b><span>21 days</span></div><div class="tl"><b>Apartments</b><span>8,400</span></div><div class="tl"><b>Villas</b><span>100%</span></div><div class="tl"><b>Studios</b><span>21 days</span></div><div class="tl"><b>Rentals</b><span>8,400</span></div><div class="tl"><b>New launches</b><span>100%</span></div><div class="tl"><b>Commercial</b><span>21 days</span></div></div></main><script>(function(){var g=document.getElementById("g"),T=g.querySelectorAll(".tl"),R=340,live=0,t=0;function at(x,y){var b=g.getBoundingClientRect();g.style.setProperty("--x",(x-b.left)+"px");g.style.setProperty("--y",(y-b.top)+"px");T.forEach(function(el){var r=el.getBoundingClientRect();el.classList.toggle("lit",Math.hypot(r.left+r.width/2-x,r.top+r.height/2-y)<R*.6)})}addEventListener("pointermove",function(e){live=1;at(e.clientX,e.clientY)});if(!matchMedia("(prefers-reduced-motion: reduce)").matches)(function w(){if(!live){t+=.012;var b=g.getBoundingClientRect();at(b.left+b.width*(.5+.42*Math.sin(t*1.3)),b.top+b.height*(.5+.4*Math.sin(t*2)))}requestAnimationFrame(w)})()})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Deep Cursor Spotlight Grid · Real estate — build prompt (VitalStack Motion Studio)

## Goal
Build a interactive section for **Keystone Homes**, a real estate brand: A dark grid lights up around your cursor like a torch, revealing real estate feature tiles.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Deep Ocean" as CSS custom properties: --bg #03131f, --surface #0a2233, --ink #e8f6ff, --muted #8fb3c9, --a1 #0ea5e9 (primary), --a2 #14b8a6 (secondary), --a3 #a3e635 (highlight). Dark theme.
- Type: Friendly rounded system stack (`ui-rounded,"SF Pro Rounded","Nunito","Segoe UI",system-ui,sans-serif`), headings weight 800, letter-spacing -.02em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Keystone Homes
- Headline: “Find the home that fits your life”
- Sub-copy: “Verified listings, honest prices and virtual tours you can take from the sofa.”
- Primary button: “Browse homes”; secondary: “Book a visit”
- Items: Apartments, Villas, Studios, Rentals, New launches, Commercial
- Stats: 8,400 listings; 100% verified; 21 days avg. to move
- Testimonial: “We saw 30 homes online and only visited the one we bought.” — The Iyer family

## Layout
- Top navigation: brand on the left, 3 links (Apartments, Villas, Studios) and a small “Browse homes” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. A full-screen grid of tiles is covered by a radial-gradient mask centred on CSS variables --x/--y, updated on pointermove.
2. Tile borders brighten inside a 340px radius; outside it they stay faint.
3. Without a pointer, the spotlight wanders on a Lissajous path so the effect is always visible.

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

Keywords: spotlight cursor grid reveal radial gradient, real estate
VSAI,
);
