---
title: JavaScript Loops (for, while)
slug: js-loops
subject: javascript
lesson: 11
difficulty: beginner
excerpt: Repeat code with for, while, do...while, for...of and for...in loops, control them with break and continue, and avoid infinite loops.
---

Computers are great at repetition. A **loop** runs the same code many times: printing every item in a list, counting down a timer, trying a password until it's right. JavaScript has several loops; each suits a different situation.

## The for loop

```js
for (let i = 1; i <= 5; i++) {
  console.log(`Lap ${i}`);
}
```

The brackets have three parts, separated by semicolons:

1. **Start**: `let i = 1` runs once, before the loop begins.
2. **Condition**: `i <= 5` is checked before each round. If false, the loop stops.
3. **Update**: `i++` runs after each round.

So the loop runs with i = 1, 2, 3, 4, 5, then stops when i becomes 6.

### Counting in other ways

```js
for (let i = 10; i > 0; i -= 2) {
  console.log(i); // 10, 8, 6, 4, 2
}

// Times table
const n = 7;
for (let i = 1; i <= 10; i++) {
  console.log(`${n} × ${i} = ${n * i}`);
}
```

### Looping over an array by index

```js
const fruits = ["apple", "banana", "cherry"];

for (let i = 0; i < fruits.length; i++) {
  console.log(`${i + 1}. ${fruits[i]}`);
}
```

Indexes start at 0, so the condition is `i < fruits.length` (not `<=`).

## for...of: the easy way to loop over values

```js
const fruits = ["apple", "banana", "cherry"];

for (const fruit of fruits) {
  console.log(fruit);
}

for (const letter of "Hi!") {
  console.log(letter);
}
```

`for...of` gives you each **value** directly: no index, no off-by-one errors. Use it whenever you don't need the index. It works on arrays, strings and other "iterable" collections.

## while

A `while` loop repeats **as long as** a condition is true. Use it when you don't know in advance how many rounds you'll need:

```js
let balance = 1000;
let months = 0;

while (balance < 2000) {
  balance = balance * 1.1; // 10% growth per month
  months++;
}

console.log(`It takes ${months} months to double: ${balance.toFixed(2)}`);
```

## do...while

`do...while` checks the condition **after** each round, so the code always runs at least once:

```js
let roll;
let attempts = 0;

do {
  roll = Math.floor(Math.random() * 6) + 1;
  attempts++;
  console.log(`Rolled ${roll}`);
} while (roll !== 6);

console.log(`Got a 6 after ${attempts} attempts.`);
```

## break and continue

- `break` exits the loop immediately.
- `continue` skips the rest of this round and moves on to the next.

```js
const numbers = [4, 9, -1, 16, 25];

for (const n of numbers) {
  if (n < 0) {
    console.log("Negative number found, stopping.");
    break;
  }
  console.log(`√${n} = ${Math.sqrt(n)}`);
}

for (let i = 1; i <= 10; i++) {
  if (i % 3 === 0) continue; // skip multiples of 3
  console.log(i);
}
```

## Looping over objects with for...in

```js
const course = { title: "JavaScript", lessons: 25, level: "beginner" };

for (const key in course) {
  console.log(`${key}: ${course[key]}`);
}
```

`for...in` loops over an object's **keys** (property names). Don't use it on arrays; use `for...of` there. You'll also see `Object.entries()` in the Objects lesson, which is often nicer.

## Nested loops

A loop inside a loop runs the inner loop completely for every round of the outer one:

```js
for (let row = 1; row <= 3; row++) {
  let line = "";
  for (let col = 1; col <= 4; col++) {
    line += "* ";
  }
  console.log(line);
}
```

Nested loops are useful for grids and tables, but can get slow on large data: 1,000 × 1,000 is a million rounds.

## Building results in a loop

A very common pattern: start with an empty value and add to it in each round.

```js
const prices = [199, 349, 99, 520];
let total = 0;
let expensive = [];

for (const price of prices) {
  total += price;
  if (price > 300) {
    expensive.push(price);
  }
}

console.log(`Total: ₹${total}`);
console.log("Items over ₹300:", expensive);
```

In the Array Methods lesson you'll learn shortcuts like `reduce` and `filter` that do this in one line.

## Infinite loops

If the condition never becomes false, the loop never stops and the page freezes:

```js
// DON'T RUN THIS
// let i = 0;
// while (i < 5) {
//   console.log(i);   // i never changes, so i < 5 is always true
// }
```

Always make sure something inside the loop moves it towards its end. If a page freezes, close the tab.

## Which loop to use

| Situation | Loop |
|---|---|
| Every value in an array or string | `for...of` |
| Need the index, or counting numbers | `for` |
| Unknown number of rounds | `while` |
| Must run at least once | `do...while` |
| Keys of an object | `for...in` or `Object.keys()` |

## Common mistakes

- **Off-by-one errors**: `i <= arr.length` goes one past the end.
- **Forgetting to update the counter** in a `while` loop → infinite loop.
- **Using `for...in` on arrays**, which gives string indexes and can include extra properties.
- **Changing an array's length while looping over it.**

## Exercise

1. Print all even numbers from 2 to 20.
2. Sum the numbers from 1 to 100 (the answer is 5050).
3. Given an array of names, print only names longer than 4 characters with `for...of`.
4. Print a 5-row triangle of stars (`*`, `**`, `***`…) with nested loops.
5. Use a `while` loop to find how many times you can halve 1000 before it drops below 1.

## Summary

- `for` counts; `for...of` loops over values; `for...in` loops over object keys.
- `while` repeats while a condition is true; `do...while` runs at least once.
- `break` exits a loop; `continue` skips to the next round.
- Make sure every loop can end.

Next, you'll learn arrays, the main way to store lists in JavaScript.
