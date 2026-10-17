---
title: Angular Routing - Pages, Parameters, Guards and Lazy Loading
slug: angular-routing
subject: angular
lesson: 8
difficulty: intermediate
excerpt: Map URLs to components with the Angular Router - routerLink, route parameters with input binding, query params, child routes, functional guards for login, lazy loading pages and a 404 route.
---

The **Angular Router** turns your single-page app into something that feels like many pages: each URL shows a component, the back button works, and links can be shared.

## Defining routes

```ts
// src/app/app.routes.ts
import { Routes } from '@angular/router';
import { HomeComponent } from './pages/home.component';
import { CoursesComponent } from './pages/courses.component';
import { CourseDetailComponent } from './pages/course-detail.component';
import { NotFoundComponent } from './pages/not-found.component';
import { authGuard } from './auth.guard';

export const routes: Routes = [
  { path: '', component: HomeComponent, title: 'VitalStack' },
  { path: 'courses', component: CoursesComponent, title: 'Courses' },
  { path: 'courses/:id', component: CourseDetailComponent },
  {
    path: 'dashboard',
    canActivate: [authGuard],                                       // only for logged-in users
    loadComponent: () => import('./pages/dashboard.component').then(m => m.DashboardComponent),   // lazy loaded
  },
  { path: 'home', redirectTo: '', pathMatch: 'full' },
  { path: '**', component: NotFoundComponent, title: 'Page not found' },   // must be last
];
```

Enable the router and **component input binding** (route params arrive as inputs):

```ts
// src/app/app.config.ts
import { ApplicationConfig } from '@angular/core';
import { provideRouter, withComponentInputBinding } from '@angular/router';
import { routes } from './app.routes';

export const appConfig: ApplicationConfig = {
  providers: [provideRouter(routes, withComponentInputBinding())],
};
```

## Navigation links and the outlet

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
      <a routerLink="/" routerLinkActive="active" [routerLinkActiveOptions]="{ exact: true }">Home</a>
      <a routerLink="/courses" routerLinkActive="active">Courses</a>
      <a routerLink="/dashboard" routerLinkActive="active">Dashboard</a>
    </nav>
    <router-outlet />
  `,
})
export class AppComponent {}
```

`routerLink` navigates **without reloading**; `routerLinkActive` adds a CSS class on the current page; `<router-outlet>` is where the matched component appears.

## Pages

```ts
// src/app/pages/home.component.ts
import { Component } from '@angular/core';

@Component({ selector: 'app-home', standalone: true, template: `<h1>Learn to code for free</h1>` })
export class HomeComponent {}
```

```ts
// src/app/pages/not-found.component.ts
import { Component } from '@angular/core';

@Component({ selector: 'app-not-found', standalone: true, template: `<h1>404: Page not found</h1>` })
export class NotFoundComponent {}
```

```ts
// src/app/pages/courses.component.ts
import { Component, computed, input } from '@angular/core';
import { RouterLink } from '@angular/router';

export const COURSES = [
  { id: 1, title: 'Java Basics', level: 'beginner' },
  { id: 2, title: 'Angular', level: 'intermediate' },
  { id: 3, title: 'SQL', level: 'beginner' },
];

@Component({
  selector: 'app-courses',
  standalone: true,
  imports: [RouterLink],
  template: `
    <a routerLink="/courses" [queryParams]="{ level: 'beginner' }">Beginner only</a>
    <ul>
      @for (c of shown(); track c.id) {
        <li><a [routerLink]="['/courses', c.id]">{{ c.title }}</a></li>
      }
    </ul>
  `,
})
export class CoursesComponent {
  level = input<string>();                          // from ?level=beginner (input binding)
  shown = computed(() => COURSES.filter(c => !this.level() || c.level === this.level()));
}
```

