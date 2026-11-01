## coding-assistants-compared
Q: Which level of AI coding help edits many files and runs tests?
- [x] An agent
- [ ] Autocomplete
- [ ] Spell check
> Autocomplete finishes lines; agents complete tasks.
Q: A CLAUDE.md or rules file is used to...
- [x] Give the assistant your project conventions every time
- [ ] Store passwords
- [ ] Replace tests
> Build commands, style and 'never do' rules.
Q: When measuring time saved by AI, you must include...
- [x] Time spent reviewing and fixing its code
- [ ] Only generation time
- [ ] Nothing
> Sometimes AI costs more than it saves.

## coding-assistants-prompting-for-code
Q: The most powerful AI coding habit is...
- [x] Writing tests first and letting them judge the AI's code
- [ ] Accepting the first answer
- [ ] Long vague prompts
> Tests found 3 bugs in the first version instantly.
Q: Why say 'Don't change the tests' in a prompt?
- [x] Some assistants 'fix' failures by editing the tests
- [ ] Tests are read-only files
- [ ] It makes code faster
> The tests define correct behaviour.
Q: When the AI gets it wrong twice, it's often best to...
- [x] Rewrite your spec and start a fresh prompt
- [ ] Argue in the same thread forever
- [ ] Give up on tests
> A clearer prompt beats a long thread of corrections.

## coding-assistants-safe-ai-code
Q: Which query is safe against SQL injection?
- [x] db.execute("SELECT ... WHERE name = ?", (name,))
- [ ] f"SELECT ... WHERE name = '{name}'"
- [ ] "SELECT ... WHERE name = '" + name + "'"
> Placeholders treat input as a value, never as SQL.
Q: 'Slopsquatting' is when attackers...
- [x] Register package names that AI models invent
- [ ] Delete your repository
- [ ] Slow down your laptop
> Verify every new dependency on its registry.
Q: AI put an API key directly in the code. You should...
- [x] Move it to an environment variable and scan before committing
- [ ] Commit it anyway
- [ ] Post it in the README
> Leaked keys get abused fast.
