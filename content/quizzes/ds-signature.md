## signature-upi-fraud-radar
Q: Only 42 of 3,000 payments are fraud. A model that always says 'not fraud' is...
- [x] 98.6% accurate but useless
- [ ] A strong fraud model
- [ ] Impossible to build
> Accuracy hides rare events; judge by fraud caught and cost.
Q: Which signal raised fraud risk the most in this dataset?
- [x] A payment from a new device
- [ ] Paying a grocery store
- [ ] A savings account
> 15.4% of new-device payments were fraud, against 0.6% otherwise.
Q: How was the alert threshold chosen?
- [x] By the lowest total cost of missed frauds plus alerts
- [ ] Always 0.5
- [ ] By the highest accuracy
> Business cost, in rupees, decides the threshold.

## signature-delivery-eta
Q: Why were orders taking 250 to 350 minutes removed?
- [x] They are almost certainly cancelled or unclosed orders, not real deliveries
- [ ] They were too cheap
- [ ] They came from one city
> A clear business rule (over 2 hours) removes impossible rows.
Q: In the ETA model, rain added about...
- [x] 10 minutes
- [ ] 1 minute
- [ ] 1 hour
> Coefficients of a linear model read as minutes.
Q: Why add a buffer to the predicted time?
- [x] A middle estimate is late about half the time
- [ ] To make the app slower
- [ ] Because the model is always wrong by 30 minutes
> A buffer from past errors keeps most promises.

## signature-clean-air-alert
Q: How were missing AQI readings filled?
- [x] By interpolating between neighbouring readings in the same city
- [ ] With the national average
- [ ] With zero
> Air quality changes slowly, city by city.
Q: Persistence forecasting means...
- [x] Predicting the next reading will be like the current one
- [ ] Training a deep network
- [ ] Ignoring the data
> It is the baseline any forecast model must beat.
Q: Why lower the alert line from 200 to 160?
- [x] Missing a bad-air day is worse than an extra warning
- [ ] To show fewer alerts
- [ ] Because 160 is the official Severe level
> It cut missed bad readings from 18 to 5.

## signature-fair-pay-audit
Q: Why fill missing salaries by department and experience?
- [x] Salary depends on them; the overall average would mislead
- [ ] It is faster
- [ ] To raise everyone's pay
> A senior engineer is not paid like a fresher.
Q: After controlling for department and experience, remote staff were paid...
- [x] About the same as office staff
- [ ] 40% less
- [ ] Twice as much
> The raw gap came from who works remotely.
Q: Which factor most increased attrition?
- [x] Regular overtime
- [ ] Living in Ahmedabad
- [ ] Working in HR
> Overtime staff left about three times as often.
