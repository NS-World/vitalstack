## gen-gans
Q: A GAN has two networks:
- [x] A generator and a discriminator (critic)
- [ ] An encoder and a database
- [ ] Two classifiers that agree
> They compete: one fakes, one detects.
Q: Mode collapse in GANs means...
- [x] The generator produces only a few kinds of output
- [ ] The discriminator stops loading
- [ ] Training data is lost
> Low variety is a classic GAN problem.
Q: Today GANs are still used for...
- [x] Fast generation and super-resolution
- [ ] Nothing at all
- [ ] Database indexing
> Diffusion leads text-to-image, but GANs remain useful.

## gen-vaes
Q: An autoencoder learns to...
- [x] Compress data to a small code and rebuild it
- [ ] Classify text
- [ ] Sort numbers
> Encoder squeezes, decoder rebuilds.
Q: High reconstruction error on a new input suggests...
- [x] It's unusual: a possible anomaly
- [ ] It's perfect
- [ ] The model is bigger
> Autoencoders trained on normal data rebuild normal inputs well.
Q: The reparameterisation trick writes z as...
- [x] μ + σ × ε, with ε sampled from a standard normal
- [ ] μ × 0
- [ ] A fixed constant
> It lets gradients flow through μ and σ.

## gen-diffusion
Q: A diffusion model is trained to...
- [x] Predict the noise added to an image
- [ ] Count pixels
- [ ] Classify cats only
> If it can predict noise, it can remove it.
Q: To generate, a diffusion model starts from...
- [x] Pure random noise and denoises step by step
- [ ] A finished photo
- [ ] A blank text file
> Each step removes a little noise.
Q: A higher guidance scale makes the image...
- [x] Follow the prompt more strictly, with less variety
- [ ] Always blurrier
- [ ] Ignore the prompt
> Guidance trades variety for prompt-faithfulness.
