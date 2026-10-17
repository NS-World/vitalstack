---
title: React useRef and Custom Hooks
slug: react-refs-and-custom-hooks
subject: react
lesson: 10
difficulty: intermediate
excerpt: Use useRef to focus inputs and keep values between renders without re-rendering, then extract reusable logic into custom hooks like useLocalStorage, useDebounce and useFetch.
---

Two tools that make React code cleaner: **refs** for things outside React's render cycle, and **custom hooks** for sharing logic between components.

## useRef for DOM elements

```jsx
// src/SearchBox.jsx
import { useRef } from 'react';

export default function SearchBox() {
  const inputRef = useRef(null);
  return (
    <div>
      <input ref={inputRef} placeholder="Search" />
      <button onClick={() => inputRef.current.focus()}>Focus search</button>
    </div>
  );
}
```

React sets `inputRef.current` to the DOM element. Use this for focusing, scrolling (`el.scrollIntoView()`), measuring sizes, playing videos, or integrating non-React libraries (charts, maps).

## useRef for values that shouldn't trigger renders

A ref is a box `{ current: … }` that **survives re-renders** and **doesn't cause one** when changed:

```jsx
// src/Stopwatch.jsx
import { useRef, useState } from 'react';

export default function Stopwatch() {
  const [seconds, setSeconds] = useState(0);
  const intervalRef = useRef(null);                 // the timer id: not shown on screen

  function start() {
    if (intervalRef.current) return;                // already running
    intervalRef.current = setInterval(() => setSeconds(s => s + 1), 1000);
  }
  function stop() {
    clearInterval(intervalRef.current);
    intervalRef.current = null;
  }

  return (
    <div>
      <p>{seconds}s</p>
      <button onClick={start}>Start</button>
      <button onClick={stop}>Stop</button>
    </div>
  );
}
```

| | `useState` | `useRef` |
|---|---|---|
| Changing it re-renders | yes | no |
| Value available | in the next render | immediately (`ref.current`) |
| Use for | anything shown on screen | DOM nodes, timer ids, previous values, instances |

Don't read or write `ref.current` during rendering (except initialisation): refs are for event handlers and effects.

## Custom hooks: reusable logic

A custom hook is a function whose name starts with **`use`** and that calls other hooks. It lets components share **stateful logic** (not state itself: each component gets its own copy).

### useLocalStorage

```jsx
// src/hooks/useLocalStorage.js
import { useEffect, useState } from 'react';

export function useLocalStorage(key, initialValue) {
  const [value, setValue] = useState(() => {          // lazy initialiser: runs once
    try {
      const saved = localStorage.getItem(key);
      return saved !== null ? JSON.parse(saved) : initialValue;
    } catch {
      return initialValue;
    }
  });

  useEffect(() => {
    try { localStorage.setItem(key, JSON.stringify(value)); } catch { /* storage full or blocked */ }
  }, [key, value]);

  return [value, setValue];
}
```

### useDebounce

Wait until the user stops typing before searching:

```jsx
// src/hooks/useDebounce.js
import { useEffect, useState } from 'react';

export function useDebounce(value, delay = 400) {
  const [debounced, setDebounced] = useState(value);
  useEffect(() => {
    const t = setTimeout(() => setDebounced(value), delay);
    return () => clearTimeout(t);                     // typing again cancels the previous timer
  }, [value, delay]);
  return debounced;
}
```

### useFetch

The fetch logic from lesson 7, reusable:

```jsx
// src/hooks/useFetch.js
import { useEffect, useState } from 'react';

export function useFetch(url) {
  const [state, setState] = useState({ data: null, error: null, loading: true });

  useEffect(() => {
    if (!url) return;
    const controller = new AbortController();
    setState(s => ({ ...s, loading: true, error: null }));
    fetch(url, { signal: controller.signal })
      .then(r => { if (!r.ok) throw new Error(`HTTP ${r.status}`); return r.json(); })
      .then(data => setState({ data, error: null, loading: false }))
      .catch(error => { if (error.name !== 'AbortError') setState({ data: null, error, loading: false }); });
    return () => controller.abort();
  }, [url]);

  return state;
}
```

### Using them together

```jsx
// src/CourseSearch.jsx
import { useState } from 'react';
import { useDebounce } from './hooks/useDebounce.js';
import { useFetch } from './hooks/useFetch.js';
import { useLocalStorage } from './hooks/useLocalStorage.js';

export default function CourseSearch() {
  const [query, setQuery] = useLocalStorage('last-search', '');
  const debounced = useDebounce(query, 400);
  const { data, loading, error } = useFetch(debounced ? `/api/courses?q=${encodeURIComponent(debounced)}` : null);

  return (
    <div>
      <input value={query} onChange={e => setQuery(e.target.value)} placeholder="Search courses" />
      {loading && debounced && <p>Searching…</p>}
      {error && <p role="alert">Search failed</p>}
      <ul>{data?.map(c => <li key={c.id}>{c.title}</li>)}</ul>
    </div>
  );
}
```

The component reads like a description of what it does; the "how" lives in hooks you can reuse and test separately.

::tip Popular ready-made hook collections: **usehooks-ts**, **ahooks**, **react-use**. Writing a few yourself first is the best way to understand hooks deeply, and a common interview task ("write useDebounce").

## Rules of hooks (again)

1. Only call hooks at the **top level** (not in loops, conditions or nested functions).
2. Only call hooks from **React components or other custom hooks**.
3. Custom hook names **must** start with `use`, so the linter can check the rules.

## Exercise

1. Write `useToggle(initial)` returning `[on, toggle]`.
2. Write `useOnlineStatus()` using the `online`/`offline` window events.
3. Write `useWindowWidth()` and show "mobile" below 600px.
4. Write `usePrevious(value)` with a ref, and show "price went up/down".

::hint Hint for 2 | `const [online, setOnline] = useState(navigator.onLine); useEffect(() => { const on = () => setOnline(true), off = () => setOnline(false); window.addEventListener('online', on); window.addEventListener('offline', off); return () => { window.removeEventListener('online', on); window.removeEventListener('offline', off); }; }, []);`

## Interview corner

- **What is useRef used for?** Accessing DOM nodes and storing mutable values that persist across renders without causing re-renders.
- **useRef vs useState?** Updating a ref doesn't re-render; updating state does.
- **What is a custom hook?** A function starting with `use` that composes hooks to share stateful logic between components.
- **Do two components using the same custom hook share state?** No: each call has its own state.

## Summary

- `useRef` holds DOM elements and render-independent values.
- Custom hooks extract reusable logic: useLocalStorage, useDebounce, useFetch.
- Follow the rules of hooks; name custom hooks `useSomething`.

Next: pages and navigation with React Router.
