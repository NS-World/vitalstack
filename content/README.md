# Content

All site content is written as Markdown with a small front-matter header, so it
can be reviewed and edited in Git.

```
content/
  lessons/<subject>/NN-slug.md   Tutorial lessons: 282 across 17 paths (html, css, javascript, sql, python,
                                 java, java-oop, servlet, jpa, hibernate, spring, spring-boot, jmix,
                                 react, angular, dsa, dsa-problems)
  quizzes/<subject>.md           End-of-lesson quizzes (3 questions per lesson)
  site-pages/<hub>/slug.md       Cheat sheets and free-tool guide pages
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

Interview problem (extra fields shown on the Interview Problems page):

```
---
title: Two Sum
slug: problem-two-sum
subject: dsa-problems
lesson: 1
difficulty: beginner                # beginner | intermediate | advanced
level: Easy                         # Easy | Medium | Hard (badge and filter)
pattern: Hashing                    # pattern chip and filter
companies: Amazon, Google, Meta     # "often asked at" column and filter
excerpt: One sentence.
---
```

Special lines (each on its own line):

| Line | Becomes |
|---|---|
| `::viz binary-search {"arr": [1, 3, 5], "target": 5}` | A live animated diagram. The name is an algorithm from `assets/js/viz.js`; the JSON is the starting input (optional). |
| `::tip Text` | A tip box with the mascot |
| `::hint Hint 1 \| Text` | A click-to-reveal hint |

Supported Markdown: `##`/`###` headings, paragraphs, `-` and `1.` lists,
`| tables |`, fenced code blocks, `inline code`, **bold** and `[links](/path/)`.

Always put the language after the opening fence (```` ```java ````, `python`,
`js`, `jsx`, `ts`, `html`, `css`, `sql`, `bash`, `yaml`, `properties`, `text`).
It picks the highlighting and the label ("Java Example"), and decides whether a
**Try it Yourself** button appears (only for HTML and plain browser JavaScript).
Internal links use site paths: `/tutorials/<lesson-slug>/` for lessons and
`/<guide-slug>/` for guides.

## Quizzes

Each learning path has one file, `content/quizzes/<subject>.md`:

```
## java-strings                     <- the lesson slug
Q: How should you compare two Strings?
- [ ] `a == b`
- [x] `a.equals(b)`                 <- exactly one [x]
- [ ] `a = b`
> == compares references; equals compares characters.   <- explanation shown after answering
```

Write the options in any order: the build shuffles them (the same way every
time), so the correct answer isn't always first. Then run:

```
python3 tools/build_quizzes.py --check
```

It writes `wp-content/themes/vitalstack/data/quizzes/<subject>.php`, checks that
every slug is a real lesson, and lists lessons that still have no quiz. Quiz
questions also feed the daily challenge.

## Cheat sheets and tool pages

`content/site-pages/cheat-sheets/<slug>.md` (and `tools/`) with front matter:

```
---
title: Java Cheat Sheet
slug: java-cheat-sheet
parent: cheat-sheets
template: page-cheat-sheet.php
icon: ☕
order: 60
excerpt: One line for cards and search results.
---
```

Run `python3 tools/build_pages.py`, then **Create starter structure** in
VitalStack Setup creates any new pages. The page text comes from the theme, so
updating the theme updates the sheets (unless you've typed your own text into
the page in the editor).
