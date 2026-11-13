---
title: "Clustering with k-Means and DBSCAN"
slug: ml-clustering
subject: ml-in-depth
lesson: 4
difficulty: intermediate
excerpt: Group customers without labels using k-means, choose k with the elbow method and silhouette score, and find odd shapes and outliers with DBSCAN and hierarchical clustering.
---

So far every model had labels to learn from. **Clustering** has none: it finds natural groups by itself. Marketing teams use it for customer segments, banks for unusual transactions, and stores for grouping products.

::scene Finding the groups
🛒 | No labels | 10,000 shoppers, their spend and visit counts. Nobody told us the groups. | chip
📍 | Drop centres | k-means drops k centre points at random. | dev
🧲 | Pull | Each shopper joins the nearest centre; each centre moves to the middle of its group. Repeat. | byte
🏷️ | Name them | Three clear segments appear: bargain hunters, regulars and big spenders. | bugsy
::end

## k-means in action

```python
import numpy as np
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler

rng = np.random.default_rng(3)
spend = np.concatenate([rng.normal(800, 150, 60), rng.normal(3000, 400, 60), rng.normal(9000, 900, 30)])
visits = np.concatenate([rng.normal(2, 0.7, 60), rng.normal(6, 1.2, 60), rng.normal(10, 2, 30)])
X = np.column_stack([spend, visits])

Xs = StandardScaler().fit_transform(X)
km = KMeans(n_clusters=3, n_init=10, random_state=0).fit(Xs)
for c in range(3):
    group = X[km.labels_ == c]
    print(f"cluster {c}: {len(group):>3} shoppers, avg spend Rs {group[:, 0].mean():,.0f}, avg visits {group[:, 1].mean():.1f}")
```

Output:

```text
cluster 0:  60 shoppers, avg spend Rs 2,979, avg visits 6.0
cluster 1:  30 shoppers, avg spend Rs 9,142, avg visits 10.3
cluster 2:  60 shoppers, avg spend Rs 798, avg visits 2.1
```

The cluster numbers are arbitrary; **you** give them business names: big spenders, regulars, occasional shoppers.

## One k-means step, by hand

::walk python | Assign, then move the centre
points = [1, 2, 3, 10, 11, 12]
centres = [2, 5]
groups = {c: [p for p in points if min(centres, key=lambda k: abs(p - k)) == c] for c in centres}
new_centres = [sum(g) / len(g) for g in groups.values()]
print(groups, new_centres)
::notes
1: Six points on a line: two obvious groups. // vars: points=[1, 2, 3, 10, 11, 12]
2: Two starting centres, placed badly on purpose. // vars: centres=[2, 5]
3: **Assign:** each point joins its nearest centre. 1, 2 and 3 go to 2; everything else is closer to 5. // vars: groups={2: [1, 2, 3], 5: [10, 11, 12]}
4: **Update:** move each centre to the mean of its group. // deep: Repeating assign + update always lowers the total distance, so k-means converges, though maybe to a local best. That's why scikit-learn runs it several times (n_init) from different starts. // vars: new_centres=[2.0, 11.0]
5: The second centre jumped to 11: the middle of the right-hand group. One more round changes nothing, so it has converged. // out: {2: [1, 2, 3], 5: [10, 11, 12]} [2.0, 11.0]
::end

## Choosing k: elbow and silhouette

```python
from sklearn.metrics import silhouette_score

for k in range(2, 7):
    m = KMeans(n_clusters=k, n_init=10, random_state=0).fit(Xs)
    print(k, "inertia", round(m.inertia_, 1), "silhouette", round(silhouette_score(Xs, m.labels_), 3))
```

Output:

```text
2 inertia 81.0 silhouette 0.702
3 inertia 24.9 silhouette 0.723
4 inertia 17.1 silhouette 0.68
5 inertia 12.9 silhouette 0.584
6 inertia 10.5 silhouette 0.581
```

