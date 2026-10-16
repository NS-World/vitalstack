---
title: CSS Position
slug: css-position
subject: css
lesson: 12
difficulty: intermediate
excerpt: Static, relative, absolute, fixed and sticky positioning explained with badges, overlays, sticky headers and back-to-top buttons, plus z-index stacking.
---

Normally, elements are placed one after another in the "normal flow" of the page. The `position` property lets you move an element out of that flow: pin a badge to the corner of a card, keep a header at the top while scrolling, or float a button over the page.

## The five values

| Value | What it does |
|---|---|
| `static` | Default. Normal flow; `top`/`left` etc. have no effect. |
| `relative` | Normal flow, but can be nudged; becomes the reference for absolute children. |
| `absolute` | Removed from the flow; placed relative to the nearest positioned ancestor. |
| `fixed` | Removed from the flow; placed relative to the browser window. Stays put when scrolling. |
| `sticky` | Normal flow until you scroll past a point, then sticks. |

The offset properties `top`, `right`, `bottom` and `left` say where to place the element.

## relative

```html
<style>
  .nudge { position: relative; top: 10px; left: 20px; background: #fde68a; }
</style>
<p>Normal paragraph.</p>
<p class="nudge">I'm moved 10px down and 20px right from where I'd normally be.</p>
<p>The space I left behind is kept, so nothing else moves.</p>
```

You'll rarely nudge things like this. The main use of `relative` is as a **container** for absolutely positioned children.

## absolute

An absolute element is placed relative to its nearest ancestor that has a position other than `static`. If there's none, it's placed relative to the page.

```html
<style>
  .card {
    position: relative;   /* the reference box */
    width: 260px;
    padding: 20px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-family: system-ui, sans-serif;
  }
  .badge {
    position: absolute;
    top: -10px;
    right: -10px;
    background: #ef4444;
    color: white;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 999px;
  }
</style>
<div class="card">
  <span class="badge">NEW</span>
  <h3>JavaScript course</h3>
  <p>25 lessons with live examples.</p>
</div>
```

The pattern to remember: **parent `relative`, child `absolute`.** The badge is pinned to the top-right corner of the card wherever the card goes.

Absolute elements are taken out of the flow: other elements act as if they don't exist, so they can overlap content.

## Overlay text on an image

```html
<style>
  .photo { position: relative; width: 300px; }
  .photo img { display: block; width: 100%; height: auto; border-radius: 8px; }
  .photo figcaption {
    position: absolute;
    left: 0; right: 0; bottom: 0;
    padding: 10px;
    background: rgb(0 0 0 / 0.6);
    color: white;
    border-radius: 0 0 8px 8px;
    font-family: system-ui, sans-serif;
  }
</style>
<figure class="photo">
  <img src="https://picsum.photos/id/1011/600/400" alt="A woman in a canoe on a lake" width="600" height="400">
  <figcaption>Morning on the lake</figcaption>
</figure>
```

Setting both `left: 0` and `right: 0` stretches the caption across the full width.

## fixed

```css
.back-to-top {
  position: fixed;
  right: 20px;
  bottom: 20px;
}
```

A fixed element stays in the same spot in the window while the page scrolls: chat buttons, cookie notices, "back to top" buttons. Keep fixed elements small, especially on phones, so they don't cover the content.

## sticky

```html
<style>
  .sticky-head {
    position: sticky;
    top: 0;
    background: #1e293b;
    color: white;
    padding: 10px;
    font-family: system-ui, sans-serif;
  }
  .filler { height: 600px; background: linear-gradient(#f8fafc, #cbd5e1); padding: 10px; }
</style>
<div class="sticky-head">I stick to the top when you scroll the result pane</div>
<div class="filler">Scroll down…</div>
```

A sticky element behaves normally until it reaches the `top` value while scrolling, then sticks there until its parent scrolls out of view. It's ideal for table headers, section headings and sidebars. It needs a `top` (or `bottom`) value to work, and it stops working if an ancestor has `overflow: hidden`.

## z-index: what's on top

When positioned elements overlap, `z-index` decides which is in front. Higher numbers are closer to the viewer:

```css
.dropdown { position: absolute; z-index: 10; }
.modal    { position: fixed;    z-index: 100; }
```

`z-index` only works on positioned elements (and flex/grid children). Keep a simple scale like 1, 10, 100 instead of random huge numbers like 99999.

## Common mistakes

- **Absolute child with no positioned parent**, so it flies to the corner of the whole page. Add `position: relative` to the parent.
- **Using absolute positioning for page layout.** Use Flexbox and Grid; absolute is for small decorations and overlays.
- **Sticky that doesn't stick**, because there's no `top` value or a parent has `overflow: hidden`.
- **Fixed elements covering content** on small screens.

## Exercise

1. Add a "SALE" badge to the corner of a product card.
2. Put a caption over the bottom of an image.
3. Create a fixed "↑ Top" button in the bottom-right corner that links to `#top`.
4. Make a long table whose header row sticks while scrolling (`th { position: sticky; top: 0; }`).

## Summary

- `relative` keeps the element in flow and creates a reference box.
- `absolute` places an element inside its nearest positioned ancestor.
- `fixed` pins to the window; `sticky` sticks after scrolling to a point.
- `z-index` controls stacking order of positioned elements.

Next, you'll start modern layout with Flexbox.
