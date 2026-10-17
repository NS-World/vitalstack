---
title: Angular HttpClient and RxJS
slug: angular-http-and-rxjs
subject: angular
lesson: 10
difficulty: intermediate
excerpt: Call REST APIs with HttpClient, understand Observables, watch an RxJS pipe filter, map and take values in an animation, build a debounced live search with switchMap, handle errors and add a JWT interceptor.
---

Angular talks to back ends (like the Spring Boot API you built) through **HttpClient**, which returns **Observables** from the **RxJS** library. Observables are streams of values over time, and RxJS operators let you transform them like a pipeline.

## Setup

```ts
// in app.config.ts providers:
provideHttpClient(withInterceptors([authInterceptor]))
```

## A data service

```ts
// src/app/course-api.service.ts
import { HttpClient, HttpParams } from '@angular/common/http';
import { Injectable, inject } from '@angular/core';
import { Observable, catchError, map, of, throwError } from 'rxjs';

export interface Course { id: number; title: string; totalLessons: number; }

@Injectable({ providedIn: 'root' })
export class CourseApi {
  private http = inject(HttpClient);
  private base = '/api/courses';

  list(): Observable<Course[]> {
    return this.http.get<Course[]>(this.base);
  }

  search(q: string): Observable<Course[]> {
    const params = new HttpParams().set('q', q);
    return this.http.get<Course[]>(`${this.base}/search`, { params }).pipe(
      catchError(() => of([]))                              // on error, show no results instead of breaking
    );
  }

  get(id: number): Observable<Course> {
    return this.http.get<Course>(`${this.base}/${id}`).pipe(
      catchError(err => throwError(() => new Error(err.error?.detail ?? 'Could not load the course')))
    );
  }

  create(title: string, totalLessons: number): Observable<Course> {
    return this.http.post<Course>(this.base, { title, totalLessons });
  }

  titles(): Observable<string[]> {
    return this.list().pipe(map(list => list.map(c => c.title)));
  }
}
```

The `detail` field comes from Spring's ProblemDetail errors, so users see the server's real message.

## Observables in 60 seconds

- An Observable is **lazy**: nothing happens until someone **subscribes**. `http.get()` doesn't send a request until then.
- It can emit **many** values over time (keystrokes, websocket messages); HTTP emits **one** value then completes.
- Operators go inside `.pipe(...)` and each returns a new Observable.

Watch values flow through `filter`, `map` and `take`:

::viz rxjs-pipe {"arr": [1, 2, 3, 4, 5, 6, 7, 8], "n": 2}

| Operator | Does |
|---|---|
| `map(fn)` | transform each value |
| `filter(fn)` | keep values that pass |
| `tap(fn)` | side effect (logging) without changing values |
| `debounceTime(ms)` | wait for a pause before emitting |
| `distinctUntilChanged()` | skip repeats |
| `switchMap(fn)` | map to a new Observable and **cancel** the previous one |
| `catchError(fn)` | recover from errors |
| `take(n)` | complete after n values |
| `combineLatest`, `forkJoin` | combine several streams / run requests in parallel |

## Showing data: toSignal or the async pipe

```ts
// src/app/course-page/course-page.component.ts
import { Component, inject } from '@angular/core';
import { toSignal } from '@angular/core/rxjs-interop';
import { CourseApi } from '../course-api.service';

@Component({
  selector: 'app-course-page',
  standalone: true,
  template: `
    <ul>
      @for (c of courses(); track c.id) { <li>{{ c.title }} ({{ c.totalLessons }} lessons)</li> }
    </ul>
  `,
})
export class CoursePageComponent {
  courses = toSignal(inject(CourseApi).list(), { initialValue: [] });    // subscribes and unsubscribes for you
}
```

The alternative is the `async` pipe: `@for (c of courses$ | async; track c.id)`. Both manage the subscription automatically, so there are no memory leaks.

## Live search with debounce and switchMap

```ts
// src/app/live-search/live-search.component.ts
import { Component, inject } from '@angular/core';
import { FormControl, ReactiveFormsModule } from '@angular/forms';
import { toSignal } from '@angular/core/rxjs-interop';
import { debounceTime, distinctUntilChanged, filter, map, switchMap } from 'rxjs';
import { CourseApi } from '../course-api.service';

@Component({
  selector: 'app-live-search',
  standalone: true,
  imports: [ReactiveFormsModule],
  template: `
    <input [formControl]="query" placeholder="Search courses" />
    <ul>
      @for (c of results(); track c.id) { <li>{{ c.title }}</li> }
    </ul>
  `,
})
export class LiveSearchComponent {
  private api = inject(CourseApi);
  query = new FormControl('', { nonNullable: true });

  results = toSignal(
    this.query.valueChanges.pipe(
      map(q => q.trim()),
      debounceTime(300),                 // wait until typing pauses
      distinctUntilChanged(),            // ignore if unchanged
      filter(q => q.length >= 2),        // at least 2 characters
      switchMap(q => this.api.search(q)) // cancel the previous request when a new query arrives
    ),
    { initialValue: [] }
  );
}
```

Typing "java" fast sends **one** request, not four, and a slow response for "ja" can never overwrite the results for "java", because `switchMap` cancels it. This is the classic RxJS interview example.

## Interceptors: add the JWT to every request

```ts
// src/app/auth.interceptor.ts
import { HttpInterceptorFn } from '@angular/common/http';

export const authInterceptor: HttpInterceptorFn = (req, next) => {
  const token = localStorage.getItem('token');
  if (!token || !req.url.startsWith('/api')) return next(req);
  return next(req.clone({ setHeaders: { Authorization: `Bearer ${token}` } }));
};
```

Requests are immutable, so you `clone` them with changes. Interceptors are also the place for global error handling (e.g. redirect to login on 401) and loading indicators.

## Proxy to Spring Boot in development

```json
{
  "/api": { "target": "http://localhost:8080", "secure": false }
}
```

Save it as `proxy.conf.json` and run `ng serve --proxy-config proxy.conf.json` (or set `proxyConfig` in `angular.json`).

::tip Manual `subscribe()` calls in components are where memory leaks come from. Prefer `toSignal` or the `async` pipe. If you must subscribe, use `takeUntilDestroyed()` from `@angular/core/rxjs-interop` to unsubscribe automatically.

## Exercise

1. Show courses from the Spring Boot API with a loading message and an error message.
2. Add a "create course" form that POSTs and then refreshes the list.
3. Use `forkJoin` to load courses and the leaderboard in parallel.
4. Add an interceptor that redirects to `/login` when any response is 401.

::hint Hint for 4 | `return next(req).pipe(catchError(err => { if (err.status === 401) inject(Router).navigate(['/login']); return throwError(() => err); }));` (call `inject(Router)` at the top of the interceptor function).

## Interview corner

- **Observable vs Promise?** Observables are lazy, can emit many values, are cancellable and composable with operators; promises are eager and resolve once.
- **switchMap vs mergeMap vs concatMap?** switchMap cancels the previous inner stream; mergeMap runs all concurrently; concatMap queues them in order.
- **How do you avoid memory leaks?** async pipe, toSignal, takeUntilDestroyed, or unsubscribing in ngOnDestroy.
- **What is an HTTP interceptor?** A function that can inspect/modify every request and response (auth headers, errors, logging).

## Summary

- HttpClient returns Observables; wrap API calls in services.
- RxJS operators (`map`, `debounceTime`, `switchMap`, `catchError`) build data pipelines.
- `toSignal`/`async` handle subscriptions; interceptors add tokens and handle errors globally.

Next: change detection and performance.
