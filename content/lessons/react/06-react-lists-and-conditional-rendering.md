---
title: React Lists, Keys and Conditional Rendering
slug: react-lists-and-conditional-rendering
subject: react
lesson: 6
difficulty: beginner
excerpt: Render arrays with map, understand why keys matter (and why the index is a bad key), filter and sort lists with derived values, and show content conditionally with &&, ternaries and early returns.
---

Most screens are lists: courses, messages, products, search results. And most screens show different things depending on state: loading or loaded, logged in or not, empty or full.

## Rendering a list with map

```jsx
// src/CourseList.jsx
export default function CourseList({ courses }) {
  if (courses.length === 0) return <p>No courses match your filters.</p>;   // early return

  return (
    <ul>
      {courses.map(course => (
        <li key={course.id}>
          {course.title} {course.free ? <strong>(Free)</strong> : <span>₹{course.price}</span>}
        </li>
      ))}
    </ul>
  );
}
```

`map` turns each data item into a JSX element. React renders an array of elements in order.

## Keys

Each item in a list needs a `key` that is **unique among siblings** and **stable** (the same item always gets the same key). React uses keys to match items between renders: which ones were added, removed or moved.

- ✅ Use ids from your data: `key={course.id}`.
- ⚠️ The array **index** (`key={i}`) only works for lists that never reorder, filter or insert. Otherwise React mixes up items: an input's typed text or a checkbox can "jump" to the wrong row.
- ❌ Random keys (`Math.random()`) make React recreate every item on every render: slow, and inputs lose focus.

## Filtering and sorting: derive, don't store

```jsx
// src/Catalog.jsx
import { useState } from 'react';
import CourseList from './CourseList.jsx';

const COURSES = [
  { id: 1, title: 'Java Basics', level: 'beginner', price: 499, free: false },
  { id: 2, title: 'HTML', level: 'beginner', price: 0, free: true },
  { id: 3, title: 'Spring Boot', level: 'intermediate', price: 799, free: false },
  { id: 4, title: 'React', level: 'beginner', price: 599, free: false },
  { id: 5, title: 'Hibernate', level: 'advanced', price: 999, free: false },
];

export default function Catalog() {
  const [query, setQuery] = useState('');
  const [level, setLevel] = useState('all');
  const [sort, setSort] = useState('title');

  // derived during render: no extra state, always consistent
  const visible = COURSES
    .filter(c => level === 'all' || c.level === level)
    .filter(c => c.title.toLowerCase().includes(query.toLowerCase()))
    .toSorted((a, b) => (sort === 'price' ? a.price - b.price : a.title.localeCompare(b.title)));

  return (
    <div>
      <input placeholder="Search courses" value={query} onChange={e => setQuery(e.target.value)} />
      <select aria-label="Level" value={level} onChange={e => setLevel(e.target.value)}>
        <option value="all">All levels</option>
        <option value="beginner">Beginner</option>
        <option value="intermediate">Intermediate</option>
        <option value="advanced">Advanced</option>
      </select>
      <select aria-label="Sort" value={sort} onChange={e => setSort(e.target.value)}>
        <option value="title">A to Z</option>
        <option value="price">Price: low to high</option>
      </select>
      <p>{visible.length} of {COURSES.length} courses</p>
      <CourseList courses={visible} />
    </div>
  );
}
```

The filtered list isn't state: it's **computed** from state (`query`, `level`, `sort`) on every render. Storing it separately would mean keeping two things in sync, which is how bugs start. (`toSorted` returns a sorted copy; `sort` would mutate the constant array.)

## Conditional rendering patterns

```jsx
// 1. if / early return: for whole-component alternatives
if (loading) return <Spinner />;

// 2. ternary: one thing OR another
{isLoggedIn ? <UserMenu /> : <LoginButton />}

// 3. &&: something OR nothing
{unread > 0 && <span className="badge">{unread}</span>}

// 4. a variable prepared before return, for complex cases
let banner = null;
if (plan === 'free') banner = <UpgradeBanner />;
else if (trialEndsSoon) banner = <TrialBanner />;
```

::tip The `&&` trap: `{count && <Badge />}` renders **0** when count is 0, because `0` is a value React displays. Use `{count > 0 && <Badge />}`.

## Grouping lists

```jsx
const byLevel = Object.groupBy(COURSES, c => c.level);    // { beginner: [...], intermediate: [...], ... }

{Object.entries(byLevel).map(([lvl, list]) => (
  <section key={lvl}>
    <h3>{lvl}</h3>
    <CourseList courses={list} />
  </section>
))}
```

## Common mistakes

- Missing keys (React warns in the console) or index keys on reorderable lists.
- Putting the key on the wrong element: it belongs on the outermost element returned by `map`.
- Sorting state arrays in place (`.sort()` mutates).
- Storing filtered/derived data in its own state.

## Exercise

1. Show a "No results" message with a "Clear filters" button when the search finds nothing.
2. Add a "Free only" checkbox filter.
3. Group the catalog by level with a heading per group.
4. Show a "Bestseller" badge only for courses with more than 1,000 students (add a `students` field).

::hint Hint for 1 | The button calls `setQuery('')` and `setLevel('all')`. Show it with `{visible.length === 0 && …}`.

## Interview corner

- **Why does React need keys?** To identify items across renders so it can preserve state and update the DOM efficiently.
- **Why not use the index as a key?** When items are inserted, removed or reordered, indexes shift, so React associates state with the wrong items.
- **What is derived state?** Values computed from existing props/state during render instead of being stored separately.
- **Ways to render conditionally?** if/early return, ternary, `&&`, or JSX stored in variables.

## Summary

- Render arrays with `map` and give each item a stable, unique `key` (an id).
- Compute filtered/sorted lists during render from state.
- Use early returns, ternaries and `&&` (with a boolean!) for conditional UI.

Next: useEffect and fetching data from an API.
