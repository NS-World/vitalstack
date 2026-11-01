## ft-hugging-face-hub
Q: Before using an open model, check its model card for...
- [x] Licence, size, languages and limitations
- [ ] Only the number of likes
- [ ] Its colour
> Licences decide if you may use it commercially.
Q: An 8B model in 4-bit needs roughly...
- [x] 4–5 GB of memory
- [ ] 80 GB
- [ ] 8 MB
> 8 × 0.5 bytes ≈ 4 GB, plus headroom.
Q: Ollama and LM Studio let you...
- [x] Run quantised models locally
- [ ] Host websites
- [ ] Train from scratch
> Download and chat with models on your machine.

## ft-when-to-fine-tune
Q: Your bot needs this week's product prices. Best tool:
- [x] RAG
- [ ] Fine-tuning
- [ ] A bigger temperature
> Facts that change belong in retrieval.
Q: Fine-tuning a small model is attractive when...
- [x] A narrow task runs at high volume
- [ ] You have 10 requests a day
- [ ] You need new facts daily
> It can cut cost and latency a lot.
Q: Before shipping a fine-tuned model, compare it against...
- [x] The base model with your best prompt, on the same test set
- [ ] Nothing
- [ ] A random model
> If it doesn't clearly win, don't ship it.

## ft-lora-qlora
Q: LoRA trains...
- [x] Small low-rank adapter matrices while the base model stays frozen
- [ ] Every weight of the model
- [ ] Only the tokenizer
> Often well under 1% of the weights.
Q: QLoRA adds...
- [x] Loading the frozen base model in 4-bit
- [ ] More GPUs
- [ ] A bigger dataset
> So fine-tuning fits on one GPU.
Q: Eval loss far above training loss suggests...
- [x] Overfitting or memorisation
- [ ] Perfect training
- [ ] A tokenizer bug always
> Try fewer epochs or more varied data.
