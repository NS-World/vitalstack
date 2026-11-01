---
title: "CRUD and Queries with PyMongo"
slug: mongodb-crud-pymongo
subject: mongodb
lesson: 2
difficulty: intermediate
excerpt: Insert, find, update and delete MongoDB documents from Python with PyMongo, using filters, operators like $gt and $in, projections, sorting and indexes.
---

CRUD means **C**reate, **R**ead, **U**pdate, **D**elete: the four things every app does with data. With MongoDB you do them from Python using the official **PyMongo** driver, with filters written as Python dictionaries.

::scene A day at the product catalogue
➕ | Create | A new product arrives: insert_one adds it as a document. | dev
🔎 | Read | "Phones under Rs 20,000 in stock": find with a filter dictionary. | chip
✏️ | Update | Diwali sale: update_many sets a discount on all festive items. | byte
🗑️ | Delete | Discontinued items are removed with delete_many. | bugsy
::end

## Connecting and CRUD with PyMongo

Install with `pip install pymongo` and run MongoDB locally, or use a free cloud cluster on MongoDB Atlas. The connection string goes in an environment variable, like an API key.

```python
import os
from pymongo import MongoClient

client = MongoClient(os.environ.get("MONGO_URL", "mongodb://localhost:27017"))
products = client["shop"]["products"]

products.insert_many([
    {"name": "Redmi phone", "category": "phone", "price": 13999, "stock": 25, "tags": ["5g"]},
    {"name": "Pixel phone", "category": "phone", "price": 39999, "stock": 0},
    {"name": "Steel bottle", "category": "kitchen", "price": 499, "stock": 120, "tags": ["festive"]},
])

for p in products.find({"category": "phone", "price": {"$lt": 20000}, "stock": {"$gt": 0}}, {"_id": 0, "name": 1}):
    print(p)

products.update_many({"tags": "festive"}, {"$set": {"discount": 10}})
products.delete_many({"stock": 0})
products.create_index([("category", 1), ("price", 1)])
```

## Try the query language without a server

MongoDB filters are just dictionaries. This tiny matcher implements a few operators so you can practise the syntax right here:

```python
OPS = {"$lt": lambda a, b: a < b, "$gt": lambda a, b: a > b, "$in": lambda a, b: a in b, "$ne": lambda a, b: a != b}

def matches(doc, flt):
    for field, cond in flt.items():
        value = doc.get(field)
        if isinstance(cond, dict):
            if not all(OPS[op](value, arg) for op, arg in cond.items()):
                return False
        elif isinstance(value, list):
            if cond not in value:
                return False
        elif value != cond:
            return False
    return True

products = [
    {"name": "Redmi phone", "category": "phone", "price": 13999, "stock": 25, "tags": ["5g"]},
    {"name": "Pixel phone", "category": "phone", "price": 39999, "stock": 0},
    {"name": "Steel bottle", "category": "kitchen", "price": 499, "stock": 120, "tags": ["festive"]},
    {"name": "Diya set", "category": "decor", "price": 299, "stock": 40, "tags": ["festive"]},
]
print([p["name"] for p in products if matches(p, {"category": "phone", "price": {"$lt": 20000}, "stock": {"$gt": 0}})])
print([p["name"] for p in products if matches(p, {"tags": "festive"})])
print([p["name"] for p in products if matches(p, {"category": {"$in": ["decor", "kitchen"]}})])
```

Output:

```text
['Redmi phone']
['Steel bottle', 'Diya set']
['Steel bottle', 'Diya set']
```

Notice `{"tags": "festive"}` matches any document whose `tags` **array contains** "festive": MongoDB does this automatically for arrays.

## Updates with operators

::walk python | $set and $inc, simulated
doc = {"name": "Diya set", "price": 299, "stock": 40}
update = {"$set": {"discount": 10}, "$inc": {"stock": -3}}
for field, value in update["$set"].items():
    doc[field] = value
for field, step in update["$inc"].items():
    doc[field] = doc.get(field, 0) + step
print(doc)
::notes
1: A product document. // vars: doc={'name': 'Diya set', 'price': 299, 'stock': 40}
2: A MongoDB update: **$set** writes fields; **$inc** adds to a number. // deep: Without an operator, update_one would replace the whole document. Operators change only the named fields, and $inc is atomic, so two shoppers buying at the same moment can't lose an update. // vars: update={'$set': {'discount': 10}, '$inc': {'stock': -3}}
3: Apply $set: add or overwrite each field.
4: The discount field appears. // vars: doc={'name': 'Diya set', 'price': 299, 'stock': 40, 'discount': 10}
5: Apply $inc: add the step to the current value.
6: Three sold: stock goes from 40 to 37. // vars: doc={..., 'stock': 37, 'discount': 10}
7: Only the named fields changed. // out: {'name': 'Diya set', 'price': 299, 'stock': 37, 'discount': 10}
::end

## Common operators

| Operator | Meaning | Example |
|---|---|---|
| `$eq`, `$ne` | equal, not equal | `{"status": {"$ne": "cancelled"}}` |
| `$gt`, `$gte`, `$lt`, `$lte` | comparisons | `{"price": {"$gte": 500}}` |
| `$in`, `$nin` | in a list | `{"city": {"$in": ["Pune", "Mumbai"]}}` |
| `$and`, `$or` | combine conditions | `{"$or": [{"stock": 0}, {"price": {"$gt": 50000}}]}` |
| `$exists` | field present? | `{"discount": {"$exists": True}}` |
| `$set`, `$inc`, `$push`, `$unset` | update operators | `{"$push": {"tags": "sale"}}` |

::tip Create **indexes** on the fields you filter and sort by most (like category + price). Without one, MongoDB scans every document; with one, it jumps straight to the matches. Check with `.explain()`.

## Summary

- PyMongo: `insert_one/many`, `find`, `update_one/many`, `delete_one/many`.
- Filters are dictionaries with operators like `$gt`, `$in`, `$or`; arrays match by containment.
- Updates use operators (`$set`, `$inc`, `$push`) instead of replacing documents.
- Index the fields you query; keep the connection string in an environment variable.
