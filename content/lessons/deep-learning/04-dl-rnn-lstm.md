---
title: "RNNs and LSTMs for Sequences"
slug: dl-rnn-lstm
subject: deep-learning
lesson: 4
difficulty: intermediate
excerpt: Networks with memory: how recurrent neural networks read sequences step by step, why plain RNNs forget, how LSTM and GRU gates fix it, and an LSTM forecaster in Keras.
---

Sentences, stock prices, sensor readings and music are **sequences**: order matters. A Recurrent Neural Network reads them one step at a time and keeps a **memory** (the hidden state) of what came before.

::scene A network with a notebook
📖 | Reading | The network reads "the movie was not good" one word at a time. | dev
📝 | Memory | After each word it updates a small notebook: its hidden state. | chip
😶 | Forgetting | Plain RNNs smudge old notes: by the end they've forgotten "not". | bugsy
🚪 | Gates | LSTMs add gates that decide what to keep, what to forget and what to output. "not" survives. | byte
::end

## An RNN step by step

At each step: `h_new = tanh(W_x × input + W_h × h_old + b)`. The same weights are reused at every step.

::walk python | Carrying memory through a sequence
import math
w_x, w_h = 0.8, 0.5
h = 0.0
for x in [1.0, 0.0, 0.0]:
    h = math.tanh(w_x * x + w_h * h)
print(round(h, 3))
::notes
1: tanh keeps the memory between −1 and 1.
2: Two weights: how much the new input matters, and how much the old memory matters. // vars: w_x=0.8, w_h=0.5
3: The memory starts empty. // vars: h=0.0
4: Read a sequence: a signal, then two zeros.
5: Step 1: h = tanh(0.8). Steps 2 and 3 only carry memory: 0.5 × h each time, so it fades. // deep: Multiplying by 0.5 again and again shrinks the signal exponentially. During training the gradients shrink the same way: the "vanishing gradient" problem that makes plain RNNs forget long-range information. // vars: h=0.664 → 0.322 → 0.159
6: After just two steps of silence, the memory of the signal is down to a quarter. // out: 0.159
::end

## LSTM and GRU: memory with gates

An **LSTM** (Long Short-Term Memory) keeps a separate **cell state** and uses three gates (each a small sigmoid layer giving 0–1):

| Gate | Question it answers |
|---|---|
| Forget gate | How much of the old memory should I keep? |
| Input gate | How much of the new information should I write? |
| Output gate | How much of the memory should I show to the next layer? |

A **GRU** is a lighter variant with two gates; it often performs similarly with fewer parameters.

```python
import numpy as np

sig = lambda z: 1 / (1 + np.exp(-z))
cell = 1.0                                    # important memory, e.g. "not"
for step in range(5):
    forget = sig(4.0)                         # a learned gate near 1: keep it
    cell = forget * cell
print("LSTM-style memory after 5 steps:", round(cell, 3))
print("plain RNN-style memory after 5 steps:", round(0.5 ** 5, 3))
```

Output:

```text
LSTM-style memory after 5 steps: 0.913
plain RNN-style memory after 5 steps: 0.031
```

Because the forget gate can learn to stay close to 1, important information survives many steps.

## An LSTM forecaster in Keras

```python
import numpy as np
from tensorflow import keras

def windows(series, size=12):
    X = np.array([series[i:i + size] for i in range(len(series) - size)])
    y = series[size:]
    return X[..., np.newaxis], y              # shape: (samples, timesteps, features)

X, y = windows(scaled_sales)                  # scale the series to roughly 0-1 first
model = keras.Sequential([
    keras.Input(shape=(12, 1)),
    keras.layers.LSTM(32),
    keras.layers.Dense(1),
])
model.compile("adam", "mse")
model.fit(X, y, epochs=50, validation_split=0.2, verbose=0)
```

The key idea is the **sliding window**: use the last 12 values to predict the next one:

```python
series = np.arange(1, 17)
X = np.array([series[i:i + 12] for i in range(len(series) - 12)])
print(X.shape, "first window ends at", X[0, -1], "-> target", series[12])
```

Output:

```text
(4, 12) first window ends at 12 -> target 13
```

## Where sequence models are used today

- **Time series:** demand, energy load, sensor anomaly detection.
- **Text:** before 2018 LSTMs dominated translation and sentiment; now **transformers** (attention) have largely replaced them for language, because they process all words in parallel and handle long range better.
- **Speech and handwriting:** combined with CNNs.

::tip For forecasting, compare an LSTM against ARIMA and gradient boosting on lag features. On small or medium datasets the simpler models often win, and they're much cheaper to train and explain.

## See it as a diagram: turning a series into training windows

Sequence models learn from **windows**: the last 12 values are the input and the next value is the target. Slide the window one step and you get the next training example.

```python
import matplotlib.pyplot as plt
import numpy as np

series = 50 + 10 * np.sin(np.arange(30) / 3) + np.arange(30)
fig, axes = plt.subplots(3, 1, figsize=(8.5, 4.4), sharex=True)
for row, start in enumerate([0, 1, 2]):
    ax = axes[row]
    ax.plot(series, color="#cfd4dc", marker="o", ms=3)
    window = np.arange(start, start + 12)
    ax.plot(window, series[window], color="#2563eb", marker="o", ms=4, lw=2, label="input window (12 steps)")
    ax.plot(start + 12, series[start + 12], "o", color="#c2255c", ms=9, label="target")
    ax.set_yticks([])
    ax.set_ylabel(f"example {row + 1}", rotation=0, ha="right", va="center")
axes[0].legend(frameon=False, ncol=2, loc="upper left", fontsize=9)
axes[-1].set_xlabel("time step")
fig.tight_layout()
plt.show()
```

::figure dl-sequence-windows.svg | Three copies of one series; in each a blue 12-step window slides one step to the right and the pink point after it is the value to predict

This is the shape `(samples, timesteps, features)` the LSTM expects: each blue window is one sample of 12 timesteps with 1 feature.

## Summary

- RNNs read sequences step by step with a hidden-state memory and shared weights.
- Plain RNNs forget (vanishing gradients); LSTM/GRU gates keep important information.
- Keras: `LSTM` layers on windows shaped (samples, timesteps, features).
- Transformers now lead for text; LSTMs remain useful for many time series.
