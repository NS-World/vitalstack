---
title: Sampling and Confidence Intervals
slug: sampling-confidence-intervals
subject: statistics
lesson: 4
difficulty: intermediate
excerpt: Why a sample of 400 can describe a million people, what a 95% confidence interval really means, and how sample size controls your margin of error.
---

You can't ask every customer, test every bulb or measure every student. You take a **sample** and use it to estimate the truth about the whole **population**. The two big questions:

1. How do I pick a sample that isn't misleading?
2. How sure can I be about what the sample says?

## Good samples are random

A sample is only useful if it represents the population. **Random sampling** (every member has the same chance of being picked) is the simplest way to avoid bias.

Classic mistakes:

- **Convenience sample**: surveying only your college friends about "Indian students' salaries".
- **Self-selection**: only angry customers fill in a feedback form.
- **Survivorship**: studying only startups that succeeded to learn "what works".

```python
import numpy as np
import pandas as pd

rng = np.random.default_rng(0)
population = pd.DataFrame({"spend": rng.gamma(shape=2, scale=500, size=1_000_000)})
print("population mean:", round(population["spend"].mean(), 1))

sample = population.sample(n=400, random_state=1)
print("sample mean    :", round(sample["spend"].mean(), 1))
```

Output:

```text
population mean: 1001.1
sample mean    : 1056.6
```

A random sample of just 400 out of a million lands within about 6% of the truth. But a different sample gives a slightly different answer. How much could it be off? That is what a confidence interval tells you.

## Standard error: how much a sample mean wobbles

From the central limit theorem (last lesson), sample means are normally distributed around the true mean, with spread:

**standard error (SE) = sample std ÷ √n**

```python
import numpy as np

spend = sample["spend"].to_numpy()
n = len(spend)
se = spend.std(ddof=1) / np.sqrt(n)
print("n =", n, " std =", round(spend.std(ddof=1), 1), " SE =", round(se, 1))
```

Output:

```text
n = 400  std = 697.7  SE = 34.9
```

## A 95% confidence interval

About 95% of a normal distribution lies within 1.96 standard deviations of its centre. So:

**95% CI = sample mean ± 1.96 × SE**

```python
mean = spend.mean()
low, high = mean - 1.96 * se, mean + 1.96 * se
print(f"95% CI: {low:.1f} to {high:.1f}")
```

Output:

```text
95% CI: 988.2 to 1125.0
```

The true population mean (1001.1) is inside this interval.

::tip What "95% confident" really means: if you repeated the whole process (new random sample, new interval) many times, about 95% of those intervals would contain the true mean. It is a statement about the **method**, not a 95% chance for this one interval.

Let's check that claim by doing it 1,000 times:

```python
import numpy as np

true_mean = population["spend"].mean()
values = population["spend"].to_numpy()
rng = np.random.default_rng(5)
hits = 0
for _ in range(1000):
    s = rng.choice(values, size=400, replace=False)
    se = s.std(ddof=1) / np.sqrt(400)
    if s.mean() - 1.96 * se <= true_mean <= s.mean() + 1.96 * se:
        hits += 1
print("intervals that caught the true mean:", hits, "out of 1000")
```

Output:

```text
intervals that caught the true mean: 943 out of 1000
```

94.3%: very close to 95%, just as promised.

## Sample size and the margin of error

The **margin of error** is the "±" part: 1.96 × SE. Because SE divides by √n, to **halve** the margin you need **four times** the sample.

```python
import numpy as np

std = 707   # about the spread of our spend data
for n in [100, 400, 1600, 6400]:
    print(f"n = {n:>5}: margin of error ± {1.96 * std / np.sqrt(n):.0f}")
```

Output:

```text
n =   100: margin of error ± 139
n =   400: margin of error ± 69
n =  1600: margin of error ± 35
n =  6400: margin of error ± 17
```

This is why opinion polls use around 1,000 to 2,000 people for a whole country: beyond that, each extra person buys very little precision.

## Proportions: "what % of users…?"

For a yes/no question, the same idea works with SE = √(p × (1 − p) ÷ n):

```python
import numpy as np

liked, asked = 312, 500
p = liked / asked
se = np.sqrt(p * (1 - p) / asked)
print(f"{p:.1%} liked the new feature, 95% CI {p - 1.96 * se:.1%} to {p + 1.96 * se:.1%}")
```

Output:

```text
62.4% liked the new feature, 95% CI 58.2% to 66.6%
```

So you would report: "About 62% of users like it (somewhere between 58% and 67%)." Much more honest than a bare "62.4%".

## Small samples: use t instead of 1.96

With small samples (say under 30), the sample std itself is shaky, so we use the slightly wider **t-distribution**. SciPy does the work:

```python
import numpy as np
from scipy import stats

load_times = np.array([2.1, 2.4, 1.9, 2.8, 2.2, 2.6, 2.0, 2.5])   # seconds
low, high = stats.t.interval(0.95, df=len(load_times) - 1, loc=load_times.mean(), scale=stats.sem(load_times))
print(f"mean {load_times.mean():.2f}s, 95% CI {low:.2f}s to {high:.2f}s")
```

Output:

```text
mean 2.31s, 95% CI 2.05s to 2.57s
```

## Summary

- Random samples avoid bias; how you sample matters more than how many.
- **SE = std ÷ √n**: sample means wobble less as n grows.
- **95% CI = mean ± 1.96 × SE** (use `stats.t.interval` for small samples).
- 4× the data halves the margin of error.
