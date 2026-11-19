<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Arctic Bento Grid Glow · Fintech</title><style>:root{--bg:#020617;--surface:#0f1b33;--ink:#eef4ff;--muted:#94a3b8;--a1:#3b82f6;--a2:#60a5fa;--a3:#c084fc;--line:rgba(255,255,255,.12);--on:#ffffff;--font:"Arial Narrow","Roboto Condensed","Helvetica Neue",Arial,sans-serif;--hw:800;--ls:-.01em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}main{max-width:1100px;margin:0 auto;padding:36px 20px 60px}h2{font-size:clamp(28px,4.4vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:0 0 24px;max-width:18ch}.grid{display:grid;grid-template-columns:repeat(4,1fr);grid-auto-rows:170px;gap:14px}.big{grid-column:span 2;grid-row:span 2}.wide{grid-column:span 2}@property --ang{syntax:"<angle>";inherits:false;initial-value:0deg}.bt{position:relative;isolation:isolate;padding:24px;border-radius:22px;display:flex;flex-direction:column;justify-content:flex-end;transition:transform .3s}.bt:hover{transform:translateY(-4px)}.bt::before{content:"";position:absolute;inset:0;border-radius:22px;padding:1.5px;background:conic-gradient(from var(--ang),transparent 0 60%,var(--a1),var(--a2),transparent);-webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);-webkit-mask-composite:xor;mask-composite:exclude;animation:rot 9s linear infinite;animation-delay:var(--d)}.bt::after{content:"";position:absolute;inset:0;border-radius:22px;z-index:-1;background:radial-gradient(circle at 80% 0%,color-mix(in srgb,var(--a1) 22%,transparent),transparent 60%),var(--surface);box-shadow:inset 0 0 0 1px var(--line);transition:filter .3s}.bt:hover::after{filter:brightness(1.15)}@keyframes rot{to{--ang:360deg}}.bt h3{margin:0 0 6px;font-size:20px}.bt p{margin:0;color:var(--muted)}.big strong{font-size:clamp(48px,7vw,88px);letter-spacing:-.05em;order:-1;margin-bottom:auto;color:var(--a1)}@media(max-width:760px){.grid{grid-template-columns:1fr;grid-auto-rows:auto}.big,.wide{grid-column:auto;grid-row:auto}.bt{min-height:150px}}@media(prefers-reduced-motion:reduce){.bt::before{animation:none}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Ledgerly</b><span class="links"><a href="#">Instant UPI</a><a href="#">Smart budgets</a><a href="#">Cashback card</a></span><a class="btn sm" href="#">Open account</a></nav><main><h2>Money that moves as fast as you do</h2><div class="grid"><article class="bt big" style="--d:-0s"><h3>Instant UPI</h3><p>Instant transfers, smart budgets and a card that pays you back, all in one app.</p><strong>₹0</strong></article><article class="bt wide" style="--d:-1s"><h3>Smart budgets</h3><p>Customers</p></article><article class="bt " style="--d:-2s"><h3>Cashback card</h3><p>App rating</p></article><article class="bt " style="--d:-3s"><h3>Bill reminders</h3><p>Monthly fee</p></article><article class="bt wide" style="--d:-4s"><h3>Savings pots</h3><p>Customers</p></article><article class="bt " style="--d:-5s"><h3>Tax reports</h3><p>App rating</p></article></div></main></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Arctic Bento Grid Glow · Fintech — build prompt (VitalStack Motion Studio)

## Goal
Build a cards section for **Ledgerly**, a fintech brand: An asymmetric fintech bento grid whose tiles have slowly rotating gradient borders and pop on hover.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Arctic Blue" as CSS custom properties: --bg #020617, --surface #0f1b33, --ink #eef4ff, --muted #94a3b8, --a1 #3b82f6 (primary), --a2 #60a5fa (secondary), --a3 #c084fc (highlight). Dark theme.
- Type: Condensed display system stack (`"Arial Narrow","Roboto Condensed","Helvetica Neue",Arial,sans-serif`), headings weight 800, letter-spacing -.01em; body 16 px, line-height 1.5.
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
1. A CSS grid with spans (2×2, 2×1, 1×1) builds the bento layout; it collapses to one column on phones.
2. Each tile has a ::before layer with a conic-gradient that rotates using a registered @property angle (speed 9s).
3. Hover lifts the tile 4px and brightens its inner gradient.

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

Keywords: bento grid conic border glow features, fintech
VSAI,
);
