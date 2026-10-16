---
title: JavaScript If/Else
slug: js-if-else
subject: javascript
lesson: 9
difficulty: beginner
excerpt: Make decisions with if, else if and else, combine conditions, use truthy checks, write guard clauses and avoid deeply nested code.
---

Programs need to make decisions: show a "Log in" button or the user's name, charge shipping or not, pass or fail a test. The `if` statement runs code only when a condition is true.

## if

```js
const temperature = 34;

if (temperature > 30) {
  console.log("It's hot. Drink water!");
}

console.log("This line always runs.");
```

The condition goes in brackets `( )`. If it's true, the code inside the braces `{ }` runs; if not, it's skipped.

## else

```js
const age = 16;

if (age >= 18) {
  console.log("You can vote.");
} else {
  console.log(`You can vote in ${18 - age} years.`);
}
```

Exactly one of the two blocks runs.

## else if: more than two options

```js
const score = 76;
let grade;

if (score >= 90) {
  grade = "A";
} else if (score >= 75) {
  grade = "B";
} else if (score >= 60) {
  grade = "C";
} else if (score >= 40) {
  grade = "D";
} else {
  grade = "F";
}

console.log(`Score ${score} → grade ${grade}`);
```

JavaScript checks the conditions **from top to bottom** and runs the **first** block whose condition is true, then skips the rest. That's why the order matters here: a score of 95 is also `>= 75`, but it never reaches that check because the first condition already matched.

## Combining conditions

```js
const age = 25;
const hasLicence = true;
const isWeekend = false;

if (age >= 18 && hasLicence) {
  console.log("You can rent a car.");
}

if (isWeekend || age < 12) {
  console.log("Discounted ticket.");
} else {
  console.log("Regular ticket.");
}

if (!hasLicence) {
  console.log("Apply for a licence first.");
}
```

Use brackets to make complex conditions clear: `if ((a && b) || c)`.

## Truthy checks

Because every value is truthy or falsy, you can check whether something "exists":

```js
const username = "";

if (username) {
  console.log(`Welcome back, ${username}!`);
} else {
  console.log("Welcome, guest!");
}

const cart = [];
if (cart.length) {
  console.log("You have items in your cart.");
} else {
  console.log("Your cart is empty.");
}
```

Remember: an empty array `[]` is **truthy**, so check `cart.length`, not `cart`. And `0` is falsy, so `if (count)` is false when count is 0.

## A real example: form validation

```html
<form id="signup">
  <label for="pw">Password</label>
  <input id="pw" type="password">
  <button>Sign up</button>
  <p id="msg"></p>
</form>

<script>
  document.getElementById("signup").addEventListener("submit", function (event) {
    event.preventDefault();
    const pw = document.getElementById("pw").value;
    const msg = document.getElementById("msg");

    if (pw.length === 0) {
      msg.textContent = "Please enter a password.";
    } else if (pw.length < 8) {
      msg.textContent = "Use at least 8 characters.";
    } else if (!/[0-9]/.test(pw)) {
      msg.textContent = "Add at least one number.";
    } else {
      msg.textContent = "Looks good!";
    }
  });
</script>
```

Type different passwords and submit. (`/[0-9]/.test(pw)` checks for a digit using a regular expression; you don't need to understand it yet.)

## Guard clauses: avoid deep nesting

Nested ifs quickly become hard to read:

```js
function checkout(user, cart) {
  if (user) {
    if (cart.length > 0) {
      if (user.address) {
        return "Order placed!";
      } else {
        return "Add an address.";
      }
    } else {
      return "Cart is empty.";
    }
  } else {
    return "Please log in.";
  }
}
console.log(checkout({ address: "Pune" }, ["book"]));
```

The same logic with **guard clauses** handles the problem cases first and exits early:

```js
function checkout(user, cart) {
  if (!user) return "Please log in.";
  if (cart.length === 0) return "Cart is empty.";
  if (!user.address) return "Add an address.";
  return "Order placed!";
}
console.log(checkout({ address: "Pune" }, []));
```

Flat, readable, and easy to extend. (You'll learn `function` and `return` in lesson 15.)

## Ternary for simple choices

```js
const isMember = true;
const price = isMember ? 199 : 299;
console.log(`Price: ₹${price}`);
```

Use the ternary for a quick choice between two **values**. Use `if` for anything that runs several statements.

## Braces: always use them

JavaScript allows an `if` without braces for a single line:

```js
const x = 5;
if (x > 3) console.log("big");
```

This is fine for very short guard clauses, but adding a second line later without adding braces is a classic bug. When in doubt, use braces.

## Common mistakes

- **`=` instead of `===`** in the condition: `if (x = 5)` is always true.
- **Wrong order of `else if` checks**, so a broad condition catches values meant for a later branch.
- **Checking an empty array with `if (arr)`**, which is always true.
- **Semicolon after the condition**: `if (x > 3); { … }` runs the block every time.

## Exercise

1. Given a number, print "positive", "negative" or "zero".
2. Write a shipping calculator: free over ₹500, ₹40 under ₹500, and ₹0 for members whatever the total.
3. Given an hour from 0–23, print "Good morning", "Good afternoon" or "Good evening".
4. Rewrite a three-level nested `if` from your own code with guard clauses.

## Summary

- `if (condition) { … }` runs code only when the condition is true; `else` handles the rest.
- `else if` chains are checked top to bottom; the first match wins.
- Combine conditions with `&&`, `||` and `!`; use truthy checks carefully.
- Guard clauses keep logic flat and readable.

Next, you'll learn the switch statement for choosing between many fixed values.
