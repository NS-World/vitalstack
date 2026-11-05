---
title: Text Diff Checker (Compare Two Texts)
slug: text-diff
parent: tools
template: page-tool.php
icon: 🆚
order: 170
excerpt: Paste two versions of code, a document or a list and see exactly which lines were added and removed, highlighted in green and red. Private and instant.
---
## How to compare two texts

Paste the **original** on the left and the **changed** version on the right. Below, every line is marked:

- `+` in green: added in the changed version
- `-` in red: removed from the original
- no mark: the same in both

The summary counts how many lines were added and removed. Everything runs in your browser, so you can safely compare private notes or code.

## Where diffs are used

A **diff** is how developers review each other's work. Every pull request on GitHub, every `git diff` in a terminal and every "track changes" view in a document editor shows the same idea: what changed between version A and version B.

Use this tool to:

1. Check what changed between two versions of a config file before deploying.
2. Compare a teacher's answer with yours, line by line.
3. Spot the one changed row in two exported CSV files.
4. See what an AI assistant actually changed when it "fixed" your code.

## How it works

The tool finds the **longest common subsequence**: the biggest set of lines that appear in both texts in the same order. Whatever is not part of it was added or removed. This is the classic algorithm behind the Unix `diff` command, and a great dynamic programming exercise.

```python
import difflib
a = ["name = 'Asha'", "city = 'Pune'"]
b = ["name = 'Asha'", "city = 'Mumbai'"]
for line in difflib.unified_diff(a, b, lineterm=""):
    print(line)
```

Tip: a change in spaces or line endings also counts as a change. If two lines look the same but are marked different, look for trailing spaces or tabs.
