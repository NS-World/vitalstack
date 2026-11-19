<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Peach Zoom Gallery + Lightbox · Fashion</title><style>:root{--bg:#fff5ef;--surface:#ffffff;--ink:#2b1610;--muted:#7d5a4e;--a1:#f97316;--a2:#ec4899;--a3:#8b5cf6;--line:rgba(0,0,0,.1);--on:#ffffff;--font:Georgia,"Iowan Old Style","Times New Roman",serif;--hw:700;--ls:-.02em;color-scheme:light}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}main{max-width:1150px;margin:0 auto;padding:30px 20px 60px}main h2{font-size:clamp(28px,4.4vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:0 0 22px}.ms{columns:3 220px;column-gap:14px}.ti{all:unset;box-sizing:border-box;cursor:zoom-in;position:relative;display:block;width:100%;margin:0 0 14px;border-radius:18px;overflow:hidden;break-inside:avoid}.ti:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.art{position:absolute;inset:0;transition:transform .6s cubic-bezier(.2,.8,.2,1)}.ti:hover .art{transform:scale(1.08)}.cap{position:absolute;left:10px;right:10px;bottom:10px;padding:8px 12px;border-radius:10px;background:#000a;color:#fff;font-size:14px;transform:translateY(130%);transition:transform .35s}.ti:hover .cap,.ti:focus .cap{transform:none}dialog{border:0;padding:0;background:transparent;max-width:min(92vw,900px);width:100%}dialog::backdrop{background:#000c;backdrop-filter:blur(4px)}dialog .big{height:min(80vh,620px);border-radius:22px;animation:pop .45s cubic-bezier(.2,1.2,.3,1)}dialog p{color:#fff;text-align:center;font-weight:700}@keyframes pop{from{transform:scale(.6);opacity:0}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Atelier Nord</b><span class="links"><a href="#">Outerwear</a><a href="#">Knitwear</a><a href="#">Linen</a></span><a class="btn sm" href="#">Shop collection</a></nav><main><h2>Made slowly. Worn forever.</h2><div class="ms"><button class="ti" type="button" style="height:360px"><span class="art" style="background:radial-gradient(circle at 30% 30%,hsl(252 90% 70%),transparent 50%),linear-gradient(160deg,hsl(292 70% 45%),hsl(342 60% 20%))"></span><span class="cap">Outerwear</span></button><button class="ti" type="button" style="height:240px"><span class="art" style="background:repeating-linear-gradient(45deg,hsl(120 70% 55%) 0 14px,hsl(150 70% 40%) 14px 28px)"></span><span class="cap">Knitwear</span></button><button class="ti" type="button" style="height:300px"><span class="art" style="background:conic-gradient(from 48deg,hsl(48 80% 60%),hsl(168 80% 50%),hsl(48 80% 60%))"></span><span class="cap">Linen</span></button><button class="ti" type="button" style="height:360px"><span class="art" style="background:radial-gradient(circle at 30% 30%,hsl(110 90% 70%),transparent 50%),linear-gradient(160deg,hsl(150 70% 45%),hsl(200 60% 20%))"></span><span class="cap">Denim</span></button><button class="ti" type="button" style="height:240px"><span class="art" style="background:repeating-linear-gradient(45deg,hsl(79 70% 55%) 0 14px,hsl(109 70% 40%) 14px 28px)"></span><span class="cap">Accessories</span></button><button class="ti" type="button" style="height:360px"><span class="art" style="background:conic-gradient(from 109deg,hsl(109 80% 60%),hsl(229 80% 50%),hsl(109 80% 60%))"></span><span class="cap">Footwear</span></button><button class="ti" type="button" style="height:180px"><span class="art" style="background:radial-gradient(circle at 30% 30%,hsl(211 90% 70%),transparent 50%),linear-gradient(160deg,hsl(251 70% 45%),hsl(301 60% 20%))"></span><span class="cap">Outerwear</span></button><button class="ti" type="button" style="height:240px"><span class="art" style="background:repeating-linear-gradient(45deg,hsl(106 70% 55%) 0 14px,hsl(136 70% 40%) 14px 28px)"></span><span class="cap">Knitwear</span></button><button class="ti" type="button" style="height:180px"><span class="art" style="background:conic-gradient(from 354deg,hsl(354 80% 60%),hsl(114 80% 50%),hsl(354 80% 60%))"></span><span class="cap">Linen</span></button><button class="ti" type="button" style="height:180px"><span class="art" style="background:radial-gradient(circle at 30% 30%,hsl(69 90% 70%),transparent 50%),linear-gradient(160deg,hsl(109 70% 45%),hsl(159 60% 20%))"></span><span class="cap">Denim</span></button></div></main><dialog id="lb"><div class="big" id="bg"></div><p id="lc"></p></dialog><script>(function(){var d=document.getElementById("lb"),bg=document.getElementById("bg"),lc=document.getElementById("lc");document.querySelectorAll(".ti").forEach(function(t){t.addEventListener("click",function(){bg.style.background=t.querySelector(".art").style.background;lc.textContent=t.querySelector(".cap").textContent;d.showModal()})});d.addEventListener("click",function(){d.close()})})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Peach Zoom Gallery + Lightbox · Fashion — build prompt (VitalStack Motion Studio)

## Goal
Build a gallery section for **Atelier Nord**, a fashion brand: A fashion masonry gallery of art tiles that zoom on hover and open in a smooth lightbox.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Peach Sorbet" as CSS custom properties: --bg #fff5ef, --surface #ffffff, --ink #2b1610, --muted #7d5a4e, --a1 #f97316 (primary), --a2 #ec4899 (secondary), --a3 #8b5cf6 (highlight). Light theme.
- Type: Editorial serif system stack (`Georgia,"Iowan Old Style","Times New Roman",serif`), headings weight 700, letter-spacing -.02em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Atelier Nord
- Headline: “Made slowly. Worn forever.”
- Sub-copy: “Small-batch clothing in natural fabrics, cut to last for years, not seasons.”
- Primary button: “Shop collection”; secondary: “Lookbook”
- Items: Outerwear, Knitwear, Linen, Denim, Accessories, Footwear
- Stats: 100% natural fibres; 12 pieces per drop; 0 fast fashion
- Testimonial: “My coat from them is five years old and still looks new.” — Isha T., Bengaluru

## Layout
- Top navigation: brand on the left, 3 links (Outerwear, Knitwear, Linen) and a small “Shop collection” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. CSS columns (3 on desktop) create a masonry layout of CSS-art tiles (layered gradients, no images).
2. Hover scales the art 1.08 inside its frame and reveals a caption bar.
3. Clicking a tile opens a <dialog> lightbox that animates from the tile size with the View Transitions-style scale trick; Esc or click closes it.

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

Keywords: gallery masonry hover zoom lightbox grid, fashion
VSAI,
);
