---
title: "NoSQL and MongoDB: Documents, Collections and JSON"
slug: mongodb-documents
subject: mongodb
lesson: 1
difficulty: intermediate
excerpt: What NoSQL means, how MongoDB stores JSON-like documents in collections, when to choose it over SQL, ObjectIds, embedding vs referencing, and designing a schema.
---

SQL databases store data in tables with fixed columns. But some data doesn't fit neat rows: a product with 3 attributes and another with 30, a user profile with a list of addresses, app events with changing fields. **MongoDB**, the most popular NoSQL database, stores such data as flexible **documents**.

::scene From tables to documents
🗃️ | Tables | In SQL, an order is spread across orders, order_items and addresses tables, joined at query time. | dev
📄 | One document | In MongoDB, the whole order (items, address and all) is one JSON-like document. | chip
🧩 | Flexible | A phone has "battery" and "camera"; a T-shirt has "size" and "fabric". Same collection, different fields. | byte
⚖️ | Trade-off | Fast reads of whole objects, less rigid structure. Use the right tool for the data. | bugsy
::end

## Documents and collections

| SQL | MongoDB |
|---|---|
| Database | Database |
| Table | **Collection** |
| Row | **Document** (stored as BSON, a binary form of JSON) |
| Column | Field |
| Primary key | `_id` (an **ObjectId** by default) |
| JOIN | Embedding, or `$lookup` |

A document looks like a Python dictionary:

```python
order = {
    "_id": "6650f1a2c3d4e5f6a7b8c9d0",
    "customer": {"name": "Asha", "city": "Pune"},
    "items": [
        {"sku": "TEA-01", "name": "Masala chai", "qty": 2, "price": 120},
        {"sku": "MUG-07", "name": "Steel mug", "qty": 1, "price": 250},
    ],
    "status": "delivered",
    "tags": ["gift", "festival"],
}
total = sum(i["qty"] * i["price"] for i in order["items"])
print(order["customer"]["city"], "| items:", len(order["items"]), "| total: Rs", total)
```

Output:

```text
Pune | items: 2 | total: Rs 490
```

Nested objects and lists live **inside** the document: reading an order needs no joins.

## ObjectId: a sortable unique ID

MongoDB's default `_id` is a 12-byte ObjectId. Its first 4 bytes are the creation time in seconds, so IDs roughly sort by insertion time:

::walk python | Reading the time inside an ObjectId
oid = "6650f1a2c3d4e5f6a7b8c9d0"
seconds_hex = oid[:8]
seconds = int(seconds_hex, 16)
import datetime
print(seconds, datetime.datetime.fromtimestamp(seconds, datetime.timezone.utc).year)
::notes
1: An ObjectId as a 24-character hex string (12 bytes). // vars: oid=6650f1a2c3d4e5f6a7b8c9d0
2: The first 8 hex characters (4 bytes) are a timestamp. // vars: seconds_hex=6650f1a2
3: Convert hex to an integer: seconds since 1 January 1970. // vars: seconds=1716580770
4: datetime turns seconds into a date. // deep: The remaining 8 bytes are a random value plus a counter, which keeps IDs unique even when many servers create documents in the same second, with no central coordinator.
5: This ID was created in 2024. // out: 1716580770 2024
::end

## Embed or reference?

The key schema question in MongoDB:

- **Embed** when data is read together and belongs to one parent: an order's items, a post's tags.
- **Reference** (store an ID) when data is shared or grows without limit: a customer used by thousands of orders, millions of comments on a viral post.

```python
customers = {"C1": {"name": "Asha", "plan": "gold"}}
orders = [{"_id": 1, "customer_id": "C1", "total": 490}, {"_id": 2, "customer_id": "C1", "total": 1200}]

for o in orders:                       # what $lookup does on the server
    o["customer"] = customers[o["customer_id"]]
print([(o["_id"], o["customer"]["plan"]) for o in orders])
```

Output:

```text
[(1, 'gold'), (2, 'gold')]
```

A document must stay under MongoDB's size limit (16 MB), so never embed lists that can grow forever.

## When to choose MongoDB vs SQL

| Choose MongoDB when… | Choose SQL (PostgreSQL, MySQL) when… |
|---|---|
| Data is naturally nested or varies per item | Data is naturally tabular and related |
| You read whole objects at once | You need complex joins and reporting |
| The schema changes often (fast-moving apps) | Strong consistency across many tables matters (banking) |
| Logs, events, catalogues, content | Finance, inventory, transactions |

::tip "NoSQL" doesn't mean "no structure". Good MongoDB apps still design their schema carefully, around how the data is **read**.

## Summary

- MongoDB stores JSON-like **documents** in **collections**; `_id` is usually an ObjectId.
- Nested objects and arrays live inside documents, so reads need no joins.
- Embed what's read together; reference what's shared or unbounded.
- Pick MongoDB for flexible, nested data; SQL for relational, transactional data.
