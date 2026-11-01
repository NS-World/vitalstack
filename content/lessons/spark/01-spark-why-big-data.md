---
title: "Big Data: Why One Computer Isn't Enough (Hadoop, MapReduce, Spark)"
slug: spark-why-big-data
subject: spark
lesson: 1
difficulty: intermediate
excerpt: When data outgrows one machine: distributed storage (HDFS), the MapReduce idea, and why Apache Spark became the standard for big data processing. With a runnable MapReduce word count.
---

pandas is brilliant, until your data is 2 TB and your laptop has 16 GB of RAM. Companies like Flipkart, Swiggy and Jio process billions of events a day. No single computer can hold or crunch that, so the work is split across many machines. That's **big data processing**.

::scene One kitchen vs a hundred
🍳 | One cook | One chef making 10,000 biryanis would take weeks. That's one computer with big data. | byte
👩‍🍳 | Many cooks | A hundred kitchens each make 100 biryanis at the same time. Done by dinner. | dev
🗺️ | Map | Each kitchen works on its own share: the "map" step. | chip
🧺 | Reduce | The results are collected and combined: the "reduce" step. | bugsy
⚡ | Spark | Spark keeps the ingredients in memory between steps, so it's up to many times faster than older disk-based systems. | dev
::end

## The three Vs (and why they matter)

- **Volume:** too much to store on one disk.
- **Velocity:** arrives too fast (clicks, sensors, payments).
- **Variety:** logs, JSON, images, tables, all mixed.

## Distributed storage: HDFS and the cloud

The **Hadoop Distributed File System (HDFS)** splits huge files into blocks (often 128 MB) and stores copies of each block on several machines, so a dead disk loses nothing. Today many teams use cloud object storage (Amazon S3, Azure Data Lake, Google Cloud Storage) in the same role.

## MapReduce: the core idea

Every big-data framework builds on this pattern. Here it is on one machine, counting words across "documents" as if each were on a different server:

```python
from collections import defaultdict

documents = ["chai and samosa", "samosa and jalebi", "chai chai chai"]

# MAP: each machine turns its document into (word, 1) pairs
mapped = [(word, 1) for doc in documents for word in doc.split()]

# SHUFFLE: pairs with the same key are sent to the same machine
groups = defaultdict(list)
for word, one in mapped:
    groups[word].append(one)

# REDUCE: each machine adds up the ones for its words
counts = {word: sum(ones) for word, ones in groups.items()}
print(sorted(counts.items(), key=lambda kv: -kv[1]))
```

Output:

```text
[('chai', 4), ('and', 2), ('samosa', 2), ('jalebi', 1)]
```

::walk python | Splitting the work across machines
docs = ["a b a", "b c", "a c c"]
machines = 2
shares = [docs[i::machines] for i in range(machines)]
partial = [sum(len(d.split()) for d in share) for share in shares]
print(shares, partial, sum(partial))
::notes
1: Three documents to process. // vars: docs=['a b a', 'b c', 'a c c']
2: Two worker machines. // vars: machines=2
3: Split the documents between the machines: every 2nd document to each. // deep: Real systems split by data blocks and try to run work on the machine that already stores the block ("data locality"), so less data moves over the network. // vars: shares=[['a b a', 'a c c'], ['b c']]
4: Each machine counts its own words in parallel. // vars: partial=[6, 2]
5: The partial results are combined: 8 words in total. With 1,000 machines the same idea handles terabytes. // out: [['a b a', 'a c c'], ['b c']] [6, 2] 8
::end

## Why Spark replaced classic Hadoop MapReduce

| | Hadoop MapReduce | Apache Spark |
|---|---|---|
| Between steps | Writes to disk | Keeps data **in memory** when it can |
| Speed | Slow for multi-step jobs | Much faster for iterative work like ML |
| APIs | Low-level Java | DataFrames and SQL in **Python (PySpark)**, Scala, Java, R |
| Extras | Batch only | SQL, streaming, machine learning (MLlib), graphs |

## How Spark runs a job

- A **driver** program (your Python script) plans the work.
- **Executors** on worker machines run tasks on **partitions** (chunks) of the data.
- Spark is **lazy**: transformations (filter, select, groupBy) only build a plan; nothing runs until an **action** (count, show, write) asks for a result. That lets Spark optimise the whole plan first.

::tip You don't need a cluster to learn Spark. `pip install pyspark` runs it on your laptop in "local mode", and free notebooks like Databricks Community Edition or Google Colab let you practise. Your code works the same on a 100-machine cluster later.

## When you DON'T need Spark

If data fits in memory (up to a few GB), pandas (or Polars, or DuckDB) is simpler and faster. Reach for Spark when data or processing truly outgrows one machine.

## Summary

- Big data = volume, velocity, variety beyond one machine.
- HDFS / cloud storage split files into replicated blocks.
- MapReduce: map in parallel → shuffle by key → reduce.
- Spark keeps data in memory, offers Python DataFrames, SQL, streaming and ML, and runs lazily.
