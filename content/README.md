# Content

All site content is written as Markdown with a small front-matter header, so it
can be reviewed and edited in Git.

```
content/
  lessons/<subject>/NN-slug.md   Tutorial lessons (html 20, css 20, javascript 25, sql 15, python 20)
  guides/<category>/slug.md      Blog guides (artificial-intelligence, ai-tools, ai-career, programming)
  pages/                         Text for About and Editorial Policy pages
  import/                        Generated WordPress import files (WXR), an alternative to the one-click import
  cleanup-list.csv               What to do with each post from the old site
```

## How content gets onto the site

1. Edit or add Markdown files here.
2. Rebuild the bundle the theme imports in one click:

   ```
   python3 tools/build_starter_content.py
   ```

   This writes `wp-content/themes/vitalstack/starter-content/content.json`.
   Re-zip the theme and upload it, then use **Appearance → VitalStack Setup →
   Import starter content**. Items whose slug already exists are skipped.

3. Optional: rebuild the WXR files for **Tools → Import → WordPress**:

   ```
   python3 tools/lessons_to_wxr.py content/lessons content/guides -o content/import/vitalstack-all-content.xml
   ```

## Front matter

Lesson:

```
---
title: HTML Links
slug: html-links
subject: html          # learning path slug
lesson: 9              # position in the path
difficulty: beginner   # beginner | intermediate | advanced
excerpt: One sentence for cards, search results and emails.
---
```

Guide:

```
---
title: What Are AI Agents?
slug: what-are-ai-agents
category: artificial-intelligence   # blog category slug
tags: AI agents, LLM
order: 10                            # position in the "a few per day" schedule
excerpt: One sentence.
---
```

Supported Markdown: `##`/`###` headings, paragraphs, `-` and `1.` lists,
`| tables |`, fenced code blocks, `inline code`, **bold** and `[links](/path/)`.
Internal links use site paths: `/tutorials/<lesson-slug>/` for lessons and
`/<guide-slug>/` for guides.
