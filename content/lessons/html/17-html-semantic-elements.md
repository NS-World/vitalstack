---
title: HTML Semantic Elements
slug: html-semantic-elements
subject: html
lesson: 17
difficulty: beginner
excerpt: Describe the parts of a page with header, nav, main, article, section, aside and footer, and see why meaningful HTML helps readers and search engines.
---

"Semantic" means "related to meaning". A semantic element tells the browser **what** its content is, not just how to show it. `<h1>` and `<p>` are semantic; `<div>` isn't. HTML has a set of semantic elements for the big parts of a page: the header, the menu, the main content, the sidebar and the footer.

## The page layout elements

Most websites follow the same pattern:

```html
<body>
  <header>
    <h1>My Coding Blog</h1>
    <nav>
      <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/blog/">Blog</a></li>
        <li><a href="/about/">About</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <article>
      <h2>How I learned HTML in 30 days</h2>
      <p>It started with one simple page…</p>
    </article>
  </main>

  <aside>
    <h3>Popular posts</h3>
    <ul>
      <li><a href="#">10 CSS tips</a></li>
    </ul>
  </aside>

  <footer>
    <p><small>© My Coding Blog</small></p>
  </footer>
</body>
```

Without any CSS this looks the same as a pile of divs, but the code now describes the page.

## What each element means

### header
Introductory content: a site logo and title, or the heading area of an article. A page can have several headers: one for the site and one inside each article.

### nav
A block of **major** navigation links: the main menu, a table of contents, pagination. Not every group of links needs `<nav>`; footer links to legal pages, for example, usually don't.

### main
The **main content** of the page, the part that's unique to it. There should be only one visible `<main>` per page, and it shouldn't include things repeated on every page like the site header, menu or footer.

### article
A self-contained piece of content that would make sense on its own, even if copied to another site: a blog post, a news story, a product card, a forum post, a comment.

### section
A thematic group of content, usually with its own heading: "Features", "Pricing", "FAQ". If you can't think of a heading for it, you probably want a `<div>` instead.

### aside
Content that's related but not essential: a sidebar, a "did you know?" box, related links, ads.

### footer
The closing part of a page or an article: copyright, contact details, related links, author info.

## Article or section?

A simple test: **would it make sense on its own?** Then it's an `<article>`. Is it one part of something bigger? Then it's a `<section>`. They can also be nested both ways:

```html
<article>
  <h2>Getting Started with Python</h2>
  <section>
    <h3>Installing Python</h3>
    <p>Download it from python.org…</p>
  </section>
  <section>
    <h3>Your first program</h3>
    <p>Open your editor and type print("Hello")…</p>
  </section>
</article>
```

## Other useful semantic elements

```html
<p>Published on <time datetime="2026-03-14">14 March</time></p>

<details>
  <summary>What is a semantic element?</summary>
  <p>An element whose name describes the meaning of its content.</p>
</details>

<address>
  Written by Asha. Contact: <a href="mailto:asha@example.com">asha@example.com</a>
</address>
```

- `<time>` marks a date or time; `datetime` gives it in a format computers understand.
- `<details>` and `<summary>` create a collapsible box with no JavaScript. Great for FAQs.
- `<address>` holds contact information for the author or owner.
- `<figure>` and `<figcaption>`, from the images lesson, are semantic too.

## Why semantic HTML matters

- **Accessibility.** Screen readers announce landmarks like "navigation" and "main", and let users jump straight to them. A "skip to content" experience comes almost for free.
- **Search engines** understand which part is the real content and which part is the menu or footer.
- **Readable code.** `</article>` tells you far more than the seventh `</div>` in a row.
- **Reader modes** in browsers use these elements to show a clean version of your page.

## Common mistakes

- **More than one `<main>`** on a page.
- **Wrapping everything in `<section>`**, even blocks with no heading. Use `<div>` for pure styling groups.
- **Using `<aside>` for main content**, just because it's displayed in a side column.
- **Forgetting headings** inside sections and articles.

## Exercise

Take any page you've built in this course (or start a new one) and:

1. Wrap the title and menu in `<header>` and `<nav>`.
2. Put the main content in `<main>` with one `<article>` and two `<section>`s inside it.
3. Add an `<aside>` with three related links.
4. Add a `<footer>` with a copyright line.
5. Add a FAQ with two `<details>` boxes.

## Summary

- Semantic elements describe meaning: `<header>`, `<nav>`, `<main>`, `<article>`, `<section>`, `<aside>`, `<footer>`.
- One `<main>` per page; `<article>` stands alone; `<section>` is a themed part with a heading.
- `<time>`, `<details>`/`<summary>` and `<address>` add meaning to smaller pieces.
- Semantic HTML helps accessibility, search engines and your own code.

Next, you'll add audio and video to your pages.
