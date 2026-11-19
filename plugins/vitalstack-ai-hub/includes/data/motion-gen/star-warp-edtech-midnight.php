<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Midnight Star Warp · EdTech</title><style>:root{--bg:#07061a;--surface:#13112e;--ink:#f4f2ff;--muted:#a7a3c9;--a1:#7c3aed;--a2:#22d3ee;--a3:#f472b6;--line:rgba(255,255,255,.12);--on:#ffffff;--font:ui-rounded,"SF Pro Rounded","Nunito","Segoe UI",system-ui,sans-serif;--hw:800;--ls:-.02em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}body{min-height:100vh;overflow:hidden;background:radial-gradient(circle at 50% 50%,var(--surface),var(--bg) 70%)}canvas{position:fixed;inset:0;width:100%;height:100%}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.copy{position:relative;z-index:2;max-width:880px;margin:0 auto;text-align:center;padding:clamp(48px,10vh,120px) 20px}.copy .sub{margin:0 auto}.copy .row{justify-content:center}</style></head><body><canvas id="cv" aria-hidden="true"></canvas><nav class="nav"><b class="logo">LearnLoop</b><span class="links"><a href="#">Python</a><a href="#">JavaScript</a><a href="#">SQL</a></span><a class="btn sm" href="#">Start learning</a></nav><div class="copy"><p class="eyebrow">EdTech</p><h1>Learn to code, one small win a day</h1><p class="sub">Bite-size lessons, live coding and projects that end up in your portfolio.</p><div class="row"><a class="btn" href="#">Start learning</a><a class="btn ghost" href="#">See courses</a></div></div><script>(function(){var cv=document.getElementById("cv"),x=cv.getContext("2d"),W,H,D=Math.min(devicePixelRatio||1,2),S=[],N=400,v=1.5,tv=1.5,col=getComputedStyle(document.documentElement).getPropertyValue("--ink").trim();function rs(){W=innerWidth;H=innerHeight;cv.width=W*D;cv.height=H*D;x.setTransform(D,0,0,D,0,0)}rs();addEventListener("resize",rs);function mk(s,far){s.x=(Math.random()-.5)*W*2;s.y=(Math.random()-.5)*H*2;s.z=far?W:Math.random()*W;s.pz=s.z;return s}for(var i=0;i<N;i++)S.push(mk({},0));addEventListener("pointerdown",function(){tv=14});addEventListener("pointerup",function(){tv=1.5});if(matchMedia("(prefers-reduced-motion: reduce)").matches){v=tv=.2}function f(){v+=(tv-v)*.05;x.fillStyle="rgba(0,0,0,0)";x.clearRect(0,0,W,H);x.strokeStyle=col;x.lineCap="round";for(var i=0;i<N;i++){var s=S[i];s.pz=s.z;s.z-=v;if(s.z<1){mk(s,1);continue}var k=W/2,sx=s.x/s.z*k+W/2,sy=s.y/s.z*k+H/2,px=s.x/s.pz*k+W/2,py=s.y/s.pz*k+H/2;x.globalAlpha=Math.min(1,(W-s.z)/W+.15);x.lineWidth=Math.max(.6,2.4*(1-s.z/W));x.beginPath();x.moveTo(px,py);x.lineTo(sx,sy);x.stroke()}x.globalAlpha=1;if(!document.hidden)requestAnimationFrame(f)}f();document.addEventListener("visibilitychange",function(){if(!document.hidden)f()})})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Midnight Star Warp · EdTech — build prompt (VitalStack Motion Studio)

## Goal
Build a background section for **LearnLoop**, a edtech brand: Stars stream out from the centre like a jump to light speed; hold the button to go faster, for an EdTech brand.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Midnight Violet" as CSS custom properties: --bg #07061a, --surface #13112e, --ink #f4f2ff, --muted #a7a3c9, --a1 #7c3aed (primary), --a2 #22d3ee (secondary), --a3 #f472b6 (highlight). Dark theme.
- Type: Friendly rounded system stack (`ui-rounded,"SF Pro Rounded","Nunito","Segoe UI",system-ui,sans-serif`), headings weight 800, letter-spacing -.02em; body 16 px, line-height 1.5.
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
1. A <canvas> projects 400 stars with a simple 3D perspective (x/z, y/z) and draws each as a short streak from its previous position.
2. Speed eases between 1.5 and 14 while the pointer is pressed.
3. Stars that pass the camera respawn far away at random positions.

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

Keywords: canvas stars warp speed space hero, edtech
VSAI,
);
