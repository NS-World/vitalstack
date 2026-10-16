---
title: CSS Grid Basics
slug: css-grid-basics
subject: css
lesson: 15
difficulty: intermediate
excerpt: Build two-dimensional layouts with CSS Grid: grid containers, columns and rows, the fr unit, repeat(), gap, and placing items across several cells.
---

Flexbox arranges items in one direction. **CSS Grid** arranges them in **two directions at once**: rows and columns. It's the best tool for page layouts, galleries and any design that lines up both horizontally and vertically.

## A first grid

```html
<style>
  .grid {
    display: grid;
    grid-template-columns: 150px 150px 150px;
    gap: 10px;
  }
  .grid div {
    background: #3b82f6;
    color: white;
    padding: 20px;
    text-align: center;
    font-family: system-ui, sans-serif;
  }
</style>
<div class="grid">
  <div>1</div><div>2</div><div>3</div>
  <div>4</div><div>5</div><div>6</div>
</div>
```

- `display: grid` makes the parent a **grid container**; its direct children become **grid items**.
- `grid-template-columns` defines the columns: here, three columns of 150px.
- Items fill the cells in order, left to right, then wrap onto the next row automatically.

## The fr unit

Fixed pixel columns don't adapt to the screen. The `fr` unit (fraction) shares the **available space**:

```html
<style>
  .g { display: grid; gap: 8px; margin-bottom: 10px; font: 14px system-ui, sans-serif; }
  .g div { background: #10b981; color: white; padding: 12px; }
</style>
<div class="g" style="grid-template-columns: 1fr 1fr 1fr;">
  <div>1fr</div><div>1fr</div><div>1fr</div>
</div>
<div class="g" style="grid-template-columns: 2fr 1fr;">
  <div>2fr (twice as wide)</div><div>1fr</div>
</div>
<div class="g" style="grid-template-columns: 200px 1fr;">
  <div>200px</div><div>1fr (the rest)</div>
</div>
```

You can mix units: `200px 1fr` is a classic sidebar layout.

## repeat()

Instead of writing `1fr` many times:

```css
grid-template-columns: repeat(4, 1fr);         /* 4 equal columns */
grid-template-columns: repeat(3, 1fr) 200px;   /* 3 equal + one fixed */
```

## Rows

Rows are created automatically as items need them, each as tall as its content. You can set them explicitly too:

```css
.grid {
  grid-template-rows: 80px 1fr 60px;
  grid-auto-rows: 150px; /* height of any extra, automatically created rows */
}
```

## gap

```css
.grid {
  gap: 16px;            /* between rows and columns */
  row-gap: 24px;        /* or set them separately */
  column-gap: 12px;
}
```

## Placing items across cells

Grid lines are numbered starting at 1 on the left (and top). An item can start and end at any line:

```html
<style>
  .board {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-auto-rows: 80px;
    gap: 8px;
    font-family: system-ui, sans-serif;
  }
  .board div { background: #e0e7ff; padding: 8px; border-radius: 6px; }
  .wide { grid-column: span 2; background: #c7d2fe !important; }
  .tall { grid-row: span 2; background: #a5b4fc !important; }
  .full { grid-column: 1 / -1; background: #818cf8 !important; color: white; }
</style>
<div class="board">
  <div class="full">Full width (1 / -1)</div>
  <div class="wide">Spans 2 columns</div>
  <div class="tall">Spans 2 rows</div>
  <div>4</div>
  <div>5</div>
  <div>6</div>
  <div>7</div>
</div>
```

- `grid-column: span 2` makes an item two columns wide.
- `grid-row: span 2` makes it two rows tall.
- `grid-column: 1 / -1` stretches from the first line to the last: full width. Negative numbers count from the end.
- You can also write exact lines: `grid-column: 2 / 4` covers columns 2 and 3.

## Aligning items in their cells

```css
.grid {
  justify-items: center;  /* horizontal alignment inside cells */
  align-items: center;    /* vertical alignment inside cells */
  place-items: center;    /* both at once */
}
```

The shortest way to centre anything: `display: grid; place-items: center;`.

## A photo gallery

```html
<style>
  .gallery {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 6px;
  }
  .gallery img { width: 100%; height: 120px; object-fit: cover; display: block; border-radius: 6px; }
  .gallery img:first-child { grid-column: span 2; grid-row: span 2; height: 100%; }
</style>
<div class="gallery">
  <img src="https://picsum.photos/id/10/400/300" alt="Forest" width="400" height="300">
  <img src="https://picsum.photos/id/11/300/200" alt="Hills" width="300" height="200">
  <img src="https://picsum.photos/id/12/300/200" alt="Beach" width="300" height="200">
  <img src="https://picsum.photos/id/13/300/200" alt="Coast" width="300" height="200">
  <img src="https://picsum.photos/id/14/300/200" alt="Sea" width="300" height="200">
  <img src="https://picsum.photos/id/15/300/200" alt="Waterfall" width="300" height="200">
</div>
```

## Grid or Flexbox?

| Use Flexbox when… | Use Grid when… |
|---|---|
| Items flow in one line (menus, toolbars, tags) | You need rows **and** columns lined up |
| Content decides the sizes | The layout decides the sizes |
| Small components | Page layouts, galleries, dashboards |

They work together: a grid page layout often contains flex components.

## Common mistakes

- **Grid on the wrong element.** `display: grid` goes on the parent.
- **Forgetting that only direct children** become grid items.
- **Fixed pixel columns** that overflow on phones. Use `fr` and the responsive tricks in the next lesson.
- **Off-by-one line numbers.** Three columns have four lines (1 to 4).

## Exercise

1. Make a 4-column grid of 8 boxes with a 12px gap.
2. Make the first box span the full width as a header.
3. Build a `250px 1fr` layout with a sidebar and content.
4. Build the photo gallery with your own images and make the first photo big.

## Summary

- `display: grid` + `grid-template-columns` creates a grid.
- `fr` shares free space; `repeat()` saves typing; `gap` spaces cells.
- `grid-column` and `grid-row` with `span` or line numbers place items across cells.
- Use Grid for two-dimensional layouts and Flexbox for one-dimensional ones.

Next, you'll build complete page layouts with named grid areas and responsive grids.
