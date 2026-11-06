---
title: "Signature Project 3: Clean-Air Alert for Indian Cities"
slug: signature-clean-air-alert
subject: ds-signature
lesson: 3
difficulty: advanced
excerpt: Turn a year of weather and AQI readings from eight Indian cities into a health alert. Fill missing readings the right way, map AQI to the official categories, find the seasonal pattern, and design an early warning that is tested against an honest baseline.
---

Every winter, news channels show Delhi's sky turning grey. Parents of children with asthma want one simple thing: **a warning before a bad-air day**, so they can keep inhalers ready and plan indoor play. In this project you build that alert on the VitalStack **city weather and AQI** dataset (8 cities, one reading every 3 days in 2025) from the [Datasets Hub](/datasets/), and you learn the most important rule of forecasting: **always beat the simple baseline, or admit you didn't**.

::scene A warning before the smog
🌫️ | The worry | Delhi's air turns "Severe" in winter. Families want a heads-up, not a surprise. | dev
🧩 | The gaps | Some days have no AQI reading. Drop them or fill them? | bugsy
📅 | The pattern | AQI follows the seasons: clean monsoons, dirty winters. | chip
🚨 | The alert | Missing a bad day is worse than one extra warning. So we warn a little early. | byte
::end

## Load and find the gaps

```python
import numpy as np
import pandas as pd

DATA = "https://vitalstack.co.in/wp-content/themes/vitalstack/data/datasets/"
df = pd.read_csv(DATA + "city-weather-aqi.csv", parse_dates=["date"]).sort_values(["city", "date"])
print(df.shape)
print("missing AQI readings:", int(df["aqi"].isna().sum()))
print(df[df["aqi"].isna()]["city"].value_counts().to_dict())
```

Output:

```text
(976, 7)
missing AQI readings: 20
{'Lucknow': 5, 'Pune': 4, 'Bengaluru': 4, 'Mumbai': 3, 'Ahmedabad': 2, 'Chennai': 1, 'Delhi': 1}
```

Sensors fail and stations go offline, so real air data always has holes. Air quality changes slowly, so the best estimate for a missing reading is **between its neighbours in the same city**. That is linear interpolation, done city by city so Delhi's values never leak into Bengaluru's.

```python
df["aqi_filled"] = (df.groupby("city")["aqi"]
                      .transform(lambda s: s.interpolate(limit_direction="both"))
                      .round())
print("still missing:", int(df["aqi_filled"].isna().sum()))
lko = df[df["city"] == "Lucknow"]
print("Lucknow mean, dropping gaps:", round(lko["aqi"].mean(), 1), "| filling gaps:", round(lko["aqi_filled"].mean(), 1))
```

Output:

```text
still missing: 0
Lucknow mean, dropping gaps: 204.8 | filling gaps: 207.0
```

Filling changed Lucknow's average by about two points. Small here, but if the gaps had all been on bad winter days, simply dropping them would have made the city look cleaner than it is.

## Speak the official language: AQI categories

People don't act on "AQI 287". They act on "Poor". India's national AQI uses six bands, so we map every reading to its band.

```python
bands = [0, 50, 100, 200, 300, 400, 500]
names = ["Good", "Satisfactory", "Moderate", "Poor", "Very Poor", "Severe"]
df["band"] = pd.cut(df["aqi_filled"], bands, labels=names, include_lowest=True)
table = pd.crosstab(df["city"], df["band"])
print(table.loc[["Delhi", "Lucknow", "Kolkata", "Bengaluru"]].to_string())
```

Output:

```text
band       Good  Satisfactory  Moderate  Poor  Very Poor  Severe
city
Delhi         0             1        57    33         17      14
Lucknow       0            13        61    18         29       1
Kolkata       4            24        63    28          3       0
Bengaluru    34            53        35     0          0       0
```

Delhi has 14 **Severe** readings in the year and Bengaluru has none. The cities live in different worlds, which is why a national alert must be set city by city.

## The seasonal story

```python
delhi = df[df["city"] == "Delhi"]
monthly = delhi.groupby(delhi["date"].dt.month)["aqi_filled"].mean().round()
print(monthly.to_dict())
print("winter-ness vs AQI (correlation of min temperature):", round(delhi["temp_min_c"].corr(delhi["aqi_filled"]), 2))
```

Output:

```text
{1: 402.0, 2: 209.0, 3: 206.0, 4: 218.0, 5: 208.0, 6: 134.0, 7: 120.0, 8: 126.0, 9: 134.0, 10: 197.0, 11: 403.0, 12: 399.0}
winter-ness vs AQI (correlation of min temperature): -0.74
```

