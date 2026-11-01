---
title: "Project 6: Image Classifier with Transfer Learning"
slug: project-image-classifier
subject: ds-projects
lesson: 6
difficulty: intermediate
excerpt: Classify crop-leaf photos as healthy or diseased: dataset layout, augmentation, transfer learning with MobileNetV2 in Keras, evaluation with a confusion matrix, and a runnable small-scale version.
---

A farmer photographs a tomato leaf with a cheap phone; an app says "early blight, 92% sure, here's what to do". Image classification with transfer learning makes projects like this possible with a few thousand photos and an ordinary laptop or free GPU notebook.

::scene A plant doctor in your pocket
🍅 | The problem | Leaf diseases spread fast. Experts can't visit every farm. | dev
🗂️ | The data | Thousands of labelled leaf photos: healthy, early blight, leaf mould. | chip
🧠 | Borrowed brains | A model pretrained on millions of images already knows edges, textures and shapes. We teach it only the last step. | byte
📱 | In the field | The trained model, shrunk for phones, gives an answer in a second, even offline. | bugsy
::end

## Dataset layout

Keras (and most tools) can read images straight from class folders:

```text
leaves/
  train/
    healthy/      img001.jpg ...
    early_blight/ img201.jpg ...
    leaf_mould/   img401.jpg ...
  val/
    healthy/ ...  early_blight/ ...  leaf_mould/ ...
```

Public datasets like **PlantVillage** have tens of thousands of labelled leaf images. Check the licence, and note that lab photos look cleaner than real field photos: collect some real ones for testing.

## The transfer-learning model (Keras)

```python
from tensorflow import keras

train = keras.utils.image_dataset_from_directory("leaves/train", image_size=(224, 224), batch_size=32)
val = keras.utils.image_dataset_from_directory("leaves/val", image_size=(224, 224), batch_size=32)

augment = keras.Sequential([keras.layers.RandomFlip("horizontal"), keras.layers.RandomRotation(0.1),
                            keras.layers.RandomZoom(0.1), keras.layers.RandomContrast(0.2)])
base = keras.applications.MobileNetV2(input_shape=(224, 224, 3), include_top=False, weights="imagenet")
base.trainable = False                                   # stage 1: train only the new head

inputs = keras.Input(shape=(224, 224, 3))
x = augment(inputs)
x = keras.applications.mobilenet_v2.preprocess_input(x)
x = base(x, training=False)
x = keras.layers.GlobalAveragePooling2D()(x)
x = keras.layers.Dropout(0.3)(x)
outputs = keras.layers.Dense(3, activation="softmax")(x)
model = keras.Model(inputs, outputs)
model.compile(keras.optimizers.Adam(1e-3), "sparse_categorical_crossentropy", metrics=["accuracy"])
model.fit(train, validation_data=val, epochs=10,
          callbacks=[keras.callbacks.EarlyStopping(patience=3, restore_best_weights=True)])

base.trainable = True                                    # stage 2: fine-tune gently
model.compile(keras.optimizers.Adam(1e-5), "sparse_categorical_crossentropy", metrics=["accuracy"])
model.fit(train, validation_data=val, epochs=5)
```

## A small runnable version of the same workflow

No GPU here, so we use scikit-learn's 8×8 digit images to practise the full workflow: split, augment, train, evaluate.

```python
import numpy as np
from sklearn.datasets import load_digits
from sklearn.model_selection import train_test_split
from sklearn.svm import SVC
from sklearn.metrics import confusion_matrix, classification_report

digits = load_digits()
X_train, X_test, y_train, y_test = train_test_split(digits.images, digits.target, test_size=0.25,
                                                    stratify=digits.target, random_state=0)
shifted = np.roll(X_train, 1, axis=2)                            # augmentation: shift right by 1 pixel
X_aug = np.concatenate([X_train, shifted]).reshape(-1, 64) / 16
y_aug = np.concatenate([y_train, y_train])

clf = SVC(gamma="scale", C=5).fit(X_aug, y_aug)
pred = clf.predict(X_test.reshape(-1, 64) / 16)
print("accuracy:", round((pred == y_test).mean(), 3))
cm = confusion_matrix(y_test, pred)
off = cm - np.diag(np.diag(cm))
i, j = np.unravel_index(off.argmax(), off.shape)
print(f"most common mistake: true {i} predicted as {j} ({off[i, j]} times)")
```

Output:

```text
accuracy: 0.991
most common mistake: true 8 predicted as 1 (2 times)
```

::walk python | Reading a confusion matrix row
row_true_blight = {"healthy": 6, "early_blight": 88, "leaf_mould": 6}
total = sum(row_true_blight.values())
recall = row_true_blight["early_blight"] / total
worst = max((k for k in row_true_blight if k != "early_blight"), key=row_true_blight.get)
print(total, recall, worst)
::notes
1: One row of a leaf-disease confusion matrix: 100 leaves that truly had early blight, and what the model said. // vars: row_true_blight={'healthy': 6, 'early_blight': 88, 'leaf_mould': 6}
2: 100 truly diseased leaves. // vars: total=100
3: Recall for early blight: 88%. // deep: For a disease detector, a blight leaf predicted "healthy" is the dangerous mistake: the farmer does nothing. Watch that cell closely; you may lower the threshold for "disease" or ask for a second photo when unsure. // vars: recall=0.88
4: The most common confusion (healthy and leaf mould tie at 6; max picks the first). // vars: worst=healthy
5: Errors are where you collect more data next. // out: 100 0.88 healthy
::end

## Deploying to a phone

- Convert with **TensorFlow Lite** (`tf.lite.TFLiteConverter`), optionally with 8-bit quantisation to shrink the model about 4×.
- Show the top prediction **and** the confidence; below a threshold, say "not sure, retake the photo in daylight".
- Collect (with permission) photos users submit, label the hard ones, and retrain.

## Portfolio checklist

- [ ] Data card: source, licence, class counts, train/val/test split by source (no near-duplicates across splits)
- [ ] Baseline (frozen base) vs fine-tuned, accuracy and per-class recall
- [ ] Confusion matrix with the dangerous mistakes discussed
- [ ] A demo: Streamlit or Hugging Face Spaces app where people upload a photo

## Summary

- Organise images in class folders; augment to simulate real-world variety.
- Transfer learning: freeze a pretrained base, train a new head, then fine-tune gently.
- Evaluate with per-class recall and the confusion matrix; focus on costly mistakes.
- Shrink with TFLite for phones and keep improving with real user photos.

🎉 You've finished the **Data Scientist** portfolio projects, and the whole **Data Scientist track**.
