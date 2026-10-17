---
title: React Context and useReducer
slug: react-context-and-usereducer
subject: react
lesson: 9
difficulty: intermediate
excerpt: Share data like the logged-in user, theme or cart across many components without prop drilling using Context, and manage complex state transitions with useReducer - combined into a cart store.
---

Some data is needed almost everywhere: the logged-in user, the theme, the language, the cart. Passing it through every component's props ("prop drilling") gets painful. **Context** lets any component read a value directly. And when state logic gets complex, **useReducer** keeps it organised.

## Context in three steps

```jsx
// src/ThemeContext.jsx
import { createContext, useContext, useState } from 'react';

const ThemeContext = createContext(null);                     // 1. create

export function ThemeProvider({ children }) {                  // 2. provide
  const [theme, setTheme] = useState('light');
  const toggle = () => setTheme(t => (t === 'light' ? 'dark' : 'light'));
  return <ThemeContext.Provider value={{ theme, toggle }}>{children}</ThemeContext.Provider>;
}

export function useTheme() {                                   // 3. consume (a custom hook)
  const ctx = useContext(ThemeContext);
  if (!ctx) throw new Error('useTheme must be used inside <ThemeProvider>');
  return ctx;
}
```

```jsx
// src/ThemeButton.jsx
import { useTheme } from './ThemeContext.jsx';

export default function ThemeButton() {
  const { theme, toggle } = useTheme();             // no props needed, however deep this is
  return <button onClick={toggle}>Theme: {theme}</button>;
}
```

Wrap the app once (in `main.jsx` or `App.jsx`):

```jsx
<ThemeProvider>
  <App />
</ThemeProvider>
```

Every component that calls `useTheme()` re-renders when the value changes.

## useReducer: state transitions in one place

When updates follow rules ("add to cart", "change quantity", "apply coupon", "clear"), scattering `setState` calls across handlers gets messy. A **reducer** is a pure function: `(currentState, action) → newState`.

```jsx
// src/cartReducer.js
export const initialCart = { items: [], coupon: null };

export function cartReducer(state, action) {
  switch (action.type) {
    case 'add': {
      const existing = state.items.find(i => i.id === action.item.id);
      const items = existing
        ? state.items.map(i => (i.id === action.item.id ? { ...i, qty: i.qty + 1 } : i))
        : [...state.items, { ...action.item, qty: 1 }];
      return { ...state, items };
    }
    case 'setQty':
      return {
        ...state,
        items: state.items
          .map(i => (i.id === action.id ? { ...i, qty: action.qty } : i))
          .filter(i => i.qty > 0),
      };
    case 'coupon':
      return { ...state, coupon: action.code === 'LEARN20' ? { code: 'LEARN20', percent: 20 } : null };
    case 'clear':
      return initialCart;
    default:
      throw new Error('Unknown action: ' + action.type);
  }
}

export function cartTotal(state) {
  const subtotal = state.items.reduce((sum, i) => sum + i.price * i.qty, 0);
  return state.coupon ? Math.round(subtotal * (100 - state.coupon.percent) / 100) : subtotal;
}
```

Reducers are plain functions: easy to read, and easy to unit test without rendering anything.

## Context + reducer = a small store

```jsx
// src/CartContext.jsx
import { createContext, useContext, useReducer } from 'react';
import { cartReducer, initialCart, cartTotal } from './cartReducer.js';

const CartContext = createContext(null);

export function CartProvider({ children }) {
  const [state, dispatch] = useReducer(cartReducer, initialCart);
  const value = { items: state.items, coupon: state.coupon, total: cartTotal(state), dispatch };
  return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
}

export function useCart() {
  const ctx = useContext(CartContext);
  if (!ctx) throw new Error('useCart must be used inside <CartProvider>');
  return ctx;
}
```

```jsx
// src/Shop.jsx
import { CartProvider, useCart } from './CartContext.jsx';

const COURSES = [{ id: 1, title: 'Java', price: 500 }, { id: 2, title: 'React', price: 600 }];

function AddButtons() {
  const { dispatch } = useCart();
  return COURSES.map(c => (
    <button key={c.id} onClick={() => dispatch({ type: 'add', item: c })}>Add {c.title}</button>
  ));
}

function CartSummary() {
  const { items, total, coupon, dispatch } = useCart();
  return (
    <aside>
      <p>{items.reduce((n, i) => n + i.qty, 0)} items · Total ₹{total}{coupon && ` (${coupon.code} applied)`}</p>
      <button onClick={() => dispatch({ type: 'coupon', code: 'LEARN20' })}>Apply LEARN20</button>
      <button onClick={() => dispatch({ type: 'clear' })}>Clear</button>
    </aside>
  );
}

export default function Shop() {
  return (
    <CartProvider>
      <AddButtons />
      <CartSummary />
    </CartProvider>
  );
}
```

Add Java twice and React once: `3 items · Total ₹1600`. Apply LEARN20: `Total ₹1280 (LEARN20 applied)`.

## useState or useReducer?

| useState | useReducer |
|---|---|
| independent, simple values | several values that change together |
| few update types | many named actions with rules |
| logic inline in handlers | logic centralised and testable |

## When not to use Context

- For data only one or two levels down: just pass props.
- For **frequently changing** values used by many components (e.g. mouse position): every consumer re-renders on each change. Split contexts (separate state and dispatch), or use a state library.
- For **server data** (courses from an API): use TanStack Query, which caches per query.

::tip For large apps, teams often use **Zustand** or **Redux Toolkit** for global client state. Redux uses exactly the reducer + actions idea you just learned, so this lesson is your foundation for both.

## Exercise

1. Create an `AuthContext` with `user`, `login(name)` and `logout()`, and show "Hi, Asha" in a header far down the tree.
2. Add `remove` and `increment` actions to the cart reducer.
3. Write unit tests (plain function calls) for `cartReducer`.
4. Persist the cart in `localStorage` (hint: next lesson's custom hook, or an effect in the provider).

::hint Hint for 3 | `const s1 = cartReducer(initialCart, { type: 'add', item: { id: 1, price: 500 } }); expect(s1.items[0].qty).toBe(1);` No React needed.

## Interview corner

- **What problem does Context solve?** Prop drilling: passing data through many layers that don't use it.
- **Downside of Context?** All consumers re-render when the value changes; it's not a full state management solution for frequently-changing data.
- **What is a reducer?** A pure function computing the next state from the current state and an action.
- **useReducer vs Redux?** Same pattern; Redux adds a global store, middleware and devtools.

## Summary

- `createContext` + a Provider + `useContext` (wrapped in a custom hook) share values without prop drilling.
- `useReducer` centralises state transitions in a pure, testable function.
- Together they make a simple app-wide store.

Next: refs and custom hooks.
