---
title: JavaScript Operators
slug: js-operators
subject: javascript
lesson: 6
difficulty: beginner
excerpt: Arithmetic, assignment, comparison and logical operators, why to use === instead of ==, the ternary operator, and nullish coalescing.
---

Operators are the symbols that do work on values: `+` adds, `>` compares, `&&` combines conditions. You've already used a few. This lesson covers all the everyday operators, plus one rule that prevents many bugs: always use `===`.

## Arithmetic operators

```js
console.log(10 + 3);  // 13  addition
console.log(10 - 3);  // 7   subtraction
console.log(10 * 3);  // 30  multiplication
console.log(10 / 3);  // 3.3333333333333335  division
console.log(10 % 3);  // 1   remainder (modulo)
console.log(2 ** 5);  // 32  exponent (2 to the power 5)
```

The **remainder** operator `%` is more useful than it looks:

```js
const n = 14;
console.log(n % 2 === 0 ? "even" : "odd"); // even
console.log(125 % 60); // 5: 125 seconds is 2 minutes and 5 seconds
```

### Order of operations

Like in maths, `*`, `/` and `%` happen before `+` and `-`. Use brackets to make the order clear:

```js
console.log(2 + 3 * 4);   // 14
console.log((2 + 3) * 4); // 20
```

## Increment and decrement

```js
let likes = 10;
likes++;    // 11
likes--;    // 10
console.log(likes);
```

## Assignment operators

```js
let total = 100;
total += 50;  // total = total + 50  → 150
total -= 20;  // total = total - 20  → 130
total *= 2;   // total = total * 2   → 260
total /= 4;   // total = total / 4   → 65
console.log(total);

let greeting = "Hello";
greeting += ", world";
console.log(greeting); // "Hello, world"
```

## Comparison operators

Comparisons produce a boolean, `true` or `false`:

```js
console.log(5 > 3);    // true
console.log(5 < 3);    // false
console.log(5 >= 5);   // true
console.log(4 <= 3);   // false
console.log(5 === 5);  // true  equal
console.log(5 !== 3);  // true  not equal
```

## === vs ==: always use the triple version

```js
console.log(5 == "5");    // true: == converts types before comparing
console.log(5 === "5");   // false: === also checks the type
console.log(0 == "");     // true (!)
console.log(0 === "");    // false
console.log(null == undefined);  // true
console.log(null === undefined); // false
```

`==` ("loose equality") converts values to the same type first, which leads to strange results like `0 == ""` being true. `===` ("strict equality") compares value **and** type, with no surprises.

**Rule: use `===` and `!==` everywhere.** If you need to compare a form value with a number, convert it first: `Number(input) === 5`.

## Comparing strings

```js
console.log("apple" < "banana"); // true: alphabetical order
console.log("Zebra" < "apple");  // true: uppercase letters come before lowercase!
console.log("10" < "9");         // true: compared as text, character by character
```

Strings are compared character by character using their character codes. To compare numbers stored as text, convert them first.

## Logical operators

```js
const age = 25;
const hasTicket = true;

console.log(age >= 18 && hasTicket);  // AND: true only if both are true
console.log(age < 12 || age > 60);    // OR: true if at least one is true
console.log(!hasTicket);              // NOT: flips true/false
```

| A | B | A && B | A \|\| B |
|---|---|---|---|
| true | true | true | true |
| true | false | false | true |
| false | true | false | true |
| false | false | false | false |

### Short-circuiting

`&&` and `||` stop as soon as they know the answer, and return one of the actual values (not always `true`/`false`):

```js
const name = "";
console.log(name || "Guest");          // "Guest": || returns the first truthy value

const user = { name: "Asha" };
console.log(user && user.name);        // "Asha": && returns the last value if all are truthy
```

`value || "default"` is a classic way to provide a fallback.

## Nullish coalescing (??)

`||` treats `0` and `""` as missing, which is sometimes wrong:

```js
const savedVolume = 0;
console.log(savedVolume || 50);  // 50: wrong, the user chose 0!
console.log(savedVolume ?? 50);  // 0: ?? only falls back on null or undefined
```

Use `??` when `0`, `""` or `false` are valid values.

## The ternary operator

A compact if/else that produces a value:

```js
const score = 72;
const result = score >= 40 ? "Pass" : "Fail";
console.log(result); // "Pass"
```

`condition ? valueIfTrue : valueIfFalse`. Great for simple choices; use a real `if` statement for anything longer.

## typeof and other operators

You've met `typeof`. Two more you'll see later: `in` (does an object have a property?) and `instanceof` (was an object made from a certain class?).

## Common mistakes

- **Using `=` in a comparison**: `if (x = 5)` assigns 5 instead of comparing. Use `===`.
- **Using `==`** and getting surprising true results.
- **Comparing numeric strings** like `"10" < "9"`.
- **Using `||` for defaults** when `0` or `""` are valid; use `??`.

## Exercise

1. Write code that prints whether 37 is even or odd using `%`.
2. Convert 200 minutes into hours and minutes with `/`, `Math.floor()` and `%`.
3. Given `age` and `hasId`, print whether someone can enter a club (18+ and has ID).
4. Use a ternary to print "Free shipping" if a cart total is 500 or more, otherwise "Add more items".
5. Compare `0 || 10` with `0 ?? 10` and explain the difference.

## Summary

- Arithmetic: `+ - * / % **`; assignment shortcuts: `+= -= *= /= ++ --`.
- Comparisons return booleans. **Always use `===` and `!==`.**
- `&&`, `||` and `!` combine conditions; `||` and `??` provide defaults.
- `condition ? a : b` chooses between two values.

Next, you'll work with text using strings and their methods.
