---
title: CSS Display
slug: css-display
subject: css
lesson: 11
difficulty: beginner
excerpt: How the display property controls layout: block, inline, inline-block, none, flex and grid, plus hiding elements correctly with display none and visibility.
---

The `display` property decides how an element takes part in the layout: whether it starts on a new line, whether it accepts a width, whether it's shown at all, and whether its children are laid out with Flexbox or Grid. It's one of the most important properties in CSS.

## block

```css
div, p, h1, ul, section { display: block; } /* their default */
```

A block element:

- Starts on a new line.
- Fills the full width of its container (unless you set a width).
- Accepts width, height, margin and padding on all sides.

## inline

```css
span, a, strong, em { display: inline; } /* their default */
```

An inline element:

- Stays in the line of text.
- Is only as wide as its content.
- Ignores `width` and `height`; vertical margin has no effect.

## inline-block: the best of both

```html
<style>
  .tag {
    display: inline-block;
    padding: 4px 12px;
    margin: 4px;
    background: #e0e7ff;
    color: #3730a3;
    border-radius: 999px;
    font: 14px system-ui, sans-serif;
  }
</style>
<p>
  Topics:
  <a class="tag" href="#">HTML</a>
  <a class="tag" href="#">CSS</a>
  <a class="tag" href="#">JavaScript</a>
</p>
```

`inline-block` sits in the line like inline text, but accepts width, height and vertical padding like a block. It's perfect for tags, badges and buttons inside text.

## Changing an element's display

Any element can be given any display. A classic example: turning a list into a horizontal menu:

```html
<style>
  .menu { list-style: none; padding: 0; margin: 0; background: #1e293b; }
  .menu li { display: inline-block; }
  .menu a {
    display: block;
    padding: 12px 16px;
    color: white;
    text-decoration: none;
    font-family: system-ui, sans-serif;
  }
  .menu a:hover { background: #334155; }
</style>
<ul class="menu">
  <li><a href="#">Home</a></li>
  <li><a href="#">Tutorials</a></li>
  <li><a href="#">Blog</a></li>
</ul>
```

The links are `display: block` so the whole padded area is clickable, not just the text. (In the Flexbox lessons you'll learn an even better way to build menus.)

Changing display only changes the **look**, never the meaning. A `<span>` with `display: block` is still a span.

## display: none

```css
.hidden { display: none; }
```

The element is removed from the page completely: it's not shown, takes up no space, and screen readers ignore it. JavaScript often toggles this to open and close menus, tabs and pop-ups.

## Other ways to hide things

```html
<style>
  .box { padding: 8px; margin: 4px 0; background: #dbeafe; }
  .invisible { visibility: hidden; }
  .faded { opacity: 0.3; }
</style>
<div class="box">Box 1</div>
<div class="box invisible">Box 2 (visibility: hidden)</div>
<div class="box">Box 3</div>
<div class="box faded">Box 4 (opacity: 0.3)</div>
```

| Method | Visible | Takes space | Screen readers |
|---|---|---|---|
| `display: none` | No | No | Hidden |
| `visibility: hidden` | No | Yes (leaves a gap) | Hidden |
| `opacity: 0` | No | Yes | Still read out |

### Hiding visually but keeping it for screen readers

Sometimes text should be read aloud but not shown, like a label for an icon button. Use this well-known class:

```css
.visually-hidden {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}
```

## flex and grid

Two more values turn an element into a **layout container** for its children:

```css
.row { display: flex; }   /* children line up in a row */
.grid { display: grid; }  /* children placed on a grid */
```

```html
<style>
  .row { display: flex; gap: 8px; }
  .row div { background: #fde68a; padding: 12px; }
</style>
<div class="row">
  <div>One</div>
  <div>Two</div>
  <div>Three</div>
</div>
```

These are how modern layouts are built, and they get four lessons of their own, starting soon.

## Common mistakes

- **Setting width on an inline element** and seeing no change. Use `inline-block` or `block`.
- **Small clickable areas** because links stay inline inside menus. Make them `block` and add padding.
- **Using `opacity: 0` to hide content**, which screen readers still read and which can still be clicked.
- **Changing display to change meaning.** Pick the right HTML element first.

## Exercise

1. Make a row of three "badge" links with `inline-block`, padding and rounded corners.
2. Turn a vertical list into a horizontal menu.
3. Hide one box with `display: none` and another with `visibility: hidden`; compare the gaps.
4. Add a visually hidden text label to an icon-only button.

## Summary

- `block` starts a new line and fills the width; `inline` flows with text and ignores sizes.
- `inline-block` flows with text but accepts sizes.
- `display: none` removes an element; `visibility: hidden` hides it but keeps its space.
- `flex` and `grid` make an element a layout container for its children.

Next, you'll position elements precisely with the position property.
