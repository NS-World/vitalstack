---
title: HTML Attributes
slug: html-attributes
subject: html
lesson: 5
difficulty: beginner
excerpt: Attributes give elements extra information: href for links, src and alt for images, lang, title, id and class, with the rules for writing them correctly.
---

Tags tell the browser **what** something is. **Attributes** add the details: where a link goes, which image to show, what language the page is in. Almost every real page uses them, so this lesson covers the ones you'll need most.

## What an attribute looks like

```html
<a href="https://www.example.com">Visit Example</a>
```

- Attributes always go inside the **opening tag**.
- They're written as **name="value"**: here the name is `href` and the value is the web address.
- An element can have several attributes, separated by spaces.

## href: where a link goes

The `<a>` (anchor) element creates a link. Its `href` attribute holds the destination:

```html
<p><a href="https://developer.mozilla.org">Read the MDN Web Docs</a></p>
<p><a href="about.html">About this site</a></p>
<p><a href="https://developer.mozilla.org" target="_blank">Open MDN in a new tab</a></p>
```

- A full web address (`https://…`) links to another site.
- A file name like `about.html` links to a page in the same folder.
- `target="_blank"` opens the link in a new tab. Use it sparingly; readers generally prefer to choose that themselves.

## src and alt: showing an image

`<img>` needs two attributes:

```html
<img src="https://picsum.photos/320/180" alt="A sample landscape photo" width="320" height="180">
```

- `src` (source) is the address of the image file.
- `alt` (alternative text) describes the image. Screen readers read it aloud for blind users, it appears if the image fails to load, and search engines use it to understand the image. **Always write an `alt`.** If an image is purely decorative, use an empty one: `alt=""`.
- `width` and `height` (in pixels) let the browser reserve space before the image loads, so the page doesn't jump around.

## lang: the page language

```html
<html lang="en">
```

As you saw in the structure lesson, `lang` on the `<html>` element states the page's language. You can also mark a single phrase in another language:

```html
<p>The word for thank you in Hindi is <span lang="hi">धन्यवाद</span>.</p>
```

## title: extra information on hover

```html
<p><abbr title="HyperText Markup Language">HTML</abbr> is easy to learn.</p>
```

The `title` attribute shows a small tooltip when you hover over the element with a mouse. It's handy for abbreviations, but don't put important information only in a title: it isn't visible on phones.

## id and class: names for styling and scripts

```html
<h2 id="contact">Contact us</h2>
<p class="note">Replies within two days.</p>
<p class="note">We read every message.</p>
```

- `id` gives one element a **unique** name. No two elements on a page may share an id. You can link straight to it: `<a href="#contact">Jump to contact</a>`.
- `class` gives a **shared** name to any number of elements, so CSS can style them all at once.

You'll use `id` and `class` constantly in the CSS and JavaScript tutorials.

## style: quick inline styling

```html
<p style="color: green; font-size: 20px;">This text is green and larger.</p>
```

The `style` attribute applies CSS directly to one element. It's fine for quick experiments, but real pages keep their styles in CSS files, which you'll learn in the CSS tutorial.

## Rules for writing attributes

- **Use lowercase names**: `href`, not `HREF`.
- **Always quote values**: `href="page.html"`. Quotes are required as soon as a value contains a space, so quoting every value avoids mistakes.
- Use double quotes normally. If the value itself contains double quotes, wrap it in single quotes: `title='She said "hi"'`.
- **Boolean attributes** need no value. Their presence alone turns them on:

```html
<input type="checkbox" checked> I agree
<button disabled>Not available yet</button>
```

## Exercise

Open any example with **Try it Yourself** and build a small profile card:

1. An `<h2>` with your name and `id="profile"`.
2. An image with `src="https://picsum.photos/200"`, a meaningful `alt` text, and `width="200"`.
3. A paragraph that contains a link to your favourite website, opening in a new tab.
4. An abbreviation with a `title`, for example `<abbr title="Cascading Style Sheets">CSS</abbr>`.
5. A link at the top of the page that jumps to `#profile`.

## Summary

- Attributes add information to elements and always go in the **opening tag**.
- They're written as `name="value"`; use lowercase names and quote every value.
- Key attributes: `href` (links), `src` and `alt` (images), `lang`, `title`, `id`, `class`, `style`.
- Always give images a useful `alt` text.

Next up: headings and paragraphs, and how to structure the text of a page so it's easy to read.
