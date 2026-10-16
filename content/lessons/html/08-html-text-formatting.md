---
title: HTML Text Formatting
slug: html-text-formatting
subject: html
lesson: 8
difficulty: beginner
excerpt: Bold, italic, highlighted, small, deleted, inserted, subscript and superscript text, and the difference between strong and b, em and i.
---

HTML has a set of small elements that change how a few words look or what they mean: important words, emphasis, highlighted text, chemical formulas and more. These are **inline** elements: they sit inside a line of text instead of starting a new block.

## Strong and em: meaning

```html
<p><strong>Warning:</strong> turn off the power before opening the case.</p>
<p>I <em>really</em> want to learn JavaScript this month.</p>
```

- `<strong>` marks text as **important**. Browsers show it in bold.
- `<em>` marks **emphasis**, the stress you'd put on a word when speaking. Browsers show it in italics.

Screen readers can change their voice for these elements, and search engines understand that the words matter. Emphasis changes meaning, too: "I *really* want" is different from "*I* really want".

## B and i: style without extra importance

```html
<p>The <b>Pro</b> plan includes everything in Basic.</p>
<p>The word <i lang="fr">bonjour</i> means hello.</p>
```

- `<b>` makes text bold **without** saying it is important, e.g. product names or key words in a summary.
- `<i>` sets text apart without emphasis: foreign words, technical terms, names of ships or books, thoughts.

**Rule of thumb:** if you'd stress it when reading aloud, use `<em>`; if it's important, use `<strong>`. If it only needs to look different, use `<b>` or `<i>`.

## Highlighted, small, deleted and inserted text

```html
<p>Search results for <mark>flexbox</mark> in this lesson.</p>
<p><small>Prices include tax.</small></p>
<p>Price: <del>₹999</del> <ins>₹499</ins></p>
```

- `<mark>` highlights text that is relevant right now, like search matches. It gets a yellow background by default.
- `<small>` is for side comments and fine print: copyright notes, legal text.
- `<del>` shows text that was removed (drawn with a line through it).
- `<ins>` shows text that was added (underlined).

`<del>` and `<ins>` are perfect for showing price drops or edits to a document.

## Subscript and superscript

```html
<p>Water is H<sub>2</sub>O.</p>
<p>E = mc<sup>2</sup></p>
<p>Today is the 1<sup>st</sup> of the month.</p>
```

- `<sub>` lowers the text: chemical formulas, maths indices.
- `<sup>` raises the text: powers, footnote numbers, ordinals like 1st.

## Code, keyboard keys and abbreviations

These are very useful on a programming site:

```html
<p>Use the <code>console.log()</code> function to print a value.</p>
<p>Press <kbd>Ctrl</kbd> + <kbd>S</kbd> to save.</p>
<p><abbr title="HyperText Markup Language">HTML</abbr> is easy to learn.</p>
```

- `<code>` marks a piece of computer code (shown in a monospace font).
- `<kbd>` marks keys the user should press.
- `<abbr>` marks an abbreviation; the `title` shows the full form when you hover over it.

## Quotations

```html
<p>As the saying goes, <q>practice makes perfect</q>.</p>

<blockquote cite="https://www.w3.org/">
  <p>The Web is for everyone.</p>
</blockquote>
```

- `<q>` is a short quote inside a sentence; the browser adds the quotation marks.
- `<blockquote>` is a longer quote shown as its own indented block. The `cite` attribute can point to the source.

## Nesting formatting elements

You can combine them, as long as you close them in the reverse order:

```html
<p><strong>Note: <em>do not</em> skip this step.</strong></p>
```

## Common mistakes

- **Using `<b>` for important warnings.** Use `<strong>` so the importance is in the code, not just the look.
- **Underlining normal text.** Readers expect underlined text to be a link. Avoid `<u>` except for special cases like marking spelling errors.
- **Overusing bold.** If everything is bold, nothing stands out. Pick a few words.
- **Wrong nesting** like `<strong><em>text</strong></em>`. Close the inner element first.

## Exercise

Write a short "product card" using only paragraphs and formatting:

1. Product name in `<b>`, old price in `<del>` and new price in `<ins>`.
2. One important note with `<strong>` and one emphasised word with `<em>`.
3. A fine-print line with `<small>`.
4. A tip that says to press Ctrl + P to print, using `<kbd>`.
5. Bonus: write the formula for carbon dioxide (CO2) with `<sub>`.

## Summary

- `<strong>` = important, `<em>` = emphasis; `<b>` and `<i>` only change the look.
- `<mark>`, `<small>`, `<del>`, `<ins>`, `<sub>` and `<sup>` cover most other formatting needs.
- `<code>`, `<kbd>` and `<abbr>` are handy for technical writing.
- Use `<q>` and `<blockquote>` for quotations.

Next, you'll learn the most important element of the web: links.
