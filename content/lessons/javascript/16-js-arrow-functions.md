---
title: JavaScript Arrow Functions
slug: js-arrow-functions
subject: javascript
lesson: 16
difficulty: intermediate
excerpt: Write short functions with the arrow syntax, learn implicit returns and returning objects, and understand how arrow functions treat this differently.
---

Arrow functions are a shorter way to write functions, added to JavaScript in 2015. You've already used them with `map` and `filter`. They're everywhere in modern code, so it's worth understanding them properly, including the one important way they behave differently.

## From function to arrow

The same function, written three ways:

```js
// 1. Function declaration
function double(n) {
  return n * 2;
}

// 2. Arrow function with a body
const double2 = (n) => {
  return n * 2;
};

// 3. Arrow function with an implicit return
const double3 = n => n * 2;

console.log(double(5), double2(5), double3(5)); // 10 10 10
```

To turn a function into an arrow function:

1. Remove the word `function`.
2. Put `=>` between the parameters and the body.
3. If the body is a single expression, remove the braces and `return`: the value is returned automatically (**implicit return**).

## Parameter rules

```js
const sayHi = () => "Hi!";               // no parameters: empty brackets required
const square = x => x * x;               // one parameter: brackets optional
const add = (a, b) => a + b;             // two or more: brackets required
const greet = (name = "friend") => `Hello, ${name}`; // defaults need brackets

console.log(sayHi(), square(4), add(2, 3), greet());
```

Many teams always use brackets, `(x) => x * x`, for consistency. Code formatters like Prettier do this by default.

## When you need braces

If the function needs more than one statement, use braces and an explicit `return`:

```js
const priceWithTax = (price, rate = 0.18) => {
  const tax = price * rate;
  const total = price + tax;
  return Math.round(total);
};

console.log(priceWithTax(1000)); // 1180
```

**Watch out:** with braces, nothing is returned unless you write `return`:

```js
const broken = n => { n * 2 };
console.log(broken(5)); // undefined
```

## Returning an object

Braces after `=>` are read as a function body, not an object. Wrap an object in brackets:

```js
const makeUserWrong = name => { name: name };    // body with a label, returns undefined
const makeUser = name => ({ name, joined: Date.now() > 0 });

console.log(makeUserWrong("Asha")); // undefined
console.log(makeUser("Asha"));      // { name: "Asha", joined: true }
```

## Arrow functions as callbacks

Arrow functions shine when passing small functions to other functions:

```js
const scores = [72, 95, 41, 88, 60];

const passed = scores.filter(s => s >= 50);
const curved = scores.map(s => Math.min(s + 5, 100));
const best = scores.reduce((max, s) => (s > max ? s : max), 0);
const sorted = [...scores].sort((a, b) => b - a);

console.log(passed, curved, best, sorted);
```

Compare `scores.filter(function (s) { return s >= 50; })`: the arrow version is easier to read at a glance.

They're also common with timers and events:

```js
setTimeout(() => {
  console.log("One second later…");
}, 1000);
```

## The big difference: this

Regular functions get their own `this`, which depends on **how** they're called. Arrow functions **don't have their own `this`**; they use the `this` of the code around them.

This makes arrow functions perfect **inside** methods:

```js
const timer = {
  seconds: 0,
  start() {
    const id = setInterval(() => {
      this.seconds++;                 // "this" is still timer: correct
      console.log(`${this.seconds}s`);
      if (this.seconds === 3) clearInterval(id);
    }, 300);
  }
};

timer.start();
```

If you used `function () { this.seconds++ }` inside `setInterval`, `this` would not be the timer object and the code would break.

But it makes arrow functions a **bad choice for methods themselves**:

```js
const user = {
  name: "Rohan",
  greetArrow: () => `Hi, I'm ${this?.name}`,       // this is NOT user
  greetMethod() { return `Hi, I'm ${this.name}`; } // this IS user
};

console.log(user.greetArrow());  // "Hi, I'm undefined"
console.log(user.greetMethod()); // "Hi, I'm Rohan"
```

**Rule of thumb:**

- Object methods → method shorthand `name() { … }`.
- Callbacks, small helpers, functions inside methods → arrow functions.

## Other differences (less common)

- Arrow functions can't be used with `new` to create objects.
- They don't have the old `arguments` object; use rest parameters `(...args)` instead.
- They aren't hoisted: define them before you use them.

```js
const logAll = (...args) => args.forEach(a => console.log(a));
logAll("one", "two", "three");
```

## Readable arrow functions

Short isn't always clear. Name complex callbacks:

```js
const orders = [
  { total: 1200, status: "paid" },
  { total: 300, status: "pending" },
  { total: 850, status: "paid" }
];

const isPaid = order => order.status === "paid";
const toTotal = order => order.total;
const sum = (a, b) => a + b;

const revenue = orders.filter(isPaid).map(toTotal).reduce(sum, 0);
console.log(revenue); // 2050
```

The last line now reads almost like a sentence.

## Common mistakes

- **Braces without `return`** → the function returns `undefined`.
- **Returning an object without wrapping brackets.**
- **Arrow functions as object methods**, losing `this`.
- **Overly clever one-liners** that are hard to read.

## Exercise

1. Rewrite `function triple(x) { return x * 3; }` as an arrow function with an implicit return.
2. Write an arrow function `fullName(first, last)` that returns a template string.
3. Write `toUser = name => ({ … })` that returns an object with name and `createdAt: new Date().toISOString()`.
4. Given an array of words, use `filter` and `map` with arrow functions to get the upper-case versions of words longer than 3 letters.
5. Create a `counter` object whose `start()` method uses `setInterval` with an arrow function to count to 5.

## Summary

- `(params) => expression` returns the expression automatically.
- Use braces and `return` for multi-line bodies; wrap returned objects in `( )`.
- Arrow functions use the surrounding `this`, which is ideal for callbacks inside methods.
- Use method shorthand, not arrows, for object methods.

Next, you'll learn about scope: where variables can be seen and used.
