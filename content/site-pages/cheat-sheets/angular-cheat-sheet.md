---
title: Angular Cheat Sheet
slug: angular-cheat-sheet
parent: cheat-sheets
template: page-cheat-sheet.php
icon: 🅰️
order: 90
excerpt: Modern Angular on one page - CLI, standalone components, bindings, @if/@for, pipes, signals, services and DI, routing, reactive forms, HttpClient, RxJS and OnPush. Printable.
---
## CLI

```bash
npm i -g @angular/cli
ng new my-app && cd my-app && ng serve -o
ng g c course-card        # component
ng g s services/course    # service
ng g pipe duration; ng g guard auth; ng g interface models/course
ng build; ng test; ng update
```

## Component

```ts
@Component({
  selector: 'app-course-card',
  standalone: true,
  imports: [CurrencyPipe],
  changeDetection: ChangeDetectionStrategy.OnPush,
  template: `<h3>{{ course().title }}</h3>
             <p>{{ course().price | currency:'INR' }}</p>
             <button (click)="enroll.emit(course())">Enroll</button>`,
})
export class CourseCardComponent {
  course = input.required<Course>();
  featured = input(false);
  enroll = output<Course>();
  label = computed(() => this.course().title.toUpperCase());
}
```

## Template syntax

| Syntax | Meaning |
|---|---|
| `{{ value }}` | interpolation |
| `[src]="url"` | property binding |
| `[attr.aria-label]="x"` | attribute binding |
| `[class.active]="on"` / `[style.width.%]="p"` | class / style |
| `(click)="save()"` | event binding |
| `[(ngModel)]="name"` | two-way (FormsModule) |
| `#box` | template reference variable |
| `<ng-content />` | content projection |

## Control flow

```html
@if (loading()) { <p>Loading…</p> } @else if (error()) { <p>{{ error() }}</p> } @else { … }
@for (c of courses(); track c.id; let i = $index) { <li>{{ i + 1 }}. {{ c.title }}</li> } @empty { <li>None</li> }
@switch (level) { @case ('Beginner') { … } @default { … } }
@defer (on viewport) { <app-reviews /> } @placeholder { <p>Reviews</p> }
```

## Pipes

`date:'d MMM y'`, `currency:'INR'`, `number:'1.0-2'`, `percent`, `uppercase`, `titlecase`, `slice:0:20`, `json`, `async`

```ts
@Pipe({ name: 'duration', standalone: true })
export class DurationPipe implements PipeTransform {
  transform(min: number) { return `${Math.floor(min / 60)}h ${min % 60}m`; }
}
```

## Signals

```ts
count = signal(0);
count.set(5); count.update(n => n + 1); count();
total = computed(() => this.price() * this.qty());
constructor() { effect(() => localStorage.setItem('n', String(this.count()))); }
items.update(list => [...list, item]);       // immutable updates
data = toSignal(this.http.get<Course[]>('/api/courses'), { initialValue: [] });
```

## Services and DI

```ts
@Injectable({ providedIn: 'root' })
export class CourseService { private http = inject(HttpClient); list() { return this.http.get<Course[]>('/api/courses'); } }

export class Page { courses = inject(CourseService); }
@Component({ providers: [DraftService] })   // one instance per component
export const API_URL = new InjectionToken<string>('API_URL', { providedIn: 'root', factory: () => '/api' });
```

## App config and routing

```ts
export const appConfig: ApplicationConfig = {
  providers: [provideRouter(routes, withComponentInputBinding()), provideHttpClient(withInterceptors([authInterceptor]))],
};
export const routes: Routes = [
  { path: '', component: HomeComponent },
  { path: 'courses/:id', component: CourseDetailComponent },     // id = input.required<string>()
  { path: 'admin', canActivate: [authGuard], loadComponent: () => import('./admin.component').then(m => m.AdminComponent) },
  { path: '**', component: NotFoundComponent },
];
export const authGuard: CanActivateFn = () => inject(AuthService).isLoggedIn() || inject(Router).createUrlTree(['/login']);
```

```html
<a routerLink="/courses" routerLinkActive="active">Courses</a>
<a [routerLink]="['/courses', c.id]" [queryParams]="{ tab: 'reviews' }">Open</a>
<router-outlet />
```

## Reactive forms

```ts
form = inject(FormBuilder).nonNullable.group({
  email: ['', [Validators.required, Validators.email]],
  password: ['', [Validators.required, Validators.minLength(8)]],
  terms: [false, Validators.requiredTrue],
});
submit() { if (this.form.invalid) { this.form.markAllAsTouched(); return; } this.form.getRawValue(); }
```

```html
<form [formGroup]="form" (ngSubmit)="submit()">
  <input formControlName="email">
  @if (form.controls.email.invalid && form.controls.email.touched) { <small>Invalid email</small> }
</form>
```

## HTTP and RxJS

```ts
this.http.post<Course>('/api/courses', body);
query.valueChanges.pipe(debounceTime(300), distinctUntilChanged(), switchMap(q => api.search(q)));
catchError(err => throwError(() => new Error(err.error?.detail ?? 'Failed')));
forkJoin([a$, b$]); takeUntilDestroyed();
export const authInterceptor: HttpInterceptorFn = (req, next) =>
  next(req.clone({ setHeaders: { Authorization: `Bearer ${token}` } }));
```

## Lifecycle

`constructor` (DI) → `ngOnInit` (load data) → `ngAfterViewInit` (DOM ready) → `ngOnDestroy` (clean up)
