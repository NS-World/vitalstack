---
title: "Metrics, Cross-Validation and Hyper-Parameter Tuning"
slug: ml-metrics-cv-tuning
subject: ml-in-depth
lesson: 6
difficulty: intermediate
excerpt: Confusion matrix, precision, recall, F1, ROC-AUC, RMSE; why accuracy lies; k-fold cross-validation; and tuning models with GridSearchCV without fooling yourself.
---

A model that is "95% accurate" can be useless. If only 5% of transactions are fraud, a model that always says "not fraud" is 95% accurate and catches nothing. Choosing the right metric is as important as choosing the model.

::scene The 95% trap
🏦 | The claim | "Our fraud model is 95% accurate!" says the intern. | byte
🕵️ | The check | It predicts "not fraud" for every transaction. It caught zero fraud. | bugsy
🔲 | The matrix | The confusion matrix shows exactly which mistakes it made. | chip
🎯 | Right metric | For fraud we care about recall: of all real fraud, how much did we catch? | dev
::end

## The confusion matrix

```python
import numpy as np
from sklearn.metrics import confusion_matrix, precision_score, recall_score, f1_score, accuracy_score

actual    = np.array([1, 0, 0, 1, 1, 0, 0, 0, 1, 0])
predicted = np.array([1, 0, 1, 0, 1, 0, 0, 0, 1, 0])
print(confusion_matrix(actual, predicted))
print("accuracy ", accuracy_score(actual, predicted))
print("precision", precision_score(actual, predicted))
print("recall   ", recall_score(actual, predicted))
print("f1       ", round(f1_score(actual, predicted), 3))
```

Output:

```text
[[5 1]
 [1 3]]
accuracy  0.8
precision 0.75
recall    0.75
f1        0.75
```

Rows are actual (0, 1), columns are predicted (0, 1): 5 true negatives, 1 false positive, 1 false negative, 3 true positives.

::walk python | Precision vs recall from the counts
tp, fp, fn = 3, 1, 1
precision = tp / (tp + fp)
recall = tp / (tp + fn)
f1 = 2 * precision * recall / (precision + recall)
print(precision, recall, f1)
::notes
1: True positives, false positives, false negatives from the matrix. // vars: tp=3, fp=1, fn=1
2: **Precision:** of everything we flagged, how much was really positive? Low precision = many false alarms. // vars: precision=0.75
3: **Recall:** of all real positives, how many did we catch? Low recall = missed cases. // deep: Recall is also called sensitivity or the true positive rate (TPR). The false positive rate (FPR) is fp / (fp + tn): it's what the ROC curve plots against TPR. // vars: recall=0.75
4: **F1** is the harmonic mean: high only when both are high. // vars: f1=0.75
5: Choose by cost: fraud and disease screening favour recall; spam filters favour precision. // out: 0.75 0.75 0.75
::end

## ROC-AUC: ranking quality across all thresholds

A classifier outputs probabilities; the 0.5 threshold is just a choice. **ROC-AUC** measures how well the model ranks positives above negatives across every threshold (1.0 = perfect, 0.5 = random guessing).

```python
from sklearn.datasets import make_classification
from sklearn.linear_model import LogisticRegression
from sklearn.model_selection import train_test_split
from sklearn.metrics import roc_auc_score

X, y = make_classification(n_samples=1000, weights=[0.9], random_state=0)
a, b, ya, yb = train_test_split(X, y, stratify=y, random_state=0)
proba = LogisticRegression(max_iter=1000).fit(a, ya).predict_proba(b)[:, 1]
print("ROC-AUC:", round(roc_auc_score(yb, proba), 3))
for t in (0.5, 0.3):
    pred = (proba >= t).astype(int)
    print(f"threshold {t}: precision {precision_score(yb, pred):.2f}, recall {recall_score(yb, pred):.2f}")
```

Output:

```text
ROC-AUC: 0.967
threshold 0.5: precision 0.90, recall 0.72
threshold 0.3: precision 0.91, recall 0.80
```

Lowering the threshold catches more positives (recall goes up). Usually precision falls as false alarms grow; on this easy example it held steady. On real data, plot precision and recall for every threshold and let the business choose the trade-off.

