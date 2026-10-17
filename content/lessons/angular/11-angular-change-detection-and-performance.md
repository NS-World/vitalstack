---
title: Angular Change Detection and Performance
slug: angular-change-detection-and-performance
subject: angular
lesson: 11
difficulty: advanced
excerpt: Understand how Angular checks components for changes, compare Default and OnPush in an animation, use signals and immutable inputs, @for track, @defer blocks, lazy routes and the production build to keep apps fast.
---

Angular keeps the page in sync with your data through **change detection**. Knowing how it works lets you build apps that stay fast with hundreds of components, a common senior-level interview topic.

## How change detection works

1. Something happens: a click, a timer, an HTTP response.
2. Angular (traditionally via **zone.js**, which patches browser APIs) notices and runs change detection.
3. It walks the component tree from the **root**, re-evaluating each component's template bindings and updating the DOM where values changed.

With the **Default** strategy, every component is checked on every event. For a small app that's fine. For a dashboard with 500 components and a ticking clock, it's wasted work.

## Default vs OnPush, animated

Run both strategies through a timer tick and a new course being added:

::viz angular-change-detection {"strategy": "OnPush"}

With `ChangeDetectionStrategy.OnPush`, Angular checks a component only when:

- one of its **inputs** receives a **new reference**,
- an **event** happens inside it (or its children),
- a **signal** it reads in its template changes,
- the `async` pipe receives a new value, or it's explicitly marked (`markForCheck`).

```ts
// src/app/course-table/course-table.component.ts
import { ChangeDetectionStrategy, Component, input } from '@angular/core';

export interface Row { id: number; title: string; students: number; }

@Component({
  selector: 'app-course-table',
  standalone: true,
  changeDetection: ChangeDetectionStrategy.OnPush,
  template: `
    <table>
      @for (r of rows(); track r.id) {
        <tr><td>{{ r.title }}</td><td>{{ r.students }}</td></tr>
      }
    </table>
  `,
})
export class CourseTableComponent {
  rows = input.required<Row[]>();
}
```

## The immutability rule

With OnPush, **mutating** an input array does nothing visible, because the reference is the same:

```ts
// ❌ the OnPush table won't update
this.rows.push(newRow);

// ✅ a new array reference: the table updates
this.rows = [...this.rows, newRow];
// or with a signal:
this.rows.update(r => [...r, newRow]);
```

```ts
// src/app/dashboard/dashboard.component.ts
import { Component, signal } from '@angular/core';
import { CourseTableComponent, Row } from '../course-table/course-table.component';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CourseTableComponent],
  template: `
    <button (click)="addWrong()">Add (mutate)</button>
    <button (click)="addRight()">Add (new array)</button>
    <app-course-table [rows]="rows" />
  `,
})
export class DashboardComponent {
  rows: Row[] = [{ id: 1, title: 'Java', students: 1200 }];

  addWrong() { this.rows.push({ id: this.rows.length + 1, title: 'Mutated', students: 0 }); }
  addRight() { this.rows = [...this.rows, { id: this.rows.length + 1, title: 'Angular', students: 800 }]; }
}
```

Click "Add (mutate)": the table doesn't change. Click "Add (new array)": the table shows **both** new rows (the mutated one was in the array all along, just not rendered).

## Signals and the zoneless future

Signals tell Angular **exactly** which components need updating. With signals + OnPush, Angular can skip whole subtrees. Angular 18+ also offers **zoneless** change detection (`provideExperimentalZonelessChangeDetection()`, stable as `provideZonelessChangeDetection()` in newer versions), dropping zone.js entirely for smaller bundles and faster apps. Code written with signals and OnPush today is ready for it.

## Other performance tools

**`@for` track by id**: when a list refreshes from the server, tracking by id lets Angular keep existing DOM rows instead of rebuilding them all.

**`@defer` blocks** load parts of a page lazily:

```html
@defer (on viewport) {
  <app-reviews [courseId]="id()" />          <!-- its code loads when scrolled into view -->
} @placeholder {
  <p>Reviews</p>
} @loading (minimum 300ms) {
  <p>Loading reviews…</p>
}
```

Triggers: `on idle` (default), `on viewport`, `on interaction`, `on hover`, `on timer(2s)`, `when condition`.

**Lazy routes** (`loadComponent`/`loadChildren`) keep the first download small.

**Avoid heavy template calls**: `{{ total() }}` with a computed signal is cached; `{{ calculateTotal() }}` as a plain method runs on every check.

**Pure pipes** cache by input.

**Production builds**: `ng build` enables ahead-of-time compilation, tree shaking, minification and budgets (warnings when bundles grow too big, configured in `angular.json`).

**Images**: `NgOptimizedImage` (`<img ngSrc="..." width height priority>`) adds lazy loading and size hints.

::tip Make `OnPush` your default (`ng g c name --change-detection OnPush`, or set it in `angular.json` schematics) and use signals for state. You'll rarely think about change detection again, and when something doesn't update, the first question is "did I mutate instead of creating a new reference?".

## Measuring

- **Angular DevTools** (browser extension): component tree, change detection profiler showing how long each component's check took.
- Chrome **Lighthouse** and the **Performance** tab for load time and long tasks.

## Exercise

1. Build the dashboard example and observe the mutate vs new-array behaviour.
2. Convert the `rows` field in `DashboardComponent` to a signal and use `update`.
3. Wrap a heavy chart component in `@defer (on viewport)` and check the Network tab when scrolling.
4. Run Angular DevTools' profiler on your app and find the component that takes longest to check.

::hint Hint for 2 | `rows = signal<Row[]>([...])`, pass `[rows]="rows()"`, and `addRight() { this.rows.update(r => [...r, newRow]); }`.

## Interview corner

- **What is change detection?** The process of syncing the DOM with component state by re-evaluating template bindings.
- **Default vs OnPush?** Default checks every component on each event; OnPush checks only on new input references, internal events, signal changes, async pipe emissions or manual marking.
- **Why must inputs be immutable with OnPush?** OnPush compares input references; mutation keeps the same reference, so the change is missed.
- **What is @defer?** A template block that lazy-loads its components based on triggers like viewport or idle.

## Summary

- Angular checks components after events; OnPush limits checks to real changes.
- Use immutable updates and signals; track lists by id.
- `@defer`, lazy routes and production builds keep apps fast.

Next: build a complete Angular project.
