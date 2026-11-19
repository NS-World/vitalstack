<?php
/**
 * AI Commands & Shortcuts. Ctrl = Cmd on a Mac. Apps change their shortcuts
 * from time to time: each app also has its own list (shown with the first row
 * where available).
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

$c = function ( $id, $tool, $title, $cat, $premium, $summary, $rows, $tips ) {
	return array( 'id' => $id, 'tool' => $tool, 'title' => $title, 'cat' => $cat, 'premium' => $premium, 'summary' => $summary, 'rows' => $rows, 'tips' => $tips );
};

return array(
	$c(
		'chatgpt',
		'ChatGPT',
		'ChatGPT: shortcuts and power features',
		'Chat assistants',
		false,
		'Keyboard shortcuts for the web and desktop app, plus the features most people never switch on.',
		array(
			array( 'Ctrl + /', 'Show every keyboard shortcut' ),
			array( 'Ctrl + Shift + O', 'New chat' ),
			array( 'Shift + Esc', 'Jump to the message box' ),
			array( 'Shift + Enter', 'New line without sending' ),
			array( 'Ctrl + Shift + C', 'Copy the last answer' ),
			array( 'Ctrl + Shift + ;', 'Copy the last code block' ),
			array( 'Ctrl + Shift + S', 'Show or hide the sidebar' ),
			array( 'Ctrl + Shift + Backspace', 'Delete the current chat' ),
			array( 'Settings → Personalization → Custom instructions', 'Tell it once who you are and how you like answers' ),
			array( 'Temporary chat', 'A chat that is not saved to your history or memory' ),
			array( 'Projects', 'Group chats and files for one topic so it keeps the context' ),
			array( 'Search (globe icon)', 'Current information with links to sources' ),
		),
		array(
			'Edit your earlier message instead of adding corrections: you get a clean new answer.',
			'Upload a PDF or image and ask questions about it; ask for page numbers to check quickly.',
			'Ask “ask me 3 questions before you start” for anything personal like a resume or a plan.',
		)
	),
	$c(
		'claude',
		'Claude',
		'Claude: features and prompting moves',
		'Chat assistants',
		false,
		'Projects, Artifacts, Styles and thinking mode, and the prompt habits that suit Claude best.',
		array(
			array( 'Shift + Enter', 'New line without sending' ),
			array( 'Projects → Project knowledge', 'Add files and instructions used in every chat of that project' ),
			array( 'Artifacts', 'Code, documents, diagrams and small apps open beside the chat to edit and download' ),
			array( 'Styles', 'Switch between Normal, Concise, Explanatory, Formal, or a custom style from your writing' ),
			array( 'Extended thinking', 'Slower, deeper reasoning for hard maths, code and planning' ),
			array( 'Edit a sent message (pencil)', 'Branch the conversation from that point' ),
			array( '<document> … </document>', 'XML-style tags keep pasted text separate from your instructions' ),
		),
		array(
			'Put long documents first and your question at the end.',
			'Give it an example of the output you want; it copies structure very well.',
			'For coding, ask it to make an Artifact so you can see and iterate on the result.',
		)
	),
	$c(
		'gemini',
		'Gemini',
		'Gemini: apps, Gems and research',
		'Chat assistants',
		false,
		'Use Google apps from the chat, build your own Gems and check answers with Google Search.',
		array(
			array( '@ + app name', 'Use a connected app, e.g. @YouTube, @Gmail, @Google Drive (where available)' ),
			array( 'Gems', 'Save a custom assistant with its own instructions, e.g. “Java interview coach”' ),
			array( 'Deep Research', 'A multi-step web research report with sources' ),
			array( 'Canvas', 'Write and edit documents or code side by side' ),
			array( 'Double-check response (G icon)', 'Highlights statements Google Search supports or contradicts' ),
			array( 'Upload image / PDF / audio', 'Ask questions about photos, notes, slides and recordings' ),
		),
		array(
			'For Workspace users, Gemini in Gmail and Docs can draft and summarise in the side panel.',
			'Use Gems for anything you repeat weekly, such as a content calendar or study planner.',
		)
	),
	$c(
		'github-copilot',
		'GitHub Copilot',
		'GitHub Copilot in VS Code',
		'Coding',
		false,
		'Accept, cycle and steer suggestions, then use chat slash commands to explain, fix and test code.',
		array(
			array( 'Tab', 'Accept the suggestion' ),
			array( 'Esc', 'Dismiss the suggestion' ),
			array( 'Alt + ]  /  Alt + [', 'Next / previous suggestion (Option on Mac)' ),
			array( 'Ctrl + →', 'Accept only the next word' ),
			array( 'Ctrl + I', 'Inline chat in the editor' ),
			array( 'Ctrl + Alt + I', 'Open the Chat view' ),
			array( '/explain', 'Explain the selected code' ),
			array( '/fix', 'Propose a fix for problems in the selection' ),
			array( '/tests', 'Generate unit tests' ),
			array( '/doc', 'Add documentation comments' ),
			array( '@workspace', 'Ask about the whole project' ),
			array( '#file  /  #selection', 'Attach a file or the selection as context' ),
		),
		array(
			'Write a clear comment or function name first; suggestions follow your intent.',
			'Keep related files open: Copilot uses open tabs as context.',
		)
	),
	$c(
		'midjourney',
		'Midjourney',
		'Midjourney parameters cheat sheet',
		'Image',
		false,
		'The parameters you add at the end of a prompt to control shape, style, variety and repeatability.',
		array(
			array( '--ar 16:9', 'Aspect ratio (9:16 for reels, 1:1 for posts, 3:2 photos)' ),
			array( '--stylize 250  (--s)', 'How artistic vs literal (0–1000, default 100)' ),
			array( '--chaos 30  (--c)', 'How different the 4 images are (0–100)' ),
			array( '--no text, watermark', 'Things to keep out of the image' ),
			array( '--seed 1234', 'Reuse a seed for similar results' ),
			array( '--sref <image URL>', 'Style reference: copy the look of an image' ),
			array( '--iw 1.5', 'Image weight when you start from an image prompt' ),
			array( '--tile', 'Seamless repeating pattern' ),
			array( '--raw', 'Less default “Midjourney look”, more literal' ),
			array( 'Vary (Region)', 'Repaint only a selected area' ),
			array( 'Upscale', 'Bigger, sharper version of one image' ),
		),
		array(
			'Order: subject, setting, composition, lighting, style, then parameters.',
			'Short, concrete prompts often beat long lists of adjectives.',
		)
	),
	$c(
		'perplexity',
		'Perplexity',
		'Perplexity: research faster',
		'Research',
		false,
		'An answer engine that searches the web and cites sources: how to get better research out of it.',
		array(
			array( 'Sources selector', 'Choose Web, Academic or Social sources for the search' ),
			array( 'Pro / deep search', 'More searches and reasoning for complex questions' ),
			array( 'Follow-up box', 'Narrow the answer without starting over' ),
			array( 'Spaces', 'Keep research threads and files on one topic together' ),
			array( 'Citations [1] [2]', 'Open them to check the claim in the original source' ),
		),
		array(
			'Ask comparison questions with criteria: “Compare X and Y for a beginner on price, speed and job demand, in a table”.',
		)
	),

	/* ── Premium ── */
	$c(
		'cursor',
		'Cursor',
		'Cursor: the AI code editor, pro workflow',
		'Coding',
		true,
		'Inline edits, chat, agent mode, context mentions and project rules: the workflow pros use to ship faster.',
		array(
			array( 'Tab', 'Accept the multi-line prediction (Cursor Tab)' ),
			array( 'Ctrl + K', 'Edit or generate code right where the cursor is' ),
			array( 'Ctrl + L', 'Open chat with the selected code' ),
			array( 'Ctrl + I', 'Agent / Composer: changes across many files' ),
			array( '@Files  @Folders', 'Point the AI at exact files' ),
			array( '@Docs  @Web', 'Use library documentation or a web search as context' ),
			array( '.cursor/rules', 'Project rules: coding style, stack, “always write tests”' ),
			array( 'Checkpoints / restore', 'Undo an agent run that went wrong' ),
		),
		array(
			'Start every task with a 3-line plan in chat; approve it, then let the agent implement.',
			'Ask for small diffs and review each one; large one-shot changes hide bugs.',
			'Keep a rules file with your stack and conventions so every answer matches your project.',
			'After the agent finishes, ask it to run the tests and fix failures before you review.',
		)
	),
	$c(
		'stable-diffusion',
		'Stable Diffusion',
		'Stable Diffusion (Automatic1111 / ComfyUI) settings guide',
		'Image',
		true,
		'Negative prompts, weights, samplers, CFG, LoRAs, ControlNet and inpainting: the settings that fix bad images.',
		array(
			array( 'Negative prompt', 'blurry, low quality, extra fingers, watermark, text, deformed hands' ),
			array( '(word:1.3)', 'Give a word more weight (Automatic1111 syntax)' ),
			array( '[word]', 'Give a word less weight' ),
			array( 'Steps 25–35', 'More steps = more detail, slower (diminishing returns after ~40)' ),
			array( 'CFG scale 5–8', 'How strictly to follow the prompt (too high looks burnt)' ),
			array( 'Sampler: DPM++ 2M Karras', 'A reliable default for most models' ),
			array( 'Seed', '-1 for random; fix it to compare settings fairly' ),
			array( '<lora:name:0.8>', 'Apply a LoRA style or character at 80% strength' ),
			array( 'Hires fix', 'Generate small, upscale with added detail' ),
			array( 'img2img denoise 0.3–0.6', 'Low keeps the original, high changes more' ),
			array( 'ControlNet (OpenPose / Canny / Depth)', 'Copy a pose, outline or depth from a reference image' ),
			array( 'Inpainting', 'Mask an area and regenerate only that part (hands, faces, text)' ),
		),
		array(
			'Match resolution to the model (e.g. 1024×1024 for SDXL models) and upscale later.',
			'Change one setting at a time with a fixed seed to learn what each does.',
			'Check the licence of every model and LoRA before commercial use.',
		)
	),
	$c(
		'terminal-agents',
		'Claude Code',
		'AI in the terminal: Claude Code and Gemini CLI',
		'Coding',
		true,
		'Coding agents that read your repo, run commands and edit files: the commands and habits that keep them on track.',
		array(
			array( '/init', 'Claude Code: create a CLAUDE.md file describing your project for future sessions' ),
			array( '/clear', 'Start fresh (clears the conversation context)' ),
			array( '/compact', 'Summarise a long session to free up context' ),
			array( '/model', 'Switch model' ),
			array( 'Shift + Tab', 'Claude Code: cycle modes (normal, auto-accept edits, plan mode)' ),
			array( 'Esc', 'Stop the current step' ),
			array( '! command', 'Run a shell command directly' ),
			array( '@path/to/file', 'Gemini CLI: include a file in the prompt' ),
			array( '/memory', 'View or edit the saved project memory' ),
			array( '/help', 'All commands' ),
		),
		array(
			'Use plan mode for anything bigger than a small fix: read the plan before any edit happens.',
			'Keep a short project file (CLAUDE.md / GEMINI.md) with build, test and style commands.',
			'Never paste secrets; give the agent a test environment, not production keys.',
			'Commit before you start so you can always roll back.',
		)
	),
	$c(
		'power-moves',
		'Any chatbot',
		'25 prompt power moves that work in every chatbot',
		'Power moves',
		true,
		'Short add-on lines that instantly improve answers: clarify, critique, compress, compare and structure.',
		array(
			array( 'Ask me questions until you are 95% sure what I need.', 'Gets missing context before it answers' ),
			array( 'Give 3 options with pros and cons, then recommend one.', 'Better decisions than a single answer' ),
			array( 'Critique your answer like a strict reviewer, then improve it.', 'Self-review raises quality' ),
			array( 'Explain it to a 12-year-old, then to an expert.', 'Checks real understanding' ),
			array( 'Answer in a table with columns: …', 'Scannable comparisons' ),
			array( 'TL;DR in 3 bullets first, details after.', 'Summary up top' ),
			array( 'List your assumptions before answering.', 'Reveals wrong guesses early' ),
			array( 'If you are not sure, say so.', 'Fewer confident mistakes' ),
			array( 'Use only the text between """ and """.', 'Stops it inventing facts' ),
			array( 'Think step by step, then give the final answer on one line.', 'Better reasoning on hard problems' ),
			array( 'Rewrite for a Class 8 reading level.', 'Plain language' ),
			array( 'Keep my words; fix only grammar and clarity.', 'Edits without changing your voice' ),
			array( 'Continue from where you stopped.', 'Finishes cut-off answers' ),
			array( 'Turn this into a checklist I can tick.', 'Actionable steps' ),
			array( 'Play the opposing side and argue against my plan.', 'Finds weak spots' ),
			array( 'What would an expert notice that a beginner would miss?', 'Deeper insight' ),
			array( 'Give an example, then a counter-example.', 'Clarifies the boundary of an idea' ),
			array( 'Return valid JSON only, matching this schema: …', 'Machine-readable output' ),
			array( 'Make it 30% shorter without losing meaning.', 'Tight writing' ),
			array( 'Give me a 7-day plan with 30 minutes a day.', 'Turns goals into a schedule' ),
			array( 'Quiz me with 5 questions, one at a time, and wait for my answer.', 'Active learning' ),
			array( 'Translate to Hindi, keep technical terms in English.', 'Natural Hinglish-friendly translations' ),
			array( 'Point out anything that might be outdated.', 'Flags old information' ),
			array( 'Write the prompt I should have asked.', 'Learn better prompting' ),
			array( 'Summarise our chat so far as notes I can save.', 'Keeps a record' ),
		),
		array( 'Stack 2–3 moves at the end of any prompt, e.g. “Ask me questions first. Then answer in a table. Keep it under 150 words.”' )
	),
);
