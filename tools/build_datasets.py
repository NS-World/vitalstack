#!/usr/bin/env python3
"""Build the free practice datasets for the Datasets Hub (5.0).

Every dataset is synthetic (made up with a fixed random seed), realistic and
India-flavoured, with a few deliberate problems (missing values, duplicates,
outliers, messy text) so learners can practise cleaning. Nothing here is real
personal data.

Outputs:
    wp-content/themes/vitalstack/data/datasets/<slug>.csv
    wp-content/themes/vitalstack/data/datasets/index.php   (metadata for the page)

Usage:
    python3 tools/build_datasets.py
"""
import csv
import datetime as dt
import io
import pathlib

import numpy as np

ROOT = pathlib.Path(__file__).resolve().parent.parent
OUT = ROOT / "wp-content" / "themes" / "vitalstack" / "data" / "datasets"
CITIES = ["Mumbai", "Delhi", "Bengaluru", "Hyderabad", "Chennai", "Pune", "Kolkata", "Ahmedabad", "Jaipur", "Surat", "Lucknow", "Indore"]


def r2(x):
    return float(round(float(x), 2))


def retail_sales(rng):
    cats = {"Electronics": (8000, 60000), "Clothing": (400, 3500), "Home & Kitchen": (300, 9000), "Books": (150, 900), "Beauty": (200, 2500), "Sports": (500, 7000), "Grocery": (50, 1200)}
    pays = ["UPI", "UPI", "UPI", "Card", "Card", "Cash on Delivery", "Net Banking", "Wallet"]
    rows = []
    start = dt.date(2025, 1, 1)
    for i in range(2000):
        cat = rng.choice(list(cats))
        lo, hi = cats[cat]
        day = start + dt.timedelta(days=int(rng.integers(0, 365)))
        # festive bump in Oct-Nov
        qty = int(rng.integers(1, 4)) + (1 if day.month in (10, 11) and rng.random() < 0.3 else 0)
        price = r2(rng.uniform(lo, hi))
        disc = int(rng.choice([0, 0, 5, 10, 15, 20, 30]))
        rating = int(rng.choice([1, 2, 3, 4, 4, 5, 5, 5])) if rng.random() > 0.06 else ""
        rows.append({"order_id": f"ORD{100000 + i}", "order_date": day.isoformat(), "city": rng.choice(CITIES), "category": cat,
                     "quantity": qty, "unit_price": price, "discount_pct": disc,
                     "total_amount": r2(qty * price * (1 - disc / 100)), "payment_method": rng.choice(pays), "rating": rating})
    for k in rng.choice(len(rows), 15, replace=False):  # duplicate rows to find
        rows.append(dict(rows[int(k)]))
    return rows


def hr_employees(rng):
    depts = {"Engineering": (6, 40), "Sales": (3, 18), "Marketing": (4, 20), "HR": (3, 14), "Finance": (4, 22), "Support": (2.5, 9)}
    rows = []
    for i in range(1200):
        dept = rng.choice(list(depts))
        lo, hi = depts[dept]
        exp = int(min(30, max(0, rng.gamma(2.2, 3))))
        sal = r2(min(hi, lo + exp * (hi - lo) / 25 + rng.normal(0, 1.2)))
        perf = int(np.clip(round(rng.normal(3.3, 0.9)), 1, 5))
        overtime = rng.random() < 0.3
        p_leave = 0.08 + (0.12 if overtime else 0) + (0.1 if perf <= 2 else 0) + (0.08 if exp < 2 else 0)
        rows.append({"employee_id": f"EMP{5000 + i}", "department": dept, "city": rng.choice(CITIES[:8]), "age": 21 + exp + int(rng.integers(0, 6)),
                     "experience_years": exp, "salary_lpa": max(2.4, sal), "performance_rating": perf,
                     "overtime": "Yes" if overtime else "No", "work_mode": rng.choice(["Office", "Hybrid", "Hybrid", "Remote"]),
                     "left_company": "Yes" if rng.random() < p_leave else "No"})
    for k in rng.choice(len(rows), 30, replace=False):
        rows[int(k)]["salary_lpa"] = ""
    return rows


