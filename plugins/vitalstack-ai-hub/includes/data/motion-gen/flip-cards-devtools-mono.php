<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Mono Flip Cards · Developer tools</title><style>:root{--bg:#0a0a0a;--surface:#171717;--ink:#fafafa;--muted:#a3a3a3;--a1:#fafafa;--a2:#a3a3a3;--a3:#525252;--line:rgba(255,255,255,.12);--on:#0b0b10;--font:"Arial Narrow","Roboto Condensed","Helvetica Neue",Arial,sans-serif;--hw:800;--ls:-.01em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}main{max-width:1080px;margin:0 auto;padding:40px 20px 60px}h2{text-align:center;font-size:clamp(28px,4.4vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:0 0 30px}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,220px),1fr));gap:16px}.fc{all:unset;cursor:pointer;display:block;height:230px;perspective:1000px}.fc:focus-visible{outline:2px solid var(--a2);outline-offset:4px;border-radius:20px}.in{position:relative;display:block;width:100%;height:100%;transform-style:preserve-3d;transition:transform 1000ms cubic-bezier(.3,1.4,.5,1)}.fc:hover .in,.fc:focus .in,.fc.on .in{transform:rotateY(180deg)}.f,.bk{position:absolute;inset:0;border-radius:20px;padding:22px;display:flex;flex-direction:column;justify-content:space-between;backface-visibility:hidden;-webkit-backface-visibility:hidden}.f{background:var(--surface);box-shadow:inset 0 0 0 1px var(--line)}.f b{color:var(--a1);font-size:14px}.f strong{font-size:24px}.f small{color:var(--muted)}.bk{background:linear-gradient(140deg,var(--a1),var(--a2));color:var(--on);transform:rotateY(180deg)}.bk strong{font-size:44px;letter-spacing:-.03em}.bk em{font-style:normal;opacity:.9}.bk u{text-decoration:none;font-weight:700}@media(prefers-reduced-motion:reduce){.in{transition:none}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}</style></head><body><nav class="nav"><b class="logo">Stackline</b><span class="links"><a href="#">Previews</a><a href="#">Rollbacks</a><a href="#">Edge cache</a></span><a class="btn sm" href="#">Deploy now</a></nav><main><h2>Deploy on push. Sleep at night.</h2><div class="grid"><button class="fc" type="button" aria-label="Previews"><span class="in"><span class="f"><b>01</b><strong>Previews</strong><small>Tap to flip</small></span><span class="bk"><strong>30s</strong><em>deploys</em><u>Deploy now →</u></span></span></button><button class="fc" type="button" aria-label="Rollbacks"><span class="in"><span class="f"><b>02</b><strong>Rollbacks</strong><small>Tap to flip</small></span><span class="bk"><strong>300+</strong><em>edge locations</em><u>Deploy now →</u></span></span></button><button class="fc" type="button" aria-label="Edge cache"><span class="in"><span class="f"><b>03</b><strong>Edge cache</strong><small>Tap to flip</small></span><span class="bk"><strong>1-click</strong><em>rollback</em><u>Deploy now →</u></span></span></button><button class="fc" type="button" aria-label="Logs"><span class="in"><span class="f"><b>04</b><strong>Logs</strong><small>Tap to flip</small></span><span class="bk"><strong>30s</strong><em>deploys</em><u>Deploy now →</u></span></span></button><button class="fc" type="button" aria-label="Cron jobs"><span class="in"><span class="f"><b>05</b><strong>Cron jobs</strong><small>Tap to flip</small></span><span class="bk"><strong>300+</strong><em>edge locations</em><u>Deploy now →</u></span></span></button><button class="fc" type="button" aria-label="Secrets"><span class="in"><span class="f"><b>06</b><strong>Secrets</strong><small>Tap to flip</small></span><span class="bk"><strong>1-click</strong><em>rollback</em><u>Deploy now →</u></span></span></button></div></main><script>(function(){var c=document.querySelectorAll(".fc"),i=0;c.forEach(function(b){b.addEventListener("click",function(){b.classList.toggle("on")})});setInterval(function(){c.forEach(function(b){b.classList.remove("on")});c[i%c.length].classList.add("on");i++},2000)})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Mono Flip Cards · Developer tools — build prompt (VitalStack Motion Studio)

## Goal
Build a cards section for **Stackline**, a developer tools brand: Cards flip in 3D to reveal the details behind each developer tools service.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Mono Contrast" as CSS custom properties: --bg #0a0a0a, --surface #171717, --ink #fafafa, --muted #a3a3a3, --a1 #fafafa (primary), --a2 #a3a3a3 (secondary), --a3 #525252 (highlight). Dark theme.
- Type: Condensed display system stack (`"Arial Narrow","Roboto Condensed","Helvetica Neue",Arial,sans-serif`), headings weight 800, letter-spacing -.01em; body 16 px, line-height 1.5.
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
1. Each card has a front and back face with backface-visibility:hidden inside a preserve-3d wrapper.
2. Hover, focus or tap rotates the wrapper rotateY(180deg) over 1000ms with a soft overshoot curve.
3. In the preview one card flips automatically every 2 seconds so the motion is visible.

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

Keywords: flip cards 3d backface hover reveal, developer tools
VSAI,
);
