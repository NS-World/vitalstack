---
title: "Distributions: Binomial, Normal and the Central Limit Theorem"
slug: distributions-normal-central-limit-theorem
subject: statistics
lesson: 3
difficulty: intermediate
excerpt: The shapes data takes. Binomial for counts of successes, the normal bell curve and the 68-95-99.7 rule, z-scores, and why averages are always bell-shaped.
---

A **distribution** describes all the values something can take and how often each one happens. Knowing the shape lets you answer questions like "how unusual is this?" without collecting more data.

## Binomial: counting successes

Use it when you repeat the same yes/no trial a fixed number of times, each with the same chance of success.

> An ad gets clicked by 4% of people who see it. It is shown to 50 people. How many clicks should we expect, and how likely is "zero clicks"?

```python
from scipy import stats

clicks = stats.binom(n=50, p=0.04)
print("expected clicks:", clicks.mean())
print("P(0 clicks)    :", round(clicks.pmf(0), 3))
print("P(at least 5)  :", round(1 - clicks.cdf(4), 3))
```

Output:

```text
expected clicks: 2.0
P(0 clicks)    : 0.13
P(at least 5)  : 0.049
```

- `pmf(k)`: probability of **exactly** k.
- `cdf(k)`: probability of **k or fewer**. So "at least 5" = 1 − P(4 or fewer).

Zero clicks happens about 13% of the time even when nothing is wrong. Before you panic about a campaign, ask how likely the result is by chance. That idea becomes hypothesis testing in lesson 5.

## The normal distribution (the bell curve)

Heights, exam scores, measurement errors, daily temperatures: many things pile up around an average and thin out symmetrically. That is the **normal distribution**, described by just two numbers: the **mean** (centre) and the **standard deviation** (width).

The **68-95-99.7 rule** is worth memorising:

| Within… | of the mean | share of values |
|---|---|---|
| 1 std | μ ± 1σ | about 68% |
| 2 std | μ ± 2σ | about 95% |
| 3 std | μ ± 3σ | about 99.7% |

```python
from scipy import stats

for k in [1, 2, 3]:
    inside = stats.norm.cdf(k) - stats.norm.cdf(-k)
    print(f"within {k} std: {inside:.3f}")
```

Output:

```text
within 1 std: 0.683
within 2 std: 0.954
within 3 std: 0.997
```

### z-scores: how unusual is a value?

A **z-score** says how many standard deviations a value is from the mean: z = (x − mean) ÷ std. It lets you compare things on different scales.

```python
from scipy import stats

# Exam A: mean 60, std 10. Exam B: mean 72, std 5.
asha_z = (78 - 60) / 10
rahul_z = (82 - 72) / 5
print("Asha z =", asha_z, " Rahul z =", rahul_z)
print("Rahul beat", round(stats.norm.cdf(rahul_z) * 100, 1), "% of his class")
```

Output:

```text
Asha z = 1.8  Rahul z = 2.0
Rahul beat 97.7 % of his class
```

Asha scored 78, Rahul 82, but Rahul's z-score is higher: relative to his class, he did better. A z beyond ±3 is rare enough that data scientists often treat it as an outlier.

## The central limit theorem (CLT)

This is the reason the normal curve shows up everywhere, and the reason most statistical tests work:

> Take many samples and compute each sample's **average**. Those averages form a normal distribution, **whatever the shape of the original data**, as long as the samples are big enough.

One die is flat (each face 1/6). Watch what happens to the **average** of several dice:

::viz stat-clt {"k": "5"}

```python
import numpy as np

rng = np.random.default_rng(7)
for dice in [1, 2, 5, 30]:
    means = rng.integers(1, 7, size=(100_000, dice)).mean(axis=1)
    print(f"{dice:>2} dice: mean of averages {means.mean():.2f}, std {means.std():.3f}")
```

Output:

```text
 1 dice: mean of averages 3.51, std 1.710
 2 dice: mean of averages 3.50, std 1.206
 5 dice: mean of averages 3.50, std 0.761
30 dice: mean of averages 3.50, std 0.312
```

Two things happen as the sample grows:

1. The averages centre on the true mean (3.5).
2. They get **narrower**: the std shrinks like 1.708 ÷ √n (1.708 is one die's std). With 30 dice it is 1.708 ÷ √30 ≈ 0.312.

That narrowing has a name, the **standard error**, and it is the engine behind confidence intervals in the next lesson.

```python
import matplotlib.pyplot as plt
import numpy as np

rng = np.random.default_rng(7)
fig, axes = plt.subplots(1, 3, figsize=(9, 2.6), sharey=True)
for ax, dice in zip(axes, [1, 5, 30]):
    means = rng.integers(1, 7, size=(20_000, dice)).mean(axis=1)
    ax.hist(means, bins=30, color="#0a7d4f")
    ax.set_title(f"average of {dice} dice")
fig.tight_layout()
plt.show()
```

::figure stat-clt-dice.svg | Histograms of dice averages: flat for 1 die, bell-shaped for 5 and narrower for 30

## Summary

- **Binomial**: number of successes in n yes/no trials. `stats.binom(n, p)`.
- **Normal**: the bell curve, set by mean and std. 68-95-99.7.
- **z-score** = (x − mean) ÷ std: how unusual a value is.
- **CLT**: sample averages are normal and get narrower with bigger samples, even if the data isn't normal.
