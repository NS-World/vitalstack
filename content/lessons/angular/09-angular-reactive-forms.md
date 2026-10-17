---
title: Angular Reactive Forms and Validation
slug: angular-reactive-forms
subject: angular
lesson: 9
difficulty: intermediate
excerpt: Build robust forms with typed reactive forms - FormBuilder, built-in validators, showing errors at the right time, a custom cross-field validator, dynamic FormArray fields and template-driven forms for simple cases.
---

Angular has two ways to build forms. **Reactive forms** define the form model in TypeScript and are the standard choice for real applications. **Template-driven forms** (with `ngModel`) are fine for very small forms.

## A reactive sign-up form

```ts
// src/app/signup/signup.component.ts
import { Component, inject, signal } from '@angular/core';
import { AbstractControl, FormBuilder, ReactiveFormsModule, ValidationErrors, Validators } from '@angular/forms';

function passwordsMatch(group: AbstractControl): ValidationErrors | null {
  const pass = group.get('password')?.value;
  const confirm = group.get('confirm')?.value;
  return pass && confirm && pass !== confirm ? { mismatch: true } : null;
}

@Component({
  selector: 'app-signup',
  standalone: true,
  imports: [ReactiveFormsModule],
  template: `
    @if (done()) {
      <p role="status">Welcome, {{ done() }}! Check your email.</p>
    } @else {
      <form [formGroup]="form" (ngSubmit)="submit()" novalidate>
        <label>Name <input formControlName="name" /></label>
        @if (showError('name')) { <small class="error">Name needs at least 2 letters</small> }

        <label>Email <input formControlName="email" type="email" /></label>
        @if (showError('email')) { <small class="error">Enter a valid email</small> }

        <label>Course
          <select formControlName="course">
            <option value="java">Java</option>
            <option value="angular">Angular</option>
          </select>
        </label>

        <div formGroupName="passwords">
          <label>Password <input formControlName="password" type="password" /></label>
          <label>Confirm <input formControlName="confirm" type="password" /></label>
        </div>
        @if (form.controls.passwords.hasError('mismatch') && form.controls.passwords.touched) {
          <small class="error">Passwords don't match</small>
        }

        <label><input type="checkbox" formControlName="terms" /> I accept the terms</label>
        <button type="submit">Create free account</button>
      </form>
    }
  `,
})
export class SignupComponent {
  private fb = inject(FormBuilder).nonNullable;
  done = signal<string | null>(null);

  form = this.fb.group({
    name: ['', [Validators.required, Validators.minLength(2)]],
    email: ['', [Validators.required, Validators.email]],
    course: ['java'],
    passwords: this.fb.group(
      { password: ['', [Validators.required, Validators.minLength(8)]], confirm: [''] },
      { validators: passwordsMatch }
    ),
    terms: [false, Validators.requiredTrue],
  });

  showError(name: 'name' | 'email') {
    const c = this.form.controls[name];
    return c.invalid && (c.touched || c.dirty);        // don't shout before the user has typed
  }

  submit() {
    if (this.form.invalid) {
      this.form.markAllAsTouched();                    // reveal every error on submit
      return;
    }
    const value = this.form.getRawValue();             // fully typed: { name: string, email: string, … }
    this.done.set(value.name);
  }
}
```

## How it works

- `FormBuilder` creates a tree of `FormGroup`s and `FormControl`s. `nonNullable` means `reset()` goes back to the initial values instead of `null`.
- `[formGroup]` connects the `<form>`; `formControlName` and `formGroupName` connect inputs to controls.
- Each control tracks its **value**, **validity** (`valid`/`invalid`, `errors`), and interaction state (`touched`, `dirty`, `pristine`).
- The form model is **typed**: `form.getRawValue().email` is a `string`, and typos are compile errors.

## Built-in validators

| Validator | Checks |
|---|---|
| `Validators.required` | not empty |
| `Validators.requiredTrue` | checkbox ticked |
| `Validators.minLength(n)` / `maxLength(n)` | text length |
| `Validators.min(n)` / `max(n)` | numbers |
| `Validators.email` | email format |
| `Validators.pattern(/^[6-9]\d{9}$/)` | regex (an Indian mobile number here) |

Errors live in `control.errors`, e.g. `{ minlength: { requiredLength: 2, actualLength: 1 } }`, so you can show precise messages.

## Custom validators

`passwordsMatch` above is a **cross-field validator** on a group. A single-field custom validator:

```ts
export function noSpaces(control: AbstractControl): ValidationErrors | null {
  return /\s/.test(control.value ?? '') ? { noSpaces: true } : null;
}
// username: ['', [Validators.required, noSpaces]]
```

Async validators (e.g. "is this username taken?" via HTTP) return an Observable or Promise.

## Reacting to changes

```ts
this.form.controls.course.valueChanges.subscribe(course => {
  console.log('Course changed to', course);
});
```

`valueChanges` is an Observable: combine it with RxJS operators like `debounceTime` for live search (next lesson).

## Dynamic fields with FormArray

```ts
skills = this.fb.array([this.fb.control('Java')]);
addSkill() { this.skills.push(this.fb.control('')); }
removeSkill(i: number) { this.skills.removeAt(i); }
```

```html
<div formArrayName="skills">
  @for (s of skills.controls; track $index; let i = $index) {
    <input [formControlName]="i" /> <button type="button" (click)="removeSkill(i)">×</button>
  }
</div>
<button type="button" (click)="addSkill()">Add skill</button>
```

## Template-driven forms (quick forms)

```html
<form #f="ngForm" (ngSubmit)="search(f.value.q)">
  <input name="q" ngModel required minlength="2" />
  <button [disabled]="f.invalid">Search</button>
</form>
```

Simpler for tiny forms, but logic lives in the template and it's harder to test. For anything with real validation, use reactive forms.

::tip Show errors after the user leaves a field (`touched`) or on submit (`markAllAsTouched`), not while they're still typing their first character. And always validate again on the server: the Spring Boot course's ProblemDetail errors can be mapped back to these fields.

## Exercise

1. Add a phone field with an Indian mobile number pattern and a clear error message.
2. Disable the submit button while the form is invalid, and show a summary of all errors on submit.
3. Add a `FormArray` of up to 5 "learning goals".
4. Write an async validator that rejects the email `taken@example.com` after 500 ms (use `of(...).pipe(delay(500))`).

::hint Hint for 1 | `phone: ['', [Validators.required, Validators.pattern(/^[6-9]\d{9}$/)]]` and show the error when `form.controls.phone.hasError('pattern')`.

## Interview corner

- **Reactive vs template-driven forms?** Reactive: model defined in TS, explicit, typed, testable; template-driven: model inferred from `ngModel` directives in the template.
- **FormControl vs FormGroup vs FormArray?** A single value; a named group of controls; a dynamic list of controls.
- **touched vs dirty?** Touched: the field lost focus at least once; dirty: the value was changed by the user.
- **How do you write a cross-field validator?** A validator function on the FormGroup that reads multiple controls.

## Summary

- Reactive forms: `FormBuilder` + `Validators` + `formControlName`.
- Show errors on touched/dirty or after submit with `markAllAsTouched`.
- Custom, cross-field and async validators cover complex rules; FormArray handles dynamic lists.

Next: HttpClient and RxJS for talking to APIs.
