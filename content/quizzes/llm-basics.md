## llm-tokens-next-token
Q: What does a large language model do at every step of writing an answer?
- [x] Predicts the next token from the text so far
- [ ] Looks the answer up in a database
- [ ] Writes the whole answer at once
> It predicts one token, adds it to the text, and predicts again.
Q: About how many tokens are 100 English words?
- [ ] 50
- [x] About 130
- [ ] 1,000
> A rough rule: 1 token ≈ ¾ of a word.
Q: Why is "learning" sometimes split into " learn" + "ing"?
- [x] Common pieces let the model handle words it has never seen whole
- [ ] To make the text shorter
- [ ] Because "ing" is a mistake
> Splitting rare words into common parts keeps the vocabulary small and flexible.

## llm-embeddings
Q: What is an embedding?
- [ ] A picture inside a web page
- [x] A list of numbers that represents meaning
- [ ] An encrypted password
> Similar meanings get vectors that point in similar directions.
Q: A cosine similarity of about 1 means the two texts are…
- [x] Very similar in meaning
- [ ] Unrelated
- [ ] Opposite in length
> 1 = same direction, 0 = unrelated.
Q: How does semantic search find documents?
- [ ] By matching exact keywords only
- [x] By finding the document vectors closest to the question's vector
- [ ] By picking the newest documents
> Embed the question and documents, then return the nearest ones.

## llm-attention
Q: What does attention let a token do?
- [x] Look at other tokens and decide which matter for its meaning
- [ ] Skip the rest of the sentence
- [ ] Translate itself into another language
> In "river bank", attention to "river" tells the model which bank is meant.
Q: After softmax, the attention weights…
- [ ] Can be negative
- [x] Are all positive and add up to 1
- [ ] Are always equal
> Softmax turns any scores into percentages that sum to 100%.
Q: Doubling the length of a prompt makes attention work grow roughly…
- [ ] 2×
- [x] 4×
- [ ] 10×
> Every token is compared with every other token, so the work grows with the square of the length.

## llm-context-temperature
Q: Which temperature suits extracting data from invoices?
- [x] 0 to 0.2
- [ ] 1.2
- [ ] 2.0
> Low temperature gives focused, repeatable answers.
Q: What does top-k sampling do?
- [ ] Picks the k-th token every time
- [x] Keeps only the k most likely tokens before sampling
- [ ] Makes the answer k tokens long
> Removing very unlikely tokens stops rare bad picks.
Q: A chatbot "forgets" what you said an hour ago. The most likely reason is…
- [ ] The server deleted your account
- [x] The chat grew past the context window, so old messages were dropped or summarised
- [ ] Temperature was too low
> The model can only see what fits in its context window.

## llm-hallucinations
Q: What is a hallucination?
- [ ] A slow answer
- [x] Confident, fluent output that is not true
- [ ] An answer in the wrong language
> The model produces likely-looking text even when it doesn't know the facts.
Q: Which request is most likely to cause a hallucination?
- [ ] Summarise this paragraph I pasted
- [x] Give me exactly 5 research papers with their DOIs
- [ ] Fix the grammar in this sentence
> Exact names, citations and links are high-risk when the facts aren't provided.
Q: What is "grounding"?
- [x] Putting the facts in the prompt and asking the model to answer only from them
- [ ] Turning the temperature up
- [ ] Training the model again
> Grounded answers can cite their source, and the model may say "I don't know".
