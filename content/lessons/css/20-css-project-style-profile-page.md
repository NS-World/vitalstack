---
title: "CSS Project: Style Your Profile Page"
slug: css-project-style-profile-page
subject: css
lesson: 20
difficulty: intermediate
excerpt: Turn the plain HTML profile page into a modern, responsive design with variables, Flexbox, Grid, cards, a dark mode and smooth transitions.
---

At the end of the HTML course you built a complete profile page. Now you'll make it look professional using everything from this course: variables, the box model, Flexbox, Grid, responsive design and transitions. Work through the steps in order, testing as you go.

## Step 0: Start with your HTML

Use your page from the HTML project. If you don't have it, this short version is enough to follow along:

```html
<header class="site-header">
  <div class="container header-inner">
    <a class="logo" href="#">Asha Verma</a>
    <nav aria-label="Main">
      <ul class="nav">
        <li><a href="#about">About</a></li>
        <li><a href="#skills">Skills</a></li>
        <li><a href="#projects">Projects</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>
    </nav>
  </div>
</header>
<main class="container">
  <section id="about" class="hero">
    <img class="avatar" src="https://picsum.photos/id/64/240/240" alt="Asha smiling at a desk" width="240" height="240">
    <div>
      <h1>Hi, I'm Asha</h1>
      <p class="lead">Beginner web developer building one small project every week.</p>
      <a class="btn" href="#contact">Get in touch</a>
    </div>
  </section>
  <section id="projects">
    <h2>Projects</h2>
    <div class="cards">
      <article class="card"><h3>Recipe page</h3><p>Headings, lists and images.</p></article>
      <article class="card"><h3>Timetable</h3><p>Tables with merged cells.</p></article>
      <article class="card"><h3>Profile page</h3><p>Everything together, now with CSS.</p></article>
    </div>
  </section>
</main>
<footer class="site-footer"><div class="container"><small>© Asha Verma</small></div></footer>
```

The only change from plain HTML: a few **classes** added as styling hooks.

## Step 1: Variables and a reset

Put this at the top of your stylesheet (or a `<style>` element while testing):

```css
:root {
  --bg: #f8fafc;
  --surface: #ffffff;
  --text: #1f2937;
  --muted: #64748b;
  --border: #e2e8f0;
  --brand: #2563eb;
  --brand-dark: #1e40af;
  --radius: 14px;
  --shadow: 0 4px 14px rgb(15 23 42 / 0.08);
  --font: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
}

*, *::before, *::after { box-sizing: border-box; }

body {
  margin: 0;
  background: var(--bg);
  color: var(--text);
  font-family: var(--font);
  line-height: 1.6;
}

img { max-width: 100%; height: auto; }
```

## Step 2: A centred container

```css
.container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 0 20px;
}
```

Every section now lines up on the same centred column.

## Step 3: Header with Flexbox

```css
.site-header {
  background: var(--surface);
  border-bottom: 1px solid var(--border);
  position: sticky;
  top: 0;
  z-index: 10;
}
.header-inner {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 12px;
  padding-block: 14px;
}
.logo {
  font-weight: 800;
  font-size: 1.25rem;
  color: var(--text);
  text-decoration: none;
}
.nav {
  display: flex;
  gap: 4px;
  list-style: none;
  margin: 0;
  padding: 0;
}
.nav a {
  display: block;
  padding: 8px 12px;
  border-radius: 8px;
  color: var(--muted);
  text-decoration: none;
  transition: background-color 0.2s, color 0.2s;
}
.nav a:hover {
  background: var(--bg);
  color: var(--brand);
}
```

(`padding-block` sets top and bottom padding together.)

## Step 4: The hero section

```css
.hero {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 24px;
  padding: 48px 0;
}
.avatar {
  width: 160px;
  height: 160px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid var(--surface);
  box-shadow: var(--shadow);
}
.hero h1 {
  font-size: clamp(2rem, 5vw, 3rem);
  line-height: 1.15;
  margin: 0 0 8px;
}
.lead {
  font-size: 1.15rem;
  color: var(--muted);
  max-width: 45ch;
  margin: 0 auto 20px;
}
@media (min-width: 700px) {
  .hero {
    flex-direction: row;
    text-align: left;
  }
  .lead { margin-left: 0; }
}
```

Mobile-first: stacked and centred on phones, photo beside text from 700px.

## Step 5: Buttons

```css
.btn {
  display: inline-block;
  background: var(--brand);
  color: white;
  padding: 12px 24px;
  border-radius: 999px;
  font-weight: 600;
  text-decoration: none;
  transition: background-color 0.2s, transform 0.2s;
}
.btn:hover {
  background: var(--brand-dark);
  transform: translateY(-2px);
}
.btn:focus-visible {
  outline: 3px solid #f59e0b;
  outline-offset: 3px;
}
```

## Step 6: Responsive project cards with Grid

```css
.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
}
.card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 20px;
  box-shadow: var(--shadow);
  transition: transform 0.2s, box-shadow 0.2s;
}
.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgb(15 23 42 / 0.12);
}
.card h3 { margin-top: 0; }
.card p { color: var(--muted); margin-bottom: 0; }
```

## Step 7: Footer, forms and tables

```css
.site-footer {
  margin-top: 64px;
  padding: 24px 0;
  border-top: 1px solid var(--border);
  color: var(--muted);
  text-align: center;
}

input, textarea {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid var(--border);
  border-radius: 8px;
  font: inherit;
  background: var(--surface);
  color: var(--text);
}
input:focus, textarea:focus {
  outline: none;
  border-color: var(--brand);
  box-shadow: 0 0 0 3px rgb(37 99 235 / 0.25);
}

table { width: 100%; border-collapse: collapse; }
th, td { padding: 10px; border-bottom: 1px solid var(--border); text-align: left; }
tbody tr:nth-child(even) { background: var(--bg); }
```

`font: inherit` makes form fields use the page font instead of the browser's default. `border-collapse: collapse` merges table borders into single lines. The focus style replaces the outline with an equally visible ring, so keyboard users can still see where they are.

## Step 8: Dark mode

Because everything uses variables, dark mode is one block:

```css
@media (prefers-color-scheme: dark) {
  :root {
    --bg: #0b1120;
    --surface: #111827;
    --text: #e5e7eb;
    --muted: #94a3b8;
    --border: #1f2937;
    --brand: #60a5fa;
    --brand-dark: #3b82f6;
  }
}
```

## Step 9: Final checks

- ☐ Test at 320px, 768px and 1280px wide: no horizontal scrolling.
- ☐ Tab through the page: every link and button shows focus.
- ☐ Check text contrast in both light and dark mode.
- ☐ Turn on "reduce motion" and add the reduced-motion block from the transitions lesson.
- ☐ Validate the CSS at jigsaw.w3.org/css-validator.

## Ideas to go further

- Add a skills section as a row of pill-shaped tags with `flex-wrap`.
- Add a theme toggle button (you'll be able to write the JavaScript after the first few JavaScript lessons).
- Publish it for free on GitHub Pages or Netlify and share the link.

## Summary

- Variables and a small reset give a consistent foundation.
- Flexbox handles the header and hero; Grid handles the card layout.
- Mobile-first media queries and `clamp()` make it responsive.
- Variables make dark mode a single block of CSS.

You've finished the CSS course. Next comes **JavaScript**, where you'll make your pages interactive.
