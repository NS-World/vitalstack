## rag-why-rag
Q: RAG stands for...
- [x] Retrieval-Augmented Generation
- [ ] Random Answer Generator
- [ ] Rapid AI Graph
> Retrieve relevant text, then generate an answer from it.
Q: RAG is the best first choice when...
- [x] The model needs your own, changing documents
- [ ] You only want a different writing style
- [ ] You have no documents
> Fine-tuning is poor at adding facts; RAG supplies them at question time.
Q: A RAG answer should...
- [x] Use only the retrieved text and say when the answer isn't there
- [ ] Always answer from memory
- [ ] Hide its sources
> Grounding and refusing reduce hallucinations.

## rag-chunking
Q: Why add overlap between chunks?
- [x] So a sentence cut at a boundary still appears whole in one chunk
- [ ] To make the index bigger on purpose
- [ ] Overlap is required by Python
> Overlap protects ideas that straddle a boundary.
Q: Structure-aware chunking splits text by...
- [x] Headings, sections and paragraphs
- [ ] A random number of characters
- [ ] Every single word
> Chunks that follow the document's meaning retrieve better.
Q: Why store metadata like page and section with each chunk?
- [x] For citations and filtering
- [ ] To slow down search
- [ ] Metadata replaces the text
> You can cite 'page 4' and filter by document.

## rag-embeddings-vector-db
Q: An embedding is...
- [x] A list of numbers that captures the meaning of text
- [ ] A compressed zip file
- [ ] A database password
> Similar meanings have nearby vectors.
Q: Vector search finds chunks by...
- [x] Similarity between the question's vector and chunk vectors
- [ ] Alphabetical order
- [ ] File size
> Usually cosine similarity, often with approximate indexes.
Q: Important rule when using embeddings:
- [x] Use the same embedding model for documents and questions
- [ ] Mix any two models freely
- [ ] Embed only the questions
> Vectors from different models aren't comparable.

## rag-retrieval-reranking-citations
Q: Hybrid search combines...
- [x] Keyword matching and vector (meaning) search
- [ ] Two databases of passwords
- [ ] Images and audio
> Keywords catch exact terms; vectors catch meaning.
Q: A re-ranker is used to...
- [x] Re-score the top retrieved chunks more accurately
- [ ] Delete old chunks
- [ ] Embed images
> Retrieve many quickly, then re-rank the best few carefully.
Q: Citations in a RAG answer let users...
- [x] Check where each fact came from
- [ ] Skip reading
- [ ] Change the model
> They build trust and expose mistakes.

## rag-evaluation
Q: Retrieval hit rate measures...
- [x] How often the right chunk is in the top results
- [ ] How fast the model types
- [ ] The number of documents
> If retrieval misses, the answer can't be right.
Q: Faithfulness (groundedness) checks whether...
- [x] The answer is supported by the retrieved text
- [ ] The answer is long
- [ ] The user is happy
> Unsupported claims are hallucinations.
Q: A good RAG test set contains...
- [x] Real questions with the expected source and answer
- [ ] Only easy greetings
- [ ] No questions
> Include questions the documents can't answer too.
