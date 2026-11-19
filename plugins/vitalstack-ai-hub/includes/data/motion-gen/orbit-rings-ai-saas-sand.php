<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sand Orbit Rings · AI SaaS</title><style>:root{--bg:#f6f1e7;--surface:#fffdf8;--ink:#2a2418;--muted:#7a6f5a;--a1:#b45309;--a2:#0f766e;--a3:#be123c;--line:rgba(0,0,0,.1);--on:#ffffff;--font:ui-rounded,"SF Pro Rounded","Nunito","Segoe UI",system-ui,sans-serif;--hw:800;--ls:-.02em;color-scheme:light}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}body{min-height:100vh;overflow:hidden}.orb{position:absolute;inset:0;overflow:hidden;perspective:1000px}.sys{position:absolute;left:50%;top:56%;width:0;height:0;transform-style:preserve-3d;transition:transform .3s}.core{position:absolute;width:16vmin;height:16vmin;margin:-8vmin;border-radius:50%;background:radial-gradient(circle at 35% 35%,var(--a2),var(--a1) 60%,transparent 72%);filter:drop-shadow(0 0 40px var(--a1));animation:pulse 4s ease-in-out infinite}@keyframes pulse{50%{transform:scale(1.08)}}.ring{position:absolute;width:var(--s);height:var(--s);margin:calc(var(--s)/-2);border-radius:50%;border:1px dashed var(--line);animation:spin var(--t) linear infinite;animation-direction:var(--dir)}.bd{position:absolute;left:50%;top:50%;transform:rotate(var(--a)) translate(calc(var(--s)/2)) rotate(calc(-1*var(--a)))}.bd b{display:block;transform:translate(-50%,-50%);padding:6px 12px;border-radius:99px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);font-size:13px;white-space:nowrap;animation:spin var(--t) linear infinite;animation-direction:var(--dir);animation-name:unspin}@keyframes spin{to{transform:rotate(360deg)}}@keyframes unspin{from{transform:translate(-50%,-50%) rotate(0)}to{transform:translate(-50%,-50%) rotate(-360deg)}}.copy{padding-top:5vh!important}.copy h1{font-size:clamp(30px,5vw,60px)}@media(prefers-reduced-motion:reduce){.ring,.bd b,.core{animation:none}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.copy{position:relative;z-index:2;max-width:880px;margin:0 auto;text-align:center;padding:clamp(48px,10vh,120px) 20px}.copy .sub{margin:0 auto}.copy .row{justify-content:center}.copy{max-width:760px}.copy .sub,.copy .row{display:none}</style></head><body><nav class="nav"><b class="logo">Nimbus AI</b><span class="links"><a href="#">Autocomplete</a><a href="#">Code review</a><a href="#">Test writer</a></span><a class="btn sm" href="#">Start free</a></nav><div class="copy"><p class="eyebrow">AI SaaS</p><h1>Ship features at the speed of thought</h1><p class="sub">An AI copilot that writes, tests and reviews your code while you focus on the product.</p><div class="row"><a class="btn" href="#">Start free</a><a class="btn ghost" href="#">Watch demo</a></div></div><div class="orb" aria-hidden="true"><div class="sys" id="sys"><div class="core"></div><div class="ring" style="--s:44vmin;--t:32s;--dir:normal"><span class="bd" style="--a:0deg"><b>Autocomplete</b></span><span class="bd" style="--a:120deg"><b>Code review</b></span><span class="bd" style="--a:240deg"><b>Test writer</b></span></div><div class="ring" style="--s:66vmin;--t:42s;--dir:reverse"><span class="bd" style="--a:0deg"><b>Docs bot</b></span><span class="bd" style="--a:90deg"><b>Bug finder</b></span><span class="bd" style="--a:180deg"><b>Deploy check</b></span><span class="bd" style="--a:270deg"><b>faster</b></span></div><div class="ring" style="--s:88vmin;--t:52s;--dir:normal"><span class="bd" style="--a:0deg"><b>smarter</b></span><span class="bd" style="--a:72deg"><b>safer</b></span><span class="bd" style="--a:144deg"><b>together</b></span><span class="bd" style="--a:216deg"><b>Autocomplete</b></span><span class="bd" style="--a:288deg"><b>Code review</b></span></div></div></div><script>(function(){var s=document.getElementById("sys");if(matchMedia("(prefers-reduced-motion: reduce)").matches)return;addEventListener("pointermove",function(e){var x=e.clientX/innerWidth-.5,y=e.clientY/innerHeight-.5;s.style.transform="rotateX("+(-y*20)+"deg) rotateY("+(x*20)+"deg)"})})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Sand Orbit Rings · AI SaaS — build prompt (VitalStack Motion Studio)

## Goal
Build a hero section for **Nimbus AI**, a ai saas brand: AI SaaS feature badges orbit a glowing core on tilted rings, behind the headline.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Sand Studio" as CSS custom properties: --bg #f6f1e7, --surface #fffdf8, --ink #2a2418, --muted #7a6f5a, --a1 #b45309 (primary), --a2 #0f766e (secondary), --a3 #be123c (highlight). Light theme.
- Type: Friendly rounded system stack (`ui-rounded,"SF Pro Rounded","Nunito","Segoe UI",system-ui,sans-serif`), headings weight 800, letter-spacing -.02em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Nimbus AI
- Headline: “Ship features at the speed of thought”
- Sub-copy: “An AI copilot that writes, tests and reviews your code while you focus on the product.”
- Primary button: “Start free”; secondary: “Watch demo”
- Items: Autocomplete, Code review, Test writer, Docs bot, Bug finder, Deploy check
- Stats: 10x faster reviews; 42k developers; 99.9% uptime
- Testimonial: “We cut our review time in half in the first week.” — Priya S., CTO at Loopline

## Layout
- Top navigation: brand on the left, 3 links (Autocomplete, Code review, Test writer) and a small “Start free” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. 3 concentric rings rotate at different speeds (CSS rotate keyframes, 32s base); each ring holds labelled badges.
2. Badges counter-rotate so their text stays upright while they travel around the ring.
3. The whole system tilts slightly with the pointer (rotateX/rotateY up to 10°).

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

Keywords: orbit rings rotate icons circle hero, ai saas
VSAI,
);
