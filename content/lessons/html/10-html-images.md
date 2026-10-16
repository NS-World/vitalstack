---
title: HTML Images
slug: html-images
subject: html
lesson: 10
difficulty: beginner
excerpt: Add images with the img element, write useful alt text, set width and height, choose the right file format and make images load fast with lazy loading.
---

Images make pages friendlier and easier to understand. In this lesson you'll add images with `<img>`, describe them for people who can't see them, and learn the habits that keep pages fast.

## The img element

```html
<img src="https://picsum.photos/id/1025/400/300" alt="A small dog wrapped in a blanket" width="400" height="300">
```

- `src` (source) is the address of the image file, absolute or relative, just like a link.
- `alt` (alternative text) describes the image.
- `width` and `height` give the image's size in pixels.

`<img>` is an empty element: no closing tag.

## Alt text: the most important attribute

Alt text is read aloud by screen readers, shown if the image fails to load, and used by search engines to understand the picture. Write what a person would need to know if they couldn't see it.

```html
<!-- Too vague -->
<img src="chart.png" alt="chart">

<!-- Useful -->
<img src="chart.png" alt="Bar chart: Python is the most used language in the survey at 49%, JavaScript second at 41%">
```

Tips for good alt text:

- Be specific and short; one sentence is usually enough.
- Don't start with "Image of" or "Picture of". Screen readers already say it's an image.
- If an image is pure decoration (a background swirl, a divider), use an **empty** alt: `alt=""`. Screen readers then skip it. Never leave the attribute out completely.
- If the image contains text, include that text in the alt.

## Width and height prevent jumping pages

When a page loads, text often appears before images. If the browser doesn't know an image's size, it can't reserve space, so the text jumps down when the image arrives. Annoying for readers, and Google measures it as a quality signal ("layout shift").

Setting `width` and `height` to the image's real size lets the browser reserve the right space from the start. With CSS you can still make the image responsive later (`max-width: 100%; height: auto;`), and the browser keeps the correct proportions.

## Image file formats

| Format | Best for |
|---|---|
| JPEG (`.jpg`) | Photos |
| PNG (`.png`) | Screenshots, images with sharp edges or transparency |
| WebP / AVIF | Modern format for photos and graphics: much smaller files |
| SVG (`.svg`) | Logos and icons: sharp at any size |
| GIF (`.gif`) | Simple animations (a short video is usually smaller) |

Large images are the most common reason pages load slowly. Resize a photo to the size it will actually be shown (there's no point sending a 4000-pixel photo for a 400-pixel box) and compress it.

## Lazy loading

```html
<img src="photo.jpg" alt="Sunset over the sea" width="800" height="533" loading="lazy">
```

`loading="lazy"` tells the browser to load the image only when the reader scrolls near it. Use it for images further down the page. Don't use it for the main image at the top; that one should load right away.

## Images as links

Put an `<img>` inside an `<a>` to make it clickable:

```html
<a href="https://www.w3.org/">
  <img src="https://picsum.photos/id/0/200/120" alt="Visit the W3C website" width="200" height="120">
</a>
```

When the image is the only content of a link, its alt text becomes the link text, so describe where the link goes.

## Figures with captions

```html
<figure>
  <img src="https://picsum.photos/id/1043/400/260" alt="A wooden desk with a laptop and a notebook" width="400" height="260">
  <figcaption>My study setup: one laptop, one notebook, no distractions.</figcaption>
</figure>
```

`<figure>` groups an image with its caption (`<figcaption>`). The caption is visible to everyone; the alt describes the image itself. They usually say different things.

## Common mistakes

- **Missing alt** attribute. Always include it, even if it's empty for decoration.
- **Wrong path.** `src="Images/Photo.JPG"` and `images/photo.jpg` are different files on most servers. Paths are case-sensitive.
- **Huge files.** Resize and compress before uploading.
- **Using images of text** for headings or buttons. Real text is sharper, searchable and translatable.

## Exercise

1. Add an image from `https://picsum.photos/id/237/300/200` with good alt text, and width and height.
2. Wrap it in a `<figure>` with a caption.
3. Make the image a link to any website.
4. Add a second image further down with `loading="lazy"`.

## Summary

- `<img src="..." alt="..." width="..." height="...">` adds an image.
- Alt text describes the image; use `alt=""` only for decoration.
- Width and height stop the page from jumping while it loads.
- Choose the right format, keep files small, and lazy-load images lower on the page.

Next, you'll organise content with ordered, unordered and description lists.
