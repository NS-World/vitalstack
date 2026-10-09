# New WordPress site from scratch: step by step

For a brand-new WordPress install on Hostinger with the VitalStack theme.
Total time: about 1 hour, plus writing lessons.

## 1. Upload the theme (5 min)
1. WordPress admin → **Appearance → Themes → Add New → Upload Theme**.
2. Choose `vitalstack-theme-v3.2.zip` → **Install Now** → **Activate**
   (or **Replace current with uploaded** if an older VitalStack is installed).

## 2. Click the setup button (2 min)
1. **Appearance → VitalStack Setup** (a blue notice on the dashboard links there too).
2. Leave **"Delete WordPress sample content"** ticked.
3. Click **Create starter structure**.

It creates:

| What | Details |
|---|---|
| Subjects (learning paths) | HTML, CSS, JavaScript, SQL, Python |
| Blog categories | Artificial Intelligence, AI Tools, AI Career, Programming |
| Difficulty levels | Beginner, Intermediate, Advanced |
| Published pages | Tutorials, Blog, Contact Us |
| Draft pages (review these) | About Us, Editorial Policy, Privacy Policy, Terms of Service, Disclaimer, Cookie Policy |
| Menus | Main menu + footer Company and Legal menus, already placed |
| Settings | "Post name" permalinks, tagline, privacy page, sample content removed |

It's safe to click again later: it only adds what's missing.

A subject only shows up in the dark top bar and on the homepage **after
it has at least one published lesson**. An empty site shows only
AI Guides / AI Tools / Careers. That's normal.

## 3. Review and publish the 6 draft pages (20 min)
The setup page lists them with edit links. For each one:
1. Read it. Change anything that isn't true for you.
2. **About Us**: replace `[one honest line about you…]` with your own line.
3. Click **Publish**.

These pages matter for AdSense: reviewers check them.

## 4. Your author profile (5 min)
**Users → Profile**:
- First name / nickname = your pen name → *Display name publicly as* = pen name.
- *Biographical Info*: 2–3 honest lines (shown under every article).

## 5. Plugins (15 min)
Install only these (Plugins → Add New):

| Plugin | Why |
|---|---|
| **Yoast SEO** (or Rank Math) | Titles, descriptions, sitemap |
| **LiteSpeed Cache** | Speed (Hostinger's server cache) |
| **WP Mail SMTP** | Emails actually arrive: setup in [THEME-V3-SETUP.md](THEME-V3-SETUP.md) §2 with `contact@vitalstack.co.in` |
| **Site Kit by Google** (optional) | Search Console + Analytics + AdSense in one place |

You don't need Contact Form 7: the theme's contact form works on its own
and sends to `contact@vitalstack.co.in`.

## 6. Write your first lesson (the important part)
**Tutorials → Add New Tutorial**:

1. **Title**: short, like W3Schools: `HTML Introduction`, `HTML Elements`,
   `HTML Attributes`. The subject name first, no year.
2. **Content**: explain one thing. Use the **Code** block (type `/code`)
   for examples. HTML and JavaScript examples automatically get a
   **Try it Yourself** button.
3. Right sidebar:
   - **Learning Paths** → tick the subject (e.g. *HTML*).
   - **Difficulty** → *Beginner*.
   - **Order** (in the *Summary/Page attributes* panel) → lesson number: 1, 2, 3…
   - **Excerpt** → one sentence about the lesson (shown on cards and in emails).
   - **Featured image** (optional).
4. **Publish**.

The lesson now appears in the top bar (under its subject), in the left
sidebar, on the homepage, and with Previous/Next buttons. At the end of a
subject's last lesson, "Next" moves on to the next subject automatically.

Suggested first HTML lessons, in order:
1. HTML Introduction 2. HTML Editors 3. HTML Basic Examples 4. HTML Elements
5. HTML Attributes 6. HTML Headings 7. HTML Paragraphs 8. HTML Text Formatting
9. HTML Links 10. HTML Images 11. HTML Lists 12. HTML Tables
13. HTML Forms 14. HTML Semantic Elements 15. HTML Project: Your First Web Page

## 7. Blog guides (AI and career articles)
**Posts → Add New** for longer guides that aren't part of a lesson series
("What are AI agents?", "Best free AI tools for students"). Pick one
category (Artificial Intelligence / AI Tools / AI Career / Programming).

## 8. Before AdSense
Follow [ADSENSE-APPROVAL.md](ADSENSE-APPROVAL.md). Short version: all 6
pages published, 30+ strong lessons/guides, Search Console set up, then
apply and paste your `ca-pub-…` ID in **Customize → VitalStack: Google AdSense**.

## Optional settings
- **Customize → VitalStack: Homepage**: change the big heading and subtitle.
- **Customize → Site Identity**: upload a logo and site icon (favicon).
- **Appearance → Menus → Topic Bar**: only if you want to control the dark
  bar yourself; otherwise it fills automatically from your subjects.
- To add a new subject (e.g. React): **Tutorials → Learning Paths → Add**,
  then give its lessons that path. It appears in the bar after the first lesson.
