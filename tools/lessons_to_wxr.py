#!/usr/bin/env python3
"""Turn lesson Markdown files into a WordPress import file (WXR).

Each lesson is a .md file with a front-matter header:

    ---
    title: HTML Introduction
    slug: html-introduction
    subject: html            # learning path slug (created by VitalStack Setup)
    lesson: 1                # lesson number in the subject
    difficulty: beginner
    excerpt: One sentence shown on cards and in emails.
    ---

Guides (blog posts) use `category:` instead of `subject:` and `lesson:`:

    ---
    title: What Is a Large Language Model?
    slug: what-is-a-large-language-model
    category: artificial-intelligence   # blog category slug
    excerpt: ...
    ---

The body supports the Markdown that lessons need: ## and ### headings,
paragraphs, - and 1. lists, | tables |, fenced ``` code blocks, `inline code`,
**bold** and [links](/path/), plus `::viz name {json}` (live visualizer),
`::tip text`, `::hint Label | text` and `::figure file.svg | alt text` (a chart from
wp-content/themes/vitalstack/assets/lessons/). It is converted to block-editor markup, so imported lessons open
cleanly in the WordPress editor.

Import the result in WordPress: Tools -> Import -> WordPress. Lessons are
imported as drafts.

Usage (folders are searched recursively; several may be given):
    python3 tools/lessons_to_wxr.py content/lessons/html -o content/import/html-lessons.xml
    python3 tools/lessons_to_wxr.py content/lessons content/guides -o content/import/all.xml
"""

import argparse
import html
import json
import pathlib
import re
import sys
from datetime import datetime, timezone

SUBJECT_NAMES = {
    "html": "HTML",
    "css": "CSS",
    "javascript": "JavaScript",
    "sql": "SQL",
    "python": "Python",
    "java": "Java",
    "java-oop": "Java OOP",
    "servlet": "Servlet & JSP",
    "jpa": "JPA",
    "hibernate": "Hibernate",
    "spring": "Spring",
    "spring-boot": "Spring Boot",
    "jmix": "Jmix",
    "react": "React",
    "angular": "Angular",
    "dsa": "DSA",
    "dsa-problems": "Interview Problems",
}

# Code fence language → highlight.js language (the class on <code> skips auto-detection).
FENCE_LANGS = {"js": "javascript", "jsx": "javascript", "tsx": "typescript", "ts": "typescript", "text": "plaintext",
               "txt": "plaintext", "py": "python", "properties": "ini", "sh": "bash", "shell": "bash", "yml": "yaml"}

CATEGORY_NAMES = {
    "artificial-intelligence": "Artificial Intelligence",
    "ai-tools": "AI Tools",
    "ai-career": "AI Career",
    "programming-development": "Programming",
}


def parse_front_matter(text, path):
    match = re.match(r"^---\n(.*?)\n---\n(.*)$", text, re.S)
    if not match:
        sys.exit(f"{path}: missing front matter (--- ... ---)")
    meta = {}
    for line in match.group(1).splitlines():
        if ":" in line:
            key, value = line.split(":", 1)
            value = value.split(" #", 1)[0].strip() if key.strip() not in ("excerpt", "title") else value.strip()
            if len(value) > 1 and value[0] == value[-1] and value[0] in "\"'":
                value = value[1:-1]
            meta[key.strip()] = value
    if meta.get("parent"):
        required = ("title", "slug")  # theme-managed site page (tools/build_pages.py)
    elif meta.get("category"):
        required = ("title", "slug", "category")
    else:
        required = ("title", "slug", "subject", "lesson")
    for key in required:
        if not meta.get(key):
            sys.exit(f"{path}: front matter needs '{key}'")
    return meta, match.group(2)


