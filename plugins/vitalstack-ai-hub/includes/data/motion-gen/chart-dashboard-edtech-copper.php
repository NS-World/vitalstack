<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Copper Animated Dashboard Charts · EdTech</title><style>:root{--bg:#120c0a;--surface:#221814;--ink:#fbefe7;--muted:#c2a796;--a1:#ea580c;--a2:#d97706;--a3:#fbbf24;--line:rgba(255,255,255,.12);--on:#ffffff;--font:system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;--hw:800;--ls:-.035em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}main{max-width:1050px;margin:0 auto;padding:30px 20px 60px}.top{display:flex;justify-content:space-between;align-items:end;gap:16px;flex-wrap:wrap;margin-bottom:20px}.top h2{font-size:clamp(24px,3.4vw,40px);letter-spacing:var(--ls);font-weight:var(--hw);margin:6px 0 0}.kps{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,180px),1fr));gap:14px;margin-bottom:14px}.kp,.pane{padding:20px;border-radius:20px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line)}.kp span{color:var(--muted);font-size:13px}.kp b{display:block;font-size:32px;letter-spacing:-.03em;margin-top:4px}.two{display:grid;grid-template-columns:1.2fr 1fr;gap:14px}.bars{display:flex;align-items:flex-end;gap:6px;height:220px;margin-top:12px}.bars i{flex:1;border-radius:6px 6px 2px 2px;background:linear-gradient(var(--a1),color-mix(in srgb,var(--a1) 40%,transparent));height:0}.go .bars i{height:var(--v);transition:height .9s cubic-bezier(.3,1.4,.5,1);transition-delay:calc(var(--i)*60ms)}svg{width:100%;height:220px;margin-top:12px;overflow:visible}.ln{fill:none;stroke:var(--a2);stroke-width:3;stroke-linecap:round;stroke-linejoin:round;stroke-dasharray:1000;stroke-dashoffset:1000}.go .ln{stroke-dashoffset:0;transition:stroke-dashoffset 2s ease}.ar{fill:url(#gd);opacity:0}.go .ar{opacity:1;transition:opacity 1s 1.2s}.pane h3{margin:0;font-size:15px;color:var(--muted);font-weight:600}@media(max-width:760px){.two{grid-template-columns:1fr}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}</style></head><body><nav class="nav"><b class="logo">LearnLoop</b><span class="links"><a href="#">Python</a><a href="#">JavaScript</a><a href="#">SQL</a></span><a class="btn sm" href="#">Start learning</a></nav><main id="db"><div class="top"><div><p class="eyebrow">LearnLoop dashboard</p><h2>Learn to code, one small win a day</h2></div><a class="btn sm" href="#">Start learning</a></div><div class="kps"><div class="kp"><span>Lessons</span><b>300+</b></div><div class="kp"><span>Per lesson</span><b>5 min</b></div><div class="kp"><span>Learners</span><b>80k</b></div></div><div class="two"><div class="pane"><h3>Monthly activity</h3><div class="bars"><i style="--v:38%;--i:0"></i><i style="--v:48%;--i:1"></i><i style="--v:55%;--i:2"></i><i style="--v:38%;--i:3"></i><i style="--v:72%;--i:4"></i><i style="--v:60%;--i:5"></i><i style="--v:82%;--i:6"></i><i style="--v:35%;--i:7"></i><i style="--v:39%;--i:8"></i><i style="--v:61%;--i:9"></i><i style="--v:85%;--i:10"></i><i style="--v:83%;--i:11"></i></div></div><div class="pane"><h3>Growth trend</h3><svg viewBox="0 0 400 100" preserveAspectRatio="none" aria-hidden="true"><defs><linearGradient id="gd" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="var(--a2)" stop-opacity=".45"/><stop offset="1" stop-color="var(--a2)" stop-opacity="0"/></linearGradient></defs><path class="ar" d="M0 47 L50 14 L100 28 L150 37 L200 11 L250 26 L300 10 L350 74 L400 43 L400 100 L0 100 Z"/><path class="ln" d="M0 47 L50 14 L100 28 L150 37 L200 11 L250 26 L300 10 L350 74 L400 43" pathLength="1000"/></svg></div></div></main><script>(function(){var d=document.getElementById("db");function run(){d.classList.remove("go");void d.offsetWidth;requestAnimationFrame(function(){d.classList.add("go")})}if(matchMedia("(prefers-reduced-motion: reduce)").matches){d.classList.add("go");return}setTimeout(run,300);setInterval(run,6000)})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Copper Animated Dashboard Charts · EdTech — build prompt (VitalStack Motion Studio)

## Goal
Build a charts section for **LearnLoop**, a edtech brand: An EdTech dashboard card where bars grow, a line chart draws itself and KPIs tick up, every few seconds.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Copper Dusk" as CSS custom properties: --bg #120c0a, --surface #221814, --ink #fbefe7, --muted #c2a796, --a1 #ea580c (primary), --a2 #d97706 (secondary), --a3 #fbbf24 (highlight). Dark theme.
- Type: Modern sans system stack (`system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif`), headings weight 800, letter-spacing -.035em; body 16 px, line-height 1.5.
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
1. Bars are divs whose height animates from 0 to their value with a 60ms stagger and a springy curve.
2. The SVG line chart animates stroke-dashoffset from its full length to 0; the area below fades in.
3. KPIs count up; everything replays every 6 seconds in the preview.

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

Keywords: dashboard charts bars line svg animate stats, edtech
VSAI,
);
