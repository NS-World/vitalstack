---
title: Colour Contrast Checker (WCAG)
slug: color-contrast-checker
parent: tools
template: page-tool.php
icon: 🎨
order: 190
excerpt: Pick a text colour and a background colour to get the WCAG contrast ratio and an instant pass or fail for AA and AAA, with a live preview.
---
## Why contrast matters

About one in twelve men and one in two hundred women have some form of colour vision deficiency, and almost everyone reads a phone in bright sunlight at some point. Low-contrast text (light grey on white, for example) is hard or impossible to read for many people. Good contrast is part of **accessibility**, and it also simply makes a site feel more professional.

## How to use the checker

Pick a **text colour** and a **background colour**. The preview updates instantly, the big number shows the **contrast ratio**, and the table tells you which WCAG levels pass.

| Level | Normal text | Large text (about 24px, or 19px bold) |
|---|---|---|
| **AA** (the usual target) | 4.5 : 1 | 3 : 1 |
| **AAA** (enhanced) | 7 : 1 | 4.5 : 1 |

Ratios go from **1 : 1** (same colour, invisible) to **21 : 1** (black on white).

## How the ratio is calculated

Each colour is converted to its **relative luminance**: how bright it looks to the human eye, where green counts much more than blue. The ratio is `(lighter + 0.05) / (darker + 0.05)`.

```python
def channel(c):
    c = c / 255
    return c / 12.92 if c <= 0.03928 else ((c + 0.055) / 1.055) ** 2.4

def luminance(r, g, b):
    return 0.2126 * channel(r) + 0.7152 * channel(g) + 0.0722 * channel(b)

ratio = (luminance(255, 255, 255) + 0.05) / (luminance(27, 31, 39) + 0.05)
print(round(ratio, 2))
```

## Design tips

1. Never use colour alone to show meaning (like red for errors): add an icon or text.
2. Check buttons, links and placeholder text, not only body text.
3. Check dark mode separately; it has its own pairs of colours.
