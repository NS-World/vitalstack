---
title: "Signature Project 1: UPI Fraud Radar"
slug: signature-upi-fraud-radar
subject: ds-signature
lesson: 1
difficulty: advanced
excerpt: Build a fraud alert system for Indian digital payments on a real-looking dataset of 3,000 transactions where only 1.4% are fraud. Find the signals, score every payment, and pick the alert threshold by rupees saved, not by accuracy.
---

Every bank and payment app in India runs a fraud radar. The hard part is not the model. It is that **fraud is rare** (here 42 of 3,000 payments), every missed fraud costs real money, and every false alarm annoys a genuine customer. This project teaches the thinking a fraud analyst uses on day one, on a dataset you will not find anywhere else: the VitalStack **bank transactions** set from the [Datasets Hub](/datasets/).

::scene Catching the 1.4%
💳 | The problem | 3,000 payments. Only 42 are fraud. A model that says "never fraud" is 98.6% accurate and completely useless. | dev
🔎 | The signals | New device, odd hours, gaming and electronics, big amounts: fraud leaves fingerprints. | bugsy
🎚️ | The threshold | Every alert costs about ₹30 in support time and annoyed customers. Every miss costs the full amount. | chip
💰 | The result | From ₹1.75 lakh lost to about ₹16,000 total cost. That is the number your manager wants. | byte
::end

## Load the data

The dataset is free on the Datasets Hub. You can download the CSV or read it straight from its address:

```python
import numpy as np
import pandas as pd

DATA = "https://vitalstack.co.in/wp-content/themes/vitalstack/data/datasets/"
df = pd.read_csv(DATA + "bank-transactions.csv", parse_dates=["timestamp"])
print(df.shape)
print(df["is_fraud"].value_counts().to_dict())
print("fraud rate:", round(df["is_fraud"].mean() * 100, 2), "%")
```

Output:

```text
(3000, 9)
{0: 2958, 1: 42}
fraud rate: 1.4 %
```

This is the first lesson of fraud work: **accuracy lies**. Predicting "not fraud" for everyone scores 98.6% accuracy and catches nothing. We will judge models by how much fraud they catch and what they cost.

## Find the fingerprints

Fraud analysts start with simple group-by questions: *where* is fraud more common than normal?

```python
df["hour"] = df["timestamp"].dt.hour
df["night"] = (df["hour"] < 6).astype(int)            # midnight to 6 am
for col in ["new_device", "night", "merchant_category"]:
    rate = df.groupby(col)["is_fraud"].mean().mul(100).round(1)
    print(col, rate.sort_values(ascending=False).head(3).to_dict())
print(df.groupby("is_fraud")["amount_inr"].median().to_dict())
```

Output:

```text
new_device {'Yes': 15.4, 'No': 0.6}
night {1: 2.6, 0: 1.0}
merchant_category {'Gaming': 4.3, 'Electronics': 2.5, 'Travel': 1.7}
{0: 474.77, 1: 2246.87}
```

Four clear signals:

1. A payment from a **new device** is 25 times more likely to be fraud (15.4% vs 0.6%). This is why your bank sends an OTP when you log in on a new phone.
2. **Night-time** payments are 2.6 times riskier.
3. **Gaming** and **electronics** lead: easy to resell, popular with scammers.
4. The median fraud is **₹2,247**, almost five times a normal payment (₹475).

## Score every payment

We turn the signals into features and train a logistic regression. With only 42 frauds, a single train/test split is noisy, so we use **5-fold cross-validation**: every payment gets a risk score from a model that never saw it.

```python
from sklearn.linear_model import LogisticRegression
from sklearn.model_selection import StratifiedKFold, cross_val_predict
from sklearn.metrics import roc_auc_score, average_precision_score

df["new_dev"] = (df["new_device"] == "Yes").astype(int)
df["log_amt"] = np.log10(df["amount_inr"])            # ₹100 → 2, ₹10,000 → 4
df["risky_cat"] = df["merchant_category"].isin(["Gaming", "Electronics"]).astype(int)
df["current_ac"] = (df["account_type"] == "Current").astype(int)
X = df[["log_amt", "new_dev", "night", "risky_cat", "current_ac"]]
y = df["is_fraud"]

model = LogisticRegression(class_weight="balanced", max_iter=1000)
cv = StratifiedKFold(n_splits=5, shuffle=True, random_state=42)
df["risk"] = cross_val_predict(model, X, y, cv=cv, method="predict_proba")[:, 1]
print("ROC-AUC:", round(roc_auc_score(y, df["risk"]), 3))
print("PR-AUC :", round(average_precision_score(y, df["risk"]), 3))
```

Output:

```text
ROC-AUC: 0.943
PR-AUC : 0.428
```

