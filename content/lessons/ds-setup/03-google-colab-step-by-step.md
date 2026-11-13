---
title: Google Colab Step by Step: Run Python in Your Browser for Free
slug: google-colab-step-by-step
subject: ds-setup
lesson: 3
difficulty: beginner
excerpt: Open Google Colab, run code and text cells, install packages, upload a CSV, connect Google Drive, switch on a free GPU, and save or share your notebook.
---

Google Colab gives you a Jupyter notebook that runs on Google's computers. You need only a browser and a Google account: nothing to install, and it works on any laptop, even a slow one. Most data science packages are already there.

## Step 1: Open Colab

1. Go to **colab.research.google.com** and sign in with your Google account.
2. Click **New notebook** (or **File → New notebook in Drive**).
3. Click the title `Untitled0.ipynb` at the top left and rename it, for example `colab-practice.ipynb`.

Your notebook is saved in your Google Drive, in a folder called **Colab Notebooks**.

## Step 2: The screen

| Part | What it does |
|---|---|
| **+ Code** | Adds a code cell (Python) |
| **+ Text** | Adds a text cell for notes, headings and formulas (Markdown) |
| ▶ next to a cell | Runs the cell (same as **Shift + Enter**) |
| **Files** (folder icon on the left) | Files on the Colab computer, and where uploads appear |
| **Runtime** menu | Restart, run all cells, change to GPU |
| **RAM / Disk** meter (top right) | How much of the free machine you are using |

The first time you run a cell, Colab connects to a machine. That takes a few seconds.

## Step 3: Run your first cells

Type this in a code cell and press **Shift + Enter**:

```python
import sys
import pandas as pd

print("Python", sys.version.split()[0])
print("pandas", pd.__version__)
```

You will see the versions Colab uses today. pandas, NumPy, Matplotlib, Seaborn and scikit-learn are already installed.

## Step 4: Install a package

Start a line with `!` to run a terminal command inside a cell:

```bash
!pip install plotly
```

Packages you install this way disappear when the session ends, so keep the `!pip install` line at the top of the notebook and run it each time.

## Step 5: Bring in your data

There are three common ways.

### a) Upload a file (quick, temporary)

Click the **Files** icon → **Upload** (the page icon with an arrow), and choose your CSV. Or in code:

```python
from google.colab import files

uploaded = files.upload()          # opens a "Choose file" button
import pandas as pd
df = pd.read_csv(next(iter(uploaded)))
df.head()
```

::tip Uploaded files are deleted when the session ends (usually after about 90 minutes idle, or at most around 12 hours). For anything you want to keep, use Google Drive.

### b) Google Drive (keeps your files)

```python
from google.colab import drive

drive.mount("/content/drive")      # click the link and allow access
import pandas as pd
df = pd.read_csv("/content/drive/MyDrive/data/sales.csv")
```

Your Drive appears under `/content/drive/MyDrive/`. Files you save there stay after the session ends.

### c) From a web address

If the CSV is online, read it straight from the link:

```text
df = pd.read_csv("https://example.com/data/sales.csv")
```

## Step 6: Draw a chart

Charts show up under the cell, just like in Jupyter. Try this (it builds a small table in code, so it runs anywhere):

```python
import io
import pandas as pd
import matplotlib.pyplot as plt

csv = io.StringIO("""month,orders
Jan,120
Feb,135
Mar,150
Apr,145
May,170
Jun,190
""")
df = pd.read_csv(csv)

fig, ax = plt.subplots(figsize=(7, 3.5))
ax.plot(df["month"], df["orders"], marker="o", color="#1d5fd1")
ax.fill_between(df["month"], df["orders"], alpha=0.12, color="#1d5fd1")
ax.set_title("Orders grew 58% in six months")
ax.set_ylabel("Orders")
ax.grid(alpha=0.3)
plt.show()
```

::figure ds-setup-colab-line.svg | Line chart of monthly orders rising from 120 in January to 190 in June, with the area under the line lightly shaded

## Step 7: Switch on a free GPU (for deep learning)

1. **Runtime → Change runtime type**.
2. Under **Hardware accelerator**, choose **T4 GPU** and click **Save**.
3. Check it:

```bash
!nvidia-smi
```

You only need a GPU for deep learning (neural networks, image models). For pandas and charts, the normal CPU is fine, and it keeps your free GPU time for when you need it. Free GPU time is limited and not always available.

## Step 8: Save and share

| You want to | Do this |
|---|---|
| Keep your work | It saves to Drive automatically; **File → Save** to be sure |
| Share with a friend or mentor | **Share** (top right) → add their email, or copy a link |
| Put it on GitHub | **File → Save a copy in GitHub** |
| Get a file | **File → Download → .ipynb** (notebook) or **.py** (plain Python) |
| Open it in Jupyter on your laptop | Download the `.ipynb` and open it in JupyterLab |

## Colab's limits (good to know)

- The machine is **temporary**: when the session ends, variables and uploaded files are gone. **Runtime → Run all** rebuilds everything from your code.
- Sessions disconnect when idle and have a maximum length. Long training jobs should save checkpoints to Drive.
- Free RAM is around 12 GB: plenty for learning, but very large datasets need sampling or a paid plan.
- Don't paste passwords or API keys into cells you share. Colab has a **Secrets** panel (key icon on the left) for keys.

::hint Quick check | You uploaded `marks.csv`, came back the next day and get `FileNotFoundError`. Why? Uploaded files live on the temporary machine and were deleted. Put the file in Google Drive and read it from `/content/drive/MyDrive/`.

## Summary

- Colab = free Jupyter notebooks in your browser, saved in Google Drive, with the main packages pre-installed.
- Run cells with **Shift + Enter**; install extra packages with `!pip install name`.
- Upload files for quick work, mount **Google Drive** for files you want to keep, or read CSVs from a URL.
- **Runtime → Change runtime type → T4 GPU** turns on a free GPU for deep learning.
- Sessions are temporary: keep data in Drive and rebuild with **Run all**.
