# New WordPress site from scratch: step by step

For a brand-new WordPress install on Hostinger with the VitalStack theme.
Total time: about 1 hour. All lessons and guides are included in the theme.

## 1. Upload the theme (5 min)
1. WordPress admin → **Appearance → Themes → Add New → Upload Theme**.
2. Choose `vitalstack-theme-v3.4.zip` → **Install Now** → **Activate**
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

## 3. Import the lessons and guides (2 min)
Same page, section **Starter content: lessons and guides**. It contains
**100 lessons** (HTML 20, CSS 20, JavaScript 25, SQL 15, Python 20) and
**13 guides** (AI, AI Tools, AI Career, Programming), with code examples,
exercises and summaries. Choose one option and click **Import starter content**:

| Option | What happens | When to choose it |
|---|---|---|
| **Import as drafts** (recommended) | Everything arrives as drafts | You want to read and personalise each piece, then publish |
| **Publish automatically, a few per day** | 2 per day (you choose 1–5), from tomorrow 09:00, HTML first, guides in between | You want a steady rhythm without daily work (about 8 weeks at 2/day) |
| **Publish everything now** | All 113 go live at once | Only if you've already reviewed the content |

- Safe to click again: anything that already exists is skipped, never overwritten.
- Bulk imports don't email subscribers. With "a few per day" you can tick
  *Email subscribers as each scheduled item goes live*.
- Scheduled items need WP-Cron. On Hostinger set up a real cron job
  ([THEME-V3-SETUP.md](THEME-V3-SETUP.md) §3) so they publish on time even
  when nobody visits the site.
- Subjects appear in the dark top bar and on the homepage once their first
  lesson is published.

**Important for AdSense:** Google rejected the old site for "low value
content". Before publishing, read each piece, run the examples, and add
something of your own (a tip, a screenshot, a better example). See
[CONTENT-GUIDE.md](CONTENT-GUIDE.md).

## 4. Review and publish the 6 draft pages (20 min)
The setup page lists them with edit links. For each one:
1. Read it. Change anything that isn't true for you.
2. **About Us**: replace `[one honest line about you…]` with your own line.
3. Click **Publish**.

These pages matter for AdSense: reviewers check them.

## 5. Your author profile (5 min)
**Users → Profile**:
- First name / nickname = your pen name → *Display name publicly as* = pen name.
- *Biographical Info*: 2–3 honest lines (shown under every article).

## 6. Plugins (15 min)
Install only these (Plugins → Add New):

| Plugin | Why |
|---|---|
| **Yoast SEO** (or Rank Math) | Titles, descriptions, sitemap |
| **LiteSpeed Cache** | Speed (Hostinger's server cache) |
| **WP Mail SMTP** | Emails actually arrive: setup in [THEME-V3-SETUP.md](THEME-V3-SETUP.md) §2 with `contact@vitalstack.co.in` |
| **Site Kit by Google** (optional) | Search Console + Analytics + AdSense in one place |

You don't need Contact Form 7: the theme's contact form works on its own
and sends to `contact@vitalstack.co.in`.

## 7. Adding more lessons later

Full guide with screenshots and templates: [CONTENT-GUIDE.md](CONTENT-GUIDE.md).
In short, **Tutorials → Add New Tutorial**: a short title (`HTML Iframes`), the
lesson text with **Code** blocks for examples, then in the sidebar tick the
**Learning Path**, choose **Difficulty**, set the **Lesson number** (or leave it
empty for "next"), write an **Excerpt**, and **Publish**. Guides are
**Posts → Add New Post** with one category.

## 8. Before AdSense
Follow [ADSENSE-APPROVAL.md](ADSENSE-APPROVAL.md). Short version: all 6
pages published, 30+ reviewed lessons/guides published, Search Console set up, then
apply and paste your `ca-pub-…` ID in **Customize → VitalStack: Google AdSense**.

## Optional settings
- **Customize → VitalStack: Homepage**: change the big heading and subtitle.
- **Customize → Site Identity**: upload a logo and site icon (favicon).
- **Appearance → Menus → Topic Bar**: only if you want to control the dark
  bar yourself; otherwise it fills automatically from your subjects.
- To add a new subject (e.g. React): **Tutorials → Learning Paths → Add**,
  then give its lessons that path. It appears in the bar after the first lesson.
