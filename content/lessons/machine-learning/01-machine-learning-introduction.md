---
title: Machine Learning Introduction
slug: machine-learning-introduction
subject: machine-learning
lesson: 1
difficulty: beginner
excerpt: What machine learning really is, the three kinds of learning, features and labels, and the workflow every model follows.
---

Normal programming: **you** write the rules. "If marks ≥ 40, print Pass."

Machine learning: you give the computer **examples with answers**, and it finds the rules itself. Show it 10,000 emails labelled "spam" or "not spam", and it learns which words and patterns mean spam, including ones you'd never think to write down.

| | Traditional programming | Machine learning |
|---|---|---|
| Input | data + rules | data + answers |
| Output | answers | rules (a **model**) |

## Where you already use it

- YouTube and Netflix recommendations
- Google Maps arrival times
- UPI and bank fraud alerts
- Face unlock on your phone
- ChatGPT, Claude and Gemini (a very large kind of model called an LLM)

## Three kinds of learning

**1. Supervised learning:** every example has the right answer (a **label**). The model learns to predict that label for new data.
- **Regression** predicts a number: house price, temperature, delivery time.
- **Classification** predicts a category: spam or not, pass or fail, which disease.

**2. Unsupervised learning:** no labels. The model finds structure on its own, for example grouping customers with similar shopping habits (**clustering**).

**3. Reinforcement learning:** the model learns by trial and error with rewards, like a game-playing AI or a robot learning to walk.

This course focuses on supervised learning, which covers most real jobs.

## Features and labels

Every dataset for supervised learning has two parts:

| area_sqft | rooms | age_years | **price_lakh** |
|---|---|---|---|
| 1200 | 2 | 5 | **68** |
| 1800 | 3 | 12 | **95** |
| 900 | 2 | 20 | **41** |

- **Features** (called `X`): the inputs the model looks at: area, rooms, age.
- **Label** or **target** (called `y`): the answer to predict: price.

```python
import pandas as pd

homes = pd.DataFrame({
    "area_sqft": [1200, 1800, 900],
    "rooms": [2, 3, 2],
    "age_years": [5, 12, 20],
    "price_lakh": [68, 95, 41],
})
X = homes[["area_sqft", "rooms", "age_years"]]
y = homes["price_lakh"]
print(X.shape, y.shape)
```

Output:

```text
(3, 3) (3,)
```

By convention `X` is capital (a table) and `y` is lower-case (one column).

## The workflow

Every model, from a tiny one to ChatGPT, follows the same steps. Play through them:

::viz ml-workflow

1. **Collect and clean data** (your Pandas skills).
2. **Split** it into a training set and a test set.
3. **Train** (`fit`): the model learns from the training set.
4. **Predict** on the test set, which it has never seen.
5. **Evaluate**: compare predictions with the real answers.
6. Improve and repeat.

::tip The test set is like a surprise exam. If you let the model see it while studying, a high score means nothing. Always keep it separate.

## The tool: scikit-learn

**scikit-learn** (imported as `sklearn`) is Python's standard machine learning library. Every model in it has the same three methods, which makes it easy to try many:

```py
model.fit(X_train, y_train)     # learn
model.predict(X_new)            # predict
model.score(X_test, y_test)     # evaluate
```

```bash
pip install scikit-learn
```

::hint Quick check | Predicting whether a customer will cancel their subscription (yes/no): regression or classification? Classification, because the answer is a category, not a number.

## Summary

- Machine learning finds rules from examples instead of you writing them.
- Supervised = learning from labelled examples: regression (numbers) or classification (categories).
- Features `X` are the inputs; the label `y` is the answer.
- Workflow: clean → split → fit → predict → evaluate.
