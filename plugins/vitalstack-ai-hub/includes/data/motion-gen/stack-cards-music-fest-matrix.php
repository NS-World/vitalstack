<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Matrix Stacking Cards Scroll · Music festival</title><style>:root{--bg:#010a04;--surface:#06180c;--ink:#e6ffee;--muted:#7fb592;--a1:#22c55e;--a2:#4ade80;--a3:#bef264;--line:rgba(255,255,255,.12);--on:#ffffff;--font:"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif;--hw:700;--ls:-.025em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.intro{min-height:60vh;display:grid;place-items:center;text-align:center;padding:24px}.stack{max-width:1000px;margin:0 auto;padding:0 20px 30vh}.sc{position:sticky;display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:center;min-height:60vh;margin-bottom:12vh;padding:clamp(24px,4vw,48px);border-radius:30px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line),0 -20px 60px -30px #0008;transform-origin:center top;will-change:transform}.sc b{color:var(--a1)}.sc h3{font-size:clamp(28px,4vw,48px);letter-spacing:var(--ls);margin:8px 0}.sc p{color:var(--muted);line-height:1.6}.art{height:100%;min-height:240px;border-radius:22px;background:radial-gradient(circle at 70% 30%,hsl(var(--h) 90% 70%),transparent 55%),linear-gradient(140deg,hsl(var(--h) 70% 50%),hsl(calc(var(--h) + 50) 60% 25%))}@media(max-width:760px){.sc{grid-template-columns:1fr}.art{min-height:160px}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Solstice Fest</b><span class="links"><a href="#">Main stage</a><a href="#">Night garden</a><a href="#">Silent disco</a></span><a class="btn sm" href="#">Get passes</a></nav><header class="intro"><div><p class="eyebrow">Music festival</p><h1>Three nights. One sky. Forty artists.</h1></div></header><div class="stack" id="sk"><article class="sc" style="top:calc(10vh + 0px);--h:6"><div><b>01</b><h3>Main stage</h3><p>A weekend of live music, art and food under the open sky, 14 to 16 March.</p><a class="btn sm" href="#">Get passes</a></div><div class="art"></div></article><article class="sc" style="top:calc(10vh + 16px);--h:100"><div><b>02</b><h3>Night garden</h3><p>A weekend of live music, art and food under the open sky, 14 to 16 March.</p><a class="btn sm" href="#">Get passes</a></div><div class="art"></div></article><article class="sc" style="top:calc(10vh + 32px);--h:134"><div><b>03</b><h3>Silent disco</h3><p>A weekend of live music, art and food under the open sky, 14 to 16 March.</p><a class="btn sm" href="#">Get passes</a></div><div class="art"></div></article><article class="sc" style="top:calc(10vh + 48px);--h:220"><div><b>04</b><h3>Art walk</h3><p>A weekend of live music, art and food under the open sky, 14 to 16 March.</p><a class="btn sm" href="#">Get passes</a></div><div class="art"></div></article><article class="sc" style="top:calc(10vh + 64px);--h:257"><div><b>05</b><h3>Food court</h3><p>A weekend of live music, art and food under the open sky, 14 to 16 March.</p><a class="btn sm" href="#">Get passes</a></div><div class="art"></div></article></div><script>(function(){var C=document.querySelectorAll(".sc"),M=0.88;if(matchMedia("(prefers-reduced-motion: reduce)").matches)return;function f(){for(var i=0;i<C.length-1;i++){var nx=C[i+1].getBoundingClientRect().top,me=C[i].getBoundingClientRect(),d=Math.min(1,Math.max(0,1-(nx-me.top)/me.height));C[i].style.transform="scale("+(1-(1-M)*d)+")";C[i].style.filter="brightness("+(1-.35*d)+")"}}addEventListener("scroll",f,{passive:true});f()})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Matrix Stacking Cards Scroll · Music festival — build prompt (VitalStack Motion Studio)

## Goal
Build a scroll story section for **Solstice Fest**, a music festival brand: Feature cards for a music festival brand stick and stack on top of each other as you scroll, each one shrinking back into depth.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Matrix Green" as CSS custom properties: --bg #010a04, --surface #06180c, --ink #e6ffee, --muted #7fb592, --a1 #22c55e (primary), --a2 #4ade80 (secondary), --a3 #bef264 (highlight). Dark theme.
- Type: Humanist system stack (`"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif`), headings weight 700, letter-spacing -.025em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Solstice Fest
- Headline: “Three nights. One sky. Forty artists.”
- Sub-copy: “A weekend of live music, art and food under the open sky, 14 to 16 March.”
- Primary button: “Get passes”; secondary: “Line-up”
- Items: Main stage, Night garden, Silent disco, Art walk, Food court, Camping
- Stats: 40 artists; 3 stages; 25k fans
- Testimonial: “The sunrise set on day two changed my life.” — Kabir, festival regular

## Layout
- Top navigation: brand on the left, 3 links (Main stage, Night garden, Silent disco) and a small “Get passes” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. Each card is position:sticky with a slightly larger top offset (16px steps), so they pile up.
2. As the next card arrives, the previous card scales down toward 0.88 and dims, computed from scroll position.
3. Cards use the palette with alternating gradient accents.

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

Keywords: sticky stacking cards scroll scale depth, music festival
VSAI,
);
