---
title: Angular CLI, Project Setup and Structure
slug: angular-setup-and-cli
subject: angular
lesson: 2
difficulty: beginner
excerpt: Install the Angular CLI, create and run a project, understand main.ts, app.config.ts and the root component, and generate components and services with ng generate.
---

The **Angular CLI** creates projects, generates code, runs a dev server, builds for production and runs tests. You'll use it every day.

## Install and create a project

```bash
npm install -g @angular/cli
ng new course-app
#   Which stylesheet format? CSS
#   Enable SSR? No (for now)
cd course-app
ng serve -o          # opens http://localhost:4200
```

Edit a file and the browser reloads automatically.

## Project structure

```text
course-app/
├── angular.json              ← CLI configuration (build, serve, test)
├── package.json
├── tsconfig.json             ← TypeScript settings (strict mode on)
└── src/
    ├── index.html            ← contains <app-root></app-root>
    ├── main.ts               ← bootstraps the app
    ├── styles.css            ← global styles
    └── app/
        ├── app.component.ts      ← root component (class)
        ├── app.component.html    ← its template
        ├── app.component.css     ← its styles (scoped to this component)
        ├── app.config.ts         ← app-wide providers (router, HttpClient…)
        └── app.routes.ts         ← routes
```

## How the app starts

```ts
// src/main.ts
import { bootstrapApplication } from '@angular/platform-browser';
import { appConfig } from './app/app.config';
import { AppComponent } from './app/app.component';

bootstrapApplication(AppComponent, appConfig).catch(err => console.error(err));
```

```ts
// src/app/app.config.ts
import { ApplicationConfig, provideZoneChangeDetection } from '@angular/core';
import { provideRouter } from '@angular/router';
import { provideHttpClient } from '@angular/common/http';
import { routes } from './app.routes';

export const appConfig: ApplicationConfig = {
  providers: [
    provideZoneChangeDetection({ eventCoalescing: true }),
    provideRouter(routes),
    provideHttpClient(),          // add this when you start calling APIs
  ],
};
```

1. `index.html` contains `<app-root>`.
2. `main.ts` bootstraps `AppComponent`, whose selector is `app-root`, with the providers from `app.config.ts`.
3. Angular renders the component's template inside `<app-root>`.

## The root component

```ts
// src/app/app.component.ts
import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { WelcomeComponent } from './welcome/welcome.component';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, WelcomeComponent],     // components used in the template
  templateUrl: './app.component.html',
  styleUrl: './app.component.css',
})
export class AppComponent {
  title = 'course-app';
}
```

```html
<!-- src/app/app.component.html -->
<app-welcome />
<router-outlet />
```

## Generating code

```bash
ng generate component welcome        # or: ng g c welcome
ng g service services/course          # or: ng g s services/course
ng g interface models/course
ng g pipe pipes/duration
ng g guard auth
```

`ng g c welcome` creates `welcome/welcome.component.ts`, `.html`, `.css` and a `.spec.ts` test file. To use it, add `WelcomeComponent` to the parent's `imports` array and put `<app-welcome />` in the template.

```ts
// src/app/welcome/welcome.component.ts
import { Component } from '@angular/core';

@Component({
  selector: 'app-welcome',
  standalone: true,
  template: `
    <section class="welcome">
      <h1>Welcome to {{ site }}</h1>
      <p>{{ lessons }} lessons · about {{ lessons * 15 }} minutes</p>
    </section>
  `,
  styles: `.welcome { padding: 16px; border-radius: 12px; background: #e8f5ee; }`,
})
export class WelcomeComponent {
  site = 'VitalStack';
  lessons = 12;
}
```

Small components can use inline `template` and `styles`; bigger ones use separate `templateUrl`/`styleUrl` files.

## Other CLI commands

| Command | Does |
|---|---|
| `ng serve` | dev server with live reload |
| `ng build` | production build into `dist/` (optimised, hashed files) |
| `ng test` | unit tests (Karma/Jasmine by default) |
| `ng lint` | linting (after `ng add @angular-eslint/schematics`) |
| `ng add @angular/material` | add libraries with automatic setup |
| `ng update` | upgrade Angular versions safely |

::tip Keep `"strict": true` in `tsconfig.json` (the default). Strict TypeScript and Angular's strict template checking catch typos and null errors in templates at build time, before users see them.

## Common mistakes

- Using a component in a template but forgetting to add it to `imports` → "'app-welcome' is not a known element".
- Editing files in `dist/` (they're regenerated on every build).
- Mixing old NgModule tutorials with standalone components.

## Exercise

1. Create a project and run it.
2. Generate a `header` component that shows the site name and a nav, and use it in `AppComponent`.
3. Generate a `footer` component with the current year (`new Date().getFullYear()`).
4. Run `ng build` and look at the files in `dist/`.

::hint Hint for 3 | In the class, `year = new Date().getFullYear();` and in the template `© {{ year }} VitalStack`.

## Interview corner

- **What does the Angular CLI do?** Scaffolds projects and code, serves, builds, tests and upgrades Angular apps.
- **How does an Angular app bootstrap?** `main.ts` calls `bootstrapApplication(AppComponent, appConfig)`, rendering the root component into its selector in index.html.
- **What is app.config.ts for?** App-wide providers such as the router, HttpClient and interceptors.
- **What files make up a component?** A TypeScript class with `@Component`, a template, styles and usually a spec test.

## Summary

- `ng new`, `ng serve`, `ng generate`, `ng build` cover daily work.
- `main.ts` bootstraps the root component with providers from `app.config.ts`.
- Standalone components list what they use in `imports`.

Next: components and templates in depth.
