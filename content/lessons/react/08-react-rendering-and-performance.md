---
title: React Rendering and Performance - memo, useMemo, useCallback
slug: react-rendering-and-performance
subject: react
lesson: 8
difficulty: advanced
excerpt: See exactly which components re-render when state changes, use React.memo to skip unchanged children, useMemo for expensive calculations, useCallback for stable functions, and measure with the Profiler.
---

React is fast by default, but understanding **when** components re-render helps you avoid slow screens and fix them when they happen. This is a favourite interview topic.

## What triggers a re-render?

A component re-renders when:

1. Its **state** changes.
2. Its **parent** re-renders (by default, all children re-render too, even if their props didn't change).
3. A **context** it uses changes (next lesson).

Re-rendering means calling the component function again and diffing the result. It does **not** mean the DOM is rebuilt: React only touches what actually changed.

## Watch it

Click Like and compare a plain `CourseList` with one wrapped in `React.memo`:

::viz react-render

## React.memo: skip a child when props are the same

```jsx
// src/Dashboard.jsx
import { memo, useCallback, useMemo, useState } from 'react';

export const renders = { list: 0 };                   // for the demo: count renders

const CourseList = memo(function CourseList({ courses, onSelect }) {
  renders.list++;
  return (
    <ul>
      {courses.map(c => <li key={c.id} onClick={() => onSelect(c.id)}>{c.title}</li>)}
    </ul>
  );
});

const ALL = [
  { id: 1, title: 'Java', price: 499 },
  { id: 2, title: 'React', price: 599 },
  { id: 3, title: 'SQL', price: 299 },
];

export default function Dashboard() {
  const [likes, setLikes] = useState(0);
  const [maxPrice, setMaxPrice] = useState(1000);
  const [selected, setSelected] = useState(null);

  const affordable = useMemo(                         // same array object until maxPrice changes
    () => ALL.filter(c => c.price <= maxPrice),
    [maxPrice]
  );

  const handleSelect = useCallback(id => setSelected(id), []);   // same function every render

  return (
    <div>
      <button onClick={() => setLikes(l => l + 1)}>👍 {likes}</button>
      <button onClick={() => setMaxPrice(500)}>Under ₹500</button>
      <p>Selected: {selected ?? 'none'}</p>
      <CourseList courses={affordable} onSelect={handleSelect} />
    </div>
  );
}
```

Clicking 👍 re-renders `Dashboard`, but `CourseList` is **skipped**: `memo` compares its props, and both are the same objects as last time. Clicking "Under ₹500" changes `affordable`, so the list re-renders.

## Why useMemo and useCallback are needed here

Without them, `memo` wouldn't help:

```jsx
const affordable = ALL.filter(c => c.price <= maxPrice);   // a NEW array every render
const handleSelect = id => setSelected(id);                // a NEW function every render
```

`memo` compares props with `Object.is`: a new array or function is "different" even with the same contents, so the child re-renders anyway.

| Hook | Remembers | Use for |
|---|---|---|
| `useMemo(() => value, deps)` | a computed **value** | expensive calculations; keeping objects/arrays stable for memoised children |
| `useCallback(fn, deps)` | a **function** | keeping callbacks stable for memoised children or effect dependencies |
| `memo(Component)` | the last render of a component | skipping re-renders when props are unchanged |

## Don't optimise everything

Memoisation has a cost: extra memory and comparisons on every render, and more complex code. Most components render in well under a millisecond. Optimise when:

- You **measured** a slow interaction (React DevTools **Profiler** shows which components rendered and how long they took).
- A component renders a **big list** or a heavy chart and its parent changes often.
- A calculation is genuinely **expensive** (sorting/filtering thousands of items).

::tip Often the best optimisation is structural: **move state down** into the component that needs it (so fewer components re-render), or pass expensive parts as `children` so they aren't re-created by the state owner. And for very long lists, use virtualisation (`react-window`, `@tanstack/react-virtual`) to render only visible rows.

## Keys reset state

Changing a component's `key` makes React treat it as a brand-new component: state is reset.

```jsx
<CommentBox key={postId} postId={postId} />    // typing a comment, then switching posts, clears the box
```

## Other performance tools

- **Code splitting**: `const Admin = lazy(() => import('./Admin.jsx'))` with `<Suspense>` loads heavy pages only when visited.
- **useTransition / useDeferredValue**: keep typing responsive while a heavy list updates in the background.
- **Production builds**: always measure with `npm run build && npm run preview`; development mode is much slower.
- **React Compiler** (React 19 era): automatically memoises components and values at build time, reducing the need for manual `memo`/`useMemo`/`useCallback`.

## Exercise

1. Add `console.log('render', name)` to components in an app and click around. Which ones re-render?
2. Wrap a list component in `memo` and confirm it stops re-rendering when an unrelated parent state changes.
3. Break the memo by passing an inline arrow `onSelect={id => setSelected(id)}`. Then fix it with `useCallback`.
4. Profile a page with React DevTools and find the slowest component.

::hint Hint for 3 | An inline arrow is a new function on each render, so memo's shallow comparison sees a changed prop. `useCallback(id => setSelected(id), [])` returns the same function every time.

## Interview corner

- **When does a component re-render?** When its state changes, its parent re-renders, or a context it consumes changes.
- **What does React.memo do?** Skips re-rendering if props are shallowly equal to the previous props.
- **useMemo vs useCallback?** useMemo memoises a computed value; useCallback memoises a function (`useCallback(fn, deps)` ≈ `useMemo(() => fn, deps)`).
- **Is re-rendering the same as updating the DOM?** No: React diffs and only commits actual changes to the DOM.

## Summary

- Re-renders come from state, parent renders and context.
- `memo` skips unchanged children; `useMemo`/`useCallback` keep props stable so memo works.
- Measure first with the Profiler; restructure state before adding memoisation.

Next: sharing state with Context and useReducer.
