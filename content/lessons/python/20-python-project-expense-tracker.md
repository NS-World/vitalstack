---
title: "Python Project: Simple Expense Tracker"
slug: python-project-expense-tracker
subject: python
lesson: 20
difficulty: intermediate
excerpt: Build a command-line expense tracker step by step with a dataclass, functions, JSON storage, error handling, reports by category and month, and CSV export.
---

Time to put the whole Python course into one useful program: a command-line **expense tracker**. You'll be able to add expenses, list them, see totals by category and month, delete mistakes, and export everything to a CSV file you can open in Excel. The data is saved to a JSON file, so it's still there next time.

## What you'll build

```text
=== Expense Tracker ===
1. Add expense
2. List expenses
3. Summary by category
4. Monthly totals
5. Delete expense
6. Export to CSV
0. Quit
Choose an option:
```

Create a folder `expense-tracker` with one file, `tracker.py`. We'll build it in steps; the complete program is at the end.

## Step 1: The data model

Each expense has an id, a date, a description, a category and an amount. A dataclass is perfect:

```python
from dataclasses import dataclass, asdict
from datetime import date

@dataclass
class Expense:
    id: int
    day: str            # ISO date, e.g. "2024-05-01"
    description: str
    category: str
    amount: float

e = Expense(1, date(2024, 5, 1).isoformat(), "Lunch", "food", 180.0)
print(e)
print(asdict(e))        # a plain dict, ready to save as JSON
```

Storing dates as ISO strings (`"2024-05-01"`) keeps them JSON-friendly and sortable.

## Step 2: Saving and loading

```python
import json
from pathlib import Path

DATA_FILE = Path("expenses.json")

def load_expenses():
    if not DATA_FILE.exists():
        return []
    try:
        with DATA_FILE.open(encoding="utf-8") as f:
            return [Expense(**item) for item in json.load(f)]
    except (json.JSONDecodeError, TypeError) as error:
        print(f"Could not read {DATA_FILE}: {error}. Starting with an empty list.")
        return []

def save_expenses(expenses):
    with DATA_FILE.open("w", encoding="utf-8") as f:
        json.dump([asdict(e) for e in expenses], f, indent=2, ensure_ascii=False)
```

`Expense(**item)` unpacks each saved dictionary into the dataclass. If the file is damaged, we warn and continue rather than crash.

## Step 3: Getting valid input

Users make typos. Helper functions keep asking until the input is valid:

```python
from datetime import date

def ask_amount(prompt):
    while True:
        text = input(prompt).strip().replace(",", "")
        try:
            amount = float(text)
            if amount <= 0:
                raise ValueError
            return round(amount, 2)
        except ValueError:
            print("Please enter a positive number, e.g. 249.50")

def ask_date(prompt):
    while True:
        text = input(prompt).strip()
        if not text:
            return date.today().isoformat()       # Enter = today
        try:
            return date.fromisoformat(text).isoformat()
        except ValueError:
            print("Use the format YYYY-MM-DD, or press Enter for today.")
```

## Step 4: Adding and listing

```python
CATEGORIES = ["food", "transport", "bills", "shopping", "health", "other"]

def next_id(expenses):
    return max((e.id for e in expenses), default=0) + 1

def add_expense(expenses):
    description = input("Description: ").strip() or "(no description)"
    print("Categories:", ", ".join(CATEGORIES))
    category = input("Category: ").strip().lower()
    if category not in CATEGORIES:
        print("Unknown category, using 'other'.")
        category = "other"
    amount = ask_amount("Amount (₹): ")
    day = ask_date("Date (YYYY-MM-DD, Enter for today): ")
    expense = Expense(next_id(expenses), day, description, category, amount)
    expenses.append(expense)
    save_expenses(expenses)
    print(f"Added #{expense.id}: {description} – ₹{amount:,.2f}")

def list_expenses(expenses):
    if not expenses:
        print("No expenses yet.")
        return
    print(f"{'ID':>3}  {'Date':<10}  {'Category':<10}  {'Amount':>10}  Description")
    for e in sorted(expenses, key=lambda e: (e.day, e.id)):
        print(f"{e.id:>3}  {e.day:<10}  {e.category:<10}  {e.amount:>10,.2f}  {e.description}")
    print(f"{'Total':>27}  {sum(e.amount for e in expenses):>10,.2f}")
```

f-string widths (`:>3`, `:<10`, `:>10,.2f`) line the columns up like a table.

## Step 5: Reports

