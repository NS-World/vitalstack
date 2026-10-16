# VitalStack

Source for [vitalstack.co.in](https://vitalstack.co.in/): a WordPress site hosted on Hostinger.

## Repository layout

```
wp-content/themes/vitalstack/   Custom WordPress theme (deployed to Hostinger)
  inc/                          PHP modules: setup, post types, learning paths, TOC, SEO, customizer,
                                accounts (/account/), email notifications, progress API, UI parts
  templates/account.php         Sign in / sign up / reader dashboard
  assets/js/main.js             Dark mode, search, TOC, code copy, Try-it editor, progress sync
  style.css                     Whole design system (light + dark)
  starter-content/content.json  100 lessons + 13 guides, imported in one click from VitalStack Setup
content/                        Lessons and guides (Markdown), page texts, import file, cleanup list
tools/build_starter_content.py  Bundles content/ into the theme's starter-content file
tools/lessons_to_wxr.py         Converts the Markdown into a WordPress import file (WXR)
docs/                           Audit, roadmap, setup guides
```

WordPress core, plugins, uploads and the database live on Hostinger and are
**not** stored here. WordPress export files (`*.WordPress.*.xml`) contain user
emails and are git-ignored on purpose.

## Deploying the theme

1. Zip the theme folder: `cd wp-content/themes && zip -r vitalstack.zip vitalstack`
2. WordPress admin → Appearance → Themes → Add New → Upload → replace current.

(Later: Hostinger Git deployment can pull this repo automatically.)

## Key docs

- [`docs/AUDIT-2026-09.md`](docs/AUDIT-2026-09.md): why AdSense rejected the site
- [`docs/CONTENT-GUIDE.md`](docs/CONTENT-GUIDE.md): **the included 100 lessons + 13 guides, making them yours, writing new ones**
- [`docs/FRESH-SITE-SETUP.md`](docs/FRESH-SITE-SETUP.md): **new WordPress site from scratch, step by step**
- [`docs/ADSENSE-APPROVAL.md`](docs/ADSENSE-APPROVAL.md): **the final checklist to get AdSense approval**
- [`docs/ROADMAP.md`](docs/ROADMAP.md): step-by-step plan to approval and growth
- [`docs/THEME-V2-SETUP.md`](docs/THEME-V2-SETUP.md): how to install the theme and clean up authors, menus and pages
- [`docs/THEME-V3-SETUP.md`](docs/THEME-V3-SETUP.md): v3 extras: accounts, email delivery (SMTP), cron, topic bar
- [`content/cleanup-list.csv`](content/cleanup-list.csv): what to do with every existing post
