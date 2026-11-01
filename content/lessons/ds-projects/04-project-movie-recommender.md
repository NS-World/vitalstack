---
title: "Project 4: Movie Recommendation System"
slug: project-movie-recommender
subject: ds-projects
lesson: 4
difficulty: intermediate
excerpt: Build "Because you watched…" recommendations three ways: popularity, content-based similarity on genres, and collaborative filtering with user-item ratings and cosine similarity.
---

Netflix, Hotstar, Spotify, Amazon and Flipkart all run on recommendations. A good recommender keeps users watching and buying, so it's one of the most valuable models a company has, and a great portfolio project.

::scene Because you watched…
🍿 | The user | Riya watched three thrillers and a comedy this week. What next? | dev
🔥 | Popular | Option 1: show what everyone watches. Safe, but not personal. | chip
🏷️ | Similar content | Option 2: find movies with similar genres to what she liked. | byte
👥 | Similar people | Option 3: find users with similar taste and recommend what they loved. | bugsy
::end

## The data: a rating matrix

```python
import numpy as np
import pandas as pd

movies = ["Dhamaka Run", "Silent Signal", "Laugh Riot", "Family Feast", "Night Chase", "Cricket Dreams"]
genres = pd.DataFrame({"thriller": [1, 1, 0, 0, 1, 0], "comedy": [0, 0, 1, 1, 0, 0],
                       "drama": [0, 1, 0, 1, 0, 1], "sports": [0, 0, 0, 0, 0, 1]}, index=movies)
ratings = pd.DataFrame([[5, 4, 0, 0, 5, 0],
                        [4, 5, 0, 1, 4, 0],
                        [0, 0, 5, 4, 0, 3],
                        [1, 0, 4, 5, 0, 4],
                        [5, 0, 0, 0, 4, 1]],
                       index=["Riya", "Arjun", "Meera", "Kabir", "Sana"], columns=movies)   # 0 = not watched
print(ratings.loc["Sana"].to_dict())
```

Output:

```text
{'Dhamaka Run': 5, 'Silent Signal': 0, 'Laugh Riot': 0, 'Family Feast': 0, 'Night Chase': 4, 'Cricket Dreams': 1}
```

## 1. Popularity baseline

```python
watched = ratings.replace(0, np.nan)
popular = watched.mean().where(watched.count() >= 2).sort_values(ascending=False)
print(popular.round(2).dropna().to_dict())
```

Output:

```text
{'Silent Signal': 4.5, 'Laugh Riot': 4.5, 'Night Chase': 4.33, 'Dhamaka Run': 3.75, 'Family Feast': 3.33, 'Cricket Dreams': 2.67}
```

## 2. Content-based: similar genres

```python
from sklearn.metrics.pairwise import cosine_similarity

sim = pd.DataFrame(cosine_similarity(genres), index=movies, columns=movies)
print("like 'Silent Signal':", sim["Silent Signal"].drop("Silent Signal").sort_values(ascending=False).head(2).round(2).to_dict())
```

Output:

```text
like 'Silent Signal': {'Dhamaka Run': 0.71, 'Night Chase': 0.71}
```

## 3. Collaborative filtering: similar users

::walk python | How similar are two users' tastes?
sana = [5, 0, 0, 0, 4, 1]
riya = [5, 4, 0, 0, 5, 0]
dot = sum(a * b for a, b in zip(sana, riya))
norms = (sum(a * a for a in sana) ** 0.5) * (sum(b * b for b in riya) ** 0.5)
print(round(dot / norms, 2))
::notes
1: Sana's ratings for the six movies (0 = not watched). // vars: sana=[5, 0, 0, 0, 4, 1]
2: Riya's ratings. // vars: riya=[5, 4, 0, 0, 5, 0]
3: Dot product: 25 + 20 = 45. Movies both liked add a lot. // vars: dot=45
4: Divide by the vector lengths to remove "how much they rate overall". // deep: Treating unwatched as 0 is a simplification. Real systems subtract each user's average rating first (centred cosine) and use matrix factorisation (SVD, ALS) to fill in missing ratings. // vars: norms=≈ 6.48 × 8.12
5: 0.85: very similar taste. Riya loved "Silent Signal", which Sana hasn't seen: recommend it. // out: 0.85
::end

```python
user_sim = pd.DataFrame(cosine_similarity(ratings), index=ratings.index, columns=ratings.index)

def recommend(user, k=2):
    neighbours = user_sim[user].drop(user)
    scores = ratings.drop(user).T.dot(neighbours) / neighbours.sum()
    unseen = ratings.loc[user] == 0
    return scores[unseen].sort_values(ascending=False).head(k).round(2).to_dict()

print("for Sana:", recommend("Sana"))
print("for Meera:", recommend("Meera"))
```

Output:

```text
for Sana: {'Silent Signal': 3.86, 'Family Feast': 1.04}
for Meera: {'Dhamaka Run': 1.44, 'Night Chase': 0.51}
```

Sana gets the thriller her taste-twin Riya loved. (Meera's list shows a weakness of simple collaborative filtering with so little data: with only five users, weak neighbours still add noise. Real systems have millions of users and use better methods.)

## Evaluating recommenders

- **Offline:** hide some known ratings, recommend, measure **precision@k / recall@k** or ranking metrics like NDCG.
- **Online:** A/B test: do users click, watch or buy more? That's the real test.
- **Cold start:** new users or movies have no ratings: fall back to popularity and content-based methods.

::tip Mention trade-offs in interviews: diversity (not ten identical thrillers), freshness (new releases), and fairness (not only blockbusters). Recommenders shape what people see.

## Summary

- Popularity is a baseline; content-based uses item features; collaborative filtering uses similar users or items.
- Cosine similarity on rating vectors finds taste twins.
- Evaluate offline with precision@k, online with A/B tests; handle cold start.
