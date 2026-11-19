<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Paper Flip Cards · Podcast</title><style>:root{--bg:#f7f5f0;--surface:#ffffff;--ink:#16140f;--muted:#6b675d;--a1:#111827;--a2:#e11d48;--a3:#2563eb;--line:rgba(0,0,0,.1);--on:#ffffff;--font:"Arial Narrow","Roboto Condensed","Helvetica Neue",Arial,sans-serif;--hw:800;--ls:-.01em;color-scheme:light}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}main{max-width:1080px;margin:0 auto;padding:40px 20px 60px}h2{text-align:center;font-size:clamp(28px,4.4vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:0 0 30px}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,220px),1fr));gap:16px}.fc{all:unset;cursor:pointer;display:block;height:230px;perspective:1000px}.fc:focus-visible{outline:2px solid var(--a2);outline-offset:4px;border-radius:20px}.in{position:relative;display:block;width:100%;height:100%;transform-style:preserve-3d;transition:transform 800ms cubic-bezier(.3,1.4,.5,1)}.fc:hover .in,.fc:focus .in,.fc.on .in{transform:rotateX(180deg)}.f,.bk{position:absolute;inset:0;border-radius:20px;padding:22px;display:flex;flex-direction:column;justify-content:space-between;backface-visibility:hidden;-webkit-backface-visibility:hidden}.f{background:var(--surface);box-shadow:inset 0 0 0 1px var(--line)}.f b{color:var(--a1);font-size:14px}.f strong{font-size:24px}.f small{color:var(--muted)}.bk{background:linear-gradient(140deg,var(--a1),var(--a2));color:var(--on);transform:rotateX(180deg)}.bk strong{font-size:44px;letter-spacing:-.03em}.bk em{font-style:normal;opacity:.9}.bk u{text-decoration:none;font-weight:700}@media(prefers-reduced-motion:reduce){.in{transition:none}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}</style></head><body><nav class="nav"><b class="logo">Signal &amp; Noise</b><span class="links"><a href="#">Latest episode</a><a href="#">Interviews</a><a href="#">Deep dives</a></span><a class="btn sm" href="#">Listen now</a></nav><main><h2>Big ideas in 30 minutes</h2><div class="grid"><button class="fc" type="button" aria-label="Latest episode"><span class="in"><span class="f"><b>01</b><strong>Latest episode</strong><small>Tap to flip</small></span><span class="bk"><strong>180</strong><em>episodes</em><u>Listen now →</u></span></span></button><button class="fc" type="button" aria-label="Interviews"><span class="in"><span class="f"><b>02</b><strong>Interviews</strong><small>Tap to flip</small></span><span class="bk"><strong>2M</strong><em>listens</em><u>Listen now →</u></span></span></button><button class="fc" type="button" aria-label="Deep dives"><span class="in"><span class="f"><b>03</b><strong>Deep dives</strong><small>Tap to flip</small></span><span class="bk"><strong>Top 10</strong><em>tech charts</em><u>Listen now →</u></span></span></button><button class="fc" type="button" aria-label="Q&amp;A"><span class="in"><span class="f"><b>04</b><strong>Q&amp;A</strong><small>Tap to flip</small></span><span class="bk"><strong>180</strong><em>episodes</em><u>Listen now →</u></span></span></button><button class="fc" type="button" aria-label="Newsletter"><span class="in"><span class="f"><b>05</b><strong>Newsletter</strong><small>Tap to flip</small></span><span class="bk"><strong>2M</strong><em>listens</em><u>Listen now →</u></span></span></button><button class="fc" type="button" aria-label="Live shows"><span class="in"><span class="f"><b>06</b><strong>Live shows</strong><small>Tap to flip</small></span><span class="bk"><strong>Top 10</strong><em>tech charts</em><u>Listen now →</u></span></span></button></div></main><script>(function(){var c=document.querySelectorAll(".fc"),i=0;c.forEach(function(b){b.addEventListener("click",function(){b.classList.toggle("on")})});setInterval(function(){c.forEach(function(b){b.classList.remove("on")});c[i%c.length].classList.add("on");i++},2000)})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Paper Flip Cards · Podcast — build prompt (VitalStack Motion Studio)

## Goal
Build a cards section for **Signal & Noise**, a podcast brand: Cards flip in 3D to reveal the details behind each podcast service.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Paper Ink" as CSS custom properties: --bg #f7f5f0, --surface #ffffff, --ink #16140f, --muted #6b675d, --a1 #111827 (primary), --a2 #e11d48 (secondary), --a3 #2563eb (highlight). Light theme.
- Type: Condensed display system stack (`"Arial Narrow","Roboto Condensed","Helvetica Neue",Arial,sans-serif`), headings weight 800, letter-spacing -.01em; body 16 px, line-height 1.5.
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
1. Each card has a front and back face with backface-visibility:hidden inside a preserve-3d wrapper.
2. Hover, focus or tap rotates the wrapper rotateX(180deg) over 800ms with a soft overshoot curve.
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

Keywords: flip cards 3d backface hover reveal, podcast
VSAI,
);
