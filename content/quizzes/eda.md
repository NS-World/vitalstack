## eda-checklist
Q: What should you do first with a new dataset?
- [x] Check its shape, column types and a few sample rows
- [ ] Train a model on it straight away
- [ ] Delete columns you don't recognise
> Knowing what you have comes before any cleaning or modelling.
Q: Which pandas method gives count, mean, std and quartiles for numeric columns?
- [x] df.describe()
- [ ] df.head()
- [ ] df.columns
> describe() summarises each numeric column in one table.
Q: A strong correlation between two columns proves that...
- [x] Nothing about cause: they only move together
- [ ] One column causes the other
- [ ] The data is wrong
> Correlation is not causation; check with experiments or domain knowledge.

## eda-missing-outliers
Q: Which method counts missing values per column?
- [x] df.isna().sum()
- [ ] df.count_missing()
- [ ] df.dropna().sum()
> isna() marks missing cells; sum() counts them per column.
Q: The IQR rule flags a value as an outlier when it is...
- [x] More than 1.5 × IQR below Q1 or above Q3
- [ ] Larger than the mean
- [ ] Exactly equal to the median
> Q1 − 1.5·IQR and Q3 + 1.5·IQR are the usual fences.
Q: A salary column has a few huge but real values. A good first step is...
- [x] Investigate them before deciding to keep, cap or transform
- [ ] Always delete them
- [ ] Replace them with zero
> Real extreme values can be important; decide with domain knowledge.

## eda-encoding-scaling
Q: Which encoding suits a column like city with no natural order?
- [x] One-hot encoding
- [ ] Label numbers 1, 2, 3 by alphabet
- [ ] Leave it as text for the model
> One-hot avoids inventing a fake order between categories.
Q: Why fit a scaler on the training set only?
- [x] To avoid leaking information from the test set
- [ ] Because scalers can't read test data
- [ ] To make training slower
> Fitting on all data lets test statistics leak into training.
Q: StandardScaler transforms each feature to...
- [x] Mean 0 and standard deviation 1
- [ ] Values between 0 and 100
- [ ] Only positive numbers
> It subtracts the mean and divides by the standard deviation.

## eda-feature-engineering
Q: Which is a good engineered feature for predicting late deliveries?
- [x] Distance between the warehouse and the customer
- [ ] The row number in the CSV
- [ ] The customer's ID number
> Features should carry real signal about the outcome.
Q: From an order timestamp, a useful feature is...
- [x] Day of week or hour of day
- [ ] The timestamp as one huge number
- [ ] The timestamp's character count
> Calendar parts capture patterns like weekend demand.
Q: Using a column that is only known after the outcome is called...
- [x] Target leakage
- [ ] Feature scaling
- [ ] Regularisation
> Leaky features make models look great in testing and fail in real use.
