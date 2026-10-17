---
title: JSON Formatter and Validator
slug: json-formatter
parent: tools
template: page-tool.php
icon: 🧾
order: 30
excerpt: Format, beautify, minify and validate JSON online. Shows the exact line and column of errors and can sort keys.
---
## How to use the JSON formatter

Paste JSON on the left and choose an action:

- **Format** makes it readable with 2 spaces, 4 spaces or tabs.
- **Minify** removes all spaces, for the smallest possible size.
- **Sort keys** orders every object's keys alphabetically, which makes two JSON files easy to compare.
- **Validate** checks the syntax and tells you the **line and column** of the first error.

Everything happens in your browser, so it's safe to paste private API responses.

## The most common JSON mistakes

| Mistake | Wrong | Right |
|---|---|---|
| Single quotes | `{'name': 'Asha'}` | `{"name": "Asha"}` |
| Trailing comma | `[1, 2, 3,]` | `[1, 2, 3]` |
| Unquoted keys | `{name: "Asha"}` | `{"name": "Asha"}` |
| Comments | `{"a": 1 // note}` | JSON has no comments |
| `undefined` / `NaN` | `{"x": undefined}` | use `null` |

## JSON in code

```javascript
const text = JSON.stringify({ name: "Asha", skills: ["HTML", "CSS"] }, null, 2);
const data = JSON.parse(text);
console.log(data.skills[1]); // CSS
```

```python
import json
data = json.loads('{"name": "Asha", "age": 21}')
print(json.dumps(data, indent=2, sort_keys=True))
```

Learn more in the [JavaScript](/tutorial-category/javascript/) and [Python](/tutorial-category/python/) tutorials.
