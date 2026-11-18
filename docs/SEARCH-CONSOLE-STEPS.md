# Google Search Console: step by step (after uploading theme 4.20.6)

Your export (18 Nov) shows: Indexed 640, Not indexed 997 (redirect 202, 404 167, noindex 468, 5xx 13,
crawled-not-indexed 114, others 31). Impressions per day have been rising (about 150–360).

## Day 0: deploy (15 minutes)

1. WordPress → Appearance → Themes → Add New → Upload `vitalstack-theme-v4.20.6.zip` → **Replace current**.
2. LiteSpeed Cache → Toolbox → **Purge All**.
3. Open `https://vitalstack.co.in/wp-sitemap.xml` in the browser. You should see `posts-post`, `posts-page`, `posts-tutorials`,
   `taxonomies-category` and `taxonomies-tutorial_category`. Open the tutorials one: there should be no premium (locked) lesson in it.
4. Visit wp-admin once. Five minutes later the theme sends every sitemap URL to Bing via IndexNow (automatic, once).

## Day 0: Search Console (20 minutes)

1. **Sitemaps** (left menu):
   - Remove every old sitemap (e.g. `sitemap_index.xml`, `post-sitemap.xml`, old-site ones).
   - Add `wp-sitemap.xml` → Submit. Status should become "Success".
2. **URL Inspection** (top search bar) → paste a URL → **Request indexing**. Google allows about 10 a day. Day 0 list:
   - `https://vitalstack.co.in/`
   - `/tutorials/`
   - `/dsa-problems/`
   - `/tools/`
   - `/tools/python-online-compiler/`
   - `/playground/`
   - `/study-plan/`
   - `/roadmaps/`
   - `/cheat-sheets/`
   - `/pricing/`
3. **Pages → each reason → "Validate fix"**: only for these three.

   | Reason | Validate? | Why |
   |---|---|---|
   | Excluded by 'noindex' | **No** | Locked lessons, account, tags: intentionally noindex. |
   | Page with redirect | **No** | Redirects are correct; validation always "fails". |
   | Not found (404) | **No** | Old-site pages; Google drops them in 1–3 months. |
   | Server error (5xx) | **Yes** | After the test below. |
   | Duplicate without user-selected canonical | **Yes** | Fixed in 4.20.6. |
   | Crawled – currently not indexed | **Yes** | Once, after the deploy. |

   For 5xx: open the reason → click a URL → **Test live URL**. If it's green, click "Validate fix".

## Send me these exports (they contain the URL lists)

In Pages, click each reason. The table at the bottom lists the URLs. Use **Export → Download CSV** for:
- **Not found (404)**: old URLs that still get clicks get a 301 redirect to the matching new lesson.
- **Server error (5xx)**: to see which pages are slow.
- **Crawled – currently not indexed**: to strengthen those pages with better internal links.

(The file sent on 18 Nov is the summary page export, which has counts but no URLs.)

## Days 1–14: 10 minutes a day

- Request indexing for 10 more pages a day: the most useful free lessons (Python, Java, SQL, DSA), and the tools.
- Search Console → **Performance**: look at queries with many impressions but a low position (8–20). Those pages are close to page 1.
  Add one internal link to them from a related lesson.

## Bing (once, 10 minutes)

bing.com/webmasters → Sign in → **Import from Google Search Console** → done. Bing feeds ChatGPT search,
Copilot, DuckDuckGo and Yahoo.

## What to expect

- Sitemap "Success" in 1–2 days. The noindex count stops growing.
- 404 and redirect counts fall slowly over 1–3 months (normal after replacing an old site).
- Indexed pages and impressions keep rising as new pages are crawled. Apply for AdSense when daily clicks are steady (2–4 weeks).
