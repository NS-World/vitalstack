---
title: CSS Text and Fonts
slug: css-text-and-fonts
subject: css
lesson: 10
difficulty: beginner
excerpt: Font families and fallbacks, web fonts, size, weight and line height, alignment, decoration, transform and spacing, plus rules for readable text.
---

Most of a website is text, so typography makes the biggest difference to how professional and readable it feels. In this lesson you'll choose fonts, size them, and control spacing, alignment and decoration.

## font-family and fallbacks

```css
body {
  font-family: "Inter", "Segoe UI", Roboto, Arial, sans-serif;
}
```

You list several fonts. The browser uses the **first one available** on the reader's device and falls back to the next. Always end with a **generic family**:

- `sans-serif`: clean fonts without small strokes (Arial, Roboto). Good for screens.
- `serif`: fonts with small strokes (Georgia, Times). Good for long articles.
- `monospace`: every character the same width. For code.
- `system-ui`: the operating system's own font. Fast and native-looking.

Put font names with spaces in quotes.

## Web fonts

To use a font readers may not have installed, load it from a service like Google Fonts:

```html
<head>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: "Inter", sans-serif; }
  </style>
</head>
```

Every font file you load slows the page a little. Use one or two families and only the weights you need. `display=swap` shows text in a fallback font immediately, then swaps when the web font arrives.

## Size, weight and style

```html
<style>
  body { font-family: system-ui, sans-serif; }
  .big { font-size: 2rem; font-weight: 700; }
  .normal { font-size: 1rem; font-weight: 400; }
  .light { font-size: 0.875rem; font-weight: 300; color: #64748b; }
  .italic { font-style: italic; }
</style>
<p class="big">Bold, 2rem (32px)</p>
<p class="normal">Normal, 1rem (16px)</p>
<p class="light">Light, 0.875rem (14px)</p>
<p class="italic">Italic text</p>
```

- `font-size`: use `rem` so text respects the reader's browser settings. 16px (1rem) is a good minimum for body text.
- `font-weight`: 100–900; `400` is normal, `700` is bold. The font must include that weight.
- `font-style`: `normal` or `italic`.

## Line height: the readability secret

```html
<style>
  .tight { line-height: 1.1; }
  .comfortable { line-height: 1.7; }
  p { max-width: 45ch; font-family: Georgia, serif; }
</style>
<p class="tight">Tight lines are hard to read because your eyes lose their place when jumping from the end of one line to the start of the next.</p>
<p class="comfortable">Comfortable spacing gives each line room to breathe, so long paragraphs feel easy instead of heavy.</p>
```

For body text, use a unitless `line-height` between **1.5 and 1.8**. Headings can be tighter (1.1–1.3).

## Text alignment

```css
h1 { text-align: center; }
p  { text-align: left; }     /* default for English */
.price { text-align: right; }
```

`justify` stretches lines to fill the width, but it creates uneven gaps between words on the web. Keep long text left-aligned.

## Decoration, transform and spacing

```html
<style>
  a { text-decoration: none; color: #2563eb; }
  a:hover { text-decoration: underline; text-underline-offset: 3px; }
  .label { text-transform: uppercase; letter-spacing: 0.08em; font-size: 0.75rem; font-weight: 600; }
  .old { text-decoration: line-through; color: #94a3b8; }
</style>
<p><a href="#">A link that underlines on hover</a></p>
<p class="label">Section label</p>
<p>Price: <span class="old">₹999</span> ₹499</p>
```

- `text-decoration`: `none`, `underline`, `line-through`.
- `text-transform`: `uppercase`, `lowercase`, `capitalize`. Keep the real text in normal case in the HTML; screen readers may spell out ALL-CAPS text letter by letter.
- `letter-spacing` adds space between letters; small uppercase labels look better with a little extra.
- `word-spacing` adds space between words.

## Text shadow and overflow

```css
.hero-title { text-shadow: 0 2px 6px rgb(0 0 0 / 0.4); }

.one-line {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;   /* shows "…" when text is cut */
}
```

## A readable article style

```html
<style>
  .post {
    font-family: Georgia, serif;
    font-size: 1.125rem;
    line-height: 1.75;
    color: #1f2937;
    max-width: 65ch;
    margin: 0 auto;
  }
  .post h2 {
    font-family: system-ui, sans-serif;
    font-size: 1.5rem;
    line-height: 1.25;
    margin-top: 2em;
  }
</style>
<article class="post">
  <h2>Why typography matters</h2>
  <p>Good typography is invisible. Readers don't notice the font size, the line height or the width of the column; they just find the text easy and pleasant to read, and they stay longer.</p>
</article>
```

## Common mistakes

- **Body text smaller than 16px.**
- **No fallback fonts**, or no generic family at the end.
- **Loading many font weights** you never use.
- **Line height of 1** on paragraphs.
- **Very long lines** of text across a wide screen. Limit to about 60–75 characters.

## Exercise

1. Load one Google Font and use it for headings, with a system font for body text.
2. Set body text to 1.125rem with line height 1.7 and a max width of 65ch.
3. Create a small uppercase "category label" with letter spacing.
4. Make links lose their underline, and get it back on hover.

## Summary

- `font-family` takes a list with fallbacks, ending in a generic family.
- Use `rem` for sizes, 400/700 weights, and line height around 1.6 for body text.
- `text-align`, `text-decoration`, `text-transform` and `letter-spacing` fine-tune text.
- Readable text = good size, generous line height, limited line length, strong contrast.

Next, you'll learn the `display` property, which controls how elements flow on the page.
