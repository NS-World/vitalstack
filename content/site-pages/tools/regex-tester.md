---
title: Regex Tester
slug: regex-tester
parent: tools
template: page-tool.php
icon: 🔍
order: 40
excerpt: Test regular expressions online with live highlighting, capture groups and replace. Ready-made patterns for email, phone, URL and dates.
---
## How to use the regex tester

Type a pattern between the slashes and some text below it. Matches are highlighted as you type, and **Match details** lists every match with its position and capture groups. Fill **Replace** to preview a find-and-replace (use `$1`, `$2` for groups).

Try the ready-made patterns for emails, Indian mobile numbers, URLs, dates and hex colours, then change them to see what happens.

## Regex cheat sheet

| Pattern | Means | Example match |
|---|---|---|
| `.` | any character except newline | `a`, `7`, `#` |
| `\d` / `\D` | a digit / not a digit | `5` |
| `\w` / `\W` | letter, digit or `_` / anything else | `a`, `Z`, `_` |
| `\s` | whitespace | space, tab |
| `[abc]` / `[^abc]` | one of / none of | `b` |
| `a*` `a+` `a?` | 0+, 1+, 0 or 1 times | `aaa` |
| `a{3}` `a{2,4}` | exactly 3 / between 2 and 4 | `aaa` |
| `^` `$` | start / end of the text (or line with `m`) | |
| `\b` | word boundary | |
| `(...)` | capture group | `$1` in replace |
| `(?:...)` | group without capturing | |
| `a|b` | a or b | |

## Flags

- `g` global: find every match, not just the first.
- `i` ignore case.
- `m` multiline: `^` and `$` match at line breaks.
- `s` dot-all: `.` also matches newlines.

## Using regex in code

```javascript
const emails = "a@x.com, b@y.org".match(/[\w.]+@[\w.]+/g);
console.log(emails); // ["a@x.com", "b@y.org"]
```

```python
import re
print(re.findall(r"\d{4}-\d{2}-\d{2}", "Due 2026-10-17 or 2026-11-01"))
```

This tester uses JavaScript's regex engine. Python's `re` module supports the same core syntax shown above.
