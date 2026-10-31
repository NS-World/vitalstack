---
title: Probability, Conditional Probability and Bayes' Theorem
slug: probability-conditional-bayes
subject: statistics
lesson: 2
difficulty: beginner
excerpt: Probability from counting, "given that" questions, independence, and Bayes' theorem explained with a medical test and a spam filter, checked by simulation in Python.
---

Probability is just **counting fairly**. Out of all the things that could happen, how many are the thing you care about?

P(event) = favourable outcomes ÷ all equally likely outcomes

A die has 6 faces. P(rolling a 5) = 1/6. P(rolling an even number) = 3/6 = 0.5. Probabilities live between 0 (never) and 1 (certain).

## Checking it by simulation

The best way to build intuition: let Python roll a die a hundred thousand times and count.

```python
import numpy as np

rng = np.random.default_rng(42)
rolls = rng.integers(1, 7, size=100_000)

print("P(5)    ≈", round((rolls == 5).mean(), 3), " exact", round(1 / 6, 3))
print("P(even) ≈", round((rolls % 2 == 0).mean(), 3), " exact", 0.5)
```

Output:

```text
P(5)    ≈ 0.168  exact 0.167
P(even) ≈ 0.5  exact 0.5
```

`(rolls == 5)` is an array of True/False; its `mean()` is the fraction of Trues. With many rolls the fraction settles near the exact answer. That is the **law of large numbers**, and simulation is a superpower: when the maths gets hard, simulate.

## AND, OR, NOT

