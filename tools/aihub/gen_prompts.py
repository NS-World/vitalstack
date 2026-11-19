#!/usr/bin/env python3
"""Generate the AI Hub Prompt Bazaar catalogue (prompts added to the hand-made ones).

Table-driven sections (languages, roles, topics, platforms, industries, styles)
plus hand-written sections in prompts_hand.py. Every prompt uses [BRACKETS]
for the reader's details and says what a good result looks like.

Output: <plugin>/includes/data/prompts-gen.php
Usage: python3 tools/aihub/gen_prompts.py [plugin_dir]
"""
import json
import os
import re
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import prompts_hand  # noqa: E402

PLUGIN = sys.argv[1] if len(sys.argv) > 1 else os.path.join(os.path.dirname(__file__), '..', '..', 'plugins', 'vitalstack-ai-hub')
TARGET = 1000
OUT = []


def add(cat, tool, kind, title, summary, prompt, output):
    OUT.append(dict(cat=cat, tool=tool, kind=kind, title=title, summary=summary, prompt=prompt.strip(), output=output.strip()))


# ── Coding: 12 languages × 9 tasks ─────────────────────────────────────────
LANGS = [
    # name, test, doc, lint, pkg, style, project, convert_from, perf
    ('Python', 'pytest (with fixtures and parametrize)', 'Google-style docstrings (PEP 257)', 'ruff and black', 'pip / uv', 'PEP 8 and type hints', 'a REST API with FastAPI and Pydantic', 'JavaScript', 'cProfile and line_profiler'),
    ('Java', 'JUnit 5 with Mockito', 'Javadoc', 'Checkstyle and SpotBugs', 'Maven or Gradle', 'Effective Java guidelines (Java 17+)', 'a REST API with Spring Boot 3, Spring Data JPA and validation', 'Python', 'JMH benchmarks and a profiler such as async-profiler or VisualVM'),
    ('JavaScript', 'Jest (or Vitest)', 'JSDoc', 'ESLint and Prettier', 'npm', 'modern ES2022+ (const/let, async/await, modules)', 'a REST API with Node.js and Express', 'Python', 'Chrome DevTools Performance panel and node --prof'),
    ('TypeScript', 'Vitest (or Jest with ts-jest)', 'TSDoc comments', 'typescript-eslint and Prettier', 'npm / pnpm', 'strict mode, no any, discriminated unions', 'a typed REST API with Node.js, Express and Zod validation', 'JavaScript', 'Chrome DevTools and clinic.js'),
    ('C++', 'GoogleTest', 'Doxygen comments', 'clang-tidy and clang-format', 'CMake (with vcpkg or Conan)', 'modern C++17/20, RAII, no raw new/delete', 'a command-line tool with CMake that parses arguments and reads a file', 'Python', 'perf, Valgrind (callgrind) and compiler optimisation reports'),
    ('C#', 'xUnit with Moq', 'XML documentation comments', '.NET analyzers and dotnet format', 'NuGet / dotnet CLI', '.NET 8 conventions, nullable reference types on', 'a minimal API with ASP.NET Core and Entity Framework Core', 'Java', 'BenchmarkDotNet and dotnet-trace'),
    ('Go', 'the standard testing package with table-driven tests', 'Go doc comments', 'gofmt, go vet and staticcheck', 'Go modules', 'Effective Go, errors as values, small interfaces', 'a REST API with net/http (Go 1.22 routing patterns)', 'Python', 'pprof and go test -bench'),
    ('Rust', 'built-in #[test] functions run with cargo test', 'rustdoc /// comments with examples', 'clippy and rustfmt', 'Cargo', 'idiomatic ownership and borrowing, Result-based errors', 'a REST API with axum and tokio', 'Python', 'cargo bench (criterion) and flamegraph'),
    ('Kotlin', 'JUnit 5 with MockK (or Kotest)', 'KDoc', 'ktlint and detekt', 'Gradle (Kotlin DSL)', 'null safety, data classes, coroutines', 'a REST API with Ktor', 'Java', 'JMH via kotlinx-benchmark and Android Studio / IntelliJ profilers'),
    ('Swift', 'XCTest (or the Swift Testing framework)', 'DocC-compatible /// comments', 'SwiftLint and swift-format', 'Swift Package Manager', 'value types, optionals handled safely, async/await', 'a small iOS screen with SwiftUI that loads and lists data from an API', 'Kotlin', 'Instruments (Time Profiler, Allocations)'),
    ('PHP', 'PHPUnit', 'PHPDoc', 'PHPStan and PHP_CodeSniffer (PSR-12)', 'Composer', 'PHP 8.2+, strict_types, typed properties', 'a REST API with Laravel (routes, controllers, validation, Eloquent)', 'JavaScript', 'Xdebug profiler or Blackfire'),
    ('Ruby', 'RSpec', 'YARD comments', 'RuboCop', 'Bundler', 'idiomatic Ruby 3, small methods', 'a JSON API with Ruby on Rails (API mode)', 'Python', 'ruby-prof and benchmark-ips'),
]
CODE_TOOLS = ['Any chatbot', 'Claude', 'ChatGPT', 'GitHub Copilot', 'Cursor', 'Gemini']


