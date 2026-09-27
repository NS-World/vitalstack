# Theme v2: setup checklist

Do these steps in order. Allow about 1–2 hours in total.

## 0. Backup first (5 min)
Hostinger hPanel → **Websites → Backups**: create a backup. Also keep the
old theme zip (`vitalstack_v2_updated.zip`) somewhere safe. If anything
breaks, you can switch back in one click from Appearance → Themes.

## 1. Upload the new theme (5 min)
1. Download `vitalstack-theme-v2.zip` (or zip `wp-content/themes/vitalstack/` from this repo).
2. WordPress admin → **Appearance → Themes → Add New → Upload Theme** → choose the zip.
3. WordPress asks "replace current with uploaded?" → **Replace**.
4. **Settings → Permalinks → Save** (no changes, just save; refreshes URLs).

Nothing is deleted: posts, news, tutorials, pages, Yoast data, and menus all stay.

## 2. Set up your pen name (15 min)
You don't need to reveal your identity. Use **one consistent pen name**
for everything. A believable first name + surname, or just a first name,
is fine. What's *not* fine is inventing fake credentials or several fake
people.

1. **Users → Add New**: username e.g. `aarav`, role **Administrator** (or
   use your existing account).
2. **Users → Profile** for that account:
   - *First/Last name*: your pen name. *Display name publicly as*: pick it.
   - *Biographical Info*: 2–3 honest lines. Example:
     > I'm a self-taught developer who writes the guides I wish I'd had when
     > I started. I test every code example before publishing, and I use AI
     > tools to help research and draft. Every article is checked and
     > edited by me.
3. For **each fake author** (michaelreynolds, sarahmitchell, jamesthompson,
   davidharrison, elenabrooks, alexrivera, priyasharma, nehaSingh,
   rohanKapoor, vikrammalhotra, ananyapatel):
   **Users → hover → Delete → "Attribute all content to:" your pen-name account → Confirm.**
   Posts are *not* deleted, they move to you.

The theme shows initials (e.g. "AK") instead of a photo, so no personal
photo or Gravatar is needed.

## 3. Menus (10 min)
**Appearance → Menus**:
- Create a menu **Main (v2)**:
  - Tutorials (page) → sub-items: Frontend Development, Backend Development, Database, Programming Fundamentals (from *Learning Paths*)
  - AI Guides (category *Artificial Intelligence*)
  - AI Tools (category)
  - Careers (category *AI Career*)
  - About (page)
- Menu Settings → Display location: **Primary Navigation**. Save.
- Assign your existing *Company* menu to **Footer — Company** and *Legal* to **Footer — Legal**.
  In *Company*, remove "News" and add the new **Editorial Policy** page (step 5).
- Set **Footer — Topics** to *no menu* (untick the old *Categories Menu*). The theme then lists your learning paths automatically.

## 4. Lesson order in learning paths (5 min)
Open each tutorial → right sidebar **Order** (under "Tutorial" / "Page Attributes"):

| Path | Lesson 1 | Lesson 2 | Lesson 3 | Lesson 4 |
|---|---|---|---|---|
| Frontend Development | HTML | CSS | JavaScript | React |
| Database | MySQL | MongoDB | | |
| Programming Fundamentals | What is Programming? | OOP Concepts in Java | | |
| Backend Development | Java | REST API | | |

Also fill in each path's **Description** (Tutorials → Learning Paths → edit).
It appears on the path page.

## 5. Pages (30 min)
- **About Us**: replace the page text with [`content/pages/about.md`](../content/pages/about.md).
  The template adds "How we create content", the author box, and contact links automatically.
- **Editorial Policy**: Pages → Add New, title *Editorial Policy*,
  slug `editorial-policy`, paste [`content/pages/editorial-policy.md`](../content/pages/editorial-policy.md).
- **Contact Us**: Appearance → Customize → *VitalStack: Contact Page*:
  set the Contact Form 7 form ID and your email. Then shorten the page text
  (the template already has the heading and intro).
- **Privacy Policy**: make sure it mentions Google AdSense cookies (it
  already does if it came from a generator; check it).

## 6. Focus switches (2 min)
Appearance → Customize → **VitalStack: Content Focus**:
- ✅ *Hide News from homepage, search and related posts*
- ✅ *Ask Google not to index News pages (noindex)*

This keeps the 195 news items reachable for visitors but tells Google
not to judge the site on them. It also removes them from the Yoast sitemap.

## 7. Quick content fixes (30 min)
See [`content/cleanup-list.csv`](../content/cleanup-list.csv) (open in
Google Sheets or Excel). Start with the rows that have **flags**:
- `NLP Basics: How Machines Understand Language (48 characters)`: remove "(48 characters)".
- `How to Build a Modern Portfolio Website Using React`: starts with "Hi, I'm Ananya…". Rewrite the intro in your own voice.
- 16 posts with first-person "I tested / my experience" claims: keep them only if they're true for *you*; otherwise rewrite neutrally ("A common approach is…").
- 19 health posts: set to Draft, or keep only the ones with a clear AI/tech angle and real sources.

## 8. Cookie consent for ads
The theme shows a small, honest cookie notice. When AdSense is approved,
enable **AdSense → Privacy & messaging → European regulations message**
(Google's certified consent banner) for EU/UK visitors.

## 9. Check
- Open the site on your phone: home, a tutorial, a blog post.
- Try the dark-mode button (moon icon) and search (press `/` on desktop).
- Search Console → **Sitemaps** → resubmit `sitemap_index.xml`.
