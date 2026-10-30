---
title: CSV Data Explorer (Pandas describe online)
slug: csv-data-explorer
parent: tools
template: page-tool.php
icon: 📈
order: 80
excerpt: Paste or upload a CSV and instantly see every column's summary, missing values, outliers, histograms and correlations, like Pandas describe(), in your browser.
---
## What it does

Paste a CSV (or upload a file) and the explorer does the first 10 minutes of any data analysis for you:

1. **First rows**, like `df.head()`.
2. **Every column**: number or text, count, missing values, and for numbers the mean, median, standard deviation, quartiles and a small histogram, like `df.describe()`.
3. **Warnings** for outliers (the 1.5 × IQR rule) and skewed columns where the median tells the truth better than the mean.
4. **Correlation matrix** between number columns, like `df.corr()`, with the strongest relationship pointed out.
5. **Data health**: missing values and rows with the wrong number of columns.

Your data **never leaves your browser**. Nothing is uploaded, so you can safely explore files from work or college.

## Try the sample data

The sample has 10 students with their city, age, marks and hours studied. Notice that one student has no marks (a missing value), and that marks and hours studied have a correlation of 0.99: students who study more score more. (In real data, always ask whether something else could explain a correlation.)

## Tips for clean CSV files

- The first row should contain the column names.
- Commas, semicolons and tabs are detected automatically.
- Text with commas inside must be wrapped in "double quotes".
- Empty cells, NA, NaN, null and "-" count as missing.

## Next step: do it in Pandas

Click **See the same analysis in Pandas** under the results to get the code, then run it in our [Python online compiler](/tools/python-online-compiler/), which supports Pandas. Learn Pandas step by step in our free [Pandas course](/tutorial-category/pandas/).