def coding():
    for i, (L, test, doc, lint, pkg, style, project, src, perf) in enumerate(LANGS):
        tool = lambda k: CODE_TOOLS[(i + k) % len(CODE_TOOLS)]  # noqa: E731
        add('Coding', tool(0), 'code', 'Explain %s code line by line' % L, 'Understand unfamiliar %s code: what it does, how it flows and where it can break.' % L,
            f"""You are a senior {L} developer and a patient teacher.
Explain the {L} code below to a [BEGINNER / INTERMEDIATE] developer.
1. One-paragraph summary of what it does.
2. Walk through it block by block (quote the lines, then explain).
3. List the {L} features or idioms it uses and why.
4. Point out bugs, edge cases or risky parts.
5. Suggest 2 small improvements that follow {style}.

```{L.lower()}
[PASTE CODE]
```""",
            f"A plain-English summary, a block-by-block walkthrough with quoted lines, a list of {L} idioms used, risks and edge cases, and two concrete improvements.")
        add('Coding', tool(1), 'code', 'Write %s unit tests' % L, 'Get a complete, runnable %s test file with edge cases.' % L,
            f"""Write unit tests for the {L} code below using {test}.
- Cover the happy path, edge cases (empty, null/None, boundaries, large input) and error paths.
- One behaviour per test, descriptive test names.
- Mock external calls (network, database, time).
- Output one complete, runnable test file, then a table: test name | what it proves.

Code:
```{L.lower()}
[PASTE CODE]
```""",
            f"A runnable {test.split(' (')[0]} test file with clear names and mocks, followed by a coverage table you can review in a minute.")
        add('Coding', tool(2), 'code', 'Refactor %s code for readability' % L, 'Clean up messy %s without changing behaviour, with each change explained.' % L,
            f"""Refactor this {L} code for readability and maintainability WITHOUT changing its behaviour.
Rules: follow {style}; small functions with clear names; remove duplication; keep the public API the same.
Output:
1. The refactored code.
2. A numbered list of each change and why it helps.
3. Anything that needs a test before you would ship it.

```{L.lower()}
[PASTE CODE]
```""",
            "Cleaner code with the same behaviour, a change log you can paste into a pull request, and a list of risky spots to test.")
        add('Coding', tool(3), 'code', 'Debug a %s error' % L, 'Paste the error and code; get the root cause, the fix and how to avoid it next time.' % (),
            f"""I get this error in my {L} project:
```
[PASTE FULL ERROR / STACK TRACE]
```
Relevant code:
```{L.lower()}
[PASTE CODE]
```
Environment: [{L} VERSION, OS, FRAMEWORK/LIBRARY VERSIONS]
1. Explain the root cause in simple words.
2. Show the minimal fix as a diff.
3. Explain why the fix works.
4. Suggest how to prevent this class of bug (test, type, lint rule).
If information is missing, ask me up to 3 questions first.""",
            "The likely root cause, a small diff, the reasoning, and one prevention step; or up to three precise questions if the error is ambiguous.")
        add('Coding', tool(4), 'code', '%s code review checklist' % L, 'A senior-style %s review: correctness, security, performance and style, ranked by severity.' % L,
            f"""Act as a strict but kind senior {L} reviewer. Review this code for:
- correctness and edge cases
- security (input validation, injection, secrets, unsafe APIs)
- performance and memory
- readability and {style}
- tests that are missing
Rank findings as Blocker / Should fix / Nit. For each: line(s), problem, suggested fix (code).
End with a 2-line summary verdict.

```{L.lower()}
[PASTE CODE OR DIFF]
```""",
            "A severity-ranked list of findings with line references and fix snippets, and a short verdict, like a real pull-request review.")
        add('Coding', tool(5), 'code', 'Convert %s code to %s' % (src, L), 'Translate code from %s into idiomatic %s, not a word-for-word copy.' % (src, L),
            f"""Convert this {src} code into idiomatic {L}.
- Use {L} conventions ({style}) and standard library equivalents.
- Keep the same behaviour; note any behaviour that cannot map 1:1.
- Add {L} type information where the language supports it.
- Include a short usage example and the dependencies to install with {pkg}.

```{src.lower()}
[PASTE {src.upper()} CODE]
```""",
            f"Idiomatic {L} code, a list of differences between the two languages that matter here, a usage example and the exact install command.")
        add('Coding', tool(0), 'code', 'Document %s code' % L, 'Add clear %s documentation comments and a README section.' % L,
            f"""Add documentation to this {L} code using {doc}.
- Document every public function/class: purpose, parameters, return value, errors raised, and one example.
- Do not change the code itself.
- Then write a README "Usage" section with installation ({pkg}) and 2 examples.

```{L.lower()}
[PASTE CODE]
```""",
            f"The same code with {doc.split(' (')[0]} on every public item, plus a README usage section you can paste as-is.")
        add('Coding', tool(1), 'code', 'Speed up slow %s code' % L, 'Find the real bottlenecks in %s code and get faster versions with measurements.' % L,
            f"""This {L} code is too slow for [INPUT SIZE / USE CASE].
```{L.lower()}
[PASTE CODE]
```
1. Estimate the time and space complexity of the current code.
2. Identify the top 3 bottlenecks.
3. Give an optimised version and its new complexity.
4. Show how to measure the improvement with {perf}.
Do not sacrifice correctness or readability without saying so.""",
            "Big-O analysis before and after, the main bottlenecks, an optimised version, and the exact profiling or benchmark steps to prove the speed-up.")
        add('Coding', tool(2), 'code', 'Guided %s project: %s' % (L, project.split(' with ')[0].replace('a ', '', 1).replace('an ', '', 1)), 'A guided build of %s, with structure, code and tests.' % project,
            f"""Guide me to build {project}.
Feature: [DESCRIBE, e.g. a to-do list with create, list, update, delete].
1. Project structure (folders and files).
2. Setup commands using {pkg}.
3. The code for each file, in order, with short explanations.
4. Input validation and error handling.
5. Tests with {test}.
6. How to run it locally and what to try next.
Keep it production-minded but beginner-friendly.""",
            "A folder tree, setup commands, complete files in build order, validation, tests and run instructions: a small project you can finish in an evening.")


