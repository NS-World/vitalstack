---
title: "CNNs for Images: Convolution, Pooling and Transfer Learning"
slug: dl-cnn-images
subject: deep-learning
lesson: 3
difficulty: intermediate
excerpt: How convolutional neural networks see images: filters that detect edges, pooling, feature maps, a Keras CNN, and transfer learning with pretrained models like ResNet. Convolution runs in NumPy.
---

To a computer, an image is a grid of numbers. A normal dense network would treat every pixel separately and need millions of weights. **Convolutional Neural Networks (CNNs)** instead slide small filters across the image, spotting edges, then shapes, then whole objects, using the same few weights everywhere.

::scene How a CNN sees a cat
🖼️ | Pixels | A 224×224 photo is just 150,528 numbers. | byte
🔦 | Filters | A 3×3 filter slides across the image, lighting up wherever it finds a vertical edge. | dev
🧩 | Shapes | Deeper layers combine edges into ears, eyes and whiskers. | chip
🐱 | Answer | The final layer says: 97% cat. | bugsy
::end

## Convolution by hand

A filter (kernel) is a tiny grid of weights. At each position, multiply it with the pixels under it and add up.

```python
import numpy as np

image = np.array([[0, 0, 9, 9],
                  [0, 0, 9, 9],
                  [0, 0, 9, 9],
                  [0, 0, 9, 9]], dtype=float)          # dark left, bright right
edge = np.array([[-1, 0, 1],
                 [-1, 0, 1],
                 [-1, 0, 1]], dtype=float)              # vertical-edge detector

out = np.zeros((2, 2))
for r in range(2):
    for c in range(2):
        out[r, c] = np.sum(image[r:r + 3, c:c + 3] * edge)
print(out)
```

Output:

```text
[[27. 27.]
 [27. 27.]]
```

Big numbers mean "edge found here". In a CNN, the network **learns** its filter values during training instead of you writing them.

::walk python | One position of the filter
patch = [[0, 0, 9], [0, 0, 9], [0, 0, 9]]
kernel = [[-1, 0, 1], [-1, 0, 1], [-1, 0, 1]]
products = [patch[r][c] * kernel[r][c] for r in range(3) for c in range(3)]
value = sum(products)
print(value)
::notes
1: The 3×3 patch of pixels under the filter: dark, dark, bright. // vars: patch=[[0, 0, 9], [0, 0, 9], [0, 0, 9]]
2: The vertical-edge filter: negative on the left, positive on the right. // vars: kernel=[[-1, 0, 1], ...]
3: Multiply each pixel by the matching weight. // vars: products=[0, 0, 9, 0, 0, 9, 0, 0, 9]
4: Add them up. Dark-to-bright left-to-right gives a big positive number. // deep: A flat region (all pixels equal) would give 0: the left and right columns cancel. That's how a filter responds only to its pattern. // vars: value=27
5: One cell of the feature map. Slide and repeat over the whole image. // out: 27
::end

## Pooling: shrink and keep the strongest signal

**Max pooling** keeps the largest value in each 2×2 block, halving width and height:

```python
fmap = np.array([[1, 3, 2, 0], [4, 6, 1, 1], [0, 2, 9, 5], [1, 1, 3, 7]])
pooled = fmap.reshape(2, 2, 2, 2).max(axis=(1, 3))
print(pooled)
```

Output:

```text
[[6 2]
 [2 9]]
```

## A CNN in Keras

```python
from tensorflow import keras

model = keras.Sequential([
    keras.Input(shape=(32, 32, 3)),
    keras.layers.Conv2D(32, 3, activation="relu", padding="same"),
    keras.layers.MaxPooling2D(),
    keras.layers.Conv2D(64, 3, activation="relu", padding="same"),
    keras.layers.MaxPooling2D(),
    keras.layers.Flatten(),
    keras.layers.Dense(128, activation="relu"),
    keras.layers.Dropout(0.3),
    keras.layers.Dense(10, activation="softmax"),         # 10 classes, e.g. CIFAR-10
])
model.compile("adam", "sparse_categorical_crossentropy", metrics=["accuracy"])
```

## Transfer learning: stand on giants' shoulders

Training a big CNN from scratch needs millions of images. Instead, take a model pretrained on ImageNet (ResNet, EfficientNet, MobileNet), freeze its layers, and train only a small new head on your images: often a few hundred per class is enough.

```python
from tensorflow import keras

base = keras.applications.MobileNetV2(input_shape=(224, 224, 3), include_top=False, weights="imagenet")
base.trainable = False
model = keras.Sequential([base, keras.layers.GlobalAveragePooling2D(), keras.layers.Dense(3, activation="softmax")])
```

## Try it now: a classifier on real digit images

```python
from sklearn.datasets import load_digits
from sklearn.neural_network import MLPClassifier
from sklearn.model_selection import train_test_split

digits = load_digits()                                     # 8×8 grayscale digits
a, b, ya, yb = train_test_split(digits.data / 16, digits.target, random_state=0)
clf = MLPClassifier(hidden_layer_sizes=(64,), max_iter=500, random_state=0).fit(a, ya)
print("digit accuracy:", round(clf.score(b, yb), 3))
```

Output:

```text
digit accuracy: 0.973
```

::tip Use **data augmentation** (random flips, rotations, crops, brightness) so the model sees varied versions of each photo. It's one of the cheapest ways to improve image models.

## See it as a chart: what a filter sees

Top row: real 8×8 handwritten digits from scikit-learn. Bottom row: the same digits after sliding the vertical-edge filter from above over them. The bright and dark areas are where the strokes have vertical edges, exactly the kind of feature a CNN's first layer learns.

```python
import matplotlib.pyplot as plt
import numpy as np
from sklearn.datasets import load_digits

digits = load_digits()
edge = np.array([[-1, 0, 1], [-1, 0, 1], [-1, 0, 1]], dtype=float)

def convolve(img, kernel):
    out = np.zeros((img.shape[0] - 2, img.shape[1] - 2))
    for r in range(out.shape[0]):
        for c in range(out.shape[1]):
            out[r, c] = (img[r:r + 3, c:c + 3] * kernel).sum()
    return out

fig, axes = plt.subplots(2, 6, figsize=(9, 3.4))
for i, col in enumerate(range(6)):
    img = digits.images[i]
    axes[0, col].imshow(img, cmap="gray_r")
    axes[0, col].set_title(f"digit {digits.target[i]}", fontsize=9)
    axes[1, col].imshow(convolve(img, edge), cmap="RdBu")
    for ax in (axes[0, col], axes[1, col]):
        ax.set_xticks([])
        ax.set_yticks([])
axes[0, 0].set_ylabel("input")
axes[1, 0].set_ylabel("edge filter")
fig.tight_layout()
plt.show()
```

::figure dl-cnn-edge-filter.svg | Top row: six small handwritten digits 0 to 5; bottom row: the same digits after a vertical-edge filter, with red and blue marking the left and right edges of each stroke

A CNN learns dozens of filters like this one by itself, then stacks layers so later filters combine edges into curves, loops and finally whole digits.

## Summary

- Convolution slides learned filters over images to make feature maps.
- Pooling shrinks maps and keeps strong signals; deeper layers see bigger shapes.
- Keras: `Conv2D` → `MaxPooling2D` → … → `Dense` softmax.
- Transfer learning with pretrained models needs far less data.
