# VitalStack roadmap

Principle: **fewer, better pages.** AdSense approves sites that are clearly
useful to a real reader, not sites with a lot of pages.

## Decision: stay on WordPress
Hostinger + WordPress + Yoast is the right stack for a content site run by
one person. A custom-coded site (Next.js etc.) would cost months and add
nothing AdSense cares about. This repo version-controls the **theme and the
content plan**. WordPress stays the CMS.

## Phase 0: pick a focus (1 day)
Choose **one** primary niche. Recommended, based on the existing strength:

> **"Learn AI and tech skills, explained simply"**: beginner-friendly
> tutorials, practical AI tool guides, and career guides.

Health and news become secondary or are removed (see Phase 1).
Proposed top-level structure (at most 4–5 sections):

| Section       | What goes here                                            |
|---------------|-----------------------------------------------------------|
| Learn to Code | Tutorials (HTML → CSS → JS → React; Java; SQL; APIs)      |
| AI Guides     | How AI works, AI agents, prompt engineering, AI tools     |
| AI Careers    | Skills, roadmaps, interview prep, salary guides           |
| Tools         | Interactive calculators/tools (see Phase 3)               |

## Phase 1: clean up (week 1–2)
1. **News:** unpublish or `noindex` the rewritten news items, and 301-redirect
   any with traffic (Search Console → Pages) to a related guide. Keep a
   news item only if it adds original analysis.
2. **Authors:** merge everything into real author accounts. Write a real
   author bio with a photo, background, and LinkedIn.
3. **Pages:** rewrite About (who, why, editorial process), Contact (real
   email, city), and add an **Editorial Policy** page.
4. **Health posts:** keep only ones you can source properly. Add references
   and a medical disclaimer, or remove them.
5. Fix titles like the `(48 characters)` one, and remove `(2026 Guide)` spam.
6. Theme: delete `single-tutorials111.php`, shrink `screenshot.png`, fix the Theme URI.

## Phase 2: rebuild content quality (weeks 2–8)
- Every article gets: original examples/code, screenshots, an FAQ, internal
  links to 3+ related posts, sources, author box, and a "last updated" date.
- Turn tutorials into **series** with next/previous navigation
  ("HTML → CSS → JavaScript → React" learning path). Series keep readers on
  the site longer than anything else.
- Pace: 2–3 deep articles a week beats 5 thin ones a day.
- Target: **30–40 strong articles** in the focus niche before reapplying.

## Phase 3: engagement features (theme work, weeks 3–10)
Done in theme v2: TOC with active-section highlight, reading progress bar,
read time, learning paths with lesson order + saved progress + next-lesson
CTA, code copy buttons with syntax highlighting, related posts, search modal,
dark mode, WhatsApp/LinkedIn/X sharing. Still to do: live code playground,
interactive tools.

Things that raise time-on-site:
- Table of contents + reading progress bar + estimated read time
- Tutorial learning paths with progress tracking and "Next lesson" CTA
- Code blocks with copy button and syntax highlighting
- "Try it" live code playground on tutorials
- Small interactive tools (e.g., AI salary estimator, prompt builder, cost
  calculator). Tools earn backlinks and repeat visits.
- Related posts, a better search experience, dark mode
- Core Web Vitals: lazy images, WebP, no render-blocking fonts

## Phase 4: reapply to AdSense (about week 8–10)
Checklist:
- [ ] 30+ original, in-depth articles in the focus niche
- [ ] No thin/rewritten news indexed
- [ ] About, Contact, Privacy, Terms, Editorial Policy complete
- [ ] Real author identity
- [ ] Clean navigation, no broken links, mobile-friendly
- [ ] Sitemap submitted in Search Console, pages indexed
- [ ] `ads.txt` in place after approval

## Phase 5: grow traffic and earnings (ongoing)
- Search Console keyword data drives new topics.
- Update top posts every quarter.
- Pinterest, YouTube Shorts and LinkedIn for tutorial snippets, plus an email newsletter.
- Ad placement: in-content ads after the intro and mid-article. Don't clutter above the fold.
- Realistic expectation: Indian-audience tech traffic earns roughly
  ₹50–₹300 per 1,000 pageviews. Income grows with traffic, so the
  focus is on traffic quality and articles people come back to.