# ── Career & Jobs: 30 roles × 5 tasks ──────────────────────────────────────
ROLES = [
    ('Software Engineer', 'data structures and algorithms, system design basics, Git, testing, code reviews'),
    ('Frontend Developer', 'HTML, CSS, JavaScript, React, accessibility, web performance'),
    ('Backend Developer', 'REST APIs, databases, caching, authentication, scalability'),
    ('Full-Stack Developer', 'React, Node.js, SQL/NoSQL databases, REST APIs, deployment'),
    ('Java Developer', 'Core Java, OOP, collections, Spring Boot, JPA/Hibernate, SQL, microservices'),
    ('Python Developer', 'Python, Django or FastAPI, SQL, testing, REST APIs'),
    ('Data Analyst', 'SQL, Excel, Power BI or Tableau, statistics, storytelling with data'),
    ('Data Scientist', 'Python, statistics, pandas, scikit-learn, experimentation, communication'),
    ('Machine Learning Engineer', 'Python, PyTorch or TensorFlow, model deployment, MLOps, data pipelines'),
    ('Data Engineer', 'SQL, Python, Spark, Airflow, data warehousing, ETL/ELT'),
    ('DevOps Engineer', 'Linux, CI/CD, Docker, Kubernetes, Terraform, monitoring'),
    ('Cloud Engineer', 'AWS, Azure or GCP, networking, IAM, infrastructure as code, cost control'),
    ('QA Automation Engineer', 'test design, Selenium or Playwright, API testing, CI integration'),
    ('Android Developer', 'Kotlin, Jetpack Compose, Android SDK, architecture components, testing'),
    ('iOS Developer', 'Swift, SwiftUI, UIKit, concurrency, App Store release'),
    ('UI/UX Designer', 'Figma, user research, wireframes, prototyping, design systems, usability testing'),
    ('Product Manager', 'discovery, roadmaps, prioritisation, metrics, stakeholder communication'),
    ('Business Analyst', 'requirements gathering, process mapping, SQL, user stories, stakeholder management'),
    ('Cybersecurity Analyst', 'SOC monitoring, SIEM, incident response, networking, threat analysis'),
    ('Digital Marketer', 'SEO, Google Ads, Meta Ads, analytics, content marketing, email'),
    ('Content Writer', 'research, SEO writing, editing, storytelling, content strategy'),
    ('Graphic Designer', 'typography, layout, branding, Adobe Illustrator/Photoshop or Figma, print and digital'),
    ('Project Manager', 'Agile and Scrum, planning, risk management, budgeting, stakeholder communication'),
    ('HR Executive', 'recruitment, onboarding, employee engagement, HR policies, HRMS tools'),
    ('Sales Executive', 'prospecting, CRM, negotiation, pipeline management, closing'),
    ('Customer Support Specialist', 'empathy, ticketing tools, troubleshooting, clear writing, SLAs'),
    ('Accountant', 'bookkeeping, GST and TDS basics, Tally or Zoho Books, Excel, reconciliation'),
    ('Teacher', 'lesson planning, classroom management, assessment, differentiated instruction, edtech tools'),
    ('Embedded Systems Engineer', 'C, microcontrollers, RTOS, communication protocols (I2C, SPI, UART), debugging hardware'),
    ('Salesforce Developer', 'Apex, Lightning Web Components, SOQL, Flows, Salesforce data model'),
]


