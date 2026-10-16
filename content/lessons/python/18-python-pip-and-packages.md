---
title: Python pip and Packages
slug: python-pip-and-packages
subject: python
lesson: 18
difficulty: intermediate
excerpt: Install third-party packages from PyPI with pip, isolate projects with virtual environments, record dependencies in requirements.txt, and choose packages safely.
---

Python's standard library is big, but the real superpower is the **ecosystem**: over half a million free packages on **PyPI** (the Python Package Index) for web apps, data analysis, AI, automation and more. **pip** is the tool that installs them. In this lesson you'll install packages the professional way, inside virtual environments.

## What is a package?

A package is code someone else wrote and published so you can use it with `import`. Examples:

| Package | Used for |
|---|---|
| `requests` | Calling web APIs |
| `pandas` | Data analysis with tables |
| `numpy` | Fast maths on arrays |
| `matplotlib` | Charts |
| `flask`, `django`, `fastapi` | Web applications and APIs |
| `beautifulsoup4` | Reading data out of HTML pages |
| `openpyxl` | Excel files |
| `pytest` | Testing |

## Step 1: Create a virtual environment

If you install every package into your main Python, different projects soon need different versions of the same package and break each other. A **virtual environment** (venv) is a private folder of packages for **one project**.

In your project folder:

```bash
python -m venv .venv
```

This creates a `.venv` folder containing a copy of Python and an empty set of packages. (Use `python3` on macOS and Linux.)

## Step 2: Activate it

```bash
# Windows (Command Prompt or PowerShell)
.venv\Scripts\activate

# macOS / Linux
source .venv/bin/activate
```

Your prompt now starts with `(.venv)`. While it's active, `python` and `pip` refer to the environment's versions, and packages install into `.venv` only. Type `deactivate` to leave.

VS Code detects `.venv` automatically and offers to use it; if not, press Ctrl+Shift+P → **Python: Select Interpreter** and choose the `.venv` one.

(On Windows PowerShell, if activation is blocked, run `Set-ExecutionPolicy -Scope CurrentUser RemoteSigned` once.)

## Step 3: Install packages

```bash
pip install requests
```

pip downloads `requests` and everything it depends on from PyPI. Then use it in your code:

```python
import requests

response = requests.get("https://jsonplaceholder.typicode.com/todos/1", timeout=10)
print(response.status_code)
print(response.json()["title"])
```

### Useful pip commands

```bash
pip install requests pandas      # install several
pip install "requests==2.32.3"   # an exact version
pip install --upgrade requests   # upgrade to the latest
pip uninstall requests           # remove
pip list                         # what's installed in this environment
pip show requests                # details: version, dependencies, location
```

If `pip` isn't found, use `python -m pip install …`, which always uses the pip belonging to the Python you're running.

## Step 4: Record your dependencies

So others (or you, on another computer or a server) can recreate the environment:

```bash
pip freeze > requirements.txt
```

`requirements.txt` lists every installed package with its exact version:

```text
certifi==2024.8.30
charset-normalizer==3.4.0
idna==3.10
requests==2.32.3
urllib3==2.2.3
```

To install everything from it in a fresh environment:

```bash
python -m venv .venv
source .venv/bin/activate          # or the Windows command
pip install -r requirements.txt
```

This is the standard way to share Python projects. Commit `requirements.txt` to Git, but **not** the `.venv` folder: add `.venv/` to your `.gitignore`.

Modern projects often use a `pyproject.toml` file and tools like **uv** or **Poetry**, which do the same job faster with more features. The ideas (an isolated environment plus a list of dependencies) are the same.

## Choosing packages safely

Anyone can publish to PyPI, so choose carefully:

- **Check the name exactly.** Attackers publish look-alike packages ("requets", "python-dateutils") hoping for typos. This is called typosquatting.
- **Look at the project page** on pypi.org: recent releases, a link to source code, documentation, and how widely it's used.
- **Prefer well-known packages** for common tasks.
- **Pin versions** in requirements.txt, so an update can't suddenly change behaviour.
- **Never run `sudo pip install`** on Linux/macOS; it can break the system Python. Use a venv.

## Example: a tiny weather-style script with requests

```python
import requests

def get_user(user_id):
    url = f"https://jsonplaceholder.typicode.com/users/{user_id}"
    try:
        response = requests.get(url, timeout=10)
        response.raise_for_status()      # raises an error for 4xx/5xx responses
    except requests.RequestException as error:
        print("Request failed:", error)
        return None
    return response.json()

user = get_user(1)
if user:
    print(f"{user['name']} works at {user['company']['name']}")
```

You'll go deeper into APIs and JSON in the next lesson.

## Example: a quick chart with matplotlib

```text
pip install matplotlib
```

```python
import matplotlib
matplotlib.use("Agg")               # draw to a file, no window needed
import matplotlib.pyplot as plt

months = ["Jan", "Feb", "Mar", "Apr"]
sales = [12000, 15500, 14200, 18900]

plt.bar(months, sales, color="#2563eb")
plt.title("Monthly sales (₹)")
plt.savefig("sales.png", dpi=120)
print("Saved sales.png")
```

A few lines to a chart image. This is why Python is so popular for data work.

## Common mistakes

- **Installing without activating the venv**, so packages land in the wrong Python.
- **`ModuleNotFoundError`** because VS Code uses a different interpreter than the one where you installed the package.
- **Committing `.venv`** to Git (it's large and machine-specific).
- **Typos in package names.**
- **Naming your own file `requests.py`**, which hides the real package.

## Exercise

1. Create a folder `api-practice`, make a virtual environment and activate it.
2. Install `requests` and print the titles of the first 5 posts from `https://jsonplaceholder.typicode.com/posts`.
3. Run `pip freeze > requirements.txt` and look at the file.
4. Delete `.venv`, recreate it, and reinstall from `requirements.txt`.
5. Install `matplotlib` and make a line chart of your weekly study hours.

## Summary

- PyPI hosts hundreds of thousands of packages; `pip install name` installs them.
- Use a virtual environment per project: `python -m venv .venv`, then activate it.
- `pip freeze > requirements.txt` records versions; `pip install -r requirements.txt` restores them.
- Check package names and sources carefully, and never install with sudo.

Next, you'll work with JSON data and web APIs.
