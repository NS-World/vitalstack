<?php
/**
 * Motion Studio: original animated sections. Each is one self-contained HTML
 * document (no libraries, no network), responsive, and calmer for visitors
 * who ask for reduced motion.
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

$m = function ( $id, $title, $cat, $premium, $summary, $tags, $code, $prompt ) {
	return array( 'id' => $id, 'title' => $title, 'cat' => $cat, 'premium' => $premium, 'summary' => $summary, 'tags' => $tags, 'code' => $code, 'prompt' => $prompt );
};

$head = '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';

return array_merge(
	include __DIR__ . '/motion-v2.php',
	array(
	$m(
		'aurora-hero',
		'Aurora Hero',
		'Hero',
		false,
		'Soft glowing aurora blobs drifting behind a bold headline and two buttons.',
		'gradient blur hero landing',
		$head . <<<'HTML'
<title>Aurora Hero</title><style>
*{box-sizing:border-box;margin:0}body{min-height:100vh;display:grid;place-items:center;background:#05060f;color:#fff;font-family:system-ui,-apple-system,Segoe UI,Roboto,sans-serif;overflow:hidden}
.blob{position:absolute;width:46vmax;height:46vmax;border-radius:50%;filter:blur(70px);opacity:.55;mix-blend-mode:screen;animation:drift 18s ease-in-out infinite alternate}
.b1{background:#7c3aed;top:-18vmax;left:-10vmax}.b2{background:#06b6d4;bottom:-20vmax;right:-12vmax;animation-delay:-6s}.b3{background:#22c55e;top:30%;left:40%;width:30vmax;height:30vmax;animation-delay:-11s}
@keyframes drift{to{transform:translate(8vmax,6vmax) scale(1.15)}}
main{position:relative;text-align:center;padding:24px;max-width:760px}
.tag{display:inline-block;padding:6px 14px;border:1px solid #ffffff30;border-radius:99px;font-size:13px;background:#ffffff0d;backdrop-filter:blur(6px)}
h1{font-size:clamp(34px,7vw,72px);line-height:1.02;letter-spacing:-.03em;margin:18px 0 14px}
p{color:#c9cbe0;font-size:clamp(15px,2.2vw,19px)}
.btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:26px}
a{padding:13px 22px;border-radius:12px;text-decoration:none;font-weight:700}
.p{background:#fff;color:#05060f}.g{border:1px solid #ffffff40;color:#fff}
@media (prefers-reduced-motion:reduce){.blob{animation:none}}
</style></head><body>
<span class="blob b1"></span><span class="blob b2"></span><span class="blob b3"></span>
<main><span class="tag">✨ New: AI-powered learning</span><h1>Build the future, one line of code at a time</h1><p>Short lessons, live examples and real projects. Start free today.</p><div class="btns"><a class="p" href="#">Start learning</a><a class="g" href="#">See courses</a></div></main>
</body></html>
HTML,
		'Create a full-screen hero section in plain HTML and CSS: a near-black background with three large blurred circles (violet, cyan, green) that drift slowly using CSS keyframes and mix-blend-mode: screen. Centre a pill badge, a large tight headline, a muted subheading and two buttons (solid white, outlined). Make it responsive with clamp() font sizes and turn the animation off for prefers-reduced-motion.'
	),
	$m(
		'neon-grid',
		'Neon Grid Horizon',
		'Background',
		false,
		'A retro synthwave grid floor rushing towards you under a glowing sun.',
		'synthwave perspective grid retro',
		$head . <<<'HTML'
<title>Neon Grid</title><style>
*{margin:0;box-sizing:border-box}body{height:100vh;overflow:hidden;background:linear-gradient(#0b0221 0%,#2b0a4a 55%,#0b0221 55%);font-family:system-ui,sans-serif;color:#fff}
.sun{position:absolute;left:50%;top:22%;width:min(46vw,340px);aspect-ratio:1;transform:translateX(-50%);border-radius:50%;background:linear-gradient(#ffd166,#ff4d8d);box-shadow:0 0 80px #ff4d8d88;-webkit-mask:repeating-linear-gradient(#000 0 14px,transparent 14px 20px);mask:repeating-linear-gradient(to bottom,#000 0 60%,transparent 60% 100%)}
.floor{position:absolute;left:-50%;right:-50%;bottom:0;height:45%;perspective:300px;overflow:hidden}
.grid{position:absolute;inset:-100% 0 0;transform:rotateX(70deg);background-image:linear-gradient(#ff4dd8 2px,transparent 2px),linear-gradient(90deg,#ff4dd8 2px,transparent 2px);background-size:60px 60px;animation:run 1.2s linear infinite;box-shadow:inset 0 0 120px #0b0221}
@keyframes run{to{background-position:0 60px}}
h1{position:absolute;width:100%;text-align:center;top:58%;font-size:clamp(28px,7vw,64px);letter-spacing:.08em;text-shadow:0 0 18px #ff4dd8,0 0 40px #7c3aed;z-index:2}
@media (prefers-reduced-motion:reduce){.grid{animation:none}}
</style></head><body><div class="sun"></div><div class="floor"><div class="grid"></div></div><h1>NEXT LEVEL</h1></body></html>
HTML,
		'Make a synthwave-style background in pure CSS: a purple gradient sky, a pink-to-yellow sun with horizontal stripes cut out using a mask, and a neon pink grid floor drawn with two linear-gradients, tilted with rotateX inside a perspective container and animated with background-position so it scrolls towards the viewer. Add a glowing headline over the horizon.'
	),
	$m(
		'typewriter-hero',
		'Typewriter Words',
		'Text',
		false,
		'A headline that types, deletes and cycles through words with a blinking caret.',
		'typing text rotate words',
		$head . <<<'HTML'
<title>Typewriter</title><style>
*{margin:0;box-sizing:border-box}body{min-height:100vh;display:grid;place-items:center;background:#0d1117;color:#e6edf3;font-family:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;padding:24px}
h1{font-size:clamp(26px,6vw,58px);font-weight:800;text-align:center;line-height:1.2}
.w{color:#3fb950;border-right:.08em solid #3fb950;padding-right:4px;animation:caret .8s step-end infinite}
@keyframes caret{50%{border-color:transparent}}
p{color:#8b949e;text-align:center;margin-top:14px;font-family:system-ui,sans-serif}
</style></head><body><div><h1>Learn to build<br><span class="w" id="w"></span></h1><p>Pick a path. Ship a project. Get hired.</p></div>
<script>
const words=['websites.','AI apps.','REST APIs.','mobile apps.','data dashboards.'];const el=document.getElementById('w');
let i=0,j=0,del=false;const reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;
function tick(){const w=words[i];if(reduce){el.textContent=w;i=(i+1)%words.length;return setTimeout(tick,2000)}
el.textContent=w.slice(0,j);if(!del&&j<w.length){j++}else if(!del){del=true;return setTimeout(tick,1400)}else if(j>0){j--}else{del=false;i=(i+1)%words.length}
setTimeout(tick,del?45:90)}tick();
</script></body></html>
HTML,
		'Write a hero headline “Learn to build ___” where the last word types itself letter by letter, pauses, deletes and moves to the next word from an array. Use a CSS border-right as a blinking caret, a dark GitHub-like theme and a monospace font. For reduced motion, just swap whole words every 2 seconds.'
	),
	$m(
		'particle-network',
		'Particle Network',
		'Background',
		false,
		'Floating dots that link up with lines and lean towards your cursor or finger.',
		'canvas particles constellation ai',
		$head . <<<'HTML'
<title>Particle Network</title><style>
*{margin:0}html,body{height:100%;background:#020617;overflow:hidden;font-family:system-ui,sans-serif}
canvas{position:fixed;inset:0}
h1{position:relative;z-index:1;color:#e2e8f0;text-align:center;top:42%;font-size:clamp(26px,6vw,56px);transform:translateY(-50%);pointer-events:none}
h1 span{background:linear-gradient(90deg,#38bdf8,#a78bfa);-webkit-background-clip:text;background-clip:text;color:transparent}
</style></head><body><canvas id="c"></canvas><h1>Neural <span>Networks</span></h1>
<script>
const c=document.getElementById('c'),x=c.getContext('2d');let W,H,P=[],m={x:-999,y:-999};
const reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;
function size(){W=c.width=innerWidth*devicePixelRatio;H=c.height=innerHeight*devicePixelRatio;c.style.width=innerWidth+'px';c.style.height=innerHeight+'px';
const n=Math.min(120,Math.floor(innerWidth*innerHeight/11000));P=Array.from({length:n},()=>({x:Math.random()*W,y:Math.random()*H,vx:(Math.random()-.5)*.6,vy:(Math.random()-.5)*.6}))}
addEventListener('resize',size);size();
addEventListener('pointermove',e=>{m.x=e.clientX*devicePixelRatio;m.y=e.clientY*devicePixelRatio});addEventListener('pointerleave',()=>{m.x=m.y=-999});
const D=140*devicePixelRatio;
function frame(){x.clearRect(0,0,W,H);for(const p of P){const dx=m.x-p.x,dy=m.y-p.y,d=Math.hypot(dx,dy);if(d<200*devicePixelRatio){p.vx+=dx/d*.03;p.vy+=dy/d*.03}
p.vx*=.98;p.vy*=.98;p.x+=p.vx+(Math.random()-.5)*.1;p.y+=p.vy+(Math.random()-.5)*.1;if(p.x<0||p.x>W)p.vx*=-1;if(p.y<0||p.y>H)p.vy*=-1}
for(let i=0;i<P.length;i++){for(let j=i+1;j<P.length;j++){const a=P[i],b=P[j],d=Math.hypot(a.x-b.x,a.y-b.y);if(d<D){x.strokeStyle='rgba(125,211,252,'+(1-d/D)*.5+')';x.lineWidth=devicePixelRatio;x.beginPath();x.moveTo(a.x,a.y);x.lineTo(b.x,b.y);x.stroke()}}}
x.fillStyle='#a5b4fc';for(const p of P){x.beginPath();x.arc(p.x,p.y,2*devicePixelRatio,0,7);x.fill()}if(!reduce)requestAnimationFrame(frame)}
frame();
</script></body></html>
HTML,
		'Build a full-screen canvas background: about 100 particles (scaled by screen area) drift slowly and bounce off the edges; draw lines between particles closer than 140px with opacity based on distance; particles are gently pulled towards the mouse or touch point. Support high-DPI screens with devicePixelRatio and render one static frame for reduced-motion users.'
	),
	$m(
		'logo-marquee',
		'Infinite Logo Marquee',
		'Section',
		false,
		'A seamless, endlessly scrolling strip of tech names that pauses on hover.',
		'marquee scroll logos brands',
		$head . <<<'HTML'
<title>Marquee</title><style>
*{margin:0;box-sizing:border-box}body{min-height:100vh;display:grid;place-content:center;gap:22px;background:#fafafa;font-family:system-ui,sans-serif;padding:24px 0;overflow:hidden}
p{text-align:center;color:#6b7280;font-size:14px;letter-spacing:.12em;text-transform:uppercase}
.m{display:flex;overflow:hidden;-webkit-mask:linear-gradient(90deg,transparent,#000 12%,#000 88%,transparent);mask:linear-gradient(90deg,transparent,#000 12%,#000 88%,transparent)}
.t{display:flex;gap:14px;padding-right:14px;flex-shrink:0;animation:s 22s linear infinite}
.m.r .t{animation-direction:reverse}.m:hover .t{animation-play-state:paused}
@keyframes s{to{transform:translateX(-100%)}}
span{white-space:nowrap;padding:12px 20px;border-radius:14px;background:#fff;border:1px solid #e5e7eb;font-weight:700;color:#111827;box-shadow:0 2px 8px #0000000a}
@media (prefers-reduced-motion:reduce){.t{animation:none}.m{overflow-x:auto}}
</style></head><body><p>Skills you’ll learn</p>
<div class="m"><div class="t"><span>🐍 Python</span><span>☕ Java</span><span>⚛️ React</span><span>🍃 Spring Boot</span><span>🗄️ SQL</span><span>🤖 LLMs</span><span>🧠 RAG</span><span>🐳 Docker</span></div><div class="t" aria-hidden="true"><span>🐍 Python</span><span>☕ Java</span><span>⚛️ React</span><span>🍃 Spring Boot</span><span>🗄️ SQL</span><span>🤖 LLMs</span><span>🧠 RAG</span><span>🐳 Docker</span></div></div>
<div class="m r"><div class="t"><span>📊 pandas</span><span>📈 Power BI</span><span>☁️ AWS</span><span>🔐 Security</span><span>🧪 Testing</span><span>🎨 Figma</span><span>⚡ Next.js</span><span>🦾 Agents</span></div><div class="t" aria-hidden="true"><span>📊 pandas</span><span>📈 Power BI</span><span>☁️ AWS</span><span>🔐 Security</span><span>🧪 Testing</span><span>🎨 Figma</span><span>⚡ Next.js</span><span>🦾 Agents</span></div></div>
</body></html>
HTML,
		'Create two rows of infinitely scrolling pill badges (one row reversed) using pure CSS: duplicate the track, animate translateX(-100%), fade the edges with a mask-image gradient, pause on hover, and fall back to a horizontally scrollable row for reduced motion.'
	),
	$m(
		'tilt-cards',
		'3D Tilt Cards',
		'Cards',
		false,
		'Glassy feature cards that tilt in 3D and follow a glare as you move the pointer.',
		'3d tilt glass cards hover',
		$head . <<<'HTML'
<title>Tilt Cards</title><style>
*{margin:0;box-sizing:border-box}body{min-height:100vh;display:grid;place-items:center;background:radial-gradient(circle at 20% 20%,#312e81,#0f172a 60%);font-family:system-ui,sans-serif;padding:30px}
.g{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:22px;width:min(980px,100%);perspective:900px}
.c{position:relative;padding:28px;border-radius:20px;background:#ffffff12;border:1px solid #ffffff26;backdrop-filter:blur(10px);color:#fff;transform-style:preserve-3d;transition:transform .15s ease-out;overflow:hidden}
.c::after{content:"";position:absolute;inset:0;background:radial-gradient(circle at var(--x,50%) var(--y,50%),#ffffff30,transparent 45%);opacity:0;transition:opacity .2s}
.c:hover::after{opacity:1}
.i{font-size:34px;transform:translateZ(40px);display:block}h3{margin:14px 0 8px;transform:translateZ(30px)}p{color:#cbd5e1;font-size:14px;line-height:1.5;transform:translateZ(20px)}
</style></head><body><div class="g">
<div class="c"><span class="i">⚡</span><h3>Fast lessons</h3><p>Five-minute lessons with live code you can edit.</p></div>
<div class="c"><span class="i">🧠</span><h3>AI tutor</h3><p>Stuck? Ask and get a hint, not just the answer.</p></div>
<div class="c"><span class="i">🏆</span><h3>Certificates</h3><p>Finish a path and share a verified certificate.</p></div></div>
<script>
const reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;
document.querySelectorAll('.c').forEach(c=>{c.addEventListener('pointermove',e=>{const r=c.getBoundingClientRect(),px=(e.clientX-r.left)/r.width,py=(e.clientY-r.top)/r.height;
c.style.setProperty('--x',px*100+'%');c.style.setProperty('--y',py*100+'%');if(!reduce)c.style.transform='rotateY('+(px-.5)*18+'deg) rotateX('+(.5-py)*18+'deg)'});
c.addEventListener('pointerleave',()=>{c.style.transform=''})});
</script></body></html>
HTML,
		'Make a responsive grid of glassmorphism cards (backdrop-filter blur, translucent white) that tilt in 3D following the pointer (rotateX/rotateY up to 9 degrees inside a perspective parent), with a radial glare that follows the cursor via CSS variables, inner elements lifted with translateZ, and no tilt for reduced motion.'
	),
	$m(
		'gradient-buttons',
		'Glow Border Buttons',
		'Buttons',
		false,
		'Buttons with a spinning gradient border, a shine sweep and a magnetic pull.',
		'button gradient border magnetic',
		$head . <<<'HTML'
<title>Glow Buttons</title><style>
*{margin:0;box-sizing:border-box}body{min-height:100vh;display:flex;gap:28px;flex-wrap:wrap;align-items:center;justify-content:center;background:#09090b;font-family:system-ui,sans-serif;padding:30px}
@property --a{syntax:"<angle>";inherits:false;initial-value:0deg}
.b{position:relative;padding:15px 30px;border-radius:14px;border:0;color:#fff;font-size:16px;font-weight:700;cursor:pointer;background:#18181b;isolation:isolate;transition:transform .2s}
.b::before{content:"";position:absolute;inset:-2px;border-radius:16px;background:conic-gradient(from var(--a),#22d3ee,#a855f7,#f43f5e,#22d3ee);z-index:-2;animation:spin 3s linear infinite}
.b::after{content:"";position:absolute;inset:0;border-radius:14px;background:#18181b;z-index:-1}
@keyframes spin{to{--a:360deg}}
.s{overflow:hidden;background:linear-gradient(135deg,#6366f1,#06b6d4)}.s::before,.s::after{display:none}
.s span{position:absolute;inset:0;background:linear-gradient(110deg,transparent 30%,#ffffff70 50%,transparent 70%);transform:translateX(-120%);animation:shine 2.6s ease-in-out infinite}
@keyframes shine{60%,100%{transform:translateX(120%)}}
@media (prefers-reduced-motion:reduce){.b::before,.s span{animation:none}}
</style></head><body><button class="b m">Get started →</button><button class="b s m"><span></span>Join free</button>
<script>
if(!matchMedia('(prefers-reduced-motion: reduce)').matches)document.querySelectorAll('.m').forEach(b=>{b.addEventListener('pointermove',e=>{const r=b.getBoundingClientRect();b.style.transform='translate('+(e.clientX-r.left-r.width/2)*.25+'px,'+(e.clientY-r.top-r.height/2)*.35+'px)'});b.addEventListener('pointerleave',()=>b.style.transform='')});
</script></body></html>
HTML,
		'Design two call-to-action buttons: one with an animated conic-gradient border made with a registered CSS @property angle, one with a light shine sweeping across a gradient fill. Both move slightly towards the cursor (magnetic effect) on pointermove and reset on leave. Disable animations for reduced motion.'
	),

	/* ── Premium ── */
	$m(
		'text-scramble',
		'Decoder Text Scramble',
		'Text',
		true,
		'Headlines that decode from random glyphs into words, hacker-style, cycling through phrases.',
		'scramble decode glitch hacker text',
		$head . <<<'HTML'
<title>Text Scramble</title><style>
*{margin:0;box-sizing:border-box}body{min-height:100vh;display:grid;place-items:center;background:#000;color:#e5e7eb;font-family:ui-monospace,Menlo,Consolas,monospace;padding:24px}
h1{font-size:clamp(26px,6vw,60px);font-weight:700;text-align:center;min-height:2.6em}
.d{color:#22c55e;opacity:.8}
p{color:#6b7280;text-align:center;font-family:system-ui,sans-serif}
</style></head><body><div><h1 id="t"></h1><p>Cybersecurity · AI · Systems</p></div>
<script>
class Scramble{constructor(el){this.el=el;this.chars='!<>-_\\/[]{}—=+*^?#01';this.update=this.update.bind(this)}
set(text){const old=this.el.innerText,len=Math.max(old.length,text.length);this.queue=[];for(let i=0;i<len;i++){const s=Math.floor(Math.random()*30),e=s+Math.floor(Math.random()*30);this.queue.push({from:old[i]||'',to:text[i]||'',s,e})}
cancelAnimationFrame(this.raf);this.f=0;return new Promise(r=>{this.done=r;this.update()})}
update(){let out='',ok=0;for(const q of this.queue){if(this.f>=q.e){ok++;out+=q.to}else if(this.f>=q.s){if(!q.c||Math.random()<.28)q.c=this.chars[Math.floor(Math.random()*this.chars.length)];out+='<span class="d">'+q.c+'</span>'}else out+=q.from}
this.el.innerHTML=out;if(ok===this.queue.length)this.done();else{this.raf=requestAnimationFrame(this.update);this.f++}}}
const phrases=['Hello, future engineer','Decode the system','Think like a hacker','Build. Break. Learn.'];
const el=document.getElementById('t'),fx=new Scramble(el);let n=0;
const reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;
(function next(){if(reduce){el.textContent=phrases[n];n=(n+1)%phrases.length;return setTimeout(next,2500)}fx.set(phrases[n]).then(()=>setTimeout(next,1600));n=(n+1)%phrases.length})();
</script></body></html>
HTML,
		'Write a small JavaScript class that animates text changing from one phrase to another by showing random glyphs per character between a random start and end frame (requestAnimationFrame), colouring scrambling glyphs green. Cycle through four phrases with a pause, monospace font on black, and simple word swaps for reduced motion.'
	),
	$m(
		'spotlight-reveal',
		'Spotlight Reveal',
		'Background',
		true,
		'A dark grid section where a soft spotlight follows the cursor and reveals a glowing pattern.',
		'spotlight cursor mask grid reveal',
		$head . <<<'HTML'
<title>Spotlight</title><style>
*{margin:0;box-sizing:border-box}body{min-height:100vh;background:#030712;color:#fff;font-family:system-ui,sans-serif;overflow:hidden;cursor:crosshair}
.base,.lit{position:fixed;inset:0;background-size:44px 44px}
.base{background-image:linear-gradient(#ffffff0a 1px,transparent 1px),linear-gradient(90deg,#ffffff0a 1px,transparent 1px)}
.lit{background-image:linear-gradient(#38bdf8 1px,transparent 1px),linear-gradient(90deg,#a78bfa 1px,transparent 1px);-webkit-mask:radial-gradient(220px circle at var(--x,50%) var(--y,40%),#000 0%,transparent 70%);mask:radial-gradient(220px circle at var(--x,50%) var(--y,40%),#000 0%,transparent 70%);opacity:.9}
.glow{position:fixed;width:420px;height:420px;border-radius:50%;left:var(--x,50%);top:var(--y,40%);transform:translate(-50%,-50%);background:radial-gradient(#6366f155,transparent 65%);pointer-events:none}
main{position:relative;display:grid;place-items:center;min-height:100vh;text-align:center;padding:24px}
h1{font-size:clamp(30px,7vw,70px);letter-spacing:-.03em}p{color:#94a3b8;margin-top:12px}
</style></head><body><div class="base"></div><div class="lit"></div><div class="glow"></div><main><div><h1>Find what’s hidden</h1><p>Move your cursor or finger around.</p></div></main>
<script>
const r=document.documentElement;let tx=innerWidth/2,ty=innerHeight*.4,x=tx,y=ty;
addEventListener('pointermove',e=>{tx=e.clientX;ty=e.clientY});
(function loop(){x+=(tx-x)*.12;y+=(ty-y)*.12;r.style.setProperty('--x',x+'px');r.style.setProperty('--y',y+'px');requestAnimationFrame(loop)})();
</script></body></html>
HTML,
		'Create a dark section with a faint grid background and a second, brightly coloured grid layer that is only visible inside a radial-gradient mask positioned at CSS variables --x/--y. Update the variables smoothly towards the pointer position with easing in requestAnimationFrame, and add a soft glow circle that follows the same point.'
	),
	$m(
		'warp-starfield',
		'Warp Speed Starfield',
		'Background',
		true,
		'Stars stream past in 3D; hold the mouse or touch to jump to warp speed.',
		'stars space 3d canvas warp',
		$head . <<<'HTML'
<title>Warp</title><style>
*{margin:0}html,body{height:100%;background:#000;overflow:hidden;font-family:system-ui,sans-serif;user-select:none}
canvas{position:fixed;inset:0}
h1{position:fixed;inset:0;display:grid;place-items:center;color:#fff;font-size:clamp(26px,7vw,64px);letter-spacing:.06em;pointer-events:none;text-shadow:0 0 30px #60a5fa}
small{position:fixed;bottom:18px;width:100%;text-align:center;color:#94a3b8;font-size:13px}
</style></head><body><canvas id="c"></canvas><h1>LAUNCH YOUR CAREER</h1><small>Hold to warp</small>
<script>
const c=document.getElementById('c'),x=c.getContext('2d');let W,H,S=[],speed=2,target=2;
function size(){W=c.width=innerWidth;H=c.height=innerHeight}addEventListener('resize',size);size();
for(let i=0;i<900;i++)S.push({x:(Math.random()-.5)*2000,y:(Math.random()-.5)*2000,z:Math.random()*1000,pz:0});
addEventListener('pointerdown',()=>target=28);addEventListener('pointerup',()=>target=2);addEventListener('pointerleave',()=>target=2);
const reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;if(reduce)target=speed=.4;
(function f(){speed+=(target-speed)*.06;x.fillStyle='rgba(0,0,0,'+(speed>6?.25:.6)+')';x.fillRect(0,0,W,H);
for(const s of S){s.pz=s.z;s.z-=speed;if(s.z<1){s.z=1000;s.pz=1000;s.x=(Math.random()-.5)*2000;s.y=(Math.random()-.5)*2000}
const k=300/s.z,sx=s.x*k+W/2,sy=s.y*k+H/2,pk=300/s.pz,px=s.x*pk+W/2,py=s.y*pk+H/2;
x.strokeStyle='hsl('+(200+s.z/10)+',90%,'+(90-s.z/20)+'%)';x.lineWidth=Math.max(.5,2.2-s.z/500);x.beginPath();x.moveTo(px,py);x.lineTo(sx,sy);x.stroke();const d=Math.max(.8,3-s.z/350);x.fillStyle=x.strokeStyle;x.fillRect(sx-d/2,sy-d/2,d,d)}
requestAnimationFrame(f)})();
</script></body></html>
HTML,
		'Make a canvas starfield: 900 stars with random x/y and depth z, projected with perspective (k = 300 / z); each frame decrease z by the speed and draw a line from the previous to the current projected position so fast stars streak. Holding the pointer raises the target speed (eased), releasing lowers it. Very slow motion for reduced-motion users.'
	),
	$m(
		'morph-blob',
		'Morphing Gradient Blob',
		'Hero',
		true,
		'An organic, shape-shifting gradient blob beside your headline, made with pure CSS.',
		'blob morph organic gradient hero',
		$head . <<<'HTML'
<title>Morph Blob</title><style>
*{margin:0;box-sizing:border-box}body{min-height:100vh;display:grid;place-items:center;background:#fff7ed;font-family:system-ui,sans-serif;padding:30px;color:#1c1917}
.w{display:grid;grid-template-columns:1.1fr 1fr;gap:40px;align-items:center;max-width:1000px}
h1{font-size:clamp(32px,5.5vw,62px);line-height:1.02;letter-spacing:-.03em}p{color:#57534e;margin:16px 0 24px;font-size:18px}
a{display:inline-block;background:#1c1917;color:#fff;padding:14px 24px;border-radius:999px;text-decoration:none;font-weight:700}
.blob{aspect-ratio:1;width:100%;max-width:420px;justify-self:center;background:linear-gradient(135deg,#f97316,#ec4899 50%,#8b5cf6);border-radius:42% 58% 63% 37%/41% 44% 56% 59%;animation:morph 9s ease-in-out infinite,spin 24s linear infinite;box-shadow:0 30px 80px #ec489955}
@keyframes morph{25%{border-radius:58% 42% 34% 66%/63% 68% 32% 37%}50%{border-radius:50% 50% 33% 67%/55% 27% 73% 45%}75%{border-radius:33% 67% 58% 42%/63% 68% 32% 37%}}
@keyframes spin{to{rotate:360deg}}
@media (max-width:720px){.w{grid-template-columns:1fr;text-align:center}.blob{max-width:260px;grid-row:1}}
@media (prefers-reduced-motion:reduce){.blob{animation:none}}
</style></head><body><div class="w"><div><h1>Creative skills for a creative future</h1><p>Design, code and AI, taught by doing.</p><a href="#">Explore the studio</a></div><div class="blob"></div></div></body></html>
HTML,
		'Create a two-column hero: headline and button on the left, and on the right a square element with a pink-orange-violet gradient whose eight-value border-radius animates between four organic shapes while slowly rotating. Stack the layout on phones with the blob on top, and stop the animation for reduced motion.'
	),
	$m(
		'scroll-reveal',
		'Scroll Reveal Story',
		'Section',
		true,
		'Sections and cards that slide, fade and stagger into view as you scroll.',
		'scroll reveal intersection observer stagger',
		$head . <<<'HTML'
<title>Scroll Reveal</title><style>
*{margin:0;box-sizing:border-box}body{background:#0b1020;color:#e2e8f0;font-family:system-ui,sans-serif}
section{min-height:90vh;display:grid;place-items:center;padding:60px 24px}
h2{font-size:clamp(28px,5vw,52px);text-align:center;max-width:800px;letter-spacing:-.02em}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:18px;max-width:900px;width:100%}
.card{padding:26px;border-radius:18px;background:linear-gradient(160deg,#1e293b,#0f172a);border:1px solid #334155}
.card b{font-size:28px;display:block;margin-bottom:8px}
.r{opacity:0;transform:translateY(40px) scale(.98);transition:opacity .8s ease,transform .8s cubic-bezier(.2,.7,.2,1);transition-delay:calc(var(--i,0)*120ms)}
.r.in{opacity:1;transform:none}
.hint{color:#64748b;text-align:center;margin-top:12px}
@media (prefers-reduced-motion:reduce){.r{opacity:1;transform:none;transition:none}}
</style></head><body>
<section><div><h2 class="r">Scroll down to see the story unfold</h2><p class="hint r" style="--i:1">↓</p></div></section>
<section><div class="grid"><div class="card r" style="--i:0"><b>01</b>Learn the basics in short lessons.</div><div class="card r" style="--i:1"><b>02</b>Practise with live code and quizzes.</div><div class="card r" style="--i:2"><b>03</b>Build projects you can show.</div><div class="card r" style="--i:3"><b>04</b>Earn a certificate and get hired.</div></div></section>
<section><h2 class="r">Ready? Your first lesson takes 5 minutes.</h2></section>
<script>
const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target)}}),{threshold:.2});
document.querySelectorAll('.r').forEach(el=>io.observe(el));
</script></body></html>
HTML,
		'Build a scrolling story page where elements with class .r start transparent and shifted down, then animate in when 20% visible using IntersectionObserver (add an .in class once). Stagger cards with a --i CSS variable multiplied into transition-delay. Show everything immediately for reduced motion.'
	),
	$m(
		'orbit-icons',
		'Orbiting Skills',
		'Hero',
		true,
		'Tech icons orbiting a glowing core on tilted 3D rings, perfect for an about or skills section.',
		'orbit 3d rings icons planets',
		$head . <<<'HTML'
<title>Orbit</title><style>
*{margin:0;box-sizing:border-box}body{min-height:100vh;display:grid;place-items:center;background:radial-gradient(#111827,#030712);font-family:system-ui,sans-serif;overflow:hidden}
.s{position:relative;width:min(90vw,520px);aspect-ratio:1;perspective:1000px}
.core{position:absolute;inset:38%;border-radius:50%;background:radial-gradient(circle at 35% 30%,#a5f3fc,#0891b2 45%,#164e63);box-shadow:0 0 60px #22d3ee88,0 0 140px #22d3ee44;display:grid;place-items:center;color:#fff;font-weight:800;font-size:clamp(14px,3vw,20px)}
.ring{position:absolute;inset:var(--in);border:1px solid #ffffff22;border-radius:50%;transform:rotateX(68deg) rotateZ(var(--tilt));transform-style:preserve-3d;animation:o var(--t) linear infinite}
@keyframes o{to{transform:rotateX(68deg) rotateZ(calc(var(--tilt) + 360deg))}}
.ring span{position:absolute;top:50%;left:50%;width:44px;height:44px;margin:-22px;border-radius:12px;background:#fff;display:grid;place-items:center;font-size:22px;box-shadow:0 6px 20px #0006;transform:rotate(var(--a)) translate(calc(var(--r) * 1px)) rotate(calc(var(--a) * -1)) rotateX(-68deg)}
@media (prefers-reduced-motion:reduce){.ring{animation:none}}
</style></head><body><div class="s">
<div class="ring" style="--in:22%;--t:14s;--tilt:0deg"><span style="--a:0deg;--r:145">🐍</span><span style="--a:180deg;--r:145">☕</span></div>
<div class="ring" style="--in:10%;--t:22s;--tilt:40deg"><span style="--a:60deg;--r:210">⚛️</span><span style="--a:200deg;--r:210">🗄️</span><span style="--a:310deg;--r:210">🤖</span></div>
<div class="ring" style="--in:0%;--t:32s;--tilt:-30deg"><span style="--a:20deg;--r:260">☁️</span><span style="--a:140deg;--r:260">🐳</span><span style="--a:250deg;--r:260">🔐</span></div>
<div class="core">AI</div></div>
<script>
// Scale the orbit radii to the stage size.
const s=document.querySelector('.s');function fit(){const k=s.offsetWidth/520;s.querySelectorAll('.ring span').forEach(e=>{e.style.setProperty('--r',(e.dataset.r||(e.dataset.r=getComputedStyle(e).getPropertyValue('--r')))*k)})}addEventListener('resize',fit);fit();
</script></body></html>
HTML,
		'Make a 3D orbit scene: a glowing sphere in the centre (radial gradients and box-shadow), three rings tilted with rotateX and different rotateZ angles that spin at different speeds, and emoji or icon tiles placed on each ring with rotate/translate/rotate so they stay upright. Scale radii to the container on resize.'
	),
	$m(
		'bento-glow',
		'Bento Grid Glow',
		'Cards',
		true,
		'A modern bento layout where a glowing border follows the pointer across all cards.',
		'bento grid border glow features',
		$head . <<<'HTML'
<title>Bento Glow</title><style>
*{margin:0;box-sizing:border-box}body{min-height:100vh;display:grid;place-items:center;background:#09090b;color:#fafafa;font-family:system-ui,sans-serif;padding:30px}
.b{display:grid;grid-template-columns:repeat(4,1fr);grid-auto-rows:150px;gap:14px;width:min(1000px,100%)}
.c{position:relative;border-radius:20px;background:#18181b;padding:22px;overflow:hidden}
.c::before{content:"";position:absolute;inset:0;border-radius:inherit;padding:1px;background:radial-gradient(300px circle at var(--x) var(--y),#a78bfa,#22d3ee 40%,transparent 60%);-webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);-webkit-mask-composite:xor;mask-composite:exclude}
.c::after{content:"";position:absolute;inset:0;background:radial-gradient(400px circle at var(--x) var(--y),#ffffff0d,transparent 50%);pointer-events:none}
.w2{grid-column:span 2}.h2{grid-row:span 2}
h3{font-size:18px;margin-bottom:6px}p{color:#a1a1aa;font-size:14px}.k{font-size:40px;font-weight:800;background:linear-gradient(90deg,#a78bfa,#22d3ee);-webkit-background-clip:text;background-clip:text;color:transparent}
@media (max-width:760px){.b{grid-template-columns:1fr 1fr}.w2{grid-column:span 2}}
</style></head><body><div class="b" id="b">
<div class="c w2 h2"><h3>AI Tutor</h3><p>Answers in seconds, explains like a friend, never judges a question.</p></div>
<div class="c"><div class="k">450+</div><p>free lessons</p></div>
<div class="c"><div class="k">51</div><p>interview problems</p></div>
<div class="c w2"><h3>Live code</h3><p>Run Python, SQL and JavaScript right in the browser.</p></div>
<div class="c w2"><h3>Projects</h3><p>Build a portfolio recruiters remember.</p></div>
<div class="c w2"><h3>Certificates</h3><p>Verified and shareable on LinkedIn.</p></div></div>
<script>
const b=document.getElementById('b');b.addEventListener('pointermove',e=>{b.querySelectorAll('.c').forEach(c=>{const r=c.getBoundingClientRect();c.style.setProperty('--x',e.clientX-r.left+'px');c.style.setProperty('--y',e.clientY-r.top+'px')})});
b.querySelectorAll('.c').forEach(c=>{c.style.setProperty('--x','-500px');c.style.setProperty('--y','-500px')});
</script></body></html>
HTML,
		'Create a bento grid of dark cards (CSS grid with spanning tiles). On pointermove over the grid, set --x/--y on every card relative to its own box, and draw a 1px gradient border only near the pointer using a radial-gradient on ::before with mask-composite to cut out the middle, plus a faint inner glow. Collapse to two columns on phones.'
	),
	$m(
		'ascii-wave',
		'ASCII Wave',
		'Background',
		true,
		'A living field of characters rippling like water, for a retro-tech look.',
		'ascii text art wave canvas glyph',
		$head . <<<'HTML'
<title>ASCII Wave</title><style>
*{margin:0}html,body{height:100%;background:#0a0a0a;overflow:hidden}
pre{position:fixed;inset:0;color:#4ade80;font:12px/12px ui-monospace,Menlo,Consolas,monospace;white-space:pre;letter-spacing:1px;text-shadow:0 0 6px #22c55e88}
h1{position:fixed;inset:0;display:grid;place-items:center;font-family:system-ui,sans-serif;color:#fff;font-size:clamp(24px,6vw,56px);pointer-events:none;mix-blend-mode:difference}
</style></head><body><pre id="o"></pre><h1>signal &gt; noise</h1>
<script>
const o=document.getElementById('o'),ramp=' .:-=+*#%@';let cols,rows,t=0;
function size(){cols=Math.ceil(innerWidth/8.2);rows=Math.ceil(innerHeight/12)}addEventListener('resize',size);size();
const reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;
(function f(){let s='';for(let y=0;y<rows;y++){for(let x=0;x<cols;x++){const v=Math.sin(x*.12+t)+Math.sin(y*.18-t*.8)+Math.sin((x+y)*.07+t*.5)+Math.sin(Math.hypot(x-cols/2,y-rows/2)*.15-t*1.4);s+=ramp[Math.max(0,Math.min(9,Math.floor((v+4)/8*10)))]}s+='\n'}
o.textContent=s;t+=.05;if(!reduce)requestAnimationFrame(f)})();
</script></body></html>
HTML,
		'Render an animated ASCII field in a <pre>: for each character cell compute the sum of several sine waves (including a circular ripple from the centre) over time, map the value to a character ramp " .:-=+*#%@", and redraw every frame in green monospace with a soft glow. Size rows and columns to the window and draw one frame for reduced motion.'
	),
	$m(
		'glass-pricing',
		'Glass Pricing Cards',
		'Pricing',
		true,
		'Three frosted-glass pricing cards over moving colour, with a highlighted popular plan.',
		'pricing plans glassmorphism cards',
		$head . <<<'HTML'
<title>Glass Pricing</title><style>
*{margin:0;box-sizing:border-box}body{min-height:100vh;display:grid;place-items:center;font-family:system-ui,sans-serif;padding:40px 20px;color:#fff;background:#0f0c29;overflow-x:hidden}
.bg{position:fixed;inset:0;background:conic-gradient(from 0deg at 50% 50%,#ff6ec4,#7873f5,#4ade80,#ff6ec4);filter:blur(90px);opacity:.55;animation:r 20s linear infinite;z-index:-1}
@keyframes r{to{transform:rotate(360deg) scale(1.2)}}
.g{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px;width:min(980px,100%);align-items:center}
.p{padding:30px 26px;border-radius:24px;background:#ffffff14;border:1px solid #ffffff30;backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px)}
.p.hot{background:#ffffff26;transform:scale(1.04);border-color:#ffffff70;box-shadow:0 20px 60px #0005}
.tag{display:inline-block;font-size:12px;font-weight:800;padding:4px 10px;border-radius:99px;background:#fff;color:#0f0c29;margin-bottom:10px}
h3{font-size:18px}.price{font-size:42px;font-weight:800;margin:12px 0}.price small{font-size:15px;opacity:.7}
ul{list-style:none;margin:16px 0 22px;display:grid;gap:9px;font-size:14px}li::before{content:"✓ ";color:#4ade80;font-weight:800}
a{display:block;text-align:center;padding:12px;border-radius:12px;background:#fff;color:#0f0c29;text-decoration:none;font-weight:800}.p:not(.hot) a{background:transparent;border:1px solid #ffffffa0;color:#fff}
@media (prefers-reduced-motion:reduce){.bg{animation:none}}
</style></head><body><div class="bg"></div><div class="g">
<div class="p"><h3>Starter</h3><div class="price">₹0 <small>/forever</small></div><ul><li>All free lessons</li><li>Code playground</li><li>Community</li></ul><a href="#">Start free</a></div>
<div class="p hot"><span class="tag">MOST POPULAR</span><h3>Pro</h3><div class="price">₹1,499 <small>/year</small></div><ul><li>Every career track</li><li>Projects + certificates</li><li>AI Hub premium</li></ul><a href="#">Go Pro</a></div>
<div class="p"><h3>Lifetime</h3><div class="price">₹3,999 <small>/once</small></div><ul><li>Everything in Pro</li><li>All future tracks</li><li>Founder badge</li></ul><a href="#">Get lifetime</a></div></div></body></html>
HTML,
		'Design a pricing section with three glassmorphism cards (translucent background, backdrop-filter blur, light border) over a slowly rotating, heavily blurred conic-gradient background. Highlight the middle plan with a badge, a slight scale-up and a solid button; others get outlined buttons. Use a responsive auto-fit grid.'
	),
) );
