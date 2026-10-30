---
title: Classification: Predicting Categories
slug: ml-classification
subject: machine-learning
lesson: 4
difficulty: intermediate
excerpt: Predict yes/no answers with logistic regression and k-nearest neighbours, and judge them with accuracy and a confusion matrix.
---

Regression predicts numbers. **Classification** predicts categories: pass or fail, spam or not, which course a student will choose. The workflow is identical; only the model and the metrics change.

## The data

Will a student pass? We know their weekly study hours and attendance:

```python
import numpy as np
import pandas as pd

rng = np.random.default_rng(5)
n = 300
hours = rng.uniform(0, 12, n)
attendance = rng.uniform(40, 100, n)
chance = 1 / (1 + np.exp(-(0.6 * hours + 0.08 * attendance - 8)))
passed = (rng.random(n) < chance).astype(int)

df = pd.DataFrame({"hours": hours.round(1), "attendance": attendance.round(), "passed": passed})
print(df.head())
print(df["passed"].value_counts().to_dict())
```

Output:

```text
   hours  attendance  passed
0    9.7        79.0       1
1    9.7        51.0       1
2    6.2        62.0       1
3    3.4        42.0       0
4    0.6        44.0       0
{1: 186, 0: 114}
```

The label `passed` is 1 (pass) or 0 (fail): 186 students passed and 114 failed.

## Logistic regression

Despite its name, **logistic regression** is a classification model. It predicts the **probability** of class 1 and calls it "pass" if that probability is above 0.5.

```python
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import accuracy_score

X = df[["hours", "attendance"]]
y = df["passed"]
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.25, random_state=1, stratify=y)

clf = LogisticRegression()
clf.fit(X_train, y_train)
pred = clf.predict(X_test)
print("Accuracy:", round(accuracy_score(y_test, pred), 3))
```

Output:

```text
Accuracy: 0.84
```

`stratify=y` keeps the same pass/fail ratio in the training and test sets, which matters when one class is rarer.

## Probabilities

```python
students = pd.DataFrame({"hours": [2, 6, 10], "attendance": [60, 80, 95]})
print(clf.predict_proba(students)[:, 1].round(2))
print(clf.predict(students))
```

Output:

```text
[0.16 0.85 0.99]
[0 1 1]
```

The first student has a 16% chance of passing, so the model says fail (0). A teacher could use the probabilities to decide who needs extra help early.

## Beyond accuracy: the confusion matrix

Accuracy alone can fool you. If 95% of students pass, a model that always says "pass" has 95% accuracy and is useless. The **confusion matrix** shows exactly what kind of mistakes the model makes:

```python
from sklearn.metrics import confusion_matrix

print(confusion_matrix(y_test, pred))
```

Output:

```text
[[25  3]
 [ 9 38]]
```

| | predicted fail | predicted pass |
|---|---|---|
| **actually failed** | 25 ✅ | 3 ❌ |
| **actually passed** | 9 ❌ | 38 ✅ |

The model wrongly predicted "pass" for 3 students who actually failed, and "fail" for 9 who passed. If the goal is to find students who need help early, those 3 are the costly mistakes: they'd get no extra support. You could lower the 0.5 threshold to catch them, at the cost of flagging more students who would have passed anyway.

::tip Two more metrics you'll hear: **precision** (of those predicted pass, how many really passed?) and **recall** (of those who really passed, how many did we find?). `from sklearn.metrics import classification_report` prints both.

## Another model: k-nearest neighbours

**k-NN** classifies a new student by looking at the k most similar students in the training data and taking a vote. Because it measures distance, features must be on similar scales, so we add a **scaler** in a pipeline:

```python
from sklearn.neighbors import KNeighborsClassifier
from sklearn.pipeline import make_pipeline
from sklearn.preprocessing import StandardScaler

knn = make_pipeline(StandardScaler(), KNeighborsClassifier(n_neighbors=7))
knn.fit(X_train, y_train)
print("k-NN accuracy:", round(knn.score(X_test, y_test), 3))
```

Output:

```text
k-NN accuracy: 0.84
```

Same `fit` / `score` methods, a completely different algorithm. That consistency is why scikit-learn is so pleasant to use.

::hint Quick check | A cancer test model has 99% accuracy, but the disease affects 1% of people. Is it good? Not necessarily: a model that always says "healthy" also scores 99%. Check the confusion matrix and recall.

## Summary

- Classification predicts categories; logistic regression and k-NN are good first models.
- `predict_proba` gives probabilities; `predict` gives the class.
- Read the confusion matrix, not just accuracy.
- Scale features for distance-based models; `make_pipeline` keeps the steps together.
