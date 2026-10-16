---
title: CSS Flexbox Layouts
slug: css-flexbox-layouts
subject: css
lesson: 14
difficulty: intermediate
excerpt: Control flex items with flex-grow, flex-shrink, flex-basis and the flex shorthand, then build a sidebar layout, equal cards, a sticky footer and a media object.
---

In the last lesson you arranged items with properties on the **container**. Now you'll control the **items** themselves: how much they grow, shrink and how wide they start. With these, you can build most everyday layouts.

## flex-grow: sharing extra space

```html
<style>
  .row { display: flex; gap: 6px; background: #f1f5f9; padding: 6px; margin-bottom: 8px; font: 14px system-ui, sans-serif; }
  .row div { background: #3b82f6; color: white; padding: 12px; }
</style>
<div class="row">
  <div>No grow</div>
  <div style="flex-grow: 1;">flex-grow: 1 (takes all the extra space)</div>
</div>
<div class="row">
  <div style="flex-grow: 1;">grow 1</div>
  <div style="flex-grow: 2;">grow 2 (gets twice as much extra space)</div>
</div>
```

`flex-grow` decides how the **leftover** space in the container is shared. `0` (the default) means "don't grow". If one item has `1` and the rest have `0`, that item takes all the extra room. This is how you make a search box fill the space next to a button.

## flex-shrink: giving up space

When there isn't enough room, items shrink. `flex-shrink: 1` is the default (shrink if needed); `flex-shrink: 0` means "never shrink below my size". Use it for things like icons and avatars that must keep their size.

## flex-basis: the starting size

`flex-basis` sets an item's size **before** extra space is shared out. It works like `width` in a row (or `height` in a column):

```css
.sidebar { flex-basis: 250px; }
```

## The flex shorthand

You'll usually write all three together with `flex: grow shrink basis`:

```css
.item { flex: 1; }           /* = 1 1 0%: share space equally */
.item { flex: 0 0 250px; }   /* fixed 250px: don't grow, don't shrink */
.item { flex: 1 1 200px; }   /* start at 200px, grow and shrink as needed */
```

`flex: 1` on every item gives equal-width columns, whatever their content.

## Layout 1: sidebar + main content

```html
<style>
  .layout { display: flex; gap: 16px; font-family: system-ui, sans-serif; }
  .sidebar { flex: 0 0 200px; background: #e2e8f0; padding: 16px; }
  .main { flex: 1; background: #f8fafc; padding: 16px; border: 1px solid #e2e8f0; }
</style>
<div class="layout">
  <aside class="sidebar">Sidebar: always 200px</aside>
  <main class="main">Main content: takes all the remaining width. Resize the result pane and watch it adapt while the sidebar keeps its size.</main>
</div>
```

## Layout 2: equal cards with buttons at the bottom

```html
<style>
  .cards { display: flex; flex-wrap: wrap; gap: 16px; font-family: system-ui, sans-serif; }
  .card {
    flex: 1 1 200px;
    display: flex;
    flex-direction: column;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 16px;
  }
  .card p { flex: 1; color: #475569; }
  .card a { background: #2563eb; color: white; padding: 8px; text-align: center; border-radius: 6px; text-decoration: none; }
</style>
<div class="cards">
  <div class="card"><h3>HTML</h3><p>Short text.</p><a href="#">Start</a></div>
  <div class="card"><h3>CSS</h3><p>This card has a much longer description, but its button still lines up with the others at the bottom.</p><a href="#">Start</a></div>
  <div class="card"><h3>JavaScript</h3><p>Medium-length text here.</p><a href="#">Start</a></div>
</div>
```

Two tricks at once:

- `flex: 1 1 200px` with `flex-wrap: wrap` makes cards share the row equally but drop to a new line when they'd be narrower than 200px: a responsive grid with no media queries.
- Each card is itself a column flex container, and the paragraph has `flex: 1`, so it stretches and pushes every button to the bottom.

## Layout 3: input with a button

```html
<style>
  .search { display: flex; max-width: 420px; }
  .search input { flex: 1; padding: 10px; border: 1px solid #94a3b8; border-right: 0; border-radius: 8px 0 0 8px; min-width: 0; }
  .search button { flex: 0 0 auto; padding: 10px 16px; border: 0; background: #2563eb; color: white; border-radius: 0 8px 8px 0; }
</style>
<form class="search">
  <input type="search" aria-label="Search lessons" placeholder="Search lessons">
  <button>Search</button>
</form>
```

The input grows; the button keeps its natural size. `min-width: 0` lets the input shrink below its default width on tiny screens.

## Layout 4: footer at the bottom of short pages

```css
body {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  margin: 0;
}
main { flex: 1; }
```

The `<main>` grows to fill any empty space, so the footer always sits at the bottom of the window, even on a page with little content.

## Pushing one item away with margin auto

```css
.nav { display: flex; gap: 12px; }
.nav .signin { margin-left: auto; }
```

An `auto` margin in a flex container eats all the free space on that side, pushing the item to the far end. Great for a "Sign in" link at the end of a menu.

## order and align-self

- `order: -1` moves an item earlier visually (without changing the HTML). Use with care: keyboard and screen reader order still follow the HTML.
- `align-self: center` overrides `align-items` for one item.

## Common mistakes

- **Expecting `flex: 1` to work without `display: flex`** on the parent.
- **Content overflowing** because of long words or inputs: add `min-width: 0` to the flex item.
- **Using `order` to fix the HTML order**, which confuses keyboard users. Fix the HTML instead.

## Exercise

1. Build a page with a 240px sidebar and fluid main area.
2. Make three cards with different text lengths and buttons aligned at the bottom.
3. Add a "Sign in" link pushed to the right end of a nav with `margin-left: auto`.
4. Make a footer stick to the bottom of a short page.

## Summary

- `flex-grow` shares extra space, `flex-shrink` handles too little space, `flex-basis` sets the starting size.
- `flex: 1` = equal shares; `flex: 0 0 250px` = fixed size.
- Nested flex containers solve layouts like equal cards with bottom-aligned buttons.
- `margin-left: auto` pushes an item to the end.

Next, you'll learn CSS Grid, the two-dimensional layout system.
