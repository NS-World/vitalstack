---
title: "Serving a Model with FastAPI and Docker"
slug: mlops-fastapi-docker
subject: mlops
lesson: 1
difficulty: intermediate
excerpt: Take a trained scikit-learn model from a notebook to a real API: save it with joblib, serve predictions with FastAPI and input validation, and package it in a Docker container.
---

A model in a Jupyter notebook helps nobody. To be useful, an app, a website or another team's service must be able to send data and get a prediction back. That's **model serving**, the first step of **MLOps** (machine learning operations).

::scene From notebook to production
📓 | Notebook | The churn model works great… on your laptop, in cell 47. | bugsy
💾 | Save | Save the whole pipeline (preprocessing + model) to one file. | dev
🌐 | API | FastAPI wraps it: send JSON, get a prediction back in milliseconds. | chip
📦 | Container | Docker packs the code, model and exact library versions into one box that runs anywhere. | byte
::end

## Step 1: save the whole pipeline

Save preprocessing and model **together**, so production applies exactly the same transformations as training:

```python
import joblib
import numpy as np
from sklearn.pipeline import make_pipeline
from sklearn.preprocessing import StandardScaler
from sklearn.linear_model import LogisticRegression

X = np.array([[2, 3], [3, 4], [1, 2], [24, 0], [36, 1], [18, 0], [5, 3], [30, 0]])
y = np.array([1, 1, 1, 0, 0, 0, 1, 0])
pipe = make_pipeline(StandardScaler(), LogisticRegression()).fit(X, y)
joblib.dump(pipe, "churn_model.joblib")
```

## Step 2: a prediction function you can test

Keep the logic in a plain function (easy to unit-test), and validate inputs before they reach the model:

```python
import numpy as np
from sklearn.pipeline import make_pipeline
from sklearn.preprocessing import StandardScaler
from sklearn.linear_model import LogisticRegression

X = np.array([[2, 3], [3, 4], [1, 2], [24, 0], [36, 1], [18, 0], [5, 3], [30, 0]])
y = np.array([1, 1, 1, 0, 0, 0, 1, 0])
model = make_pipeline(StandardScaler(), LogisticRegression()).fit(X, y)

def predict(payload, model=model):
    months, complaints = payload.get("months"), payload.get("complaints")
    if not isinstance(months, int) or not 0 <= months <= 600:
        return {"error": "months must be a whole number from 0 to 600"}, 422
    if not isinstance(complaints, int) or not 0 <= complaints <= 100:
        return {"error": "complaints must be a whole number from 0 to 100"}, 422
    p = float(model.predict_proba([[months, complaints]])[0, 1])
    return {"churn_probability": round(p, 3), "will_churn": p >= 0.5}, 200

print(predict({"months": 3, "complaints": 4}))
print(predict({"months": 30, "complaints": 0}))
print(predict({"months": "ten", "complaints": 0}))
```

Output:

```text
({'churn_probability': 0.923, 'will_churn': True}, 200)
({'churn_probability': 0.098, 'will_churn': False}, 200)
({'error': 'months must be a whole number from 0 to 600'}, 422)
```

## Step 3: the FastAPI app

```python
# app.py
import joblib
from fastapi import FastAPI
from pydantic import BaseModel, Field

app = FastAPI(title="Churn API")
model = joblib.load("churn_model.joblib")           # load ONCE at start-up, not per request

class Customer(BaseModel):
    months: int = Field(ge=0, le=600)
    complaints: int = Field(ge=0, le=100)

@app.get("/health")
def health():
    return {"status": "ok"}

@app.post("/predict")
def predict(c: Customer):
    p = float(model.predict_proba([[c.months, c.complaints]])[0, 1])
    return {"churn_probability": round(p, 3), "will_churn": p >= 0.5}
```

Run it with `uvicorn app:app --reload` and open `http://localhost:8000/docs`: FastAPI generates interactive documentation automatically. Pydantic's `Field(ge=0, le=600)` rejects bad input with a clear 422 error before your model ever sees it.

::walk python | What happens to one request
request = {"months": 3, "complaints": 4}
valid = all(isinstance(v, int) and v >= 0 for v in request.values())
features = [[request["months"], request["complaints"]]]
probability = 0.923
response = {"churn_probability": probability, "will_churn": probability >= 0.5}
print(valid, response)
::notes
1: A client (an app, a CRM) POSTs JSON to /predict. // vars: request={'months': 3, 'complaints': 4}
2: **Validate** first: types and ranges. Bad input never reaches the model. // deep: In FastAPI, the Pydantic model does this automatically and returns a 422 error listing exactly which field is wrong. // vars: valid=True
3: Turn JSON into the 2-D shape the model expects: one row, two columns. // vars: features=[[3, 4]]
4: The model (loaded once at start-up) returns a probability. // vars: probability=0.923
5: Build a small, stable JSON response. Other teams build on this "contract", so change it carefully. // vars: response={'churn_probability': 0.923, 'will_churn': True}
6: The whole round trip usually takes a few milliseconds. // out: True {'churn_probability': 0.923, 'will_churn': True}
::end

## Step 4: Docker

```dockerfile
FROM python:3.12-slim
WORKDIR /app
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt
COPY app.py churn_model.joblib ./
EXPOSE 8000
CMD ["uvicorn", "app:app", "--host", "0.0.0.0", "--port", "8000"]
```

```bash
docker build -t churn-api .
docker run -p 8000:8000 churn-api
```

Pin library versions in `requirements.txt` (for example `scikit-learn==` the version you trained with): a model saved with one version may not load correctly in another.

::tip Never deploy a model without a `/health` endpoint, input validation, logging of each prediction (inputs, output, model version, time) and a version number. You'll need all four the first time something goes wrong.

## Summary

- Save preprocessing + model together (`joblib`).
- Wrap prediction in a testable function; validate inputs.
- FastAPI + Pydantic give a fast API with automatic docs and validation.
- Docker packages code, model and pinned versions so it runs the same everywhere.
