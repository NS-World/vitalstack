---
title: "Vision: Sending Images and PDFs to a Model"
slug: multimodal-vision-pdfs
subject: multimodal-ai
lesson: 1
difficulty: intermediate
excerpt: Modern LLMs can read images, screenshots, charts, receipts and PDFs. Learn how to send them through the API (base64 and URLs), what they're good and bad at, image token costs, and privacy.
---

The best models today are **multimodal**: they read images and documents, not just text. Snap a bill and ask "split this between 4 friends"; upload a chart and ask "what's the trend?"; send a 40-page PDF and ask for the key dates. It's the same API you already know, with one more content type.

::scene The model that can see
🧾 | A receipt | A blurry restaurant bill photo is uploaded with "Split this between 4 people". | dev
👁️ | Read | The model reads each line item, the GST and the service charge from the pixels. | chip
🧮 | Reason | It totals the bill and divides it. Your code double-checks the maths. | byte
📄 | PDFs too | A 40-page tender document? It reads text, tables and diagrams together. | bugsy
::end

## Sending an image to Claude

Images go inside the message `content` as a list of blocks, image first, then your question:

```python
import base64
import anthropic

client = anthropic.Anthropic()
with open("bill.jpg", "rb") as f:
    data = base64.standard_b64encode(f.read()).decode()

response = client.messages.create(
    model="claude-opus-5-5",
    max_tokens=1000,
    messages=[{"role": "user", "content": [
        {"type": "image", "source": {"type": "base64", "media_type": "image/jpeg", "data": data}},
        {"type": "text", "text": "List each item and price as JSON, then the total."},
    ]}],
)
print(response.content[0].text)
```

PDFs work the same way with a `document` block (`"media_type": "application/pdf"`). OpenAI and Gemini accept images and PDFs too, with their own block formats: see each provider's vision docs.

## Preparing images (runnable)

Large photos cost more tokens and take longer. Resize before sending:

```python
from PIL import Image
import io, base64

photo = Image.new("RGB", (4000, 3000), "white")          # stand-in for a 12-megapixel phone photo
photo.thumbnail((1568, 1568))                             # keep aspect ratio, cap the long side
buf = io.BytesIO()
photo.save(buf, format="JPEG", quality=85)
encoded = base64.standard_b64encode(buf.getvalue()).decode()
print(photo.size, "| base64 characters:", len(encoded))
```

Output:

```text
(1568, 1176) | base64 characters: 39516
```

::walk python | Estimating image tokens
width, height = 1568, 1176
pixels_per_token = 750
tokens = round(width * height / pixels_per_token)
cost = tokens / 1e6 * 4.00
print(tokens, round(cost, 4))
::notes
1: The resized image. // vars: width=1568, height=1176
2: A rule of thumb used in Anthropic's docs: about 750 pixels per token. Other providers count differently (some use fixed-size tiles). // vars: pixels_per_token=750
3: About 2,459 tokens for this image. // deep: A 12-megapixel original would be resized by the provider anyway, but you'd pay upload time and possibly more tokens. Check each provider's vision docs for exact rules and limits. // vars: tokens=2459
4: At an example price of $4 per million input tokens: about one rupee per image. // vars: cost=0.0098
5: Cheap per image, but it adds up for thousands of photos a day. // out: 2459 0.0098
::end

## What vision models are good (and bad) at

| Good at | Weaker at |
|---|---|
| Reading printed text, receipts, forms, screenshots | Tiny or very blurry text |
| Describing scenes, charts and diagrams | Exact counting of many small objects |
| Tables in documents and screenshots | Precise measurements and pixel positions |
| Handwriting (often), multiple languages | Identifying specific real people (providers restrict it) |

Validate extracted numbers with code, just as with text output: totals should add up.

```python
items = {"Paneer tikka": 320, "Butter naan x4": 240, "Lassi x2": 180}
gst = round(sum(items.values()) * 0.05)
total = sum(items.values()) + gst
print("model said 790; our total:", total, "| match:", total == 790, "| per person:", round(total / 4, 2))
```

Output:

```text
model said 790; our total: 777 | match: False | per person: 194.25
```

The model's total was off: your code catches it and uses the correct one.

## Privacy

Images often contain more than you think: faces, ID cards, addresses on parcels, other people. Tell users what's sent to the AI provider, avoid sending ID documents unless necessary, and blur or crop sensitive parts first.

::tip For document-heavy apps (invoices, forms), ask the model for structured output (JSON with fixed fields) and validate every field. That turns "the model read the image" into data you can trust.

## Summary

- Multimodal models take images and PDFs as content blocks next to your text.
- Resize images before sending; estimate image tokens for cost.
- Great at reading and describing; check numbers and counts with code.
- Be careful with privacy: faces, IDs and addresses.
