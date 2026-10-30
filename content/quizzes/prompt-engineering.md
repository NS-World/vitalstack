## prompt-engineering-introduction
Q: How does an LLM produce an answer?
- [ ] It searches a database of answers
- [x] It predicts the next token again and again
- [ ] It copies a web page
> Tokens are words or parts of words.
Q: Why can the same prompt give different answers?
- [x] The next token is chosen with some randomness
- [ ] The AI is broken
- [ ] The internet changed
> Temperature controls how much.
Q: What is a hallucination?
- [ ] A slow answer
- [x] A confident answer that is false
- [ ] A refusal
> Always verify important facts.

## prompt-clear-instructions
Q: Which prompt is clearest?
- [ ] Make a study plan
- [x] Make a 4-week SQL study plan for a BCom student, 1 hour a day, as a table
- [ ] SQL plan please
> Task, context, constraints and format.
Q: Why put pasted text inside triple quotes or tags?
- [x] So the AI can tell your instructions from the material
- [ ] To make it bold
- [ ] It's required by every tool
> Clear separation avoids confusion.
Q: The first answer isn't right. Best next step?
- [ ] Give up
- [x] Reply with specific changes and iterate
- [ ] Start a new tool
> Treat the first answer as a draft.

## prompt-roles-context
Q: What does "You are a senior Java developer reviewing code" add?
- [x] A role that sets expertise and tone
- [ ] A format
- [ ] An example
> Roles shape how the answer is written.
Q: Answers are full of jargon for a beginner. What do you add?
- [ ] More examples of jargon
- [x] The audience: "for someone with no technical background"
- [ ] A longer word limit
> The audience sets depth and vocabulary.
Q: Why save prompt templates?
- [x] Good prompts can be reused in seconds with new details
- [ ] Tools require them
- [ ] They make answers longer
> Fill in the blanks for each new topic.

## prompt-few-shot
Q: What is few-shot prompting?
- [ ] Asking very short questions
- [x] Including examples of input and desired output
- [ ] Using several AI tools
> The AI copies the pattern.
Q: All your examples are positive reviews. Risk?
- [x] The AI leans towards "positive"
- [ ] No risk
- [ ] The AI refuses
> Use varied examples.
Q: How many examples are usually enough?
- [ ] 50 or more
- [x] 2 to 5
- [ ] Exactly 1
> More rarely helps and costs tokens.

## prompt-step-by-step
Q: For a tricky maths question, what helps?
- [x] Ask it to work step by step, then give the final answer
- [ ] Ask for a one-word answer
- [ ] Use capital letters
> Steps give room to reason and let you check.
Q: What is a prompt chain?
- [ ] One very long prompt
- [x] Several smaller prompts where each output feeds the next
- [ ] A prompt with links
> Review between steps.
Q: You're unsure what details matter. Good prompt?
- [x] Ask the AI to ask you clarifying questions first
- [ ] Guess and send
- [ ] Ask for the longest answer
> Let it gather what it needs.

## prompt-output-formats
Q: You'll paste the answer into Excel. What format?
- [x] A table or CSV
- [ ] A poem
- [ ] Long paragraphs
> Tables paste into spreadsheets.
Q: A program will read the answer. What format?
- [ ] Bullet points
- [x] JSON with exact keys
- [ ] A screenshot
> Programs read JSON easily.
Q: Which length instruction is followed most reliably?
- [ ] "Short"
- [x] "Under 80 words, 3 bullet points"
- [ ] "Not too long"
> Precise limits work better.

## prompt-ai-for-learning
Q: Your code fails. What should you paste?
- [ ] "It's not working"
- [x] The code, the full error, what you expected and what happened
- [ ] Only the error's first word
> Details make the answer accurate.
Q: Best habit when learning with AI?
- [x] Try first, then ask for a hint, not the full answer
- [ ] Copy every answer without reading
- [ ] Never test the code
> Hints keep you learning.
Q: How can AI help with interviews?
- [x] Role-play an interviewer who scores your answers
- [ ] Attend the interview for you
- [ ] Guarantee a job
> Practise with feedback.

## prompt-limits-safety
Q: Which should you never paste into a chatbot?
- [x] Passwords, OTPs, API keys and ID numbers
- [ ] A public news article
- [ ] Your own practice code
> Use placeholders for sensitive data.
Q: How do you reduce hallucinations when asking about a document?
- [x] Paste it and say "answer only from the text above; say if it's not there"
- [ ] Ask the AI to be confident
- [ ] Use a longer prompt
> Ground the answer in your source.
Q: What is prompt injection?
- [ ] A fast prompt
- [x] Hidden instructions inside content that try to hijack an AI app
- [ ] A coding error
> Separate instructions from data and limit access.
