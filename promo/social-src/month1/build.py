"""Build month-1 education carousels: python3 -I build.py OUT_DIR
Writes OUT_DIR/month1.html (every slide as <section class="s" data-name=...>) and
OUT_DIR/CAPTIONS-MONTH1.md. Render with: node ../render2.js OUT_DIR/month1.html PNG_DIR"""
import html, os, re, sys

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
from posts import POSTS  # noqa: E402

OUT = sys.argv[1]
CREW = 'file://' + os.path.join(os.path.dirname(HERE), 'crew') + '/'
LOGO = 'file:///home/user/vitalstack/brand-kit/logo.png'
SERIES = {
    'VS': ('⚔️', 'This vs That'), 'PREDICT': ('🧩', 'Predict the Output'), 'ELI5': ('🧸', 'Explain Like I’m 5'),
    'INTERVIEW': ('🎤', 'Interview Cheat Code'), 'BUG': ('🐞', 'Bug Hunt'), 'SHEET': ('📌', 'Save This'),
    'BUILD': ('🛠️', 'Build It'), 'RECAP': ('🏁', 'Recap'),
}

CSS = r"""
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:Inter,"Noto Color Emoji",sans-serif;background:#000}
.s{width:1080px;height:1350px;position:relative;overflow:hidden;background:#f6f8f7;color:#1b1f27;margin-bottom:20px}
.s:before{content:"";position:absolute;right:-180px;top:-180px;width:520px;height:520px;border-radius:50%;background:#e3f3eb}
.content{position:absolute;left:80px;right:80px;top:80px}
.tag{display:inline-block;background:#0a7d4f;color:#fff;font-weight:800;font-size:25px;letter-spacing:.07em;padding:13px 22px;border-radius:12px}
.num{position:absolute;top:92px;right:80px;color:#7b8794;font-weight:700;font-size:28px;z-index:2}
.series{position:absolute;top:150px;right:80px;color:#0a7d4f;font-weight:800;font-size:22px;letter-spacing:.06em}
h1{font-family:"Inter Display",Inter;font-weight:900;font-size:92px;line-height:1.02;letter-spacing:-.035em;margin-top:40px}
h2{font-family:"Inter Display",Inter;font-weight:800;font-size:60px;line-height:1.08;letter-spacing:-.02em;margin-top:34px}
.g{color:#0a7d4f}
.sub{font-size:35px;color:#4a5562;line-height:1.38;margin-top:24px}
p.t{font-size:33px;color:#3a4552;line-height:1.45;margin-top:26px}
p.t b{color:#1b1f27}
.code{background:#0f1720;border-radius:24px;overflow:hidden;margin-top:30px}
.code .bar{background:#1b2532;color:#9fb0c3;font-size:24px;font-weight:600;padding:14px 24px}
.code .bar i{display:inline-block;width:13px;height:13px;border-radius:50%;margin-right:8px;vertical-align:middle}
pre{font-family:"DejaVu Sans Mono",monospace;line-height:1.5;color:#e8eef5;padding:22px 28px;white-space:pre}
pre .c{color:#7d8ea3}
.out{background:#fff;border:3px solid #dfe7e3;border-radius:22px;margin-top:18px;padding:20px 26px}
.out .lbl{font-size:22px;font-weight:800;letter-spacing:.08em;color:#7b8794;margin-bottom:8px}
.out pre{padding:0;color:#0a7d4f;font-size:28px}
.out.err pre{color:#c62828}
table{width:100%;border-collapse:separate;border-spacing:0;margin-top:34px;background:#fff;border:3px solid #dfe7e3;border-radius:26px;overflow:hidden;font-size:30px}
th{background:#0a7d4f;color:#fff;text-align:left;padding:22px 22px;font-size:30px}
td{padding:20px 22px;border-top:2px solid #edf1ef;line-height:1.3;vertical-align:top}
td:first-child{font-weight:700;color:#3a4552}
tr:nth-child(even) td{background:#f9fbfa}
td code,th code{font-family:"DejaVu Sans Mono",monospace;font-size:.88em}
.mono td:first-child{font-family:"DejaVu Sans Mono",monospace;font-size:27px;font-weight:400;color:#0f1720}
.li{display:flex;gap:24px;align-items:flex-start;background:#fff;border:3px solid #dfe7e3;border-radius:24px;padding:24px 28px;margin-top:16px}
.li .e{font-size:48px;min-width:64px;text-align:center;line-height:1.1}
.li .e.n{font-family:"Inter Display",Inter;font-weight:900;color:#0a7d4f;font-size:44px}
.li b{display:block;font-size:34px}
.li span{display:block;font-size:28px;color:#4a5562;margin-top:4px;line-height:1.35}
.opts{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:26px}
.opts div{background:#fff;border:3px solid #dfe7e3;border-radius:20px;padding:22px 26px;font-size:32px;font-weight:700;font-family:"DejaVu Sans Mono",monospace}
.script{background:#fff;border-left:10px solid #0a7d4f;border-radius:20px;padding:30px 34px;margin-top:28px;font-size:33px;line-height:1.5;color:#1b1f27;font-style:italic}
.steps{margin-top:30px}
.st{display:flex;gap:24px;align-items:center;margin-bottom:14px}
.st .d{width:66px;height:66px;border-radius:50%;background:#0a7d4f;color:#fff;font-weight:900;font-size:30px;display:flex;align-items:center;justify-content:center;flex:none}
.st div{font-size:34px;font-weight:700;line-height:1.25}
.st small{display:block;font-size:27px;font-weight:400;color:#4a5562}
.rule{background:#fff8e6;border:4px dashed #e0a800;border-radius:28px;padding:32px 36px;margin-top:36px}
.rule small{display:block;font-size:24px;font-weight:900;letter-spacing:.1em;color:#a77a00;margin-bottom:10px}
.rule b{font-size:42px;line-height:1.25;display:block}
.cta{background:#0a7d4f;color:#fff;border-radius:28px;padding:34px 40px;margin-top:28px}
.cta b{font-size:44px;display:block}
.cta p{font-size:30px;margin-top:10px;color:#d9f3e6;line-height:1.4}
.strip{position:absolute;left:60px;right:60px;bottom:128px;height:300px;display:flex;align-items:flex-end;justify-content:space-between}
.strip .ch{width:170px;flex:none}
.strip .ch img{display:block;width:100%}
.bub{background:#fff;border:4px solid #1b1f27;border-radius:26px;padding:18px 22px;font-weight:800;font-size:29px;line-height:1.25;box-shadow:6px 6px 0 #1b1f27;position:relative;margin-bottom:150px;max-width:330px}
.bub.g2{background:#e3f3eb}
.bub:after{content:"";position:absolute;width:24px;height:24px;background:inherit;border-right:4px solid #1b1f27;border-bottom:4px solid #1b1f27;bottom:24px}
.bub.l:after{left:-16px;transform:rotate(135deg)}
.bub.r:after{right:-16px;transform:rotate(-45deg)}
.mid{flex:1;display:flex;justify-content:space-between;gap:20px;padding:0 14px}
.solo{justify-content:flex-start;gap:24px}
.ft{position:absolute;left:0;right:0;bottom:0;height:116px;background:#fff;border-top:2px solid #e3e9e6;display:flex;align-items:center;justify-content:space-between;padding:0 80px}
.ft img{height:50px}.ft span{color:#0a7d4f;font-weight:800;font-size:31px}
"""


