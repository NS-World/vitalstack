---
title: JavaScript Scope
slug: js-scope
subject: javascript
lesson: 17
difficulty: intermediate
excerpt: Global, function and block scope explained, why let and const beat var, how shadowing works, and a first look at closures.
---

**Scope** decides where a variable can be seen and used. A variable created inside a function isn't visible outside it; a variable created at the top of a file is visible everywhere. Understanding scope prevents bugs where variables clash, disappear or change unexpectedly.

## Global scope

A variable declared outside any function or block is **global**: every part of the script can use it.

```js
const siteName = "VitalStack";

function showName() {
  console.log(siteName); // can read the global
}

showName();
console.log(siteName);
```

Globals are convenient, but in a big program any code can change them, which makes bugs hard to trace. Keep globals to a minimum.

## Function scope

Variables declared inside a function exist only inside that function:

```js
function calculate() {
  const result = 6 * 7;
  console.log("Inside:", result);
}

calculate();
// console.log(result); // ReferenceError: result is not defined
```

Each call gets its **own fresh** variables, which disappear when the function ends. Two functions can use the same variable names without interfering:

```js
function a() { const x = 1; console.log("a:", x); }
function b() { const x = 2; console.log("b:", x); }
a();
b();
```

## Block scope

A **block** is any code inside `{ }`: an `if`, a loop, or just braces. Variables declared with `let` and `const` inside a block exist only in that block:

```js
const age = 20;

if (age >= 18) {
  const message = "Adult";
  console.log(message);
}

// console.log(message); // ReferenceError

for (let i = 0; i < 3; i++) {
  // i exists only inside the loop
}
// console.log(i); // ReferenceError
```

This is good: variables live only as long as they're needed.

## Why var is confusing

`var` ignores blocks and only respects functions:

```js
if (true) {
  var leaky = "I escaped the block!";
  let contained = "I stay inside";
}

console.log(leaky);        // works: var leaks out
// console.log(contained); // ReferenceError
```

And `var` can be used before its declaration without an error (it's "hoisted" with the value `undefined`):

```js
console.log(early); // undefined, no error
var early = 5;

// console.log(later); // ReferenceError with let/const
// let later = 5;
```

The classic `var` loop bug:

```js
for (var i = 1; i <= 3; i++) {
  setTimeout(() => console.log("var:", i), 100);
}
// prints 4, 4, 4: all three share one i

for (let j = 1; j <= 3; j++) {
  setTimeout(() => console.log("let:", j), 200);
}
// prints 1, 2, 3: each round gets its own j
```

These surprises are why modern code uses only `let` and `const`.

## The scope chain

When you use a variable, JavaScript looks for it in the **current** scope, then the scope outside it, and so on up to the global scope:

```js
const level = "global";

function outer() {
  const level2 = "outer";

  function inner() {
    const level3 = "inner";
    console.log(level, level2, level3); // can see all three
  }

  inner();
  // console.log(level3); // ReferenceError: can't look inwards
}

outer();
```

Inner scopes can see outward; outer scopes can't see inward.

## Shadowing

If an inner scope declares a variable with the same name as an outer one, the inner one **shadows** (hides) the outer one inside that scope:

```js
const colour = "blue";

function paint() {
  const colour = "red";   // a different variable
  console.log("inside:", colour);
}

paint();
console.log("outside:", colour); // still blue
```

This is allowed, but can be confusing. Use different names when you can.

## Accidental globals

Assigning to a variable that was never declared creates a global by accident (in non-strict code):

```js
function setScore() {
  score = 100;  // forgot let/const!
}
setScore();
console.log(score); // 100: a global was created
```

In **strict mode**, this throws an error instead. Modules and classes are strict automatically; in older scripts, add `"use strict";` at the top of the file. And always declare variables with `let` or `const`.

## Closures: functions remember their scope

A function keeps access to the variables of the scope where it was **created**, even after that outer function has finished. This is called a **closure**:

```js
function makeCounter() {
  let count = 0;            // private to this counter
  return function () {
    count++;
    return count;
  };
}

const counterA = makeCounter();
const counterB = makeCounter();

console.log(counterA()); // 1
console.log(counterA()); // 2
console.log(counterB()); // 1: B has its own count
console.log(counterA()); // 3
```

`count` can't be reached from outside except through the returned function: it's private. Closures are used for private data, event handlers that remember settings, and many library patterns.

Another example: a function factory.

```js
const makeGreeting = greeting => name => `${greeting}, ${name}!`;

const hello = makeGreeting("Hello");
const namaste = makeGreeting("Namaste");

console.log(hello("Asha"));
console.log(namaste("Rohan"));
```

## Common mistakes

- **Using a variable outside the block** where it was declared.
- **Accidental globals** from missing `let`/`const`.
- **Using `var`** and getting leaky or shared variables.
- **Shadowing by accident**, then changing the wrong variable.

## Exercise

1. Write a function that declares a variable, then try to log it outside. Read the error.
2. Create a global `theme` and a function that shadows it; log both.
3. Run the `var` vs `let` loop example and explain the output in your own words.
4. Write `makeMultiplier(n)` that returns a function multiplying its input by n. Create `double` and `triple`.
5. Build a `createWallet(start)` closure with `add(amount)` and `balance()` functions, keeping the balance private.

## Summary

- Global variables are visible everywhere; function and block variables only inside.
- `let` and `const` are block-scoped; `var` ignores blocks, so avoid it.
- JavaScript looks outward through the scope chain, never inward.
- Closures let functions remember variables from where they were created.

Next, you'll start working with the web page itself: selecting elements in the DOM.
