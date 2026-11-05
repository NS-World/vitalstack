---
title: Link Safety Checker (Spot Scam Links)
slug: link-safety-checker
parent: tools
template: page-tool.php
icon: 🛡️
order: 210
excerpt: Paste a link from an SMS, email or WhatsApp and see the red flags before you click: fake bank names, look-alike domains, shorteners, app downloads and more. The link is never opened.
---
## Check before you click

Scam messages say your KYC is pending, your electricity will be cut tonight, a parcel is stuck, or you have won a reward. They all end with a link. This tool reads the link **as text only**. It never opens it and never sends it anywhere, so it is safe to paste anything.

It shows the **real domain** (the part that decides which website you reach) and explains each red flag in plain words.

## How to read a link

In `https://onlinesbi.sbi.kyc-update.online/login`, the real website is **kyc-update.online**, not SBI. Read the address up to the first single `/`, then look at the **last two parts** (or three for endings like `.co.in` and `.gov.in`): that is the owner. Everything to the left of it is decoration the scammer can choose freely.

Red flags the tool checks:

| Red flag | Why it matters |
|---|---|
| Bank or brand name, but a different real domain | the most common phishing trick |
| Numbers instead of letters (`amaz0n`) | look-alike domains |
| `@` in the address | the browser ignores everything before it |
| Raw IP number | real companies use names |
| Link shorteners | they hide the destination |
| `.apk` or `.exe` downloads | fake apps can read your SMS and OTPs |
| Words like KYC, refund, reward, blocked | pressure words used in scams |

## Important

A clean result does **not** prove a link is safe; it only means none of the common tricks were found. When in doubt, do not click. Open the official app or type the bank's address yourself.

If you lose money to a scam, call **1930** (the national cyber crime helpline) immediately and report at **cybercrime.gov.in**. The faster you report, the better the chance of freezing the money. Learn more in the free [Cyber Safety for Everyone](/tutorial-category/cyber-safety/) course.
