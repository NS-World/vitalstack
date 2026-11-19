<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sand Typewriter Headline · Space &amp; science</title><style>:root{--bg:#f6f1e7;--surface:#fffdf8;--ink:#2a2418;--muted:#7a6f5a;--a1:#b45309;--a2:#0f766e;--a3:#be123c;--line:rgba(0,0,0,.1);--on:#ffffff;--font:Georgia,"Iowan Old Style","Times New Roman",serif;--hw:700;--ls:-.02em;color-scheme:light}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}body{min-height:100vh;display:flex;flex-direction:column}main{flex:1;display:grid;place-items:center;text-align:center;padding:24px}h1 .w{color:var(--a1);border-right:.08em solid var(--a2);padding-right:.05em;animation:car 1s steps(1) infinite}@keyframes car{50%{border-color:transparent}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.sub{margin:0 auto}.row{justify-content:center}</style></head><body><nav class="nav"><b class="logo">Orbit Lab</b><span class="links"><a href="#">Exoplanets</a><a href="#">Asteroids</a><a href="#">Galaxies</a></span><a class="btn sm" href="#">Join a mission</a></nav><main><div><p class="eyebrow">Space &amp; science</p><h1 aria-label="Orbit Lab makes you further / deeper / brighter / together">Orbit Lab makes you <span class="w" id="w"></span></h1><p class="sub">A citizen-science platform where anyone can help discover new planets and asteroids.</p><div class="row"><a class="btn" href="#">Join a mission</a><a class="btn ghost" href="#">Live sky</a></div></div></main><script>(function(){var W=["further", "deeper", "brighter", "together"],el=document.getElementById("w"),i=0,n=0,del=false;if(matchMedia("(prefers-reduced-motion: reduce)").matches){el.textContent=W[0];return}function s(){var w=W[i];n+=del?-1:1;el.textContent=w.slice(0,n);var d=del?40:110;if(!del&&n===w.length){del=true;d=1200}else if(del&&n===0){del=false;i=(i+1)%W.length;d=300}setTimeout(s,d)}s()})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Sand Typewriter Headline · Space & science — build prompt (VitalStack Motion Studio)

## Goal
Build a text effect section for **Orbit Lab**, a space & science brand: The headline types, pauses and deletes a rotating word with a blinking caret, for a space & science brand.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Sand Studio" as CSS custom properties: --bg #f6f1e7, --surface #fffdf8, --ink #2a2418, --muted #7a6f5a, --a1 #b45309 (primary), --a2 #0f766e (secondary), --a3 #be123c (highlight). Light theme.
- Type: Editorial serif system stack (`Georgia,"Iowan Old Style","Times New Roman",serif`), headings weight 700, letter-spacing -.02em; body 16 px, line-height 1.5.
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
1. A fixed headline start is followed by a <span> that types each word from the list [further, deeper, brighter, together] one letter every 110ms.
2. After a 1200ms pause it deletes letter by letter and moves to the next word, forever.
3. A CSS caret blinks with steps(1); screen readers get the full list through aria-label.

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

Keywords: typewriter typing words rotating headline, space & science
VSAI,
);
