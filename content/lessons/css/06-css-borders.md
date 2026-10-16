---
title: CSS Borders
slug: css-borders
subject: css
lesson: 6
difficulty: beginner
excerpt: Border width, style and colour, single sides, the border shorthand, rounded corners with border-radius, circles, outlines and box shadows.
---

Borders draw lines around elements. Together with rounded corners and shadows, they're how you make cards, buttons, inputs and avatars look finished.

## The three parts of a border

```html
<style>
  .b {
    border-width: 3px;
    border-style: solid;
    border-color: #2563eb;
    padding: 12px;
  }
</style>
<p class="b">A 3px solid blue border.</p>
```

A border needs a **style** to appear. Without `border-style`, there's no border at all, even if you set a width and colour.

## Border styles

```html
<style>
  .s { border-width: 4px; border-color: #334155; padding: 8px; margin: 8px 0; }
</style>
<p class="s" style="border-style: solid;">solid</p>
<p class="s" style="border-style: dashed;">dashed</p>
<p class="s" style="border-style: dotted;">dotted</p>
<p class="s" style="border-style: double;">double</p>
<p class="s" style="border-style: groove;">groove</p>
<p class="s" style="border-style: none;">none</p>
```

In real designs you'll use `solid` 95% of the time, and occasionally `dashed` for things like "drop a file here" areas.

## The border shorthand

Write all three parts on one line, in any order:

```css
.card { border: 1px solid #e2e8f0; }
```

This is how you'll almost always write borders.

## Individual sides

```html
<style>
  .note {
    border-left: 5px solid #f59e0b;
    background: #fffbeb;
    padding: 12px 16px;
  }
  .title {
    border-bottom: 2px solid #2563eb;
    padding-bottom: 6px;
  }
</style>
<h2 class="title">Section title with an underline</h2>
<p class="note">A note with a coloured bar on the left.</p>
```

Use `border-top`, `border-right`, `border-bottom` and `border-left` to style one side. A left border on a tinted box is a classic "note" or "tip" style.

## Rounded corners with border-radius

```html
<style>
  .r { background: #dbeafe; padding: 16px; margin-bottom: 10px; }
  .r1 { border-radius: 6px; }
  .r2 { border-radius: 20px; }
  .r3 { border-radius: 999px; text-align: center; }
  .r4 { border-radius: 20px 0 20px 0; }
</style>
<div class="r r1">Slightly rounded (6px)</div>
<div class="r r2">Very rounded (20px)</div>
<div class="r r3">Pill shape (999px)</div>
<div class="r r4">Opposite corners only</div>
```

- One value rounds all four corners.
- Four values go clockwise from the top-left: top-left, top-right, bottom-right, bottom-left.
- A very large value like `999px` makes a "pill", perfect for tags and buttons.

`border-radius` works even without a border: it rounds the background too.

## Circles

A square with `border-radius: 50%` becomes a circle:

```html
<style>
  .avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 2px 8px rgb(0 0 0 / 0.25);
  }
</style>
<img class="avatar" src="https://picsum.photos/id/64/240/240" alt="Profile photo">
```

`object-fit: cover` stops the photo from being squashed if it isn't square.

## Box shadows

```html
<style>
  .card {
    max-width: 280px;
    padding: 20px;
    border-radius: 12px;
    background: white;
    box-shadow: 0 4px 12px rgb(0 0 0 / 0.12);
    font-family: system-ui, sans-serif;
  }
  body { background: #f1f5f9; padding: 24px; }
</style>
<div class="card">
  <h3>Card title</h3>
  <p>A soft shadow makes the card float above the page.</p>
</div>
```

`box-shadow: x-offset y-offset blur colour;`

- Positive x moves the shadow right; positive y moves it down.
- A bigger blur makes it softer.
- Use a transparent black for natural shadows. Strong, dark shadows look dated.
- Add a fourth length for **spread**, and the keyword `inset` for an inner shadow.

## Outline: a border that takes no space

```css
button:focus-visible {
  outline: 3px solid #f59e0b;
  outline-offset: 2px;
}
```

An `outline` looks like a border but doesn't take up space and doesn't move anything. Browsers use it to show keyboard focus. If you restyle focus, use `outline`, never just remove it.

## Common mistakes

- **No border-style**, so the border never appears.
- **`border-radius: 50%` on a rectangle**, which makes an oval, not a circle. Make width and height equal first.
- **Heavy shadows** like `5px 5px 5px black`. Keep shadows soft and subtle.
- **Removing focus outlines** with `outline: none` and nothing in their place.

## Exercise

1. Style a "warning" box with a thick orange left border and a pale orange background.
2. Make a pill-shaped tag that says "Beginner".
3. Turn a photo into a round avatar with a white border and soft shadow.
4. Build a card with rounded corners and a subtle shadow, and give it a bigger shadow on hover with `.card:hover`.

## Summary

- A border needs width, style and colour; the `border` shorthand sets all three.
- `border-top`, `-right`, `-bottom`, `-left` style one side.
- `border-radius` rounds corners; `50%` on a square makes a circle.
- `box-shadow` adds depth; `outline` is for focus and takes no space.

Next, you'll control the space around and inside elements with margin and padding.
