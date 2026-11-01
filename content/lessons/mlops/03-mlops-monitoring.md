---
title: "Monitoring Models in Production"
slug: mlops-monitoring
subject: mlops
lesson: 3
difficulty: intermediate
excerpt: Models decay. Detect data drift with PSI and statistical tests, track prediction and business metrics, set alerts, and decide when to retrain, with runnable Python.
---

A model is trained on yesterday's world. Then the world changes: a new app version, a festival season, a pandemic, a competitor's discount. Accuracy quietly drops and nobody notices until a business number goes red. **Monitoring** catches this early.

::scene The model that slowly went blind
🎯 | Launch | The fraud model launches at 94% recall. Everyone celebrates. | dev
🔄 | The world changes | Three months later, fraudsters switch to UPI collect requests: a pattern the model never saw. | bugsy
📉 | Drift | The input data looks different from training data. The drift alarm turns orange. | chip
🔁 | Retrain | Fresh data, retrain, compare, redeploy. Recall is back to 93%. | byte
::end

## What to monitor

| Layer | Examples | Why |
|---|---|---|
| System | latency, errors, uptime | Is the API working? |
| Data | missing values, ranges, new categories, **drift** | Is the input still like training data? |
| Predictions | share predicted positive, score distribution | Is the model behaving differently? |
| Quality | accuracy, recall (once true labels arrive) | Is it still right? |
| Business | fraud losses, conversions, complaints | Is it still useful? |

True labels often arrive late (did the customer churn? you know in 90 days), so **data and prediction drift are your early-warning signals**.

## Population Stability Index (PSI)

PSI compares the distribution of a feature in production with training, bin by bin.

::walk python | PSI for one feature
import math
train = [0.25, 0.25, 0.25, 0.25]
live = [0.10, 0.20, 0.30, 0.40]
psi = sum((l - t) * math.log(l / t) for t, l in zip(train, live))
status = "stable" if psi < 0.1 else "watch" if psi < 0.25 else "drifted"
print(round(psi, 3), status)
::notes
1: math for the logarithm.
2: Share of training data in each of 4 bins (for example, transaction amount quartiles). // vars: train=[0.25, 0.25, 0.25, 0.25]
3: Share of this week's live data in the same bins: big amounts are now more common. // vars: live=[0.1, 0.2, 0.3, 0.4]
4: PSI = Σ (live − train) × ln(live ÷ train). Each bin adds more the further it moved. // deep: Bins with zero share break the log; real implementations add a tiny value. PSI is popular in banking credit-risk teams; the KS test and Jensen-Shannon distance are common alternatives. // vars: psi=0.228
5: A common rule of thumb: below 0.1 stable, 0.1–0.25 watch, above 0.25 significant drift. // vars: status=watch
6: This feature needs attention but isn't an emergency yet. // out: 0.228 watch
::end

## Drift checks on many features

```python
import numpy as np
from scipy.stats import ks_2samp

rng = np.random.default_rng(0)
training = {"amount": rng.lognormal(7, 0.5, 5000), "age": rng.normal(35, 9, 5000), "hour": rng.integers(0, 24, 5000)}
live = {"amount": rng.lognormal(7.4, 0.6, 2000), "age": rng.normal(35.3, 9, 2000), "hour": rng.integers(0, 24, 2000)}

for feature in training:
    stat, p = ks_2samp(training[feature], live[feature])
    flag = "DRIFT" if stat > 0.1 else "ok"
    print(f"{feature:>6}: KS {stat:.3f}  {flag}")
```

Output:

```text
amount: KS 0.293  DRIFT
   age: KS 0.023  ok
  hour: KS 0.030  ok
```

With thousands of rows, p-values are almost always tiny, so teams usually alert on the **size** of the difference (the KS statistic or PSI), not significance alone. Tools like Evidently, WhyLabs, SageMaker Model Monitor and Azure ML data drift monitors automate these reports.

## When to retrain

1. **On a schedule:** monthly or weekly; simple and predictable.
2. **On a trigger:** drift above a threshold, or quality below a threshold once labels arrive.
3. **Always with a check:** train the new model, compare it with the current one on recent data, deploy only if it's better (a **champion/challenger** setup), and keep the old version ready for rollback.

```python
champion_recall, challenger_recall, min_gain = 0.88, 0.92, 0.01
deploy = challenger_recall >= champion_recall + min_gain
print("deploy new model?", deploy)
```

Output:

```text
deploy new model? True
```

::tip Log every prediction with its inputs, model version and timestamp. Without that log you can't measure drift, investigate a complaint, or build the next training set.

## Summary

- Models decay because the world changes; monitor system, data, predictions, quality and business metrics.
- PSI and KS statistics measure drift per feature; alert on effect size.
- Retrain on a schedule or trigger, but deploy only if the challenger beats the champion.
- Keep a prediction log and a rollback plan.

🎉 You've finished **MLOps: Deploying Models**.
