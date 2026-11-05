---
title: Unix Timestamp Converter (IST and UTC)
slug: timestamp-converter
parent: tools
template: page-tool.php
icon: ⏱️
order: 150
excerpt: Convert Unix epoch time to a readable date in IST, UTC and your local time, and back again. Handles seconds and milliseconds automatically.
---
## What is a Unix timestamp?

A **Unix timestamp** is the number of seconds since **1 January 1970, 00:00 UTC** (called "the epoch"). Computers love it because it is one simple number with no time zones or date formats to argue about. Databases, logs, APIs and JWTs all use it.

- `1700000000` is a timestamp in **seconds** (10 digits today).
- `1700000000000` is the same moment in **milliseconds** (13 digits), the format JavaScript's `Date.now()` returns.

The tool spots which one you pasted and shows the date in **IST (UTC+5:30)**, **UTC**, ISO 8601 format and as "how long ago".

## How to use it

Paste a timestamp, or pick a date and time (in your own time zone) to get its timestamp. Press **Now** to see the current time.

## Code you will use again and again

```python
from datetime import datetime, timezone, timedelta
ts = 1700000000
utc = datetime.fromtimestamp(ts, tz=timezone.utc)
ist = utc.astimezone(timezone(timedelta(hours=5, minutes=30)))
print(utc.isoformat())
print(ist.isoformat())
```

```javascript
const d = new Date(1700000000 * 1000);
console.log(d.toISOString());
console.log(d.toLocaleString("en-IN", { timeZone: "Asia/Kolkata" }));
```

## Common bugs

1. Mixing seconds and milliseconds: a date in 1970 or in the year 55,000 means you used the wrong one.
2. Storing local time without the zone. Store UTC, convert to IST only when you show it.
3. 32-bit systems overflow on 19 January 2038. Use 64-bit integers for timestamps.
