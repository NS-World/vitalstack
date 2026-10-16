---
title: HTML Lists
slug: html-lists
subject: html
lesson: 11
difficulty: beginner
excerpt: Unordered, ordered and description lists, nested lists, numbering options and why navigation menus are built from lists.
---

Lists are everywhere: shopping lists, steps in a recipe, features of a product, the menu at the top of a website. HTML has three kinds of lists, and choosing the right one makes your content clearer for everyone.

## Unordered lists

Use `<ul>` when the order doesn't matter. Each item goes in an `<li>` (list item):

```html
<h3>Things to pack</h3>
<ul>
  <li>Laptop and charger</li>
  <li>Water bottle</li>
  <li>Notebook</li>
</ul>
```

Browsers show each item with a bullet point.

## Ordered lists

Use `<ol>` when the order matters, such as steps or rankings:

```html
<h3>Make a cup of tea</h3>
<ol>
  <li>Boil water.</li>
  <li>Add tea leaves and let them brew for 3 minutes.</li>
  <li>Add milk and sugar.</li>
  <li>Strain into a cup.</li>
</ol>
```

The browser numbers the items automatically. If you add or remove a step, the numbers update by themselves.

### Changing the numbering

`<ol>` has a few useful attributes:

```html
<ol type="A">
  <li>Option A</li>
  <li>Option B</li>
</ol>

<ol start="5">
  <li>This item is number 5</li>
  <li>This one is 6</li>
</ol>

<ol reversed>
  <li>Third place</li>
  <li>Second place</li>
  <li>Winner</li>
</ol>
```

- `type` can be `1` (default), `A`, `a`, `I` or `i`.
- `start` sets the first number.
- `reversed` counts down.

## Description lists

Use `<dl>` for pairs of terms and descriptions, like a glossary or a list of details:

```html
<dl>
  <dt>HTML</dt>
  <dd>The language that gives a web page its structure.</dd>

  <dt>CSS</dt>
  <dd>The language that controls how the page looks.</dd>

  <dt>JavaScript</dt>
  <dd>The language that makes the page interactive.</dd>
</dl>
```

- `<dl>` is the description list.
- `<dt>` is the term.
- `<dd>` is the description of that term.

## Nested lists

A list item can contain another list. Put the inner list **inside** the `<li>`, before its closing tag:

```html
<ul>
  <li>Frontend
    <ul>
      <li>HTML</li>
      <li>CSS</li>
      <li>JavaScript</li>
    </ul>
  </li>
  <li>Backend
    <ul>
      <li>Python</li>
      <li>SQL</li>
    </ul>
  </li>
</ul>
```

Browsers indent nested lists and change the bullet style for each level.

## Lists for navigation menus

Almost every website menu is a list of links inside a `<nav>` element:

```html
<nav>
  <ul>
    <li><a href="/">Home</a></li>
    <li><a href="/tutorials/">Tutorials</a></li>
    <li><a href="/blog/">Blog</a></li>
    <li><a href="/contact-us/">Contact</a></li>
  </ul>
</nav>
```

A menu *is* a list of choices, so this is the right meaning. Screen readers also announce "list, 4 items", which helps users know how big the menu is. With CSS you'll later remove the bullets and lay the links out in a row.

## What can go inside a list

- `<ul>` and `<ol>` may only contain `<li>` elements directly.
- An `<li>` can contain almost anything: text, links, images, paragraphs, even other lists.

```html
<!-- Wrong: text directly inside ul -->
<ul>
  Fruits
  <li>Apple</li>
</ul>

<!-- Right -->
<h4>Fruits</h4>
<ul>
  <li>Apple</li>
</ul>
```

## Common mistakes

- **Typing bullet characters** (• or -) at the start of paragraphs instead of using a real list.
- **Closing the `<li>` before the nested list**, which places the inner list outside the item.
- **Using `<ol>` for things with no order**, or `<ul>` for steps that must be followed in order.
- **Putting headings or paragraphs directly in `<ul>`** without an `<li>`.

## Exercise

1. Make an unordered list of five programming languages.
2. Make an ordered list with the steps to send an email.
3. Create a nested list: three countries, each with two cities.
4. Make a description list with three computer terms of your choice and their meanings.
5. Build a small `<nav>` menu of four links using a list.

## Summary

- `<ul>` for unordered lists, `<ol>` for ordered lists, `<li>` for each item.
- `type`, `start` and `reversed` change how ordered lists are numbered.
- `<dl>`, `<dt>` and `<dd>` create description lists.
- Nest a list inside an `<li>`; navigation menus are lists of links.

Next, you'll organise data in rows and columns with tables.
