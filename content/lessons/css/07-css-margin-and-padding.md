---
title: CSS Margin and Padding
slug: css-margin-and-padding
subject: css
lesson: 7
difficulty: beginner
excerpt: The difference between padding (space inside) and margin (space outside), the one-to-four value shorthand, centring with auto, and collapsing margins.
---

Good spacing is the secret behind clean designs. CSS has two spacing properties: **padding** adds space *inside* an element, between its content and its border; **margin** adds space *outside*, between the element and its neighbours.

## Padding vs margin

```html
<style>
  .box {
    background: #dbeafe;
    border: 2px solid #2563eb;
    padding: 20px;  /* inside the border */
    margin: 30px;   /* outside the border */
  }
  body { background: #fef3c7; }
</style>
<div class="box">Padding pushes the border away from this text. Margin pushes other things away from the border.</div>
<div class="box">A second box.</div>
```

Look at it with **Try it Yourself**: the blue background fills the padding area, but the margin is transparent (you see the yellow page through it).

A simple way to remember:

- **Padding** = cushion inside the box. Background colour shows in it.
- **Margin** = gap outside the box. Always transparent.

## One to four values

Both properties accept one to four values. They go **clockwise from the top**:

```css
padding: 10px;                 /* all four sides */
padding: 10px 20px;            /* top & bottom 10px, left & right 20px */
padding: 10px 20px 30px;       /* top 10, left & right 20, bottom 30 */
padding: 10px 20px 30px 40px;  /* top, right, bottom, left */
```

Think of a clock: **T**op, **R**ight, **B**ottom, **L**eft ("TRouBLe" helps some people remember).

You can also set one side at a time:

```css
margin-top: 0;
margin-bottom: 24px;
padding-left: 16px;
```

## Buttons: padding in practice

```html
<style>
  .btn {
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    padding: 12px 24px;
  }
  .btn-small { padding: 6px 12px; font-size: 14px; }
</style>
<button class="btn">Normal button</button>
<button class="btn btn-small">Small button</button>
```

More horizontal than vertical padding is what makes a button look like a button.

## Centring a block with margin: auto

A block with a fixed width can be centred horizontally with `auto` left and right margins:

```html
<style>
  .container {
    max-width: 500px;
    margin: 0 auto;
    background: #f1f5f9;
    padding: 20px;
  }
</style>
<div class="container">I'm centred on the page, with a maximum width of 500px.</div>
```

`margin: 0 auto` means "no top/bottom margin, and split the remaining space equally left and right". This is how almost every website centres its main content column.

## Collapsing margins

When two vertical margins touch, they **collapse** into one: the bigger one wins instead of adding up.

```html
<style>
  .a { margin-bottom: 40px; background: #fde68a; }
  .b { margin-top: 20px; background: #a7f3d0; }
</style>
<p class="a">Margin-bottom: 40px</p>
<p class="b">Margin-top: 20px. The gap between us is 40px, not 60px.</p>
```

This only happens with **vertical** margins between block elements, never with horizontal margins or padding, and not inside flex or grid containers. It surprises everyone at first; just remember the bigger margin wins.

A simple habit avoids most confusion: add spacing in **one direction** only, for example `margin-bottom` on elements, and `margin-top: 0`.

## Negative margins

Margins can be negative, which pulls an element towards its neighbour or out of its container. Padding can't be negative. Use negative margins rarely; there's usually a clearer way.

## Browser default spacing

Browsers add default margins to `body`, headings, paragraphs and lists. That's why there's a small gap around every unstyled page. Many projects start with a small reset:

```css
body { margin: 0; }
h1, h2, h3, p { margin-top: 0; }
```

## Common mistakes

- **Using padding when you need margin** (or the reverse): if you want the background to grow, it's padding; if you want a gap between boxes, it's margin.
- **Getting the four-value order wrong.** It's always clockwise from the top.
- **`margin: auto` without a width**: a block already fills the full width, so there's nothing to centre.
- **Being surprised by collapsing margins.**

## Exercise

1. Create three cards with `padding: 20px` and `margin-bottom: 16px`.
2. Centre a 600px-wide container with `margin: 0 auto`.
3. Make two buttons, one big and one small, using only padding and font size.
4. Prove margin collapsing: give two paragraphs margins of 30px and 50px and measure the gap with developer tools (F12 → inspect).

## Summary

- Padding is space inside the border (shows the background); margin is space outside (transparent).
- One to four values go clockwise: top, right, bottom, left.
- `margin: 0 auto` centres a block that has a width.
- Vertical margins between blocks collapse into the larger one.

Next, you'll put content, padding, border and margin together in the box model.
