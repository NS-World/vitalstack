---
title: "Signature Project 6: Kirana Restock Agent"
slug: signature-kirana-restock-agent
subject: ai-signature
lesson: 2
difficulty: advanced
excerpt: Build an AI agent that helps a neighbourhood kirana store restock. It reads stock and sales, compares supplier prices, and drafts a purchase order with Claude using tools, but a budget cap and the shop owner's approval sit between the agent and any real order.
---

Ramesh runs a kirana store in Pune. Every Sunday night he counts what is running low, remembers which supplier was cheaper last time, and sends orders on WhatsApp. It takes two hours and he still runs out of atta on Wednesdays. In this project you build an **agent** that does the boring part (checking stock, working out quantities, comparing prices) and drafts the order, while **Ramesh stays in charge of the money**.

::scene The Sunday-night restock
🏪 | The shop | 8 everyday products, two suppliers, one tired owner. | dev
🧮 | The maths | How much will sell before the next delivery arrives? Order only the gap. | chip
🛠️ | The tools | The agent can look, calculate and compare. It cannot spend. | bugsy
✅ | The approval | One message to Ramesh: "Order these 5 items for ₹3,866? Yes / No". | byte
::end

## The shop's data

```python
STOCK = {"atta_10kg": 3, "rice_5kg": 6, "toor_dal_1kg": 4, "sugar_1kg": 12,
         "sunflower_oil_1l": 5, "tea_500g": 2, "salt_1kg": 15, "biscuits_pack": 30}
DAILY_SALES = {"atta_10kg": 1.2, "rice_5kg": 1.5, "toor_dal_1kg": 2.0, "sugar_1kg": 2.5,
               "sunflower_oil_1l": 1.8, "tea_500g": 0.9, "salt_1kg": 1.0, "biscuits_pack": 6.0}
SUPPLIERS = {
    "Sharma Traders": {"lead_days": 2, "prices": {"atta_10kg": 420, "rice_5kg": 310, "toor_dal_1kg": 148,
                        "sugar_1kg": 44, "sunflower_oil_1l": 152, "tea_500g": 245, "salt_1kg": 22, "biscuits_pack": 9}},
    "Metro Wholesale": {"lead_days": 4, "prices": {"atta_10kg": 398, "rice_5kg": 318, "toor_dal_1kg": 139,
                         "sugar_1kg": 42, "sunflower_oil_1l": 149, "tea_500g": 251, "salt_1kg": 20, "biscuits_pack": 8}},
}
print(len(STOCK), "products,", len(SUPPLIERS), "suppliers")
```

Output:

```text
8 products, 2 suppliers
```

## The restock rule

A shopkeeper's rule of thumb, written as maths: order enough to last until the delivery arrives **plus a few safety days**, minus what is already on the shelf.

::walk python | How much atta to order
daily = 1.2
lead_days = 4
safety_days = 3
on_shelf = 3
need = daily * (lead_days + safety_days) - on_shelf
print(max(0, round(need)))
::notes
1: The shop sells about 1.2 bags of atta a day. // vars: daily=1.2
2: The cheaper supplier takes 4 days to deliver. // vars: lead_days=4
3: Keep 3 extra days as a cushion for busy days. // deep: Safety stock is the price of uncertainty. A longer or less reliable delivery time needs a bigger cushion. This is the same idea big retailers use, just with more data. // vars: safety_days=3
4: 3 bags are on the shelf. // vars: on_shelf=3
5: 1.2 × 7 − 3 = 5.4 bags are needed. // vars: need=5.4
6: Order 5 bags (never a negative order). // out: 5
::end

## Tools: what the agent is allowed to do

An agent is only as safe as its tools. Ours can **read** stock, **calculate** quantities and **compare** prices. The only tool that touches money, `place_order`, refuses unless the owner approved that exact order.

