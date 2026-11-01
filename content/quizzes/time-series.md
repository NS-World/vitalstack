## ts-trend-seasonality
Q: Seasonality means...
- [x] A pattern that repeats at a fixed interval, like every December
- [ ] A random spike once
- [ ] A steady long-term rise
> Trend is the long-term direction; seasonality repeats.
Q: A rolling mean helps you...
- [x] See the trend by smoothing short-term noise
- [ ] Predict exact future values
- [ ] Remove all data
> Averaging a moving window smooths the line.
Q: Why must time-series train/test splits keep time order?
- [x] So the model never learns from the future
- [ ] Random splits are always better
- [ ] Order doesn't matter
> Test on later dates, train on earlier ones.

## ts-stationarity-differencing
Q: A stationary series has...
- [x] A constant mean and variance over time
- [ ] A strong upward trend
- [ ] Only seasonal spikes
> Many models like ARIMA assume stationarity.
Q: Differencing a series means...
- [x] Taking the change from one period to the next
- [ ] Sorting the values
- [ ] Removing every second value
> It removes trend, often making the series stationary.
Q: A low p-value in the ADF test suggests the series is...
- [x] Stationary
- [ ] Definitely trending
- [ ] Missing data
> ADF's null hypothesis is a unit root (non-stationary).

## ts-arima-forecasting
Q: In ARIMA(p, d, q), d is...
- [x] The number of times the series is differenced
- [ ] The number of days to forecast
- [ ] The decimal places
> p is AR terms, d differencing, q MA terms.
Q: A good simple baseline for monthly sales is...
- [x] Same month last year (seasonal naive)
- [ ] Always zero
- [ ] A random number
> Beat a baseline before trusting a complex model.
Q: A forecast should come with...
- [x] A range (interval), not just one number
- [ ] No uncertainty
- [ ] Only a chart title
> The future is uncertain; intervals show how much.
