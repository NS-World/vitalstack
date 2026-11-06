---
title: "Signature Project 4: Fair Pay and Attrition Audit"
slug: signature-fair-pay-audit
subject: ds-signature
lesson: 4
difficulty: advanced
excerpt: Act as the data analyst for an HR head with 1,200 employees across eight cities. Fill missing salaries correctly, test whether remote workers are paid fairly once experience and department are taken into account, find who is about to leave, and put a rupee figure on keeping them.
---

An HR head asks two questions every appraisal season: **"Are we paying people fairly?"** and **"Who is about to leave?"** Answering them with raw averages leads to wrong and sometimes unfair decisions. In this project you answer both properly, with the VitalStack **HR employees** dataset (1,200 people, 6 departments, 8 cities) from the [Datasets Hub](/datasets/).

::scene The appraisal-season brief
🧑‍💼 | The brief | "Are remote workers underpaid? And why are good people leaving?" | dev
🧩 | The gaps | 30 salaries are missing. The average would put a senior engineer on a fresher's pay. | bugsy
⚖️ | Fair comparison | Compare like with like: same department, same experience. | chip
💸 | The budget | Retaining a few high performers costs far less than replacing them. | byte
::end

## Load and inspect

```python
import numpy as np
import pandas as pd

DATA = "https://vitalstack.co.in/wp-content/themes/vitalstack/data/datasets/"
df = pd.read_csv(DATA + "hr-employees.csv")
print(df.shape)
print("missing salaries:", int(df["salary_lpa"].isna().sum()))
print("left the company:", round((df["left_company"] == "Yes").mean() * 100, 1), "%")
```

Output:

```text
(1200, 10)
missing salaries: 30
left the company: 11.8 %
```

## Fill missing salaries like an analyst

Filling with the overall average (₹8 lakh) would give a 13-year engineer the same pay as a support fresher. Salary depends on **department and experience**, so we fill each gap with the median of people like them.

```python
df["exp_band"] = pd.cut(df["experience_years"], [-1, 2, 5, 10, 40], labels=["0-2", "3-5", "6-10", "10+"])
peers = df.groupby(["department", "exp_band"], observed=True)["salary_lpa"].transform("median")
df["salary"] = df["salary_lpa"].fillna(peers).round(2)
print("overall average:", round(df["salary_lpa"].mean(), 2), "lakh")
print(df.loc[df["salary_lpa"].isna(), ["department", "experience_years", "salary"]].head(3).to_string(index=False))
```

Output:

```text
overall average: 8.04 lakh
 department  experience_years  salary
  Marketing                 7    8.76
    Support                13    6.47
Engineering                13   24.08
```

The senior engineer gets **₹24 lakh** instead of 8. Group-wise filling keeps the analysis honest.

## Is remote work paid fairly?

The raw medians suggest remote workers earn a little less. But raw medians mix departments and experience levels together.

```python
print(df.groupby("work_mode")["salary"].median().to_dict())
```

Output:

```text
{'Hybrid': 6.81, 'Office': 6.99, 'Remote': 6.7}
```

To compare like with like we use a regression on the **log of salary**, so each coefficient becomes a percentage difference, holding the other columns fixed.

::walk python | Turning a log-salary coefficient into a percentage
coef = 0.072
pct = (2.718281828 ** coef - 1) * 100
print(round(pct, 1), "% more salary per year of experience")
::notes
1: The regression gives 0.072 for experience_years on log(salary). // vars: coef=0.072
2: exp(coef) − 1 converts it into a percentage change. // deep: Log models are standard for pay because raises are multiplied, not added: a 10% raise on ₹4 lakh and on ₹20 lakh are the same decision. // vars: pct=7.47
3: About 7.5% more pay for each extra year. // out: 7.5 % more salary per year of experience
::end

```python
from sklearn.linear_model import LinearRegression

X = pd.get_dummies(df[["department", "work_mode"]], drop_first=True).astype(int)
X["experience_years"] = df["experience_years"]
X["performance_rating"] = df["performance_rating"]
reg = LinearRegression().fit(X, np.log(df["salary"]))
effect = pd.Series((np.exp(reg.coef_) - 1) * 100, index=X.columns).round(1)
print(effect[["work_mode_Office", "work_mode_Remote", "experience_years", "performance_rating"]].to_dict())
print("R²:", round(reg.score(X, np.log(df["salary"])), 2))
```

Output:

