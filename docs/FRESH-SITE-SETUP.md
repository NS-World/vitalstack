# New WordPress site from scratch: step by step

For a brand-new WordPress install on Hostinger with the VitalStack theme.
Total time: about 1 hour. All lessons and guides are included in the theme.

## 1. Upload the theme (5 min)
1. WordPress admin → **Appearance → Themes → Add New → Upload Theme**.
2. Choose `vitalstack-theme-v4.8.0.zip` → **Install Now** → **Activate**
   (or **Replace current with uploaded** if an older VitalStack is installed).

## 2. Click the setup button (2 min)
1. **Appearance → VitalStack Setup** (a blue notice on the dashboard links there too).
2. Leave **"Delete WordPress sample content"** ticked.
3. Click **Create starter structure**.

It creates:

| What | Details |
|---|---|
| Subjects (learning paths) | HTML, CSS, JavaScript, SQL, Python, Java, Java OOP, Servlet & JSP, JPA, Hibernate, Spring, Spring Boot, Jmix, React, Angular, DSA, Interview Problems |
| Blog categories | Artificial Intelligence, AI Tools, AI Career, Programming |
| Difficulty levels | Beginner, Intermediate, Advanced |
| Published pages | Tutorials, Blog, Contact Us, Brain Games (`/games/`), Interview Problems (`/dsa-problems/`), Daily Challenge, Free Tools (5 tools), Cheat Sheets (11 printable sheets), Mock Tests (17 timed tests), Career Roadmaps (4 roadmaps) |
| Draft pages (review these) | About Us, Editorial Policy, Privacy Policy, Terms of Service, Disclaimer, Cookie Policy |
| Menus | Main menu (with Interview Qs, Games, Daily Challenge, Free Tools, Cheat Sheets, Mock Tests, Roadmaps) + footer Company and Legal menus, already placed |
| Settings | "Post name" permalinks, tagline, privacy page, sample content removed |

It's safe to click again later: it only adds what's missing.

A subject only shows up in the dark top bar and on the homepage **after
it has at least one published lesson**. An empty site shows only
AI Guides / AI Tools / Careers. That's normal.

## 3. Import the lessons and guides (2 min)
Same page, section **Starter content: lessons and guides**. It contains
**282 lessons** (HTML 20, CSS 20, JavaScript 25, SQL 15, Python 20, Java 16,
Java OOP 12, Servlet & JSP 8, JPA 9, Hibernate 7, Spring 9, Spring Boot 10,
Jmix 7, React 12, Angular 12, DSA 29, Interview Problems 51) and **13 guides**
(AI, AI Tools, AI Career, Programming), with code examples, exercises, interview
questions and summaries. Every lesson ends with a short quiz, and many include
live animated diagrams (play, step, try your own input) and hidden hints. Choose one option and click **Import starter content**:

| Option | What happens | When to choose it |
|---|---|---|
| **Import as drafts** (recommended) | Everything arrives as drafts | You want to read and personalise each piece, then publish |
| **Publish automatically, a few per day** | 2 per day (you choose 1–5), from tomorrow 09:00, HTML first, guides in between | You want a steady rhythm without daily work (about 5 months at 2/day) |
| **Publish everything now** | All 295 go live at once | Only if you've already reviewed the content |

- Safe to click again: anything that already exists is skipped, never overwritten.
- Subscribers are emailed when each item is published: drafts when you
  publish them, scheduled items as each goes live (the *Email subscribers…*
  box is ticked by default). Only **Publish everything now** stays silent, so
  nobody gets hundreds of emails at once.
- Scheduled items need WP-Cron. On Hostinger set up a real cron job
  ([THEME-V3-SETUP.md](THEME-V3-SETUP.md) §3) so they publish on time even
  when nobody visits the site.
- **Drafts are not visible on the site.** Subjects (HTML, CSS…) appear in the
  dark top bar and on the homepage only once their first lesson is
  **published**. After importing as drafts, use the green **Publish the
  imported items** box on the same page: "a few per day" (the first ones go
  live immediately) or "Publish all now".

**Important for AdSense:** Google rejected the old site for "low value
content". Before publishing, read each piece, run the examples, and add
something of your own (a tip, a screenshot, a better example). See
[CONTENT-GUIDE.md](CONTENT-GUIDE.md).

### Updating to v4.2 (free account extras and sign-up gift)
Upload the new zip → **Replace current with uploaded** → **LiteSpeed Cache → Purge All**.
Nothing else to set up. What changes for visitors:

| Free, no account | Free account needed |
|---|---|
| Every lesson, animation, quiz, roadmap, game, tool and cheat sheet page | Mock test answer review and "Revise these lessons" |
| Taking a mock test and seeing the score | Cheat sheet PDF download |
| | Progress on every device, certificates, leaderboard |

- After a visitor's third lesson a small card suggests a free account. It never
  covers the lesson, and "Not now" hides it for 3 days.
- Sign-up gift: the **30-Day Java Interview Plan** (PDF) on the reader
  dashboard and in the confirmation email. It's served only to signed-in
  readers from `data/gifts/` (rebuild it with `tools/build_gift.py`).

### Updating to v4.1 (Mock Tests and Career Roadmaps)
1. Upload `vitalstack-theme-v4.8.0.zip` → **Replace current with uploaded**.
2. **Appearance → VitalStack Setup → Create starter structure**. It adds the
   Mock Tests page with 17 tests, the Career Roadmaps page with 4 roadmaps,
   and their links in the main menu. Nothing else changes.
3. **LiteSpeed Cache → Purge All**.
4. Search Console → URL Inspection → request indexing for `/mock-tests/` and
   `/roadmaps/`.

