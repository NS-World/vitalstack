---
title: React useEffect and Fetching Data
slug: react-useeffect-and-data-fetching
subject: react
lesson: 7
difficulty: intermediate
excerpt: Synchronise components with the outside world using useEffect, watch the dependency array and cleanup in an animation, fetch data with loading and error states, cancel stale requests, and know when you don't need an effect.
---

Rendering should be pure: compute JSX from props and state, nothing else. But apps must talk to the outside world: fetch data, start timers, subscribe to events, update the page title. That's what **effects** are for.

## useEffect basics

```jsx
import { useEffect, useState } from 'react';

function Clock() {
  const [now, setNow] = useState(new Date());

  useEffect(() => {
    const id = setInterval(() => setNow(new Date()), 1000);   // start something
    return () => clearInterval(id);                            // cleanup: stop it
  }, []);                                                      // [] = run after the first render only

  return <p>{now.toLocaleTimeString('en-IN')}</p>;
}
```

- The effect runs **after** React has updated the screen.
- The returned function is the **cleanup**: it runs before the effect runs again and when the component is removed (unmounted).
- The **dependency array** says when to re-run the effect.

## Dependencies, animated

Pick a dependency array and watch when the effect and its cleanup run as `userId` changes and the component unmounts:

::viz use-effect

| Dependencies | Runs |
|---|---|
| `[userId]` | after the first render and whenever `userId` changes |
| `[]` | once, after the first render |
| none | after **every** render (rarely what you want) |

Include **every** prop, state or function from the component that the effect uses. The ESLint rule `react-hooks/exhaustive-deps` (enabled in Vite's template) warns you when you forget one.

## Fetching data properly

```jsx
// src/CourseDetails.jsx
import { useEffect, useState } from 'react';

export default function CourseDetails({ courseId }) {
  const [course, setCourse] = useState(null);
  const [status, setStatus] = useState('loading');       // 'loading' | 'ok' | 'error'

  useEffect(() => {
    const controller = new AbortController();
    setStatus('loading');
    fetch(`/api/courses/${courseId}`, { signal: controller.signal })
      .then(res => {
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
      })
      .then(data => { setCourse(data); setStatus('ok'); })
      .catch(err => { if (err.name !== 'AbortError') setStatus('error'); });

    return () => controller.abort();                     // cancel if courseId changes or we unmount
  }, [courseId]);

  if (status === 'loading') return <p>Loading…</p>;
  if (status === 'error') return <p role="alert">Could not load the course. Please try again.</p>;
  return (
    <article>
      <h2>{course.title}</h2>
      <p>{course.lessons} lessons · ₹{course.price}</p>
    </article>
  );
}
```

Every fetch needs three states: **loading**, **error** and **success**. Users on slow mobile networks will see all of them.

Why the `AbortController`? If the user quickly clicks course 1 then course 2, the response for course 1 might arrive **after** course 2's and overwrite it (a race condition). Aborting the old request on cleanup prevents stale data.

### With async/await

The effect function itself can't be `async`, so define an async function inside:

```jsx
useEffect(() => {
  let ignore = false;
  async function load() {
    const res = await fetch(`/api/courses?level=${level}`);
    const data = await res.json();
    if (!ignore) setCourses(data);
  }
  load();
  return () => { ignore = true; };                       // ignore late responses
}, [level]);
```

## Calling your Spring Boot API

In development, configure Vite to proxy `/api` to Spring Boot so there are no CORS problems:

```js
// vite.config.js
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [react()],
  server: { proxy: { '/api': 'http://localhost:8080' } },
});
```

For authenticated requests, add the JWT from the Spring Boot security lesson:

```js
fetch('/api/me', { headers: { Authorization: `Bearer ${token}` } });
```

## You might not need an effect

Effects are for **synchronising with external systems**. Common misuses:

| Instead of… | Do this |
|---|---|
| an effect that sets `fullName` from `first` + `last` | compute `const fullName = first + ' ' + last` during render |
| an effect that filters a list into state | filter during render (lesson 6), or `useMemo` if it's slow |
| an effect that reacts to a button click | put the logic in the click handler |
| an effect that resets state when a prop changes | give the component a `key` so React resets it |

::tip In real projects, use a data-fetching library like **TanStack Query** (`useQuery`). It handles caching, loading/error states, retries, refetching on focus and race conditions for you. Learn the effect version first so you understand what it's doing.

## StrictMode runs effects twice in development

In development, `StrictMode` mounts, unmounts and re-mounts components once to check that your cleanup works. If you see two requests in the Network tab, that's why. It doesn't happen in production. A correct cleanup (abort/ignore) makes it harmless.

## Exercise

1. Fetch posts from `https://jsonplaceholder.typicode.com/posts?_limit=5` and show their titles with loading and error states.
2. Add a select to choose a user id (1-10) and fetch that user's posts; cancel stale requests.
3. Update `document.title` to "(3) VitalStack" when there are 3 unread messages.
4. Find an effect in your code that could be a calculation during render instead.

::hint Hint for 3 | `useEffect(() => { document.title = unread > 0 ? \`(${unread}) VitalStack\` : 'VitalStack'; }, [unread]);`

## Interview corner

- **What is useEffect for?** Synchronising a component with external systems (network, timers, subscriptions, DOM APIs) after rendering.
- **What does the dependency array do?** Controls when the effect re-runs: on changes to listed values; `[]` means only after mount.
- **What is the cleanup function?** A function returned from the effect that undoes it before re-running and on unmount.
- **How do you avoid race conditions when fetching?** Abort the previous request or ignore its result in cleanup.

## Summary

- Effects run after render; return a cleanup to undo them.
- List every dependency; `[]` runs once.
- Fetch with loading/error/success states and cancel stale requests.
- Don't use effects for things you can compute during render or do in event handlers.

Next: how React renders and how to make it fast.
