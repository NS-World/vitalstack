---
title: Git Cheat Sheet
slug: git-cheat-sheet
parent: cheat-sheets
template: page-cheat-sheet.php
icon: 🌿
order: 110
excerpt: Git commands on one page - setup, commit, branch, merge, rebase, remote, stash, undo mistakes and a clean team workflow with GitHub pull requests. Printable.
---
## Setup

```bash
git config --global user.name "Asha Kumar"
git config --global user.email "asha@example.com"
git config --global init.defaultBranch main
git init                         # new repository
git clone https://github.com/user/repo.git
```

## Daily work

```bash
git status                       # what changed
git diff                         # unstaged changes
git diff --staged                # staged changes
git add file.js                  # stage a file
git add -p                       # stage chunk by chunk
git commit -m "Add login form validation"
git log --oneline --graph --all  # history
git show <commit>                # one commit
```

## Branches

```bash
git switch -c feature/login      # create and switch
git switch main
git branch                       # list
git branch -d feature/login      # delete (merged)
git merge feature/login          # merge into current branch
git rebase main                  # replay your commits on top of main
```

## Remotes

```bash
git remote -v
git fetch origin
git pull                         # fetch + merge (or rebase with --rebase)
git push -u origin feature/login # first push of a branch
git push
```

## Undo

| Situation | Command |
|---|---|
| discard changes in a file | `git restore file.js` |
| unstage a file | `git restore --staged file.js` |
| fix the last commit message / add a forgotten file | `git commit --amend` (before pushing) |
| undo a pushed commit safely | `git revert <commit>` |
| move back, keep changes | `git reset --soft HEAD~1` |
| throw away local commits | `git reset --hard origin/main` ⚠️ |
| find "lost" commits | `git reflog` |

## Stash

```bash
git stash push -m "wip navbar"
git stash list
git stash pop
```

## .gitignore

```text
node_modules/
target/
.env
*.log
.DS_Store
```

Never commit secrets (`.env`, API keys, passwords). If one leaks, **rotate it**: removing the commit isn't enough.

## Team workflow (GitHub flow)

1. `git switch -c feature/short-name` from an up-to-date `main`.
2. Small commits with clear messages ("Add …", "Fix …").
3. `git push -u origin feature/short-name`, open a **pull request**.
4. Review, fix comments, CI passes.
5. Merge (squash or merge commit), delete the branch, `git switch main && git pull`.

## Merge conflicts

```text
<<<<<<< HEAD
your version
=======
their version
>>>>>>> feature/x
```

Edit the file to the correct result, remove the markers, then `git add file` and `git commit` (or `git rebase --continue`).

## Good commit messages

- Imperative, short subject: "Add course search endpoint".
- Explain **why** in the body when it isn't obvious.
- One logical change per commit.
