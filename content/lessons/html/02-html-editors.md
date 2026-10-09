---
title: HTML Editors
slug: html-editors
subject: html
lesson: 2
difficulty: beginner
excerpt: Write HTML on your own computer: create your first .html file in Notepad or TextEdit, then set up VS Code, the free editor most developers use.
---

You can write HTML in any program that saves plain text. In this lesson you'll create a real HTML file on your computer, open it in your browser, and then set up **Visual Studio Code**, the free editor most web developers use every day.

## Option 1: The editor you already have

Every computer comes with a simple text editor. It's enough for your first page.

### On Windows (Notepad)

1. Open the Start menu, type **Notepad** and open it.
2. Type or paste the page below.
3. Click **File → Save As**.
4. Choose a folder (for example, Desktop).
5. Set **Save as type** to **All files**.
6. Name the file `index.html` and set **Encoding** to **UTF-8**.
7. Click **Save**.

### On Mac (TextEdit)

TextEdit saves rich text by default, which breaks HTML. Change that first:

1. Open **TextEdit → Settings** (or Preferences).
2. On the **New Document** tab, select **Plain text**.
3. On the **Open and Save** tab, tick **Display HTML files as HTML code instead of formatted text**.
4. Create a new document, type the page below, and save it as `index.html`.

Here is the page to type:

```html
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>My Computer Page</title>
</head>
<body>
  <h1>It works!</h1>
  <p>I wrote this file on my own computer.</p>
</body>
</html>
```

## Open the file in a browser

Find `index.html` in the folder where you saved it and **double-click** it. It opens in your default browser and shows your heading and paragraph.

Every time you change the file:

1. Save it in the editor (`Ctrl + S`, or `Cmd + S` on Mac).
2. Switch to the browser and **refresh** (`F5`, or `Cmd + R` on Mac).

That save-and-refresh loop is how you'll work through this whole tutorial.

## Option 2: Visual Studio Code (recommended)

Notepad works, but a code editor makes HTML much easier. **Visual Studio Code** (VS Code) is free, works on Windows, Mac and Linux, and is used by most professional web developers.

What it does for you:

- **Colours** your code, so tags, text and attributes are easy to tell apart.
- **Auto-completes** tags: type `<p` and it suggests the rest.
- **Closes tags** for you, so you don't forget `</p>`.
- **Emmet shortcut**: in a new `.html` file, type `!` and press `Tab` to get a full page outline instantly.

### Set it up

1. Download VS Code from the official site, **code.visualstudio.com**, and install it.
2. Create a folder for your practice files, for example `html-practice`.
3. In VS Code choose **File → Open Folder** and select that folder.
4. Click the **New File** icon and name it `index.html`.
5. Type `!` then press `Tab`. VS Code writes the page outline for you.
6. Add `<h1>Hello from VS Code</h1>` inside the `<body>`, then save.

### Live Server: refresh automatically

The **Live Server** extension reloads the browser every time you save:

1. Click the **Extensions** icon on the left (four squares).
2. Search for **Live Server** and click **Install**.
3. Right-click `index.html` and choose **Open with Live Server**.

Now every save updates the browser by itself, with no manual refresh needed.

## Practising on VitalStack

Every example in these lessons has a **Try it Yourself** button. It opens an editor right here in your browser: change the code on the left, press **Run**, and see the result on the right. It's the quickest way to test a small idea. For anything you want to keep, use your own files.

## Common mistakes

- **The page shows the code instead of the result.** The file was saved as `.txt` or rich text. Make sure the name ends in `.html` and the format is plain text.
- **Strange characters (Ã© instead of é).** Save the file as **UTF-8** and keep the `<meta charset="UTF-8">` line in the `<head>`.
- **Changes don't appear.** You forgot to save, or to refresh the browser.

## Exercise

1. Create a folder called `html-practice` with an `index.html` file inside it.
2. Write a page with your name as the `<h1>` and one paragraph about yourself.
3. Open it in the browser, change the paragraph, save, and refresh to see the update.

## Summary

- HTML files are plain text files ending in `.html`.
- You can write them in Notepad or TextEdit, but **VS Code** is free and much easier.
- Open the file in a browser to see the result; save and refresh after every change.
- Save files as **UTF-8** to avoid broken characters.

Next, you'll learn what each part of the basic page structure means.
