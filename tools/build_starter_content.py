#!/usr/bin/env python3
"""Bundle every lesson and guide into the theme's starter-content file.

The VitalStack Setup screen (Appearance -> VitalStack Setup) imports this
file in one click, so a fresh site needs no import plugin or upload.

Items are written in the order they should be published when the site owner
chooses "publish a few per day": the learning paths in course order (HTML,
CSS, JavaScript, SQL, Python) with a guide after every few lessons.

Usage:
    python3 tools/build_starter_content.py
"""

import json
import pathlib
import sys

sys.path.insert(0, str(pathlib.Path(__file__).parent))
from lessons_to_wxr import CATEGORY_NAMES, SUBJECT_NAMES, markdown_to_blocks, parse_front_matter  # noqa: E402

ROOT = pathlib.Path(__file__).resolve().parent.parent
LESSONS = ROOT / "content" / "lessons"
GUIDES = ROOT / "content" / "guides"
OUTPUT = ROOT / "wp-content" / "themes" / "vitalstack" / "starter-content" / "content.json"
SUBJECT_ORDER = ["html", "css", "javascript", "sql", "python", "dsa", "dsa-problems"]
LESSONS_PER_GUIDE = 7


def load(path):
    meta, body = parse_front_matter(path.read_text(encoding="utf-8"), path)
    item = {
        "title": meta["title"],
        "slug": meta["slug"],
        "excerpt": meta.get("excerpt", ""),
        "content": markdown_to_blocks(body),
    }
    if meta.get("category"):
        item.update(type="post", category=meta["category"], order=int(meta.get("order", 999)),
                    tags=[t.strip() for t in meta.get("tags", "").split(",") if t.strip()])
    else:
        item.update(type="tutorials", subject=meta["subject"], lesson=int(meta["lesson"]),
                    difficulty=meta.get("difficulty", "beginner").lower())
        # Interview problems carry extra details shown on the problems page.
        extra = {k: meta[k] for k in ("pattern", "companies", "level") if meta.get(k)}
        if extra:
            item["meta"] = extra
    return item


def main():
    lessons = []
    for subject in SUBJECT_ORDER:
        lessons += sorted((load(p) for p in (LESSONS / subject).glob("*.md")), key=lambda i: i["lesson"])
    extra = [p for p in LESSONS.iterdir() if p.is_dir() and p.name not in SUBJECT_ORDER]
    for folder in sorted(extra):
        lessons += sorted((load(p) for p in folder.glob("*.md")), key=lambda i: i["lesson"])

    # Guides go out in the order set by `order:` in their front matter.
    guides = sorted((load(p) for p in GUIDES.rglob("*.md")), key=lambda g: (g["order"], g["slug"]))

    ordered = []
    for n, lesson in enumerate(lessons, start=1):
        ordered.append(lesson)
        if n % LESSONS_PER_GUIDE == 0 and guides:
            ordered.append(guides.pop(0))
    ordered += guides

    bundle = {
        "version": 1,
        "subjects": SUBJECT_NAMES,
        "categories": CATEGORY_NAMES,
        "items": ordered,
    }
    OUTPUT.parent.mkdir(parents=True, exist_ok=True)
    OUTPUT.write_text(json.dumps(bundle, ensure_ascii=False, separators=(",", ":")), encoding="utf-8")
    counts = {}
    for item in ordered:
        key = item.get("subject") or "guides"
        counts[key] = counts.get(key, 0) + 1
    print(f"Wrote {len(ordered)} items to {OUTPUT.relative_to(ROOT)}: {counts}")


if __name__ == "__main__":
    main()
