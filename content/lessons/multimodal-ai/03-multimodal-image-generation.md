---
title: "Image Generation APIs"
slug: multimodal-image-generation
subject: multimodal-ai
lesson: 3
difficulty: intermediate
excerpt: Generate and edit images from text with APIs and open models: writing good image prompts, sizes and costs, editing and inpainting, safety filters, copyright and labelling AI-generated images.
---

Type "a cosy tea stall on a rainy Mumbai street, watercolour style" and get a picture in seconds. Image generation is used for marketing posts, product mock-ups, thumbnails, storyboards and game art. You'll learn how the models work in the next module; this lesson is about **using** them well and responsibly.

::scene From words to pictures
✍️ | Prompt | "A cosy tea stall on a rainy Mumbai street, warm lights, watercolour style." | dev
🎨 | Generate | The image model creates four options in a few seconds. | chip
🖌️ | Edit | Mask the sign and ask: "Change the sign to say Chai Point." | byte
🏷️ | Publish | Label it as AI-generated, check you're allowed to use it, and post. | bugsy
::end

## The options

- **API services:** OpenAI's image models, Google's Imagen (via Gemini / Vertex AI), Stability AI, and others. Pay per image.
- **Open models:** Stable Diffusion and FLUX-family models run locally on a GPU with the Hugging Face `diffusers` library or apps like ComfyUI.
- **Design tools:** Canva, Adobe Firefly and similar have generation built in.

A typical API call (Python, OpenAI SDK):

```python
from openai import OpenAI
import base64

client = OpenAI()
result = client.images.generate(model="MODEL_NAME",          # copy a current image model name from the docs
                                prompt="A cosy tea stall on a rainy Mumbai street at dusk, watercolour style",
                                size="1024x1024")
with open("chai.png", "wb") as f:
    f.write(base64.b64decode(result.data[0].b64_json))
```

## Writing image prompts

Good image prompts name the **subject, setting, style, lighting, composition** and any **text** that must appear. A small builder makes prompts consistent across a campaign:

```python
def image_prompt(subject, setting, style, lighting="soft natural light", extras=()):
    parts = [subject, setting, f"{style} style", lighting, *extras]
    return ", ".join(p for p in parts if p)

print(image_prompt("a steaming cup of masala chai with cardamom pods", "on a wooden table by a rainy window",
                   "flat vector illustration", extras=("top-down view", "warm colours", "no text")))
```

Output:

```text
a steaming cup of masala chai with cardamom pods, on a wooden table by a rainy window, flat vector illustration style, soft natural light, top-down view, warm colours, no text
```

::walk python | Monthly cost of a thumbnail pipeline
posts_per_day = 12
options_per_post = 4
price_per_image = 0.04
monthly = posts_per_day * options_per_post * price_per_image * 30
print(round(monthly, 2))
::notes
1: A content team publishes 12 posts a day. // vars: posts_per_day=12
2: They generate 4 options per post and pick the best. // vars: options_per_post=4
3: An example price per standard-quality image (check current pricing; bigger or higher-quality images cost more). // vars: price_per_image=0.04
4: 12 × 4 × $0.04 × 30 days. // deep: Generating fewer options at low resolution first, then upscaling only the chosen one, often halves the cost. // vars: monthly=57.6
5: About $58 a month: cheap compared with a designer's time, but not free. // out: 57.6
::end

## Editing: inpainting and variations

- **Inpainting:** upload an image and a mask; only the masked area is regenerated ("replace the background with a beach").
- **Variations / image-to-image:** start from an existing image and change style or details.
- **Text in images** has improved a lot but still fails sometimes: check spelling, especially in Hindi or other scripts, or add text afterwards in a design tool.

## Responsible use

1. **Safety filters:** providers block harmful content (violence, sexual content involving minors, real people in compromising situations). Don't try to bypass them.
2. **Real people and brands:** don't generate fake photos of real people, or images that copy logos or a living artist's distinctive style in ways that mislead.
3. **Copyright:** rules on who owns AI-generated images, and on how models were trained, are still evolving and differ by country. Check the provider's terms for commercial use.
4. **Label it:** say when an image is AI-generated, especially in news-like or educational content. Some providers add invisible watermarks or content credentials (C2PA metadata).

::tip For educational sites and ads, AI images work best as illustrations (concepts, scenes, icons), not as "photos" of real events. That keeps you honest and avoids misleading readers.

## Summary

- Generate images via APIs, open models (diffusers) or design tools.
- Prompts: subject, setting, style, lighting, composition, required text.
- Estimate cost per image × volume; generate small first, upscale the winner.
- Respect safety filters, real people, brands and copyright; label AI images.

🎉 You've finished **Multimodal AI**.
