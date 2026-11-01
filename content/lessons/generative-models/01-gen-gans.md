---
title: "GANs: A Generator and a Critic"
slug: gen-gans
subject: generative-models
lesson: 1
difficulty: intermediate
excerpt: How Generative Adversarial Networks create realistic images: a forger network versus a detective network, the adversarial training loop, DCGAN, StyleGAN, CycleGAN, and a 1-D GAN-style game you can run.
---

In 2014 Ian Goodfellow and colleagues had a striking idea: train **two** networks against each other. One, the **generator**, tries to create fake data that looks real. The other, the **discriminator**, tries to tell fakes from real data. As each improves, the other must improve too, until the fakes are hard to tell apart from the real thing.

::scene The forger and the detective
🖼️ | Forger | The generator paints a fake "photo" of a face from random noise. At first it's a blurry mess. | bugsy
🕵️ | Detective | The discriminator looks at real faces and fakes, and learns to spot the fakes. | chip
🔁 | Arms race | Each time it's caught, the forger learns what gave it away. The detective must get sharper. | dev
🎭 | Masterpiece | After thousands of rounds, the generated faces are realistic enough to fool people. | byte
::end

## The two players

- **Generator G:** takes random noise **z** and outputs a sample G(z) (an image, a sound, a row of data).
- **Discriminator D:** takes a sample and outputs the probability it's real.
- **Training:** D is trained to maximise its accuracy; G is trained to make D say "real" for its fakes.

## A tiny adversarial game (runnable)

Real GANs use deep networks. This toy has a one-number "generator" (it outputs `mean + noise`) and a simple discriminator, but shows the same tug-of-war: the generator moves towards where real data lives.

```python
import numpy as np

rng = np.random.default_rng(0)
real_mean = 4.0
g_mean = -2.0                                     # the generator starts far from the real data
lr = 0.05

for step in range(301):
    real = rng.normal(real_mean, 1, 64)
    fake = rng.normal(g_mean, 1, 64)
    # discriminator: a threshold halfway between the two groups it sees
    threshold = (real.mean() + fake.mean()) / 2
    caught = np.mean(fake < threshold)            # share of fakes the detective catches
    # generator: move towards the side the detective calls "real"
    g_mean += lr * (threshold - g_mean) * caught
    if step in (0, 50, 150, 300):
        print(f"step {step:>3}: generator mean {g_mean:5.2f} | fakes caught {caught:.0%}")
```

Output:

```text
step   0: generator mean -1.85 | fakes caught 100%
step  50: generator mean  2.21 | fakes caught 84%
step 150: generator mean  3.68 | fakes caught 53%
step 300: generator mean  3.95 | fakes caught 53%
```

The generator ends up producing data around 4, like the real data, and the detective is reduced to guessing (about 50%). That's the GAN goal: an equilibrium where fakes are indistinguishable.

::walk python | The discriminator's loss for one batch
import math
p_real_on_real = 0.9
p_real_on_fake = 0.2
d_loss = -(math.log(p_real_on_real) + math.log(1 - p_real_on_fake))
g_loss = -math.log(p_real_on_fake)
print(round(d_loss, 3), round(g_loss, 3))
::notes
1: Logs for binary cross-entropy.
2: D says a real image is real with 90% confidence: good. // vars: p_real_on_real=0.9
3: D says a fake is real with only 20%: D is winning. // vars: p_real_on_fake=0.2
4: D's loss: low when it's confident on real AND sceptical of fakes. // vars: d_loss=0.329
5: G's loss: −log D(fake). G wants D to believe the fakes, so this is high when D isn't fooled. // deep: This "non-saturating" generator loss gives stronger gradients early in training than the original minimax form. Balancing the two losses is the hard part of training GANs. // vars: g_loss=1.609
6: Right now the detective is ahead; the generator gets a strong signal to improve. // out: 0.329 1.609
::end

## Famous GAN variants

| Variant | Idea | Used for |
|---|---|---|
| **DCGAN** | Convolutional generator and discriminator | The first good image GANs; digits, faces |
| **Conditional GAN** | Also give a label ("generate a 7") | Controlled generation |
| **StyleGAN** | Controls style at each scale | Photorealistic faces (the "this person does not exist" images) |
| **CycleGAN** | Translate between two image sets without paired examples | Horses ↔ zebras, summer ↔ winter, photo ↔ painting |
| **Pix2Pix** | Translate with paired examples | Sketch → photo, map → satellite |

## Why GANs are tricky

- **Mode collapse:** the generator finds a few outputs that fool D and only makes those (every face the same).
- **Instability:** if one player gets too strong, the other stops learning.
- **No easy quality score:** metrics like FID compare generated and real image statistics.

That's why **diffusion models** (lesson 3) have taken over most image generation: they're more stable to train and produce more varied images. GANs remain useful for fast generation, super-resolution, and data augmentation.

## A DCGAN in PyTorch (outline)

```python
import torch
from torch import nn

G = nn.Sequential(nn.ConvTranspose2d(100, 128, 7, 1, 0), nn.BatchNorm2d(128), nn.ReLU(),
                  nn.ConvTranspose2d(128, 64, 4, 2, 1), nn.BatchNorm2d(64), nn.ReLU(),
                  nn.ConvTranspose2d(64, 1, 4, 2, 1), nn.Tanh())             # noise -> 28x28 image
D = nn.Sequential(nn.Conv2d(1, 64, 4, 2, 1), nn.LeakyReLU(0.2),
                  nn.Conv2d(64, 128, 4, 2, 1), nn.BatchNorm2d(128), nn.LeakyReLU(0.2),
                  nn.Flatten(), nn.Linear(128 * 7 * 7, 1))                    # image -> real/fake score
# each step: update D on real + G(noise); then update G so D(G(noise)) looks "real"
```

::tip GAN-generated faces and voices can be misused for deepfakes. Responsible use means consent, clear labelling, and never impersonating real people.

## Summary

- A GAN pits a generator (forger) against a discriminator (detective).
- Training alternates: D learns to catch fakes, G learns to fool D, until fakes look real.
- Variants: DCGAN, conditional GAN, StyleGAN, CycleGAN, Pix2Pix.
- Hard to train (mode collapse, instability); diffusion now leads image generation.
