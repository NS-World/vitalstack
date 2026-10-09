---
title: HTML Elements
slug: html-elements
subject: html
lesson: 4
difficulty: beginner
excerpt: How HTML elements work: opening and closing tags, content, nesting elements correctly, empty elements like br and img, and block versus inline.
---

A web page is made of **elements**. Each heading, paragraph, link and image is one element. In this lesson you'll learn how elements are written, how they fit inside each other, and the few rules that keep a page from breaking.

## Anatomy of an element

Most elements have three parts:

```html
<p>This is a paragraph.</p>
```

- `<p>` is the **opening tag**: the element's name in angle brackets.
- `This is a paragraph.` is the **content**.
- `</p>` is the **closing tag**: the same name with a forward slash.

Together, the opening tag, the content and the closing tag form one **element**. People often say "tag" when they mean element; the meaning is usually clear from context.

## Some elements you'll use constantly

```html
<h1>Main heading</h1>
<h2>Smaller heading</h2>
<p>A paragraph of text.</p>
<a href="https://example.com">A link</a>
<strong>Important text</strong>
<em>Emphasised text</em>
```

You'll learn each of these properly in later lessons. Open the example with **Try it Yourself** to see how each one looks.

## Nesting elements

Elements can contain other elements. This is called **nesting**:

```html
<p>Learning <strong>HTML</strong> is <em>fun</em>.</p>
```

Here `<strong>` and `<em>` sit **inside** the paragraph. The whole page is nested too: `<body>` is inside `<html>`, headings and paragraphs are inside `<body>`.

### The one rule of nesting

**Close elements in the reverse order you opened them.** The last one opened is the first one closed.

```html
<!-- Correct -->
<p>This is <strong>very important</strong>.</p>

<!-- Wrong: strong opened inside p, but p closes first -->
<p>This is <strong>very important.</p></strong>
```

Browsers try to repair wrong nesting, but each browser repairs it differently, and the result is often not what you meant. Correct nesting avoids those surprises. VS Code's auto-closing and indentation make wrong nesting easy to spot.

## Empty elements

Some elements have no content and therefore no closing tag. They're called **empty** (or void) elements:

```html
<p>First line<br>Second line</p>
<hr>
<img src="https://picsum.photos/200/120" alt="A random sample photo">
```

- `<br>` inserts a line break inside text.
- `<hr>` draws a horizontal line, used to separate sections.
- `<img>` displays an image; you'll learn its `src` and `alt` attributes in the next lesson.

You may see these written as `<br />` in older code. Both forms work in HTML5; this tutorial uses the shorter `<br>`.

## Don't skip closing tags

Browsers often show a page correctly even when you forget a closing tag:

```html
<p>First paragraph
<p>Second paragraph
```

It looks fine here, but missing closing tags cause hard-to-find layout bugs as pages grow, especially once you add CSS. **Always close every element that has a closing tag.**

## Upper or lower case?

`<P>` and `<p>` mean the same thing; tag names are not case-sensitive. The convention, and what you'll see in almost all modern code, is **lowercase**. Stick to it.

## Block and inline elements

Elements display in one of two basic ways:

- **Block** elements start on a new line and take the full width available: `<h1>` to `<h6>`, `<p>`, `<div>`, `<ul>`, `<table>`.
- **Inline** elements stay inside the line of text and take only the space they need: `<a>`, `<strong>`, `<em>`, `<span>`, `<img>`.

```html
<p>This paragraph is a block.</p>
<p>So is this one. It starts on a new line.</p>
<p>Inline elements like <strong>strong</strong> and <a href="#">links</a> stay within the line.</p>
```

Two generic elements have no meaning of their own and are used to group content for styling later: `<div>` (block) and `<span>` (inline). You'll use them a lot once you start CSS.

## Exercise

Use **Try it Yourself** on any example above and build this:

1. An `<h1>` with the title "My Favourite Things".
2. A paragraph that contains one word in `<strong>` and one in `<em>`.
3. A `<hr>` line.
4. A paragraph with a line break (`<br>`) in the middle.
5. Check your nesting: every element closes in the reverse order it opened.

## Summary

- An element is usually an opening tag, content and a closing tag.
- Elements can be **nested**; close them in reverse order.
- **Empty elements** like `<br>`, `<hr>` and `<img>` have no closing tag.
- Always close elements, and write tag names in lowercase.
- **Block** elements start on a new line; **inline** elements flow within text.

Next, you'll learn attributes, which give elements extra information, like where a link goes or which image to show.