def careers():
    for i, (R, skills) in enumerate(ROLES):
        tools = ['ChatGPT', 'Claude', 'Any chatbot', 'Gemini']
        t = lambda k: tools[(i + k) % 4]  # noqa: E731
        add('Career & Jobs', t(0), 'text', '%s mock interview' % R, 'Practise a realistic %s interview, one question at a time, with scores and model answers.' % R,
            f"""Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] {R}.
Key skills to test: {skills}.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.""",
            f"An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a {R} role.")
        add('Career & Jobs', t(1), 'text', '%s resume bullet points' % R, 'Turn plain duties into impact-focused, ATS-friendly %s resume bullets.' % R,
            f"""Rewrite my experience as strong resume bullets for a {R} role.
My raw notes:
\"\"\"
[PASTE WHAT YOU DID: projects, tools, results, numbers]
\"\"\"
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: {skills}.
- Give 6–8 bullets, then a 2-line professional summary.""",
            "Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.")
        add('Career & Jobs', t(2), 'text', '%s cover letter' % R, 'A short, specific %s cover letter that matches the job post instead of sounding generic.' % R,
            f"""Write a cover letter for a {R} position.
Job description:
\"\"\"
[PASTE JOB POST]
\"\"\"
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.""",
            "A sub-250-word letter that mirrors the job's key requirements with your real achievements and ends with a clear next step.")
        add('Career & Jobs', t(3), 'text', '%s LinkedIn headline and About' % R, 'A searchable LinkedIn headline and About section for a %s.' % R,
            f"""Write my LinkedIn profile copy as a {R}.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as {skills.split(',')[0]}.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].""",
            "Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.")
        add('Career & Jobs', t(0), 'text', '%s 30-60-90 day plan' % R, 'Impress in the final round with a clear first-90-days plan for a new %s role.' % R,
            f"""Create a 30-60-90 day plan for my new job as a {R} at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role's core skills: {skills}.
Format as a table, then a 5-line summary I can say out loud in an interview.""",
            "A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.")


# ── DSA & Interviews: 25 topics × 2 ────────────────────────────────────────
DSA = [
    ('Arrays and prefix sums', 'Two Sum, Product of Array Except Self, Subarray Sum Equals K'),
    ('Two pointers', 'Valid Palindrome, 3Sum, Container With Most Water'),
    ('Sliding window', 'Longest Substring Without Repeating Characters, Minimum Window Substring, Max Consecutive Ones III'),
    ('Binary search', 'Search in Rotated Sorted Array, Find Peak Element, Koko Eating Bananas'),
    ('Linked lists', 'Reverse Linked List, Linked List Cycle, Merge Two Sorted Lists'),
    ('Stacks and monotonic stacks', 'Valid Parentheses, Daily Temperatures, Largest Rectangle in Histogram'),
    ('Queues and deques', 'Sliding Window Maximum, Design Circular Queue, Number of Recent Calls'),
    ('Hashing', 'Group Anagrams, Longest Consecutive Sequence, Top K Frequent Elements'),
    ('Recursion', 'Pow(x, n), Generate Parentheses, Merge Sort'),
    ('Backtracking', 'Subsets, Permutations, N-Queens'),
    ('Binary trees', 'Maximum Depth, Invert Binary Tree, Lowest Common Ancestor'),
    ('Binary search trees', 'Validate BST, Kth Smallest Element in a BST, Insert into a BST'),
    ('Heaps and priority queues', 'Kth Largest Element, Merge k Sorted Lists, Find Median from Data Stream'),
    ('Graph BFS and DFS', 'Number of Islands, Clone Graph, Rotting Oranges'),
    ('Shortest paths (Dijkstra, Bellman-Ford)', 'Network Delay Time, Cheapest Flights Within K Stops, Path With Minimum Effort'),
    ('Union-Find (disjoint set)', 'Number of Connected Components, Redundant Connection, Accounts Merge'),
    ('Topological sort', 'Course Schedule, Course Schedule II, Alien Dictionary'),
    ('1D dynamic programming', 'Climbing Stairs, House Robber, Coin Change'),
    ('2D dynamic programming', 'Unique Paths, Longest Common Subsequence, Edit Distance'),
    ('Knapsack patterns', '0/1 Knapsack, Partition Equal Subset Sum, Target Sum'),
    ('Greedy algorithms', 'Jump Game, Gas Station, Non-overlapping Intervals'),
    ('Tries', 'Implement Trie, Word Search II, Design Add and Search Words'),
    ('Bit manipulation', 'Single Number, Counting Bits, Sum of Two Integers'),
    ('Intervals', 'Merge Intervals, Insert Interval, Meeting Rooms II'),
    ('Segment trees and Fenwick trees', 'Range Sum Query - Mutable, Count of Smaller Numbers After Self, Range Minimum Query'),
]


