---
title: JavaScript Arrays
slug: js-arrays
subject: javascript
lesson: 12
difficulty: beginner
excerpt: Store ordered lists in arrays: create them, read and change items by index, add and remove with push, pop, shift, unshift and splice, and copy with spread.
---

An **array** is an ordered list of values: a shopping list, the lessons in a course, the scores in a game. Arrays are one of the two data structures you'll use constantly in JavaScript (the other is objects).

## Creating arrays

```js
const languages = ["HTML", "CSS", "JavaScript"];
const scores = [92, 87, 95, 78];
const mixed = ["Asha", 21, true, null];
const empty = [];

console.log(languages);
console.log(scores.length); // 4
```

Square brackets, values separated by commas. An array can hold any mix of types, though in practice each array usually holds one kind of thing.

## Reading items by index

```js
const colours = ["red", "green", "blue"];

console.log(colours[0]);                  // "red": the first item
console.log(colours[2]);                  // "blue"
console.log(colours[colours.length - 1]); // "blue": the last item
console.log(colours.at(-1));              // "blue": at() supports negative indexes
console.log(colours[10]);                 // undefined: there's no item 10
```

The first item is at index **0**, the last at `length - 1`.

## Changing items

```js
const week = ["Mon", "Tue", "Wed"];
week[1] = "Tuesday";
console.log(week); // ["Mon", "Tuesday", "Wed"]
```

Even with `const`, you can change what's **inside** an array; you just can't replace the whole array with a new one.

## Adding and removing at the ends

```js
const queue = ["Asha", "Rohan"];

queue.push("Meera");       // add to the end
console.log(queue);        // ["Asha", "Rohan", "Meera"]

queue.unshift("Kabir");    // add to the start
console.log(queue);        // ["Kabir", "Asha", "Rohan", "Meera"]

const last = queue.pop();  // remove from the end
console.log(last, queue);  // "Meera" ["Kabir", "Asha", "Rohan"]

const first = queue.shift(); // remove from the start
console.log(first, queue);   // "Kabir" ["Asha", "Rohan"]
```

| Method | Does | Returns |
|---|---|---|
| `push(x)` | Adds to the end | New length |
| `pop()` | Removes the last item | The removed item |
| `unshift(x)` | Adds to the start | New length |
| `shift()` | Removes the first item | The removed item |

## Adding and removing in the middle: splice

```js
const tasks = ["wake up", "exercise", "breakfast", "study"];

tasks.splice(1, 1);                    // at index 1, remove 1 item
console.log(tasks);                    // ["wake up", "breakfast", "study"]

tasks.splice(2, 0, "shower");          // at index 2, remove 0, insert "shower"
console.log(tasks);                    // ["wake up", "breakfast", "shower", "study"]

tasks.splice(0, 1, "wake up early");   // replace the first item
console.log(tasks);
```

`splice(start, deleteCount, ...itemsToInsert)` changes the array in place.

## Finding items

```js
const tags = ["html", "css", "js", "css"];

console.log(tags.includes("js"));     // true
console.log(tags.indexOf("css"));     // 1: first position
console.log(tags.lastIndexOf("css")); // 3
console.log(tags.indexOf("python"));  // -1: not found
```

## Slicing and joining arrays

```js
const letters = ["a", "b", "c", "d", "e"];

console.log(letters.slice(1, 3)); // ["b", "c"]: copy from 1 up to (not including) 3
console.log(letters.slice(-2));   // ["d", "e"]
console.log(letters);             // unchanged

const more = letters.concat(["f", "g"]);
console.log(more);

console.log(letters.join("-"));   // "a-b-c-d-e"
```

`slice` makes a copy and doesn't change the original; `splice` changes the original. Their names are confusingly similar, so double-check which one you mean.

## The spread operator

`...` "spreads" an array's items out. It's the modern way to copy and combine arrays:

```js
const frontend = ["HTML", "CSS", "JavaScript"];
const backend = ["Python", "SQL"];

const fullStack = [...frontend, ...backend];
console.log(fullStack);

const copy = [...frontend];
copy.push("React");
console.log(frontend); // unchanged
console.log(copy);
```

### Why copying matters

Arrays are stored **by reference**: assigning an array to another variable doesn't copy it.

```js
const original = [1, 2, 3];
const sameArray = original;  // not a copy!
sameArray.push(4);
console.log(original);       // [1, 2, 3, 4]: changed too
```

Both names point to the same array. Use `[...original]` when you need an independent copy.

## Destructuring

Pull values out of an array into variables:

```js
const point = [10, 25];
const [x, y] = point;
console.log(x, y);

const [winner, runnerUp, ...others] = ["Asha", "Rohan", "Meera", "Kabir"];
console.log(winner, runnerUp, others);
```

## Arrays of arrays

```js
const board = [
  ["X", "O", "X"],
  ["O", "X", "O"],
  ["O", "X", "X"]
];
console.log(board[1][2]); // "O": row 1, column 2
```

## Sorting

```js
const names = ["Rohan", "asha", "Meera"];
names.sort();
console.log(names); // ["Meera", "Rohan", "asha"]: uppercase sorts first!

const nums = [10, 1, 5, 100];
nums.sort();
console.log(nums);  // [1, 10, 100, 5]: sorted as text!

nums.sort((a, b) => a - b);
console.log(nums);  // [1, 5, 10, 100]: numeric sort
```

By default, `sort` compares items **as strings**. For numbers, always pass a compare function `(a, b) => a - b` (you'll understand the arrow syntax in lesson 16). `sort` and `reverse` change the original array.

## Common mistakes

- **Off-by-one**: the last item is `arr[arr.length - 1]`, not `arr[arr.length]`.
- **Sorting numbers without a compare function.**
- **Thinking `=` copies an array**: use spread.
- **Mixing up `slice` (copy) and `splice` (change).**

## Exercise

1. Create an array of five favourite films. Print the first, the last and the total count.
2. Add one film to the start and one to the end; remove the third one with `splice`.
3. Combine two arrays of friends with the spread operator.
4. Sort `[45, 3, 120, 7, 64]` from smallest to largest.
5. Use destructuring to get the first two items of an array and the rest separately.

## Summary

- Arrays are ordered lists; indexes start at 0; `length` gives the count.
- `push`, `pop`, `shift`, `unshift` and `splice` add and remove items.
- `includes`, `indexOf`, `slice`, `concat` and `join` search and copy.
- Use spread `[...arr]` to copy or combine; sort numbers with `(a, b) => a - b`.

Next, you'll learn powerful array methods like map, filter and reduce.
