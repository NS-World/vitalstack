## machine-learning-introduction
Q: In supervised learning, what is the label?
- [ ] The input columns
- [x] The answer the model should predict
- [ ] The model's name
> Features are inputs (X); the label is y.
Q: Predicting a house price is…
- [x] Regression
- [ ] Classification
- [ ] Clustering
> The answer is a number.
Q: Why keep a separate test set?
- [ ] To train faster
- [x] To check the model honestly on data it has never seen
- [ ] To store backups
> It's the surprise exam.

## ml-linear-regression
Q: What does a linear regression slope tell you?
- [x] How much y changes for one unit of x
- [ ] The prediction at x = 0
- [ ] The accuracy
> The intercept is the value at x = 0.
Q: Why `reshape(-1, 1)` for a single feature?
- [ ] To sort it
- [x] scikit-learn expects X as a table (rows × features)
- [ ] To remove duplicates
> One column, many rows.
Q: Why are predictions far outside the training data risky?
- [x] The line keeps going, but real life may not
- [ ] The model crashes
- [ ] They're always negative
> This is extrapolation.

## ml-train-test-split
Q: Train score 0.99, test score 0.55. What is this?
- [x] Overfitting
- [ ] Underfitting
- [ ] A perfect model
> The model memorised the training rows.
Q: What does MAE measure?
- [ ] Percentage explained
- [x] The average size of the errors, in the target's units
- [ ] Training time
> Easy to explain: "off by about X on average".
Q: What does 5-fold cross-validation do?
- [ ] Trains on 5 different datasets
- [x] Trains 5 times, testing on a different fifth each time
- [ ] Uses 5 models at once
> It gives a more stable score.

## ml-classification
Q: Logistic regression is used for…
- [ ] Predicting numbers
- [x] Classification (categories)
- [ ] Clustering
> It predicts class probabilities.
Q: Why isn't accuracy always enough?
- [x] With rare classes, always guessing the common class scores high
- [ ] Accuracy is too slow
- [ ] It ignores the training set
> Read the confusion matrix too.
Q: Why scale features for k-NN?
- [ ] To speed up predict
- [x] k-NN uses distances, so big-number columns would dominate
- [ ] scikit-learn requires it for all models
> StandardScaler puts features on a similar scale.

## ml-project-iris
Q: What does `max_depth=3` do for a decision tree?
- [x] Limits it to 3 levels of questions, reducing overfitting
- [ ] Uses 3 features
- [ ] Trains 3 trees
> Shallow trees generalise better.
Q: What do `feature_importances_` show?
- [ ] The accuracy per class
- [x] How much each feature contributed to the decisions
- [ ] The training time
> Use them to explain a model.
Q: What is the right order for a project?
- [x] Explore → split → train → evaluate → explain → use
- [ ] Train → explore → split → use
- [ ] Use → train → explore
> Always look at the data first.
