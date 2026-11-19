<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Sand Glitch Headline · Fintech</title><style>:root{--bg:#f6f1e7;--surface:#fffdf8;--ink:#2a2418;--muted:#7a6f5a;--a1:#b45309;--a2:#0f766e;--a3:#be123c;--line:rgba(0,0,0,.1);--on:#ffffff;--font:"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif;--hw:700;--ls:-.025em;color-scheme:light}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}body{min-height:100vh;display:flex;flex-direction:column;position:relative}body::after{content:"";position:fixed;inset:0;pointer-events:none;background:repeating-linear-gradient(0deg,#0000 0 2px,#0000000f 2px 3px)}main{flex:1;display:grid;place-items:center;text-align:center;padding:24px}.gl{position:relative;display:inline-block}.gl::before,.gl::after{content:attr(data-text);position:absolute;inset:0;opacity:0}.gl::before{color:var(--a1);transform:translate(-3px,0)}.gl::after{color:var(--a2);transform:translate(3px,0)}.gl.on::before{opacity:.9;animation:g1 .6s steps(2) both}.gl.on::after{opacity:.9;animation:g2 .6s steps(2) both}@keyframes g1{0%{clip-path:inset(10% 0 70% 0)}25%{clip-path:inset(60% 0 10% 0);transform:translate(-6px,2px)}50%{clip-path:inset(30% 0 40% 0)}75%{clip-path:inset(80% 0 5% 0);transform:translate(4px,-1px)}100%{clip-path:inset(0 0 100% 0)}}@keyframes g2{0%{clip-path:inset(70% 0 10% 0)}25%{clip-path:inset(5% 0 75% 0);transform:translate(6px,-2px)}50%{clip-path:inset(45% 0 30% 0)}75%{clip-path:inset(15% 0 60% 0);transform:translate(-4px,1px)}100%{clip-path:inset(100% 0 0 0)}}.sub{margin:0 auto}.row{justify-content:center}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Ledgerly</b><span class="links"><a href="#">Instant UPI</a><a href="#">Smart budgets</a><a href="#">Cashback card</a></span><a class="btn sm" href="#">Open account</a></nav><main><div><p class="eyebrow">Fintech</p><h1><span class="gl" data-text="Money that moves as fast as you do">Money that moves as fast as you do</span></h1><p class="sub">Instant transfers, smart budgets and a card that pays you back, all in one app.</p><div class="row"><a class="btn" href="#">Open account</a><a class="btn ghost" href="#">See pricing</a></div></div></main><script>(function(){if(matchMedia("(prefers-reduced-motion: reduce)").matches)return;var g=document.querySelector(".gl");function b(){g.classList.remove("on");void g.offsetWidth;g.classList.add("on")}setTimeout(b,600);setInterval(b,5000)})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Sand Glitch Headline · Fintech — build prompt (VitalStack Motion Studio)

## Goal
Build a text effect section for **Ledgerly**, a fintech brand: A fintech headline that glitches with RGB-split slices every few seconds, cyber style.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Sand Studio" as CSS custom properties: --bg #f6f1e7, --surface #fffdf8, --ink #2a2418, --muted #7a6f5a, --a1 #b45309 (primary), --a2 #0f766e (secondary), --a3 #be123c (highlight). Light theme.
- Type: Humanist system stack (`"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif`), headings weight 700, letter-spacing -.025em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Ledgerly
- Headline: “Money that moves as fast as you do”
- Sub-copy: “Instant transfers, smart budgets and a card that pays you back, all in one app.”
- Primary button: “Open account”; secondary: “See pricing”
- Items: Instant UPI, Smart budgets, Cashback card, Bill reminders, Savings pots, Tax reports
- Stats: ₹0 monthly fee; 2M+ customers; 4.8★ app rating
- Testimonial: “Finally a bank app that I actually enjoy opening.” — Arjun M., freelancer

## Layout
- Top navigation: brand on the left, 3 links (Instant UPI, Smart budgets, Cashback card) and a small “Open account” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. Two ::before/::after copies of the headline (via data-text) are tinted --a1 and --a2 and offset a few pixels.
2. Every 5s a short burst animates clip-path inset() slices and translate on each copy for about 600ms.
3. Scanlines are a repeating-linear-gradient overlay; reduced motion disables the bursts.

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

Keywords: glitch text rgb split cyber headline, fintech
VSAI,
);
