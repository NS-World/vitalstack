<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Graphite 3D Tilt Cards · Yoga studio</title><style>:root{--bg:#0d0d0f;--surface:#1a1a1e;--ink:#f6f3ea;--muted:#a8a49a;--a1:#eab308;--a2:#f59e0b;--a3:#e5e7eb;--line:rgba(255,255,255,.12);--on:#0b0b10;--font:ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace;--hw:700;--ls:-.04em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}main{max-width:1100px;margin:0 auto;padding:40px 20px 60px}.head{text-align:center;margin-bottom:34px}.head h2{font-size:clamp(28px,4.4vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:10px 0}.head p{color:var(--muted)}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,250px),1fr));gap:18px}.card{position:relative;padding:26px;border-radius:20px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line),0 20px 40px -24px #0009;transform-style:preserve-3d;transition:transform .4s cubic-bezier(.2,.8,.2,1);overflow:hidden;min-height:180px}.card.on{transition:transform .08s}.card .ic{display:inline-grid;place-items:center;width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,var(--a1),var(--a2));color:var(--on);font-weight:800;transform:translateZ(40px)}.card h3{margin:18px 0 6px;font-size:20px;transform:translateZ(30px)}.card p{color:var(--muted);margin:0;transform:translateZ(20px)}.glare{position:absolute;inset:0;pointer-events:none;background:radial-gradient(400px circle at var(--gx,50%) var(--gy,0%),color-mix(in srgb,var(--a2) 28%,transparent),transparent 45%);opacity:0;transition:opacity .3s}.card.on .glare,.card:focus .glare{opacity:1}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Still Point Yoga</b><span class="links"><a href="#">Hatha</a><a href="#">Vinyasa</a><a href="#">Yin</a></span><a class="btn sm" href="#">Book a class</a></nav><main><div class="head"><p class="eyebrow">Yoga studio</p><h2>Breathe in. Slow down.</h2><p>Small classes, kind teachers and a quiet space to come back to yourself.</p></div><div class="grid"><article class="card" tabindex="0"><span class="ic">01</span><h3>Hatha</h3><p>Classes daily, built in.</p><i class="glare"></i></article><article class="card" tabindex="0"><span class="ic">02</span><h3>Vinyasa</h3><p>Per class, built in.</p><i class="glare"></i></article><article class="card" tabindex="0"><span class="ic">03</span><h3>Yin</h3><p>Teachers, built in.</p><i class="glare"></i></article><article class="card" tabindex="0"><span class="ic">04</span><h3>Pranayama</h3><p>Classes daily, built in.</p><i class="glare"></i></article><article class="card" tabindex="0"><span class="ic">05</span><h3>Meditation</h3><p>Per class, built in.</p><i class="glare"></i></article><article class="card" tabindex="0"><span class="ic">06</span><h3>Workshops</h3><p>Teachers, built in.</p><i class="glare"></i></article></div></main><script>(function(){var D=12,red=matchMedia("(prefers-reduced-motion: reduce)").matches;if(red)return;document.querySelectorAll(".card").forEach(function(c,i){function set(px,py){c.style.transform="perspective(900px) rotateX("+((.5-py)*D*2)+"deg) rotateY("+((px-.5)*D*2)+"deg)";c.style.setProperty("--gx",px*100+"%");c.style.setProperty("--gy",py*100+"%")}c.addEventListener("pointermove",function(e){var b=c.getBoundingClientRect();c.classList.add("on");set((e.clientX-b.left)/b.width,(e.clientY-b.top)/b.height)});c.addEventListener("pointerleave",function(){c.classList.remove("on");c.style.transform=""});if(!matchMedia("(hover:hover)").matches){var t0=i;setInterval(function(){t0+=.05;set(.5+Math.sin(t0)*.3,.5+Math.cos(t0)*.3)},60)}})})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Graphite 3D Tilt Cards · Yoga studio — build prompt (VitalStack Motion Studio)

## Goal
Build a cards section for **Still Point Yoga**, a yoga studio brand: Feature cards for a yoga studio brand tilt in 3D toward the pointer with a moving glare highlight.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Graphite Gold" as CSS custom properties: --bg #0d0d0f, --surface #1a1a1e, --ink #f6f3ea, --muted #a8a49a, --a1 #eab308 (primary), --a2 #f59e0b (secondary), --a3 #e5e7eb (highlight). Dark theme.
- Type: Developer mono system stack (`ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace`), headings weight 700, letter-spacing -.04em; body 16 px, line-height 1.5.
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
1. Each card listens to pointermove, maps the pointer to rotateX/rotateY of up to 12° inside perspective(900px), and moves a radial-gradient glare to the pointer.
2. On pointerleave the card eases back to flat with a 400ms transition.
3. Inner layers use translateZ for depth; on touch devices the cards gently auto-tilt.

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

Keywords: 3d tilt perspective cards hover glare, yoga studio
VSAI,
);
