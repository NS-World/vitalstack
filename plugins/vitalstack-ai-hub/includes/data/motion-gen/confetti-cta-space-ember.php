<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Ember Confetti Burst CTA · Space &amp; science</title><style>:root{--bg:#140806;--surface:#24110c;--ink:#fff3ec;--muted:#c9a597;--a1:#f97316;--a2:#ef4444;--a3:#facc15;--line:rgba(255,255,255,.12);--on:#ffffff;--font:ui-rounded,"SF Pro Rounded","Nunito","Segoe UI",system-ui,sans-serif;--hw:800;--ls:-.02em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}body{min-height:100vh;display:flex;flex-direction:column}main{flex:1;display:grid;place-items:center;text-align:center;padding:24px}canvas{position:fixed;inset:0;width:100%;height:100%;pointer-events:none;z-index:9}.card{max-width:640px;padding:clamp(30px,6vw,60px);border-radius:28px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line),0 40px 80px -40px var(--a1)}.card h2{font-size:clamp(28px,4.6vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:10px 0}.card p{color:var(--muted)}#go{margin-top:24px;font-size:18px;padding:16px 28px}.ok{display:block;margin-top:12px;color:var(--a1);font-weight:700;min-height:1.4em}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Orbit Lab</b><span class="links"><a href="#">Exoplanets</a><a href="#">Asteroids</a><a href="#">Galaxies</a></span><a class="btn sm" href="#">Join a mission</a></nav><main><div class="card"><p class="eyebrow">Orbit Lab</p><h2>Look up. Then go further.</h2><p>A citizen-science platform where anyone can help discover new planets and asteroids.</p><button class="btn" id="go" type="button">Join a mission 🎉</button><span class="ok" id="ok" aria-live="polite"></span></div></main><canvas id="cv" aria-hidden="true"></canvas><script>(function(){var cv=document.getElementById("cv"),x=cv.getContext("2d"),W,H,P=[],N=160,cs=getComputedStyle(document.documentElement),C=["--a1","--a2","--a3","--ink"].map(function(k){return cs.getPropertyValue(k).trim()}),run=0,red=matchMedia("(prefers-reduced-motion: reduce)").matches,ok=document.getElementById("ok");function rs(){W=cv.width=innerWidth;H=cv.height=innerHeight}rs();addEventListener("resize",rs);function burst(px,py){ok.textContent="You are in! ✓";setTimeout(function(){ok.textContent=""},1800);if(red)return;for(var i=0;i<N;i++){var a=Math.random()*6.283,s=4+Math.random()*9;P.push({x:px,y:py,vx:Math.cos(a)*s,vy:Math.sin(a)*s-6,r:Math.random()*6.283,vr:(Math.random()-.5)*.4,w:6+Math.random()*6,h:4+Math.random()*6,c:C[i%4],l:120})}if(!run){run=1;requestAnimationFrame(f)}}function f(){x.clearRect(0,0,W,H);P=P.filter(function(p){p.vy+=.25;p.vx*=.99;p.vy*=.99;p.x+=p.vx+Math.sin(p.l/8);p.y+=p.vy;p.r+=p.vr;p.l--;x.save();x.globalAlpha=Math.min(1,p.l/40);x.translate(p.x,p.y);x.rotate(p.r);x.fillStyle=p.c;x.fillRect(-p.w/2,-p.h/2,p.w,p.h);x.restore();return p.l>0&&p.y<H+40});if(P.length)requestAnimationFrame(f);else run=0}var b=document.getElementById("go");b.addEventListener("click",function(e){burst(e.clientX||W/2,e.clientY||H/2)});setInterval(function(){var r=b.getBoundingClientRect();burst(r.left+r.width/2,r.top+r.height/2)},3000)})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Ember Confetti Burst CTA · Space & science — build prompt (VitalStack Motion Studio)

## Goal
Build a cta section for **Orbit Lab**, a space & science brand: Clicking the space & science button fires a burst of colourful confetti with gravity and spin.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Ember Night" as CSS custom properties: --bg #140806, --surface #24110c, --ink #fff3ec, --muted #c9a597, --a1 #f97316 (primary), --a2 #ef4444 (secondary), --a3 #facc15 (highlight). Dark theme.
- Type: Friendly rounded system stack (`ui-rounded,"SF Pro Rounded","Nunito","Segoe UI",system-ui,sans-serif`), headings weight 800, letter-spacing -.02em; body 16 px, line-height 1.5.
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
1. A full-screen pointer-events:none <canvas> spawns 160 paper pieces at the click point with random angle, speed, spin and colour from the palette.
2. Each piece applies gravity 0.25, air drag 0.99 and wobble, and fades after about 2 seconds.
3. The preview fires a burst every 3 seconds; reduced motion shows a simple checkmark instead.

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

Keywords: confetti burst celebration button canvas particles, space & science
VSAI,
);
