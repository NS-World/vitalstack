## nlp-cleaning-tokenising
Q: Tokenising means...
- [x] Splitting text into pieces like words or subwords
- [ ] Encrypting text
- [ ] Translating text
> Models work on tokens, not raw strings.
Q: Stop words are...
- [x] Very common words like 'the' or 'is' often removed for simple models
- [ ] Rude words
- [ ] Words at the end of a sentence
> Removing them helps bag-of-words models focus on content.
Q: Why be careful removing punctuation and emojis from reviews?
- [x] They can carry sentiment, like '!!!' or 😡
- [ ] They are always noise
- [ ] Models can't read them
> Cleaning choices depend on the task.

## nlp-tfidf-similarity
Q: TF-IDF gives a high weight to words that are...
- [x] Frequent in this document but rare across documents
- [ ] Common in every document
- [ ] Very short
> Rare-but-present words describe a document best.
Q: Cosine similarity of 1 between two vectors means...
- [x] They point in exactly the same direction
- [ ] They are unrelated
- [ ] One is empty
> Values near 0 mean little overlap.
Q: TF-IDF can't tell that 'parcel' and 'delivery' are related because...
- [x] It matches exact words, not meaning
- [ ] It ignores rare words
- [ ] It only works on numbers
> Embeddings capture meaning; TF-IDF counts words.

## nlp-sentiment-transformers
Q: A simple sentiment classifier can be built with...
- [x] TF-IDF features and logistic regression
- [ ] k-means only
- [ ] A date table
> A strong, fast baseline before transformers.
Q: Hugging Face pipeline('sentiment-analysis') gives you...
- [x] A pre-trained transformer ready to classify text
- [ ] A database
- [ ] A web server
> Pre-trained models work in a few lines.
Q: Why test a sentiment model on Hinglish or sarcastic reviews?
- [x] Models trained on plain English often fail on them
- [ ] They are always easy
- [ ] It isn't needed
> Test on the language your users actually write.
