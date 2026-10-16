---
title: HTML Div and Span
slug: html-div-and-span
subject: html
lesson: 13
difficulty: beginner
excerpt: Block and inline elements explained, and how the generic div and span containers group content for styling and scripting.
---

So far, every element you've used has a meaning: a heading, a paragraph, a link. Sometimes you just need a container to group things together so you can style or move them. That's what `<div>` and `<span>` are for. To use them well, you first need to understand block and inline elements.

## Block elements

A **block** element starts on a new line and stretches across the full width available. Paragraphs, headings, lists and tables are all block elements.

```html
<h3>A heading</h3>
<p>A paragraph.</p>
<p>Another paragraph.</p>
```

Each one sits on its own line, stacked from top to bottom.

## Inline elements

An **inline** element stays inside the flow of text. It only takes as much width as its content and doesn't start a new line. Links, `<strong>`, `<em>`, `<code>` and `<img>` are inline.

```html
<p>This sentence has a <a href="#">link</a>, some <strong>bold text</strong> and <code>code</code> in it.</p>
```

All of these stay on the same line as the surrounding text.

A simple rule: **block elements can contain inline elements, but inline elements shouldn't contain block elements**. For example, don't put a `<p>` inside a `<strong>`.

## The div element

`<div>` (division) is a generic **block** container. It has no meaning and no default style; it simply groups other elements.

```html
<div style="background: #eef6ff; padding: 16px; border-radius: 8px;">
  <h3>Tip of the day</h3>
  <p>Save your work often. Press Ctrl + S.</p>
</div>
```

Here the div groups a heading and a paragraph into one box so they can share a background colour. The `style` attribute adds a little CSS; you'll learn proper ways to do this in the CSS course.

Divs are used for:

- Grouping elements to style them together (cards, boxes, columns).
- Building page layouts with CSS Flexbox or Grid.
- Giving JavaScript one element to show, hide or update.

## The span element

`<span>` is a generic **inline** container. Use it to style or target a few words inside a line of text:

```html
<p>Your order status: <span style="color: green; font-weight: bold;">Delivered</span></p>
<p>Total: <span id="total">₹0</span></p>
```

The first span colours one word. The second gives JavaScript a handle: a script could find `#total` and update the price without touching the rest of the paragraph.

## Div vs span

| | `<div>` | `<span>` |
|---|---|---|
| Type | Block | Inline |
| Starts a new line | Yes | No |
| Typical use | Group sections, boxes, layout | Style or target words in a line |

## Use meaningful elements first

Div and span have **no meaning**. Before reaching for them, ask: is there an element that describes this content?

```html
<!-- Avoid: a div pretending to be a heading -->
<div style="font-size: 24px; font-weight: bold;">Our team</div>

<!-- Better -->
<h2>Our team</h2>
```

HTML also has meaningful containers like `<header>`, `<nav>`, `<main>`, `<article>`, `<section>` and `<footer>`. You'll learn them in the Semantic Elements lesson. Use those when they fit; use a div only when nothing else does.

Similarly, never build a clickable "button" from a div or span. Use a real `<button>`, which works with the keyboard and screen readers automatically.

## A small card layout

```html
<div style="border: 1px solid #ccc; border-radius: 8px; padding: 16px; max-width: 280px;">
  <img src="https://picsum.photos/id/20/280/160" alt="A laptop on a desk" width="280" height="160">
  <h3>Learn HTML</h3>
  <p>20 lessons · <span style="color: #2563eb;">Beginner</span></p>
  <a href="#">Start learning</a>
</div>
```

One div wraps the whole card; one span highlights a single word inside the text.

## Common mistakes

- **"Divitis":** wrapping everything in many layers of divs. Keep the structure as simple as possible.
- **Divs instead of headings, lists or buttons.** Use the element with the right meaning.
- **Putting a div inside a span or a paragraph.** A block can't go inside inline content.

## Exercise

1. Create two "tip boxes" with divs, each with a different background colour, a heading and a paragraph.
2. In a sentence, use spans to colour the words "red", "green" and "blue" in their own colours.
3. Build a profile card for yourself like the example above.

## Summary

- Block elements start on a new line and fill the width; inline elements stay in the text.
- `<div>` is a generic block container; `<span>` is a generic inline container.
- Neither has any meaning, so use meaningful elements first.
- Divs and spans are mainly used as hooks for CSS and JavaScript.

Next, you'll learn how to name elements with classes and ids.
