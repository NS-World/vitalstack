---
title: CSS Transitions and Transforms
slug: css-transitions
subject: css
lesson: 18
difficulty: intermediate
excerpt: Animate changes smoothly with transition, move, scale and rotate elements with transform, choose easing, and add simple keyframe animations responsibly.
---

When a button changes colour on hover, the change normally happens instantly. A **transition** makes it happen smoothly over a short time. Combined with **transforms** (move, scale, rotate), transitions give a site a polished, responsive feel with very little code.

## A first transition

```html
<style>
  .btn {
    background: #2563eb;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 16px;
    transition: background-color 0.3s ease;
  }
  .btn:hover {
    background-color: #1e3a8a;
  }
</style>
<button class="btn">Hover over me</button>
```

The `transition` goes on the **normal** state, not the hover state, so the change animates both when the mouse arrives and when it leaves.

## The parts of a transition

```css
transition: property duration timing-function delay;
```

- **property**: what to animate (`background-color`, `transform`, `opacity`), or `all`.
- **duration**: how long, e.g. `0.2s` or `200ms`.
- **timing function**: the speed curve.
- **delay** (optional): wait before starting.

Several properties can be listed with commas:

```css
.card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
```

Avoid `transition: all`: it can animate things you didn't intend and is slower.

## Timing functions

```html
<style>
  .track { background: #f1f5f9; margin-bottom: 6px; padding: 4px; font: 13px system-ui, sans-serif; }
  .ball {
    width: 90px; padding: 6px; background: #8b5cf6; color: white; border-radius: 999px; text-align: center;
    transition: transform 1.2s;
  }
  .tracks:hover .ball { transform: translateX(220px); }
</style>
<div class="tracks">
  <p>Hover over this area:</p>
  <div class="track"><div class="ball" style="transition-timing-function: linear;">linear</div></div>
  <div class="track"><div class="ball" style="transition-timing-function: ease;">ease</div></div>
  <div class="track"><div class="ball" style="transition-timing-function: ease-in;">ease-in</div></div>
  <div class="track"><div class="ball" style="transition-timing-function: ease-out;">ease-out</div></div>
  <div class="track"><div class="ball" style="transition-timing-function: ease-in-out;">ease-in-out</div></div>
</div>
```

- `ease` (default): starts fast, ends slow. Good general choice.
- `ease-out`: fast start, gentle stop. Great for things appearing.
- `ease-in`: slow start. Good for things leaving.
- `linear`: constant speed. Mostly for spinners.
- `cubic-bezier(...)` creates custom curves.

## Transforms

`transform` changes how an element is drawn without affecting the layout around it:

```css
transform: translateX(20px);       /* move right */
transform: translateY(-4px);       /* move up */
transform: scale(1.05);            /* 5% bigger */
transform: rotate(45deg);          /* rotate */
transform: translateY(-4px) scale(1.02);  /* combine several */
```

Because transforms don't move other elements, they're smooth and cheap for the browser to animate.

## A lifting card

```html
<style>
  body { background: #f1f5f9; padding: 30px; font-family: system-ui, sans-serif; }
  .card {
    width: 240px;
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 24px rgb(0 0 0 / 0.15);
  }
</style>
<div class="card">
  <h3>Hover me</h3>
  <p>I lift up and my shadow grows.</p>
</div>
```

## Which properties to animate

For smooth, 60-frames-per-second animation, prefer **`transform` and `opacity`**. Animating `width`, `height`, `top`, `left` or `margin` forces the browser to recalculate the layout on every frame, which can stutter on phones.

## Keyframe animations

Transitions animate between two states. **Animations** can run on their own, loop, and have many steps:

```html
<style>
  @keyframes pulse {
    0%   { transform: scale(1); }
    50%  { transform: scale(1.08); }
    100% { transform: scale(1); }
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
  .live { display: inline-block; background: #ef4444; color: white; padding: 6px 14px; border-radius: 999px; animation: pulse 1.5s ease-in-out infinite; font-family: system-ui, sans-serif; }
  .spinner { width: 32px; height: 32px; border: 4px solid #e2e8f0; border-top-color: #2563eb; border-radius: 50%; animation: spin 0.8s linear infinite; margin-top: 16px; }
</style>
<span class="live">● LIVE</span>
<div class="spinner" role="status" aria-label="Loading"></div>
```

- `@keyframes name { ... }` defines the steps.
- `animation: name duration timing-function iteration-count;` runs it.
- `infinite` loops forever.

## Respect reduced motion

Some people get dizzy or sick from movement on screen and turn on "reduce motion" in their system settings. Honour it:

```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
```

## Good practice

- Keep UI transitions short: **150–300ms**. Longer feels slow.
- Animate to give feedback (hover, open, close), not just for decoration.
- Avoid things that flash more than three times a second.

## Common mistakes

- **Putting `transition` only on `:hover`**, so the change animates in but snaps back out.
- **Animating layout properties** like `width` and `top` instead of `transform`.
- **`transition: all`** catching unintended changes.
- **Endless decorative animations** that distract from the content.

## Exercise

1. Give your buttons a 200ms colour transition on hover.
2. Make cards lift with `translateY` and a bigger shadow on hover.
3. Create a loading spinner with `@keyframes`.
4. Add the reduced-motion media query to your stylesheet.

## Summary

- `transition: property duration timing` animates changes between states.
- `transform` moves, scales and rotates without affecting layout.
- Animate `transform` and `opacity` for smooth results.
- `@keyframes` creates standalone animations; always respect `prefers-reduced-motion`.

Next, you'll learn CSS variables, which make large stylesheets easy to manage and theme.
