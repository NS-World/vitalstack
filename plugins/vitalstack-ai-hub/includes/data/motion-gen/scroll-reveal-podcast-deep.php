<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Deep Scroll Reveal Story · Podcast</title><style>:root{--bg:#03131f;--surface:#0a2233;--ink:#e8f6ff;--muted:#8fb3c9;--a1:#0ea5e9;--a2:#14b8a6;--a3:#a3e635;--line:rgba(255,255,255,.12);--on:#ffffff;--font:ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace;--hw:700;--ls:-.04em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.bar{position:fixed;top:0;left:0;height:3px;width:100%;transform-origin:0 50%;transform:scaleX(0);background:linear-gradient(90deg,var(--a1),var(--a2));z-index:9}.intro{min-height:80vh;display:grid;place-items:center;text-align:center;padding:24px}.intro h1{max-width:16ch;margin:12px auto}.blk{display:grid;grid-template-columns:auto 1fr 1fr;gap:28px;align-items:center;max-width:1050px;margin:0 auto;padding:12vh 20px}.blk.alt .art{order:-1}.num{font-size:64px;font-weight:var(--hw);color:transparent;-webkit-text-stroke:1.5px var(--a1)}.blk h2{font-size:clamp(26px,3.6vw,44px);letter-spacing:var(--ls);margin:0 0 10px}.blk p{color:var(--muted);line-height:1.6}.art{aspect-ratio:4/3;border-radius:22px;background:radial-gradient(circle at 30% 30%,var(--a2),transparent 60%),linear-gradient(135deg,var(--a1),var(--surface));box-shadow:0 30px 60px -30px var(--a1)}[data-reveal]{opacity:0;transition:opacity 900ms ease,transform 900ms cubic-bezier(.2,.8,.2,1)}[data-reveal=up]{transform:translateY(60px)}[data-reveal=left]{transform:translateX(-80px)}[data-reveal=right]{transform:translateX(80px)}[data-reveal=zoom]{transform:scale(.85)}[data-reveal].in{opacity:1;transform:none}.end{text-align:center;padding:16vh 20px}@media(max-width:760px){.blk{grid-template-columns:1fr}.blk.alt .art{order:0}[data-reveal=left],[data-reveal=right]{transform:translateY(50px)}}@media(prefers-reduced-motion:reduce){[data-reveal]{opacity:1;transform:none;transition:none}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><div class="bar" id="bar"></div><nav class="nav"><b class="logo">Signal &amp; Noise</b><span class="links"><a href="#">Latest episode</a><a href="#">Interviews</a><a href="#">Deep dives</a></span><a class="btn sm" href="#">Listen now</a></nav><header class="intro"><div><p class="eyebrow">Podcast</p><h1>Big ideas in 30 minutes</h1><p class="sub" style="margin:0 auto">Scroll to explore ↓</p></div></header><section class="blk " data-reveal="left"><div class="num">01</div><div><h2>Latest episode</h2><p>A weekly podcast about technology, culture and the people building the future.</p></div><div class="art" style="--h:0"></div></section><section class="blk alt" data-reveal="right"><div class="num">02</div><div><h2>Interviews</h2><p>A weekly podcast about technology, culture and the people building the future.</p></div><div class="art" style="--h:47"></div></section><section class="blk " data-reveal="up"><div class="num">03</div><div><h2>Deep dives</h2><p>A weekly podcast about technology, culture and the people building the future.</p></div><div class="art" style="--h:94"></div></section><section class="blk alt" data-reveal="zoom"><div class="num">04</div><div><h2>Q&amp;A</h2><p>A weekly podcast about technology, culture and the people building the future.</p></div><div class="art" style="--h:141"></div></section><section class="blk " data-reveal="left"><div class="num">05</div><div><h2>Newsletter</h2><p>A weekly podcast about technology, culture and the people building the future.</p></div><div class="art" style="--h:188"></div></section><section class="end" data-reveal="up"><h2>My favourite 30 minutes of the week.</h2><a class="btn" href="#">Listen now</a></section><script>(function(){var io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting)e.target.classList.add("in")})},{threshold:.2});document.querySelectorAll("[data-reveal]").forEach(function(el){io.observe(el)});var b=document.getElementById("bar");function s(){var m=document.documentElement.scrollHeight-innerHeight;b.style.transform="scaleX("+(m>0?scrollY/m:0)+")"}addEventListener("scroll",s,{passive:true});s()})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Deep Scroll Reveal Story · Podcast — build prompt (VitalStack Motion Studio)

## Goal
Build a scroll story section for **Signal & Noise**, a podcast brand: Sections of the podcast story fade, slide and scale into place as you scroll down.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Deep Ocean" as CSS custom properties: --bg #03131f, --surface #0a2233, --ink #e8f6ff, --muted #8fb3c9, --a1 #0ea5e9 (primary), --a2 #14b8a6 (secondary), --a3 #a3e635 (highlight). Dark theme.
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
1. Each block gets a data-reveal direction (left, right, up, zoom); an IntersectionObserver adds .in when 20% of it is visible.
2. Blocks animate opacity 0→1 plus translate/scale over 900ms with a staggered delay for children.
3. A thin progress bar at the top grows with document scroll.

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

Keywords: scroll reveal intersection observer fade slide sections, podcast
VSAI,
);
