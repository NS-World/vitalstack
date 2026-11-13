---
title: Install Anaconda and Open Jupyter Notebook (Windows, Mac, Linux)
slug: install-anaconda-jupyter
subject: ds-setup
lesson: 2
difficulty: beginner
excerpt: Install Anaconda step by step, create a clean environment for data science, open Jupyter Notebook or JupyterLab, and draw your first chart in a notebook.
---

Anaconda gives you Python plus the main data science packages in one installer, so you don't have to install pandas, NumPy, Matplotlib and Jupyter one by one. In this lesson you install it, create a tidy "environment" for your projects, open Jupyter and draw your first chart.

## Step 1: Download

1. Open **anaconda.com/download** in your browser.
2. The site may ask for your email. You can enter it, or look for the **Skip registration** link.
3. Download the installer for your system:
   - **Windows:** 64-bit graphical installer (`.exe`).
   - **macOS:** choose **Apple silicon** (M1, M2, M3 and newer) or **Intel**. Not sure? Apple menu → About This Mac.
   - **Linux:** the `.sh` installer.

The download is large (about 1 GB), so use Wi-Fi.

::tip Anaconda is free for individuals, students and small organisations. Large companies (200+ people) may need a paid licence, so at work ask IT first. A small free alternative is **Miniconda** or **Miniforge**: the same `conda` tool, but you install only the packages you need.

## Step 2: Install

### Windows

1. Double-click the `.exe` and click **Next**, then **I Agree**.
2. Choose **Just Me (recommended)**.
3. Keep the suggested folder (it should have **no spaces** in the path).
4. On the options screen, leave **"Add Anaconda to my PATH"** unticked (recommended). You will use the **Anaconda Prompt** instead, which always works.
5. Click **Install** and wait a few minutes, then **Finish**.

### macOS

1. Double-click the `.pkg` and follow the steps (**Continue**, **Agree**, **Install**).
2. Enter your Mac password when asked.

### Linux

```bash
cd ~/Downloads
bash Anaconda3-*-Linux-x86_64.sh
# press Enter to read the licence, type "yes", accept the folder, then "yes" to initialise conda
source ~/.bashrc
```

## Step 3: Check it works

- **Windows:** Start menu → **Anaconda Prompt**.
- **macOS / Linux:** open **Terminal**. You should see `(base)` at the start of the line.

Type:

```bash
conda --version
python --version
```

You should see something like `conda 24.x` and `Python 3.12.x`. Any version numbers are fine.

::hint Stuck? | "conda is not recognized" on Windows means you opened the normal Command Prompt. Open **Anaconda Prompt** from the Start menu instead.

## Step 4: Create a clean environment

An **environment** is a separate box with its own Python and packages. Keeping one per project means one project's updates never break another. Create one called `ds`:

```bash
conda create -n ds python=3.11 pandas numpy matplotlib seaborn scikit-learn jupyterlab -y
conda activate ds
```

The line now starts with `(ds)`. Every time you open a new prompt, run `conda activate ds` first.

Need another package later? With the environment active:

```bash
conda install -c conda-forge plotly    # from conda-forge, a big community channel
pip install some-package               # pip also works inside a conda environment
```

## Step 5: Open Jupyter

```bash
jupyter lab          # the modern interface (recommended)
jupyter notebook     # the classic, simpler interface
```

Your browser opens at `http://localhost:8888`. The address means the notebook server runs **on your own computer**, so your files stay with you. Keep the prompt window open while you work; closing it stops Jupyter.

Prefer clicking? Open **Anaconda Navigator** from the Start menu (or Applications), choose the `ds` environment at the top, and click **Launch** under JupyterLab.

## Step 6: Your first notebook and chart

1. In JupyterLab, click **Python 3 (ipykernel)** under **Notebook**.
2. Rename it: right-click the tab → **Rename** → `first-chart.ipynb`.
3. Paste this into the first cell and press **Shift + Enter** to run it:

```python
import matplotlib.pyplot as plt

days = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"]
minutes = [20, 35, 15, 40, 30, 60, 45]

fig, ax = plt.subplots(figsize=(7, 3.5))
ax.bar(days, minutes, color="#0a7d4f")
ax.axhline(30, color="tab:orange", linestyle="--", label="Daily goal: 30 min")
ax.set_title("My study minutes this week")
ax.set_ylabel("Minutes")
ax.legend()
plt.show()
```

::figure ds-setup-first-chart.svg | Bar chart of study minutes for each day of the week with a dashed line at the 30-minute daily goal

The chart appears right under the cell. That's the magic of notebooks: code, result and notes together.

4. Save with **Ctrl + S** (or **Cmd + S** on a Mac). The file is saved in the folder shown on the left.

## When things go wrong

| Problem | Fix |
|---|---|
| Your `ds` environment doesn't appear as a kernel in Jupyter | With `ds` active, run `python -m ipykernel install --user --name ds --display-name "Python (ds)"`, then refresh Jupyter |
| `ModuleNotFoundError: No module named 'seaborn'` | You are in the wrong environment or kernel. Activate `ds`, or install the package there |
| The kernel keeps dying | You may be out of memory: close other notebooks, or work with a smaller part of the data |
| Port 8888 is busy | Jupyter picks the next free port (8889…) by itself; use the link printed in the prompt |
| Installer fails on Windows | Make sure the install path has no spaces or special characters, and install for "Just Me" |

## Summary

- Download Anaconda from anaconda.com/download and install it for **Just Me**, without adding it to PATH on Windows.
- Use **Anaconda Prompt** (Windows) or **Terminal** (Mac/Linux): `conda --version` checks the install.
- Create an environment with `conda create -n ds ...` and switch to it with `conda activate ds`.
- Start Jupyter with `jupyter lab`, create a notebook, and run cells with **Shift + Enter**.
