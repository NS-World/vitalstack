---
title: React Introduction
slug: react-introduction
subject: react
lesson: 1
difficulty: beginner
excerpt: What React is, why companies like Meta, Netflix, Swiggy and Flipkart use it, how components and the virtual DOM work, and what you need to know before starting.
---

**React** is a JavaScript library for building user interfaces. It's the most popular front-end technology in job listings, used by Meta (Facebook, Instagram, WhatsApp Web), Netflix, Airbnb, Swiggy, Zomato, Flipkart and thousands of startups.

## The problem React solves

With plain JavaScript, you update the page by hand:

```js
// Plain JS: keep the screen in sync yourself
let count = 0;
const button = document.querySelector('#like');
const label = document.querySelector('#likes');
button.addEventListener('click', () => {
  count++;
  label.textContent = count + ' likes';          // remember to update EVERY place that shows count
  if (count === 10) document.querySelector('#badge').hidden = false;
});
```

In a big app (a feed, a cart, notifications, filters), keeping every part of the screen in sync with the data becomes a tangle of bugs.

React flips it around: you describe **what the UI should look like for the current data**, and React updates the page for you whenever the data changes.

```jsx
function LikeButton() {
  const [count, setCount] = useState(0);
  return (
    <div>
      <button onClick={() => setCount(count + 1)}>👍 {count} likes</button>
      {count >= 10 && <span className="badge">Popular!</span>}
    </div>
  );
}
```

No `querySelector`, no manual updates. Change `count`, and the button text and badge follow automatically.

## Three big ideas

**1. Components.** A UI is built from small, reusable pieces, each a JavaScript function:

```text
<App>
 ├── <Header>  (logo, <SearchBox>, <UserMenu>)
 ├── <CourseList>
 │     ├── <CourseCard>
 │     ├── <CourseCard>
 │     └── <CourseCard>
 └── <Footer>
```

Write `CourseCard` once, use it a hundred times with different data.

**2. Declarative UI.** Components return what the UI should be (JSX). React figures out how to change the real page.

**3. One-way data flow.** Data flows **down** from parents to children through **props**. Children notify parents through callback functions. This makes data changes easy to trace.

## The virtual DOM and rendering

Changing the real browser DOM is slow. When state changes, React:

1. Calls your component functions again to get the new UI description (a tree of plain objects, the "virtual DOM").
2. **Compares** it with the previous one (reconciliation / "diffing").
3. Applies only the **minimal changes** to the real DOM.

You'll see this animated in the rendering lesson.

## React vs Angular vs Vue

| | React | Angular | Vue |
|---|---|---|---|
| Type | library (UI) + ecosystem | full framework | progressive framework |
| Language | JavaScript/TypeScript + JSX | TypeScript + HTML templates | JS/TS + templates |
| Made by | Meta | Google | community (Evan You) |
| Routing, forms, HTTP | choose libraries (React Router…) | built in | official libraries |
| Popular in | startups, product companies | enterprises, banks, large teams | startups, Asia |

All three are good. React has the biggest job market; Angular is common in large Indian IT service and enterprise projects (this site has courses for both).

## What you should know first

- HTML and CSS basics.
- JavaScript: `let/const`, arrow functions, arrays (`map`, `filter`), objects, destructuring, spread (`...`), modules (`import/export`), promises and `async/await`. The JavaScript course on this site covers all of these.

## React in 2026: the ecosystem

- **Vite**: the standard tool to create and run React projects (next lesson).
- **React Router**: pages and navigation.
- **TanStack Query**: server data fetching and caching.
- **Next.js**: a React framework for server rendering and full-stack apps.
- **React Native**: mobile apps with the same ideas.

::tip Learn React itself first: components, props, state, effects. Libraries change every couple of years; these fundamentals have stayed the same since hooks arrived in 2019.

## Exercise

1. Open a website you use daily (Swiggy, YouTube, LinkedIn) and sketch its component tree for one page.
2. Mark which parts would need **state** (data that changes while you use the page).
3. Revise JavaScript `map`, destructuring and arrow functions if they're not comfortable yet.

::hint Hint for 2 | Search text, selected filters, cart items, whether a menu is open, liked posts, notifications count: anything that changes on screen without reloading.

## Interview corner

- **What is React?** A JavaScript library for building component-based, declarative user interfaces.
- **What is the virtual DOM?** An in-memory representation of the UI that React diffs to compute minimal real DOM updates.
- **Library or framework?** A library focused on UI; routing, forms and data fetching come from the ecosystem.
- **What is one-way data flow?** Data passes from parent to child via props; children communicate up via callbacks.

## Summary

- React builds UIs from components that describe the UI for the current data.
- React re-renders and diffs to update the DOM efficiently.
- Props flow down, events flow up.

Next: set up a project with Vite and learn JSX.
