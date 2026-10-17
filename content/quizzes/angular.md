## angular-introduction
Q: Angular is…
- [x] A complete TypeScript framework from Google
- [ ] A React library
- [ ] The same as AngularJS
> Routing, forms and HTTP are built in.

Q: Modern Angular uses…
- [x] Standalone components, @if/@for and signals
- [ ] NgModules for everything
- [ ] jQuery
> Old tutorials use *ngIf and NgModules.

Q: Where is Angular especially popular?
- [x] Enterprise, banking and large team projects
- [ ] Only games
- [ ] Only mobile
> Conventions help big teams.

## angular-setup-and-cli
Q: Which command creates a component?
- [x] ng generate component name
- [ ] npm create component
- [ ] ng new component
> Shortcut: ng g c name.

Q: What does main.ts do?
- [x] Bootstraps the root component with the app config
- [ ] Contains all routes
- [ ] Builds CSS
> bootstrapApplication(AppComponent, appConfig).

Q: "'app-welcome' is not a known element" usually means…
- [x] The component isn't in the parent's imports
- [ ] The file is missing CSS
- [ ] Node is outdated
> Standalone components list their dependencies.

## angular-components-inputs-outputs
Q: How do you read a signal input named course?
- [x] course()
- [ ] this.course.value
- [ ] course.get()
> Signal inputs are read by calling them.

Q: `(enroll)="onEnroll($event)"` binds…
- [x] An output event from the child
- [ ] An input
- [ ] A CSS class
> $event is the emitted value.

Q: Where should data loading start?
- [x] ngOnInit (or a signal/effect), not the constructor
- [ ] ngOnDestroy
- [ ] The template
> Inputs are set by ngOnInit.

## angular-data-binding
Q: `[src]="logoUrl"` is…
- [x] Property binding (class → view)
- [ ] Event binding
- [ ] Two-way binding
> Square brackets bring data in.

Q: What do you need for [(ngModel)]?
- [x] FormsModule in imports
- [ ] HttpClient
- [ ] RouterModule
> Otherwise "Can't bind to ngModel".

Q: `[(value)]` on your own component needs…
- [x] model() (or an input plus a valueChange output)
- [ ] ngOnChanges
- [ ] A service
> [( )] is input + Change output.

## angular-control-flow-and-pipes
Q: What is required in @for?
- [x] track
- [ ] @empty
- [ ] let i = $index
> track identifies items to reuse DOM nodes.

Q: `{{ 1499.5 | currency:'INR' }}` shows…
- [x] ₹1,499.50
- [ ] INR 1499.5
- [ ] 1,499.50 Rs
> Pipes format values for display.

Q: Pure pipes re-run…
- [x] Only when their input changes
- [ ] On every change detection
- [ ] Never
> That's why they beat method calls in templates.

## angular-signals
Q: How do you change a signal from its current value?
- [x] count.update(n => n + 1)
- [ ] count = count + 1
- [ ] count.value++
> set replaces; update computes.

Q: computed signals are…
- [x] Derived, cached and read-only
- [ ] Writable
- [ ] Recomputed on every read
> They recalc when dependencies change.

Q: What's effect for?
- [x] Side effects like syncing to localStorage
- [ ] Deriving values
- [ ] Routing
> Use computed for derived state.

## angular-services-and-di
Q: providedIn: 'root' gives…
- [x] One app-wide instance
- [ ] One per component
- [ ] A new instance per inject()
> It's tree-shakable too.

Q: How do you give each component its own service instance?
- [x] Add the service to the component's providers
- [ ] Use providedIn: 'root'
- [ ] Use static fields
> The component injector creates it.

Q: Where can inject() be called?
- [x] In an injection context: constructors, field initialisers, guards
- [ ] Anywhere, like in setTimeout
- [ ] Only in main.ts
> Otherwise Angular throws an error.

## angular-routing
Q: Where does the matched route component render?
- [x] In <router-outlet>
- [ ] In index.html body
- [ ] In app.config.ts
> routerLink navigates without reload.

Q: What does loadComponent do?
- [x] Lazy-loads the page's code on first visit
- [ ] Preloads every page
- [ ] Loads data
> Keeps the initial bundle small.

Q: A functional guard returning a UrlTree…
- [x] Redirects to that URL
- [ ] Allows navigation
- [ ] Crashes
> Use it to send users to /login.

## angular-reactive-forms
Q: Validators.requiredTrue checks…
- [x] A checkbox is ticked
- [ ] A field is not empty
- [ ] A number is positive
> Perfect for accepting terms.

Q: When should errors appear?
- [x] After the field is touched/dirty or on submit
- [ ] Immediately on page load
- [ ] Never
> markAllAsTouched reveals all on submit.

Q: A FormArray is for…
- [x] A dynamic list of controls
- [ ] A fixed group
- [ ] HTTP requests
> Add and remove fields at runtime.

## angular-http-and-rxjs
Q: Does http.get() send a request immediately?
- [ ] Yes
- [x] No, only when subscribed
- [ ] Only with async/await
> Observables are lazy.

Q: Why switchMap in live search?
- [x] It cancels the previous request when a new query arrives
- [ ] It sorts results
- [ ] It caches results
> Stale responses can't overwrite new ones.

Q: How do you add a JWT to every API request?
- [x] An HTTP interceptor that clones the request with the header
- [ ] Add it in every component
- [ ] Put it in the URL
> Requests are immutable, so clone them.

## angular-change-detection-and-performance
Q: With OnPush, mutating an input array…
- [x] Doesn't update the view
- [ ] Always updates it
- [ ] Throws an error
> Create a new array reference.

Q: @defer (on viewport) …
- [x] Loads a block's components when scrolled into view
- [ ] Hides the block forever
- [ ] Delays the whole app
> Great for heavy widgets below the fold.

Q: Which makes change detection most precise?
- [x] Signals with OnPush
- [ ] Calling methods in templates
- [ ] Default strategy everywhere
> Angular knows exactly what changed.

## angular-project-course-hub
Q: How does the app show server errors like "max 3 active courses"?
- [x] The API service maps ProblemDetail.detail to an Error message
- [ ] It hard-codes the text
- [ ] It ignores errors
> catchError + throwError in one place.

Q: Where is enrollment state kept?
- [x] A signal-based store service
- [ ] In localStorage only
- [ ] In the router
> Pages inject the store.

Q: Why lazy-load each page?
- [x] Smaller initial download and faster first load
- [ ] It's required for routing
- [ ] To support OnPush
> loadComponent splits the bundles.
