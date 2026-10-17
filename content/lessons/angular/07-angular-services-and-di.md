---
title: Angular Services and Dependency Injection
slug: angular-services-and-di
subject: angular
lesson: 7
difficulty: intermediate
excerpt: Move logic and shared data into services, see how Angular's injector hierarchy decides which instance you get in an animation, use providedIn root vs component providers, inject(), and injection tokens for configuration.
---

Components should focus on the UI. Business logic, data access and shared state belong in **services**. Angular's **dependency injection (DI)** creates services and hands them to whoever needs them, the same idea as Spring's IoC container.

## A service

```ts
// src/app/course.service.ts
import { Injectable, computed, signal } from '@angular/core';

export interface Course { id: number; title: string; level: string; price: number; }

@Injectable({ providedIn: 'root' })          // one shared instance for the whole app
export class CourseService {
  private readonly courses = signal<Course[]>([
    { id: 1, title: 'Java Basics', level: 'Beginner', price: 499 },
    { id: 2, title: 'Angular', level: 'Intermediate', price: 699 },
    { id: 3, title: 'SQL', level: 'Beginner', price: 299 },
  ]);

  readonly all = this.courses.asReadonly();
  readonly beginnerCount = computed(() => this.courses().filter(c => c.level === 'Beginner').length);

  byId(id: number) { return this.courses().find(c => c.id === id); }

  add(title: string, level: string, price: number) {
    const id = Math.max(0, ...this.courses().map(c => c.id)) + 1;
    this.courses.update(list => [...list, { id, title, level, price }]);
    return id;
  }
}
```

## Injecting it

```ts
// src/app/course-list/course-list.component.ts
import { Component, inject } from '@angular/core';
import { CourseService } from '../course.service';

@Component({
  selector: 'app-course-list',
  standalone: true,
  template: `
    <p>{{ courses.all().length }} courses ({{ courses.beginnerCount() }} for beginners)</p>
    <ul>
      @for (c of courses.all(); track c.id) { <li>{{ c.title }}</li> }
    </ul>
    <button (click)="courses.add('Spring Boot', 'Intermediate', 799)">Add Spring Boot</button>
  `,
})
export class CourseListComponent {
  courses = inject(CourseService);           // modern style
  // or: constructor(private courses: CourseService) {}   ← constructor injection (classic style)
}
```

You never write `new CourseService()`. Angular creates it the first time someone asks for it, and gives everyone **the same instance**. Two components showing courses stay in sync automatically.

## The injector hierarchy, animated

Angular has a tree of **injectors** mirroring your component tree. `inject(X)` starts at the asking component and walks **up** until some injector can provide `X`. Switch between a root service and a component-level service:

::viz angular-di

## Where a service is provided

| How | Instances | Use for |
|---|---|---|
| `@Injectable({ providedIn: 'root' })` | one for the whole app (and tree-shaken if unused) | most services: API clients, stores, auth |
| `providers: [X]` on a component | one per component instance (and its children) | state that belongs to one widget (a wizard, an editor) |
| route `providers: [X]` | one per lazy-loaded route section | feature-level state |
| `providers` in `app.config.ts` | one for the app | configuration, interceptors, third-party setup |

```ts
// src/app/editor/editor.component.ts
import { Component, Injectable, inject, signal } from '@angular/core';

@Injectable()                                  // no providedIn: must be provided somewhere
export class DraftService {
  text = signal('');
}

@Component({
  selector: 'app-editor',
  standalone: true,
  providers: [DraftService],                   // each <app-editor> gets its OWN DraftService
  template: `<textarea [value]="draft.text()" (input)="draft.text.set($any($event.target).value)"></textarea>
             <small>{{ draft.text().length }} chars</small>`,
})
export class EditorComponent {
  draft = inject(DraftService);
}
```

Two `<app-editor>` components on a page now have independent drafts.

## Injection tokens for configuration

Inject values that aren't classes (API URLs, feature flags) with an `InjectionToken`:

```ts
// src/app/tokens.ts
import { InjectionToken } from '@angular/core';

export const API_URL = new InjectionToken<string>('API_URL', {
  providedIn: 'root',
  factory: () => '/api',                        // default value
});
```

```ts
const apiUrl = inject(API_URL);                // '/api', or whatever app.config.ts provides:
// providers: [{ provide: API_URL, useValue: 'https://api.vitalstack.co.in' }]
```

Provider recipes: `useClass` (swap implementations, e.g. a mock), `useValue`, `useFactory`, `useExisting`.

## Why DI matters for testing

In tests, replace real services with fakes:

```ts
TestBed.configureTestingModule({
  providers: [{ provide: CourseService, useValue: { all: () => [], beginnerCount: () => 0 } }],
});
```

Your component code doesn't change at all. Same benefit as constructor injection in Spring.

::tip `inject()` only works in an **injection context**: field initialisers, constructors, and functions called from them (like route guards and interceptors). Calling it inside a click handler or `setTimeout` throws "inject() must be called from an injection context".

## Exercise

1. Create an `AuthService` with a `user` signal and `login(name)` / `logout()`, and show the user's name in a header component.
2. Add a `ToastService` that holds a list of messages, with a `ToastsComponent` that displays and auto-removes them.
3. Make a `CounterService` without `providedIn`, provide it in a component, and show that two instances of that component count independently.
4. Create a `FEATURE_FLAGS` token and hide a "Beta" menu item when the flag is off.

::hint Hint for 2 | `messages = signal<string[]>([]); show(msg: string) { this.messages.update(m => [...m, msg]); setTimeout(() => this.messages.update(m => m.slice(1)), 3000); }`

## Interview corner

- **What is dependency injection in Angular?** A system where injectors create and supply dependencies, declared via providers and requested with `inject()` or constructors.
- **What does providedIn: 'root' mean?** A single app-wide instance from the root injector, tree-shakable if unused.
- **How do you get a separate service instance per component?** List it in the component's `providers`.
- **What is an InjectionToken?** A key for injecting non-class values like configuration.

## Summary

- Services hold logic and shared state; `@Injectable({ providedIn: 'root' })` makes app-wide singletons.
- `inject()` walks up the injector tree; component `providers` create per-component instances.
- Injection tokens provide configuration; DI makes testing easy.

Next: routing between pages.
