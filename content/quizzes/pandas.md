## pandas-introduction
Q: What is a DataFrame?
- [ ] A single list of numbers
- [x] A table of labelled columns that share an index
- [ ] A chart
> Each column of a DataFrame is a Series.
Q: What does `df.shape` return for 100 rows and 5 columns?
- [x] `(100, 5)`
- [ ] `(5, 100)`
- [ ] `500`
> (rows, columns).
Q: What does `df[["name", "marks"]]` return?
- [ ] A Series
- [x] A DataFrame with two columns
- [ ] An error
> A list of column names returns a DataFrame.

## pandas-reading-data
Q: How does Pandas show an empty cell from a CSV?
- [ ] `0`
- [x] `NaN`
- [ ] `""`
> NaN marks a missing value.
Q: Which method shows column types and non-null counts?
- [x] `df.info()`
- [ ] `df.types()`
- [ ] `df.count_all()`
> Run it on every new dataset.
Q: Why use `index=False` in `to_csv`?
- [ ] To sort the rows
- [x] To avoid writing the row numbers as an extra column
- [ ] To compress the file
> Otherwise the index becomes a column in the file.

## pandas-selecting-data
Q: What is the difference between `loc` and `iloc`?
- [x] `loc` uses labels and names; `iloc` uses integer positions
- [ ] They are identical
- [ ] `iloc` is only for columns
> loc = label, iloc = integer location.
Q: In `df.loc[1:3]`, is row 3 included?
- [x] Yes
- [ ] No
- [ ] Only if the index is numeric
> Label slices with loc include the end.
Q: What's the safe way to change one cell?
- [ ] `df["marks"][3] = 61`
- [x] `df.loc[3, "marks"] = 61`
- [ ] `df.set(3, "marks", 61)`
> Chained brackets may change a copy instead of the table.

## pandas-filtering
Q: How do you keep rows where marks are above 70?
- [x] `df[df["marks"] > 70]`
- [ ] `df.filter(marks > 70)`
- [ ] `df["marks" > 70]`
> The condition creates a mask of True/False.
Q: What does `df["city"].isin(["Pune", "Delhi"])` check?
- [ ] City contains both words
- [x] City is one of the listed values
- [ ] City is missing
> isin matches any value in the list.
Q: Which counts how many rows match a condition?
- [ ] `(df["marks"] > 70).count()`
- [x] `(df["marks"] > 70).sum()`
- [ ] `df.count(df["marks"] > 70)`
> True counts as 1, so sum() counts the matches.

## pandas-cleaning-data
Q: How do you count missing values per column?
- [x] `df.isna().sum()`
- [ ] `df.missing()`
- [ ] `df.count()`
> isna() marks missing cells; sum() counts them.
Q: A fees column contains "1,200". How do you make it numeric?
- [x] `pd.to_numeric(df["fees"].str.replace(",", ""), errors="coerce")`
- [ ] `df["fees"].astype(int)` directly
- [ ] `df["fees"] + 0`
> Remove the commas first; coerce turns leftovers into NaN.
Q: What does `drop_duplicates()` remove?
- [ ] Missing values
- [x] Rows that are exact copies of an earlier row
- [ ] Repeated column names
> Run duplicated().sum() first to see how many.

## pandas-adding-columns-sorting
Q: How do you add a total column from qty and price?
- [x] `df["total"] = df["qty"] * df["price"]`
- [ ] `df.add("total", qty * price)`
- [ ] `df["total"] = sum(df)`
> Column maths works row by row.
Q: What does `value_counts()` give?
- [ ] The sum of a column
- [x] How many times each value appears
- [ ] The number of columns
> It's the fastest frequency table.
Q: Which labels rows "big" or "small" depending on total?
- [x] `np.where(df["total"] >= 1000, "big", "small")`
- [ ] `df.label("total")`
- [ ] `df["total"].big()`
> np.where(condition, if_true, if_false).

## pandas-groupby
Q: What are the three steps of groupby?
- [ ] Sort, filter, save
- [x] Split, apply, combine
- [ ] Load, clean, plot
> Rows are split into groups, a calculation is applied, results are combined.
Q: What does `df.groupby("city")["marks"].mean()` return?
- [x] The average marks for each city
- [ ] The average of all marks
- [ ] The number of cities
> One result per group.
Q: Which makes an Excel-style table with one category down and one across?
- [ ] `df.stack()`
- [x] `df.pivot_table(index=..., columns=..., values=...)`
- [ ] `df.cross()`
> Pivot tables summarise two categories at once.

## pandas-merge-project
Q: Which `how` keeps only keys found in both tables?
- [x] `inner`
- [ ] `left`
- [ ] `outer`
> inner = matches only.
Q: What does a left merge do with rows that have no match?
- [ ] Drops them
- [x] Keeps them and fills the missing columns with NaN
- [ ] Raises an error
> Every row of the left table is kept.
Q: When do you use `pd.concat` instead of `merge`?
- [x] To stack tables with the same columns, like two months of orders
- [ ] To match rows by a key
- [ ] To sort a table
> concat stacks; merge joins.