```python
SAFETY_DAYS = 3
BUDGET = 6000          # the most the agent may propose in one order, in rupees

def check_stock():
    return {item: {"on_shelf": qty, "sells_per_day": DAILY_SALES[item]} for item, qty in STOCK.items()}

def reorder_quantity(item, supplier):
    days = SUPPLIERS[supplier]["lead_days"] + SAFETY_DAYS
    return max(0, round(DAILY_SALES[item] * days - STOCK[item]))

def compare_suppliers(item):
    rows = []
    for name, s in SUPPLIERS.items():
        qty = reorder_quantity(item, name)
        rows.append({"supplier": name, "qty": qty, "unit_price": s["prices"][item],
                     "cost": qty * s["prices"][item], "arrives_in_days": s["lead_days"]})
    return sorted(rows, key=lambda r: r["cost"])

APPROVED = set()       # order IDs the owner said yes to

def place_order(order_id, supplier, lines):
    if order_id not in APPROVED:
        return {"error": "Not approved by the owner. Ask for approval first."}
    total = sum(qty * SUPPLIERS[supplier]["prices"][item] for item, qty in lines.items())
    if total > BUDGET:
        return {"error": f"Total ₹{total} is above the ₹{BUDGET} budget."}
    return {"status": "sent", "supplier": supplier, "total": total}

print(compare_suppliers("atta_10kg"))
print(place_order("ORD-1", "Metro Wholesale", {"atta_10kg": 5}))
```

Output:

```text
[{'supplier': 'Sharma Traders', 'qty': 3, 'unit_price': 420, 'cost': 1260, 'arrives_in_days': 2}, {'supplier': 'Metro Wholesale', 'qty': 5, 'unit_price': 398, 'cost': 1990, 'arrives_in_days': 4}]
{'error': 'Not approved by the owner. Ask for approval first.'}
```

Two things to notice. For atta, Metro is cheaper per bag (₹398 against ₹420), **but** its slower delivery means ordering 5 bags instead of 3 to stay safe, so Sharma's order is cheaper overall (₹1,260 against ₹1,990). Comparing only unit prices would have chosen wrong. And `place_order` refuses: no approval, no order. That rule lives **in the code**, so no prompt or clever message can talk the agent past it.

## A dry run without a model

Before connecting a model, run the plan the way an agent would: look, calculate, choose, draft, then ask. This proves the tools work and gives you a result to compare the model's plan with.

```python
def draft_order():
    low = [item for item, info in check_stock().items()
           if info["on_shelf"] < info["sells_per_day"] * (2 + SAFETY_DAYS)]
    plan = {}
    for item in low:
        best = compare_suppliers(item)[0]
        if best["qty"] > 0:
            plan.setdefault(best["supplier"], {})[item] = best["qty"]
    return plan

plan = draft_order()
for supplier, lines in plan.items():
    total = sum(q * SUPPLIERS[supplier]["prices"][i] for i, q in lines.items())
    print(supplier, lines, f"₹{total}")
```

Output:

```text
Sharma Traders {'atta_10kg': 3, 'rice_5kg': 2, 'toor_dal_1kg': 6, 'sunflower_oil_1l': 4, 'tea_500g': 2} ₹3866
```

## Ask the owner, then act

The approval is a plain question that a busy shopkeeper can answer in one tap.

```python
def approval_message(plan):
    lines = []
    grand = 0
    for supplier, items in plan.items():
        total = sum(q * SUPPLIERS[supplier]["prices"][i] for i, q in items.items())
        grand += total
        lines.append(f"{supplier}: " + ", ".join(f"{q} x {i}" for i, q in items.items()) + f" = ₹{total}")
    return "Restock order\n" + "\n".join(lines) + f"\nTotal ₹{grand}. Reply YES to send."

print(approval_message(plan))
APPROVED.add("ORD-2")                         # Ramesh replied YES
for supplier, items in plan.items():
    print(place_order("ORD-2", supplier, items))
```

Output:

