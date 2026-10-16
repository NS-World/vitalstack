#!/usr/bin/env python3
"""Minify the theme's CSS and JS into assets/build/.

Run after editing style.css or any file in assets/js/:

    pip install rcssmin rjsmin
    python3 tools/build_assets.py

The build records an MD5 of each source file in assets/build/manifest.json. The
theme serves a minified copy only while its source still matches that hash,
so an edited style.css is never hidden behind a stale build.
"""
import hashlib
import json
import pathlib

import rcssmin
import rjsmin

THEME = pathlib.Path(__file__).resolve().parent.parent / "wp-content" / "themes" / "vitalstack"
BUILD = THEME / "assets" / "build"


def main():
    BUILD.mkdir(parents=True, exist_ok=True)
    jobs = [(THEME / "style.css", BUILD / "style.min.css", rcssmin.cssmin)]
    for js in sorted((THEME / "assets" / "js").glob("*.js")):
        jobs.append((js, BUILD / (js.stem + ".min.js"), rjsmin.jsmin))
    manifest = {}
    for src, dest, minify in jobs:
        text = src.read_text(encoding="utf-8")
        manifest[str(src.relative_to(THEME))] = hashlib.md5(src.read_bytes()).hexdigest()
        out = minify(text)
        dest.write_text(out, encoding="utf-8")
        print(f"{src.relative_to(THEME)}: {len(text) // 1024} KB -> {len(out) // 1024} KB")
    (BUILD / "manifest.json").write_text(json.dumps(manifest, indent=1) + "\n", encoding="utf-8")


if __name__ == "__main__":
    main()
