---
title: React Cheat Sheet
slug: react-cheat-sheet
parent: cheat-sheets
template: page-cheat-sheet.php
icon: ⚛️
order: 80
excerpt: React on one page - JSX, components, props, useState, events, forms, lists and keys, useEffect, context, useReducer, useRef, memo, custom hooks and React Router. Printable.
---
## Setup

```bash
npm create vite@latest my-app -- --template react   # or react-ts
cd my-app && npm install && npm run dev
npm run build                                       # dist/
```

## JSX rules

```jsx
export default function Card({ title, price = 0, children }) {
  return (
    <>
      <h3 className="title">{title}</h3>
      <p style={{ fontWeight: 700 }}>{price === 0 ? 'Free' : `₹${price}`}</p>
      <label htmlFor="q">Search</label> <input id="q" />
      {children}
    </>
  );
}
```

`className`, `htmlFor`, camelCase events, close every tag, one root (or `<>…</>`), `{expressions}` only.

## State

```jsx
const [count, setCount] = useState(0);
setCount(c => c + 1);                                // when based on previous value
const [user, setUser] = useState({ name: 'Asha', xp: 0 });
setUser(u => ({ ...u, xp: u.xp + 10 }));             // new object
setItems(prev => [...prev, item]);                   // add
setItems(prev => prev.filter(i => i.id !== id));     // remove
setItems(prev => prev.map(i => i.id === id ? { ...i, done: !i.done } : i));   // update
```

## Events and forms

```jsx
<button onClick={() => remove(id)}>Remove</button>
<input value={name} onChange={e => setName(e.target.value)} />
<input type="checkbox" checked={ok} onChange={e => setOk(e.target.checked)} />
<form onSubmit={e => { e.preventDefault(); save(); }}>…</form>
```

## Lists and conditions

```jsx
<ul>{courses.map(c => <li key={c.id}>{c.title}</li>)}</ul>
{loading ? <Spinner /> : <List />}
{count > 0 && <Badge count={count} />}          // not {count && …}
if (!user) return <Login />;
```

## Effects

```jsx
useEffect(() => {
  const ctrl = new AbortController();
  fetch(`/api/courses/${id}`, { signal: ctrl.signal })
    .then(r => r.json()).then(setCourse).catch(() => {});
  return () => ctrl.abort();                    // cleanup
}, [id]);                                       // re-run when id changes
```

| Deps | Runs |
|---|---|
| `[a, b]` | after mount and when a or b change |
| `[]` | once after mount |
| none | after every render |

## Context and reducer

```jsx
const ThemeContext = createContext(null);
function ThemeProvider({ children }) {
  const [theme, setTheme] = useState('light');
  return <ThemeContext.Provider value={{ theme, setTheme }}>{children}</ThemeContext.Provider>;
}
const useTheme = () => useContext(ThemeContext);

function reducer(state, action) {
  switch (action.type) {
    case 'add': return { ...state, items: [...state.items, action.item] };
    default: return state;
  }
}
const [state, dispatch] = useReducer(reducer, { items: [] });
dispatch({ type: 'add', item });
```

## Refs and performance

```jsx
const inputRef = useRef(null);   <input ref={inputRef} />   inputRef.current.focus();
const timer = useRef(null);      // survives renders, no re-render on change
const List = memo(function List({ items }) { … });
const visible = useMemo(() => items.filter(f), [items, f]);
const onSelect = useCallback(id => setSelected(id), []);
const Admin = lazy(() => import('./Admin.jsx'));   <Suspense fallback={<p>Loading…</p>}><Admin /></Suspense>
```

## Custom hook

```jsx
function useLocalStorage(key, initial) {
  const [value, setValue] = useState(() => JSON.parse(localStorage.getItem(key)) ?? initial);
  useEffect(() => localStorage.setItem(key, JSON.stringify(value)), [key, value]);
  return [value, setValue];
}
```

## React Router (v6)

```jsx
<BrowserRouter>
  <Routes>
    <Route element={<Layout />}>                 {/* contains <Outlet /> */}
      <Route index element={<Home />} />
      <Route path="courses/:slug" element={<Course />} />
      <Route path="*" element={<NotFound />} />
    </Route>
  </Routes>
</BrowserRouter>
const { slug } = useParams();
const [params, setParams] = useSearchParams();
const navigate = useNavigate(); navigate('/login');
<NavLink to="/courses">Courses</NavLink>   <Navigate to="/login" replace />
```

## Rules of hooks

Call hooks at the top level of components or custom hooks only; never in loops, conditions or nested functions. Custom hooks start with `use`.
