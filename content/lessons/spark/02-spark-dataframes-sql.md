---
title: "PySpark DataFrames and Spark SQL"
slug: spark-dataframes-sql
subject: spark
lesson: 2
difficulty: intermediate
excerpt: Read big files with PySpark, filter, add columns, group, join and query with Spark SQL, understand lazy evaluation and partitions, and compare every step with pandas.
---

If you know pandas and SQL, you already know most of PySpark. The DataFrame API looks familiar, and you can mix in plain SQL whenever it's clearer.

::scene The same question, at any size
❓ | Question | "Revenue per city for delivered orders last month." | dev
🐼 | pandas | On 50 MB, pandas answers in a second on your laptop. | chip
⚡ | PySpark | On 500 GB, the same code shape runs on a cluster. Spark splits it into hundreds of tasks. | byte
🧾 | SQL | Or write it in plain SQL: spark.sql runs it on the same engine. | bugsy
::end

## The PySpark version

```python
from pyspark.sql import SparkSession, functions as F

spark = SparkSession.builder.appName("orders").getOrCreate()
orders = spark.read.parquet("s3://my-bucket/orders/")        # or .csv(path, header=True, inferSchema=True)
cities = spark.read.csv("cities.csv", header=True)

result = (orders
          .filter(F.col("status") == "delivered")
          .withColumn("revenue", F.col("qty") * F.col("price"))
          .join(cities, on="city_id", how="left")
          .groupBy("city")
          .agg(F.sum("revenue").alias("revenue"), F.countDistinct("customer_id").alias("customers"))
          .orderBy(F.desc("revenue")))
result.show(5)                       # an ACTION: only now does Spark run the plan

orders.createOrReplaceTempView("orders")
spark.sql("SELECT status, COUNT(*) AS n FROM orders GROUP BY status").show()
```

## The same logic in pandas (runnable)

```python
import pandas as pd

orders = pd.DataFrame({"order_id": range(1, 9), "city_id": [1, 2, 1, 3, 2, 1, 3, 1],
                       "customer_id": [11, 12, 11, 13, 14, 15, 13, 16],
                       "status": ["delivered", "delivered", "cancelled", "delivered", "delivered", "delivered", "delivered", "delivered"],
                       "qty": [2, 1, 4, 3, 2, 1, 5, 2], "price": [120, 450, 120, 80, 450, 300, 80, 120]})
cities = pd.DataFrame({"city_id": [1, 2, 3], "city": ["Pune", "Delhi", "Chennai"]})

result = (orders[orders["status"] == "delivered"]
          .assign(revenue=lambda d: d["qty"] * d["price"])
          .merge(cities, on="city_id", how="left")
          .groupby("city")
          .agg(revenue=("revenue", "sum"), customers=("customer_id", "nunique"))
          .sort_values("revenue", ascending=False))
print(result)
```

Output:

```text
         revenue  customers
city
Delhi       1350          2
Pune         780          3
Chennai      640          1
```

| pandas | PySpark |
|---|---|
| `df[df.x > 5]` | `df.filter(F.col("x") > 5)` |
| `df.assign(y=...)` | `df.withColumn("y", ...)` |
| `df.merge(other, on=...)` | `df.join(other, on=..., how=...)` |
| `df.groupby("k").agg(...)` | `df.groupBy("k").agg(F.sum(...), ...)` |
| `df.sort_values(...)` | `df.orderBy(...)` |
| `df.head()` | `df.show()` / `df.limit(5).toPandas()` |

## Lazy evaluation

::walk python | Plans first, work later
plan = []
plan.append("filter status == delivered")
plan.append("add revenue")
plan.append("group by city")
ran = False
print(len(plan), "steps planned, ran:", ran)
::notes
1: In Spark, transformations only add steps to a plan. // vars: plan=[]
2: filter() → a plan step, no data touched yet. // vars: plan=['filter status == delivered']
3: withColumn() → another plan step. // vars: plan=[..., 'add revenue']
4: groupBy().agg() → another. // vars: plan=[..., 'group by city']
5: Still nothing has run. // deep: When an action like show(), count() or write() arrives, Spark's Catalyst optimiser rewrites the whole plan: for example pushing the filter down so it reads less data from Parquet. df.explain() prints the plan. // vars: ran=False
6: Three steps planned, zero rows processed: the work waits for an action. // out: 3 steps planned, ran: False
::end

## Partitions and performance tips

- Data is split into **partitions**; each is processed by one task. Too few = idle machines; too many = overhead.
- **Shuffles** (groupBy, join, orderBy) move data across the network: the expensive part.
- Store big data as **Parquet** (columnar, compressed): Spark reads only the columns it needs.
- **Broadcast** small tables in joins (`F.broadcast(cities)`) to avoid shuffling the big one.
- **Cache** a DataFrame you reuse many times (`df.cache()`).
- Avoid `toPandas()` on huge results: it pulls everything onto one machine.

::tip Write your logic with pandas on a small sample first, check the numbers, then translate to PySpark for the full data. Same answers on the sample = confidence at scale.

## Summary

- PySpark DataFrames mirror pandas: filter, withColumn, join, groupBy, orderBy.
- `spark.sql` runs SQL on the same engine.
- Transformations are lazy; actions trigger an optimised plan.
- Use Parquet, minimise shuffles, broadcast small tables, cache reused data.
