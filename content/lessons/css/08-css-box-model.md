---
title: CSS Box Model
slug: css-box-model
subject: css
lesson: 8
difficulty: beginner
excerpt: Every element is a box of content, padding, border and margin. Learn how its size is calculated, why box-sizing border-box makes life easier, and how to inspect boxes.
---

Every element on a web page is a rectangular box, even text and round avatars. The **box model** describes the layers of that box and how its final size is calculated. Once you understand it, layout bugs become much easier to fix.

## The four layers

From the inside out:

1. **Content**: the text, image or child elements.
2. **Padding**: space around the content, inside the border.
3. **Border**: the line around the padding.
4. **Margin**: space outside the border, separating the box from others.

```html
<style>
  .model {
    width: 200px;
    padding: 20px;
    border: 10px solid #2563eb;
    margin: 30px;
    background: #dbeafe;
  }
</style>
<div class="model">Content area</div>
```

## How the width is calculated (the surprise)

By default, `width` sets the width of the **content area only**. Padding and border are **added** to it.

For the box above:

```text
  200px  content
+  40px  padding (20 left + 20 right)
+  20px  border  (10 left + 10 right)
= 260px  visible width
```

Plus 30px of margin on each side, so it takes up 320px of space in total. Many beginners set `width: 200px`, add padding, and wonder why the box doesn't fit.

## box-sizing: border-box

There's a better way. With `box-sizing: border-box`, the `width` includes the padding and border:

```html
<style>
  .box {
    width: 200px;
    padding: 20px;
    border: 10px solid #334155;
    margin-bottom: 12px;
    background: #e2e8f0;
  }
  .content-box { box-sizing: content-box; }
  .border-box { box-sizing: border-box; }
</style>
<div class="box content-box">content-box: 260px wide</div>
<div class="box border-box">border-box: exactly 200px wide</div>
```

With `border-box`, the box is exactly as wide as you said, and the content shrinks to make room for padding and border. This is so much more intuitive that nearly every modern project starts with this rule:

```css
*,
*::before,
*::after {
  box-sizing: border-box;
}
```

Put it at the top of every stylesheet you write from now on.

## A practical example: two columns that fit

```html
<style>
  * { box-sizing: border-box; }
  .col {
    width: 50%;
    float: left;
    padding: 16px;
    border: 2px solid #94a3b8;
  }
</style>
<div class="col">Left column: 50% wide including padding and border.</div>
<div class="col">Right column: still fits next to the left one.</div>
```

Without `border-box`, each column would be 50% *plus* 36px, and the second one would drop below the first. (You'll learn better ways to make columns with Flexbox and Grid; this just shows the box model at work.)

## Inline elements are different

Inline elements like `<span>`, `<a>` and `<strong>` follow the box model only partly:

- `width` and `height` are ignored.
- Left and right padding, border and margin push neighbours away.
- Top and bottom padding and border are drawn, but **don't** push other lines away, so they can overlap.

To give an inline element a real size, change its display to `inline-block` or `block`. You'll learn about `display` in a later lesson.

## Inspecting the box model

Every browser can show you the box model of any element:

1. Right-click an element on any page → **Inspect** (or press F12).
2. In the **Styles** or **Computed** panel, find the box diagram.
3. It shows the exact content size, padding, border and margin. Hover over each layer to highlight it on the page.

This is the fastest way to find out why something is too wide or has an unexpected gap. Use it often.

## Common mistakes

- **Forgetting that padding adds to width** (without `border-box`), causing boxes to overflow.
- **Setting width on inline elements** and wondering why nothing happens.
- **Using margin for space inside a coloured box.** The background only fills content and padding.
- **Not using the developer tools** to look at the box.

## Exercise

1. Create a box with `width: 300px; padding: 25px; border: 5px solid;` and calculate its visible width. Check your answer with developer tools.
2. Add `box-sizing: border-box` and check again.
3. Put the universal `border-box` rule at the top of your profile page project's CSS.
4. Give a `<span>` a width of 200px, then change it to `display: inline-block` and compare.

## Summary

- Every element is a box: content, padding, border, margin.
- By default, `width` is content only; padding and border are added.
- `box-sizing: border-box` makes `width` include padding and border. Use it everywhere.
- Inline elements ignore width and height; developer tools show every box's real size.

Next, you'll control element sizes with width, height, max-width and more.
