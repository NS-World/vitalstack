---
title: HTML Accessibility Basics
slug: html-accessibility-basics
subject: html
lesson: 19
difficulty: beginner
excerpt: Make pages everyone can use: who relies on accessibility, keyboard access, alt text, labels, contrast, headings, landmarks and when to use ARIA.
---

Accessibility (often shortened to **a11y**: "a", eleven letters, "y") means building pages that everyone can use, including people who are blind, have low vision, are deaf, can't use a mouse, or have trouble reading. The good news: if you've followed this course, you're already doing most of it, because accessible HTML is mostly *correct* HTML.

## Who benefits

- **Screen reader users** (blind or low vision) hear the page read aloud and navigate by headings, links and landmarks.
- **Keyboard users** (people with motor disabilities, power users) move through the page with Tab, Enter and the arrow keys.
- **People who zoom** the page to 200% or more.
- **Deaf and hard-of-hearing users** rely on captions and transcripts.
- **Everyone else, sometimes:** a broken arm, bright sunlight on a phone screen, a slow connection, a noisy train.

Accessible sites also tend to rank better in search, because search engines "read" pages in a similar way to screen readers.

## 1. Use the right element

The single biggest rule. Native elements come with built-in keyboard support and meaning:

```html
<!-- Bad: looks like a button, but keyboard users can't reach or press it -->
<div class="btn" onclick="save()">Save</div>

<!-- Good: focusable, works with Enter and Space, announced as "button" -->
<button type="button" onclick="save()">Save</button>
```

Use `<button>` for actions, `<a href>` for going somewhere, headings for titles, lists for lists.

## 2. Text alternatives

```html
<img src="team.jpg" alt="Our five team members at the 2025 hackathon">
<img src="divider.png" alt="">
```

Every image needs `alt`: a description for meaningful images, empty for decoration. Videos need captions; audio needs a transcript.

## 3. Label every form field

```html
<label for="email">Email address</label>
<input type="email" id="email" name="email" autocomplete="email">
```

A placeholder is not a label. Error messages should be in text near the field and say how to fix the problem ("Enter an email like name@example.com"), not just turn the field red.

## 4. A clear heading structure

One `<h1>`, then `<h2>` and `<h3>` in order. Many screen reader users jump from heading to heading to find what they need, just as sighted users skim.

## 5. Landmarks

```html
<header>…</header>
<nav aria-label="Main">…</nav>
<main>…</main>
<footer>…</footer>
```

Semantic layout elements become **landmarks** that screen readers list and jump to. If you have two `<nav>`s, give each an `aria-label` so users can tell them apart.

## 6. Keyboard access and focus

Try this now: put your mouse away and press **Tab** to move through any website. You should:

- Reach every link, button and field, in a logical order.
- Always see **where** you are (the focus outline).
- Be able to activate everything with Enter or Space.

Never remove the focus outline with CSS (`outline: none`) unless you replace it with another clear style.

A **skip link** lets keyboard users jump past the menu:

```html
<a href="#main" class="skip-link">Skip to content</a>
<header>…long menu…</header>
<main id="main">…</main>
```

## 7. Colour and contrast

- Text needs enough contrast with its background: at least **4.5:1** for normal text. Light grey on white often fails. Browser developer tools can check contrast for you.
- Never use colour alone to carry meaning. "Fields in red are required" fails for colour-blind users; add a word or symbol: "Email (required)".

## 8. Language and page title

```html
<html lang="en">
<head>
  <title>Contact us · VitalStack</title>
</head>
```

`lang` makes screen readers use the right pronunciation. A unique, descriptive `<title>` is the first thing a screen reader announces on a new page.

## 9. ARIA: only when HTML isn't enough

ARIA attributes add extra information for assistive technology:

```html
<button aria-label="Close menu">✕</button>
<button aria-expanded="false" aria-controls="menu">Menu</button>
<p role="alert">Your message has been sent.</p>
```

- `aria-label` gives a name to an element with no visible text, like an icon button.
- `aria-expanded` tells users whether a menu is open.
- `role="alert"` makes a screen reader announce a message immediately.

The first rule of ARIA: **don't use ARIA if a native element does the job.** A `<button>` is better than `<div role="button">`. Wrong ARIA is worse than none.

## Quick checklist

- ☐ Every image has suitable alt text.
- ☐ Every form field has a label.
- ☐ Headings are in order; one `<h1>`.
- ☐ Everything works with the keyboard, and focus is visible.
- ☐ Text contrast is strong enough; colour is never the only signal.
- ☐ `lang` and a unique `<title>` are set.
- ☐ Videos have captions.

## Common mistakes

- **Clickable divs and spans** instead of buttons and links.
- **Removing focus outlines** for looks.
- **"Click here" links** and icon buttons with no name.
- **Adding ARIA everywhere** instead of using the right HTML.

## Exercise

1. Take your form from the Form Inputs lesson and check every field has a working label (click each label: the field should get focus).
2. Use only the Tab key to fill in and submit the form.
3. Add a "Skip to content" link to your semantic page from lesson 17.
4. Find an icon-only button (like ✕) and give it an `aria-label`.

## Summary

- Accessible HTML is mostly correct, semantic HTML.
- Alt text, labels, headings, landmarks and visible focus cover most needs.
- Check contrast and never use colour alone.
- Use ARIA only when native HTML can't express what you need.

Next, you'll put everything together and build a complete personal profile page.