def esc(t):
    return html.escape(t, quote=False)


def code_block(fname, text):
    lines = text.split('\n')
    longest = max(len(l) for l in lines)
    size = min(30, int(850 / (0.602 * max(longest, 1))))
    out = []
    for l in lines:
        e = esc(l)
        m = re.search(r'(\s(#|//|--)\s.*|/\*.*\*/|^\s*(#|//|--)\s.*)$', e)
        if m:
            e = e[:m.start()] + '<span class="c">' + e[m.start():] + '</span>'
        out.append(e)
    dots = '<i style="background:#ff5f57"></i><i style="background:#febc2e"></i><i style="background:#28c840"></i>'
    return f'<div class="code"><div class="bar">{dots}{esc(fname)}</div><pre style="font-size:{size}px">' + '\n'.join(out) + '</pre></div>'


def block(b):
    k = b[0]
    if k == 'h2': return f'<h2>{b[1]}</h2>'
    if k == 'p': return f'<p class="t">{b[1]}</p>'
    if k == 'code': return code_block(b[1], b[2])
    if k == 'out':
        return f'<div class="out{" err" if b[2] else ""}"><div class="lbl">OUTPUT</div><pre>{esc(b[1])}</pre></div>'
    if k == 'table':
        hdr, rows = b[1], b[2]
        mono = ' class="mono"' if hdr and hdr[0] in ('Command', 'Code', 'Operation') else ''
        h = ''.join(f'<th>{esc(x)}</th>' for x in hdr)
        r = ''.join('<tr>' + ''.join(f'<td>{esc(x)}</td>' for x in row) + '</tr>' for row in rows)
        return f'<table{mono}><tr>{h}</tr>{r}</table>'
    if k == 'list':
        items = []
        for e, bold, txt in b[1]:
            cls = 'e n' if e.isdigit() else 'e'
            items.append(f'<div class="li"><div class="{cls}">{e}</div><div><b>{esc(bold)}</b>' + (f'<span>{esc(txt)}</span>' if txt else '') + '</div></div>')
        return ''.join(items)
    if k == 'opts': return '<div class="opts">' + ''.join(f'<div>{esc(o)}</div>' for o in b[1]) + '</div>'
    if k == 'script': return f'<div class="script">{esc(b[1])}</div>'
    if k == 'steps':
        return '<div class="steps">' + ''.join(
            f'<div class="st"><div class="d">{i}</div><div>{esc(t)}' + (f'<small>{esc(s)}</small>' if s else '') + '</div></div>'
            for i, (t, s) in enumerate(b[1], 1)) + '</div>'
    raise ValueError(k)


