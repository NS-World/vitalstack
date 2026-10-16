---
title: HTML Media (Audio and Video)
slug: html-media-audio-video
subject: html
lesson: 18
difficulty: beginner
excerpt: Play sound and video with the audio and video elements, offer several file formats, add captions, and embed YouTube videos with iframe.
---

Before HTML5, playing a video on a web page needed a plugin like Flash. Today the browser does it on its own with two elements: `<video>` and `<audio>`. In this lesson you'll use both, add captions, and embed videos from YouTube.

## The video element

```html
<video src="https://interactive-examples.mdn.mozilla.net/media/cc0-videos/flower.mp4"
       width="400" height="225" controls>
  Your browser does not support the video element.
</video>
```

- `src` is the video file.
- `controls` shows the play button, timeline and volume. Without it, the user has no way to start the video.
- `width` and `height` reserve space, just like with images.
- The text inside the tags only appears in very old browsers that can't play video.

## Useful video attributes

```html
<video src="https://interactive-examples.mdn.mozilla.net/media/cc0-videos/flower.mp4"
       width="400" height="225"
       controls muted loop playsinline
       poster="https://picsum.photos/id/106/400/225"
       preload="metadata">
</video>
```

- `poster`: an image shown before the video starts.
- `muted`: starts with sound off.
- `loop`: starts again at the end.
- `autoplay`: starts playing by itself. Browsers only allow this if the video is also `muted`, because surprise sound is annoying.
- `playsinline`: on iPhones, plays inside the page instead of going full-screen.
- `preload`: `none`, `metadata` (just the length and size) or `auto`. Use `none` or `metadata` to save your readers' mobile data.

## Several formats with source

Not every browser supports every format. You can list several files and the browser picks the first one it can play:

```html
<video width="400" height="225" controls>
  <source src="movie.webm" type="video/webm">
  <source src="movie.mp4" type="video/mp4">
  Sorry, your browser can't play this video.
</video>
```

Today **MP4 (H.264)** plays in every modern browser, so a single MP4 file is usually enough. WebM files are often smaller, so listing WebM first and MP4 as a fallback is a common pattern.

## The audio element

```html
<audio controls src="https://interactive-examples.mdn.mozilla.net/media/cc0-audio/t-rex-roar.mp3">
  Your browser does not support the audio element.
</audio>
```

`<audio>` works just like `<video>`, with `controls`, `loop`, `muted`, `preload` and `<source>` elements. MP3 plays everywhere.

## Captions and subtitles with track

Captions help deaf and hard-of-hearing viewers, people watching without sound (most mobile viewers), and people who speak another language.

```html
<video width="400" height="225" controls>
  <source src="lesson.mp4" type="video/mp4">
  <track src="lesson-en.vtt" kind="captions" srclang="en" label="English" default>
  <track src="lesson-hi.vtt" kind="subtitles" srclang="hi" label="Hindi">
</video>
```

Caption files use the simple **WebVTT** format:

```text
WEBVTT

00:00:00.000 --> 00:00:04.000
Welcome to the HTML video lesson.

00:00:04.000 --> 00:00:08.000
Today we'll add captions to a video.
```

## Embedding YouTube videos with iframe

Video files are large, and streaming them from your own hosting can be slow and expensive. For long videos, upload them to YouTube and embed them with an `<iframe>` (an element that shows another web page inside yours):

```html
<iframe width="400" height="225"
        src="https://www.youtube-nocookie.com/embed/VIDEO_ID"
        title="Lesson video: HTML basics"
        loading="lazy"
        allow="accelerometer; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen></iframe>
```

Replace `VIDEO_ID` with the code from the video's address (the part after `watch?v=`). YouTube's **Share → Embed** button gives you this code ready-made.

- `title` describes the frame for screen readers. Always add it.
- `loading="lazy"` delays loading until the reader scrolls near it, which saves a lot of data.
- `youtube-nocookie.com` is YouTube's privacy-friendlier version that doesn't set tracking cookies until the user plays the video.

## Good practice with media

- **Never autoplay with sound.**
- Provide **captions** for videos with speech and a **transcript** for audio such as podcasts. Transcripts also help search engines understand the content.
- Keep files small: compress videos and use a sensible resolution (720p is plenty for most lesson clips).
- Don't put essential information *only* in a video. Some readers can't or won't play it.

## Common mistakes

- **Forgetting `controls`**, which leaves an un-playable box.
- **Autoplay without `muted`**: the browser blocks it.
- **Huge video files on cheap hosting**, which make pages slow. Use YouTube or a video host.
- **An iframe without a `title`.**

## Exercise

1. Add the flower video with controls, a poster image and `preload="metadata"`.
2. Make a muted, looping version without controls, like a background clip.
3. Add the audio example.
4. Embed any YouTube video using its Share → Embed code, and add a `title` and `loading="lazy"`.

## Summary

- `<video>` and `<audio>` play media without plugins; add `controls`.
- Use `<source>` for several formats and `<track>` for captions.
- Autoplay only works muted, and should be rare.
- Embed long videos from YouTube with an `<iframe>` that has a title.

Next, you'll learn accessibility basics: how to make sure everyone can use your pages.
