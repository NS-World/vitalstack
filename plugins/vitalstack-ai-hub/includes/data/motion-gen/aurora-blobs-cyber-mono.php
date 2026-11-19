<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Mono Aurora Blobs Hero · Cybersecurity</title><style>:root{--bg:#0a0a0a;--surface:#171717;--ink:#fafafa;--muted:#a3a3a3;--a1:#fafafa;--a2:#a3a3a3;--a3:#525252;--line:rgba(255,255,255,.12);--on:#0b0b10;--font:ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace;--hw:700;--ls:-.04em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.b0{left:15%;top:-8%;width:30vmax;height:30vmax;background:var(--a1);animation-delay:-0s}.b1{left:31%;top:-1%;width:32vmax;height:32vmax;background:var(--a2);animation-delay:-5s}.b2{left:10%;top:39%;width:39vmax;height:39vmax;background:var(--a3);animation-delay:-10s}.b3{left:62%;top:-9%;width:35vmax;height:35vmax;background:var(--a1);animation-delay:-15s}.b4{left:25%;top:26%;width:45vmax;height:45vmax;background:var(--a2);animation-delay:-20s}body{min-height:100vh;overflow:hidden}.blob{position:absolute;border-radius:50%;filter:blur(100px);opacity:.55;mix-blend-mode:screen;animation:drift 14s ease-in-out infinite alternate}@keyframes drift{50%{transform:translate(9vmax,-6vmax) scale(1.2)}to{transform:translate(-6vmax,7vmax) scale(.9)}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.copy{position:relative;z-index:2;max-width:880px;margin:0 auto;text-align:center;padding:clamp(48px,10vh,120px) 20px}.copy .sub{margin:0 auto}.copy .row{justify-content:center}.bg{position:fixed;inset:0;overflow:hidden}</style></head><body><div class="bg" aria-hidden="true"><i class="blob b0"></i><i class="blob b1"></i><i class="blob b2"></i><i class="blob b3"></i><i class="blob b4"></i></div><nav class="nav"><b class="logo">Sentinel Shield</b><span class="links"><a href="#">Endpoint</a><a href="#">Phishing</a><a href="#">Zero trust</a></span><a class="btn sm" href="#">Get protected</a></nav><div class="copy"><p class="eyebrow">Cybersecurity</p><h1>Stop threats before they start</h1><p class="sub">Real-time protection, phishing defence and 24/7 monitoring for growing teams.</p><div class="row"><a class="btn" href="#">Get protected</a><a class="btn ghost" href="#">Free audit</a></div></div></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Mono Aurora Blobs Hero · Cybersecurity — build prompt (VitalStack Motion Studio)

## Goal
Build a hero section for **Sentinel Shield**, a cybersecurity brand: Soft glowing colour blobs drift and blend behind a bold cybersecurity headline.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Mono Contrast" as CSS custom properties: --bg #0a0a0a, --surface #171717, --ink #fafafa, --muted #a3a3a3, --a1 #fafafa (primary), --a2 #a3a3a3 (secondary), --a3 #525252 (highlight). Dark theme.
- Type: Developer mono system stack (`ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace`), headings weight 700, letter-spacing -.04em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Sentinel Shield
- Headline: “Stop threats before they start”
- Sub-copy: “Real-time protection, phishing defence and 24/7 monitoring for growing teams.”
- Primary button: “Get protected”; secondary: “Free audit”
- Items: Endpoint, Phishing, Zero trust, Backups, Monitoring, Training
- Stats: 24/7 monitoring; 3 min avg. response; 0 breaches
- Testimonial: “We blocked a ransomware attempt in the first month.” — IT lead, Medlink

## Layout
- Top navigation: brand on the left, 3 links (Endpoint, Phishing, Zero trust) and a small “Get protected” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. 5 blurred circles (filter: blur(100px), mix-blend-mode: screen) drift on 14s alternate keyframes with different delays.
2. Each blob moves with translate + scale only, so it stays at 60 fps.
3. Content sits above the blobs in a glassy eyebrow pill, headline, sub-copy and two buttons.

Note: Reduced motion: blobs stop.

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

Keywords: gradient blur aurora blobs hero landing, cybersecurity
VSAI,
);
