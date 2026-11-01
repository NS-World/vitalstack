---
title: "Filters, Edges and Contours"
slug: cv-filters-edges-contours
subject: computer-vision
lesson: 2
difficulty: intermediate
excerpt: Blur to remove noise, detect edges with Sobel and Canny, clean shapes with morphology, and find and count objects with contours, with runnable NumPy/SciPy and the OpenCV calls.
---

Before deep learning, and still in many factory and document systems today, vision meant clever filters: blur the noise, find the edges, then trace the outlines of objects. These classic tools are fast, need no training data, and also explain what CNN layers learn.

::scene Counting tablets on a conveyor belt
💊 | The task | A pharma line must count tablets in each tray, 20 trays a minute. | dev
🌫️ | Blur | A gentle blur removes camera noise and dust specks. | chip
✏️ | Edges | The edge detector outlines every tablet. | byte
🔢 | Contours | Each closed outline is one object. Count them: 12 tablets. Tray OK. | bugsy
::end

## Blurring removes noise

A blur replaces each pixel with an average of its neighbours:

```python
import numpy as np
from scipy import ndimage

noisy = np.array([[10, 10, 10, 10, 10],
                  [10, 10, 90, 10, 10],                  # one speck of noise
                  [10, 10, 10, 10, 10]], dtype=float)
box = ndimage.uniform_filter(noisy, size=3)
median = ndimage.median_filter(noisy, size=3)
print("box blur centre:", round(box[1, 2], 1), "| median filter centre:", median[1, 2])
```

Output:

```text
box blur centre: 18.9 | median filter centre: 10.0
```

A box (or Gaussian) blur spreads the speck out; a **median** filter removes it completely. Median filters are excellent for "salt-and-pepper" noise.

## Edges with Sobel

::walk python | Horizontal gradient at one pixel
row = [20, 20, 20, 200, 200]
x = 2
gradient = row[x + 1] - row[x - 1]
is_edge = abs(gradient) > 50
print(gradient, is_edge)
::notes
1: One row of pixels: dark, then suddenly bright. // vars: row=[20, 20, 20, 200, 200]
2: Look at pixel index 2, just before the jump. // vars: x=2
3: The gradient: right neighbour minus left neighbour. A big change means an edge. // deep: The Sobel filter does this in 2D with weights [-1, 0, 1] across and [1, 2, 1] down, which also smooths a little. Canny then thins edges to one pixel and keeps only strong, connected ones. // vars: gradient=180
4: Bigger than our threshold: an edge. // vars: is_edge=True
5: Edge detection is just measuring how fast brightness changes. // out: 180 True
::end

```python
img = np.zeros((7, 7))
img[2:5, 2:5] = 255                                  # a bright square
sx = ndimage.sobel(img, axis=1)
sy = ndimage.sobel(img, axis=0)
edges = np.hypot(sx, sy) > 0
print(edges.astype(int))
```

Output:

```text
[[0 0 0 0 0 0 0]
 [0 1 1 1 1 1 0]
 [0 1 1 1 1 1 0]
 [0 1 1 0 1 1 0]
 [0 1 1 1 1 1 0]
 [0 1 1 1 1 1 0]
 [0 0 0 0 0 0 0]]
```

The flat centre has no edge; everything around the border of the square lights up.

## Counting objects (connected components)

```python
tray = np.zeros((10, 14), dtype=int)
for r, c in [(2, 2), (2, 8), (6, 4), (6, 10)]:      # four tablets
    tray[r:r + 2, c:c + 3] = 1
tray[8, 0] = 1                                       # a dust speck
cleaned = ndimage.binary_opening(tray, structure=np.ones((2, 2)))
labels, count = ndimage.label(cleaned)
print("objects before cleaning:", ndimage.label(tray)[1], "| after:", count)
```

Output:

```text
objects before cleaning: 5 | after: 4
```

**Morphological opening** (erode then dilate) removed the one-pixel speck but kept the tablets. Then labelling counted the separate blobs.

## The OpenCV version

```python
import cv2

img = cv2.imread("tray.jpg", cv2.IMREAD_GRAYSCALE)
blur = cv2.GaussianBlur(img, (5, 5), 0)
edges = cv2.Canny(blur, 50, 150)
_, mask = cv2.threshold(blur, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)
mask = cv2.morphologyEx(mask, cv2.MORPH_OPEN, cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (5, 5)))
contours, _ = cv2.findContours(mask, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
tablets = [c for c in contours if cv2.contourArea(c) > 200]      # ignore tiny blobs
print("tablets:", len(tablets))
```

Contours also give each object's area, perimeter and bounding box: enough to check size, shape and position on a production line.

::tip Classic vision works best with controlled lighting and a fixed camera (factories, scanners). For messy real-world photos (street scenes, phones), use deep-learning detectors: next lesson.

## Summary

- Blurs smooth noise; median filters remove specks.
- Edges = large brightness gradients (Sobel, Canny).
- Morphology (opening/closing) cleans masks; labelling and contours find and count objects.
- Use OpenCV for speed; prefer deep learning for uncontrolled scenes.
