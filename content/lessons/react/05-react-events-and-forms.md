---
title: React Events and Forms
slug: react-events-and-forms
subject: react
lesson: 5
difficulty: beginner
excerpt: Handle clicks, typing and submits in React, build controlled inputs (text, select, checkbox, radio), validate a sign-up form, prevent default submission and keep one state object for many fields.
---

Users interact through events: clicking, typing, submitting. React handles events with props like `onClick` and `onChange`, and forms with **controlled inputs**, where React state is the single source of truth.

## Handling events

```jsx
function Demo() {
  function handleClick(event) {
    console.log('clicked', event.target);
  }
  return (
    <>
      <button onClick={handleClick}>Click me</button>                    {/* pass the function */}
      <button onClick={() => alert('Hi!')}>Say hi</button>              {/* inline arrow */}
      <input onKeyDown={e => e.key === 'Enter' && console.log('search')} />
    </>
  );
}
```

- Pass a **function**, don't call it: `onClick={handleClick}` ✅, `onClick={handleClick()}` ❌ (runs during render).
- To pass arguments, wrap it: `onClick={() => remove(id)}`.
- Events are camelCase: `onClick`, `onChange`, `onSubmit`, `onMouseEnter`, `onFocus`, `onBlur`, `onKeyDown`.
- `event.preventDefault()` and `event.stopPropagation()` work as in the DOM.

## Controlled inputs

A controlled input's value comes from state, and every keystroke updates the state:

```jsx
const [name, setName] = useState('');
<input value={name} onChange={e => setName(e.target.value)} />
```

Now `name` is always in sync with what's on screen, so you can validate, transform (`e.target.value.toUpperCase()`) or reset (`setName('')`) easily.

## A complete sign-up form

```jsx
// src/SignupForm.jsx
import { useState } from 'react';

const empty = { name: '', email: '', course: 'java', level: 'beginner', terms: false };

function validate(f) {
  const errors = {};
  if (f.name.trim().length < 2) errors.name = 'Enter your name';
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.email)) errors.email = 'Enter a valid email';
  if (!f.terms) errors.terms = 'Please accept the terms';
  return errors;
}

export default function SignupForm({ onSignup }) {
  const [form, setForm] = useState(empty);
  const [errors, setErrors] = useState({});
  const [submitted, setSubmitted] = useState(false);

  function handleChange(e) {
    const { name, value, type, checked } = e.target;
    setForm(f => ({ ...f, [name]: type === 'checkbox' ? checked : value }));   // one handler for all fields
  }

  function handleSubmit(e) {
    e.preventDefault();                           // stop the browser reloading the page
    const found = validate(form);
    setErrors(found);
    if (Object.keys(found).length > 0) return;
    onSignup?.(form);
    setSubmitted(true);
    setForm(empty);
  }

  if (submitted) return <p role="status">Welcome aboard! Check your email.</p>;

  return (
    <form onSubmit={handleSubmit} noValidate>
      <label>
        Name
        <input name="name" value={form.name} onChange={handleChange} />
      </label>
      {errors.name && <p className="error">{errors.name}</p>}

      <label>
        Email
        <input name="email" type="email" value={form.email} onChange={handleChange} />
      </label>
      {errors.email && <p className="error">{errors.email}</p>}

      <label>
        Course
        <select name="course" value={form.course} onChange={handleChange}>
          <option value="java">Java</option>
          <option value="react">React</option>
          <option value="sql">SQL</option>
        </select>
      </label>

      <fieldset>
        <legend>Level</legend>
        {['beginner', 'intermediate'].map(l => (
          <label key={l}>
            <input type="radio" name="level" value={l} checked={form.level === l} onChange={handleChange} /> {l}
          </label>
        ))}
      </fieldset>

      <label>
        <input type="checkbox" name="terms" checked={form.terms} onChange={handleChange} /> I accept the terms
      </label>
      {errors.terms && <p className="error">{errors.terms}</p>}

      <button type="submit">Create free account</button>
    </form>
  );
}
```

Key techniques:

- **One state object** for the whole form, and one `handleChange` using the input's `name` with a computed key `[name]`.
- Checkboxes use `checked`, not `value`.
- `e.preventDefault()` in `onSubmit` stops the full-page reload.
- Pressing Enter in a field submits the form automatically (because of `<form onSubmit>` + a submit button). That's better than `onClick` on the button.
- Errors are shown next to the fields that caused them.

## Uncontrolled inputs (and when to use them)

You can also read values directly from the DOM with `FormData` when you don't need live validation:

```jsx
function QuickSearch({ onSearch }) {
  function handleSubmit(e) {
    e.preventDefault();
    const data = new FormData(e.currentTarget);
    onSearch(data.get('q'));
  }
  return (
    <form onSubmit={handleSubmit}>
      <input name="q" defaultValue="java" />
      <button>Search</button>
    </form>
  );
}
```

`defaultValue` sets the initial value without making it controlled. For big forms, libraries like **React Hook Form** (with Zod for validation schemas) reduce boilerplate.

::tip Client-side validation is for user experience. **The server must validate again** (as in the Spring Boot course): anyone can bypass your React form with a direct API call.

## Common mistakes

- `onClick={handleClick()}`: runs immediately on every render.
- A controlled input with `value` but no `onChange`: it becomes read-only (React warns).
- Switching an input between `undefined` and a string value ("uncontrolled to controlled" warning): initialise with `''`.
- Forgetting `e.preventDefault()` in submit handlers.

## Exercise

1. Build a login form (email + password) with a "show password" checkbox that toggles the input type.
2. Add a character counter under a textarea: "42 / 200", turning red over 200.
3. Validate that "password" and "confirm password" match before submitting.
4. Add a "Reset" button that clears the form and the errors.

::hint Hint for 1 | `<input type={show ? 'text' : 'password'} value={password} onChange={e => setPassword(e.target.value)} />` with a separate `show` state controlled by the checkbox.

## Interview corner

- **Controlled vs uncontrolled components?** Controlled inputs take their value from React state; uncontrolled inputs keep their own DOM state, read via refs or FormData.
- **Why call preventDefault on submit?** To stop the browser's default full-page form submission.
- **How do you handle many inputs with one handler?** Use each input's `name` and a computed property: `setForm(f => ({ ...f, [name]: value }))`.
- **What is a synthetic event?** React's cross-browser wrapper around native events.

## Summary

- Attach handlers with `onClick`, `onChange`, `onSubmit`; pass functions, don't call them.
- Controlled inputs: `value` + `onChange` bound to state; checkboxes use `checked`.
- Validate on submit, show errors per field, prevent the default submit.

Next: rendering lists and conditional content.