def inline(text):
    """Escape text, then apply `code`, **bold** and [links](url)."""
    parts = re.split(r"(``.+?``|`[^`]+`)", text)
    out = []
    for part in parts:
        if part.startswith("``") and part.endswith("``") and len(part) > 4:
            out.append("<code>" + html.escape(part[2:-2].strip(), quote=False) + "</code>")
        elif part.startswith("`") and part.endswith("`") and len(part) > 1:
            out.append("<code>" + html.escape(part[1:-1], quote=False) + "</code>")
        else:
            escaped = html.escape(part, quote=False)
            escaped = re.sub(r"\*\*(.+?)\*\*", r"<strong>\1</strong>", escaped)
            escaped = re.sub(r"\[([^\]]+)\]\(([^)\s\"]+)\)", r'<a href="\2">\1</a>', escaped)
            out.append(escaped)
    return "".join(out)


def block(name, inner, attrs=""):
    attrs = f" {attrs}" if attrs else ""
    return f"<!-- wp:{name}{attrs} -->\n{inner}\n<!-- /wp:{name} -->\n\n"


def markdown_to_blocks(body):
    lines = body.strip("\n").splitlines()
    out = []
    i = 0
    while i < len(lines):
        line = lines[i]
        if not line.strip():
            i += 1
            continue
        if line.startswith("```"):
            lang = FENCE_LANGS.get(line[3:].strip().lower(), line[3:].strip().lower())
            code = []
            i += 1
            while i < len(lines) and not lines[i].startswith("```"):
                code.append(lines[i])
                i += 1
            i += 1  # closing fence
            escaped = html.escape("\n".join(code), quote=False)
            cls = f' class="language-{lang}"' if re.fullmatch(r"[a-z0-9]+", lang or "") else ""
            out.append(block("code", f'<pre class="wp-block-code"><code{cls}>{escaped}</code></pre>'))
            continue
        if line.startswith("::viz "):
            # ::viz binary-search {"arr": [1, 3, 5], "target": 3}
            m = re.match(r"^::viz\s+([a-z0-9-]+)\s*(\{.*\})?\s*$", line.strip())
            if not m:
                sys.exit(f"Bad ::viz line: {line}")
            data = m.group(2) or "{}"
            json.loads(data)  # fail early on invalid JSON
            div = f'<div class="vs-viz" data-viz="{m.group(1)}" data-input="{html.escape(data, quote=True)}"></div>'
            out.append(block("html", div))
            i += 1
            continue
        if line.startswith("::tip "):
            out.append(block("html", f'<div class="vs-tip"><p>{inline(line[6:].strip())}</p></div>'))
            i += 1
            continue
        if line.startswith("::hint "):
            label, _, text = line[7:].partition("|")
            out.append(block("html", f'<details class="vs-hint"><summary>{inline(label.strip())}</summary><p>{inline(text.strip())}</p></details>'))
            i += 1
            continue
        if line.startswith("::figure "):
            # ::figure dataviz-line-chart.svg | Monthly sales as a line chart
            name, _, alt = line[9:].partition("|")
            name = name.strip()
            if not re.fullmatch(r"[a-z0-9-]+\.(svg|png)", name):
                sys.exit(f"Bad ::figure line: {line}")
            alt = alt.strip().replace('"', "'")
            out.append(block("shortcode", f'[vs_figure file="{name}" alt="{html.escape(alt, quote=False)}"]'))
            i += 1
            continue
        if line.startswith("### "):
            out.append(block("heading", f'<h3 class="wp-block-heading">{inline(line[4:].strip())}</h3>', '{"level":3}'))
            i += 1
            continue
        if line.startswith("## "):
            out.append(block("heading", f'<h2 class="wp-block-heading">{inline(line[3:].strip())}</h2>'))
            i += 1
            continue
        if line.startswith("|"):
            rows = []
            while i < len(lines) and lines[i].startswith("|"):
                row = lines[i].strip()[1:]
                row = row[:-1] if row.endswith("|") and not row.endswith("\\|") else row
                cells = [c.strip().replace("\\|", "|") for c in re.split(r"(?<!\\)\|", row)]
                if not all(re.fullmatch(r":?-{3,}:?", c) for c in cells):
                    rows.append(cells)
                i += 1
            head = "".join(f"<th>{inline(c)}</th>" for c in rows[0])
            body_rows = "".join("<tr>" + "".join(f"<td>{inline(c)}</td>" for c in r) + "</tr>" for r in rows[1:])
            out.append(block("table", f'<figure class="wp-block-table"><table><thead><tr>{head}</tr></thead><tbody>{body_rows}</tbody></table></figure>'))
            continue
        ordered = re.match(r"^\d+\.\s", line)
        if line.startswith("- ") or ordered:
            tag = "ol" if ordered else "ul"
            pattern = r"^\d+\.\s+" if ordered else r"^-\s+"
            items = []
            while i < len(lines) and re.match(pattern, lines[i]):
                items.append(re.sub(pattern, "", lines[i]).strip())
                i += 1
                # continuation lines indented under a list item
                while i < len(lines) and lines[i].startswith("  ") and lines[i].strip():
                    items[-1] += " " + lines[i].strip()
                    i += 1
            lis = "".join(block("list-item", f"<li>{inline(item)}</li>") for item in items).rstrip("\n")
            attrs = '{"ordered":true}' if ordered else ""
            out.append(block("list", f'<{tag} class="wp-block-list">{lis}</{tag}>', attrs))
            continue
        para = [line.strip()]
        i += 1
        while i < len(lines) and lines[i].strip() and not re.match(r"^(#{2,3} |- |\d+\.\s|```|\||::)", lines[i]):
            para.append(lines[i].strip())
            i += 1
        out.append(block("paragraph", f"<p>{inline(' '.join(para))}</p>"))
    return "".join(out).rstrip("\n") + "\n"


