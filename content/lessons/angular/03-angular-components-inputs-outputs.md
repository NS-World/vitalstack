---
title: Angular Components, Inputs and Outputs
slug: angular-components-inputs-outputs
subject: angular
lesson: 3
difficulty: beginner
excerpt: Build reusable Angular components, pass data in with input(), send events out with output(), project content with ng-content, and use lifecycle hooks like ngOnInit and ngOnDestroy.
---

Like React components, Angular components are reusable building blocks. A parent passes data **in** through **inputs** and listens to events coming **out** through **outputs**.

## A component with inputs

```ts
// src/app/course-card/course-card.component.ts
import { Component, computed, input, output } from '@angular/core';

export interface Course {
  id: number;
  title: string;
  level: 'Beginner' | 'Intermediate' | 'Advanced';
  price: number;
  lessons: number;
}

@Component({
  selector: 'app-course-card',
  standalone: true,
  template: `
    <article class="card">
      <h3>{{ course().title }} @if (isNew()) { <span class="badge">New</span> }</h3>
      <p>{{ course().level }} · {{ course().lessons }} lessons</p>
      <strong>{{ priceLabel() }}</strong>
      <button (click)="enroll.emit(course())">Enroll</button>
    </article>
  `,
})
export class CourseCardComponent {
  course = input.required<Course>();          // must be provided
  isNew = input(false);                       // optional, default false
  enroll = output<Course>();                  // an event the parent can listen to

  priceLabel = computed(() => (this.course().price === 0 ? 'Free' : `₹${this.course().price}`));
}
```

- `input.required<T>()` and `input(default)` create **signal inputs**: read them by calling them, `course()`.
- `output<T>()` creates an event; call `.emit(value)` to send it.
- `computed()` derives a value that updates when its inputs change.

Older code uses decorators, which work the same way and you'll see them everywhere:

```ts
@Input({ required: true }) course!: Course;
@Input() isNew = false;
@Output() enroll = new EventEmitter<Course>();
```

## Using it from a parent

```ts
// src/app/catalog/catalog.component.ts
import { Component, signal } from '@angular/core';
import { Course, CourseCardComponent } from '../course-card/course-card.component';

@Component({
  selector: 'app-catalog',
  standalone: true,
  imports: [CourseCardComponent],
  template: `
    @for (c of courses; track c.id) {
      <app-course-card [course]="c" [isNew]="c.id === 3" (enroll)="onEnroll($event)" />
    }
    @if (message()) {
      <p role="status">{{ message() }}</p>
    }
  `,
})
export class CatalogComponent {
  courses: Course[] = [
    { id: 1, title: 'Java Basics', level: 'Beginner', price: 499, lessons: 16 },
    { id: 2, title: 'HTML', level: 'Beginner', price: 0, lessons: 10 },
    { id: 3, title: 'Angular', level: 'Intermediate', price: 699, lessons: 12 },
  ];
  message = signal('');

  onEnroll(course: Course) {
    this.message.set(`Enrolled in ${course.title}!`);
  }
}
```

- `[course]="c"` binds an **input** (square brackets: data into the child).
- `(enroll)="onEnroll($event)"` listens to an **output** (round brackets: events out of the child); `$event` is the emitted value.

## Content projection with ng-content

Like React's `children`, `<ng-content>` places whatever the parent puts between the tags:

```ts
// src/app/panel/panel.component.ts
import { Component, input } from '@angular/core';

@Component({
  selector: 'app-panel',
  standalone: true,
  template: `
    <section class="panel">
      <header><h2>{{ title() }}</h2><ng-content select="[panel-actions]" /></header>
      <div class="body"><ng-content /></div>
    </section>
  `,
})
export class PanelComponent {
  title = input('');
}
```

```html
<app-panel title="Your progress">
  <button panel-actions>Reset</button>     <!-- goes to the select="[panel-actions]" slot -->
  <p>Java: 60% complete</p>                 <!-- goes to the default slot -->
</app-panel>
```

## Lifecycle hooks

| Hook | Runs |
|---|---|
| `constructor` | when the class is created (inject dependencies here; inputs aren't set yet) |
| `ngOnInit` | once, after the first inputs are set: load data here |
| `ngOnChanges(changes)` | when decorator `@Input`s change |
| `ngAfterViewInit` | after the component's view (and children) is rendered: access DOM via `viewChild` |
| `ngOnDestroy` | before the component is removed: clean up timers and subscriptions |

```ts
export class ClockComponent implements OnInit, OnDestroy {
  now = signal(new Date());
  private timer?: ReturnType<typeof setInterval>;

  ngOnInit() { this.timer = setInterval(() => this.now.set(new Date()), 1000); }
  ngOnDestroy() { clearInterval(this.timer); }
}
```

With signal inputs, most "react to input changes" logic becomes `computed()` or `effect()` instead of `ngOnChanges`.

## Component styles are scoped

Styles in a component's `styles`/`styleUrl` apply **only** to that component's template (Angular adds unique attributes behind the scenes). `.card` in `CourseCardComponent` won't leak into other components. Global styles go in `src/styles.css`.

::tip Split components into **presentational** ("dumb") components that only use inputs/outputs (like `CourseCard`) and **container** ("smart") components that talk to services (like `Catalog`). Presentational components are easy to reuse and test.

## Exercise

1. Create a `UserBadgeComponent` with `name` (required) and `role` (default "Student") inputs.
2. Add a `remove` output to `CourseCardComponent` and remove the course from the parent's list when it fires.
3. Build a `ModalComponent` with a title input, projected content and a `closed` output.
4. Add a `ClockComponent` and check (with `console.log`) that `ngOnDestroy` runs when it's hidden with `@if`.

::hint Hint for 2 | Make `courses` a signal in the parent: `courses = signal<Course[]>([...])` and `onRemove(c: Course) { this.courses.update(list => list.filter(x => x.id !== c.id)); }`.

## Interview corner

- **@Input vs @Output?** Inputs pass data from parent to child; outputs emit events from child to parent.
- **What is content projection?** Inserting parent-provided content into a child's template with `<ng-content>`.
- **constructor vs ngOnInit?** Constructor for DI and simple setup; ngOnInit after inputs are initialised, for data loading.
- **What are signal inputs?** `input()`/`input.required()` that expose inputs as read-only signals.

## Summary

- Inputs (`input()`, `@Input`) bring data in; outputs (`output()`, `@Output`) send events out.
- `<ng-content>` projects parent content; component styles are scoped.
- Lifecycle hooks: init in `ngOnInit`, clean up in `ngOnDestroy`.

Next: the four kinds of data binding, animated.
