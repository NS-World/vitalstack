<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Paper Typewriter Headline · Nonprofit</title><style>:root{--bg:#f7f5f0;--surface:#ffffff;--ink:#16140f;--muted:#6b675d;--a1:#111827;--a2:#e11d48;--a3:#2563eb;--line:rgba(0,0,0,.1);--on:#ffffff;--font:system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;--hw:800;--ls:-.035em;color-scheme:light}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}body{min-height:100vh;display:flex;flex-direction:column}main{flex:1;display:grid;place-items:center;text-align:center;padding:24px}h1 .w{color:var(--a1);border-right:.08em solid var(--a2);padding-right:.05em;animation:car 1s steps(1) infinite}@keyframes car{50%{border-color:transparent}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.sub{margin:0 auto}.row{justify-content:center}</style></head><body><nav class="nav"><b class="logo">Open Shelf</b><span class="links"><a href="#">Libraries</a><a href="#">Reading clubs</a><a href="#">Teacher kits</a></span><a class="btn sm" href="#">Donate now</a></nav><main><div><p class="eyebrow">Nonprofit</p><h1 aria-label="Open Shelf makes you brighter / kinder / wiser / together">Open Shelf makes you <span class="w" id="w"></span></h1><p class="sub">We build free community libraries in villages and run reading clubs every week.</p><div class="row"><a class="btn" href="#">Donate now</a><a class="btn ghost" href="#">Volunteer</a></div></div></main><script>(function(){var W=["brighter", "kinder", "wiser", "together"],el=document.getElementById("w"),i=0,n=0,del=false;if(matchMedia("(prefers-reduced-motion: reduce)").matches){el.textContent=W[0];return}function s(){var w=W[i];n+=del?-1:1;el.textContent=w.slice(0,n);var d=del?40:70;if(!del&&n===w.length){del=true;d=1200}else if(del&&n===0){del=false;i=(i+1)%W.length;d=300}setTimeout(s,d)}s()})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Paper Typewriter Headline · Nonprofit — build prompt (VitalStack Motion Studio)

## Goal
Build a text effect section for **Open Shelf**, a nonprofit brand: The headline types, pauses and deletes a rotating word with a blinking caret, for a nonprofit brand.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Paper Ink" as CSS custom properties: --bg #f7f5f0, --surface #ffffff, --ink #16140f, --muted #6b675d, --a1 #111827 (primary), --a2 #e11d48 (secondary), --a3 #2563eb (highlight). Light theme.
- Type: Modern sans system stack (`system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif`), headings weight 800, letter-spacing -.035em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Open Shelf
- Headline: “Every child deserves a library”
- Sub-copy: “We build free community libraries in villages and run reading clubs every week.”
- Primary button: “Donate now”; secondary: “Volunteer”
- Items: Libraries, Reading clubs, Teacher kits, Digital labs, Scholarships, Events
- Stats: 230 libraries; 60k children; ₹500 buys 10 books
- Testimonial: “My daughter reads to me every night now.” — Parent, Wardha district

## Layout
- Top navigation: brand on the left, 3 links (Libraries, Reading clubs, Teacher kits) and a small “Donate now” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. A fixed headline start is followed by a <span> that types each word from the list [brighter, kinder, wiser, together] one letter every 70ms.
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

Keywords: typewriter typing words rotating headline, nonprofit
VSAI,
);
