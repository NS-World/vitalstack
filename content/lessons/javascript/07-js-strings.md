---
title: JavaScript Strings
slug: js-strings
subject: javascript
lesson: 7
difficulty: beginner
excerpt: Create and combine strings, read characters, and use the essential string methods to search, slice, change case, trim, split and replace text.
---

Strings hold text: names, messages, URLs, whole articles. Because so much of web development is about text, JavaScript gives strings many built-in **methods**, ready-made functions that work on them.

## Creating strings

```js
const a = "double quotes";
const b = 'single quotes';
const c = `backticks: ${a.length} characters in a`;
console.log(a, b, c);
```

If your text contains one kind of quote, wrap it in the other, or **escape** it with a backslash:

```js
const s1 = "It's easy";
const s2 = 'She said "hi"';
const s3 = 'It\'s also fine';
const s4 = "Line one\nLine two"; // \n is a new line
console.log(s1, s2, s3);
console.log(s4);
```

Template literals can span several lines directly:

```js
const card = `Name: Asha
Course: JavaScript
Lesson: 7`;
console.log(card);
```

## Length and characters

```js
const word = "VitalStack";
console.log(word.length);        // 10
console.log(word[0]);            // "V": the first character is at index 0
console.log(word[word.length - 1]); // "k": the last character
console.log(word.at(-1));        // "k": at() accepts negative positions
```

Positions (indexes) start at **0**, not 1. This is true for strings and arrays throughout JavaScript.

Strings are **immutable**: you can't change one character in place. Methods always return a **new** string.

## Changing case and trimming

```js
const email = "  Asha@Example.COM  ";
console.log(email.trim());               // "Asha@Example.COM"
console.log(email.trim().toLowerCase()); // "asha@example.com"
console.log("hello".toUpperCase());      // "HELLO"
```

`trim()` removes spaces from both ends, essential for cleaning form input. `trimStart()` and `trimEnd()` remove from one side.

Notice you can **chain** methods: each returns a new string, and the next method works on that.

## Searching

```js
const sentence = "Learn JavaScript at VitalStack";

console.log(sentence.includes("Java"));    // true
console.log(sentence.startsWith("Learn")); // true
console.log(sentence.endsWith("Stack"));   // true
console.log(sentence.indexOf("at"));       // 17: position of the first match
console.log(sentence.indexOf("Python"));   // -1: not found
```

Searches are **case-sensitive**. To search without caring about case, lower-case both sides:

```js
const search = "javascript";
console.log(sentence.toLowerCase().includes(search.toLowerCase())); // true
```

## Extracting parts with slice

```js
const text = "JavaScript";
console.log(text.slice(0, 4));  // "Java": from index 0 up to (not including) 4
console.log(text.slice(4));     // "Script": from 4 to the end
console.log(text.slice(-6));    // "Script": the last 6 characters
```

`slice(start, end)` is the main method for cutting strings. (`substring` is similar; you'll see it in older code.)

## Replacing

```js
const msg = "I like cats. cats are great.";
console.log(msg.replace("cats", "dogs"));    // replaces the first match only
console.log(msg.replaceAll("cats", "dogs")); // replaces every match
```

## Splitting and joining

```js
const csv = "HTML,CSS,JavaScript";
const parts = csv.split(",");
console.log(parts);              // ["HTML", "CSS", "JavaScript"]
console.log(parts.join(" | "));  // "HTML | CSS | JavaScript"

const letters = "hello".split("");
console.log(letters);            // ["h", "e", "l", "l", "o"]
console.log(letters.reverse().join("")); // "olleh"
```

`split` turns a string into an array; `join` turns an array back into a string. Together they solve many text problems.

## Padding and repeating

```js
console.log("7".padStart(3, "0"));  // "007"
console.log("5".padStart(2, "0") + ":" + "3".padStart(2, "0")); // "05:03"
console.log("=".repeat(20));
```

`padStart` is perfect for formatting times, dates and invoice numbers.

## A practical example: making a URL slug

```js
function slugify(title) {
  return title
    .trim()
    .toLowerCase()
    .replaceAll(" ", "-");
}

console.log(slugify("  JavaScript Strings Explained ")); // "javascript-strings-explained"
```

(You'll learn functions properly in lesson 15. For now, notice how chaining makes each step clear.)

## Comparing strings

```js
console.log("abc" === "abc");     // true
console.log("abc" === "ABC");     // false
console.log("ä".localeCompare("z", "de")); // -1: sorts correctly for languages
```

## Common mistakes

- **Forgetting indexes start at 0**: the first character is `str[0]`.
- **Expecting methods to change the original**: `name.toUpperCase()` returns a new string; save it: `name = name.toUpperCase()`.
- **Case-sensitive searches** that miss matches.
- **Using `replace` when you meant `replaceAll`.**

## Exercise

1. Take `"  hello WORLD  "` and turn it into `"Hello world"` (trim, lower-case, then capitalise the first letter with `slice`).
2. Check if an email address contains `@` and ends with `.com`.
3. Count the words in a sentence with `split(" ")` and `.length`.
4. Reverse any word and check whether it's a palindrome (like "level").
5. Format the time 9:5 as `"09:05"` with `padStart`.

## Summary

- Create strings with quotes or backticks; escape quotes with `\`.
- `length` and `[index]` read strings; indexes start at 0; strings never change in place.
- Key methods: `trim`, `toLowerCase`, `includes`, `indexOf`, `slice`, `replaceAll`, `split`, `padStart`.
- Chain methods to do several steps in one readable expression.

Next, you'll work with numbers and the Math object.