def dsa():
    for i, (topic, probs) in enumerate(DSA):
        add('DSA & Interviews', 'Any chatbot' if i % 2 else 'Claude', 'code', 'Learn the %s pattern' % topic.split(' (')[0], 'Understand when and how to use %s, with a template you can reuse in interviews.' % topic.lower(),
            f"""Teach me the {topic} pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem ({probs.split(',')[0]}) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.""",
            f"A clear mental model, recognition signals, a commented template, a dry-run walkthrough of {probs.split(',')[0]}, complexity and pitfalls, ending in a one-line cheat sheet.")
        add('DSA & Interviews', 'ChatGPT' if i % 2 else 'Any chatbot', 'code', '%s practice set with hints' % topic.split(' (')[0], 'Five graded %s problems with progressive hints, and solutions only when you ask.' % topic.lower(),
            f"""Be my {topic} practice coach.
Give me 5 problems from easy to hard (you can include classics like {probs}).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.""",
            "An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.")


# ── Study & Exams ──────────────────────────────────────────────────────────
SUBJECTS = ['Physics', 'Chemistry', 'Biology', 'Mathematics', 'History', 'Geography', 'Economics', 'Political Science', 'Accountancy',
            'Computer Science', 'English Grammar', 'Statistics', 'Business Studies', 'Environmental Science', 'Psychology']
EXAMS = [
    ('JEE Main', 'Physics, Chemistry and Mathematics'),
    ('NEET UG', 'Physics, Chemistry, Botany and Zoology'),
    ('UPSC CSE Prelims', 'General Studies Paper I and CSAT (Paper II, qualifying)'),
    ('CAT', 'VARC (Verbal Ability and Reading Comprehension), DILR (Data Interpretation and Logical Reasoning) and QA (Quantitative Ability)'),
    ('GATE', 'General Aptitude and your engineering/science subject paper'),
    ('CBSE Class 10 boards', 'Mathematics, Science, Social Science, English and your second language'),
    ('CBSE Class 12 boards', 'your stream subjects and English'),
    ('SAT', 'Reading and Writing, and Math (digital, adaptive)'),
    ('IELTS Academic', 'Listening, Reading, Writing and Speaking'),
    ('GRE', 'Verbal Reasoning, Quantitative Reasoning and Analytical Writing'),
    ('GMAT Focus Edition', 'Quantitative Reasoning, Verbal Reasoning and Data Insights'),
    ('TOEFL iBT', 'Reading, Listening, Speaking and Writing'),
]


def study():
    for i, s in enumerate(SUBJECTS):
        add('Study & Exams', 'Any chatbot', 'text', 'Explain a %s concept simply' % s, 'Understand any %s topic with an analogy, a worked example and a quick check.' % s,
            f"""You are an expert {s} teacher. Explain [TOPIC] to a [CLASS / LEVEL] student.
1. Simple definition in one line.
2. Real-life analogy or example.
3. Step-by-step explanation (with formulas, dates, diagrams-in-words or rules as {s} needs).
4. One worked example or case.
5. 3 common mistakes or misconceptions.
6. A 3-question check — wait for my answers, then correct them.
Use simple English and stay accurate; if something depends on my syllabus or board, say so.""",
            f"A layered explanation of your {s} topic, from one-line definition to worked example, plus a quick interactive check.")
        add('Study & Exams', 'ChatGPT' if i % 2 else 'Claude', 'text', '%s practice questions with answers' % s, 'Exam-style %s questions at mixed difficulty, with answers and explanations.' % s,
            f"""Create practice questions on [CHAPTER / TOPIC] in {s} for [CLASS / EXAM].
- 5 multiple-choice, 3 short-answer and 2 long-answer questions, from easy to hard.
- Mark the difficulty of each.
- Put the answer key with step-by-step explanations at the END, after a line "--- ANSWERS ---", so I can attempt first.
- Match the style of [BOARD / EXAM] questions.""",
            "Ten mixed-format questions graded by difficulty, with a separate worked answer key so you can test yourself first.")
        add('Study & Exams', 'Any chatbot', 'text', '%s one-page revision notes' % s, 'Condense a %s chapter into a one-page sheet with key points, formulas and mnemonics.' % s,
            f"""Make one-page revision notes for {s}: [CHAPTER].
Include: key definitions, must-know facts or formulas, a small comparison table where useful, 2 mnemonics, a "most asked in exams" list, and a 60-second recap at the end.
Keep it scannable: headings, bullets, bold keywords. Be accurate; mark anything that varies by syllabus.""",
            "A scannable single-page sheet with definitions, formulas or facts, a comparison table, mnemonics and an exam-focus list.")
    for i, (ex, parts) in enumerate(EXAMS):
        add('Study & Exams', 'ChatGPT', 'text', '%s study plan' % ex, 'A realistic, week-by-week %s plan around your weak areas and free time.' % ex,
            f"""Make a study plan for {ex}. Sections: {parts}.
Exam date: [DATE]. Today: [DATE]. Hours per day: weekdays [X], weekends [Y].
Strong areas: [LIST]. Weak areas: [LIST]. Current mock score: [SCORE].
Include: weekly targets, daily schedule, spaced revision of weak topics, full-length mock tests with analysis days, and a final-week plan.
Output as a table (Week | Focus | Daily tasks | Mock/test) plus 5 rules to stay consistent.
Remind me to check the official {ex} website for the latest pattern and dates.""",
            f"A week-by-week {ex} table with daily tasks, spaced revision and mock-test days, plus consistency rules and a reminder to confirm the official pattern.")
        add('Study & Exams', 'Claude' if i % 2 else 'Any chatbot', 'text', '%s mock test and analysis' % ex, 'Take a short %s-style mock in chat and get a mistake analysis.' % ex,
            f"""Give me a short {ex}-style mock test on [SECTION / TOPIC] ({parts}).
- 10 questions in the real exam's style and difficulty. Ask them one by one and wait for my answer.
- After all 10: score, time-saving tips, and a mistake analysis grouped by concept gap, silly error or time pressure.
- Recommend the next 3 topics to practise.
Do not claim the questions are official.""",
            "An interactive ten-question mock with a score, mistake analysis by type and a short next-steps list.")