```ts
// src/app/pages/course-detail.component.ts
import { Component, computed, inject, input } from '@angular/core';
import { Router } from '@angular/router';
import { COURSES } from './courses.component';

@Component({
  selector: 'app-course-detail',
  standalone: true,
  template: `
    @if (course(); as c) {
      <h1>{{ c.title }}</h1>
      <button (click)="back()">All courses</button>
    } @else {
      <p>Course not found.</p>
    }
  `,
})
export class CourseDetailComponent {
  id = input.required<string>();                    // from /courses/:id
  private router = inject(Router);
  course = computed(() => COURSES.find(c => c.id === Number(this.id())));

  back() { this.router.navigate(['/courses']); }    // navigate in code
}
```

Without input binding, read parameters with `inject(ActivatedRoute).paramMap` / `queryParamMap` (Observables).

## Guards: protecting routes

A **functional guard** decides whether navigation may continue:

```ts
// src/app/auth.guard.ts
import { inject } from '@angular/core';
import { CanActivateFn, Router } from '@angular/router';
import { AuthService } from './auth.service';

export const authGuard: CanActivateFn = (route, state) => {
  const auth = inject(AuthService);
  if (auth.isLoggedIn()) return true;
  return inject(Router).createUrlTree(['/login'], { queryParams: { returnUrl: state.url } });   // redirect
};
```

```ts
// src/app/auth.service.ts
import { Injectable, signal } from '@angular/core';

@Injectable({ providedIn: 'root' })
export class AuthService {
  private readonly token = signal<string | null>(localStorage.getItem('token'));
  isLoggedIn() { return this.token() !== null; }
  login(token: string) { localStorage.setItem('token', token); this.token.set(token); }
  logout() { localStorage.removeItem('token'); this.token.set(null); }
}
```

```ts
// src/app/pages/dashboard.component.ts
import { Component } from '@angular/core';

@Component({ selector: 'app-dashboard', standalone: true, template: `<h1>Your dashboard</h1>` })
export class DashboardComponent {}
```

Other guard types: `canMatch` (pick routes / prevent lazy loading), `canDeactivate` ("You have unsaved changes"), and `resolve` (load data before showing a page).

::tip Guards improve UX; they are **not** security. The API (Spring Security) must check permissions too, because anyone can call your API directly.

## Lazy loading

`loadComponent` (or `loadChildren` for a group of routes) splits that page into a separate JavaScript file, downloaded only when the user first visits it. The initial download stays small, which matters on mobile networks.

## Child routes

```ts
{
  path: 'account',
  component: AccountLayoutComponent,          // has its own <router-outlet>
  children: [
    { path: '', redirectTo: 'profile', pathMatch: 'full' },
    { path: 'profile', component: ProfileComponent },
    { path: 'certificates', component: CertificatesComponent },
  ],
}
```

## Deploying

Like React, the server must send `index.html` for deep links like `/courses/2` (SPA fallback rewrite on Netlify, Vercel, Firebase Hosting, Nginx or Apache).

## Exercise

1. Add an About page and a nav link.
2. Add a `level` select on the courses page that navigates with query params.
3. Build a login page that calls `auth.login('demo')` and redirects to `returnUrl`.
4. Lazy load the courses page with `loadComponent` and check the extra JS file in DevTools' Network tab.

::hint Hint for 3 | `const returnUrl = inject(ActivatedRoute).snapshot.queryParamMap.get('returnUrl') ?? '/'; this.router.navigateByUrl(returnUrl);`

## Interview corner

- **What is lazy loading?** Loading a route's code only when it's first navigated to (`loadComponent`/`loadChildren`).
- **What are route guards?** Functions like `canActivate`, `canMatch`, `canDeactivate` that allow, block or redirect navigation.
- **How do you read route parameters?** Component input binding (`withComponentInputBinding`), or ActivatedRoute's `paramMap`/`snapshot`.
- **routerLink vs href?** routerLink navigates inside the SPA without a full page reload.

## Summary

- Routes map paths to components; `<router-outlet>` displays them; `routerLink` navigates.
- Route and query params arrive as inputs with `withComponentInputBinding`.
- Functional guards protect pages; `loadComponent` lazy-loads them; `**` catches 404s.

Next: forms with validation.
