# VitalStack 4.11: every premium module is now real, plus AI roadmaps and cheat sheets

This release only adds things. No existing page, lesson or feature was changed.

## No more "Coming soon"
All 32 syllabus modules that showed **🕒 Coming soon** now have real lessons:

- Buyers see **✅ Unlocked**.
- Visitors see **⭐ Premium · Ready**.

That adds **111 new lessons**. Each one has:

- a lesson film (`::scene`) with the approved crew (Dev, Chip, Bugsy, Byte);
- runnable Python whose output is checked by `tools/check_lesson_code.py`;
- a line-by-line walkthrough;
- a tip and a summary;
- a 3-question quiz.

Code that needs an API key or a heavy library (Claude, OpenAI, PyTorch, Spark, LangGraph, CrewAI, MCP and others) is shown with the official patterns. The checker does not run that code.

| Track | New modules (lessons) |
|---|---|
| **Data Scientist** (45) | EDA (4) · ML in Depth (7) · MongoDB (3) · Power BI (3) · Time Series (3) · Spark (3) · Deep Learning (5) · NLP (3) · Computer Vision (3) · Reinforcement Learning (2) · MLOps (3) · Portfolio Projects (6) |
| **Gen AI Developer** (35) | RAG (5) · LangChain and LlamaIndex (3) · AI Agents and Tool Use (5) · Multimodal AI (3) · GANs, VAEs and Diffusion (3) · Fine-tuning and Hugging Face (3) · Safe and Reliable AI Apps (3) · AI Coding Assistants (3) · Deploying AI Apps (3) · Capstones (4) |
| **Agentic AI Engineer** (31) | Tool Use in Depth (3) · Memory and State (3) · Reasoning Patterns (3) · MCP (3) · Frameworks Compared (4) · Multi-Agent Systems (2) · Evals and Tracing (3) · Safety (3) · Production (3) · Capstones (4) |

How the new paths behave:

- Each new path is premium. It is marked automatically by the setup wizard.
- Lessons show the premium teaser to visitors, like the existing premium paths.
- Every new path has its own homepage code example.
- The "All courses" panel has two new groups: **📊 Data Science track** and **🦾 Gen AI & Agents**.

## New pages for traffic and sales (free, indexed, carry ads)
| Page | Why |
|---|---|
| **AI for Complete Beginners: Start Here** | An 8-week path for non-tech learners. It starts with the free courses and leads into the premium ones. |
| **Data Scientist Roadmap 2026** | A stage-by-stage plan through every Data Scientist module, with progress cards. |
| **Gen AI Developer Roadmap 2026** | Covers prompting through to the capstones, and ends with the premium track. |
| **Agentic AI Engineer Roadmap 2026** | Covers the first agent through to production, and includes the questions interviewers ask. |
| **Machine Learning Cheat Sheet** | Which algorithm to use, metrics, tuning and common mistakes. Printable. |
| **RAG Cheat Sheet** | The pipeline, chunking, retrieval, a grounded prompt, evaluation and fixes. |
| **AI Agents Cheat Sheet** | The loop, tools, memory, patterns, MCP, safety and production. |

These are search-friendly topics people look up a lot ("data scientist roadmap", "RAG cheat sheet"). Each page links into the matching premium track.

## Put it on the site
1. Upload `vitalstack-theme-v4.11.0.zip` and choose **Replace current with uploaded**.
2. Go to **Appearance → VitalStack Setup** and click **Run setup / Create starter structure**. This:
   - creates the 32 new learning paths and marks them premium;
   - adds the 4 roadmaps and 3 cheat sheets.

   Nothing that already exists is changed.
3. Click **Import starter content**. Only the 111 new lessons are added. Then publish them, or import `content/import/vitalstack-all-content.xml`.
4. **LiteSpeed Cache → Purge All**.
5. In Search Console, request indexing for the new roadmaps and cheat sheets.
