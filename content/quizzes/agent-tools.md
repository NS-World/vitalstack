## agent-tools-json-schemas
Q: Which schema keyword limits a field to a fixed list of values?
- [x] enum
- [ ] pattern
- [ ] items
> e.g. mode: video, audio or chat.
Q: additionalProperties: false prevents...
- [x] The model adding invented fields
- [ ] Required fields
- [ ] Descriptions
> It rejects anything not in the schema.
Q: Generating schemas from Python functions uses...
- [x] Type hints, defaults and docstrings
- [ ] The file name only
- [ ] Random guesses
> Defaults decide which fields are required.

## agent-tools-errors-retries-timeouts
Q: Which error is worth retrying?
- [x] 503 service busy
- [ ] 401 not allowed
- [ ] 400 invalid arguments
> Temporary errors may succeed on retry; permanent ones won't.
Q: Jitter in backoff means...
- [x] Adding randomness so many clients don't retry at the same instant
- [ ] Retrying instantly
- [ ] Never retrying
> It prevents synchronised retry storms.
Q: An idempotency key protects against...
- [x] A retried payment charging twice
- [ ] Slow networks
- [ ] Long prompts
> The server returns the first result for repeats.

## agent-tools-designing-tools
Q: Task-shaped tools are better than API-shaped ones because...
- [x] The agent needs fewer, clearer choices
- [ ] They have longer names
- [ ] They return more fields
> Merge overlapping tools into one.
Q: A tool result listing 240 orders with 60 fields should be...
- [x] Paginated and trimmed to the useful fields, with a 'more' note
- [ ] Sent as-is
- [ ] Deleted
> Compact results keep the context small.
Q: A helpful 'not found' result...
- [x] Suggests what to try next, like a near-match email
- [ ] Returns []
- [ ] Crashes the agent
> It saves a wasted step.
