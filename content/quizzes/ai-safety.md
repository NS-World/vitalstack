## safety-prompt-injection
Q: Indirect prompt injection comes from...
- [x] Content the app reads, like web pages, emails or reviews
- [ ] Only the developer
- [ ] The model's weights
> The attacker never talks to your app directly.
Q: Why aren't keyword filters enough?
- [x] Attackers can rephrase, translate or hide the text
- [ ] Filters are illegal
- [ ] They block everything
> The Hinglish attack slipped past the English filter.
Q: Least privilege for a review summariser means...
- [x] Giving it no tools at all
- [ ] Giving it email and payment tools
- [ ] Sharing the admin password
> It can't misuse powers it doesn't have.

## safety-guardrails-moderation-privacy
Q: Why redact PAN, Aadhaar and phone numbers before calling a model?
- [x] So raw personal data never leaves your server or lands in logs
- [ ] Models can't read numbers
- [ ] To make prompts longer
> Send only what the model needs.
Q: Where should a rule like 'the bot may never approve refunds' live?
- [x] In code, as an output guardrail
- [ ] Only in the prompt
- [ ] In a comment
> Prompts can be ignored; if statements can't.
Q: India's DPDP Act expects apps to...
- [x] Get consent, collect minimal data and allow deletion
- [ ] Store all data forever
- [ ] Share data freely
> A simple overview; check details with a lawyer.

## safety-testing-evaluating
Q: An eval is...
- [x] A fixed set of test cases with automatic scoring
- [ ] A single manual test
- [ ] A type of model
> Compare pass rates between versions.
Q: A 'must-not' check catches...
- [x] Forbidden content like a wrong fact or a coupon code
- [ ] Slow responses
- [ ] Spelling only
> e.g. 'COUPON10' must never appear.
Q: Where should new test cases come from?
- [x] Real questions, fixed bugs and attack attempts
- [ ] Only made-up easy questions
- [ ] Nowhere
> Grow the suite every time something breaks.
