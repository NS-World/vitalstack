---
title: "k-Nearest Neighbours, Naive Bayes and SVM"
slug: ml-knn-naive-bayes-svm
subject: ml-in-depth
lesson: 3
difficulty: intermediate
excerpt: Three classic classifiers with three different ideas: ask your neighbours (k-NN), count evidence with probability (Naive Bayes), and draw the widest boundary (SVM). With scikit-learn.
---

Different models make different assumptions about the world. Knowing three very different classic ideas makes you better at choosing, and at explaining your choice in interviews.

::scene Three ways to decide
🏘️ | Neighbours | k-NN: "Show me the 5 most similar customers. What did most of them do?" | dev
🧮 | Evidence | Naive Bayes: "The words 'free' and 'winner' each make spam more likely. Multiply the evidence." | chip
🛣️ | Widest road | SVM: "Draw the boundary with the widest empty road between the two groups." | byte
::end

## k-Nearest Neighbours: ask the neighbours

k-NN stores the training data. To classify a new point, it finds the k closest points and takes a vote. It's a "lazy learner": no training, all the work at prediction time.

::walk python | Classify by the 3 nearest neighbours
points = [((1, 1), "A"), ((2, 1), "A"), ((8, 9), "B"), ((9, 8), "B"), ((2, 2), "A")]
new = (3, 2)
dist = sorted((((x - new[0]) ** 2 + (y - new[1]) ** 2) ** 0.5, label) for (x, y), label in points)
votes = [label for d, label in dist[:3]]
print(votes, max(set(votes), key=votes.count))
::notes
1: Five labelled points: three of class A near the origin, two of class B far away. // vars: points=5 labelled points
2: The new point to classify. // vars: new=(3, 2)
3: Euclidean distance from the new point to every stored point, sorted nearest first. // deep: Manhattan distance (sum of absolute differences) is another option. Because k-NN is all about distance, always scale features first. // vars: dist=[(1.0, 'A'), (1.41, 'A'), (2.24, 'A'), ...]
4: Take the labels of the 3 nearest. // vars: votes=['A', 'A', 'A']
5: Majority vote: class A. // out: ['A', 'A', 'A'] A
::end

## Naive Bayes: multiply the evidence

Naive Bayes applies Bayes' theorem and "naively" assumes each feature is independent given the class. It's fast, works well with text, and needs little data.

```python
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.naive_bayes import MultinomialNB

messages = ["win a free prize now", "free cash winner", "claim your free reward",
            "meeting at 5 pm", "can you send the notes", "lunch tomorrow?"]
labels = ["spam", "spam", "spam", "ham", "ham", "ham"]

vec = CountVectorizer()
nb = MultinomialNB().fit(vec.fit_transform(messages), labels)
tests = ["free prize waiting", "send me the meeting notes"]
print(dict(zip(tests, nb.predict(vec.transform(tests)).tolist())))
```

Output:

```text
{'free prize waiting': 'spam', 'send me the meeting notes': 'ham'}
```

Types: **Multinomial** NB for word counts, **Bernoulli** NB for yes/no features, **Gaussian** NB for continuous numbers. **Laplace smoothing** (the `alpha` parameter) stops a word never seen in one class from making its probability exactly zero.

## SVM: the widest road

A Support Vector Machine finds the boundary with the largest **margin**: the widest gap between classes. The points touching the margin are the **support vectors**. With a **kernel** (like RBF), an SVM can draw curved boundaries.

```python
import numpy as np
from sklearn.datasets import make_moons
from sklearn.svm import SVC
from sklearn.model_selection import train_test_split

X, y = make_moons(n_samples=400, noise=0.2, random_state=0)
a, b, ya, yb = train_test_split(X, y, random_state=0)
for kernel in ("linear", "rbf"):
    svm = SVC(kernel=kernel, C=1.0, gamma="scale").fit(a, ya)
    print(f"{kernel:>6} kernel: accuracy {svm.score(b, yb):.2f}, support vectors {svm.n_support_.sum()}")
```

Output:

```text
linear kernel: accuracy 0.90, support vectors 103
   rbf kernel: accuracy 0.93, support vectors 69
```

The moon-shaped classes need a curved boundary, so the RBF kernel wins. **C** trades margin width for training errors (higher C = fit training data harder); **gamma** controls how wiggly an RBF boundary can be.

## Choosing between them

| Model | Strengths | Weaknesses |
|---|---|---|
| k-NN | Simple, no training, any shape of boundary | Slow on big data; needs scaling; struggles with many features |
| Naive Bayes | Very fast, great for text, little data needed | Independence assumption; probabilities are often overconfident |
| SVM | Strong on medium-size data, curved boundaries with kernels | Slow on very large data; needs scaling and tuning of C and gamma |

::tip In an interview, "which model would you use?" is really "what are the trade-offs?". Mention data size, number of features, need for explanations and speed.

## See it as a chart: the SVM's widest road

A linear SVM picks the line with the widest empty "road" between the two classes. The circled points touching the road's edges are the **support vectors**; they alone decide where the line goes.

```python
import matplotlib.pyplot as plt
import numpy as np
from sklearn.svm import SVC

rng = np.random.default_rng(4)
X = np.vstack([rng.normal([2, 2], 0.6, (25, 2)), rng.normal([5, 5], 0.6, (25, 2))])
y = np.array([0] * 25 + [1] * 25)
svm = SVC(kernel="linear", C=10).fit(X, y)

xx, yy = np.meshgrid(np.linspace(0, 7, 200), np.linspace(0, 7, 200))
score = svm.decision_function(np.column_stack([xx.ravel(), yy.ravel()])).reshape(xx.shape)
fig, ax = plt.subplots(figsize=(5.5, 4.5))
ax.contourf(xx, yy, score, levels=[-1, 1], colors=["#fff4d6"])
ax.contour(xx, yy, score, levels=[-1, 0, 1], colors=["#f59e0b", "#1b1f27", "#f59e0b"], linestyles=["--", "-", "--"])
ax.scatter(X[y == 0, 0], X[y == 0, 1], color="#2563eb", s=25, label="class A")
ax.scatter(X[y == 1, 0], X[y == 1, 1], color="#0a7d4f", s=25, label="class B")
ax.scatter(*svm.support_vectors_.T, s=140, facecolors="none", edgecolors="#c2255c", lw=2, label="support vectors")
ax.legend(frameon=False, loc="upper left")
ax.set_title("SVM: the widest road")
fig.tight_layout()
plt.show()
```

::figure mlid-svm-margin.svg | Two clusters of dots split by a solid line inside a shaded road with dashed edges; the few points on the edges are circled as support vectors

Move any other point and the line stays put; move a support vector and the line moves. That is why SVMs are robust to points far from the boundary.

## Summary

- **k-NN** votes among the closest training points; scale features first.
- **Naive Bayes** multiplies per-feature evidence; excellent baseline for text.
- **SVM** maximises the margin; kernels give curved boundaries; tune C and gamma.
