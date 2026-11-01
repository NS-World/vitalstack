---
title: "GitHub Copilot, Cursor and Claude Code Compared"
slug: coding-assistants-compared
subject: ai-coding-assistants
lesson: 1
difficulty: beginner
excerpt: The three levels of AI coding help (autocomplete, chat, agent), what GitHub Copilot, Cursor and Claude Code each do best, project instruction files, and how to measure whether an assistant really saves you time.
---

AI coding assistants now write a big share of new code in many teams. But "use AI for coding" means very different things: finishing the line you're typing, explaining an error, or an **agent** that reads your whole project, edits ten files and runs the tests. Knowing which level fits which job is the real skill.

::scene Three helpers, three jobs
⌨️ | Autocomplete | Dev types "def total(" and grey text suggests the rest. Tab to accept. | dev
💬 | Chat | Bugsy pastes an error: "Why does this crash?" The chat explains and suggests a fix. | bugsy
🤖 | Agent | Chip is asked: "Add dark mode and update the tests." It plans, edits files and runs the tests. | chip
🧐 | Review | Byte reads every change before it's merged. The human stays in charge. | byte
::end

## The three levels

| Level | You give it | It gives you | Best for |
|---|---|---|---|
| **Autocomplete** | The code you're typing | The next line or block | Boilerplate, loops, obvious next steps |
| **Chat** | A question + selected code | An explanation or a snippet | Errors, "how do I…", learning a library |
| **Agent** | A task | Edits across files, commands run, a summary | Features, refactors, tests, bug hunts |

## The popular tools

| Tool | What it is | Strengths |
|---|---|---|
| **GitHub Copilot** | An assistant inside VS Code, JetBrains and other editors, plus GitHub | Fast inline completions, chat, an agent mode, and a coding agent that can work on GitHub issues and open pull requests |
| **Cursor** | An AI-first code editor (based on VS Code) | Smart multi-line tab completions, chat with your codebase, an agent that edits many files |
| **Claude Code** | Anthropic's agentic coding tool, in the terminal, IDEs, a desktop app and the web | Reads and edits whole projects, runs commands and tests, follows a `CLAUDE.md` file of project rules, asks permission before risky actions |

Others worth knowing include Windsurf, Gemini CLI and OpenAI Codex. Features and prices change quickly, so check each tool's current site, and try the free tiers or trials before paying.

## Project instructions: tell the assistant your rules once

Most agentic tools read an instructions file from your project (Claude Code uses `CLAUDE.md`; other tools have their own rule files). It's added to every request:

::walk python | How project rules join every request
rules = ["Use Python 3.12", "Run pytest before finishing", "Never edit migrations/"]
task = "Add a /health endpoint"
prompt = task + "\n\nProject rules:\n" + "\n".join("- " + r for r in rules)
print(len(prompt.splitlines()), prompt.splitlines()[-1])
::notes
1: The rules you'd otherwise repeat in every chat: versions, test commands, folders not to touch. // vars: rules=3 rules
2: Today's task. // vars: task=Add a /health endpoint
3: The tool joins the task and the rules into one prompt, automatically, every time. // deep: Keep the rules file short and specific: build and test commands, code style, folder layout and "never do" rules. Long, vague rules files get ignored just like long, vague prompts.
4: Six lines: the task, a blank line, the heading and three rules. The last rule is the one that protects your database migrations. // out: 6 - Never edit migrations/
::end

## Does it actually save time?

Count the time you spend **reviewing and fixing** AI code, not just the time it took to generate:

```python
tasks = {"write tests": (40, 12, 8), "new endpoint": (90, 30, 25), "fix CSS bug": (15, 10, 9)}
for name, (manual, with_ai, review) in tasks.items():
    saved = manual - (with_ai + review)
    print(f"{name:<13} saved {saved:>3} min")
```

Output:

```text
write tests   saved  20 min
new endpoint  saved  35 min
fix CSS bug   saved  -4 min
```

AI shines on tests and new, well-defined features. On a small, fiddly bug it can cost you more time than doing it yourself. Track a week of your own tasks like this and you'll know where to use it.

## Choosing for yourself

- **Learning to code:** use chat to **explain**, and type the code yourself. Turn off autocomplete for practice problems; your brain needs the reps.
- **Working developer:** autocomplete + chat in your editor every day; an agent for multi-file tasks.
- **Team lead:** agree on rules files, review standards and which tools may see company code.

::tip Interviews and exams still test you without AI. Use assistants to understand faster, then solve the same problem again without help. If you can't, you haven't learned it yet.

## Summary

- Three levels: autocomplete, chat and agents, each for different jobs.
- Copilot, Cursor and Claude Code all cover these, with different strengths and homes (editor, AI editor, terminal-first agent).
- Project rule files like `CLAUDE.md` give the assistant your conventions every time.
- Measure time saved including review; AI doesn't help equally on every task.
