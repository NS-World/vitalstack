---
title: JavaScript Cheat Sheet
slug: javascript-cheat-sheet
parent: cheat-sheets
template: page-cheat-sheet.php
icon: 🟨
order: 20
excerpt: Modern JavaScript on one page - variables, types, strings, arrays, objects, functions, DOM, events, fetch and async/await. Free and printable.
---
## Variables and types

```js
const name = 'Asha';        // can't be reassigned
let count = 0;              // can change
typeof 42;                  // 'number'
typeof null;                // 'object' (quirk)
Number('42'); String(10); Boolean('');   // conversions
const msg = `Hi ${name}, you have ${count} messages`;
```

| Falsy values | |
|---|---|
| `false`, `0`, `''`, `null`, `undefined`, `NaN` | everything else is truthy (even `[]`, `'0'`) |

## Operators

```js
a === b; a !== b               // always use strict equality
x ?? 'default'                 // only null/undefined → default
x || 'default'                 // any falsy → default
user?.address?.city            // optional chaining
age >= 18 ? 'Adult' : 'Minor'  // ternary
count += 1; count++;
```

## Strings

```js
const s = '  Learn JS  ';
s.trim(); s.toUpperCase(); s.includes('JS');
s.slice(2, 7); s.replace('JS', 'React'); s.replaceAll('a', 'A');
'a,b,c'.split(','); ['a', 'b'].join('-');
'5'.padStart(3, '0');           // '005'
```

## Arrays

```js
const nums = [5, 2, 9];
nums.push(4); nums.pop(); nums.unshift(1); nums.shift();
nums.includes(2); nums.indexOf(9);
nums.map(n => n * 2);
nums.filter(n => n > 3);
nums.find(n => n > 3); nums.findIndex(n => n > 3);
nums.some(n => n > 8); nums.every(n => n > 0);
nums.reduce((sum, n) => sum + n, 0);
[...nums].sort((a, b) => a - b);     // numeric sort (copy)
const [first, ...rest] = nums;        // destructuring
const merged = [...a, ...b];
```

## Objects

```js
const user = { name: 'Asha', city: 'Pune' };
user.age = 21; delete user.city;
const { name, age = 18 } = user;      // destructuring with default
const copy = { ...user, city: 'Delhi' };
Object.keys(user); Object.values(user); Object.entries(user);
'name' in user;
JSON.stringify(user); JSON.parse('{"a":1}');
```

## Functions

```js
function add(a, b = 0) { return a + b; }
const square = x => x * x;
const sum = (...nums) => nums.reduce((s, n) => s + n, 0);
const makeUser = (name) => ({ name, created: Date.now() });   // return an object
```

Arrow functions don't have their own `this`.

## Control flow

```js
if (x > 0) { } else if (x < 0) { } else { }
switch (day) { case 'Sat': case 'Sun': weekend = true; break; default: weekend = false; }
for (let i = 0; i < 5; i++) { }
for (const item of items) { }       // values
for (const key in obj) { }          // keys
while (n > 0) { n--; }
```

## DOM

```js
const el = document.querySelector('#title');
const items = document.querySelectorAll('.card');
el.textContent = 'Hello';                   // safe for user text
el.classList.add('active'); el.classList.toggle('open');
el.setAttribute('aria-expanded', 'true');
el.dataset.courseId;                         // data-course-id
const li = document.createElement('li');
li.textContent = 'New'; list.append(li); li.remove();
```

## Events

```js
button.addEventListener('click', (e) => { });
form.addEventListener('submit', (e) => {
  e.preventDefault();
  const data = new FormData(form);
  console.log(data.get('email'));
});
list.addEventListener('click', (e) => {                // delegation
  const btn = e.target.closest('[data-id]');
  if (btn) remove(btn.dataset.id);
});
input.addEventListener('input', (e) => console.log(e.target.value));
```

## Storage

```js
localStorage.setItem('todos', JSON.stringify(todos));
const saved = JSON.parse(localStorage.getItem('todos') ?? '[]');
localStorage.removeItem('todos');
```

## Fetch and async/await

```js
async function loadCourses() {
  try {
    const res = await fetch('/api/courses');
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return await res.json();
  } catch (err) {
    console.error(err);
    return [];
  }
}

await fetch('/api/courses', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ title: 'React' }),
});

const [a, b] = await Promise.all([fetch(u1), fetch(u2)]);   // parallel
setTimeout(() => {}, 1000); setInterval(fn, 1000);
```

## Modules

```js
// math.js
export const PI = 3.14;
export default function area(r) { return PI * r * r; }
// app.js
import area, { PI } from './math.js';
```

```html
<script type="module" src="app.js"></script>
<script src="app.js" defer></script>
```
