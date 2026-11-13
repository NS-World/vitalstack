---
title: "Your First Neural Network with Keras (TensorFlow)"
slug: dl-first-network-keras
subject: deep-learning
lesson: 2
difficulty: intermediate
excerpt: Build, train and evaluate a neural network with Keras: Sequential models, Dense layers, compile, fit with validation, callbacks like EarlyStopping, and a runnable scikit-learn MLP version.
---

Writing backpropagation by hand teaches the idea; real projects use a framework. **Keras**, part of TensorFlow, lets you define a network in a few lines and handles the gradients, batching and GPU for you.

::scene Five lines to a neural network
🧱 | Define | Stack layers: 64 neurons, then 32, then one output neuron. | dev
⚙️ | Compile | Pick the optimiser (Adam), the loss and the metrics. | chip
🏋️ | Fit | Train for a few epochs, watching validation accuracy. | byte
🛑 | Stop early | When validation stops improving, training stops itself and keeps the best weights. | bugsy
::end

## The Keras recipe

Install with `pip install tensorflow`. A binary classifier for tabular data:

```python
import tensorflow as tf
from tensorflow import keras

model = keras.Sequential([
    keras.Input(shape=(20,)),
    keras.layers.Dense(64, activation="relu"),
    keras.layers.Dropout(0.2),
    keras.layers.Dense(32, activation="relu"),
    keras.layers.Dense(1, activation="sigmoid"),
])
model.compile(optimizer="adam", loss="binary_crossentropy", metrics=["accuracy"])
model.summary()

early = keras.callbacks.EarlyStopping(monitor="val_loss", patience=5, restore_best_weights=True)
history = model.fit(X_train, y_train, validation_split=0.2, epochs=100, batch_size=32, callbacks=[early])
print(model.evaluate(X_test, y_test))
model.save("churn_model.keras")
```

| Step | What you choose |
|---|---|
| Layers | `Dense` (fully connected), `Dropout` (randomly switches neurons off to reduce overfitting) |
| Output | 1 sigmoid neuron (yes/no), k softmax neurons (k classes), 1 linear neuron (a number) |
| Loss | `binary_crossentropy`, `sparse_categorical_crossentropy`, `mse` |
| Optimiser | `adam` is a strong default |
| Callbacks | `EarlyStopping`, `ModelCheckpoint`, `TensorBoard` |

## The same network, runnable with scikit-learn

scikit-learn's `MLPClassifier` is a small neural network: perfect for trying the ideas without TensorFlow.

```python
from sklearn.datasets import make_classification
from sklearn.neural_network import MLPClassifier
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler

X, y = make_classification(n_samples=2000, n_features=20, n_informative=8, random_state=0)
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=0)
scaler = StandardScaler().fit(X_train)

mlp = MLPClassifier(hidden_layer_sizes=(64, 32), early_stopping=True, validation_fraction=0.2,
                    max_iter=300, random_state=0)
mlp.fit(scaler.transform(X_train), y_train)
print("epochs run:", mlp.n_iter_, "| test accuracy:", round(mlp.score(scaler.transform(X_test), y_test), 3))
```

Output:

```text
epochs run: 37 | test accuracy: 0.882
```

## Counting parameters

::walk python | How many weights does the model have?
layers = [20, 64, 32, 1]
params = 0
for a, b in zip(layers, layers[1:]):
    params += a * b + b
print(params)
::notes
1: Input size 20, then layers of 64, 32 and 1 neurons. // vars: layers=[20, 64, 32, 1]
2: Start counting. // vars: params=0
3: Between each pair of layers…
4: …there are a × b weights plus b biases: 20×64+64 = 1,344, then 64×32+32 = 2,080, then 32×1+1 = 33. // deep: Dropout layers have no parameters. Keras's model.summary() prints exactly this per layer. LLMs use the same arithmetic, just with billions instead of thousands. // vars: params=3457
5: 3,457 trainable numbers. // out: 3457
::end

## Reading the training curves

Plot `history.history["loss"]` and `["val_loss"]`:

- Both falling → still learning; train longer.
- Training falls, validation rises → **overfitting**; add dropout, regularisation or data, or stop earlier.
- Both high and flat → **underfitting**; bigger model, more epochs, better features, or a higher learning rate.

::tip Always scale inputs (StandardScaler or Normalization layer) for neural networks. Unscaled features make training slow and unstable: the most common beginner bug.

## See it as a chart: the training curves

Plot the training loss and the validation accuracy for each epoch of the scikit-learn network above. Training stops early once validation accuracy stops improving, which protects against overfitting.

```python
import matplotlib.pyplot as plt
from sklearn.datasets import make_classification
from sklearn.model_selection import train_test_split
from sklearn.neural_network import MLPClassifier
from sklearn.preprocessing import StandardScaler

X, y = make_classification(n_samples=2000, n_features=20, n_informative=8, random_state=0)
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=0)
scaler = StandardScaler().fit(X_train)
mlp = MLPClassifier(hidden_layer_sizes=(64, 32), early_stopping=True, validation_fraction=0.2,
                    max_iter=300, random_state=0).fit(scaler.transform(X_train), y_train)

fig, (left, right) = plt.subplots(1, 2, figsize=(10, 3.4))
left.plot(mlp.loss_curve_, color="#c2255c", lw=2)
left.set_title("Training loss")
left.set_xlabel("epoch")
right.plot(mlp.validation_scores_, color="#0a7d4f", lw=2)
best = max(range(len(mlp.validation_scores_)), key=mlp.validation_scores_.__getitem__)
right.axvline(best, color="#1b1f27", ls="--", lw=1)
right.text(best + 0.5, min(mlp.validation_scores_), " best epoch", va="bottom")
right.set_title("Validation accuracy")
right.set_xlabel("epoch")
fig.tight_layout()
plt.show()
```

::figure dl-training-curves.svg | Left: training loss falling quickly then flattening; right: validation accuracy rising then levelling off, with a dashed line at the best epoch

Healthy curves look like this: loss going down, validation accuracy going up and then flat. If training loss keeps falling while validation accuracy drops, the network is memorising.

## Summary

- Keras: `Sequential` → `compile` → `fit` → `evaluate` → `save`.
- Dense layers + ReLU inside, sigmoid/softmax/linear at the output, Adam optimiser.
- Use a validation split, EarlyStopping and Dropout against overfitting.
- Parameters = weights + biases; read the loss curves to diagnose training.
