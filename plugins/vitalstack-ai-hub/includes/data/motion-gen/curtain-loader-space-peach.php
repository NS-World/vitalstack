<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Peach Curtain Preloader · Space &amp; science</title><style>:root{--bg:#fff5ef;--surface:#ffffff;--ink:#2b1610;--muted:#7d5a4e;--a1:#f97316;--a2:#ec4899;--a3:#8b5cf6;--line:rgba(0,0,0,.1);--on:#ffffff;--font:"Arial Narrow","Roboto Condensed","Helvetica Neue",Arial,sans-serif;--hw:800;--ls:-.01em;color-scheme:light}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}body{min-height:100vh;overflow:hidden}.ld{position:fixed;inset:0;z-index:20;display:flex}.ld i{flex:1;background:var(--a1);transition:transform .8s cubic-bezier(.7,0,.2,1);transition-delay:calc(var(--i)*80ms)}.ld.open i{transform:translateY(-101%)}.ld .in{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--on);transition:opacity .3s}.ld.open .in{opacity:0}.ld b{font-size:clamp(20px,3vw,28px);letter-spacing:.2em;text-transform:uppercase}.ld span{font-size:clamp(60px,14vw,160px);font-weight:900;letter-spacing:-.05em;font-variant-numeric:tabular-nums}.copy{opacity:0;transform:translateY(24px);transition:opacity 1s .2s,transform 1.4s cubic-bezier(.2,.8,.2,1)}.shown .copy{opacity:1;transform:none}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.copy{position:relative;z-index:2;max-width:880px;margin:0 auto;text-align:center;padding:clamp(48px,10vh,120px) 20px}.copy .sub{margin:0 auto}.copy .row{justify-content:center}</style></head><body><div class="ld" id="ld"><i style="--i:0"></i><i style="--i:1"></i><i style="--i:2"></i><i style="--i:3"></i><i style="--i:4"></i><div class="in"><b>Orbit Lab</b><span id="pc">0</span></div></div><nav class="nav"><b class="logo">Orbit Lab</b><span class="links"><a href="#">Exoplanets</a><a href="#">Asteroids</a><a href="#">Galaxies</a></span><a class="btn sm" href="#">Join a mission</a></nav><div class="copy"><p class="eyebrow">Space &amp; science</p><h1>Look up. Then go further.</h1><p class="sub">A citizen-science platform where anyone can help discover new planets and asteroids.</p><div class="row"><a class="btn" href="#">Join a mission</a><a class="btn ghost" href="#">Live sky</a></div></div><script>(function(){var L=document.getElementById("ld"),P=document.getElementById("pc"),D=1200;function run(){L.classList.remove("open");document.body.classList.remove("shown");var t0=performance.now();(function f(n){var p=Math.min(1,(n-t0)/D),e=1-Math.pow(1-p,3);P.textContent=Math.round(e*100);if(p<1)requestAnimationFrame(f);else setTimeout(function(){L.classList.add("open");document.body.classList.add("shown")},200)})(t0)}if(matchMedia("(prefers-reduced-motion: reduce)").matches){L.classList.add("open");document.body.classList.add("shown");return}run();setInterval(run,D+4500)})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Peach Curtain Preloader · Space & science — build prompt (VitalStack Motion Studio)

## Goal
Build a loader section for **Orbit Lab**, a space & science brand: A counter runs to 100%, then curtains slide open to reveal the space & science hero; it replays in the preview.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Peach Sorbet" as CSS custom properties: --bg #fff5ef, --surface #ffffff, --ink #2b1610, --muted #7d5a4e, --a1 #f97316 (primary), --a2 #ec4899 (secondary), --a3 #8b5cf6 (highlight). Light theme.
- Type: Condensed display system stack (`"Arial Narrow","Roboto Condensed","Helvetica Neue",Arial,sans-serif`), headings weight 800, letter-spacing -.01em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Orbit Lab
- Headline: “Look up. Then go further.”
- Sub-copy: “A citizen-science platform where anyone can help discover new planets and asteroids.”
- Primary button: “Join a mission”; secondary: “Live sky”
- Items: Exoplanets, Asteroids, Galaxies, Satellites, Night sky, Classes
- Stats: 2.1M classifications; 37 discoveries; 140 countries
- Testimonial: “A 14-year-old on our platform found a new asteroid.” — Orbit Lab team

## Layout
- Top navigation: brand on the left, 3 links (Exoplanets, Asteroids, Galaxies) and a small “Join a mission” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. A fixed overlay shows the brand and a percentage counting 0→100 over 1200ms with an ease-out curve.
2. At 100 the overlay splits into 5 vertical panels that slide up with a staggered delay.
3. The hero copy fades in and rises 24px as it is revealed. In production run the loader only on the first visit.

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

Keywords: preloader loading curtain reveal intro counter, space & science
VSAI,
);
