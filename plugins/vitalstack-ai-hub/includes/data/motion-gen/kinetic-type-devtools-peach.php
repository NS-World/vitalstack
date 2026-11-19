<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Peach Kinetic Scroll Type · Developer tools</title><style>:root{--bg:#fff5ef;--surface:#ffffff;--ink:#2b1610;--muted:#7d5a4e;--a1:#f97316;--a2:#ec4899;--a3:#8b5cf6;--line:rgba(0,0,0,.1);--on:#ffffff;--font:system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;--hw:800;--ls:-.035em;color-scheme:light}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}#ks{height:500vh;position:relative}.stg{position:sticky;top:0;height:100vh;overflow:hidden;display:grid;place-items:center;background:radial-gradient(circle at 50% 60%,color-mix(in srgb,var(--a1) calc(var(--p,0)*40%),var(--surface)),var(--bg) 70%)}.kw{position:absolute;font-size:clamp(56px,16vw,220px);font-weight:900;letter-spacing:-.05em;text-transform:lowercase;opacity:0;will-change:transform,opacity}.lb{position:absolute;top:12vh;left:0;right:0;text-align:center;color:var(--muted);letter-spacing:.2em;text-transform:uppercase;font-size:13px}.ct{position:absolute;bottom:8vh;right:6vw;font-variant-numeric:tabular-nums;color:var(--muted)}.intro,.end{min-height:70vh;display:grid;place-items:center;text-align:center;padding:24px}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">Stackline</b><span class="links"><a href="#">Previews</a><a href="#">Rollbacks</a><a href="#">Edge cache</a></span><a class="btn sm" href="#">Deploy now</a></nav><header class="intro"><div><p class="eyebrow">Stackline</p><h1>Deploy on push. Sleep at night.</h1><p class="sub" style="margin:0 auto">Scroll ↓</p></div></header><section id="ks"><div class="stg" id="st"><p class="lb">Stackline is</p><span class="kw" style="--i:0">faster</span><span class="kw" style="--i:1">safer</span><span class="kw" style="--i:2">simpler</span><span class="kw" style="--i:3">global</span><span class="ct" id="ct">01 / 04</span></div></section><section class="end"><div><h2>We went from weekly to 40 deploys a day.</h2><a class="btn" href="#">Deploy now</a></div></section><script>(function(){var s=document.getElementById("ks"),st=document.getElementById("st"),W=st.querySelectorAll(".kw"),ct=document.getElementById("ct"),n=W.length,R=6;function f(){var b=s.getBoundingClientRect(),m=s.offsetHeight-innerHeight,p=Math.min(1,Math.max(0,-b.top/m));st.style.setProperty("--p",p);var a=Math.min(n-1,Math.floor(p*n));ct.textContent=("0"+(a+1)).slice(-2)+" / "+("0"+n).slice(-2);W.forEach(function(w,i){var l=p*n-i,e=l<0?0:l>1?0:Math.sin(l*Math.PI),dir=i%2?1:-1;w.style.opacity=e;w.style.transform="translateX("+((1-e)*dir*30*(l<.5?1:-1))+"vw) scale("+(.6+.4*e)+") rotate("+((1-e)*dir*R)+"deg)"})}addEventListener("scroll",f,{passive:true});addEventListener("resize",f);f()})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Peach Kinetic Scroll Type · Developer tools — build prompt (VitalStack Motion Studio)

## Goal
Build a scroll story section for **Stackline**, a developer tools brand: Giant developer tools words scale, slide and rotate with your scroll inside a pinned stage.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Peach Sorbet" as CSS custom properties: --bg #fff5ef, --surface #ffffff, --ink #2b1610, --muted #7d5a4e, --a1 #f97316 (primary), --a2 #ec4899 (secondary), --a3 #8b5cf6 (highlight). Light theme.
- Type: Modern sans system stack (`system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif`), headings weight 800, letter-spacing -.035em; body 16 px, line-height 1.5.
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
1. A 500vh section holds a sticky stage with 4 huge words; scroll progress p (0–1) drives each word's transform.
2. Word i is active around p = i/n: it scales from 0.6 to 1, slides in from alternating sides and rotates from ±6° to 0, then fades out.
3. A counter shows "0i / 0n" and the background hue shifts with progress.

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

Keywords: kinetic typography scroll scale rotate words sticky, developer tools
VSAI,
);
