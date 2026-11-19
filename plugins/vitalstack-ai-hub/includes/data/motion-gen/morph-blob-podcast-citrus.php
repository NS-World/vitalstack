<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Citrus Morphing Blob · Podcast</title><style>:root{--bg:#fffbea;--surface:#ffffff;--ink:#1f1a05;--muted:#6f6640;--a1:#eab308;--a2:#16a34a;--a3:#ef4444;--line:rgba(0,0,0,.1);--on:#0b0b10;--font:ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace;--hw:700;--ls:-.04em;color-scheme:light}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}body{min-height:100vh}.wrap{display:grid;grid-template-columns:1.1fr 1fr;align-items:center;max-width:1200px;margin:0 auto;min-height:calc(100vh - 80px)}.art{position:relative;display:grid;place-items:center;height:60vmin}.blob,.glow{position:absolute;width:44vmin;height:44vmin;background:linear-gradient(135deg,var(--a1),var(--a2) 60%,var(--a3));animation:mo 8s ease-in-out infinite}.glow{filter:blur(40px);opacity:.6;animation-delay:-2s}@keyframes mo{0%{border-radius:60% 40% 30% 70%/60% 30% 70% 40%}0%{border-radius:42% 58% 63% 37%/70% 61% 39% 30%}25%{border-radius:53% 47% 39% 61%/43% 54% 46% 57%}50%{border-radius:39% 61% 57% 43%/68% 60% 40% 32%}75%{border-radius:53% 47% 54% 46%/68% 48% 52% 32%}to{border-radius:60% 40% 30% 70%/60% 30% 70% 40%;transform:rotate(360deg)}}@media(max-width:820px){.wrap{grid-template-columns:1fr}.art{height:46vmin;order:-1}.blob,.glow{width:38vmin;height:38vmin}}@media(prefers-reduced-motion:reduce){.blob,.glow{animation:none}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.copy{position:relative;z-index:2;max-width:640px;padding:clamp(40px,9vh,110px) clamp(16px,5vw,64px)}</style></head><body><nav class="nav"><b class="logo">Signal &amp; Noise</b><span class="links"><a href="#">Latest episode</a><a href="#">Interviews</a><a href="#">Deep dives</a></span><a class="btn sm" href="#">Listen now</a></nav><div class="wrap"><div class="copy"><p class="eyebrow">Podcast</p><h1>Big ideas in 30 minutes</h1><p class="sub">A weekly podcast about technology, culture and the people building the future.</p><div class="row"><a class="btn" href="#">Listen now</a><a class="btn ghost" href="#">All episodes</a></div></div><div class="art" aria-hidden="true"><i class="glow"></i><i class="blob"></i></div></div></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Citrus Morphing Blob · Podcast — build prompt (VitalStack Motion Studio)

## Goal
Build a hero section for **Signal & Noise**, a podcast brand: An organic gradient blob morphs its shape and slowly spins next to a podcast headline.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Citrus Pop" as CSS custom properties: --bg #fffbea, --surface #ffffff, --ink #1f1a05, --muted #6f6640, --a1 #eab308 (primary), --a2 #16a34a (secondary), --a3 #ef4444 (highlight). Light theme.
- Type: Developer mono system stack (`ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace`), headings weight 700, letter-spacing -.04em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Signal & Noise
- Headline: “Big ideas in 30 minutes”
- Sub-copy: “A weekly podcast about technology, culture and the people building the future.”
- Primary button: “Listen now”; secondary: “All episodes”
- Items: Latest episode, Interviews, Deep dives, Q&A, Newsletter, Live shows
- Stats: 180 episodes; 2M listens; Top 10 tech charts
- Testimonial: “My favourite 30 minutes of the week.” — Listener review

## Layout
- Top navigation: brand on the left, 3 links (Latest episode, Interviews, Deep dives) and a small “Listen now” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. The blob is a div whose 8-value border-radius cycles through 4 keyframe shapes over 8s while rotating.
2. A second, blurred copy behind it creates a soft glow; both use the brand gradient.
3. Layout is two columns on desktop and stacks on phones.

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

Keywords: blob morph border-radius organic shape hero, podcast
VSAI,
);
