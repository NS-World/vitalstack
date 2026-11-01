---
title: "Build an MCP Server"
slug: mcp-build-server
subject: mcp
lesson: 2
difficulty: intermediate
excerpt: Build a real MCP server in Python: a course catalogue backed by SQLite, tools with type hints and docstrings, a resource template, a prompt, helpful errors, safe SQL, logging that doesn't break stdio, and testing with MCP Inspector.
---

Time to build. Our server exposes a small **course catalogue** stored in SQLite: the model can search courses and read details, and users get a ready-made "study plan" prompt. We write and test the logic as plain Python first, then wrap it with the official SDK's **FastMCP**.

::scene Chip opens a shop
🗄️ | The data | Byte fills a SQLite table with courses: slug, title, level and minutes. | byte
🔧 | The tools | Chip writes search_courses and course_detail as normal Python functions. | chip
🧪 | Tested | Dev tests them directly before any MCP code exists. | dev
🔌 | Plugged in | Bugsy adds three decorators and opens it in MCP Inspector. It just works. | bugsy
::end

## Step 1: the logic, as plain Python

```python
import sqlite3

db = sqlite3.connect(":memory:")
db.executescript("""
CREATE TABLE courses (slug TEXT PRIMARY KEY, title TEXT, level TEXT, minutes INTEGER);
INSERT INTO courses VALUES
  ('python', 'Python Basics', 'beginner', 240),
  ('sql', 'SQL for Analysis', 'beginner', 180),
  ('rag', 'Retrieval-Augmented Generation', 'intermediate', 150),
  ('mcp', 'Model Context Protocol', 'intermediate', 120);
""")

def search_courses(level: str = "", max_minutes: int = 1000) -> list[dict]:
    """Find courses. level: 'beginner' or 'intermediate' (empty = any). max_minutes: longest course to include."""
    rows = db.execute("SELECT slug, title, minutes FROM courses "
                      "WHERE (? = '' OR level = ?) AND minutes <= ? ORDER BY minutes",
                      (level, level, max_minutes)).fetchall()
    return [{"slug": s, "title": t, "minutes": m} for s, t, m in rows]

def course_detail(slug: str) -> str:
    """Get one course's title, level and length by its slug, e.g. 'sql'."""
    row = db.execute("SELECT title, level, minutes FROM courses WHERE slug = ?", (slug,)).fetchone()
    if row is None:
        raise ValueError(f"No course '{slug}'. Call search_courses to see valid slugs.")
    return f"{row[0]} ({row[1]}, {row[2]} min)"

print(search_courses(level="intermediate"))
print(course_detail("sql"))
try:
    course_detail("java")
except ValueError as e:
    print("error:", e)
```

Output:

```text
[{'slug': 'mcp', 'title': 'Model Context Protocol', 'minutes': 120}, {'slug': 'rag', 'title': 'Retrieval-Augmented Generation', 'minutes': 150}]
SQL for Analysis (beginner, 180 min)
error: No course 'java'. Call search_courses to see valid slugs.
```

Note the `?` placeholders: inputs from the model are never glued into SQL (AI Coding Assistants module).

::walk python | One search, step by step
level, max_minutes = "beginner", 200
params = (level, level, max_minutes)
rows = search_courses(level, max_minutes)
print(params, [r["slug"] for r in rows])
::notes
1: The model asks for beginner courses of at most 200 minutes. // vars: level=beginner, max_minutes=200
2: The values for the three ? placeholders: level twice (for the "any level" trick) and the limit. // deep: (? = '' OR level = ?) means "if no level was given, match every row; otherwise match that level". It keeps one query instead of building SQL strings in code. // vars: params=('beginner', 'beginner', 200)
3: Run the tool function exactly as the server will. // vars: rows=1 course
4: Python Basics (240 min) is too long, so only SQL fits. // out: ('beginner', 'beginner', 200) ['sql']
::end

## Step 2: wrap it with FastMCP

Save as `server.py` (with the database code above, using a file path instead of `:memory:`):

```python
import logging
import sys
from mcp.server.fastmcp import FastMCP

logging.basicConfig(stream=sys.stderr, level=logging.INFO)   # stdout is reserved for protocol messages
mcp = FastMCP("course-catalog")

mcp.tool()(search_courses)        # the same functions, registered as tools
mcp.tool()(course_detail)

@mcp.resource("course://{slug}")
def course_resource(slug: str) -> str:
    """A course as a readable resource."""
    return course_detail(slug)

@mcp.prompt()
def study_plan(goal: str) -> str:
    """A ready-made prompt users can pick."""
    return f"Use the course-catalog tools to make a 2-week study plan for this goal: {goal}. Include minutes per day."

if __name__ == "__main__":
    mcp.run()                     # stdio; use mcp.run(transport="streamable-http") to serve over HTTP
```

What FastMCP does for you:

- Type hints → the tool's JSON input schema; the docstring → its description.
- Return values → tool result content.
- A raised exception → an error result (`isError: true`) with your message, which the model can read and act on.

## Step 3: test with MCP Inspector

```bash
npx @modelcontextprotocol/inspector python server.py
```

Inspector opens a web page where you can list tools, call them with your own arguments, read resources and try prompts. Check:

- Do descriptions read clearly?
- Do bad inputs give helpful errors?
- Are results short enough?

## Good server habits

| Habit | Why |
|---|---|
| Log to **stderr**, never `print` to stdout | On stdio, stdout carries protocol messages; a stray print breaks the connection |
| Read-only by default | Add write tools only when needed, with clear descriptions |
| Secrets from environment variables | Never in code or tool results |
| Limit result size | Paginate or summarise large results |
| Version your server | Hosts show the server name and version; bump it when tools change |

::tip Write unit tests for the plain functions (step 1) and keep the MCP layer thin. Most bugs live in your logic, and plain functions are far easier to test than a running server.

## Summary

- Write and test tool logic as plain Python first.
- FastMCP turns functions into tools, resources and prompts using type hints and docstrings.
- Raise exceptions with helpful messages; they become error results.
- Log to stderr, keep it read-only by default, and test with MCP Inspector.
