---
title: "Neurons, Layers and How a Neural Network Learns"
slug: dl-neurons-layers
subject: deep-learning
lesson: 1
difficulty: intermediate
excerpt: Build a neural network from scratch in NumPy: neurons, weights, activation functions, forward pass, loss, and backpropagation, and watch it learn XOR, a problem a straight line can't solve.
---

Deep learning powers face unlock, Google Translate, voice assistants and every large language model. Underneath, it's a simple idea stacked many times: **a neuron multiplies inputs by weights, adds them up, and squashes the result**. Stack neurons in layers and you can learn almost any pattern.

::scene Inside the network
🔌 | A neuron | Inputs come in, each multiplied by a weight. Add them up with a bias. | dev
🚦 | Activation | A squashing function (ReLU, sigmoid) decides how strongly the neuron fires. | chip
🧱 | Layers | Neurons are stacked in layers; each layer builds on the last: edges, shapes, faces. | byte
🔙 | Backprop | The error flows backwards, telling every weight which way to move. | bugsy
🎓 | Learned | After thousands of nudges, the network solves a problem no straight line could. | dev
::end

## One neuron

```python
import numpy as np

def relu(z):
    return np.maximum(0, z)

inputs = np.array([0.5, 0.8, 0.2])       # e.g. three features
weights = np.array([0.4, -0.6, 0.9])
bias = 0.3
z = inputs @ weights + bias              # weighted sum
print("weighted sum:", round(z, 3), "| after ReLU:", round(relu(z), 3))
```

Output:

```text
weighted sum: 0.2 | after ReLU: 0.2
```

That's a neuron. A **layer** is many neurons side by side (a matrix of weights); a network is layers in a row.

## Activation functions

Without a non-linear activation, stacking layers would still only draw straight lines.

| Activation | Formula | Used for |
|---|---|---|
| ReLU | max(0, z) | Hidden layers (default choice) |
| Sigmoid | 1 / (1 + e^−z) | Output for yes/no probability |
| Softmax | e^zᵢ / Σe^z | Output for one-of-many classes |
| tanh | between −1 and 1 | Older networks, RNNs |

## Learn XOR from scratch

XOR is "true if exactly one input is true". No single straight line separates it; one hidden layer does.

```python
rng = np.random.default_rng(1)
X = np.array([[0, 0], [0, 1], [1, 0], [1, 1]], dtype=float)
y = np.array([[0], [1], [1], [0]], dtype=float)

W1, b1 = rng.normal(0, 1, (2, 4)), np.zeros((1, 4))
W2, b2 = rng.normal(0, 1, (4, 1)), np.zeros((1, 1))
sigmoid = lambda z: 1 / (1 + np.exp(-z))
lr = 1.0

for epoch in range(3001):
    h = np.tanh(X @ W1 + b1)                 # forward: hidden layer
    out = sigmoid(h @ W2 + b2)               # forward: output
    loss = np.mean((out - y) ** 2)
    d_out = (out - y) * out * (1 - out)      # backward: output error
    d_h = (d_out @ W2.T) * (1 - h ** 2)      # backward: hidden error
    W2 -= lr * h.T @ d_out;  b2 -= lr * d_out.sum(0, keepdims=True)
    W1 -= lr * X.T @ d_h;    b1 -= lr * d_h.sum(0, keepdims=True)
    if epoch in (0, 500, 3000):
        print(f"epoch {epoch:>4}: loss {loss:.4f}")

print("predictions:", out.round(2).ravel())
```

Output:

```text
epoch    0: loss 0.2400
epoch  500: loss 0.0008
epoch 3000: loss 0.0001
predictions: [0.   0.99 0.99 0.01]
```

The network learned XOR: near 0 for (0,0) and (1,1), near 1 for the others. Every deep-learning framework automates exactly these steps.

## Backpropagation in one step

