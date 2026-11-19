<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Neon Flowing Wave Lines · Electric cars</title><style>:root{--bg:#05010f;--surface:#120726;--ink:#fbf7ff;--muted:#b49fd6;--a1:#e879f9;--a2:#22d3ee;--a3:#fde047;--line:rgba(255,255,255,.12);--on:#ffffff;--font:"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif;--hw:700;--ls:-.025em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}body{min-height:100vh;overflow:hidden}canvas{position:fixed;left:0;width:100%;bottom:0;height:55vh}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.copy{position:relative;z-index:2;max-width:880px;margin:0 auto;text-align:center;padding:clamp(48px,10vh,120px) 20px}.copy .sub{margin:0 auto}.copy .row{justify-content:center}</style></head><body><canvas id="cv" aria-hidden="true"></canvas><nav class="nav"><b class="logo">Volt Drive</b><span class="links"><a href="#">Range</a><a href="#">Fast charging</a><a href="#">Autopilot</a></span><a class="btn sm" href="#">Book a test drive</a></nav><div class="copy"><p class="eyebrow">Electric cars</p><h1>Silent power. Zero excuses.</h1><p class="sub">An electric SUV with 520 km range, 18-minute fast charging and room for everyone.</p><div class="row"><a class="btn" href="#">Book a test drive</a><a class="btn ghost" href="#">Configure</a></div></div><script>(function(){var cv=document.getElementById("cv"),x=cv.getContext("2d"),W,H,D=Math.min(devicePixelRatio||1,2),t=0,my=.5,N=7,cs=getComputedStyle(document.documentElement);var a1=cs.getPropertyValue("--a1").trim(),a2=cs.getPropertyValue("--a2").trim(),red=matchMedia("(prefers-reduced-motion: reduce)").matches;function rs(){W=cv.clientWidth;H=cv.clientHeight;cv.width=W*D;cv.height=H*D;x.setTransform(D,0,0,D,0,0)}rs();addEventListener("resize",rs);addEventListener("pointermove",function(e){my=e.clientY/innerHeight});function f(){if(!red)t+=.012;x.clearRect(0,0,W,H);var g=x.createLinearGradient(0,0,W,0);g.addColorStop(0,a1);g.addColorStop(1,a2);x.strokeStyle=g;x.globalAlpha=.5;x.lineWidth=2;for(var i=0;i<N;i++){var A=H*(.06+.02*i)*(1+.6*(1-Math.abs(my-i/N))),L=W/(1.2+i*.25),ph=t*(1+i*.15)+i;x.beginPath();for(var X=0;X<=W;X+=8){var Y=H*(.35+i*.5/N)+Math.sin(X/L*6.283+ph)*A+Math.sin(X/(L*.37)+ph*1.7)*A*.25;X?x.lineTo(X,Y):x.moveTo(X,Y)}x.stroke()}if(!document.hidden)requestAnimationFrame(f)}f();document.addEventListener("visibilitychange",function(){if(!document.hidden)f()})})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Neon Flowing Wave Lines · Electric cars — build prompt (VitalStack Motion Studio)

## Goal
Build a background section for **Volt Drive**, a electric cars brand: Layered sine waves flow across the screen in your brand colours behind an electric cars message.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Neon Arcade" as CSS custom properties: --bg #05010f, --surface #120726, --ink #fbf7ff, --muted #b49fd6, --a1 #e879f9 (primary), --a2 #22d3ee (secondary), --a3 #fde047 (highlight). Dark theme.
- Type: Humanist system stack (`"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif`), headings weight 700, letter-spacing -.025em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Volt Drive
- Headline: “Silent power. Zero excuses.”
- Sub-copy: “An electric SUV with 520 km range, 18-minute fast charging and room for everyone.”
- Primary button: “Book a test drive”; secondary: “Configure”
- Items: Range, Fast charging, Autopilot, Interior, Safety, Warranty
- Stats: 520 km range; 18 min 10–80%; 5★ safety
- Testimonial: “I forgot what a petrol pump looks like.” — Owner, Hyderabad

## Layout
- Top navigation: brand on the left, 3 links (Range, Fast charging, Autopilot) and a small “Book a test drive” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. A <canvas> draws 7 wave lines; each line is a sum of two sine waves with its own amplitude, wavelength and phase speed.
2. Lines are stroked with a horizontal gradient from --a1 to --a2 at .5 opacity.
3. The pointer's vertical position subtly raises the amplitude of nearby waves.

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

Keywords: canvas sine waves lines flowing hero, electric cars
VSAI,
);
