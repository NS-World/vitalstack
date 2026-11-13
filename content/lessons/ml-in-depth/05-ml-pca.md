---
title: "PCA and Dimensionality Reduction"
slug: ml-pca
subject: ml-in-depth
lesson: 5
difficulty: intermediate
excerpt: The curse of dimensionality, how PCA finds the directions of greatest variance using eigenvectors, how to read explained variance and a scree plot, and when to use PCA.
---

A dataset with 300 columns is hard to visualise, slow to train on and full of redundant information. **Principal Component Analysis (PCA)** compresses many correlated columns into a few new ones that keep most of the information.

::scene Finding the best camera angle
🫖 | A teapot | Photograph a 3D teapot. From the top it's a circle; from the side you see the spout and handle. | dev
🎥 | Best angle | PCA finds the camera angle that shows the most variation, then the next best. | chip
🗜️ | Compress | 300 columns become 10 components that keep 95% of the information. | byte
::end

## The curse of dimensionality

As columns grow, data gets sparse: points end up far from each other, distances stop meaning much, and models need far more examples. Fewer, better dimensions help.

## PCA on correlated features

Height in cm and height in inches carry the same information. PCA notices that automatically:

```python
import numpy as np
from sklearn.decomposition import PCA
from sklearn.preprocessing import StandardScaler

rng = np.random.default_rng(0)
height_cm = rng.normal(165, 10, 200)
height_in = height_cm / 2.54 + rng.normal(0, 0.5, 200)
weight = 0.9 * height_cm - 85 + rng.normal(0, 6, 200)
shoe = rng.normal(8, 1.5, 200)                       # unrelated
X = StandardScaler().fit_transform(np.column_stack([height_cm, height_in, weight, shoe]))

pca = PCA().fit(X)
print("explained variance ratio:", pca.explained_variance_ratio_.round(3))
print("cumulative:", pca.explained_variance_ratio_.cumsum().round(3))
```

Output:

```text
explained variance ratio: [0.694 0.249 0.054 0.002]
cumulative: [0.694 0.943 0.998 1.   ]
```

Two components keep about 94% of the information in four columns. A **scree plot** is just a bar chart of these ratios: keep components until the curve flattens or the cumulative total reaches your target (often 90–95%).

## What a component is made of

```python
print("PC1 weights:", pca.components_[0].round(2))
print("PC2 weights:", pca.components_[1].round(2))
```

Output:

```text
PC1 weights: [0.59 0.59 0.55 0.05]
PC2 weights: [-0.02 -0.   -0.06  1.  ]
```

PC1 mixes the three body-size columns ("overall size"); PC2 is almost purely shoe size, the unrelated column.

## How PCA works

::walk python | Eigenvectors of the covariance matrix
import numpy as np
X = np.array([[2.0, 1.9], [1.0, 1.1], [3.0, 3.2], [4.0, 3.9]])
Xc = X - X.mean(axis=0)
cov = np.cov(Xc.T)
values, vectors = np.linalg.eigh(cov)
print(values.round(3), np.abs(vectors[:, -1]).round(2))
::notes
1: NumPy does the linear algebra.
2: Four points whose two columns move together. // vars: X=4 rows × 2 columns
3: Centre each column at zero: PCA is about variation around the mean. // vars: Xc=centred data
4: The covariance matrix says how the columns vary together. // vars: cov=[[1.667, 1.617], [1.617, 1.589]]
5: Its eigenvectors are the principal directions; eigenvalues are the variance along each. // deep: eigh is for symmetric matrices and returns eigenvalues in ascending order, so the last one is the biggest: that's PC1. In practice scikit-learn uses the SVD, which gives the same answer more stably. // vars: values=[0.011, 3.245]
6: Nearly all variance (3.245 vs 0.011) lies along one direction, about 45°: both columns in equal measure. // out: [0.011 3.245] [0.72 0.7 ]
::end

## When to use PCA

- **Visualise** high-dimensional data in 2D or 3D.
- **Speed up** training and reduce overfitting when there are many correlated features.
- **Remove noise** by dropping the smallest components.

When not to: when you need to explain individual original features (components are mixtures), or when features are already few and uncorrelated.

```python
from sklearn.datasets import load_digits

digits = load_digits()
pca = PCA(n_components=0.95).fit(digits.data)
print(digits.data.shape[1], "pixel columns ->", pca.n_components_, "components keep 95% of the variance")
```

Output:

```text
64 pixel columns -> 29 components keep 95% of the variance
```

::tip Always scale before PCA when columns have different units, otherwise the column with the biggest numbers (salary in rupees) dominates every component.

## See it as a chart: the scree plot

A scree plot shows how much of the variance each component explains (bars) and the running total (line). Here the first component alone carries most of the information, because three of the four features are really one thing: body size.

```python
import matplotlib.pyplot as plt
import numpy as np
from sklearn.decomposition import PCA
from sklearn.preprocessing import StandardScaler

rng = np.random.default_rng(0)
height_cm = rng.normal(165, 10, 200)
height_in = height_cm / 2.54 + rng.normal(0, 0.5, 200)
weight = 0.9 * height_cm - 85 + rng.normal(0, 6, 200)
shoe = rng.normal(8, 1.5, 200)
X = StandardScaler().fit_transform(np.column_stack([height_cm, height_in, weight, shoe]))
ratio = PCA().fit(X).explained_variance_ratio_

fig, ax = plt.subplots(figsize=(7, 3.6))
parts = [f"PC{i}" for i in range(1, 5)]
ax.bar(parts, ratio, color="#0a7d4f", label="explained by this component")
ax.plot(parts, ratio.cumsum(), "o-", color="#c2255c", label="cumulative")
ax.axhline(0.95, color="#9ca3af", ls="--", lw=1)
ax.text(3.05, 0.9, "95%", color="#6b7280")
ax.set_ylim(0, 1.05)
ax.set_ylabel("share of variance")
ax.legend(frameon=False, loc="center right")
fig.tight_layout()
plt.show()
```

::figure mlid-pca-scree.svg | Bar chart where the first principal component explains most of the variance, with a cumulative line passing 95% by the second or third component

Keep enough components to pass a target such as 95% of the variance. Here 4 features shrink to 2 or 3 with almost no information lost.

## Summary

- Many columns → sparse data, slow models: the curse of dimensionality.
- PCA finds new directions (components) ordered by how much variance they capture.
- Read explained variance and the scree plot to choose how many to keep.
- Components are eigenvectors of the covariance matrix; scale your data first.
