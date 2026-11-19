<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Arctic Parallax Depth Layers · AI SaaS</title><style>:root{--bg:#020617;--surface:#0f1b33;--ink:#eef4ff;--muted:#94a3b8;--a1:#3b82f6;--a2:#60a5fa;--a3:#c084fc;--line:rgba(255,255,255,.12);--on:#ffffff;--font:"Arial Narrow","Roboto Condensed","Helvetica Neue",Arial,sans-serif;--hw:800;--ls:-.01em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.scene{position:relative;height:120vh;overflow:hidden;background:linear-gradient(180deg,var(--surface),color-mix(in srgb,var(--a2) 30%,var(--bg)))}.sun{position:absolute;left:50%;top:36%;width:22vmin;height:22vmin;margin-left:-11vmin;border-radius:50%;background:radial-gradient(circle,var(--a3),color-mix(in srgb,var(--a3) 20%,transparent) 70%)}.ly{position:absolute;left:0;bottom:-10vh;width:100%;height:100vh;will-change:transform}.ttl{position:absolute;inset:0;display:grid;place-items:start center;text-align:center;padding:16vh 24px 24px;z-index:1}.ttl h1{max-width:14ch;margin:10px auto}.after{position:relative;z-index:6;background:color-mix(in srgb,var(--a1) 15%,var(--bg));padding:12vh 20px;text-align:center}.after p{color:var(--muted);max-width:56ch;margin:10px auto 24px}.nav{position:absolute;left:0;right:0;top:0}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.ly:nth-of-type(1){z-index:2}.ly:nth-of-type(2){z-index:3}.ly:nth-of-type(3){z-index:4}.ly:nth-of-type(4){z-index:5}.ly:nth-of-type(5){z-index:6}</style></head><body><section class="scene"><nav class="nav"><b class="logo">Nimbus AI</b><span class="links"><a href="#">Autocomplete</a><a href="#">Code review</a><a href="#">Test writer</a></span><a class="btn sm" href="#">Start free</a></nav><div class="sun" data-speed="0.05"></div><div class="ttl" data-speed="-0.3"><div><p class="eyebrow">AI SaaS</p><h1>Ship features at the speed of thought</h1></div></div><svg class="ly" data-speed="0.15" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"><path d="M0 40 Q 25 28 50 40 T 100 36 V 100 H 0 Z" fill="color-mix(in srgb,var(--a1) 85%,var(--bg))"/></svg><svg class="ly" data-speed="0.33" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"><path d="M0 49 Q 25 35 50 49 T 100 45 V 100 H 0 Z" fill="color-mix(in srgb,var(--a1) 70%,var(--bg))"/></svg><svg class="ly" data-speed="0.51" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"><path d="M0 58 Q 25 42 50 58 T 100 54 V 100 H 0 Z" fill="color-mix(in srgb,var(--a1) 55%,var(--bg))"/></svg><svg class="ly" data-speed="0.69" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"><path d="M0 67 Q 25 49 50 67 T 100 63 V 100 H 0 Z" fill="color-mix(in srgb,var(--a1) 40%,var(--bg))"/></svg><svg class="ly" data-speed="0.87" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"><path d="M0 76 Q 25 56 50 76 T 100 72 V 100 H 0 Z" fill="color-mix(in srgb,var(--a1) 25%,var(--bg))"/></svg></section><section class="after"><h2>Nimbus AI</h2><p>An AI copilot that writes, tests and reviews your code while you focus on the product.</p><a class="btn" href="#">Start free</a></section><script>(function(){if(matchMedia("(prefers-reduced-motion: reduce)").matches)return;var L=document.querySelectorAll("[data-speed]"),tk=0;function f(){tk=0;var y=scrollY;L.forEach(function(l){l.style.transform="translateY("+(y*parseFloat(l.dataset.speed))+"px)"})}addEventListener("scroll",function(){if(!tk){tk=1;requestAnimationFrame(f)}},{passive:true});f()})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Arctic Parallax Depth Layers · AI SaaS — build prompt (VitalStack Motion Studio)

## Goal
Build a scroll story section for **Nimbus AI**, a ai saas brand: Layered shapes of a stylised landscape move at different speeds as you scroll the AI SaaS page.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Arctic Blue" as CSS custom properties: --bg #020617, --surface #0f1b33, --ink #eef4ff, --muted #94a3b8, --a1 #3b82f6 (primary), --a2 #60a5fa (secondary), --a3 #c084fc (highlight). Dark theme.
- Type: Condensed display system stack (`"Arial Narrow","Roboto Condensed","Helvetica Neue",Arial,sans-serif`), headings weight 800, letter-spacing -.01em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Nimbus AI
- Headline: “Ship features at the speed of thought”
- Sub-copy: “An AI copilot that writes, tests and reviews your code while you focus on the product.”
- Primary button: “Start free”; secondary: “Watch demo”
- Items: Autocomplete, Code review, Test writer, Docs bot, Bug finder, Deploy check
- Stats: 10x faster reviews; 42k developers; 99.9% uptime
- Testimonial: “We cut our review time in half in the first week.” — Priya S., CTO at Loopline

## Layout
- Top navigation: brand on the left, 3 links (Autocomplete, Code review, Test writer) and a small “Start free” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. 5 absolutely positioned SVG/CSS layers each carry a data-speed between 0.1 and 0.9.
2. On scroll, each layer translates on Y by scrollY × speed using requestAnimationFrame batching.
3. The headline fades and lifts away while the foreground hills cover it.

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

Keywords: parallax scroll layers depth shapes landscape, ai saas
VSAI,
);
