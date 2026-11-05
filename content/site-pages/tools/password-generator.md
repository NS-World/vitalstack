---
title: Strong Password Generator
slug: password-generator
parent: tools
template: page-tool.php
icon: 🔐
order: 110
excerpt: Create strong passwords and easy-to-remember passphrases using your browser's secure random generator, with an honest strength meter. Nothing is sent or saved.
---
## How it works

Choose **Passphrase** for a few random words joined together (easy to type and remember), or **Random characters** for a short, dense password (best kept in a password manager). Move the slider to change the length and press **Generate**.

The tool uses `crypto.getRandomValues`, the same secure random source browsers use for encryption, so the result cannot be predicted. Nothing you generate is sent to any server or stored.

## Reading the strength meter

The meter shows the **bits of entropy**: how many guesses an attacker would need in the worst case. Every extra bit doubles the work.

| Bits | Meaning |
|---|---|
| below 40 | weak: a fast computer can guess it |
| 40 to 70 | okay for low-value accounts |
| above 70 | strong: impractical to guess |

A five-word passphrase from a large word list is already strong, and much easier to remember than `Xk#9q!`.

## Password rules that actually matter

1. **Never reuse a password.** When one site is breached, attackers try the same email and password everywhere else.
2. **Use a password manager** (the one built into your browser or phone is fine) so you only remember one master passphrase.
3. **Turn on two-step verification** (2FA) for email, banking and social media. An authenticator app is safer than SMS.
4. **Length beats tricks.** `Password@123` is one of the first guesses attackers try.
5. **Never share an OTP**, even with someone who says they are from your bank. Banks never ask for it.

Learn the full story, with real scam examples, in the free [Cyber Safety for Everyone](/tutorial-category/cyber-safety/) course.
