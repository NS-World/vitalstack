---
title: "Cloud ML: AWS SageMaker and Azure Machine Learning"
slug: mlops-cloud-ml
subject: mlops
lesson: 2
difficulty: intermediate
excerpt: How teams train and deploy models in the cloud: AWS SageMaker and Azure ML compared, compute choices (CPU vs GPU), experiment tracking with MLflow, model registries and managed endpoints, plus cost control.
---

Your laptop can't train on 500 GB of data, and you don't want to keep a server running at 3 a.m. by hand. Cloud ML platforms give you on-demand compute, managed training jobs, model registries and one-click endpoints. The two big ones in Indian companies are **AWS SageMaker** and **Azure Machine Learning** (Google's **Vertex AI** is the third).

::scene A model's trip through the cloud
☁️ | Upload | Training data goes to cloud storage: S3 on AWS, Blob Storage on Azure. | dev
🏋️ | Train | A training job spins up a GPU machine, trains, saves the model, then shuts down. You pay only for those minutes. | chip
📒 | Track | Every run's parameters and scores are logged, so you can compare and reproduce them. | byte
🚀 | Deploy | The best model goes to a managed endpoint that scales up on busy days and down at night. | bugsy
::end

## The standard cloud ML workflow

1. **Data** in cloud storage (S3, Azure Blob/Data Lake).
2. **Experiment** in managed notebooks (SageMaker Studio, Azure ML notebooks).
3. **Train** as a job on chosen compute; log metrics.
4. **Register** the best model with a version in a **model registry**.
5. **Deploy** to a real-time **endpoint** (or run **batch** predictions on a schedule).
6. **Monitor** and retrain (next lesson).

## SageMaker vs Azure ML

| Need | AWS SageMaker | Azure Machine Learning |
|---|---|---|
| Notebooks | SageMaker Studio | Azure ML Studio notebooks |
| Training jobs | Estimators (scikit-learn, PyTorch, XGBoost containers) | Command jobs on compute clusters |
| Tracking | SageMaker Experiments, or MLflow | MLflow built in |
| Model registry | SageMaker Model Registry | Azure ML model registry |
| Real-time serving | SageMaker endpoints | Managed online endpoints |
| No-code / AutoML | SageMaker Canvas, Autopilot | Designer, Automated ML |
| CLI | AWS CLI, `sagemaker` Python SDK | Azure CLI `ml` extension, `azure-ai-ml` SDK |

A SageMaker training job, in outline:

```python
import sagemaker
from sagemaker.sklearn.estimator import SKLearn

estimator = SKLearn(entry_point="train.py", role=sagemaker.get_execution_role(),
                    instance_type="ml.m5.large", framework_version="1.2-1")
estimator.fit({"train": "s3://my-bucket/churn/train/"})
predictor = estimator.deploy(initial_instance_count=1, instance_type="ml.t2.medium")
```

## Experiment tracking with MLflow (works everywhere)

MLflow is the open-source standard for logging runs; Azure ML and Databricks support it natively, and SageMaker offers managed MLflow:

```python
import mlflow

with mlflow.start_run(run_name="rf-depth-8"):
    mlflow.log_params({"model": "random_forest", "max_depth": 8})
    mlflow.log_metric("val_auc", 0.912)
    mlflow.sklearn.log_model(model, "model")
```

The idea, without any library:

```python
runs = [
    {"run": "logreg", "params": {"C": 1.0}, "val_auc": 0.871},
    {"run": "rf-depth-8", "params": {"max_depth": 8}, "val_auc": 0.912},
    {"run": "gbm-lr-0.05", "params": {"learning_rate": 0.05}, "val_auc": 0.918},
]
best = max(runs, key=lambda r: r["val_auc"])
print("register:", best["run"], best["params"], "AUC", best["val_auc"])
```

Output:

```text
register: gbm-lr-0.05 {'learning_rate': 0.05} AUC 0.918
```

## Choosing compute (and not burning money)

::walk python | Training cost: GPU vs CPU
cpu_hours, cpu_rate = 10, 0.20
gpu_hours, gpu_rate = 0.8, 1.50
cpu_cost = cpu_hours * cpu_rate
gpu_cost = gpu_hours * gpu_rate
print(f"CPU ${cpu_cost:.2f} vs GPU ${gpu_cost:.2f}")
::notes
1: A tabular model takes 10 hours on a CPU machine at about $0.20 an hour (example price). // vars: cpu_hours=10, cpu_rate=0.2
2: A deep-learning job finishes in 0.8 hours on a GPU machine at about $1.50 an hour (example price). // deep: GPUs cost more per hour but can be far faster for neural networks. For scikit-learn or XGBoost on tables, a CPU is usually enough. Check your cloud's current pricing for real numbers. // vars: gpu_hours=0.8, gpu_rate=1.5
3: CPU total. // vars: cpu_cost=2.0
4: GPU total: more expensive per hour, but here it's cheaper overall because it finishes so fast. // vars: gpu_cost=1.2
5: Compare total cost, not hourly price. And always shut machines down after jobs. // out: CPU $2.00 vs GPU $1.20
::end

Cost-control checklist:

- [ ] Use **training jobs** that stop automatically, not notebooks left running overnight.
- [ ] Use **spot / low-priority** instances for training (much cheaper, may be interrupted).
- [ ] Set **budgets and alerts** in the billing console.
- [ ] Prefer **serverless** or **batch** inference for low or irregular traffic.
- [ ] Delete idle endpoints: they bill every hour they exist.

::tip Both AWS and Azure have free tiers and student credits. Practise on tiny instances, set a budget alert on day one, and you can learn the platforms for very little money.

## Summary

- Cloud ML = storage → notebooks → training jobs → registry → endpoints → monitoring.
- SageMaker and Azure ML offer the same building blocks with different names.
- Track every run (MLflow) and register the best model with a version.
- Compare total cost, use auto-stopping jobs and spot instances, delete idle endpoints.
