---
title: "Project 2: Customer Churn Prediction"
slug: project-churn-prediction
subject: ds-projects
lesson: 2
difficulty: intermediate
excerpt: Predict which telecom customers will leave: EDA, feature engineering, a scikit-learn pipeline, model comparison, threshold choice by business cost, and an explainable result.
---

Keeping a customer is far cheaper than winning a new one. A telecom or subscription company that knows **who is likely to leave** can call them with an offer first. Churn prediction is a classic interview project because it uses every ML skill.

::scene Saving customers before they leave
📱 | The business | A telecom loses thousands of subscribers every month. Each costs Rs 2,000 to replace. | dev
🧠 | The model | Predict, for every customer, the chance they'll leave next month. | chip
🎯 | The threshold | Call the riskiest customers with an offer. But calls cost money too: where to draw the line? | byte
💰 | The result | The model-driven campaign saves far more than it costs. | bugsy
::end

## Data and features

```python
import numpy as np
import pandas as pd

rng = np.random.default_rng(7)
n = 4000
df = pd.DataFrame({
    "tenure_months": rng.integers(1, 72, n),
    "monthly_bill": rng.normal(550, 180, n).clip(150, 1500).round(),
    "contract": rng.choice(["monthly", "yearly"], n, p=[0.6, 0.4]),
    "complaints": rng.poisson(0.6, n),
    "data_gb": rng.gamma(2, 6, n).round(1),
})
logit = (-1.0 - 0.05 * df["tenure_months"] + 0.002 * df["monthly_bill"]
         + 1.2 * (df["contract"] == "monthly") + 0.7 * df["complaints"])
df["churned"] = (rng.random(n) < 1 / (1 + np.exp(-logit))).astype(int)
df["bill_per_gb"] = (df["monthly_bill"] / (df["data_gb"] + 1)).round(1)     # engineered feature
print("churn rate:", round(df["churned"].mean(), 3))
print(df.groupby("contract")["churned"].mean().round(3).to_dict())
```

Output:

```text
churn rate: 0.379
{'monthly': 0.467, 'yearly': 0.252}
```

EDA already shows the story: monthly-contract customers churn almost twice as often as yearly ones.

## Model comparison

```python
from sklearn.compose import ColumnTransformer
from sklearn.pipeline import Pipeline
from sklearn.preprocessing import OneHotEncoder, StandardScaler
from sklearn.linear_model import LogisticRegression
from sklearn.ensemble import GradientBoostingClassifier
from sklearn.model_selection import train_test_split, cross_val_score

X, y = df.drop(columns="churned"), df["churned"]
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.25, stratify=y, random_state=0)
prep = ColumnTransformer([("cat", OneHotEncoder(), ["contract"]),
                          ("num", StandardScaler(), ["tenure_months", "monthly_bill", "complaints", "data_gb", "bill_per_gb"])])
models = {"logistic": LogisticRegression(max_iter=1000, class_weight="balanced"),
          "boosting": GradientBoostingClassifier(random_state=0)}
for name, m in models.items():
    auc = cross_val_score(Pipeline([("prep", prep), ("m", m)]), X_train, y_train, cv=5, scoring="roc_auc").mean()
    print(f"{name}: CV ROC-AUC {auc:.3f}")
```

Output:

```text
logistic: CV ROC-AUC 0.799
boosting: CV ROC-AUC 0.781
```

The simple logistic model wins here, and it's explainable: a good result to report honestly.

## Choosing the threshold by money

::walk python | Expected profit of calling one customer
p_churn = 0.40
save_rate = 0.30
customer_value = 2000
call_cost = 100
profit = p_churn * save_rate * customer_value - call_cost
print(profit, profit > 0)
::notes
1: The model says this customer has a 40% chance of leaving. // vars: p_churn=0.4
2: Our retention offer convinces 30% of would-be leavers to stay (from past campaigns). // vars: save_rate=0.3
3: Keeping a customer is worth Rs 2,000. // vars: customer_value=2000
4: Each call plus offer costs Rs 100. // vars: call_cost=100
5: Expected profit = 0.40 × 0.30 × 2000 − 100 = Rs 140. // deep: Solve for the break-even probability: p × 0.3 × 2000 = 100, so p ≈ 0.167. Call everyone above about 17% risk. That's how a business picks a threshold, not by defaulting to 0.5. // vars: profit=140.0
6: Positive: worth calling. // out: 140.0 True
::end

```python
pipe = Pipeline([("prep", prep), ("m", LogisticRegression(max_iter=1000, class_weight="balanced"))]).fit(X_train, y_train)
p = pipe.predict_proba(X_test)[:, 1]
for t in (0.5, 0.17):
    called = p >= t
    saved_value = (y_test[called] * 0.3 * 2000).sum()
    cost = called.sum() * 100
    print(f"threshold {t}: call {called.sum()} customers, expected net Rs {saved_value - cost:,.0f}")
```

Output:

```text
threshold 0.5: call 453 customers, expected net Rs 122,700
threshold 0.17: call 828 customers, expected net Rs 133,800
```

Calling more customers at the break-even threshold earns more here, because each saved customer is worth far more than a call. But note that `class_weight="balanced"` shifts probabilities upwards, so the break-even maths is only a starting point: always check thresholds on held-out data with real costs.

## Explain the drivers

```python
coefs = pd.Series(pipe.named_steps["m"].coef_[0], index=pipe.named_steps["prep"].get_feature_names_out())
print(coefs.round(2).sort_values().to_dict())
```

Output:

```text
{'num__tenure_months': -0.97, 'cat__contract_yearly': -0.79, 'num__bill_per_gb': -0.05, 'num__data_gb': -0.04, 'num__monthly_bill': 0.33, 'num__complaints': 0.58, 'cat__contract_monthly': 0.6}
```

Short tenure, monthly contracts and complaints drive churn: recommend yearly-plan offers to new monthly customers and fast complaint resolution.

## Portfolio checklist

- [ ] EDA with churn rate by segment
- [ ] Pipeline with no leakage; cross-validated comparison
- [ ] Threshold chosen with business costs on a test set
- [ ] Drivers explained in plain language
- [ ] README: "the campaign at threshold X saves an estimated Rs Y per month"

## Summary

- Churn = imbalanced classification; use ROC-AUC and business-cost thresholds.
- Simple, explainable models can win; report honestly.
- Turn coefficients into actions the business can take.
