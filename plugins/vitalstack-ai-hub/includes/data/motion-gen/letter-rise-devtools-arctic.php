<?php
defined( 'ABSPATH' ) || exit;
return array(
	'code' => <<<'VSAI'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Arctic Letter Rise Reveal · Developer tools</title><style>:root{--bg:#020617;--surface:#0f1b33;--ink:#eef4ff;--muted:#94a3b8;--a1:#3b82f6;--a2:#60a5fa;--a3:#c084fc;--line:rgba(255,255,255,.12);--on:#ffffff;--font:"Arial Narrow","Roboto Condensed","Helvetica Neue",Arial,sans-serif;--hw:800;--ls:-.01em;color-scheme:dark}*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}body{min-height:100vh;display:flex;flex-direction:column}main{flex:1;display:grid;place-items:center;padding:24px}h1 .ch{display:inline-block;opacity:0;transform:translateY(120%) rotate(8deg)}.go h1 .ch{animation:up .8s cubic-bezier(.2,1.4,.4,1) forwards;animation-delay:calc(var(--i)*35ms)}@keyframes up{to{opacity:1;transform:none}}.sub,.row{opacity:0;transform:translateY(16px);transition:.7s .9s}.go .sub,.go .row{opacity:1;transform:none}@media(prefers-reduced-motion:reduce){h1 .ch,.sub,.row{opacity:1!important;transform:none!important;animation:none!important}}.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.copy{position:relative;z-index:2;max-width:880px;margin:0 auto;text-align:center;padding:clamp(48px,10vh,120px) 20px}.copy .sub{margin:0 auto}.copy .row{justify-content:center}</style></head><body><nav class="nav"><b class="logo">Stackline</b><span class="links"><a href="#">Previews</a><a href="#">Rollbacks</a><a href="#">Edge cache</a></span><a class="btn sm" href="#">Deploy now</a></nav><div class="copy"><p class="eyebrow">Developer tools</p><h1>Deploy on push. Sleep at night.</h1><p class="sub">Preview environments, instant rollbacks and logs that make sense.</p><div class="row"><a class="btn" href="#">Deploy now</a><a class="btn ghost" href="#">Read docs</a></div></div><script>(function(){var h=document.querySelector("h1"),t=h.textContent,i=0;h.setAttribute("aria-label",t);h.innerHTML=t.split(" ").map(function(w){return '<span style="white-space:nowrap">'+w.split("").map(function(ch){return '<span class="ch" aria-hidden="true" style="--i:'+(i++)+'">'+ch+"</span>"}).join("")+"</span>"}).join(" ");var m=document.querySelector(".copy");function go(){m.classList.remove("go");void m.offsetWidth;m.classList.add("go")}go();setInterval(go,7000)})();</script></body></html>
VSAI,
	'spec' => <<<'VSAI'
# Arctic Letter Rise Reveal · Developer tools — build prompt (VitalStack Motion Studio)

## Goal
Build a text effect section for **Stackline**, a developer tools brand: Each letter of the developer tools headline rises and fades in one after another, then the buttons slide up.

Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.

## Visual style
- Palette "Arctic Blue" as CSS custom properties: --bg #020617, --surface #0f1b33, --ink #eef4ff, --muted #94a3b8, --a1 #3b82f6 (primary), --a2 #60a5fa (secondary), --a3 #c084fc (highlight). Dark theme.
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
1. JavaScript wraps every letter of the headline in a <span> (spaces kept) and sets a CSS variable --i for its index.
2. Letters animate from translateY(120%) rotate(8deg) and opacity 0 with a 35ms stagger and a springy cubic-bezier.
3. The whole reveal replays every 7 seconds in the preview; in production run it once.

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

Keywords: split text letters stagger reveal headline, developer tools
VSAI,
);
