---
title: A/B Test Significance Calculator
slug: ab-test-calculator
parent: tools
template: page-tool.php
icon: 🆎
order: 60
excerpt: Is version B really better, or was it luck? Enter visitors and conversions to get the lift, p-value and confidence interval instantly.
---
## How to use the A/B test calculator

Enter how many visitors saw each version and how many of them **converted** (bought, signed up, clicked). The result updates as you type:

- **Conversion rate** of A and B, side by side.
- **Relative lift:** how much better (or worse) B is than A, in percent.
- **p-value:** how likely a difference this big would be if A and B were really the same.
- **Confidence interval:** the range the true difference probably lies in.

## Reading the result

The default example has 250 conversions out of 5,000 for A (5%) and 300 out of 5,000 for B (6%). That is a 20% relative lift, with a p-value of about 0.028. Because 0.028 is below 0.05, the difference is **statistically significant** at 95% confidence: it is unlikely to be luck.

If the confidence interval for the difference includes 0, the test is not significant: B might be better, worse, or the same.

## How it works

The calculator runs a **two-proportion z-test**, the same test data scientists run in Python:

```python
from statsmodels.stats.proportion import proportions_ztest
z, p = proportions_ztest([300, 250], [5000, 5000])
```

It pools both groups to estimate the standard error, turns the difference into a z-score, and converts that into a two-sided p-value.

## Three mistakes to avoid

1. **Peeking:** stopping the test the first time p dips below 0.05. Decide the sample size first (use our [sample size calculator](/tools/sample-size-calculator/)).
2. **Too many variations:** testing 10 versions at once means one will "win" by chance.
3. **Ignoring the size of the effect:** with huge traffic, a 0.1% difference can be significant but not worth anything.

Learn the theory step by step, with animations, in our lesson on [hypothesis tests and p-values](/tutorials/hypothesis-tests-p-values/).
