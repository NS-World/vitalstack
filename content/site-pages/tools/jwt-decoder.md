---
title: JWT Decoder
slug: jwt-decoder
parent: tools
template: page-tool.php
icon: 🪪
order: 120
excerpt: Paste a JSON Web Token to read its header and payload, see when it was issued and when it expires. Decoding happens in your browser; the token is never sent.
---
## What is a JWT?

A **JSON Web Token** is a compact string that apps use to say "this user is logged in" without asking the database on every request. It has three parts separated by dots:

```text
header.payload.signature
```

- **Header**: which algorithm signed it, for example `HS256` or `RS256`.
- **Payload**: the *claims*, such as the user id (`sub`), when it was issued (`iat`) and when it expires (`exp`).
- **Signature**: proves the server created the token and nobody changed it.

The header and payload are only **Base64URL-encoded, not encrypted**. Anyone who has the token can read them, which is exactly what this tool does.

## How to use the decoder

Paste a token. The tool shows the header and payload as formatted JSON and turns `iat`, `nbf` and `exp` into readable dates, telling you if the token has already expired.

It does **not** verify the signature, because that needs the server's secret or public key. A decoded token is not proof that it is genuine.

## Security tips for developers

1. **Never put secrets in the payload** (passwords, card numbers, Aadhaar numbers). It is readable by anyone.
2. **Keep tokens short-lived** (minutes to hours) and use refresh tokens for longer sessions.
3. **Always verify the signature on the server** and reject `alg: none`.
4. **Do not paste real production tokens into online tools** you do not trust. This one runs only in your browser, but the habit protects you everywhere.
5. Prefer **HttpOnly cookies** over localStorage for tokens in web apps, so injected scripts cannot steal them.
