---
title: HTML Links
slug: html-links
subject: html
lesson: 9
difficulty: beginner
excerpt: Create links with the a element: absolute and relative URLs, opening links in a new tab safely, linking to a section, email and phone links, and writing good link text.
---

Links are what make the web a *web*. With one element, `<a>`, you can connect a page to any other page in the world, to a section of the same page, to an email address or to a phone number.

## The a element

```html
<a href="https://developer.mozilla.org/">MDN Web Docs</a>
```

- `<a>` stands for **anchor**.
- `href` (hypertext reference) is the address the link goes to.
- The text between the tags is what the reader sees and clicks.

By default, browsers show unvisited links blue and underlined, and visited links purple. You'll restyle them with CSS later.

## Absolute and relative URLs

An **absolute URL** contains the full address, including `https://` and the domain. Use it to link to other websites:

```html
<a href="https://www.python.org/">Python's official site</a>
```

A **relative URL** is a path from the current page. Use it for pages on your own site:

```html
<a href="about.html">About (same folder)</a>
<a href="blog/first-post.html">First post (inside the blog folder)</a>
<a href="../index.html">Home (one folder up)</a>
<a href="/contact/">Contact (from the site root)</a>
```

- No slash at the start: relative to the current folder.
- `../` goes up one folder.
- A slash at the start (`/contact/`) starts from the root of the website.

Relative links keep working if you move the whole site to another domain, which is why sites use them internally.

## Opening a link in a new tab

```html
<a href="https://github.com/" target="_blank" rel="noopener noreferrer">GitHub</a>
```

`target="_blank"` opens the link in a new tab. Add `rel="noopener noreferrer"` so the new page can't control your page through JavaScript (modern browsers do this automatically, but it's still a good habit).

Use new tabs sparingly. Readers can open a new tab themselves; forcing it can be confusing, especially on phones. A good rule: open in a new tab only when leaving the page would lose the reader's work, e.g. a link inside a half-filled form.

## Linking to a section of a page

Give an element an `id`, then link to it with `#`:

```html
<a href="#faq">Jump to the FAQ</a>

<!-- ... lots of content ... -->

<h2 id="faq">Frequently asked questions</h2>
```

Clicking the link scrolls straight to the heading. You can also combine it with a page address: `href="guide.html#faq"`. The "On this page" box on VitalStack lessons works exactly like this.

## Email and phone links

```html
<a href="mailto:contact@example.com">Email us</a>
<a href="tel:+911234567890">Call us</a>
```

`mailto:` opens the reader's email app with the address filled in. `tel:` starts a call on phones. Note that email addresses in page code can be collected by spam bots, so many sites use a contact form instead.

## Download links

```html
<a href="files/cheatsheet.pdf" download>Download the cheat sheet</a>
```

The `download` attribute asks the browser to save the file instead of opening it.

## Write link text that makes sense on its own

Screen reader users often list all the links on a page, and search engines use link text to understand the target page. "Click here" tells them nothing.

```html
<!-- Avoid -->
<p>To learn about images, <a href="html-images.html">click here</a>.</p>

<!-- Better -->
<p>Next, learn how to <a href="html-images.html">add images to a page</a>.</p>
```

## The title attribute

```html
<a href="html-forms.html" title="Lesson 15: HTML Forms">Forms</a>
```

`title` shows a small tooltip on hover. It's a nice extra, but don't put important information only there: phone users and many screen reader users never see it.

## Common mistakes

- **Missing `https://`** in external links: `href="google.com"` is treated as a file in your own folder.
- **"Click here" link text.** Describe the destination instead.
- **Spaces in file names** like `my page.html`. Use hyphens: `my-page.html`.
- **Linking to an `id` that doesn't exist** or has different spelling. `#FAQ` and `#faq` are different.

## Exercise

1. Make a link to your favourite website that opens in the same tab.
2. Make a second link that opens in a new tab with the safe `rel` value.
3. Add three `<h2>` sections with ids, and a small "table of contents" at the top with links to each.
4. Add an email link that says "Write to me".

## Summary

- `<a href="...">text</a>` creates a link.
- Use absolute URLs for other sites and relative URLs inside your own site.
- `#id` links jump to a section; `mailto:` and `tel:` start emails and calls.
- Write link text that describes where the link goes.

Next, you'll add pictures to your pages with images.
