---
title: "Using Open Models from the Hugging Face Hub"
slug: ft-hugging-face-hub
subject: fine-tuning
lesson: 1
difficulty: intermediate
excerpt: Find, download and run open models from the Hugging Face Hub: model cards, licences, pipelines for common tasks, running small LLMs locally, and choosing a model by size and hardware.
---

Hugging Face is the "GitHub of AI models": hundreds of thousands of open models for text, images, audio and more, free to download. Before fine-tuning anything, you'll learn to find a good model, read its card, and run it in a few lines.

::scene The model supermarket
🏬 | The Hub | Aisles of models: sentiment, translation, speech, chat LLMs, image generators. | dev
🏷️ | Read the label | Every model card lists what it does, its size, licence and known limits. | chip
🧺 | Pick one | A small Hindi-capable sentiment model fits your laptop and your licence. | byte
▶️ | Run | Three lines with pipeline() and it's working. | bugsy
::end

## Pipelines: models in three lines

```python
from transformers import pipeline

translate = pipeline("translation", model="MODEL_ID")              # e.g. an English-to-Hindi model from the Hub
summarise = pipeline("summarization")                               # default English model
zero_shot = pipeline("zero-shot-classification")

print(zero_shot("My order arrived broken", candidate_labels=["delivery", "payment", "product quality"]))
```

Common tasks: `sentiment-analysis`, `ner` (names, places), `question-answering`, `summarization`, `translation`, `zero-shot-classification`, `automatic-speech-recognition`, `image-classification`, `text-generation`.

## Reading a model card

Before using a model, check:

| Section | Why it matters |
|---|---|
| **Task and languages** | Does it handle Hindi, Tamil, code-mixed text? |
| **Size (parameters)** | Will it fit your GPU/CPU memory? |
| **Licence** | Apache-2.0 and MIT allow commercial use; some licences restrict it; some models need you to accept terms |
| **Training data** | Domain match (tweets vs legal text) and possible biases |
| **Evaluation** | Scores on benchmarks close to your task |
| **Limitations** | What the authors warn about |

## Will it fit? (runnable)

::walk python | Memory needed to run a model
params_billion = 8
bytes_per_param = {"fp16": 2, "int8": 1, "int4": 0.5}
need = {k: round(params_billion * v * 1.2, 1) for k, v in bytes_per_param.items()}
fits_16gb = [k for k, gb in need.items() if gb <= 16]
print(need, fits_16gb)
::notes
1: An 8-billion-parameter open LLM. // vars: params_billion=8
2: Bytes per parameter at different precisions: 16-bit, 8-bit and 4-bit quantised. // vars: bytes_per_param={'fp16': 2, 'int8': 1, 'int4': 0.5}
3: GB needed ≈ billions of parameters × bytes per parameter, plus about 20% for working memory. // deep: Long conversations need extra memory for the KV cache, so leave headroom. Training needs several times more than inference (gradients and optimiser state), which is why LoRA and QLoRA (lesson 3) exist. // vars: need={'fp16': 19.2, 'int8': 9.6, 'int4': 4.8}
4: Which versions fit a 16 GB GPU? // vars: fits_16gb=['int8', 'int4']
5: The 8B model fits in 8-bit or 4-bit. That's why quantised versions (GGUF, AWQ, GPTQ files) are so popular. // out: {'fp16': 19.2, 'int8': 9.6, 'int4': 4.8} ['int8', 'int4']
::end

## Running a chat LLM locally

Options, from easiest to most flexible:

1. **Ollama** or **LM Studio:** download a quantised model and chat, or call it through an OpenAI-compatible local API (`http://localhost:11434/v1` for Ollama).
2. **transformers:** full control in Python:

```python
from transformers import AutoTokenizer, AutoModelForCausalLM

model_id = "MODEL_ID"                     # a small instruct model from the Hub; check its licence
tok = AutoTokenizer.from_pretrained(model_id)
model = AutoModelForCausalLM.from_pretrained(model_id, device_map="auto", torch_dtype="auto")
messages = [{"role": "user", "content": "Explain recursion in two sentences."}]
inputs = tok.apply_chat_template(messages, add_generation_prompt=True, return_tensors="pt").to(model.device)
out = model.generate(inputs, max_new_tokens=120)
print(tok.decode(out[0][inputs.shape[1]:], skip_special_tokens=True))
```

3. **vLLM / TGI:** fast servers for many users at once.

## Choosing a model

```python
candidates = [
    {"name": "tiny-1b", "params_b": 1, "quality": 55, "licence_ok": True},
    {"name": "mid-8b", "params_b": 8, "quality": 74, "licence_ok": True},
    {"name": "big-70b", "params_b": 70, "quality": 86, "licence_ok": True},
    {"name": "mid-7b-nc", "params_b": 7, "quality": 76, "licence_ok": False},
]
gpu_gb = 16
ok = [c for c in candidates if c["licence_ok"] and c["params_b"] * 0.5 * 1.2 <= gpu_gb]
print(max(ok, key=lambda c: c["quality"])["name"])
```

Output:

```text
mid-8b
```

The best-scoring model with a usable licence that fits a 16 GB GPU at 4-bit. (Names and scores here are illustrative; test candidates on your own examples.)

::tip Try open models on the Hub's hosted demos ("Spaces") or a free Colab notebook before downloading many GB. And check the community tab of a model page: other users often report problems you'd otherwise discover the hard way.

## Summary

- The Hugging Face Hub hosts open models with cards describing task, size, licence and limits.
- `pipeline()` runs common tasks in a few lines.
- Memory ≈ parameters × bytes per parameter (+ headroom); quantisation makes big models fit.
- Run locally with Ollama/LM Studio, `transformers`, or serving tools like vLLM.
