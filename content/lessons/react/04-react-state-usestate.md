---
title: React State with useState
slug: react-state-usestate
subject: react
lesson: 4
difficulty: beginner
excerpt: Give components memory with useState, see why setCount(count + 1) twice adds only 1 in an animation, use updater functions, and update objects and arrays immutably.
---

Props come from outside. **State** is a component's own memory: data that changes while the user interacts (a counter, an input's text, whether a menu is open, items in a cart). When state changes, React **re-renders** the component with the new value.

## useState

```jsx
// src/Counter.jsx
import { useState } from 'react';

export default function Counter() {
  const [count, setCount] = useState(0);      // [current value, setter], initial value 0

  return (
    <div>
      <p>Count: {count}</p>
      <button onClick={() => setCount(count + 1)}>+1</button>
      <button onClick={() => setCount(0)}>Reset</button>
    </div>
  );
}
```

- `useState(0)` returns the current value and a function to change it.
- Calling `setCount(...)` **schedules** a re-render; on the next render, `count` has the new value.
- Each `<Counter />` on the page has its own independent count.

Why not a normal variable? `let count = 0; count++` changes the variable, but React doesn't know it should re-render, and the next render starts again at 0.

## The surprise: state is a snapshot

Inside one render, `count` is a **constant**. Setting it doesn't change it immediately. Watch what happens when you call the setter twice:

::viz react-state

```jsx
// src/AddTwo.jsx
import { useState } from 'react';

export default function AddTwo() {
  const [count, setCount] = useState(0);
  const [score, setScore] = useState(0);

  function addTwoBroken() {
    setCount(count + 1);       // count is 0 in this render → "set to 1"
    setCount(count + 1);       // still 0 → "set to 1" again
  }

  function addTwoCorrect() {
    setScore(s => s + 1);      // updater function: gets the latest value
    setScore(s => s + 1);
  }

  return (
    <div>
      <button onClick={addTwoBroken}>count: {count}</button>
      <button onClick={addTwoCorrect}>score: {score}</button>
    </div>
  );
}
```

After one click each: `count: 1`, `score: 2`.

**Rule:** when the new state depends on the old state, pass a function: `setX(prev => prev + 1)`.

React also **batches** updates: several `setX` calls in one event handler cause just **one** re-render.

## State with objects: copy, don't mutate

```jsx
// src/ProfileForm.jsx
import { useState } from 'react';

export default function ProfileForm() {
  const [user, setUser] = useState({ name: 'Asha', city: 'Pune', xp: 120 });

  function moveTo(city) {
    // ❌ user.city = city;  setUser(user);   → same object, React may skip the re-render
    setUser({ ...user, city });              // ✅ a NEW object with the change
  }

  return (
    <div>
      <p>{user.name} lives in {user.city} ({user.xp} XP)</p>
      <button onClick={() => moveTo('Bengaluru')}>Move to Bengaluru</button>
      <button onClick={() => setUser(u => ({ ...u, xp: u.xp + 10 }))}>+10 XP</button>
    </div>
  );
}
```

React decides whether to re-render by checking if the state value is a **different object** (`Object.is`). Mutating the same object keeps the same reference, so the UI can go stale. Always create new objects and arrays.

## State with arrays

```jsx
// src/TodoList.jsx
import { useState } from 'react';

export default function TodoList() {
  const [todos, setTodos] = useState([{ id: 1, text: 'Learn JSX', done: true }]);
  const [text, setText] = useState('');

  function add() {
    if (!text.trim()) return;
    setTodos(prev => [...prev, { id: Date.now(), text: text.trim(), done: false }]);   // add
    setText('');
  }
  const toggle = id => setTodos(prev => prev.map(t => (t.id === id ? { ...t, done: !t.done } : t)));   // update
  const remove = id => setTodos(prev => prev.filter(t => t.id !== id));                              // remove

  return (
    <div>
      <input value={text} onChange={e => setText(e.target.value)} placeholder="New task" />
      <button onClick={add}>Add</button>
      <ul>
        {todos.map(t => (
          <li key={t.id}>
            <label>
              <input type="checkbox" checked={t.done} onChange={() => toggle(t.id)} />
              {t.done ? <s>{t.text}</s> : t.text}
            </label>
            <button onClick={() => remove(t.id)} aria-label={`Remove ${t.text}`}>×</button>
          </li>
        ))}
      </ul>
      <p>{todos.filter(t => !t.done).length} left</p>
    </div>
  );
}
```

| Want to | Don't (mutates) | Do (new array) |
|---|---|---|
| add | `push` | `[...arr, item]` |
| remove | `splice` | `arr.filter(x => x.id !== id)` |
| update | `arr[i].done = true` | `arr.map(x => x.id === id ? { ...x, done: true } : x)` |
| sort | `arr.sort()` | `[...arr].sort()` or `arr.toSorted()` |

## Where should state live?

- Keep state in the **lowest common parent** of the components that need it ("lifting state up").
- Don't store what you can **calculate**: if you have `todos`, compute `remaining` during render instead of storing it in separate state.

::tip Hooks rules: call `useState` (and every hook) at the **top level** of your component, never inside conditions, loops or nested functions. React identifies each piece of state by the order of hook calls.

## Exercise

1. Build a like button that toggles between 🤍 and ❤️ and counts likes.
2. Make a "step counter" with +1, +5 and reset buttons, using updater functions.
3. Build a shopping cart with add, increase/decrease quantity and remove, showing the total price.
4. What does this show after one click? `setN(n + 5); setN(m => m * 2);` with `n = 1`.

::hint Hint for 4 | The first call queues "set to 6", the second queues a function that receives 6 and returns 12. Result: 12.

## Interview corner

- **What is state?** Data owned by a component that changes over time and triggers re-renders when updated.
- **Why use an updater function?** State is a snapshot per render; updater functions receive the latest pending value, so consecutive updates don't get lost.
- **Why must state be immutable?** React compares references to detect changes; mutation keeps the same reference.
- **What is batching?** Grouping multiple state updates from one event into a single re-render.

## Summary

- `const [x, setX] = useState(initial)`; setting state schedules a re-render.
- State is a snapshot: use `setX(prev => …)` when the new value depends on the old.
- Never mutate objects/arrays in state; create new ones with spread, `map`, `filter`.

Next: events and forms.
