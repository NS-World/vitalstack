---
title: End-to-End Project: Classifying Flowers
slug: ml-project-iris
subject: machine-learning
lesson: 5
difficulty: intermediate
excerpt: Run a complete machine learning project on the classic Iris dataset with a decision tree: explore, train, evaluate and explain.
---

Time to put everything together. The **Iris dataset** is the most famous starter dataset in machine learning: 150 flowers, 4 measurements each, and 3 species to predict. It comes built into scikit-learn, so it works even offline.

## 1. Load and explore

```python
import pandas as pd
from sklearn.datasets import load_iris

iris = load_iris(as_frame=True)
df = iris.frame
df["species"] = df["target"].map(dict(enumerate(iris.target_names)))
print(df.shape)
print(df["species"].value_counts().tolist())
print(df.groupby("species")[["petal length (cm)", "petal width (cm)"]].mean().round(2))
```

Output:

```text
(150, 6)
[50, 50, 50]
            petal length (cm)  petal width (cm)
species                                        
setosa                   1.46              0.25
versicolor               4.26              1.33
virginica                5.55              2.03
```

Exploring first already tells us a lot: petal size is very different between species. Setosa petals are tiny. A good model should find that.

## 2. Split

```python
from sklearn.model_selection import train_test_split

X = df[iris.feature_names]
y = df["species"]
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.3, random_state=7, stratify=y)
print(len(X_train), len(X_test))
```

Output:

```text
105 45
```

## 3. Train a decision tree

A **decision tree** learns a flowchart of yes/no questions, like "Is petal length below 2.45 cm?". It's easy to understand, which makes it great for explaining a model to people who don't code.

```python
from sklearn.tree import DecisionTreeClassifier, export_text

tree = DecisionTreeClassifier(max_depth=3, random_state=0)
tree.fit(X_train, y_train)
print(export_text(tree, feature_names=list(X.columns)))
```

Output:

```text
|--- petal width (cm) <= 0.70
|   |--- class: setosa
|--- petal width (cm) >  0.70
|   |--- petal length (cm) <= 4.95
|   |   |--- petal width (cm) <= 1.70
|   |   |   |--- class: versicolor
|   |   |--- petal width (cm) >  1.70
|   |   |   |--- class: virginica
|   |--- petal length (cm) >  4.95
|   |   |--- petal length (cm) <= 5.05
|   |   |   |--- class: virginica
|   |   |--- petal length (cm) >  5.05
|   |   |   |--- class: virginica
```

The model found the same thing we saw while exploring: a narrow petal (0.7 cm or less) means setosa, then petal length and width separate the other two. (The last split leads to virginica on both sides: trees sometimes make splits that don't change the answer.)

`max_depth=3` limits the tree to 3 levels of questions. Deeper trees can memorise the training data (overfitting), so limiting depth is a simple safety net.

## 4. Evaluate

```python
from sklearn.metrics import accuracy_score, confusion_matrix

pred = tree.predict(X_test)
print("Accuracy:", round(accuracy_score(y_test, pred), 3))
print(confusion_matrix(y_test, pred, labels=iris.target_names))
```

Output:

```text
Accuracy: 0.978
[[15  0  0]
 [ 0 14  1]
 [ 0  0 15]]
```

98% on flowers the tree never saw: only one mistake, a versicolor taken for a virginica. Those two species really do overlap in size.

## 5. Which features mattered?

```python
importance = pd.Series(tree.feature_importances_, index=X.columns).sort_values(ascending=False)
print(importance.round(3))
```

Output:

```text
petal width (cm)     0.556
petal length (cm)    0.444
sepal width (cm)     0.000
sepal length (cm)    0.000
dtype: float64
```

The tree used only the petal measurements. Feature importance is how you explain to a manager **why** a model decides what it decides.

## 6. Use it

```python
flower = pd.DataFrame([[6.1, 2.8, 4.7, 1.2]], columns=X.columns)
print(tree.predict(flower)[0])
```

Output:

```text
versicolor
```

::tip Save a trained model with `import joblib; joblib.dump(tree, "iris_tree.joblib")`, and load it later with `joblib.load`. That's how models get used inside websites and apps.

## You've built a real ML project

The same six steps (explore, split, train, evaluate, explain, use) apply to every project, from predicting customer churn to detecting fraud. The **Data Scientist career track** goes deeper: statistics, feature engineering, random forests, gradient boosting, tuning and full portfolio projects.

::hint Challenge | Replace the decision tree with `LogisticRegression(max_iter=1000)` and with `KNeighborsClassifier()`. Which model gets the best test accuracy?

## See it as a chart: why petals win

Plot every flower by petal length and width, coloured by species, next to the tree's feature importances. Setosa sits alone in the corner, and the other two separate well on petal size, which is exactly why the tree leans on the petal features.

```python
import matplotlib.pyplot as plt
from sklearn.datasets import load_iris
from sklearn.tree import DecisionTreeClassifier

iris = load_iris(as_frame=True)
df = iris.frame
tree = DecisionTreeClassifier(max_depth=3, random_state=42).fit(iris.data, iris.target)

fig, (left, right) = plt.subplots(1, 2, figsize=(10, 3.8))
for code, name, color in zip(range(3), iris.target_names, ["#2563eb", "#0a7d4f", "#f59e0b"]):
    part = df[df["target"] == code]
    left.scatter(part["petal length (cm)"], part["petal width (cm)"], s=18, color=color, label=name)
left.set_xlabel("petal length (cm)")
left.set_ylabel("petal width (cm)")
left.legend(frameon=False)
left.set_title("Each dot is one flower")
order = tree.feature_importances_.argsort()
right.barh([iris.feature_names[i] for i in order], tree.feature_importances_[order], color="#0a7d4f")
right.set_xlabel("importance")
right.set_title("What the tree uses")
fig.tight_layout()
plt.show()
```

::figure ml-iris-petals.svg | Left: three clear groups of flowers on a petal length versus petal width plot; right: bar chart where petal features have almost all the importance

When one or two features separate the classes this cleanly in a chart, you can expect high accuracy before training anything. A quick plot like this is the best first step of any project.

## Summary

- Explore → split → train → evaluate → explain → use.
- Decision trees learn readable yes/no rules; limit `max_depth` to avoid overfitting.
- `feature_importances_` shows which inputs drove the predictions.
