# VitalStack 4.10: AI courses, lesson films, AI games and a free cost calculator

Additions only: no existing page, lesson or feature changed. Topics were chosen by comparing a commercial GenAI/ML programme outline with our tracks. All lesson text, code and examples are original.

## New courses

| Course | Free / premium | Lessons | What it covers |
|---|---|---|---|
| **How AI Models Are Built** (`ai-models`) | **Free**: indexed, carries ads | 6 | How ChatGPT, Claude, Gemini and Grok are made: training data and cleaning, tokens, pretraining with gradient descent, fine-tuning, RLHF, Constitutional AI and DPO, serving (KV cache, batching, quantization, why output tokens cost more), and the model families compared (open vs closed weights, chat apps vs APIs). |
| **Working with LLM APIs** (`llm-apis`) | Premium: Gen AI Developer | 7 | **How to get and pay for API keys** from OpenAI, Anthropic, Google and xAI, plus free options (Gemini free tier, Ollama/LM Studio, fake clients). First calls to all four providers in Python, chat history and system prompts, streaming, structured JSON output, errors, retries and cost control, and keeping keys and user data safe (DPDP, prompt injection). |
| **Project: AI Study Buddy App** (`ai-app-project`) | Premium: Gen AI Developer | 3 | Plan, build (Python + Streamlit, swappable Claude, OpenAI, Gemini or Grok provider), test with a fake model, secure (secrets, per-user limits, spending cap) and deploy for free. |

- Every lesson has runnable Python checked by `tools/check_lesson_code.py`, a 3-question quiz, and most have a **lesson film** and a line-by-line walkthrough (with the 3D view).
- Code that calls a real provider is shown with the official SDK patterns and is not run by the checker, because it needs an API key. Model names are placeholders: students are told to copy current names from each provider's docs.

## Syllabus additions (show as "Coming soon · included" for buyers)
- **Data Scientist:** NoSQL with MongoDB · Big Data with Apache Spark · NLP: Working with Text · Computer Vision (OpenCV, YOLO) · Reinforcement Learning Basics · MLOps: Deploying Models (FastAPI, Docker, SageMaker, Azure ML).
- **Gen AI Developer:** How AI Models Are Built (free, ready) · Working with LLM APIs (ready) · Project: AI Study Buddy (ready) · Multimodal AI · Generative Models: GANs, VAEs and Diffusion · AI Coding Assistants (Copilot, Cursor, Claude Code).

## New: 🎬 Lesson films
- A new `::scene` block in lesson files plays like a short animated movie:
  - Letterbox bars and a poster with a play button.
  - Slow camera moves (zoom and pans) across a big set piece.
  - Chapter cards and typed subtitles.
  - An approved crew character walking in.
  - Scene progress bar, play/pause, previous/next and the keyboard all work.
- It pauses when scrolled away and respects "reduce motion".
- Without JavaScript, the scenes stay a readable list, so search engines and AdSense reviewers still see real text.
- 16 films across the new lessons (one in every new lesson).
- Writers add one like this:
  ```
  ::scene Title
  icon | Chapter (optional) | Subtitle text | crew (dev, chip, bugsy, byte; optional)
  ::end
  ```

## New games (Games page)
- **🤖 Who Made This AI?** Match ChatGPT, Claude, Gemini, Grok, Llama, Qwen, Copilot and others to their makers.
- **🧠 AI Word Rush:** 60 seconds of Gen AI terms (token, embedding, RAG, RLHF, MCP…).
- **🥊 Prompt Duel:** pick the prompt that gets the better answer.

## New free tool
- **💸 AI API Cost Calculator** (`/tools/ai-cost-calculator/`):
  - Paste a prompt to estimate its tokens.
  - Enter output length and requests per day.
  - Compare small, mid-size and frontier model costs per request, day and month, in $ and ₹.
  - Prices are editable and pre-filled with clearly labelled examples.

## After uploading
1. Appearance → VitalStack Setup → **Run setup**. This adds the three new learning paths (two marked premium) and the new tool page.
2. **Import starter content.** Only the 16 new lessons are added; existing ones are untouched.
3. Publish them, free course first. The 6 free "How AI Models Are Built" lessons are good AdSense pages: long, original and useful.
4. LiteSpeed Cache → **Purge All**.

## Still to write (next batches)
The modules listed as "Coming soon" above, plus the remaining Gen AI and Agentic modules (RAG, LangChain/LlamaIndex, agents with tools, MCP, multi-agent, evals, fine-tuning, deployment) and the Data Scientist modules (EDA, ML in depth, Power BI, time series, deep learning, projects).
