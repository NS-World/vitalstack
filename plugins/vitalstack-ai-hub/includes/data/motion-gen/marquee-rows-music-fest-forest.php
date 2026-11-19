<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Forest Infinite Marquee · Music festival</title><style>:root{--bg:#06140e;--surface:#0d2419;--ink:#ecfff5;--muted:#97c4ad;--a1:#22c55e;--a2:#84cc16;--a3:#2dd4bf;--line:rgba(255,255,255,.12);--on:#ffffff;--font:ui-rounded,"SF Pro Rounded","Nunito","Segoe UI",system-ui,sans-serif;--hw:800;--ls:-.02em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}body{min-height:100vh;display:flex;flex-direction:column}main{flex:1;display:flex;flex-direction:column;justify-content:center;gap:22px;padding:40px 0}.lead{text-align:center;padding:0 20px;margin-bottom:10px}.lead h2{font-size:clamp(26px,4vw,48px);letter-spacing:var(--ls);font-weight:var(--hw);margin:0 0 8px}.lead p{color:var(--muted)}.track{overflow:hidden;-webkit-mask-image:linear-gradient(90deg,transparent,#000 12%,#000 88%,transparent);mask-image:linear-gradient(90deg,transparent,#000 12%,#000 88%,transparent)}.belt{display:flex;width:max-content;align-items:center;gap:28px;animation:mq 30s linear infinite}.track:hover .belt{animation-play-state:paused}.track.rev .belt{animation-direction:reverse}.belt span{font-size:clamp(28px,6vw,72px);font-weight:var(--hw);letter-spacing:var(--ls);white-space:nowrap}.track.rev .belt span{-webkit-text-stroke:1.5px var(--ink);color:transparent}.belt i{font-style:normal;color:var(--a1);font-size:clamp(18px,3vw,32px)}@keyframes mq{to{transform:translateX(-50%)}}@media(prefers-reduced-motion:reduce){.belt{animation:none}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.cta{text-align:center;margin-top:18px}</style></head><body><nav class="nav"><b class="logo">Solstice Fest</b><span class="links"><a href="#">Main stage</a><a href="#">Night garden</a><a href="#">Silent disco</a></span><a class="btn sm" href="#">Get passes</a></nav><main><div class="lead"><h2>Three nights. One sky. Forty artists.</h2><p>A weekend of live music, art and food under the open sky, 14 to 16 March.</p></div><div class="track"><div class="belt"><span>Main stage</span><i>◆</i><span>Night garden</span><i>◆</i><span>Silent disco</span><i>◆</i><span>Art walk</span><i>◆</i><span>Food court</span><i>◆</i><span>Camping</span><i>◆</i><span>Main stage</span><i>◆</i><span>Night garden</span><i>◆</i><span>Silent disco</span><i>◆</i><span>Art walk</span><i>◆</i><span>Food court</span><i>◆</i><span>Camping</span><i>◆</i></div></div><div class="track rev"><div class="belt"><span>Food court</span><i>◆</i><span>Camping</span><i>◆</i><span>louder</span><i>◆</i><span>brighter</span><i>◆</i><span>freer</span><i>◆</i><span>alive</span><i>◆</i><span>Food court</span><i>◆</i><span>Camping</span><i>◆</i><span>louder</span><i>◆</i><span>brighter</span><i>◆</i><span>freer</span><i>◆</i><span>alive</span><i>◆</i></div></div><div class="cta"><a class="btn" href="#">Get passes</a></div></main></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Forest Infinite Marquee · Music festival — build prompt (VitalStack Motion Studio)

## Goal
Build a interactive section for **Solstice Fest**, a music festival brand: Two endless rows of music festival keywords glide in opposite directions and slow down on hover.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Forest Mist" as CSS custom properties: --bg #06140e, --surface #0d2419, --ink #ecfff5, --muted #97c4ad, --a1 #22c55e (primary), --a2 #84cc16 (secondary), --a3 #2dd4bf (highlight). Dark theme.
- Type: Friendly rounded system stack (`ui-rounded,"SF Pro Rounded","Nunito","Segoe UI",system-ui,sans-serif`), headings weight 800, letter-spacing -.02em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Solstice Fest
- Headline: “Three nights. One sky. Forty artists.”
- Sub-copy: “A weekend of live music, art and food under the open sky, 14 to 16 March.”
- Primary button: “Get passes”; secondary: “Line-up”
- Items: Main stage, Night garden, Silent disco, Art walk, Food court, Camping
- Stats: 40 artists; 3 stages; 25k fans
- Testimonial: “The sunrise set on day two changed my life.” — Kabir, festival regular

## Layout
- Top navigation: brand on the left, 3 links (Main stage, Night garden, Silent disco) and a small “Get passes” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. Each row duplicates its content once and animates translateX(0 → -50%) linearly in 30s, so the loop is seamless.
2. The second row runs in reverse; hovering a row sets animation-play-state to paused.
3. Edges fade out with a CSS mask-image linear-gradient.

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

Keywords: marquee ticker infinite scroll logos words, music festival
VSAI,
);
