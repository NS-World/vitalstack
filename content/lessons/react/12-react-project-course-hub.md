---
title: "React Project: Course Hub Front End"
slug: react-project-course-hub
subject: react
lesson: 12
difficulty: advanced
excerpt: Build a complete React front end for the Spring Boot enrollment API - an API client, pages with React Router, enrollment with optimistic UI and error messages, progress bars, a leaderboard and deployment.
---

Time to connect everything. You'll build **Course Hub**, a React front end for the **Course Enrollment API** from the Spring Boot course: browse courses, enroll, track progress and see the leaderboard.

## Features

- `/`: all courses with an **Enroll** button.
- `/my-courses`: your enrollments with progress bars and a "+1 lesson" button.
- `/leaderboard`: top learners.
- Clear loading and error states; friendly messages for API errors (e.g. "max 3 active courses").

## Project setup

```bash
npm create vite@latest course-hub -- --template react
cd course-hub
npm install react-router-dom
```

Point Vite's dev proxy at the Spring Boot app (lesson 7) so `/api` calls reach `http://localhost:8080`.

## 1. A small API client

Keep all HTTP details in one module. Components call functions, not URLs:

```js
// src/api.js
async function request(path, options = {}) {
  const res = await fetch(`/api${path}`, {
    headers: { 'Content-Type': 'application/json' },
    ...options,
  });
  if (!res.ok) {
    let message = `Request failed (${res.status})`;
    try {
      const problem = await res.json();              // Spring's ProblemDetail
      if (problem.detail) message = problem.detail;
    } catch { /* not JSON */ }
    throw new Error(message);
  }
  return res.status === 204 ? null : res.json();
}

export const api = {
  courses: () => request('/courses'),
  enrollments: studentId => request(`/students/${studentId}/enrollments`),
  enroll: (studentId, courseId) =>
    request('/enrollments', { method: 'POST', body: JSON.stringify({ studentId, courseId }) }),
  progress: (enrollmentId, lessons) =>
    request(`/enrollments/${enrollmentId}/progress`, { method: 'POST', body: JSON.stringify({ lessons }) }),
  leaderboard: () => request('/leaderboard?top=10'),
};
```

The server's ProblemDetail `detail` ("Finish a course first: max 3 active courses") becomes the error message the user sees.

## 2. A reusable data hook

```jsx
// src/useApi.js
import { useCallback, useEffect, useState } from 'react';

export function useApi(loader, deps) {
  const [state, setState] = useState({ data: null, error: null, loading: true });

  const reload = useCallback(() => {
    setState(s => ({ ...s, loading: true }));
    return loader()
      .then(data => setState({ data, error: null, loading: false }))
      .catch(error => setState({ data: null, error, loading: false }));
  }, deps);   // eslint-disable-line react-hooks/exhaustive-deps

  useEffect(() => { reload(); }, [reload]);
  return { ...state, reload };
}
```

## 3. Pages

```jsx
// src/CoursesPage.jsx
import { useState } from 'react';
import { api } from './api.js';
import { useApi } from './useApi.js';

export default function CoursesPage({ studentId }) {
  const { data: courses, error, loading } = useApi(() => api.courses(), []);
  const [message, setMessage] = useState(null);
  const [busy, setBusy] = useState(null);

  async function enroll(course) {
    setBusy(course.id);
    setMessage(null);
    try {
      await api.enroll(studentId, course.id);
      setMessage({ type: 'ok', text: `Enrolled in ${course.title}. Happy learning!` });
    } catch (e) {
      setMessage({ type: 'error', text: e.message });
    } finally {
      setBusy(null);
    }
  }

  if (loading) return <p>Loading courses…</p>;
  if (error) return <p role="alert">{error.message}</p>;

  return (
    <section>
      <h1>Courses</h1>
      {message && <p role={message.type === 'error' ? 'alert' : 'status'} className={message.type}>{message.text}</p>}
      <ul className="grid">
        {courses.map(c => (
          <li key={c.id} className="card">
            <h3>{c.title}</h3>
            <p>{c.totalLessons} lessons</p>
            <button disabled={busy === c.id} onClick={() => enroll(c)}>
              {busy === c.id ? 'Enrolling…' : 'Enroll'}
            </button>
          </li>
        ))}
      </ul>
    </section>
  );
}
```

