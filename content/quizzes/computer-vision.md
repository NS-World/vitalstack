## cv-images-as-arrays
Q: A colour image in NumPy usually has shape...
- [x] (height, width, 3)
- [ ] (3,)
- [ ] (width,)
> Three channels: red, green and blue.
Q: Pixel values in an 8-bit image range from...
- [x] 0 to 255
- [ ] 0 to 1,000
- [ ] −1 to 1 only
> 0 is dark, 255 is full brightness.
Q: Why normalise pixels (e.g. divide by 255) before a neural network?
- [x] Small, consistent input ranges make training stable
- [ ] To make images bigger
- [ ] To add colours
> Networks train better on scaled inputs.

## cv-filters-edges-contours
Q: A blur filter works by...
- [x] Averaging each pixel with its neighbours
- [ ] Deleting pixels
- [ ] Inverting colours
> Convolution with an averaging kernel.
Q: Edge detectors like Sobel or Canny find...
- [x] Places where brightness changes sharply
- [ ] The image's file size
- [ ] Faces only
> Edges are big local changes in intensity.
Q: Contours are useful for...
- [x] Finding and measuring object outlines
- [ ] Compressing video
- [ ] Training language models
> e.g. counting coins or measuring areas.

## cv-object-detection-yolo
Q: Object detection outputs...
- [x] Boxes, class labels and confidence scores
- [ ] Only one label per image
- [ ] A sentence
> Classification says what; detection also says where.
Q: IoU (intersection over union) measures...
- [x] How much a predicted box overlaps the true box
- [ ] Image brightness
- [ ] Training time
> IoU of 1 is a perfect match.
Q: Non-maximum suppression removes...
- [x] Duplicate boxes for the same object
- [ ] All boxes
- [ ] The background
> Keep the most confident box; drop heavy overlaps.