def cdata(text):
    return "<![CDATA[" + text.replace("]]>", "]]]]><![CDATA[>") + "]]>"


def build_wxr(lessons, site):
    now = datetime.now(timezone.utc)
    date = now.strftime("%Y-%m-%d %H:%M:%S")
    rfc = now.strftime("%a, %d %b %Y %H:%M:%S +0000")
    terms, items = {}, []
    for n, (meta, content) in enumerate(lessons, start=1):
        if meta.get("category"):
            post_type, order, path = "post", 0, ""
            cat = meta["category"]
            terms[("category", cat)] = CATEGORY_NAMES.get(cat, cat.replace("-", " ").title())
            cats = [f'<category domain="category" nicename="{cat}">{cdata(terms[("category", cat)])}</category>']
            for tag in filter(None, (t.strip() for t in meta.get("tags", "").split(","))):
                slug = re.sub(r"[^a-z0-9]+", "-", tag.lower()).strip("-")
                terms[("post_tag", slug)] = tag
                cats.append(f'<category domain="post_tag" nicename="{slug}">{cdata(tag)}</category>')
        else:
            post_type, order, path = "tutorials", int(meta["lesson"]), "tutorials/"
            subject = meta["subject"]
            terms[("tutorial_category", subject)] = SUBJECT_NAMES.get(subject, subject.title())
            cats = [f'<category domain="tutorial_category" nicename="{subject}">{cdata(terms[("tutorial_category", subject)])}</category>']
            if meta.get("difficulty"):
                level = meta["difficulty"].lower()
                terms[("tutorial_difficulty", level)] = level.title()
                cats.append(f'<category domain="tutorial_difficulty" nicename="{level}">{cdata(level.title())}</category>')
        postmeta = "".join(
            f"\n\t\t<wp:postmeta><wp:meta_key>{cdata('_vs_' + k)}</wp:meta_key><wp:meta_value>{cdata(meta[k])}</wp:meta_value></wp:postmeta>"
            for k in ("pattern", "companies", "level") if meta.get(k)
        )
        items.append(f"""	<item>
		<title>{cdata(meta["title"])}</title>
		<link>{site}/{path}{meta["slug"]}/</link>
		<pubDate>{rfc}</pubDate>
		<dc:creator>{cdata("admin")}</dc:creator>
		<guid isPermaLink="false">{site}/?post_type={post_type}&amp;p={n}</guid>
		<description></description>
		<content:encoded>{cdata(content)}</content:encoded>
		<excerpt:encoded>{cdata(meta.get("excerpt", ""))}</excerpt:encoded>
		<wp:post_id>{n}</wp:post_id>
		<wp:post_date>{cdata(date)}</wp:post_date>
		<wp:post_date_gmt>{cdata(date)}</wp:post_date_gmt>
		<wp:comment_status>{cdata("open")}</wp:comment_status>
		<wp:ping_status>{cdata("closed")}</wp:ping_status>
		<wp:post_name>{cdata(meta["slug"])}</wp:post_name>
		<wp:status>{cdata("draft")}</wp:status>
		<wp:post_parent>0</wp:post_parent>
		<wp:menu_order>{order}</wp:menu_order>
		<wp:post_type>{cdata(post_type)}</wp:post_type>
		<wp:post_password>{cdata("")}</wp:post_password>
		<wp:is_sticky>0</wp:is_sticky>
		{chr(10).join("		" + c if i else c for i, c in enumerate(cats))}{postmeta}
	</item>""")
    term_xml = []
    for k, ((taxonomy, slug), name) in enumerate(sorted(terms.items()), start=1):
        term_xml.append(f"""	<wp:term>
		<wp:term_id>{k}</wp:term_id>
		<wp:term_taxonomy>{cdata(taxonomy)}</wp:term_taxonomy>
		<wp:term_slug>{cdata(slug)}</wp:term_slug>
		<wp:term_parent>{cdata("")}</wp:term_parent>
		<wp:term_name>{cdata(name)}</wp:term_name>
	</wp:term>""")
    return f"""<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0"
	xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:wp="http://wordpress.org/export/1.2/">
<channel>
	<title>VitalStack lessons</title>
	<link>{site}</link>
	<description>Lesson import</description>
	<pubDate>{rfc}</pubDate>
	<language>en-US</language>
	<wp:wxr_version>1.2</wp:wxr_version>
	<wp:base_site_url>{site}</wp:base_site_url>
	<wp:base_blog_url>{site}</wp:base_blog_url>
	<wp:author><wp:author_id>1</wp:author_id><wp:author_login>{cdata("admin")}</wp:author_login><wp:author_email>{cdata("")}</wp:author_email><wp:author_display_name>{cdata("admin")}</wp:author_display_name></wp:author>
{chr(10).join(term_xml)}
{chr(10).join(items)}
</channel>
</rss>
"""


def main():
    parser = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    parser.add_argument("folders", nargs="+", help="folders of lesson / guide .md files")
    parser.add_argument("-o", "--output", required=True, help="WXR file to write")
    parser.add_argument("--site", default="https://vitalstack.co.in", help="site URL written into the file")
    args = parser.parse_args()

    files = [f for folder in args.folders for f in sorted(pathlib.Path(folder).rglob("*.md"))]
    if not files:
        sys.exit(f"No .md files in {', '.join(args.folders)}")
    lessons = []
    for path in files:
        meta, body = parse_front_matter(path.read_text(encoding="utf-8"), path)
        lessons.append((meta, markdown_to_blocks(body)))
    pathlib.Path(args.output).parent.mkdir(parents=True, exist_ok=True)
    pathlib.Path(args.output).write_text(build_wxr(lessons, args.site.rstrip("/")), encoding="utf-8")
    guides = sum(1 for meta, _ in lessons if meta.get("category"))
    print(f"Wrote {len(lessons) - guides} lessons and {guides} guides to {args.output}")


if __name__ == "__main__":
    main()
