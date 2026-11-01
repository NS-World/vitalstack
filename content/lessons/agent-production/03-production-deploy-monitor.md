---
title: "Deployment and Monitoring"
slug: production-deploy-monitor
subject: agent-production
lesson: 3
difficulty: advanced
excerpt: Run agents reliably for real users: background jobs with a queue and status endpoint for long tasks, idempotent jobs, the metrics that matter (success rate, p95 latency, cost, steps), alert rules, versioned prompts and canary releases.
---

Agents are slower and less predictable than normal web requests: a research task might take two minutes and twelve tool calls. Production agents run as **background jobs**, are **watched** with the right metrics, and are **released carefully**, so one bad prompt change doesn't break every user at once.

::scene Launch week
📮 | Queue | Dev's API accepts a job and replies instantly: "Started, job 81". | dev
⚙️ | Worker | Chip picks jobs from the queue and runs the agent in the background. | chip
📊 | Dashboard | Byte watches success rate, p95 time and cost per task. | byte
🐤 | Canary | Bugsy sends 10% of users to the new prompt first. Its success rate drops, so it's rolled back. | bugsy
::end

## Long tasks: queue, worker, status

```
POST /jobs        → saves the job, puts it on a queue, returns {"job_id": 81, "status": "queued"}
worker            → takes job 81, runs the agent, saves progress and the result
GET /jobs/81      → {"status": "running", "step": "reading sources (3/5)"} … then "done" + result
```

Queues like Redis-based task queues, cloud queues (SQS, Pub/Sub) or workflow engines (Temporal) handle retries and crashes. Make jobs **idempotent**: if a worker crashes halfway and the job runs again, it must not send the email twice (use idempotency keys and record finished steps).

## The metrics that matter

```python
import numpy as np

rng = np.random.default_rng(3)
latency = rng.lognormal(mean=8.0, sigma=0.5, size=500)           # ms, typical long-tailed latencies
success = rng.random(500) < 0.96
cost = rng.normal(0.03, 0.008, 500).clip(0.005)
steps = rng.integers(2, 9, 500)

print(f"success rate {success.mean():.1%}")
print(f"latency p50 {np.percentile(latency, 50):,.0f} ms | p95 {np.percentile(latency, 95):,.0f} ms")
print(f"cost per task ${cost.mean():.3f} | avg steps {steps.mean():.1f}")
```

Output:

```text
success rate 95.8%
latency p50 3,038 ms | p95 6,750 ms
cost per task $0.030 | avg steps 4.9
```

Track **p95** latency, not just the average: the slowest 5% of users are the ones who complain. Also track tool error rates, hand-offs to humans, user feedback (thumbs up/down) and eval scores on a daily sample.

## Alert rules

::walk python | Turning metrics into alerts
success_rate = 0.91
p95_ms = 8200
cost_per_task = 0.031
alerts = [name for name, bad in [("success < 95%", success_rate < 0.95), ("p95 > 10s", p95_ms > 10000), ("cost > $0.05", cost_per_task > 0.05)] if bad]
print(alerts)
::notes
1: Today's success rate dropped to 91%. // vars: success_rate=0.91
2: The slowest 5% of tasks take 8.2 seconds. // vars: p95_ms=8200
3: Average cost per task is about 3 cents. // vars: cost_per_task=0.031
4: Check each rule and keep the ones that are breached. // deep: Alert on what users feel (success, latency) and what the business feels (cost). Set thresholds from your normal baseline, and alert on sudden changes too: a 3× jump in cost per task often means a loop bug or a broken cache.
5: One alert fires: success is below target. Time to read today's failed traces. // out: ['success < 95%']
::end

## Versioned prompts and canary releases

Treat prompts, tool descriptions and model choices like code:

- **Version them** (`support-agent v14`) and record the version in every trace.
- **Run the eval suite** before releasing a change.
- **Canary release:** send a small share of traffic to the new version and compare its metrics with the old one.

```python
import hashlib

def bucket(user_id):
    return int(hashlib.sha256(user_id.encode()).hexdigest(), 16) % 100

def version_for(user_id, canary_percent=10):
    return "v15" if bucket(user_id) < canary_percent else "v14"

users = [f"user{i}" for i in range(1000)]
share = sum(version_for(u) == "v15" for u in users) / len(users)
print(f"{share:.1%} of users on the canary;", "user42 gets", version_for("user42"))
```

Output:

```text
8.6% of users on the canary; user42 gets v14
```

Hashing the user id means each user **always** gets the same version (no flip-flopping between replies), and roughly 10% get the new one (8.6% in this sample of 1,000). If the canary's success rate or cost looks worse, set the percentage to 0: instant rollback.

## The launch checklist

- [ ] Long tasks run as background jobs with a status endpoint.
- [ ] Jobs are idempotent and have timeouts and step limits.
- [ ] Traces for every run, with prompt version and cost.
- [ ] Dashboards: success rate, p50/p95 latency, cost per task, steps, tool errors.
- [ ] Alerts with sensible thresholds, sent to someone who'll act.
- [ ] Evals in CI; canary releases with quick rollback.
- [ ] Spending limits at the provider; per-user rate limits.

::tip Review 10 random production traces every week, even when all metrics are green. You'll spot slow drifts (longer answers, extra steps, odd tool choices) long before they show up in averages.

## Summary

- Run long agent tasks as queued background jobs with status updates, idempotency and limits.
- Monitor success rate, p95 latency, cost and steps; alert on breaches and sudden changes.
- Version prompts, run evals before release, and roll out with canaries.

🎉 You've finished **Production**.
