# VitalStack 4.12: AI City in 3D, AI Dictionary, Nova and beginner helpers

This release is based on owner feedback after 4.11:

- Lessons are still too hard for beginners.
- The lesson films and the 3D code stage didn't look good.
- Some popups and sections break on phones.
- Gen AI and agents need a fun, visual way in.

The interactive animations (the `::viz` animations) are unchanged and stay that way.

## 🏙️ AI City: Gen AI and agents in 3D (new page, free, carries ads)

A scroll tour of a 3D miniature island built with three.js. Each district explains one idea, in this order:

1. Data Mines
2. Token Factory
3. Embedding Park
4. Transformer Tower
5. Training Gym
6. Prompt Café
7. RAG Library
8. Tool Workshop
9. Agent HQ
10. Memory Vault
11. MCP Port
12. Guardrail Gate
13. Eval Lab
14. Launch Pad

Things move around the city:

- tokens ride a conveyor belt;
- attention beams pulse;
- agent bots run the plan → act → observe loop;
- MCP boats sail to the islands;
- the rocket hovers on the launch pad.

How the tour works:

- As you scroll, the camera flies to each district.
- Each district's card has four parts: the idea **in simple words**, a **real-life example**, the **new words** (linked to the AI Dictionary), and **"Learn it step by step"** (a link to the course).

Built for search engines, phones and every browser:

- All text is normal HTML, so Google can read it (good for AdSense).
- The 3D city is only a background layer.
- On phones the card sits at the bottom and the city shows above it.
- If WebGL is missing, the page shows a soft gradient background instead. With "reduce motion" turned on, nothing animates.
- three.js 0.160.0 is self-hosted under its MIT licence and loads only on this page.

## 📖 AI Dictionary (new page, free, carries ads)

The dictionary has **74 AI words** in 6 groups: Basics, How LLMs work, Building AI apps, AI agents, Safety and quality, and Shipping AI products.

- Every word has a simple definition, a 💡 real-life example and a link to the matching course.
- Readers can search and filter by group. Each word has its own anchor (`/ai-dictionary/#rag`), and opening a link to it highlights the word.
- Display ads sit between the groups.

## 🤖 Nova: the new HD guide

Nova is a crisp vector character drawn in SVG, so it stays sharp at any size and on retina screens.

- Six moods: wave, point, think, cheer, explain and wow.
- Small animations: floating, blinking and waving.
- Nova narrates the AI City, the dictionary, the story strips and the code explainer.
- The old crew characters still appear where they did before.

## Lessons: beginner helpers (on by default)

1. **Story strips instead of films.** Each lesson film becomes an illustrated comic strip: numbered panels, a big icon, Nova and a speech bubble. Panels pop in as you scroll. On phones they become a swipe carousel.
2. **📦 Tools this code uses.** Under every Python example, a chip for each import says what that library is for. For example, "pandas: Excel inside Python". It covers 100+ libraries.
3. **🧩 Explain this code, line by line.** A colour-coded list labels every line: Import, Variable, Function, Loop, Decision, Safety net, Print and so on. Each label has a plain-English sentence. It also understands common calls such as `.fit()`, `train_test_split`, `.messages.create()` and `read_csv`.
4. **Tap-to-explain words.** AI terms in the lesson text (training data, RAG, embeddings, LLM…) get a dotted underline. Tapping one opens its meaning: a small card on desktop, a bottom sheet on phones.

All four work on premium lessons too: they run again after a protected lesson loads.

**Customizer → Layout** has three new checkboxes:

| Setting | Default |
|---|---|
| Beginner helpers in lessons | **On** |
| Old lesson film player | **Off** (films show as story strips) |
| Old "3D view" button in code walkthroughs | **Off** |

## 📱 Responsive fixes

| Where | Fix |
|---|---|
| Phones (≤600 px) | Only one popup shows at a time: the newest message wins. The cookie note, the "mark complete" nudge and the celebration no longer stack over the page. They span the screen with 10 px margins. |
| Premium track page at 320 px | The 1 px overflow is gone. |
| CSV Data Explorer at 320 px | The file picker and the code no longer overflow (they were 40 px too wide). |
| Focus panel close button | Now 40 × 40 px, easier to tap. |
| Lesson content | Images, videos, iframes and code never overflow. Long links and headings wrap. |

**How it was checked:** an audit of 25 page types at 7 widths (320–1024 px), with popups opened, plus every lesson with the code explanation open at 375 px.

## Homepage and navigation

- A new **AI City banner** sits above the premium section on the homepage, with buttons for the tour and the dictionary.
- **🏙️ AI City 3D** is added to the header and topic-bar links.
- Both new pages and links only appear once the pages exist.

## Put it on the site

1. Upload `vitalstack-theme-v4.12.0.zip` and choose **Replace current with uploaded**.
2. Go to **Appearance → VitalStack Setup** and click **Run setup**. This creates the **AI City** and **AI Dictionary** pages. Nothing else changes.
3. **LiteSpeed Cache → Purge All**.
4. In Search Console, request indexing for `/ai-city/` and `/ai-dictionary/`.

## Tests

| Suite | Result |
|---|---|
| PHP suites (11, including the new `ai412_test` with 480 data checks) | All pass |
| Existing browser suites (w410 and w411 included) | All pass |
| New `w412_e2e` browser suite | 953 / 953 |

What `w412_e2e` covers:

- **AI City** at 1366, 390 and 320 px: 3D on, the camera moves, the cards fit and there are no errors. It also checks the no-WebGL and reduced-motion fallback.
- **Dictionary:** search, filters and anchors.
- **Helpers:** story strips, explain boxes and tap-words on all 266 lessons that have Python or a film, with no sideways scroll.
