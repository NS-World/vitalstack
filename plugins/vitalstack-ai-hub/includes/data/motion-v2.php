<?php
/**
 * Motion Studio flagship designs (v1.1): full-page scroll stories and
 * interactive galleries, original to VitalStack. Every one is a single HTML
 * file with inline CSS + vanilla JS, CSS/SVG art only (no external assets),
 * and comes with a detailed build prompt ("spec").
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

$head = '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';

return array(

	/* ── 1. Card Wheel Story (Pro) ───────────────────────────────────── */
	array(
		'id'       => 'card-wheel-story',
		'title'    => 'Card Wheel Story',
		'cat'      => 'Scroll Story',
		'premium'  => true,
		'featured' => true,
		'summary'  => 'A pinned scroll story: art cards rise into a fan, scatter, gather into a pile and re-fan into a turning wheel under a two-line headline.',
		'tags'     => 'scroll pinned cards fan wheel inertia story landing',
		'code'     => $head . <<<'HTML'
<title>Card Wheel Story</title><style>
:root{--bg:#0c0b14;--ink:#f4f1ff;--muted:#7f7a9c;--cw:200px}
*{box-sizing:border-box;margin:0}html,body{background:var(--bg);color:var(--ink);font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif}
#story{position:relative;height:700vh}
.stage{position:sticky;top:0;height:100vh;overflow:hidden}
.head{position:absolute;left:0;right:0;top:15%;text-align:center;padding:0 16px;z-index:3;font-size:clamp(28px,4.6vw,64px);font-weight:650;letter-spacing:-.035em;line-height:1.08}
.head i{font-style:normal;color:var(--muted)}
.head .l2{display:block;opacity:0;transform:translateY(.3em);transition:opacity .6s,transform .6s}
.head.on .l2{opacity:1;transform:none}
.sub{margin-top:14px;font-size:clamp(14px,1.4vw,18px);font-weight:400;color:var(--muted);letter-spacing:0}
.card{position:absolute;left:0;top:0;width:var(--cw);height:calc(var(--cw)*1.32);margin:calc(var(--cw)*-.66) 0 0 calc(var(--cw)*-.5);border-radius:calc(var(--cw)*.08);display:grid;place-items:center;font-size:calc(var(--cw)*.34);box-shadow:0 calc(var(--cw)*.05) calc(var(--cw)*.14) #0008,inset 0 0 0 1px #ffffff2a;will-change:transform,opacity;overflow:hidden;z-index:2}
.card::after{content:"";position:absolute;inset:0;background:linear-gradient(140deg,#ffffff38,transparent 45%)}
.card b{position:absolute;left:10%;bottom:8%;font-size:calc(var(--cw)*.075);letter-spacing:.02em;font-weight:700;color:#fff;text-shadow:0 1px 6px #0006}
.end{min-height:70vh;display:grid;place-items:center;text-align:center;padding:40px 16px;font-size:clamp(22px,3vw,40px);letter-spacing:-.02em}
.end a{display:inline-block;margin-top:20px;padding:14px 24px;border-radius:14px;background:var(--ink);color:var(--bg);text-decoration:none;font-weight:700;font-size:16px}
</style></head><body>
<section id="story"><div class="stage" id="stage">
<h1 class="head" id="head">From <i>scattered</i> ideas<span class="l2">to one <i>shipped</i> product</span><p class="sub">Learn it, build it, launch it: one path, one place.</p></h1>
</div></section>
<section class="end"><div>Your story starts with one card.<br><a href="#">Start building</a></div></section>
<script>
(function(){
const art=[['#7c3aed','#22d3ee','🚀','LAUNCH'],['#f43f5e','#fb923c','🎨','DESIGN'],['#0ea5e9','#6366f1','🧠','AI'],['#10b981','#84cc16','📈','GROWTH'],['#f59e0b','#ef4444','⚡','SPEED'],['#8b5cf6','#ec4899','✨','MAGIC'],['#14b8a6','#0ea5e9','🌊','FLOW'],['#e11d48','#7c3aed','🎧','FOCUS'],['#22c55e','#06b6d4','🧪','LABS'],['#f97316','#facc15','🔥','HYPE'],['#3b82f6','#a855f7','🛰️','ORBIT'],['#64748b','#0f172a','🌙','NIGHT']];
const stage=document.getElementById('stage'),head=document.getElementById('head'),story=document.getElementById('story');
const N=12,cards=art.map((a,i)=>{const d=document.createElement('div');d.className='card';d.style.background='linear-gradient(150deg,'+a[0]+','+a[1]+')';d.innerHTML=a[2]+'<b>'+a[3]+'</b>';stage.appendChild(d);return{el:d,p:null}});
const reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;
let W,H,cw,mob;
function size(){W=innerWidth;H=innerHeight;mob=W<820&&W<H*1.1;cw=mob?Math.min(W*.3,H*.2):Math.min(W/1440,H/780)*210;stage.style.setProperty('--cw',cw+'px')}
addEventListener('resize',size);size();
const step=R=>2*Math.asin(Math.min(1,1.05*cw/(2*R)));
const eIO=t=>t<.5?4*t*t*t:1-Math.pow(-2*t+2,3)/2,cl=t=>Math.max(0,Math.min(1,t));
const mix=(a,b,t)=>a.map((v,k)=>v+(b[k]-v)*t);
const SC=mob=>mob?[[.1,.62,-18],[.48,.68,12],[.92,.6,16],[.24,.92,-12],[.74,.95,9],[.5,1.3,0]]:[[.09,.5,-18],[.2,.86,-10],[.8,.84,13],[.38,1.04,-7],[.91,.46,15],[.62,1.04,8]];
function fan(i){if(i>5)return[W*.5,H*1.5,0,0];const R=mob?W*1.35:W*.85,a=(i-2.5)*step(R);return[W*.5+R*Math.sin(a),H*.9+R-R*Math.cos(a),a*57.3,1]}
function scat(i){if(i>5)return[W*.5,H*1.5,0,0];const s=SC(mob)[i];return[s[0]*W,s[1]*H,s[2],1]}
function pile(i){return[W*.5+((i%3)-1)*cw*.08,H*.63+(((i*7)%5)-2)*cw*.04,(i%2?1:-1)*(4+i*1.3),1]}
function wheel(i,rot){const R=mob?W*1.2:Math.max(W*.6,cw*5.5),s=(((i+rot)%N)+N)%N-5.5,a=s*step(R),deg=a*57.3;return[W*.5+R*Math.sin(a),H*(mob?.8:.84)+R-R*Math.cos(a),deg,Math.abs(deg)<62?1:0]}
function target(i,p){
 if(p<.16)return mix(fan(i),scat(i),eIO(cl(p/.16)));
 if(p<.36){const t=eIO(cl((p-.16)/.2)),b=pile(i);if(i>5)b[3]=t;return mix(scat(i),b,t)}
 if(p<.5)return mix(pile(i),wheel(i,0),eIO(cl((p-.36)/.14)));
 return wheel(i,-3*eIO(cl((p-.52)/.38)));
}
const t0=performance.now();let last=t0;
function frame(now){
 const dt=Math.min(64,now-last);last=now;
 const r=story.getBoundingClientRect(),p=cl(-r.top/(r.height-H));
 head.classList.toggle('on',p>.44);
 cards.forEach((c,i)=>{
  let t=target(i,p);
  const intro=(now-t0)/1000-[3,1,0,2,4,5,6,7,8,9,10,11][i]*.08-.2;
  if(p<.002&&i<6&&!reduce){const k=cl(intro/.7),e=1-Math.pow(1-k,3);t=mix([t[0],H+cw*1.2,t[2]+8,1],t,e)}
  if(!c.p||reduce||p<.002)c.p=t.slice();else{const k=1-Math.pow(1-(.055+(i%4)*.008),dt/16.67);c.p=c.p.map((v,j)=>v+(t[j]-v)*k)}
  c.el.style.transform='translate('+c.p[0]+'px,'+c.p[1]+'px) rotate('+c.p[2]+'deg)';c.el.style.opacity=c.p[3];
 });
 requestAnimationFrame(frame);
}
requestAnimationFrame(frame);
})();
</script></body></html>
HTML,
		'prompt'   => '',
		'spec'     => <<<'TXT'
# Card Wheel Story — full build prompt

Build a single-file scroll story (`index.html`, inline CSS + vanilla JS, no libraries, no external assets). A pinned full-screen stage shows 12 colourful art cards that rise into a fan on load, then, as you scroll, scatter, gather into a centre pile and fan out into a wheel along the bottom that turns right-to-left. A two-line headline sits at the top: line 1 "From *scattered* ideas", line 2 "to one *shipped* product" (italic words in the muted colour; line 2 fades up when the wheel forms). A short closing section follows with a CTA button.

## 1. Tokens
| Token | Value |
|---|---|
| bg | `#0C0B14` |
| ink | `#F4F1FF` |
| muted | `#7F7A9C` |
| font | Inter / system-ui, headline weight 650, tracking −0.035em |

## 2. Structure
- `<section id="story">` height **700vh**; inside, a sticky stage (`top:0; height:100vh; overflow:hidden`).
- Headline: absolute, top 15%, centred, `clamp(28px, 4.6vw, 64px)`. Sub-line: "Learn it, build it, launch it: one path, one place.", `clamp(14px, 1.4vw, 18px)`, weight 400, muted.
- 12 card divs (absolute, centred on their transform point). Card width `cw`: phones `min(W*.30, H*.20)`, desktop `min(W/1440, H/780) * 210`. Height `cw*1.32`, radius `cw*.08`, shadow `0 cw*.05 cw*.14 #0008` + inset 1px white 16%. A diagonal white sheen overlay. Each card: a two-colour linear gradient, a big emoji (font `cw*.34`) and a small caps label bottom-left.
- Cards (gradient from → to, emoji, label): violet→cyan 🚀 LAUNCH; rose→orange 🎨 DESIGN; sky→indigo 🧠 AI; emerald→lime 📈 GROWTH; amber→red ⚡ SPEED; violet→pink ✨ MAGIC; teal→sky 🌊 FLOW; rose→violet 🎧 FOCUS; green→cyan 🧪 LABS; orange→yellow 🔥 HYPE; blue→purple 🛰️ ORBIT; slate→navy 🌙 NIGHT.

## 3. Poses `[x, y, rotation°, opacity]`
- Spacing rule for fan and wheel: angular step `step(R) = 2·asin(1.05·cw / 2R)`, so neighbouring cards keep a 5% gap.
- **Fan** (cards 0–5; 6–11 hidden below at `(W/2, 1.5H)`): `R = 0.85W` (phones `1.35W`), angle `(i − 2.5)·step(R)`, centre `(W/2, 0.9H + R)`.
- **Scatter** (fractions of W, H, rotation). Desktop: `(.09,.50,−18) (.20,.86,−10) (.80,.84,13) (.38,1.04,−7) (.91,.46,15) (.62,1.04,8)`. Phones: `(.10,.62,−18) (.48,.68,12) (.92,.60,16) (.24,.92,−12) (.74,.95,9) (.50,1.30,0)`. Cards must never cover the headline.
- **Pile:** `x = W/2 + ((i%3) − 1)·cw·.08`, `y = .63H + (((i·7)%5) − 2)·cw·.04`, rotation `±(4 + 1.3i)°` alternating; cards 6–11 fade in while gathering.
- **Wheel:** 12 slots, `slot = ((i + rot) mod 12) − 5.5`, angle `slot·step(R)`, `R = max(0.6W, 5.5cw)` (phones `1.2W`), centre `(W/2, 0.84H + R)` (phones 0.8H). Visible only when |angle| < 62°.

## 4. Scroll choreography (p = story progress 0–1, eIO = cubic in-out)
| p | Motion |
|---|---|
| 0 → .16 | fan → scatter |
| .16 → .36 | scatter → pile (cards 6–11 fade in) |
| .36 → .50 | pile → wheel (rot 0) |
| .44 | headline line 2 fades up (0.6 s) |
| .52 → .90 | wheel turns 3 slots right-to-left (`rot = −3·eIO`) |

**Inertia:** each frame, every card moves toward its target pose by `k = 1 − (1 − w)^(dt/16.67)`, `w = .055 + (i%4)·.008`, so cards arrive one after another. At p ≈ 0 apply poses directly.

**Load:** cards 0–5 rise from `y = H + 1.2cw` with +8° extra rotation over 0.7 s (ease-out cubic), staggered 80 ms in the order 3, 1, 0, 2, 4, 5.

## 5. Responsive + accessibility
- Phone layout when `W < 820 && W < 1.1H`. Recompute everything on resize. No horizontal scroll at 320–1920 px.
- `prefers-reduced-motion`: no inertia and no intro rise (poses applied directly).
- The headline is a real `<h1>`; the CTA is a real link.

## 6. Acceptance checklist
- Fan → scatter → pile → wheel → turning wheel, all smooth, with a staggered soft arrival.
- The headline stays readable; no card covers it.
- 60 fps (only transform/opacity change).
TXT
	),

	/* ── 2. Pixel Morph Hero (Free) ──────────────────────────────────── */
	array(
		'id'       => 'pixel-morph-hero',
		'title'    => 'Pixel Morph Hero',
		'cat'      => 'Hero',
		'premium'  => false,
		'featured' => true,
		'summary'  => 'Six pixel-art icons pop in, then turn into the letters of “Build with AI” one by one until the headline stands alone.',
		'tags'     => 'pixel icons morph headline intro hero',
		'code'     => $head . <<<'HTML'
<title>Pixel Morph Hero</title><style>
:root{--bg:#F6F1EA;--ink:#161A24;--muted:#8E8790}
*{box-sizing:border-box;margin:0}body{min-height:100vh;display:grid;place-items:center;background:var(--bg);color:var(--ink);font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif;overflow:hidden;padding:20px}
.wrap{text-align:center}
h1{display:flex;align-items:center;justify-content:center;font-size:clamp(40px,9vw,120px);font-weight:600;letter-spacing:-.04em;line-height:1;min-height:1.2em}
.ch{display:inline-block;max-width:0;overflow:hidden;clip-path:inset(0 100% 0 0);transition:max-width .35s cubic-bezier(.25,.75,.2,1),clip-path .35s ease;white-space:pre}
.ch.on{max-width:1em;clip-path:inset(0 0 0 0)}
.ic{display:inline-block;width:.62em;height:.62em;margin-left:.12em;transform:scale(0);transition:transform .32s cubic-bezier(.34,1.56,.64,1),opacity .2s,width .3s,margin .3s,filter .3s}
.ic.in{transform:scale(1)}
.ic.out{transform:scaleY(.16) scaleX(1.25) rotate(-60deg);opacity:0;width:0;margin:0;filter:brightness(0)}
.ic svg{width:100%;height:100%;shape-rendering:crispEdges;display:block}
p{margin-top:22px;color:var(--muted);font-size:clamp(15px,1.6vw,19px)}
a{display:inline-block;margin-top:22px;padding:13px 22px;border-radius:12px;background:var(--ink);color:var(--bg);text-decoration:none;font-weight:600}
</style></head><body><div class="wrap"><h1 id="h" aria-label="Build with AI"></h1><p>Learn to code and create with AI, one tiny step at a time.</p><a href="#">Start free</a></div>
<script>
(function(){
const P={k:'#161A24',b:'#7C9CF5',w:'#ffffff',p:'#F59E8B',y:'#F7C948'};
const icons=[
['....k....','...kwk...','...kbk...','..kbwbk..','..kbbbk..','..kbwbk..','.kkbbbkk.','.k.kyk.k.','....p....'],
['.k.k.k.k.','kkkkkkkkk','.kbbbbbk.','kkbwwwbkk','.kbwkwbk.','kkbwwwbkk','.kbbbbbk.','kkkkkkkkk','.k.k.k.k.'],
['.....kkk.','....kyyk.','...kyyk..','..kyykkk.','.kyyyyyk.','..kkkyyk.','...kyyk..','..kyyk...','..kkk....'],
['.kk...kk.','kppk.kppk','kpwpkpppk','kpppppppk','.kpppppk.','..kpppk..','...kpk...','....k....','.........'],
['....k....','...kyk...','kkkkykkkk','.kyyyyyk.','..kyyyk..','..kykyk..','.kyk.kyk.','.kk...kk.','.........'],
['k........','kk.......','kwk......','kwwk.....','kwwwk....','kwwwwk...','kwwkkk...','kk.kwk...','k...kk...']];
const svg=r=>'<svg viewBox="0 0 9 9" aria-hidden="true">'+r.map((row,y)=>[...row].map((c,x)=>c==='.'?'':'<rect x="'+x+'" y="'+y+'" width="1.02" height="1.02" fill="'+P[c]+'"/>').join('')).join('')+'</svg>';
const word='Build with AI',h=document.getElementById('h'),reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;
const chars=[...word].map(c=>{const s=document.createElement('span');s.className='ch';s.textContent=c;h.appendChild(s);return s});
const ics=icons.map(r=>{const s=document.createElement('span');s.className='ic';s.innerHTML=svg(r);h.appendChild(s);return s});
if(reduce){chars.forEach(c=>c.classList.add('on'));ics.forEach(i=>i.remove());return}
function run(){
 chars.forEach(c=>c.classList.remove('on'));ics.forEach(i=>i.className='ic');
 ics.forEach((ic,j)=>setTimeout(()=>ic.classList.add('in'),300+j*90));
 const total=word.replace(/ /g,'').length;let shown=0,letters=0;
 const tick=()=>{if(shown>=chars.length){setTimeout(run,3200);return}
  const c=chars[shown++];c.classList.add('on');if(c.textContent!==' ')letters++;
  const keep=Math.ceil(ics.length*(total-letters)/total);ics.forEach((ic,j)=>{if(j<ics.length-keep)ic.classList.add('out')});
  setTimeout(tick,200)};
 setTimeout(tick,1300);
}
run();
})();
</script></body></html>
HTML,
		'prompt'   => '',
		'spec'     => <<<'TXT'
# Pixel Morph Hero — full build prompt

Build a one-file hero (`index.html`, inline CSS + vanilla JS, no libraries, no images). On a warm off-white page, six 9×9 pixel-art icons pop into a row; then, every 200 ms, one more letter of the headline **"Build with AI"** wipes in from the left while the leftmost remaining icon squashes into an ink stroke and disappears. When the word is complete no icons remain and the headline is perfectly centred. A sub-line and a dark CTA sit below. The animation loops every few seconds.

## Tokens
| Token | Hex |
|---|---|
| bg | `#F6F1EA` |
| ink (headline, icon outlines, button) | `#161A24` |
| muted (sub-line) | `#8E8790` |
| icon blue | `#7C9CF5` |
| icon peach | `#F59E8B` |
| icon yellow | `#F7C948` |

Font: Inter / system-ui. Headline `clamp(40px, 9vw, 120px)`, weight 600, tracking −0.04em, line-height 1.

## Pixel icons
SVG `viewBox="0 0 9 9"`, `shape-rendering: crispEdges`, each cell a 1.02 × 1.02 rect. Icon size 0.62em, gap 0.12em. Palette: k ink, b blue, w white, p peach, y yellow. Order: rocket, chip, lightning bolt, heart, star, cursor. Rows from top (`.` = empty):
- rocket `....k....` `...kwk...` `...kbk...` `..kbwbk..` `..kbbbk..` `..kbwbk..` `.kkbbbkk.` `.k.kyk.k.` `....p....`
- chip `.k.k.k.k.` `kkkkkkkkk` `.kbbbbbk.` `kkbwwwbkk` `.kbwkwbk.` `kkbwwwbkk` `.kbbbbbk.` `kkkkkkkkk` `.k.k.k.k.`
- bolt `.....kkk.` `....kyyk.` `...kyyk..` `..kyykkk.` `.kyyyyyk.` `..kkkyyk.` `...kyyk..` `..kyyk...` `..kkk....`
- heart `.kk...kk.` `kppk.kppk` `kpwpkpppk` `kpppppppk` `.kpppppk.` `..kpppk..` `...kpk...` `....k....` `.........`
- star `....k....` `...kyk...` `kkkkykkkk` `.kyyyyyk.` `..kyyyk..` `..kykyk..` `.kyk.kyk.` `.kk...kk.` `.........`
- cursor `k........` `kk.......` `kwk......` `kwwk.....` `kwwwk....` `kwwwwk...` `kwwkkk...` `kk.kwk...` `k...kk...`

## Timeline
| t | Event |
|---|---|
| 0.30 + 0.09j s | icon j pops in (scale 0 → 1, back-out `cubic-bezier(.34,1.56,.64,1)`, 0.32 s) |
| 1.3 s, then every 0.2 s | the next character appears: max-width 0 → 1em and clip-path `inset(0 100% 0 0)` → `inset(0)` over 0.35 s with `cubic-bezier(.25,.75,.2,1)`, so the whole row glides and stays centred |
| same step | icons kept = `ceil(6 × lettersLeft / 11)`; removed icons squash (scaleY .16, scaleX 1.25, rotate −60°, brightness 0, width → 0) |
| end + 3.2 s | reset and replay |

## Layout, responsive, accessibility
- A centred column: `<h1 aria-label="Build with AI">` (flex row of character spans + icon spans), sub-line "Learn to code and create with AI, one tiny step at a time.", and a "Start free" button.
- Works from 320 px (font scales with vw); no horizontal scroll.
- Reduced motion: show the full word immediately, no icons.

## Acceptance
- Icons pop in with a spring, then morph letter by letter into a centred "Build with AI".
- No layout jump; text stays crisp; loops cleanly.
TXT
	),

	/* ── 3. Portal Zoom (Pro) ────────────────────────────────────────── */
	array(
		'id'       => 'portal-zoom',
		'title'    => 'Portal Zoom',
		'cat'      => 'Scroll Story',
		'premium'  => true,
		'featured' => true,
		'summary'  => 'A small rounded window grows to full screen around a glowing 3D laptop; keep scrolling and you fly into its screen.',
		'tags'     => 'scroll zoom portal laptop 3d reveal clip-path',
		'code'     => $head . <<<'HTML'
<title>Portal Zoom</title><style>
:root{--bg:#F3EFF8;--ink:#1B1830;--muted:#8F89A6}
*{box-sizing:border-box;margin:0}html,body{background:var(--bg);color:var(--ink);font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif}
#s{height:500vh;position:relative}
.st{position:sticky;top:0;height:100vh;overflow:hidden}
.t{position:absolute;inset:auto 0 0;top:12%;text-align:center;font-size:clamp(28px,5vw,66px);font-weight:650;letter-spacing:-.035em;z-index:1;padding:0 16px}
.t i{font-style:normal;color:var(--muted)}
.win{position:absolute;inset:0;z-index:2;clip-path:inset(40% 41% 30% 41% round 18px);background:radial-gradient(120% 90% at 20% 10%,#a78bfa,transparent 55%),radial-gradient(90% 80% at 85% 90%,#22d3ee,transparent 60%),linear-gradient(160deg,#312e81,#0f172a);overflow:hidden}
.win::before{content:"";position:absolute;inset:-20%;background:conic-gradient(from 0deg,#ffffff00,#ffffff22,#ffffff00 30%);animation:sp 14s linear infinite}
@keyframes sp{to{transform:rotate(360deg)}}
.lap{position:absolute;left:50%;top:52%;width:min(46vw,560px);transform:translate(-50%,-50%);perspective:1000px}
.scr{position:relative;aspect-ratio:16/10;border-radius:14px 14px 4px 4px;background:#0b1020;border:8px solid #1f2437;box-shadow:0 0 60px #22d3ee55;transform-origin:50% 100%;overflow:hidden}
.scr pre{margin:0;padding:6%;font:clamp(7px,1vw,13px)/1.7 ui-monospace,Menlo,Consolas,monospace;color:#a5f3fc;white-space:pre-wrap}
.scr pre b{color:#f0abfc;font-weight:400}.scr pre u{color:#fde68a;text-decoration:none}
.base{height:clamp(8px,1.4vw,16px);margin:0 -7%;border-radius:0 0 18px 18px;background:linear-gradient(#cbd5e1,#64748b);box-shadow:0 18px 40px #0007}
.word{position:absolute;z-index:3;color:#fff;font-size:clamp(20px,3.6vw,46px);font-weight:600;letter-spacing:-.03em;opacity:0;transition:opacity .6s,transform .6s;transform:translateY(.4em)}
.w1{left:5%;top:14%}.w2{right:5%;bottom:10%;text-align:right}.on .word{opacity:1;transform:none}
.word i{font-style:normal;color:#c4b5fd}
.blk{position:absolute;inset:0;background:#05040c;opacity:0;z-index:4;pointer-events:none}
.after{min-height:100vh;display:grid;place-items:center;background:#05040c;color:#fff;text-align:center;font-size:clamp(26px,4vw,52px);letter-spacing:-.03em;padding:40px 16px}
</style></head><body>
<section id="s"><div class="st"><h2 class="t">Step <i>inside</i> the future</h2>
<div class="win" id="win"><div class="lap" id="lap"><div class="scr" id="scr"><pre><b>const</b> you = <u>"curious"</u>;
<b>while</b> (you.learn()) {
  skills.push(<u>"new"</u>);
  dreams.ship();
}
<b>export default</b> you;</pre></div><div class="base"></div></div>
<p class="word w1"><i>Not just</i> lessons</p><p class="word w2"><i>Real</i> things <i>you</i> build</p></div>
<div class="blk" id="blk"></div></div></section>
<section class="after"><div>Welcome to the inside.</div></section>
<script>
(function(){
const s=document.getElementById('s'),win=document.getElementById('win'),lap=document.getElementById('lap'),scr=document.getElementById('scr'),blk=document.getElementById('blk');
const cl=t=>Math.max(0,Math.min(1,t)),eIO=t=>t<.5?4*t*t*t:1-Math.pow(-2*t+2,3)/2,reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;
let g=0,last=performance.now();
function f(now){const dt=Math.min(64,now-last);last=now;const r=s.getBoundingClientRect(),p=cl(-r.top/(r.height-innerHeight));
 const target=eIO(cl(p/.35));g=reduce?target:g+(target-g)*(1-Math.pow(1-.09,dt/16.67));
 const mob=innerWidth<820&&innerWidth<innerHeight*1.1,t0=mob?[42,21,36,21]:[40,41,30,41];
 const ins=t0.map(v=>v*(1-g)),rad=18*(1-g);
 win.style.clipPath='inset('+ins[0]+'% '+ins[1]+'% '+ins[2]+'% '+ins[3]+'% round '+rad+'px)';
 win.classList.toggle('on',g>.98);
 const yaw=-12+24*cl((p-.35)/.3),bob=Math.sin(now/600)*6;
 const z=Math.pow(cl((p-.68)/.27),1.6)*(g>.9?1:0),sc=(.55+.45*g)*(1+z*z*26);
 lap.style.transform='translate(-50%,-50%) translateY('+(reduce?0:bob)+'px) scale('+sc+')';
 lap.style.transformOrigin='50% 40%';scr.style.transform='rotateX(4deg) rotateY('+(reduce?0:yaw*(1-z))+'deg)';
 blk.style.opacity=cl((z-.45)/.4);requestAnimationFrame(f)}
requestAnimationFrame(f);
})();
</script></body></html>
HTML,
		'prompt'   => '',
		'spec'     => <<<'TXT'
# Portal Zoom — full build prompt

Build a one-file scroll story (`index.html`, inline CSS + vanilla JS, no libraries, CSS-only art). A pale lilac page shows the headline "Step *inside* the future". As you scroll, a small rounded window in the centre grows to full screen, revealing a deep violet-to-cyan glowing scene with a CSS laptop whose screen shows a tiny code poem. The laptop gently yaws and bobs. Two lines of text appear in the corners. Then the camera flies into the laptop screen until everything goes black, and a dark section says "Welcome to the inside."

## Tokens
- bg `#F3EFF8`; ink `#1B1830`; muted `#8F89A6`; scene `#312E81` → `#0F172A` with glows `#A78BFA` (top-left) and `#22D3EE` (bottom-right); code `#A5F3FC`, keywords `#F0ABFC`, strings `#FDE68A`; black `#05040C`.
- Font: Inter / system-ui; headline `clamp(28px, 5vw, 66px)`, weight 650, tracking −0.035em.

## Structure
- `<section>` height 500vh with a sticky 100vh stage: headline (top 12%), the window layer (full stage, clipped), and a blackout layer.
- Window: `clip-path: inset(40% 41% 30% 41% round 18px)` at rest (phones `42% 21% 36% 21%`). The background is two radial glows over a linear gradient, plus a slowly rotating soft conic sheen (14 s).
- Laptop: width `min(46vw, 560px)`, centred at 52% height. Screen 16:10, an 8 px `#1F2437` bezel, 14 px top corners, a cyan glow shadow, a monospace code poem:
  `const you = "curious"; while (you.learn()) { skills.push("new"); dreams.ship(); } export default you;` (on 6 lines). Base: a thin slate gradient bar wider than the screen, rounded bottom.
- Corner words (white, `clamp(20px, 3.6vw, 46px)`): top-left "*Not just* lessons", bottom-right "*Real* things *you* build" (italic = violet `#C4B5FD`).

## Choreography (p = section progress)
| p | Motion |
|---|---|
| 0 → .35 | Window grows: inset values × (1 − g) and radius 18 px → 0, where `g` chases `eIO(p/.35)` with weight .09 per frame (frame-rate independent) |
| g > .98 | Corner words fade up over 0.6 s |
| always | Laptop bob ±6 px (sine, ~3.8 s period); scale `0.55 + 0.45·g` |
| .35 → .65 | Screen yaw −12° → +12° (perspective 1000 px, slight rotateX 4°) |
| .68 → .95 | Zoom `z = ((p − .68)/.27)^1.6` (only once g > .9): scale × `(1 + z²·26)`, transform-origin 50% 40%; yaw eases to 0; blackout opacity follows `(z − .45)/.4` |

## Responsive + accessibility
- Phone window sizes as above; fonts clamp; no horizontal scroll.
- Reduced motion: no bob or yaw, and the window follows scroll directly.

## Acceptance
- The window grows smoothly to full bleed, words appear, the laptop yaws, then the view dives into the screen and fades to black into the next section.
TXT
	),

	/* ── 4. Sideways Manifesto (Pro) ─────────────────────────────────── */
	array(
		'id'       => 'sideways-manifesto',
		'title'    => 'Sideways Manifesto',
		'cat'      => 'Scroll Story',
		'premium'  => true,
		'featured' => false,
		'summary'  => 'One huge sentence slides sideways as you scroll; letters drop in and pastel stickers pop with a spring.',
		'tags'     => 'horizontal scroll manifesto stickers text reveal',
		'code'     => $head . <<<'HTML'
<title>Sideways Manifesto</title><style>
:root{--bg:#101118;--grey:#6E7180;--mfs:clamp(44px,7vw,110px)}
*{box-sizing:border-box;margin:0}html,body{background:var(--bg);color:#fff;font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif}
.intro,.outro{min-height:60vh;display:grid;place-items:center;color:var(--grey);font-size:15px;letter-spacing:.16em;text-transform:uppercase}
#m{height:600vh;position:relative}
.st{position:sticky;top:0;height:100vh;overflow:hidden}
.tr{position:absolute;left:0;top:50%;display:flex;align-items:center;gap:calc(var(--mfs)*.32);white-space:nowrap;font-size:var(--mfs);font-weight:600;letter-spacing:-.03em;will-change:transform}
.w{display:inline-flex}.w.g{color:var(--grey)}
.w span{display:inline-block;opacity:0;transform:translateY(.55em) rotate(14deg);transform-origin:0 100%;transition:opacity .5s cubic-bezier(.22,1,.36,1),transform .5s cubic-bezier(.22,1,.36,1)}
.w.on span{opacity:1;transform:none}
.st2{display:inline-block;font-size:.3em;font-weight:700;color:#14151d;padding:.3em .62em;border-radius:.2em;box-shadow:0 .14em .35em #0007;transform:scale(0) rotate(-14deg);transition:transform .5s cubic-bezier(.34,1.56,.64,1) .12s}
.st2.on{transform:scale(1) rotate(var(--r))}
.emo{display:inline-block;font-size:.9em;transform:scale(0) rotate(-14deg);transition:transform .5s cubic-bezier(.34,1.56,.64,1) .12s;filter:drop-shadow(0 .08em .12em #0008)}
.emo.on{transform:scale(1) rotate(var(--r))}
</style></head><body>
<section class="intro">Scroll ↓</section>
<section id="m"><div class="st"><div class="tr" id="tr"></div></div></section>
<section class="outro">Built by learners, for learners.</section>
<script>
(function(){
const items=[['w','We'],['w','turn','g'],['s','curious minds','#F9D5C4',-6],['w','into'],['e','🧠',-10],['w','builders'],['w','—','g'],['w','one','g'],['w','line,'],['s','no fluff','#C8E6F5',5],['w','one','g'],['w','lesson,'],['e','✨',8],['w','one','g'],['w','launch'],['s','ship it','#E9D5FF',-8],['e','🚀',-12],['w','at','g'],['w','a','g'],['w','time.'],['e','💡',10],['sp','']];
const tr=document.getElementById('tr'),m=document.getElementById('m'),reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;
const els=items.map(it=>{let e;if(it[0]==='w'){e=document.createElement('span');e.className='w'+(it[2]?' g':'');e.innerHTML=[...it[1]].map(c=>'<span>'+(c===' '?'&nbsp;':c)+'</span>').join('');[...e.children].forEach((c,i)=>c.style.transitionDelay=(i*35)+'ms')}
 else if(it[0]==='s'){e=document.createElement('span');e.className='st2';e.textContent=it[1];e.style.background=it[2];e.style.setProperty('--r',it[3]+'deg')}
 else if(it[0]==='e'){e=document.createElement('span');e.className='emo';e.textContent=it[1];e.style.setProperty('--r',it[2]+'deg')}
 else{e=document.createElement('span');e.style.width='30vw';e.style.flex='0 0 30vw'}
 tr.appendChild(e);return e});
function f(){const W=innerWidth,r=m.getBoundingClientRect(),p=Math.max(0,Math.min(1,-r.top/(r.height-innerHeight)));
 const x0=W*.06,x1=-(tr.scrollWidth-W*.9),x=x0+(x1-x0)*p;tr.style.transform='translate('+x+'px,-50%)';
 const near=r.top<innerHeight*.2;els.forEach(e=>{if(!e.classList.contains('on')&&(reduce||(near&&e.getBoundingClientRect().left<W*.86)))e.classList.add('on')});
 requestAnimationFrame(f)}
requestAnimationFrame(f);
})();
</script></body></html>
HTML,
		'prompt'   => '',
		'spec'     => <<<'TXT'
# Sideways Manifesto — full build prompt

Build a one-file section (`index.html`, inline CSS + vanilla JS). A near-black section 600vh tall holds a sticky 100vh stage. Inside it, one giant sentence on a single line slides from right to left as you scroll vertically: "We turn *curious minds* into 🧠 builders — one line, *no fluff* one lesson, ✨ one launch *ship it* 🚀 at a time. 💡". Words reveal letter by letter as they cross 86% of the screen width. Pastel stickers and emoji pop in with a spring.

## Tokens
- bg `#101118`; white text; grey words `#6E7180` ("turn", "—", "one", "at", "a").
- Line size `--mfs: clamp(44px, 7vw, 110px)`, weight 600, tracking −0.03em; gap between items `0.32 × mfs`.
- Stickers: `curious minds` `#F9D5C4` (−6°), `no fluff` `#C8E6F5` (+5°), `ship it` `#E9D5FF` (−8°). Font 0.3em of the line, weight 700, dark text `#14151D`, padding .3em .62em, radius .2em, shadow `0 .14em .35em #0007`.
- Emoji: 🧠 (−10°), ✨ (+8°), 🚀 (−12°), 💡 (+10°) at 0.9em, with a drop-shadow.

## Motion
- Track: absolute, left 0, top 50%, flex row, nowrap. translateX goes from `0.06W` to `−(trackWidth − 0.9W)` linearly with section progress; translateY −50%.
- Reveal once, only after the section is within 20% of the viewport top, when an item's left edge passes `0.86W`.
  - Words: each letter goes from opacity 0, translateY .55em, rotate 14° (origin bottom-left) to rest over 0.5 s, `cubic-bezier(.22,1,.36,1)`, 35 ms stagger.
  - Stickers/emoji: 120 ms delay, scale 0 → 1 with back-out `cubic-bezier(.34,1.56,.64,1)` over 0.5 s, rotating from −14° to their angle.
- A 30vw spacer at the end so the last word clears the screen.

## Responsive + accessibility
- Works at every width (the font is clamped); no vertical clipping; no horizontal page scroll (only the track moves).
- Reduced motion: everything revealed immediately; the track still follows scroll.

## Acceptance
- The sentence glides sideways smoothly; letters drop in; stickers spring in; nothing jitters.
TXT
	),

	/* ── 5. Infinite Gallery (Free) ──────────────────────────────────── */
	array(
		'id'       => 'infinite-gallery',
		'title'    => 'Infinite Gallery',
		'cat'      => 'Interactive',
		'premium'  => false,
		'featured' => true,
		'summary'  => 'An endless wall of project tiles you can drag or scroll in any direction, gliding with inertia.',
		'tags'     => 'drag infinite grid gallery inertia portfolio',
		'code'     => $head . <<<'HTML'
<title>Infinite Gallery</title><style>
*{box-sizing:border-box;margin:0}html,body{height:100%;background:#0a0a0f;overflow:hidden;font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif;touch-action:none;user-select:none}
#g{position:fixed;inset:0;cursor:grab}#g.drag{cursor:grabbing}
.t{position:absolute;left:0;top:0;border-radius:18px;overflow:hidden;display:grid;place-items:center;font-size:44px;box-shadow:0 10px 30px #0008;will-change:transform}
.t b{position:absolute;left:14px;bottom:12px;color:#fff;font-size:13px;font-weight:700;letter-spacing:.04em;text-shadow:0 1px 6px #0007}
.hud{position:fixed;left:50%;bottom:22px;transform:translateX(-50%);padding:10px 16px;border-radius:999px;background:#ffffff14;border:1px solid #ffffff26;color:#d4d4d8;font-size:13px;backdrop-filter:blur(8px);pointer-events:none}
h1{position:fixed;left:24px;top:18px;color:#fff;font-size:20px;letter-spacing:-.02em;pointer-events:none;z-index:2}
</style></head><body><h1>Gallery ∞</h1><div id="g"></div><div class="hud">Drag or scroll to explore</div>
<script>
(function(){
const g=document.getElementById('g'),COLS=6,ROWS=5,TW=240,TH=300,GAP=24;
const em=['🚀','🎨','🧠','📈','⚡','✨','🌊','🎧','🧪','🔥','🛰️','🌙','🍃','🎮','📷','💎','🧩','🪐'];
const nm=['Launchpad','Canvas','Neuron','Growth','Volt','Spark','Tide','Studio','Lab','Blaze','Orbit','Lunar','Leaf','Arcade','Lens','Gem','Puzzle','Saturn'];
const tiles=[];for(let r=0;r<ROWS;r++)for(let c=0;c<COLS;c++){const i=(r*COLS+c)%em.length,h=(i*47)%360,d=document.createElement('div');d.className='t';d.style.width=TW+'px';d.style.height=(TH-((c+r)%3)*30)+'px';d.style.background='linear-gradient(150deg,hsl('+h+' 75% 55%),hsl('+((h+60)%360)+' 70% 35%))';d.innerHTML=em[i]+'<b>'+nm[i]+'</b>';g.appendChild(d);tiles.push({el:d,c,r})}
const SW=COLS*(TW+GAP),SH=ROWS*(TH+GAP);let x=0,y=0,vx=-.6,vy=-.3,down=false,lx=0,ly=0,last=performance.now();
const reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;if(reduce){vx=vy=0}
g.addEventListener('pointerdown',e=>{down=true;lx=e.clientX;ly=e.clientY;g.classList.add('drag');g.setPointerCapture(e.pointerId)});
g.addEventListener('pointermove',e=>{if(!down)return;const dx=e.clientX-lx,dy=e.clientY-ly;x+=dx;y+=dy;vx=dx;vy=dy;lx=e.clientX;ly=e.clientY});
const up=()=>{down=false;g.classList.remove('drag')};g.addEventListener('pointerup',up);g.addEventListener('pointercancel',up);
addEventListener('wheel',e=>{e.preventDefault();vx-=e.deltaX*.08;vy-=e.deltaY*.08},{passive:false});
const mod=(a,n)=>((a%n)+n)%n;
function f(now){const dt=Math.min(64,now-last)/16.67;last=now;
 if(!down){x+=vx*dt;y+=vy*dt;const fr=Math.pow(.95,dt);vx*=fr;vy*=fr;if(!reduce&&Math.abs(vx)+Math.abs(vy)<.4){vx+=(-.5-vx)*.02;vy+=(-.25-vy)*.02}}
 for(const t of tiles){const px=mod(t.c*(TW+GAP)+x+(t.r%2)*(TW/2),SW)-TW-GAP,py=mod(t.r*(TH+GAP)+y,SH)-TH-GAP;t.el.style.transform='translate('+px+'px,'+py+'px)'}
 requestAnimationFrame(f)}
requestAnimationFrame(f);
})();
</script></body></html>
HTML,
		'prompt'   => '',
		'spec'     => <<<'TXT'
# Infinite Gallery — full build prompt

Build a one-file interactive gallery (`index.html`, inline CSS + vanilla JS, no libraries, no images). A dark full-screen canvas of 30 colourful tiles (6 columns × 5 rows, repeating forever) that you can drag with mouse or touch, or move with the wheel/trackpad, in any direction. On release it glides with inertia, then settles into a slow diagonal drift.

## Tokens
- bg `#0A0A0F`; tile radius 18 px, shadow `0 10px 30px #0008`; HUD pill: white 8% background, white 15% border, `#D4D4D8` text, blur 8 px.
- Tiles: 240 px wide, heights 300/270/240 px (varying by `(col + row) % 3`), gap 24 px; odd rows offset by half a tile (brick layout).
- Each tile: a linear gradient from `hsl(h, 75%, 55%)` to `hsl(h + 60, 70%, 35%)` with `h = (i·47) mod 360`, a big emoji centred (44 px), and a small label bottom-left.
- 18 emoji/label pairs: 🚀 Launchpad, 🎨 Canvas, 🧠 Neuron, 📈 Growth, ⚡ Volt, ✨ Spark, 🌊 Tide, 🎧 Studio, 🧪 Lab, 🔥 Blaze, 🛰️ Orbit, 🌙 Lunar, 🍃 Leaf, 🎮 Arcade, 📷 Lens, 💎 Gem, 🧩 Puzzle, 🪐 Saturn.

## Behaviour
- Wrap-around: tile position `= mod(base + offset, gridSize) − tile − gap` on both axes, so the wall never ends.
- Pointer events with pointer capture: drag adds the movement directly; the last move becomes the velocity.
- Wheel: `preventDefault()`; velocity −= delta × .08.
- Every frame (dt-scaled): position += velocity; friction `0.95^dt`; when nearly still, ease the velocity toward a drift of (−0.5, −0.25).
- Cursor grab/grabbing; `touch-action: none`; `user-select: none`.
- Title "Gallery ∞" top-left; HUD "Drag or scroll to explore" bottom-centre.

## Responsive + accessibility
- Fills any screen; works with touch.
- Reduced motion: no drift (inertia from your own drag is fine).

## Acceptance
- Endless in every direction, buttery inertia, no visible seams or popping tiles.
TXT
	),

	/* ── 6. Sphere Gallery (Pro) ─────────────────────────────────────── */
	array(
		'id'       => 'sphere-gallery',
		'title'    => 'Sphere Gallery',
		'cat'      => 'Interactive',
		'premium'  => true,
		'featured' => true,
		'summary'  => 'Dozens of tiles float on a 3D globe that spins by itself and follows your drag with inertia.',
		'tags'     => '3d sphere globe gallery drag portfolio',
		'code'     => $head . <<<'HTML'
<title>Sphere Gallery</title><style>
*{box-sizing:border-box;margin:0}html,body{height:100%;background:radial-gradient(circle at 50% 45%,#16162a,#06060c 70%);overflow:hidden;font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif;touch-action:none;user-select:none}
#v{position:fixed;inset:0;perspective:1100px;cursor:grab}
#b{position:absolute;left:50%;top:50%;transform-style:preserve-3d}
.t{position:absolute;width:74px;height:56px;margin:-28px 0 0 -37px;border-radius:10px;display:grid;place-items:center;font-size:24px;box-shadow:0 6px 18px #0009;backface-visibility:hidden;transition:opacity .3s}
.c{position:fixed;inset:0;display:grid;place-items:center;pointer-events:none;color:#fff;font-weight:800;letter-spacing:.3em;font-size:clamp(18px,3vw,30px);text-shadow:0 0 30px #8b5cf6}
.hint{position:fixed;bottom:20px;width:100%;text-align:center;color:#a1a1aa;font-size:13px}
</style></head><body><div id="v"><div id="b"></div></div><div class="c">VITAL</div><p class="hint">Drag to spin</p>
<script>
(function(){
const b=document.getElementById('b'),v=document.getElementById('v'),N=64,em=['🚀','🎨','🧠','📈','⚡','✨','🌊','🎧','🧪','🔥','🛰️','🌙','🎮','📷','💎','🧩'];
let R=Math.min(innerWidth,innerHeight)*.36;const tiles=[];
for(let i=0;i<N;i++){const y=1-2*(i+.5)/N,r=Math.sqrt(1-y*y),th=i*2.399963,h=(i*37)%360,d=document.createElement('div');d.className='t';d.style.background='linear-gradient(150deg,hsl('+h+' 80% 60%),hsl('+((h+50)%360)+' 70% 38%))';d.textContent=em[i%em.length];b.appendChild(d);
 tiles.push({el:d,lat:Math.asin(y)*57.3,lon:Math.atan2(Math.sin(th)*r,Math.cos(th)*r)*57.3})}
function place(){R=Math.min(innerWidth,innerHeight)*.36;tiles.forEach(t=>t.el.style.transform='rotateY('+t.lon+'deg) rotateX('+t.lat+'deg) translateZ('+R+'px)')}
place();addEventListener('resize',place);
let rx=-12,ry=0,vx=0,vy=.18,down=false,lx=0,ly=0,last=performance.now();const reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;if(reduce)vy=0;
v.addEventListener('pointerdown',e=>{down=true;lx=e.clientX;ly=e.clientY;v.setPointerCapture(e.pointerId);v.style.cursor='grabbing'});
v.addEventListener('pointermove',e=>{if(!down)return;vy=(e.clientX-lx)*.25;vx=-(e.clientY-ly)*.25;ry+=vy;rx+=vx;lx=e.clientX;ly=e.clientY});
const up=()=>{down=false;v.style.cursor='grab'};v.addEventListener('pointerup',up);v.addEventListener('pointercancel',up);
function f(now){const dt=Math.min(64,now-last)/16.67;last=now;
 if(!down){ry+=vy*dt;rx+=vx*dt;vx*=Math.pow(.94,dt);vy+=((reduce?0:.18)-vy)*.03*dt;rx+=(-12-rx)*.02*dt}
 rx=Math.max(-60,Math.min(60,rx));b.style.transform='rotateX('+rx+'deg) rotateY('+ry+'deg)';requestAnimationFrame(f)}
requestAnimationFrame(f);
})();
</script></body></html>
HTML,
		'prompt'   => '',
		'spec'     => <<<'TXT'
# Sphere Gallery — full build prompt

Build a one-file 3D gallery (`index.html`, inline CSS + vanilla JS, CSS 3D transforms only, no WebGL, no images). 64 small gradient tiles with emoji are spread evenly over a sphere that spins slowly on its own. Drag to spin it in any direction with inertia; it eases back to a gentle tilt and auto-spin. A glowing word "VITAL" sits in the centre.

## Tokens
- Background: radial gradient `#16162A` (centre) → `#06060C`.
- Tiles: 74 × 56 px, radius 10 px, shadow `0 6px 18px #0009`, gradient `hsl(h, 80%, 60%)` → `hsl(h + 50, 70%, 38%)` with `h = (i·37) mod 360`, emoji 24 px, `backface-visibility: hidden`.
- Centre word: white, weight 800, tracking .3em, glow `0 0 30px #8B5CF6`. Hint "Drag to spin" at the bottom, `#A1A1AA`.

## Geometry
- Even spread with a Fibonacci sphere: `y = 1 − 2(i + .5)/N`, `r = √(1 − y²)`, `θ = i·2.399963`; latitude = `asin(y)`, longitude = `atan2(sin θ·r, cos θ·r)`.
- Each tile: `rotateY(lon) rotateX(lat) translateZ(R)` with `R = 0.36 × min(W, H)` (recomputed on resize).
- Container: `perspective: 1100px`; the ball (`transform-style: preserve-3d`) is centred and rotated by `rotateX(rx) rotateY(ry)`.

## Motion
- Pointer drag (with capture): `vy = dx·.25`, `vx = −dy·.25`, applied immediately.
- Idle (dt-scaled): friction on vx `0.94^dt`; vy eases toward 0.18°/frame auto-spin; rx eases back to −12°; rx clamped to ±60°.

## Responsive + accessibility
- Size scales with the viewport; touch works (`touch-action: none`).
- Reduced motion: no auto-spin.

## Acceptance
- A smooth, solid-feeling globe of tiles; back tiles hidden; drag feels physical; no jitter.
TXT
	),

	/* ── 7. Mouse Trail CTA (Free) ───────────────────────────────────── */
	array(
		'id'       => 'mouse-trail-cta',
		'title'    => 'Mouse Trail CTA',
		'cat'      => 'CTA',
		'premium'  => false,
		'featured' => false,
		'summary'  => 'Colourful mini cards spill out behind your cursor or finger and fade away around a calm call to action.',
		'tags'     => 'cursor trail cta cards playful',
		'code'     => $head . <<<'HTML'
<title>Mouse Trail CTA</title><style>
*{box-sizing:border-box;margin:0}html,body{height:100%;background:#F4F4F5;overflow:hidden;font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif}
.cta{position:fixed;inset:0;display:grid;place-items:center;text-align:center;z-index:2;pointer-events:none;padding:20px}
h1{font-size:clamp(34px,6vw,76px);letter-spacing:-.04em;font-weight:500;color:#18181B}
h1 i{font-style:normal;color:#A1A1AA}
a{pointer-events:auto;display:inline-flex;align-items:center;gap:10px;margin-top:22px;padding:12px 18px 12px 12px;border-radius:999px;background:#18181B;color:#fff;text-decoration:none;font-weight:600;box-shadow:0 10px 30px #0003}
a span{width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#a78bfa,#22d3ee);display:grid;place-items:center}
.tr{position:fixed;left:0;top:0;width:120px;height:84px;margin:-42px 0 0 -60px;border-radius:12px;display:grid;place-items:center;font-size:30px;box-shadow:0 12px 30px #0003;pointer-events:none;z-index:1;animation:out .9s ease-in forwards}
@keyframes out{0%{opacity:0;transform:translate(var(--x),var(--y)) scale(.6) rotate(var(--r))}12%{opacity:1;transform:translate(var(--x),var(--y)) scale(1) rotate(var(--r))}70%{opacity:1}100%{opacity:0;transform:translate(var(--x),calc(var(--y) + 40px)) scale(.85) rotate(var(--r))}}
</style></head><body><div class="cta"><div><h1>Let’s build <i>something</i><br>you’re proud of</h1><a href="#"><span>✦</span>Start your first project</a></div></div>
<script>
(function(){
if(matchMedia('(prefers-reduced-motion: reduce)').matches)return;
const em=['🚀','🎨','🧠','⚡','✨','🎧','🧪','🔥','🛰️','💎'];let lx=-999,ly=-999,i=0;
function spawn(x,y){const d=document.createElement('div'),h=(i*53)%360;d.className='tr';d.style.background='linear-gradient(150deg,hsl('+h+' 80% 62%),hsl('+((h+50)%360)+' 75% 45%))';d.textContent=em[i%em.length];d.style.setProperty('--x',x+'px');d.style.setProperty('--y',y+'px');d.style.setProperty('--r',(Math.random()*24-12)+'deg');document.body.appendChild(d);i++;d.addEventListener('animationend',()=>d.remove())}
addEventListener('pointermove',e=>{if(Math.hypot(e.clientX-lx,e.clientY-ly)>70){lx=e.clientX;ly=e.clientY;spawn(lx,ly)}});
let a=0;const demo=setInterval(()=>{if(lx>-999)return clearInterval(demo);a+=.5;spawn(innerWidth/2+Math.cos(a)*innerWidth*.32,innerHeight/2+Math.sin(a*1.3)*innerHeight*.3)},180);
addEventListener('pointermove',()=>clearInterval(demo),{once:true});
})();
</script></body></html>
HTML,
		'prompt'   => '',
		'spec'     => <<<'TXT'
# Mouse Trail CTA — full build prompt

Build a one-file CTA section (`index.html`, inline CSS + vanilla JS, no libraries, no images). On a light zinc background, a calm centred headline "Let’s build *something* you’re proud of" and a dark pill button "Start your first project". Moving the cursor (or a finger) leaves a trail of colourful mini cards with emoji that pop in, hang for a moment and drop away. Before any movement, a demo trail loops around the centre so the effect is visible straight away.

## Tokens
- bg `#F4F4F5`; headline `#18181B`, `clamp(34px, 6vw, 76px)`, weight 500, tracking −0.04em; italic word `#A1A1AA`.
- Button: `#18181B`, white text, radius 999 px, shadow `0 10px 30px #0003`, a 30 px gradient dot (`#A78BFA` → `#22D3EE`) with ✦ on the left.
- Trail card: 120 × 84 px, radius 12 px, shadow `0 12px 30px #0003`, gradient `hsl(h, 80%, 62%)` → `hsl(h + 50, 75%, 45%)` with `h = (i·53) mod 360`, emoji 30 px.

## Behaviour
- Spawn a card when the pointer has moved more than 70 px since the last one. Position via CSS variables `--x`, `--y`; random rotation ±12°.
- Keyframes, 0.9 s: fade/scale in (0 → 12%), hold, then drop 40 px and fade out; remove the element on `animationend`.
- Demo: every 180 ms spawn along a Lissajous path (`cos a`, `sin 1.3a`) until the first pointer move.
- The headline layer ignores pointer events except on the button.

## Accessibility
- Reduced motion: no trail at all. The button is a real link with visible focus.

## Acceptance
- A playful, clean trail; it never blocks the button; no leftover DOM nodes.
TXT
	),
);
