---
title: Where to Put JavaScript
slug: js-where-to-put-javascript
subject: javascript
lesson: 2
difficulty: beginner
excerpt: Add JavaScript with script elements and external .js files, understand why placement matters, and load scripts correctly with defer.
---

Just like CSS, JavaScript can live inside the HTML page or in a separate file. Where and how you load it affects whether it works at all, and how fast your page appears.

## Inline script element

The simplest way is a `<script>` element with code inside it:

```html
<p id="msg">Waiting…</p>

<script>
  document.getElementById("msg").textContent = "JavaScript is running!";
</script>
```

The browser reads the page from top to bottom. When it reaches `<script>`, it stops, runs the code, and then continues.

## Why order matters

A script can only find elements that the browser has **already read**. This fails:

```html
<script>
  // The paragraph doesn't exist yet when this runs
  const p = document.getElementById("late");
  console.log(p); // null
</script>

<p id="late">I come after the script.</p>
```

`getElementById` returns `null` ("nothing"), and trying to change `null` causes an error. That's why scripts used to be placed at the **end of the body**, after all the HTML:

```html
<body>
  <h1>My page</h1>
  <p id="intro">Hello</p>

  <script>
    document.getElementById("intro").textContent = "Found it!";
  </script>
</body>
```

## External JavaScript files

On real websites, JavaScript goes in its own file, for example `js/main.js`:

```js
// js/main.js
console.log("main.js loaded");
```

Link it with the `src` attribute:

```html
<script src="js/main.js"></script>
```

- A `<script>` with `src` must be **empty**; any code between the tags is ignored.
- The closing `</script>` tag is required.

Benefits are the same as for external CSS: one file shared by every page, cached by the browser, and clean HTML.

## defer: the modern best practice

Put the script in the `<head>` with the `defer` attribute:

```html
<head>
  <meta charset="UTF-8">
  <title>My page</title>
  <script src="js/main.js" defer></script>
</head>
```

`defer` tells the browser:

1. Download the file **in the background** while reading the HTML (no pause).
2. Run it only **after** the whole page has been read, so every element exists.
3. Run several deferred scripts in the order they appear.

This gives you the best of both: the download starts early, and your code can safely find any element. Use `defer` for almost all your scripts.

## async

```html
<script src="https://example.com/analytics.js" async></script>
```

`async` also downloads in the background, but runs the script **as soon as it arrives**, possibly before the page is finished and in any order. It's for independent scripts that don't touch your page's elements, such as analytics or ads.

| Attribute | Downloads | Runs | Order kept |
|---|---|---|---|
| none | Pauses the page | Immediately | Yes |
| `defer` | In background | After HTML is read | Yes |
| `async` | In background | As soon as downloaded | No |

## The DOMContentLoaded event

If you must use a script in the head without `defer` (for example, inline code), wait for the page to be ready:

```html
<script>
  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("box").textContent = "The page is ready.";
  });
</script>
<p id="box">Loading…</p>
```

The code inside runs once the whole HTML has been read.

## JavaScript modules

Modern code is often split into modules that import from each other:

```html
<script type="module" src="js/app.js"></script>
```

Module scripts are deferred automatically and can use `import` and `export`. You'll meet them later; for now, `defer` is all you need.

## Inline event attributes (avoid)

Old tutorials often show this:

```html
<button onclick="alert('Hi!')">Old style</button>
```

It works, but mixing code into HTML is hard to maintain and blocked by strict security settings. Use `addEventListener` in your script instead, as you'll learn in the Events lesson.

## Common mistakes

- **Script before the elements it uses**, without `defer`: `null` errors.
- **Code inside a `<script src="…">`**, which is silently ignored.
- **Wrong file path** in `src`. Check the console and Network tab (F12) for a 404 error.
- **Forgetting `</script>`**: `<script src="x.js" />` is not valid.

## Exercise

1. Make a page with a paragraph and an inline script at the end of the body that changes its text.
2. Move the script into `main.js`, link it in the head **without** `defer`, and see the error in the console.
3. Add `defer` and check that it works again.
4. Add `console.log("one")` in one deferred file and `console.log("two")` in a second; confirm the order.

## Summary

- JavaScript goes in `<script>` elements or external `.js` files linked with `src`.
- Scripts can only find elements the browser has already read.
- Use `<script src="…" defer>` in the head: it downloads early and runs when the page is ready.
- Use `async` only for independent scripts like analytics.

Next, you'll learn the different ways to show output from JavaScript.