# ── Social Media: 8 platforms × 6 ──────────────────────────────────────────
PLATFORMS = [
    ('Instagram', 'Reels, carousels and stories; captions can be long but the first line must hook; use 3–5 relevant hashtags'),
    ('LinkedIn', 'text posts, documents (PDF carousels) and short videos; only the first 2–3 lines show before "see more"'),
    ('X (Twitter)', 'posts of up to 280 characters on standard accounts, threads, images and short video'),
    ('Facebook', 'posts, Reels, groups and events; community and local audiences respond well'),
    ('Pinterest', 'vertical 2:3 pins with a keyword-rich title (up to 100 characters) and description (up to 500 characters)'),
    ('Threads', 'conversational text posts of up to 500 characters, images and short video'),
    ('WhatsApp Business', 'status updates, broadcast lists (only to people who opted in), catalogues and quick replies'),
    ('YouTube Shorts', 'vertical short videos; the first 2 seconds decide if people keep watching'),
]


def social():
    for i, (P, fmt) in enumerate(PLATFORMS):
        tools = ['ChatGPT', 'Claude', 'Any chatbot']
        t = lambda k: tools[(i + k) % 3]  # noqa: E731
        add('Social Media', t(0), 'text', '30-day %s content calendar' % P, 'A month of %s post ideas with formats, hooks and a posting rhythm.' % P,
            f"""Create a 30-day {P} content calendar for [BRAND / CREATOR] in [NICHE].
Audience: [WHO]. Goal: [FOLLOWERS / LEADS / SALES]. I can post [X] times a week.
Platform notes: {fmt}.
Mix content pillars: education, behind the scenes, social proof, engagement, offers (max 20% promotional).
Table: Day | Pillar | Format | Hook / title | Key message | Call to action.""",
            f"A 30-row table balanced across content pillars and {P} formats, each with a hook and call to action, ready to copy into a planner.")
        add('Social Media', t(1), 'text', '%s captions that convert' % P, 'Five %s captions in different styles for one post, with a clear call to action.' % P,
            f"""Write 5 {P} captions for this post: [DESCRIBE THE POST / PRODUCT / OFFER].
Brand voice: [FUN / PREMIUM / FRIENDLY / EXPERT]. Audience: [WHO].
Styles: 1) story, 2) list, 3) question, 4) bold statement, 5) short and punchy.
Follow {P} norms: {fmt}.
Each caption: hook line, value, call to action. Add suggested hashtags or keywords only where the platform uses them.""",
            "Five distinct captions with hooks and calls to action that fit the platform's length and style.")
        add('Social Media', t(2), 'text', '%s hooks for the first line' % P, '20 scroll-stopping %s opening lines for your niche.' % P,
            f"""Give me 20 opening hooks for {P} posts about [TOPIC] for [AUDIENCE].
Mix: curiosity, contrarian, number/list, mistake, transformation, question.
Max 12 words each. Label each with its type. Avoid clickbait that the post cannot deliver.
Then pick the 3 strongest and explain why.""",
            "Twenty labelled hooks under twelve words, and the top three with reasons, to test against each other.")
        add('Social Media', t(0), 'text', 'Optimise your %s profile' % P, 'A %s profile audit with a new bio, name keywords and pinned-content plan.' % P,
            f"""Audit and rewrite my {P} profile.
Current bio/profile text: [PASTE]. Niche: [NICHE]. Goal: [GOAL]. Link: [LINK].
Give: 3 bio options within the platform's character limit, a searchable name/headline field, what to pin or feature first, and 5 quick fixes that build trust.
Platform notes: {fmt}.""",
            "Three bio options that fit the character limit, keyword ideas, a pinned-content plan and quick trust fixes.")
        add('Social Media', t(1), 'text', '%s %s outline' % (P, 'carousel' if P in ('Instagram', 'LinkedIn', 'Facebook') else ('thread' if P in ('X (Twitter)', 'Threads') else ('pin set' if P == 'Pinterest' else ('broadcast' if P == 'WhatsApp Business' else 'script')))),
            'Turn one idea into a ready-to-design %s post sequence.' % P,
            f"""Turn this idea into a {P} multi-part post: [IDEA / BLOG LINK / KEY POINTS].
Platform notes: {fmt}.
Give: a title/hook part, 6–8 value parts (one idea each, short sentences), and a final part with a call to action.
For each part add a short note on the visual or on-screen text.""",
            "A part-by-part outline with hook, value steps, call to action and visual notes, ready to design or record.")
        add('Social Media', t(2), 'text', '%s comment and DM replies' % P, 'Friendly, on-brand replies for common %s comments, questions and complaints.' % P,
            f"""Write reply templates for my {P} account ([BRAND], voice: [VOICE]).
Cases: thank-you comment, price question, "is this available?", shipping/delivery question, complaint, negative troll comment, collaboration request, and a lead asking for details.
For each: 2 short reply options that sound human, plus when to move the chat to DM or support.
Never promise things I have not listed: [POLICIES].""",
            "Two human-sounding replies for each common case, with guidance on when to move a conversation private.")


