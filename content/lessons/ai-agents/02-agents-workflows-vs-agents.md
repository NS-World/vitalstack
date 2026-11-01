---
title: "Workflows vs Agents: When Not to Use an Agent"
slug: agents-workflows-vs-agents
subject: ai-agents
lesson: 2
difficulty: intermediate
excerpt: Most AI features should be a fixed workflow, not an agent. Learn the difference, the cost of each, and a simple test for choosing, with runnable Python.
---

Agents are exciting, so the first instinct is to make everything an agent. Experienced AI engineers do the opposite: they start with the **simplest thing that works** and add freedom only when they must.

There are two ways to build with a language model:

| | Workflow | Agent |
|---|---|---|
| Who decides the steps? | **You**, in code | **The model**, at run time |
| Number of model calls | Fixed and known | Varies, sometimes a lot |
| Cost and speed | Predictable | Hard to predict |
| Easy to test? | Yes: same steps every time | Harder: paths differ |
| Good for | Known, repeatable jobs | Open-ended jobs with unknown steps |

## A workflow: steps you wrote down

A support inbox gets thousands of emails a day. Each one must be sorted into **billing**, **technical** or **other**, and billing emails must get the order ID pulled out. The steps never change, so we write them as a fixed pipeline. Again we use small stand-in functions for the model calls:

```python
import re

def classify(email):            # stands in for one model call
    text = email.lower()
    if "refund" in text or "payment" in text:
        return "billing"
    if "error" in text or "crash" in text:
        return "technical"
    return "other"

def find_order_id(email):       # plain code: no model needed at all
    m = re.search(r"VS-\d{4}", email)
    return m.group(0) if m else None

def workflow(email):
    calls = 1
    team = classify(email)
    order = find_order_id(email) if team == "billing" else None
    return team, order, calls

print(workflow("Hi, I want a refund for order VS-2041, payment went twice"))
print(workflow("The Python compiler shows an error on lesson 3"))
```

Output:

```text
('billing', 'VS-2041', 1)
('technical', None, 1)
```

One model call per email, every time. Notice that finding the order ID didn't need a model at all: a **regular expression** does it perfectly, for free. A good rule: **use a model only for the step that needs understanding language**.

## The cost difference

Say each model call costs ₹0.20 and takes 1.5 seconds. Our workflow makes exactly one call. An agent that "thinks" about each email might make 3 to 6 calls. Here is what that means for 10,000 emails a day:

::walk python | Workflow vs agent: the bill for one day
emails = 10000
price = 0.20
workflow_cost = emails * 1 * price
agent_cost = emails * 4 * price
print("workflow: Rs", workflow_cost)
print("agent:    Rs", agent_cost)
print("extra per month: Rs", (agent_cost - workflow_cost) * 30)
::notes
1: **10,000** support emails arrive every day. // vars: emails=10000
2: Each model call costs about **₹0.20** (a typical small-model price). // deep: Real prices depend on the model and on how many tokens go in and out. Longer prompts and histories cost more, which is another reason agents (whose history grows each round) cost more. // vars: price=0.2
3: The workflow makes **1 call** per email: ₹2,000 a day. // vars: workflow_cost=2000.0
4: An agent that averages **4 calls** per email (plan, tool, check, answer): ₹8,000 a day. // deep: Agents often re-read the whole growing history on every call, so the real gap is usually even bigger than 4×. // vars: agent_cost=8000.0
5: Print the workflow bill. // out: workflow: Rs 2000.0
6: Print the agent bill. // out: agent:    Rs 8000.0
7: Over a month the agent costs **₹1.8 lakh more**, for a job that has the same steps every time. // out: extra per month: Rs 180000.0
::end

## So when IS an agent the right tool?

Use an agent when you **can't write the steps down in advance**, because they depend on what the model finds along the way:

- "Research these 5 competitors and compare their pricing" (which pages to open depends on what each search returns).
- "Fix the failing test in this repository" (which files to read depends on the error).
- "Plan a 3-day trip under ₹15,000 with trains that actually have seats" (each choice changes the next search).

## The two-question test

Before building an agent, ask:

1. **Can I list the steps?** If yes, write a workflow. You can still use a model inside a step.
2. **Is a wrong step expensive?** If an agent's mistake costs money, data or trust (sending emails, paying, deleting), keep a human approval step, or don't use an agent.

```python
def choose(can_list_steps, mistakes_costly):
    if can_list_steps:
        return "workflow"
    if mistakes_costly:
        return "agent + human approval"
    return "agent"

print(choose(True, False))
print(choose(False, True))
print(choose(False, False))
```

Output:

```text
workflow
agent + human approval
agent
```

::tip Many great products are **workflows with one small agent inside**: a fixed pipeline handles 90% of cases, and only the hard, unusual ones go to an agent. You get predictable cost for most traffic and flexibility where it matters.

## Summary

- A **workflow** follows steps you wrote; an **agent** chooses its own steps.
- Workflows are cheaper, faster and easier to test. Start there.
- Use plain code (like a regex) wherever a model isn't needed.
- Pick an agent only when the steps can't be known in advance, and add human approval for costly actions.

Next: you'll build a complete agent with two tools, error handling and a step limit, in about 40 lines of Python.
