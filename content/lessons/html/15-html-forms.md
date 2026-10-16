---
title: HTML Forms
slug: html-forms
subject: html
lesson: 15
difficulty: beginner
excerpt: How forms collect information: the form element, action and method, labels, text inputs, the name attribute and submit buttons.
---

Every sign-up page, search box, contact page and checkout is a form. Forms let visitors send information to a website. In this lesson you'll build a complete basic form and understand what happens when it's submitted.

## A first form

```html
<form action="/subscribe" method="post">
  <label for="name">Your name</label>
  <input type="text" id="name" name="name">

  <label for="email">Email</label>
  <input type="email" id="email" name="email">

  <button type="submit">Subscribe</button>
</form>
```

That's a working form with two fields and a button. Let's look at each part.

## The form element

`<form>` wraps all the fields that are sent together. Its two most important attributes are:

- `action`: the URL that receives the data. If you leave it out, the form submits to the current page.
- `method`: how the data is sent, `get` or `post`.

### GET or POST?

| `method="get"` | `method="post"` |
|---|---|
| Data is added to the URL: `/search?q=html` | Data is sent in the request body, not shown in the URL |
| Good for searches and filters (the result can be bookmarked) | Good for anything that changes data: sign-ups, logins, orders |
| Never for passwords | Use for passwords and private data |

A search form is a perfect GET form:

```html
<form action="https://www.google.com/search" method="get">
  <label for="q">Search Google</label>
  <input type="search" id="q" name="q">
  <button type="submit">Search</button>
</form>
```

Type a word and submit: you'll see `?q=yourword` added to the address.

## Labels

A `<label>` describes a field. Connect it to the field by making the label's `for` match the input's `id`:

```html
<label for="city">City</label>
<input type="text" id="city" name="city">
```

Why this matters:

- Clicking the label puts the cursor in the field, a bigger target on phones.
- Screen readers read the label when the field gets focus. Without it, the user just hears "edit text" and has to guess.

You can also wrap the input inside the label, which connects them without `for`/`id`:

```html
<label>City <input type="text" name="city"></label>
```

## The name attribute

`name` is the key the data is sent with. A field without a `name` is **not sent at all**.

If someone types "Asha" into `<input name="name">` and "asha@example.com" into `<input name="email">`, the server receives:

```text
name=Asha&email=asha%40example.com
```

The `id` is for the page (labels, CSS, JavaScript); the `name` is for the server. They often have the same value, which is fine.

## Placeholder and value

```html
<label for="phone">Phone</label>
<input type="tel" id="phone" name="phone" placeholder="98765 43210">

<label for="country">Country</label>
<input type="text" id="country" name="country" value="India">
```

- `placeholder` shows a light example inside an empty field. It disappears when the user types, so it should never replace the label.
- `value` sets the starting content of the field. The user can change it.

## Textarea for longer text

```html
<label for="message">Message</label>
<textarea id="message" name="message" rows="5" cols="40"></textarea>
```

`<textarea>` is a multi-line text box. It has a closing tag; any text between the tags becomes its starting value.

## Buttons

```html
<button type="submit">Send</button>
<button type="reset">Clear form</button>
<button type="button">Does nothing by itself</button>
```

- `submit` sends the form (this is the default for a button inside a form).
- `reset` clears all fields back to their starting values; rarely useful, and easy to click by mistake.
- `button` does nothing unless JavaScript gives it a job.

## Grouping with fieldset and legend

```html
<form>
  <fieldset>
    <legend>Delivery address</legend>
    <label for="street">Street</label>
    <input type="text" id="street" name="street">
    <label for="pin">PIN code</label>
    <input type="text" id="pin" name="pin">
  </fieldset>
  <button type="submit">Continue</button>
</form>
```

`<fieldset>` draws a box around related fields, and `<legend>` gives the group a title that screen readers announce.

## Common mistakes

- **No labels**, only placeholders. The hint vanishes as soon as the user types.
- **Missing `name`** on a field, so its value is never sent.
- **`for` and `id` that don't match** exactly (they're case-sensitive).
- **Sending passwords with GET**, which puts them in the address bar and browser history.

## Exercise

Build a "Contact us" form with:

1. Name and email fields, each with a label.
2. A subject field with a placeholder example.
3. A message `<textarea>`.
4. A submit button that says "Send message".
5. Group name and email in a `<fieldset>` with the legend "Your details".

Submit it with `method="get"` and look at the URL to see exactly what would be sent.

## Summary

- `<form>` wraps fields; `action` says where data goes, `method` says how.
- Use GET for searches, POST for anything private or that changes data.
- Every field needs a `<label>` and a `name`.
- `<textarea>` handles long text; `<button type="submit">` sends the form.

Next, you'll learn all the input types: checkboxes, radio buttons, dropdowns, dates and built-in validation.
