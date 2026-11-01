---
title: "Computer Vision Basics: Images as Arrays"
slug: cv-images-as-arrays
subject: computer-vision
lesson: 1
difficulty: intermediate
excerpt: How computers store images as arrays of pixels, colour channels (RGB vs BGR), grayscale, resizing, cropping, thresholding and drawing, with NumPy and Pillow you can run, plus the OpenCV equivalents.
---

Face unlock, UPI QR scanning, number-plate recognition at toll plazas, crop disease detection from a farmer's phone: computer vision is everywhere. It starts with one idea: **an image is just an array of numbers**.

::scene What the camera really sees
📷 | A photo | A selfie looks like a face to you. To the computer it's a grid of millions of numbers. | dev
🎨 | Channels | Each pixel has three numbers: red, green and blue, each from 0 to 255. | chip
⚫ | Grayscale | Mix the three into one brightness number: simpler and often enough. | byte
✂️ | Edit | Cropping is slicing, brightening is adding, resizing is resampling. It's all array maths. | bugsy
::end

## Building an image from numbers

```python
import numpy as np

img = np.zeros((4, 6, 3), dtype=np.uint8)     # height 4, width 6, 3 channels (RGB)
img[:, :3] = [255, 153, 51]                    # left half saffron
img[:, 3:] = [19, 136, 8]                      # right half green
print(img.shape, img.dtype)
print("top-left pixel:", img[0, 0].tolist(), "| top-right pixel:", img[0, -1].tolist())
```

Output:

```text
(4, 6, 3) uint8
top-left pixel: [255, 153, 51] | top-right pixel: [19, 136, 8]
```

Shape is (height, width, channels). `uint8` means each value is 0–255.

::tip **OpenCV loads images as BGR, not RGB.** `cv2.imread` gives blue first. If colours look wrong (blue faces), convert with `cv2.cvtColor(img, cv2.COLOR_BGR2RGB)`.

## Grayscale, crop, resize with Pillow

```python
from PIL import Image

pil = Image.fromarray(img)
gray = np.array(pil.convert("L"))              # one brightness channel
print("gray values:", gray[0].tolist())
crop = img[1:3, 2:5]                            # slicing = cropping
print("crop shape:", crop.shape)
small = np.array(pil.resize((3, 2)))
print("resized shape:", small.shape)
```

Output:

```text
gray values: [172, 172, 172, 86, 86, 86]
crop shape: (2, 3, 3)
resized shape: (2, 3, 3)
```

## Grayscale by hand

::walk python | Weighted average of R, G and B
r, g, b = 255, 153, 51
gray = 0.299 * r + 0.587 * g + 0.114 * b
level = round(gray)
print(level)
::notes
1: The saffron pixel's three channels. // vars: r=255, g=153, b=51
2: Human eyes are most sensitive to green, then red, then blue, so the weights differ. // deep: These are the ITU-R BT.601 weights that Pillow's "L" mode and OpenCV's COLOR_RGB2GRAY use. A plain average (r+g+b)/3 looks slightly wrong to our eyes. // vars: gray=172.4
3: Round to a whole brightness level. // vars: level=172
4: One number per pixel instead of three. Pillow's convert("L") gave the same 172 above. // out: 172
::end

## Thresholding: separate object from background

Turn a grayscale image into black and white: a key step for reading documents, QR codes and simple object detection.

```python
scan = np.array([[30, 40, 200, 210], [35, 220, 230, 50], [25, 30, 215, 45]])
binary = (scan > 128).astype(np.uint8) * 255
print(binary)
print("ink pixels:", int((binary == 255).sum()))
```

Output:

```text
[[  0   0 255 255]
 [  0 255 255   0]
 [  0   0 255   0]]
ink pixels: 5
```

## The OpenCV way

```python
import cv2

img = cv2.imread("receipt.jpg")                                # BGR
gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
small = cv2.resize(gray, (640, 480))
_, binary = cv2.threshold(small, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)  # Otsu picks the cut-off
cv2.rectangle(img, (50, 50), (200, 120), (0, 255, 0), 2)       # draw a green box
cv2.imwrite("receipt_bw.png", binary)
```

Install with `pip install opencv-python`. Otsu's method chooses the threshold automatically from the image's histogram.

## Summary

- An image is an array of shape (height, width, channels) with values 0–255.
- RGB vs BGR: OpenCV uses BGR.
- Grayscale = weighted mix of channels; cropping = slicing; thresholding = comparison.
- NumPy and Pillow for basics; OpenCV for fast, full-featured processing.
