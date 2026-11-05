---
title: Hash Generator (SHA-256, SHA-512)
slug: hash-generator
parent: tools
template: page-tool.php
icon: 🧾
order: 140
excerpt: Get SHA-1, SHA-256, SHA-384 and SHA-512 hashes of any text instantly, using your browser's built-in Web Crypto. Learn what hashes are for and where they are not enough.
---
## What is a hash?

A **hash function** turns any input into a fixed-length fingerprint. Three properties make it useful:

1. **Same input, same hash**, every time, on every computer.
2. **Tiny change, totally different hash.** Change one letter and about half the bits flip.
3. **One-way.** You cannot work back from the hash to the input.

Type in the box and watch all four hashes change as you type. The tool uses `crypto.subtle.digest`, built into every modern browser, so your text never leaves the page.

## Where hashes are used

- **Downloads**: websites publish the SHA-256 of a file so you can check your copy was not corrupted or tampered with.
- **Git**: every commit id is a hash of its content.
- **Caching and de-duplication**: two files with the same hash are almost certainly identical.
- **Blockchains** chain blocks together using hashes.

## Hashes and passwords

Never store passwords as plain SHA-256. Attackers can test billions of guesses per second against a fast hash. Use a slow, salted password hashing function instead: **bcrypt**, **scrypt** or **Argon2**. A *salt* is random data added to each password so two users with the same password get different hashes.

**SHA-1** is shown for learning and for old systems only. Researchers have produced real SHA-1 collisions, so do not use it for anything security-related.

```python
import hashlib
print(hashlib.sha256("hello".encode()).hexdigest())
```