```text
Restock order
Sharma Traders: 3 x atta_10kg, 2 x rice_5kg, 6 x toor_dal_1kg, 4 x sunflower_oil_1l, 2 x tea_500g = ₹3866
Total ₹3866. Reply YES to send.
{'status': 'sent', 'supplier': 'Sharma Traders', 'total': 3866}
```

## Connect Claude with tool use

Now Claude plans instead of our fixed `draft_order`, so it can handle requests like *"Diwali is next week, plan for double sales of sugar and oil"*. We describe the tools, run the standard tool-use loop, and **leave `place_order` out of Claude's tools entirely**: the model drafts, the human approves, and only our code places the order.

```python
import json
import anthropic

client = anthropic.Anthropic()          # reads ANTHROPIC_API_KEY from the environment

TOOLS = [
    {"name": "check_stock", "description": "Current shelf stock and average daily sales for every product.",
     "input_schema": {"type": "object", "properties": {}}},
    {"name": "compare_suppliers", "description": "For one product, the quantity to order and the total cost at each supplier, cheapest first.",
     "input_schema": {"type": "object", "properties": {"item": {"type": "string", "enum": list(STOCK)}}, "required": ["item"]}},
]
RUN = {"check_stock": lambda args: check_stock(),
       "compare_suppliers": lambda args: compare_suppliers(args["item"])}

SYSTEM = (f"You help a small Indian grocery store restock. Use the tools to check stock and compare suppliers. "
          f"Propose one order per supplier as JSON: {{supplier: {{item: qty}}}}. Keep the total under ₹{BUDGET}. "
          "You cannot place orders: the owner approves every order.")

def agent(request, max_turns=10):
    messages = [{"role": "user", "content": request}]
    for _ in range(max_turns):
        response = client.messages.create(model="claude-opus-5-5", max_tokens=4000,
                                          system=SYSTEM, tools=TOOLS, messages=messages)
        if response.stop_reason != "tool_use":
            return next((b.text for b in response.content if b.type == "text"), "")
        messages.append({"role": "assistant", "content": response.content})
        results = []
        for block in response.content:
            if block.type == "tool_use":
                try:
                    output = json.dumps(RUN[block.name](block.input))
                    results.append({"type": "tool_result", "tool_use_id": block.id, "content": output})
                except Exception as exc:          # a bad tool call becomes a message, not a crash
                    results.append({"type": "tool_result", "tool_use_id": block.id,
                                    "content": f"Error: {exc}", "is_error": True})
        messages.append({"role": "user", "content": results})
    return "Stopped: too many steps."

print(agent("It's Sunday night. Plan this week's restock."))
```

Run it with your API key, then **compare Claude's plan with the dry run**. Where they differ, ask why: did Claude spot something (like the Diwali request) or make a mistake? Then pass its plan to `approval_message` and `place_order` exactly as before.

Notice the safety design:

- **Least privilege:** Claude only gets read-and-calculate tools. Spending money is not a tool it can call.
- **A step limit** (`max_turns`) stops a confused loop from running up a bill.
- **Errors become messages**, so the model can correct itself instead of the program crashing.

::tip This pattern (agent drafts, rules check, human approves, code executes) is how real businesses use agents for money, refunds and emails. Show it in interviews: it proves you think about safety, not just demos.

## Make it yours

- **Expiry dates:** add a `days_to_expiry` field and stop the agent from over-ordering items like milk and bread.
- **Festival mode:** pass "expected demand multipliers" (for example sugar ×2 before Diwali) as a tool and test whether Claude uses it.
- **WhatsApp flow:** send the approval message through a messaging API sandbox and wait for "YES" before `place_order`.

## Portfolio checklist

- [ ] Restock maths (lead time, safety days, current stock) explained with one example
- [ ] Read-only tools for the model; the order tool guarded by approval and a budget in code
- [ ] A dry run without a model as a baseline plan
- [ ] Claude tool-use loop with a step limit and error handling
- [ ] An approval message a busy owner can answer in one tap
- [ ] A comparison of Claude's plan with the dry run, and what you learned
