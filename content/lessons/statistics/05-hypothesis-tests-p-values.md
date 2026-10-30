---
title: "Hypothesis Tests: z, t, Chi-Square and p-values"
slug: hypothesis-tests-p-values
subject: statistics
lesson: 5
difficulty: intermediate
excerpt: Decide with data instead of gut feeling. Null hypothesis, p-values, the t-test for A/B tests, chi-square for categories, and the mistakes to avoid.
---

Your team changes the "Buy" button from grey to green and sales go up 3%. Real improvement, or just a lucky week? A **hypothesis test** answers: *if nothing had really changed, how surprising would this result be?*

## The recipe

1. **Null hypothesis (H₀):** the boring explanation. "The button colour makes no difference."
2. **Alternative (H₁):** what you suspect. "It makes a difference."
3. Compute a **test statistic** from the data.
4. Get the **p-value**: the probability of a result **at least this extreme** if H₀ were true.
5. If the p-value is below a threshold chosen in advance (usually **0.05**), reject H₀.

## A first test: is this coin fair?

You flip a coin 100 times and get 61 heads. Fair coin or not?

::viz stat-pvalue {"heads": 61}

```python
from scipy import stats

result = stats.binomtest(61, n=100, p=0.5)
print("p-value:", round(result.pvalue, 4))
```

Output:

```text
p-value: 0.0352
```

If the coin were fair, 61 or more heads (or 39 or fewer) happens only about 3.5% of the time. That is below 0.05, so we reject "fair". With 56 heads it would be a different story:

```python
from scipy import stats

print("56 heads p-value:", round(stats.binomtest(56, n=100, p=0.5).pvalue, 4))
```

Output:

```text
56 heads p-value: 0.2713
```

0.27: a fair coin does that all the time. Not evidence of anything.

::hint What a p-value is NOT | It is not "the probability that H₀ is true", and a small p-value doesn't mean the effect is big or important. It only says: this data would be surprising if nothing were going on.

## The t-test: comparing two averages (A/B testing)

Two versions of a checkout page. Did version B make people spend more?

```python
import numpy as np
from scipy import stats

rng = np.random.default_rng(11)
a = rng.normal(loc=520, scale=120, size=200)   # order values on page A (₹)
b = rng.normal(loc=548, scale=120, size=200)   # page B

print("mean A:", round(a.mean(), 1), " mean B:", round(b.mean(), 1))
t, p = stats.ttest_ind(b, a, equal_var=False)
print("t =", round(t, 2), " p-value =", round(p, 4))
```

Output:

```text
mean A: 522.0  mean B: 556.8
t = 3.0  p-value = 0.0028
```

The difference is about ₹35 per order and the p-value is well below 0.05: very unlikely to be luck. Ship version B.

- `ttest_ind`: two **independent** groups (different users saw A and B).
- `ttest_rel`: the **same** people measured twice (before and after training).
- `ttest_1samp`: one group against a fixed number ("is our average delivery time different from the promised 30 minutes?").

```python
import numpy as np
from scipy import stats

times = np.array([31, 34, 29, 36, 33, 35, 32, 30, 37, 34])
t, p = stats.ttest_1samp(times, popmean=30)
print("mean", times.mean(), " p-value", round(p, 4))
```

Output:

```text
mean 33.1  p-value 0.0044
```

Average 33.1 minutes vs a promise of 30, p ≈ 0.004: the promise is being broken, and it isn't bad luck.

## Chi-square: are two categories related?

For counts in categories (not averages), use the **chi-square test of independence**. Does the payment method chosen depend on the city?

```python
import pandas as pd
from scipy import stats

table = pd.DataFrame(
    {"UPI": [320, 410, 150], "card": [140, 120, 130], "cash": [40, 70, 120]},
    index=["Mumbai", "Pune", "Patna"],
)
print(table)
chi2, p, dof, expected = stats.chi2_contingency(table)
print("chi2 =", round(chi2, 1), " p-value =", f"{p:.1e}")
```

Output:

```text
        UPI  card  cash
Mumbai  320   140    40
Pune    410   120    70
Patna   150   130   120
chi2 = 137.0  p-value = 1.2e-28
```

A tiny p-value: payment method clearly depends on the city (Patna uses much more cash). The `expected` table shows what the counts would look like if there were no relationship; chi-square measures how far reality is from it.

## The two ways to be wrong

| | H₀ actually true | H₀ actually false |
|---|---|---|
| You reject H₀ | **Type I error** (false alarm) | correct |
| You keep H₀ | correct | **Type II error** (missed effect) |

The 0.05 threshold means you accept a 5% false-alarm rate when nothing is going on. Bigger samples reduce Type II errors (they give the test more **power**).

## Mistakes to avoid

- **p-hacking:** testing 20 things and reporting the one with p < 0.05. By chance alone, 1 in 20 will "work". Decide what you test before you look.
- **Peeking:** stopping an A/B test the moment p dips below 0.05. Fix the sample size in advance.
- **Significant ≠ important:** with a million users, a ₹0.50 difference can be "significant" and still not worth anything. Always report the size of the effect too, ideally with a confidence interval.

## Summary

| Question | Test | SciPy |
|---|---|---|
| Is a proportion different from p? | binomial | `stats.binomtest` |
| Do two groups' averages differ? | t-test | `stats.ttest_ind` |
| Did the same people change? | paired t-test | `stats.ttest_rel` |
| Are two categories related? | chi-square | `stats.chi2_contingency` |