- **ROC-AUC 0.943**: pick one fraud and one genuine payment at random, and 94% of the time the fraud gets the higher risk score.
- **PR-AUC 0.428**: on rare events this is the honest number. Random guessing would score about 0.014, the fraud rate, so the model is about 30 times better than chance.

`class_weight="balanced"` tells the model that each fraud matters as much as 70 genuine payments. Without it, the model learns to say "not fraud" almost always.

## Compare with a hand-written rule

Banks often start with rules. Ours: "new device **and** more than ₹2,000".

```python
rule = (df["new_dev"] == 1) & (df["amount_inr"] > 2000)
print("rule flags", int(rule.sum()), "payments and catches", int((rule & (y == 1)).sum()), "of", int(y.sum()))
```

Output:

```text
rule flags 21 payments and catches 11 of 42
```

The rule is precise (11 of 21 flags are fraud) but misses 31 frauds. Models shine where many weak signals add up.

## Pick the threshold by money

Every alert costs about **₹30**: an OTP call, a blocked payment, a customer who might switch apps. Every missed fraud costs its **full amount**. So the best threshold is the one with the lowest total cost, not 0.5.

::walk python | The cost of one threshold
ALERT_COST = 30
flagged = 333
missed_amount = 5709
total = missed_amount + ALERT_COST * flagged
print(total)
::notes
1: Each alert costs about ₹30 in support time and friction. // vars: ALERT_COST=30
2: At this threshold the radar flags 333 of 3,000 payments. // vars: flagged=333
3: The frauds it still misses add up to ₹5,709. // vars: missed_amount=5709
4: Total cost = missed money + cost of all alerts. // deep: This is a business decision, not a maths one. If alerts were cheaper (an automatic in-app confirmation instead of a call), the best threshold would move lower and the radar would flag more. // vars: total=15699
5: ₹15,699, compared with ₹1,74,522 lost with no radar at all. // out: 15699
::end

```python
ALERT_COST = 30

def total_cost(threshold):
    flagged = df["risk"] >= threshold
    missed = df.loc[~flagged & (y == 1), "amount_inr"].sum()
    return missed + ALERT_COST * flagged.sum(), int((flagged & (y == 1)).sum()), int(flagged.sum())

print("no radar:", round(df.loc[y == 1, "amount_inr"].sum()))
for t in [0.3, 0.5, 0.7, 0.9]:
    cost, caught, flagged = total_cost(t)
    print(f"threshold {t}: catch {caught}/42, alerts {flagged}, total cost ₹{cost:,.0f}")
best = min(np.arange(0.05, 0.99, 0.01), key=lambda t: total_cost(t)[0])
print("best threshold:", round(best, 2), "cost ₹{:,.0f}".format(total_cost(best)[0]))
```

Output:

```text
no radar: 174522
threshold 0.3: catch 39/42, alerts 560, total cost ₹18,639
threshold 0.5: catch 37/42, alerts 358, total cost ₹16,449
threshold 0.7: catch 31/42, alerts 232, total cost ₹29,030
threshold 0.9: catch 23/42, alerts 86, total cost ₹42,843
best threshold: 0.54 cost ₹15,699
```

At threshold **0.54** the radar catches 37 of 42 frauds with 333 alerts, cutting the total cost by **91%**. Notice how a "safe-looking" high threshold (0.9) costs almost three times more: the frauds it lets through are expensive.

## Explain it to the risk team

```python
model.fit(X, y)
weights = pd.Series(model.coef_[0], index=X.columns).round(2).sort_values(ascending=False)
print(weights.to_dict())
```

Output:

```text
{'new_dev': 4.22, 'log_amt': 3.57, 'risky_cat': 2.26, 'night': 1.03, 'current_ac': 0.66}
```

In plain words for the risk team: *"A new device is the strongest warning sign. Each tenfold increase in amount adds strong risk. Gaming and electronics come next, then night-time."* That sentence, plus the cost table, is what gets a model approved.

::tip Real fraud systems also look at things this dataset does not have: how fast payments arrive (velocity), whether the payee is new, and the device's location. Add one such feature in your own version (for example, payments per account per hour) and show how much it saves.

## Make it yours

- **Velocity feature:** simulate several payments from one account within 10 minutes and add a "payments in the last hour" column.
- **Two-stage radar:** auto-block above 0.9, send an in-app confirmation from 0.54 to 0.9 (cost ₹5 instead of ₹30), and recompute the total cost.
- **Dashboard:** in Power BI or Streamlit, show alerts per hour, top risky categories, and money saved this week.

## Portfolio checklist

- [ ] Shows why accuracy is the wrong metric for rare events
- [ ] Fraud rate by device, hour and category, with a short business reading
- [ ] Cross-validated risk scores, ROC-AUC and PR-AUC explained in plain words
- [ ] A rule baseline next to the model
- [ ] Threshold chosen by total rupee cost, with the cost table in the README
- [ ] One feature or idea of your own that the lesson did not include
