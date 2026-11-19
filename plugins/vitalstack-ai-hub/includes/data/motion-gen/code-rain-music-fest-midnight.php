<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Midnight Code Rain · Music festival</title><style>:root{--bg:#07061a;--surface:#13112e;--ink:#f4f2ff;--muted:#a7a3c9;--a1:#7c3aed;--a2:#22d3ee;--a3:#f472b6;--line:rgba(255,255,255,.12);--on:#ffffff;--font:system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;--hw:800;--ls:-.035em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}body{min-height:100vh;overflow:hidden}canvas{position:fixed;inset:0;width:100%;height:100%;opacity:.55}.copy h1{text-shadow:0 0 30px var(--bg)}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.copy{position:relative;z-index:2;max-width:880px;margin:0 auto;text-align:center;padding:clamp(48px,10vh,120px) 20px}.copy .sub{margin:0 auto}.copy .row{justify-content:center}</style></head><body><canvas id="cv" aria-hidden="true"></canvas><nav class="nav"><b class="logo">Solstice Fest</b><span class="links"><a href="#">Main stage</a><a href="#">Night garden</a><a href="#">Silent disco</a></span><a class="btn sm" href="#">Get passes</a></nav><div class="copy"><p class="eyebrow">Music festival</p><h1>Three nights. One sky. Forty artists.</h1><p class="sub">A weekend of live music, art and food under the open sky, 14 to 16 March.</p><div class="row"><a class="btn" href="#">Get passes</a><a class="btn ghost" href="#">Line-up</a></div></div><script>(function(){var cv=document.getElementById("cv"),x=cv.getContext("2d"),W,H,F=18,C,Y,G="アイウエオカキクケコサシスセソ0123456789",cs=getComputedStyle(document.documentElement),a1=cs.getPropertyValue("--a1").trim(),ink=cs.getPropertyValue("--ink").trim(),bg=cs.getPropertyValue("--bg").trim(),last=0;function rs(){W=cv.width=innerWidth;H=cv.height=innerHeight;C=Math.ceil(W/F);Y=[];for(var i=0;i<C;i++)Y.push(Math.random()*-H/F)}rs();addEventListener("resize",rs);var red=matchMedia("(prefers-reduced-motion: reduce)").matches;function f(n){if(n-last>42){last=n;x.globalAlpha=.12;x.fillStyle=bg;x.fillRect(0,0,W,H);x.globalAlpha=1;x.font=F+"px monospace";for(var i=0;i<C;i++){var g=G[Math.floor(Math.random()*G.length)];x.fillStyle=Math.random()<.04?ink:a1;x.fillText(g,i*F,Y[i]*F);if(Y[i]*F>H&&Math.random()>.975)Y[i]=0;Y[i]++}}if(!red&&!document.hidden)requestAnimationFrame(f)}requestAnimationFrame(f);document.addEventListener("visibilitychange",function(){if(!document.hidden&&!red)requestAnimationFrame(f)})})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Midnight Code Rain · Music festival — build prompt (VitalStack Motion Studio)

## Goal
Build a background section for **Solstice Fest**, a music festival brand: Columns of glyphs rain down the screen with glowing heads behind a music festival headline.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Midnight Violet" as CSS custom properties: --bg #07061a, --surface #13112e, --ink #f4f2ff, --muted #a7a3c9, --a1 #7c3aed (primary), --a2 #22d3ee (secondary), --a3 #f472b6 (highlight). Dark theme.
- Type: Modern sans system stack (`system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif`), headings weight 800, letter-spacing -.035em; body 16 px, line-height 1.5.
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
1. A <canvas> splits the width into 18px columns; each column keeps a y position and draws one random glyph per frame.
2. A translucent background fill each frame leaves fading trails; the leading glyph is brighter.
3. Frame rate is throttled to about 24 fps for the classic look and low CPU.

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

Keywords: matrix code rain characters canvas hacker, music festival
VSAI,
);
