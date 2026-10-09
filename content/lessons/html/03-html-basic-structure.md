---
title: HTML Basic Structure
slug: html-basic-structure
subject: html
lesson: 3
difficulty: beginner
excerpt: The outline every HTML page shares: doctype, html, head, title, meta tags and body, what each one does and the mistakes to avoid.
---

Every HTML page, from a one-line test to a huge website, starts from the same outline. Once you know it, you can start any new page in seconds and understand the source of pages you find online.

## The complete outline

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page title shown in the tab</title>
</head>
<body>

  <h1>Visible content goes here</h1>
  <p>Everything inside body appears on the page.</p>

</body>
</html>
```

Let's go through it line by line.

## The doctype

```html
<!DOCTYPE html>
```

The very first line tells the browser to use **modern HTML** (HTML5). Without it, browsers fall back to an old "quirks mode" where some layouts and styles behave differently. It is not a tag and has no closing part. Just put it at the top of every page.

## The html element

```html
<html lang="en">
  ...
</html>
```

`<html>` is the **root**: every other element lives inside it. The `lang` attribute says which language the page is written in. Screen readers use it to pronounce words correctly, and search engines use it to show your page to the right readers. Use `en` for English and `hi` for Hindi.

## The head: information about the page

The `<head>` holds details **about** the page. Nothing here appears in the page itself.

```html
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HTML Basic Structure</title>
</head>
```

- `<meta charset="UTF-8">` sets the character encoding, so letters like é, ñ and Hindi or emoji characters display correctly. Keep it as the first line inside `<head>`.
- `<meta name="viewport" ...>` makes the page fit phone screens instead of showing a tiny desktop version. Every page you build should have it.
- `<title>` sets the text in the browser tab, in bookmarks, and as the clickable headline in Google results. Make it short and describe the page.

Later you'll also put links to CSS files and other page information in the head.

## The body: everything visible

```html
<body>
  <h1>Welcome</h1>
  <p>Hello! This text is visible.</p>
</body>
```

Headings, paragraphs, images, links, tables and forms all go inside `<body>`. A page has exactly **one** `<head>` and **one** `<body>`.

## Indentation keeps code readable

Browsers ignore extra spaces and line breaks in your code, so these two are identical to the browser:

```html
<body><h1>Title</h1><p>Text</p></body>
```

```html
<body>
  <h1>Title</h1>
  <p>Text</p>
</body>
```

The second is far easier for people to read. Indent the contents of an element by two spaces, so you can see at a glance what is inside what.

## Comments

Comments are notes for yourself or other developers. The browser skips them.

```html
<!-- This is a comment. It does not appear on the page. -->
<p>This paragraph does appear.</p>
```

Use comments to explain sections of a long page, or to temporarily hide some HTML while testing.

## Common mistakes

- **Content in the head.** Visible content belongs in `<body>`. If you put a paragraph in `<head>`, the browser quietly moves it into the body to repair the page, so it may look fine while your HTML is actually invalid.
- **Forgetting the viewport meta tag.** The page looks fine on a computer but tiny on phones.
- **Two body elements.** Keep exactly one; put all content inside it.
- **Missing doctype.** The page may look slightly different in each browser.

## Exercise

Open the complete outline with **Try it Yourself** and:

1. Change the `<title>` to "My Practice Page". (The tab title isn't visible in the editor's result, but it will be in your own files.)
2. Add a comment above the heading that says what the page is for.
3. Add a second heading with `<h2>` and a paragraph under it.
4. Change `lang="en"` to `lang="hi"` and add a short Hindi sentence in a new paragraph.

## Summary

- Start every page with `<!DOCTYPE html>`.
- `<html lang="…">` wraps everything and states the language.
- `<head>` holds information about the page: charset, viewport and title.
- `<body>` holds everything the reader sees.
- Indent nested elements and use `<!-- comments -->` to keep code readable.

Next, you'll look closely at elements: opening tags, closing tags and how to nest them.
