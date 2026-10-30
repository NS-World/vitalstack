## statistics-mean-median-spread
Q: Salaries are 22, 25, 25, 28, 30, 31 and 150 (₹ thousand). Which number best describes a typical salary?
- [ ] The mean, 44.4
- [x] The median, 28
- [ ] The range, 128
> One huge value pulls the mean up. The median ignores how big the outlier is.
Q: Two batters both average 40. Which statistic tells the steady one from the risky one?
- [ ] The mean
- [x] The standard deviation
- [ ] The count
> Same centre, different spread. A small standard deviation means consistent scores.
Q: Q1 is 21.75 and Q3 is 28.5. Using the 1.5 × IQR rule, which delivery time is an outlier?
- [ ] 31 minutes
- [ ] 18 minutes
- [x] 75 minutes
> IQR = 6.75, so the fences are 11.625 and 38.625. Only 75 is outside.

## probability-conditional-bayes
Q: What is P(not rolling a 6) with a fair die?
- [ ] 1/6
- [x] 5/6
- [ ] 1/2
> P(not A) = 1 − P(A) = 1 − 1/6.
Q: A disease affects 1 in 100 people. A test catches 99% of cases but has a 5% false-positive rate. You test positive. About how likely is it that you are sick?
- [ ] 99%
- [ ] 95%
- [x] About 17%
> 99 true positives against 495 false positives: 99 ÷ 594 ≈ 17%. The rare disease (the prior) matters a lot.
Q: P(buy) is 8.5% but P(buy | laptop) is 12%. What does that tell you?
- [x] Device and buying are not independent
- [ ] Laptops cause buying
- [ ] The data must be wrong
> Knowing the device changes the probability, so the two are related. It doesn't prove cause.

## distributions-normal-central-limit-theorem
Q: About what share of a normal distribution lies within 2 standard deviations of the mean?
- [ ] 68%
- [x] 95%
- [ ] 99.7%
> The 68-95-99.7 rule.
Q: Asha has z = 1.8 in her exam and Rahul has z = 2.0 in his. Who did better relative to their class?
- [ ] Asha
- [x] Rahul
- [ ] Can't compare different exams
> z-scores put different scales on the same footing. Higher z means further above the class average.
Q: What does the central limit theorem say about averages of many samples?
- [ ] They follow the same shape as the original data
- [x] They form a normal distribution, narrower as samples get bigger
- [ ] They are always exactly the true mean
> Even a flat die gives bell-shaped averages, and the spread shrinks like 1 ÷ √n.

## sampling-confidence-intervals
Q: How do you halve the margin of error?
- [ ] Double the sample size
- [x] Make the sample 4 times bigger
- [ ] Use the median instead of the mean
> Standard error divides by √n, and √4 = 2.
Q: What does "95% confidence interval" mean?
- [ ] There is a 95% chance this one interval contains the truth
- [x] The method catches the true value in about 95% of repeated samples
- [ ] 95% of the data lies inside it
> It describes the method. Our simulation caught the true mean in 943 of 1,000 intervals.
Q: Which survey is most likely to be biased?
- [ ] 500 customers picked at random
- [x] Feedback from customers who chose to fill in a form
- [ ] 2,000 people picked at random across the country
> Self-selected samples over-represent people with strong feelings.

## hypothesis-tests-p-values
Q: A fair-coin test gives p = 0.035 for 61 heads in 100 flips. With a 0.05 threshold, what do you conclude?
- [x] Reject "the coin is fair"
- [ ] The coin is fair
- [ ] There is a 3.5% chance the coin is fair
> p < 0.05, so the result would be surprising for a fair coin. A p-value is not the probability that H₀ is true.
Q: Which test compares the average order value of two separate groups of users (A/B test)?
- [ ] Chi-square
- [x] Independent t-test (`ttest_ind`)
- [ ] Paired t-test (`ttest_rel`)
> Different people saw A and B, so the groups are independent.
Q: Why is stopping an A/B test the moment p dips below 0.05 a mistake?
- [ ] It makes the test too slow
- [x] Checking again and again raises the chance of a false alarm
- [ ] p-values can't go below 0.05
> Peeking gives luck many chances to look like an effect. Fix the sample size first.

## correlation-vs-causation
Q: x goes from −5 to 5 and y = x². What is Pearson's r?
- [x] About 0
- [ ] About 1
- [ ] About −1
> Pearson only sees straight lines. Always plot the data.
Q: Ice-cream sales and drownings are strongly correlated. What is the best explanation?
- [ ] Ice cream causes drowning
- [x] Hot weather drives both (a confounder)
- [ ] Drowning causes ice-cream sales
> When temperature is held nearly fixed, the correlation falls to noise.
Q: What is the most reliable way to show that a change causes an effect?
- [ ] A high correlation
- [ ] A big dataset
- [x] A randomised experiment
> Random groups spread confounders evenly, so only your change can explain the difference.
