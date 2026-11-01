---
title: "Speech to Text and Text to Speech"
slug: multimodal-speech
subject: multimodal-ai
lesson: 2
difficulty: intermediate
excerpt: Add voice to AI apps: transcription with Whisper-style models, timestamps and Indian languages, text-to-speech voices, voice-assistant pipelines, latency budgets and audio basics in Python.
---

Hundreds of millions of Indians prefer speaking to typing, often in Hindi, Tamil or a mix. Voice turns an AI app into something your grandmother can use. The building blocks: **speech-to-text (STT)**, an LLM in the middle, and **text-to-speech (TTS)**.

::scene A voice assistant for a kirana store
🎙️ | Listen | The shopkeeper says: "Do kilo atta aur ek litre doodh add karo." | dev
📝 | Transcribe | Speech-to-text writes it down, in mixed Hindi and English. | chip
🧠 | Understand | The LLM turns it into a structured order: 2 kg atta, 1 litre milk. | byte
🔊 | Reply | Text-to-speech answers aloud: "Ho gaya. Total 180 rupaye." | bugsy
::end

## Audio basics

Sound is a wave sampled many times per second. Speech models usually want 16,000 samples per second (16 kHz), mono.

```python
import numpy as np

sample_rate = 16_000
seconds = 2
t = np.arange(sample_rate * seconds) / sample_rate
tone = 0.3 * np.sin(2 * np.pi * 440 * t)                   # a 440 Hz beep
pcm16 = (tone * 32767).astype(np.int16)
print(len(pcm16), "samples |", pcm16.nbytes / 1024, "KB of raw 16-bit audio")
```

Output:

```text
32000 samples | 62.5 KB of raw 16-bit audio
```

Compressed formats (MP3, Opus, M4A) are far smaller; most APIs accept them.

## Speech to text

OpenAI's open-source **Whisper** models (run locally with `pip install openai-whisper` or the faster `faster-whisper`) and many cloud APIs (OpenAI, Google, Azure, AWS, plus Indian-language specialists) transcribe dozens of languages.

```python
import whisper

model = whisper.load_model("small")                        # larger = more accurate, slower
result = model.transcribe("order.mp3", language="hi")      # or let it detect the language
print(result["text"])
for seg in result["segments"]:
    print(f"{seg['start']:.1f}s-{seg['end']:.1f}s {seg['text']}")
```

Timestamps let you build subtitles, search inside recordings, or jump to "the part where the customer complained".

## Text to speech

Cloud TTS services (Google, Azure, AWS Polly, ElevenLabs, OpenAI and others) offer natural voices in many Indian languages; open models (like Coqui-style or Piper voices) run locally. You send text and get audio back. For long replies, stream audio sentence by sentence so speaking starts quickly.

## A voice pipeline and its latency budget

::walk python | Will the assistant feel instant?
stt_ms = 450
llm_first_token_ms = 600
tts_first_audio_ms = 300
total = stt_ms + llm_first_token_ms + tts_first_audio_ms
feels = "natural" if total < 1500 else "slow"
print(total, feels)
::notes
1: Speech-to-text finishes about 450 ms after the user stops talking (streaming STT helps). // vars: stt_ms=450
2: The LLM's first token arrives after about 600 ms (smaller models and streaming help). // vars: llm_first_token_ms=600
3: TTS produces the first audio chunk 300 ms after receiving the first sentence. // vars: tts_first_audio_ms=300
4: Time from the user finishing to hearing the reply. // deep: People notice pauses over roughly a second or two in conversation. Real-time "speech-to-speech" APIs, which handle audio in and out in one model, cut this further and also allow interruptions. // vars: total=1350
5: Under 1.5 seconds: it feels like a conversation. // out: 1350 natural
::end

## Turning speech into actions

The LLM step is where voice becomes useful: extract structured data from messy, code-mixed speech, then validate it.

```python
transcript = "do kilo atta aur ek litre doodh add karo"
NUMBERS = {"ek": 1, "do": 2, "teen": 3, "char": 4, "paanch": 5}
UNITS = {"kilo": "kg", "litre": "l"}
words = transcript.split()
order = []
for i, w in enumerate(words[:-2]):
    if w in NUMBERS and words[i + 1] in UNITS:
        order.append({"qty": NUMBERS[w], "unit": UNITS[words[i + 1]], "item": words[i + 2]})
print(order)
```

Output:

```text
[{'qty': 2, 'unit': 'kg', 'item': 'atta'}, {'qty': 1, 'unit': 'l', 'item': 'doodh'}]
```

In a real app, an LLM with structured output does this far more robustly; the rule-based version above shows the target format and works as a fallback and test.

::tip Always read the order back ("2 kilo atta, 1 litre doodh, sahi hai?") before acting. Speech recognition makes mistakes, especially with background noise; a confirmation step prevents wrong orders and payments.

## Summary

- Voice apps = speech-to-text → LLM → text-to-speech.
- Audio is sampled waves; 16 kHz mono is standard for speech models.
- Whisper-style models and cloud APIs handle many Indian languages; timestamps enable search and subtitles.
- Watch latency (under ~1.5 s) and confirm actions aloud.
