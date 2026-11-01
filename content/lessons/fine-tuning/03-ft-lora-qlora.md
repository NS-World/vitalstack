---
title: "Fine-Tuning a Small Model with LoRA and QLoRA"
slug: ft-lora-qlora
subject: fine-tuning
lesson: 3
difficulty: intermediate
excerpt: Fine-tune an open LLM on one GPU: why full fine-tuning is expensive, how LoRA adapters train a tiny fraction of the weights, QLoRA with 4-bit models, the training script, and merging adapters.
---

Fully fine-tuning a 7-billion-parameter model means updating all 7 billion weights and storing gradients and optimiser state for each: far beyond one consumer GPU. **LoRA** (Low-Rank Adaptation) freezes the original model and trains small add-on matrices instead, often well under 1% of the weights. **QLoRA** also loads the frozen model in 4-bit. Together they let you fine-tune on a single free or cheap GPU.

::scene Teaching a giant a new trick
🏛️ | Frozen giant | The base model's billions of weights stay frozen: we don't touch them. | dev
📎 | Small adapters | Tiny LoRA matrices are clipped onto some layers. Only these learn. | chip
🗜️ | 4-bit | QLoRA stores the frozen giant in 4-bit, so it fits on one GPU. | byte
🎓 | New skill | After an hour of training, the model writes in your format, and the adapter file is only a few MB. | bugsy
::end

## The LoRA idea

A layer's weight update ΔW is a big d × d matrix. LoRA assumes the useful update has **low rank** and writes it as two thin matrices: ΔW = B × A, where B is d × r and A is r × d with a small rank r (like 8 or 16).

```python
import numpy as np

d, r = 4096, 16
full = d * d
lora = d * r + r * d
print(f"full update: {full:,} numbers | LoRA (r={r}): {lora:,} numbers | {lora / full:.2%} of the size")
```

Output:

```text
full update: 16,777,216 numbers | LoRA (r=16): 131,072 numbers | 0.78% of the size
```

::walk python | A low-rank update in miniature
import numpy as np
B = np.array([[1.0], [2.0], [0.5]])
A = np.array([[0.2, -0.1, 0.4]])
delta = B @ A
W = np.eye(3)
print((W + delta).round(2).tolist())
::notes
1: NumPy for matrices.
2: B: a tall, thin matrix (3 × 1). Rank r = 1 here. // vars: B=[[1.0], [2.0], [0.5]]
3: A: a short, wide matrix (1 × 3). // vars: A=[[0.2, -0.1, 0.4]]
4: B × A makes a full 3 × 3 update from only 6 trainable numbers instead of 9. // deep: In LoRA, A starts random and B starts at zero, so training begins exactly at the original model's behaviour. A scaling factor (alpha ÷ r) controls how strongly the update is applied. // vars: delta=3×3 matrix
5: The frozen weight W (identity here, for clarity). // vars: W=identity
6: The adapted layer uses W + B·A. At 4096 × 4096 the saving is enormous. // out: [[1.2, -0.1, 0.4], [0.4, 0.8, 0.8], [0.1, -0.05, 1.2]]
::end

## A QLoRA training script (Hugging Face)

```python
import torch
from datasets import load_dataset
from transformers import AutoTokenizer, AutoModelForCausalLM, BitsAndBytesConfig
from peft import LoraConfig, get_peft_model
from trl import SFTTrainer, SFTConfig

base = "MODEL_ID"                                       # a small open instruct model; check its licence
bnb = BitsAndBytesConfig(load_in_4bit=True, bnb_4bit_quant_type="nf4", bnb_4bit_compute_dtype=torch.bfloat16)
model = AutoModelForCausalLM.from_pretrained(base, quantization_config=bnb, device_map="auto")
tok = AutoTokenizer.from_pretrained(base)

lora = LoraConfig(r=16, lora_alpha=32, lora_dropout=0.05, target_modules=["q_proj", "k_proj", "v_proj", "o_proj"],
                  task_type="CAUSAL_LM")
model = get_peft_model(model, lora)
model.print_trainable_parameters()                      # typically well under 1%

data = load_dataset("json", data_files={"train": "train.jsonl", "test": "test.jsonl"})   # chat-format examples
trainer = SFTTrainer(model=model, train_dataset=data["train"], eval_dataset=data["test"],
                     args=SFTConfig(output_dir="out", num_train_epochs=2, per_device_train_batch_size=4,
                                    gradient_accumulation_steps=4, learning_rate=2e-4, logging_steps=10))
trainer.train()
model.save_pretrained("ticket-classifier-lora")         # saves only the small adapter
```

Libraries like **Unsloth** and **Axolotl** wrap these steps and make them faster.

## Key settings

| Setting | Typical | Effect |
|---|---|---|
| `r` (rank) | 8–64 | Capacity of the adapter; higher = more to learn, more memory |
| `lora_alpha` | 2 × r | Scaling of the update |
| `target_modules` | attention projections (and sometimes MLP layers) | Where adapters are attached |
| learning rate | ~1e-4 to 2e-4 | Higher than full fine-tuning |
| epochs | 1–3 | More risks overfitting on small datasets |

## After training

- **Evaluate** on the held-out test set against the base model + best prompt (previous lesson).
- **Merge** the adapter into the base weights for simpler serving (`model.merge_and_unload()`), or keep several adapters and swap them per task.
- **Serve** with vLLM or similar (many support LoRA adapters directly), or convert to a quantised format for Ollama.

```python
base_accuracy, tuned_accuracy = 0.78, 0.91
train_loss_end, eval_loss_end = 0.21, 0.64
overfitting = eval_loss_end > 2 * train_loss_end
print(f"gain: {tuned_accuracy - base_accuracy:+.0%} | overfitting warning: {overfitting}")
```

Output:

```text
gain: +13% | overfitting warning: True
```

A big gain, but evaluation loss far above training loss suggests memorisation: try fewer epochs, more varied data or lower rank, and confirm on fresh examples.

::tip Fine-tuning can weaken a model's general abilities or safety behaviour ("catastrophic forgetting"). Test a few general questions and refusal cases after training, not just your target task.

## Summary

- Full fine-tuning updates every weight; LoRA trains small low-rank adapters (often under 1%).
- QLoRA loads the frozen base in 4-bit so fine-tuning fits on one GPU.
- Hugging Face `peft` + `trl` (or Unsloth/Axolotl) handle the training loop.
- Evaluate against the base model, watch for overfitting and forgetting, then merge or serve adapters.

🎉 You've finished **Fine-tuning and Hugging Face**.
