---
title: CSS Responsive Design and Media Queries
slug: css-responsive-design
subject: css
lesson: 17
difficulty: intermediate
excerpt: Make pages work on every screen: the viewport meta tag, mobile-first CSS, media queries and breakpoints, fluid type with clamp(), and testing on real sizes.
---

Most people visit websites on their phones. **Responsive design** means one page that adapts its layout to any screen, from a small phone to a wide monitor. In this lesson you'll learn the tools: the viewport tag, flexible sizes, media queries and fluid typography.

## Step 1: the viewport meta tag

```html
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```

Without this line in the `<head>`, phones pretend to be a 980px-wide desktop and shrink the whole page down to tiny, unreadable text. With it, the page uses the phone's real width. Every responsive page needs it.

## Step 2: flexible foundations

Much of responsive design is just avoiding fixed sizes. You've already learned the basics:

- `max-width` instead of `width` for containers.
- `max-width: 100%; height: auto;` for images.
- `fr`, `%` and `flex` for columns.
- `rem` for text and spacing.
- `repeat(auto-fit, minmax(...))` for card grids.

Get these right and a page is already 80% responsive before you write a single media query.

## Media queries

A media query applies CSS **only when a condition is true**, usually a minimum or maximum screen width:

```html
<style>
  .banner {
    padding: 16px;
    background: #fde68a;
    font: 18px system-ui, sans-serif;
  }
  @media (min-width: 600px) {
    .banner { background: #a7f3d0; }
    .banner::after { content: " (600px or wider)"; }
  }
  @media (min-width: 900px) {
    .banner { background: #bfdbfe; }
    .banner::after { content: " (900px or wider)"; }
  }
</style>
<div class="banner">Resize the result pane</div>
```

The rules inside `@media (min-width: 600px) { … }` only apply when the window is at least 600px wide.

## Mobile-first

There are two ways to write media queries:

- **Desktop-first:** write the desktop layout, then use `max-width` queries to fix it for smaller screens.
- **Mobile-first:** write the simple phone layout first (with no media query), then use `min-width` queries to add columns as space grows.

Mobile-first is the recommended approach: phone layouts are usually simpler (one column), so you start simple and **add** complexity, instead of undoing it.

```html
<style>
  .layout { display: grid; gap: 12px; font-family: system-ui, sans-serif; }
  .layout > div { background: #e0e7ff; padding: 16px; border-radius: 8px; }

  /* Tablets: two columns */
  @media (min-width: 600px) {
    .layout { grid-template-columns: 1fr 1fr; }
  }

  /* Desktops: sidebar + main + sidebar */
  @media (min-width: 900px) {
    .layout { grid-template-columns: 200px 1fr 200px; }
  }
</style>
<div class="layout">
  <div>Menu</div>
  <div>Main content</div>
  <div>Related links</div>
</div>
```

## Choosing breakpoints

A **breakpoint** is a width where the layout changes. Don't design for particular phone models; there are thousands. Instead, widen the browser slowly and add a breakpoint **where the design starts to look wrong**. Common starting points:

| Breakpoint | Typical devices |
|---|---|
| `min-width: 600px` | Large phones in landscape, small tablets |
| `min-width: 900px` | Tablets in landscape, small laptops |
| `min-width: 1200px` | Desktops |

Two or three breakpoints are enough for most sites.

## A responsive navigation

```html
<style>
  .nav { font-family: system-ui, sans-serif; background: #1e293b; padding: 12px; }
  .nav ul { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 4px; }
  .nav a { color: white; text-decoration: none; display: block; padding: 8px; }

  @media (min-width: 600px) {
    .nav ul { flex-direction: row; gap: 16px; }
  }
</style>
<nav class="nav">
  <ul>
    <li><a href="#">Home</a></li>
    <li><a href="#">Tutorials</a></li>
    <li><a href="#">Blog</a></li>
    <li><a href="#">Contact</a></li>
  </ul>
</nav>
```

Stacked on phones, in a row on larger screens. (A real site often hides the menu behind a "☰" button on phones; that needs a little JavaScript, which you'll learn in the JavaScript course.)

## Fluid typography with clamp()

Headings that look great on a desktop are often too big on a phone. `clamp()` scales text smoothly between a minimum and maximum:

```css
h1 {
  font-size: clamp(1.75rem, 4vw + 1rem, 3rem);
}
```

`clamp(min, preferred, max)`: the heading is never smaller than 1.75rem, never larger than 3rem, and scales with the window width in between. No breakpoints needed.

## Other useful media features

```css
@media (prefers-color-scheme: dark) {
  body { background: #0f172a; color: #e2e8f0; }
}

@media (prefers-reduced-motion: reduce) {
  * { animation: none !important; transition: none !important; }
}

@media (hover: hover) {
  .card:hover { transform: translateY(-2px); }
}

@media print {
  nav, footer, .ads { display: none; }
}
```

- `prefers-color-scheme`: the user's light or dark mode setting.
- `prefers-reduced-motion`: the user asked for less animation (some people get dizzy).
- `hover`: whether the device can hover (phones can't).
- `print`: styles for printing.

## Testing responsive pages

- **Browser device mode:** press F12, then the phone/tablet icon (Ctrl + Shift + M). Choose sizes or drag the width.
- **Test on a real phone**, too: touch targets, font sizes and fixed elements feel different in your hand.
- Check that there's **no horizontal scrolling** at 320px wide, the narrowest common phone width.

## Common mistakes

- **Forgetting the viewport meta tag.**
- **Fixed widths** like `width: 960px` on containers.
- **Too many breakpoints** for specific devices.
- **Tiny tap targets.** Buttons and links should be at least about 44 × 44 pixels on touch screens.
- **Hiding important content on mobile.** Phone users need the same information.

## Exercise

1. Take your profile page and make it mobile-first: one column by default.
2. At 700px, show the skills and projects sections side by side.
3. Make the main heading fluid with `clamp()`.
4. Add a `prefers-color-scheme: dark` block with dark colours.
5. Test it at 320px, 768px and 1280px widths in device mode.

## Summary

- Always include the viewport meta tag.
- Flexible units and layouts do most of the work; media queries handle the rest.
- Write mobile-first CSS and add `min-width` breakpoints where the design needs them.
- `clamp()` makes text fluid; media features handle dark mode, motion, hover and print.

Next, you'll add smooth movement with CSS transitions.
