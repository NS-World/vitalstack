---
title: React Router - Pages and Navigation
slug: react-router
subject: react
lesson: 11
difficulty: intermediate
excerpt: Add multiple pages to a React single-page app with React Router - routes, links, nested layouts, URL parameters, query strings, navigation in code, 404 pages and protected routes.
---

A React app is a **single page**: one `index.html`. But users expect URLs (`/courses`, `/courses/42`, `/profile`), working back buttons and shareable links. **React Router** maps URLs to components without reloading the page.

```bash
npm install react-router-dom
```

## Basic routes

```jsx
// src/App.jsx
import { BrowserRouter, Routes, Route, Link, NavLink, Outlet } from 'react-router-dom';
import CoursesPage from './CoursesPage.jsx';
import CoursePage from './CoursePage.jsx';
import ProfilePage from './ProfilePage.jsx';
import RequireAuth from './RequireAuth.jsx';

function Layout() {
  return (
    <>
      <nav>
        <Link to="/">VitalStack</Link>
        <NavLink to="/courses">Courses</NavLink>              {/* gets class "active" on its page */}
        <NavLink to="/profile">Profile</NavLink>
      </nav>
      <main><Outlet /></main>                                {/* the child route renders here */}
    </>
  );
}

const Home = () => <h1>Learn to code for free</h1>;
const NotFound = () => <h1>404: Page not found</h1>;

export function AppRoutes() {
  return (
    <Routes>
      <Route element={<Layout />}>                           {/* layout route: shared nav */}
        <Route index element={<Home />} />                   {/* "/" */}
        <Route path="courses" element={<CoursesPage />} />
        <Route path="courses/:slug" element={<CoursePage />} />
        <Route path="profile" element={<RequireAuth><ProfilePage /></RequireAuth>} />
        <Route path="*" element={<NotFound />} />            {/* anything else */}
      </Route>
    </Routes>
  );
}

export default function App() {
  return (
    <BrowserRouter>
      <AppRoutes />
    </BrowserRouter>
  );
}
```

- `<Link to="/courses">` changes the URL **without reloading** (unlike `<a href>`).
- `<NavLink>` adds an `active` class for styling the current page.
- A **layout route** (no path, with `<Outlet />`) wraps pages with shared UI.

## URL parameters and query strings

```jsx
// src/CoursePage.jsx
import { useParams, useNavigate } from 'react-router-dom';

const COURSES = { java: 'Java Basics', react: 'React' };

export default function CoursePage() {
  const { slug } = useParams();                     // from "courses/:slug"
  const navigate = useNavigate();
  const title = COURSES[slug];

  if (!title) return <p>Course not found.</p>;
  return (
    <article>
      <h1>{title}</h1>
      <button onClick={() => navigate('/courses')}>Back to all courses</button>
    </article>
  );
}
```

```jsx
// src/CoursesPage.jsx
import { Link, useSearchParams } from 'react-router-dom';

const LIST = [
  { slug: 'java', title: 'Java Basics', level: 'beginner' },
  { slug: 'react', title: 'React', level: 'beginner' },
  { slug: 'spring', title: 'Spring Boot', level: 'intermediate' },
];

export default function CoursesPage() {
  const [params, setParams] = useSearchParams();    // ?level=beginner
  const level = params.get('level') ?? 'all';
  const shown = LIST.filter(c => level === 'all' || c.level === level);

  return (
    <div>
      <select aria-label="Level" value={level}
              onChange={e => setParams(e.target.value === 'all' ? {} : { level: e.target.value })}>
        <option value="all">All</option>
        <option value="beginner">Beginner</option>
        <option value="intermediate">Intermediate</option>
      </select>
      <ul>{shown.map(c => <li key={c.slug}><Link to={`/courses/${c.slug}`}>{c.title}</Link></li>)}</ul>
    </div>
  );
}
```

Storing filters in the **URL** (instead of only in state) means users can bookmark and share filtered views, and the back button works as expected.

## Protected routes

```jsx
// src/RequireAuth.jsx
import { Navigate, useLocation } from 'react-router-dom';

export function isLoggedIn() {
  return Boolean(localStorage.getItem('token'));
}

export default function RequireAuth({ children }) {
  const location = useLocation();
  if (!isLoggedIn()) {
    return <Navigate to="/login" replace state={{ from: location.pathname }} />;   // come back after login
  }
  return children;
}
```

```jsx
// src/ProfilePage.jsx
export default function ProfilePage() {
  return <h1>Your profile</h1>;
}
```

After a successful login, `navigate(location.state?.from ?? '/', { replace: true })` sends the user back where they wanted to go.

::tip Hiding a page in React is **not** security. A protected route only improves the user experience. The real protection is your API (Spring Security returning 401/403). Never trust the client.

## Deploying a router app

With `BrowserRouter`, refreshing `/courses/java` asks the **server** for that path. Static hosts must send `index.html` for unknown paths:

- **Netlify**: a `_redirects` file with `/*  /index.html  200`.
- **Vercel / Cloudflare Pages**: SPA fallback setting or rewrites config.
- **Apache / shared hosting**: an `.htaccess` rewrite to `index.html`.

## Data routers (going further)

React Router 6.4+ also offers `createBrowserRouter` with **loaders** (fetch data before a page renders), **actions** (handle form submissions) and error boundaries per route. Frameworks like Remix/React Router 7 build on this.

## Exercise

1. Add an About page and a link in the nav.
2. Add `/courses/:slug/lessons/:lessonId` as a nested route with an `<Outlet />` inside `CoursePage`.
3. Build a login page that saves a fake token and redirects back to the page the user came from.
4. Add a search box on the courses page that stores the query in `?q=`.

::hint Hint for 4 | `setParams(p => { const n = new URLSearchParams(p); value ? n.set('q', value) : n.delete('q'); return n; })` keeps other parameters like `level`.

## Interview corner

- **What is client-side routing?** Changing the URL and rendering matching components in JavaScript without full page reloads.
- **Link vs a tag?** Link navigates through the router (no reload); a full `<a href>` reloads the whole app.
- **How do you read route parameters?** `useParams()`; query strings with `useSearchParams()`.
- **How do you protect routes?** A wrapper component that redirects with `<Navigate>` when unauthenticated (backed by server-side checks).

## Summary

- `BrowserRouter` + `Routes` + `Route` map URLs to components; `Link`/`NavLink` navigate.
- Layout routes with `<Outlet />` share UI; `:params` and search params make URLs meaningful.
- Redirect unauthenticated users with `<Navigate>`; configure SPA fallback when deploying.

Next: build a complete project.
