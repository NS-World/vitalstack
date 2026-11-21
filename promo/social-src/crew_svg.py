"""Export the VitalStack crew sprites (inc/crew.php) as standalone SVG files.
usage: python3 crew_svg.py path/to/crew.php OUT_DIR
Writes <who>-<mood>.svg for moods: happy, wow, cheer (cheer = closed eyes + sparkle)."""
import re, sys

src = open(sys.argv[1]).read()
out = sys.argv[2]

body = src.split('function vitalstack_crew()')[1].split('function vitalstack_character(')[0]
blocks = re.split(r"\n\t\t'([a-z]+)'\s*=>\s*array\(", body)[1:]


def str_list(block, key):
    m = re.search(r"'%s'\s*=>\s*array\((.*?)\)\s*,\s*\n" % key, block, re.S)
    if not m:
        return None
    inner = m.group(1)
    if '=>' in inner:  # indexed replacement rows: 7 => '....'
        return {int(k): v for k, v in re.findall(r"(\d+)\s*=>\s*'([^']*)'", inner)}
    return re.findall(r"'([^']*)'", inner)


crew = {}
for who, block in zip(blocks[0::2], blocks[1::2]):
    pal = dict(re.findall(r"'(\w)'\s*=>\s*'(#[0-9a-fA-F]{6})'", re.search(r"'pal'\s*=>\s*array\((.*?)\)", block).group(1)))
    crew[who] = {
        'pal': pal,
        'rows': str_list(block, 'rows'),
        'closed': str_list(block, 'closed') or {},
        'wide': str_list(block, 'wide') or {},
    }

SPARK = 'M2 0h1v1h-1zM1 1h3v1h-3zM2 2h1v1h-1zM0 4h1v1h-1zM-1 5h3v1h-3zM0 6h1v1h-1z'
ALERT = 'M1 0h2v4h-2zM1 5h2v2h-2z'

# 'shades' = heist look: dark sunglasses with a neon glint (palette swap only)
SHADES = {
    'dev': {'G': '#0d0820', 'g': '#ff2e63', 'E': '#0d0820'},
    'byte': {'E': '#0d0820', 'e': '#5ef2ff', 'H': '#ff2e63', 'h': '#ff8fab'},
    'chip': {'E': '#ff2e63', 'e': '#ff8fab', 'M': '#ff2e63'},
    'bugsy': {'E': '#0d0820', 'W': '#5ef2ff'},
}

for who, c in crew.items():
    for mood in ('happy', 'wow', 'cheer', 'shades'):
        pal = dict(c['pal'], **SHADES.get(who, {})) if mood == 'shades' else c['pal']
        rows = list(c['rows'])
        repl = c['closed'] if mood == 'cheer' else c['wide'] if mood == 'wow' else {}
        for i, r in repl.items():
            if i < len(rows):
                rows[i] = r
        w = max(map(len, rows)); h = len(rows)
        ox, oy = 1, 5
        paths = {}
        for y, row in enumerate(rows):
            x = 0
            while x < len(row):
                ch = row[x]
                if ch == '.' or ch not in pal:
                    x += 1; continue
                e = x
                while e < len(row) and row[e] == ch:
                    e += 1
                paths.setdefault(ch, []).append(f'M{x+ox} {y+oy}h{e-x}v1h-{e-x}z')
                x = e
        svg = [f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {w+5} {h+7}" shape-rendering="crispEdges">',
               f'<path fill="#000" fill-opacity=".18" d="M{ox+2} {oy+h}h{w-4}v1h-{w-4}z"/>']
        for ch, segs in paths.items():
            svg.append(f'<path fill="{pal[ch]}" d="{"".join(segs)}"/>')
        if mood == 'cheer':
            svg.append(f'<path fill="#ffc53d" transform="translate({w} 0)" d="{SPARK}"/>')
        if mood == 'wow':
            svg.append(f'<path fill="#e5484d" transform="translate({w} 0)" d="{ALERT}"/>')
        svg.append('</svg>')
        open(f'{out}/{who}-{mood}.svg', 'w').write(''.join(svg))
print(', '.join(f'{k}({len(v["rows"])} rows, closed={len(v["closed"])}, wide={len(v["wide"])})' for k, v in crew.items()))
