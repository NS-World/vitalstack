<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Neon Flip Cards · EdTech</title><style>:root{--bg:#05010f;--surface:#120726;--ink:#fbf7ff;--muted:#b49fd6;--a1:#e879f9;--a2:#22d3ee;--a3:#fde047;--line:rgba(255,255,255,.12);--on:#ffffff;--font:"Arial Narrow","Roboto Condensed","Helvetica Neue",Arial,sans-serif;--hw:800;--ls:-.01em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}main{max-width:1080px;margin:0 auto;padding:40px 20px 60px}h2{text-align:center;font-size:clamp(28px,4.4vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:0 0 30px}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,220px),1fr));gap:16px}.fc{all:unset;cursor:pointer;display:block;height:230px;perspective:1000px}.fc:focus-visible{outline:2px solid var(--a2);outline-offset:4px;border-radius:20px}.in{position:relative;display:block;width:100%;height:100%;transform-style:preserve-3d;transition:transform 600ms cubic-bezier(.3,1.4,.5,1)}.fc:hover .in,.fc:focus .in,.fc.on .in{transform:rotateY(180deg)}.f,.bk{position:absolute;inset:0;border-radius:20px;padding:22px;display:flex;flex-direction:column;justify-content:space-between;backface-visibility:hidden;-webkit-backface-visibility:hidden}.f{background:var(--surface);box-shadow:inset 0 0 0 1px var(--line)}.f b{color:var(--a1);font-size:14px}.f strong{font-size:24px}.f small{color:var(--muted)}.bk{background:linear-gradient(140deg,var(--a1),var(--a2));color:var(--on);transform:rotateY(180deg)}.bk strong{font-size:44px;letter-spacing:-.03em}.bk em{font-style:normal;opacity:.9}.bk u{text-decoration:none;font-weight:700}@media(prefers-reduced-motion:reduce){.in{transition:none}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}</style></head><body><nav class="nav"><b class="logo">LearnLoop</b><span class="links"><a href="#">Python</a><a href="#">JavaScript</a><a href="#">SQL</a></span><a class="btn sm" href="#">Start learning</a></nav><main><h2>Learn to code, one small win a day</h2><div class="grid"><button class="fc" type="button" aria-label="Python"><span class="in"><span class="f"><b>01</b><strong>Python</strong><small>Tap to flip</small></span><span class="bk"><strong>300+</strong><em>lessons</em><u>Start learning →</u></span></span></button><button class="fc" type="button" aria-label="JavaScript"><span class="in"><span class="f"><b>02</b><strong>JavaScript</strong><small>Tap to flip</small></span><span class="bk"><strong>5 min</strong><em>per lesson</em><u>Start learning →</u></span></span></button><button class="fc" type="button" aria-label="SQL"><span class="in"><span class="f"><b>03</b><strong>SQL</strong><small>Tap to flip</small></span><span class="bk"><strong>80k</strong><em>learners</em><u>Start learning →</u></span></span></button><button class="fc" type="button" aria-label="Data science"><span class="in"><span class="f"><b>04</b><strong>Data science</strong><small>Tap to flip</small></span><span class="bk"><strong>300+</strong><em>lessons</em><u>Start learning →</u></span></span></button><button class="fc" type="button" aria-label="AI basics"><span class="in"><span class="f"><b>05</b><strong>AI basics</strong><small>Tap to flip</small></span><span class="bk"><strong>5 min</strong><em>per lesson</em><u>Start learning →</u></span></span></button><button class="fc" type="button" aria-label="Web design"><span class="in"><span class="f"><b>06</b><strong>Web design</strong><small>Tap to flip</small></span><span class="bk"><strong>80k</strong><em>learners</em><u>Start learning →</u></span></span></button></div></main><script>(function(){var c=document.querySelectorAll(".fc"),i=0;c.forEach(function(b){b.addEventListener("click",function(){b.classList.toggle("on")})});setInterval(function(){c.forEach(function(b){b.classList.remove("on")});c[i%c.length].classList.add("on");i++},2000)})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Neon Flip Cards · EdTech — build prompt (VitalStack Motion Studio)

## Goal
Build a cards section for **LearnLoop**, a edtech brand: Cards flip in 3D to reveal the details behind each EdTech service.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Neon Arcade" as CSS custom properties: --bg #05010f, --surface #120726, --ink #fbf7ff, --muted #b49fd6, --a1 #e879f9 (primary), --a2 #22d3ee (secondary), --a3 #fde047 (highlight). Dark theme.
- Type: Condensed display system stack (`"Arial Narrow","Roboto Condensed","Helvetica Neue",Arial,sans-serif`), headings weight 800, letter-spacing -.01em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: LearnLoop
- Headline: “Learn to code, one small win a day”
- Sub-copy: “Bite-size lessons, live coding and projects that end up in your portfolio.”
- Primary button: “Start learning”; secondary: “See courses”
- Items: Python, JavaScript, SQL, Data science, AI basics, Web design
- Stats: 300+ lessons; 5 min per lesson; 80k learners
- Testimonial: “I got my first developer job eight months after lesson one.” — Ananya P., junior developer

## Layout
- Top navigation: brand on the left, 3 links (Python, JavaScript, SQL) and a small “Start learning” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. Each card has a front and back face with backface-visibility:hidden inside a preserve-3d wrapper.
2. Hover, focus or tap rotates the wrapper rotateY(180deg) over 600ms with a soft overshoot curve.
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

Keywords: flip cards 3d backface hover reveal, edtech
VSAI,
);
