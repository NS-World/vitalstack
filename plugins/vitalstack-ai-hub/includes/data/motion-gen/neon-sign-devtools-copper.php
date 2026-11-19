<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Copper Neon Sign Flicker · Developer tools</title><style>:root{--bg:#120c0a;--surface:#221814;--ink:#fbefe7;--muted:#c2a796;--a1:#ea580c;--a2:#d97706;--a3:#fbbf24;--line:rgba(255,255,255,.12);--on:#ffffff;--font:ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace;--hw:700;--ls:-.04em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}body{min-height:100vh;display:flex;flex-direction:column;background-color:var(--bg);background-image:linear-gradient(335deg,#00000033 23px,transparent 23px),linear-gradient(155deg,#00000022 23px,transparent 23px),linear-gradient(335deg,#00000033 23px,transparent 23px),linear-gradient(155deg,#00000022 23px,transparent 23px);background-size:58px 58px;background-position:0 2px,4px 35px,29px 31px,34px 6px}main{flex:1;display:grid;place-items:center;text-align:center;padding:24px}.sign{padding:clamp(24px,5vw,50px) clamp(24px,6vw,70px);border-radius:24px;border:3px solid color-mix(in srgb,var(--a1) 50%,transparent);box-shadow:0 0 30px -5px var(--a1),inset 0 0 30px -10px var(--a1)}.n1{font-size:clamp(44px,10vw,120px);font-weight:400;letter-spacing:.02em;margin:0;color:#fff;text-shadow:0 0 4px #fff,0 0 10px var(--a1),0 0 22px var(--a1),0 0 44px var(--a1),0 0 80px var(--a1);animation:fk 4s infinite}.n2{font-size:clamp(16px,2.4vw,24px);letter-spacing:.3em;text-transform:uppercase;color:#fff;text-shadow:0 0 6px var(--a2),0 0 18px var(--a2);margin:10px 0 0}@keyframes fk{0%,18%,22%,25%,53%,57%,100%{opacity:1}20%,24%,55%{opacity:.35;text-shadow:none}}.below{margin-top:34px;color:var(--muted)}.below .btn{margin-top:16px}@media(prefers-reduced-motion:reduce){.n1{animation:none}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Stackline</b><span class="links"><a href="#">Previews</a><a href="#">Rollbacks</a><a href="#">Edge cache</a></span><a class="btn sm" href="#">Deploy now</a></nav><main><div><div class="sign"><p class="n1">Stackline</p><p class="n2">faster · safer</p></div><div class="below"><p>Preview environments, instant rollbacks and logs that make sense.</p><a class="btn" href="#">Deploy now</a></div></div></main></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Copper Neon Sign Flicker · Developer tools — build prompt (VitalStack Motion Studio)

## Goal
Build a text effect section for **Stackline**, a developer tools brand: The developer tools name glows like a neon sign on a brick wall, flickers to life and hums softly.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Copper Dusk" as CSS custom properties: --bg #120c0a, --surface #221814, --ink #fbefe7, --muted #c2a796, --a1 #ea580c (primary), --a2 #d97706 (secondary), --a3 #fbbf24 (highlight). Dark theme.
- Type: Developer mono system stack (`ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace`), headings weight 700, letter-spacing -.04em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Stackline
- Headline: “Deploy on push. Sleep at night.”
- Sub-copy: “Preview environments, instant rollbacks and logs that make sense.”
- Primary button: “Deploy now”; secondary: “Read docs”
- Items: Previews, Rollbacks, Edge cache, Logs, Cron jobs, Secrets
- Stats: 30s deploys; 300+ edge locations; 1-click rollback
- Testimonial: “We went from weekly to 40 deploys a day.” — Lead engineer, Pixelcart

## Layout
- Top navigation: brand on the left, 3 links (Previews, Rollbacks, Edge cache) and a small “Deploy now” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. The word uses layered text-shadows in --a1 for the glow; a second line uses --a2.
2. A flicker keyframe (4s cycle) drops opacity and glow for a few random frames, as real neon does.
3. The wall is a CSS brick pattern from two linear-gradients; a sign frame casts a soft light on it.

Note: For best results use a dark palette.

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

Keywords: neon glow sign flicker text shadow night, developer tools
VSAI,
);
