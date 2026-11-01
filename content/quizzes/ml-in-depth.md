## ml-linear-logistic-regression
Q: Logistic regression outputs...
- [x] A probability between 0 and 1
- [ ] Any number from minus to plus infinity
- [ ] Only the labels 0 or 1, never probabilities
> The sigmoid squeezes the score into a probability.
Q: Linear regression is trained by minimising...
- [x] The squared errors between predictions and true values
- [ ] The number of features
- [ ] The accuracy
> Least squares picks the line with the smallest total squared error.
Q: Raising a classifier's threshold from 0.5 to 0.8 usually...
- [x] Increases precision and lowers recall
- [ ] Increases both precision and recall
- [ ] Changes nothing
> Fewer, more confident positives: more precise, but more misses.

## ml-trees-forests-boosting
Q: A random forest improves on one decision tree by...
- [x] Averaging many trees trained on random samples and features
- [ ] Making one very deep tree
- [ ] Removing all the features
> Many varied trees reduce overfitting.
Q: Gradient boosting builds trees...
- [x] One after another, each fixing the previous errors
- [ ] All at once, independently
- [ ] Without using the data
> Boosting adds trees sequentially to correct mistakes.
Q: A very deep single tree with perfect training accuracy is likely...
- [x] Overfitting
- [ ] Underfitting
- [ ] Perfect for new data
> It memorised the training data; check on a test set.

## ml-knn-naive-bayes-svm
Q: Why must you scale features before k-NN?
- [x] It uses distances, so large-scale features would dominate
- [ ] k-NN can't read decimals
- [ ] Scaling makes k-NN a tree
> Distance-based models are sensitive to units.
Q: Naive Bayes is 'naive' because it assumes...
- [x] Features are independent given the class
- [ ] The data is always small
- [ ] All classes are equally common
> That simplification makes it fast and surprisingly effective for text.
Q: An SVM tries to find the boundary with...
- [x] The largest margin between the classes
- [ ] The most features
- [ ] The smallest number of support vectors only
> Maximising the margin helps it generalise.

## ml-clustering
Q: k-means needs you to choose...
- [x] The number of clusters k
- [ ] The true labels
- [ ] A learning rate
> You pick k, e.g. with the elbow or silhouette method.
Q: Which algorithm can find odd-shaped clusters and mark noise points?
- [x] DBSCAN
- [ ] k-means
- [ ] Linear regression
> DBSCAN groups dense regions and labels sparse points as noise.
Q: Clustering is an example of...
- [x] Unsupervised learning
- [ ] Supervised learning
- [ ] Reinforcement learning
> There are no labels; the algorithm finds groups itself.

## ml-pca
Q: PCA finds new axes that...
- [x] Capture the most variance in the data
- [ ] Always match the original columns
- [ ] Remove all variance
> Principal components are directions of greatest spread.
Q: 'Explained variance ratio' tells you...
- [x] How much information each component keeps
- [ ] The model's accuracy
- [ ] How many rows were removed
> Pick enough components to keep, say, 95% of the variance.
Q: A common use of PCA is...
- [x] Reducing many features to a few for speed or plotting
- [ ] Labelling data
- [ ] Increasing the number of features
> It compresses correlated features.

## ml-metrics-cv-tuning
Q: Why use cross-validation?
- [x] To get a more reliable estimate by testing on several splits
- [ ] To train faster
- [ ] To avoid needing a test set ever
> Averaging over folds reduces luck from one split.
Q: On a dataset with 1% fraud, a model predicting 'not fraud' always gets 99% accuracy. Better metrics are...
- [x] Precision, recall and F1 for the fraud class
- [ ] Accuracy only
- [ ] Training time
> Accuracy hides failure on rare classes.
Q: GridSearchCV is used for...
- [x] Trying combinations of hyper-parameters with cross-validation
- [ ] Cleaning missing values
- [ ] Plotting data
> It finds good settings like tree depth.

## ml-imbalance-explainability
Q: Setting class_weight='balanced' helps because...
- [x] Mistakes on the rare class cost more during training
- [ ] It deletes the common class
- [ ] It doubles the dataset
> The model pays more attention to the minority class.
Q: Permutation importance measures a feature by...
- [x] How much the score drops when that feature is shuffled
- [ ] Its column position
- [ ] How many missing values it has
> If shuffling hurts, the model relied on that feature.
Q: Why explain a loan model's decisions?
- [x] To build trust, find bias and meet rules
- [ ] Explanations make models faster
- [ ] It's never useful
> People affected deserve reasons, and teams need to catch unfair patterns.
