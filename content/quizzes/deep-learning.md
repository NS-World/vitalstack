## dl-neurons-layers
Q: A neuron computes...
- [x] A weighted sum of its inputs plus a bias, passed through an activation
- [ ] Only the largest input
- [ ] A random number
> z = w·x + b, then an activation like ReLU.
Q: Why do networks need non-linear activations like ReLU?
- [x] Without them, stacked layers collapse into one linear model
- [ ] They make training data bigger
- [ ] They remove the need for weights
> Non-linearity lets networks learn curved patterns.
Q: Backpropagation is used to...
- [x] Compute how each weight should change to reduce the loss
- [ ] Load the dataset
- [ ] Draw the network
> Gradients flow backwards; an optimiser updates the weights.

## dl-first-network-keras
Q: For a 10-class digit classifier, the last layer usually has...
- [x] 10 units with softmax
- [ ] 1 unit with no activation
- [ ] 1,000 units with ReLU
> Softmax gives a probability for each class.
Q: An epoch is...
- [x] One full pass over the training data
- [ ] One weight
- [ ] One layer
> Training usually runs several epochs.
Q: Validation loss rising while training loss falls means...
- [x] Overfitting: stop earlier or regularise
- [ ] Perfect training
- [ ] The data is missing
> Early stopping and dropout help.

## dl-cnn-images
Q: A convolution layer learns...
- [x] Small filters that detect patterns like edges anywhere in the image
- [ ] One weight per pixel of the whole image only
- [ ] The image file name
> Filters slide across the image, sharing weights.
Q: Max pooling...
- [x] Shrinks feature maps by keeping the strongest value in each patch
- [ ] Adds colour
- [ ] Doubles the image size
> It reduces size and adds some position tolerance.
Q: Transfer learning means...
- [x] Starting from a model pre-trained on a big dataset
- [ ] Copying test answers
- [ ] Training without data
> Fine-tuning a pre-trained CNN works well with small datasets.

## dl-rnn-lstm
Q: RNNs are designed for...
- [x] Sequences, like text or time series
- [ ] Only single images
- [ ] Unordered sets of numbers
> They carry a hidden state from step to step.
Q: LSTMs improve on simple RNNs by...
- [x] Using gates to keep or forget information over long sequences
- [ ] Removing the hidden state
- [ ] Using no weights
> Gates reduce the vanishing gradient problem.
Q: For most modern language tasks, RNNs have been largely replaced by...
- [x] Transformers
- [ ] Decision trees
- [ ] k-means
> Attention handles long context and trains in parallel.

## dl-pytorch
Q: In a PyTorch training loop, optimizer.zero_grad() is called to...
- [x] Clear old gradients before the next backward pass
- [ ] Delete the model
- [ ] Set the learning rate to zero
> Gradients accumulate by default.
Q: loss.backward() does what?
- [x] Computes gradients of the loss for every parameter
- [ ] Saves the model
- [ ] Loads the next batch
> Then optimizer.step() updates the weights.
Q: model.eval() and torch.no_grad() are used when...
- [x] Evaluating or predicting, not training
- [ ] Starting training
- [ ] Loading CSV files
> They switch off training behaviour and gradient tracking.
