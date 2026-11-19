<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Citrus Counting Stats Band · Fitness</title><style>:root{--bg:#fffbea;--surface:#ffffff;--ink:#1f1a05;--muted:#6f6640;--a1:#eab308;--a2:#16a34a;--a3:#ef4444;--line:rgba(0,0,0,.1);--on:#0b0b10;--font:Georgia,"Iowan Old Style","Times New Roman",serif;--hw:700;--ls:-.02em;color-scheme:light}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}main{min-height:100vh;display:grid;place-items:center;padding:30px 20px}.wrap{max-width:1050px;width:100%;text-align:center}.wrap h2{font-size:clamp(26px,4vw,48px);letter-spacing:var(--ls);font-weight:var(--hw);margin:12px auto 34px;max-width:20ch}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,220px),1fr));gap:18px}.st{padding:28px 22px;border-radius:22px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);text-align:left;position:relative;overflow:hidden}.st b{display:block;font-size:clamp(40px,6vw,64px);letter-spacing:-.04em;background:linear-gradient(90deg,var(--a1),var(--a2));-webkit-background-clip:text;background-clip:text;color:transparent;font-variant-numeric:tabular-nums}.st span{color:var(--muted)}.st i{position:absolute;left:0;bottom:0;height:4px;width:var(--w);background:var(--a1);transform-origin:0;transform:scaleX(0);transition:transform 1800ms cubic-bezier(.2,.8,.2,1)}.go .st i{transform:scaleX(1)}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.wrap .btn{margin-top:30px}</style></head><body><nav class="nav"><b class="logo">PulseFit</b><span class="links"><a href="#">Strength</a><a href="#">HIIT</a><a href="#">Mobility</a></span><a class="btn sm" href="#">Start training</a></nav><main><div class="wrap" id="wr"><p class="eyebrow">PulseFit</p><h2>Train smarter, recover faster</h2><div class="grid"><div class="st"><b data-pre="" data-to="320" data-suf="+">320+</b><span>workouts</span><i style="--w:55%"></i></div><div class="st"><b data-pre="" data-to="15" data-suf=" min">15 min</b><span>daily plans</span><i style="--w:70%"></i></div><div class="st"><b data-pre="" data-to="1" data-suf="M">1M</b><span>reps logged</span><i style="--w:85%"></i></div></div><a class="btn" href="#">Start training</a></div></main><script>(function(){var D=1800,w=document.getElementById("wr"),B=w.querySelectorAll("b[data-to]");function fmt(v,dec){return dec?v.toFixed(dec):Math.round(v).toLocaleString("en-IN")}function run(){w.classList.remove("go");void w.offsetWidth;w.classList.add("go");var t0=performance.now();B.forEach(function(b){b._to=parseFloat(b.dataset.to);b._dec=(b.dataset.to.split(".")[1]||"").length});(function f(n){var p=Math.min(1,(n-t0)/D),e=p===1?1:1-Math.pow(2,-10*p);B.forEach(function(b){b.textContent=b.dataset.pre+fmt(b._to*e,b._dec)+b.dataset.suf});if(p<1)requestAnimationFrame(f)})(t0)}if(matchMedia("(prefers-reduced-motion: reduce)").matches){w.classList.add("go");return}new IntersectionObserver(function(es){if(es[0].isIntersecting){run();setInterval(run,6000);this.disconnect()}}).observe(w)})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Citrus Counting Stats Band · Fitness — build prompt (VitalStack Motion Studio)

## Goal
Build a stats section for **PulseFit**, a fitness brand: Big fitness numbers count up from zero with an easing curve when the band scrolls into view.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Citrus Pop" as CSS custom properties: --bg #fffbea, --surface #ffffff, --ink #1f1a05, --muted #6f6640, --a1 #eab308 (primary), --a2 #16a34a (secondary), --a3 #ef4444 (highlight). Light theme.
- Type: Editorial serif system stack (`Georgia,"Iowan Old Style","Times New Roman",serif`), headings weight 700, letter-spacing -.02em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: PulseFit
- Headline: “Train smarter, recover faster”
- Sub-copy: “Personal workout plans that adapt to your sleep, stress and progress every single day.”
- Primary button: “Start training”; secondary: “View plans”
- Items: Strength, HIIT, Mobility, Running, Yoga flow, Recovery
- Stats: 320+ workouts; 15 min daily plans; 1M reps logged
- Testimonial: “Down 8 kg and stronger than ever. The plans just work.” — Neha K., Pune

## Layout
- Top navigation: brand on the left, 3 links (Strength, HIIT, Mobility) and a small “Start training” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. Each stat stores its target in data-to (prefix/suffix kept), and an IntersectionObserver starts the count once.
2. Values ease with easeOutExpo over 1800ms using requestAnimationFrame; decimals and separators are preserved.
3. Below, a thin bar fills to match each stat for extra motion; the preview replays every 6 seconds.

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

Keywords: numbers counter count up stats band, fitness
VSAI,
);