```text
{'work_mode_Office': -0.2, 'work_mode_Remote': 0.1, 'experience_years': 7.5, 'performance_rating': -0.7}
R²: 0.85
```

Once department and experience are held fixed, **office and remote staff are paid the same** (differences of −0.2% and +0.1%). The raw gap came from *who* works remotely, not from unfair pay. The model explains 85% of the variation in pay (R² 0.85), so this conclusion rests on solid ground.

But one line should worry the HR head: **performance rating has no effect on pay** (−0.7% per rating point, basically zero). Top performers are paid like everyone else in their band.

## Who is leaving?

```python
df["left"] = (df["left_company"] == "Yes").astype(int)
df["top_performer"] = df["performance_rating"] >= 4
rates = df.pivot_table(index="overtime", columns="top_performer", values="left", aggfunc="mean").mul(100).round(1)
print(rates.to_string())
print(df.groupby("overtime")["left"].mean().mul(100).round(1).to_dict())
```

Output:

```text
top_performer  False  True
overtime
No               8.1    5.9
Yes             22.5   22.2
{'No': 7.2, 'Yes': 22.4}
```

Overtime is the loudest signal: people on regular overtime leave about **three times as often** (22% against 7%). Among **top performers** on overtime, more than one in five leaves within the year, and they are exactly the people the company can least afford to lose.

## Put a rupee figure on keeping them

We find top performers who do overtime **and** are paid at or below the median of their peers (same department and experience band), then compare two options:

- **Do nothing:** expect their observed leave rate to continue. Replacing an employee commonly costs about half a year's salary (hiring, notice period, ramp-up time). Treat that 50% as an assumption your HR team can change.
- **Act:** give them a 10% raise and cut their overtime, and assume (another assumption to test) that this halves their attrition.

```python
df["vs_peers"] = df["salary"] / df.groupby(["department", "exp_band"], observed=True)["salary"].transform("median")
at_risk = df[df["top_performer"] & (df["overtime"] == "Yes") & (df["vs_peers"] <= 1)]
total_pay = at_risk["salary"].sum() * 100000              # lakh → rupees
leave_rate = at_risk["left"].mean()
REPLACE = 0.5                                            # replacement cost = 50% of salary (assumption)
do_nothing = leave_rate * total_pay * REPLACE
act = 0.10 * total_pay + (leave_rate / 2) * total_pay * 1.10 * REPLACE
print("people at risk:", len(at_risk), "| their yearly pay: ₹{:.1f} crore".format(total_pay / 1e7))
print("observed leave rate:", round(leave_rate * 100, 1), "%")
print("cost of doing nothing: ₹{:.1f} lakh".format(do_nothing / 1e5))
print("cost of 10% raise + less overtime: ₹{:.1f} lakh".format(act / 1e5))
```

Output:

```text
people at risk: 78 | their yearly pay: ₹5.3 crore
observed leave rate: 24.4 %
cost of doing nothing: ₹65.0 lakh
cost of 10% raise + less overtime: ₹89.1 lakh
```

Read this result carefully. With these assumptions, a blanket 10% raise **costs more** than the attrition it prevents. That is a valuable finding: the cheaper lever is the one that drives the risk, **overtime**, not salary. A good audit recommends fixing workload first and saving raises for people whose pay is clearly below their peers. The raise only pays for itself if replacing someone costs more than about **90% of a year's salary** (try `REPLACE = 1.0`, a figure often used for senior roles).

::tip Fair pay analysis touches real people. Never publish individual salaries, aggregate small groups (fewer than 5 people) before sharing, and check results for gender or other protected groups only with proper consent and legal guidance. This dataset has no gender column on purpose.

## Make it yours

- **Break-even:** find the replacement cost (as a share of salary) at which the raise becomes worth it.
- **Attrition model:** train a classifier on overtime, pay vs peers, experience and city, and list the top 20 people by risk for HR (anonymised by employee ID).
- **City view:** Delhi and Pune show higher attrition than Ahmedabad. Is that overtime, pay or something else?

## Portfolio checklist

- [ ] Missing salaries filled by department and experience, with the reason
- [ ] Raw pay gap vs adjusted gap from a log-salary regression, explained as percentages
- [ ] Finding on pay vs performance, stated plainly
- [ ] Attrition by overtime and performance
- [ ] A rupee comparison of two actions, with every assumption written down
- [ ] A clear recommendation (and what data would make it stronger)
