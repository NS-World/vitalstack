---
title: JavaScript Introduction
slug: js-introduction
subject: javascript
lesson: 1
difficulty: beginner
excerpt: What JavaScript is, what it can do in the browser and beyond, how it relates to HTML and CSS, and your first lines of code.
---

HTML gives a page structure and CSS makes it look good. **JavaScript** makes it **do** things: respond to clicks, check forms, load new data without reloading, show and hide menus, run games. It's the only programming language that runs natively in every web browser, which makes it one of the most useful languages you can learn.

## Your first JavaScript

```js
console.log("Hello, world!");
console.log(2 + 3);
```

Click **Try it Yourself** and press Run. You'll see `Hello, world!` and `5` in the output. `console.log()` prints a value to the **console**, a panel developers use to see what their code is doing. You'll use it in almost every lesson.

## JavaScript changes the page

Here's JavaScript working with HTML:

```html
<h2 id="greeting">Hello!</h2>
<button id="btn">Click me</button>

<script>
  const button = document.getElementById("btn");
  const heading = document.getElementById("greeting");

  button.addEventListener("click", function () {
    heading.textContent = "You clicked the button!";
  });
</script>
```

Click the button and the heading changes. In three steps, the script:

1. Finds the button and the heading on the page.
2. Waits for a click on the button.
3. Changes the heading's text when the click happens.

Almost every interactive feature on the web follows this pattern: **find** elements, **listen** for something, **change** something.

## HTML, CSS and JavaScript together

| Language | Role | Example |
|---|---|---|
| HTML | Content and structure | A button exists |
| CSS | Presentation | The button is blue and rounded |
| JavaScript | Behaviour | Clicking the button opens a menu |

## What JavaScript can do

In the browser:

- React to clicks, typing, scrolling and other **events**.
- Change any HTML or CSS on the page.
- Check form input before it's sent.
- Load data from servers (weather, search results, new posts) without reloading.
- Save small amounts of data in the browser, e.g. a dark-mode preference.
- Draw graphics and run games.

Outside the browser, with **Node.js**, JavaScript also runs web servers, command-line tools and desktop apps. Learn it once and use it nearly everywhere.

## JavaScript is not Java

Despite the name, JavaScript has nothing to do with Java. The name was a marketing decision in 1995. The official standard is called **ECMAScript**; "ES6" or "ES2015" refers to a big update that added modern features like `let`, `const` and arrow functions, which you'll learn in this course.

## A taste of the language

```js
let name = "Asha";
let age = 21;

if (age >= 18) {
  console.log(name + " is an adult.");
} else {
  console.log(name + " is a minor.");
}

for (let i = 1; i <= 3; i++) {
  console.log("Counting: " + i);
}
```

Don't worry about understanding every line yet. You'll learn variables, `if` statements and loops step by step. Notice that it reads almost like English.

## Statements and semicolons

A JavaScript program is a list of **statements**, instructions run from top to bottom. Each one usually ends with a semicolon `;`. JavaScript can often add missing semicolons automatically, but writing them yourself avoids rare, confusing bugs. This course always uses them.

## Comments

```js
// A single-line comment

/*
  A comment that
  spans several lines
*/

console.log("Comments are ignored when the code runs.");
```

## Case sensitivity

JavaScript is **case-sensitive**: `name`, `Name` and `NAME` are three different things, and `console.Log()` (capital L) is an error. When something doesn't work, check the capitalisation first.

## How to practise

- Use **Try it Yourself** on every example in this course and change things.
- In any browser, press **F12** (or Ctrl + Shift + J) and open the **Console** tab. You can type JavaScript there and press Enter to run it immediately.
- Make mistakes on purpose and read the error messages. Learning to read errors is half of programming.

## Common mistakes

- **Wrong capitalisation**: `Console.log` or `getElementByID`.
- **Missing quotes around text**: `console.log(Hello)` looks for a variable called Hello.
- **Mismatched brackets** `( )` and braces `{ }`.
- **Smart quotes** pasted from a word processor (“ ”) instead of plain quotes (" ").

## Exercise

1. Print your name and your favourite food with two `console.log()` lines.
2. Print the result of `7 * 6`.
3. In the button example, change the new heading text to something of your own.
4. Open your browser's console on any website and type `2 ** 10`. What do you get?

## Summary

- JavaScript adds behaviour to web pages and also runs on servers with Node.js.
- `console.log()` prints values so you can see what your code does.
- Interactive features follow the pattern: find elements, listen for events, change the page.
- JavaScript is case-sensitive, and statements end with semicolons.

Next, you'll learn where to put JavaScript in an HTML page.