def student_performance(rng):
    rows = []
    for i in range(1000):
        hours = r2(np.clip(rng.normal(3, 1.4), 0, 9))
        att = int(np.clip(rng.normal(80, 12), 30, 100))
        prev = int(np.clip(rng.normal(65, 14), 20, 100))
        internet = rng.choice(["Yes", "Yes", "Yes", "No"])
        score = int(np.clip(0.35 * prev + 6 * hours + 0.25 * att + (4 if internet == "Yes" else 0) + rng.normal(0, 7), 0, 100))
        rows.append({"student_id": f"STU{2000 + i}", "gender": rng.choice(["Female", "Male"]), "board": rng.choice(["CBSE", "State", "ICSE"]),
                     "study_hours_per_day": hours, "attendance_pct": att, "previous_score": prev, "internet_access": internet,
                     "extra_classes": rng.choice(["Yes", "No"]), "final_score": score, "result": "Pass" if score >= 40 else "Fail"})
    return rows


def bank_transactions(rng):
    rows = []
    start = dt.datetime(2025, 6, 1)
    merchants = ["Grocery", "Food Delivery", "Fuel", "Electronics", "Travel", "Bills", "Shopping", "Transfer", "Gaming", "Education"]
    for i in range(3000):
        t = start + dt.timedelta(minutes=int(rng.integers(0, 60 * 24 * 90)))
        channel = rng.choice(["UPI", "UPI", "UPI", "Debit Card", "Credit Card", "Net Banking"])
        m = rng.choice(merchants)
        amt = r2(np.exp(rng.normal(6.2, 1.1)))
        night = t.hour < 5
        fraud_p = 0.004 + (0.03 if night else 0) + (0.04 if amt > 20000 else 0) + (0.02 if m in ("Gaming", "Electronics") else 0)
        is_fraud = rng.random() < fraud_p
        if is_fraud:
            amt = r2(amt * rng.uniform(2, 8))
        rows.append({"txn_id": f"TXN{900000 + i}", "timestamp": t.strftime("%Y-%m-%d %H:%M"), "account_type": rng.choice(["Savings", "Savings", "Current"]),
                     "channel": channel, "merchant_category": m, "amount_inr": amt, "city": rng.choice(CITIES),
                     "new_device": "Yes" if (is_fraud and rng.random() < 0.6) or rng.random() < 0.05 else "No", "is_fraud": 1 if is_fraud else 0})
    return rows


def customer_churn(rng):
    rows = []
    for i in range(1500):
        plan = rng.choice(["Basic", "Standard", "Premium"])
        charge = {"Basic": 199, "Standard": 449, "Premium": 799}[plan] + int(rng.integers(-20, 40))
        tenure = int(rng.integers(1, 61))
        tickets = int(rng.poisson(1.4))
        last_login = int(rng.integers(0, 60))
        p = 0.1 + (0.2 if tenure < 6 else 0) + 0.06 * tickets + (0.25 if last_login > 30 else 0) - (0.08 if plan == "Premium" else 0)
        rows.append({"customer_id": f"CUS{10000 + i}", "plan": plan, "monthly_charge_inr": charge, "tenure_months": tenure,
                     "support_tickets": tickets, "days_since_last_login": last_login, "auto_pay": rng.choice(["Yes", "No"]),
                     "city_tier": rng.choice(["Tier 1", "Tier 1", "Tier 2", "Tier 3"]), "churned": "Yes" if rng.random() < min(0.9, max(0.02, p)) else "No"})
    return rows


def city_weather_aqi(rng):
    base = {"Delhi": (25, 210), "Mumbai": (28, 110), "Bengaluru": (24, 75), "Chennai": (29, 85), "Kolkata": (27, 140), "Lucknow": (26, 190), "Ahmedabad": (28, 120), "Pune": (25, 90)}
    rows = []
    start = dt.date(2025, 1, 1)
    for city, (t0, a0) in base.items():
        for d in range(0, 365, 3):
            day = start + dt.timedelta(days=d)
            season = np.sin((day.timetuple().tm_yday - 100) / 365 * 2 * np.pi)
            winter = day.month in (11, 12, 1)
            monsoon = day.month in (6, 7, 8, 9)
            tmax = r2(t0 + 7 * season + rng.normal(0, 1.5))
            rain = r2(max(0, rng.normal(14, 10))) if monsoon and rng.random() < 0.6 else 0.0
            aqi = int(max(20, a0 * (1.9 if winter else (0.6 if monsoon else 1)) + rng.normal(0, 25)))
            rows.append({"date": day.isoformat(), "city": city, "temp_max_c": tmax, "temp_min_c": r2(tmax - rng.uniform(6, 12)),
                         "humidity_pct": int(np.clip(rng.normal(80 if monsoon else 55, 10), 15, 100)), "rainfall_mm": rain, "aqi": aqi})
    for k in rng.choice(len(rows), 20, replace=False):
        rows[int(k)]["aqi"] = ""
    return rows


