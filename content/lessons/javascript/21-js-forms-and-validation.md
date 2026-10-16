---
title: JavaScript Forms and Validation
slug: js-forms-and-validation
subject: javascript
lesson: 21
difficulty: intermediate
excerpt: Read form values, handle submit, validate input with clear error messages, combine HTML validation with JavaScript, and use FormData.
---

Forms are where users give you information, and where things most often go wrong: empty fields, typos, the wrong format. In this lesson you'll read form values with JavaScript, validate them, and show helpful error messages, while keeping the form accessible.

## Reading values

```html
<form id="profile">
  <label for="name">Name</label>
  <input id="name" name="name" value="Asha">

  <label for="level">Level</label>
  <select id="level" name="level">
    <option>Beginner</option>
    <option selected>Intermediate</option>
  </select>

  <label><input type="checkbox" id="news" name="news" checked> Newsletter</label>
  <button>Save</button>
</form>

<script>
  const form = document.getElementById("profile");
  console.log(form.elements.name.value);   // "Asha"
  console.log(form.elements.level.value);  // "Intermediate"
  console.log(form.elements.news.checked); // true: use .checked for checkboxes
</script>
```

- Text inputs, textareas and selects: `.value` (always a **string**).
- Checkboxes and radios: `.checked` (true/false).
- `form.elements.fieldName` finds a field by its `name` (or `id`).

For radio buttons, find the checked one:

```js
// const plan = form.querySelector('input[name="plan"]:checked')?.value;
```

## Handling submit

```html
<form id="signup">
  <label for="email">Email</label>
  <input id="email" name="email" type="email">
  <button>Sign up</button>
</form>
<p id="result"></p>

<script>
  document.getElementById("signup").addEventListener("submit", event => {
    event.preventDefault();
    const email = event.target.elements.email.value.trim();
    document.getElementById("result").textContent = `Thanks! We'll write to ${email}.`;
  });
</script>
```

`preventDefault()` stops the page from reloading so JavaScript can handle the data, for example by sending it with `fetch` (lesson 23).

## FormData: all values at once

```html
<form id="order">
  <input name="product" value="Notebook">
  <input name="qty" type="number" value="3">
  <label><input type="radio" name="ship" value="standard" checked> Standard</label>
  <label><input type="radio" name="ship" value="express"> Express</label>
  <button>Order</button>
</form>

<script>
  document.getElementById("order").addEventListener("submit", event => {
    event.preventDefault();
    const data = new FormData(event.target);
    const values = Object.fromEntries(data);
    console.log(values); // { product: "Notebook", qty: "3", ship: "standard" }
    console.log(Number(values.qty) * 120);
  });
</script>
```

`new FormData(form)` collects every named field. `Object.fromEntries` turns it into a normal object. A `FormData` object can also be sent directly with `fetch`.

## HTML validation first

Remember the built-in validation attributes from the HTML course: `required`, `type="email"`, `minlength`, `min`, `max`, `pattern`. They work without JavaScript and give you a solid base. JavaScript adds checks HTML can't do, like "passwords must match" or "username already taken", and lets you control the error messages.

## Custom validation with clear messages

```html
<style>
  .field { margin-bottom: 12px; font-family: system-ui, sans-serif; }
  .error { color: #b91c1c; font-size: 14px; min-height: 1.2em; margin: 4px 0 0; }
  input[aria-invalid="true"] { border: 2px solid #b91c1c; }
</style>

<form id="register" novalidate>
  <div class="field">
    <label for="username">Username</label><br>
    <input id="username" name="username" aria-describedby="username-error">
    <p class="error" id="username-error"></p>
  </div>
  <div class="field">
    <label for="pw">Password</label><br>
    <input id="pw" name="pw" type="password" aria-describedby="pw-error">
    <p class="error" id="pw-error"></p>
  </div>
  <div class="field">
    <label for="pw2">Confirm password</label><br>
    <input id="pw2" name="pw2" type="password" aria-describedby="pw2-error">
    <p class="error" id="pw2-error"></p>
  </div>
  <button>Create account</button>
  <p id="success" role="status"></p>
</form>

<script>
  const form = document.getElementById("register");

  function setError(input, message) {
    document.getElementById(`${input.id}-error`).textContent = message;
    input.setAttribute("aria-invalid", message ? "true" : "false");
    return !message;
  }

  function validate() {
    const { username, pw, pw2 } = form.elements;
    const results = [
      setError(username,
        username.value.trim().length < 3 ? "Use at least 3 characters." :
        !/^[a-z0-9_]+$/i.test(username.value) ? "Only letters, numbers and _." : ""),
      setError(pw, pw.value.length < 8 ? "Use at least 8 characters." : ""),
      setError(pw2, pw2.value !== pw.value ? "Passwords don't match." : "")
    ];
    return results.every(Boolean);
  }

  form.addEventListener("submit", event => {
    event.preventDefault();
    if (!validate()) {
      form.querySelector('[aria-invalid="true"]').focus();
      return;
    }
    document.getElementById("success").textContent = "Account created!";
  });
</script>
```

What makes this good validation:

- `novalidate` on the form turns off the browser's pop-ups so you control the messages. (Keep the HTML attributes anyway; they document intent.)
- Each error appears **next to its field**, in text, and says how to fix the problem.
- `aria-invalid` and `aria-describedby` let screen readers announce the error with the field.
- Focus moves to the first invalid field.
- The success message uses `role="status"` so it's announced.

## Validate at the right moment

Showing "invalid email" while someone is still typing their first letter is annoying. A friendly pattern:

- Validate a field when it **loses focus** (`blur`) or on **submit**.
- Once a field has shown an error, re-check it on every `input`, so the error disappears as soon as it's fixed.

```js
// field.addEventListener("blur", () => validateField(field));
// field.addEventListener("input", () => {
//   if (field.getAttribute("aria-invalid") === "true") validateField(field);
// });
```

## Using the built-in Constraint API

You can also use the browser's validation from JavaScript:

```html
<form id="f">
  <input id="age" type="number" min="13" max="120" required>
  <button>Go</button>
</form>
<p id="msg"></p>

<script>
  document.getElementById("f").addEventListener("submit", e => {
    e.preventDefault();
    const age = document.getElementById("age");
    document.getElementById("msg").textContent =
      age.checkValidity() ? "Valid!" : age.validationMessage;
  });
</script>
```

`checkValidity()` returns true or false based on the HTML attributes; `validationMessage` gives the browser's message in the user's language.

## Never trust the browser alone

Anyone can turn off JavaScript or send data straight to your server. Client-side validation is for a **good user experience**; the server must **always validate again** for security.

## Common mistakes

- **Forgetting `.trim()`**, so "   " counts as filled in.
- **Using `.value` on checkboxes** instead of `.checked`.
- **Comparing numbers as strings** (`"10" > "9"` is false): convert with `Number()`.
- **Error messages only in red colour** or only in a tooltip.
- **Relying only on client-side checks.**

## Exercise

1. Build a contact form (name, email, message) that checks all fields are filled and the message is at least 20 characters.
2. Show each error under its field and clear it as the user fixes it.
3. Disable the submit button and show "Sending…" after a valid submit.
4. Use `FormData` and `Object.fromEntries` to log all values as an object.

## Summary

- Read fields with `.value` (strings) and `.checked`; `FormData` collects everything.
- Handle the form's `submit` event and call `preventDefault()`.
- Show specific, accessible error messages next to each field.
- Client-side validation helps users; the server must always validate too.

Next, you'll save data in the browser with Local Storage.
