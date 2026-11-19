<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Matrix Counting Stats Band · Architecture</title><style>:root{--bg:#010a04;--surface:#06180c;--ink:#e6ffee;--muted:#7fb592;--a1:#22c55e;--a2:#4ade80;--a3:#bef264;--line:rgba(255,255,255,.12);--on:#ffffff;--font:"Arial Narrow","Roboto Condensed","Helvetica Neue",Arial,sans-serif;--hw:800;--ls:-.01em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}main{min-height:100vh;display:grid;place-items:center;padding:30px 20px}.wrap{max-width:1050px;width:100%;text-align:center}.wrap h2{font-size:clamp(26px,4vw,48px);letter-spacing:var(--ls);font-weight:var(--hw);margin:12px auto 34px;max-width:20ch}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,220px),1fr));gap:18px}.st{padding:28px 22px;border-radius:22px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);text-align:left;position:relative;overflow:hidden}.st b{display:block;font-size:clamp(40px,6vw,64px);letter-spacing:-.04em;background:linear-gradient(90deg,var(--a1),var(--a2));-webkit-background-clip:text;background-clip:text;color:transparent;font-variant-numeric:tabular-nums}.st span{color:var(--muted)}.st i{position:absolute;left:0;bottom:0;height:4px;width:var(--w);background:var(--a1);transform-origin:0;transform:scaleX(0);transition:transform 2200ms cubic-bezier(.2,.8,.2,1)}.go .st i{transform:scaleX(1)}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.wrap .btn{margin-top:30px}</style></head><body><nav class="nav"><b class="logo">Form &amp; Field</b><span class="links"><a href="#">Residential</a><a href="#">Public space</a><a href="#">Interiors</a></span><a class="btn sm" href="#">See projects</a></nav><main><div class="wrap" id="wr"><p class="eyebrow">Form &amp; Field</p><h2>Buildings that breathe</h2><div class="grid"><div class="st"><b data-pre="" data-to="70" data-suf="">70</b><span>projects</span><i style="--w:55%"></i></div><div class="st"><b data-pre="" data-to="40" data-suf="%">40%</b><span>less energy</span><i style="--w:70%"></i></div><div class="st"><b data-pre="" data-to="8" data-suf="">8</b><span>awards</span><i style="--w:85%"></i></div></div><a class="btn" href="#">See projects</a></div></main><script>(function(){var D=2200,w=document.getElementById("wr"),B=w.querySelectorAll("b[data-to]");function fmt(v,dec){return dec?v.toFixed(dec):Math.round(v).toLocaleString("en-IN")}function run(){w.classList.remove("go");void w.offsetWidth;w.classList.add("go");var t0=performance.now();B.forEach(function(b){b._to=parseFloat(b.dataset.to);b._dec=(b.dataset.to.split(".")[1]||"").length});(function f(n){var p=Math.min(1,(n-t0)/D),e=p===1?1:1-Math.pow(2,-10*p);B.forEach(function(b){b.textContent=b.dataset.pre+fmt(b._to*e,b._dec)+b.dataset.suf});if(p<1)requestAnimationFrame(f)})(t0)}if(matchMedia("(prefers-reduced-motion: reduce)").matches){w.classList.add("go");return}new IntersectionObserver(function(es){if(es[0].isIntersecting){run();setInterval(run,6000);this.disconnect()}}).observe(w)})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Matrix Counting Stats Band · Architecture — build prompt (VitalStack Motion Studio)

## Goal
Build a stats section for **Form & Field**, a architecture brand: Big architecture numbers count up from zero with an easing curve when the band scrolls into view.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Matrix Green" as CSS custom properties: --bg #010a04, --surface #06180c, --ink #e6ffee, --muted #7fb592, --a1 #22c55e (primary), --a2 #4ade80 (secondary), --a3 #bef264 (highlight). Dark theme.
- Type: Condensed display system stack (`"Arial Narrow","Roboto Condensed","Helvetica Neue",Arial,sans-serif`), headings weight 800, letter-spacing -.01em; body 16 px, line-height 1.5.
- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.

## Content (use exactly)
- Brand: Form & Field
- Headline: “Buildings that breathe”
- Sub-copy: “An architecture studio designing climate-smart homes and public spaces.”
- Primary button: “See projects”; secondary: “Contact studio”
- Items: Residential, Public space, Interiors, Landscape, Restoration, Research
- Stats: 70 projects; 40% less energy; 8 awards
- Testimonial: “Our home stays cool all summer without an AC.” — Client, Jaipur house

## Layout
- Top navigation: brand on the left, 3 links (Residential, Public space, Interiors) and a small “See projects” button on the right; links hide under 640 px.
- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).

## Motion & interaction
1. Each stat stores its target in data-to (prefix/suffix kept), and an IntersectionObserver starts the count once.
2. Values ease with easeOutExpo over 2200ms using requestAnimationFrame; decimals and separators are preserved.
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

Keywords: numbers counter count up stats band, architecture
VSAI,
);
