## powerbi-data-model
Q: In a star schema, the central table holds...
- [x] Facts like sales amounts, linked to dimension tables
- [ ] Only product names
- [ ] Random notes
> Facts in the middle, dimensions (date, product, store) around it.
Q: Power Query is used to...
- [x] Clean and shape data before it loads
- [ ] Draw charts only
- [ ] Send emails
> It records repeatable transformation steps.
Q: Why add a separate date table?
- [x] For reliable time intelligence like month-to-date and year-over-year
- [ ] Power BI can't read dates otherwise
- [ ] To slow down reports
> A proper date table makes DAX time functions work correctly.

## powerbi-dax
Q: A DAX measure is...
- [x] A calculation evaluated in the current filter context
- [ ] A fixed column stored per row
- [ ] A chart type
> Measures recalculate as slicers and filters change.
Q: CALCULATE is used to...
- [x] Change the filter context of a calculation
- [ ] Rename columns
- [ ] Import data
> e.g. CALCULATE([Sales], Region = "West").
Q: Why prefer DIVIDE(a, b) over a / b?
- [x] It handles division by zero safely
- [ ] It is the only way to divide
- [ ] It rounds results
> DIVIDE returns blank (or a value you choose) instead of an error.

## powerbi-interactive-reports
Q: Slicers let report users...
- [x] Filter the visuals on a page interactively
- [ ] Edit the data source
- [ ] Delete measures
> They're on-page filters anyone can use.
Q: Drill-through lets a user...
- [x] Jump from a summary to a detail page for one item
- [ ] Export to PDF only
- [ ] Change the theme
> Right-click a product to see its own detail page.
Q: A good report page shows...
- [x] A few key numbers and charts that answer one question
- [ ] Every column of the dataset
- [ ] As many colours as possible
> Focus helps readers act on the data.