Delhi's average AQI doubles from about 200 in October to **400 in November**, and stays there till January. Cold nights trap pollution near the ground, which shows up as a strong negative correlation (−0.74) with the minimum temperature. Monsoon rain (June to September) washes the air clean.

## The baseline you must beat

Our question: **will the next reading be Poor or worse (above 200)?** The simplest forecast is *persistence*: "it will be like today". Any model must beat it.

::walk python | Persistence: the forecast every model must beat
today_aqi = 236
alert = today_aqi > 200
print("Alert for next reading:", alert)
::notes
1: Today's AQI reading is 236 (Poor). // vars: today_aqi=236
2: Persistence says tomorrow looks like today, so alert if today is above 200. // deep: Persistence is surprisingly strong for slow-changing things like air quality, temperature and stock prices. Weather services judge their models by how much they beat it. A model that can't is not worth running. // vars: alert=True
3: The family gets a warning. // out: Alert for next reading: True
::end

```python
df["next_aqi"] = df.groupby("city")["aqi_filled"].shift(-1)
data = df.dropna(subset=["next_aqi"])
test = data[data["date"] >= "2025-09-01"]           # judge on the last four months only
bad_next = test["next_aqi"] > 200

def score(alert):
    return {"caught": int((alert & bad_next).sum()), "of": int(bad_next.sum()),
            "missed": int((~alert & bad_next).sum()), "false_alarms": int((alert & ~bad_next).sum())}

print("persistence:", score(test["aqi_filled"] > 200))
```

Output:

```text
persistence: {'caught': 85, 'of': 103, 'missed': 18, 'false_alarms': 13}
```

Persistence catches 85 of 103 bad readings. Now the honest test: can a model do better?

```python
from sklearn.linear_model import LogisticRegression

g = data.groupby("city")["aqi_filled"]
data = data.assign(trend=g.diff().fillna(0))           # is the air getting worse or better?
train = data[data["date"] < "2025-09-01"]
test = data[data["date"] >= "2025-09-01"]
features = ["aqi_filled", "trend"]
model = LogisticRegression(max_iter=5000).fit(train[features], train["next_aqi"] > 200)
print("model:", score(pd.Series(model.predict(test[features]), index=test.index)))
```

Output:

```text
model: {'caught': 78, 'of': 103, 'missed': 25, 'false_alarms': 10}
```

The model **misses more bad days** (25 versus 18). In this dataset the next reading is mostly "today plus noise", so the model has nothing extra to learn. Saying so in your report is a strength, not a weakness: many beginner projects hide a model that loses to the baseline.

## Design the early warning

For a child with asthma, a missed bad day is far worse than an extra warning. So instead of a fancier model, we **move the alert line**: warn when today's AQI is above a lower number.

```python
for line in [200, 180, 160, 140]:
    print(f"alert when AQI > {line}:", score(test["aqi_filled"] > line))
```

Output:

```text
alert when AQI > 200: {'caught': 85, 'of': 103, 'missed': 18, 'false_alarms': 13}
alert when AQI > 180: {'caught': 93, 'of': 103, 'missed': 10, 'false_alarms': 25}
alert when AQI > 160: {'caught': 98, 'of': 103, 'missed': 5, 'false_alarms': 45}
alert when AQI > 140: {'caught': 101, 'of': 103, 'missed': 2, 'false_alarms': 73}
```

At **160**, the alert misses only 5 of 103 bad readings, at the cost of 45 extra warnings over four months across 8 cities: less than one extra warning per city per fortnight. That is a trade-off families will happily accept, and you can explain it in one sentence.

::tip Health messages make the alert useful. For example: **Poor**, sensitive groups should limit long outdoor activity. **Very Poor**, everyone should reduce outdoor exertion and asthma patients should keep medicines ready. **Severe**, avoid outdoor activity. Always point people to official sources such as the national AQI bulletin for decisions.

## Make it yours

- **Daily data:** this set has a reading every 3 days. Explain how a 1-day forecast would change the baseline, and why persistence gets even stronger.
- **City-specific lines:** choose a different alert line per city so each one misses at most 5% of bad days.
- **A WhatsApp-style message:** write a function that turns a city, date and band into a short Hindi and English alert text.

## Portfolio checklist

- [ ] Missing readings filled per city, with the effect of filling vs dropping
- [ ] Every reading mapped to the six national AQI bands, compared across cities
- [ ] Seasonal pattern shown and explained (winter inversion, monsoon wash-out)
- [ ] Persistence baseline measured on a time-based test period
- [ ] A model honestly compared with the baseline
- [ ] Alert line chosen by the cost of missed days, with the trade-off table
