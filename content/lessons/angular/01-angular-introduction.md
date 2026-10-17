---
title: Angular Introduction
slug: angular-introduction
subject: angular
lesson: 1
difficulty: beginner
excerpt: What Angular is, why banks, enterprises and Indian IT companies choose it, how it compares with React, and the building blocks you'll learn - components, templates, signals, services, routing, forms and RxJS.
---

**Angular** is a complete front-end **framework** from Google for building large web applications. Where React gives you a UI library and lets you choose the rest, Angular ships everything in one box: components, routing, forms, HTTP, testing and a powerful CLI. That consistency is why enterprises, banks, insurance companies and big IT services firms (TCS, Infosys, Wipro, Accenture, Capgemini) use it heavily.

## What you build with Angular

- Internet banking and trading dashboards
- Admin panels and ERP front ends
- Insurance, HR and healthcare portals
- Google products (Google Cloud Console, Firebase console) and many Microsoft, Deutsche Bank and Forbes apps

## The building blocks

```text
           ┌──────────────── Angular application ────────────────┐
 Browser ──│ Router  ──▶  Components (template + class + styles)  │
           │                 │ data binding, @if/@for, pipes       │
           │                 ▼                                    │
           │              Services (business logic, state)        │
           │                 │ dependency injection               │
           │                 ▼                                    │
           │              HttpClient + RxJS ──▶ REST API (Spring Boot)
           └──────────────────────────────────────────────────────┘
```

| Concept | What it is |
|---|---|
| **Component** | a piece of UI: a TypeScript class + an HTML template + CSS |
| **Template syntax** | `{{ }}`, `[property]`, `(event)`, `@if`, `@for` |
| **Signals** | reactive state that updates the UI precisely when it changes |
| **Service** | a class for logic and data shared across components |
| **Dependency injection** | Angular creates services and hands them to the classes that need them |
| **Router** | maps URLs to components |
| **Forms** | reactive and template-driven forms with validation |
| **HttpClient + RxJS** | calling APIs with Observables |
| **CLI** | `ng new`, `ng generate`, `ng serve`, `ng build`, `ng test` |

## A first look

```ts
// src/app/hello.component.ts
import { Component, signal } from '@angular/core';

@Component({
  selector: 'app-hello',
  standalone: true,
  template: `
    <h1>Namaste, {{ name() }}!</h1>
    <button (click)="likes.set(likes() + 1)">👍 {{ likes() }}</button>
    @if (likes() >= 10) {
      <span class="badge">Popular!</span>
    }
  `,
})
export class HelloComponent {
  name = signal('Asha');
  likes = signal(0);
}
```

The template describes the UI; when `likes` changes, Angular updates the button and shows the badge automatically.

## Angular vs React

| | Angular | React |
|---|---|---|
| Type | full framework | UI library + ecosystem |
| Language | TypeScript (required) | JavaScript or TypeScript |
| Templates | HTML with Angular syntax | JSX |
| Routing, forms, HTTP | built in | third-party libraries |
| State | signals, services, RxJS | hooks, context, libraries |
| Architecture | opinionated: "the Angular way" | flexible: many styles |
| Learning curve | steeper start, consistent later | gentle start, many choices later |

Both are excellent. Angular shines in large teams and long-lived enterprise projects, where every project looking the same is a big advantage. Learning both makes you very employable.

## Modern Angular (v17+)

Angular changed a lot recently. If you see old tutorials, know the difference:

| Old style | Modern style (this course) |
|---|---|
| `NgModule`s everywhere | **standalone** components |
| `*ngIf`, `*ngFor` | built-in control flow `@if`, `@for` |
| state in plain fields + zone.js | **signals** (`signal`, `computed`) |
| `constructor(private http: HttpClient)` | also `inject(HttpClient)` |
| AngularJS (1.x, 2010) | a completely different, retired framework; ignore it |

## Prerequisites

- HTML and CSS
- JavaScript (ES6+): classes, arrow functions, modules, promises
- **TypeScript** basics: types, interfaces, classes. You'll pick up the rest as you go, and TypeScript also helps in React and Node.

::tip Angular releases a major version every six months, but upgrades are smooth thanks to `ng update`. Learn the concepts in this course and you can follow any recent version.

## Exercise

1. Install Node.js 20+ if you haven't already (`node -v`).
2. List three apps you use that look like "dashboards with lots of forms and tables": good Angular candidates.
3. Revise TypeScript interfaces and classes.

::hint Hint for 3 | `interface Course { id: number; title: string; price?: number }` declares a shape; `price?` means optional. Classes can `implements Course`.

## Interview corner

- **What is Angular?** A TypeScript-based front-end framework by Google with components, DI, routing, forms, HTTP and tooling built in.
- **Angular vs AngularJS?** AngularJS (1.x) was a JavaScript MVC framework; Angular (2+) is a complete rewrite in TypeScript with components.
- **What are standalone components?** Components that declare their own dependencies without NgModules, the default since Angular 17.
- **What are signals?** Reactive primitives holding values; Angular tracks where they're read and updates only what depends on them.

## Summary

- Angular is a complete, opinionated framework written in TypeScript.
- Components, templates, signals, services with DI, router, forms and HttpClient are its core.
- Modern Angular uses standalone components, `@if`/`@for` and signals.

Next: create a project with the Angular CLI.
