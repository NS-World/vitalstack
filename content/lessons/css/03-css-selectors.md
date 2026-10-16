---
title: CSS Selectors
slug: css-selectors
subject: css
lesson: 3
difficulty: beginner
excerpt: Element, class, id, group, descendant, child, attribute and pseudo-class selectors, plus specificity, the rule that decides which style wins.
---

A selector is the part of a CSS rule that chooses which elements to style. Learning selectors well is the key to writing short, clean CSS. This lesson covers the selectors you'll use every day and the rule that settles conflicts: **specificity**.

## Basic selectors

```html
<style>
  p { color: #444; }                 /* element (type) selector */
  .highlight { background: #fff3b0; } /* class selector */
  #intro { font-size: 20px; }        /* id selector */
  * { box-sizing: border-box; }      /* universal selector: every element */
</style>

<p id="intro">This is the introduction.</p>
<p>A normal paragraph with a <span class="highlight">highlighted phrase</span>.</p>
<p class="highlight">A whole highlighted paragraph.</p>
```

- **Element**: `p` matches every `<p>`.
- **Class**: `.highlight` matches every element with `class="highlight"`.
- **Id**: `#intro` matches the one element with `id="intro"`.
- **Universal**: `*` matches everything.

You can combine an element and a class with no space: `p.highlight` matches only paragraphs that have the class.

## Grouping selectors

Separate selectors with commas to give them the same styles:

```css
h1, h2, h3 {
  font-family: Georgia, serif;
  color: #1e293b;
}
```

## Combinators: selecting by position

```html
<style>
  article p { color: green; }      /* descendant: any p inside article */
  ul > li { font-weight: bold; }   /* child: only direct li children of ul */
  h2 + p { font-style: italic; }   /* adjacent sibling: the p right after an h2 */
</style>

<article>
  <h2>Article title</h2>
  <p>First paragraph (green and italic).</p>
  <p>Second paragraph (green only).</p>
</article>

<ul>
  <li>Direct child (bold)
    <ol><li>Nested item: not a direct child of ul, so its text isn't bold</li></ol>
  </li>
</ul>
```

- `A B` (space): B anywhere inside A.
- `A > B`: B directly inside A.
- `A + B`: B immediately after A, with the same parent.
- `A ~ B`: any B after A with the same parent.

Note: the nested item's text is still bold because `font-weight` is inherited from its parent `<li>`; the selector itself didn't match it.

## Attribute selectors

```css
a[target="_blank"] { color: purple; }     /* exact value */
input[type="email"] { border-color: blue; }
a[href^="https"] { font-weight: bold; }   /* value starts with */
a[href$=".pdf"] { color: crimson; }       /* value ends with */
```

These are great for forms and for styling links by their destination.

## Pseudo-classes: state and position

A pseudo-class starts with a colon and selects elements in a certain **state** or **position**:

```html
<style>
  a:hover { color: crimson; }
  button:focus-visible { outline: 3px solid orange; }
  input:invalid { border: 2px solid red; }
  li:first-child { color: green; }
  li:last-child { color: blue; }
  li:nth-child(even) { background: #f1f5f9; }
  p:not(.note) { color: #555; }
</style>

<a href="#">Hover over me</a>
<p><button>Tab to me</button></p>
<p><input type="email" placeholder="Type an invalid email"></p>
<ul>
  <li>First</li><li>Second</li><li>Third</li><li>Fourth</li>
</ul>
<p class="note">A note</p>
<p>Not a note</p>
```

`:nth-child(even)` (or `odd`, `3`, `3n`) is perfect for striped table rows. `:not()` excludes elements.

## Pseudo-elements

A pseudo-element starts with two colons and styles a **part** of an element:

```css
p::first-line { font-weight: bold; }
.required::after { content: " *"; color: red; }
::selection { background: #2563eb; color: white; }
```

`::before` and `::after` insert decorative content. They need the `content` property, even if it's empty (`content: ""`).

## Specificity: which rule wins

When two rules conflict, the more **specific** selector wins, no matter which comes first. Think of it as a score with three columns:

| Selector | Ids | Classes, attributes, pseudo-classes | Elements |
|---|---|---|---|
| `p` | 0 | 0 | 1 |
| `.note` | 0 | 1 | 0 |
| `p.note` | 0 | 1 | 1 |
| `#intro` | 1 | 0 | 0 |
| `article p:first-child` | 0 | 1 | 2 |

Compare from the left: one id beats any number of classes, and one class beats any number of elements. If the scores are equal, the later rule wins. Inline styles beat all of these.

```html
<style>
  #title { color: blue; }
  .heading { color: green; }
  h1 { color: red; }
</style>
<h1 id="title" class="heading">I'm blue: the id is most specific</h1>
```

**Practical advice:** style with classes, keep selectors short, and avoid ids in CSS. Low, even specificity makes your CSS predictable.

## Common mistakes

- **Forgetting the dot or hash**: `highlight { }` looks for a `<highlight>` element.
- **Adding a space by accident**: `p .note` (note inside p) is not `p.note` (p that has class note).
- **Long chains** like `body div.main ul li a`, which are fragile and hard to override.
- **Fighting specificity with `!important`** instead of simplifying selectors.

## Exercise

1. Make every second row of a list grey with `:nth-child(even)`.
2. Colour links that open in a new tab differently with an attribute selector.
3. Add a red asterisk after every label with class `required` using `::after`.
4. Write two conflicting rules and predict which wins, then test it.

## Summary

- Element, `.class`, `#id` and `*` are the basic selectors; commas group them.
- Combinators (space, `>`, `+`, `~`) select by position.
- Pseudo-classes (`:hover`, `:nth-child`) select by state; pseudo-elements (`::before`) style parts.
- Specificity decides conflicts: ids > classes > elements; then the later rule wins.

Next, you'll learn all the ways to write colours in CSS.