::walk python | The chain rule, on one weight
x, w, target = 2.0, 0.5, 3.0
pred = w * x
loss = (pred - target) ** 2
grad = 2 * (pred - target) * x
w = w - 0.1 * grad
print(pred, loss, grad, w)
::notes
1: One input, one weight, and the value we want. // vars: x=2.0, w=0.5, target=3.0
2: **Forward pass:** the prediction 0.5 × 2 = 1. // vars: pred=1.0
3: The loss: how far off, squared. // vars: loss=4.0
4: **Backward pass:** the chain rule gives d(loss)/dw = 2 × (pred − target) × x. // deep: In a deep network the chain rule is applied layer by layer from the output back to the input, reusing each layer's result. That's backpropagation. PyTorch and TensorFlow compute it automatically ("autograd"). // vars: grad=-8.0
5: Gradient descent: move w against the gradient. The new prediction would be 1.8 × 2 = 3.6, much closer to 3. // vars: w=1.3
6: One step of learning. Networks do millions of these. // out: 1.0 4.0 -8.0 1.3
::end

## Words you'll hear

- **Epoch:** one pass over all the training data.
- **Batch:** the small group of examples used for one update.
- **Learning rate:** step size; too big diverges, too small crawls.
- **Overfitting:** memorising training data; fight it with more data, dropout, early stopping.
- **Optimiser:** Adam is the usual default; it adapts the step size per weight.

::tip A network is only "deep" because it has many layers. More layers can learn richer patterns but need more data and compute. For small tabular datasets, gradient boosting usually beats neural networks.

## See it as a chart: activations and learning XOR

Left: the three activation functions you will meet most. Right: the loss of the small network above while it learns XOR, falling as backpropagation adjusts the weights.

```python
import matplotlib.pyplot as plt
import numpy as np

z = np.linspace(-5, 5, 200)
rng = np.random.default_rng(1)
X = np.array([[0, 0], [0, 1], [1, 0], [1, 1]], dtype=float)
y = np.array([[0], [1], [1], [0]], dtype=float)
W1, b1 = rng.normal(0, 1, (2, 4)), np.zeros((1, 4))
W2, b2 = rng.normal(0, 1, (4, 1)), np.zeros((1, 1))
sigmoid = lambda v: 1 / (1 + np.exp(-v))
losses = []
for epoch in range(3001):
    h = np.tanh(X @ W1 + b1)
    out = sigmoid(h @ W2 + b2)
    losses.append(float(np.mean((out - y) ** 2)))
    d_out = (out - y) * out * (1 - out)
    d_h = (d_out @ W2.T) * (1 - h ** 2)
    W2 -= h.T @ d_out
    b2 -= d_out.sum(axis=0, keepdims=True)
    W1 -= X.T @ d_h
    b1 -= d_h.sum(axis=0, keepdims=True)

fig, (left, right) = plt.subplots(1, 2, figsize=(10, 3.5))
left.plot(z, 1 / (1 + np.exp(-z)), color="#2563eb", lw=2, label="sigmoid")
left.plot(z, np.tanh(z), color="#f59e0b", lw=2, label="tanh")
left.plot(z, np.maximum(0, z), color="#0a7d4f", lw=2, label="ReLU")
left.set_ylim(-1.2, 2.5)
left.axhline(0, color="#9ca3af", lw=0.8)
left.legend(frameon=False)
left.set_title("Activation functions")
right.plot(losses, color="#c2255c", lw=2)
right.set_xlabel("epoch")
right.set_ylabel("mean squared error")
right.set_title("Learning XOR: the loss falls")
fig.tight_layout()
plt.show()
```

::figure dl-activations-xor-loss.svg | Left: sigmoid, tanh and ReLU curves; right: the training loss for XOR dropping from about 0.25 towards zero over 3000 epochs

ReLU is the default inside modern networks because it is cheap and does not flatten out for big inputs. The falling loss is what "the network is learning" looks like.

## Summary

- Neuron = weighted sum + bias + activation; layers stack neurons.
- Non-linear activations (ReLU, sigmoid, softmax) let networks learn curves.
- Training = forward pass, loss, backpropagation (chain rule), gradient descent.
- You trained a network on XOR from scratch in NumPy.
