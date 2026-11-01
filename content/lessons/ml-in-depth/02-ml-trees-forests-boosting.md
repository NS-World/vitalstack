---
title: "Decision Trees, Random Forests and Gradient Boosting"
slug: ml-trees-forests-boosting
subject: ml-in-depth
lesson: 2
difficulty: intermediate
excerpt: How a decision tree asks yes/no questions, why Gini impurity picks the best split, and how random forests (bagging) and gradient boosting combine many trees into strong models.
---

Tree models are the workhorses of real-world tabular data: credit scoring, fraud, churn, pricing. They handle mixed feature types, need no scaling, and the boosted versions win most data competitions on tables.

::scene Twenty questions, but smarter
🌳 | One tree | Is income above 6 lakh? Yes. Any missed EMI? No. Approve. A tree is a flowchart of questions. | dev
🎯 | Best question | At each step the tree picks the question that makes the groups purest. | chip
🌲🌲🌲 | A forest | Hundreds of slightly different trees vote. One tree's mistakes are outvoted. | byte
🧗 | Boosting | Trees built one after another, each fixing the previous ones' mistakes. | bugsy
::end

## A decision tree you can read

```python
import numpy as np
from sklearn.tree import DecisionTreeClassifier, export_text

# income (lakh/yr), missed EMIs last year -> loan defaulted?
X = np.array([[3, 2], [4, 3], [12, 0], [8, 0], [5, 1], [15, 1], [2, 0], [9, 2], [7, 0], [3, 1]])
y = np.array([1, 1, 0, 0, 1, 0, 1, 1, 0, 1])

tree = DecisionTreeClassifier(max_depth=2, random_state=0).fit(X, y)
print(export_text(tree, feature_names=["income", "missed_emi"]))
```

Output:

```text
|--- income <= 6.00
|   |--- class: 1
|--- income >  6.00
|   |--- missed_emi <= 1.50
|   |   |--- class: 0
|   |--- missed_emi >  1.50
|   |   |--- class: 1
```

That's the whole model: a bank officer can read it. `max_depth=2` keeps it small; deeper trees memorise noise (overfitting).

## How the tree picks a split: Gini impurity

A group is **pure** if everyone in it has the same label. Gini impurity measures how mixed a group is: 0 = pure, 0.5 = a 50/50 mix (for two classes).

::walk python | Scoring one split with Gini
def gini(labels):
    p = sum(labels) / len(labels)
    return 1 - p ** 2 - (1 - p) ** 2
left, right = [1, 1, 1, 1, 1], [0, 0, 0, 0, 1]
score = (len(left) * gini(left) + len(right) * gini(right)) / 10
print(round(gini(left), 2), round(gini(right), 2), round(score, 2))
::notes
1: Gini impurity for a group of 0/1 labels.
2: p is the share of 1s in the group.
3: 1 − p² − (1 − p)²: zero when everyone agrees, highest when it's 50/50. // deep: Entropy is an alternative impurity measure (used by ID3 and C4.5). Gini is scikit-learn's default and gives very similar trees.
4: The split "income ≤ 6" sends five defaulters left, and four non-defaulters plus one defaulter right. // vars: left=[1, 1, 1, 1, 1], right=[0, 0, 0, 0, 1]
5: The split's score is the size-weighted average impurity. The tree tries every possible split and keeps the lowest. // vars: score=0.16
6: Left is perfectly pure (0), right is mostly pure (0.32), overall 0.16. A good split. // out: 0.0 0.32 0.16
::end

## Random forest: many trees vote (bagging)

Each tree sees a random bootstrap sample of rows and a random subset of features, so the trees make different mistakes, and their vote is more reliable than any single tree.

```python
from sklearn.datasets import make_classification
from sklearn.ensemble import RandomForestClassifier, GradientBoostingClassifier
from sklearn.model_selection import cross_val_score

X, y = make_classification(n_samples=600, n_features=10, n_informative=5, random_state=1)
models = {
    "single deep tree": DecisionTreeClassifier(random_state=0),
    "random forest": RandomForestClassifier(n_estimators=200, random_state=0),
    "gradient boosting": GradientBoostingClassifier(random_state=0),
}
for name, m in models.items():
    print(f"{name:>17}: {cross_val_score(m, X, y, cv=5).mean():.3f}")
```

Output:

```text
 single deep tree: 0.858
    random forest: 0.903
gradient boosting: 0.895
```

## Gradient boosting: learning from mistakes

Boosting builds trees **one after another**. Each new tree is trained on what the previous trees got wrong (the residual errors), and its prediction is added with a small learning rate. Popular libraries: scikit-learn's `HistGradientBoostingClassifier`, **XGBoost**, **LightGBM** and **CatBoost**. They're the go-to models for tabular data.

```python
forest = RandomForestClassifier(n_estimators=200, random_state=0).fit(X, y)
top = np.argsort(forest.feature_importances_)[::-1][:3]
print("most important features:", top.tolist())
```

Output:

```text
most important features: [0, 3, 6]
```

`feature_importances_` shows which columns the forest relied on most: a quick way to explain a model and spot leakage (a suspiciously important ID column, for example).

::tip Default settings of a random forest are a strong baseline. For the last few percent, tune gradient boosting's `learning_rate`, `n_estimators` and `max_depth` with cross-validation (lesson 6).

## Summary

- A decision tree asks yes/no questions; each split minimises impurity (Gini or entropy).
- Deep single trees overfit; limit depth or use ensembles.
- **Random forest** = bagging: many randomised trees vote.
- **Gradient boosting** = trees in sequence, each fixing the last; best on tabular data.
