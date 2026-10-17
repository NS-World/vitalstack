---
title: Angular Data Binding - Interpolation, Property, Event, Two-Way
slug: angular-data-binding
subject: angular
lesson: 4
difficulty: beginner
excerpt: Watch the four kinds of Angular data binding in an animation, then use interpolation, property binding, event binding, two-way binding with ngModel and model(), class and style bindings, and template reference variables.
---

**Data binding** connects your component class (data) with the template (what users see). Angular has four kinds, and their brackets tell you which way data flows.

## The four bindings, animated

Pick a binding type and watch where the data moves:

::viz data-binding {"kind": "all four"}

| Syntax | Name | Direction |
|---|---|---|
| `{{ value }}` | interpolation | class → view (text) |
| `[property]="value"` | property binding | class → view (DOM property / input) |
| `(event)="handler()"` | event binding | view → class |
| `[(ngModel)]="value"` | two-way binding | both ways |

Memory trick: **[ ]** brings data **in** to the element, **( )** sends events **out**, and **[( )]** is "banana in a box": both.

## All four in one component

```ts
// src/app/binding-demo/binding-demo.component.ts
import { Component, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-binding-demo',
  standalone: true,
  imports: [FormsModule],                               // needed for ngModel
  template: `
    <h2>{{ title }}</h2>                                 <!-- interpolation -->
    <img [src]="logoUrl" [alt]="title + ' logo'" width="48" />   <!-- property binding -->
    <button (click)="like()" [disabled]="likes() >= 5">👍 {{ likes() }}</button>   <!-- event + property -->
    <input [(ngModel)]="name" placeholder="Your name" />  <!-- two-way -->
    <p>Hello {{ name || 'stranger' }}</p>
  `,
})
export class BindingDemoComponent {
  title = 'Java Basics';
  logoUrl = '/assets/java.svg';
  likes = signal(0);
  name = '';

  like() { this.likes.update(n => n + 1); }
}
```

## Property binding vs attribute binding

Property binding sets **DOM properties** (`[value]`, `[disabled]`, `[src]`, `[hidden]`). Some HTML attributes have no matching property (ARIA, `colspan`), so use `attr.`:

```html
<td [attr.colspan]="span">…</td>
<button [attr.aria-label]="'Remove ' + course.title">×</button>
```

## Class and style bindings

```html
<li [class.done]="task.done" [class.urgent]="task.priority === 'high'">{{ task.title }}</li>
<div [class]="{ card: true, selected: isSelected, disabled: !enabled }"></div>
<div [style.width.%]="progress">{{ progress }}%</div>
<p [style.color]="balance < 0 ? 'crimson' : 'green'">₹{{ balance }}</p>
```

## Event binding details

```html
<input (input)="query = $any($event.target).value" />     <!-- $event is the DOM event -->
<input (keyup.enter)="search()" />                        <!-- key filters -->
<form (ngSubmit)="save()">…</form>                        <!-- forms module submit -->
<div (mouseenter)="hover = true" (mouseleave)="hover = false"></div>
```

## Template reference variables

`#name` gives a template element a name you can use elsewhere in the template:

```html
<input #search placeholder="Search courses" />
<button (click)="find(search.value); search.value = ''">Search</button>
```

## Two-way binding on your own components: model()

```ts
// src/app/rating/rating.component.ts
import { Component, model } from '@angular/core';

@Component({
  selector: 'app-rating',
  standalone: true,
  template: `
    @for (star of [1, 2, 3, 4, 5]; track star) {
      <button type="button" (click)="value.set(star)" [attr.aria-label]="star + ' stars'">
        {{ star <= value() ? '★' : '☆' }}
      </button>
    }
  `,
})
export class RatingComponent {
  value = model(0);          // a two-way bindable signal
}
```

```html
<app-rating [(value)]="myRating" />
<p>You rated {{ myRating }} stars</p>
```

`model()` combines an input and an output (`valueChange`) so parents can use `[( )]` with your component.

::tip Keep templates simple. If an expression gets longer than a few words (`[class.x]="a && (b || c) && !d"`), move it into a `computed()` signal or a method with a clear name. Templates are for displaying, classes are for logic.

## Common mistakes

- Using `[(ngModel)]` without importing `FormsModule`: "Can't bind to 'ngModel'".
- `[src]="'logo.png'"` vs `src="logo.png"`: inside `[ ]` the value is an **expression**, so plain strings need quotes.
- Calling heavy methods in templates (`{{ calculateTotal() }}`): they run on every change detection. Prefer `computed` signals.

## Exercise

1. Build a temperature converter: an input bound with `[(ngModel)]` and a live Fahrenheit result.
2. Add a "dark card" toggle button that adds a class with `[class.dark]`.
3. Make a progress bar whose width comes from a number input (`[style.width.%]`).
4. Use the `RatingComponent` with `[(value)]` and show "Thanks!" after rating.

::hint Hint for 1 | `celsius = 25;` and in the template `{{ celsius * 9 / 5 + 32 | number:'1.0-1' }} °F` (the `number` pipe formats decimals, next lesson).

## Interview corner

- **Types of data binding?** Interpolation, property binding, event binding and two-way binding.
- **How does two-way binding work?** `[(x)]` is shorthand for `[x]="val" (xChange)="val = $event"`.
- **Property vs attribute binding?** Property binding sets DOM properties; `[attr.name]` sets HTML attributes.
- **What is a template reference variable?** A `#ref` name for an element or component usable elsewhere in the template.

## Summary

- `{{ }}` and `[ ]` move data into the view, `( )` brings events back, `[( )]` does both.
- Class, style and attribute bindings handle styling and accessibility.
- `model()` gives your components two-way binding.

Next: control flow (`@if`, `@for`) and pipes.
