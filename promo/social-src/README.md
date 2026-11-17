# Social post sources

HTML sources for the weekly carousels and reels (images and videos are not stored in git).

- `week1-day1-api-waiter.html`: Code in Real Life #1 (self-contained).
- `week1.html` + `style.css`: week 1, days 2–6. Each `<section class="s" data-name="…">` is one 1080×1350 slide.
- `CAPTIONS-WEEK1.md`: captions, alt text, DM links, LinkedIn/dev.to/Threads copy.

Render slides (Playwright + Chromium):
```bash
node promo/social-src/render2.js "$PWD/promo/social-src/week1.html" /path/to/output-folder
```
Make a reel from slides (ffmpeg; the audio file is any original VitalStack track, e.g. a reel from the social pack):
```bash
python3 promo/social-src/make_reel.py out.mp4 music.mp4 slide1.png:3 slide2.png:4
```
