---
title: JavaScript Switch
slug: js-switch
subject: javascript
lesson: 10
difficulty: beginner
excerpt: Choose between many exact values with switch, understand break and fall-through, use default, and know when an object lookup is cleaner.
---

When you compare one value against many possible exact values, a long `else if` chain gets repetitive. The `switch` statement is designed for exactly this case.

## Basic switch

```js
const day = "tue";
let message;

switch (day) {
  case "mon":
    message = "Start of the week. Plan your lessons.";
    break;
  case "tue":
    message = "Tuesday: practise JavaScript.";
    break;
  case "wed":
    message = "Midweek review.";
    break;
  default:
    message = "Keep going!";
}

console.log(message);
```

How it works:

1. The value in `switch ( )` is compared with each `case` using strict equality (`===`).
2. When a case matches, the code after it runs.
3. `break` exits the switch.
4. `default` runs if no case matches (like a final `else`).

## The same logic with if/else

```js
const day = "tue";
let message;

if (day === "mon") {
  message = "Start of the week. Plan your lessons.";
} else if (day === "tue") {
  message = "Tuesday: practise JavaScript.";
} else if (day === "wed") {
  message = "Midweek review.";
} else {
  message = "Keep going!";
}
console.log(message);
```

Both are correct. `switch` reads more clearly when there are many cases comparing **the same value** with fixed options.

## Don't forget break: fall-through

Without `break`, execution "falls through" into the next case:

```js
const level = 2;

switch (level) {
  case 1:
    console.log("Level 1 unlocked");
  case 2:
    console.log("Level 2 unlocked");
  case 3:
    console.log("Level 3 unlocked");
}
// Prints "Level 2 unlocked" and "Level 3 unlocked"
```

Once case 2 matches, every following case runs too, until a `break` or the end. Forgetting `break` is the most common switch bug.

## Grouping cases on purpose

Fall-through is useful when several values should share the same code:

```js
const month = 2;
let days;

switch (month) {
  case 2:
    days = 28;
    break;
  case 4:
  case 6:
  case 9:
  case 11:
    days = 30;
    break;
  default:
    days = 31;
}

console.log(`Month ${month} has ${days} days (ignoring leap years).`);
```

Empty cases stacked on top of each other all run the same block.

## Switch uses strict comparison

```js
const input = "1";

switch (input) {
  case 1:
    console.log("number one");
    break;
  case "1":
    console.log("string one");
    break;
}
// "string one": "1" !== 1
```

Values from forms are strings, so convert them (`Number(input)`) or use string cases.

## Ranges with switch (true)

`switch` compares exact values, so ranges like "score above 90" are better written with `if/else`. You'll sometimes see this trick:

```js
const score = 82;

switch (true) {
  case score >= 90:
    console.log("A");
    break;
  case score >= 75:
    console.log("B");
    break;
  default:
    console.log("C or below");
}
```

It works (each case expression is compared with `true`), but most developers find an `if/else` chain clearer for ranges.

## Returning from switch inside a function

Inside a function, `return` exits immediately, so no `break` is needed:

```js
function statusLabel(code) {
  switch (code) {
    case 200: return "OK";
    case 404: return "Not Found";
    case 500: return "Server Error";
    default:  return "Unknown status";
  }
}

console.log(statusLabel(404));
console.log(statusLabel(418));
```

## An alternative: object lookup

For mapping values to values, an object is often shortest:

```js
const labels = {
  200: "OK",
  404: "Not Found",
  500: "Server Error"
};

const code = 500;
console.log(labels[code] ?? "Unknown status");
```

You'll learn objects in lesson 14. Rule of thumb:

- **Object lookup**: simple value → value mappings.
- **switch**: each case needs to run different statements.
- **if/else**: ranges and complex conditions.

## Common mistakes

- **Missing `break`**, causing accidental fall-through.
- **Type mismatch**: `case 1` won't match the string `"1"`.
- **Using switch for ranges** where `if/else` is clearer.
- **Forgetting `default`** for unexpected values.

## Exercise

1. Write a switch that converts a number 1–7 into a day name, with a default "Invalid day".
2. Group cases so that "sat" and "sun" print "Weekend" and other days print "Weekday".
3. Write a simple calculator: given two numbers and an operator (`"+"`, `"-"`, `"*"`, `"/"`), print the result.
4. Rewrite your day-name switch as an object lookup.

## Summary

- `switch` compares one value against many `case` values with `===`.
- `break` stops execution; without it, code falls through to the next case.
- Stack empty cases to share code; `default` handles everything else.
- Use object lookups for simple mappings and `if/else` for ranges.

Next, you'll repeat code with for and while loops.
