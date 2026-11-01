---
title: "Imbalanced Data and Model Explainability"
slug: ml-imbalance-explainability
subject: ml-in-depth
lesson: 7
difficulty: intermediate
excerpt: Handle rare classes with class weights, resampling and threshold tuning, then explain predictions with feature importance, permutation importance and partial dependence.
---

In real projects the interesting class is usually rare: fraud, machine failure, disease, churn. And once you have a model, someone will ask "why did it reject this loan?". This lesson covers both.

::scene The rare-event detective
🔍 | 1 in 100 | Only one transaction in a hundred is fraud. The model learns to ignore them. | bugsy
⚖️ | Rebalance | Tell the model each fraud case counts 99 times as much. Suddenly it pays attention. | chip
🧾 | Why? | A customer asks why their card was blocked. The model must explain itself. | dev
🗺️ | Explain | Permutation importance shows which features drive decisions. | byte
::end

## The problem

```python
import numpy as np
from sklearn.datasets import make_classification
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import recall_score, precision_score

X, y = make_classification(n_samples=4000, n_features=8, n_informative=4, weights=[0.97], flip_y=0, random_state=2)
a, b, ya, yb = train_test_split(X, y, stratify=y, test_size=0.3, random_state=0)
print("fraud share:", round(y.mean(), 3))

plain = LogisticRegression(max_iter=1000).fit(a, ya)
weighted = LogisticRegression(max_iter=1000, class_weight="balanced").fit(a, ya)
for name, m in (("plain", plain), ("class_weight='balanced'", weighted)):
    p = m.predict(b)
    print(f"{name:>24}: recall {recall_score(yb, p):.2f}  precision {precision_score(yb, p):.2f}")
```

Output:

```text
fraud share: 0.03
                   plain: recall 0.50  precision 1.00
 class_weight='balanced': recall 0.81  precision 0.16
```

`class_weight="balanced"` makes mistakes on the rare class cost more. Recall jumps, precision drops: you catch more fraud with more false alarms. Where to land depends on the cost of each mistake.

## Three tools for imbalance

1. **Class weights** (above): simplest, no data changes. Most scikit-learn models support it.
2. **Resampling:** oversample the rare class (or create synthetic examples with SMOTE from the `imbalanced-learn` library), or undersample the common class. Resample **only the training data**.
3. **Threshold tuning:** keep the model, move the decision threshold (lesson 6).

```python
from sklearn.utils import resample

rare = a[ya == 1]
boosted = resample(rare, n_samples=len(a[ya == 0]), replace=True, random_state=0)
print("training rows: common", (ya == 0).sum(), "| rare before", len(rare), "| rare after", len(boosted))
```

Output:

```text
training rows: common 2716 | rare before 84 | rare after 2716
```

::tip Use precision-recall curves (and the area under them) instead of accuracy for rare events, and always stratify your splits so the test set contains enough positives.

## Explaining a model: permutation importance

Shuffle one feature's values and see how much the score drops. A big drop means the model relies on that feature.

::walk python | The idea behind permutation importance
baseline = 0.91
shuffled = {"income": 0.72, "age": 0.89, "pincode": 0.91}
importance = {f: round(baseline - s, 2) for f, s in shuffled.items()}
top = max(importance, key=importance.get)
print(importance, top)
::notes
1: The model's score on test data with all features intact. // vars: baseline=0.91
2: The score after shuffling each feature in turn (breaking its link to the answer). // vars: shuffled={'income': 0.72, 'age': 0.89, 'pincode': 0.91}
3: Importance = how much the score fell. // deep: Unlike a tree's built-in feature_importances_, permutation importance works for any model and is measured on test data, so it reflects what generalises rather than what the model memorised. // vars: importance={'income': 0.19, 'age': 0.02, 'pincode': 0.0}
4: Income matters most; pincode not at all, which is reassuring for fairness. // vars: top=income
5: Use this to explain the model and to catch suspicious dependencies. // out: {'income': 0.19, 'age': 0.02, 'pincode': 0.0} income
::end

```python
from sklearn.ensemble import RandomForestClassifier
from sklearn.inspection import permutation_importance

forest = RandomForestClassifier(n_estimators=200, class_weight="balanced", random_state=0).fit(a, ya)
r = permutation_importance(forest, b, yb, scoring="roc_auc", n_repeats=5, random_state=0)
order = r.importances_mean.argsort()[::-1][:3]
print([(f"feature_{i}", round(float(r.importances_mean[i]), 3)) for i in order])
```

Output:

```text
[('feature_7', 0.15), ('feature_5', 0.063), ('feature_6', 0.026)]
```

## Explaining individual predictions

- **Partial dependence plots** (`sklearn.inspection.PartialDependenceDisplay`) show how the prediction changes as one feature changes.
- **SHAP values** (the `shap` library) split each single prediction into contributions from each feature: "income −0.3, missed EMIs +0.5", which is what you show a customer or a regulator.
- For simple, regulated decisions, prefer inherently explainable models (logistic regression, small trees).

## Summary

- Rare classes need class weights, training-set resampling or threshold tuning.
- Measure with recall, precision and PR curves, not accuracy.
- Permutation importance explains any model using test data.
- Partial dependence and SHAP explain how features drive predictions.

🎉 You've finished **Machine Learning in Depth**.
