## mlops-fastapi-docker
Q: Why save a trained model with joblib?
- [x] So the API can load it without retraining
- [ ] To make it more accurate
- [ ] To encrypt it
> Train once, load many times.
Q: A /predict endpoint should validate...
- [x] The input fields and types before predicting
- [ ] Nothing
- [ ] Only the user's name
> Bad input should return a clear error, not a crash.
Q: Docker helps ML deployment by...
- [x] Packaging the code, model and libraries so they run the same everywhere
- [ ] Training models faster
- [ ] Replacing the model
> No more 'works on my machine'.

## mlops-cloud-ml
Q: Managed ML platforms (SageMaker, Vertex AI, Azure ML) mainly provide...
- [x] Hosted training, model registry and deployment endpoints
- [ ] Free unlimited GPUs
- [ ] Spreadsheets
> They handle infrastructure for you.
Q: A model registry tracks...
- [x] Model versions, their metrics and which one is in production
- [ ] Customer emails
- [ ] Website pages
> It makes rollbacks and audits easy.
Q: Experiment tracking (e.g. MLflow) records...
- [x] Parameters, metrics and artefacts of each training run
- [ ] Only the final model
- [ ] Network traffic
> So you can compare and reproduce runs.

## mlops-monitoring
Q: Data drift means...
- [x] Live input data has shifted from the training data
- [ ] The model file is missing
- [ ] The server is down
> Drift silently lowers accuracy.
Q: Why log predictions and later the true outcomes?
- [x] To measure real accuracy over time
- [ ] To slow the API
- [ ] Logs aren't useful
> You can only monitor quality with ground truth.
Q: When performance drops below a threshold, a good MLOps response is...
- [x] Alert, investigate and retrain on recent data if needed
- [ ] Ignore it
- [ ] Delete the logs
> Monitoring should trigger action.
