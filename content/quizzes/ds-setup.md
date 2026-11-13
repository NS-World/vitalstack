## data-science-tools-overview
Q: What is a notebook "kernel"?
- [ ] The file that stores your notebook
- [x] A running Python that executes your cells and remembers variables
- [ ] The browser tab
> The page sends each cell to the kernel and shows what comes back.
Q: You can't install software on a lab computer. Which tool lets you run Python charts today?
- [x] Google Colab
- [ ] Anaconda
- [ ] VS Code
> Colab needs only a browser and a Google account.
Q: What does Anaconda give you?
- [ ] Only the Python language
- [x] Python plus hundreds of data packages and tools to manage them
- [ ] A cloud GPU
> It bundles pandas, NumPy, Matplotlib, Jupyter and more.

## install-anaconda-jupyter
Q: On Windows, "conda is not recognized". What is the usual fix?
- [ ] Reinstall Windows
- [x] Open "Anaconda Prompt" instead of the normal Command Prompt
- [ ] Install Python from python.org too
> Anaconda Prompt always knows where conda is.
Q: Why create a conda environment like `ds`?
- [x] Each project gets its own packages, so updates in one never break another
- [ ] It makes Python run faster
- [ ] Jupyter only works inside environments
> Environments keep projects separate and tidy.
Q: Which command starts the modern Jupyter interface?
- [ ] `conda start`
- [x] `jupyter lab`
- [ ] `python notebook`
> `jupyter notebook` starts the classic one.

## google-colab-step-by-step
Q: How do you install an extra package in Colab?
- [x] Run `!pip install name` in a cell
- [ ] It's impossible in Colab
- [ ] Email Google
> The `!` runs a terminal command; reinstall each new session.
Q: Where should you keep data files you need tomorrow?
- [ ] Upload them each time and hope
- [x] In Google Drive, mounted at /content/drive
- [ ] In a code comment
> Uploaded files are deleted when the session ends.
Q: When do you need the free GPU?
- [ ] For every pandas task
- [x] For deep learning (neural networks, image models)
- [ ] To draw charts
> CPU is fine for pandas and charts.

## jupyter-notebook-skills
Q: You press Esc, then B. What happens?
- [x] A new cell is added below
- [ ] The letter B is typed
- [ ] The notebook is saved
> Esc enters command mode, where single keys are commands.
Q: Why use "Restart and Run All" before sharing?
- [ ] It deletes your code
- [x] It proves the notebook works top to bottom, with fresh outputs
- [ ] It makes the file smaller
> Variables live in the kernel; running out of order can hide bugs.
Q: How do you write a heading in a Markdown cell?
- [ ] `<h>Heading`
- [x] `## Heading`
- [ ] `print("Heading")`
> Markdown uses # signs for headings.
