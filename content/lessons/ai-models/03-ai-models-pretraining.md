---
title: "Pretraining: How a Model Learns by Guessing the Next Word"
slug: ai-models-pretraining
subject: ai-models
lesson: 3
difficulty: beginner
excerpt: Loss, gradient descent, parameters and GPUs explained simply. Train a one-number model in Python and see exactly how ChatGPT-style models learn during pretraining.
---

Pretraining is where a model gets almost all of its knowledge. It is also where most of the money goes. Yet the core idea fits in one sentence:

> **Make a guess, measure how wrong it was, adjust slightly, repeat, a few trillion times.**

## The three words you need

- **Parameters (weights):** the adjustable numbers inside the model. Today's large models have billions of them. At the start they are random.
- **Loss:** a single number that says how wrong the model's guesses were. Lower is better.
- **Gradient descent:** the method for adjusting every parameter a tiny step in the direction that lowers the loss.

::scene One training step, slowed down
📝 | Show | The model sees "The capital of India is" and must guess the next token. | dev
🎲 | Guess | It gives "Delhi" only a 2% chance. It is a fresh model; its numbers are random. | byte
📏 | Measure | The loss is high: the right answer got a tiny probability. | chip
🧭 | Direction | Calculus works out, for every parameter, which way to move to make "Delhi" more likely. | bugsy
👣 | Step | Every parameter moves a tiny bit. Next time "Delhi" gets 3%. | dev
🔁 | Repeat | Do this for trillions of tokens. Facts, grammar and reasoning slowly appear. | byte
::end

## Train a model with ONE parameter

Real models predict tokens, but the learning process is identical for any prediction. Our model guesses chai prices: `price = w × cups`. It must discover `w` (the price per cup) from examples.

```python
cups   = [1, 2, 3, 4]
prices = [15, 30, 45, 60]        # true price: 15 per cup

w = 0.0                          # start with a bad guess
lr = 0.01                        # learning rate: how big each step is

for epoch in range(1, 41):
    grad = 0.0
    loss = 0.0
    for x, y in zip(cups, prices):
        guess = w * x
        error = guess - y
        loss += error ** 2
        grad += 2 * error * x
    w -= lr * grad / len(cups)
    if epoch in (1, 2, 5, 10, 40):
        print(f"epoch {epoch:>2}: w = {w:6.2f}  loss = {loss / len(cups):8.2f}")
```

Output:

```text
epoch  1: w =   2.25  loss =  1687.50
epoch  2: w =   4.16  loss =  1219.22
epoch  5: w =   8.34  loss =   459.83
epoch 10: w =  12.05  loss =    90.53
epoch 40: w =  14.98  loss =     0.01
```

Starting from 0, `w` climbs to almost exactly 15 and the loss falls to nearly zero. Nobody told the model the price: it **learned it from examples**. Now imagine billions of `w`s and the task "predict the next token": that's pretraining.

## The update rule, line by line

::walk python | One gradient descent step
w = 0.0
x, y = 2, 30
guess = w * x
error = guess - y
grad = 2 * error * x
w = w - 0.01 * grad
print("new w:", w)
::notes
1: The model's only parameter starts at **0**: it knows nothing. // vars: w=0.0
2: One training example: 2 cups cost ₹30. // vars: x=2, y=30
3: The model guesses 0 × 2 = **0**. Way off. // vars: guess=0.0
4: The error is −30: the guess was 30 too low. // vars: error=-30.0
5: The **gradient** says how the loss changes if w grows. Negative means "increase w". // deep: The loss is error². Its slope with respect to w is 2 × error × x = 2 × (−30) × 2 = −120. A negative slope means the loss goes down when w goes up. // vars: grad=-120.0
6: Step opposite to the gradient, scaled by the learning rate 0.01: w becomes 0 + 1.2. // deep: Too large a learning rate overshoots and can blow up; too small takes forever. Choosing it well is a big part of training large models. // vars: w=1.2
7: One step closer to 15. Repeat thousands of times. // out: new w: 1.2
::end

## Why it needs thousands of GPUs

Every training step does a forward pass (make guesses), a backward pass (work out gradients for every parameter), and an update. For a model with billions of parameters reading trillions of tokens, that is an astronomical number of multiplications. GPUs are built to do huge numbers of multiplications in parallel, so labs connect thousands of them and train for weeks.

```python
params = 70e9            # a 70-billion-parameter model
tokens = 15e12           # trained on 15 trillion tokens
flops = 6 * params * tokens          # a common rule of thumb
gpu_flops_per_sec = 4e14             # one modern GPU, realistic speed
gpu_days = flops / gpu_flops_per_sec / 86400
print(f"{flops:.1e} operations ≈ {gpu_days:,.0f} GPU-days")
print(f"with 4,000 GPUs: about {gpu_days / 4000:.0f} days")
```

Output:

```text
6.3e+24 operations ≈ 182,292 GPU-days
with 4,000 GPUs: about 46 days
```

"6 × parameters × tokens" is a well-known estimate of training compute. The GPU speed above is a rough assumption: real numbers vary by hardware and efficiency, but the scale is right: months of work for thousands of chips.

::tip **Scaling laws:** researchers found that loss falls predictably as you grow parameters, data and compute together. That discovery is why companies keep building larger models and larger data centres.

## What the model ends up "knowing"

After pretraining, the model has compressed patterns from its data into its parameters: grammar in many languages, facts, code syntax, how arguments are structured. It does **not** store web pages like a database, and it has no idea what is true; it only knows what text is **likely**. That is why pretrained models also learn mistakes, bias and outdated information present in their data.

## Summary

- Parameters start random; **loss** measures wrongness; **gradient descent** reduces it step by step.
- Your one-parameter model learned w = 15 from examples; LLMs learn billions of parameters the same way.
- Compute ≈ 6 × parameters × tokens, which means thousands of GPUs for weeks.
- Pretraining gives knowledge, not manners or judgement. Those come next.
