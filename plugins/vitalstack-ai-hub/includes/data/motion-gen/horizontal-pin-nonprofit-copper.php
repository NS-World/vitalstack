<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Copper Horizontal Scroll Gallery · Nonprofit</title><style>:root{--bg:#120c0a;--surface:#221814;--ink:#fbefe7;--muted:#c2a796;--a1:#ea580c;--a2:#d97706;--a3:#fbbf24;--line:rgba(255,255,255,.12);--on:#ffffff;--font:Georgia,"Iowan Old Style","Times New Roman",serif;--hw:700;--ls:-.02em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.intro{min-height:70vh;display:grid;place-items:center;text-align:center;padding:24px}#hs{height:600vh;position:relative}.stage{position:sticky;top:0;height:100vh;overflow:hidden;display:flex;align-items:center}.track{display:flex;gap:4vw;padding:0 8vw;will-change:transform}.pn{flex:0 0 min(70vw,520px);height:min(62vh,440px);border-radius:28px;padding:30px;display:flex;flex-direction:column;justify-content:flex-end;color:#fff;background:linear-gradient(160deg,hsl(var(--h) 80% 60%),hsl(calc(var(--h) + 60) 70% 30%));box-shadow:0 40px 80px -40px #000a;transition:transform .2s}.pn b{font-size:14px;opacity:.8}.pn h3{font-size:clamp(28px,4vw,52px);margin:6px 0;letter-spacing:-.03em}.pn p{margin:0;opacity:.9}.end{min-height:60vh;display:grid;place-items:center;text-align:center;padding:24px}@media(max-width:700px){#hs{height:auto}.stage{position:static;height:auto;padding:30px 0}.track{overflow-x:auto;scroll-snap-type:x mandatory;transform:none!important;padding:0 20px}.pn{scroll-snap-align:center;flex-basis:80vw;height:360px}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Open Shelf</b><span class="links"><a href="#">Libraries</a><a href="#">Reading clubs</a><a href="#">Teacher kits</a></span><a class="btn sm" href="#">Donate now</a></nav><header class="intro"><div><p class="eyebrow">Nonprofit</p><h1>Every child deserves a library</h1><p class="sub" style="margin:0 auto">We build free community libraries in villages and run reading clubs every week.</p></div></header><section id="hs"><div class="stage"><div class="track" id="tr"><article class="pn" style="--h:26"><b>01</b><h3>Libraries</h3><p>230 libraries</p></article><article class="pn" style="--h:53"><b>02</b><h3>Reading clubs</h3><p>60k children</p></article><article class="pn" style="--h:108"><b>03</b><h3>Teacher kits</h3><p>₹500 buys 10 books</p></article><article class="pn" style="--h:167"><b>04</b><h3>Digital labs</h3><p>230 libraries</p></article><article class="pn" style="--h:212"><b>05</b><h3>Scholarships</h3><p>60k children</p></article><article class="pn" style="--h:292"><b>06</b><h3>Events</h3><p>₹500 buys 10 books</p></article></div></div></section><section class="end"><div><h2>My daughter reads to me every night now.</h2><a class="btn" href="#">Donate now</a></div></section><script>(function(){var s=document.getElementById("hs"),tr=document.getElementById("tr"),P=tr.children;function f(){if(innerWidth<=700)return;var b=s.getBoundingClientRect(),m=s.offsetHeight-innerHeight,p=Math.min(1,Math.max(0,-b.top/m)),dx=tr.scrollWidth-innerWidth;tr.style.transform="translateX("+(-p*dx)+"px)";for(var i=0;i<P.length;i++){var r=P[i].getBoundingClientRect(),c=1-Math.min(1,Math.abs(r.left+r.width/2-innerWidth/2)/innerWidth);P[i].style.transform="scale("+(.9+.1*c)+")"}}addEventListener("scroll",f,{passive:true});addEventListener("resize",f);f()})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Copper Horizontal Scroll Gallery · Nonprofit — build prompt (VitalStack Motion Studio)

## Goal
Build a scroll story section for **Open Shelf**, a nonprofit brand: Scrolling down slides a row of nonprofit panels sideways inside a pinned section.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Copper Dusk" as CSS custom properties: --bg #120c0a, --surface #221814, --ink #fbefe7, --muted #c2a796, --a1 #ea580c (primary), --a2 #d97706 (secondary), --a3 #fbbf24 (highlight). Dark theme.
- Type: Editorial serif system stack (`Georgia,"Iowan Old Style","Times New Roman",serif`), headings weight 700, letter-spacing -.02em; body 16 px, line-height 1.5.
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
1. A tall section (600vh) contains a sticky 100vh stage; its track of 6 panels translates on X by scroll progress × (track width − viewport width).
2. Each panel scales from .9 to 1 as it reaches the centre of the screen.
3. On phones (under 700px) the same panels become a native swipe row with scroll-snap.

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

Keywords: scroll pinned horizontal sticky gallery panels, nonprofit
VSAI,
);
