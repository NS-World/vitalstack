---
title: "Permissions and Human Approval"
slug: agent-safety-permissions-approval
subject: agent-safety
lesson: 2
difficulty: advanced
excerpt: Decide what an agent may do on its own: a permission policy with allow, ask and deny, deny-by-default for unknown tools, amount limits, approval prompts with clear previews, scoped credentials and an audit log.
---

An agent with tools is like a new employee with a company card. You decide what they can do alone (look things up), what needs a manager's OK (refunds), and what they may never do (delete customer accounts). In code, that's a **permission policy**, enforced outside the model where no prompt can change it.

::scene The refund desk
📜 | Rules | Byte writes the policy: search freely, ask before refunds, never delete accounts. | byte
✅ | Small refund | Chip asks to refund ₹799. A preview pops up: Dev clicks Approve. | chip
⛔ | Too big | A ₹25,000 refund is over the limit. Denied outright, no approval button. | bugsy
🧾 | Logged | Every request, decision and approver is written in the audit log. | dev
::end

## Allow, ask, deny

```python
POLICY = {"search_orders": "allow", "draft_reply": "allow", "send_reply": "ask",
          "refund": "ask", "delete_account": "deny"}
LIMITS = {"refund": 2000}                                # rupees
audit = []

def decide(tool, args):
    rule = POLICY.get(tool, "deny")                       # unknown tools are denied by default
    if tool in LIMITS and args.get("amount", 0) > LIMITS[tool]:
        rule = "deny"
    audit.append({"tool": tool, "args": args, "decision": rule})
    return rule

requests = [("search_orders", {}), ("refund", {"amount": 799}), ("refund", {"amount": 25000}),
            ("delete_account", {}), ("export_all_users", {})]
for tool, args in requests:
    print(f"{tool:<17} {str(args):<18} -> {decide(tool, args)}")
print(len(audit), "entries in the audit log")
```

Output:

```text
search_orders     {}                 -> allow
refund            {'amount': 799}    -> ask
refund            {'amount': 25000}  -> deny
delete_account    {}                 -> deny
export_all_users  {}                 -> deny
5 entries in the audit log
```

Notice `export_all_users`: it isn't in the policy, so it's denied. New tools must be reviewed and added on purpose.

## Approval prompts people can actually judge

"Agent wants to call refund(order_id='1042', amount=799). Allow?" is better than nothing, but a good preview speaks human:

::walk python | A clear approval preview
action = {"tool": "refund", "order": "1042", "amount": 799, "reason": "arrived damaged"}
preview = f"Refund ₹{action['amount']} for order {action['order']} ({action['reason']})?"
approved = True
print(preview, "->", "executing" if approved else "cancelled")
::notes
1: The action the agent wants to take, with its reason. // vars: action=refund ₹799
2: Turn it into a sentence a busy person can judge in two seconds: what, how much, for what, why. // deep: Show what will change, not just the function call. For emails, show the full email; for file edits, show the diff; for payments, the amount and recipient. Approval fatigue is real: if people approve everything without reading, the control is useless, so ask only for actions that matter.
3: The person clicked Approve (in a real app this waits for a button, a Slack reply or an app notification). // vars: approved=True
4: Only now does the refund run. // out: Refund ₹799 for order 1042 (arrived damaged)? -> executing
::end

## Tiers of actions

| Tier | Examples | Default |
|---|---|---|
| Read | search, look up, read files in the project | Allow |
| Reversible write | draft, create a branch, add a label | Allow or ask |
| Outbound | send email, post publicly, call external APIs | Ask |
| Destructive | delete, overwrite, force-push | Ask or deny |
| Financial | refunds, payments, purchases | Ask with limits, or deny |

## Scoped credentials

The policy only works if the agent **can't** go around it:

- Give the agent its **own** credentials, not yours, with only the permissions it needs (a read-only database user, a GitHub token limited to one repository).
- Use **short-lived** tokens that expire.
- Keep secrets in the tool layer: the model never sees API keys.

## The audit log

Record every request: time, agent, tool, arguments (redacted), decision, approver and result. It answers "who refunded this?" and helps you tune the policy, for example noticing that people approve 100% of `send_reply` requests, so maybe it can be `allow` for known customers.

## Agent tools that already do this

Agentic coding tools have permission modes (ask before edits or commands, allow-lists of commands, read-only plan modes); agent SDKs have allowed-tool lists, hooks and approval callbacks; LangGraph has interrupts. Use these features rather than inventing your own, and configure them strictly at first.

::tip Start strict: everything that writes is "ask". After a week, look at the audit log and relax only the rules that were approved every single time.

## Summary

- Enforce a policy in code: allow, ask or deny, with deny-by-default.
- Add limits (amounts, counts) and clear, human-readable approval previews.
- Give agents scoped, short-lived credentials so they can't bypass the policy.
- Keep an audit log and use it to tune permissions.
