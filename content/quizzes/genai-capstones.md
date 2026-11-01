## capstone-faq-support-bot
Q: When the best FAQ match has a low score, the bot should...
- [x] Hand off to a human
- [ ] Invent an answer
- [ ] Pick a random FAQ
> Low confidence → human follow-up.
Q: Why did 'when will my parcel arrive' fail with TF-IDF?
- [x] It shares no words with the delivery FAQ
- [ ] TF-IDF can't read questions
- [ ] The FAQ was empty
> Embeddings fix this by matching meaning.
Q: A routing test set gives you...
- [x] An accuracy number to improve and report
- [ ] A prettier UI
- [ ] Nothing useful
> Run it after every change.

## capstone-chat-with-pdfs
Q: Why keep the page number with each chunk?
- [x] To cite pages in the answer
- [ ] To sort chunks alphabetically
- [ ] It isn't needed
> Citations let users check facts.
Q: If retrieval finds nothing relevant, the app should...
- [x] Say the documents don't mention it
- [ ] Guess an answer
- [ ] Crash
> Refusing beats hallucinating.
Q: Verifying citations means checking that...
- [x] Every cited page was actually retrieved
- [ ] The PDF is large
- [ ] The answer is short
> A citation to an unretrieved page is unsupported.

## capstone-research-agent
Q: Why give a research agent a budget of tool calls?
- [x] To keep cost and time predictable
- [ ] To make it more creative
- [ ] Budgets aren't needed
> Agents always want one more search.
Q: Skipping already-seen URLs helps because...
- [x] Budget goes to new sources
- [ ] Duplicates are illegal
- [ ] It slows the agent
> Duplicates waste calls.
Q: Each claim in the final report should...
- [x] Cite a source
- [ ] Be as long as possible
- [ ] Avoid sources
> Uncited claims can't be checked.

## capstone-study-buddy-quizzes
Q: Why validate generated quiz questions?
- [x] They can have wrong option counts, duplicates or bad answer indexes
- [ ] Models never make mistakes
- [ ] To slow the app
> Drop or regenerate broken ones.
Q: In Leitner boxes, a wrong answer moves the card to...
- [x] Box 1, reviewed tomorrow
- [ ] The highest box
- [ ] The bin
> Right answers move up; wrong ones reset.
Q: Tracking accuracy per topic lets the buddy...
- [x] Find weak topics and practise them more
- [ ] Delete topics
- [ ] Change the font
> dicts at 33% need more practice.
