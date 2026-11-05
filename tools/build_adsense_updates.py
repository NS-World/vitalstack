#!/usr/bin/env python3
"""Bundle refreshed lessons into the AdSense Helper plugin.

The theme importer never overwrites posts that already exist, so lessons
expanded after the site was set up need another route. This writes
plugins/vitalstack-adsense-helper/updates.json; the plugin's "Update thin
lessons" button replaces a published post's content with the bundled
version only when the bundled one is longer.

Usage:
    python3 tools/build_adsense_updates.py content/lessons/dsa-problems/02-contains-duplicate.md ...
"""

import json
import pathlib
import sys

sys.path.insert(0, str(pathlib.Path(__file__).parent))
from lessons_to_wxr import markdown_to_blocks, parse_front_matter  # noqa: E402

ROOT = pathlib.Path(__file__).resolve().parent.parent
OUTPUT = ROOT / "plugins" / "vitalstack-adsense-helper" / "updates.json"


def main(paths):
    items = []
    for p in map(pathlib.Path, paths):
        meta, body = parse_front_matter(p.read_text(encoding="utf-8"), p)
        items.append({"slug": meta["slug"], "title": meta["title"], "content": markdown_to_blocks(body)})
    OUTPUT.write_text(json.dumps({"version": 1, "items": items}, ensure_ascii=False, indent=1), encoding="utf-8")
    print(f"Wrote {len(items)} items to {OUTPUT.relative_to(ROOT)}")


if __name__ == "__main__":
    main(sys.argv[1:])
