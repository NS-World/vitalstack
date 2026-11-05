---
title: Cron Expression Explainer
slug: cron-explainer
parent: tools
template: page-tool.php
icon: 🗓️
order: 160
excerpt: Paste a cron schedule like "30 9 * * 1-5" and read it in plain English, with the next five run times in your local time. Includes ready-made presets.
---
## What is cron?

**Cron** is the scheduler on Linux servers. Backups, daily emails, report jobs, WordPress scheduled posts and many cloud schedulers (GitHub Actions, Kubernetes CronJobs) use the same five-field format:

```text
┌ minute (0-59)
│ ┌ hour (0-23)
│ │ ┌ day of month (1-31)
│ │ │ ┌ month (1-12)
│ │ │ │ ┌ day of week (0-6, Sunday = 0)
│ │ │ │ │
30 9 * * 1-5
```

That example means **at 9:30, Monday to Friday**.

## Symbols

| Symbol | Meaning | Example |
|---|---|---|
| `*` | every value | `* * * * *` every minute |
| `,` | a list | `0 9,18 * * *` at 9:00 and 18:00 |
| `-` | a range | `0 9 * * 1-5` weekdays at 9:00 |
| `/` | a step | `*/15 * * * *` every 15 minutes |

## How to use the explainer

Type an expression or tap a preset. The tool explains it in a sentence and lists the next five times it would run, in your own time zone. An error message tells you which field is wrong.

## Gotchas

1. **Time zone**: most servers run cron in **UTC**. 9:30 UTC is 15:00 in India. Check your server's zone before scheduling.
2. **Day of month and day of week together**: when both are set (not `*`), classic cron runs when *either* matches, not both.
3. **Overlapping runs**: if a job takes longer than its interval, two copies can run at once. Use a lock.
4. Names like `MON` or `JAN` and special strings like `@daily` work in many cron versions, but this explainer sticks to numbers so the meaning is always clear.
