---
title: CSS Width and Height
slug: css-width-and-height
subject: css
lesson: 9
difficulty: beginner
excerpt: Set sizes with px, %, rem, vw and vh, use max-width and min-height for flexible layouts, and control overflowing content.
---

Sizing elements well is what separates layouts that break on phones from layouts that adapt to any screen. This lesson covers `width` and `height`, the units you can use, and the min and max properties that make sizes flexible.

## Default sizes

- **Block elements** (div, p, section) are as wide as their container and as tall as their content.
- **Inline elements** are only as wide as their content, and ignore width and height.

Most of the time, the defaults are what you want. Set sizes only when you need to.

## Setting width and height

```html
<style>
  .box {
    width: 300px;
    height: 120px;
    background: #dbeafe;
    border: 2px solid #2563eb;
  }
</style>
<div class="box">300 × 120 pixels</div>
```

## CSS units

| Unit | Meaning | Good for |
|---|---|---|
| `px` | Pixels | Borders, shadows, small fixed sizes |
| `%` | Percentage of the parent's size | Fluid widths |
| `rem` | Multiple of the root font size (usually 16px) | Font sizes, spacing |
| `em` | Multiple of the element's own font size | Padding that grows with text |
| `vw` / `vh` | 1% of the browser window's width / height | Full-screen sections |
| `ch` | Width of the "0" character | Comfortable text line lengths |

`rem` is especially useful: if a user increases their browser's default font size, everything sized in `rem` grows with it, which is good for accessibility.

```css
h1 { font-size: 2rem; }      /* 32px by default */
.card { padding: 1.5rem; }   /* 24px */
```

## Percentages

```html
<style>
  .parent { width: 400px; background: #f1f5f9; padding: 8px; }
  .child  { width: 50%; background: #93c5fd; }
</style>
<div class="parent">
  <div class="child">50% of 400px = 200px</div>
</div>
```

A percentage width is relative to the **parent's** content width.

## max-width: the most useful size property

A fixed width breaks on small screens: a 900px box on a 375px phone causes horizontal scrolling. `max-width` fixes this:

```html
<style>
  .article {
    max-width: 65ch;
    margin: 0 auto;
    padding: 0 16px;
    font-family: Georgia, serif;
    line-height: 1.7;
  }
</style>
<div class="article">
  <p>This column is never wider than about 65 characters, which is comfortable to read. On a narrow phone screen it simply shrinks to fit, with no horizontal scrolling. Resize the result pane to see it adapt.</p>
</div>
```

The box is as wide as possible **up to** the limit. This one property makes a layout work on everything from phones to wide monitors.

The same idea makes images responsive:

```css
img {
  max-width: 100%;
  height: auto;
}
```

Images can shrink to fit their container but never grow beyond their real size, and `height: auto` keeps their proportions.

## min-height instead of height

Fixed heights are risky: if the content grows (longer text, a translation, bigger font), it overflows the box. Prefer `min-height`, which lets the box grow:

```html
<style>
  .fixed { height: 60px; background: #fecaca; margin-bottom: 30px; }
  .flexible { min-height: 60px; background: #bbf7d0; }
</style>
<div class="fixed">Fixed height: this long text doesn't fit in sixty pixels, so it spills out of the box and overlaps the next element when the box is narrow. That's the problem.</div>
<div class="flexible">min-height: at least 60px, but this box grows as tall as its content needs, so nothing ever spills out.</div>
```

A full-screen hero section is a classic use: `min-height: 100vh;`.

## Overflow

When content is bigger than its box, `overflow` decides what happens:

```css
.a { overflow: visible; } /* default: content spills out */
.b { overflow: hidden; }  /* extra content is cut off */
.c { overflow: auto; }    /* scrollbars appear only if needed */
```

```html
<style>
  .scroll {
    height: 90px;
    overflow: auto;
    border: 1px solid #94a3b8;
    padding: 8px;
  }
</style>
<div class="scroll">
  <p>Line one</p><p>Line two</p><p>Line three</p><p>Line four</p><p>Line five</p>
</div>
```

`overflow: auto` is perfect for wide code blocks and tables on small screens. Use `overflow-x` or `overflow-y` to control just one direction.

## aspect-ratio

```css
.video-box {
  width: 100%;
  aspect-ratio: 16 / 9;
}
```

`aspect-ratio` keeps a box's proportions as its width changes. Great for video embeds and image placeholders.

## Common mistakes

- **Fixed pixel widths** on large elements, which cause horizontal scrolling on phones. Use `max-width`.
- **Fixed heights on text boxes**, which overflow when content grows. Use `min-height`.
- **`height: 100%` that does nothing**, because the parent has no defined height.
- **Forgetting `height: auto`** when making images responsive, which squashes them.

## Exercise

1. Make a reading column with `max-width: 65ch` centred on the page.
2. Make all images in your profile page responsive.
3. Build a full-screen welcome section with `min-height: 100vh`.
4. Put a long table inside a div with `overflow-x: auto`.

## Summary

- Blocks fill their container's width by default; inline elements ignore width and height.
- Use `rem` for text and spacing, `%` and `vw` for fluid sizes, `px` for small fixed details.
- `max-width` and `min-height` make layouts flexible; avoid fixed widths and heights.
- `overflow` controls content that doesn't fit.

Next, you'll style text and fonts.
