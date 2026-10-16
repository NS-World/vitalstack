---
title: JavaScript Variables (let and const)
slug: js-variables
subject: javascript
lesson: 4
difficulty: beginner
excerpt: Store values in variables with let and const, learn naming rules, when to use each keyword, and why to avoid the old var.
---

A **variable** is a named box that stores a value so you can use it later: a user's name, a score, a list of products. Variables are the most basic building block of every program.

## Declaring a variable with let

```js
let score = 0;
console.log(score); // 0

score = 10;
console.log(score); // 10

score = score + 5;
console.log(score); // 15
```

- `let` creates (declares) a variable.
- `=` **assigns** a value. In programming, `=` doesn't mean "equals"; it means "put this value into the box".
- After declaring a `let` variable, you can change its value as often as you like.

`score = score + 5` reads as: take the current value of score, add 5, and store the result back in score.

## Constants with const

```js
const siteName = "VitalStack";
const pi = 3.14159;

console.log(siteName, pi);

// siteName = "Other"; // TypeError: Assignment to constant variable.
```

`const` creates a variable that **can't be reassigned**. Try uncommenting the last line: it causes an error.

### Which one to use?

**Use `const` by default.** Switch to `let` only when you know the value will change (a counter, a running total, a variable updated in a loop). This makes code easier to understand: when you see `const`, you know that name always refers to the same value.

```js
const taxRate = 0.18;     // never changes
let total = 0;            // will change

total = total + 100;
total = total + 250;
console.log(`Total with tax: ${total * (1 + taxRate)}`);
```

### const with arrays and objects

`const` stops you from **reassigning** the variable, but the contents of an array or object can still change:

```js
const cart = ["pen"];
cart.push("notebook");     // allowed: changing the contents
console.log(cart);         // ["pen", "notebook"]

// cart = ["eraser"];      // error: reassigning the variable
```

## Declaring without a value

```js
let username;
console.log(username); // undefined

username = "rohan_k";
console.log(username);
```

A variable declared without a value holds `undefined`. A `const` must be given a value immediately.

## Several variables at once

```js
let x = 1, y = 2, z = 3;
console.log(x + y + z);
```

This works, but one declaration per line is easier to read.

## Naming rules

Names can contain letters, digits, `_` and `$`, but:

- Can't start with a digit: `1st` is invalid, `first` is fine.
- Can't contain spaces or hyphens: `user-name` is invalid.
- Can't be a reserved word like `let`, `if`, `function` or `class`.
- Are case-sensitive: `total` and `Total` are different.

### Naming conventions

JavaScript developers use **camelCase**: start lowercase, capitalise each following word.

```js
let firstName = "Meera";
let totalPrice = 1299;
let isLoggedIn = true;
const MAX_UPLOAD_MB = 10; // fixed configuration values are often UPPER_CASE
```

Choose names that say what the value **is**: `price` beats `p`, `userEmails` beats `arr`. Booleans read well with `is`, `has` or `can`: `isOpen`, `hasAccess`.

## Swapping and updating values

```js
let a = "first";
let b = "second";

let temp = a;
a = b;
b = temp;

console.log(a, b); // "second" "first"

let count = 5;
count += 2;  // same as count = count + 2
count -= 1;  // count = count - 1
count++;     // add 1
console.log(count); // 7
```

## The old var keyword

Before 2015, JavaScript only had `var`. You'll see it in older code:

```js
var oldStyle = "still works";
console.log(oldStyle);
```

`var` has confusing behaviour: it ignores block boundaries like `if` and `for`, and can be used before it's declared without an error. `let` and `const` fixed these problems. **Don't use `var` in new code.** You'll understand the details in the Scope lesson.

## Common mistakes

- **Reassigning a `const`** → `TypeError: Assignment to constant variable`.
- **Declaring the same `let` twice** in the same block → `SyntaxError: Identifier has already been declared`. Declare once, then assign without `let`.
- **Using a variable before declaring it** → `ReferenceError`.
- **Meaningless names** like `x1`, `data2`, `temp3` in real code.

## Exercise

1. Create `const` variables for your name and birth year, and a `let` for the current year. Print your age.
2. Create `let balance = 1000`. Subtract a purchase of 250 with `-=` and add a refund of 50 with `+=`. Print the result.
3. Swap the values of two variables using a third one.
4. Try reassigning a `const` and read the error message in the output.

## Summary

- Variables store values; `=` assigns a value.
- Use `const` by default, and `let` when the value must change.
- Name variables in camelCase with descriptive names.
- Avoid `var` in new code.

Next, you'll learn the different types of data a variable can hold.
