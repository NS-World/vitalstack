---
title: "Working Safely with AI-Written Code"
slug: coding-assistants-safe-ai-code
subject: ai-coding-assistants
lesson: 3
difficulty: intermediate
excerpt: The real risks of AI-generated code and how to catch them: SQL injection you can see happen, made-up package names, leaked secrets, licences, agent permissions, and a review checklist for every AI change.
---

AI assistants write code that **looks** right. It's usually well-formatted, nicely named and confident, which makes its mistakes easy to miss. Security studies keep finding vulnerabilities in a meaningful share of AI-suggested code. This lesson shows the common problems, lets you see one happen, and gives you a checklist.

::scene The too-friendly login
🔑 | Quick code | Chip writes a user search with an f-string SQL query. It works in testing. | chip
🦹 | The trick | Bugsy types  x' OR '1'='1  into the search box. Every user in the database comes back. | bugsy
🛡️ | The fix | Byte switches to a parameterised query. Same input, zero rows. | byte
📋 | The rule | Dev adds "no SQL from strings" to the project rules and the review checklist. | dev
::end

## See SQL injection happen

::walk python | How the attack rewrites the query
user_input = "x' OR '1'='1"
unsafe_sql = f"SELECT * FROM users WHERE name = '{user_input}'"
print(unsafe_sql)
::notes
1: What an attacker types into a search box. // vars: user_input=x' OR '1'='1
2: Code that glues user input into SQL, which AI assistants still sometimes suggest. // deep: The quote in the input closes the string early, and OR '1'='1' is always true, so the WHERE clause matches every row. The same idea attacks shell commands, HTML and file paths.
3: The database receives a different query than you meant: "name is x, OR true". // out: SELECT * FROM users WHERE name = 'x' OR '1'='1'
::end

```python
import sqlite3

db = sqlite3.connect(":memory:")
db.execute("CREATE TABLE users (name TEXT, is_admin INTEGER)")
db.executemany("INSERT INTO users VALUES (?, ?)", [("asha", 0), ("ravi", 1)])

evil = "x' OR '1'='1"
unsafe = db.execute(f"SELECT name FROM users WHERE name = '{evil}'").fetchall()
safe = db.execute("SELECT name FROM users WHERE name = ?", (evil,)).fetchall()
print("unsafe:", unsafe, "| safe:", safe)
```

Output:

```text
unsafe: [('asha',), ('ravi',)] | safe: []
```

With `?` placeholders the database treats the input as a plain value, never as SQL.

## Made-up packages

Models sometimes suggest packages that **don't exist**. Attackers register those names on PyPI or npm with malware inside ("slopsquatting"). Check every new dependency:

```python
suggested = ["requests", "pandas", "fastapi-auth-helperz", "numpy"]
approved = {"requests", "pandas", "numpy", "fastapi"}
print("check before installing:", [p for p in suggested if p not in approved])
```

Output:

```text
check before installing: ['fastapi-auth-helperz']
```

Look the package up on its official registry page: real maintainers, real downloads, a real repository. If in doubt, don't install it.

## Secrets in code

AI may "helpfully" put a key straight into code. Scan before you commit:

```python
import re

code = '''API_KEY = "sk-test-EXAMPLE-not-a-real-key"
url = "https://api.example.com"
password = os.environ["DB_PASS"]'''
pattern = r'(key|secret|password|token)\s*=\s*["\'][^"\']{8,}'
print([line for line in code.splitlines() if re.search(pattern, line, re.I)])
```

Output:

```text
['API_KEY = "sk-test-EXAMPLE-not-a-real-key"']
```

The hard-coded key is flagged; the password read from an environment variable is fine. Tools like git hooks and secret scanners in GitHub do this automatically.

## Agent permissions

Agentic tools can run commands. Keep them on a short leash:

- Use the tool's **permission prompts** for commands, file writes and network access, especially at first.
- Never let an agent run `rm -rf`, deploy, push to main or touch production data without your approval.
- Run agents in a branch, a container or a sandbox, so mistakes are easy to throw away.
- Don't paste secrets, customer data or company code into tools your organisation hasn't approved.

## Licences

AI can reproduce code that looks like existing open-source code. For company projects, use tools with code-reference filters where available, and don't copy large blocks you can't explain.

## The review checklist for every AI change

1. Do I understand every line? If not, ask the AI to explain, or rewrite it.
2. Do tests cover the new behaviour, including bad input?
3. Any SQL, shell commands, HTML or file paths built from user input?
4. Any new dependency? Is it real and trustworthy?
5. Any secrets, keys or personal data in code or logs?
6. Error handling: what happens when the network, file or API fails?
7. Did it change anything I didn't ask for?

::tip Treat AI code like code from a fast, talented intern who never says "I'm not sure": helpful, often right, and always reviewed before it ships.

## Summary

- AI code looks confident even when it's wrong or unsafe; review it like any other code.
- Use parameterised queries; never build SQL or shell commands from strings.
- Verify every new package, and scan for secrets before committing.
- Limit agent permissions, work in branches or sandboxes, and use a checklist.

🎉 You've finished **AI Coding Assistants**.
