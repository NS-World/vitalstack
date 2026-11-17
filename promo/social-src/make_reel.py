"""Turn carousel slides into a 1080x1920 reel: each slide centred on the brand background,
gentle zoom, crossfades, original VitalStack music from the earlier reel pack.
usage: python3 make_reel.py out.mp4 audio.mp4 slide1.png:secs slide2.png:secs ..."""
import subprocess, sys

out, audio, *items = sys.argv[1:]
slides = [(p.rsplit(':', 1)[0], float(p.rsplit(':', 1)[1])) for p in items]
FADE = 0.5
inputs, filters = [], []
for i, (path, secs) in enumerate(slides):
    inputs += ['-loop', '1', '-t', str(secs + FADE), '-i', path]
    frames = int((secs + FADE) * 30)
    filters.append(
        f"[{i}:v]scale=1080:1350,zoompan=z='min(zoom+0.0006,1.04)':d={frames}:s=1080x1350:fps=30,"
        f"pad=1080:1920:0:285:color=0x10141c,setsar=1,format=yuv420p[v{i}]")
last, offset = 'v0', 0.0
for i in range(1, len(slides)):
    offset += slides[i - 1][1]
    filters.append(f"[{last}][v{i}]xfade=transition=fade:duration={FADE}:offset={offset:.2f}[x{i}]")
    last = f'x{i}'
total = sum(s for _, s in slides) + FADE
a = len(slides)
filters.append(f"[{a}:a]atrim=0:{total:.2f},afade=t=out:st={total-1.2:.2f}:d=1.2[a]")
cmd = ['ffmpeg', '-y', '-loglevel', 'error', *inputs, '-stream_loop', '-1', '-i', audio,
       '-filter_complex', ';'.join(filters), '-map', f'[{last}]', '-map', '[a]',
       '-t', f'{total:.2f}', '-c:v', 'libx264', '-preset', 'medium', '-crf', '20', '-pix_fmt', 'yuv420p',
       '-c:a', 'aac', '-b:a', '160k', '-movflags', '+faststart', out]
subprocess.run(cmd, check=True)
print(out, f'{total:.1f}s')
