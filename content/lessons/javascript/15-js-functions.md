---
title: JavaScript Functions
slug: js-functions
subject: javascript
lesson: 15
difficulty: beginner
excerpt: Write reusable code with functions: declarations, parameters and arguments, return values, default and rest parameters, and functions as values.
---

A **function** is a named block of code you can run whenever you need it. Instead of repeating the same lines in ten places, you write them once as a function and call it ten times. Functions make code shorter, easier to read and easier to fix.

## Declaring and calling a function

```js
function greet() {
  console.log("Hello! Welcome to VitalStack.");
}

greet();
greet();
```

- `function` starts the declaration, followed by the function's **name** and brackets.
- The code inside `{ }` is the function's **body**.
- Writing `greet()` **calls** (runs) it. Without the brackets, nothing happens.

## Parameters and arguments

Functions become powerful when they accept input:

```js
function greet(name) {
  console.log(`Hello, ${name}!`);
}

greet("Asha");
greet("Rohan");
```

- `name` is a **parameter**: a variable that exists inside the function.
- `"Asha"` is an **argument**: the actual value passed in when calling.

Several parameters are separated by commas:

```js
function introduce(name, city, age) {
  console.log(`${name}, ${age}, lives in ${city}.`);
}

introduce("Meera", "Jaipur", 24);
introduce("Kabir", "Kochi");  // age is undefined
```

A missing argument becomes `undefined`.

## Returning values

Most functions **calculate** something and give it back with `return`:

```js
function add(a, b) {
  return a + b;
}

const sum = add(4, 6);
console.log(sum);              // 10
console.log(add(add(1, 2), 3)); // 6: use the result directly
```

`return` does two things:

1. Sends a value back to wherever the function was called.
2. **Stops** the function immediately. Code after `return` never runs.

```js
function checkAge(age) {
  if (age < 0) {
    return "Invalid age";
  }
  if (age >= 18) {
    return "Adult";
  }
  return "Minor";
}

console.log(checkAge(25), checkAge(12), checkAge(-3));
```

A function without `return` gives back `undefined`.

### console.log vs return

A very common beginner confusion:

```js
function doubleLog(n) {
  console.log(n * 2);   // shows the value, but gives nothing back
}

function doubleReturn(n) {
  return n * 2;         // gives the value back to use later
}

const a = doubleLog(5);     // prints 10
const b = doubleReturn(5);  // prints nothing
console.log(a, b);          // undefined 10
```

Use `return` when the result is needed by other code; `console.log` is only for looking.

## Default parameters

```js
function createUser(name, role = "student", active = true) {
  return { name, role, active };
}

console.log(createUser("Asha"));
console.log(createUser("Rohan", "teacher"));
```

If an argument is missing (or `undefined`), the default is used.

## Rest parameters

`...` collects any number of arguments into an array:

```js
function total(...prices) {
  return prices.reduce((sum, p) => sum + p, 0);
}

console.log(total(100, 250));          // 350
console.log(total(99, 199, 299, 49));  // 646
```

## Object parameters

When a function needs many options, pass one object and destructure it. The order of arguments then no longer matters, and calls are self-explaining:

```js
function formatPrice({ amount, currency = "INR", decimals = 2 }) {
  return new Intl.NumberFormat("en-IN", {
    style: "currency",
    currency,
    minimumFractionDigits: decimals
  }).format(amount);
}

console.log(formatPrice({ amount: 1499 }));
console.log(formatPrice({ amount: 20, currency: "USD", decimals: 0 }));
```

## Function expressions

A function can also be stored in a variable:

```js
const square = function (n) {
  return n * n;
};

console.log(square(9));
```

Functions are **values** in JavaScript: you can store them, put them in arrays and objects, and pass them to other functions. That's exactly what you did with `map` and `filter`:

```js
function isEven(n) {
  return n % 2 === 0;
}

console.log([1, 2, 3, 4, 5, 6].filter(isEven)); // pass the function itself, no brackets
```

A function passed to another function is called a **callback**.

## Hoisting

Function **declarations** can be called before they appear in the code; JavaScript moves ("hoists") them to the top:

```js
console.log(cube(3)); // 27: works

function cube(n) {
  return n ** 3;
}
```

Function **expressions** stored in `const` can't be used before their line.

## Writing good functions

- **One job per function.** `calculateTotal` shouldn't also update the page and send an email.
- **Name it with a verb** that says what it does: `getUser`, `formatDate`, `isValidEmail`.
- **Keep it short.** If it doesn't fit on your screen, split it.
- **Prefer returning values** over changing variables outside the function.

```js
function isValidEmail(email) {
  return email.includes("@") && email.includes(".") && email.length >= 6;
}

console.log(isValidEmail("asha@example.com")); // true
console.log(isValidEmail("asha.example"));     // false
```

## Common mistakes

- **Forgetting the brackets** when calling: `greet` instead of `greet()`.
- **Forgetting `return`**, then wondering why the result is `undefined`.
- **Code after `return`**, which never runs.
- **Calling a callback instead of passing it**: `arr.filter(isEven())` calls it immediately; write `arr.filter(isEven)`.

## Exercise

1. Write `celsiusToFahrenheit(c)` that returns `c * 9/5 + 32`. Test it with 0, 37 and 100.
2. Write `isAdult(age)` that returns true or false.
3. Write `average(...numbers)` that returns the average of any number of values.
4. Write `createProfile({ name, city = "Unknown" })` that returns a sentence.
5. Write `capitalize(word)` and use it with `map` on an array of lower-case names.

## Summary

- Declare functions with `function name(params) { … }` and call them with `name(args)`.
- `return` sends a value back and ends the function.
- Default parameters, rest parameters and object parameters make functions flexible.
- Functions are values: store them, and pass them as callbacks.

Next, you'll learn the short arrow function syntax used throughout modern JavaScript.
