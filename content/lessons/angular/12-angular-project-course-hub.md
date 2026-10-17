---
title: "Angular Project: Course Hub Front End"
slug: angular-project-course-hub
subject: angular
lesson: 12
difficulty: advanced
excerpt: Build the Course Hub front end in Angular for the Spring Boot enrollment API - a typed API service, a signal-based store, routed pages, enrollment with server error messages, progress tracking, a leaderboard and deployment.
---

Let's build the same **Course Hub** app as the React course, the Angular way, against the **Course Enrollment API** from the Spring Boot course. Comparing the two versions is a great way to understand both frameworks.

## What we'll build

| Route | Page |
|---|---|
| `/` | courses with Enroll buttons, success/error messages |
| `/my-courses` | your enrollments with progress bars and "+1 lesson" |
| `/leaderboard` | top learners |

## Project setup

```bash
ng new course-hub --style=css --ssr=false
cd course-hub
ng serve --proxy-config proxy.conf.json      # proxy /api to Spring Boot (lesson 10)
```

## 1. Models and the API service

```ts
// src/app/models.ts
export interface Course { id: number; title: string; totalLessons: number; }
export interface Enrollment { id: number; course: string; completed: number; percent: number; finished: boolean; }
export interface LeaderRow { student: string; lessonsCompleted: number; courses: number; }
```

```ts
// src/app/hub-api.service.ts
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';
import { Observable, catchError, throwError } from 'rxjs';
import { Course, Enrollment, LeaderRow } from './models';

@Injectable({ providedIn: 'root' })
export class HubApi {
  private http = inject(HttpClient);

  private friendly<T>(source: Observable<T>): Observable<T> {
    return source.pipe(
      catchError((e: HttpErrorResponse) =>
        throwError(() => new Error(e.error?.detail ?? `Request failed (${e.status})`)))   // ProblemDetail → message
    );
  }

  courses() { return this.friendly(this.http.get<Course[]>('/api/courses')); }
  enrollments(studentId: number) { return this.friendly(this.http.get<Enrollment[]>(`/api/students/${studentId}/enrollments`)); }
  enroll(studentId: number, courseId: number) { return this.friendly(this.http.post<Enrollment>('/api/enrollments', { studentId, courseId })); }
  progress(id: number, lessons: number) { return this.friendly(this.http.post<Enrollment>(`/api/enrollments/${id}/progress`, { lessons })); }
  leaderboard() { return this.friendly(this.http.get<LeaderRow[]>('/api/leaderboard?top=10')); }
}
```

## 2. A signal store for "my enrollments"

```ts
// src/app/enrollment.store.ts
import { Injectable, computed, inject, signal } from '@angular/core';
import { HubApi } from './hub-api.service';
import { Enrollment } from './models';

@Injectable({ providedIn: 'root' })
export class EnrollmentStore {
  private api = inject(HubApi);
  readonly studentId = signal(1);                    // after adding login: from the JWT
  readonly enrollments = signal<Enrollment[]>([]);
  readonly error = signal<string | null>(null);
  readonly active = computed(() => this.enrollments().filter(e => !e.finished).length);

  load() {
    this.api.enrollments(this.studentId()).subscribe({
      next: list => { this.enrollments.set(list); this.error.set(null); },
      error: (e: Error) => this.error.set(e.message),
    });
  }

  completeLesson(id: number) {
    this.api.progress(id, 1).subscribe({
      next: updated => this.enrollments.update(list => list.map(e => (e.id === id ? updated : e))),
      error: (e: Error) => this.error.set(e.message),
    });
  }
}
```

## 3. Pages

```ts
// src/app/pages/courses-page.component.ts
import { ChangeDetectionStrategy, Component, inject, signal } from '@angular/core';
import { toSignal } from '@angular/core/rxjs-interop';
import { HubApi } from '../hub-api.service';
import { EnrollmentStore } from '../enrollment.store';
import { Course } from '../models';

@Component({
  selector: 'app-courses-page',
  standalone: true,
  changeDetection: ChangeDetectionStrategy.OnPush,
  template: `
    <h1>Courses</h1>
    @if (message(); as m) {
      <p [attr.role]="m.ok ? 'status' : 'alert'" [class.error]="!m.ok">{{ m.text }}</p>
    }
    <ul class="grid">
      @for (c of courses(); track c.id) {
        <li class="card">
          <h3>{{ c.title }}</h3>
          <p>{{ c.totalLessons }} lessons</p>
          <button [disabled]="busy() === c.id" (click)="enroll(c)">{{ busy() === c.id ? 'Enrolling…' : 'Enroll' }}</button>
        </li>
      } @empty {
        <li>Loading courses…</li>
      }
    </ul>
  `,
})
export class CoursesPageComponent {
  private api = inject(HubApi);
  private store = inject(EnrollmentStore);
  courses = toSignal(this.api.courses(), { initialValue: [] as Course[] });
  busy = signal<number | null>(null);
  message = signal<{ ok: boolean; text: string } | null>(null);

  enroll(course: Course) {
    this.busy.set(course.id);
    this.api.enroll(this.store.studentId(), course.id).subscribe({
      next: () => { this.message.set({ ok: true, text: `Enrolled in ${course.title}. Happy learning!` }); this.busy.set(null); },
      error: (e: Error) => { this.message.set({ ok: false, text: e.message }); this.busy.set(null); },
    });
  }
}
```

