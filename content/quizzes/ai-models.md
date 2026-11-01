## ai-models-life-of-a-model
Q: Which stage gives a model most of its knowledge?
- [x] Pretraining
- [ ] Serving
- [ ] Tokenizing
> Guessing the next token over trillions of tokens is where knowledge comes from.
Q: What does fine-tuning add to a pretrained model?
- [ ] More GPUs
- [x] Assistant behaviour, learned from good question-and-answer examples
- [ ] A bigger vocabulary
> Pretraining gives knowledge; fine-tuning teaches the model to answer like an assistant.
Q: Why do AI APIs charge per token?
- [x] Every generated token costs computing power on GPUs
- [ ] Tokens are a type of cryptocurrency
- [ ] Because the chat app is free
> Each token requires a pass through the model, which uses GPU time.

## ai-models-training-data
Q: Why are duplicate documents removed from training data?
- [ ] They make the files bigger to download
- [x] Repeated text makes the model memorise instead of learning general patterns
- [ ] Duplicates are always illegal
> Seeing the same text hundreds of times encourages memorisation.
Q: What does "normalising" text before fingerprinting do?
- [x] Makes small differences like capital letters and extra spaces disappear
- [ ] Translates it to English
- [ ] Encrypts it
> "MUMBAI  RAINS" and "mumbai rains" should count as the same document.
Q: Why must personal data be removed from training text?
- [ ] It slows training down
- [ ] It uses too many tokens
- [x] A model can memorise and repeat it, harming people's privacy
> Phone numbers and emails should never be learnable by a model.

## ai-models-pretraining
Q: What is the "loss" during training?
- [ ] The money spent on GPUs
- [x] A number measuring how wrong the model's guesses were
- [ ] The data that was deleted
> Training tries to make the loss smaller and smaller.
Q: In gradient descent, if the gradient is negative, the parameter should…
- [x] Increase
- [ ] Decrease
- [ ] Stay the same
> Move opposite to the gradient: a negative slope means the loss falls as the parameter grows.
Q: A common estimate of training compute is…
- [ ] parameters + tokens
- [x] 6 × parameters × tokens
- [ ] tokens ÷ GPUs
> Roughly six operations per parameter per training token.

## ai-models-fine-tuning-rlhf
Q: What does a reward model do in RLHF?
- [x] Predicts which answer people would prefer, as a score
- [ ] Pays the people who label data
- [ ] Writes the training data
> The assistant is then trained to produce answers the reward model scores highly.
Q: Constitutional AI, introduced by Anthropic, mainly uses…
- [ ] More GPUs
- [x] An AI model judging answers against a written list of principles
- [ ] Only users' thumbs-up clicks
> It reduces the amount of human labelling needed for alignment.
Q: Why might two chatbots trained on similar data behave very differently?
- [ ] Different keyboards
- [x] Different post-training: examples, reviewer guidelines and principles
- [ ] Different internet speed
> Post-training shapes tone, caution and helpfulness.

## ai-models-serving-inference
Q: Why do output tokens usually cost more than input tokens?
- [x] Output tokens are generated one at a time, which uses GPUs less efficiently
- [ ] Output tokens are longer words
- [ ] Input tokens are free
> Prefill handles input in parallel; decoding writes output step by step.
Q: What does quantization do?
- [ ] Adds more parameters
- [x] Stores weights with fewer bits so the model needs less memory
- [ ] Translates the model to another language
> 4-bit weights need a quarter of the memory of 16-bit weights.
Q: Batching requests on a GPU…
- [x] Serves many users with little extra time per user
- [ ] Makes each answer more accurate
- [ ] Is only used during training
> The weights are loaded once for the whole batch.

## ai-models-families-compared
Q: Which company makes the Claude models?
- [ ] Google
- [ ] OpenAI
- [x] Anthropic
> Claude is made by Anthropic; GPT by OpenAI; Gemini by Google; Grok by xAI.
Q: What does "open weights" mean?
- [x] You can download the model and run it on your own hardware
- [ ] The model is free to use in the chat app
- [ ] The model has no safety training
> Llama, Qwen and DeepSeek's major releases are examples.
Q: Does a ChatGPT Plus or Claude Pro subscription include API credits?
- [ ] Yes, always
- [x] No, API usage is a separate account and bill
- [ ] Only on weekends
> Chat apps and developer APIs are separate products.