# ── Business: 20 industries × 4 ────────────────────────────────────────────
INDUSTRIES = ['café', 'bakery', 'clothing boutique', 'gym', 'beauty salon', 'dental clinic', 'real estate agency', 'coaching institute', 'SaaS startup', 'e-commerce store',
              'restaurant', 'travel agency', 'event management company', 'interior design studio', 'digital marketing agency', 'photography studio', 'organic farm brand', 'pet store', 'mobile repair shop', 'courier and logistics business']


def business():
    for i, ind in enumerate(INDUSTRIES):
        I = ind[0].upper() + ind[1:]
        tools = ['Claude', 'ChatGPT', 'Any chatbot', 'Gemini']
        t = lambda k: tools[(i + k) % 4]  # noqa: E731
        add('Business', t(0), 'text', 'One-page business plan for a %s' % ind, 'A lean, one-page plan for a %s: customers, offer, money and first steps.' % ind,
            f"""Write a one-page business plan for my {ind} in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.""",
            f"A lean one-page {ind} plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.")
        add('Business', t(1), 'text', '%s customer personas' % I, 'Three detailed buyer personas for a %s, with what makes them buy.' % ind,
            f"""Create 3 customer personas for a {ind} in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.""",
            "Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.")
        add('Business', t(2), 'text', '%s competitor analysis' % I, 'Compare your %s with local or online competitors and find a gap you can own.' % ind,
            f"""Help me analyse competitors for my {ind}.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.""",
            "A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.")
        add('Business', t(3), 'text', '90-day marketing plan for a %s' % ind, 'A practical 90-day plan to get the first or next 100 customers for a %s.' % ind,
            f"""Create a 90-day marketing plan for my {ind}.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.""",
            "A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.")


# ── Image Generation: 20 styles × 3 ────────────────────────────────────────
STYLES = [
    ('Cinematic photo', 'cinematic photograph, 35mm lens, shallow depth of field, dramatic rim light, film grain, color graded teal and orange'),
    ('Studio product photo', 'professional studio product photography, softbox lighting, seamless backdrop, crisp reflections, ultra sharp focus'),
    ('Watercolor', 'loose watercolor painting, soft pigment blooms, visible paper texture, gentle washes, white space'),
    ('Flat vector illustration', 'flat vector illustration, clean shapes, limited palette, subtle grain, modern editorial style'),
    ('Isometric 3D', 'isometric 3D illustration, soft global illumination, pastel palette, clean geometry, tiny details'),
    ('Clay 3D render', 'cute clay 3D render, soft rounded forms, matte texture, studio lighting, playful'),
    ('Anime', 'anime illustration, cel shading, expressive characters, vibrant colors, detailed background'),
    ('Pixel art', '16-bit pixel art, limited palette, crisp pixels, retro game style'),
    ('Line art', 'minimal black ink line art, single weight lines, lots of negative space'),
    ('Oil painting', 'classical oil painting, rich brushwork, warm chiaroscuro lighting, canvas texture'),
    ('Cyberpunk neon', 'cyberpunk scene, neon signs, rain-soaked streets, magenta and cyan lighting, volumetric fog'),
    ('Minimalist poster', 'minimalist poster design, bold geometric shapes, two-color palette, Swiss grid, lots of negative space'),
    ('Vintage 35mm film', 'vintage 35mm film photo, natural light, faded colors, light leaks, nostalgic mood'),
    ('Woodblock print', 'Japanese woodblock print inspired, flat colour areas, bold outlines, textured paper'),
    ('Low-poly', 'low-poly 3D art, faceted geometry, gradient lighting, clean background'),
    ('Paper cut-out', 'layered paper cut-out art, soft shadows between layers, handcrafted look'),
    ('Art Deco', 'Art Deco style, geometric patterns, gold and black, symmetrical composition, 1920s glamour'),
    ('Retro 70s', '1970s retro illustration, warm orange and brown palette, rounded type, sunburst shapes'),
    ('Double exposure', 'double exposure photograph, silhouette blended with landscape, monochrome with one accent color'),
    ('Macro photography', 'macro photograph, extreme close-up, tiny water droplets, creamy bokeh, ultra detailed textures'),
]
SUBJ = [
    ('product shot', 'Midjourney', '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS]', '--ar 4:5 --style raw --stylize 150'),
    ('portrait', 'Stable Diffusion', 'portrait of [PERSON: age, look, clothing, expression], [SETTING]', ''),
    ('scene or poster', 'ChatGPT Images', '[SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk]', ''),
]


