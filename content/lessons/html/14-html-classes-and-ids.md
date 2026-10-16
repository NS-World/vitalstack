---
title: HTML Classes and IDs
slug: html-classes-and-ids
subject: html
lesson: 14
difficulty: beginner
excerpt: Name elements with the class and id attributes, use them from CSS and JavaScript, and learn the naming rules that keep a project tidy.
---

As pages grow, you need a way to say "these five boxes should look the same" or "this one element is the shopping cart". The `class` and `id` attributes give elements names that CSS and JavaScript can use.

## The class attribute

A **class** is a label you can give to any number of elements:

```html
<style>
  .note {
    background: #fff7d6;
    border-left: 4px solid #f2b705;
    padding: 8px 12px;
  }
</style>

<p class="note">Remember to save your file before refreshing the browser.</p>
<p>A normal paragraph.</p>
<p class="note">File names are case-sensitive on most servers.</p>
```

The CSS rule `.note` (with a dot) styles every element that has `class="note"`. Change the rule once, and every note on the site changes.

## Several classes on one element

Separate class names with spaces:

```html
<style>
  .btn { padding: 8px 16px; border-radius: 6px; border: none; }
  .btn-primary { background: #2563eb; color: white; }
  .btn-large { font-size: 20px; }
</style>

<button class="btn btn-primary">Save</button>
<button class="btn btn-primary btn-large">Sign up</button>
<button class="btn">Cancel</button>
```

This is how most real-world CSS works: small, reusable classes combined on each element.

## The id attribute

An **id** is a unique name: only **one** element on a page may have a given id.

```html
<style>
  #site-title { color: #2563eb; }
</style>

<h1 id="site-title">VitalStack</h1>
```

In CSS, an id is selected with `#`. Ids have three main uses:

- **Links to a section**: `<a href="#pricing">` jumps to `id="pricing"`.
- **JavaScript**: `document.getElementById("cart")` finds exactly one element.
- **Forms**: a `<label for="email">` is connected to the input with `id="email"`.

## Class vs id

| | class | id |
|---|---|---|
| How many elements | Any number | Exactly one per page |
| CSS selector | `.name` | `#name` |
| Used for | Styling groups of elements | Links, labels, JavaScript, one-off elements |

A good habit: **use classes for styling** and keep ids for links, forms and JavaScript. Id rules in CSS are very "strong" and hard to override later, which you'll understand in the CSS Selectors lesson.

## Classes and ids in JavaScript

```html
<p id="greeting">Hello!</p>
<button class="change">Change greeting</button>

<script>
  document.querySelector(".change").addEventListener("click", function () {
    document.getElementById("greeting").textContent = "Welcome to VitalStack!";
  });
</script>
```

Click the button and the paragraph text changes. The script found the button by its class and the paragraph by its id. You'll learn this properly in the JavaScript course.

## Naming rules and good habits

- Names are **case-sensitive**: `Note` and `note` are different.
- Don't use spaces inside one name (a space means "two classes"). Use hyphens: `main-menu`, `card-title`.
- Don't start a name with a number.
- Name things by **what they are**, not how they look: `.warning` is better than `.red-text`, because you might change the colour to orange next year.
- Keep names short, lowercase and consistent across the site.

## Common mistakes

- **The same id twice** on a page. Links and JavaScript will only find the first one.
- **Using `.` or `#` in the HTML attribute.** Write `class="note"`, not `class=".note"`. The dot belongs in CSS only.
- **Writing `class="btn" class="large"`.** Only the first one counts. Use `class="btn large"`.
- **Naming by appearance**, which makes names lie after a redesign.

## Exercise

1. Create a `.card` class with a border and padding, and use it on three divs.
2. Add a second class `.featured` with a different background, and apply it to one of the cards as well.
3. Give your page a heading with `id="top"` and add a "Back to top" link at the bottom.
4. Change the button example so the button's text also changes after it's clicked.

## Summary

- `class` labels any number of elements; select them in CSS with `.name`.
- `id` names one unique element; select it with `#name`.
- An element can have many classes, separated by spaces.
- Use classes for styling, ids for links, labels and JavaScript.

Next, you'll start collecting information from users with HTML forms.
