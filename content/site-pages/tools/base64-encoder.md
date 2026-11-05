---
title: Base64 and URL Encoder / Decoder
slug: base64-encoder
parent: tools
template: page-tool.php
icon: 🔁
order: 130
excerpt: Encode and decode Base64 and URL (percent) encoding, with full support for Hindi, emoji and other Unicode text. Runs in your browser.
---
## When do you need Base64?

**Base64** turns any bytes into plain letters, digits, `+` and `/`, so binary data can travel safely through systems built for text: email attachments, JSON APIs, data URLs for small images and the parts of a JWT.

It grows the data by about a third (every 3 bytes become 4 characters), and it is **not encryption**. Anyone can decode it, so never use it to "hide" a password.

**Base64URL** swaps `+` and `/` for `-` and `_` and drops the `=` padding, so the result is safe inside web addresses.

**URL encoding** (percent encoding) replaces characters such as spaces, `&` and `?` with codes like `%20`, so they do not break a link.

## How to use the tool

Type or paste text on the left and press **Base64 encode**, **Base64 decode**, **URL encode** or **URL decode**. The result appears on the right; **Copy output** puts it on your clipboard. Unicode text such as `नमस्ते 👋` is encoded as UTF-8 first, so it decodes back exactly.

## Try it in code

```python
import base64
s = "नमस्ते"
b = base64.b64encode(s.encode("utf-8")).decode()
print(b)
print(base64.b64decode(b).decode("utf-8"))
```

```javascript
const b = btoa(String.fromCharCode(...new TextEncoder().encode("नमस्ते")));
console.log(b);
```

A common bug: calling `btoa("नमस्ते")` directly throws an error, because `btoa` only accepts characters below 256. Encode to UTF-8 bytes first, as above.
