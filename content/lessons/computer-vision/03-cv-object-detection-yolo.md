---
title: "Object Detection with YOLO"
slug: cv-object-detection-yolo
subject: computer-vision
lesson: 3
difficulty: intermediate
excerpt: Detect and locate many objects in one pass with YOLO: bounding boxes, confidence, IoU, non-max suppression and mAP, running a pretrained model and training on your own images.
---

Classification says "this photo contains a car". **Object detection** says "there are 3 cars, 2 people and a cow, and here exactly is each one". YOLO ("You Only Look Once") is the most popular family of fast detectors: it finds everything in a single pass, fast enough for live video.

::scene A busy Indian street, analysed
🛣️ | The frame | A traffic camera frame: autos, bikes, a bus, pedestrians and a cow. | dev
🔲 | Boxes | YOLO draws a box around each object with a label and a confidence. | chip
🧹 | Clean-up | Overlapping duplicate boxes are removed by non-max suppression. | byte
📈 | Count | Frame after frame, the city counts vehicles at each junction. | bugsy
::end

## What a detector outputs

For every object: a **class**, a **confidence** (0–1) and a **bounding box** (x1, y1, x2, y2).

## IoU: how much two boxes overlap

Intersection over Union is used both to judge if a predicted box matches the true one, and to remove duplicates.

::walk python | Intersection over Union
a = (10, 10, 50, 50)
b = (30, 30, 70, 70)
inter = max(0, min(a[2], b[2]) - max(a[0], b[0])) * max(0, min(a[3], b[3]) - max(a[1], b[1]))
union = (a[2] - a[0]) * (a[3] - a[1]) + (b[2] - b[0]) * (b[3] - b[1]) - inter
print(inter, union, round(inter / union, 3))
::notes
1: Box A: corners (10, 10) to (50, 50), a 40×40 square. // vars: a=(10, 10, 50, 50)
2: Box B: shifted 20 pixels right and down. // vars: b=(30, 30, 70, 70)
3: The overlap rectangle runs from (30, 30) to (50, 50): 20 × 20 = 400. // deep: max(0, …) handles boxes that don't overlap at all: the width or height becomes 0, so the intersection is 0. // vars: inter=400
4: Union = area A + area B − overlap = 1600 + 1600 − 400. // vars: union=2800
5: IoU ≈ 0.14: a weak overlap. A prediction usually counts as correct when IoU ≥ 0.5 with the true box. // out: 400 2800 0.143
::end

## Non-max suppression (NMS)

A detector often predicts several boxes for the same object. NMS keeps the most confident one and removes others that overlap it too much:

```python
def iou(a, b):
    inter = max(0, min(a[2], b[2]) - max(a[0], b[0])) * max(0, min(a[3], b[3]) - max(a[1], b[1]))
    area = lambda r: (r[2] - r[0]) * (r[3] - r[1])
    return inter / (area(a) + area(b) - inter)

def nms(boxes, scores, threshold=0.5):
    order = sorted(range(len(boxes)), key=lambda i: scores[i], reverse=True)
    keep = []
    while order:
        best = order.pop(0)
        keep.append(best)
        order = [i for i in order if iou(boxes[best], boxes[i]) < threshold]
    return keep

boxes = [(100, 100, 200, 200), (105, 102, 205, 198), (300, 120, 380, 220), (98, 99, 195, 205)]
scores = [0.92, 0.85, 0.88, 0.60]
print("kept boxes:", nms(boxes, scores))
```

Output:

```text
kept boxes: [0, 2]
```

Three boxes were the same auto-rickshaw; NMS kept the most confident (box 0) and the separate object (box 2).

## Running a pretrained YOLO model

The `ultralytics` package (`pip install ultralytics`) gives pretrained YOLO models trained on the COCO dataset (80 everyday classes: person, car, bus, cow, dog…):

```python
from ultralytics import YOLO

model = YOLO("yolov8n.pt")                 # small, fast model (check the docs for the latest versions)
results = model("street.jpg", conf=0.4)
for box in results[0].boxes:
    print(model.names[int(box.cls)], round(float(box.conf), 2), box.xyxy.tolist())
results[0].save("street_detected.jpg")     # image with boxes drawn
```

## Training on your own objects

1. Collect a few hundred images per class (helmets, number plates, leaf diseases…).
2. **Label** boxes with a tool such as Label Studio, CVAT or Roboflow, exporting in YOLO format.
3. Write a small `data.yaml` with the image folders and class names.
4. Fine-tune a pretrained model: `model.train(data="data.yaml", epochs=50, imgsz=640)`.
5. Check **mAP** (mean average precision, averaged over classes and IoU thresholds) on the validation set.

```python
precision_at_iou50 = {"helmet": 0.91, "no_helmet": 0.84, "number_plate": 0.88}
print("mAP@0.5 ≈", round(sum(precision_at_iou50.values()) / len(precision_at_iou50), 3))
```

Output:

```text
mAP@0.5 ≈ 0.877
```

(Real AP is the area under each class's precision-recall curve; this shows how the per-class scores are averaged.)

::tip For edge devices (a Raspberry Pi at a shop entrance, a phone app), pick the smallest model size and export it (ONNX, TFLite) for speed. Check privacy rules before analysing people in camera footage.

## Summary

- Detection = class + confidence + bounding box for every object.
- IoU measures box overlap; ≥ 0.5 usually counts as a match.
- NMS removes duplicate boxes; mAP summarises detector quality.
- Use pretrained YOLO models, then fine-tune on a few hundred labelled images of your own.

🎉 You've finished **Computer Vision**.
