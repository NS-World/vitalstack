---
title: "HTML Project: Build a Personal Profile Page"
slug: html-project-profile-page
subject: html
lesson: 20
difficulty: beginner
excerpt: Put the whole HTML course into practice by building a complete, accessible personal profile page step by step, ready to style in the CSS course.
---

Time to use everything you've learned. In this project you'll build a complete personal profile page: a header with a menu, an about section, a skills list, a projects table, a contact form and a footer. It's pure HTML for now; in the CSS course you'll make it beautiful.

## What you'll build

The finished page has:

1. A header with your name and a navigation menu that jumps to each section.
2. An "About me" section with a photo and a short introduction.
3. A "Skills" section with a nested list.
4. A "Projects" table.
5. A "Contact" form.
6. A FAQ with collapsible answers.
7. A footer.

Work step by step. After each step, check the result with **Try it Yourself** or in your own editor.

## Step 1: The skeleton

Start with the basic structure from lesson 3 and the semantic layout from lesson 17:

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asha Verma · Web Developer</title>
</head>
<body>
  <a href="#main">Skip to content</a>

  <header>
    <h1>Asha Verma</h1>
    <p>Beginner web developer from Pune</p>
    <nav aria-label="Main">
      <ul>
        <li><a href="#about">About</a></li>
        <li><a href="#skills">Skills</a></li>
        <li><a href="#projects">Projects</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>
    </nav>
  </header>

  <main id="main">
    <!-- sections go here -->
  </main>

  <footer>
    <p><small>© Asha Verma. Built with plain HTML.</small></p>
  </footer>
</body>
</html>
```

Replace the name, city and title with your own (or invent a character). Notice the menu links point to ids you'll create next.

## Step 2: About me

Inside `<main>`, add the first section:

```html
<section id="about">
  <h2>About me</h2>
  <figure>
    <img src="https://picsum.photos/id/64/240/240" alt="Asha smiling, sitting at a desk with a laptop" width="240" height="240">
    <figcaption>Learning to code, one lesson a day.</figcaption>
  </figure>
  <p>Hi! I'm Asha. I started learning web development in January and I'm <strong>building one small project every week</strong>.</p>
  <p>Before coding, I worked in <em>customer support</em>, which taught me to explain things clearly. Now I want to build websites that are <mark>simple and easy to use</mark>.</p>
</section>
```

You used: a heading, a figure with an image and caption, paragraphs, and text formatting.

## Step 3: Skills

```html
<section id="skills">
  <h2>Skills</h2>
  <ul>
    <li>Frontend
      <ul>
        <li>HTML: semantic pages, forms, tables</li>
        <li>CSS: learning now</li>
      </ul>
    </li>
    <li>Tools
      <ul>
        <li>VS Code</li>
        <li>Browser developer tools</li>
      </ul>
    </li>
  </ul>

  <h3>Learning plan</h3>
  <ol>
    <li>Finish the HTML course</li>
    <li>Learn CSS layout with Flexbox and Grid</li>
    <li>Start JavaScript</li>
  </ol>
</section>
```

An unordered list for skills (no order), an ordered list for the plan (order matters).

## Step 4: Projects table

```html
<section id="projects">
  <h2>Projects</h2>
  <table border="1">
    <caption>Things I've built so far</caption>
    <thead>
      <tr>
        <th scope="col">Project</th>
        <th scope="col">What I learned</th>
        <th scope="col">Link</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">Recipe page</th>
        <td>Headings, lists and images</td>
        <td><a href="#">View</a></td>
      </tr>
      <tr>
        <th scope="row">Timetable</th>
        <td>Tables with colspan</td>
        <td><a href="#">View</a></td>
      </tr>
      <tr>
        <th scope="row">This profile</th>
        <td>Putting it all together</td>
        <td><a href="#main">You're here</a></td>
      </tr>
    </tbody>
  </table>
</section>
```

## Step 5: Contact form

```html
<section id="contact">
  <h2>Contact me</h2>
  <form action="https://example.com/contact" method="post">
    <fieldset>
      <legend>Your details</legend>
      <p>
        <label for="name">Name</label><br>
        <input type="text" id="name" name="name" autocomplete="name" required>
      </p>
      <p>
        <label for="email">Email</label><br>
        <input type="email" id="email" name="email" autocomplete="email" required>
      </p>
    </fieldset>

    <p>Reason for contacting:</p>
    <p>
      <label><input type="radio" name="reason" value="project" checked> A project</label><br>
      <label><input type="radio" name="reason" value="hello"> Just saying hello</label>
    </p>

    <p>
      <label for="message">Message</label><br>
      <textarea id="message" name="message" rows="5" cols="40" required minlength="10"></textarea>
    </p>

    <button type="submit">Send message</button>
  </form>
</section>
```

Labels on every field, the right input types, validation, and grouped radio buttons.

## Step 6: FAQ

```html
<section id="faq">
  <h2>FAQ</h2>
  <details>
    <summary>Are you available for freelance work?</summary>
    <p>Small HTML and CSS projects, yes. Use the contact form.</p>
  </details>
  <details>
    <summary>How long have you been coding?</summary>
    <p>Since <time datetime="2026-01">January 2026</time>.</p>
  </details>
</section>
```

## Step 7: Check your work

Go through this checklist:

- ☐ Every menu link jumps to the right section.
- ☐ Only one `<h1>`; headings go h2 → h3 without skipping.
- ☐ Every image has alt text, width and height.
- ☐ Every form field has a label; submitting empty fields shows browser messages.
- ☐ You can reach every link and field using only the Tab key.
- ☐ No ids are repeated.

For a final check, paste your page into the free **W3C Markup Validator** (validator.w3.org, "Validate by Direct Input"). It lists any mistakes in your HTML; fix them until it shows no errors.

## Ideas to extend it

- Add a "Currently learning" section with a short `<blockquote>` from a favourite programmer.
- Add a short YouTube video introducing yourself, with `loading="lazy"` and a title.
- Make a second page, `projects.html`, and link between the two pages.

## What you've learned in this course

Over 20 lessons you've gone from "what is HTML?" to a complete, accessible, semantic page. You can now structure text, links, images, lists, tables, forms and media, and you know how to make pages that work for everyone.

## Summary

- A complete page combines structure, content and forms inside semantic landmarks.
- Check menus, headings, alt text, labels and keyboard access before calling it done.
- The W3C validator is a free way to find mistakes.

Next comes the **CSS course**, where you'll take this exact page and give it colours, fonts, spacing and a modern layout.