def strip(crew):
    if len(crew) == 1:
        who, mood, line = crew[0]
        return (f'<div class="strip solo"><div class="ch"><img src="{CREW}{who}-{mood}.svg"></div>'
                f'<div class="bub l g2">{esc(line)}</div></div>')
    (w1, m1, l1), (w2, m2, l2) = crew
    return (f'<div class="strip"><div class="ch"><img src="{CREW}{w1}-{m1}.svg"></div>'
            f'<div class="mid"><div class="bub l">{esc(l1)}</div><div class="bub r g2" style="margin-bottom:60px">{esc(l2)}</div></div>'
            f'<div class="ch"><img src="{CREW}{w2}-{m2}.svg"></div></div>')


def section(name, n, total, inner, extra=''):
    return (f'<section class="s" data-name="{name}"><div class="num">{n}/{total}</div>'
            f'<div class="content">{inner}</div>{extra}'
            f'<div class="ft"><img src="{LOGO}"><span>vitalstack.co.in</span></div></section>')


parts, caps = [], []
for p in POSTS:
    base = f"day{p['day']:02d}-{p['slug']}"
    total = 2 + len(p['slides'])
    emoji, sname = SERIES[p['series']]
    cover = f'<div class="tag">{esc(p["tag"])}</div><h1>{p["title"]}</h1><p class="sub">{esc(p["sub"])}</p>'
    if p.get('code'):
        cover += code_block(*p['code'])
    if p.get('opts'):
        cover += block(('opts', p['opts']))
    parts.append(section(f'{base}-1', 1, total, cover, strip(p['crew'])))
    for i, sl in enumerate(p['slides'], 2):
        inner = f'<div class="tag">{emoji} {esc(sname.upper())}</div>' + ''.join(block(b) for b in sl)
        parts.append(section(f'{base}-{i}', i, total, inner))
    kw, cta_txt = p['cta']
    last = (f'<div class="tag">✅ REMEMBER THIS</div>'
            f'<div class="rule"><small>📌 RULE OF THUMB</small><b>{esc(p["rule"])}</b></div>'
            f'<div class="cta"><b>Comment “{esc(kw)}” 👇</b><p>{esc(cta_txt)}</p></div>')
    parts.append(section(f'{base}-{total}', total, total, last, strip([p['closer']])))

    utm = f"?utm_source=instagram&utm_medium=dm&utm_campaign=m1-d{p['day']:02d}"
    caps.append(f"""## Day {p['day']} · {p['date']} · {emoji} {sname} · `{base}-1…{total}.png`
```
{p['hook']}

{re.sub('<[^>]+>', '', p['sub'])}
Swipe to the end for the rule of thumb 👉

💬 Comment "{kw}" and we'll DM you the link
📌 Save this · Share it with someone learning to code

{p['tags']} #VitalStack
```
**DM link ({kw}):** {p['link']}{utm}
**Rule of thumb (pin as first comment):** 📌 {p['rule']}
""")

os.makedirs(OUT, exist_ok=True)
open(f'{OUT}/month1.html', 'w').write(f'<!DOCTYPE html><html><head><meta charset="utf-8"><style>{CSS}</style></head><body>{"".join(parts)}</body></html>')
open(f'{OUT}/CAPTIONS-MONTH1.md', 'w').write('# VitalStack · Month 1 education posts: captions\n\n'
    'Post at 7:00 am IST (US evening / Europe morning) or 7:30 pm IST (India + Gulf). '
    'Music: lo-fi/chill for explainers and cheat sheets, "suspense" for Predict and Bug Hunt, "8 bit" for Build It.\n\n' + '\n'.join(caps))
print(len(POSTS), 'posts,', sum(2 + len(p['slides']) for p in POSTS), 'slides')
