<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Mint Testimonial Carousel · Yoga studio</title><style>:root{--bg:#f1fbf6;--surface:#ffffff;--ink:#0b2a1d;--muted:#4f7364;--a1:#059669;--a2:#0ea5e9;--a3:#f59e0b;--line:rgba(0,0,0,.1);--on:#ffffff;--font:Georgia,"Iowan Old Style","Times New Roman",serif;--hw:700;--ls:-.02em;color-scheme:light}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}main{max-width:900px;margin:0 auto;padding:40px 20px 60px;text-align:center}main h2{font-size:clamp(26px,4vw,44px);letter-spacing:var(--ls);font-weight:var(--hw);margin:10px 0 26px}.vp{overflow:hidden;border-radius:26px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);touch-action:pan-y}.tr{display:flex;transition:transform 500ms cubic-bezier(.3,.8,.2,1)}.sl{flex:0 0 100%;margin:0;padding:clamp(28px,6vw,60px)}blockquote{margin:0;font-size:clamp(20px,3vw,32px);line-height:1.35;letter-spacing:-.01em}figcaption{display:flex;align-items:center;justify-content:center;gap:10px;margin-top:22px;color:var(--muted)}figcaption i{display:grid;place-items:center;width:40px;height:40px;border-radius:50%;font-style:normal;font-weight:800;color:#fff;background:hsl(var(--h) 70% 50%)}.dots{display:flex;gap:8px;justify-content:center;margin-top:18px}.dots button{all:unset;cursor:pointer;width:34px;height:6px;border-radius:9px;background:var(--line);overflow:hidden}.dots button:focus-visible{outline:2px solid var(--a2)}.dots i{display:block;height:100%;width:0;background:var(--a1)}.dots .on i{animation:pg 4s linear forwards}.paused .dots .on i{animation-play-state:paused}@keyframes pg{to{width:100%}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Still Point Yoga</b><span class="links"><a href="#">Hatha</a><a href="#">Vinyasa</a><a href="#">Yin</a></span><a class="btn sm" href="#">Book a class</a></nav><main id="tm"><p class="eyebrow">Loved by customers</p><h2>What people say about Still Point Yoga</h2><div class="vp" id="vp" tabindex="0" aria-roledescription="carousel"><div class="tr" id="tr"><figure class="sl"><blockquote>“The only hour of my week with no phone and no stress.”</blockquote><figcaption><i style="--h:20">M</i>Member since 2021</figcaption></figure><figure class="sl"><blockquote>“Setup took ten minutes and the team adopted it the same day.”</blockquote><figcaption><i style="--h:100">O</i>Operations lead, Still Point Yoga customer</figcaption></figure><figure class="sl"><blockquote>“Clear, fast and honestly priced. Exactly what we needed.”</blockquote><figcaption><i style="--h:180">S</i>Small business owner</figcaption></figure><figure class="sl"><blockquote>“Support answered in minutes, by a real person who knew the product.”</blockquote><figcaption><i style="--h:260">L</i>Long-time user</figcaption></figure></div></div><div class="dots" id="dt"><button type="button" aria-label="Show quote 1"><i></i></button><button type="button" aria-label="Show quote 2"><i></i></button><button type="button" aria-label="Show quote 3"><i></i></button><button type="button" aria-label="Show quote 4"><i></i></button></div></main><script>(function(){var m=document.getElementById("tm"),tr=document.getElementById("tr"),D=document.querySelectorAll("#dt button"),n=D.length,i=0,E=4000,tm;function go(k){i=(k+n)%n;tr.style.transform="translateX("+(-100*i)+"%)";D.forEach(function(d,j){d.classList.toggle("on",j===i);d.firstChild.style.animation="none";void d.offsetWidth;d.firstChild.style.animation=""});clearTimeout(tm);if(!m.classList.contains("paused"))tm=setTimeout(function(){go(i+1)},E)}D.forEach(function(d,j){d.addEventListener("click",function(){go(j)})});m.addEventListener("mouseenter",function(){m.classList.add("paused");clearTimeout(tm)});m.addEventListener("mouseleave",function(){m.classList.remove("paused");go(i)});var vp=document.getElementById("vp");vp.addEventListener("keydown",function(e){if(e.key==="ArrowRight")go(i+1);if(e.key==="ArrowLeft")go(i-1)});var sx=null;vp.addEventListener("pointerdown",function(e){sx=e.clientX});vp.addEventListener("pointerup",function(e){if(sx!==null&&Math.abs(e.clientX-sx)>40)go(i+(e.clientX<sx?1:-1));sx=null});go(0)})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Mint Testimonial Carousel · Yoga studio — build prompt (VitalStack Motion Studio)

## Goal
Build a testimonials section for **Still Point Yoga**, a yoga studio brand: Customer quotes for a yoga studio brand slide by automatically with progress dots, swipe and keyboard support.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Mint Cream" as CSS custom properties: --bg #f1fbf6, --surface #ffffff, --ink #0b2a1d, --muted #4f7364, --a1 #059669 (primary), --a2 #0ea5e9 (secondary), --a3 #f59e0b (highlight). Light theme.
- Type: Editorial serif system stack (`Georgia,"Iowan Old Style","Times New Roman",serif`), headings weight 700, letter-spacing -.02em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Still Point Yoga
- Headline: “Breathe in. Slow down.”
- Sub-copy: “Small classes, kind teachers and a quiet space to come back to yourself.”
- Primary button: “Book a class”; secondary: “Timetable”
- Items: Hatha, Vinyasa, Yin, Pranayama, Meditation, Workshops
- Stats: 6 classes daily; 12 per class; 9 teachers
- Testimonial: “The only hour of my week with no phone and no stress.” — Member since 2021

## Layout
- Top navigation: brand on the left, 3 links (Hatha, Vinyasa, Yin) and a small “Book a class” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. Slides sit in a flex track that translates by −100% × index with a 500ms ease transition.
2. Autoplay advances every 4s; a progress bar in the active dot shows the time left; hovering or focusing pauses it.
3. Arrow keys and swipe (pointer events with a 40px threshold) change slides.

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

Keywords: testimonials carousel slider auto play dots, yoga studio
VSAI,
);