```jsx
// src/MyCoursesPage.jsx
import { api } from './api.js';
import { useApi } from './useApi.js';

function ProgressBar({ percent }) {
  return (
    <div className="bar" role="progressbar" aria-valuenow={percent} aria-valuemin={0} aria-valuemax={100}>
      <div style={{ width: `${percent}%` }} />
    </div>
  );
}

export default function MyCoursesPage({ studentId }) {
  const { data, error, loading, reload } = useApi(() => api.enrollments(studentId), [studentId]);

  async function completeLesson(id) {
    await api.progress(id, 1);
    reload();                                           // refresh from the server: it owns the rules
  }

  if (loading && !data) return <p>Loading…</p>;
  if (error) return <p role="alert">{error.message}</p>;
  if (data.length === 0) return <p>You haven't enrolled in any course yet.</p>;

  return (
    <section>
      <h1>My courses</h1>
      {data.map(e => (
        <article key={e.id} className="card">
          <h3>{e.course} {e.finished && '🎉'}</h3>
          <ProgressBar percent={e.percent} />
          <p>{e.completed} lessons · {e.percent}%</p>
          {!e.finished && <button onClick={() => completeLesson(e.id)}>+1 lesson</button>}
        </article>
      ))}
    </section>
  );
}
```

```jsx
// src/LeaderboardPage.jsx
import { api } from './api.js';
import { useApi } from './useApi.js';

export default function LeaderboardPage() {
  const { data, error, loading } = useApi(() => api.leaderboard(), []);
  if (loading) return <p>Loading…</p>;
  if (error) return <p role="alert">{error.message}</p>;
  return (
    <ol className="leaderboard">
      {data.map((row, i) => (
        <li key={row.student}>
          {['🥇', '🥈', '🥉'][i] ?? `#${i + 1}`} {row.student}: {row.lessonsCompleted} lessons
        </li>
      ))}
    </ol>
  );
}
```

## 4. The app shell

```jsx
// src/App.jsx
import { BrowserRouter, NavLink, Route, Routes } from 'react-router-dom';
import CoursesPage from './CoursesPage.jsx';
import MyCoursesPage from './MyCoursesPage.jsx';
import LeaderboardPage from './LeaderboardPage.jsx';

export function Shell({ studentId }) {
  return (
    <>
      <nav>
        <NavLink to="/">Courses</NavLink>
        <NavLink to="/my-courses">My courses</NavLink>
        <NavLink to="/leaderboard">Leaderboard</NavLink>
      </nav>
      <main>
        <Routes>
          <Route path="/" element={<CoursesPage studentId={studentId} />} />
          <Route path="/my-courses" element={<MyCoursesPage studentId={studentId} />} />
          <Route path="/leaderboard" element={<LeaderboardPage />} />
        </Routes>
      </main>
    </>
  );
}

export default function App() {
  const studentId = 1;                // after adding login: read it from your auth context / JWT
  return (
    <BrowserRouter>
      <Shell studentId={studentId} />
    </BrowserRouter>
  );
}
```

## 5. Run it end to end

1. Start the Spring Boot API (`./mvnw spring-boot:run`) and register a student with id 1 (`POST /api/students`).
2. Start React (`npm run dev`), open `http://localhost:5173`.
3. Enroll in four courses: the fourth shows **"Finish a course first: max 3 active courses"**, straight from the server's business rule.
4. Complete lessons on My courses and watch the progress bars and the leaderboard update.

## 6. Deploy

```bash
npm run build          # dist/ contains static files
```

- Host `dist/` on Netlify, Vercel or Cloudflare Pages (with SPA fallback), or serve it from Spring Boot by copying it into `src/main/resources/static`.
- In production, call the API by its full URL (an env variable like `VITE_API_URL` read with `import.meta.env.VITE_API_URL`), and allow that origin in Spring's CORS configuration.

## Ideas to extend it

1. **Login with JWT** (Spring Boot security lesson): store the token in an `AuthContext`, attach `Authorization: Bearer …` in `api.js`, and protect pages with `RequireAuth`.
2. **TanStack Query** instead of `useApi`, with automatic refetching after mutations.
3. **Optimistic updates**: show the new progress immediately and roll back on error.
4. **Tests** with Vitest + Testing Library, mocking `fetch`.
5. **TypeScript**: type the API responses (`type Enrollment = { id: number; course: string; … }`).

::hint Hint for 1 | In `request`, read the token from localStorage (or your context) and add `...(token && { Authorization: \`Bearer ${token}\` })` to the headers.

::tip A deployed full-stack project (Spring Boot API + React UI, with a README and a live link) is one of the strongest things you can put on a fresher CV. Recruiters can click it and see it working.

## Summary

- Centralise HTTP in an API module that turns ProblemDetail errors into messages.
- A small `useApi` hook handles loading, errors and reloads.
- Pages + React Router + the server's rules = a real full-stack app.

**Congratulations on finishing the React course!** Next, try the **Angular** course to compare the two most popular front-end frameworks.
