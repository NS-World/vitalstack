---
title: JavaScript Data Types
slug: js-data-types
subject: javascript
lesson: 5
difficulty: beginner
excerpt: Strings, numbers, booleans, undefined, null, objects and arrays; checking types with typeof; and how JavaScript converts between types.
---

Every value in JavaScript has a **type**: text is a string, 42 is a number, `true` is a boolean. The type decides what you can do with a value: you can multiply numbers but not sentences. Understanding types helps you avoid some of JavaScript's most famous surprises.

## The main types

```js
const name = "Asha";             // string
const age = 21;                  // number
const price = 199.5;             // number (no separate decimal type)
const isStudent = true;          // boolean
let nickname;                    // undefined
const middleName = null;         // null
const user = { name: "Asha" };   // object
const skills = ["HTML", "CSS"];  // array (a kind of object)

console.log(typeof name, typeof age, typeof isStudent);
```

JavaScript has seven **primitive** types (string, number, boolean, undefined, null, bigint, symbol) plus **objects**. You'll use the first five and objects every day; bigint and symbol are for special cases.

## Strings

Text, written in quotes:

```js
const single = 'Hello';
const double = "Hello";
const template = `Hello, ${single}!`;
console.log(single, double, template);
```

Single and double quotes work the same. Backticks create template literals with `${ }` placeholders. Strings get a whole lesson of their own.

## Numbers

```js
console.log(10 + 5);      // 15
console.log(7 / 2);       // 3.5
console.log(0.1 + 0.2);   // 0.30000000000000004
console.log(10 / 0);      // Infinity
console.log("abc" * 2);   // NaN
```

- Whole numbers and decimals are the same type.
- `0.1 + 0.2` isn't exactly `0.3` because of how computers store decimals. For money, calculate in the smallest unit (paise, cents) or round the result.
- `NaN` means "Not a Number": the result of a maths operation that makes no sense.

## Booleans

Only two values: `true` and `false`. They're the answers to yes/no questions and drive every decision in a program:

```js
const age = 20;
const isAdult = age >= 18;
console.log(isAdult); // true
```

## undefined and null

Both mean "no value", with a small difference:

- `undefined`: a value **hasn't been set** yet. JavaScript uses it automatically: an unassigned variable, a missing object property, a function that returns nothing.
- `null`: **intentionally empty**. You set it yourself to say "there's deliberately nothing here".

```js
let selectedCourse;
console.log(selectedCourse); // undefined: not chosen yet

let winner = null;           // we know there's no winner yet
console.log(winner);
```

## Objects and arrays

Objects group related values under names; arrays hold ordered lists:

```js
const book = { title: "Clean Code", pages: 464, available: true };
const colours = ["red", "green", "blue"];

console.log(book.title);   // "Clean Code"
console.log(colours[0]);   // "red"
```

Both have full lessons later in this course.

## Checking a type with typeof

```js
console.log(typeof "hi");        // "string"
console.log(typeof 3.14);        // "number"
console.log(typeof false);       // "boolean"
console.log(typeof undefined);   // "undefined"
console.log(typeof {});          // "object"
console.log(typeof []);          // "object" (arrays are objects)
console.log(typeof null);        // "object" (a famous old bug!)
console.log(Array.isArray([]));  // true: the right way to check for arrays
```

`typeof null` returning `"object"` is a mistake from 1995 that can never be fixed without breaking old websites. To check for null, use `value === null`.

## Dynamic typing

A variable can hold any type, and the type can change:

```js
let value = 42;
console.log(typeof value); // "number"
value = "forty-two";
console.log(typeof value); // "string"
```

This is flexible but can hide bugs. (TypeScript, a popular extension of JavaScript, adds type checking for big projects.)

## Type conversion

JavaScript often converts types automatically, sometimes surprisingly:

```js
console.log("5" + 2);   // "52": + with a string joins text
console.log("5" - 2);   // 3: - only works on numbers, so "5" becomes 5
console.log("5" * "2"); // 10
console.log(true + 1);  // 2: true becomes 1
```

The `+` rule causes the most bugs: values from form fields are **always strings**, so `"10" + "5"` gives `"105"`, not 15.

Convert explicitly to be safe:

```js
const input = "10";
console.log(Number(input) + 5);   // 15
console.log(String(99) + "%");    // "99%"
console.log(parseInt("42px"));    // 42
console.log(parseFloat("3.5kg")); // 3.5
console.log(Number("hello"));     // NaN
```

## Truthy and falsy

In conditions, every value counts as either true or false. These are **falsy**:

```js
// false, 0, "" (empty string), null, undefined, NaN
console.log(Boolean(0), Boolean(""), Boolean(null));  // false false false
console.log(Boolean("0"), Boolean([]), Boolean("hi")); // true true true
```

Everything else is **truthy**, including `"0"`, `"false"` and empty arrays. You'll use this in `if` statements: `if (username) { ... }` runs only when username isn't empty.

## Common mistakes

- **Adding form values without converting**: `"10" + "5"` → `"105"`.
- **Checking arrays with typeof** instead of `Array.isArray()`.
- **Exact comparisons of decimals** like `0.1 + 0.2 === 0.3` (false).
- **Confusing `null` and `undefined`**; check for both with `value == null` or explicitly.

## Exercise

1. Create one variable of each main type and print each with its `typeof`.
2. Predict the result of `"3" + 4 + 5` and `3 + 4 + "5"`, then check. (Hint: JavaScript works left to right.)
3. Convert the string `"250"` to a number and add 50.
4. Print `Boolean()` of `0`, `"0"`, `[]`, `""` and `"hello"`.

## Summary

- Main types: string, number, boolean, undefined, null, object (including arrays).
- `typeof` checks a type; use `Array.isArray()` for arrays and `=== null` for null.
- `+` with a string joins text; convert with `Number()`, `String()`, `parseInt()`.
- Falsy values: `false`, `0`, `""`, `null`, `undefined`, `NaN`. Everything else is truthy.

Next, you'll learn operators for maths, comparison and logic.
