---
title: "The Same Models in PyTorch"
slug: dl-pytorch
subject: deep-learning
lesson: 5
difficulty: intermediate
excerpt: Learn PyTorch, the framework behind most AI research and LLMs: tensors, autograd, nn.Module, the training loop, DataLoaders and moving to a GPU, compared side by side with Keras.
---

Keras is great for getting started. **PyTorch** is what most AI research, Hugging Face models and large language models are written in. It feels like writing normal Python: you write the training loop yourself, which makes it flexible and easy to debug.

::scene PyTorch's training loop
🔢 | Tensors | Data lives in tensors: NumPy arrays that can run on a GPU. | dev
🧮 | Autograd | Every operation is recorded, so loss.backward() computes all gradients automatically. | chip
🔁 | The loop | zero_grad, forward, loss, backward, step. Five lines, repeated every batch. | byte
⚡ | GPU | .to("cuda") moves the model and data to the GPU, often many times faster. | bugsy
::end

## Tensors and autograd

```python
import torch

x = torch.tensor(2.0, requires_grad=True)
w = torch.tensor(0.5, requires_grad=True)
loss = (w * x - 3.0) ** 2
loss.backward()                      # autograd computes d(loss)/dw and d(loss)/dx
print(loss.item(), w.grad.item())    # 4.0  -8.0  (same as our hand-made backprop)
```

## A model and the training loop

```python
import torch
from torch import nn

class ChurnNet(nn.Module):
    def __init__(self, n_features):
        super().__init__()
        self.net = nn.Sequential(nn.Linear(n_features, 64), nn.ReLU(), nn.Dropout(0.2),
                                 nn.Linear(64, 32), nn.ReLU(), nn.Linear(32, 1))

    def forward(self, x):
        return self.net(x)           # raw scores (logits)

device = "cuda" if torch.cuda.is_available() else "cpu"
model = ChurnNet(20).to(device)
loss_fn = nn.BCEWithLogitsLoss()     # sigmoid + binary cross-entropy, numerically stable
opt = torch.optim.Adam(model.parameters(), lr=1e-3)
loader = torch.utils.data.DataLoader(torch.utils.data.TensorDataset(X_train, y_train), batch_size=32, shuffle=True)

for epoch in range(20):
    model.train()
    for xb, yb in loader:
        xb, yb = xb.to(device), yb.to(device)
        opt.zero_grad()              # 1. clear old gradients
        loss = loss_fn(model(xb).squeeze(1), yb)   # 2-3. forward + loss
        loss.backward()              # 4. backpropagate
        opt.step()                   # 5. update weights
```

## The loop, simulated in plain Python

The five steps are the same everywhere. Here they are training our one-weight model until it fits:

::walk python | zero_grad, forward, loss, backward, step
w, lr = 0.5, 0.05
for step in range(30):
    grad = 0.0
    pred = w * 2.0
    loss = (pred - 3.0) ** 2
    grad = 2 * (pred - 3.0) * 2.0
    w -= lr * grad
print(round(w, 3), round(loss, 6))
::notes
1: Start with a bad weight and a learning rate. // vars: w=0.5, lr=0.05
2: Thirty training steps.
3: **zero_grad:** forget the previous step's gradient. // deep: PyTorch adds gradients up by default (useful for some tricks), so forgetting opt.zero_grad() is the classic bug: gradients from every step pile up and training explodes.
4: **forward:** make a prediction.
5: **loss:** measure the error.
6: **backward:** compute the gradient (PyTorch: loss.backward()).
7: **step:** update the weight (PyTorch: opt.step()). // vars: w → 1.5
8: The weight converges to 1.5, because 1.5 × 2 = 3, and the loss is practically 0. // out: 1.5 0.0
::end

## Keras vs PyTorch

| | Keras | PyTorch |
|---|---|---|
| Training | `model.fit(...)` does the loop | You write the loop (or use PyTorch Lightning) |
| Feel | High-level, quick | Pythonic, flexible, easy to debug |
| Popular in | Industry apps, beginners, mobile (TF Lite) | Research, LLMs, Hugging Face, most new papers |
| Saving | `model.save("m.keras")` | `torch.save(model.state_dict(), "m.pt")` |

Both are excellent. Learn the concepts once and switching is easy.

## Evaluating in PyTorch

```python
import torch

model.eval()                         # switches off dropout
with torch.no_grad():                # no gradient tracking: faster, less memory
    probs = torch.sigmoid(model(X_test.to(device))).squeeze(1)
    accuracy = ((probs > 0.5).float() == y_test.to(device)).float().mean().item()
```

::tip Always call `model.train()` before training and `model.eval()` before evaluating. Dropout and batch-norm layers behave differently in each mode, and mixing them up silently gives wrong results.

## See it as a chart: the learning rate

The training loop is the same in every framework: predict, measure the loss, step the weight against the gradient. The size of that step, the **learning rate**, decides everything. Here is the loop for one weight with three learning rates, plotting the weight itself as it moves towards the best value, 1.5.

```python
import matplotlib.pyplot as plt

x, target = 2.0, 3.0                   # we want w * 2 = 3, so the best w is 1.5
fig, ax = plt.subplots(figsize=(7.5, 3.6))
for lr, color in ((0.02, "#2563eb"), (0.1, "#0a7d4f"), (0.22, "#c2255c")):
    w, history = 0.5, []
    for step in range(25):
        history.append(w)
        grad = 2 * (w * x - target) * x  # d(loss)/dw, what loss.backward() computes
        w -= lr * grad
    ax.plot(history, marker="o", ms=3, color=color, label=f"learning rate {lr}")
ax.axhline(1.5, color="#1b1f27", ls="--", lw=1, label="best weight 1.5")
ax.set_xlabel("training step")
ax.set_ylabel("weight w")
ax.legend(frameon=False, loc="lower right")
fig.tight_layout()
plt.show()
```

::figure dl-learning-rates.svg | The weight over 25 steps for three learning rates: 0.02 creeps slowly towards 1.5, 0.1 reaches it in a few steps, 0.22 overshoots above and below 1.5 before settling

Too small and training crawls; well chosen and it converges in a few steps; too large and the weight overshoots back and forth (a bit larger still and it would fly off completely). Optimisers like Adam adapt the step size for you, but the learning rate is still the first setting to tune.

## Summary

- Tensors are GPU-ready arrays; autograd computes gradients with `loss.backward()`.
- Models subclass `nn.Module`; the loop is zero_grad → forward → loss → backward → step.
- `DataLoader` batches and shuffles; `.to(device)` moves work to the GPU.
- Use `model.eval()` + `torch.no_grad()` for evaluation.

🎉 You've finished **Deep Learning Foundations**.
