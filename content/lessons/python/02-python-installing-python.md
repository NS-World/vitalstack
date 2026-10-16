---
title: Installing Python
slug: python-installing-python
subject: python
lesson: 2
difficulty: beginner
excerpt: Install Python 3 on Windows, macOS or Linux, check it works, use the interactive shell, run your first .py file, and set up VS Code for Python.
---

To write real programs, you'll want Python on your own computer. This lesson walks you through installing it on Windows, macOS and Linux, running code in two different ways, and setting up a good free editor.

## Step 1: Download and install

### Windows

1. Go to **python.org/downloads** and click the big **Download Python 3.x** button.
2. Run the installer.
3. **Important:** tick **"Add python.exe to PATH"** at the bottom of the first screen. Without it, typing `python` in the terminal won't work.
4. Click **Install Now**.

Python is also available in the Microsoft Store, which sets up the PATH automatically.

### macOS

1. Download the macOS installer from **python.org/downloads** and run it.
2. Or, if you use Homebrew: `brew install python`.

macOS may include an older system Python used by the operating system. Always use the one you installed, with the command `python3`.

### Linux

Most distributions already include Python 3. If not:

```bash
sudo apt install python3 python3-pip python3-venv   # Ubuntu / Debian
sudo dnf install python3                            # Fedora
```

## Step 2: Check it works

Open a terminal (Windows: search for **Terminal** or **Command Prompt**; macOS: **Terminal**) and type:

```bash
python --version
```

On macOS and Linux, use `python3`:

```bash
python3 --version
```

You should see something like `Python 3.13.1`. Any version from 3.10 upward works for this course. If you see "command not found" or a Microsoft Store window on Windows, reinstall and make sure "Add to PATH" is ticked.

From here on, we'll write `python`; use `python3` if that's what works on your computer.

## Way 1: The interactive shell (REPL)

Type `python` alone and press Enter:

```text
>>> 2 + 2
4
>>> "hello".upper()
'HELLO'
>>> name = "Asha"
>>> f"Hi {name}"
'Hi Asha'
>>> exit()
```

The `>>>` prompt means Python is waiting for a line. It runs each line immediately and shows the result. This is called a **REPL** (Read–Eval–Print Loop) and is perfect for quick experiments: "what does this function return?". Type `exit()` (or press Ctrl+D on macOS/Linux, Ctrl+Z then Enter on Windows) to leave.

## Way 2: Python files

Real programs live in files ending in `.py`. Create a folder, for example `python-course`, and a file inside it called `hello.py`:

```python
name = input("What's your name? ")
print(f"Nice to meet you, {name}!")
print("You just ran your first Python file.")
```

In the terminal, go to the folder and run it:

```bash
cd python-course
python hello.py
```

Type your name when asked. `input()` waits for the user to type something and press Enter.

## Step 3: Set up an editor (VS Code)

You can write Python in any text editor, but a code editor helps a lot. **Visual Studio Code** (VS Code) is free and the most popular choice:

1. Download it from **code.visualstudio.com** and install it.
2. Open VS Code → **Extensions** (the squares icon on the left) → search **Python** → install the one by **Microsoft**.
3. **File → Open Folder** → choose your `python-course` folder.
4. Open `hello.py` and click the **▶ Run** button in the top-right corner.

The output appears in the built-in terminal at the bottom. The Python extension also gives you:

- Colour highlighting and error underlines as you type.
- Autocomplete for functions and variables.
- A debugger to run code step by step.

**PyCharm Community** (free) is another excellent Python-specific editor.

## Step 4 (optional): Jupyter notebooks

Data scientists often use **notebooks**: documents that mix code, results, charts and notes. You can try them with no installation at **colab.research.google.com** (Google Colab), or in VS Code with the Jupyter extension. They're great for experiments; for programs and scripts, `.py` files are better.

## Where pip fits in

Python comes with **pip**, a tool for installing extra packages (libraries others have written):

```bash
python -m pip --version
```

You'll use it in the "pip and Packages" lesson. For now, just check that this command prints a version.

## Troubleshooting

| Problem | Fix |
|---|---|
| `'python' is not recognized` (Windows) | Reinstall with "Add python.exe to PATH" ticked, or use `py` instead of `python` |
| `python` opens the Microsoft Store | Use `py`, or turn off the "App execution aliases" for python in Windows settings |
| `command not found: python` (Mac/Linux) | Use `python3` |
| VS Code runs the wrong Python | Press Ctrl+Shift+P → "Python: Select Interpreter" → choose the version you installed |
| `can't open file 'hello.py'` | You're in the wrong folder; use `cd` to go to the folder containing the file |

## Common mistakes

- **Forgetting "Add to PATH"** on Windows.
- **Typing Python code into the terminal** without starting `python` first (or the reverse: typing `python hello.py` inside the `>>>` shell).
- **Saving files as `hello.py.txt`** with Notepad. Use a code editor.
- **Naming your file after a module**, like `random.py` or `math.py`. It hides Python's own module of that name and causes confusing errors.

## Exercise

1. Install Python and check the version in the terminal.
2. In the REPL, calculate `2 ** 100` (Python handles very big numbers).
3. Create `age.py` that asks for the user's birth year with `input()` and prints their approximate age. (Hint: `int(input(...))` converts the answer to a number; you'll learn why in lesson 5.)
4. Install VS Code with the Python extension and run `age.py` from it.

## Summary

- Install Python 3 from python.org; on Windows, tick "Add python.exe to PATH".
- Check with `python --version` (or `python3`).
- The REPL (`>>>`) runs lines instantly; `.py` files hold real programs: `python file.py`.
- VS Code with the Python extension is a great free editor.

Next, you'll learn the basic syntax rules of Python.
