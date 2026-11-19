<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Forest Magnetic CTA · Skincare</title><style>:root{--bg:#06140e;--surface:#0d2419;--ink:#ecfff5;--muted:#97c4ad;--a1:#22c55e;--a2:#84cc16;--a3:#2dd4bf;--line:rgba(255,255,255,.12);--on:#ffffff;--font:system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;--hw:800;--ls:-.035em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}body{min-height:100vh;display:flex;flex-direction:column}main{flex:1;display:grid;place-items:center;padding:24px}.box{position:relative;max-width:900px;width:100%;text-align:center;padding:clamp(40px,8vw,90px) 24px;border-radius:32px;overflow:hidden;background:linear-gradient(120deg,color-mix(in srgb,var(--a1) 30%,var(--surface)),var(--surface),color-mix(in srgb,var(--a2) 30%,var(--surface)));background-size:200% 200%;animation:sweep 8s ease-in-out infinite alternate;box-shadow:inset 0 0 0 1px var(--line)}@keyframes sweep{to{background-position:100% 100%}}.box h2{font-size:clamp(30px,5vw,62px);letter-spacing:var(--ls);font-weight:var(--hw);margin:10px auto 12px;max-width:16ch}.box p{color:var(--muted);max-width:52ch;margin:0 auto}.mag{position:relative;overflow:hidden;transition:transform .35s cubic-bezier(.3,1.6,.5,1)}.rp{position:absolute;width:40px;height:40px;margin:-20px 0 0 -20px;border-radius:50%;background:currentColor;opacity:.35;transform:scale(0);animation:rp .6s ease-out forwards;pointer-events:none}@keyframes rp{to{transform:scale(6);opacity:0}}.row{justify-content:center}@media(prefers-reduced-motion:reduce){.box{animation:none}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Dew Lab</b><span class="links"><a href="#">Cleanser</a><a href="#">Serum</a><a href="#">Moisturiser</a></span><a class="btn sm" href="#">Find my routine</a></nav><main><div class="box"><p class="eyebrow">Dew Lab</p><h2>Skincare that keeps it simple</h2><p>Three-step routines with clinically tested ingredients and nothing you do not need.</p><div class="row"><a class="btn mag" href="#">Find my routine →</a><a class="btn ghost mag" href="#">Ingredients</a></div></div></main><script>(function(){var P=16,red=matchMedia("(prefers-reduced-motion: reduce)").matches;document.querySelectorAll(".mag").forEach(function(b){b.addEventListener("click",function(e){e.preventDefault();var r=b.getBoundingClientRect(),s=document.createElement("span");s.className="rp";s.style.left=(e.clientX-r.left)+"px";s.style.top=(e.clientY-r.top)+"px";b.appendChild(s);setTimeout(function(){s.remove()},650)})});if(red)return;addEventListener("pointermove",function(e){document.querySelectorAll(".mag").forEach(function(b){var r=b.getBoundingClientRect(),cx=r.left+r.width/2,cy=r.top+r.height/2,dx=e.clientX-cx,dy=e.clientY-cy,d=Math.hypot(dx,dy),R=Math.max(r.width,r.height)/2+90;b.style.transform=d<R?"translate("+(dx/R*P)+"px,"+(dy/R*P)+"px)":""})});var t=0,b0=document.querySelector(".mag");if(!matchMedia("(hover:hover)").matches)setInterval(function(){t+=.1;b0.style.transform="translate("+Math.sin(t)*P*.6+"px,"+Math.cos(t*1.3)*P*.4+"px)"},50)})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Forest Magnetic CTA · Skincare — build prompt (VitalStack Motion Studio)

## Goal
Build a cta section for **Dew Lab**, a skincare brand: A skincare call-to-action whose buttons are pulled toward the cursor and ripple on click.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Forest Mist" as CSS custom properties: --bg #06140e, --surface #0d2419, --ink #ecfff5, --muted #97c4ad, --a1 #22c55e (primary), --a2 #84cc16 (secondary), --a3 #2dd4bf (highlight). Dark theme.
- Type: Modern sans system stack (`system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif`), headings weight 800, letter-spacing -.035em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Dew Lab
- Headline: “Skincare that keeps it simple”
- Sub-copy: “Three-step routines with clinically tested ingredients and nothing you do not need.”
- Primary button: “Find my routine”; secondary: “Ingredients”
- Items: Cleanser, Serum, Moisturiser, Sunscreen, Night repair, Kits
- Stats: 3 steps; 28 days to results; 0% fragrance
- Testimonial: “My skin has never been this calm.” — Tanvi, verified buyer

## Layout
- Top navigation: brand on the left, 3 links (Cleanser, Serum, Moisturiser) and a small “Find my routine” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. Each .mag button moves up to 16px toward the pointer when it is within 90px, using transform with a spring-back transition.
2. Clicking spawns a circular ripple <span> at the click point that scales from 0 to 4 and fades over 600ms.
3. The section background has a slow animated gradient sweep.

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

Keywords: magnetic button ripple cursor cta call to action, skincare
VSAI,
);
