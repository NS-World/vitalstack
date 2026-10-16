---
title: CSS Grid Layouts
slug: css-grid-layouts
subject: css
lesson: 16
difficulty: intermediate
excerpt: Build full page layouts with grid-template-areas, make responsive card grids with auto-fit and minmax(), and combine Grid with Flexbox.
---

With the basics of Grid in place, you can build complete page layouts. Two features make Grid especially powerful: **named areas**, which let you draw your layout in the CSS, and **auto-fit with minmax()**, which makes grids responsive without a single media query.

## Named grid areas

`grid-template-areas` lets you name regions of the grid and arrange them like a map:

```html
<style>
  .page {
    display: grid;
    grid-template-columns: 200px 1fr;
    grid-template-rows: auto 1fr auto;
    grid-template-areas:
      "header  header"
      "sidebar main"
      "footer  footer";
    gap: 10px;
    min-height: 360px;
    font-family: system-ui, sans-serif;
  }
  .page > * { padding: 16px; border-radius: 8px; }
  .page header { grid-area: header; background: #1e293b; color: white; }
  .page aside  { grid-area: sidebar; background: #e2e8f0; }
  .page main   { grid-area: main; background: #f8fafc; border: 1px solid #e2e8f0; }
  .page footer { grid-area: footer; background: #cbd5e1; }
</style>
<div class="page">
  <header>Header</header>
  <aside>Sidebar</aside>
  <main>Main content</main>
  <footer>Footer</footer>
</div>
```

- Each string in `grid-template-areas` is one row; each word is one cell.
- Repeating a name (`header header`) makes that area span those cells.
- Each item is assigned with `grid-area: name`.
- A `.` means an empty cell.

The CSS now looks like a picture of the layout, which makes it easy to read and change.

## Rearranging for phones

The real magic: to change the layout on small screens, you only redraw the map. You'll learn media queries properly in the next lesson; here's a preview:

```css
@media (max-width: 600px) {
  .page {
    grid-template-columns: 1fr;
    grid-template-areas:
      "header"
      "main"
      "sidebar"
      "footer";
  }
}
```

On phones, everything stacks in one column, and the sidebar moves below the main content. The HTML doesn't change.

## Responsive cards with auto-fit and minmax()

This one line creates a card grid that adapts to any screen width:

```html
<style>
  .cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 14px;
    font-family: system-ui, sans-serif;
  }
  .cards article {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 16px;
    background: white;
  }
</style>
<div class="cards">
  <article><h3>HTML</h3><p>Structure</p></article>
  <article><h3>CSS</h3><p>Style</p></article>
  <article><h3>JavaScript</h3><p>Behaviour</p></article>
  <article><h3>SQL</h3><p>Data</p></article>
  <article><h3>Python</h3><p>Everything else</p></article>
</div>
```

Resize the result pane and the number of columns changes by itself. Here's how it works:

- `minmax(180px, 1fr)`: each column is at least 180px, and at most one equal share of the space.
- `repeat(auto-fit, …)`: create as many columns as fit.

On a phone you get one column; on a laptop, four or five. No media queries needed.

### auto-fit vs auto-fill

- `auto-fit` stretches the existing items to fill the row when there are only a few.
- `auto-fill` keeps empty "ghost" columns, so a few items stay at their minimum width.

For card grids, `auto-fit` is usually what you want.

## A blog layout

```html
<style>
  .blog {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 260px;
    gap: 24px;
    font-family: system-ui, sans-serif;
  }
  .posts {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
  }
  .post { border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; }
  .post img { width: 100%; height: 110px; object-fit: cover; display: block; }
  .post h3 { font-size: 16px; margin: 10px 12px 14px; }
  .side { background: #f1f5f9; padding: 16px; border-radius: 10px; align-self: start; }
</style>
<div class="blog">
  <div class="posts">
    <article class="post"><img src="https://picsum.photos/id/1/400/220" alt="" width="400" height="220"><h3>Getting started with Git</h3></article>
    <article class="post"><img src="https://picsum.photos/id/2/400/220" alt="" width="400" height="220"><h3>10 VS Code shortcuts</h3></article>
    <article class="post"><img src="https://picsum.photos/id/3/400/220" alt="" width="400" height="220"><h3>What is an API?</h3></article>
  </div>
  <aside class="side"><h3>Popular</h3><p>CSS Flexbox Basics</p><p>Python Lists</p></aside>
</div>
```

The outer grid makes two columns: posts and sidebar. The inner grid is a responsive card grid. `minmax(0, 1fr)` stops long content from stretching the column. `align-self: start` keeps the sidebar from stretching to the full height.

## Grid and Flexbox together

A good rule of thumb:

- **Grid** for the overall layout (the page, the card grid).
- **Flexbox** inside components (the content of each card, a header's nav links, a button with an icon).

You don't have to choose one. Most modern sites use both, often on the same page.

## Common mistakes

- **Area names that don't form rectangles.** Each named area must be a rectangle; L-shapes aren't allowed.
- **Different numbers of cells in each row** of `grid-template-areas`.
- **Forgetting `grid-area`** on the items, so they ignore the map.
- **Columns that overflow** because of long words or wide images: use `minmax(0, 1fr)` and `max-width: 100%` on images.

## Exercise

1. Build the header / sidebar / main / footer layout with named areas.
2. Add the media query so it stacks on narrow screens, with the sidebar under the main content.
3. Create a responsive grid of 8 cards with `repeat(auto-fit, minmax(200px, 1fr))`.
4. Inside each card, use Flexbox to put an icon and a title side by side.

## Summary

- `grid-template-areas` draws the layout; `grid-area` places items.
- Redraw the areas in a media query to rearrange the layout for phones.
- `repeat(auto-fit, minmax(min, 1fr))` creates responsive grids without media queries.
- Use Grid for layout and Flexbox for components.

Next, you'll make every page work on phones, tablets and desktops with responsive design and media queries.