```python
from collections import defaultdict

def summary_by_category(expenses):
    totals = defaultdict(float)
    for e in expenses:
        totals[e.category] += e.amount
    grand_total = sum(totals.values())
    if not grand_total:
        print("No expenses yet.")
        return
    for category, total in sorted(totals.items(), key=lambda item: item[1], reverse=True):
        share = total / grand_total
        bar = "█" * round(share * 30)
        print(f"{category:<10} ₹{total:>10,.2f}  {share:>4.0%}  {bar}")

def monthly_totals(expenses):
    months = defaultdict(float)
    for e in expenses:
        months[e.day[:7]] += e.amount          # "2024-05-01"[:7] → "2024-05"
    for month in sorted(months):
        print(f"{month}  ₹{months[month]:>10,.2f}")
```

The category report even draws a little bar chart with text characters.

## Step 6: Deleting and exporting

```python
import csv

def delete_expense(expenses):
    try:
        expense_id = int(input("ID to delete: "))
    except ValueError:
        print("Please enter a number.")
        return
    for e in expenses:
        if e.id == expense_id:
            confirm = input(f"Delete '{e.description}' (₹{e.amount:,.2f})? [y/N] ")
            if confirm.lower() == "y":
                expenses.remove(e)
                save_expenses(expenses)
                print("Deleted.")
            return
    print(f"No expense with id {expense_id}.")

def export_csv(expenses, path="expenses.csv"):
    with open(path, "w", newline="", encoding="utf-8-sig") as f:
        writer = csv.DictWriter(f, fieldnames=["id", "day", "description", "category", "amount"])
        writer.writeheader()
        writer.writerows(asdict(e) for e in expenses)
    print(f"Exported {len(expenses)} expenses to {path}")
```

`utf-8-sig` makes Excel display the ₹ sign and other special characters correctly.

## Step 7: The menu

```python
def main():
    expenses = load_expenses()
    actions = {
        "1": add_expense,
        "2": list_expenses,
        "3": summary_by_category,
        "4": monthly_totals,
        "5": delete_expense,
        "6": export_csv,
    }
    while True:
        print("\n=== Expense Tracker ===")
        print("1. Add expense\n2. List expenses\n3. Summary by category")
        print("4. Monthly totals\n5. Delete expense\n6. Export to CSV\n0. Quit")
        choice = input("Choose an option: ").strip()
        if choice == "0":
            print("Goodbye!")
            break
        action = actions.get(choice)
        if action:
            action(expenses)
        else:
            print("Please choose a number from the menu.")

if __name__ == "__main__":
    main()
```

A dictionary maps menu choices to functions: no long if/elif chain needed, and adding a feature means adding one line.

## The complete program

Put all the pieces together in `tracker.py` in this order: imports, the dataclass, the constants, the functions from steps 2–6, then `main()`. All the imports at the top:

```python
import csv
import json
from collections import defaultdict
from dataclasses import dataclass, asdict
from datetime import date
from pathlib import Path
```

Run it:

```bash
python tracker.py
```

Add a few expenses, quit, and run it again: your data is still there in `expenses.json`.

## Testing the logic without typing

Functions that don't ask for input are easy to test automatically. Here's a quick check of the reports with sample data:

```python
from collections import defaultdict
from dataclasses import dataclass

@dataclass
class Expense:
    id: int
    day: str
    description: str
    category: str
    amount: float

sample = [
    Expense(1, "2024-05-01", "Lunch", "food", 180),
    Expense(2, "2024-05-02", "Metro card", "transport", 500),
    Expense(3, "2024-05-15", "Groceries", "food", 1450.5),
    Expense(4, "2024-06-01", "Electricity", "bills", 1320),
]

totals = defaultdict(float)
for e in sample:
    totals[e.category] += e.amount
print(dict(totals))   # {'food': 1630.5, 'transport': 500.0, 'bills': 1320.0}

months = defaultdict(float)
for e in sample:
    months[e.day[:7]] += e.amount
print(dict(months))   # {'2024-05': 2130.5, '2024-06': 1320.0}
```

In a real project, you'd put checks like this in a test file and run them with `pytest`.

## What you used from the course

- Variables, strings, numbers and f-string formatting.
- Lists, dictionaries, `defaultdict`, sorting with `key=` and lambdas.
- Loops, conditions and a dictionary of functions.
- Functions with clear single jobs, and `if __name__ == "__main__":`.
- A dataclass, JSON and CSV files, `pathlib`.
- Exceptions for invalid input and damaged files.

## Ideas to extend it

- A **budget** per category with a warning when you go over it.
- **Edit** an existing expense.
- Filter the list by month or category.
- Store data in **SQLite** with the built-in `sqlite3` module, using what you learned in the SQL course.
- Draw a real chart with **matplotlib** (see the pip lesson).
- Turn it into a small web app with **Flask**.

## Summary

- Model data with a dataclass and store it as JSON for persistence.
- Validate every input with loops and exceptions.
- Keep functions small, and map menu choices to functions with a dictionary.
- Separate logic from input/output so it can be tested.

Congratulations on finishing the Python course! You can now write useful programs that read, process and save real data. From here, explore web development with Flask or Django, data analysis with pandas, or AI with the guides in our AI section.
