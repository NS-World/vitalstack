---
title: HTML Cheat Sheet
slug: html-cheat-sheet
parent: cheat-sheets
template: page-cheat-sheet.php
icon: 🧱
order: 40
excerpt: Every HTML tag you need on one page - page structure, text, links, images, lists, tables, forms, inputs, semantic layout, media and accessibility. Printable.
---
## Page skeleton

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Page title</title>
  <meta name="description" content="Shown in search results">
  <link rel="stylesheet" href="style.css">
  <script src="app.js" defer></script>
</head>
<body>
  …
</body>
</html>
```

## Text

| Tag | Use |
|---|---|
| `<h1>`…`<h6>` | headings (one h1 per page) |
| `<p>` | paragraph |
| `<br>` / `<hr>` | line break / thematic break |
| `<strong>` / `<em>` | important / emphasis |
| `<mark>`, `<small>`, `<del>`, `<ins>` | highlight, fine print, deleted, inserted |
| `<sub>` / `<sup>` | H₂O / x² |
| `<code>`, `<pre>`, `<kbd>` | code, preformatted, keys |
| `<blockquote>`, `<q>`, `<cite>` | quotes |
| `<abbr title="…">` | abbreviation |

## Links and images

```html
<a href="https://example.com">External</a>
<a href="/about.html">Same site</a>
<a href="#faq">Jump to section</a>
<a href="mailto:hi@example.com">Email</a> <a href="tel:+919999999999">Call</a>
<a href="report.pdf" download>Download</a>
<a href="…" target="_blank" rel="noopener">New tab</a>

<img src="photo.webp" alt="Describe the image" width="600" height="400" loading="lazy">
<figure><img src="chart.png" alt="…"><figcaption>Caption</figcaption></figure>
```

## Lists

```html
<ul><li>Bullet</li></ul>
<ol start="3"><li>Numbered</li></ol>
<dl><dt>Term</dt><dd>Description</dd></dl>
```

## Tables

```html
<table>
  <caption>Marks</caption>
  <thead><tr><th scope="col">Name</th><th scope="col">Marks</th></tr></thead>
  <tbody><tr><td>Asha</td><td>88</td></tr></tbody>
  <tfoot><tr><td colspan="2">Average: 80</td></tr></tfoot>
</table>
```

## Forms

```html
<form action="/signup" method="post">
  <label for="email">Email</label>
  <input id="email" name="email" type="email" required>

  <fieldset>
    <legend>Level</legend>
    <label><input type="radio" name="level" value="beginner" checked> Beginner</label>
    <label><input type="radio" name="level" value="pro"> Pro</label>
  </fieldset>

  <label><input type="checkbox" name="terms" required> I agree</label>
  <select name="course"><option value="java">Java</option></select>
  <textarea name="msg" rows="4"></textarea>
  <button type="submit">Send</button>
</form>
```

| Input type | For |
|---|---|
| `text`, `email`, `password`, `tel`, `url`, `search` | text-like |
| `number`, `range` | numbers (`min`, `max`, `step`) |
| `date`, `time`, `datetime-local`, `month` | dates |
| `checkbox`, `radio` | choices |
| `file` | uploads (`accept`, `multiple`) |
| `color`, `hidden` | colour picker, hidden value |

Validation: `required`, `minlength`, `maxlength`, `min`, `max`, `pattern`, `type`.

## Semantic layout

```html
<header>Logo + <nav>menu</nav></header>
<main>
  <article><h2>Post</h2><section>…</section></article>
  <aside>Related</aside>
</main>
<footer>© 2026</footer>
```

Also: `<details><summary>Question</summary>Answer</details>`, `<time datetime="2026-10-17">`, `<address>`.

## Media

```html
<video controls width="640" poster="thumb.jpg">
  <source src="intro.mp4" type="video/mp4">
  <track kind="captions" src="intro.vtt" srclang="en" label="English">
</video>
<audio controls src="podcast.mp3"></audio>
<iframe src="https://www.youtube.com/embed/ID" title="Video title" loading="lazy" allowfullscreen></iframe>
```

## Accessibility checklist

- Use real elements: `<button>` for actions, `<a>` for navigation.
- Every image has `alt` (empty `alt=""` for decorative).
- Every input has a `<label>`.
- One `<h1>`, headings in order.
- Set `lang` on `<html>`; meaningful link text.
- Visible focus styles; colour isn't the only signal.
- ARIA only when no HTML element fits.

## Global attributes

`id`, `class`, `style`, `title`, `lang`, `hidden`, `tabindex`, `data-*`, `aria-*`
