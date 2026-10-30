---
title: Reading and Writing Data
slug: pandas-reading-data
subject: pandas
lesson: 2
difficulty: beginner
excerpt: Load CSV and Excel files into a DataFrame, check them with info(), and save your results back to a file.
---

Real data lives in files: CSV exports, Excel sheets, database dumps. Pandas reads all of them into a DataFrame with one function call.

## Reading a CSV file

A CSV (comma-separated values) file is plain text, one row per line:

```text
date,product,units,price
2026-01-03,Pen,10,15
2026-01-03,Notebook,4,60
2026-01-04,Pen,,15
2026-01-05,Bag,1,850
```

If it's saved as `sales.csv` next to your program, reading it is one line:

```py
df = pd.read_csv("sales.csv")
```

To keep this lesson runnable anywhere, we'll read the same text from memory with `io.StringIO`. It behaves exactly like a file:

```python
import io
import pandas as pd

csv_text = """date,product,units,price
2026-01-03,Pen,10,15
2026-01-03,Notebook,4,60
2026-01-04,Pen,,15
2026-01-05,Bag,1,850"""

df = pd.read_csv(io.StringIO(csv_text))
print(df)
```

Output:

```text
         date   product  units  price
0  2026-01-03       Pen   10.0     15
1  2026-01-03  Notebook    4.0     60
2  2026-01-04       Pen    NaN     15
3  2026-01-05       Bag    1.0    850
```

The empty `units` cell became `NaN` (not a number): Pandas' marker for a **missing value**. Because NaN is a float, the whole `units` column turned into floats (`10.0`).

## info(): the health check

```python
df.info()
```

Output:

```text
<class 'pandas.core.frame.DataFrame'>
RangeIndex: 4 entries, 0 to 3
Data columns (total 4 columns):
 #   Column   Non-Null Count  Dtype  
---  ------   --------------  -----  
 0   date     4 non-null      object 
 1   product  4 non-null      object 
 2   units    3 non-null      float64
 3   price    4 non-null      int64  
dtypes: float64(1), int64(1), object(2)
memory usage: 260.0+ bytes
```

`info()` tells you the number of rows, every column's type, and how many values are **non-null** (not missing). `units` has 3 of 4, so one value is missing. Notice `date` is `object` (text), not a real date yet.

## Useful read_csv options

```python
df = pd.read_csv(
    io.StringIO(csv_text),
    parse_dates=["date"],      # turn text into real dates
    usecols=["date", "product", "units"],  # load only some columns
)
print(df.dtypes)
```

Output:

```text
date       datetime64[ns]
product            object
units             float64
dtype: object
```

Other options you'll meet: `sep=";"` for semicolon files, `nrows=1000` to load only the first rows of a huge file, `encoding="latin-1"` when you see strange characters.

## Excel and other formats

```py
pd.read_excel("report.xlsx", sheet_name="Sales")   # needs: pip install openpyxl
pd.read_json("data.json")
pd.read_sql("SELECT * FROM orders", connection)
```

## Saving your work

```py
df.to_csv("clean_sales.csv", index=False)
df.to_excel("clean_sales.xlsx", index=False)
```

`index=False` stops Pandas from writing the 0, 1, 2… row numbers as an extra column.

::tip In Google Colab, upload a file with the folder icon on the left, then read it with `pd.read_csv("/content/your_file.csv")`.

::hint Quick check | A column that should be numbers shows `object` in `info()`. Why? Usually one cell contains text like `"N/A"` or `"1,200"`. You'll fix that in the cleaning lesson.

## Summary

- `pd.read_csv("file.csv")` loads a CSV; `read_excel`, `read_json`, `read_sql` load other formats.
- Empty cells become `NaN`.
- `df.info()` shows types and missing counts; run it on every new file.
- `df.to_csv("out.csv", index=False)` saves your result.