def food_delivery(rng):
    rows = []
    for i in range(2500):
        dist = r2(rng.uniform(0.5, 12))
        prep = int(rng.integers(8, 35))
        peak = rng.random() < 0.35
        weather = rng.choice(["Clear", "Clear", "Clear", "Rainy", "Cloudy"])
        t = int(prep + dist * 3.2 + (8 if peak else 0) + (10 if weather == "Rainy" else 0) + rng.normal(0, 4))
        rating = int(np.clip(round(5 - max(0, t - 35) / 10 + rng.normal(0, 0.6)), 1, 5))
        rows.append({"order_id": f"FD{300000 + i}", "city": rng.choice(CITIES[:8]), "cuisine": rng.choice(["North Indian", "South Indian", "Chinese", "Pizza", "Biryani", "Desserts", "Healthy"]),
                     "distance_km": dist, "prep_time_min": prep, "peak_hour": "Yes" if peak else "No", "weather": weather,
                     "delivery_time_min": max(10, t), "order_value_inr": int(np.exp(rng.normal(6, 0.5))), "rating": rating})
    for k in rng.choice(len(rows), 8, replace=False):  # outliers to spot
        rows[int(k)]["delivery_time_min"] = int(rng.integers(180, 400))
    return rows


def house_prices(rng):
    city_rate = {"Mumbai": 18000, "Bengaluru": 8500, "Hyderabad": 7000, "Pune": 7500, "Delhi": 11000, "Chennai": 7200, "Ahmedabad": 5200, "Jaipur": 4600}
    rows = []
    for i in range(1500):
        city = rng.choice(list(city_rate))
        bhk = int(rng.choice([1, 2, 2, 2, 3, 3, 4]))
        area = int(np.clip(rng.normal(450 + bhk * 330, 120), 300, 3500))
        age = int(rng.integers(0, 30))
        tier = rng.choice(["Prime", "Mid", "Mid", "Outskirts"])
        mult = {"Prime": 1.45, "Mid": 1.0, "Outskirts": 0.7}[tier]
        price = area * city_rate[city] * mult * (1 - age * 0.006) * rng.normal(1, 0.08)
        rows.append({"listing_id": f"HSE{7000 + i}", "city": city, "locality": tier, "bhk": bhk, "area_sqft": area,
                     "bathrooms": max(1, bhk - int(rng.integers(0, 2))), "age_years": age, "parking": rng.choice(["Yes", "Yes", "No"]),
                     "furnishing": rng.choice(["Unfurnished", "Semi-furnished", "Furnished"]), "price_lakh": r2(price / 1e5)})
    return rows


