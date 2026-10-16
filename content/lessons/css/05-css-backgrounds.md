---
title: CSS Backgrounds
slug: css-backgrounds
subject: css
lesson: 5
difficulty: beginner
excerpt: Background colours, images, repeat, position, size, cover and contain, gradients and the background shorthand, with a hero banner example.
---

Backgrounds turn plain boxes into banners, cards and sections. In this lesson you'll use colours, images and gradients as backgrounds and control exactly how images are sized and placed.

## Background colour

```html
<style>
  .box { background-color: #e0f2fe; padding: 20px; }
</style>
<div class="box">A light blue box</div>
```

## Background images

```html
<style>
  .pattern {
    background-image: url("https://picsum.photos/id/1080/120/120");
    height: 240px;
  }
</style>
<div class="pattern"></div>
```

`url()` takes the path to the image. By default the image **repeats** in both directions to fill the box. That's useful for patterns but usually not what you want for photos.

Unlike `<img>`, a background image has no alt text and is invisible to screen readers. Use backgrounds only for decoration; if the image matters to the content, use an `<img>` element.

## background-repeat

```css
.a { background-repeat: no-repeat; } /* show it once */
.b { background-repeat: repeat-x; }  /* only horizontally */
.c { background-repeat: repeat-y; }  /* only vertically */
```

## background-position

```css
.hero { background-position: center; }
.logo-corner { background-position: right bottom; }
.custom { background-position: 20px 40px; }  /* x then y */
```

You can use keywords (`top`, `bottom`, `left`, `right`, `center`), lengths or percentages.

## background-size: cover and contain

This is the property you'll use most:

```html
<style>
  .frame {
    width: 260px;
    height: 160px;
    border: 2px solid #94a3b8;
    margin-bottom: 12px;
    background: url("https://picsum.photos/id/1015/600/300") no-repeat center #f1f5f9;
  }
  .cover { background-size: cover; }
  .contain { background-size: contain; }
</style>
<div class="frame cover"></div>
<div class="frame contain"></div>
```

- `cover` scales the image to **fill** the whole box, cropping the edges if needed. Perfect for hero banners.
- `contain` scales it to **fit** inside the box completely, which can leave empty space.
- You can also give exact sizes: `background-size: 100px auto;`.

## Gradients

Gradients are backgrounds made by CSS itself, so they need no image file:

```html
<style>
  .g { height: 80px; margin-bottom: 10px; border-radius: 8px; }
  .g1 { background: linear-gradient(#2563eb, #7c3aed); }
  .g2 { background: linear-gradient(to right, #f97316, #facc15); }
  .g3 { background: linear-gradient(135deg, #10b981 0%, #3b82f6 50%, #8b5cf6 100%); }
  .g4 { background: radial-gradient(circle, #fde68a, #f59e0b); }
</style>
<div class="g g1"></div>
<div class="g g2"></div>
<div class="g g3"></div>
<div class="g g4"></div>
```

- `linear-gradient(direction, colour1, colour2, ...)` blends in a straight line. The direction can be `to right`, `to bottom left` or an angle like `135deg`.
- Percentages after colours (colour stops) control where each colour sits.
- `radial-gradient()` blends outward from a centre.

## Several backgrounds at once

You can layer backgrounds, separated by commas. The **first** one is on top. A common trick is a dark gradient over a photo so white text stays readable:

```html
<style>
  .hero {
    background:
      linear-gradient(rgb(0 0 0 / 0.55), rgb(0 0 0 / 0.55)),
      url("https://picsum.photos/id/1044/900/400") center / cover no-repeat;
    color: white;
    padding: 80px 24px;
    text-align: center;
    font-family: system-ui, sans-serif;
  }
  .hero h1 { margin: 0 0 8px; }
</style>
<section class="hero">
  <h1>Learn to code for free</h1>
  <p>Short lessons, real examples, no sign-up needed.</p>
</section>
```

## The background shorthand

All background properties can be set in one line:

```css
.hero {
  background: #1e293b url("hero.jpg") center / cover no-repeat;
}
```

The order is flexible, but the size must come straight after the position, separated by `/`. Any part you leave out goes back to its default, which can accidentally reset something you set earlier.

## background-attachment

`background-attachment: fixed;` keeps the image still while the page scrolls (a "parallax" feel). It's often ignored on phones and can make scrolling slow, so use it sparingly.

## Common mistakes

- **Wrong image path in `url()`.** Paths in a CSS file are relative to the **CSS file**, not the HTML page.
- **Forgetting the box needs a height.** An empty div with a background image has zero height and shows nothing.
- **White text on a busy photo** without a dark overlay.
- **Important images as backgrounds**, which screen readers can't see.

## Exercise

1. Create a 300 × 200 box with a photo background using `cover`.
2. Add a gradient overlay and white centred text on top.
3. Make a striped button with `linear-gradient(to right, ...)`.
4. Try `contain` instead of `cover` and note the difference.

## Summary

- `background-color`, `background-image`, `-repeat`, `-position` and `-size` control backgrounds.
- `cover` fills and crops; `contain` fits without cropping.
- `linear-gradient()` and `radial-gradient()` create backgrounds with no image file.
- Layer a gradient over a photo to keep text readable.

Next, you'll draw borders, rounded corners and shadows.
