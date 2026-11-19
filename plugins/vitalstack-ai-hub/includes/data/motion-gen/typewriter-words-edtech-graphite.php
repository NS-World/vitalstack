<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Graphite Typewriter Headline · EdTech</title><style>:root{--bg:#0d0d0f;--surface:#1a1a1e;--ink:#f6f3ea;--muted:#a8a49a;--a1:#eab308;--a2:#f59e0b;--a3:#e5e7eb;--line:rgba(255,255,255,.12);--on:#0b0b10;--font:"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif;--hw:700;--ls:-.025em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}body{min-height:100vh;display:flex;flex-direction:column}main{flex:1;display:grid;place-items:center;text-align:center;padding:24px}h1 .w{color:var(--a1);border-right:.08em solid var(--a2);padding-right:.05em;animation:car 1s steps(1) infinite}@keyframes car{50%{border-color:transparent}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.sub{margin:0 auto}.row{justify-content:center}</style></head><body><nav class="nav"><b class="logo">LearnLoop</b><span class="links"><a href="#">Python</a><a href="#">JavaScript</a><a href="#">SQL</a></span><a class="btn sm" href="#">Start learning</a></nav><main><div><p class="eyebrow">EdTech</p><h1 aria-label="LearnLoop makes you easier / faster / deeper / daily">LearnLoop makes you <span class="w" id="w"></span></h1><p class="sub">Bite-size lessons, live coding and projects that end up in your portfolio.</p><div class="row"><a class="btn" href="#">Start learning</a><a class="btn ghost" href="#">See courses</a></div></div></main><script>(function(){var W=["easier", "faster", "deeper", "daily"],el=document.getElementById("w"),i=0,n=0,del=false;if(matchMedia("(prefers-reduced-motion: reduce)").matches){el.textContent=W[0];return}function s(){var w=W[i];n+=del?-1:1;el.textContent=w.slice(0,n);var d=del?40:110;if(!del&&n===w.length){del=true;d=1200}else if(del&&n===0){del=false;i=(i+1)%W.length;d=300}setTimeout(s,d)}s()})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Graphite Typewriter Headline · EdTech — build prompt (VitalStack Motion Studio)

## Goal
Build a text effect section for **LearnLoop**, a edtech brand: The headline types, pauses and deletes a rotating word with a blinking caret, for an EdTech brand.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Graphite Gold" as CSS custom properties: --bg #0d0d0f, --surface #1a1a1e, --ink #f6f3ea, --muted #a8a49a, --a1 #eab308 (primary), --a2 #f59e0b (secondary), --a3 #e5e7eb (highlight). Dark theme.
- Type: Humanist system stack (`"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif`), headings weight 700, letter-spacing -.025em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: LearnLoop
- Headline: “Learn to code, one small win a day”
- Sub-copy: “Bite-size lessons, live coding and projects that end up in your portfolio.”
- Primary button: “Start learning”; secondary: “See courses”
- Items: Python, JavaScript, SQL, Data science, AI basics, Web design
- Stats: 300+ lessons; 5 min per lesson; 80k learners
- Testimonial: “I got my first developer job eight months after lesson one.” — Ananya P., junior developer

## Layout
- Top navigation: brand on the left, 3 links (Python, JavaScript, SQL) and a small “Start learning” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. A fixed headline start is followed by a <span> that types each word from the list [easier, faster, deeper, daily] one letter every 110ms.
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

Keywords: typewriter typing words rotating headline, edtech
VSAI,
);
