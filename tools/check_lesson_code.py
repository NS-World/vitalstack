#!/usr/bin/env python3
"""Run the Python examples in lesson Markdown and compare them with the output
shown under them, so a lesson never teaches a wrong result.

A ```python block followed by an "Output" line and a ```text block is run and
its printed output compared. Blocks in one lesson share variables, like a
notebook. Write "(may differ)" or "your version" on the Output line to skip
the comparison for that block (random or version-dependent output).

A "::figure name.svg" line after a block that draws a chart saves that chart
to the theme's assets/lessons/ folder when --figures is given.

Usage:
    python3 tools/check_lesson_code.py content/lessons/numpy [more folders...]
    python3 tools/check_lesson_code.py --figures content/lessons/data-visualization
"""
import contextlib
import io
import pathlib
import re
import sys

sys.path.insert(0, str(pathlib.Path(__file__).resolve().parent))

import matplotlib

matplotlib.use("Agg")
import matplotlib.pyplot as plt  # noqa: E402

plt.show = lambda *a, **k: None

BLOCK = re.compile(r"```(\w+)\n(.*?)```", re.S)
FIGURES = pathlib.Path(__file__).resolve().parent.parent / "wp-content" / "themes" / "vitalstack" / "assets" / "lessons"
SAVE = False
plt.rcParams.update({"svg.fonttype": "none", "figure.dpi": 100, "savefig.bbox": "tight"})


def check(path):
    text = path.read_text(encoding="utf-8")
    blocks = [(m.group(1), m.group(2), m.start(), m.end()) for m in BLOCK.finditer(text)]
    ns, bad = {}, 0
    for i, (lang, code, start, end) in enumerate(blocks):
        if lang != "python":
            continue
        out = io.StringIO()
        try:
            with contextlib.redirect_stdout(out):
                exec(compile(code, f"{path.name}#{i}", "exec"), ns)
        except Exception as exc:  # noqa: BLE001
            if "# raises" in code or "Error" in (blocks[i + 1][1] if i + 1 < len(blocks) else ""):
                out.write(f"{type(exc).__name__}: {exc}\n")
            else:
                print(f"  ERROR {path.name} block {i}: {type(exc).__name__}: {exc}")
                bad += 1
                continue
        nxt = blocks[i + 1][2] if i + 1 < len(blocks) else len(text)
        fig = re.search(r"^::figure\s+([a-z0-9-]+\.svg)", text[end:nxt], re.M)
        if fig and SAVE and plt.get_fignums():
            FIGURES.mkdir(parents=True, exist_ok=True)
            plt.gcf().savefig(FIGURES / fig.group(1), format="svg", metadata={"Date": None})
        plt.close("all")
        if i + 1 < len(blocks) and blocks[i + 1][0] == "text":
            between = text[end:blocks[i + 1][2]]
            if "Output" not in between:
                continue
            if "may differ" in between or "your version" in between:
                continue
            want = "\n".join(l.rstrip() for l in blocks[i + 1][1].strip("\n").splitlines())
            got = "\n".join(l.rstrip() for l in out.getvalue().strip("\n").splitlines())
            if "Error" in want and want.split(":")[0] in got:
                continue
            if want != got:
                print(f"  MISMATCH {path.name} block {i}:\n--- shown ---\n{want}\n--- actual ---\n{got}\n")
                bad += 1
    return bad + check_walks(path, text)


def check_walks(path, text):
    """Run each ::walk python block; its "out:" notes, in order, must equal what it prints."""
    from lessons_to_wxr import parse_walk  # noqa: E402 (same folder)
    lines, bad = text.splitlines(), 0
    for k, line in enumerate(lines):
        if not line.startswith("::walk "):
            continue
        lang, title, code, steps = parse_walk(lines, k)
        if lang not in ("python", "py"):
            continue
        out = io.StringIO()
        try:
            with contextlib.redirect_stdout(out):
                exec(compile("\n".join(code), f"{path.name}#walk:{title}", "exec"), {})
        except Exception as exc:  # noqa: BLE001
            print(f"  ERROR {path.name} walk '{title}': {type(exc).__name__}: {exc}")
            bad += 1
            continue
        want = "\n".join(s["out"] for s in steps if s["out"]).strip()
        got = "\n".join(l.rstrip() for l in out.getvalue().strip("\n").splitlines())
        if want and want != got:
            print(f"  MISMATCH {path.name} walk '{title}':\n--- notes say ---\n{want}\n--- actual ---\n{got}\n")
            bad += 1
    return bad


def main():
    global SAVE
    args = sys.argv[1:]
    if "--figures" in args:
        SAVE = True
        args.remove("--figures")
    total = 0
    for folder in args:
        for p in sorted(pathlib.Path(folder).glob("*.md")):
            n = check(p)
            total += n
            print(("ok   " if not n else "FAIL ") + p.name)
    sys.exit(1 if total else 0)


if __name__ == "__main__":
    main()
