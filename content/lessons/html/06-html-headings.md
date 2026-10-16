---
title: HTML Headings
slug: html-headings
subject: html
lesson: 6
difficulty: beginner
excerpt: The six heading levels from h1 to h6, how to build a clear outline with them, and why headings matter for readers, screen readers and Google.
---

Headings are the titles and subtitles of a page. Readers scan them to decide what to read, screen readers use them to jump around, and search engines use them to understand what each part of the page is about. Getting headings right is one of the easiest ways to make a page better.

## Six levels of headings

HTML has six heading elements, from `<h1>` (most important) to `<h6>` (least important):

```html
<h1>Heading level 1</h1>
<h2>Heading level 2</h2>
<h3>Heading level 3</h3>
<h4>Heading level 4</h4>
<h5>Heading level 5</h5>
<h6>Heading level 6</h6>
```

Browsers show `<h1>` largest and boldest, and each level after it a little smaller. But the size is only the default look. What really matters is the **level**, because it describes how important the heading is and where it sits in the page's outline.

## Headings build an outline

Think of a page like a book. The `<h1>` is the book title, each `<h2>` is a chapter, and each `<h3>` is a section inside a chapter.

```html
<h1>Healthy Breakfast Ideas</h1>

<h2>Quick breakfasts</h2>
<h3>Overnight oats</h3>
<p>Mix oats with milk and leave them in the fridge.</p>
<h3>Banana toast</h3>
<p>Toast, peanut butter and a sliced banana.</p>

<h2>Weekend breakfasts</h2>
<h3>Vegetable omelette</h3>
<p>Eggs, onions, tomatoes and spinach.</p>
```

If you read only the headings, you understand the whole page: the topic, the two groups, and the dishes in each. That's the test of a good heading structure.

## Rules for a clean outline

### One h1 per page
The `<h1>` is the main title of the page. Use it once. On a blog, it is the post title; on a lesson page like this one, it is the lesson name.

### Don't skip levels
Go from `<h2>` to `<h3>`, not from `<h2>` straight to `<h5>`. Skipping levels confuses screen reader users, who often navigate by heading level.

```html
<!-- Avoid -->
<h2>Our services</h2>
<h5>Web design</h5>

<!-- Better -->
<h2>Our services</h2>
<h3>Web design</h3>
```

Going back up is fine: after an `<h3>` you can start a new `<h2>` section.

### Choose headings by meaning, not by size
Never pick `<h4>` just because you like its smaller size. Pick the level that fits the outline, and change the size later with CSS. You'll learn how in the CSS lessons.

## Headings and accessibility

Many blind and low-vision users browse with a screen reader. A common habit is to press a key that jumps from heading to heading, like skimming. A page with clear headings is quick to explore; a page with no headings is one long wall of text read aloud from top to bottom.

## Headings and search engines

Google reads your headings to understand the topics on the page. A heading like `<h2>How to boil eggs</h2>` tells it far more than a bold paragraph that says the same thing. Write headings for people first: short, specific and descriptive. That's also what works best for search.

## Don't use headings just to make text big or bold

```html
<!-- Wrong: this isn't a title, just a sentence someone wanted to stand out -->
<h3>Free delivery on all orders today!</h3>
```

If the text isn't a title for the content that follows, use a paragraph and make it stand out with `<strong>` or CSS instead.

## Common mistakes

- **Several h1 elements** on one page for decoration. Keep one main title.
- **Skipping levels** (h2 → h4) to get a smaller font.
- **Using headings for styling** text that isn't a title.
- **Vague headings** like "Section 2" or "More info". Say what the section contains.

## Exercise

Open the breakfast example with **Try it Yourself** and:

1. Add a third `<h2>` section called "Breakfasts for kids" with two `<h3>` dishes.
2. Write one paragraph under each new dish.
3. Read only the headings out loud. Does the outline make sense on its own?
4. Change one `<h3>` into `<h5>` and notice how the outline now "jumps". Change it back.

## Summary

- HTML has six heading levels, `<h1>` to `<h6>`.
- Headings form an outline: one `<h1>`, then `<h2>` sections, then `<h3>` sub-sections.
- Don't skip levels and don't choose a level for its size.
- Good headings help readers, screen reader users and search engines.

Next, you'll learn about paragraphs, line breaks and horizontal rules.
