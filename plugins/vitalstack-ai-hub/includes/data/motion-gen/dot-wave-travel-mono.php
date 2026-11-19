<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Mono Dot Grid Wave · Travel</title><style>:root{--bg:#0a0a0a;--surface:#171717;--ink:#fafafa;--muted:#a3a3a3;--a1:#fafafa;--a2:#a3a3a3;--a3:#525252;--line:rgba(255,255,255,.12);--on:#0b0b10;--font:system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;--hw:800;--ls:-.035em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}body{min-height:100vh;overflow:hidden}canvas{position:fixed;inset:0;width:100%;height:100%}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.copy{position:relative;z-index:2;max-width:640px;padding:clamp(40px,9vh,110px) clamp(16px,5vw,64px)}</style></head><body><canvas id="cv" aria-hidden="true"></canvas><nav class="nav"><b class="logo">Wander Atlas</b><span class="links"><a href="#">Mountains</a><a href="#">Islands</a><a href="#">Old towns</a></span><a class="btn sm" href="#">Plan my trip</a></nav><div class="copy"><p class="eyebrow">Travel</p><h1>Go further than the guidebook</h1><p class="sub">Hand-planned trips with local hosts, hidden spots and zero tourist traps.</p><div class="row"><a class="btn" href="#">Plan my trip</a><a class="btn ghost" href="#">Explore places</a></div></div><script>(function(){var cv=document.getElementById("cv"),x=cv.getContext("2d"),W,H,D=Math.min(devicePixelRatio||1,2),G=22,t=0,cx=.5,cy=.5,tx=.5,ty=.5,live=0,cs=getComputedStyle(document.documentElement),a1=cs.getPropertyValue("--a1").trim(),a2=cs.getPropertyValue("--a2").trim();function rs(){W=innerWidth;H=innerHeight;cv.width=W*D;cv.height=H*D;x.setTransform(D,0,0,D,0,0)}rs();addEventListener("resize",rs);addEventListener("pointermove",function(e){live=1;tx=e.clientX/W;ty=e.clientY/H});var red=matchMedia("(prefers-reduced-motion: reduce)").matches;function f(){if(!red)t+=.05;if(!live){tx=.5+.3*Math.sin(t*.13);ty=.5+.25*Math.cos(t*.11)}cx+=(tx-cx)*.08;cy+=(ty-cy)*.08;x.clearRect(0,0,W,H);for(var X=G/2;X<W;X+=G)for(var Y=G/2;Y<H;Y+=G){var d=Math.hypot(X-cx*W,Y-cy*H),s=Math.sin(d/40-t);x.globalAlpha=.25+.6*(s+1)/2;x.fillStyle=s>0?a1:a2;x.beginPath();x.arc(X,Y,1.2+1.8*(s+1)/2,0,7);x.fill()}x.globalAlpha=1;if(!document.hidden)requestAnimationFrame(f)}f();document.addEventListener("visibilitychange",function(){if(!document.hidden)f()})})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Mono Dot Grid Wave · Travel — build prompt (VitalStack Motion Studio)

## Goal
Build a background section for **Wander Atlas**, a travel brand: A field of dots swells in a travelling wave and ripples outward from your cursor, for a travel brand.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Mono Contrast" as CSS custom properties: --bg #0a0a0a, --surface #171717, --ink #fafafa, --muted #a3a3a3, --a1 #fafafa (primary), --a2 #a3a3a3 (secondary), --a3 #525252 (highlight). Dark theme.
- Type: Modern sans system stack (`system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif`), headings weight 800, letter-spacing -.035em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Wander Atlas
- Headline: “Go further than the guidebook”
- Sub-copy: “Hand-planned trips with local hosts, hidden spots and zero tourist traps.”
- Primary button: “Plan my trip”; secondary: “Explore places”
- Items: Mountains, Islands, Old towns, Deserts, Food trails, Road trips
- Stats: 64 countries; 900+ local hosts; 4.9★ trip rating
- Testimonial: “Every day felt like a secret the city told only us.” — Sara & Tom, Lisbon trip

## Layout
- Top navigation: brand on the left, 3 links (Mountains, Islands, Old towns) and a small “Plan my trip” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. A <canvas> draws a grid of dots every 22px; each dot's radius = base + sin(distance to wave centre − time) × amplitude.
2. The wave centre follows the pointer smoothly (lerp 0.08) and drifts by itself when there is no pointer.
3. Dot colour blends from --a1 to --a2 with the wave height.

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

Keywords: dots grid wave ripple canvas pointer, travel
VSAI,
);
