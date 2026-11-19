<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Ember Smooth FAQ Accordion · Music festival</title><style>:root{--bg:#140806;--surface:#24110c;--ink:#fff3ec;--muted:#c9a597;--a1:#f97316;--a2:#ef4444;--a3:#facc15;--line:rgba(255,255,255,.12);--on:#ffffff;--font:system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;--hw:800;--ls:-.035em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}main{max-width:760px;margin:0 auto;padding:40px 20px 60px}main h2{font-size:clamp(28px,4.2vw,48px);letter-spacing:var(--ls);font-weight:var(--hw);margin:10px 0 26px}details{border-bottom:1px solid var(--line)}summary{list-style:none;cursor:pointer;display:flex;justify-content:space-between;align-items:center;gap:16px;padding:20px 4px;font-weight:700;font-size:clamp(16px,2vw,19px)}summary::-webkit-details-marker{display:none}summary:focus-visible{outline:2px solid var(--a2);border-radius:8px}summary i{flex:0 0 28px;height:28px;border-radius:50%;position:relative;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);transition:transform .3s,background .3s}summary i::before,summary i::after{content:"";position:absolute;left:50%;top:50%;width:12px;height:2px;margin:-1px 0 0 -6px;background:var(--ink)}summary i::after{transform:rotate(90deg)}details[open] summary i{transform:rotate(45deg);background:var(--a1)}details[open] summary i::before,details[open] summary i::after{background:var(--on)}.a{overflow:hidden}.a p{margin:0 4px 20px;color:var(--muted);line-height:1.6}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Solstice Fest</b><span class="links"><a href="#">Main stage</a><a href="#">Night garden</a><a href="#">Silent disco</a></span><a class="btn sm" href="#">Get passes</a></nav><main><p class="eyebrow">FAQ</p><h2>Questions about Solstice Fest</h2><details open><summary>What is Solstice Fest?<i></i></summary><div class="a"><p>A weekend of live music, art and food under the open sky, 14 to 16 March.</p></div></details><details><summary>How do I get started?<i></i></summary><div class="a"><p>Click “Get passes”, create your account in under a minute and follow the short guided tour.</p></div></details><details><summary>Is there a free option?<i></i></summary><div class="a"><p>Yes. You can try the core features free, and upgrade only when you need more.</p></div></details><details><summary>Which main stage options do you offer?<i></i></summary><div class="a"><p>We offer Main stage, Night garden and Silent disco, with more added every month.</p></div></details><details><summary>Can I cancel any time?<i></i></summary><div class="a"><p>Yes. There are no lock-ins; you can cancel from your account settings in two clicks.</p></div></details><details><summary>How do I contact support?<i></i></summary><div class="a"><p>Write to us from the help centre. A real person replies within one working day.</p></div></details></main><script>(function(){var D=250,red=matchMedia("(prefers-reduced-motion: reduce)").matches,all=document.querySelectorAll("details");all.forEach(function(d){var s=d.querySelector("summary"),a=d.querySelector(".a");s.addEventListener("click",function(e){e.preventDefault();if(d.open){close(d)}else{all.forEach(function(o){if(o!==d&&o.open)close(o)});d.open=true;if(!red)a.animate([{height:"0px"},{height:a.scrollHeight+"px"}],{duration:D,easing:"ease-out"})}})});function close(d){var a=d.querySelector(".a");if(red){d.open=false;return}var an=a.animate([{height:a.scrollHeight+"px"},{height:"0px"}],{duration:D,easing:"ease-in"});an.onfinish=function(){d.open=false}}})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Ember Smooth FAQ Accordion · Music festival — build prompt (VitalStack Motion Studio)

## Goal
Build a faq section for **Solstice Fest**, a music festival brand: A music festival FAQ where answers open with a smooth height animation and a rotating plus icon.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Ember Night" as CSS custom properties: --bg #140806, --surface #24110c, --ink #fff3ec, --muted #c9a597, --a1 #f97316 (primary), --a2 #ef4444 (secondary), --a3 #facc15 (highlight). Dark theme.
- Type: Modern sans system stack (`system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif`), headings weight 800, letter-spacing -.035em; body 16 px, line-height 1.5.
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
1. Built on native <details>/<summary> for accessibility; JavaScript animates height from 0 to scrollHeight over 250ms with the Web Animations API.
2. Only one item stays open (accordion mode); the plus icon rotates 45° into a cross.
3. The first answer opens by itself in the preview.

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

Keywords: faq accordion smooth height details summary, music festival
VSAI,
);
