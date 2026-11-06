## signature-scam-sms-shield
Q: Rules scored perfectly on the first 24 messages. Why was that suspicious?
- [x] They were written while looking at those same messages
- [ ] Rules are always perfect
- [ ] The messages were all genuine
> Only a held-out set shows real performance.
Q: Why mask numbers before calling the API?
- [x] The model does not need OTPs, phone or account numbers
- [ ] To save money
- [ ] Claude cannot read digits
> Privacy by design: logs never contain personal data.
Q: What does the shield do when Claude's reply is not valid JSON?
- [x] Treats it as 'unsure' instead of crashing
- [ ] Deletes the message
- [ ] Marks it genuine
> A safe fallback keeps the app working.

## signature-kirana-restock-agent
Q: Which tool was deliberately NOT given to Claude?
- [x] place_order
- [ ] check_stock
- [ ] compare_suppliers
> Least privilege: the model drafts, the owner approves, code executes.
Q: Why can't a clever prompt make the agent spend money?
- [x] Approval and the budget are checked in code inside place_order
- [ ] Claude never reads prompts
- [ ] The supplier refuses orders
> Safety rules belong in code, not only in prompts.
Q: Why was Sharma Traders chosen for atta even though Metro is cheaper per bag?
- [x] Metro's slower delivery means ordering more bags, so the total is higher
- [ ] Sharma is in Pune
- [ ] Metro has no atta
> Compare total cost for a safe stock level, not just unit price.
