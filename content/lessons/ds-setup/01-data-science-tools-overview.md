---
title: Your Data Science Toolkit: Python, Anaconda, Jupyter and Colab
slug: data-science-tools-overview
subject: ds-setup
lesson: 1
difficulty: beginner
excerpt: What Python, Anaconda, Jupyter Notebook, Google Colab and VS Code each do, how they fit together, and which one to start with on your computer or phone.
---

Before your first chart or model, you need a place to write and run Python. Beginners often get stuck here because the names sound alike: Python, Anaconda, Jupyter, Colab. This lesson explains each one in plain words, shows how they fit together, and helps you pick the right start in two minutes.

## The five names, in one line each

| Tool | What it is | Think of it as |
|---|---|---|
| **Python** | The programming language itself | The language you speak |
| **Anaconda** | A free bundle: Python + hundreds of data packages + tools to manage them | A ready-made kitchen with every pan already there |
| **Jupyter Notebook** | A page in your browser where you run code in small blocks and see the result (tables, charts) right under it | A lab notebook that runs code |
| **Google Colab** | Jupyter notebooks that run on Google's computers, free, in your browser | A lab notebook in the cloud |
| **VS Code** | A free code editor that can also open notebooks | A workshop for bigger projects |

## How they fit together

```text
You type code
   │
   ▼
Notebook page (Jupyter on your laptop, or Colab in the cloud)
   │  sends each cell to
   ▼
Kernel = a running Python
   │  uses packages like
   ▼
pandas · NumPy · Matplotlib · Seaborn · scikit-learn
   │
   ▼
Result appears under the cell: a number, a table or a chart
```

The **kernel** is the important idea: it is a Python program running in the background that remembers your variables. Each time you run a cell, the notebook sends that code to the kernel, and the kernel sends back the output.

::tip A notebook file ends in `.ipynb` ("IPython notebook"). It stores your code, your notes and the last outputs together, so you can share one file and others see your charts without running anything.

## Where data science notebooks came from

- **2001:** Fernando Pérez, a physics student, created **IPython**, a friendlier interactive Python shell.
- **2011:** the IPython Notebook arrives: code, text and charts in one page in the browser.
- **2014:** the notebook part becomes **Project Jupyter**, named after three languages it supports: **Ju**lia, **Pyt**hon and **R**.
- **2017:** Google opens **Colaboratory (Colab)** to everyone: Jupyter notebooks in the cloud, with free access to GPUs.
- **Today:** notebooks are the standard tool for data analysis, teaching and machine learning experiments.

## Which one should you start with?

| Your situation | Start with |
|---|---|
| Any laptop, just want to try, nothing to install | **Google Colab** (lesson 3) |
| Phone or tablet only | **Google Colab** in the browser (fine for reading and small edits) |
| Your own Windows, Mac or Linux laptop, want to work offline | **Anaconda + Jupyter** (lesson 2) |
| Office laptop where you can't install software | **Colab**, or ask IT for Anaconda / Miniconda |
| Building an app, website or bigger project | **VS Code** with the Python and Jupyter extensions |
| Need a GPU for deep learning, no money for one | **Colab** (free GPU, with time limits) |

::hint Quick check | You have a college lab computer where you can't install anything, and you want to plot a chart today. Which tool? Colab: it only needs a browser and a Google account.

## What you'll be able to do after this path

1. Install Anaconda, create a clean environment and open Jupyter.
2. Use Google Colab: run cells, upload a CSV, connect Google Drive and switch on a GPU.
3. Work fast in notebooks: shortcuts, Markdown notes, magic commands and exporting your work.

Then you are ready for NumPy, Pandas and the Data Visualization lessons, where every example can be pasted straight into your notebook.

## Summary

- **Python** is the language; **Anaconda** is Python plus the data packages; **Jupyter** and **Colab** are notebooks that run Python cell by cell; **VS Code** is an editor for bigger projects.
- A notebook sends each cell to a **kernel** (a running Python) and shows the result under the cell.
- No installation possible? Use **Colab**. Own laptop and want offline work? Use **Anaconda + Jupyter**.
