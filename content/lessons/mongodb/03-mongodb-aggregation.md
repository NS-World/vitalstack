---
title: "The MongoDB Aggregation Pipeline"
slug: mongodb-aggregation
subject: mongodb
lesson: 3
difficulty: intermediate
excerpt: Analyse data inside MongoDB with the aggregation pipeline: $match, $unwind, $group, $sort, $project and $lookup, with the same analysis reproduced in pandas so you can run it.
---

Finding documents is half the job. The other half is **analysis**: revenue by city, best-selling products, average rating per category. MongoDB's **aggregation pipeline** does this on the server, as a series of stages, like an assembly line.

::scene The assembly line
🏭 | Raw orders | Thousands of order documents enter the pipeline. | dev
🚪 | $match | Only delivered orders from this month get through. | chip
📦 | $unwind | Each order with 3 items becomes 3 rows, one per item. | byte
🧮 | $group | Rows are grouped by product, and revenue is summed. | bugsy
🏆 | $sort | Best sellers come out on top. | dev
::end

## A pipeline in PyMongo

```python
from pymongo import MongoClient

orders = MongoClient()["shop"]["orders"]
pipeline = [
    {"$match": {"status": "delivered"}},
    {"$unwind": "$items"},
    {"$group": {"_id": "$items.sku",
                "units": {"$sum": "$items.qty"},
                "revenue": {"$sum": {"$multiply": ["$items.qty", "$items.price"]}}}},
    {"$sort": {"revenue": -1}},
    {"$limit": 3},
]
for row in orders.aggregate(pipeline):
    print(row)
```

## The same analysis in pandas (runnable)

Each stage maps to a familiar pandas step:

```python
import pandas as pd

orders = [
    {"_id": 1, "city": "Pune", "status": "delivered", "items": [{"sku": "TEA", "qty": 2, "price": 120}, {"sku": "MUG", "qty": 1, "price": 250}]},
    {"_id": 2, "city": "Delhi", "status": "delivered", "items": [{"sku": "TEA", "qty": 5, "price": 120}]},
    {"_id": 3, "city": "Pune", "status": "cancelled", "items": [{"sku": "MUG", "qty": 4, "price": 250}]},
    {"_id": 4, "city": "Mumbai", "status": "delivered", "items": [{"sku": "DIYA", "qty": 10, "price": 30}, {"sku": "TEA", "qty": 1, "price": 120}]},
]
df = pd.DataFrame(orders)
df = df[df["status"] == "delivered"]                         # $match
df = df.explode("items", ignore_index=True)                   # $unwind
items = pd.json_normalize(df["items"])
df = pd.concat([df.drop(columns="items"), items], axis=1)
df["revenue"] = df["qty"] * df["price"]
top = (df.groupby("sku").agg(units=("qty", "sum"), revenue=("revenue", "sum"))   # $group
         .sort_values("revenue", ascending=False))                              # $sort
print(top)
```

Output:

```text
      units  revenue
sku
TEA       8      960
DIYA     10      300
MUG       1      250
```

The cancelled order (4 mugs) was filtered out by `$match`, so MUG shows only 1 unit.

## Watch $group work

::walk python | $group: one bucket per key
rows = [("TEA", 240), ("MUG", 250), ("TEA", 600), ("TEA", 120)]
groups = {}
for sku, revenue in rows:
    groups[sku] = groups.get(sku, 0) + revenue
best = max(groups, key=groups.get)
print(groups, best)
::notes
1: Rows after $unwind: one per item, with its revenue. // vars: rows=4 rows
2: An empty dictionary of buckets, like the output of $group. // vars: groups={}
3: Walk through every row.
4: Add the revenue to the bucket for its _id (the SKU). // deep: $group also supports $avg, $min, $max, $count, $push (collect into a list) and $addToSet (unique list). The _id can be a combination, like {"city": "$city", "month": {"$month": "$date"}}. // vars: groups={'TEA': 960, 'MUG': 250}
5: The best seller is the bucket with the biggest total. // vars: best=TEA
6: Same answer as the pipeline and pandas. // out: {'TEA': 960, 'MUG': 250} TEA
::end

## More useful stages

| Stage | Does | pandas equivalent |
|---|---|---|
| `$match` | filter documents | boolean indexing |
| `$project` / `$addFields` | choose or compute fields | select / assign columns |
| `$unwind` | one row per array element | `explode` |
| `$group` | aggregate per key | `groupby().agg()` |
| `$sort`, `$limit` | order and cut | `sort_values`, `head` |
| `$lookup` | join another collection | `merge` |
| `$bucket` | group into ranges | `pd.cut` + `groupby` |

::tip Put `$match` (and `$project`) as early as possible: fewer documents flowing through the pipeline means faster queries, and an early `$match` can use indexes.

## Summary

- The aggregation pipeline runs analysis on the server, stage by stage.
- Core stages: `$match`, `$unwind`, `$group`, `$sort`, `$project`, `$lookup`.
- Each maps to a pandas operation, which makes pipelines easy to reason about.
- Filter early for speed.

🎉 You've finished **NoSQL with MongoDB**.
