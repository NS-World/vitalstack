---
title: "Autoencoders and Variational Autoencoders (VAEs)"
slug: gen-vaes
subject: generative-models
lesson: 2
difficulty: intermediate
excerpt: Compress data into a small code and rebuild it: autoencoders for compression and anomaly detection, VAEs for generation with a smooth latent space, the reparameterisation trick, and VAEs vs GANs.
---

An **autoencoder** squeezes data through a narrow bottleneck and tries to rebuild it. To succeed, it must learn the most important features. A **Variational Autoencoder (VAE)** makes that bottleneck a smooth, organised space, so you can pick a new point in it and **generate** something new. VAEs are also a key part of modern image generators: Stable Diffusion runs its diffusion inside a VAE's compressed space.

::scene Packing a suitcase
🧳 | Compress | The encoder packs a 784-pixel digit into just 2 numbers: a tiny suitcase. | dev
📦 | The code | Those 2 numbers are the "latent code": the essence of the image. | chip
🔄 | Unpack | The decoder rebuilds the digit from the code. Not perfect, but recognisable. | byte
✨ | Generate | In a VAE, nearby codes give similar digits, so picking a new code creates a new digit. | bugsy
::end

## An autoencoder you can run (via PCA)

A linear autoencoder learns essentially what PCA learns, so we can see compression and reconstruction with scikit-learn:

```python
import numpy as np
from sklearn.datasets import load_digits
from sklearn.decomposition import PCA

X = load_digits().data / 16                       # 64 pixels per digit
for k in (2, 10, 30):
    pca = PCA(n_components=k).fit(X)
    rebuilt = pca.inverse_transform(pca.transform(X))
    err = np.mean((X - rebuilt) ** 2)
    print(f"code size {k:>2}: reconstruction error {err:.4f}")
```

Output:

```text
code size  2: reconstruction error 0.0524
code size 10: reconstruction error 0.0192
code size 30: reconstruction error 0.0030
```

Bigger codes rebuild better. Neural (non-linear) autoencoders compress far better than PCA at the same code size.

## Anomaly detection with reconstruction error

An autoencoder trained only on normal data rebuilds normal inputs well and strange ones badly. That makes it a great anomaly detector for fraud, faulty machines or bad scans:

```python
pca = PCA(n_components=10).fit(X)
def recon_error(x):
    return float(np.mean((x - pca.inverse_transform(pca.transform(x[None])))[0] ** 2))

normal = X[0]
weird = np.random.default_rng(0).random(64)       # random noise, not a digit
print("normal digit error:", round(recon_error(normal), 4), "| random noise error:", round(recon_error(weird), 4))
```

Output:

```text
normal digit error: 0.0087 | random noise error: 0.1635
```

The noise image is rebuilt badly, so it's flagged.

## From autoencoder to VAE

A plain autoencoder's code space has gaps: a random point may decode into garbage. A VAE fixes this:

- The encoder outputs a **mean μ and a spread σ** for each code dimension, not one point.
- We **sample** a code from that distribution.
- The loss has two parts: **reconstruction** (rebuild well) + **KL divergence** (keep codes close to a standard normal distribution).

The result is a smooth latent space: move a little, the output changes a little. Walk from a "3" to an "8" and you see in-between digits.

::walk python | The reparameterisation trick
import random
random.seed(1)
mu, sigma = 0.5, 0.2
eps = random.gauss(0, 1)
z = mu + sigma * eps
print(round(eps, 3), round(z, 3))
::notes
1: random for a standard normal sample.
2: Fix the seed so you see the same numbers.
3: The encoder says: this digit's code is around 0.5, give or take 0.2. // vars: mu=0.5, sigma=0.2
4: Sample noise from a standard normal distribution, outside the network. // vars: eps=1.288
5: Shift and scale it: z = μ + σ × ε. // deep: Sampling directly from N(μ, σ) can't be backpropagated through. Writing it as μ + σ·ε moves the randomness into ε, so gradients flow through μ and σ. That's the "reparameterisation trick" that makes VAEs trainable. // vars: z=0.758
6: This z goes to the decoder. // out: 1.288 0.758
::end

## The KL term in one dimension

```python
import math

def kl_to_standard_normal(mu, sigma):
    return 0.5 * (sigma ** 2 + mu ** 2 - 1 - math.log(sigma ** 2))

for mu, sigma in ((0, 1), (0.5, 0.2), (3, 0.1)):
    print(mu, sigma, round(kl_to_standard_normal(mu, sigma), 3))
```

Output:

```text
0 1 0.0
0.5 0.2 1.254
3 0.1 6.308
```

Codes far from 0 or too narrow get a penalty, which keeps the latent space tidy and fillable.

## VAEs vs GANs vs diffusion

| | VAE | GAN | Diffusion |
|---|---|---|---|
| Training | Stable | Tricky | Stable |
| Image sharpness | Often blurry | Sharp | Sharp and varied |
| Latent space | Smooth, meaningful | Less structured | Noise space |
| Speed of generating | Fast | Fast | Slower (many steps), improving |
| Typical use today | Compression, anomaly detection, latent space for diffusion | Super-resolution, fast generation | Most text-to-image and video |

::tip Autoencoder-based anomaly detection is one of the most practical uses for industry: train on months of normal sensor data, then alert when reconstruction error jumps.

## Summary

- Autoencoders compress to a latent code and rebuild; reconstruction error flags anomalies.
- VAEs encode a distribution (μ, σ), sample with z = μ + σ·ε, and add a KL penalty.
- The smooth latent space lets VAEs generate and interpolate.
- Diffusion models often work inside a VAE's latent space.
