<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sky Pricing Toggle · Fashion</title><style>:root{--bg:#f0f7ff;--surface:#ffffff;--ink:#0b1b33;--muted:#53657d;--a1:#2563eb;--a2:#0891b2;--a3:#f97316;--line:rgba(0,0,0,.1);--on:#ffffff;--font:"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif;--hw:700;--ls:-.025em;color-scheme:light}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}main{max-width:1060px;margin:0 auto;padding:40px 20px 60px;text-align:center}main h2{font-size:clamp(28px,4.4vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:10px 0}main>p{color:var(--muted)}.sw{position:relative;display:inline-flex;margin:22px 0 34px;padding:4px;border-radius:99px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line)}.sw button{all:unset;cursor:pointer;position:relative;z-index:1;padding:10px 20px;font-weight:700;font-size:14px;width:110px;text-align:center}.sw button:focus-visible{outline:2px solid var(--a2);border-radius:99px}.pill{position:absolute;top:4px;left:4px;width:110px;height:calc(100% - 8px);border-radius:99px;background:var(--a1);transition:transform .35s cubic-bezier(.3,1.3,.5,1)}[data-period=y] .pill{transform:translateX(110px)}.sw button[aria-pressed=true]{color:var(--on)}.save{display:inline-block;margin-left:6px;font-size:11px;padding:2px 6px;border-radius:6px;background:var(--a3);color:#111}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,260px),1fr));gap:18px;align-items:stretch}.pl{position:relative;padding:28px;border-radius:22px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);text-align:left;display:flex;flex-direction:column}.pl.hot{isolation:isolate;transform:translateY(-8px)}.pl.hot::before{content:"";position:absolute;inset:-2px;border-radius:24px;z-index:-1;background:conic-gradient(from var(--a,0deg),var(--a1),var(--a2),var(--a3),var(--a1));animation:spin 4s linear infinite}.pl.hot::after{content:"";position:absolute;inset:0;border-radius:22px;z-index:-1;background:var(--surface)}@property --a{syntax:"<angle>";inherits:false;initial-value:0deg}@keyframes spin{to{--a:360deg}}.pr{display:flex;align-items:baseline;gap:2px;margin:10px 0 16px;font-size:48px;font-weight:800;letter-spacing:-.03em;overflow:hidden;height:62px}.pr small{font-size:14px;color:var(--muted);font-weight:500}.val{display:inline-block;transition:transform 500ms,opacity 500ms}.val.out{transform:translateY(-100%);opacity:0}.val.in{transform:translateY(100%);opacity:0;transition:none}.pl ul{list-style:none;padding:0;margin:0 0 22px;color:var(--muted);flex:1}.pl li{padding:6px 0 6px 24px;position:relative}.pl li::before{content:"✓";position:absolute;left:0;color:var(--a1);font-weight:800}.pl .btn{justify-content:center}@media(prefers-reduced-motion:reduce){.pl.hot::before{animation:none}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Atelier Nord</b><span class="links"><a href="#">Outerwear</a><a href="#">Knitwear</a><a href="#">Linen</a></span><a class="btn sm" href="#">Shop collection</a></nav><main id="pm" data-period="m"><p class="eyebrow">Atelier Nord pricing</p><h2>Simple plans for every stage</h2><p>Small-batch clothing in natural fabrics, cut to last for years, not seasons.</p><div class="sw" role="group" aria-label="Billing period"><i class="pill"></i><button type="button" aria-pressed="true" data-p="m">Monthly</button><button type="button" aria-pressed="false" data-p="y">Yearly<span class="save">−17%</span></button></div><div class="grid"><article class="pl"><h3>Starter</h3><p class="pr"><span class="cur">$</span><span class="val" data-m="9" data-y="90">9</span><small data-per>/month</small></p><ul><li>Outerwear</li><li>Knitwear</li><li>Linen</li></ul><a class="btn ghost" href="#">Shop collection</a></article><article class="pl hot"><h3>Pro</h3><p class="pr"><span class="cur">$</span><span class="val" data-m="29" data-y="290">29</span><small data-per>/month</small></p><ul><li>Outerwear</li><li>Knitwear</li><li>Linen</li><li>Denim</li></ul><a class="btn" href="#">Shop collection</a></article><article class="pl"><h3>Team</h3><p class="pr"><span class="cur">$</span><span class="val" data-m="79" data-y="790">79</span><small data-per>/month</small></p><ul><li>Outerwear</li><li>Knitwear</li><li>Linen</li><li>Denim</li><li>Accessories</li></ul><a class="btn ghost" href="#">Shop collection</a></article></div></main><script>(function(){var m=document.getElementById("pm"),D=500;function set(p){if(m.dataset.period===p)return;m.dataset.period=p;m.querySelectorAll(".sw button").forEach(function(b){b.setAttribute("aria-pressed",b.dataset.p===p)});m.querySelectorAll(".val").forEach(function(v){v.classList.add("out");setTimeout(function(){v.textContent=Number(v.dataset[p]).toLocaleString("en-IN");v.classList.remove("out");v.classList.add("in");void v.offsetWidth;v.classList.remove("in")},D)});m.querySelectorAll("[data-per]").forEach(function(s){s.textContent=p==="y"?"/year":"/month"})}m.querySelectorAll(".sw button").forEach(function(b){b.addEventListener("click",function(){set(b.dataset.p)})});var k=0;var iv=setInterval(function(){set(++k%2?"y":"m")},3500);m.addEventListener("pointerdown",function(){clearInterval(iv)},{once:true})})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Sky Pricing Toggle · Fashion — build prompt (VitalStack Motion Studio)

## Goal
Build a pricing section for **Atelier Nord**, a fashion brand: A fashion pricing table with a sliding monthly/yearly switch; prices roll to the new value.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Sky Daylight" as CSS custom properties: --bg #f0f7ff, --surface #ffffff, --ink #0b1b33, --muted #53657d, --a1 #2563eb (primary), --a2 #0891b2 (secondary), --a3 #f97316 (highlight). Light theme.
- Type: Humanist system stack (`"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif`), headings weight 700, letter-spacing -.025em; body 16 px, line-height 1.5.
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
1. A two-option switch moves a pill with transform; the selected period is stored on the wrapper as data-period.
2. Each price rolls: the old number slides up and fades while the new one slides in from below (500ms).
3. The featured plan floats with a soft animated gradient border made with a rotating conic-gradient.

Note: Prices are examples; the yearly price is 10 × monthly (two months free).

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

Keywords: pricing table toggle monthly yearly animated, fashion
VSAI,
);
