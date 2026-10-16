---
title: JavaScript Objects
slug: js-objects
subject: javascript
lesson: 14
difficulty: beginner
excerpt: Group related data with objects: create them, read and update properties with dot and bracket notation, loop over them, copy them, destructure them and add methods.
---

Arrays store ordered lists. **Objects** store related information under names, like a user's name, email and age, or a product's title, price and stock. Almost all data on the web, from API responses to settings, is made of objects.

## Creating an object

```js
const user = {
  name: "Asha Verma",
  age: 21,
  email: "asha@example.com",
  isMember: true,
  skills: ["HTML", "CSS"]
};

console.log(user);
```

An object is written with braces `{ }` and contains **properties**: `key: value` pairs separated by commas. Values can be any type, including arrays and other objects.

## Reading properties

```js
const user = { name: "Asha", age: 21, "favourite colour": "teal" };

console.log(user.name);                 // dot notation
console.log(user["age"]);               // bracket notation
console.log(user["favourite colour"]);  // brackets for keys with spaces

const field = "name";
console.log(user[field]);               // brackets with a variable

console.log(user.phone);                // undefined: no such property
```

- **Dot notation** (`user.name`) is the usual way.
- **Bracket notation** is needed when the key is stored in a variable or contains spaces or hyphens.

## Adding, changing and deleting

```js
const book = { title: "Atomic Habits", pages: 320 };

book.pages = 322;            // change
book.author = "James Clear"; // add a new property
delete book.pages;           // remove

console.log(book);
```

Like arrays, a `const` object can have its properties changed; you just can't replace the whole object.

## Nested objects

```js
const order = {
  id: 1042,
  customer: {
    name: "Rohan",
    address: { city: "Pune", pin: "411001" }
  },
  items: [
    { name: "Notebook", qty: 2 },
    { name: "Pen", qty: 5 }
  ]
};

console.log(order.customer.address.city); // "Pune"
console.log(order.items[1].name);         // "Pen"
console.log(order.items.length);          // 2
```

Read nested paths from left to right: order → customer → address → city.

### Optional chaining

If a part of the path might be missing, `?.` stops safely instead of throwing an error:

```js
const user = { name: "Meera" };

// console.log(user.address.city);  // TypeError: Cannot read properties of undefined
console.log(user.address?.city);     // undefined, no error
console.log(user.address?.city ?? "City not set");
```

## Methods: functions inside objects

```js
const counter = {
  count: 0,
  increment() {
    this.count++;
    return this.count;
  },
  reset() {
    this.count = 0;
  }
};

counter.increment();
counter.increment();
console.log(counter.count); // 2
counter.reset();
console.log(counter.count); // 0
```

A function stored in an object is called a **method**. Inside it, `this` refers to the object the method was called on. You've used built-in methods all along: `console.log`, `"text".toUpperCase()`.

## Shorthand properties

When a variable has the same name as the key, you can write it once:

```js
const name = "Kabir";
const age = 30;

const person = { name, age };   // same as { name: name, age: age }
console.log(person);
```

## Looping over objects

```js
const stock = { pens: 40, notebooks: 12, erasers: 0 };

console.log(Object.keys(stock));    // ["pens", "notebooks", "erasers"]
console.log(Object.values(stock));  // [40, 12, 0]
console.log(Object.entries(stock)); // [["pens", 40], ["notebooks", 12], ["erasers", 0]]

for (const [item, qty] of Object.entries(stock)) {
  console.log(`${item}: ${qty > 0 ? qty : "out of stock"}`);
}
```

`Object.entries` plus `for...of` is the cleanest way to loop over an object.

## Checking whether a property exists

```js
const settings = { theme: "dark", fontSize: 0 };

console.log("theme" in settings);           // true
console.log(Object.hasOwn(settings, "lang")); // false
console.log(settings.fontSize ? "set" : "not set"); // "not set": wrong, 0 is falsy!
console.log("fontSize" in settings ? "set" : "not set"); // "set": correct
```

## Destructuring objects

```js
const product = { title: "Backpack", price: 1899, stock: 7 };

const { title, price } = product;
console.log(title, price);

const { stock: available, colour = "black" } = product; // rename and default value
console.log(available, colour);
```

Destructuring is used everywhere in modern JavaScript, especially for function parameters.

## Copying and merging

Objects, like arrays, are stored **by reference**:

```js
const a = { x: 1 };
const b = a;      // same object, not a copy
b.x = 99;
console.log(a.x); // 99

const original = { theme: "light", size: 16 };
const copy = { ...original };                 // shallow copy
const updated = { ...original, theme: "dark" }; // copy + override
console.log(original, copy, updated);
```

The spread `...` copies the top-level properties. Nested objects inside are still shared; for a full deep copy, use `structuredClone(obj)`.

## Arrays of objects

The most common data shape on the web:

```js
const students = [
  { name: "Asha", score: 92 },
  { name: "Rohan", score: 78 },
  { name: "Meera", score: 85 }
];

const top = students.filter(s => s.score > 80).map(s => s.name);
console.log(top); // ["Asha", "Meera"]
```

## Common mistakes

- **Missing commas** between properties.
- **Dot notation with a variable**: `user.field` looks for a property literally called "field". Use `user[field]`.
- **Reading deep paths that might not exist**: use `?.`.
- **Thinking `=` copies an object**: use `{ ...obj }` or `structuredClone`.

## Exercise

1. Create an object for a film with title, year, rating and an array of actors. Print a sentence about it.
2. Add a `director` property, change the rating, and delete the year.
3. Loop over the object with `Object.entries` and print each key and value.
4. Create a `bankAccount` object with `balance`, `deposit(amount)` and `withdraw(amount)` methods. Don't allow withdrawing more than the balance.
5. Use destructuring to pull out the title and rating.

## Summary

- Objects store `key: value` properties; read them with `obj.key` or `obj["key"]`.
- Use `?.` for paths that may not exist and `??` for defaults.
- `Object.keys`, `values` and `entries` let you loop over objects.
- Destructuring and spread make reading, copying and merging objects easy.

Next, you'll learn functions, the reusable building blocks of every program.