def images():
    for i, (S, desc) in enumerate(STYLES):
        for j, (subj, tool, slot, params) in enumerate(SUBJ):
            if tool == 'Midjourney':
                prompt = f"""{slot}, {desc}, high detail, balanced composition {params}

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element."""
                out = f"A {S.lower()} image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary."
            elif tool == 'Stable Diffusion':
                prompt = f"""Positive prompt:
{slot}, {desc}, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait."""
                out = f"A {S.lower()} portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point."
            else:
                prompt = f"""Create an image: {slot}.
Style: {desc}.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE]."""
                out = f"A {S.lower()} scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English."
            add('Image Generation', tool, 'image', '%s %s' % (S, subj), 'A ready %s prompt for a %s in %s style.' % (tool, subj, S.lower()), prompt, out)


def main():
    coding()
    careers()
    dsa()
    study()
    social()
    business()
    images()
    for sec in prompts_hand.SECTIONS:
        cat, tool_default, kind_default, items = sec
        for it in items:
            title, summary, prompt, output = it[:4]
            tool = it[4] if len(it) > 4 else tool_default
            kind = it[5] if len(it) > 5 else kind_default
            add(cat, tool, kind, title, summary, prompt, output)
    print('total before trim', len(OUT))
    items = OUT[:TARGET]
    # ids
    seen = set()
    old = os.path.join(PLUGIN, 'includes', 'data', 'prompts.php')
    if not os.path.exists(old):
        old = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..', '..', 'plugins', 'vitalstack-ai-hub', 'includes', 'data', 'prompts.php')
    seen.update(re.findall(r"\$p\( '([a-z0-9-]+)'", open(old, encoding='utf-8').read()))
    assert len(seen) >= 40, 'existing prompt ids not found'
    for n, it in enumerate(items):
        base = re.sub(r'[^a-z0-9]+', '-', it['title'].lower()).strip('-')[:56].strip('-')
        sid = base
        k = 2
        while sid in seen:
            sid = '%s-%d' % (base[:52], k)
            k += 1
        seen.add(sid)
        it['id'] = sid
        it['premium'] = (n % 10) in (2, 5, 8)
    # checks
    for it in items:
        assert it['prompt'] and it['output'] and it['summary'] and it['title'], it
        assert len(it['id']) <= 60 and re.match(r'^[a-z0-9-]+$', it['id'])
    from collections import Counter
    print('items', len(items), 'pro', sum(i['premium'] for i in items))
    print(Counter(i['cat'] for i in items))

    def q(s):
        return "'" + s.replace('\\', '\\\\').replace("'", "\\'") + "'"
    lines = ["<?php", "/**", " * Prompt Bazaar: generated prompts (tools/aihub/gen_prompts.py).", " *", " * @package VitalStackAIHub", " */", "", "defined( 'ABSPATH' ) || exit;", "", "return array("]
    for it in items:
        lines.append("\tarray( 'id' => %s, 'title' => %s, 'cat' => %s, 'tool' => %s, 'kind' => %s, 'premium' => %s, 'summary' => %s, 'prompt' => %s, 'output' => %s, 'image' => '' )," % (
            q(it['id']), q(it['title']), q(it['cat']), q(it['tool']), q(it['kind']), 'true' if it['premium'] else 'false', q(it['summary']), q(it['prompt']), q(it['output'])))
    lines.append(");\n")
    path = os.path.join(PLUGIN, 'includes', 'data', 'prompts-gen.php')
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, 'w', encoding='utf-8') as fh:
        fh.write('\n'.join(lines))
    with open(os.path.join(os.path.dirname(os.path.abspath(__file__)), 'prompts-manifest.json'), 'w') as fh:
        json.dump([{k: it[k] for k in ('id', 'title', 'cat', 'tool', 'premium')} for it in items], fh, indent=0)


if __name__ == '__main__':
    main()
