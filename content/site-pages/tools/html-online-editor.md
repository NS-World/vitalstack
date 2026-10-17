---
title: HTML, CSS and JavaScript Online Editor
slug: html-online-editor
parent: tools
template: page-tool.php
icon: 🌐
order: 50
excerpt: Write HTML, CSS and JavaScript online and see the result instantly. A free live editor for learning web development.
---
## How to use the online editor

Switch between the **HTML**, **CSS** and **JS** tabs and type. The result on the right updates half a second after you stop typing, or press **Run** (Ctrl + Enter). Your code is saved on this device, so you can come back to it later.

## Ideas to practise

1. Build a profile card: a photo, your name, a short bio and three skill badges.
2. Make a button that changes the page's background colour each time you click it.
3. Create a to-do list: an input, an Add button, and items you can click to strike through.
4. Recreate a layout with [Flexbox](/tutorial-category/css/) or CSS Grid.

## A tiny example

```html
<input id="name" placeholder="Your name">
<p id="hello"></p>
<script>
  document.getElementById("name").addEventListener("input", (e) => {
    document.getElementById("hello").textContent = "Hello, " + e.target.value + "!";
  });
</script>
```

## Good to know

- The preview runs in a sandbox, so your scripts can't affect this website.
- `alert()` and `console.log()` work; use your browser's developer tools (F12) to see console messages.
- New to the web? Start with the [HTML tutorials](/tutorial-category/html/), then [CSS](/tutorial-category/css/) and [JavaScript](/tutorial-category/javascript/).
