---
title: JavaScript Output (console.log)
slug: js-output
subject: javascript
lesson: 3
difficulty: beginner
excerpt: Show results with console.log and its relatives, write into the page with textContent, use alert sparingly, and debug with the browser console.
---

A program is only useful if you can see what it's doing. JavaScript can show output in several places: the developer console, the web page itself, and pop-up dialogs. Each has its own job.

## console.log(): your best friend

```js
console.log("Hello");
console.log(42);
console.log(true);
console.log("Total:", 19.99, "INR");
```

`console.log()` prints to the browser's console. You can pass several values separated by commas, and they're printed with spaces between them.

Developers use it constantly to check what's happening: "What's in this variable right now?" "Did this part of the code even run?" It's invisible to normal visitors, so it doesn't affect the page.

## Logging objects and arrays

```js
const user = { name: "Asha", age: 21, skills: ["HTML", "CSS"] };
console.log(user);

const scores = [92, 87, 95];
console.log(scores);
```

The console shows the whole structure, which is very handy when working with data. In the browser's own console you can click to expand nested values.

## Other console methods

```js
console.info("Information");
console.warn("Careful: this looks wrong");
console.error("Something broke");

console.table([
  { language: "HTML", lessons: 20 },
  { language: "CSS", lessons: 20 },
  { language: "JavaScript", lessons: 25 }
]);
```

- `console.warn` and `console.error` are shown in yellow and red in the browser console and are easy to filter.
- `console.table` displays an array of objects as a neat table (open your browser's console with F12 to see it).

## Template literals: easier messages

Joining text with `+` gets messy. **Template literals** use backticks (`` ` ``) and `${ }` to insert values:

```js
const name = "Rohan";
const lessons = 7;

console.log("Hi " + name + ", you finished " + lessons + " lessons.");
console.log(`Hi ${name}, you finished ${lessons} lessons.`);
console.log(`Next lesson number: ${lessons + 1}`);
```

Both lines print the same thing, but the second is much easier to read and write. You can put any expression inside `${ }`. You'll use template literals throughout the course.

## Writing into the page

To show output to **visitors**, change an element's content:

```html
<p id="result"></p>

<script>
  const price = 499;
  const qty = 3;
  document.getElementById("result").textContent = `Total: ₹${price * qty}`;
</script>
```

`textContent` sets the text inside an element. There's also `innerHTML`, which interprets the string as HTML:

```html
<div id="box"></div>

<script>
  document.getElementById("box").innerHTML = "<strong>Bold</strong> and <em>italic</em>";
</script>
```

**Be careful with `innerHTML`:** never put text typed by users into it. If a user types `<img src=x onerror=...>`, the browser runs it as code, a security hole called cross-site scripting (XSS). Use `textContent` for plain text, which is always safe.

## alert, confirm and prompt

```html
<button id="a">Alert</button>
<button id="c">Confirm</button>
<button id="p">Prompt</button>

<script>
  document.getElementById("a").addEventListener("click", function () {
    alert("This is an alert.");
  });
  document.getElementById("c").addEventListener("click", function () {
    const ok = confirm("Delete this item?");
    console.log("User chose:", ok);
  });
  document.getElementById("p").addEventListener("click", function () {
    const name = prompt("What's your name?");
    console.log("Name entered:", name);
  });
</script>
```

- `alert()` shows a message with an OK button.
- `confirm()` asks OK/Cancel and returns `true` or `false`.
- `prompt()` asks for text and returns it (or `null` if cancelled).

These are handy for quick tests, but they block the whole page and look different in every browser. Real websites use custom dialogs built with HTML and CSS.

## document.write (avoid)

You may see `document.write("Hello")` in old tutorials. If it runs after the page has loaded, it **erases the whole page**. Don't use it.

## Debugging with the console

When something doesn't work:

1. Open the console (F12 → Console).
2. Look for **red error messages**. They tell you what went wrong and on which line. Click the file name to jump to the line.
3. Add `console.log()` lines to check values step by step.

```js
const items = ["pen", "book"];
console.log("items is:", items);
console.log("first item:", items[0]);
console.log("third item:", items[2]); // undefined: there's no third item
```

## Common mistakes

- **Expecting visitors to see console.log**: it's for developers. Use `textContent` for visitors.
- **Using `innerHTML` with user input**, creating a security hole.
- **Regular quotes instead of backticks** in template literals: `"Hi ${name}"` prints the `${name}` literally.
- **Leaving lots of console.log calls** in finished code. Clean up before publishing.

## Exercise

1. Store your name and age in two variables and print a sentence with a template literal.
2. Print an array of three friends with `console.log` and an array of objects with `console.table` (in your browser console).
3. Create a page that shows "2 + 2 = 4" inside a paragraph, calculated by JavaScript.
4. Add a button that uses `confirm()` and writes "Confirmed" or "Cancelled" into the page.

## Summary

- `console.log()` prints values for developers; `warn`, `error` and `table` add variety.
- Template literals (`` `Hi ${name}` ``) make messages easy.
- `textContent` safely shows text to visitors; use `innerHTML` only with trusted HTML.
- `alert`, `confirm` and `prompt` are quick tests, not real UI.

Next, you'll store values in variables with let and const.
