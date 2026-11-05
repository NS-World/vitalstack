---
title: Free Diagram Maker (Text to Flowchart)
slug: diagram-maker
parent: tools
template: page-tool.php
icon: 🧭
order: 100
excerpt: Type simple arrows and get a clean flowchart. Drag boxes, change colours, and download PNG or SVG, or copy Mermaid code. Nothing leaves your browser.
---
## How to draw a diagram with text

Write **one arrow per line**. The tool turns your lines into boxes and arrows and arranges them for you.

| You type | You get |
|---|---|
| `Login -> Dashboard` | two boxes joined by an arrow |
| `Logged in? -> Dashboard: yes` | an arrow with the label "yes" |
| `Logged in?` (ends with `?`) | a yellow decision diamond |
| `((Start))` | a rounded start/end shape |
| `[[Users DB]]` | a database cylinder |
| `# my note` | a comment, ignored |

Pick a **template** (login flow, RAG pipeline, AI agent loop, ML workflow, Git workflow) to see the idea, then edit it. Switch between **top → bottom** and **left → right**, and try the four colour themes.

## Move, export and share

- **Drag** any box to tidy the picture. On a keyboard, press Tab to focus a box and use the arrow keys.
- **⬇ PNG** gives a sharp image for slides, notes and WhatsApp.
- **⬇ SVG** gives a vector file that stays crisp at any size.
- **📋 Mermaid code** copies the same diagram as [Mermaid](https://mermaid.js.org/) text, which GitHub, Notion and many docs tools draw automatically.

Your last diagram is saved in this browser only, so it is still there when you come back.

## Why draw before you code?

A flowchart forces you to answer the questions code will ask later: *what happens if the password is wrong? where does the data live? when does the loop stop?* Interviewers love candidates who sketch the flow first, and teams use diagrams in every design review.

Good habits:

1. Keep one idea per box, in 2 to 5 words.
2. Every decision diamond should have at least two labelled exits (yes / no).
3. Show where data is stored with a database shape.
4. If the diagram needs more than about 15 boxes, split it into two diagrams.

Practise with the **AI agent loop** template, then draw the flow of an app you use every day, like ordering food or booking a train ticket.
