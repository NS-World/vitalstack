---
title: "Diffusion Models: How Image Generators Work"
slug: gen-diffusion
subject: generative-models
lesson: 3
difficulty: intermediate
excerpt: The idea behind Stable Diffusion, DALL·E-style and Imagen-style generators: add noise step by step, train a network to remove it, then generate from pure noise, guided by text. With a runnable noise demo.
---

Text-to-image tools are built on **diffusion models**. The idea sounds backwards: teach a network to **remove noise** from images. Then start from pure noise and let it "clean up" step by step, until a brand-new image appears, shaped by your text prompt.

::scene From static to a sunset
📺 | Static | Start with pure random noise, like an old TV with no signal. | byte
🧽 | Denoise | The network predicts the noise in the image and removes a little of it. | dev
🌅 | Shapes appear | After 10 steps: blurry colours. After 30: a sunset over the sea. | chip
💬 | Guided | At every step the text "sunset over Marine Drive, oil painting" steers what appears. | bugsy
::end

## Step 1: the forward process (adding noise)

Take a real image and add a little Gaussian noise, again and again, until only noise is left. This is easy and needs no learning:

```python
import numpy as np

rng = np.random.default_rng(0)
image = np.array([0.9, 0.9, 0.1, 0.1, 0.9, 0.9])           # a tiny 1-D "image": bright, dark, bright
betas = np.linspace(0.02, 0.3, 10)                          # how much noise each step adds
x = image.copy()
for t, beta in enumerate(betas, 1):
    x = np.sqrt(1 - beta) * x + np.sqrt(beta) * rng.normal(size=x.shape)
    if t in (1, 5, 10):
        print(f"step {t:>2}: correlation with the original = {np.corrcoef(image, x)[0, 1]:.2f}")
```

Output:

```text
step  1: correlation with the original = 0.99
step  5: correlation with the original = 0.83
step 10: correlation with the original = 0.36
```

After just 10 steps most of the picture is gone (correlation 0.36). Real models use hundreds or thousands of small steps, until only pure noise remains.

## Step 2: train a network to predict the noise

For millions of images, pick a random step t, add the right amount of noise, and train a network (usually a **U-Net** or, in newer models, a **transformer**) to predict exactly which noise was added. If it can predict the noise, it can subtract it.

::walk python | One noising step and its training target
import math
x0 = 0.9
noise = -0.5
alpha_bar = 0.6
xt = math.sqrt(alpha_bar) * x0 + math.sqrt(1 - alpha_bar) * noise
print(round(xt, 3), "target:", noise)
::notes
1: math for square roots.
2: One pixel of a clean image. // vars: x0=0.9
3: The random noise we're about to add (the network never sees this directly). // vars: noise=-0.5
4: How much signal survives at this step: 60% (it shrinks as t grows). // vars: alpha_bar=0.6
5: The noisy pixel: mostly signal, some noise. A closed formula lets training jump straight to any step t. // deep: x_t = √ᾱ·x₀ + √(1−ᾱ)·ε. The network gets x_t and t, and is trained with a simple squared error to output ε. Learning to predict noise turns out to be a stable, easy objective, which is why diffusion trains more reliably than GANs. // vars: xt=0.381
6: The training target is the noise itself: −0.5. // out: 0.381 target: -0.5
::end

## Step 3: generate by denoising

Start from pure noise. At each step, the network predicts the noise; remove part of it; repeat (often 20–50 steps with modern samplers). A new image emerges, different every time because the starting noise is random (that's the **seed**).

## Text guidance

A text encoder (for example, a CLIP or T5-style model) turns the prompt into embeddings. The denoising network reads them at every step through attention, so "sunset" pushes the image towards sunset colours. **Classifier-free guidance** mixes a prompt-guided prediction with an unguided one; the **guidance scale** controls how strictly the image follows the prompt (higher = more literal, less varied).

## Latent diffusion

Denoising full-size 1024×1024 pixels is expensive. **Latent diffusion** (the idea behind Stable Diffusion) runs the whole process inside a VAE's small compressed space, then decodes to pixels at the end, which is far faster. The same ideas now power video and audio generators.

## Using a diffusion model in code

```python
from diffusers import AutoPipelineForText2Image
import torch

pipe = AutoPipelineForText2Image.from_pretrained("MODEL_ID_FROM_HUGGING_FACE", torch_dtype=torch.float16).to("cuda")
image = pipe("sunset over Marine Drive, oil painting", num_inference_steps=30, guidance_scale=6.0,
             generator=torch.Generator("cuda").manual_seed(42)).images[0]
image.save("marine_drive.png")
```

| Setting | Effect |
|---|---|
| `num_inference_steps` | More steps = more detail, slower |
| `guidance_scale` | How strictly to follow the prompt |
| `seed` | Same seed + same settings = same image |
| Negative prompt | What to avoid ("blurry, extra fingers") |

::tip Check each model's licence before commercial use; some open image models restrict it. And as with all generative media, label AI images and never create misleading pictures of real people.

## Summary

- Forward process: add noise step by step until the image is gone.
- Training: a network learns to predict the added noise at any step.
- Generation: start from noise and denoise step by step, guided by text embeddings.
- Latent diffusion works in a VAE's compressed space for speed; steps, guidance and seed control the output.

🎉 You've finished **Generative Models: GANs, VAEs and Diffusion**.
