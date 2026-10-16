---
title: CSS Colors
slug: css-colors
subject: css
lesson: 4
difficulty: beginner
excerpt: Colour names, hex codes, rgb, hsl and transparency, how to pick a small colour palette, and how to check text contrast.
---

Colour is one of the first things people notice about a website. CSS gives you several ways to write the same colour, and knowing them all makes it easy to copy colours from design tools and adjust them.

## Where colours are used

```html
<style>
  .demo {
    color: white;              /* text colour */
    background-color: #2563eb; /* background */
    border: 4px solid #1e40af; /* border colour */
    padding: 16px;
  }
</style>
<div class="demo">Text, background and border colours</div>
```

`color` always means the **text** colour. Backgrounds and borders have their own properties.

## Colour names

```css
p { color: tomato; }
h1 { color: navy; }
```

CSS knows about 140 names like `red`, `teal`, `gold`, `slategray` and `rebeccapurple`. They're easy to read but limited, so real designs use the formats below.

## Hex codes

```css
h1 { color: #2563eb; }
p  { color: #333; }   /* short form of #333333 */
```

A hex colour is `#` followed by three pairs of hexadecimal digits (0–9, a–f) for **red, green and blue**. `00` means none of that colour; `ff` means full.

- `#000000` is black, `#ffffff` is white.
- `#ff0000` is pure red, `#00ff00` green, `#0000ff` blue.
- If each pair has two equal digits, you can shorten it: `#336699` → `#369`.

Hex is the format you'll copy most often from design tools like Figma.

## rgb()

```css
h1 { color: rgb(37, 99, 235); }
```

The same red-green-blue idea, with numbers from 0 to 255. `rgb(37, 99, 235)` is exactly the same colour as `#2563eb`.

## hsl(): the most human-friendly format

```css
.primary { color: hsl(221, 83%, 53%); }
```

- **Hue** (0–360): the position on the colour wheel. 0 is red, 120 green, 240 blue.
- **Saturation** (0–100%): from grey to full colour.
- **Lightness** (0–100%): 0% is black, 50% normal, 100% white.

HSL makes it easy to create shades of one colour: keep the hue, change the lightness.

```html
<style>
  .shade { padding: 10px; color: white; }
  .s1 { background: hsl(221, 83%, 30%); }
  .s2 { background: hsl(221, 83%, 45%); }
  .s3 { background: hsl(221, 83%, 60%); }
  .s4 { background: hsl(221, 83%, 75%); color: #111; }
</style>
<div class="shade s1">Dark</div>
<div class="shade s2">Medium</div>
<div class="shade s3">Light</div>
<div class="shade s4">Lighter</div>
```

## Transparency

Add a fourth value for **alpha** (opacity), from 0 (invisible) to 1 (solid):

```html
<style>
  .photo {
    background: url("https://picsum.photos/id/1018/400/200") center/cover;
    height: 200px;
    display: flex;
    align-items: end;
  }
  .caption {
    background: rgb(0 0 0 / 0.6);
    color: white;
    padding: 12px;
    width: 100%;
  }
</style>
<div class="photo"><div class="caption">A dark see-through caption over a photo</div></div>
```

You can write transparency as `rgb(0 0 0 / 0.6)`, `rgba(0, 0, 0, 0.6)`, `hsl(221 83% 53% / 0.5)` or an 8-digit hex like `#2563eb80`. They all work in modern browsers.

There's also the `opacity` property, but it makes the **whole element** see-through, including its text. To fade only a background, use a transparent colour instead.

## currentColor and transparent

- `transparent` is fully see-through.
- `currentColor` means "the current text colour". It's useful for borders and icons that should match the text: `border: 2px solid currentColor;`.

## Choosing a palette

Most good websites use very few colours:

- **One brand colour** for links, buttons and highlights.
- **Neutrals** (near-black, greys, off-white) for text and backgrounds.
- **Status colours**: green for success, red for errors, yellow/orange for warnings.

Use pure black (`#000`) for text sparingly; a very dark grey like `#1f2937` is softer to read.

## Contrast matters

Text must stand out from its background. The accessibility guideline (WCAG) asks for a contrast ratio of at least **4.5:1** for normal text and **3:1** for large text. Light grey text on white often fails. In Chrome or Firefox, open developer tools (F12), click a text element's colour swatch, and the colour picker shows the contrast ratio.

## Common mistakes

- **Forgetting the `#`** in hex codes: `color: 2563eb;` is ignored.
- **Low-contrast text**, like `#aaa` on white.
- **Using `opacity` to fade a background**, which fades the text too.
- **Too many colours.** Pick a small palette and reuse it.

## Exercise

1. Write the same colour as a name, hex, rgb and hsl and put four boxes side by side to compare.
2. Create five shades of green by changing only the lightness in `hsl()`.
3. Add a semi-transparent caption over an image.
4. Check the contrast of your text with your browser's developer tools.

## Summary

- `color` is text colour; `background-color` and `border-color` are separate.
- Write colours as names, hex (`#2563eb`), `rgb()` or `hsl()`.
- Add alpha for transparency; `opacity` fades the whole element.
- Use a small palette and keep text contrast at 4.5:1 or higher.

Next, you'll style backgrounds with colours, images and gradients.
