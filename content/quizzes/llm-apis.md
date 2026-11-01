## llm-apis-getting-api-keys
Q: Where should your API key be stored?
- [ ] In your Python code
- [x] In an environment variable or a .env file that isn't committed
- [ ] In the website's JavaScript
> Keys in code or frontends get leaked and abused.
Q: Which provider offers a free tier through its AI Studio for learning?
- [x] Google (Gemini)
- [ ] Only xAI
- [ ] None of them
> Gemini's free tier has rate limits and different data terms.
Q: Your key leaked on GitHub. What do you do first?
- [ ] Delete the repository later
- [x] Revoke the key in the console immediately
- [ ] Change your account password only
> Revoke first, then create a new key and check usage.

## llm-apis-first-call
Q: Which Python package do you use to call xAI's Grok API?
- [ ] grok
- [x] openai, with xAI's base_url
- [ ] anthropic
> xAI's API is OpenAI-compatible.
Q: Why wrap providers behind one function like ask(llm, question)?
- [x] To switch models easily and test with a fake client for free
- [ ] Because SDKs don't work otherwise
- [ ] To make requests faster
> The rest of the app doesn't care which provider is behind it.
Q: Where should you get the current model name from?
- [ ] An old blog post
- [x] The provider's models documentation page
- [ ] Guess a likely name
> Model names change often.

## llm-apis-chat-history-system-prompts
Q: How does a chatbot "remember" earlier messages?
- [ ] The model stores them on the server
- [x] The app resends the conversation history with every request
- [ ] Through cookies inside the model
> LLM APIs are stateless.
Q: What is the system prompt for?
- [x] Standing instructions: role, rules and format
- [ ] Storing the API key
- [ ] Counting tokens
> It sets behaviour for the whole conversation.
Q: Why do long chats cost more per message?
- [ ] Providers charge late-night fees
- [x] The growing history is sent as input tokens every time
- [ ] Output tokens get more expensive
> Trim, summarise or cache to keep costs down.

## llm-apis-streaming
Q: What does streaming improve?
- [x] How fast the first words appear to the user
- [ ] The total cost
- [ ] The accuracy of the answer
> Same answer and cost; much better perceived speed.
Q: A Python function with yield is called a…
- [ ] Decorator
- [x] Generator
- [ ] Lambda
> Generators hand out one piece at a time, perfect for faking a stream.
Q: The stream stopped with "max_tokens". What happened?
- [x] The answer was cut off by the length limit
- [ ] The answer is complete
- [ ] The key is invalid
> Tell the user or ask the model to continue.

## llm-apis-structured-output
Q: Valid JSON from the model means the data is correct.
- [ ] True
- [x] False: values still need checking (ranges, allowed labels)
- [ ] Only for Claude
> Structure and sense are different things.
Q: In the Anthropic Python SDK, which call returns a validated Pydantic object?
- [x] client.messages.parse(..., output_format=MyModel)
- [ ] client.json()
- [ ] client.validate()
> parsed_output holds the validated object.
Q: What is tool use (function calling)?
- [ ] Using the mouse in the console
- [x] The model replies with a function name and JSON arguments for your code to run
- [ ] A billing feature
> It's structured output aimed at actions; agents are built on it.

## llm-apis-errors-retries-cost
Q: Which error should you retry with backoff?
- [ ] 400 Bad request
- [ ] 401 Unauthorized
- [x] 429 Too many requests
> 429 and 5xx are temporary; 400 and 401 need fixing.
Q: Why add random "jitter" to backoff waits?
- [x] So many clients don't all retry at the same moment
- [ ] To make tests slower
- [ ] Providers require it
> Jitter spreads retries out.
Q: The easiest way to cut an AI bill is often…
- [ ] Removing error handling
- [x] Using the smallest model that passes your tests
- [ ] Sending longer prompts
> Small models can be dozens of times cheaper.

## llm-apis-keys-data-safety
Q: Why must the browser never call the AI provider directly?
- [x] The API key would be visible to anyone using DevTools
- [ ] Browsers can't send HTTPS requests
- [ ] It is slower
> Put the key on your server and let the browser talk to your server.
Q: What is prompt injection?
- [ ] Sending too many prompts
- [x] Text the model reads that tries to override its instructions
- [ ] A way to speed up prompts
> Treat everything the model reads as untrusted.
Q: Which two safety nets protect your API budget?
- [x] Per-user rate limits and a spending cap
- [ ] A longer system prompt and a bigger model
- [ ] Logging prompts and streaming
> Limit each user and cap total spend; set console limits too.
