---
title: JavaScript Numbers
slug: js-numbers
subject: javascript
lesson: 8
difficulty: beginner
excerpt: Work with numbers: rounding, fixed decimals, converting from strings, NaN checks, the Math object, random numbers and formatting prices.
---

Numbers are everywhere in programs: prices, scores, sizes, timers. JavaScript has a single number type for both whole numbers and decimals, plus a built-in `Math` object full of useful functions.

## Integers and decimals

```js
const items = 3;          // integer
const price = 249.99;     // decimal (floating point)
const big = 1_000_000;    // underscores make big numbers readable
console.log(items * price, big);
```

## The decimal precision problem

```js
console.log(0.1 + 0.2);            // 0.30000000000000004
console.log(0.1 + 0.2 === 0.3);    // false
```

Computers store decimals in binary, and some decimals (like 0.1) can't be stored exactly. It's the same in Python, Java and most languages. Two solutions:

```js
// 1. Round when displaying
console.log((0.1 + 0.2).toFixed(2));  // "0.30"

// 2. For money, calculate in the smallest unit (paise, cents)
const pricePaise = 24999;     // ₹249.99
const totalPaise = pricePaise * 3;
console.log(totalPaise / 100); // 749.97
```

## Rounding

```js
const n = 4.567;
console.log(Math.round(n));  // 5: nearest whole number
console.log(Math.floor(n));  // 4: always down
console.log(Math.ceil(n));   // 5: always up
console.log(Math.trunc(-4.7)); // -4: just removes the decimals
console.log(n.toFixed(1));   // "4.6": a string with 1 decimal
```

`toFixed()` returns a **string**, which is perfect for display. Convert back with `Number()` if you need to calculate further.

`Math.ceil` is handy for page counts: 23 items at 10 per page needs `Math.ceil(23 / 10)` = 3 pages.

## Converting strings to numbers

Values from form fields and URLs are always strings:

```js
console.log(Number("42"));       // 42
console.log(Number("42.5"));     // 42.5
console.log(Number(""));         // 0 (careful!)
console.log(Number("42px"));     // NaN
console.log(parseInt("42px"));   // 42: reads digits until it hits something else
console.log(parseFloat("3.75 kg")); // 3.75
console.log(+"7");               // 7: the unary plus also converts
```

- `Number()` is strict: the whole string must be a number.
- `parseInt()` and `parseFloat()` read from the start and stop at the first invalid character.

## NaN and checking for valid numbers

```js
const result = Number("abc");
console.log(result);                 // NaN
console.log(result === NaN);         // false: NaN is not equal to anything, even itself!
console.log(Number.isNaN(result));   // true: the correct check
console.log(Number.isFinite(42));    // true
console.log(Number.isInteger(4.0));  // true
console.log(Number.isInteger(4.5));  // false
```

When validating user input, `Number.isFinite(Number(value))` checks that it's a real, usable number.

## The Math object

```js
console.log(Math.max(4, 19, 7));   // 19
console.log(Math.min(4, 19, 7));   // 4
console.log(Math.abs(-12));        // 12: distance from zero
console.log(Math.sqrt(81));        // 9
console.log(Math.pow(2, 8));       // 256 (same as 2 ** 8)
console.log(Math.PI);              // 3.141592653589793
```

`Math.max(...array)` finds the largest value in an array (the `...` "spread" operator is explained in the Arrays lessons):

```js
const scores = [72, 95, 88];
console.log(Math.max(...scores)); // 95
```

## Random numbers

`Math.random()` returns a decimal from 0 (included) up to 1 (not included):

```js
console.log(Math.random());

// A whole number from 1 to 6, like a dice roll:
const dice = Math.floor(Math.random() * 6) + 1;
console.log("You rolled", dice);

// Pick a random item from an array:
const tips = ["Drink water", "Take a break", "Stretch"];
console.log(tips[Math.floor(Math.random() * tips.length)]);
```

The pattern `Math.floor(Math.random() * N)` gives a whole number from 0 to N − 1. (Don't use `Math.random()` for passwords or security codes; browsers have `crypto.getRandomValues()` for that.)

## Formatting numbers for people

```js
const amount = 1234567.891;
console.log(amount.toLocaleString("en-IN"));  // "12,34,567.891"
console.log(amount.toLocaleString("en-US"));  // "1,234,567.891"

const inr = new Intl.NumberFormat("en-IN", { style: "currency", currency: "INR" });
console.log(inr.format(249.5));    // "₹249.50"

const usd = new Intl.NumberFormat("en-US", { style: "currency", currency: "USD" });
console.log(usd.format(249.5));    // "$249.50"

const pct = new Intl.NumberFormat("en", { style: "percent" });
console.log(pct.format(0.42));     // "42%"
```

`Intl.NumberFormat` formats numbers correctly for any country, including currency symbols and digit grouping (Indian lakhs and crores, or Western thousands).

## Common mistakes

- **Comparing decimals exactly**: round first or work in whole units.
- **Checking `x === NaN`**: always false. Use `Number.isNaN(x)`.
- **Forgetting `toFixed` returns a string**: `price.toFixed(2) + 1` joins text.
- **`Math.random() * 6` without `Math.floor`**, giving decimals.
- **`Number("")` being 0**: check for empty input separately.

## Exercise

1. Calculate the total of 3 items at ₹199.90 and show it with exactly 2 decimals.
2. Write code that rolls two dice and prints both values and their sum.
3. Convert `"1500.75"` to a number, add 18% tax, and format it as Indian rupees.
4. Calculate how many pages are needed to show 47 search results at 10 per page.
5. Check whether the input `"12abc"` is a valid number with `Number()` and `Number.isNaN()`.

## Summary

- JavaScript has one number type; decimals can be slightly imprecise, so round or use whole units.
- `Math.round`, `floor`, `ceil` and `toFixed` handle rounding.
- `Number()`, `parseInt()` and `parseFloat()` convert strings; check results with `Number.isNaN`.
- `Math.random()` makes random numbers; `Intl.NumberFormat` formats them for people.

Next, you'll make decisions in your code with if and else.
