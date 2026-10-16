---
title: Git and GitHub for Beginners: A Complete Practical Guide
slug: git-and-github-for-beginners
category: programming-development
tags: git, github, version control
order: 9
excerpt: Learn version control from scratch: what Git is, installing and configuring it, commits, branches, merging, pushing to GitHub, .gitignore, and fixing common mistakes.
---

**Short answer:** **Git** is a tool that records snapshots of your project over time, so you can see what changed, undo mistakes and work on features safely. **GitHub** is a website that stores Git projects online so you can back them up, share them and collaborate. Every developer job uses them.

This guide takes you from zero to a project on GitHub, with the commands you'll use daily.

## Why version control?

Without Git, projects end up like this:

```text
website-final.html
website-final-v2.html
website-final-v2-REALLY-final.html
website-final-v2-fixed-menu.html
```

With Git, you have one set of files plus a complete history: every change, who made it, when and why. You can go back to any point, try risky ideas on a separate branch, and combine work from several people.

## Key ideas

- **Repository (repo):** a project folder tracked by Git.
- **Commit:** a saved snapshot of your project, with a message describing the change.
- **Branch:** an independent line of work, e.g. a new feature, that doesn't affect the main version until you merge it.
- **Merge:** combining the changes from one branch into another.
- **Remote:** a copy of the repository somewhere else, usually on GitHub.
- **Push / pull:** sending your commits to the remote / getting new commits from it.

## Step 1: Install and configure Git

Download Git from **git-scm.com** (Windows), or use `brew install git` (macOS) or `sudo apt install git` (Linux). Check it:

```bash
git --version
```

Tell Git who you are (this appears in your commits):

```bash
git config --global user.name "Your Name"
git config --global user.email "you@example.com"
git config --global init.defaultBranch main
```

Use the email of your GitHub account (or GitHub's private "noreply" email, from your GitHub email settings, if you don't want your address public).

## Step 2: Create a repository

```bash
mkdir my-website
cd my-website
git init
```

`git init` creates a hidden `.git` folder where all the history is stored. Now create a file, `index.html`, with a few lines of HTML.

## Step 3: The basic cycle: status, add, commit

```bash
git status
```

Git shows `index.html` as **untracked**: it sees the file but isn't saving its history yet.

```bash
git add index.html
git commit -m "Add home page"
```

- `git add` puts changes in the **staging area**: the list of changes to include in the next commit. `git add .` stages everything in the folder.
- `git commit -m "message"` saves the snapshot with a message.

The everyday rhythm is: **edit → `git status` → `git add` → `git commit`**.

### Writing good commit messages

Describe **what** the change does, in the present tense, short and specific:

```text
Good:  Add contact form validation
Good:  Fix menu overlapping logo on mobile
Bad:   update
Bad:   asdf
Bad:   changes
```

Commit small, logical units of work, ideally each time something works.

## Step 4: View history and changes

```bash
git log --oneline          # list of commits
git diff                   # changes not yet staged
git diff --staged          # changes staged for the next commit
git show <commit-id>       # what a specific commit changed
```

## Step 5: Branches

Branches let you work on something without risking the main version:

```bash
git switch -c dark-mode        # create and switch to a new branch
# ...edit files, then:
git add .
git commit -m "Add dark mode toggle"

git switch main                # back to main: dark mode changes aren't here
git merge dark-mode            # bring them in
git branch -d dark-mode        # delete the merged branch
```

On teams, every feature or fix gets its own branch, and is merged after review.

### Merge conflicts

If two branches change the same lines differently, Git can't decide which to keep and marks a **conflict** in the file:

```text
<<<<<<< HEAD
<h1>Welcome to my site</h1>
=======
<h1>Hello and welcome!</h1>
>>>>>>> new-heading
```

Edit the file to keep what you want (deleting the marker lines), then:

```bash
git add index.html
git commit -m "Merge new heading"
```

Conflicts look scary the first time but are routine.

## Step 6: Put it on GitHub

1. Create a free account at **github.com**.
2. Click **New repository**, name it (e.g. `my-website`), and **don't** add a README (your project already has files).
3. GitHub shows commands like these; run them in your project folder:

```bash
git remote add origin https://github.com/your-username/my-website.git
git push -u origin main
```

`origin` is the conventional name for your main remote. `-u` remembers it, so next time just type `git push`.

GitHub will ask you to sign in. For the command line, use **GitHub CLI** (`gh auth login`), Git Credential Manager (included with Git for Windows), or SSH keys. Your regular password won't work for pushes; GitHub uses tokens or keys for security.

### Getting changes: pull and clone

```bash
git pull                                              # get new commits from GitHub
git clone https://github.com/someone/project.git      # copy an existing repository
```

## Step 7: .gitignore

Some files should never be committed: dependencies, build output, editor settings and especially **secrets**. List them in a `.gitignore` file:

```text
# Dependencies
node_modules/
.venv/

# Environment variables and secrets
.env

# System and editor files
.DS_Store
Thumbs.db
.vscode/

# Build output and logs
dist/
*.log
```

**Never commit passwords, API keys or tokens.** If you do by accident, removing the file isn't enough (it stays in the history): revoke and replace the key immediately.

## Step 8: Pull requests (how teams work)

On GitHub, a **pull request (PR)** proposes merging a branch into `main`:

1. Push your branch: `git push -u origin dark-mode`.
2. On GitHub, click **Compare & pull request**, describe the change and submit.
3. Teammates review the code, comment and approve.
4. Automated checks (tests, linting) often run.
5. The PR is merged into `main`.

Even when working alone, PRs are a good habit for reviewing your own changes.

## Fixing common mistakes

```bash
# Unstage a file (keep the changes)
git restore --staged file.html

# Throw away uncommitted changes to a file (careful: can't be undone)
git restore file.html

# Fix the message of the last commit (only if not pushed yet)
git commit --amend -m "Better message"

# Undo a commit that's already pushed, by creating a new "opposite" commit
git revert <commit-id>
```

`git revert` is the safe way to undo shared history. Avoid commands that rewrite history (like `git reset --hard` or force-pushing) on branches others use.

## Cheat sheet

| Task | Command |
|---|---|
| Start a repo | `git init` |
| Check what changed | `git status`, `git diff` |
| Stage changes | `git add file` / `git add .` |
| Commit | `git commit -m "message"` |
| History | `git log --oneline` |
| New branch | `git switch -c name` |
| Change branch | `git switch name` |
| Merge into current branch | `git merge name` |
| Connect to GitHub | `git remote add origin URL` |
| Upload | `git push` |
| Download changes | `git pull` |
| Copy a repo | `git clone URL` |

## Frequently asked questions

### Is Git the same as GitHub?

No. Git is the tool on your computer; GitHub is a hosting service for Git repositories (alternatives include GitLab and Bitbucket).

### How often should I commit?

Whenever you complete a small, working step: often several times an hour while coding.

### Should beginners use a visual tool instead of commands?

VS Code's Source Control panel and GitHub Desktop are fine and helpful. Learning the basic commands too makes you comfortable everywhere, including servers and job interviews.

### Can I use GitHub to host my website?

Yes. **GitHub Pages** hosts static websites (HTML, CSS, JavaScript) for free, which is perfect for your portfolio.

## Summary

- Git records snapshots (commits) of your project; GitHub stores and shares them online.
- Daily cycle: edit → `git status` → `git add` → `git commit -m "…"` → `git push`.
- Use branches for features, merge when ready, and use pull requests for review.
- Keep secrets and dependencies out of Git with `.gitignore`.
