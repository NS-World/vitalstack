---
title: Jupyter Notebook Skills: Shortcuts, Markdown, Magic Commands and Exporting
slug: jupyter-notebook-skills
subject: ds-setup
lesson: 4
difficulty: beginner
excerpt: Work fast in Jupyter and Colab with keyboard shortcuts, Markdown notes and formulas, magic commands, restarting the kernel, exporting to HTML or PDF, and notebooks in VS Code.
---

You can run cells now. This lesson makes you fast and tidy: the shortcuts people use every day, notes with headings and formulas, a few "magic" commands, and the habits that make a notebook easy for anyone (including future you) to read.

## Two modes: editing and commanding

A notebook has two modes, and most confusion comes from mixing them up:

| Mode | How to enter | Looks like | What keys do |
|---|---|---|---|
| **Edit mode** | Press **Enter** or click inside a cell | Cursor blinking in the cell | Typing writes code |
| **Command mode** | Press **Esc** | Cell has a coloured bar, no cursor | Single keys run commands (below) |

## The shortcuts worth learning first

| Keys (command mode) | Action |
|---|---|
| **Shift + Enter** | Run the cell and move to the next one |
| **Ctrl + Enter** | Run the cell and stay on it |
| **A** / **B** | Insert a new cell **A**bove / **B**elow |
| **D, D** (press D twice) | Delete the cell |
| **Z** | Undo the cell delete |
| **M** | Turn the cell into a Markdown (text) cell |
| **Y** | Turn it back into a code cell |
| **Ctrl + S** | Save |

In **Colab**, most command shortcuts start with **Ctrl + M** first: **Ctrl + M, B** adds a cell below, **Ctrl + M, M** makes a text cell. **Tools → Keyboard shortcuts** lists them all.

## Notes with Markdown

Change a cell to Markdown (**M**) and write:

```text
## Sales analysis, June

**Goal:** find which city grew fastest.

- Data: `sales.csv`, 1,200 rows
- Steps: clean → group by city → chart

Growth rate: $g = \frac{new - old}{old} \times 100$
```

Run it and it turns into formatted text with a heading, bold words, a list and a neat formula (the part between `$` signs uses LaTeX, the standard way to write maths).

::tip Start every notebook with a Markdown cell that says what question you are answering and where the data comes from. It takes one minute and makes your notebook ten times easier to share in a portfolio.

## Cells run in the order YOU run them

The number on the left, like `[3]`, shows the order cells were run. Variables live in the kernel, not on the page, so running cells out of order can surprise you:

```python
price = 100
```

```python
price = price * 2
print(price)
```

Output:

```text
200
```

Run the second cell again and it prints `400`, because `price` is already 200 in the kernel. That's why the most useful habit in notebooks is:

**Kernel → Restart Kernel and Run All Cells** (Colab: **Runtime → Restart and run all**).

If the notebook still works from top to bottom after that, anyone can rerun it and get the same results.

## Magic commands

Lines starting with `%` (one line) or `%%` (whole cell) are Jupyter "magics", not Python. A few useful ones:

```text
%timeit sum(range(1000))      # how long a line takes, measured many times
%%time                        # (first line of a cell) time the whole cell once
%pwd                          # which folder the notebook is in
%ls                           # list files in that folder
!pip install plotly           # ! runs a terminal command
%matplotlib inline            # show charts under cells (the default today)
```

## Seeing data nicely

- The **last line** of a cell is displayed automatically: write `df.head()` on its own line instead of `print(df.head())` to get a formatted table.
- `df.info()` shows columns, types and missing values; `df.describe()` gives quick statistics.
- To see all columns of a wide table: `pd.set_option("display.max_columns", None)`.

## Exporting and sharing

| You want | How |
|---|---|
| A web page anyone can open | **File → Save and Export Notebook As → HTML** |
| A PDF | **File → Print** in the browser, then "Save as PDF" (simplest), or export as PDF if LaTeX is installed |
| Plain Python script | **File → Save and Export Notebook As → Executable Script** (`.py`) |
| From the terminal | `jupyter nbconvert --to html analysis.ipynb` |

Before sharing: **restart and run all** so the outputs are fresh, delete scratch cells, and make sure no passwords or API keys are in the notebook.

## Notebooks in VS Code

VS Code opens `.ipynb` files too, which is handy once your projects grow:

1. Install the **Python** and **Jupyter** extensions (Extensions icon on the left).
2. Open a folder, create `analysis.ipynb`.
3. Click **Select Kernel** (top right) and choose your `ds` conda environment.
4. Run cells with **Shift + Enter**, exactly like Jupyter.

You also get autocomplete, a variable viewer and Git built in.

::hint Quick check | Your notebook worked yesterday, but today the cell `df.groupby("city")` says `NameError: name 'df' is not defined`. What happened? The kernel restarted overnight, so `df` is gone. Run the cells above it again, or use **Restart and Run All**.

## Summary

- **Esc** = command mode (single-key shortcuts), **Enter** = edit mode.
- Learn **Shift + Enter**, **A**, **B**, **D D**, **M**, **Y** first; in Colab put **Ctrl + M** before the letter.
- Markdown cells hold your story: headings, lists and LaTeX formulas.
- Variables live in the kernel: **Restart and Run All** before trusting or sharing results.
- Export to HTML, PDF or `.py`, and open notebooks in VS Code for bigger projects.