**Inertia** (total squared distance to centres) always falls as k grows; look for the **elbow** where it stops falling fast. The **silhouette score** (−1 to 1, higher is better) peaks at the best separation. Both point to k = 3.

## DBSCAN: shapes and outliers

k-means assumes round, similar-sized clusters. **DBSCAN** groups points that are densely packed and labels isolated points as **noise** (−1): handy for odd shapes and anomaly detection.

```python
from sklearn.cluster import DBSCAN
from sklearn.datasets import make_moons

moons, _ = make_moons(n_samples=200, noise=0.06, random_state=0)
moons = np.vstack([moons, [[2.5, 2.5], [-1.5, 2.0]]])          # two odd points
labels = DBSCAN(eps=0.2, min_samples=5).fit_predict(moons)
print("clusters:", len(set(labels) - {-1}), "| noise points:", int((labels == -1).sum()))
```

Output:

```text
clusters: 2 | noise points: 2
```

## Hierarchical clustering

Agglomerative clustering starts with every point alone and keeps merging the two closest groups, producing a tree (a **dendrogram**) you can cut at any level. It's great for small datasets where you want to see how groups nest, like product categories.

| Method | Needs k? | Cluster shapes | Finds outliers? | Scale |
|---|---|---|---|---|
| k-means | Yes | Round, similar size | No | Very large data |
| DBSCAN | No (needs eps) | Any shape | Yes | Medium |
| Hierarchical | No (cut the tree) | Depends on linkage | No | Small-medium |

## See it as a chart: the clusters and the elbow

Left: the shoppers coloured by their k-means cluster, with each centre marked. Right: the elbow plot, inertia (how spread out the clusters are) for k = 1 to 8. The bend at k = 3 says three groups explain most of the structure.

```python
import matplotlib.pyplot as plt
import numpy as np
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler

rng = np.random.default_rng(3)
spend = np.concatenate([rng.normal(800, 150, 60), rng.normal(3000, 400, 60), rng.normal(9000, 900, 30)])
visits = np.concatenate([rng.normal(2, 0.7, 60), rng.normal(6, 1.2, 60), rng.normal(10, 2, 30)])
X = np.column_stack([spend, visits])
scaler = StandardScaler().fit(X)
Xs = scaler.transform(X)
km = KMeans(n_clusters=3, n_init=10, random_state=0).fit(Xs)
centres = scaler.inverse_transform(km.cluster_centers_)
inertia = [KMeans(n_clusters=k, n_init=10, random_state=0).fit(Xs).inertia_ for k in range(1, 9)]

fig, (left, right) = plt.subplots(1, 2, figsize=(10, 3.8))
colors = np.array(["#2563eb", "#0a7d4f", "#f59e0b"])
left.scatter(spend, visits, c=colors[km.labels_], s=16)
left.scatter(centres[:, 0], centres[:, 1], marker="X", s=200, color="#1b1f27", label="cluster centres")
left.set_xlabel("monthly spend (₹)")
left.set_ylabel("visits per month")
left.legend(frameon=False)
right.plot(range(1, 9), inertia, "o-", color="#0a7d4f")
right.annotate("elbow at k = 3", (3, inertia[2]), (4.5, inertia[0] * 0.6), arrowprops={"arrowstyle": "->"})
right.set_xlabel("k (number of clusters)")
right.set_ylabel("inertia")
fig.tight_layout()
plt.show()
```

::figure mlid-kmeans-elbow.svg | Left: shoppers in three coloured clusters with black X centres; right: an elbow curve that drops steeply until k equals 3 and then flattens

Always look at the clusters, not only the numbers. A chart shows at once whether the groups make business sense (budget, regular, premium shoppers here).

## Summary

- Clustering finds groups without labels; scale features first.
- k-means: assign to nearest centre, move centres, repeat; choose k with elbow + silhouette.
- DBSCAN: density-based, any shape, marks noise.
- Hierarchical: a tree of merges you can cut at any level.