```ts
// src/app/pages/my-courses-page.component.ts
import { ChangeDetectionStrategy, Component, OnInit, inject } from '@angular/core';
import { EnrollmentStore } from '../enrollment.store';

@Component({
  selector: 'app-my-courses-page',
  standalone: true,
  changeDetection: ChangeDetectionStrategy.OnPush,
  template: `
    <h1>My courses <small>({{ store.active() }} active)</small></h1>
    @if (store.error()) { <p role="alert">{{ store.error() }}</p> }
    @for (e of store.enrollments(); track e.id) {
      <article class="card">
        <h3>{{ e.course }} @if (e.finished) { 🎉 }</h3>
        <div class="bar" role="progressbar" [attr.aria-valuenow]="e.percent"><div [style.width.%]="e.percent"></div></div>
        <p class="stats">{{ e.completed }} lessons · {{ e.percent }}%</p>
        @if (!e.finished) { <button (click)="store.completeLesson(e.id)">+1 lesson</button> }
      </article>
    } @empty {
      <p>You haven't enrolled in any course yet.</p>
    }
  `,
})
export class MyCoursesPageComponent implements OnInit {
  store = inject(EnrollmentStore);
  ngOnInit() { this.store.load(); }
}
```

```ts
// src/app/pages/leaderboard-page.component.ts
import { ChangeDetectionStrategy, Component, inject } from '@angular/core';
import { toSignal } from '@angular/core/rxjs-interop';
import { HubApi } from '../hub-api.service';

@Component({
  selector: 'app-leaderboard-page',
  standalone: true,
  changeDetection: ChangeDetectionStrategy.OnPush,
  template: `
    <h1>Leaderboard</h1>
    <ol>
      @for (row of rows(); track row.student; let i = $index) {
        <li>{{ medals[i] ?? '#' + (i + 1) }} {{ row.student }}: {{ row.lessonsCompleted }} lessons</li>
      }
    </ol>
  `,
})
export class LeaderboardPageComponent {
  medals = ['🥇', '🥈', '🥉'];
  rows = toSignal(inject(HubApi).leaderboard(), { initialValue: [] });
}
```

## 4. Routes and shell

```ts
// src/app/app.routes.ts
import { Routes } from '@angular/router';

export const routes: Routes = [
  { path: '', loadComponent: () => import('./pages/courses-page.component').then(m => m.CoursesPageComponent), title: 'Courses' },
  { path: 'my-courses', loadComponent: () => import('./pages/my-courses-page.component').then(m => m.MyCoursesPageComponent), title: 'My courses' },
  { path: 'leaderboard', loadComponent: () => import('./pages/leaderboard-page.component').then(m => m.LeaderboardPageComponent), title: 'Leaderboard' },
  { path: '**', redirectTo: '' },
];
```

```ts
// src/app/app.component.ts
import { Component } from '@angular/core';
import { RouterLink, RouterLinkActive, RouterOutlet } from '@angular/router';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, RouterLink, RouterLinkActive],
  template: `
    <nav>
      <a routerLink="/" routerLinkActive="active" [routerLinkActiveOptions]="{ exact: true }">Courses</a>
      <a routerLink="/my-courses" routerLinkActive="active">My courses</a>
      <a routerLink="/leaderboard" routerLinkActive="active">Leaderboard</a>
    </nav>
    <main><router-outlet /></main>
  `,
})
export class AppComponent {}
```

```ts
// src/app/app.config.ts
import { ApplicationConfig, provideZoneChangeDetection } from '@angular/core';
import { provideRouter, withComponentInputBinding } from '@angular/router';
import { provideHttpClient } from '@angular/common/http';
import { routes } from './app.routes';

export const appConfig: ApplicationConfig = {
  providers: [
    provideZoneChangeDetection({ eventCoalescing: true }),
    provideRouter(routes, withComponentInputBinding()),
    provideHttpClient(),
  ],
};
```

## 5. Try it end to end

1. Start the Spring Boot API and register student 1.
2. `ng serve --proxy-config proxy.conf.json` and open `http://localhost:4200`.
3. Enroll in four courses: the fourth shows **"Finish a course first: max 3 active courses"** from the server.
4. On My courses, click "+1 lesson" and watch the progress bar move; finished courses get 🎉.

## 6. Deploy

```bash
ng build                 # dist/course-hub/browser
```

Host the static files anywhere with an SPA fallback, or serve them from Spring Boot's `static` folder. In production, put the API URL in an `InjectionToken` or environment file, and enable CORS for your domain in Spring.

## Angular vs React: what you noticed

| | React version | Angular version |
|---|---|---|
| State | `useState`, custom hook | signals in a service store |
| Data | `fetch` + `useEffect` | HttpClient + RxJS + `toSignal` |
| DI | props/context | `inject()` services |
| Routing | React Router | built-in router with lazy `loadComponent` |
| Structure | your choice | conventions (services, pages, models) |

## Extend it

1. Login with JWT + an interceptor + a route guard (lessons 8 and 10).
2. An enroll confirmation dialog with Angular Material (`ng add @angular/material`).
3. Unit tests with `TestBed` and `HttpTestingController`.
4. Optimistic progress updates with rollback on error.

::hint Hint for 3 | `TestBed.configureTestingModule({ providers: [provideHttpClient(), provideHttpClientTesting()] })`, then `const ctrl = TestBed.inject(HttpTestingController); ctrl.expectOne('/api/courses').flush([{ id: 1, title: 'Java', totalLessons: 16 }]);`

::tip Put both front ends (React and Angular) and the Spring Boot API on GitHub with live links. It shows you can work in any Java full-stack team, which is exactly what Indian IT companies hire for.

## Summary

- A typed API service turns ProblemDetail errors into friendly messages.
- A signal store holds enrollment state; pages use `toSignal`, OnPush and `@for`/`@if`.
- Lazy routes and a simple shell complete a production-style Angular app.

**Congratulations on finishing the Angular course!**
