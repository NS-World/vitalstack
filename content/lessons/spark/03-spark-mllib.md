---
title: "Machine Learning at Scale with Spark MLlib"
slug: spark-mllib
subject: spark
lesson: 3
difficulty: intermediate
excerpt: Train models on huge datasets with Spark MLlib pipelines: VectorAssembler, StringIndexer, logistic regression and random forests, evaluation, and how it maps to scikit-learn pipelines you can run.
---

When the training data is hundreds of millions of rows (every click, every transaction), scikit-learn on one machine runs out of memory. **MLlib** is Spark's machine-learning library: the same ideas, distributed across a cluster.

::scene Training on a billion clicks
🖱️ | The data | A billion ad clicks. Will this user click the next ad? | dev
🧱 | Assemble | Spark gathers each row's features into one vector column. | chip
🏋️ | Train | Logistic regression trains in parallel; each machine computes gradients on its partition. | byte
📏 | Evaluate | The evaluator scores the model on a held-out split. | bugsy
💾 | Save | The whole pipeline is saved and reused every night on new data. | dev
::end

## An MLlib pipeline

```python
from pyspark.ml import Pipeline
from pyspark.ml.feature import StringIndexer, OneHotEncoder, VectorAssembler, StandardScaler
from pyspark.ml.classification import LogisticRegression
from pyspark.ml.evaluation import BinaryClassificationEvaluator

df = spark.read.parquet("s3://my-bucket/clicks/")
train, test = df.randomSplit([0.8, 0.2], seed=42)

pipeline = Pipeline(stages=[
    StringIndexer(inputCol="device", outputCol="device_idx", handleInvalid="keep"),
    OneHotEncoder(inputCols=["device_idx"], outputCols=["device_vec"]),
    VectorAssembler(inputCols=["device_vec", "age", "past_clicks", "hour"], outputCol="raw"),
    StandardScaler(inputCol="raw", outputCol="features"),
    LogisticRegression(featuresCol="features", labelCol="clicked"),
])
model = pipeline.fit(train)
auc = BinaryClassificationEvaluator(labelCol="clicked").evaluate(model.transform(test))
print("test AUC:", auc)
model.write().overwrite().save("s3://my-bucket/models/click-model")
```

MLlib models expect all features in **one vector column**, which is what `VectorAssembler` builds.

## The same pipeline in scikit-learn (runnable)

```python
import numpy as np
import pandas as pd
from sklearn.compose import ColumnTransformer
from sklearn.pipeline import Pipeline
from sklearn.preprocessing import OneHotEncoder, StandardScaler
from sklearn.linear_model import LogisticRegression
from sklearn.model_selection import train_test_split
from sklearn.metrics import roc_auc_score

rng = np.random.default_rng(0)
n = 3000
df = pd.DataFrame({"device": rng.choice(["android", "ios", "web"], n), "age": rng.integers(18, 60, n),
                   "past_clicks": rng.poisson(2, n), "hour": rng.integers(0, 24, n)})
logit = -2 + 0.6 * df["past_clicks"] + 0.8 * (df["device"] == "ios") - 0.02 * (df["age"] - 30)
df["clicked"] = (rng.random(n) < 1 / (1 + np.exp(-logit))).astype(int)

pipe = Pipeline([
    ("prep", ColumnTransformer([("cat", OneHotEncoder(handle_unknown="ignore"), ["device"]),
                                ("num", StandardScaler(), ["age", "past_clicks", "hour"])])),
    ("model", LogisticRegression(max_iter=1000)),
])
train, test = train_test_split(df, test_size=0.2, random_state=42)
pipe.fit(train.drop(columns="clicked"), train["clicked"])
print("test AUC:", round(roc_auc_score(test["clicked"], pipe.predict_proba(test.drop(columns="clicked"))[:, 1]), 3))
```

Output:

```text
test AUC: 0.768
```

| scikit-learn | Spark MLlib |
|---|---|
| `Pipeline` | `Pipeline` |
| `OneHotEncoder` | `StringIndexer` + `OneHotEncoder` |
| `ColumnTransformer` + features | `VectorAssembler` |
| `LogisticRegression`, `RandomForestClassifier` | same names in `pyspark.ml.classification` |
| `GridSearchCV` | `CrossValidator` + `ParamGridBuilder` |
| `roc_auc_score` | `BinaryClassificationEvaluator` |

## How training is distributed

::walk python | Averaging gradients from each partition
partition_grads = [0.40, 0.35, 0.45, 0.40]
sizes = [1000, 1000, 1000, 1000]
avg = sum(g * s for g, s in zip(partition_grads, sizes)) / sum(sizes)
weight = 1.0 - 0.5 * avg
print(round(avg, 3), round(weight, 3))
::notes
1: Each executor computes the gradient of the loss on its own partition of rows. // vars: partition_grads=[0.4, 0.35, 0.45, 0.4]
2: How many rows each partition has. // vars: sizes=[1000, 1000, 1000, 1000]
3: The driver combines them into one gradient, weighted by partition size. // deep: Only these small numbers travel over the network, not the billion rows. That's why gradient-based models scale well on Spark. // vars: avg=0.4
4: One update step for the model weight (learning rate 0.5). Repeat until it converges. // vars: weight=0.8
5: Same gradient descent you learned before, split across machines. // out: 0.4 0.8
::end

::tip A common real-world pattern: use Spark to clean, join and aggregate billions of rows into features, sample or aggregate them down, then train with scikit-learn or XGBoost on one big machine. Pick the simplest setup that fits the data.

## Summary

- MLlib brings pipelines, feature transformers and models to Spark.
- `VectorAssembler` packs features into one vector column.
- MLlib pipelines map one-to-one to scikit-learn pipelines.
- Gradient-based training scales by combining per-partition gradients.

🎉 You've finished **Big Data with Apache Spark**.
