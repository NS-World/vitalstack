## multimodal-vision-pdfs
Q: To send an image to a vision model you usually include...
- [x] The image (base64 or URL) as a content block with your text
- [ ] Only the file name
- [ ] A link to a folder
> Image and text go together in one message.
Q: Why resize very large images before sending?
- [x] To cut cost and latency without losing needed detail
- [ ] Models refuse all large images
- [ ] Resizing improves accuracy always
> Images are converted to tokens; huge images cost more.
Q: Before sending ID cards or medical reports to a model you should...
- [x] Check consent and privacy rules, and mask what isn't needed
- [ ] Post them publicly
- [ ] Skip any checks
> Images often contain personal data.

## multimodal-speech
Q: Speech-to-text (ASR) converts...
- [x] Audio into text
- [ ] Text into images
- [ ] Video into music
> e.g. Whisper-style models.
Q: In a voice assistant, the latency budget includes...
- [x] Listening, transcribing, the LLM reply and speaking it back
- [ ] Only the LLM
- [ ] Only the microphone
> Every stage adds delay; streaming helps.
Q: Text-to-speech (TTS) is used to...
- [x] Speak the model's reply aloud
- [ ] Transcribe meetings
- [ ] Train models
> Pick a voice and stream audio.

## multimodal-image-generation
Q: A strong image prompt describes...
- [x] Subject, setting, style, lighting and composition
- [ ] Only one word
- [ ] The file format only
> Specific prompts give controllable results.
Q: Inpainting means...
- [x] Editing just a masked part of an image
- [ ] Printing the image
- [ ] Making the image smaller
> Change a region while keeping the rest.
Q: Responsible image generation includes...
- [x] Labelling AI images and never faking real people
- [ ] Removing all watermarks
- [ ] Copying artists' work exactly
> Avoid deception and respect rights.
