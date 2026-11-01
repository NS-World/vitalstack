## langchain-chains-prompts-parsers
Q: In LangChain, prompt | model | parser creates...
- [x] A chain where each step's output feeds the next
- [ ] Three separate apps
- [ ] A database
> The | operator composes Runnables.
Q: An output parser is used to...
- [x] Turn the model's text into a usable format like a string or JSON
- [ ] Speed up the model
- [ ] Store API keys
> e.g. StrOutputParser or structured output.
Q: When might you skip LangChain?
- [x] For a simple app where a few direct API calls are clearer
- [ ] Never
- [ ] When using Python
> Frameworks add value for bigger pipelines; small apps can stay plain.

## langchain-memory-chatbots
Q: A 'window' memory strategy keeps...
- [x] Only the last few messages
- [ ] Every message forever
- [ ] No messages
> It limits tokens but forgets older details.
Q: A summary memory strategy...
- [x] Condenses older turns into a short summary
- [ ] Deletes the system prompt
- [ ] Stores audio
> Good for long chats.
Q: Why keep one memory per session or user id?
- [x] So users never see each other's conversations
- [ ] To use more memory
- [ ] It's random
> Separate histories keep chats private and correct.

## langchain-loaders-retrievers-llamaindex
Q: Document loaders are used to...
- [x] Read PDFs, web pages and files into text documents
- [ ] Train models
- [ ] Draw charts
> They're the first step of RAG.
Q: A recursive text splitter tries to split on...
- [x] Paragraphs first, then sentences, then words
- [ ] Random characters
- [ ] Only full pages
> It keeps chunks meaningful while respecting a size limit.
Q: LlamaIndex is especially focused on...
- [x] Indexing and querying your data for RAG
- [ ] Image generation
- [ ] Mobile apps
> LangChain is broader; LlamaIndex specialises in data and retrieval.
