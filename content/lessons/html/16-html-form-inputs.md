---
title: HTML Form Inputs
slug: html-form-inputs
subject: html
lesson: 16
difficulty: beginner
excerpt: Every common input type, from email, number and date to checkboxes, radio buttons and dropdowns, plus built-in validation with required, min, max and pattern.
---

The `<input>` element changes completely depending on its `type`. One attribute turns a plain text box into a date picker, a slider or a checkbox. Choosing the right type gives users the right keyboard on their phone and gives you free validation.

## Text-like inputs

```html
<form>
  <label for="u">Username</label>
  <input type="text" id="u" name="username"><br><br>

  <label for="e">Email</label>
  <input type="email" id="e" name="email"><br><br>

  <label for="p">Password</label>
  <input type="password" id="p" name="password"><br><br>

  <label for="t">Phone</label>
  <input type="tel" id="t" name="phone"><br><br>

  <label for="w">Website</label>
  <input type="url" id="w" name="website"><br><br>

  <button>Send</button>
</form>
```

- `email` checks for an @ and shows an email keyboard on phones.
- `password` hides the characters.
- `tel` shows a number pad on phones (it doesn't check the format, because phone formats differ by country).
- `url` checks for a full web address.

## Numbers, ranges and dates

```html
<label for="qty">Quantity (1–10)</label>
<input type="number" id="qty" name="qty" min="1" max="10" value="1"><br><br>

<label for="vol">Volume</label>
<input type="range" id="vol" name="volume" min="0" max="100" step="5"><br><br>

<label for="dob">Date of birth</label>
<input type="date" id="dob" name="dob"><br><br>

<label for="time">Meeting time</label>
<input type="time" id="time" name="time"><br><br>

<label for="fav">Favourite colour</label>
<input type="color" id="fav" name="colour" value="#2563eb">
```

`min`, `max` and `step` limit what can be chosen. Date and time inputs show the device's own calendar or clock picker.

## Checkboxes

Checkboxes let users pick **any number** of options:

```html
<p>Which languages do you know?</p>
<label><input type="checkbox" name="langs" value="html"> HTML</label><br>
<label><input type="checkbox" name="langs" value="css" checked> CSS</label><br>
<label><input type="checkbox" name="langs" value="js"> JavaScript</label>
```

- `value` is what gets sent when the box is ticked (if you leave it out, the browser sends "on").
- `checked` ticks the box from the start.
- Unticked boxes send nothing.

## Radio buttons

Radio buttons let users pick **exactly one** option from a group. Buttons with the **same `name`** form one group:

```html
<p>Choose a plan:</p>
<label><input type="radio" name="plan" value="free" checked> Free</label><br>
<label><input type="radio" name="plan" value="pro"> Pro</label><br>
<label><input type="radio" name="plan" value="team"> Team</label>
```

Clicking one automatically un-selects the others. If each radio had a different name, they'd all be selectable at once, the most common radio-button bug.

## Dropdowns with select

```html
<label for="course">Course</label>
<select id="course" name="course">
  <option value="">Choose a course…</option>
  <option value="html">HTML</option>
  <option value="css">CSS</option>
  <option value="js" selected>JavaScript</option>
</select>
```

Use `<optgroup label="...">` to group long lists, and add `multiple` to let users pick several options (though checkboxes are usually easier for that).

## File uploads and hidden fields

```html
<label for="cv">Upload your CV (PDF)</label>
<input type="file" id="cv" name="cv" accept=".pdf">

<input type="hidden" name="source" value="newsletter">
```

A form with a file input must use `method="post"` and `enctype="multipart/form-data"`. Hidden inputs send a value the user doesn't see, such as which page the form came from.

## Built-in validation

The browser can check fields before the form is sent, with no JavaScript:

```html
<form>
  <label for="n">Name (required)</label>
  <input type="text" id="n" name="name" required minlength="2"><br><br>

  <label for="age">Age (13–120)</label>
  <input type="number" id="age" name="age" min="13" max="120"><br><br>

  <label for="pin">PIN code (6 digits)</label>
  <input type="text" id="pin" name="pin" pattern="[0-9]{6}" title="Six digits, e.g. 400001"><br><br>

  <button>Submit</button>
</form>
```

Try submitting it empty or with wrong values: the browser stops and shows a message.

- `required`: the field can't be empty.
- `minlength` / `maxlength`: text length limits.
- `min` / `max`: number and date limits.
- `pattern`: a regular expression the value must match; describe the format in `title` or near the field.

**Important:** browser validation helps users, but anyone can bypass it. A server must always check the data again.

## Other useful attributes

- `disabled`: the field can't be used and isn't sent.
- `readonly`: the field can't be changed but is sent.
- `autocomplete="email"` (or `name`, `tel`, `street-address`…): lets the browser fill in saved details.
- `autofocus`: puts the cursor in this field when the page loads (use once, at most).

## Common mistakes

- **Radio buttons with different names**, so more than one can be selected.
- **Using `type="text"` for everything**, missing the right phone keyboards and free checks.
- **Trusting browser validation alone.** Always validate on the server too.
- **Checkboxes without `value`**, so the server receives "on" with no idea which option it was.

## Exercise

Build a course registration form with:

1. Name (required), email (type email, required) and age (13–100).
2. A dropdown of three courses.
3. Radio buttons for "Morning" or "Evening" batch.
4. Checkboxes for "I have a laptop" and "Send me updates".
5. A start date picker.

Try to submit it with empty and wrong values and read the browser's messages.

## Summary

- The `type` attribute turns `<input>` into the right control for each kind of data.
- Checkboxes allow many choices; radio buttons with the same `name` allow one.
- `<select>` creates a dropdown from `<option>` elements.
- `required`, `min`, `max`, `minlength` and `pattern` give free validation, but the server must check again.

Next, you'll learn semantic elements that describe the structure of a whole page.
