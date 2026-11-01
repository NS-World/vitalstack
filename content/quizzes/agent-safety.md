## agent-safety-injection
Q: The 'lethal trifecta' is...
- [x] Private data + untrusted content + a way to send data out
- [ ] Three models in a row
- [ ] Slow, costly and large
> Remove one leg and attacks mostly fail.
Q: Taint tracking means...
- [x] After reading untrusted content, outbound actions need approval
- [ ] Colouring the UI
- [ ] Deleting all emails
> It doesn't need to detect the attack itself.
Q: Why filter URLs in an agent's reply?
- [x] An image link can leak data just by being displayed
- [ ] URLs are always safe
- [ ] To shorten replies
> Allow only known domains.

## agent-safety-permissions-approval
Q: A tool missing from the permission policy should be...
- [x] Denied by default
- [ ] Allowed by default
- [ ] Run twice
> New tools must be reviewed on purpose.
Q: A good approval preview...
- [x] Says what, how much and why in plain words
- [ ] Shows only the function name
- [ ] Hides the amount
> People must judge it in seconds.
Q: Agents should use credentials that are...
- [x] Their own, scoped and short-lived
- [ ] The admin's personal password
- [ ] Shared with everyone
> So they can't bypass the policy.

## agent-safety-sandboxes
Q: Running model code with env={} protects against...
- [x] The code reading your API keys from environment variables
- [ ] Infinite loops
- [ ] Slow disks
> No secrets inside the sandbox.
Q: Path checks in file tools should compare...
- [x] The resolved path against the workspace root
- [ ] The string with startswith
- [ ] Only the file extension
> ../ and symlinks fool string checks.
Q: docker run --network none means...
- [x] The code can't send anything to the internet
- [ ] No CPU is allowed
- [ ] The container has no files
> It removes the outbound channel.
