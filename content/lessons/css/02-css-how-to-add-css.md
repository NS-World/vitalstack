---
title: How to Add CSS
slug: css-how-to-add-css
subject: css
lesson: 2
difficulty: beginner
excerpt: The three ways to add CSS (inline styles, internal style elements and external stylesheets), when to use each one, and which wins when they conflict.
---

There are three places you can write CSS: directly on an element, in a `<style>` element inside the page, or in a separate `.css` file. Real websites mostly use the third, but you'll meet all three.

## 1. Inline styles

Add a `style` attribute to one element:

```html
<p style="color: crimson; font-weight: bold;">This paragraph is styled inline.</p>
<p>This one isn't.</p>
```

Inline styles affect only that one element. They're handy for a quick test, or when a script changes one element's style, but they have big downsides:

- You can't reuse them; every element needs its own copy.
- They mix content and design, making HTML hard to read.
- They're very hard to override from a stylesheet.

## 2. Internal stylesheet

Put rules inside a `<style>` element, usually in the `<head>`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Internal CSS</title>
  <style>
    body { font-family: Georgia, serif; }
    h1 { color: teal; }
    p { line-height: 1.6; }
  </style>
</head>
<body>
  <h1>Internal stylesheet</h1>
  <p>Every paragraph on this page gets the same line height.</p>
  <p>Including this one.</p>
</body>
</html>
```

The rules apply to the whole page. This is fine for a single-page project, an email template or a quick demo (the examples in these lessons use it so they run in **Try it Yourself**). But the styles can't be shared with other pages.

## 3. External stylesheet (the normal way)

Write the CSS in its own file, for example `styles.css`:

```css
/* styles.css */
body {
  font-family: system-ui, sans-serif;
  margin: 0;
}
h1 {
  color: #2563eb;
}
```

Then link it from the `<head>` of every page:

```html
<head>
  <meta charset="UTF-8">
  <title>My site</title>
  <link rel="stylesheet" href="styles.css">
</head>
```

- `rel="stylesheet"` says what kind of file it is.
- `href` is the path to the file, relative or absolute, just like a link.

Benefits:

- **One file styles the whole site.** Change it once and every page updates.
- **Faster:** the browser downloads `styles.css` once and reuses it from its cache on every other page.
- **Clean HTML** with no styling mixed in.

A typical project folder looks like this:

```text
my-site/
├── index.html
├── about.html
└── css/
    └── styles.css
```

With that structure, the link is `<link rel="stylesheet" href="css/styles.css">`.

## Several stylesheets

You can link more than one file. They're applied in order, so later files can override earlier ones:

```html
<link rel="stylesheet" href="css/base.css">
<link rel="stylesheet" href="css/theme.css">
```

Many sites also load web fonts this way, for example a stylesheet from Google Fonts.

## Which one wins?

When the same property is set in more than one place for the same element and with equal selector strength, the order decides:

1. Browser default styles (weakest)
2. External and internal stylesheets, in the order they appear in the page
3. Inline `style` attributes (strongest)

```html
<style>
  p { color: blue; }
</style>
<p style="color: green;">I'm green: the inline style wins.</p>
<p>I'm blue.</p>
```

There's also `!important`, which forces a declaration to win (`color: red !important;`). Avoid it: it makes CSS very hard to manage, because the only way to beat it is another `!important`.

## When to use which

| Method | Use it for |
|---|---|
| External file | Almost everything on a real website |
| Internal `<style>` | Single-page demos, HTML emails, page-specific tweaks |
| Inline `style` | Quick tests, and styles set by JavaScript |

## Common mistakes

- **Wrong path in `href`**, so the stylesheet never loads and the page looks unstyled. Check the browser's developer tools (F12 → Network) for a red 404.
- **Writing `<style>` tags inside a `.css` file.** A CSS file contains only CSS.
- **Using `<link>` in the body.** Put stylesheets in the `<head>` so the page doesn't flash unstyled.
- **Overusing `!important`.**

## Exercise

1. Create a folder with `index.html` and `css/styles.css` on your computer.
2. Link the stylesheet and set a background colour on `body` and a colour on `h1`.
3. Create `about.html`, link the same stylesheet, and check both pages share the design.
4. Add an inline style to one paragraph and confirm it beats the stylesheet.

## Summary

- CSS can be inline (`style` attribute), internal (`<style>`) or external (`<link>` to a `.css` file).
- External stylesheets are the standard for real websites.
- Later rules beat earlier ones; inline styles beat stylesheets; avoid `!important`.

Next, you'll learn how selectors choose which elements a rule styles.
