---
title: React Setup with Vite and JSX Basics
slug: react-setup-and-jsx
subject: react
lesson: 2
difficulty: beginner
excerpt: Create a React project with Vite, understand the folder structure and how main.jsx mounts the app, then learn JSX - expressions in braces, className, self-closing tags, fragments and the rules that trip beginners.
---

Let's create a React project and learn **JSX**, the HTML-like syntax React components return.

## Create a project with Vite

You need **Node.js 20+** (check with `node -v`). Then:

```bash
npm create vite@latest course-app -- --template react
cd course-app
npm install
npm run dev
```

Open the URL it prints (usually `http://localhost:5173`). Edit `src/App.jsx`, save, and the browser updates instantly (hot module replacement).

::tip Use the `react-ts` template instead of `react` to get TypeScript. It's what most companies use. This course uses JavaScript so the React ideas stay in focus; everything transfers directly.

## Project structure

```text
course-app/
├── index.html          ← the single HTML page, with <div id="root"></div>
├── package.json        ← dependencies and scripts (dev, build, preview)
├── vite.config.js
└── src/
    ├── main.jsx        ← entry point: mounts <App /> into #root
    ├── App.jsx         ← your root component
    ├── App.css, index.css
    └── assets/
```

```jsx
// src/main.jsx
import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import App from './App.jsx';
import './index.css';

createRoot(document.getElementById('root')).render(
  <StrictMode>
    <App />
  </StrictMode>
);
```

`StrictMode` runs extra checks in development (it may call some functions twice on purpose to reveal bugs). It does nothing in production builds.

## Your first component

```jsx
// src/Welcome.jsx
export default function Welcome() {
  const name = 'Asha';
  const lessons = 12;
  const today = new Date(2026, 9, 17);

  return (
    <section className="welcome">
      <h1>Namaste, {name}!</h1>
      <p>You have {lessons} lessons left. That's about {lessons * 15} minutes.</p>
      <p>Today is {today.toLocaleDateString('en-IN', { weekday: 'long' })}.</p>
      <img src="/logo.svg" alt="VitalStack logo" width={48} />
    </section>
  );
}
```

A component is a **function** whose name starts with a **capital letter** and that returns JSX.

## JSX rules

JSX looks like HTML, but it's JavaScript underneath. Vite (with Babel/esbuild) turns `<h1>Hi</h1>` into `React.createElement('h1', null, 'Hi')`.

| Rule | HTML | JSX |
|---|---|---|
| JavaScript inside `{ }` | – | `{name}`, `{price * 2}`, `{user.isAdmin ? 'Admin' : 'User'}` |
| `class` → `className` | `class="card"` | `className="card"` |
| `for` → `htmlFor` | `<label for="email">` | `<label htmlFor="email">` |
| Close every tag | `<img src="a.png">` | `<img src="a.png" />` |
| camelCase attributes | `onclick`, `tabindex` | `onClick`, `tabIndex` |
| Inline styles are objects | `style="color: red"` | `style={{ color: 'red', fontSize: 18 }}` |
| One root element | – | wrap siblings in a `<div>` or a fragment `<>…</>` |
| Comments | `<!-- -->` | `{/* comment */}` |

```jsx
// src/PriceTag.jsx
export default function PriceTag() {
  const price = 499;
  const onSale = true;
  return (
    <>
      {/* a fragment groups elements without adding a <div> */}
      <span className="price" style={{ fontWeight: 700 }}>₹{price}</span>
      {onSale && <span className="badge">Sale</span>}
    </>
  );
}
```

Inside `{ }` you can put any **expression** (something that produces a value): variables, math, function calls, ternaries. You can't put **statements** like `if` or `for` directly; you'll use ternaries, `&&` and `map` instead (lesson 6).

## Components using components

```jsx
// src/App.jsx
import Welcome from './Welcome.jsx';
import PriceTag from './PriceTag.jsx';

export default function App() {
  return (
    <main>
      <Welcome />
      <PriceTag />
    </main>
  );
}
```

Lowercase tags (`<main>`, `<section>`) are HTML elements; capitalised tags (`<Welcome />`) are your components.

## Building for production

```bash
npm run build      # creates dist/ with optimised, minified files
npm run preview    # serve the build locally to test it
```

The `dist` folder is static files: deploy it to Netlify, Vercel, GitHub Pages, Cloudflare Pages, or any web host (even shared hosting).

## Common mistakes

- Lowercase component names (`<welcome />` is treated as an unknown HTML tag).
- Returning two sibling elements without a wrapper or fragment.
- Writing `class=` instead of `className=` (works with a warning, but avoid it).
- Forgetting `export default` / the import, so the component is undefined.
- Putting `if` statements inside JSX braces.

## Exercise

1. Create the project and replace `App.jsx` with your own `Welcome` component.
2. Create a `ProfileCard` component that shows a name, city, photo and a "Follow" button, styled with a CSS class.
3. Show "Good morning", "Good afternoon" or "Good evening" depending on the current hour.
4. Run `npm run build` and look inside `dist`.

::hint Hint for 3 | `const hour = new Date().getHours(); const greeting = hour < 12 ? 'Good morning' : hour < 17 ? 'Good afternoon' : 'Good evening';` then `<h2>{greeting}</h2>`.

## Interview corner

- **What is JSX?** A syntax extension that lets you write HTML-like markup in JavaScript; it compiles to `React.createElement` calls (or the modern JSX runtime).
- **Why className?** `class` is a reserved word in JavaScript.
- **What is a fragment?** `<>…</>` groups children without adding a DOM node.
- **Why must components start with a capital letter?** So JSX can tell components from HTML elements.

## Summary

- `npm create vite@latest` creates a React project; `npm run dev` runs it.
- `main.jsx` renders `<App />` into `#root`.
- JSX: expressions in `{}`, `className`, closed tags, camelCase, one root (or a fragment).

Next: components and props.
