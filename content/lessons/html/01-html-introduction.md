---
title: HTML Introduction
slug: html-introduction
subject: html
lesson: 1
difficulty: beginner
excerpt: What HTML is, what a web page is made of, and your first complete HTML page that you can edit and run right away.
---

HTML is the language every web page is built with. When you open any website, your browser receives HTML and turns it into the headings, paragraphs, links and images you see. If you want to build websites, HTML is the first thing to learn, and it is also the easiest.

In this lesson you will learn what HTML is, how it works, and you will write your first web page.

## What is HTML?

**HTML** stands for **HyperText Markup Language**.

- **HyperText** means text that contains links to other pages. Links are what turn separate pages into the web.
- **Markup** means you "mark up" ordinary text with labels, called **tags**, that tell the browser what each piece of text is: a heading, a paragraph, a link, a list.
- **Language** means it follows rules, so every browser understands it the same way.

HTML is not a programming language. It has no calculations, no conditions and no loops. It only **describes the structure** of a page. That's why beginners can learn it in a few days.

## Your first HTML page

Here is a complete web page. Click **Try it Yourself** to open it in the editor, change the text, and press **Run**.

```html
<!DOCTYPE html>
<html>
<head>
  <title>My First Page</title>
</head>
<body>

  <h1>Hello, world!</h1>
  <p>This is my first web page.</p>

</body>
</html>
```

The browser shows a big heading and a paragraph underneath. The tags themselves, the words in angle brackets, are never shown. They only tell the browser how to display the content.

## How the example works

Every line has a job:

- `<!DOCTYPE html>` tells the browser this is a modern HTML document.
- `<html>` is the root element. Everything else goes inside it.
- `<head>` holds information **about** the page, such as its title. It is not shown on the page itself.
- `<title>` sets the text shown in the browser tab and in search results.
- `<body>` holds everything that is **visible** on the page.
- `<h1>` is a main heading.
- `<p>` is a paragraph.

You'll learn each of these in detail in the next lessons. For now, notice the pattern: most content sits between an **opening tag** like `<p>` and a **closing tag** like `</p>`. The closing tag has a forward slash.

## What does a browser do with HTML?

1. You visit a web address.
2. The server sends back an HTML file, which is plain text.
3. The browser reads the tags from top to bottom.
4. It builds the page you see on screen.

You can see the HTML of any page yourself: right-click a page and choose **View page source** (or press `Ctrl + U`). Try it on this page.

## HTML, CSS and JavaScript

Almost every website uses three languages together:

- **HTML** gives the page its **structure**: headings, paragraphs, images, links.
- **CSS** controls the **look**: colours, fonts, spacing, layout.
- **JavaScript** adds **behaviour**: buttons that react, menus that open, content that updates.

A simple way to remember it: HTML is the skeleton, CSS is the clothes, and JavaScript is the movement. This tutorial starts with HTML because the other two are built on top of it.

## What you need

Nothing to buy and nothing to install:

- A **text editor** to write HTML. Any computer already has one (Notepad on Windows, TextEdit on Mac), and the next lesson shows a better free one.
- A **web browser** to view the result: Chrome, Firefox, Edge or Safari.

You can also practise directly on this site: every example has a **Try it Yourself** button.

## Exercise

Open the first example with **Try it Yourself** and make these changes, pressing **Run** after each one:

1. Change the heading to your own name.
2. Change the paragraph to say why you want to learn web development.
3. Add a second paragraph below the first one. (Hint: copy the whole `<p>…</p>` line.)

If all three show up on the right, you have just edited your first web page.

## Summary

- HTML is the language that describes the **structure** of web pages.
- Content is marked up with **tags**, usually in pairs like `<p>` and `</p>`.
- Every page has the same basic outline: `<!DOCTYPE html>`, `<html>`, `<head>` and `<body>`.
- HTML handles structure, CSS handles style, JavaScript handles behaviour.

In the next lesson you'll set up a free code editor and create an HTML file on your own computer.
