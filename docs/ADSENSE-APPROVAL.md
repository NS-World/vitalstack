# Getting AdSense approval: the complete checklist

Do the stages in order. Don't apply again until every box in stages 1–4
is ticked; a second rejection for the same reason costs more weeks.

Realistic timeline: **8–12 weeks** of steady work, then 1–4 weeks of review.

Google doesn't publish a minimum number of articles or visitors. The
targets below ("30+ articles") are what reliably works for new content
sites, not an official rule. What Google does say is that the site must
offer **original, useful content** and follow the
[AdSense Program policies](https://support.google.com/adsense/answer/48182).

---

## Stage 1: Remove what caused the rejection (week 1)

These are the problems found in the September 2026 audit
([AUDIT-2026-09.md](AUDIT-2026-09.md)).

- [ ] **Fake authors gone.** Delete the 11 invented author accounts and
      attribute their posts to your one pen-name account
      ([THEME-V2-SETUP.md](THEME-V2-SETUP.md) step 2). A pen name is fine;
      invented people and invented credentials are not.
- [ ] **Fake claims gone.** No "18k readers", "expert-reviewed",
      "fact-checked by doctors" anywhere. The new theme has none; check
      your pages and posts too.
- [ ] **False first-person stories fixed.** 16 posts say "I tested…" /
      "my experience…". Keep these only if they're true for you; otherwise
      rewrite neutrally. One post starts "Hi, I'm Ananya…". Rewrite it.
      (List: [`content/cleanup-list.csv`](../content/cleanup-list.csv), *flags* column.)
- [ ] **News section noindexed.** Customizer → *VitalStack: Content Focus*
      → tick both boxes. 195 rewritten news items are the biggest "low value
      content" signal.
- [ ] **Health posts.** 19 posts in Health/Mental Health/Fitness: set to
      Draft (or keep only ones with a clear tech angle and real sources).
- [ ] **Junk pages removed.** Delete WordPress's default *Sample Page*.
      You have two privacy pages (`privacy-policy` and `privacy-policy-2`);
      keep one, 301-redirect the other.
- [ ] **Title mistakes fixed.** Remove "(48 characters)" from the NLP post,
      and "(2026 Guide)"-style suffixes from titles.

## Stage 2: Trust pages (week 1)

AdSense reviewers check who runs the site and how to reach you.

- [ ] **About Us**: text from [`content/pages/about.md`](../content/pages/about.md),
      with your pen name and one honest line about you.
- [ ] **Editorial Policy** page (slug `editorial-policy`):
      [`content/pages/editorial-policy.md`](../content/pages/editorial-policy.md).
      It openly says AI helps with drafts and a person checks everything,
      which is allowed. Hiding it is what hurts.
- [ ] **Contact Us**: form works and the email `contact@vitalstack.co.in`
      receives it (send yourself a test). SMTP set up as in
      [THEME-V3-SETUP.md](THEME-V3-SETUP.md) §2.
- [ ] **Privacy Policy** mentions: Google AdSense and its cookies
      (third-party vendors including Google use cookies to serve ads based
      on prior visits; users can opt out at
      https://adssettings.google.com), Google Analytics if used, and reader
      accounts (paragraph in THEME-V3-SETUP §7).
- [ ] **Terms**, **Disclaimer**, **Cookie Policy** linked in the footer.
- [ ] Author profile has a 2–3 line honest bio (Users → Profile).

## Stage 3: Content (weeks 2–10): the part that decides approval

- [ ] **30+ strong, original articles** in the focus area (coding
      tutorials + AI guides), not counting news or health.
- [ ] Each one passes this bar:
  - answers one clear question a beginner actually searches for
  - 1,000+ words where the topic needs it (don't pad short answers)
  - your own examples: code you ran, screenshots you took, a small exercise
  - no copied or lightly reworded text from other sites
  - headings, a short intro that answers the question early, and an FAQ if useful
  - 3+ links to related VitalStack lessons and 1–2 links to official docs
  - title without a year ("JavaScript Arrays: Beginner Guide", not "(2026)")
- [ ] **Learning paths filled in order**: every tutorial has its *Order*
      number set; each path has a description.
- [ ] **No empty or thin pages** anywhere in the menu: no empty categories,
      no "coming soon", no category with 1 post in the main menu.
- [ ] Publishing pace: 2–3 good articles a week. Steady beats bursts.

## Stage 4: Technical health (week 1, recheck before applying)

- [ ] Theme v3.1 uploaded; **Settings → Permalinks → Save** done.
- [ ] Site opens fast on mobile (test 3 pages at https://pagespeed.web.dev;
      aim for "Good" Core Web Vitals). LiteSpeed Cache on, images WebP.
- [ ] HTTPS everywhere, no mixed-content warnings.
- [ ] No broken links (Search Console → *Pages*, or a broken-link plugin run once).
- [ ] **Google Search Console** set up, `sitemap_index.xml` submitted, and
      most of your good articles show as **Indexed**. If Google hasn't
      indexed your pages, AdSense can't evaluate them.
- [ ] Some real visitors from search. There's no official minimum, but a
      site with zero organic traffic often gets "low value content" again.
- [ ] Navigation works on phone: menu, topic bar, search, footer links.
- [ ] **Brand looks complete.** Theme v3.7+ adds the logo, favicon, phone icons and
  a share image automatically. If an old *Site Icon* is set in
  Appearance → Customize → Site Identity, remove it or replace it with
  `assets/brand/icon-512.png`, so the browser tab shows the new icon.
- [ ] **Yoast → Settings → Site representation:** Organization name "VitalStack",
  logo = `logo.png`. **Yoast → Settings → Site basics → Site image** = `share.png`
  (shown when a page is shared on WhatsApp, Facebook or LinkedIn).

## Stage 5: Apply

1. **AdSense account**: use your **real legal name and address**. Payments
   and PIN verification letters go there. This stays private; the
   public site can keep your pen name.
2. Add site `vitalstack.co.in`.
3. **Connect the site** (one method only):
   - **Theme:** Appearance → Customize → *VitalStack: Google AdSense* →
     paste your publisher ID (`ca-pub-…`). The theme adds the AdSense code
     to every page and serves `https://vitalstack.co.in/ads.txt`
     automatically, **or**
   - **Site Kit** plugin (already installed on your site) → connect AdSense.
     Then leave the theme field empty so the code isn't added twice.
4. Open `https://vitalstack.co.in/ads.txt` in a browser. It should show
   `google.com, pub-…, DIRECT, f08c47fec0942fa0`. If your old "App-ads.txt"
   plugin or a real `ads.txt` file shows something else, fix or remove it.
5. AdSense → **Privacy & messaging** → create the *European regulations*
   message (Google's certified consent banner, needed for EU/UK visitors).
6. Click **Request review**. Then **don't** redesign the site, move it,
   or delete lots of content during review. Keep publishing normally.

Review usually takes days to 2–4 weeks.

## If it's rejected again

Read the exact reason in AdSense → *Sites*:

| Reason | What it means here | Fix |
|---|---|---|
| Low value content | Not enough original, helpful pages yet | Stage 3: more/better articles; check news is noindexed |
| Site down or unavailable | Reviewer couldn't load pages | Check hosting uptime, remove "maintenance mode", disable aggressive firewall/bot blocking for Google |
| Policy violations | Copied content, misleading info, prohibited topics | Find and fix the specific pages, then request review |
| Not found / no content | Code or ads.txt missing, or pages not indexed | Stage 5 steps 3–4, Search Console indexing |

Fix the cause, wait at least **2 weeks** of continued publishing, then
request review again. Don't reapply the same day.

## After approval: keep the account safe

- Turn on **Auto ads**, start with a moderate ad load. Too many ads above
  the fold hurts readers and rankings.
- **Never click your own ads**, never ask others to click, never buy
  traffic or use "traffic exchange" sites. Invalid clicks get accounts
  closed permanently.
- No ads on pages without content (the theme already keeps ads off
  `/account/`, search results and 404 pages).
- Watch AdSense → *Policy center* every week.
- Keep doing Stage 3. Earnings grow with search traffic; updating your
  best 20 articles every 6 months is worth more than 20 new thin ones.
