"""Original, copyright-free VitalStack tracks synthesised from scratch.
usage: python3 make_music.py OUT_DIR
Writes chiptune-levelup.wav (upbeat 8-bit, 132 bpm) and lofi-study.wav (chill, 84 bpm), 32 s each."""
import sys, wave
import numpy as np

SR = 44100
rng = np.random.default_rng(7)


def note_hz(n):  # MIDI note number -> Hz
    return 440.0 * 2 ** ((n - 69) / 12)


def env(n, a=0.005, r=0.08):
    e = np.ones(n)
    ai, ri = int(a * SR), int(r * SR)
    if ai: e[:ai] = np.linspace(0, 1, ai)
    if ri and ri < n: e[-ri:] *= np.linspace(1, 0, ri)
    return e


def place(buf, sig, t):
    i = int(t * SR)
    if i >= len(buf): return
    j = min(len(buf), i + len(sig))
    buf[i:j] += sig[: j - i]


def square(f, dur, duty=0.5):
    t = np.arange(int(dur * SR)) / SR
    return np.where((t * f) % 1 < duty, 1.0, -1.0)


def tri(f, dur):
    t = np.arange(int(dur * SR)) / SR
    return 2 * np.abs(2 * ((t * f) % 1) - 1) - 1


def kick(dur=0.25, f0=150, f1=45):
    t = np.arange(int(dur * SR)) / SR
    f = f1 + (f0 - f1) * np.exp(-t * 30)
    return np.sin(2 * np.pi * np.cumsum(f) / SR) * np.exp(-t * 12)


def noise_hit(dur, decay, hp=True):
    n = rng.uniform(-1, 1, int(dur * SR))
    if hp: n = np.diff(n, prepend=0)
    return n * np.exp(-np.arange(len(n)) / SR * decay)


def save(path, x):
    x = x / (np.max(np.abs(x)) + 1e-9) * 0.89
    fade = int(1.5 * SR)
    x[-fade:] *= np.linspace(1, 0, fade)
    x[:int(0.02 * SR)] *= np.linspace(0, 1, int(0.02 * SR))
    st = np.stack([x, x], 1)
    with wave.open(path, 'wb') as w:
        w.setnchannels(2); w.setsampwidth(2); w.setframerate(SR)
        w.writeframes((st * 32767).astype(np.int16).tobytes())


def chiptune(total=32.0):
    bpm = 132; beat = 60 / bpm; s16 = beat / 4
    buf = np.zeros(int(total * SR) + SR)
    # I–V–vi–IV in C major, 1 bar each
    prog = [(48, [60, 64, 67]), (43, [59, 62, 67]), (45, [60, 64, 69]), (41, [60, 65, 69])]
    lead_a = [72, 76, 79, 76, 81, 79, 76, 74, 72, 74, 76, 79, 76, 74, 72, 67]
    lead_b = [79, 81, 84, 81, 79, 76, 79, 81, 76, 74, 72, 74, 76, 79, 81, 84]
    bar = 4 * beat
    nbars = int(total / bar) + 1
    for b in range(nbars):
        root, chord = prog[b % 4]
        t0 = b * bar
        # bass: octave bounce on 8ths
        for k in range(8):
            f = note_hz(root + (12 if k % 2 else 0))
            place(buf, 0.33 * tri(f, beat / 2 * 0.9) * env(int(beat / 2 * 0.9 * SR)), t0 + k * beat / 2)
        # arpeggio on 16ths (quiet)
        for k in range(16):
            n = chord[k % 3] + 12
            place(buf, 0.07 * square(note_hz(n), s16 * 0.8, 0.25) * env(int(s16 * 0.8 * SR)), t0 + k * s16)
        # lead from bar 2, alternating phrases
        if b >= 1:
            phrase = lead_a if (b // 2) % 2 == 0 else lead_b
            half = phrase[:8] if b % 2 == 0 else phrase[8:]
            for k, n in enumerate(half):
                d = beat / 2 * 0.85
                place(buf, 0.16 * square(note_hz(n), d, 0.5) * env(int(d * SR), r=0.05), t0 + k * beat / 2)
        # drums
        for k in range(4):
            place(buf, 0.9 * kick(), t0 + k * beat)
            if k in (1, 3):
                place(buf, 0.35 * noise_hit(0.18, 18), t0 + k * beat)
        for k in range(8):
            place(buf, 0.10 * noise_hit(0.05, 90), t0 + k * beat / 2 + beat / 4)
    return buf[: int(total * SR)]


def epiano(f, dur):
    t = np.arange(int(dur * SR)) / SR
    x = (np.sin(2 * np.pi * f * t) + 0.35 * np.sin(2 * np.pi * 2 * f * t) * np.exp(-t * 3)
         + 0.5 * np.sin(2 * np.pi * f * 1.003 * t))
    return x * np.exp(-t * 1.1)


def lofi(total=32.0):
    bpm = 84; beat = 60 / bpm; bar = 4 * beat
    buf = np.zeros(int(total * SR) + SR)
    # Fmaj7 – Em7 – Dm7 – Cmaj7 (warm, study vibe)
    chords = [[53, 57, 60, 64], [52, 55, 59, 62], [50, 53, 57, 60], [48, 52, 55, 59]]
    melody = [[72, None, 69, 67], [67, None, 64, None], [65, 67, 69, None], [64, None, 62, 60]]
    nbars = int(total / bar) + 1
    for b in range(nbars):
        t0 = b * bar
        ch = chords[b % 4]
        for i, n in enumerate(ch):  # slight strum
            place(buf, 0.16 * epiano(note_hz(n), bar * 1.1), t0 + i * 0.015)
        place(buf, 0.28 * np.sin(2 * np.pi * note_hz(ch[0] - 12) * np.arange(int(bar * SR)) / SR) * env(int(bar * SR), 0.02, 0.4), t0)
        if b >= 2:
            for k, n in enumerate(melody[b % 4]):
                if n: place(buf, 0.13 * epiano(note_hz(n), beat * 1.4), t0 + k * beat + 0.02 * (k % 2))
        # lazy swung drums
        place(buf, 0.75 * kick(0.3, 110, 40), t0)
        place(buf, 0.6 * kick(0.3, 110, 40), t0 + 2.5 * beat)
        for k in (1, 3):
            place(buf, 0.22 * noise_hit(0.25, 14, hp=False), t0 + k * beat)
        for k in range(8):
            sw = 0.06 if k % 2 else 0
            place(buf, 0.05 * noise_hit(0.04, 120), t0 + k * beat / 2 + sw)
    x = buf[: int(total * SR)]
    # gentle low-pass (moving average) + vinyl crackle + hiss
    x = np.convolve(x, np.ones(6) / 6, mode='same')
    crackle = np.zeros_like(x)
    idx = rng.integers(0, len(x), int(total * 6))
    crackle[idx] = rng.uniform(-0.15, 0.15, len(idx))
    return x + crackle + rng.normal(0, 0.004, len(x))


out = sys.argv[1]
save(f'{out}/chiptune-levelup.wav', chiptune())
save(f'{out}/lofi-study.wav', lofi())
print('ok')
