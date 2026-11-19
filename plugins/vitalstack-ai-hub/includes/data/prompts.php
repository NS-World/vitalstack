<?php
/**
 * Prompt Bazaar: original prompts by category and tool. [BRACKETS] are the
 * parts the reader fills in. "output" describes what a good result looks like.
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

$p = function ( $id, $title, $cat, $tool, $kind, $premium, $summary, $prompt, $output ) {
	return array( 'id' => $id, 'title' => $title, 'cat' => $cat, 'tool' => $tool, 'kind' => $kind, 'premium' => $premium, 'summary' => $summary, 'prompt' => $prompt, 'output' => $output, 'image' => '' );
};

return array(
	/* Study & exams */
	$p( 'explain-like-teacher', 'Explain any topic like a great teacher', 'Study & Exams', 'Any chatbot', 'text', false, 'Understand a hard topic in 5 minutes with an analogy, an example and a quick quiz.',
		"You are a patient teacher. Explain [TOPIC] to me. I am a [CLASS / YEAR / BEGINNER].\n1. One-line definition.\n2. A real-life analogy.\n3. A worked example step by step.\n4. 3 common mistakes students make.\n5. A 3-question quiz. Wait for my answers before revealing the solutions.",
		"A short definition, a relatable analogy (for recursion: Russian dolls), a solved example, a mistakes list, then the chat pauses for your quiz answers and gives feedback on each one." ),
	$p( 'notes-to-flashcards', 'Turn notes into flashcards', 'Study & Exams', 'Any chatbot', 'text', false, 'Paste notes, get question–answer flashcards you can import into Anki or Quizlet.',
		"Turn the notes below into 20 flashcards for revision.\nRules: one fact per card, questions that test understanding (not just definitions), answers under 25 words.\nOutput as a table with columns Question | Answer, then the same as CSV.\n\nNotes:\n\"\"\"\n[PASTE NOTES]\n\"\"\"",
		"A 20-row table plus CSV text ready to import into Anki or Quizlet." ),
	$p( 'exam-plan', '30-day exam revision plan', 'Study & Exams', 'ChatGPT', 'text', false, 'A realistic day-by-day plan based on your syllabus, weak topics and free hours.',
		"Make a 30-day revision plan for [EXAM] on [DATE].\nSyllabus: [LIST TOPICS]. Weak topics: [WEAK]. I can study [HOURS] hours on weekdays and [HOURS] on weekends.\nInclude: daily topics, practice-test days, revision of weak topics every 3 days, 1 rest half-day a week, and a final 3-day revision. Output as a table: Day | Topic | Task | Time.",
		"A 30-row table with spaced revision, mock-test days and lighter days, which you can copy to Google Sheets." ),
	$p( 'socratic-tutor', 'Socratic tutor that never gives the answer', 'Study & Exams', 'Claude', 'text', true, 'Learn by being guided with questions: ideal for maths, coding and physics practice.',
		"Act as a Socratic tutor for [SUBJECT]. Here is my problem: [PROBLEM].\nNever give the final answer. Ask me one guiding question at a time, wait for my reply, then:\n- if I am right, move one step forward;\n- if I am wrong, give a smaller hint, not the solution.\nAfter I solve it, summarise the method in 3 steps and give me a similar problem to try alone.",
		"A back-and-forth where you solve the problem yourself, then a 3-step method summary and a new practice problem." ),

	/* Coding */
	$p( 'debug-error', 'Debug any error message', 'Coding', 'Any chatbot', 'code', false, 'Paste the error and code; get the cause, the fix and how to avoid it next time.',
		"I get this error in [LANGUAGE / FRAMEWORK]:\n\"\"\"\n[ERROR MESSAGE + STACK TRACE]\n\"\"\"\nRelevant code:\n\"\"\"\n[CODE]\n\"\"\"\n1. Explain the cause in plain words.\n2. Show the corrected code with the changed lines marked.\n3. Tell me how to prevent this kind of bug (test, check or habit).",
		"The root cause (e.g. a null value from a missing database row), a fixed snippet with comments on changed lines, and one prevention tip such as an Optional check or a unit test." ),
	$p( 'code-review', 'Senior-level code review', 'Coding', 'Claude', 'code', false, 'Bugs, security issues, performance and readability, ranked by importance.',
		"Review this [LANGUAGE] code like a senior engineer.\nList issues in order of severity: bugs, security, performance, readability.\nFor each: the line, why it matters, and the fix. Then show the improved full version.\n\n\"\"\"\n[CODE]\n\"\"\"",
		"A ranked list (e.g. SQL injection risk first, then an N+1 query, then naming) and a cleaned-up version of the code." ),
	$p( 'explain-code', 'Explain code line by line', 'Coding', 'GitHub Copilot', 'code', false, 'Understand unfamiliar code fast, including what each line does and why.',
		"Explain this code to a beginner, line by line. Then explain the overall idea in 3 sentences and give one example input with its output.\n\n\"\"\"\n[CODE]\n\"\"\"",
		"Numbered explanations per line, a plain summary and a traced example run." ),
	$p( 'unit-tests', 'Write thorough unit tests', 'Coding', 'Cursor', 'code', false, 'Happy path, edge cases and failure cases with a real testing framework.',
		"Write unit tests for the function below using [JUnit 5 / pytest / Jest].\nCover: normal cases, edge cases (empty, null, very large, negative), and error cases.\nUse clear test names that describe the behaviour. Explain any case you think the function handles wrongly.\n\n\"\"\"\n[FUNCTION]\n\"\"\"",
		"A full test file with 8–15 named tests and a note on any bug the tests reveal." ),
	$p( 'system-design', 'System design interview practice', 'Coding', 'ChatGPT', 'text', true, 'A mock interviewer that asks follow-ups and scores you like a real panel.',
		"Be a system design interviewer at a product company. Ask me to design [e.g. a URL shortener / food delivery app].\nRun a 45-minute interview: requirements → estimates → API → data model → architecture → scaling → trade-offs.\nAsk one question at a time and push back on weak answers. At the end, score me (1–5) on each area with what a strong candidate would have said.",
		"A realistic interview with follow-up questions, then a scorecard and model answers for your weakest areas." ),
	$p( 'sql-from-english', 'SQL from plain English', 'Coding', 'Any chatbot', 'code', false, 'Describe what you need; get the query, an explanation and an index tip.',
		"Database: [MySQL / PostgreSQL]. Tables:\n[TABLE DEFINITIONS]\nWrite a query that [WHAT YOU NEED]. Explain each clause, show sample output, and suggest an index if it would help.",
		"A working query with JOINs and GROUP BY explained, a small example result table and an index suggestion." ),
	$p( 'project-readme', 'GitHub README that impresses recruiters', 'Coding', 'Any chatbot', 'text', true, 'Structure, badges, demo section and results that make a project stand out.',
		"Write a GitHub README for my project.\nName: [NAME]. What it does: [1–2 lines]. Stack: [STACK]. Key features: [LIST]. A result or metric: [METRIC].\nSections: title + one-line pitch, demo (GIF placeholder), features, tech stack, architecture (simple ASCII diagram), setup in 4 commands, what I learned, roadmap. Keep it scannable.",
		"A polished Markdown README with an ASCII architecture diagram and setup commands, ready to paste." ),

	/* Career & jobs */
	$p( 'resume-bullets', 'Turn duties into achievement bullets', 'Career & Jobs', 'Any chatbot', 'text', false, 'Rewrite resume lines with action verbs and numbers recruiters notice.',
		"Rewrite these resume points as achievement bullets for a [ROLE] role.\nUse the format: action verb + what I did + result with a number. Max 22 words each. If a number is missing, suggest what I could measure.\n\nMy points:\n[PASTE POINTS]",
		"Bullets like “Cut API response time 40% by adding Redis caching to 6 endpoints”, plus prompts for missing numbers." ),
	$p( 'mock-interview', 'Mock HR + technical interview', 'Career & Jobs', 'ChatGPT', 'text', false, 'Practise answers with instant feedback, one question at a time.',
		"Interview me for a [ROLE] position at a [COMPANY TYPE]. Ask 8 questions (3 HR, 5 technical on [SKILLS]), one at a time.\nAfter each answer: score 1–10, what was good, what was missing, and a model answer in under 80 words.",
		"An interactive interview with a score and model answer after each of your replies." ),
	$p( 'cold-email-recruiter', 'Cold message to a recruiter or alumni', 'Career & Jobs', 'Any chatbot', 'text', false, 'Short, specific messages that actually get replies on LinkedIn or email.',
		"Write a LinkedIn message (under 90 words) to [NAME], a [ROLE] at [COMPANY].\nMe: [1 LINE ABOUT YOU]. Ask: [referral / 15-min chat / feedback on my project]. Mention one specific thing about their work: [DETAIL].\nGive 2 versions: friendly and formal.",
		"Two short messages that open with a specific detail, make one clear ask and end politely." ),
	$p( 'salary-negotiation', 'Salary negotiation script', 'Career & Jobs', 'Claude', 'text', true, 'What to say, numbers to ask for and replies to common pushbacks.',
		"I got an offer for [ROLE] in [CITY] at [OFFER]. Market range I found: [RANGE]. My strengths: [LIST]. Other offers: [YES/NO].\nWrite a polite negotiation email, a phone script, and replies to 4 pushbacks (budget fixed, “this is our best offer”, joining bonus instead, review after 6 months).",
		"A ready email, a call script and calm responses to typical objections, with a target and a walk-away number." ),

	/* Writing */
	$p( 'rewrite-clear', 'Make any text clear and professional', 'Writing', 'Any chatbot', 'text', false, 'Fix grammar and clarity while keeping your meaning and voice.',
		"Rewrite the text below to be clear, correct and professional. Keep my meaning and roughly the same length. Then list the 5 most important changes you made.\n\n\"\"\"\n[TEXT]\n\"\"\"",
		"A cleaned-up version and a short list of changes, so you learn as well." ),
	$p( 'blog-outline', 'SEO blog post outline', 'Writing', 'Any chatbot', 'text', false, 'A search-friendly outline with headings, FAQs and a meta description.',
		"Create an outline for a blog post targeting the search “[KEYWORD]” for [AUDIENCE].\nInclude: a title under 60 characters, meta description under 155, H2/H3 headings, what each section must answer, 5 FAQs people search, and internal link ideas.",
		"A structured outline with title, meta description, headings, FAQ list and link ideas." ),
	$p( 'email-difficult', 'Write a difficult email calmly', 'Writing', 'Claude', 'text', false, 'Say no, follow up on payment or handle a complaint without sounding rude.',
		"Help me write an email to [PERSON / CLIENT] about [SITUATION]. Goal: [WHAT I WANT]. Tone: firm but polite.\nKeep it under 150 words, one clear request, and a deadline if needed. Give a softer and a firmer version.",
		"Two short drafts with a clear ask, for example a payment reminder with a due date." ),

	/* Marketing */
	$p( 'ad-copy', 'Ad copy in 5 angles', 'Marketing', 'ChatGPT', 'text', false, 'Headlines and texts for Google, Meta or Instagram ads from 5 different angles.',
		"Write ad copy for [PRODUCT] for [AUDIENCE]. Main benefit: [BENEFIT]. Offer: [OFFER].\nGive 5 angles (pain, benefit, social proof, curiosity, urgency). For each: headline (max 30 chars), primary text (max 125 chars), CTA.",
		"Five ready ad variations you can A/B test." ),
	$p( 'landing-page-copy', 'Landing page copy that converts', 'Marketing', 'Claude', 'text', true, 'Hero, benefits, objections, FAQ and CTA in a proven structure.',
		"Write landing page copy for [PRODUCT/COURSE]. Audience: [WHO]. Price: [PRICE]. Proof: [TESTIMONIALS/NUMBERS].\nSections: hero (headline + sub + CTA), problem, solution, 3 benefits with icons ideas, how it works in 3 steps, objections handled, FAQ (6), final CTA. Plain, honest language, no hype.",
		"A complete page of copy section by section, ready for a designer or a website builder." ),
	$p( 'customer-persona', 'Customer persona from scratch', 'Marketing', 'Any chatbot', 'text', true, 'Know who you are selling to: goals, fears, words they use, where they hang out.',
		"Create 2 detailed customer personas for [PRODUCT] in [MARKET/COUNTRY].\nFor each: name, age, job, income range, goals, frustrations, buying objections, words they use, channels they trust, and 3 message ideas that would speak to them.",
		"Two realistic personas with message ideas you can use in ads and posts." ),

	/* Social media */
	$p( 'reel-script', '30-second reel script', 'Social Media', 'Any chatbot', 'video', false, 'Hook, value and call to action, with on-screen text and shot list.',
		"Write a 30-second Instagram reel script about [TOPIC] for [AUDIENCE].\nFormat a table: Time | What I say | On-screen text | Shot. Start with a 2-second hook, give 3 quick tips, end with a CTA to [ACTION]. Add a caption under 150 characters and 8 hashtags.",
		"A second-by-second script table, a caption and hashtags." ),
	$p( 'content-calendar', '30-day content calendar', 'Social Media', 'ChatGPT', 'text', false, 'A month of post ideas mixed across formats, with hooks.',
		"Make a 30-day content calendar for [BRAND / PAGE] about [NICHE] on [PLATFORMS].\nMix: 40% educational, 20% stories, 20% engagement, 20% promotion. Table: Day | Format (reel/carousel/post/story) | Topic | Hook | CTA.",
		"A 30-row table you can paste into Sheets and schedule." ),
	$p( 'carousel', 'Carousel post, slide by slide', 'Social Media', 'Any chatbot', 'text', true, 'A 7-slide carousel with a scroll-stopping first slide and design notes.',
		"Create a 7-slide LinkedIn/Instagram carousel on [TOPIC].\nSlide 1: bold hook (max 8 words). Slides 2–6: one idea each, max 25 words, with a visual idea. Slide 7: summary + CTA. Add design notes (colours, icons) and a caption.",
		"Slide-by-slide text with visual suggestions, ready for Canva." ),

	/* Business */
	$p( 'business-idea-validate', 'Validate a business idea', 'Business', 'Claude', 'text', false, 'Find risks, competitors and a cheap test before you spend money.',
		"Act as a sceptical startup mentor. My idea: [IDEA] for [CUSTOMERS] in [CITY/COUNTRY].\nGive: the riskiest assumptions, likely competitors and alternatives, a pricing guess, and a 7-day test I can run for under ₹5,000 to check demand.",
		"A list of risky assumptions, competitors, a price range and a cheap one-week experiment." ),
	$p( 'swot', 'SWOT and action plan', 'Business', 'Any chatbot', 'text', false, 'A SWOT that ends in concrete next steps, not just a list.',
		"Do a SWOT analysis for [BUSINESS]. Context: [DETAILS]. Then turn it into 5 prioritised actions for the next 90 days with an owner and a metric for each.",
		"A 4-box SWOT and a 90-day action table." ),
	$p( 'pitch-deck', 'Pitch deck outline', 'Business', 'ChatGPT', 'text', true, 'A 10-slide investor or client pitch with what each slide must prove.',
		"Outline a 10-slide pitch deck for [STARTUP]. Problem: [..]. Solution: [..]. Traction: [..]. Ask: [..].\nFor each slide: title, 3 bullet points, the one thing it must prove, and a chart or visual idea.",
		"A complete 10-slide structure with talking points and visual ideas." ),

	/* Data & Excel */
	$p( 'excel-formula', 'Excel / Google Sheets formula helper', 'Data & Excel', 'Any chatbot', 'code', false, 'Describe the result; get the formula and how it works.',
		"In [Excel / Google Sheets], my data: [DESCRIBE COLUMNS, e.g. A=Date, B=Product, C=Amount].\nI need: [WHAT YOU WANT]. Give the formula, explain each part, and an alternative if my version doesn’t support it.",
		"A working formula (e.g. SUMIFS or XLOOKUP) with a part-by-part explanation." ),
	$p( 'data-insights', 'Find insights in a dataset', 'Data & Excel', 'ChatGPT', 'text', true, 'Upload a CSV and get the 5 insights that matter, with charts to make.',
		"I uploaded a CSV about [TOPIC]. First describe the columns and data quality issues. Then find the 5 most useful insights for [GOAL], each with the numbers behind it and the chart I should make. Finally list 3 questions this data cannot answer.",
		"A data-quality summary, 5 numbered insights with figures and chart suggestions, and honest limits." ),

	/* Image generation */
	$p( 'img-product', 'Product photo on a clean background', 'Image Generation', 'ChatGPT Images', 'image', false, 'E-commerce style photo for a shop, Instagram or Amazon listing.',
		"Professional product photo of [PRODUCT, e.g. a matte black steel water bottle], centred on a soft light-grey seamless background, soft studio lighting from the left, gentle shadow, 50mm lens, sharp focus, photorealistic, plenty of empty space on the right for text.",
		"A clean, catalogue-style photo with even lighting and space for a price or headline. Edit with “make the background white” or “add a slight reflection”." ),
	$p( 'img-thumbnail', 'YouTube thumbnail background', 'Image Generation', 'Midjourney', 'image', false, 'High-contrast thumbnail art with space for big text.',
		"Dramatic tech-themed background for a YouTube thumbnail about [TOPIC], glowing laptop on a desk, neon blue and orange rim light, dark background, strong contrast, empty left half for big text, cinematic --ar 16:9 --stylize 200",
		"A 16:9 image with a strong focal point on the right and clean space on the left; add your text in Canva for crisp lettering." ),
	$p( 'img-logo', 'Minimal logo concepts', 'Image Generation', 'Midjourney', 'image', false, 'Simple logo ideas to explore before a designer finalises them.',
		"Minimal flat vector logo for [BRAND], a [BUSINESS TYPE], simple geometric mark combining [SYMBOL 1] and [SYMBOL 2], two colours [COLOUR], white background, no text, clean lines --no gradient, shadow, photo --v 7",
		"Four simple mark concepts to choose from. Recreate the chosen one as a true vector (SVG) before using it as a real logo." ),
	$p( 'img-isometric', 'Isometric 3D illustration', 'Image Generation', 'ChatGPT Images', 'image', true, 'Trendy 3D isometric scenes for websites, slides and banners.',
		"Isometric 3D illustration of [SCENE, e.g. a tiny AI-powered classroom with students, laptops and a floating robot tutor], soft pastel colours, clay-like materials, gentle shadows, white background, high detail, cute and friendly style",
		"A floating diorama-style scene that works as a website hero or slide visual, with a transparent-looking white background." ),
	$p( 'img-character', 'Consistent character sheet', 'Image Generation', 'Midjourney', 'image', true, 'One character in several poses for comics, mascots or courses.',
		"Character sheet of [CHARACTER, e.g. a cheerful young Indian coding mentor with round glasses and a green hoodie], front view, side view, back view and 3 facial expressions, consistent outfit and proportions, clean white background, 3D animated film style --ar 3:2 --stylize 150",
		"A reference sheet with the same character from several angles; reuse it with --cref or as a reference image to keep the mascot consistent." ),
	$p( 'img-sd-portrait', 'Photoreal portrait (Stable Diffusion)', 'Image Generation', 'Stable Diffusion', 'image', true, 'Prompt + negative prompt + settings for natural-looking portraits.',
		"Prompt: portrait photo of a [AGE] [PERSON DESCRIPTION], natural window light, 85mm lens, shallow depth of field, soft skin texture, realistic, detailed eyes, neutral background\nNegative prompt: blurry, cartoon, extra fingers, deformed, oversaturated, watermark, text, plastic skin\nSettings: SDXL model, 1024×1024, 30 steps, CFG 6, DPM++ 2M Karras",
		"A natural portrait with realistic skin and lighting. Use inpainting to fix hands or eyes if needed." ),

	/* Video & reels */
	$p( 'video-ai-shot', 'Cinematic AI video shot', 'Video & Reels', 'Runway / Veo / Sora', 'video', true, 'A shot description that text-to-video tools follow well.',
		"Cinematic 6-second shot: [SUBJECT] [ACTION] in [PLACE]. Camera: slow dolly in from medium to close-up. Lighting: golden hour, warm. Style: realistic, 24fps film look, shallow depth of field. Mood: hopeful. No text on screen.",
		"A short clip with a smooth camera move; generate 3–4 takes and keep the most natural one." ),
	$p( 'explainer-video', 'Explainer video script + scenes', 'Video & Reels', 'Any chatbot', 'video', false, 'A 60-second explainer with narration, visuals and timing.',
		"Write a 60-second explainer video script about [TOPIC] for [AUDIENCE].\nTable: Scene | Seconds | Narration | Visual | On-screen text. Simple words, one idea per scene, end with a clear CTA.",
		"A scene table you can hand to an editor or use with a slideshow/AI video tool." ),

	/* Music & audio */
	$p( 'song-suno', 'Original song in any style', 'Music & Audio', 'Suno', 'audio', false, 'Lyrics and a style prompt for an AI music tool.',
		"Style: [GENRE, e.g. upbeat lo-fi hip hop], [MOOD], [INSTRUMENTS], [TEMPO] BPM, female vocals\nLyrics:\n[Verse] … [Chorus] …\nTopic: [TOPIC, e.g. late-night coding before a hackathon]. Write catchy, original lyrics with a repeating chorus.",
		"A 2–3 minute track with your lyrics. Check the tool’s licence before using it in monetised videos." ),
	$p( 'podcast-outline', 'Podcast episode plan', 'Music & Audio', 'Any chatbot', 'audio', true, 'Episode flow, questions and show notes for a solo or guest episode.',
		"Plan a [LENGTH]-minute podcast episode on [TOPIC] with [GUEST / SOLO].\nGive: a hook intro, 3 segments with timings, 12 interview questions (easy → deep), a closing CTA, show notes with timestamps, and 5 title options.",
		"A run sheet with timings, questions, show notes and title ideas." ),

	/* Productivity */
	$p( 'meeting-notes', 'Meeting notes to action items', 'Productivity', 'Any chatbot', 'text', false, 'Turn a transcript into decisions, owners and deadlines.',
		"From this meeting transcript, extract: a 5-line summary, decisions made, action items as a table (Task | Owner | Deadline), open questions, and a follow-up email to send.\n\n\"\"\"\n[TRANSCRIPT]\n\"\"\"",
		"A tidy summary, an action-item table and a ready follow-up email." ),
	$p( 'weekly-plan', 'Weekly plan with priorities', 'Productivity', 'Claude', 'text', false, 'Sort your tasks by impact and fit them into real time slots.',
		"Here are my tasks for the week: [LIST]. Fixed commitments: [CLASSES / OFFICE HOURS].\nSort tasks by impact and urgency, estimate time for each, and build a day-by-day plan with focus blocks, buffers and one free evening. Flag anything I should drop or delegate.",
		"A prioritised list and a realistic weekly schedule with focus blocks." ),
	$p( 'learn-anything-plan', 'Learn any skill in 30 days', 'Productivity', 'Any chatbot', 'text', true, 'A project-based learning path with free resources and checkpoints.',
		"I want to learn [SKILL] in 30 days, [HOURS] per day. My level: [LEVEL]. Goal: [GOAL / PROJECT].\nGive a weekly plan built around one project, free resources for each week, a checkpoint test at the end of each week, and common traps to avoid.",
		"A 4-week project-based plan with free resources and weekly self-tests." ),
);
