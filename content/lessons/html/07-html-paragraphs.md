---
title: HTML Paragraphs
slug: html-paragraphs
subject: html
lesson: 7
difficulty: beginner
excerpt: How the p element works, why browsers ignore your extra spaces, and when to use line breaks, horizontal rules and pre for preformatted text.
---

Most of the text on the web lives in paragraphs. In this lesson you'll learn how the `<p>` element works, how browsers treat spaces and line breaks in your code, and three related elements: `<br>`, `<hr>` and `<pre>`.

## The p element

A paragraph starts with `<p>` and ends with `</p>`:

```html
<p>This is the first paragraph. It can be as long as you like.</p>
<p>This is the second paragraph. Browsers add space above and below each one.</p>
```

Each paragraph is a **block**: it starts on a new line and takes the full width available. The browser adds a margin before and after it, so paragraphs are visually separated without any extra work.

## Browsers collapse white space

This surprises almost every beginner. Extra spaces and line breaks in your HTML code are **ignored**. Any run of spaces, tabs and new lines is shown as a single space.

```html
<p>
  This     paragraph
  has lots of      spaces
  and line breaks      in the code.
</p>
```

In the browser, this shows as one normal line of text: "This paragraph has lots of spaces and line breaks in the code."

This is actually useful: you can indent and wrap your code however you like to keep it readable, and the page still looks clean.

## Line breaks with br

To break a line **inside** a paragraph without starting a new paragraph, use `<br>`:

```html
<p>
  VitalStack<br>
  Learn to code, one lesson at a time<br>
  contact@example.com
</p>
```

`<br>` is an empty element: it has no closing tag. Use it where a line break is part of the content, such as addresses, poems or song lyrics.

Don't use several `<br>` tags to create space between blocks. That's a job for separate paragraphs or for CSS margins.

```html
<!-- Avoid -->
<p>First idea.<br><br><br>Second idea.</p>

<!-- Better -->
<p>First idea.</p>
<p>Second idea.</p>
```

## Horizontal rules with hr

`<hr>` marks a **change of topic** between paragraphs. Browsers draw it as a horizontal line.

```html
<p>That's everything about the morning schedule.</p>
<hr>
<p>Now let's look at the afternoon.</p>
```

Like `<br>`, it has no closing tag. Use it for a thematic break, not as decoration. If you just want a line for looks, add a border with CSS later.

## Preformatted text with pre

Sometimes spaces and line breaks **do** matter, for example in ASCII art, poems laid out in a special way, or plain-text output. The `<pre>` element keeps every space and line break exactly as you typed it, and shows the text in a monospace font.

```html
<pre>
Name      Score
Asha        92
Rohan       87
Meera       95
</pre>
```

The columns line up because nothing is collapsed. `<pre>` is also commonly used together with `<code>` to show blocks of program code.

## Choosing the right element

| You want | Use |
|---|---|
| A block of text | `<p>` |
| A line break inside the same block (address, poem) | `<br>` |
| A change of topic | `<hr>` |
| Text where spacing matters | `<pre>` |
| More space between blocks | CSS margin (not `<br>`) |

## Common mistakes

- **Expecting Enter to make a new line.** Pressing Enter in your code does nothing on the page. Use a new `<p>` or a `<br>`.
- **Using `<br><br>` for spacing.** Make separate paragraphs instead.
- **Putting a heading or list inside `<p>`.** A paragraph can only contain text and inline elements. The browser will close the paragraph early and your layout will break.
- **Forgetting the closing `</p>`.** Browsers often guess correctly, but not always. Close every paragraph.

## Exercise

1. Write three short paragraphs about your favourite food.
2. Add your (made-up) postal address using one paragraph and `<br>` tags.
3. Separate two topics with an `<hr>`.
4. Make a small table of three friends and their ages using `<pre>`, and line up the columns with spaces.

## Summary

- `<p>` creates a paragraph; browsers add space around it.
- Extra spaces and new lines in your code collapse into one space.
- `<br>` breaks a line inside a block; `<hr>` marks a change of topic.
- `<pre>` keeps spacing exactly as written, in a monospace font.

Next, you'll learn how to make text bold, italic, highlighted and more with text formatting elements.
