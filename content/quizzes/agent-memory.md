## memory-short-term-context
Q: An agent's short-term memory is...
- [x] Whatever is in the context window for this call
- [ ] A file on disk
- [ ] The model's training data
> The model remembers nothing between calls.
Q: What usually fills an agent's context fastest?
- [x] Tool results
- [ ] The user's greeting
- [ ] The model's name
> Clear or summarise them after use.
Q: Why not just rely on a huge context window?
- [x] It costs more, is slower and details can get lost
- [ ] Huge windows don't exist
- [ ] It makes answers shorter
> Lean context is cheaper and often more accurate.

## memory-long-term-summaries
Q: A facts store should handle 'I moved to Bengaluru' by...
- [x] Updating the city fact, not adding a duplicate
- [ ] Keeping both cities forever
- [ ] Ignoring it
> One value per key, newest wins (or confirm).
Q: Which should NOT be remembered without clear consent?
- [x] Health and ID numbers
- [ ] Preferred language
- [ ] Learning goal
> Sensitive data needs consent or should be skipped.
Q: Users should be able to...
- [x] See, edit and delete what's remembered
- [ ] Never know memory exists
- [ ] Only add memories
> Control builds trust and meets privacy rules.

## memory-vector-embeddings
Q: Memory retrieval scores often combine...
- [x] Relevance, recency and importance
- [ ] Only alphabetical order
- [ ] File size and colour
> The best memories are relevant, fresh and important.
Q: With recency 0.9 per day, a week-old memory keeps about...
- [x] 48% of its recency score
- [ ] 90%
- [ ] 0%
> 0.9⁷ ≈ 0.478.
Q: Why filter memories by user id on every query?
- [x] So one user's memories never reach another
- [ ] To speed up typing
- [ ] It's optional
> Mixing users is a privacy bug.
