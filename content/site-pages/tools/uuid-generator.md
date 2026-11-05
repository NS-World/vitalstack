---
title: UUID Generator (v4)
slug: uuid-generator
parent: tools
template: page-tool.php
icon: 🆔
order: 200
excerpt: Generate up to 100 random version 4 UUIDs at once using your browser's secure random source. Copy them all with one click.
---
## What is a UUID?

A **UUID** (universally unique identifier) is a 128-bit id written as 32 hex characters in five groups:

```text
3f2b8c1e-7a4d-4e9b-b1c2-9d8e7f6a5b4c
```

**Version 4** UUIDs are almost entirely random (122 random bits). The chance of two ever colliding is so small that systems around the world create them independently without checking with each other.

## How to use the generator

Choose how many you need (1 to 100), tick **UPPERCASE** if your system expects it, and press **Generate**. **Copy all** copies the list, one per line. The ids come from `crypto.randomUUID` or `crypto.getRandomValues`, the browser's cryptographically secure random generator.

## When to use UUIDs

- **Database primary keys** when records are created in many places (mobile apps working offline, several servers).
- **Public ids in URLs**, so people cannot guess the next record by adding 1 (`/order/1001`, `/order/1002`…).
- **Idempotency keys** for payments, so a retried request is not charged twice.
- **File names** for uploads, so two users uploading `photo.jpg` do not overwrite each other.

## Things to know

1. A UUID is unique, **not secret**. Do not use one as a password-reset token or API key on its own; use a dedicated secure token.
2. Random UUIDs make database indexes a little slower than sequential ids. Newer formats such as UUID v7 start with a timestamp to fix this.
3. In Python: `import uuid; print(uuid.uuid4())`. In JavaScript: `crypto.randomUUID()`.
