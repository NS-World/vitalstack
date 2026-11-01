---
title: "Capstone: Customer Support Agent with Tools"
slug: capstone-agent-support
subject: agent-capstones
lesson: 2
difficulty: advanced
excerpt: Build a support agent that actually resolves issues: look up orders, apply the refund policy in code, ask a human before money moves, escalate angry or unusual cases, and prove it works with an end-state eval.
---

The Gen AI track built an FAQ bot that **answers** questions. This agent **acts**: it looks up the order, decides if a refund is allowed, asks a human before sending money, and escalates when needed. It's what companies mean when they say "AI agent for support".

::scene Three customers, three outcomes
📦 | Order 1042 | Damaged, delivered 3 days ago. Chip checks the policy: refund allowed. A human approves ₹799. | chip
⌛ | Order 1044 | Delivered 12 days ago, outside the 7-day window. Byte explains politely and offers a repair centre. | byte
🚚 | Order 1043 | Still in transit. Dev's agent shares the tracking date, no refund needed. | dev
😡 | Angry message | "This is the worst service ever!" Bugsy escalates straight to a human. | bugsy
::end

## Tools and policy

```python
ORDERS = {"1042": {"status": "delivered", "days_since": 3, "amount": 799},
          "1043": {"status": "shipped", "eta": "2026-11-04", "amount": 1299},
          "1044": {"status": "delivered", "days_since": 12, "amount": 2499}}
LOG = []

def lookup_order(order_id):
    return ORDERS.get(order_id)

def refund_allowed(order):
    return order["status"] == "delivered" and order["days_since"] <= 7

def human_approves(order_id, amount):                    # stand-in for a real approval button
    return amount <= 1000

def refund(order_id):
    ORDERS[order_id]["status"] = "refunded"
    return f"Refund of ₹{ORDERS[order_id]['amount']} started for order {order_id}."

def escalate(reason):
    LOG.append(f"escalated: {reason}")
    return "I've passed this to a senior colleague, who will contact you within 2 hours."
```

## The agent's decision loop

In production the **model** chooses the tools; here a small function plays the model so you can see each decision. The policy and the approval step are real code that no prompt can change:

```python
ANGRY = ("worst", "useless", "fraud", "complaint")

def support_agent(message, order_id):
    if any(w in message.lower() for w in ANGRY):
        return escalate("upset customer")
    order = lookup_order(order_id)
    if order is None:
        return "I couldn't find that order. Could you check the number on your invoice?"
    if order["status"] == "shipped":
        return f"Your order is on the way and should arrive by {order['eta']}."
    if "refund" in message.lower() or "damaged" in message.lower():
        if not refund_allowed(order):
            return "This order is outside our 7-day return window, but I can book a free repair-centre visit."
        if not human_approves(order_id, order["amount"]):
            return escalate(f"refund of ₹{order['amount']} needs a manager")
        return refund(order_id)
    return "How can I help with this order?"

chats = [("My phone arrived damaged, refund please", "1042"), ("Refund please, it stopped working", "1044"),
         ("Where is my parcel?", "1043"), ("This is the worst service ever!", "1042"), ("Refund?", "9999")]
for message, order_id in chats:
    print(f"{order_id}: {support_agent(message, order_id)}")
print(LOG)
```

Output:

```text
1042: Refund of ₹799 started for order 1042.
1044: This order is outside our 7-day return window, but I can book a free repair-centre visit.
1043: Your order is on the way and should arrive by 2026-11-04.
1042: I've passed this to a senior colleague, who will contact you within 2 hours.
9999: I couldn't find that order. Could you check the number on your invoice?
['escalated: upset customer']
```

::walk python | The refund policy, in code
order = {"status": "delivered", "days_since": 12, "amount": 2499}
within_window = order["days_since"] <= 7
allowed = order["status"] == "delivered" and within_window
print(within_window, allowed)
::notes
1: Order 1044, delivered 12 days ago. // vars: order=delivered, 12 days, ₹2499
2: The policy: returns within 7 days of delivery. // vars: within_window=False
3: Both conditions must hold. // deep: The model never decides whether a refund is allowed: it calls a tool, and the tool applies the rule. Prompts can be talked around ("my grandmother says you always refund"); an if statement can't. The model's job is to explain the result kindly.
4: Not allowed, so the agent offers the repair-centre option instead. // out: False False
::end

## Prove it with an end-state eval

Check what **happened** to the orders, not just what the agent said:

```python
expected_status = {"1042": "refunded", "1043": "shipped", "1044": "delivered"}
results = {oid: ORDERS[oid]["status"] == want for oid, want in expected_status.items()}
print(results, "| escalations:", len(LOG))
```

Output:

```text
{'1042': True, '1043': True, '1044': True} | escalations: 1
```

Only the eligible order changed, and one escalation happened, both exactly as intended.

## The real version

1. Turn `lookup_order`, `refund` and `escalate` into tools with JSON schemas (Tool Use module); keep `refund_allowed` and the approval inside the `refund` tool.
2. System prompt: tone, what the agent can and can't do, "always look up the order before answering".
3. Permission policy: `lookup_order` allow, `refund` ask (with a preview), anything else deny (Safety module).
4. Memory: load the customer's previous tickets (Memory module).
5. Evals: 30 conversations including tricky ones ("refund but I already got a replacement", injection attempts), checked by end state + an LLM judge for tone.
6. Traces and metrics: resolution rate, escalation rate, refunds per day, cost per ticket.

::tip Write the policy as code first, and have the agent's tools enforce it. Then the prompt only needs to make the agent kind and clear, not to "remember" the rules.

## Summary

- A support agent acts through tools: look up, refund, escalate.
- Business rules and approvals live in tool code, not the prompt.
- Escalate upset or unusual cases quickly.
- Evaluate end states (what happened to orders), plus tone with a judge.
