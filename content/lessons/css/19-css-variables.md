---
title: CSS Variables
slug: css-variables
subject: css
lesson: 19
difficulty: intermediate
excerpt: Store colours, spacing and fonts in custom properties, use var() with fallbacks, build a dark mode, and organise a stylesheet around design tokens.
---

In a real stylesheet, the same brand colour may appear fifty times. Changing it means fifty edits. **CSS variables** (officially "custom properties") let you store a value once and reuse it everywhere. They also make dark mode and themes easy.

## Defining and using a variable

```html
<style>
  :root {
    --brand: #2563eb;
    --radius: 10px;
  }
  .btn {
    background: var(--brand);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: var(--radius);
  }
  .link { color: var(--brand); }
  .box { border: 2px solid var(--brand); border-radius: var(--radius); padding: 12px; margin-top: 12px; }
</style>
<button class="btn">Button</button>
<a class="link" href="#">A link</a>
<div class="box">A box</div>
```

- A variable name starts with **two hyphens**: `--brand`.
- `:root` means the `<html>` element, so variables defined there are available everywhere.
- `var(--brand)` uses the value.

Now change `--brand` to `#16a34a` and everything turns green at once.

## Fallback values

```css
.title { color: var(--heading-color, #111827); }
```

If `--heading-color` isn't defined, the fallback after the comma is used.

## Variables are inherited and can be overridden

A variable can be redefined on any element, and the new value applies to that element and its children:

```html
<style>
  :root { --accent: #2563eb; }
  .card { border-top: 4px solid var(--accent); padding: 12px; margin-bottom: 10px; background: #f8fafc; font-family: system-ui, sans-serif; }
  .card h3 { color: var(--accent); margin: 0; }
  .card.success { --accent: #16a34a; }
  .card.danger  { --accent: #dc2626; }
</style>
<div class="card"><h3>Default</h3></div>
<div class="card success"><h3>Success</h3></div>
<div class="card danger"><h3>Danger</h3></div>
```

One set of card styles, three colour variants, each changing just one variable. This is how design systems build components.

## A dark mode in a few lines

```html
<style>
  :root {
    --bg: #ffffff;
    --text: #1f2937;
    --muted: #6b7280;
    --surface: #f3f4f6;
    --brand: #2563eb;
  }
  @media (prefers-color-scheme: dark) {
    :root {
      --bg: #0f172a;
      --text: #e5e7eb;
      --muted: #94a3b8;
      --surface: #1e293b;
      --brand: #60a5fa;
    }
  }
  body { background: var(--bg); color: var(--text); font-family: system-ui, sans-serif; padding: 20px; }
  .panel { background: var(--surface); padding: 16px; border-radius: 10px; }
  .panel p { color: var(--muted); }
  a { color: var(--brand); }
</style>
<div class="panel">
  <h3>Automatic dark mode</h3>
  <p>Switch your system to dark mode and this page follows.</p>
  <a href="#">A link</a>
</div>
```

The components only use variables. Dark mode simply swaps the variable values. VitalStack's own dark mode works this way, with a toggle button that sets a `data-theme` attribute on `<html>`:

```css
:root[data-theme="dark"] {
  --bg: #0f172a;
  --text: #e5e7eb;
}
```

## Design tokens: organising variables

Large projects group their variables by purpose. These are often called **design tokens**:

```css
:root {
  /* Colours */
  --color-brand: #2563eb;
  --color-text: #1f2937;
  --color-muted: #6b7280;
  --color-border: #e5e7eb;

  /* Spacing scale */
  --space-1: 0.25rem;
  --space-2: 0.5rem;
  --space-3: 1rem;
  --space-4: 1.5rem;
  --space-5: 2rem;

  /* Typography */
  --font-body: "Inter", system-ui, sans-serif;
  --font-code: "Source Code Pro", monospace;
  --text-sm: 0.875rem;
  --text-lg: 1.25rem;

  /* Shapes */
  --radius-sm: 6px;
  --radius-lg: 14px;
  --shadow: 0 4px 12px rgb(0 0 0 / 0.08);
}
```

Using a fixed scale (`--space-3` instead of random values like 13px, 17px, 22px) makes a design look consistent.

## Calculating with variables

```css
:root { --gap: 16px; }
.section { padding: calc(var(--gap) * 2); }
.tight { margin-top: calc(var(--gap) / 2); }
```

`calc()` combines variables with maths, even mixing units: `calc(100% - var(--sidebar-width))`.

## Changing variables with JavaScript

```html
<style>
  :root { --hue: 220; }
  .swatch { height: 80px; border-radius: 8px; background: hsl(var(--hue) 80% 55%); }
</style>
<div class="swatch"></div>
<label>Hue <input type="range" min="0" max="360" value="220" id="hue"></label>
<script>
  document.getElementById("hue").addEventListener("input", function () {
    document.documentElement.style.setProperty("--hue", this.value);
  });
</script>
```

Drag the slider and the colour updates live. Variables are the bridge between JavaScript and CSS.

## Common mistakes

- **One hyphen** instead of two: `-brand` isn't a variable.
- **Forgetting `var()`**: `color: --brand;` does nothing.
- **Spaces or typos** in names; variable names are case-sensitive.
- **Defining variables inside one component** and expecting them to work in others. Put shared ones in `:root`.

## Exercise

1. Move your profile page's colours, radius and spacing into `:root` variables.
2. Add a dark mode with `prefers-color-scheme` by redefining only the variables.
3. Make "success", "warning" and "danger" versions of a message box by changing one variable each.
4. Add a range slider that changes a variable with JavaScript.

## Summary

- Define variables with `--name: value` (usually on `:root`) and use them with `var(--name)`.
- Variables inherit and can be overridden per element, which makes component variants easy.
- Dark mode = swap variable values.
- Design tokens (colours, spacing, fonts) keep a whole site consistent.

Next, you'll use the whole CSS course to style your profile page project.