The tests use the lesson quiz questions, so they update with the theme. The
intro text of each test and roadmap can be replaced from the page editor; leave
the page empty to keep the theme's text.

### Already have the site running (updating to v4.0)?
1. Upload `vitalstack-theme-v4.8.0.zip` → **Replace current with uploaded**.
2. **Appearance → VitalStack Setup → Create starter structure** again. It adds
   only what's missing: the 10 new learning paths (Java to Angular), the Daily
   Challenge, Free Tools and Cheat Sheets pages, and their menu links. It also
   puts the subjects in the right order in the top bar.
3. **Import starter content** again. Only the 102 new lessons are added;
   existing lessons are left untouched (your edits are safe).
4. Publish them with the green **Publish the imported items** box. "A few per
   day" is a good choice: fresh content every day keeps Google visiting.
5. **LiteSpeed Cache → Toolbox → Purge All**, so visitors get the new menus.

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

### LiteSpeed Cache settings for a fast site (5 min)

The theme is already light: fonts, icons and scripts come from your own
server, CSS and JS are minified, and lesson lists are cached. Page caching
is what turns a ~150 ms page into a ~20 ms one. In **LiteSpeed Cache**:

| Setting | Value | Why |
|---|---|---|
| Cache → Enable Cache | **ON** | Serves ready-made pages to visitors |
| Cache → Cache Logged-in Users | OFF | Signed-in readers see their own XP and progress |
| Cache → TTL → Default Public Cache TTL | 604800 (1 week) | Pages are purged automatically when you publish |
| Page Optimization → CSS Minify / Combine | **OFF** | Already minified by the theme; combining can break the theme picker |
| Page Optimization → JS Minify / Combine / Defer | **OFF** | Already minified and deferred by the theme |
| Page Optimization → Lazy Load Images | ON | Faster pages with screenshots |
| Image Optimization | ON (optional) | Smaller uploaded images |
| Browser → Browser Cache | ON | Repeat visits load instantly |
| Object Cache (if your plan has Redis or Memcached) | ON | Faster for signed-in readers |

Sign-in, sign-up and the contact form are automatically kept out of the
cache, so they keep working. After changing settings: **LiteSpeed Cache →
Toolbox → Purge All**. Also turn on **Hostinger → Websites → Performance →
CDN** if your plan includes it.

## 7. Adding more lessons later

Full guide with screenshots and templates: [CONTENT-GUIDE.md](CONTENT-GUIDE.md).
In short, **Tutorials → Add New Tutorial**: a short title (`HTML Iframes`), the
lesson text with **Code** blocks for examples, then in the sidebar tick the
**Learning Path**, choose **Difficulty**, set the **Lesson number** (or leave it
empty for "next"), write an **Excerpt**, and **Publish**. Guides are
**Posts → Add New Post** with one category.

## 8. Moving from the temporary domain to vitalstack.co.in

AdSense only reviews sites on a **real domain you own**. Do this before
applying (and ideally before publishing lots of content, so links and the
sitemap use the final address):

1. **Hostinger → Websites → your site → Connect domain** (or *Change domain*)
   and choose `vitalstack.co.in`. Follow Hostinger's DNS instructions; it can
   take up to 24 hours to work everywhere.
2. **Hostinger → Security → SSL**: install the free SSL certificate and turn on
   **Force HTTPS**.
3. **Settings → General**: both addresses must be `https://vitalstack.co.in`
   (Hostinger usually updates them automatically when you change the domain).
4. Replace old links stored in content: install **Better Search Replace**,
   search `indigo-shrew-998820.hostingersite.com`, replace with
   `vitalstack.co.in`, tick all tables, run a dry run, then run it for real.
   Delete the plugin afterwards.
5. **Settings → Reading**: make sure **"Discourage search engines from
   indexing this site"** is **unticked**.
6. **Settings → Permalinks → Save** (refreshes the URLs for certificates,
   the app manifest and the IndexNow key).
7. **LiteSpeed Cache → Purge All**, then add the domain in **Google Search
   Console**, verify it, and submit `https://vitalstack.co.in/sitemap_index.xml`
   (Yoast) or `/wp-sitemap.xml`.

## 9. Before AdSense
Follow [ADSENSE-APPROVAL.md](ADSENSE-APPROVAL.md). Short version: all 6
pages published, 30+ reviewed lessons/guides published, Search Console set up, then
apply and paste your `ca-pub-…` ID in **Customize → VitalStack: Google AdSense**.

## Settings worth a look (Appearance → Customize)

| Section | Setting | Recommended |
|---|---|---|
| Colours & theme | Default theme / accent colour | Day (light) and green; readers can switch with the 🎨 button |
| VitalStack: Accounts & Email | Subscriber emails | **One email for every new post or lesson** (default); choose the weekly digest if you publish many items a day |
| VitalStack: Accounts & Email | Allow comments and questions | Off at first; turn on when you have time to moderate |
| VitalStack: Google AdSense | AdSense publisher ID | Paste `ca-pub-…` after approval |
| VitalStack: Google AdSense | In-article / Display ad unit ID | Optional; without them Auto ads are used |

Ads are never shown on the account, certificate, games and daily challenge
pages, so learners aren't distracted where they interact most, and AdSense
policies on accidental clicks are respected.

## Optional settings
- **Customize → VitalStack: Homepage**: change the big heading and subtitle.
- **Customize → Site Identity**: upload a logo and site icon (favicon).
- **Appearance → Menus → Topic Bar**: only if you want to control the dark
  bar yourself; otherwise it fills automatically from your subjects.
- To add a new subject (e.g. Kotlin): **Tutorials → Learning Paths → Add**,
  then give its lessons that path. It appears in the bar after the first lesson.
