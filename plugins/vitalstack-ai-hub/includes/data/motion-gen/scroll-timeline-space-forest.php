<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Forest Scroll Timeline · Space &amp; science</title><style>:root{--bg:#06140e;--surface:#0d2419;--ink:#ecfff5;--muted:#97c4ad;--a1:#22c55e;--a2:#84cc16;--a3:#2dd4bf;--line:rgba(255,255,255,.12);--on:#ffffff;--font:ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace;--hw:700;--ls:-.04em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.intro{text-align:center;padding:12vh 20px 6vh}.intro h1{max-width:16ch;margin:10px auto}.tl{position:relative;max-width:900px;margin:0 auto 20vh;padding:0 20px;list-style:none}.tl::before,.fill{content:"";position:absolute;left:50%;top:0;bottom:0;width:3px;margin-left:-1.5px;background:var(--line)}.fill{background:linear-gradient(var(--a1),var(--a2));transform-origin:top;transform:scaleY(0)}.st{position:relative;width:50%;padding:0 40px 60px 0;opacity:0;transform:translateX(-40px);transition:opacity 700ms,transform 700ms}.st:nth-child(odd){margin-left:50%;padding:0 0 60px 40px;transform:translateX(40px)}.st.on{opacity:1;transform:none}.dot{position:absolute;right:-9px;top:6px;width:18px;height:18px;border-radius:50%;background:var(--bg);box-shadow:inset 0 0 0 3px var(--a1)}.st:nth-child(odd) .dot{right:auto;left:-9px}.st.on .dot{background:var(--a1);animation:pu .8s}@keyframes pu{0%{box-shadow:0 0 0 0 var(--a1)}100%{box-shadow:0 0 0 18px transparent}}.cd{padding:20px;border-radius:18px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line)}.cd b{color:var(--a1);font-size:13px}.cd h3{margin:6px 0}.cd p{margin:0;color:var(--muted)}@media(max-width:700px){.tl::before,.fill{left:28px}.st,.st:nth-child(odd){width:auto;margin-left:0;padding:0 0 40px 54px;transform:translateY(24px)}.dot,.st:nth-child(odd) .dot{left:0;right:auto}}@media(prefers-reduced-motion:reduce){.st{opacity:1;transform:none!important;transition:none}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Orbit Lab</b><span class="links"><a href="#">Exoplanets</a><a href="#">Asteroids</a><a href="#">Galaxies</a></span><a class="btn sm" href="#">Join a mission</a></nav><header class="intro"><p class="eyebrow">How Orbit Lab works</p><h1>Look up. Then go further.</h1><p class="sub" style="margin:0 auto">Scroll ↓</p></header><ol class="tl" id="tl"><i class="fill" id="fl"></i><li class="st"><span class="dot"></span><div class="cd"><b>Step 1</b><h3>Exoplanets</h3><p>A citizen-science platform where anyone can help discover new planets and asteroids.</p></div></li><li class="st"><span class="dot"></span><div class="cd"><b>Step 2</b><h3>Asteroids</h3><p>Asteroids: discoveries.</p></div></li><li class="st"><span class="dot"></span><div class="cd"><b>Step 3</b><h3>Galaxies</h3><p>Galaxies: countries.</p></div></li><li class="st"><span class="dot"></span><div class="cd"><b>Step 4</b><h3>Satellites</h3><p>Satellites: classifications.</p></div></li><li class="st"><span class="dot"></span><div class="cd"><b>Step 5</b><h3>Night sky</h3><p>Night sky: discoveries.</p></div></li><li class="st"><span class="dot"></span><div class="cd"><b>Step 6</b><h3>Classes</h3><p>Classes: countries.</p></div></li></ol><script>(function(){var tl=document.getElementById("tl"),fl=document.getElementById("fl"),S=tl.querySelectorAll(".st");function f(){var b=tl.getBoundingClientRect(),mid=innerHeight*.6,p=Math.min(1,Math.max(0,(mid-b.top)/b.height));fl.style.transform="scaleY("+p+")";S.forEach(function(s){if(s.getBoundingClientRect().top<mid)s.classList.add("on")})}addEventListener("scroll",f,{passive:true});addEventListener("resize",f);f()})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Forest Scroll Timeline · Space & science — build prompt (VitalStack Motion Studio)

## Goal
Build a timeline section for **Orbit Lab**, a space & science brand: A vertical space & science timeline whose line fills as you scroll and whose steps pop in with a dot pulse.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Forest Mist" as CSS custom properties: --bg #06140e, --surface #0d2419, --ink #ecfff5, --muted #97c4ad, --a1 #22c55e (primary), --a2 #84cc16 (secondary), --a3 #2dd4bf (highlight). Dark theme.
- Type: Developer mono system stack (`ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace`), headings weight 700, letter-spacing -.04em; body 16 px, line-height 1.5.
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
1. A central line has a gradient fill whose scaleY follows how far the timeline has scrolled through the viewport.
2. Each step alternates left/right (single column on phones); when its dot passes the screen middle it gets .on and pulses once.
3. Steps slide in from their side over 700ms.

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

Keywords: timeline scroll progress line steps roadmap, space & science
VSAI,
);
