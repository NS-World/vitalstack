---
title: JavaScript Array Methods
slug: js-array-methods
subject: javascript
lesson: 13
difficulty: intermediate
excerpt: Transform and search data with forEach, map, filter, find, some, every and reduce, and chain them together to process lists in a few readable lines.
---

Most real programs work with lists of data: products, users, messages, lessons. JavaScript arrays have built-in methods that let you loop, transform, search and summarise lists in one line each. Learning these well will change how you write code.

All the methods in this lesson take a **function** as an argument, which is called once for each item. We'll write those functions with the short **arrow** syntax, `item => something`. You'll learn functions in detail in lessons 15 and 16; for now, read `n => n * 2` as "take n, give back n times 2".

## The sample data

```js
const products = [
  { name: "Notebook", price: 120, category: "stationery", inStock: true },
  { name: "Headphones", price: 1499, category: "electronics", inStock: false },
  { name: "Pen set", price: 250, category: "stationery", inStock: true },
  { name: "USB cable", price: 299, category: "electronics", inStock: true },
  { name: "Backpack", price: 1899, category: "bags", inStock: true }
];
console.log(products.length, "products");
```

The examples below each start with a copy of this data so they run on their own.

## forEach: do something with each item

```js
const names = ["Asha", "Rohan", "Meera"];

names.forEach((name, index) => {
  console.log(`${index + 1}. ${name}`);
});
```

`forEach` simply runs the function for every item. It returns nothing. (`for...of` does the same job, and many developers prefer it.)

## map: transform every item

`map` creates a **new array** of the same length, with each item transformed:

```js
const prices = [120, 1499, 250];

const withTax = prices.map(p => Math.round(p * 1.18));
console.log(withTax); // [142, 1769, 295]

const products = [
  { name: "Notebook", price: 120 },
  { name: "Pen set", price: 250 }
];
const labels = products.map(p => `${p.name}: ₹${p.price}`);
console.log(labels);
```

Use `map` whenever you think "I want a list of X from this list of Y". It's how lists of data become lists of HTML elements on a page.

## filter: keep only matching items

`filter` creates a new array with only the items for which the function returns true:

```js
const products = [
  { name: "Notebook", price: 120, inStock: true },
  { name: "Headphones", price: 1499, inStock: false },
  { name: "USB cable", price: 299, inStock: true }
];

const available = products.filter(p => p.inStock);
console.log(available.map(p => p.name)); // ["Notebook", "USB cable"]

const cheap = products.filter(p => p.price < 300);
console.log(cheap.length); // 2

const numbers = [5, 12, 8, 21, 3];
console.log(numbers.filter(n => n % 2 === 0)); // [12, 8]
```

## find and findIndex: the first match

```js
const users = [
  { id: 1, name: "Asha" },
  { id: 2, name: "Rohan" },
  { id: 3, name: "Meera" }
];

const user = users.find(u => u.id === 2);
console.log(user);           // { id: 2, name: "Rohan" }

const missing = users.find(u => u.id === 99);
console.log(missing);        // undefined

console.log(users.findIndex(u => u.name === "Meera")); // 2
```

`find` returns the first matching **item** (or `undefined`); `filter` returns an **array** of all matches.

## some and every: yes/no questions

```js
const scores = [72, 88, 45, 91];

console.log(scores.some(s => s < 50));   // true: at least one failed
console.log(scores.every(s => s >= 40)); // true: everyone passed
```

## reduce: boil a list down to one value

`reduce` combines all items into a single result: a total, a maximum, a count, even an object.

```js
const cart = [
  { name: "Notebook", price: 120, qty: 3 },
  { name: "Pen set", price: 250, qty: 1 },
  { name: "USB cable", price: 299, qty: 2 }
];

const total = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
console.log(`Total: ₹${total}`); // ₹1208
```

How it works:

- The function receives an **accumulator** (`sum`, the result so far) and the current item.
- Whatever it returns becomes the accumulator for the next item.
- The `0` at the end is the starting value.

Round by round: 0 → 360 → 610 → 1208.

Counting things by category:

```js
const products = [
  { name: "Notebook", category: "stationery" },
  { name: "Headphones", category: "electronics" },
  { name: "Pen set", category: "stationery" }
];

const counts = products.reduce((acc, p) => {
  acc[p.category] = (acc[p.category] || 0) + 1;
  return acc;
}, {});

console.log(counts); // { stationery: 2, electronics: 1 }
```

## Chaining methods

Because `map` and `filter` return arrays, you can chain them into a clear data pipeline:

```js
const products = [
  { name: "Notebook", price: 120, category: "stationery", inStock: true },
  { name: "Headphones", price: 1499, category: "electronics", inStock: false },
  { name: "Pen set", price: 250, category: "stationery", inStock: true },
  { name: "USB cable", price: 299, category: "electronics", inStock: true },
  { name: "Backpack", price: 1899, category: "bags", inStock: true }
];

const result = products
  .filter(p => p.inStock)
  .filter(p => p.price < 1000)
  .sort((a, b) => a.price - b.price)
  .map(p => `${p.name} (₹${p.price})`);

console.log(result);
```

Read it top to bottom: in stock → under ₹1000 → cheapest first → formatted labels. (Careful: `sort` changes the array it's called on. Here that's fine, because `filter` already made a new array.)

## Other handy methods

```js
const nested = [[1, 2], [3, 4], [5]];
console.log(nested.flat());                   // [1, 2, 3, 4, 5]

const words = ["hello", "world"];
console.log(words.flatMap(w => w.split(""))); // all letters in one array

const nums = [3, 1, 2];
const sorted = nums.toSorted((a, b) => a - b); // sorted copy (modern browsers)
console.log(nums, sorted);

console.log(Array.from({ length: 5 }, (_, i) => i * 10)); // [0, 10, 20, 30, 40]
```

## Which method do I need?

| I want… | Use |
|---|---|
| To do something with each item | `forEach` / `for...of` |
| A new list, same length, transformed | `map` |
| A shorter list of matching items | `filter` |
| The first matching item | `find` |
| A yes/no answer about the list | `some` / `every` |
| One value from the whole list | `reduce` |

## Common mistakes

- **Forgetting to return** in an arrow function with braces: `p => { p.price * 2 }` returns undefined. Write `p => p.price * 2` or add `return`.
- **Using `map` just to loop** and ignoring its result; use `forEach`.
- **Forgetting the starting value in `reduce`**.
- **Expecting `filter` to return one item**; it always returns an array.

## Exercise

Using the products array from the chaining example:

1. Get an array of just the product names with `map`.
2. Get all electronics with `filter`.
3. Find the product called "Backpack" with `find`.
4. Check if any product costs more than ₹1500 with `some`.
5. Calculate the total price of all in-stock products with `filter` + `reduce`.
6. Produce a list of "NAME – ₹PRICE" strings for stationery, sorted by price.

## Summary

- `map` transforms, `filter` selects, `find` gets the first match, `reduce` combines.
- `some` and `every` answer yes/no questions about a list.
- These methods return new arrays (except `forEach`, `sort` and `reverse`), so they chain well.
- Chained methods turn complex data processing into readable pipelines.

Next, you'll learn objects, the way to group related data with names.
