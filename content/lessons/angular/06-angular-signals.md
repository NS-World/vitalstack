---
title: Angular Signals - signal, computed and effect
slug: angular-signals
subject: angular
lesson: 6
difficulty: intermediate
excerpt: Manage state the modern Angular way with signals - create and update them with set and update, derive values with computed, run side effects with effect, keep arrays immutable, and build a signal-based cart.
---

**Signals** are Angular's modern way to hold state. A signal is a value that **notifies** anything reading it when it changes, so Angular knows exactly which parts of the page to update.

## Creating and reading signals

```ts
import { signal } from '@angular/core';

const count = signal(0);        // create with an initial value
console.log(count());           // read by CALLING it: 0
count.set(5);                   // replace the value
count.update(n => n + 1);       // compute from the current value: 6
```

In a template, call it too: `{{ count() }}`. When the signal changes, that spot in the template updates.

## computed: derived values

```ts
import { computed, signal } from '@angular/core';

const price = signal(500);
const quantity = signal(2);
const total = computed(() => price() * quantity());    // 1000

quantity.set(3);
console.log(total());                                  // 1500: recalculated automatically
```

- A computed signal **tracks** the signals it reads and recalculates only when one of them changes.
- It's **lazy and cached**: reading `total()` ten times computes once.
- It's read-only: you can't `set` a computed.

## effect: side effects

An `effect` runs when signals it reads change. Use it to sync with things **outside** Angular (localStorage, logging, a chart library):

```ts
import { effect } from '@angular/core';

effect(() => {
  localStorage.setItem('cart', JSON.stringify(items()));   // re-runs whenever items changes
});
```

Effects must be created in an injection context (a constructor or field initialiser of a component/service). Don't use effects to set other signals: that's what `computed` is for.

## A signal-based cart

```ts
// src/app/cart.store.ts
import { Injectable, computed, signal } from '@angular/core';

export interface CartItem { id: number; title: string; price: number; qty: number; }

@Injectable({ providedIn: 'root' })
export class CartStore {
  private readonly _items = signal<CartItem[]>([]);
  readonly items = this._items.asReadonly();                       // components can read, not write
  readonly count = computed(() => this._items().reduce((n, i) => n + i.qty, 0));
  readonly total = computed(() => this._items().reduce((s, i) => s + i.price * i.qty, 0));
  readonly coupon = signal<string | null>(null);
  readonly payable = computed(() => (this.coupon() === 'LEARN20' ? Math.round(this.total() * 0.8) : this.total()));

  add(course: { id: number; title: string; price: number }) {
    this._items.update(items => {
      const found = items.find(i => i.id === course.id);
      return found
        ? items.map(i => (i.id === course.id ? { ...i, qty: i.qty + 1 } : i))   // new array, new object
        : [...items, { ...course, qty: 1 }];
    });
  }

  remove(id: number) {
    this._items.update(items => items.filter(i => i.id !== id));
  }

  clear() {
    this._items.set([]);
    this.coupon.set(null);
  }
}
```

```ts
// src/app/cart-view/cart-view.component.ts
import { Component, inject } from '@angular/core';
import { CartStore } from '../cart.store';

@Component({
  selector: 'app-cart-view',
  standalone: true,
  template: `
    <button (click)="cart.add({ id: 1, title: 'Java', price: 500 })">Add Java</button>
    <button (click)="cart.add({ id: 2, title: 'Angular', price: 600 })">Add Angular</button>
    <button (click)="cart.coupon.set('LEARN20')">Apply LEARN20</button>
    <p class="summary">{{ cart.count() }} items · ₹{{ cart.payable() }}</p>
    <ul>
      @for (item of cart.items(); track item.id) {
        <li>{{ item.title }} × {{ item.qty }} <button (click)="cart.remove(item.id)">Remove</button></li>
      }
    </ul>
  `,
})
export class CartViewComponent {
  cart = inject(CartStore);
}
```

Add Java twice and Angular once: `3 items · ₹1600`. Apply LEARN20: `3 items · ₹1280`. The service is the single source of truth; any component that injects `CartStore` sees the same cart.

## Immutability still matters

Signals compare values with `Object.is` by default. If you mutate an array in place (`items().push(x)`) and set the same array, readers aren't notified. Always produce **new** arrays/objects in `update`, just like React state.

## Signals and RxJS

Angular apps also use **RxJS Observables** (for HTTP, events over time). Convert between them with `@angular/core/rxjs-interop`:

```ts
import { toSignal, toObservable } from '@angular/core/rxjs-interop';

courses = toSignal(this.http.get<Course[]>('/api/courses'), { initialValue: [] });
```

You'll use this in lesson 10.

::tip Rule of thumb in modern Angular: **signals for state** (what the UI shows), **RxJS for events and async streams** (HTTP, debounced search, websockets), and `toSignal` to bring stream results into templates.

## Exercise

1. Create a counter component with `count`, `double = computed(...)` and +1 / reset buttons.
2. Add a `remaining` computed signal to a to-do list that counts unfinished tasks.
3. Persist the cart to localStorage with an `effect`, and load it in the signal's initial value.
4. Add a `discountPercent` signal and make `payable` use it.

::hint Hint for 3 | `private readonly _items = signal<CartItem[]>(JSON.parse(localStorage.getItem('cart') ?? '[]'));` and in the constructor `effect(() => localStorage.setItem('cart', JSON.stringify(this._items())));`

## Interview corner

- **What is a signal?** A reactive value container; reading it registers a dependency, and changes notify dependents.
- **signal vs computed vs effect?** Writable state; derived read-only state; side effects reacting to signal changes.
- **Why are signals good for performance?** Angular knows exactly which views depend on which signals, enabling fine-grained updates (and zoneless change detection).
- **set vs update?** `set` replaces the value; `update` computes the new value from the current one.

## Summary

- `signal(initial)`, read with `x()`, change with `set`/`update`.
- `computed` derives cached values; `effect` syncs with the outside world.
- Keep updates immutable; put shared signal state in services.

Next: services and dependency injection.