- **NOT:** P(not A) = 1 − P(A). P(not rolling a 6) = 5/6.
- **OR** (A or B or both): P(A or B) = P(A) + P(B) − P(A and B). Subtract the overlap or you count it twice.
- **AND** for **independent** events (one doesn't affect the other): P(A and B) = P(A) × P(B). Two coin flips both heads: 0.5 × 0.5 = 0.25.

```python
import numpy as np

rng = np.random.default_rng(1)
coin1 = rng.integers(0, 2, size=200_000)   # 1 = heads
coin2 = rng.integers(0, 2, size=200_000)

both = ((coin1 == 1) & (coin2 == 1)).mean()
either = ((coin1 == 1) | (coin2 == 1)).mean()
print("P(both heads)   ≈", round(both, 3), " formula 0.25")
print("P(at least one) ≈", round(either, 3), " formula", 0.5 + 0.5 - 0.25)
```

Output:

```text
P(both heads)   ≈ 0.249  formula 0.25
P(at least one) ≈ 0.75  formula 0.75
```

## Conditional probability: "given that…"

P(A | B) reads "probability of A **given** B". Once you know B happened, you only look at the cases where B is true:

P(A | B) = P(A and B) ÷ P(B)

A real example from an online store, 1,000 visitors:

```python
import pandas as pd

visits = pd.DataFrame({
    "device": ["mobile"] * 700 + ["laptop"] * 300,
    "bought": [True] * 49 + [False] * 651 + [True] * 36 + [False] * 264,
})
print(pd.crosstab(visits["device"], visits["bought"], margins=True))

p_buy = visits["bought"].mean()
p_buy_given_laptop = visits.loc[visits["device"] == "laptop", "bought"].mean()
print("P(buy)          =", p_buy)
print("P(buy | laptop) =", p_buy_given_laptop)
```

Output:

```text
bought  False  True   All
device                   
laptop    264    36   300
mobile    651    49   700
All       915    85  1000
P(buy)          = 0.085
P(buy | laptop) = 0.12
```

Overall, 8.5% of visitors buy. But **given** the visitor is on a laptop, it is 12%. Knowing the device changed the probability, so device and buying are **not independent**. That kind of finding drives real business decisions (which page to improve first).

## Bayes' theorem: flipping the question

Here is a question most people, including many doctors in surveys, get badly wrong:

> A disease affects 1 in 100 people. A test catches 99% of sick people, but also gives a false "positive" to 5% of healthy people. You test positive. What is the chance you are actually sick?

Most people say "99%". Let's count instead, with 10,000 people:

::viz stat-bayes {"rate": "1 in 100"}

```python
people = 10_000
sick = people * 0.01            # 100
healthy = people - sick         # 9,900

true_pos = sick * 0.99          # sick people who test positive
false_pos = healthy * 0.05      # healthy people who test positive

p_sick_given_pos = true_pos / (true_pos + false_pos)
print("true positives :", true_pos)
print("false positives:", false_pos)
print("P(sick | positive) =", round(p_sick_given_pos, 3))
```

Output:

```text
true positives : 99.0
false positives: 495.0
P(sick | positive) = 0.167
```

**Only about 17%.** The test is accurate, but healthy people outnumber sick ones 99 to 1, so their 5% false alarms swamp the real cases. That counting is exactly Bayes' theorem:

P(A | B) = P(B | A) × P(A) ÷ P(B)

- **P(A)**, the **prior**: how likely before any evidence (1% are sick).
- **P(B | A)**, the **likelihood**: how likely the evidence is if A is true (99% of sick test positive).
- **P(B)**: how likely the evidence is overall (all positives, true and false).
- **P(A | B)**, the **posterior**: your updated belief after the evidence.

```python
def bayes(prior, likelihood, false_alarm_rate):
    evidence = likelihood * prior + false_alarm_rate * (1 - prior)
    return likelihood * prior / evidence

print(round(bayes(0.01, 0.99, 0.05), 3))
print(round(bayes(0.10, 0.99, 0.05), 3))   # a more common disease
print(round(bayes(0.17, 0.99, 0.05), 3))   # test again: yesterday's posterior is today's prior
```

Output:

```text
0.167
0.688
0.802
```

Here is the same function, run line by line with the medical-test numbers:

::walk python | Bayes' theorem as a function
def bayes(prior, hit_rate, false_alarm):
    true_pos = hit_rate * prior
    false_pos = false_alarm * (1 - prior)
    return true_pos / (true_pos + false_pos)

p = bayes(prior=0.01, hit_rate=0.99, false_alarm=0.05)
print(round(p, 3))
::notes
1: We **define** a function with three inputs. Nothing runs yet: Python just remembers the recipe. // deep: def creates a function object named bayes. Its body runs only when it is called on line 6.
6: Now we **call** it: 1% of people are sick, the test catches 99%, and 5% of healthy people get a false alarm. // vars: prior=0.01, hit_rate=0.99, false_alarm=0.05
2: Share of everyone who is sick **and** tests positive: 0.99 × 0.01. // deep: This is P(positive | sick) × P(sick) = P(positive and sick). // vars: true_pos=0.0099
3: Share who are healthy **but** test positive: 0.05 × 0.99. Five times more than the real cases! // deep: 1 − prior is P(healthy) = 0.99. This is P(positive and healthy). // vars: false_pos=0.0495
4: Of all positives, how many are real? 0.0099 ÷ 0.0594 ≈ **0.167**. // deep: The denominator is P(positive), the evidence. The result is the posterior P(sick | positive). // vars: result=0.16666666666666669
6: The answer comes back and is stored in **p**. // vars: p=0.16666666666666669
7: Only **16.7%**: a positive test from a rare disease is usually a false alarm. // out: 0.167
::end

Two lessons hiding here:

1. **Base rates matter.** For a rare event, even a great test produces mostly false alarms. This is why fraud alerts, spam filters and medical screening all need a second check.
2. **Evidence stacks.** A second positive test takes you from 17% to about 80%. Bayes is how beliefs should update.

::tip Where you'll meet Bayes as a data scientist: Naive Bayes spam filters, A/B testing tools, recommendation systems and any model that outputs a probability you need to interpret.

## A tiny spam filter

The word "lottery" appears in 30% of spam emails but only 0.5% of normal emails. 20% of all email is spam. An email contains "lottery". Spam?

```python
p_spam = 0.20
p_word_given_spam = 0.30
p_word_given_ham = 0.005

p_word = p_word_given_spam * p_spam + p_word_given_ham * (1 - p_spam)
print("P(spam | 'lottery') =", round(p_word_given_spam * p_spam / p_word, 3))
```

Output:

```text
P(spam | 'lottery') = 0.938
```

About 94% spam. A real Naive Bayes filter does exactly this for every word in the email and multiplies the evidence together.

## Summary

- Probability = favourable ÷ all outcomes; simulate when in doubt.
- P(A | B) restricts attention to cases where B happened.
- Independent events: multiply. Not independent: use conditional probability.
- Bayes' theorem turns "P(evidence | cause)" into "P(cause | evidence)", and the prior can matter more than the test.
