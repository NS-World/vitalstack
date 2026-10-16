---
title: HTML Tables
slug: html-tables
subject: html
lesson: 12
difficulty: beginner
excerpt: Build tables with table, tr, th and td; add headers, captions and sections; merge cells with colspan and rowspan; and know when not to use a table.
---

Tables show data in rows and columns: timetables, price lists, results, comparisons. HTML tables are built from a handful of elements that fit together like a grid.

## A first table

```html
<table>
  <tr>
    <th>Name</th>
    <th>Language</th>
    <th>Score</th>
  </tr>
  <tr>
    <td>Asha</td>
    <td>Python</td>
    <td>92</td>
  </tr>
  <tr>
    <td>Rohan</td>
    <td>JavaScript</td>
    <td>87</td>
  </tr>
</table>
```

- `<table>` wraps the whole table.
- `<tr>` (table row) creates a row.
- `<th>` (table header) is a heading cell, shown bold and centred.
- `<td>` (table data) is a normal cell.

Every row should have the same number of cells. By default, tables have no borders. You'll add them with CSS; for a quick test you can add `border="1"` to the `<table>` tag.

## Captions

A `<caption>` gives the table a title. It must be the first thing inside `<table>`:

```html
<table border="1">
  <caption>Class test results, March</caption>
  <tr>
    <th>Name</th>
    <th>Score</th>
  </tr>
  <tr>
    <td>Meera</td>
    <td>95</td>
  </tr>
</table>
```

Screen readers announce the caption before reading the table, so users know what they're about to hear.

## Table sections: thead, tbody, tfoot

For larger tables, group the rows into sections:

```html
<table border="1">
  <caption>Monthly expenses</caption>
  <thead>
    <tr>
      <th>Item</th>
      <th>Cost (₹)</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Rent</td>
      <td>12,000</td>
    </tr>
    <tr>
      <td>Internet</td>
      <td>700</td>
    </tr>
    <tr>
      <td>Food</td>
      <td>6,500</td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <th>Total</th>
      <td>19,200</td>
    </tr>
  </tfoot>
</table>
```

- `<thead>` holds the header rows.
- `<tbody>` holds the main data.
- `<tfoot>` holds summary rows such as totals.

These sections make the structure clear, make styling easier, and let browsers repeat the header on every printed page.

## Row headers and the scope attribute

Headers can label rows as well as columns. `scope` tells assistive technology which way a header points:

```html
<table border="1">
  <tr>
    <th scope="col">Day</th>
    <th scope="col">Morning</th>
    <th scope="col">Evening</th>
  </tr>
  <tr>
    <th scope="row">Monday</th>
    <td>HTML</td>
    <td>Exercise</td>
  </tr>
  <tr>
    <th scope="row">Tuesday</th>
    <td>CSS</td>
    <td>Reading</td>
  </tr>
</table>
```

## Merging cells: colspan and rowspan

`colspan` makes a cell stretch across several columns; `rowspan` makes it stretch down several rows.

```html
<table border="1">
  <tr>
    <th>Time</th>
    <th>Monday</th>
    <th>Tuesday</th>
  </tr>
  <tr>
    <td>9:00</td>
    <td colspan="2">Team meeting (both days)</td>
  </tr>
  <tr>
    <td>10:00</td>
    <td rowspan="2">Workshop</td>
    <td>Coding</td>
  </tr>
  <tr>
    <td>11:00</td>
    <td>Review</td>
  </tr>
</table>
```

When a cell spans, the rows it covers have **fewer** `<td>` elements. In the last row above there are only two cells, because "Workshop" from the row before is still taking up the Monday column.

## When not to use a table

Long ago, people built whole page layouts out of tables. Don't. Tables are for **tabular data** only: information that naturally has rows and columns. For page layout, use CSS Flexbox and Grid (you'll learn both in the CSS course). Layout tables confuse screen readers and are very hard to make work on phones.

## Common mistakes

- **Different numbers of cells per row**, which makes columns misalign.
- **Using `<td>` for headings** with bold text instead of `<th>`.
- **Putting the `<caption>` anywhere except first** inside `<table>`.
- **Forgetting to remove cells** when using `colspan` or `rowspan`.
- **Using tables for layout.**

## Exercise

1. Build a table of your weekly timetable: days as rows, three time slots as columns.
2. Add a caption and `<thead>` / `<tbody>` sections.
3. Use `scope="row"` on the day names.
4. Merge two cells with `colspan` for an activity that lasts two slots.
5. Add a `<tfoot>` row that counts the number of study sessions.

## Summary

- `<table>`, `<tr>`, `<th>` and `<td>` build the grid.
- `<caption>`, `<thead>`, `<tbody>` and `<tfoot>` give it structure.
- `scope` clarifies header direction; `colspan` and `rowspan` merge cells.
- Use tables only for data, never for page layout.

Next, you'll meet the two general-purpose containers: div and span.
