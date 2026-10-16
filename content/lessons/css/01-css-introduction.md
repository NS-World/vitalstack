---
title: CSS Introduction
slug: css-introduction
subject: css
lesson: 1
difficulty: beginner
excerpt: What CSS is, how a CSS rule is built from a selector and declarations, and why separating style from HTML makes websites easier to build and change.
---

HTML gives a page its structure: headings, paragraphs, lists, forms. **CSS** (Cascading Style Sheets) controls how that structure looks: colours, fonts, spacing, layout, even animations. Every good-looking website you've ever used is HTML plus CSS.

## Before and after CSS

Here is a small page with no CSS at all:

```html
<h1>VitalStack</h1>
<p>Learn to code, one lesson at a time.</p>
<a href="#">Start learning</a>
```

It works, but it looks plain. Now the same HTML with a few lines of CSS:

```html
<style>
  body {
    font-family: system-ui, sans-serif;
    background: #f5f7fb;
    padding: 32px;
  }
  h1 {
    color: #2563eb;
  }
  a {
    display: inline-block;
    background: #2563eb;
    color: white;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
  }
</style>

<h1>VitalStack</h1>
<p>Learn to code, one lesson at a time.</p>
<a href="#">Start learning</a>
```

Open it with **Try it Yourself**. The HTML didn't change at all; CSS changed the font, the background, the heading colour, and turned a plain link into a button.

## Anatomy of a CSS rule

```css
h1 {
  color: #2563eb;
  font-size: 32px;
}
```

- `h1` is the **selector**: which elements the rule applies to.
- Everything inside `{ }` is the **declaration block**.
- `color: #2563eb;` is a **declaration**, made of a **property** (`color`) and a **value** (`#2563eb`), separated by a colon and ending with a semicolon.

A rule can have as many declarations as you like. Put each on its own line to keep it readable.

## What "cascading" means

Several rules can apply to the same element. The **cascade** is the set of rules that decides which one wins. In short:

1. More specific selectors beat less specific ones (an id beats a class, a class beats an element name).
2. If two rules are equally specific, the one that comes **later** wins.

```html
<style>
  p { color: gray; }
  p { color: green; }
</style>
<p>What colour am I?</p>
```

The paragraph is green, because the second rule comes later. You'll learn the full details in the Selectors lesson.

## Inheritance

Some properties, mainly text-related ones like `color`, `font-family` and `line-height`, are **inherited**: children take them from their parent. That's why setting a font on `body` changes the font of the whole page. Box-related properties such as `border`, `padding` and `background` are not inherited.

## Why keep style separate from HTML

You *could* style every element with a `style` attribute, but separating CSS from HTML has big advantages:

- **Change once, update everywhere.** One stylesheet can style hundreds of pages. Change a colour in one place and the whole site updates.
- **Cleaner HTML** that only describes content.
- **Faster sites:** the browser downloads the stylesheet once and caches it.
- **Different looks for different devices** (phone, desktop, print) from the same HTML.

## CSS comments

```css
/* Main heading colour */
h1 {
  color: #2563eb; /* brand blue */
}
```

Comments start with `/*` and end with `*/`. CSS has no `//` single-line comments.

## Common mistakes

- **Missing semicolons.** `color: red font-size: 20px;` breaks both declarations. End every declaration with `;`.
- **Missing closing brace** `}`, which can break every rule after it.
- **Spelling property names wrong** (`colour` instead of `color`). The browser silently ignores properties it doesn't know.
- **Spaces in values where they don't belong**, like `20 px`. Write `20px`.

## Exercise

Open the "after" example with **Try it Yourself** and:

1. Change the heading colour to any colour you like.
2. Change the background of the page to `white`.
3. Make the button's corners fully round with `border-radius: 999px;`.
4. Add a rule for `p` that sets `font-size: 18px;` and `color: #444;`.

## Summary

- CSS controls how HTML looks: colours, fonts, spacing and layout.
- A rule = selector + declaration block; a declaration = property + value.
- The cascade decides which rule wins; some properties are inherited.
- Keeping CSS separate from HTML makes sites easier to change and faster.

Next, you'll learn the three ways to add CSS to a page.
