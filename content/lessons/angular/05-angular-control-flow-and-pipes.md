---
title: Angular Control Flow and Pipes
slug: angular-control-flow-and-pipes
subject: angular
lesson: 5
difficulty: beginner
excerpt: Show and repeat content with @if, @else, @for with track and @empty, and @switch, format values with built-in pipes (date, currency, number, uppercase), and write your own custom pipe.
---

Templates need logic: show a spinner while loading, repeat a card for each course, format prices and dates. Angular's **built-in control flow** and **pipes** handle this cleanly.

## @if / @else

```html
@if (loading()) {
  <p>Loading…</p>
} @else if (error()) {
  <p role="alert">{{ error() }}</p>
} @else {
  <app-course-list [courses]="courses()" />
}
```

Store the result of an expression with `as`:

```html
@if (user(); as u) {
  <p>Welcome back, {{ u.name }}</p>
}
```

## @for with track and @empty

```html
@for (course of courses(); track course.id; let i = $index, last = $last) {
  <li>{{ i + 1 }}. {{ course.title }} @if (!last) { <hr /> }</li>
} @empty {
  <li>No courses match your search.</li>
}
```

- `track` is **required**. It tells Angular how to identify items (like React's `key`), so it can move DOM nodes instead of recreating them. Track by a unique id.
- Context variables: `$index`, `$first`, `$last`, `$even`, `$odd`, `$count`.
- `@empty` renders when the list is empty.

## @switch

```html
@switch (course.level) {
  @case ('Beginner') { <span class="badge green">Beginner</span> }
  @case ('Advanced') { <span class="badge red">Advanced</span> }
  @default { <span class="badge">{{ course.level }}</span> }
}
```

## Pipes: format values in templates

A **pipe** transforms a value for display: `{{ value | pipeName:arg1:arg2 }}`.

```ts
// src/app/pipes-demo/pipes-demo.component.ts
import { Component } from '@angular/core';
import { CurrencyPipe, DatePipe, DecimalPipe, PercentPipe, UpperCasePipe, TitleCasePipe, SlicePipe, JsonPipe } from '@angular/common';

@Component({
  selector: 'app-pipes-demo',
  standalone: true,
  imports: [CurrencyPipe, DatePipe, DecimalPipe, PercentPipe, UpperCasePipe, TitleCasePipe, SlicePipe, JsonPipe],
  template: `
    <p>{{ price | currency:'INR' }}</p>                      <!-- ₹1,499.50 -->
    <p>{{ price | currency:'INR':'symbol':'1.0-0' }}</p>     <!-- ₹1,500 -->
    <p>{{ joined | date:'d MMM y' }}</p>                     <!-- 17 Oct 2026 -->
    <p>{{ joined | date:'shortTime' }}</p>                   <!-- 9:30 AM -->
    <p>{{ rating | number:'1.1-1' }}</p>                     <!-- 4.7 -->
    <p>{{ progress | percent }}</p>                          <!-- 63% -->
    <p>{{ name | uppercase }} / {{ 'spring boot basics' | titlecase }}</p>
    <p>{{ description | slice:0:20 }}…</p>
    <pre>{{ settings | json }}</pre>                          <!-- debugging -->
  `,
})
export class PipesDemoComponent {
  price = 1499.5;
  joined = new Date(2026, 9, 17, 9, 30);
  rating = 4.666;
  progress = 0.63;
  name = 'asha';
  description = 'Learn Angular from scratch with projects';
  settings = { theme: 'dark', lang: 'en' };
}
```

Import each pipe you use in the component's `imports` (or `CommonModule` for all of them).

The `async` pipe subscribes to an Observable and shows its latest value (lesson 10): `{{ courses$ | async }}`.

## A custom pipe

```ts
// src/app/duration.pipe.ts
import { Pipe, PipeTransform } from '@angular/core';

@Pipe({ name: 'duration', standalone: true })
export class DurationPipe implements PipeTransform {
  transform(minutes: number | null | undefined, style: 'short' | 'long' = 'short'): string {
    if (minutes == null || minutes < 0) return '';
    const h = Math.floor(minutes / 60);
    const m = minutes % 60;
    if (style === 'long') {
      return [h && `${h} hour${h > 1 ? 's' : ''}`, m && `${m} minute${m > 1 ? 's' : ''}`].filter(Boolean).join(' ') || '0 minutes';
    }
    return h ? `${h}h ${m}m` : `${m}m`;
  }
}
```

```html
<p>{{ 135 | duration }}</p>          <!-- 2h 15m -->
<p>{{ 135 | duration:'long' }}</p>   <!-- 2 hours 15 minutes -->
<p>{{ 45 | duration }}</p>           <!-- 45m -->
```

Pipes are **pure** by default: Angular re-runs them only when the input value changes, which makes them faster than calling methods in templates.

::tip Old Angular code uses structural directives `*ngIf`, `*ngFor` and `[ngSwitch]`. They still work, but `@if`/`@for`/`@switch` are faster, need no imports, and `@for`'s required `track` prevents a classic performance mistake. Migrate with `ng generate @angular/core:control-flow`.

## Common mistakes

- Forgetting `track` in `@for` (compile error) or tracking by `$index` in lists that reorder.
- Using pipes without importing them: "No pipe found with name 'currency'".
- Doing filtering/sorting in custom pipes that run on every change: use `computed` signals instead.

## Exercise

1. Show a list of courses with numbered items, "No courses" when empty, and a different badge per level with `@switch`.
2. Show each course's price with the `currency` pipe and its release date with `date:'mediumDate'`.
3. Write a `truncate` pipe: `{{ text | truncate:30 }}` adds "…" when longer.
4. Write a `timeAgo` pipe that turns a Date into "5 minutes ago" / "2 days ago".

::hint Hint for 3 | `transform(value: string, max = 50) { return value.length > max ? value.slice(0, max).trimEnd() + '…' : value; }`

## Interview corner

- **What does track do in @for?** Identifies items so Angular can reuse DOM nodes when the list changes.
- **What is a pipe?** A template function that transforms values for display.
- **Pure vs impure pipes?** Pure pipes run only when inputs change (by reference); impure pipes run on every change detection.
- **@if vs *ngIf?** Same purpose; @if is the built-in control flow syntax (Angular 17+), faster and needs no import.

## Summary

- `@if`/`@else`, `@for` (with `track` and `@empty`) and `@switch` control templates.
- Pipes format values: `currency`, `date`, `number`, `percent`, `uppercase`, `async`.
- Custom pipes implement `PipeTransform`.

Next: signals, Angular's reactive state.
