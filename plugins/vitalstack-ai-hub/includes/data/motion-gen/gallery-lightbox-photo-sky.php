<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sky Zoom Gallery + Lightbox · Photography</title><style>:root{--bg:#f0f7ff;--surface:#ffffff;--ink:#0b1b33;--muted:#53657d;--a1:#2563eb;--a2:#0891b2;--a3:#f97316;--line:rgba(0,0,0,.1);--on:#ffffff;--font:"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif;--hw:700;--ls:-.025em;color-scheme:light}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}main{max-width:1150px;margin:0 auto;padding:30px 20px 60px}main h2{font-size:clamp(28px,4.4vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:0 0 22px}.ms{columns:3 220px;column-gap:14px}.ti{all:unset;box-sizing:border-box;cursor:zoom-in;position:relative;display:block;width:100%;margin:0 0 14px;border-radius:18px;overflow:hidden;break-inside:avoid}.ti:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.art{position:absolute;inset:0;transition:transform .6s cubic-bezier(.2,.8,.2,1)}.ti:hover .art{transform:scale(1.08)}.cap{position:absolute;left:10px;right:10px;bottom:10px;padding:8px 12px;border-radius:10px;background:#000a;color:#fff;font-size:14px;transform:translateY(130%);transition:transform .35s}.ti:hover .cap,.ti:focus .cap{transform:none}dialog{border:0;padding:0;background:transparent;max-width:min(92vw,900px);width:100%}dialog::backdrop{background:#000c;backdrop-filter:blur(4px)}dialog .big{height:min(80vh,620px);border-radius:22px;animation:pop .45s cubic-bezier(.2,1.2,.3,1)}dialog p{color:#fff;text-align:center;font-weight:700}@keyframes pop{from{transform:scale(.6);opacity:0}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Lumen Studio</b><span class="links"><a href="#">Weddings</a><a href="#">Portraits</a><a href="#">Products</a></span><a class="btn sm" href="#">Check dates</a></nav><main><h2>Light, caught honestly</h2><div class="ms"><button class="ti" type="button" style="height:180px"><span class="art" style="background:radial-gradient(circle at 30% 30%,hsl(257 90% 70%),transparent 50%),linear-gradient(160deg,hsl(297 70% 45%),hsl(347 60% 20%))"></span><span class="cap">Weddings</span></button><button class="ti" type="button" style="height:240px"><span class="art" style="background:repeating-linear-gradient(45deg,hsl(16 70% 55%) 0 14px,hsl(46 70% 40%) 14px 28px)"></span><span class="cap">Portraits</span></button><button class="ti" type="button" style="height:180px"><span class="art" style="background:conic-gradient(from 215deg,hsl(215 80% 60%),hsl(335 80% 50%),hsl(215 80% 60%))"></span><span class="cap">Products</span></button><button class="ti" type="button" style="height:180px"><span class="art" style="background:radial-gradient(circle at 30% 30%,hsl(149 90% 70%),transparent 50%),linear-gradient(160deg,hsl(189 70% 45%),hsl(239 60% 20%))"></span><span class="cap">Events</span></button><button class="ti" type="button" style="height:240px"><span class="art" style="background:repeating-linear-gradient(45deg,hsl(194 70% 55%) 0 14px,hsl(224 70% 40%) 14px 28px)"></span><span class="cap">Editorial</span></button><button class="ti" type="button" style="height:240px"><span class="art" style="background:conic-gradient(from 220deg,hsl(220 80% 60%),hsl(340 80% 50%),hsl(220 80% 60%))"></span><span class="cap">Prints</span></button><button class="ti" type="button" style="height:240px"><span class="art" style="background:radial-gradient(circle at 30% 30%,hsl(196 90% 70%),transparent 50%),linear-gradient(160deg,hsl(236 70% 45%),hsl(286 60% 20%))"></span><span class="cap">Weddings</span></button><button class="ti" type="button" style="height:180px"><span class="art" style="background:repeating-linear-gradient(45deg,hsl(280 70% 55%) 0 14px,hsl(310 70% 40%) 14px 28px)"></span><span class="cap">Portraits</span></button><button class="ti" type="button" style="height:300px"><span class="art" style="background:conic-gradient(from 237deg,hsl(237 80% 60%),hsl(357 80% 50%),hsl(237 80% 60%))"></span><span class="cap">Products</span></button><button class="ti" type="button" style="height:240px"><span class="art" style="background:radial-gradient(circle at 30% 30%,hsl(279 90% 70%),transparent 50%),linear-gradient(160deg,hsl(319 70% 45%),hsl(9 60% 20%))"></span><span class="cap">Events</span></button></div></main><dialog id="lb"><div class="big" id="bg"></div><p id="lc"></p></dialog><script>(function(){var d=document.getElementById("lb"),bg=document.getElementById("bg"),lc=document.getElementById("lc");document.querySelectorAll(".ti").forEach(function(t){t.addEventListener("click",function(){bg.style.background=t.querySelector(".art").style.background;lc.textContent=t.querySelector(".cap").textContent;d.showModal()})});d.addEventListener("click",function(){d.close()})})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Sky Zoom Gallery + Lightbox · Photography — build prompt (VitalStack Motion Studio)

## Goal
Build a gallery section for **Lumen Studio**, a photography brand: A photography masonry gallery of art tiles that zoom on hover and open in a smooth lightbox.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Sky Daylight" as CSS custom properties: --bg #f0f7ff, --surface #ffffff, --ink #0b1b33, --muted #53657d, --a1 #2563eb (primary), --a2 #0891b2 (secondary), --a3 #f97316 (highlight). Light theme.
- Type: Humanist system stack (`"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif`), headings weight 700, letter-spacing -.025em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Lumen Studio
- Headline: “Light, caught honestly”
- Sub-copy: “Wedding, portrait and product photography with a documentary eye.”
- Primary button: “Check dates”; secondary: “Portfolio”
- Items: Weddings, Portraits, Products, Events, Editorial, Prints
- Stats: 400+ shoots; 2 weeks delivery; 11 cities
- Testimonial: “They captured moments we did not even notice happening.” — Ritika & Sam

## Layout
- Top navigation: brand on the left, 3 links (Weddings, Portraits, Products) and a small “Check dates” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. CSS columns (3 on desktop) create a masonry layout of CSS-art tiles (layered gradients, no images).
2. Hover scales the art 1.08 inside its frame and reveals a caption bar.
3. Clicking a tile opens a <dialog> lightbox that animates from the tile size with the View Transitions-style scale trick; Esc or click closes it.

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

Keywords: gallery masonry hover zoom lightbox grid, photography
VSAI,
);