DATASETS = [
    ("retail-sales", "Retail Sales Orders", "🛒", retail_sales, "2,000 online orders from 2025 across Indian cities: category, quantity, price, discount, payment method and rating. Contains 15 duplicate rows and some missing ratings to clean.",
     ["Which category earns the most revenue? Which city?", "How do sales change month by month (look at October–November)?", "Does a bigger discount lead to more quantity sold?", "What share of orders use UPI vs cards vs cash on delivery?", "Find and remove the duplicate orders. How many were there?"]),
    ("hr-employees", "HR Employees and Attrition", "👩‍💼", hr_employees, "1,200 employees with department, experience, salary (lakh per annum), performance, overtime, work mode and whether they left. 30 salaries are missing.",
     ["What is the attrition rate by department and by work mode?", "Do people who work overtime leave more often?", "How does salary grow with experience in each department?", "Fill the missing salaries sensibly (median by department?).", "Build a model to predict who might leave."]),
    ("student-performance", "Student Performance", "🎓", student_performance, "1,000 students with study hours, attendance, previous score, internet access, extra classes and final score. Great for regression and correlation practice.",
     ["Which factor correlates most with the final score?", "What is the pass rate by board?", "Does internet access make a difference?", "Predict the final score from study hours and attendance.", "Plot study hours vs final score: what pattern do you see?"]),
    ("bank-transactions", "Bank Transactions and Fraud", "💳", bank_transactions, "3,000 card, UPI and net-banking transactions over 90 days with a fraud label (about 1–2% fraud). A classic imbalanced classification problem.",
     ["What percentage of transactions are fraud?", "At what hours does fraud happen most?", "Are fraud transactions bigger on average?", "Does a new device raise the fraud rate?", "Build a classifier and report precision and recall (not just accuracy)."]),
    ("customer-churn", "Subscription Customer Churn", "📉", customer_churn, "1,500 subscription customers with plan, monthly charge, tenure, support tickets, last login and churn. Perfect for a churn-prediction portfolio project.",
     ["What is the churn rate by plan and city tier?", "Do customers with many support tickets churn more?", "How does churn change with tenure?", "Which customers should get a retention offer first?", "Train a model and explain the top 3 features."]),
    ("city-weather-aqi", "City Weather and Air Quality", "🌫️", city_weather_aqi, "Weather and AQI every 3 days in 2025 for 8 Indian cities: temperature, humidity, rainfall and air-quality index. 20 AQI values are missing.",
     ["Which city has the worst average AQI? In which months?", "How does rainfall relate to AQI?", "Plot temperature through the year for two cities.", "Fill the missing AQI values using nearby dates.", "Build a simple AQI forecast for Delhi."]),
    ("food-delivery", "Food Delivery Times", "🛵", food_delivery, "2,500 food deliveries with distance, preparation time, peak hour, weather, delivery time and rating. Includes a few extreme delivery times (outliers) to detect.",
     ["How much does rain add to delivery time?", "What is the relationship between delivery time and rating?", "Find the outliers in delivery time. Are they errors?", "Which cuisine has the longest prep time?", "Predict delivery time from distance, prep time, peak hour and weather."]),
    ("house-prices", "House Prices in Indian Cities", "🏠", house_prices, "1,500 house listings across 8 cities with BHK, area, age, locality tier, parking, furnishing and price in lakh rupees.",
     ["What is the price per sq ft in each city?", "How much more do Prime localities cost than Outskirts?", "Does house age lower the price?", "Build a price prediction model and report its MAE.", "Which city gives the most area for ₹1 crore?"]),
]


def php_str(s):
    return "'" + str(s).replace("\\", "\\\\").replace("'", "\\'") + "'"


def main():
    OUT.mkdir(parents=True, exist_ok=True)
    meta = []
    for i, (slug, title, icon, fn, desc, questions) in enumerate(DATASETS):
        rows = fn(np.random.default_rng(100 + i))
        cols = list(rows[0].keys())
        buf = io.StringIO()
        w = csv.DictWriter(buf, fieldnames=cols, lineterminator="\n")
        w.writeheader()
        w.writerows(rows)
        (OUT / f"{slug}.csv").write_text(buf.getvalue(), encoding="utf-8")
        info = []
        for c in cols:
            vals = [r[c] for r in rows]
            missing = sum(1 for v in vals if v == "")
            nums = [v for v in vals if isinstance(v, (int, float)) and v != ""]
            kind = "number" if len(nums) == len(vals) - missing else "text"
            info.append((c, kind, missing))
        size = len(buf.getvalue().encode("utf-8"))
        meta.append((slug, title, icon, desc, len(rows), info, rows[:6], questions, size))
        print(f"{slug}: {len(rows)} rows, {len(cols)} columns, {size // 1024} KB")
    lines = ["<?php", "// Generated by tools/build_datasets.py. Do not edit by hand.", "defined( 'ABSPATH' ) || exit;", "return array("]
    for slug, title, icon, desc, n, info, preview, questions, size in meta:
        cols = ", ".join(f"array( {php_str(c)}, {php_str(k)}, {m} )" for c, k, m in info)
        prev = ", ".join("array( " + ", ".join(php_str(v) for v in r.values()) + " )" for r in preview)
        qs = ", ".join(php_str(q) for q in questions)
        lines.append(f"\t{php_str(slug)} => array( 'title' => {php_str(title)}, 'icon' => {php_str(icon)}, 'desc' => {php_str(desc)}, 'rows' => {n}, 'bytes' => {size}, 'cols' => array( {cols} ), 'preview' => array( {prev} ), 'questions' => array( {qs} ) ),")
    lines.append(");")
    (OUT / "index.php").write_text("\n".join(lines) + "\n", encoding="utf-8")


if __name__ == "__main__":
    main()
