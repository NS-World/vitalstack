---
title: CSS Flexbox Basics
slug: css-flexbox-basics
subject: css
lesson: 13
difficulty: intermediate
excerpt: Lay out items in a row or column with Flexbox: flex containers, direction, justify-content, align-items, gap and wrapping, with a visual example of each.
---

Flexbox is a layout system for arranging items in **one direction**, a row or a column, and distributing space between them. It solved problems that used to need hacks, like vertical centring and equal-height columns, and it's now the everyday tool for menus, toolbars, cards and forms.

## The flex container

Flexbox works on a parent and its **direct children**. Add `display: flex` to the parent:

```html
<style>
  .container {
    display: flex;
    background: #f1f5f9;
    padding: 10px;
  }
  .item {
    background: #3b82f6;
    color: white;
    padding: 16px;
    margin: 4px;
    font-family: system-ui, sans-serif;
  }
</style>
<div class="container">
  <div class="item">1</div>
  <div class="item">2</div>
  <div class="item">3</div>
</div>
```

The parent is the **flex container**; its children are **flex items**. The items now sit side by side in a row.

## The two axes

Flexbox thinks in two directions:

- The **main axis**: the direction items are placed in (horizontal by default).
- The **cross axis**: at right angles to it (vertical by default).

`justify-content` arranges items along the main axis; `align-items` along the cross axis. Keep this in mind and the rest of Flexbox makes sense.

## flex-direction

```css
.container { flex-direction: row; }            /* default: left to right */
.container { flex-direction: column; }         /* top to bottom */
.container { flex-direction: row-reverse; }
.container { flex-direction: column-reverse; }
```

With `column`, the main axis becomes vertical, so `justify-content` and `align-items` swap their visual direction.

## justify-content: spacing on the main axis

```html
<style>
  .row { display: flex; background: #f1f5f9; margin-bottom: 8px; font: 13px system-ui, sans-serif; }
  .row span { background: #8b5cf6; color: white; padding: 8px 14px; margin: 3px; }
</style>
<div class="row" style="justify-content: flex-start;"><span>start</span><span>B</span><span>C</span></div>
<div class="row" style="justify-content: center;"><span>center</span><span>B</span><span>C</span></div>
<div class="row" style="justify-content: flex-end;"><span>end</span><span>B</span><span>C</span></div>
<div class="row" style="justify-content: space-between;"><span>between</span><span>B</span><span>C</span></div>
<div class="row" style="justify-content: space-around;"><span>around</span><span>B</span><span>C</span></div>
<div class="row" style="justify-content: space-evenly;"><span>evenly</span><span>B</span><span>C</span></div>
```

`space-between` is the classic choice for a header with a logo on the left and a menu on the right.

## align-items: alignment on the cross axis

```html
<style>
  .tall { display: flex; height: 120px; background: #f1f5f9; margin-bottom: 8px; font: 13px system-ui, sans-serif; }
  .tall span { background: #10b981; color: white; padding: 8px 14px; margin: 3px; }
</style>
<div class="tall" style="align-items: stretch;"><span>stretch (default)</span><span>B</span></div>
<div class="tall" style="align-items: flex-start;"><span>flex-start</span><span>B</span></div>
<div class="tall" style="align-items: center;"><span>center</span><span>B</span></div>
<div class="tall" style="align-items: flex-end;"><span>flex-end</span><span>B</span></div>
```

The default, `stretch`, makes all items the same height, which is why flex columns are equal height for free.

## Perfect centring

The famous "how do I centre a div?" question, answered in three lines:

```html
<style>
  .center {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 200px;
    background: #0f172a;
  }
  .center p { color: white; font: 20px system-ui, sans-serif; }
</style>
<div class="center"><p>Perfectly centred</p></div>
```

## gap

```css
.container {
  display: flex;
  gap: 16px;        /* space between items */
  /* row-gap and column-gap can be set separately */
}
```

`gap` puts space **between** items only, not on the outer edges. It's much cleaner than adding margins to every item.

## flex-wrap

By default, flex items stay on one line and shrink to fit. With `flex-wrap: wrap`, they move to a new line when there's no room:

```html
<style>
  .tags { display: flex; flex-wrap: wrap; gap: 8px; max-width: 300px; }
  .tags span { background: #e0e7ff; padding: 6px 12px; border-radius: 999px; font: 14px system-ui, sans-serif; }
</style>
<div class="tags">
  <span>HTML</span><span>CSS</span><span>JavaScript</span><span>Python</span>
  <span>SQL</span><span>Git</span><span>React</span><span>Node.js</span>
</div>
```

Use `align-content` to control the spacing between wrapped lines.

## A real example: a site header

```html
<style>
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 12px 20px;
    background: #1e293b;
    font-family: system-ui, sans-serif;
  }
  .logo { color: white; font-weight: 700; font-size: 20px; }
  .nav { display: flex; gap: 16px; }
  .nav a { color: #cbd5e1; text-decoration: none; }
  .nav a:hover { color: white; }
</style>
<header class="header">
  <div class="logo">VitalStack</div>
  <nav class="nav">
    <a href="#">Tutorials</a>
    <a href="#">Blog</a>
    <a href="#">Contact</a>
  </nav>
</header>
```

Two flex containers: the header (logo vs nav) and the nav itself (the links).

## Common mistakes

- **Putting `display: flex` on the items** instead of their parent.
- **Mixing up the axes** after changing `flex-direction` to `column`.
- **Using margins for spacing** between items when `gap` does it better.
- **Forgetting `flex-wrap`**, so items squash instead of moving to a new line on phones.

## Exercise

1. Build the header example, then add a "Sign in" button on the far right.
2. Centre a card in the middle of a 300px-tall box.
3. Make a wrapping list of tag "pills" with `gap`.
4. Change a row to `flex-direction: column` and see how `justify-content` now works vertically.

## Summary

- `display: flex` on a parent lays out its direct children in a row (or column).
- `justify-content` spaces items on the main axis; `align-items` aligns them on the cross axis.
- `gap` adds space between items; `flex-wrap: wrap` lets them flow onto new lines.
- Flexbox makes centring and equal-height items easy.

Next, you'll control how flex items grow and shrink to build real layouts.