## Regression metrics

| Metric | Meaning | Notes |
|---|---|---|
| MAE | average absolute error | Easy to explain: "off by ₹1,200 on average" |
| RMSE | root mean squared error | Punishes big misses more |
| R² | share of variance explained | 1 = perfect, 0 = no better than predicting the mean |

## Cross-validation: don't trust one split

One train/test split can be lucky. **k-fold cross-validation** splits the data into k parts, trains k times, and tests on each part once.

```python
from sklearn.model_selection import cross_val_score, StratifiedKFold
from sklearn.ensemble import RandomForestClassifier

cv = StratifiedKFold(n_splits=5, shuffle=True, random_state=0)
scores = cross_val_score(RandomForestClassifier(random_state=0), X, y, cv=cv, scoring="roc_auc")
print(scores.round(3), "mean", scores.mean().round(3))
```

Output:

```text
[0.987 0.976 1.    0.97  0.997] mean 0.986
```

`StratifiedKFold` keeps the same class balance in every fold: important for rare classes.

## Tuning with GridSearchCV

```python
from sklearn.model_selection import GridSearchCV

grid = GridSearchCV(RandomForestClassifier(random_state=0),
                    {"n_estimators": [100, 300], "max_depth": [4, None], "min_samples_leaf": [1, 5]},
                    cv=cv, scoring="roc_auc")
grid.fit(a, ya)
print(grid.best_params_, round(grid.best_score_, 3))
print("held-out test AUC:", round(roc_auc_score(yb, grid.predict_proba(b)[:, 1]), 3))
```

Output:

```text
{'max_depth': 4, 'min_samples_leaf': 1, 'n_estimators': 300} 0.991
held-out test AUC: 0.963
```

::tip Tune with cross-validation on the **training** set, then check once on the untouched test set. If you tune against the test set, your reported score is optimistic and won't hold up in production.

## See it as a chart: the ROC curve

The ROC curve shows every threshold at once: for each one, the share of positives caught (true positive rate) against the share of negatives wrongly flagged (false positive rate). The closer the curve hugs the top-left corner, the better; the area under it is the AUC.

```python
import matplotlib.pyplot as plt
from sklearn.datasets import make_classification
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import roc_auc_score, roc_curve
from sklearn.model_selection import train_test_split
from sklearn.tree import DecisionTreeClassifier

X, y = make_classification(n_samples=2000, n_features=10, n_informative=4, random_state=7)
a, b, ya, yb = train_test_split(X, y, test_size=0.3, random_state=0)
models = {"logistic regression": LogisticRegression(max_iter=1000), "shallow tree": DecisionTreeClassifier(max_depth=2, random_state=0)}

fig, ax = plt.subplots(figsize=(5.5, 4.8))
for (name, m), color in zip(models.items(), ["#0a7d4f", "#f59e0b"]):
    scores = m.fit(a, ya).predict_proba(b)[:, 1]
    fpr, tpr, _ = roc_curve(yb, scores)
    ax.plot(fpr, tpr, color=color, lw=2.2, label=f"{name} (AUC {roc_auc_score(yb, scores):.2f})")
ax.plot([0, 1], [0, 1], color="#9ca3af", ls="--", label="random guess (AUC 0.50)")
ax.set_xlabel("false positive rate")
ax.set_ylabel("true positive rate (recall)")
ax.legend(frameon=False, loc="lower right", fontsize=9)
fig.tight_layout()
plt.show()
```

::figure mlid-roc-curve.svg | Two ROC curves above the dashed diagonal: logistic regression bows closer to the top-left corner with a higher AUC than a shallow tree

The dashed diagonal is a coin flip. Comparing curves like this is fairer than comparing accuracy at one threshold, especially on imbalanced data.

## Summary

- Accuracy lies on imbalanced data; use the confusion matrix, precision, recall, F1 and ROC-AUC.
- The threshold trades precision against recall; the business chooses.
- Use k-fold (stratified) cross-validation instead of one lucky split.
- Tune with GridSearchCV on training data; test once at the end.
