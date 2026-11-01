---
title: "LLM-as-Judge"
slug: evals-llm-judge
subject: agent-evals
lesson: 2
difficulty: advanced
excerpt: Use a model to grade agent work you can't check with code: rubric judges with critical items, pairwise comparisons, catching position bias by swapping order, and calibrating the judge against human labels with agreement and Cohen's kappa.
---

Some things code can't check: "Was the reply polite?", "Did it follow the refund policy?", "Is this summary faithful to the document?" An **LLM judge** reads the agent's work and grades it against a rubric. It's fast and cheap compared with human review, **if** you test the judge itself.

::scene Judging the judge
⚖️ | The judge | Byte grades 10 support replies with a rubric. | byte
🧑‍🏫 | Humans too | Dev grades the same 10 by hand. | dev
🔍 | Compare | They agree on 8 of 10. Chip calculates how much of that is real agreement, not luck. | chip
🔄 | Bias check | Bugsy swaps answer order in comparisons. A good judge shouldn't change its mind. | bugsy
::end

## A rubric judge

Write rubric items as **yes/no questions**, and mark the ones that are critical:

```python
import anthropic

client = anthropic.Anthropic()
RUBRIC = """Grade the support agent's reply. Answer each item with 1 (yes) or 0 (no), as JSON:
{"used_correct_order_data": 0|1, "followed_refund_policy": 0|1, "polite": 0|1, "no_invented_facts": 0|1,
 "reason": "one sentence"}
Refund policy: refunds only for delivered orders within 7 days."""

def judge(conversation, order_record):
    r = client.messages.create(model="claude-opus-5-5", max_tokens=300, system=RUBRIC,
        messages=[{"role": "user", "content": f"<order>{order_record}</order>\n<conversation>{conversation}</conversation>"}])
    return r.content[0].text
```

Give the judge the **facts it needs** (the order record, the policy, the source document). A judge without the facts is just guessing.

::walk python | Scoring with a critical item
rubric = {"used correct order data": 1, "followed refund policy": 1, "polite": 1, "no invented facts": 0}
score = sum(rubric.values()) / len(rubric)
critical_failed = rubric["no invented facts"] == 0
print(score, "FAIL" if critical_failed else "PASS")
::notes
1: The judge's verdict on four rubric items. // vars: rubric=3 of 4 passed
2: The average looks fine: 75%. // vars: score=0.75
3: But inventing facts is a critical failure: one strike and the reply fails. // deep: Averages hide disasters. A polite, well-formatted reply that invents a refund policy is worse than a blunt correct one. Keep critical items (safety, honesty, policy) as hard gates, separate from the score. // vars: critical_failed=True
4: 0.75 overall, but FAIL. // out: 0.75 FAIL
::end

## Pairwise comparisons

To choose between two prompt versions, ask the judge "Which reply is better, A or B?" Pairwise judgments are often more reliable than absolute scores. But judges can prefer whichever answer comes **first** (position bias). Test it by swapping the order:

```python
def biased_judge(first, second):        # a judge that always prefers the first answer it sees
    return "first"

a, b = "Reply from prompt v1", "Reply from prompt v2"
v1 = biased_judge(a, b)                  # A shown first
v2 = biased_judge(b, a)                  # B shown first
consistent = (v1 == "first") == (v2 == "second")
print(v1, v2, "| consistent:", consistent)
```

Output:

```text
first first | consistent: False
```

A fair judge picks the **same reply** both ways ("first" then "second"). Run every comparison in both orders and count only consistent verdicts.

## Calibrate against humans

Label 30–50 examples yourself, run the judge on them, and measure agreement. **Cohen's kappa** adjusts for agreement you'd get by chance:

```python
from sklearn.metrics import cohen_kappa_score

human = [1, 1, 0, 1, 0, 1, 1, 0, 1, 1]      # 1 = good reply, 0 = bad, by a person
judge_labels = [1, 1, 0, 1, 1, 1, 1, 0, 1, 0]
agree = sum(h == j for h, j in zip(human, judge_labels)) / len(human)
print(f"agreement {agree:.0%}, kappa {cohen_kappa_score(human, judge_labels):.2f}")
```

Output:

```text
agreement 80%, kappa 0.52
```

80% agreement sounds great, but kappa shows it's only moderate once chance is removed. Read the disagreements, improve the rubric (clearer items, examples of 0 and 1), and measure again. Rough guide: kappa above 0.6 is good, above 0.8 is excellent.

## Judge best practices

- Ask for the **reason before the verdict**, or alongside it: it improves quality and helps you debug.
- Use **binary or small scales** (0/1, 1–3) rather than 1–10.
- Give **examples** of passing and failing answers in the judge prompt.
- Use a **strong model** as judge, and keep the judge prompt fixed while you compare versions.
- Re-calibrate when you change the judge prompt or model.
- Keep humans reviewing a weekly sample.

::tip Never let the agent grade its own homework in production metrics. Use a separate judge prompt, and ideally check the judge against human labels before trusting its numbers.

## Summary

- LLM judges grade what code can't: policy, tone, faithfulness.
- Use yes/no rubric items with critical items as hard gates, and give the judge the facts.
- Pairwise comparisons need both orders to cancel position bias.
- Calibrate with human labels: agreement and Cohen's kappa.
