---
title: CSS Cheat Sheet
slug: css-cheat-sheet
parent: cheat-sheets
template: page-cheat-sheet.php
icon: 🎨
order: 50
excerpt: CSS on one page - selectors, specificity, box model, colours, text, Flexbox, Grid, positioning, responsive media queries, variables and animations. Printable.
---
## Selectors

| Selector | Matches |
|---|---|
| `p` | all `<p>` |
| `.card` | class="card" |
| `#menu` | id="menu" |
| `nav a` | a inside nav (any depth) |
| `ul > li` | direct children |
| `h2 + p` | p right after h2 |
| `[type="email"]` | attribute |
| `a:hover`, `:focus-visible`, `:checked` | states |
| `li:first-child`, `:nth-child(2n)`, `:not(.done)` | position / negation |
| `::before`, `::after`, `::placeholder` | pseudo-elements |

Specificity: inline > `#id` > `.class`/`:pseudo`/`[attr]` > `element`. Later rules win ties.

## Box model

```css
*, *::before, *::after { box-sizing: border-box; }   /* width includes padding + border */
.box {
  width: 100%; max-width: 640px; min-height: 200px;
  padding: 16px 24px;          /* top/bottom left/right */
  margin: 0 auto;              /* centre a block */
  border: 1px solid #ddd; border-radius: 12px;
  box-shadow: 0 4px 12px rgb(0 0 0 / .1);
  overflow: auto;
}
```

## Colours and backgrounds

```css
color: #0a7d4f;  color: rgb(10 125 79);  color: hsl(156 85% 27%);
background: rgb(0 0 0 / .5);
background: linear-gradient(135deg, #0a7d4f, #34c987);
background: url(hero.webp) center / cover no-repeat;
opacity: .8;
```

## Text

```css
font-family: Inter, system-ui, sans-serif;
font-size: 1.125rem; font-weight: 600; line-height: 1.6;
text-align: center; text-transform: uppercase; letter-spacing: .02em;
text-decoration: none;
white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
font-size: clamp(1.5rem, 4vw, 2.5rem);   /* fluid */
```

Units: `px` fixed, `rem` root font size, `em` element font size, `%` parent, `vw/vh` viewport, `fr` grid fraction, `ch` character width.

## Flexbox

```css
.row {
  display: flex;
  flex-direction: row;            /* column */
  justify-content: space-between; /* main axis: flex-start | center | space-around | space-evenly */
  align-items: center;            /* cross axis: stretch | flex-start | flex-end */
  flex-wrap: wrap;
  gap: 16px;
}
.item { flex: 1; }                 /* grow to share space */
.sidebar { flex: 0 0 240px; }      /* grow shrink basis */
.center { display: flex; place-items: center; justify-content: center; }
```

## Grid

```css
.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));   /* responsive cards */
  gap: 20px;
}
.layout {
  display: grid;
  grid-template-columns: 240px 1fr;
  grid-template-areas: "side main";
}
.big { grid-column: span 2; }
.main { grid-area: main; }
```

## Position and display

```css
display: block | inline | inline-block | flex | grid | none;
position: relative;                         /* anchor for absolute children */
.badge { position: absolute; top: 8px; right: 8px; }
.header { position: sticky; top: 0; z-index: 10; }
.toast { position: fixed; bottom: 16px; right: 16px; }
visibility: hidden;                         /* keeps its space */
```

## Responsive

```html
<meta name="viewport" content="width=device-width, initial-scale=1">
```

```css
/* mobile-first */
.cards { grid-template-columns: 1fr; }
@media (min-width: 768px)  { .cards { grid-template-columns: 1fr 1fr; } }
@media (min-width: 1100px) { .cards { grid-template-columns: repeat(3, 1fr); } }
@media (prefers-color-scheme: dark) { :root { --bg: #10141c; } }
@media (prefers-reduced-motion: reduce) { * { animation: none !important; } }
img { max-width: 100%; height: auto; }
```

## Variables

```css
:root { --brand: #0a7d4f; --radius: 12px; --space: 16px; }
.btn { background: var(--brand); border-radius: var(--radius); padding: calc(var(--space) / 2) var(--space); }
[data-theme="dark"] { --brand: #34c987; }
```

## Transitions and animations

```css
.card { transition: transform .2s ease, box-shadow .2s ease; }
.card:hover { transform: translateY(-4px); }
@keyframes spin { to { transform: rotate(360deg); } }
.loader { animation: spin 1s linear infinite; }
```

Animate `transform` and `opacity` for smooth 60 fps.
