---
title: Machine Learning Cheat Sheet
slug: machine-learning-cheat-sheet
parent: cheat-sheets
template: page-cheat-sheet.php
icon: 🤖
order: 120
excerpt: Machine learning on one page - which algorithm to use, the scikit-learn workflow, preprocessing, metrics for regression and classification, cross-validation, tuning and common mistakes. Printable.
---
## The scikit-learn workflow

```python
from sklearn.model_selection import train_test_split
from sklearn.pipeline import make_pipeline
from sklearn.preprocessing import StandardScaler
from sklearn.linear_model import LogisticRegression

X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42, stratify=y)
model = make_pipeline(StandardScaler(), LogisticRegression(max_iter=1000))
model.fit(X_train, y_train)            # learn from training data only
print(model.score(X_test, y_test))     # judge on unseen data
```

## Which algorithm?

| Problem | Start with | Then try |
|---|---|---|
| Predict a number | Linear regression | Random forest, gradient boosting |
| Yes/no or categories | Logistic regression | Random forest, gradient boosting, SVM |
| Text classification | TF-IDF + logistic regression | Transformers |
| Find groups (no labels) | k-means | DBSCAN, hierarchical |
| Too many features | PCA | Feature selection |
| Images, audio | Pre-trained CNN / transformer | Fine-tuning |

## Preprocessing

| Task | Tool |
|---|---|
| Fill missing numbers | `SimpleImputer(strategy="median")` |
| Categories → numbers | `OneHotEncoder(handle_unknown="ignore")` |
| Scale features | `StandardScaler()` (needed for k-NN, SVM, linear models) |
| Mixed columns | `ColumnTransformer([...])` |

## Metrics

| Regression | Classification |
|---|---|
| MAE: average error in real units | Accuracy: share correct (misleading if imbalanced) |
| RMSE: punishes big errors | Precision: of predicted positives, how many are right |
| R²: share of variance explained | Recall: of real positives, how many we caught |
| MAPE: error as a percentage | F1: balance of precision and recall; ROC AUC: ranking quality |

## Validation and tuning

```python
from sklearn.model_selection import cross_val_score, GridSearchCV
scores = cross_val_score(model, X, y, cv=5, scoring="f1")
grid = GridSearchCV(RandomForestClassifier(), {"max_depth": [3, 6, None], "n_estimators": [100, 300]}, cv=5)
grid.fit(X_train, y_train); print(grid.best_params_)
```

## Common mistakes

- Fitting scalers or encoders on all data (leakage): fit on training data only, or use a pipeline.
- Using accuracy on imbalanced data: check recall and precision of the rare class.
- Random splits for time series: train on the past, test on the future.
- Features only known after the outcome (target leakage).
- No baseline: always compare with a simple model or "predict the average".

Learn every topic step by step in [Machine Learning](/tutorial-category/machine-learning/) and the premium [Machine Learning in Depth](/tutorial-category/ml-in-depth/) module.
