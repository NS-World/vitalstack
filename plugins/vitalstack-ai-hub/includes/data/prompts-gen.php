<?php
/**
 * Prompt Bazaar: generated prompts (tools/aihub/gen_prompts.py).
 *
 * @package VitalStackAIHub
 */

defined( 'ABSPATH' ) || exit;

return array(
	array( 'id' => 'explain-python-code-line-by-line', 'title' => 'Explain Python code line by line', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand unfamiliar Python code: what it does, how it flows and where it can break.', 'prompt' => 'You are a senior Python developer and a patient teacher.
Explain the Python code below to a [BEGINNER / INTERMEDIATE] developer.
1. One-paragraph summary of what it does.
2. Walk through it block by block (quote the lines, then explain).
3. List the Python features or idioms it uses and why.
4. Point out bugs, edge cases or risky parts.
5. Suggest 2 small improvements that follow PEP 8 and type hints.

```python
[PASTE CODE]
```', 'output' => 'A plain-English summary, a block-by-block walkthrough with quoted lines, a list of Python idioms used, risks and edge cases, and two concrete improvements.', 'image' => '' ),
	array( 'id' => 'write-python-unit-tests', 'title' => 'Write Python unit tests', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Get a complete, runnable Python test file with edge cases.', 'prompt' => 'Write unit tests for the Python code below using pytest (with fixtures and parametrize).
- Cover the happy path, edge cases (empty, null/None, boundaries, large input) and error paths.
- One behaviour per test, descriptive test names.
- Mock external calls (network, database, time).
- Output one complete, runnable test file, then a table: test name | what it proves.

Code:
```python
[PASTE CODE]
```', 'output' => 'A runnable pytest test file with clear names and mocks, followed by a coverage table you can review in a minute.', 'image' => '' ),
	array( 'id' => 'refactor-python-code-for-readability', 'title' => 'Refactor Python code for readability', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => true, 'summary' => 'Clean up messy Python without changing behaviour, with each change explained.', 'prompt' => 'Refactor this Python code for readability and maintainability WITHOUT changing its behaviour.
Rules: follow PEP 8 and type hints; small functions with clear names; remove duplication; keep the public API the same.
Output:
1. The refactored code.
2. A numbered list of each change and why it helps.
3. Anything that needs a test before you would ship it.

```python
[PASTE CODE]
```', 'output' => 'Cleaner code with the same behaviour, a change log you can paste into a pull request, and a list of risky spots to test.', 'image' => '' ),
	array( 'id' => 'debug-a-python-error', 'title' => 'Debug a Python error', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => false, 'summary' => 'Paste the error and code; get the root cause, the fix and how to avoid it next time.', 'prompt' => 'I get this error in my Python project:
```
[PASTE FULL ERROR / STACK TRACE]
```
Relevant code:
```python
[PASTE CODE]
```
Environment: [Python VERSION, OS, FRAMEWORK/LIBRARY VERSIONS]
1. Explain the root cause in simple words.
2. Show the minimal fix as a diff.
3. Explain why the fix works.
4. Suggest how to prevent this class of bug (test, type, lint rule).
If information is missing, ask me up to 3 questions first.', 'output' => 'The likely root cause, a small diff, the reasoning, and one prevention step; or up to three precise questions if the error is ambiguous.', 'image' => '' ),
	array( 'id' => 'python-code-review-checklist', 'title' => 'Python code review checklist', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => false, 'summary' => 'A senior-style Python review: correctness, security, performance and style, ranked by severity.', 'prompt' => 'Act as a strict but kind senior Python reviewer. Review this code for:
- correctness and edge cases
- security (input validation, injection, secrets, unsafe APIs)
- performance and memory
- readability and PEP 8 and type hints
- tests that are missing
Rank findings as Blocker / Should fix / Nit. For each: line(s), problem, suggested fix (code).
End with a 2-line summary verdict.

```python
[PASTE CODE OR DIFF]
```', 'output' => 'A severity-ranked list of findings with line references and fix snippets, and a short verdict, like a real pull-request review.', 'image' => '' ),
	array( 'id' => 'convert-javascript-code-to-python', 'title' => 'Convert JavaScript code to Python', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => true, 'summary' => 'Translate code from JavaScript into idiomatic Python, not a word-for-word copy.', 'prompt' => 'Convert this JavaScript code into idiomatic Python.
- Use Python conventions (PEP 8 and type hints) and standard library equivalents.
- Keep the same behaviour; note any behaviour that cannot map 1:1.
- Add Python type information where the language supports it.
- Include a short usage example and the dependencies to install with pip / uv.

```javascript
[PASTE JAVASCRIPT CODE]
```', 'output' => 'Idiomatic Python code, a list of differences between the two languages that matter here, a usage example and the exact install command.', 'image' => '' ),
	array( 'id' => 'document-python-code', 'title' => 'Document Python code', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Add clear Python documentation comments and a README section.', 'prompt' => 'Add documentation to this Python code using Google-style docstrings (PEP 257).
- Document every public function/class: purpose, parameters, return value, errors raised, and one example.
- Do not change the code itself.
- Then write a README "Usage" section with installation (pip / uv) and 2 examples.

```python
[PASTE CODE]
```', 'output' => 'The same code with Google-style docstrings on every public item, plus a README usage section you can paste as-is.', 'image' => '' ),
	array( 'id' => 'speed-up-slow-python-code', 'title' => 'Speed up slow Python code', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Find the real bottlenecks in Python code and get faster versions with measurements.', 'prompt' => 'This Python code is too slow for [INPUT SIZE / USE CASE].
```python
[PASTE CODE]
```
1. Estimate the time and space complexity of the current code.
2. Identify the top 3 bottlenecks.
3. Give an optimised version and its new complexity.
4. Show how to measure the improvement with cProfile and line_profiler.
Do not sacrifice correctness or readability without saying so.', 'output' => 'Big-O analysis before and after, the main bottlenecks, an optimised version, and the exact profiling or benchmark steps to prove the speed-up.', 'image' => '' ),
	array( 'id' => 'guided-python-project-rest-api', 'title' => 'Guided Python project: REST API', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => true, 'summary' => 'A guided build of a REST API with FastAPI and Pydantic, with structure, code and tests.', 'prompt' => 'Guide me to build a REST API with FastAPI and Pydantic.
Feature: [DESCRIBE, e.g. a to-do list with create, list, update, delete].
1. Project structure (folders and files).
2. Setup commands using pip / uv.
3. The code for each file, in order, with short explanations.
4. Input validation and error handling.
5. Tests with pytest (with fixtures and parametrize).
6. How to run it locally and what to try next.
Keep it production-minded but beginner-friendly.', 'output' => 'A folder tree, setup commands, complete files in build order, validation, tests and run instructions: a small project you can finish in an evening.', 'image' => '' ),
	array( 'id' => 'explain-java-code-line-by-line', 'title' => 'Explain Java code line by line', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand unfamiliar Java code: what it does, how it flows and where it can break.', 'prompt' => 'You are a senior Java developer and a patient teacher.
Explain the Java code below to a [BEGINNER / INTERMEDIATE] developer.
1. One-paragraph summary of what it does.
2. Walk through it block by block (quote the lines, then explain).
3. List the Java features or idioms it uses and why.
4. Point out bugs, edge cases or risky parts.
5. Suggest 2 small improvements that follow Effective Java guidelines (Java 17+).

```java
[PASTE CODE]
```', 'output' => 'A plain-English summary, a block-by-block walkthrough with quoted lines, a list of Java idioms used, risks and edge cases, and two concrete improvements.', 'image' => '' ),
	array( 'id' => 'write-java-unit-tests', 'title' => 'Write Java unit tests', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Get a complete, runnable Java test file with edge cases.', 'prompt' => 'Write unit tests for the Java code below using JUnit 5 with Mockito.
- Cover the happy path, edge cases (empty, null/None, boundaries, large input) and error paths.
- One behaviour per test, descriptive test names.
- Mock external calls (network, database, time).
- Output one complete, runnable test file, then a table: test name | what it proves.

Code:
```java
[PASTE CODE]
```', 'output' => 'A runnable JUnit 5 with Mockito test file with clear names and mocks, followed by a coverage table you can review in a minute.', 'image' => '' ),
	array( 'id' => 'refactor-java-code-for-readability', 'title' => 'Refactor Java code for readability', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => false, 'summary' => 'Clean up messy Java without changing behaviour, with each change explained.', 'prompt' => 'Refactor this Java code for readability and maintainability WITHOUT changing its behaviour.
Rules: follow Effective Java guidelines (Java 17+); small functions with clear names; remove duplication; keep the public API the same.
Output:
1. The refactored code.
2. A numbered list of each change and why it helps.
3. Anything that needs a test before you would ship it.

```java
[PASTE CODE]
```', 'output' => 'Cleaner code with the same behaviour, a change log you can paste into a pull request, and a list of risky spots to test.', 'image' => '' ),
	array( 'id' => 'debug-a-java-error', 'title' => 'Debug a Java error', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => true, 'summary' => 'Paste the error and code; get the root cause, the fix and how to avoid it next time.', 'prompt' => 'I get this error in my Java project:
```
[PASTE FULL ERROR / STACK TRACE]
```
Relevant code:
```java
[PASTE CODE]
```
Environment: [Java VERSION, OS, FRAMEWORK/LIBRARY VERSIONS]
1. Explain the root cause in simple words.
2. Show the minimal fix as a diff.
3. Explain why the fix works.
4. Suggest how to prevent this class of bug (test, type, lint rule).
If information is missing, ask me up to 3 questions first.', 'output' => 'The likely root cause, a small diff, the reasoning, and one prevention step; or up to three precise questions if the error is ambiguous.', 'image' => '' ),
	array( 'id' => 'java-code-review-checklist', 'title' => 'Java code review checklist', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => false, 'summary' => 'A senior-style Java review: correctness, security, performance and style, ranked by severity.', 'prompt' => 'Act as a strict but kind senior Java reviewer. Review this code for:
- correctness and edge cases
- security (input validation, injection, secrets, unsafe APIs)
- performance and memory
- readability and Effective Java guidelines (Java 17+)
- tests that are missing
Rank findings as Blocker / Should fix / Nit. For each: line(s), problem, suggested fix (code).
End with a 2-line summary verdict.

```java
[PASTE CODE OR DIFF]
```', 'output' => 'A severity-ranked list of findings with line references and fix snippets, and a short verdict, like a real pull-request review.', 'image' => '' ),
	array( 'id' => 'convert-python-code-to-java', 'title' => 'Convert Python code to Java', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Translate code from Python into idiomatic Java, not a word-for-word copy.', 'prompt' => 'Convert this Python code into idiomatic Java.
- Use Java conventions (Effective Java guidelines (Java 17+)) and standard library equivalents.
- Keep the same behaviour; note any behaviour that cannot map 1:1.
- Add Java type information where the language supports it.
- Include a short usage example and the dependencies to install with Maven or Gradle.

```python
[PASTE PYTHON CODE]
```', 'output' => 'Idiomatic Java code, a list of differences between the two languages that matter here, a usage example and the exact install command.', 'image' => '' ),
	array( 'id' => 'document-java-code', 'title' => 'Document Java code', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'Add clear Java documentation comments and a README section.', 'prompt' => 'Add documentation to this Java code using Javadoc.
- Document every public function/class: purpose, parameters, return value, errors raised, and one example.
- Do not change the code itself.
- Then write a README "Usage" section with installation (Maven or Gradle) and 2 examples.

```java
[PASTE CODE]
```', 'output' => 'The same code with Javadoc on every public item, plus a README usage section you can paste as-is.', 'image' => '' ),
	array( 'id' => 'speed-up-slow-java-code', 'title' => 'Speed up slow Java code', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Find the real bottlenecks in Java code and get faster versions with measurements.', 'prompt' => 'This Java code is too slow for [INPUT SIZE / USE CASE].
```java
[PASTE CODE]
```
1. Estimate the time and space complexity of the current code.
2. Identify the top 3 bottlenecks.
3. Give an optimised version and its new complexity.
4. Show how to measure the improvement with JMH benchmarks and a profiler such as async-profiler or VisualVM.
Do not sacrifice correctness or readability without saying so.', 'output' => 'Big-O analysis before and after, the main bottlenecks, an optimised version, and the exact profiling or benchmark steps to prove the speed-up.', 'image' => '' ),
	array( 'id' => 'guided-java-project-rest-api', 'title' => 'Guided Java project: REST API', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => false, 'summary' => 'A guided build of a REST API with Spring Boot 3, Spring Data JPA and validation, with structure, code and tests.', 'prompt' => 'Guide me to build a REST API with Spring Boot 3, Spring Data JPA and validation.
Feature: [DESCRIBE, e.g. a to-do list with create, list, update, delete].
1. Project structure (folders and files).
2. Setup commands using Maven or Gradle.
3. The code for each file, in order, with short explanations.
4. Input validation and error handling.
5. Tests with JUnit 5 with Mockito.
6. How to run it locally and what to try next.
Keep it production-minded but beginner-friendly.', 'output' => 'A folder tree, setup commands, complete files in build order, validation, tests and run instructions: a small project you can finish in an evening.', 'image' => '' ),
	array( 'id' => 'explain-javascript-code-line-by-line', 'title' => 'Explain JavaScript code line by line', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => true, 'summary' => 'Understand unfamiliar JavaScript code: what it does, how it flows and where it can break.', 'prompt' => 'You are a senior JavaScript developer and a patient teacher.
Explain the JavaScript code below to a [BEGINNER / INTERMEDIATE] developer.
1. One-paragraph summary of what it does.
2. Walk through it block by block (quote the lines, then explain).
3. List the JavaScript features or idioms it uses and why.
4. Point out bugs, edge cases or risky parts.
5. Suggest 2 small improvements that follow modern ES2022+ (const/let, async/await, modules).

```javascript
[PASTE CODE]
```', 'output' => 'A plain-English summary, a block-by-block walkthrough with quoted lines, a list of JavaScript idioms used, risks and edge cases, and two concrete improvements.', 'image' => '' ),
	array( 'id' => 'write-javascript-unit-tests', 'title' => 'Write JavaScript unit tests', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => false, 'summary' => 'Get a complete, runnable JavaScript test file with edge cases.', 'prompt' => 'Write unit tests for the JavaScript code below using Jest (or Vitest).
- Cover the happy path, edge cases (empty, null/None, boundaries, large input) and error paths.
- One behaviour per test, descriptive test names.
- Mock external calls (network, database, time).
- Output one complete, runnable test file, then a table: test name | what it proves.

Code:
```javascript
[PASTE CODE]
```', 'output' => 'A runnable Jest test file with clear names and mocks, followed by a coverage table you can review in a minute.', 'image' => '' ),
	array( 'id' => 'refactor-javascript-code-for-readability', 'title' => 'Refactor JavaScript code for readability', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => false, 'summary' => 'Clean up messy JavaScript without changing behaviour, with each change explained.', 'prompt' => 'Refactor this JavaScript code for readability and maintainability WITHOUT changing its behaviour.
Rules: follow modern ES2022+ (const/let, async/await, modules); small functions with clear names; remove duplication; keep the public API the same.
Output:
1. The refactored code.
2. A numbered list of each change and why it helps.
3. Anything that needs a test before you would ship it.

```javascript
[PASTE CODE]
```', 'output' => 'Cleaner code with the same behaviour, a change log you can paste into a pull request, and a list of risky spots to test.', 'image' => '' ),
	array( 'id' => 'debug-a-javascript-error', 'title' => 'Debug a JavaScript error', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => false, 'summary' => 'Paste the error and code; get the root cause, the fix and how to avoid it next time.', 'prompt' => 'I get this error in my JavaScript project:
```
[PASTE FULL ERROR / STACK TRACE]
```
Relevant code:
```javascript
[PASTE CODE]
```
Environment: [JavaScript VERSION, OS, FRAMEWORK/LIBRARY VERSIONS]
1. Explain the root cause in simple words.
2. Show the minimal fix as a diff.
3. Explain why the fix works.
4. Suggest how to prevent this class of bug (test, type, lint rule).
If information is missing, ask me up to 3 questions first.', 'output' => 'The likely root cause, a small diff, the reasoning, and one prevention step; or up to three precise questions if the error is ambiguous.', 'image' => '' ),
	array( 'id' => 'javascript-code-review-checklist', 'title' => 'JavaScript code review checklist', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'A senior-style JavaScript review: correctness, security, performance and style, ranked by severity.', 'prompt' => 'Act as a strict but kind senior JavaScript reviewer. Review this code for:
- correctness and edge cases
- security (input validation, injection, secrets, unsafe APIs)
- performance and memory
- readability and modern ES2022+ (const/let, async/await, modules)
- tests that are missing
Rank findings as Blocker / Should fix / Nit. For each: line(s), problem, suggested fix (code).
End with a 2-line summary verdict.

```javascript
[PASTE CODE OR DIFF]
```', 'output' => 'A severity-ranked list of findings with line references and fix snippets, and a short verdict, like a real pull-request review.', 'image' => '' ),
	array( 'id' => 'convert-python-code-to-javascript', 'title' => 'Convert Python code to JavaScript', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Translate code from Python into idiomatic JavaScript, not a word-for-word copy.', 'prompt' => 'Convert this Python code into idiomatic JavaScript.
- Use JavaScript conventions (modern ES2022+ (const/let, async/await, modules)) and standard library equivalents.
- Keep the same behaviour; note any behaviour that cannot map 1:1.
- Add JavaScript type information where the language supports it.
- Include a short usage example and the dependencies to install with npm.

```python
[PASTE PYTHON CODE]
```', 'output' => 'Idiomatic JavaScript code, a list of differences between the two languages that matter here, a usage example and the exact install command.', 'image' => '' ),
	array( 'id' => 'document-javascript-code', 'title' => 'Document JavaScript code', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Add clear JavaScript documentation comments and a README section.', 'prompt' => 'Add documentation to this JavaScript code using JSDoc.
- Document every public function/class: purpose, parameters, return value, errors raised, and one example.
- Do not change the code itself.
- Then write a README "Usage" section with installation (npm) and 2 examples.

```javascript
[PASTE CODE]
```', 'output' => 'The same code with JSDoc on every public item, plus a README usage section you can paste as-is.', 'image' => '' ),
	array( 'id' => 'speed-up-slow-javascript-code', 'title' => 'Speed up slow JavaScript code', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => true, 'summary' => 'Find the real bottlenecks in JavaScript code and get faster versions with measurements.', 'prompt' => 'This JavaScript code is too slow for [INPUT SIZE / USE CASE].
```javascript
[PASTE CODE]
```
1. Estimate the time and space complexity of the current code.
2. Identify the top 3 bottlenecks.
3. Give an optimised version and its new complexity.
4. Show how to measure the improvement with Chrome DevTools Performance panel and node --prof.
Do not sacrifice correctness or readability without saying so.', 'output' => 'Big-O analysis before and after, the main bottlenecks, an optimised version, and the exact profiling or benchmark steps to prove the speed-up.', 'image' => '' ),
	array( 'id' => 'guided-javascript-project-rest-api', 'title' => 'Guided JavaScript project: REST API', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => false, 'summary' => 'A guided build of a REST API with Node.js and Express, with structure, code and tests.', 'prompt' => 'Guide me to build a REST API with Node.js and Express.
Feature: [DESCRIBE, e.g. a to-do list with create, list, update, delete].
1. Project structure (folders and files).
2. Setup commands using npm.
3. The code for each file, in order, with short explanations.
4. Input validation and error handling.
5. Tests with Jest (or Vitest).
6. How to run it locally and what to try next.
Keep it production-minded but beginner-friendly.', 'output' => 'A folder tree, setup commands, complete files in build order, validation, tests and run instructions: a small project you can finish in an evening.', 'image' => '' ),
	array( 'id' => 'explain-typescript-code-line-by-line', 'title' => 'Explain TypeScript code line by line', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand unfamiliar TypeScript code: what it does, how it flows and where it can break.', 'prompt' => 'You are a senior TypeScript developer and a patient teacher.
Explain the TypeScript code below to a [BEGINNER / INTERMEDIATE] developer.
1. One-paragraph summary of what it does.
2. Walk through it block by block (quote the lines, then explain).
3. List the TypeScript features or idioms it uses and why.
4. Point out bugs, edge cases or risky parts.
5. Suggest 2 small improvements that follow strict mode, no any, discriminated unions.

```typescript
[PASTE CODE]
```', 'output' => 'A plain-English summary, a block-by-block walkthrough with quoted lines, a list of TypeScript idioms used, risks and edge cases, and two concrete improvements.', 'image' => '' ),
	array( 'id' => 'write-typescript-unit-tests', 'title' => 'Write TypeScript unit tests', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => true, 'summary' => 'Get a complete, runnable TypeScript test file with edge cases.', 'prompt' => 'Write unit tests for the TypeScript code below using Vitest (or Jest with ts-jest).
- Cover the happy path, edge cases (empty, null/None, boundaries, large input) and error paths.
- One behaviour per test, descriptive test names.
- Mock external calls (network, database, time).
- Output one complete, runnable test file, then a table: test name | what it proves.

Code:
```typescript
[PASTE CODE]
```', 'output' => 'A runnable Vitest test file with clear names and mocks, followed by a coverage table you can review in a minute.', 'image' => '' ),
	array( 'id' => 'refactor-typescript-code-for-readability', 'title' => 'Refactor TypeScript code for readability', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => false, 'summary' => 'Clean up messy TypeScript without changing behaviour, with each change explained.', 'prompt' => 'Refactor this TypeScript code for readability and maintainability WITHOUT changing its behaviour.
Rules: follow strict mode, no any, discriminated unions; small functions with clear names; remove duplication; keep the public API the same.
Output:
1. The refactored code.
2. A numbered list of each change and why it helps.
3. Anything that needs a test before you would ship it.

```typescript
[PASTE CODE]
```', 'output' => 'Cleaner code with the same behaviour, a change log you can paste into a pull request, and a list of risky spots to test.', 'image' => '' ),
	array( 'id' => 'debug-a-typescript-error', 'title' => 'Debug a TypeScript error', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Paste the error and code; get the root cause, the fix and how to avoid it next time.', 'prompt' => 'I get this error in my TypeScript project:
```
[PASTE FULL ERROR / STACK TRACE]
```
Relevant code:
```typescript
[PASTE CODE]
```
Environment: [TypeScript VERSION, OS, FRAMEWORK/LIBRARY VERSIONS]
1. Explain the root cause in simple words.
2. Show the minimal fix as a diff.
3. Explain why the fix works.
4. Suggest how to prevent this class of bug (test, type, lint rule).
If information is missing, ask me up to 3 questions first.', 'output' => 'The likely root cause, a small diff, the reasoning, and one prevention step; or up to three precise questions if the error is ambiguous.', 'image' => '' ),
	array( 'id' => 'typescript-code-review-checklist', 'title' => 'TypeScript code review checklist', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A senior-style TypeScript review: correctness, security, performance and style, ranked by severity.', 'prompt' => 'Act as a strict but kind senior TypeScript reviewer. Review this code for:
- correctness and edge cases
- security (input validation, injection, secrets, unsafe APIs)
- performance and memory
- readability and strict mode, no any, discriminated unions
- tests that are missing
Rank findings as Blocker / Should fix / Nit. For each: line(s), problem, suggested fix (code).
End with a 2-line summary verdict.

```typescript
[PASTE CODE OR DIFF]
```', 'output' => 'A severity-ranked list of findings with line references and fix snippets, and a short verdict, like a real pull-request review.', 'image' => '' ),
	array( 'id' => 'convert-javascript-code-to-typescript', 'title' => 'Convert JavaScript code to TypeScript', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => true, 'summary' => 'Translate code from JavaScript into idiomatic TypeScript, not a word-for-word copy.', 'prompt' => 'Convert this JavaScript code into idiomatic TypeScript.
- Use TypeScript conventions (strict mode, no any, discriminated unions) and standard library equivalents.
- Keep the same behaviour; note any behaviour that cannot map 1:1.
- Add TypeScript type information where the language supports it.
- Include a short usage example and the dependencies to install with npm / pnpm.

```javascript
[PASTE JAVASCRIPT CODE]
```', 'output' => 'Idiomatic TypeScript code, a list of differences between the two languages that matter here, a usage example and the exact install command.', 'image' => '' ),
	array( 'id' => 'document-typescript-code', 'title' => 'Document TypeScript code', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => false, 'summary' => 'Add clear TypeScript documentation comments and a README section.', 'prompt' => 'Add documentation to this TypeScript code using TSDoc comments.
- Document every public function/class: purpose, parameters, return value, errors raised, and one example.
- Do not change the code itself.
- Then write a README "Usage" section with installation (npm / pnpm) and 2 examples.

```typescript
[PASTE CODE]
```', 'output' => 'The same code with TSDoc comments on every public item, plus a README usage section you can paste as-is.', 'image' => '' ),
	array( 'id' => 'speed-up-slow-typescript-code', 'title' => 'Speed up slow TypeScript code', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => false, 'summary' => 'Find the real bottlenecks in TypeScript code and get faster versions with measurements.', 'prompt' => 'This TypeScript code is too slow for [INPUT SIZE / USE CASE].
```typescript
[PASTE CODE]
```
1. Estimate the time and space complexity of the current code.
2. Identify the top 3 bottlenecks.
3. Give an optimised version and its new complexity.
4. Show how to measure the improvement with Chrome DevTools and clinic.js.
Do not sacrifice correctness or readability without saying so.', 'output' => 'Big-O analysis before and after, the main bottlenecks, an optimised version, and the exact profiling or benchmark steps to prove the speed-up.', 'image' => '' ),
	array( 'id' => 'guided-typescript-project-typed-rest-api', 'title' => 'Guided TypeScript project: typed REST API', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => true, 'summary' => 'A guided build of a typed REST API with Node.js, Express and Zod validation, with structure, code and tests.', 'prompt' => 'Guide me to build a typed REST API with Node.js, Express and Zod validation.
Feature: [DESCRIBE, e.g. a to-do list with create, list, update, delete].
1. Project structure (folders and files).
2. Setup commands using npm / pnpm.
3. The code for each file, in order, with short explanations.
4. Input validation and error handling.
5. Tests with Vitest (or Jest with ts-jest).
6. How to run it locally and what to try next.
Keep it production-minded but beginner-friendly.', 'output' => 'A folder tree, setup commands, complete files in build order, validation, tests and run instructions: a small project you can finish in an evening.', 'image' => '' ),
	array( 'id' => 'explain-c-code-line-by-line', 'title' => 'Explain C++ code line by line', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand unfamiliar C++ code: what it does, how it flows and where it can break.', 'prompt' => 'You are a senior C++ developer and a patient teacher.
Explain the C++ code below to a [BEGINNER / INTERMEDIATE] developer.
1. One-paragraph summary of what it does.
2. Walk through it block by block (quote the lines, then explain).
3. List the C++ features or idioms it uses and why.
4. Point out bugs, edge cases or risky parts.
5. Suggest 2 small improvements that follow modern C++17/20, RAII, no raw new/delete.

```c++
[PASTE CODE]
```', 'output' => 'A plain-English summary, a block-by-block walkthrough with quoted lines, a list of C++ idioms used, risks and edge cases, and two concrete improvements.', 'image' => '' ),
	array( 'id' => 'write-c-unit-tests', 'title' => 'Write C++ unit tests', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => false, 'summary' => 'Get a complete, runnable C++ test file with edge cases.', 'prompt' => 'Write unit tests for the C++ code below using GoogleTest.
- Cover the happy path, edge cases (empty, null/None, boundaries, large input) and error paths.
- One behaviour per test, descriptive test names.
- Mock external calls (network, database, time).
- Output one complete, runnable test file, then a table: test name | what it proves.

Code:
```c++
[PASTE CODE]
```', 'output' => 'A runnable GoogleTest test file with clear names and mocks, followed by a coverage table you can review in a minute.', 'image' => '' ),
	array( 'id' => 'refactor-c-code-for-readability', 'title' => 'Refactor C++ code for readability', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'Clean up messy C++ without changing behaviour, with each change explained.', 'prompt' => 'Refactor this C++ code for readability and maintainability WITHOUT changing its behaviour.
Rules: follow modern C++17/20, RAII, no raw new/delete; small functions with clear names; remove duplication; keep the public API the same.
Output:
1. The refactored code.
2. A numbered list of each change and why it helps.
3. Anything that needs a test before you would ship it.

```c++
[PASTE CODE]
```', 'output' => 'Cleaner code with the same behaviour, a change log you can paste into a pull request, and a list of risky spots to test.', 'image' => '' ),
	array( 'id' => 'debug-a-c-error', 'title' => 'Debug a C++ error', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Paste the error and code; get the root cause, the fix and how to avoid it next time.', 'prompt' => 'I get this error in my C++ project:
```
[PASTE FULL ERROR / STACK TRACE]
```
Relevant code:
```c++
[PASTE CODE]
```
Environment: [C++ VERSION, OS, FRAMEWORK/LIBRARY VERSIONS]
1. Explain the root cause in simple words.
2. Show the minimal fix as a diff.
3. Explain why the fix works.
4. Suggest how to prevent this class of bug (test, type, lint rule).
If information is missing, ask me up to 3 questions first.', 'output' => 'The likely root cause, a small diff, the reasoning, and one prevention step; or up to three precise questions if the error is ambiguous.', 'image' => '' ),
	array( 'id' => 'c-code-review-checklist', 'title' => 'C++ code review checklist', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'A senior-style C++ review: correctness, security, performance and style, ranked by severity.', 'prompt' => 'Act as a strict but kind senior C++ reviewer. Review this code for:
- correctness and edge cases
- security (input validation, injection, secrets, unsafe APIs)
- performance and memory
- readability and modern C++17/20, RAII, no raw new/delete
- tests that are missing
Rank findings as Blocker / Should fix / Nit. For each: line(s), problem, suggested fix (code).
End with a 2-line summary verdict.

```c++
[PASTE CODE OR DIFF]
```', 'output' => 'A severity-ranked list of findings with line references and fix snippets, and a short verdict, like a real pull-request review.', 'image' => '' ),
	array( 'id' => 'convert-python-code-to-c', 'title' => 'Convert Python code to C++', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => false, 'summary' => 'Translate code from Python into idiomatic C++, not a word-for-word copy.', 'prompt' => 'Convert this Python code into idiomatic C++.
- Use C++ conventions (modern C++17/20, RAII, no raw new/delete) and standard library equivalents.
- Keep the same behaviour; note any behaviour that cannot map 1:1.
- Add C++ type information where the language supports it.
- Include a short usage example and the dependencies to install with CMake (with vcpkg or Conan).

```python
[PASTE PYTHON CODE]
```', 'output' => 'Idiomatic C++ code, a list of differences between the two languages that matter here, a usage example and the exact install command.', 'image' => '' ),
	array( 'id' => 'document-c-code', 'title' => 'Document C++ code', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => true, 'summary' => 'Add clear C++ documentation comments and a README section.', 'prompt' => 'Add documentation to this C++ code using Doxygen comments.
- Document every public function/class: purpose, parameters, return value, errors raised, and one example.
- Do not change the code itself.
- Then write a README "Usage" section with installation (CMake (with vcpkg or Conan)) and 2 examples.

```c++
[PASTE CODE]
```', 'output' => 'The same code with Doxygen comments on every public item, plus a README usage section you can paste as-is.', 'image' => '' ),
	array( 'id' => 'speed-up-slow-c-code', 'title' => 'Speed up slow C++ code', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => false, 'summary' => 'Find the real bottlenecks in C++ code and get faster versions with measurements.', 'prompt' => 'This C++ code is too slow for [INPUT SIZE / USE CASE].
```c++
[PASTE CODE]
```
1. Estimate the time and space complexity of the current code.
2. Identify the top 3 bottlenecks.
3. Give an optimised version and its new complexity.
4. Show how to measure the improvement with perf, Valgrind (callgrind) and compiler optimisation reports.
Do not sacrifice correctness or readability without saying so.', 'output' => 'Big-O analysis before and after, the main bottlenecks, an optimised version, and the exact profiling or benchmark steps to prove the speed-up.', 'image' => '' ),
	array( 'id' => 'guided-c-project-command-line-tool', 'title' => 'Guided C++ project: command-line tool', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'A guided build of a command-line tool with CMake that parses arguments and reads a file, with structure, code and tests.', 'prompt' => 'Guide me to build a command-line tool with CMake that parses arguments and reads a file.
Feature: [DESCRIBE, e.g. a to-do list with create, list, update, delete].
1. Project structure (folders and files).
2. Setup commands using CMake (with vcpkg or Conan).
3. The code for each file, in order, with short explanations.
4. Input validation and error handling.
5. Tests with GoogleTest.
6. How to run it locally and what to try next.
Keep it production-minded but beginner-friendly.', 'output' => 'A folder tree, setup commands, complete files in build order, validation, tests and run instructions: a small project you can finish in an evening.', 'image' => '' ),
	array( 'id' => 'explain-c-code-line-by-line-2', 'title' => 'Explain C# code line by line', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => true, 'summary' => 'Understand unfamiliar C# code: what it does, how it flows and where it can break.', 'prompt' => 'You are a senior C# developer and a patient teacher.
Explain the C# code below to a [BEGINNER / INTERMEDIATE] developer.
1. One-paragraph summary of what it does.
2. Walk through it block by block (quote the lines, then explain).
3. List the C# features or idioms it uses and why.
4. Point out bugs, edge cases or risky parts.
5. Suggest 2 small improvements that follow .NET 8 conventions, nullable reference types on.

```c#
[PASTE CODE]
```', 'output' => 'A plain-English summary, a block-by-block walkthrough with quoted lines, a list of C# idioms used, risks and edge cases, and two concrete improvements.', 'image' => '' ),
	array( 'id' => 'write-c-unit-tests-2', 'title' => 'Write C# unit tests', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Get a complete, runnable C# test file with edge cases.', 'prompt' => 'Write unit tests for the C# code below using xUnit with Moq.
- Cover the happy path, edge cases (empty, null/None, boundaries, large input) and error paths.
- One behaviour per test, descriptive test names.
- Mock external calls (network, database, time).
- Output one complete, runnable test file, then a table: test name | what it proves.

Code:
```c#
[PASTE CODE]
```', 'output' => 'A runnable xUnit with Moq test file with clear names and mocks, followed by a coverage table you can review in a minute.', 'image' => '' ),
	array( 'id' => 'refactor-c-code-for-readability-2', 'title' => 'Refactor C# code for readability', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Clean up messy C# without changing behaviour, with each change explained.', 'prompt' => 'Refactor this C# code for readability and maintainability WITHOUT changing its behaviour.
Rules: follow .NET 8 conventions, nullable reference types on; small functions with clear names; remove duplication; keep the public API the same.
Output:
1. The refactored code.
2. A numbered list of each change and why it helps.
3. Anything that needs a test before you would ship it.

```c#
[PASTE CODE]
```', 'output' => 'Cleaner code with the same behaviour, a change log you can paste into a pull request, and a list of risky spots to test.', 'image' => '' ),
	array( 'id' => 'debug-a-c-error-2', 'title' => 'Debug a C# error', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => true, 'summary' => 'Paste the error and code; get the root cause, the fix and how to avoid it next time.', 'prompt' => 'I get this error in my C# project:
```
[PASTE FULL ERROR / STACK TRACE]
```
Relevant code:
```c#
[PASTE CODE]
```
Environment: [C# VERSION, OS, FRAMEWORK/LIBRARY VERSIONS]
1. Explain the root cause in simple words.
2. Show the minimal fix as a diff.
3. Explain why the fix works.
4. Suggest how to prevent this class of bug (test, type, lint rule).
If information is missing, ask me up to 3 questions first.', 'output' => 'The likely root cause, a small diff, the reasoning, and one prevention step; or up to three precise questions if the error is ambiguous.', 'image' => '' ),
	array( 'id' => 'c-code-review-checklist-2', 'title' => 'C# code review checklist', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => false, 'summary' => 'A senior-style C# review: correctness, security, performance and style, ranked by severity.', 'prompt' => 'Act as a strict but kind senior C# reviewer. Review this code for:
- correctness and edge cases
- security (input validation, injection, secrets, unsafe APIs)
- performance and memory
- readability and .NET 8 conventions, nullable reference types on
- tests that are missing
Rank findings as Blocker / Should fix / Nit. For each: line(s), problem, suggested fix (code).
End with a 2-line summary verdict.

```c#
[PASTE CODE OR DIFF]
```', 'output' => 'A severity-ranked list of findings with line references and fix snippets, and a short verdict, like a real pull-request review.', 'image' => '' ),
	array( 'id' => 'convert-java-code-to-c', 'title' => 'Convert Java code to C#', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => false, 'summary' => 'Translate code from Java into idiomatic C#, not a word-for-word copy.', 'prompt' => 'Convert this Java code into idiomatic C#.
- Use C# conventions (.NET 8 conventions, nullable reference types on) and standard library equivalents.
- Keep the same behaviour; note any behaviour that cannot map 1:1.
- Add C# type information where the language supports it.
- Include a short usage example and the dependencies to install with NuGet / dotnet CLI.

```java
[PASTE JAVA CODE]
```', 'output' => 'Idiomatic C# code, a list of differences between the two languages that matter here, a usage example and the exact install command.', 'image' => '' ),
	array( 'id' => 'document-c-code-2', 'title' => 'Document C# code', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => false, 'summary' => 'Add clear C# documentation comments and a README section.', 'prompt' => 'Add documentation to this C# code using XML documentation comments.
- Document every public function/class: purpose, parameters, return value, errors raised, and one example.
- Do not change the code itself.
- Then write a README "Usage" section with installation (NuGet / dotnet CLI) and 2 examples.

```c#
[PASTE CODE]
```', 'output' => 'The same code with XML documentation comments on every public item, plus a README usage section you can paste as-is.', 'image' => '' ),
	array( 'id' => 'speed-up-slow-c-code-2', 'title' => 'Speed up slow C# code', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'Find the real bottlenecks in C# code and get faster versions with measurements.', 'prompt' => 'This C# code is too slow for [INPUT SIZE / USE CASE].
```c#
[PASTE CODE]
```
1. Estimate the time and space complexity of the current code.
2. Identify the top 3 bottlenecks.
3. Give an optimised version and its new complexity.
4. Show how to measure the improvement with BenchmarkDotNet and dotnet-trace.
Do not sacrifice correctness or readability without saying so.', 'output' => 'Big-O analysis before and after, the main bottlenecks, an optimised version, and the exact profiling or benchmark steps to prove the speed-up.', 'image' => '' ),
	array( 'id' => 'guided-c-project-minimal-api', 'title' => 'Guided C# project: minimal API', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A guided build of a minimal API with ASP.NET Core and Entity Framework Core, with structure, code and tests.', 'prompt' => 'Guide me to build a minimal API with ASP.NET Core and Entity Framework Core.
Feature: [DESCRIBE, e.g. a to-do list with create, list, update, delete].
1. Project structure (folders and files).
2. Setup commands using NuGet / dotnet CLI.
3. The code for each file, in order, with short explanations.
4. Input validation and error handling.
5. Tests with xUnit with Moq.
6. How to run it locally and what to try next.
Keep it production-minded but beginner-friendly.', 'output' => 'A folder tree, setup commands, complete files in build order, validation, tests and run instructions: a small project you can finish in an evening.', 'image' => '' ),
	array( 'id' => 'explain-go-code-line-by-line', 'title' => 'Explain Go code line by line', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand unfamiliar Go code: what it does, how it flows and where it can break.', 'prompt' => 'You are a senior Go developer and a patient teacher.
Explain the Go code below to a [BEGINNER / INTERMEDIATE] developer.
1. One-paragraph summary of what it does.
2. Walk through it block by block (quote the lines, then explain).
3. List the Go features or idioms it uses and why.
4. Point out bugs, edge cases or risky parts.
5. Suggest 2 small improvements that follow Effective Go, errors as values, small interfaces.

```go
[PASTE CODE]
```', 'output' => 'A plain-English summary, a block-by-block walkthrough with quoted lines, a list of Go idioms used, risks and edge cases, and two concrete improvements.', 'image' => '' ),
	array( 'id' => 'write-go-unit-tests', 'title' => 'Write Go unit tests', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'Get a complete, runnable Go test file with edge cases.', 'prompt' => 'Write unit tests for the Go code below using the standard testing package with table-driven tests.
- Cover the happy path, edge cases (empty, null/None, boundaries, large input) and error paths.
- One behaviour per test, descriptive test names.
- Mock external calls (network, database, time).
- Output one complete, runnable test file, then a table: test name | what it proves.

Code:
```go
[PASTE CODE]
```', 'output' => 'A runnable the standard testing package with table-driven tests test file with clear names and mocks, followed by a coverage table you can review in a minute.', 'image' => '' ),
	array( 'id' => 'refactor-go-code-for-readability', 'title' => 'Refactor Go code for readability', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Clean up messy Go without changing behaviour, with each change explained.', 'prompt' => 'Refactor this Go code for readability and maintainability WITHOUT changing its behaviour.
Rules: follow Effective Go, errors as values, small interfaces; small functions with clear names; remove duplication; keep the public API the same.
Output:
1. The refactored code.
2. A numbered list of each change and why it helps.
3. Anything that needs a test before you would ship it.

```go
[PASTE CODE]
```', 'output' => 'Cleaner code with the same behaviour, a change log you can paste into a pull request, and a list of risky spots to test.', 'image' => '' ),
	array( 'id' => 'debug-a-go-error', 'title' => 'Debug a Go error', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => false, 'summary' => 'Paste the error and code; get the root cause, the fix and how to avoid it next time.', 'prompt' => 'I get this error in my Go project:
```
[PASTE FULL ERROR / STACK TRACE]
```
Relevant code:
```go
[PASTE CODE]
```
Environment: [Go VERSION, OS, FRAMEWORK/LIBRARY VERSIONS]
1. Explain the root cause in simple words.
2. Show the minimal fix as a diff.
3. Explain why the fix works.
4. Suggest how to prevent this class of bug (test, type, lint rule).
If information is missing, ask me up to 3 questions first.', 'output' => 'The likely root cause, a small diff, the reasoning, and one prevention step; or up to three precise questions if the error is ambiguous.', 'image' => '' ),
	array( 'id' => 'go-code-review-checklist', 'title' => 'Go code review checklist', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => true, 'summary' => 'A senior-style Go review: correctness, security, performance and style, ranked by severity.', 'prompt' => 'Act as a strict but kind senior Go reviewer. Review this code for:
- correctness and edge cases
- security (input validation, injection, secrets, unsafe APIs)
- performance and memory
- readability and Effective Go, errors as values, small interfaces
- tests that are missing
Rank findings as Blocker / Should fix / Nit. For each: line(s), problem, suggested fix (code).
End with a 2-line summary verdict.

```go
[PASTE CODE OR DIFF]
```', 'output' => 'A severity-ranked list of findings with line references and fix snippets, and a short verdict, like a real pull-request review.', 'image' => '' ),
	array( 'id' => 'convert-python-code-to-go', 'title' => 'Convert Python code to Go', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => false, 'summary' => 'Translate code from Python into idiomatic Go, not a word-for-word copy.', 'prompt' => 'Convert this Python code into idiomatic Go.
- Use Go conventions (Effective Go, errors as values, small interfaces) and standard library equivalents.
- Keep the same behaviour; note any behaviour that cannot map 1:1.
- Add Go type information where the language supports it.
- Include a short usage example and the dependencies to install with Go modules.

```python
[PASTE PYTHON CODE]
```', 'output' => 'Idiomatic Go code, a list of differences between the two languages that matter here, a usage example and the exact install command.', 'image' => '' ),
	array( 'id' => 'document-go-code', 'title' => 'Document Go code', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Add clear Go documentation comments and a README section.', 'prompt' => 'Add documentation to this Go code using Go doc comments.
- Document every public function/class: purpose, parameters, return value, errors raised, and one example.
- Do not change the code itself.
- Then write a README "Usage" section with installation (Go modules) and 2 examples.

```go
[PASTE CODE]
```', 'output' => 'The same code with Go doc comments on every public item, plus a README usage section you can paste as-is.', 'image' => '' ),
	array( 'id' => 'speed-up-slow-go-code', 'title' => 'Speed up slow Go code', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Find the real bottlenecks in Go code and get faster versions with measurements.', 'prompt' => 'This Go code is too slow for [INPUT SIZE / USE CASE].
```go
[PASTE CODE]
```
1. Estimate the time and space complexity of the current code.
2. Identify the top 3 bottlenecks.
3. Give an optimised version and its new complexity.
4. Show how to measure the improvement with pprof and go test -bench.
Do not sacrifice correctness or readability without saying so.', 'output' => 'Big-O analysis before and after, the main bottlenecks, an optimised version, and the exact profiling or benchmark steps to prove the speed-up.', 'image' => '' ),
	array( 'id' => 'guided-go-project-rest-api', 'title' => 'Guided Go project: REST API', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => true, 'summary' => 'A guided build of a REST API with net/http (Go 1.22 routing patterns), with structure, code and tests.', 'prompt' => 'Guide me to build a REST API with net/http (Go 1.22 routing patterns).
Feature: [DESCRIBE, e.g. a to-do list with create, list, update, delete].
1. Project structure (folders and files).
2. Setup commands using Go modules.
3. The code for each file, in order, with short explanations.
4. Input validation and error handling.
5. Tests with the standard testing package with table-driven tests.
6. How to run it locally and what to try next.
Keep it production-minded but beginner-friendly.', 'output' => 'A folder tree, setup commands, complete files in build order, validation, tests and run instructions: a small project you can finish in an evening.', 'image' => '' ),
	array( 'id' => 'explain-rust-code-line-by-line', 'title' => 'Explain Rust code line by line', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand unfamiliar Rust code: what it does, how it flows and where it can break.', 'prompt' => 'You are a senior Rust developer and a patient teacher.
Explain the Rust code below to a [BEGINNER / INTERMEDIATE] developer.
1. One-paragraph summary of what it does.
2. Walk through it block by block (quote the lines, then explain).
3. List the Rust features or idioms it uses and why.
4. Point out bugs, edge cases or risky parts.
5. Suggest 2 small improvements that follow idiomatic ownership and borrowing, Result-based errors.

```rust
[PASTE CODE]
```', 'output' => 'A plain-English summary, a block-by-block walkthrough with quoted lines, a list of Rust idioms used, risks and edge cases, and two concrete improvements.', 'image' => '' ),
	array( 'id' => 'write-rust-unit-tests', 'title' => 'Write Rust unit tests', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Get a complete, runnable Rust test file with edge cases.', 'prompt' => 'Write unit tests for the Rust code below using built-in #[test] functions run with cargo test.
- Cover the happy path, edge cases (empty, null/None, boundaries, large input) and error paths.
- One behaviour per test, descriptive test names.
- Mock external calls (network, database, time).
- Output one complete, runnable test file, then a table: test name | what it proves.

Code:
```rust
[PASTE CODE]
```', 'output' => 'A runnable built-in #[test] functions run with cargo test test file with clear names and mocks, followed by a coverage table you can review in a minute.', 'image' => '' ),
	array( 'id' => 'refactor-rust-code-for-readability', 'title' => 'Refactor Rust code for readability', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => true, 'summary' => 'Clean up messy Rust without changing behaviour, with each change explained.', 'prompt' => 'Refactor this Rust code for readability and maintainability WITHOUT changing its behaviour.
Rules: follow idiomatic ownership and borrowing, Result-based errors; small functions with clear names; remove duplication; keep the public API the same.
Output:
1. The refactored code.
2. A numbered list of each change and why it helps.
3. Anything that needs a test before you would ship it.

```rust
[PASTE CODE]
```', 'output' => 'Cleaner code with the same behaviour, a change log you can paste into a pull request, and a list of risky spots to test.', 'image' => '' ),
	array( 'id' => 'debug-a-rust-error', 'title' => 'Debug a Rust error', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => false, 'summary' => 'Paste the error and code; get the root cause, the fix and how to avoid it next time.', 'prompt' => 'I get this error in my Rust project:
```
[PASTE FULL ERROR / STACK TRACE]
```
Relevant code:
```rust
[PASTE CODE]
```
Environment: [Rust VERSION, OS, FRAMEWORK/LIBRARY VERSIONS]
1. Explain the root cause in simple words.
2. Show the minimal fix as a diff.
3. Explain why the fix works.
4. Suggest how to prevent this class of bug (test, type, lint rule).
If information is missing, ask me up to 3 questions first.', 'output' => 'The likely root cause, a small diff, the reasoning, and one prevention step; or up to three precise questions if the error is ambiguous.', 'image' => '' ),
	array( 'id' => 'rust-code-review-checklist', 'title' => 'Rust code review checklist', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => false, 'summary' => 'A senior-style Rust review: correctness, security, performance and style, ranked by severity.', 'prompt' => 'Act as a strict but kind senior Rust reviewer. Review this code for:
- correctness and edge cases
- security (input validation, injection, secrets, unsafe APIs)
- performance and memory
- readability and idiomatic ownership and borrowing, Result-based errors
- tests that are missing
Rank findings as Blocker / Should fix / Nit. For each: line(s), problem, suggested fix (code).
End with a 2-line summary verdict.

```rust
[PASTE CODE OR DIFF]
```', 'output' => 'A severity-ranked list of findings with line references and fix snippets, and a short verdict, like a real pull-request review.', 'image' => '' ),
	array( 'id' => 'convert-python-code-to-rust', 'title' => 'Convert Python code to Rust', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'Translate code from Python into idiomatic Rust, not a word-for-word copy.', 'prompt' => 'Convert this Python code into idiomatic Rust.
- Use Rust conventions (idiomatic ownership and borrowing, Result-based errors) and standard library equivalents.
- Keep the same behaviour; note any behaviour that cannot map 1:1.
- Add Rust type information where the language supports it.
- Include a short usage example and the dependencies to install with Cargo.

```python
[PASTE PYTHON CODE]
```', 'output' => 'Idiomatic Rust code, a list of differences between the two languages that matter here, a usage example and the exact install command.', 'image' => '' ),
	array( 'id' => 'document-rust-code', 'title' => 'Document Rust code', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Add clear Rust documentation comments and a README section.', 'prompt' => 'Add documentation to this Rust code using rustdoc /// comments with examples.
- Document every public function/class: purpose, parameters, return value, errors raised, and one example.
- Do not change the code itself.
- Then write a README "Usage" section with installation (Cargo) and 2 examples.

```rust
[PASTE CODE]
```', 'output' => 'The same code with rustdoc /// comments with examples on every public item, plus a README usage section you can paste as-is.', 'image' => '' ),
	array( 'id' => 'speed-up-slow-rust-code', 'title' => 'Speed up slow Rust code', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Find the real bottlenecks in Rust code and get faster versions with measurements.', 'prompt' => 'This Rust code is too slow for [INPUT SIZE / USE CASE].
```rust
[PASTE CODE]
```
1. Estimate the time and space complexity of the current code.
2. Identify the top 3 bottlenecks.
3. Give an optimised version and its new complexity.
4. Show how to measure the improvement with cargo bench (criterion) and flamegraph.
Do not sacrifice correctness or readability without saying so.', 'output' => 'Big-O analysis before and after, the main bottlenecks, an optimised version, and the exact profiling or benchmark steps to prove the speed-up.', 'image' => '' ),
	array( 'id' => 'guided-rust-project-rest-api', 'title' => 'Guided Rust project: REST API', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => false, 'summary' => 'A guided build of a REST API with axum and tokio, with structure, code and tests.', 'prompt' => 'Guide me to build a REST API with axum and tokio.
Feature: [DESCRIBE, e.g. a to-do list with create, list, update, delete].
1. Project structure (folders and files).
2. Setup commands using Cargo.
3. The code for each file, in order, with short explanations.
4. Input validation and error handling.
5. Tests with built-in #[test] functions run with cargo test.
6. How to run it locally and what to try next.
Keep it production-minded but beginner-friendly.', 'output' => 'A folder tree, setup commands, complete files in build order, validation, tests and run instructions: a small project you can finish in an evening.', 'image' => '' ),
	array( 'id' => 'explain-kotlin-code-line-by-line', 'title' => 'Explain Kotlin code line by line', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => true, 'summary' => 'Understand unfamiliar Kotlin code: what it does, how it flows and where it can break.', 'prompt' => 'You are a senior Kotlin developer and a patient teacher.
Explain the Kotlin code below to a [BEGINNER / INTERMEDIATE] developer.
1. One-paragraph summary of what it does.
2. Walk through it block by block (quote the lines, then explain).
3. List the Kotlin features or idioms it uses and why.
4. Point out bugs, edge cases or risky parts.
5. Suggest 2 small improvements that follow null safety, data classes, coroutines.

```kotlin
[PASTE CODE]
```', 'output' => 'A plain-English summary, a block-by-block walkthrough with quoted lines, a list of Kotlin idioms used, risks and edge cases, and two concrete improvements.', 'image' => '' ),
	array( 'id' => 'write-kotlin-unit-tests', 'title' => 'Write Kotlin unit tests', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => false, 'summary' => 'Get a complete, runnable Kotlin test file with edge cases.', 'prompt' => 'Write unit tests for the Kotlin code below using JUnit 5 with MockK (or Kotest).
- Cover the happy path, edge cases (empty, null/None, boundaries, large input) and error paths.
- One behaviour per test, descriptive test names.
- Mock external calls (network, database, time).
- Output one complete, runnable test file, then a table: test name | what it proves.

Code:
```kotlin
[PASTE CODE]
```', 'output' => 'A runnable JUnit 5 with MockK test file with clear names and mocks, followed by a coverage table you can review in a minute.', 'image' => '' ),
	array( 'id' => 'refactor-kotlin-code-for-readability', 'title' => 'Refactor Kotlin code for readability', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => false, 'summary' => 'Clean up messy Kotlin without changing behaviour, with each change explained.', 'prompt' => 'Refactor this Kotlin code for readability and maintainability WITHOUT changing its behaviour.
Rules: follow null safety, data classes, coroutines; small functions with clear names; remove duplication; keep the public API the same.
Output:
1. The refactored code.
2. A numbered list of each change and why it helps.
3. Anything that needs a test before you would ship it.

```kotlin
[PASTE CODE]
```', 'output' => 'Cleaner code with the same behaviour, a change log you can paste into a pull request, and a list of risky spots to test.', 'image' => '' ),
	array( 'id' => 'debug-a-kotlin-error', 'title' => 'Debug a Kotlin error', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => true, 'summary' => 'Paste the error and code; get the root cause, the fix and how to avoid it next time.', 'prompt' => 'I get this error in my Kotlin project:
```
[PASTE FULL ERROR / STACK TRACE]
```
Relevant code:
```kotlin
[PASTE CODE]
```
Environment: [Kotlin VERSION, OS, FRAMEWORK/LIBRARY VERSIONS]
1. Explain the root cause in simple words.
2. Show the minimal fix as a diff.
3. Explain why the fix works.
4. Suggest how to prevent this class of bug (test, type, lint rule).
If information is missing, ask me up to 3 questions first.', 'output' => 'The likely root cause, a small diff, the reasoning, and one prevention step; or up to three precise questions if the error is ambiguous.', 'image' => '' ),
	array( 'id' => 'kotlin-code-review-checklist', 'title' => 'Kotlin code review checklist', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'A senior-style Kotlin review: correctness, security, performance and style, ranked by severity.', 'prompt' => 'Act as a strict but kind senior Kotlin reviewer. Review this code for:
- correctness and edge cases
- security (input validation, injection, secrets, unsafe APIs)
- performance and memory
- readability and null safety, data classes, coroutines
- tests that are missing
Rank findings as Blocker / Should fix / Nit. For each: line(s), problem, suggested fix (code).
End with a 2-line summary verdict.

```kotlin
[PASTE CODE OR DIFF]
```', 'output' => 'A severity-ranked list of findings with line references and fix snippets, and a short verdict, like a real pull-request review.', 'image' => '' ),
	array( 'id' => 'convert-java-code-to-kotlin', 'title' => 'Convert Java code to Kotlin', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Translate code from Java into idiomatic Kotlin, not a word-for-word copy.', 'prompt' => 'Convert this Java code into idiomatic Kotlin.
- Use Kotlin conventions (null safety, data classes, coroutines) and standard library equivalents.
- Keep the same behaviour; note any behaviour that cannot map 1:1.
- Add Kotlin type information where the language supports it.
- Include a short usage example and the dependencies to install with Gradle (Kotlin DSL).

```java
[PASTE JAVA CODE]
```', 'output' => 'Idiomatic Kotlin code, a list of differences between the two languages that matter here, a usage example and the exact install command.', 'image' => '' ),
	array( 'id' => 'document-kotlin-code', 'title' => 'Document Kotlin code', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => true, 'summary' => 'Add clear Kotlin documentation comments and a README section.', 'prompt' => 'Add documentation to this Kotlin code using KDoc.
- Document every public function/class: purpose, parameters, return value, errors raised, and one example.
- Do not change the code itself.
- Then write a README "Usage" section with installation (Gradle (Kotlin DSL)) and 2 examples.

```kotlin
[PASTE CODE]
```', 'output' => 'The same code with KDoc on every public item, plus a README usage section you can paste as-is.', 'image' => '' ),
	array( 'id' => 'speed-up-slow-kotlin-code', 'title' => 'Speed up slow Kotlin code', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => false, 'summary' => 'Find the real bottlenecks in Kotlin code and get faster versions with measurements.', 'prompt' => 'This Kotlin code is too slow for [INPUT SIZE / USE CASE].
```kotlin
[PASTE CODE]
```
1. Estimate the time and space complexity of the current code.
2. Identify the top 3 bottlenecks.
3. Give an optimised version and its new complexity.
4. Show how to measure the improvement with JMH via kotlinx-benchmark and Android Studio / IntelliJ profilers.
Do not sacrifice correctness or readability without saying so.', 'output' => 'Big-O analysis before and after, the main bottlenecks, an optimised version, and the exact profiling or benchmark steps to prove the speed-up.', 'image' => '' ),
	array( 'id' => 'guided-kotlin-project-rest-api', 'title' => 'Guided Kotlin project: REST API', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => false, 'summary' => 'A guided build of a REST API with Ktor, with structure, code and tests.', 'prompt' => 'Guide me to build a REST API with Ktor.
Feature: [DESCRIBE, e.g. a to-do list with create, list, update, delete].
1. Project structure (folders and files).
2. Setup commands using Gradle (Kotlin DSL).
3. The code for each file, in order, with short explanations.
4. Input validation and error handling.
5. Tests with JUnit 5 with MockK (or Kotest).
6. How to run it locally and what to try next.
Keep it production-minded but beginner-friendly.', 'output' => 'A folder tree, setup commands, complete files in build order, validation, tests and run instructions: a small project you can finish in an evening.', 'image' => '' ),
	array( 'id' => 'explain-swift-code-line-by-line', 'title' => 'Explain Swift code line by line', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand unfamiliar Swift code: what it does, how it flows and where it can break.', 'prompt' => 'You are a senior Swift developer and a patient teacher.
Explain the Swift code below to a [BEGINNER / INTERMEDIATE] developer.
1. One-paragraph summary of what it does.
2. Walk through it block by block (quote the lines, then explain).
3. List the Swift features or idioms it uses and why.
4. Point out bugs, edge cases or risky parts.
5. Suggest 2 small improvements that follow value types, optionals handled safely, async/await.

```swift
[PASTE CODE]
```', 'output' => 'A plain-English summary, a block-by-block walkthrough with quoted lines, a list of Swift idioms used, risks and edge cases, and two concrete improvements.', 'image' => '' ),
	array( 'id' => 'write-swift-unit-tests', 'title' => 'Write Swift unit tests', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => true, 'summary' => 'Get a complete, runnable Swift test file with edge cases.', 'prompt' => 'Write unit tests for the Swift code below using XCTest (or the Swift Testing framework).
- Cover the happy path, edge cases (empty, null/None, boundaries, large input) and error paths.
- One behaviour per test, descriptive test names.
- Mock external calls (network, database, time).
- Output one complete, runnable test file, then a table: test name | what it proves.

Code:
```swift
[PASTE CODE]
```', 'output' => 'A runnable XCTest test file with clear names and mocks, followed by a coverage table you can review in a minute.', 'image' => '' ),
	array( 'id' => 'refactor-swift-code-for-readability', 'title' => 'Refactor Swift code for readability', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => false, 'summary' => 'Clean up messy Swift without changing behaviour, with each change explained.', 'prompt' => 'Refactor this Swift code for readability and maintainability WITHOUT changing its behaviour.
Rules: follow value types, optionals handled safely, async/await; small functions with clear names; remove duplication; keep the public API the same.
Output:
1. The refactored code.
2. A numbered list of each change and why it helps.
3. Anything that needs a test before you would ship it.

```swift
[PASTE CODE]
```', 'output' => 'Cleaner code with the same behaviour, a change log you can paste into a pull request, and a list of risky spots to test.', 'image' => '' ),
	array( 'id' => 'debug-a-swift-error', 'title' => 'Debug a Swift error', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Paste the error and code; get the root cause, the fix and how to avoid it next time.', 'prompt' => 'I get this error in my Swift project:
```
[PASTE FULL ERROR / STACK TRACE]
```
Relevant code:
```swift
[PASTE CODE]
```
Environment: [Swift VERSION, OS, FRAMEWORK/LIBRARY VERSIONS]
1. Explain the root cause in simple words.
2. Show the minimal fix as a diff.
3. Explain why the fix works.
4. Suggest how to prevent this class of bug (test, type, lint rule).
If information is missing, ask me up to 3 questions first.', 'output' => 'The likely root cause, a small diff, the reasoning, and one prevention step; or up to three precise questions if the error is ambiguous.', 'image' => '' ),
	array( 'id' => 'swift-code-review-checklist', 'title' => 'Swift code review checklist', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'A senior-style Swift review: correctness, security, performance and style, ranked by severity.', 'prompt' => 'Act as a strict but kind senior Swift reviewer. Review this code for:
- correctness and edge cases
- security (input validation, injection, secrets, unsafe APIs)
- performance and memory
- readability and value types, optionals handled safely, async/await
- tests that are missing
Rank findings as Blocker / Should fix / Nit. For each: line(s), problem, suggested fix (code).
End with a 2-line summary verdict.

```swift
[PASTE CODE OR DIFF]
```', 'output' => 'A severity-ranked list of findings with line references and fix snippets, and a short verdict, like a real pull-request review.', 'image' => '' ),
	array( 'id' => 'convert-kotlin-code-to-swift', 'title' => 'Convert Kotlin code to Swift', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Translate code from Kotlin into idiomatic Swift, not a word-for-word copy.', 'prompt' => 'Convert this Kotlin code into idiomatic Swift.
- Use Swift conventions (value types, optionals handled safely, async/await) and standard library equivalents.
- Keep the same behaviour; note any behaviour that cannot map 1:1.
- Add Swift type information where the language supports it.
- Include a short usage example and the dependencies to install with Swift Package Manager.

```kotlin
[PASTE KOTLIN CODE]
```', 'output' => 'Idiomatic Swift code, a list of differences between the two languages that matter here, a usage example and the exact install command.', 'image' => '' ),
	array( 'id' => 'document-swift-code', 'title' => 'Document Swift code', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => false, 'summary' => 'Add clear Swift documentation comments and a README section.', 'prompt' => 'Add documentation to this Swift code using DocC-compatible /// comments.
- Document every public function/class: purpose, parameters, return value, errors raised, and one example.
- Do not change the code itself.
- Then write a README "Usage" section with installation (Swift Package Manager) and 2 examples.

```swift
[PASTE CODE]
```', 'output' => 'The same code with DocC-compatible /// comments on every public item, plus a README usage section you can paste as-is.', 'image' => '' ),
	array( 'id' => 'speed-up-slow-swift-code', 'title' => 'Speed up slow Swift code', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => true, 'summary' => 'Find the real bottlenecks in Swift code and get faster versions with measurements.', 'prompt' => 'This Swift code is too slow for [INPUT SIZE / USE CASE].
```swift
[PASTE CODE]
```
1. Estimate the time and space complexity of the current code.
2. Identify the top 3 bottlenecks.
3. Give an optimised version and its new complexity.
4. Show how to measure the improvement with Instruments (Time Profiler, Allocations).
Do not sacrifice correctness or readability without saying so.', 'output' => 'Big-O analysis before and after, the main bottlenecks, an optimised version, and the exact profiling or benchmark steps to prove the speed-up.', 'image' => '' ),
	array( 'id' => 'guided-swift-project-small-ios-screen', 'title' => 'Guided Swift project: small iOS screen', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => false, 'summary' => 'A guided build of a small iOS screen with SwiftUI that loads and lists data from an API, with structure, code and tests.', 'prompt' => 'Guide me to build a small iOS screen with SwiftUI that loads and lists data from an API.
Feature: [DESCRIBE, e.g. a to-do list with create, list, update, delete].
1. Project structure (folders and files).
2. Setup commands using Swift Package Manager.
3. The code for each file, in order, with short explanations.
4. Input validation and error handling.
5. Tests with XCTest (or the Swift Testing framework).
6. How to run it locally and what to try next.
Keep it production-minded but beginner-friendly.', 'output' => 'A folder tree, setup commands, complete files in build order, validation, tests and run instructions: a small project you can finish in an evening.', 'image' => '' ),
	array( 'id' => 'explain-php-code-line-by-line', 'title' => 'Explain PHP code line by line', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand unfamiliar PHP code: what it does, how it flows and where it can break.', 'prompt' => 'You are a senior PHP developer and a patient teacher.
Explain the PHP code below to a [BEGINNER / INTERMEDIATE] developer.
1. One-paragraph summary of what it does.
2. Walk through it block by block (quote the lines, then explain).
3. List the PHP features or idioms it uses and why.
4. Point out bugs, edge cases or risky parts.
5. Suggest 2 small improvements that follow PHP 8.2+, strict_types, typed properties.

```php
[PASTE CODE]
```', 'output' => 'A plain-English summary, a block-by-block walkthrough with quoted lines, a list of PHP idioms used, risks and edge cases, and two concrete improvements.', 'image' => '' ),
	array( 'id' => 'write-php-unit-tests', 'title' => 'Write PHP unit tests', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => false, 'summary' => 'Get a complete, runnable PHP test file with edge cases.', 'prompt' => 'Write unit tests for the PHP code below using PHPUnit.
- Cover the happy path, edge cases (empty, null/None, boundaries, large input) and error paths.
- One behaviour per test, descriptive test names.
- Mock external calls (network, database, time).
- Output one complete, runnable test file, then a table: test name | what it proves.

Code:
```php
[PASTE CODE]
```', 'output' => 'A runnable PHPUnit test file with clear names and mocks, followed by a coverage table you can review in a minute.', 'image' => '' ),
	array( 'id' => 'refactor-php-code-for-readability', 'title' => 'Refactor PHP code for readability', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'Clean up messy PHP without changing behaviour, with each change explained.', 'prompt' => 'Refactor this PHP code for readability and maintainability WITHOUT changing its behaviour.
Rules: follow PHP 8.2+, strict_types, typed properties; small functions with clear names; remove duplication; keep the public API the same.
Output:
1. The refactored code.
2. A numbered list of each change and why it helps.
3. Anything that needs a test before you would ship it.

```php
[PASTE CODE]
```', 'output' => 'Cleaner code with the same behaviour, a change log you can paste into a pull request, and a list of risky spots to test.', 'image' => '' ),
	array( 'id' => 'debug-a-php-error', 'title' => 'Debug a PHP error', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Paste the error and code; get the root cause, the fix and how to avoid it next time.', 'prompt' => 'I get this error in my PHP project:
```
[PASTE FULL ERROR / STACK TRACE]
```
Relevant code:
```php
[PASTE CODE]
```
Environment: [PHP VERSION, OS, FRAMEWORK/LIBRARY VERSIONS]
1. Explain the root cause in simple words.
2. Show the minimal fix as a diff.
3. Explain why the fix works.
4. Suggest how to prevent this class of bug (test, type, lint rule).
If information is missing, ask me up to 3 questions first.', 'output' => 'The likely root cause, a small diff, the reasoning, and one prevention step; or up to three precise questions if the error is ambiguous.', 'image' => '' ),
	array( 'id' => 'php-code-review-checklist', 'title' => 'PHP code review checklist', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'A senior-style PHP review: correctness, security, performance and style, ranked by severity.', 'prompt' => 'Act as a strict but kind senior PHP reviewer. Review this code for:
- correctness and edge cases
- security (input validation, injection, secrets, unsafe APIs)
- performance and memory
- readability and PHP 8.2+, strict_types, typed properties
- tests that are missing
Rank findings as Blocker / Should fix / Nit. For each: line(s), problem, suggested fix (code).
End with a 2-line summary verdict.

```php
[PASTE CODE OR DIFF]
```', 'output' => 'A severity-ranked list of findings with line references and fix snippets, and a short verdict, like a real pull-request review.', 'image' => '' ),
	array( 'id' => 'convert-javascript-code-to-php', 'title' => 'Convert JavaScript code to PHP', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => true, 'summary' => 'Translate code from JavaScript into idiomatic PHP, not a word-for-word copy.', 'prompt' => 'Convert this JavaScript code into idiomatic PHP.
- Use PHP conventions (PHP 8.2+, strict_types, typed properties) and standard library equivalents.
- Keep the same behaviour; note any behaviour that cannot map 1:1.
- Add PHP type information where the language supports it.
- Include a short usage example and the dependencies to install with Composer.

```javascript
[PASTE JAVASCRIPT CODE]
```', 'output' => 'Idiomatic PHP code, a list of differences between the two languages that matter here, a usage example and the exact install command.', 'image' => '' ),
	array( 'id' => 'document-php-code', 'title' => 'Document PHP code', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => false, 'summary' => 'Add clear PHP documentation comments and a README section.', 'prompt' => 'Add documentation to this PHP code using PHPDoc.
- Document every public function/class: purpose, parameters, return value, errors raised, and one example.
- Do not change the code itself.
- Then write a README "Usage" section with installation (Composer) and 2 examples.

```php
[PASTE CODE]
```', 'output' => 'The same code with PHPDoc on every public item, plus a README usage section you can paste as-is.', 'image' => '' ),
	array( 'id' => 'speed-up-slow-php-code', 'title' => 'Speed up slow PHP code', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => false, 'summary' => 'Find the real bottlenecks in PHP code and get faster versions with measurements.', 'prompt' => 'This PHP code is too slow for [INPUT SIZE / USE CASE].
```php
[PASTE CODE]
```
1. Estimate the time and space complexity of the current code.
2. Identify the top 3 bottlenecks.
3. Give an optimised version and its new complexity.
4. Show how to measure the improvement with Xdebug profiler or Blackfire.
Do not sacrifice correctness or readability without saying so.', 'output' => 'Big-O analysis before and after, the main bottlenecks, an optimised version, and the exact profiling or benchmark steps to prove the speed-up.', 'image' => '' ),
	array( 'id' => 'guided-php-project-rest-api', 'title' => 'Guided PHP project: REST API', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'A guided build of a REST API with Laravel (routes, controllers, validation, Eloquent), with structure, code and tests.', 'prompt' => 'Guide me to build a REST API with Laravel (routes, controllers, validation, Eloquent).
Feature: [DESCRIBE, e.g. a to-do list with create, list, update, delete].
1. Project structure (folders and files).
2. Setup commands using Composer.
3. The code for each file, in order, with short explanations.
4. Input validation and error handling.
5. Tests with PHPUnit.
6. How to run it locally and what to try next.
Keep it production-minded but beginner-friendly.', 'output' => 'A folder tree, setup commands, complete files in build order, validation, tests and run instructions: a small project you can finish in an evening.', 'image' => '' ),
	array( 'id' => 'explain-ruby-code-line-by-line', 'title' => 'Explain Ruby code line by line', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand unfamiliar Ruby code: what it does, how it flows and where it can break.', 'prompt' => 'You are a senior Ruby developer and a patient teacher.
Explain the Ruby code below to a [BEGINNER / INTERMEDIATE] developer.
1. One-paragraph summary of what it does.
2. Walk through it block by block (quote the lines, then explain).
3. List the Ruby features or idioms it uses and why.
4. Point out bugs, edge cases or risky parts.
5. Suggest 2 small improvements that follow idiomatic Ruby 3, small methods.

```ruby
[PASTE CODE]
```', 'output' => 'A plain-English summary, a block-by-block walkthrough with quoted lines, a list of Ruby idioms used, risks and edge cases, and two concrete improvements.', 'image' => '' ),
	array( 'id' => 'write-ruby-unit-tests', 'title' => 'Write Ruby unit tests', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Get a complete, runnable Ruby test file with edge cases.', 'prompt' => 'Write unit tests for the Ruby code below using RSpec.
- Cover the happy path, edge cases (empty, null/None, boundaries, large input) and error paths.
- One behaviour per test, descriptive test names.
- Mock external calls (network, database, time).
- Output one complete, runnable test file, then a table: test name | what it proves.

Code:
```ruby
[PASTE CODE]
```', 'output' => 'A runnable RSpec test file with clear names and mocks, followed by a coverage table you can review in a minute.', 'image' => '' ),
	array( 'id' => 'refactor-ruby-code-for-readability', 'title' => 'Refactor Ruby code for readability', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Clean up messy Ruby without changing behaviour, with each change explained.', 'prompt' => 'Refactor this Ruby code for readability and maintainability WITHOUT changing its behaviour.
Rules: follow idiomatic Ruby 3, small methods; small functions with clear names; remove duplication; keep the public API the same.
Output:
1. The refactored code.
2. A numbered list of each change and why it helps.
3. Anything that needs a test before you would ship it.

```ruby
[PASTE CODE]
```', 'output' => 'Cleaner code with the same behaviour, a change log you can paste into a pull request, and a list of risky spots to test.', 'image' => '' ),
	array( 'id' => 'debug-a-ruby-error', 'title' => 'Debug a Ruby error', 'cat' => 'Coding', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => true, 'summary' => 'Paste the error and code; get the root cause, the fix and how to avoid it next time.', 'prompt' => 'I get this error in my Ruby project:
```
[PASTE FULL ERROR / STACK TRACE]
```
Relevant code:
```ruby
[PASTE CODE]
```
Environment: [Ruby VERSION, OS, FRAMEWORK/LIBRARY VERSIONS]
1. Explain the root cause in simple words.
2. Show the minimal fix as a diff.
3. Explain why the fix works.
4. Suggest how to prevent this class of bug (test, type, lint rule).
If information is missing, ask me up to 3 questions first.', 'output' => 'The likely root cause, a small diff, the reasoning, and one prevention step; or up to three precise questions if the error is ambiguous.', 'image' => '' ),
	array( 'id' => 'ruby-code-review-checklist', 'title' => 'Ruby code review checklist', 'cat' => 'Coding', 'tool' => 'GitHub Copilot', 'kind' => 'code', 'premium' => false, 'summary' => 'A senior-style Ruby review: correctness, security, performance and style, ranked by severity.', 'prompt' => 'Act as a strict but kind senior Ruby reviewer. Review this code for:
- correctness and edge cases
- security (input validation, injection, secrets, unsafe APIs)
- performance and memory
- readability and idiomatic Ruby 3, small methods
- tests that are missing
Rank findings as Blocker / Should fix / Nit. For each: line(s), problem, suggested fix (code).
End with a 2-line summary verdict.

```ruby
[PASTE CODE OR DIFF]
```', 'output' => 'A severity-ranked list of findings with line references and fix snippets, and a short verdict, like a real pull-request review.', 'image' => '' ),
	array( 'id' => 'convert-python-code-to-ruby', 'title' => 'Convert Python code to Ruby', 'cat' => 'Coding', 'tool' => 'Cursor', 'kind' => 'code', 'premium' => false, 'summary' => 'Translate code from Python into idiomatic Ruby, not a word-for-word copy.', 'prompt' => 'Convert this Python code into idiomatic Ruby.
- Use Ruby conventions (idiomatic Ruby 3, small methods) and standard library equivalents.
- Keep the same behaviour; note any behaviour that cannot map 1:1.
- Add Ruby type information where the language supports it.
- Include a short usage example and the dependencies to install with Bundler.

```python
[PASTE PYTHON CODE]
```', 'output' => 'Idiomatic Ruby code, a list of differences between the two languages that matter here, a usage example and the exact install command.', 'image' => '' ),
	array( 'id' => 'document-ruby-code', 'title' => 'Document Ruby code', 'cat' => 'Coding', 'tool' => 'Gemini', 'kind' => 'code', 'premium' => true, 'summary' => 'Add clear Ruby documentation comments and a README section.', 'prompt' => 'Add documentation to this Ruby code using YARD comments.
- Document every public function/class: purpose, parameters, return value, errors raised, and one example.
- Do not change the code itself.
- Then write a README "Usage" section with installation (Bundler) and 2 examples.

```ruby
[PASTE CODE]
```', 'output' => 'The same code with YARD comments on every public item, plus a README usage section you can paste as-is.', 'image' => '' ),
	array( 'id' => 'speed-up-slow-ruby-code', 'title' => 'Speed up slow Ruby code', 'cat' => 'Coding', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Find the real bottlenecks in Ruby code and get faster versions with measurements.', 'prompt' => 'This Ruby code is too slow for [INPUT SIZE / USE CASE].
```ruby
[PASTE CODE]
```
1. Estimate the time and space complexity of the current code.
2. Identify the top 3 bottlenecks.
3. Give an optimised version and its new complexity.
4. Show how to measure the improvement with ruby-prof and benchmark-ips.
Do not sacrifice correctness or readability without saying so.', 'output' => 'Big-O analysis before and after, the main bottlenecks, an optimised version, and the exact profiling or benchmark steps to prove the speed-up.', 'image' => '' ),
	array( 'id' => 'guided-ruby-project-json-api', 'title' => 'Guided Ruby project: JSON API', 'cat' => 'Coding', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A guided build of a JSON API with Ruby on Rails (API mode), with structure, code and tests.', 'prompt' => 'Guide me to build a JSON API with Ruby on Rails (API mode).
Feature: [DESCRIBE, e.g. a to-do list with create, list, update, delete].
1. Project structure (folders and files).
2. Setup commands using Bundler.
3. The code for each file, in order, with short explanations.
4. Input validation and error handling.
5. Tests with RSpec.
6. How to run it locally and what to try next.
Keep it production-minded but beginner-friendly.', 'output' => 'A folder tree, setup commands, complete files in build order, validation, tests and run instructions: a small project you can finish in an evening.', 'image' => '' ),
	array( 'id' => 'software-engineer-mock-interview', 'title' => 'Software Engineer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Practise a realistic Software Engineer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Software Engineer.
Key skills to test: data structures and algorithms, system design basics, Git, testing, code reviews.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Software Engineer role.', 'image' => '' ),
	array( 'id' => 'software-engineer-resume-bullet-points', 'title' => 'Software Engineer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Software Engineer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Software Engineer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: data structures and algorithms, system design basics, Git, testing, code reviews.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'software-engineer-cover-letter', 'title' => 'Software Engineer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A short, specific Software Engineer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Software Engineer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'software-engineer-linkedin-headline-and-about', 'title' => 'Software Engineer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Software Engineer.', 'prompt' => 'Write my LinkedIn profile copy as a Software Engineer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as data structures and algorithms.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'software-engineer-30-60-90-day-plan', 'title' => 'Software Engineer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Software Engineer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Software Engineer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: data structures and algorithms, system design basics, Git, testing, code reviews.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'frontend-developer-mock-interview', 'title' => 'Frontend Developer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Practise a realistic Frontend Developer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Frontend Developer.
Key skills to test: HTML, CSS, JavaScript, React, accessibility, web performance.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Frontend Developer role.', 'image' => '' ),
	array( 'id' => 'frontend-developer-resume-bullet-points', 'title' => 'Frontend Developer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Frontend Developer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Frontend Developer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: HTML, CSS, JavaScript, React, accessibility, web performance.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'frontend-developer-cover-letter', 'title' => 'Frontend Developer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => true, 'summary' => 'A short, specific Frontend Developer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Frontend Developer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'frontend-developer-linkedin-headline-and-about', 'title' => 'Frontend Developer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Frontend Developer.', 'prompt' => 'Write my LinkedIn profile copy as a Frontend Developer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as HTML.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'frontend-developer-30-60-90-day-plan', 'title' => 'Frontend Developer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Frontend Developer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Frontend Developer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: HTML, CSS, JavaScript, React, accessibility, web performance.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'backend-developer-mock-interview', 'title' => 'Backend Developer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Practise a realistic Backend Developer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Backend Developer.
Key skills to test: REST APIs, databases, caching, authentication, scalability.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Backend Developer role.', 'image' => '' ),
	array( 'id' => 'backend-developer-resume-bullet-points', 'title' => 'Backend Developer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Backend Developer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Backend Developer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: REST APIs, databases, caching, authentication, scalability.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'backend-developer-cover-letter', 'title' => 'Backend Developer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A short, specific Backend Developer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Backend Developer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'backend-developer-linkedin-headline-and-about', 'title' => 'Backend Developer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Backend Developer.', 'prompt' => 'Write my LinkedIn profile copy as a Backend Developer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as REST APIs.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'backend-developer-30-60-90-day-plan', 'title' => 'Backend Developer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Backend Developer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Backend Developer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: REST APIs, databases, caching, authentication, scalability.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'full-stack-developer-mock-interview', 'title' => 'Full-Stack Developer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Practise a realistic Full-Stack Developer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Full-Stack Developer.
Key skills to test: React, Node.js, SQL/NoSQL databases, REST APIs, deployment.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Full-Stack Developer role.', 'image' => '' ),
	array( 'id' => 'full-stack-developer-resume-bullet-points', 'title' => 'Full-Stack Developer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Full-Stack Developer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Full-Stack Developer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: React, Node.js, SQL/NoSQL databases, REST APIs, deployment.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'full-stack-developer-cover-letter', 'title' => 'Full-Stack Developer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'A short, specific Full-Stack Developer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Full-Stack Developer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'full-stack-developer-linkedin-headline-and-about', 'title' => 'Full-Stack Developer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Full-Stack Developer.', 'prompt' => 'Write my LinkedIn profile copy as a Full-Stack Developer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as React.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'full-stack-developer-30-60-90-day-plan', 'title' => 'Full-Stack Developer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Full-Stack Developer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Full-Stack Developer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: React, Node.js, SQL/NoSQL databases, REST APIs, deployment.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'java-developer-mock-interview', 'title' => 'Java Developer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Practise a realistic Java Developer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Java Developer.
Key skills to test: Core Java, OOP, collections, Spring Boot, JPA/Hibernate, SQL, microservices.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Java Developer role.', 'image' => '' ),
	array( 'id' => 'java-developer-resume-bullet-points', 'title' => 'Java Developer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Java Developer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Java Developer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: Core Java, OOP, collections, Spring Boot, JPA/Hibernate, SQL, microservices.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'java-developer-cover-letter', 'title' => 'Java Developer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A short, specific Java Developer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Java Developer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'java-developer-linkedin-headline-and-about', 'title' => 'Java Developer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Java Developer.', 'prompt' => 'Write my LinkedIn profile copy as a Java Developer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as Core Java.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'java-developer-30-60-90-day-plan', 'title' => 'Java Developer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Java Developer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Java Developer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: Core Java, OOP, collections, Spring Boot, JPA/Hibernate, SQL, microservices.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'python-developer-mock-interview', 'title' => 'Python Developer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Practise a realistic Python Developer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Python Developer.
Key skills to test: Python, Django or FastAPI, SQL, testing, REST APIs.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Python Developer role.', 'image' => '' ),
	array( 'id' => 'python-developer-resume-bullet-points', 'title' => 'Python Developer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Python Developer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Python Developer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: Python, Django or FastAPI, SQL, testing, REST APIs.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'python-developer-cover-letter', 'title' => 'Python Developer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => true, 'summary' => 'A short, specific Python Developer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Python Developer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'python-developer-linkedin-headline-and-about', 'title' => 'Python Developer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Python Developer.', 'prompt' => 'Write my LinkedIn profile copy as a Python Developer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as Python.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'python-developer-30-60-90-day-plan', 'title' => 'Python Developer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Python Developer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Python Developer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: Python, Django or FastAPI, SQL, testing, REST APIs.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'data-analyst-mock-interview', 'title' => 'Data Analyst mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Practise a realistic Data Analyst interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Data Analyst.
Key skills to test: SQL, Excel, Power BI or Tableau, statistics, storytelling with data.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Data Analyst role.', 'image' => '' ),
	array( 'id' => 'data-analyst-resume-bullet-points', 'title' => 'Data Analyst resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Data Analyst resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Data Analyst role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: SQL, Excel, Power BI or Tableau, statistics, storytelling with data.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'data-analyst-cover-letter', 'title' => 'Data Analyst cover letter', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A short, specific Data Analyst cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Data Analyst position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'data-analyst-linkedin-headline-and-about', 'title' => 'Data Analyst LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Data Analyst.', 'prompt' => 'Write my LinkedIn profile copy as a Data Analyst.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as SQL.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'data-analyst-30-60-90-day-plan', 'title' => 'Data Analyst 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Data Analyst role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Data Analyst at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: SQL, Excel, Power BI or Tableau, statistics, storytelling with data.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'data-scientist-mock-interview', 'title' => 'Data Scientist mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Practise a realistic Data Scientist interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Data Scientist.
Key skills to test: Python, statistics, pandas, scikit-learn, experimentation, communication.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Data Scientist role.', 'image' => '' ),
	array( 'id' => 'data-scientist-resume-bullet-points', 'title' => 'Data Scientist resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Data Scientist resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Data Scientist role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: Python, statistics, pandas, scikit-learn, experimentation, communication.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'data-scientist-cover-letter', 'title' => 'Data Scientist cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'A short, specific Data Scientist cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Data Scientist position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'data-scientist-linkedin-headline-and-about', 'title' => 'Data Scientist LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Data Scientist.', 'prompt' => 'Write my LinkedIn profile copy as a Data Scientist.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as Python.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'data-scientist-30-60-90-day-plan', 'title' => 'Data Scientist 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Data Scientist role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Data Scientist at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: Python, statistics, pandas, scikit-learn, experimentation, communication.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'machine-learning-engineer-mock-interview', 'title' => 'Machine Learning Engineer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Practise a realistic Machine Learning Engineer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Machine Learning Engineer.
Key skills to test: Python, PyTorch or TensorFlow, model deployment, MLOps, data pipelines.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Machine Learning Engineer role.', 'image' => '' ),
	array( 'id' => 'machine-learning-engineer-resume-bullet-points', 'title' => 'Machine Learning Engineer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Machine Learning Engineer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Machine Learning Engineer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: Python, PyTorch or TensorFlow, model deployment, MLOps, data pipelines.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'machine-learning-engineer-cover-letter', 'title' => 'Machine Learning Engineer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A short, specific Machine Learning Engineer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Machine Learning Engineer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'machine-learning-engineer-linkedin-headline-and-about', 'title' => 'Machine Learning Engineer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Machine Learning Engineer.', 'prompt' => 'Write my LinkedIn profile copy as a Machine Learning Engineer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as Python.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'machine-learning-engineer-30-60-90-day-plan', 'title' => 'Machine Learning Engineer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Machine Learning Engineer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Machine Learning Engineer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: Python, PyTorch or TensorFlow, model deployment, MLOps, data pipelines.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'data-engineer-mock-interview', 'title' => 'Data Engineer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Practise a realistic Data Engineer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Data Engineer.
Key skills to test: SQL, Python, Spark, Airflow, data warehousing, ETL/ELT.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Data Engineer role.', 'image' => '' ),
	array( 'id' => 'data-engineer-resume-bullet-points', 'title' => 'Data Engineer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Data Engineer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Data Engineer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: SQL, Python, Spark, Airflow, data warehousing, ETL/ELT.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'data-engineer-cover-letter', 'title' => 'Data Engineer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => true, 'summary' => 'A short, specific Data Engineer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Data Engineer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'data-engineer-linkedin-headline-and-about', 'title' => 'Data Engineer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Data Engineer.', 'prompt' => 'Write my LinkedIn profile copy as a Data Engineer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as SQL.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'data-engineer-30-60-90-day-plan', 'title' => 'Data Engineer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Data Engineer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Data Engineer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: SQL, Python, Spark, Airflow, data warehousing, ETL/ELT.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'devops-engineer-mock-interview', 'title' => 'DevOps Engineer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Practise a realistic DevOps Engineer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] DevOps Engineer.
Key skills to test: Linux, CI/CD, Docker, Kubernetes, Terraform, monitoring.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a DevOps Engineer role.', 'image' => '' ),
	array( 'id' => 'devops-engineer-resume-bullet-points', 'title' => 'DevOps Engineer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly DevOps Engineer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a DevOps Engineer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: Linux, CI/CD, Docker, Kubernetes, Terraform, monitoring.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'devops-engineer-cover-letter', 'title' => 'DevOps Engineer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A short, specific DevOps Engineer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a DevOps Engineer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'devops-engineer-linkedin-headline-and-about', 'title' => 'DevOps Engineer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a DevOps Engineer.', 'prompt' => 'Write my LinkedIn profile copy as a DevOps Engineer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as Linux.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'devops-engineer-30-60-90-day-plan', 'title' => 'DevOps Engineer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new DevOps Engineer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a DevOps Engineer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: Linux, CI/CD, Docker, Kubernetes, Terraform, monitoring.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'cloud-engineer-mock-interview', 'title' => 'Cloud Engineer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Practise a realistic Cloud Engineer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Cloud Engineer.
Key skills to test: AWS, Azure or GCP, networking, IAM, infrastructure as code, cost control.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Cloud Engineer role.', 'image' => '' ),
	array( 'id' => 'cloud-engineer-resume-bullet-points', 'title' => 'Cloud Engineer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Cloud Engineer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Cloud Engineer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: AWS, Azure or GCP, networking, IAM, infrastructure as code, cost control.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'cloud-engineer-cover-letter', 'title' => 'Cloud Engineer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'A short, specific Cloud Engineer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Cloud Engineer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'cloud-engineer-linkedin-headline-and-about', 'title' => 'Cloud Engineer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Cloud Engineer.', 'prompt' => 'Write my LinkedIn profile copy as a Cloud Engineer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as AWS.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'cloud-engineer-30-60-90-day-plan', 'title' => 'Cloud Engineer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Cloud Engineer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Cloud Engineer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: AWS, Azure or GCP, networking, IAM, infrastructure as code, cost control.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'qa-automation-engineer-mock-interview', 'title' => 'QA Automation Engineer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Practise a realistic QA Automation Engineer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] QA Automation Engineer.
Key skills to test: test design, Selenium or Playwright, API testing, CI integration.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a QA Automation Engineer role.', 'image' => '' ),
	array( 'id' => 'qa-automation-engineer-resume-bullet-points', 'title' => 'QA Automation Engineer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly QA Automation Engineer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a QA Automation Engineer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: test design, Selenium or Playwright, API testing, CI integration.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'qa-automation-engineer-cover-letter', 'title' => 'QA Automation Engineer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A short, specific QA Automation Engineer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a QA Automation Engineer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'qa-automation-engineer-linkedin-headline-and-about', 'title' => 'QA Automation Engineer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a QA Automation Engineer.', 'prompt' => 'Write my LinkedIn profile copy as a QA Automation Engineer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as test design.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'qa-automation-engineer-30-60-90-day-plan', 'title' => 'QA Automation Engineer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new QA Automation Engineer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a QA Automation Engineer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: test design, Selenium or Playwright, API testing, CI integration.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'android-developer-mock-interview', 'title' => 'Android Developer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Practise a realistic Android Developer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Android Developer.
Key skills to test: Kotlin, Jetpack Compose, Android SDK, architecture components, testing.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Android Developer role.', 'image' => '' ),
	array( 'id' => 'android-developer-resume-bullet-points', 'title' => 'Android Developer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Android Developer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Android Developer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: Kotlin, Jetpack Compose, Android SDK, architecture components, testing.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'android-developer-cover-letter', 'title' => 'Android Developer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => true, 'summary' => 'A short, specific Android Developer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Android Developer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'android-developer-linkedin-headline-and-about', 'title' => 'Android Developer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Android Developer.', 'prompt' => 'Write my LinkedIn profile copy as a Android Developer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as Kotlin.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'android-developer-30-60-90-day-plan', 'title' => 'Android Developer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Android Developer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Android Developer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: Kotlin, Jetpack Compose, Android SDK, architecture components, testing.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'ios-developer-mock-interview', 'title' => 'iOS Developer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Practise a realistic iOS Developer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] iOS Developer.
Key skills to test: Swift, SwiftUI, UIKit, concurrency, App Store release.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a iOS Developer role.', 'image' => '' ),
	array( 'id' => 'ios-developer-resume-bullet-points', 'title' => 'iOS Developer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly iOS Developer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a iOS Developer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: Swift, SwiftUI, UIKit, concurrency, App Store release.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'ios-developer-cover-letter', 'title' => 'iOS Developer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A short, specific iOS Developer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a iOS Developer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'ios-developer-linkedin-headline-and-about', 'title' => 'iOS Developer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a iOS Developer.', 'prompt' => 'Write my LinkedIn profile copy as a iOS Developer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as Swift.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'ios-developer-30-60-90-day-plan', 'title' => 'iOS Developer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new iOS Developer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a iOS Developer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: Swift, SwiftUI, UIKit, concurrency, App Store release.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'ui-ux-designer-mock-interview', 'title' => 'UI/UX Designer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Practise a realistic UI/UX Designer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] UI/UX Designer.
Key skills to test: Figma, user research, wireframes, prototyping, design systems, usability testing.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a UI/UX Designer role.', 'image' => '' ),
	array( 'id' => 'ui-ux-designer-resume-bullet-points', 'title' => 'UI/UX Designer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly UI/UX Designer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a UI/UX Designer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: Figma, user research, wireframes, prototyping, design systems, usability testing.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'ui-ux-designer-cover-letter', 'title' => 'UI/UX Designer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'A short, specific UI/UX Designer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a UI/UX Designer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'ui-ux-designer-linkedin-headline-and-about', 'title' => 'UI/UX Designer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a UI/UX Designer.', 'prompt' => 'Write my LinkedIn profile copy as a UI/UX Designer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as Figma.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'ui-ux-designer-30-60-90-day-plan', 'title' => 'UI/UX Designer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new UI/UX Designer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a UI/UX Designer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: Figma, user research, wireframes, prototyping, design systems, usability testing.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'product-manager-mock-interview', 'title' => 'Product Manager mock interview', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Practise a realistic Product Manager interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Product Manager.
Key skills to test: discovery, roadmaps, prioritisation, metrics, stakeholder communication.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Product Manager role.', 'image' => '' ),
	array( 'id' => 'product-manager-resume-bullet-points', 'title' => 'Product Manager resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Product Manager resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Product Manager role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: discovery, roadmaps, prioritisation, metrics, stakeholder communication.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'product-manager-cover-letter', 'title' => 'Product Manager cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A short, specific Product Manager cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Product Manager position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'product-manager-linkedin-headline-and-about', 'title' => 'Product Manager LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Product Manager.', 'prompt' => 'Write my LinkedIn profile copy as a Product Manager.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as discovery.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'product-manager-30-60-90-day-plan', 'title' => 'Product Manager 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Product Manager role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Product Manager at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: discovery, roadmaps, prioritisation, metrics, stakeholder communication.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'business-analyst-mock-interview', 'title' => 'Business Analyst mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Practise a realistic Business Analyst interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Business Analyst.
Key skills to test: requirements gathering, process mapping, SQL, user stories, stakeholder management.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Business Analyst role.', 'image' => '' ),
	array( 'id' => 'business-analyst-resume-bullet-points', 'title' => 'Business Analyst resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Business Analyst resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Business Analyst role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: requirements gathering, process mapping, SQL, user stories, stakeholder management.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'business-analyst-cover-letter', 'title' => 'Business Analyst cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => true, 'summary' => 'A short, specific Business Analyst cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Business Analyst position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'business-analyst-linkedin-headline-and-about', 'title' => 'Business Analyst LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Business Analyst.', 'prompt' => 'Write my LinkedIn profile copy as a Business Analyst.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as requirements gathering.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'business-analyst-30-60-90-day-plan', 'title' => 'Business Analyst 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Business Analyst role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Business Analyst at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: requirements gathering, process mapping, SQL, user stories, stakeholder management.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'cybersecurity-analyst-mock-interview', 'title' => 'Cybersecurity Analyst mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Practise a realistic Cybersecurity Analyst interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Cybersecurity Analyst.
Key skills to test: SOC monitoring, SIEM, incident response, networking, threat analysis.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Cybersecurity Analyst role.', 'image' => '' ),
	array( 'id' => 'cybersecurity-analyst-resume-bullet-points', 'title' => 'Cybersecurity Analyst resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Cybersecurity Analyst resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Cybersecurity Analyst role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: SOC monitoring, SIEM, incident response, networking, threat analysis.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'cybersecurity-analyst-cover-letter', 'title' => 'Cybersecurity Analyst cover letter', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A short, specific Cybersecurity Analyst cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Cybersecurity Analyst position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'cybersecurity-analyst-linkedin-headline-and-about', 'title' => 'Cybersecurity Analyst LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Cybersecurity Analyst.', 'prompt' => 'Write my LinkedIn profile copy as a Cybersecurity Analyst.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as SOC monitoring.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'cybersecurity-analyst-30-60-90-day-plan', 'title' => 'Cybersecurity Analyst 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Cybersecurity Analyst role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Cybersecurity Analyst at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: SOC monitoring, SIEM, incident response, networking, threat analysis.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'digital-marketer-mock-interview', 'title' => 'Digital Marketer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Practise a realistic Digital Marketer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Digital Marketer.
Key skills to test: SEO, Google Ads, Meta Ads, analytics, content marketing, email.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Digital Marketer role.', 'image' => '' ),
	array( 'id' => 'digital-marketer-resume-bullet-points', 'title' => 'Digital Marketer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Digital Marketer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Digital Marketer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: SEO, Google Ads, Meta Ads, analytics, content marketing, email.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'digital-marketer-cover-letter', 'title' => 'Digital Marketer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'A short, specific Digital Marketer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Digital Marketer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'digital-marketer-linkedin-headline-and-about', 'title' => 'Digital Marketer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Digital Marketer.', 'prompt' => 'Write my LinkedIn profile copy as a Digital Marketer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as SEO.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'digital-marketer-30-60-90-day-plan', 'title' => 'Digital Marketer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Digital Marketer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Digital Marketer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: SEO, Google Ads, Meta Ads, analytics, content marketing, email.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'content-writer-mock-interview', 'title' => 'Content Writer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Practise a realistic Content Writer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Content Writer.
Key skills to test: research, SEO writing, editing, storytelling, content strategy.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Content Writer role.', 'image' => '' ),
	array( 'id' => 'content-writer-resume-bullet-points', 'title' => 'Content Writer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Content Writer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Content Writer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: research, SEO writing, editing, storytelling, content strategy.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'content-writer-cover-letter', 'title' => 'Content Writer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A short, specific Content Writer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Content Writer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'content-writer-linkedin-headline-and-about', 'title' => 'Content Writer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Content Writer.', 'prompt' => 'Write my LinkedIn profile copy as a Content Writer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as research.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'content-writer-30-60-90-day-plan', 'title' => 'Content Writer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Content Writer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Content Writer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: research, SEO writing, editing, storytelling, content strategy.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'graphic-designer-mock-interview', 'title' => 'Graphic Designer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Practise a realistic Graphic Designer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Graphic Designer.
Key skills to test: typography, layout, branding, Adobe Illustrator/Photoshop or Figma, print and digital.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Graphic Designer role.', 'image' => '' ),
	array( 'id' => 'graphic-designer-resume-bullet-points', 'title' => 'Graphic Designer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Graphic Designer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Graphic Designer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: typography, layout, branding, Adobe Illustrator/Photoshop or Figma, print and digital.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'graphic-designer-cover-letter', 'title' => 'Graphic Designer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => true, 'summary' => 'A short, specific Graphic Designer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Graphic Designer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'graphic-designer-linkedin-headline-and-about', 'title' => 'Graphic Designer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Graphic Designer.', 'prompt' => 'Write my LinkedIn profile copy as a Graphic Designer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as typography.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'graphic-designer-30-60-90-day-plan', 'title' => 'Graphic Designer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Graphic Designer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Graphic Designer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: typography, layout, branding, Adobe Illustrator/Photoshop or Figma, print and digital.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'project-manager-mock-interview', 'title' => 'Project Manager mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Practise a realistic Project Manager interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Project Manager.
Key skills to test: Agile and Scrum, planning, risk management, budgeting, stakeholder communication.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Project Manager role.', 'image' => '' ),
	array( 'id' => 'project-manager-resume-bullet-points', 'title' => 'Project Manager resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Project Manager resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Project Manager role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: Agile and Scrum, planning, risk management, budgeting, stakeholder communication.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'project-manager-cover-letter', 'title' => 'Project Manager cover letter', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A short, specific Project Manager cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Project Manager position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'project-manager-linkedin-headline-and-about', 'title' => 'Project Manager LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Project Manager.', 'prompt' => 'Write my LinkedIn profile copy as a Project Manager.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as Agile and Scrum.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'project-manager-30-60-90-day-plan', 'title' => 'Project Manager 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Project Manager role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Project Manager at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: Agile and Scrum, planning, risk management, budgeting, stakeholder communication.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'hr-executive-mock-interview', 'title' => 'HR Executive mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Practise a realistic HR Executive interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] HR Executive.
Key skills to test: recruitment, onboarding, employee engagement, HR policies, HRMS tools.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a HR Executive role.', 'image' => '' ),
	array( 'id' => 'hr-executive-resume-bullet-points', 'title' => 'HR Executive resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly HR Executive resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a HR Executive role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: recruitment, onboarding, employee engagement, HR policies, HRMS tools.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'hr-executive-cover-letter', 'title' => 'HR Executive cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'A short, specific HR Executive cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a HR Executive position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'hr-executive-linkedin-headline-and-about', 'title' => 'HR Executive LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a HR Executive.', 'prompt' => 'Write my LinkedIn profile copy as a HR Executive.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as recruitment.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'hr-executive-30-60-90-day-plan', 'title' => 'HR Executive 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new HR Executive role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a HR Executive at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: recruitment, onboarding, employee engagement, HR policies, HRMS tools.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'sales-executive-mock-interview', 'title' => 'Sales Executive mock interview', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Practise a realistic Sales Executive interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Sales Executive.
Key skills to test: prospecting, CRM, negotiation, pipeline management, closing.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Sales Executive role.', 'image' => '' ),
	array( 'id' => 'sales-executive-resume-bullet-points', 'title' => 'Sales Executive resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Sales Executive resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Sales Executive role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: prospecting, CRM, negotiation, pipeline management, closing.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'sales-executive-cover-letter', 'title' => 'Sales Executive cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A short, specific Sales Executive cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Sales Executive position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'sales-executive-linkedin-headline-and-about', 'title' => 'Sales Executive LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Sales Executive.', 'prompt' => 'Write my LinkedIn profile copy as a Sales Executive.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as prospecting.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'sales-executive-30-60-90-day-plan', 'title' => 'Sales Executive 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Sales Executive role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Sales Executive at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: prospecting, CRM, negotiation, pipeline management, closing.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'customer-support-specialist-mock-interview', 'title' => 'Customer Support Specialist mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Practise a realistic Customer Support Specialist interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Customer Support Specialist.
Key skills to test: empathy, ticketing tools, troubleshooting, clear writing, SLAs.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Customer Support Specialist role.', 'image' => '' ),
	array( 'id' => 'customer-support-specialist-resume-bullet-points', 'title' => 'Customer Support Specialist resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Customer Support Specialist resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Customer Support Specialist role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: empathy, ticketing tools, troubleshooting, clear writing, SLAs.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'customer-support-specialist-cover-letter', 'title' => 'Customer Support Specialist cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => true, 'summary' => 'A short, specific Customer Support Specialist cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Customer Support Specialist position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'customer-support-specialist-linkedin-headline-and-about', 'title' => 'Customer Support Specialist LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Customer Support Specialist.', 'prompt' => 'Write my LinkedIn profile copy as a Customer Support Specialist.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as empathy.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'customer-support-specialist-30-60-90-day-plan', 'title' => 'Customer Support Specialist 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Customer Support Specialist role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Customer Support Specialist at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: empathy, ticketing tools, troubleshooting, clear writing, SLAs.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'accountant-mock-interview', 'title' => 'Accountant mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Practise a realistic Accountant interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Accountant.
Key skills to test: bookkeeping, GST and TDS basics, Tally or Zoho Books, Excel, reconciliation.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Accountant role.', 'image' => '' ),
	array( 'id' => 'accountant-resume-bullet-points', 'title' => 'Accountant resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Accountant resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Accountant role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: bookkeeping, GST and TDS basics, Tally or Zoho Books, Excel, reconciliation.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'accountant-cover-letter', 'title' => 'Accountant cover letter', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A short, specific Accountant cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Accountant position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'accountant-linkedin-headline-and-about', 'title' => 'Accountant LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Accountant.', 'prompt' => 'Write my LinkedIn profile copy as a Accountant.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as bookkeeping.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'accountant-30-60-90-day-plan', 'title' => 'Accountant 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Accountant role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Accountant at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: bookkeeping, GST and TDS basics, Tally or Zoho Books, Excel, reconciliation.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'teacher-mock-interview', 'title' => 'Teacher mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Practise a realistic Teacher interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Teacher.
Key skills to test: lesson planning, classroom management, assessment, differentiated instruction, edtech tools.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Teacher role.', 'image' => '' ),
	array( 'id' => 'teacher-resume-bullet-points', 'title' => 'Teacher resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Teacher resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Teacher role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: lesson planning, classroom management, assessment, differentiated instruction, edtech tools.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'teacher-cover-letter', 'title' => 'Teacher cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'A short, specific Teacher cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Teacher position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'teacher-linkedin-headline-and-about', 'title' => 'Teacher LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Teacher.', 'prompt' => 'Write my LinkedIn profile copy as a Teacher.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as lesson planning.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'teacher-30-60-90-day-plan', 'title' => 'Teacher 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Teacher role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Teacher at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: lesson planning, classroom management, assessment, differentiated instruction, edtech tools.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'embedded-systems-engineer-mock-interview', 'title' => 'Embedded Systems Engineer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Practise a realistic Embedded Systems Engineer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Embedded Systems Engineer.
Key skills to test: C, microcontrollers, RTOS, communication protocols (I2C, SPI, UART), debugging hardware.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Embedded Systems Engineer role.', 'image' => '' ),
	array( 'id' => 'embedded-systems-engineer-resume-bullet-points', 'title' => 'Embedded Systems Engineer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Embedded Systems Engineer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Embedded Systems Engineer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: C, microcontrollers, RTOS, communication protocols (I2C, SPI, UART), debugging hardware.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'embedded-systems-engineer-cover-letter', 'title' => 'Embedded Systems Engineer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A short, specific Embedded Systems Engineer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Embedded Systems Engineer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'embedded-systems-engineer-linkedin-headline-and-about', 'title' => 'Embedded Systems Engineer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Embedded Systems Engineer.', 'prompt' => 'Write my LinkedIn profile copy as a Embedded Systems Engineer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as C.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'embedded-systems-engineer-30-60-90-day-plan', 'title' => 'Embedded Systems Engineer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Embedded Systems Engineer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Embedded Systems Engineer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: C, microcontrollers, RTOS, communication protocols (I2C, SPI, UART), debugging hardware.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'salesforce-developer-mock-interview', 'title' => 'Salesforce Developer mock interview', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Practise a realistic Salesforce Developer interview, one question at a time, with scores and model answers.', 'prompt' => 'Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] Salesforce Developer.
Key skills to test: Apex, Lightning Web Components, SOQL, Flows, Salesforce data model.
Run a mock interview:
- Ask ONE question at a time and wait for my answer.
- Mix technical, scenario and behavioural questions (use the STAR method for behavioural).
- After each answer: score it out of 10, say what was good, what was missing, and give a model answer.
- After 8 questions, give an overall verdict and the 3 topics I should practise most.', 'output' => 'An interactive interview that pauses after each question, scores every answer with feedback and a model answer, and ends with a focused practice list for a Salesforce Developer role.', 'image' => '' ),
	array( 'id' => 'salesforce-developer-resume-bullet-points', 'title' => 'Salesforce Developer resume bullet points', 'cat' => 'Career & Jobs', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn plain duties into impact-focused, ATS-friendly Salesforce Developer resume bullets.', 'prompt' => 'Rewrite my experience as strong resume bullets for a Salesforce Developer role.
My raw notes:
"""
[PASTE WHAT YOU DID: projects, tools, results, numbers]
"""
Rules:
- Start each bullet with a strong action verb; one line each.
- Show impact with numbers (time saved, %, users, revenue) — if I gave no number, write [ADD NUMBER] instead of inventing one.
- Naturally include relevant keywords: Apex, Lightning Web Components, SOQL, Flows, Salesforce data model.
- Give 6–8 bullets, then a 2-line professional summary.', 'output' => 'Six to eight one-line, metric-driven bullets with honest [ADD NUMBER] placeholders where you need real figures, plus a short summary, ready for an ATS-friendly resume.', 'image' => '' ),
	array( 'id' => 'salesforce-developer-cover-letter', 'title' => 'Salesforce Developer cover letter', 'cat' => 'Career & Jobs', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => true, 'summary' => 'A short, specific Salesforce Developer cover letter that matches the job post instead of sounding generic.', 'prompt' => 'Write a cover letter for a Salesforce Developer position.
Job description:
"""
[PASTE JOB POST]
"""
My background: [3–5 LINES ABOUT YOU, KEY PROJECTS, YEARS OF EXPERIENCE]
Company: [COMPANY NAME] — why I like it: [REASON]
Rules: under 250 words; open with a specific hook, not "I am writing to apply"; connect 2–3 of my achievements to their requirements; confident, warm tone; end with a clear call to action. Do not invent achievements.', 'output' => 'A sub-250-word letter that mirrors the job\'s key requirements with your real achievements and ends with a clear next step.', 'image' => '' ),
	array( 'id' => 'salesforce-developer-linkedin-headline-and-about', 'title' => 'Salesforce Developer LinkedIn headline and About', 'cat' => 'Career & Jobs', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A searchable LinkedIn headline and About section for a Salesforce Developer.', 'prompt' => 'Write my LinkedIn profile copy as a Salesforce Developer.
About me: [EXPERIENCE, SKILLS, PROUD RESULTS, WHAT ROLE I WANT NEXT]
Give me:
1. 5 headline options (max 220 characters each) that include searchable keywords such as Apex.
2. An About section of 150–250 words: hook line, what I do, 3 achievements with numbers (use [ADD NUMBER] if unknown), skills list, and a call to action.
3. 10 skills to add to the Skills section.
Tone: [FRIENDLY / PROFESSIONAL].', 'output' => 'Five headline options, a skimmable About section with a hook and proof points, and a list of skills recruiters search for.', 'image' => '' ),
	array( 'id' => 'salesforce-developer-30-60-90-day-plan', 'title' => 'Salesforce Developer 30-60-90 day plan', 'cat' => 'Career & Jobs', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Impress in the final round with a clear first-90-days plan for a new Salesforce Developer role.', 'prompt' => 'Create a 30-60-90 day plan for my new job as a Salesforce Developer at [COMPANY / INDUSTRY].
Team context: [TEAM SIZE, PRODUCT, WHAT I KNOW ABOUT THEIR GOALS]
For each phase (Learn, Contribute, Lead) give: goals, specific actions, people to meet, and how success is measured.
Keep it realistic for the role\'s core skills: Apex, Lightning Web Components, SOQL, Flows, Salesforce data model.
Format as a table, then a 5-line summary I can say out loud in an interview.', 'output' => 'A three-phase table with actions, relationships and success metrics, plus a short spoken summary for the final interview round.', 'image' => '' ),
	array( 'id' => 'learn-the-arrays-and-prefix-sums-pattern', 'title' => 'Learn the Arrays and prefix sums pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'Understand when and how to use arrays and prefix sums, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Arrays and prefix sums pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Two Sum) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Two Sum, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'arrays-and-prefix-sums-practice-set-with-hints', 'title' => 'Arrays and prefix sums practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded arrays and prefix sums problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Arrays and prefix sums practice coach.
Give me 5 problems from easy to hard (you can include classics like Two Sum, Product of Array Except Self, Subarray Sum Equals K).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-two-pointers-pattern', 'title' => 'Learn the Two pointers pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand when and how to use two pointers, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Two pointers pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Valid Palindrome) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Valid Palindrome, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'two-pointers-practice-set-with-hints', 'title' => 'Two pointers practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded two pointers problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Two pointers practice coach.
Give me 5 problems from easy to hard (you can include classics like Valid Palindrome, 3Sum, Container With Most Water).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-sliding-window-pattern', 'title' => 'Learn the Sliding window pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'Understand when and how to use sliding window, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Sliding window pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Longest Substring Without Repeating Characters) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Longest Substring Without Repeating Characters, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'sliding-window-practice-set-with-hints', 'title' => 'Sliding window practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded sliding window problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Sliding window practice coach.
Give me 5 problems from easy to hard (you can include classics like Longest Substring Without Repeating Characters, Minimum Window Substring, Max Consecutive Ones III).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-binary-search-pattern', 'title' => 'Learn the Binary search pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand when and how to use binary search, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Binary search pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Search in Rotated Sorted Array) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Search in Rotated Sorted Array, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'binary-search-practice-set-with-hints', 'title' => 'Binary search practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => true, 'summary' => 'Five graded binary search problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Binary search practice coach.
Give me 5 problems from easy to hard (you can include classics like Search in Rotated Sorted Array, Find Peak Element, Koko Eating Bananas).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-linked-lists-pattern', 'title' => 'Learn the Linked lists pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand when and how to use linked lists, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Linked lists pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Reverse Linked List) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Reverse Linked List, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'linked-lists-practice-set-with-hints', 'title' => 'Linked lists practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded linked lists problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Linked lists practice coach.
Give me 5 problems from easy to hard (you can include classics like Reverse Linked List, Linked List Cycle, Merge Two Sorted Lists).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-stacks-and-monotonic-stacks-pattern', 'title' => 'Learn the Stacks and monotonic stacks pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'Understand when and how to use stacks and monotonic stacks, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Stacks and monotonic stacks pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Valid Parentheses) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Valid Parentheses, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'stacks-and-monotonic-stacks-practice-set-with-hints', 'title' => 'Stacks and monotonic stacks practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded stacks and monotonic stacks problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Stacks and monotonic stacks practice coach.
Give me 5 problems from easy to hard (you can include classics like Valid Parentheses, Daily Temperatures, Largest Rectangle in Histogram).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-queues-and-deques-pattern', 'title' => 'Learn the Queues and deques pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand when and how to use queues and deques, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Queues and deques pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Sliding Window Maximum) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Sliding Window Maximum, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'queues-and-deques-practice-set-with-hints', 'title' => 'Queues and deques practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded queues and deques problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Queues and deques practice coach.
Give me 5 problems from easy to hard (you can include classics like Sliding Window Maximum, Design Circular Queue, Number of Recent Calls).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-hashing-pattern', 'title' => 'Learn the Hashing pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'Understand when and how to use hashing, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Hashing pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Group Anagrams) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Group Anagrams, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'hashing-practice-set-with-hints', 'title' => 'Hashing practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded hashing problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Hashing practice coach.
Give me 5 problems from easy to hard (you can include classics like Group Anagrams, Longest Consecutive Sequence, Top K Frequent Elements).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-recursion-pattern', 'title' => 'Learn the Recursion pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand when and how to use recursion, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Recursion pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Pow(x) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Pow(x, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'recursion-practice-set-with-hints', 'title' => 'Recursion practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'Five graded recursion problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Recursion practice coach.
Give me 5 problems from easy to hard (you can include classics like Pow(x, n), Generate Parentheses, Merge Sort).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-backtracking-pattern', 'title' => 'Learn the Backtracking pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand when and how to use backtracking, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Backtracking pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Subsets) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Subsets, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'backtracking-practice-set-with-hints', 'title' => 'Backtracking practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded backtracking problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Backtracking practice coach.
Give me 5 problems from easy to hard (you can include classics like Subsets, Permutations, N-Queens).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-binary-trees-pattern', 'title' => 'Learn the Binary trees pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'Understand when and how to use binary trees, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Binary trees pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Maximum Depth) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Maximum Depth, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'binary-trees-practice-set-with-hints', 'title' => 'Binary trees practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded binary trees problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Binary trees practice coach.
Give me 5 problems from easy to hard (you can include classics like Maximum Depth, Invert Binary Tree, Lowest Common Ancestor).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-binary-search-trees-pattern', 'title' => 'Learn the Binary search trees pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand when and how to use binary search trees, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Binary search trees pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Validate BST) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Validate BST, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'binary-search-trees-practice-set-with-hints', 'title' => 'Binary search trees practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded binary search trees problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Binary search trees practice coach.
Give me 5 problems from easy to hard (you can include classics like Validate BST, Kth Smallest Element in a BST, Insert into a BST).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-heaps-and-priority-queues-pattern', 'title' => 'Learn the Heaps and priority queues pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'Understand when and how to use heaps and priority queues, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Heaps and priority queues pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Kth Largest Element) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Kth Largest Element, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'heaps-and-priority-queues-practice-set-with-hints', 'title' => 'Heaps and priority queues practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded heaps and priority queues problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Heaps and priority queues practice coach.
Give me 5 problems from easy to hard (you can include classics like Kth Largest Element, Merge k Sorted Lists, Find Median from Data Stream).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-graph-bfs-and-dfs-pattern', 'title' => 'Learn the Graph BFS and DFS pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand when and how to use graph bfs and dfs, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Graph BFS and DFS pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Number of Islands) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Number of Islands, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'graph-bfs-and-dfs-practice-set-with-hints', 'title' => 'Graph BFS and DFS practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => true, 'summary' => 'Five graded graph bfs and dfs problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Graph BFS and DFS practice coach.
Give me 5 problems from easy to hard (you can include classics like Number of Islands, Clone Graph, Rotting Oranges).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-shortest-paths-pattern', 'title' => 'Learn the Shortest paths pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand when and how to use shortest paths (dijkstra, bellman-ford), with a template you can reuse in interviews.', 'prompt' => 'Teach me the Shortest paths (Dijkstra, Bellman-Ford) pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Network Delay Time) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Network Delay Time, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'shortest-paths-practice-set-with-hints', 'title' => 'Shortest paths practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded shortest paths (dijkstra, bellman-ford) problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Shortest paths (Dijkstra, Bellman-Ford) practice coach.
Give me 5 problems from easy to hard (you can include classics like Network Delay Time, Cheapest Flights Within K Stops, Path With Minimum Effort).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-union-find-pattern', 'title' => 'Learn the Union-Find pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'Understand when and how to use union-find (disjoint set), with a template you can reuse in interviews.', 'prompt' => 'Teach me the Union-Find (disjoint set) pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Number of Connected Components) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Number of Connected Components, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'union-find-practice-set-with-hints', 'title' => 'Union-Find practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded union-find (disjoint set) problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Union-Find (disjoint set) practice coach.
Give me 5 problems from easy to hard (you can include classics like Number of Connected Components, Redundant Connection, Accounts Merge).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-topological-sort-pattern', 'title' => 'Learn the Topological sort pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand when and how to use topological sort, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Topological sort pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Course Schedule) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Course Schedule, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'topological-sort-practice-set-with-hints', 'title' => 'Topological sort practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded topological sort problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Topological sort practice coach.
Give me 5 problems from easy to hard (you can include classics like Course Schedule, Course Schedule II, Alien Dictionary).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-1d-dynamic-programming-pattern', 'title' => 'Learn the 1D dynamic programming pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'Understand when and how to use 1d dynamic programming, with a template you can reuse in interviews.', 'prompt' => 'Teach me the 1D dynamic programming pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Climbing Stairs) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Climbing Stairs, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => '1d-dynamic-programming-practice-set-with-hints', 'title' => '1D dynamic programming practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded 1d dynamic programming problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my 1D dynamic programming practice coach.
Give me 5 problems from easy to hard (you can include classics like Climbing Stairs, House Robber, Coin Change).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-2d-dynamic-programming-pattern', 'title' => 'Learn the 2D dynamic programming pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand when and how to use 2d dynamic programming, with a template you can reuse in interviews.', 'prompt' => 'Teach me the 2D dynamic programming pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Unique Paths) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Unique Paths, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => '2d-dynamic-programming-practice-set-with-hints', 'title' => '2D dynamic programming practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'Five graded 2d dynamic programming problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my 2D dynamic programming practice coach.
Give me 5 problems from easy to hard (you can include classics like Unique Paths, Longest Common Subsequence, Edit Distance).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-knapsack-patterns-pattern', 'title' => 'Learn the Knapsack patterns pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand when and how to use knapsack patterns, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Knapsack patterns pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (0/1 Knapsack) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of 0/1 Knapsack, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'knapsack-patterns-practice-set-with-hints', 'title' => 'Knapsack patterns practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded knapsack patterns problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Knapsack patterns practice coach.
Give me 5 problems from easy to hard (you can include classics like 0/1 Knapsack, Partition Equal Subset Sum, Target Sum).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-greedy-algorithms-pattern', 'title' => 'Learn the Greedy algorithms pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'Understand when and how to use greedy algorithms, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Greedy algorithms pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Jump Game) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Jump Game, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'greedy-algorithms-practice-set-with-hints', 'title' => 'Greedy algorithms practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded greedy algorithms problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Greedy algorithms practice coach.
Give me 5 problems from easy to hard (you can include classics like Jump Game, Gas Station, Non-overlapping Intervals).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-tries-pattern', 'title' => 'Learn the Tries pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand when and how to use tries, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Tries pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Implement Trie) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Implement Trie, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'tries-practice-set-with-hints', 'title' => 'Tries practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded tries problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Tries practice coach.
Give me 5 problems from easy to hard (you can include classics like Implement Trie, Word Search II, Design Add and Search Words).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-bit-manipulation-pattern', 'title' => 'Learn the Bit manipulation pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'Understand when and how to use bit manipulation, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Bit manipulation pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Single Number) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Single Number, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'bit-manipulation-practice-set-with-hints', 'title' => 'Bit manipulation practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded bit manipulation problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Bit manipulation practice coach.
Give me 5 problems from easy to hard (you can include classics like Single Number, Counting Bits, Sum of Two Integers).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-intervals-pattern', 'title' => 'Learn the Intervals pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand when and how to use intervals, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Intervals pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Merge Intervals) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Merge Intervals, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'intervals-practice-set-with-hints', 'title' => 'Intervals practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => true, 'summary' => 'Five graded intervals problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Intervals practice coach.
Give me 5 problems from easy to hard (you can include classics like Merge Intervals, Insert Interval, Meeting Rooms II).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'learn-the-segment-trees-and-fenwick-trees-pattern', 'title' => 'Learn the Segment trees and Fenwick trees pattern', 'cat' => 'DSA & Interviews', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Understand when and how to use segment trees and fenwick trees, with a template you can reuse in interviews.', 'prompt' => 'Teach me the Segment trees and Fenwick trees pattern for coding interviews in [LANGUAGE].
1. The core idea in 3 sentences and an analogy.
2. Signals in a problem statement that this pattern fits.
3. A reusable code template with comments.
4. Walk through one classic problem (Range Sum Query - Mutable) step by step with a dry run on a small input.
5. Time and space complexity, and the common mistakes.
6. A one-line cheat-sheet summary.', 'output' => 'A clear mental model, recognition signals, a commented template, a dry-run walkthrough of Range Sum Query - Mutable, complexity and pitfalls, ending in a one-line cheat sheet.', 'image' => '' ),
	array( 'id' => 'segment-trees-and-fenwick-trees-practice-set-with-hints', 'title' => 'Segment trees and Fenwick trees practice set with hints', 'cat' => 'DSA & Interviews', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Five graded segment trees and fenwick trees problems with progressive hints, and solutions only when you ask.', 'prompt' => 'Be my Segment trees and Fenwick trees practice coach.
Give me 5 problems from easy to hard (you can include classics like Range Sum Query - Mutable, Count of Smaller Numbers After Self, Range Minimum Query).
For each: the problem statement, 2 examples and constraints. Do NOT show solutions.
When I say "hint", give the next small hint (max 3 hints per problem).
When I paste my code, review it for correctness and complexity, then show an optimal solution in [LANGUAGE] with explanation.
Track my score out of 5 at the end.', 'output' => 'An interactive practice session: graded problems, hints on request, code review of your attempts, optimal solutions and a final score.', 'image' => '' ),
	array( 'id' => 'explain-a-physics-concept-simply', 'title' => 'Explain a Physics concept simply', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Understand any Physics topic with an analogy, a worked example and a quick check.', 'prompt' => 'You are an expert Physics teacher. Explain [TOPIC] to a [CLASS / LEVEL] student.
1. Simple definition in one line.
2. Real-life analogy or example.
3. Step-by-step explanation (with formulas, dates, diagrams-in-words or rules as Physics needs).
4. One worked example or case.
5. 3 common mistakes or misconceptions.
6. A 3-question check — wait for my answers, then correct them.
Use simple English and stay accurate; if something depends on my syllabus or board, say so.', 'output' => 'A layered explanation of your Physics topic, from one-line definition to worked example, plus a quick interactive check.', 'image' => '' ),
	array( 'id' => 'physics-practice-questions-with-answers', 'title' => 'Physics practice questions with answers', 'cat' => 'Study & Exams', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Exam-style Physics questions at mixed difficulty, with answers and explanations.', 'prompt' => 'Create practice questions on [CHAPTER / TOPIC] in Physics for [CLASS / EXAM].
- 5 multiple-choice, 3 short-answer and 2 long-answer questions, from easy to hard.
- Mark the difficulty of each.
- Put the answer key with step-by-step explanations at the END, after a line "--- ANSWERS ---", so I can attempt first.
- Match the style of [BOARD / EXAM] questions.', 'output' => 'Ten mixed-format questions graded by difficulty, with a separate worked answer key so you can test yourself first.', 'image' => '' ),
	array( 'id' => 'physics-one-page-revision-notes', 'title' => 'Physics one-page revision notes', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Condense a Physics chapter into a one-page sheet with key points, formulas and mnemonics.', 'prompt' => 'Make one-page revision notes for Physics: [CHAPTER].
Include: key definitions, must-know facts or formulas, a small comparison table where useful, 2 mnemonics, a "most asked in exams" list, and a 60-second recap at the end.
Keep it scannable: headings, bullets, bold keywords. Be accurate; mark anything that varies by syllabus.', 'output' => 'A scannable single-page sheet with definitions, formulas or facts, a comparison table, mnemonics and an exam-focus list.', 'image' => '' ),
	array( 'id' => 'explain-a-chemistry-concept-simply', 'title' => 'Explain a Chemistry concept simply', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Understand any Chemistry topic with an analogy, a worked example and a quick check.', 'prompt' => 'You are an expert Chemistry teacher. Explain [TOPIC] to a [CLASS / LEVEL] student.
1. Simple definition in one line.
2. Real-life analogy or example.
3. Step-by-step explanation (with formulas, dates, diagrams-in-words or rules as Chemistry needs).
4. One worked example or case.
5. 3 common mistakes or misconceptions.
6. A 3-question check — wait for my answers, then correct them.
Use simple English and stay accurate; if something depends on my syllabus or board, say so.', 'output' => 'A layered explanation of your Chemistry topic, from one-line definition to worked example, plus a quick interactive check.', 'image' => '' ),
	array( 'id' => 'chemistry-practice-questions-with-answers', 'title' => 'Chemistry practice questions with answers', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Exam-style Chemistry questions at mixed difficulty, with answers and explanations.', 'prompt' => 'Create practice questions on [CHAPTER / TOPIC] in Chemistry for [CLASS / EXAM].
- 5 multiple-choice, 3 short-answer and 2 long-answer questions, from easy to hard.
- Mark the difficulty of each.
- Put the answer key with step-by-step explanations at the END, after a line "--- ANSWERS ---", so I can attempt first.
- Match the style of [BOARD / EXAM] questions.', 'output' => 'Ten mixed-format questions graded by difficulty, with a separate worked answer key so you can test yourself first.', 'image' => '' ),
	array( 'id' => 'chemistry-one-page-revision-notes', 'title' => 'Chemistry one-page revision notes', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Condense a Chemistry chapter into a one-page sheet with key points, formulas and mnemonics.', 'prompt' => 'Make one-page revision notes for Chemistry: [CHAPTER].
Include: key definitions, must-know facts or formulas, a small comparison table where useful, 2 mnemonics, a "most asked in exams" list, and a 60-second recap at the end.
Keep it scannable: headings, bullets, bold keywords. Be accurate; mark anything that varies by syllabus.', 'output' => 'A scannable single-page sheet with definitions, formulas or facts, a comparison table, mnemonics and an exam-focus list.', 'image' => '' ),
	array( 'id' => 'explain-a-biology-concept-simply', 'title' => 'Explain a Biology concept simply', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Understand any Biology topic with an analogy, a worked example and a quick check.', 'prompt' => 'You are an expert Biology teacher. Explain [TOPIC] to a [CLASS / LEVEL] student.
1. Simple definition in one line.
2. Real-life analogy or example.
3. Step-by-step explanation (with formulas, dates, diagrams-in-words or rules as Biology needs).
4. One worked example or case.
5. 3 common mistakes or misconceptions.
6. A 3-question check — wait for my answers, then correct them.
Use simple English and stay accurate; if something depends on my syllabus or board, say so.', 'output' => 'A layered explanation of your Biology topic, from one-line definition to worked example, plus a quick interactive check.', 'image' => '' ),
	array( 'id' => 'biology-practice-questions-with-answers', 'title' => 'Biology practice questions with answers', 'cat' => 'Study & Exams', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'Exam-style Biology questions at mixed difficulty, with answers and explanations.', 'prompt' => 'Create practice questions on [CHAPTER / TOPIC] in Biology for [CLASS / EXAM].
- 5 multiple-choice, 3 short-answer and 2 long-answer questions, from easy to hard.
- Mark the difficulty of each.
- Put the answer key with step-by-step explanations at the END, after a line "--- ANSWERS ---", so I can attempt first.
- Match the style of [BOARD / EXAM] questions.', 'output' => 'Ten mixed-format questions graded by difficulty, with a separate worked answer key so you can test yourself first.', 'image' => '' ),
	array( 'id' => 'biology-one-page-revision-notes', 'title' => 'Biology one-page revision notes', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Condense a Biology chapter into a one-page sheet with key points, formulas and mnemonics.', 'prompt' => 'Make one-page revision notes for Biology: [CHAPTER].
Include: key definitions, must-know facts or formulas, a small comparison table where useful, 2 mnemonics, a "most asked in exams" list, and a 60-second recap at the end.
Keep it scannable: headings, bullets, bold keywords. Be accurate; mark anything that varies by syllabus.', 'output' => 'A scannable single-page sheet with definitions, formulas or facts, a comparison table, mnemonics and an exam-focus list.', 'image' => '' ),
	array( 'id' => 'explain-a-mathematics-concept-simply', 'title' => 'Explain a Mathematics concept simply', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Understand any Mathematics topic with an analogy, a worked example and a quick check.', 'prompt' => 'You are an expert Mathematics teacher. Explain [TOPIC] to a [CLASS / LEVEL] student.
1. Simple definition in one line.
2. Real-life analogy or example.
3. Step-by-step explanation (with formulas, dates, diagrams-in-words or rules as Mathematics needs).
4. One worked example or case.
5. 3 common mistakes or misconceptions.
6. A 3-question check — wait for my answers, then correct them.
Use simple English and stay accurate; if something depends on my syllabus or board, say so.', 'output' => 'A layered explanation of your Mathematics topic, from one-line definition to worked example, plus a quick interactive check.', 'image' => '' ),
	array( 'id' => 'mathematics-practice-questions-with-answers', 'title' => 'Mathematics practice questions with answers', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Exam-style Mathematics questions at mixed difficulty, with answers and explanations.', 'prompt' => 'Create practice questions on [CHAPTER / TOPIC] in Mathematics for [CLASS / EXAM].
- 5 multiple-choice, 3 short-answer and 2 long-answer questions, from easy to hard.
- Mark the difficulty of each.
- Put the answer key with step-by-step explanations at the END, after a line "--- ANSWERS ---", so I can attempt first.
- Match the style of [BOARD / EXAM] questions.', 'output' => 'Ten mixed-format questions graded by difficulty, with a separate worked answer key so you can test yourself first.', 'image' => '' ),
	array( 'id' => 'mathematics-one-page-revision-notes', 'title' => 'Mathematics one-page revision notes', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Condense a Mathematics chapter into a one-page sheet with key points, formulas and mnemonics.', 'prompt' => 'Make one-page revision notes for Mathematics: [CHAPTER].
Include: key definitions, must-know facts or formulas, a small comparison table where useful, 2 mnemonics, a "most asked in exams" list, and a 60-second recap at the end.
Keep it scannable: headings, bullets, bold keywords. Be accurate; mark anything that varies by syllabus.', 'output' => 'A scannable single-page sheet with definitions, formulas or facts, a comparison table, mnemonics and an exam-focus list.', 'image' => '' ),
	array( 'id' => 'explain-a-history-concept-simply', 'title' => 'Explain a History concept simply', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Understand any History topic with an analogy, a worked example and a quick check.', 'prompt' => 'You are an expert History teacher. Explain [TOPIC] to a [CLASS / LEVEL] student.
1. Simple definition in one line.
2. Real-life analogy or example.
3. Step-by-step explanation (with formulas, dates, diagrams-in-words or rules as History needs).
4. One worked example or case.
5. 3 common mistakes or misconceptions.
6. A 3-question check — wait for my answers, then correct them.
Use simple English and stay accurate; if something depends on my syllabus or board, say so.', 'output' => 'A layered explanation of your History topic, from one-line definition to worked example, plus a quick interactive check.', 'image' => '' ),
	array( 'id' => 'history-practice-questions-with-answers', 'title' => 'History practice questions with answers', 'cat' => 'Study & Exams', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Exam-style History questions at mixed difficulty, with answers and explanations.', 'prompt' => 'Create practice questions on [CHAPTER / TOPIC] in History for [CLASS / EXAM].
- 5 multiple-choice, 3 short-answer and 2 long-answer questions, from easy to hard.
- Mark the difficulty of each.
- Put the answer key with step-by-step explanations at the END, after a line "--- ANSWERS ---", so I can attempt first.
- Match the style of [BOARD / EXAM] questions.', 'output' => 'Ten mixed-format questions graded by difficulty, with a separate worked answer key so you can test yourself first.', 'image' => '' ),
	array( 'id' => 'history-one-page-revision-notes', 'title' => 'History one-page revision notes', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Condense a History chapter into a one-page sheet with key points, formulas and mnemonics.', 'prompt' => 'Make one-page revision notes for History: [CHAPTER].
Include: key definitions, must-know facts or formulas, a small comparison table where useful, 2 mnemonics, a "most asked in exams" list, and a 60-second recap at the end.
Keep it scannable: headings, bullets, bold keywords. Be accurate; mark anything that varies by syllabus.', 'output' => 'A scannable single-page sheet with definitions, formulas or facts, a comparison table, mnemonics and an exam-focus list.', 'image' => '' ),
	array( 'id' => 'explain-a-geography-concept-simply', 'title' => 'Explain a Geography concept simply', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Understand any Geography topic with an analogy, a worked example and a quick check.', 'prompt' => 'You are an expert Geography teacher. Explain [TOPIC] to a [CLASS / LEVEL] student.
1. Simple definition in one line.
2. Real-life analogy or example.
3. Step-by-step explanation (with formulas, dates, diagrams-in-words or rules as Geography needs).
4. One worked example or case.
5. 3 common mistakes or misconceptions.
6. A 3-question check — wait for my answers, then correct them.
Use simple English and stay accurate; if something depends on my syllabus or board, say so.', 'output' => 'A layered explanation of your Geography topic, from one-line definition to worked example, plus a quick interactive check.', 'image' => '' ),
	array( 'id' => 'geography-practice-questions-with-answers', 'title' => 'Geography practice questions with answers', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Exam-style Geography questions at mixed difficulty, with answers and explanations.', 'prompt' => 'Create practice questions on [CHAPTER / TOPIC] in Geography for [CLASS / EXAM].
- 5 multiple-choice, 3 short-answer and 2 long-answer questions, from easy to hard.
- Mark the difficulty of each.
- Put the answer key with step-by-step explanations at the END, after a line "--- ANSWERS ---", so I can attempt first.
- Match the style of [BOARD / EXAM] questions.', 'output' => 'Ten mixed-format questions graded by difficulty, with a separate worked answer key so you can test yourself first.', 'image' => '' ),
	array( 'id' => 'geography-one-page-revision-notes', 'title' => 'Geography one-page revision notes', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Condense a Geography chapter into a one-page sheet with key points, formulas and mnemonics.', 'prompt' => 'Make one-page revision notes for Geography: [CHAPTER].
Include: key definitions, must-know facts or formulas, a small comparison table where useful, 2 mnemonics, a "most asked in exams" list, and a 60-second recap at the end.
Keep it scannable: headings, bullets, bold keywords. Be accurate; mark anything that varies by syllabus.', 'output' => 'A scannable single-page sheet with definitions, formulas or facts, a comparison table, mnemonics and an exam-focus list.', 'image' => '' ),
	array( 'id' => 'explain-a-economics-concept-simply', 'title' => 'Explain a Economics concept simply', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Understand any Economics topic with an analogy, a worked example and a quick check.', 'prompt' => 'You are an expert Economics teacher. Explain [TOPIC] to a [CLASS / LEVEL] student.
1. Simple definition in one line.
2. Real-life analogy or example.
3. Step-by-step explanation (with formulas, dates, diagrams-in-words or rules as Economics needs).
4. One worked example or case.
5. 3 common mistakes or misconceptions.
6. A 3-question check — wait for my answers, then correct them.
Use simple English and stay accurate; if something depends on my syllabus or board, say so.', 'output' => 'A layered explanation of your Economics topic, from one-line definition to worked example, plus a quick interactive check.', 'image' => '' ),
	array( 'id' => 'economics-practice-questions-with-answers', 'title' => 'Economics practice questions with answers', 'cat' => 'Study & Exams', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Exam-style Economics questions at mixed difficulty, with answers and explanations.', 'prompt' => 'Create practice questions on [CHAPTER / TOPIC] in Economics for [CLASS / EXAM].
- 5 multiple-choice, 3 short-answer and 2 long-answer questions, from easy to hard.
- Mark the difficulty of each.
- Put the answer key with step-by-step explanations at the END, after a line "--- ANSWERS ---", so I can attempt first.
- Match the style of [BOARD / EXAM] questions.', 'output' => 'Ten mixed-format questions graded by difficulty, with a separate worked answer key so you can test yourself first.', 'image' => '' ),
	array( 'id' => 'economics-one-page-revision-notes', 'title' => 'Economics one-page revision notes', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Condense a Economics chapter into a one-page sheet with key points, formulas and mnemonics.', 'prompt' => 'Make one-page revision notes for Economics: [CHAPTER].
Include: key definitions, must-know facts or formulas, a small comparison table where useful, 2 mnemonics, a "most asked in exams" list, and a 60-second recap at the end.
Keep it scannable: headings, bullets, bold keywords. Be accurate; mark anything that varies by syllabus.', 'output' => 'A scannable single-page sheet with definitions, formulas or facts, a comparison table, mnemonics and an exam-focus list.', 'image' => '' ),
	array( 'id' => 'explain-a-political-science-concept-simply', 'title' => 'Explain a Political Science concept simply', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Understand any Political Science topic with an analogy, a worked example and a quick check.', 'prompt' => 'You are an expert Political Science teacher. Explain [TOPIC] to a [CLASS / LEVEL] student.
1. Simple definition in one line.
2. Real-life analogy or example.
3. Step-by-step explanation (with formulas, dates, diagrams-in-words or rules as Political Science needs).
4. One worked example or case.
5. 3 common mistakes or misconceptions.
6. A 3-question check — wait for my answers, then correct them.
Use simple English and stay accurate; if something depends on my syllabus or board, say so.', 'output' => 'A layered explanation of your Political Science topic, from one-line definition to worked example, plus a quick interactive check.', 'image' => '' ),
	array( 'id' => 'political-science-practice-questions-with-answers', 'title' => 'Political Science practice questions with answers', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Exam-style Political Science questions at mixed difficulty, with answers and explanations.', 'prompt' => 'Create practice questions on [CHAPTER / TOPIC] in Political Science for [CLASS / EXAM].
- 5 multiple-choice, 3 short-answer and 2 long-answer questions, from easy to hard.
- Mark the difficulty of each.
- Put the answer key with step-by-step explanations at the END, after a line "--- ANSWERS ---", so I can attempt first.
- Match the style of [BOARD / EXAM] questions.', 'output' => 'Ten mixed-format questions graded by difficulty, with a separate worked answer key so you can test yourself first.', 'image' => '' ),
	array( 'id' => 'political-science-one-page-revision-notes', 'title' => 'Political Science one-page revision notes', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Condense a Political Science chapter into a one-page sheet with key points, formulas and mnemonics.', 'prompt' => 'Make one-page revision notes for Political Science: [CHAPTER].
Include: key definitions, must-know facts or formulas, a small comparison table where useful, 2 mnemonics, a "most asked in exams" list, and a 60-second recap at the end.
Keep it scannable: headings, bullets, bold keywords. Be accurate; mark anything that varies by syllabus.', 'output' => 'A scannable single-page sheet with definitions, formulas or facts, a comparison table, mnemonics and an exam-focus list.', 'image' => '' ),
	array( 'id' => 'explain-a-accountancy-concept-simply', 'title' => 'Explain a Accountancy concept simply', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Understand any Accountancy topic with an analogy, a worked example and a quick check.', 'prompt' => 'You are an expert Accountancy teacher. Explain [TOPIC] to a [CLASS / LEVEL] student.
1. Simple definition in one line.
2. Real-life analogy or example.
3. Step-by-step explanation (with formulas, dates, diagrams-in-words or rules as Accountancy needs).
4. One worked example or case.
5. 3 common mistakes or misconceptions.
6. A 3-question check — wait for my answers, then correct them.
Use simple English and stay accurate; if something depends on my syllabus or board, say so.', 'output' => 'A layered explanation of your Accountancy topic, from one-line definition to worked example, plus a quick interactive check.', 'image' => '' ),
	array( 'id' => 'accountancy-practice-questions-with-answers', 'title' => 'Accountancy practice questions with answers', 'cat' => 'Study & Exams', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Exam-style Accountancy questions at mixed difficulty, with answers and explanations.', 'prompt' => 'Create practice questions on [CHAPTER / TOPIC] in Accountancy for [CLASS / EXAM].
- 5 multiple-choice, 3 short-answer and 2 long-answer questions, from easy to hard.
- Mark the difficulty of each.
- Put the answer key with step-by-step explanations at the END, after a line "--- ANSWERS ---", so I can attempt first.
- Match the style of [BOARD / EXAM] questions.', 'output' => 'Ten mixed-format questions graded by difficulty, with a separate worked answer key so you can test yourself first.', 'image' => '' ),
	array( 'id' => 'accountancy-one-page-revision-notes', 'title' => 'Accountancy one-page revision notes', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Condense a Accountancy chapter into a one-page sheet with key points, formulas and mnemonics.', 'prompt' => 'Make one-page revision notes for Accountancy: [CHAPTER].
Include: key definitions, must-know facts or formulas, a small comparison table where useful, 2 mnemonics, a "most asked in exams" list, and a 60-second recap at the end.
Keep it scannable: headings, bullets, bold keywords. Be accurate; mark anything that varies by syllabus.', 'output' => 'A scannable single-page sheet with definitions, formulas or facts, a comparison table, mnemonics and an exam-focus list.', 'image' => '' ),
	array( 'id' => 'explain-a-computer-science-concept-simply', 'title' => 'Explain a Computer Science concept simply', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Understand any Computer Science topic with an analogy, a worked example and a quick check.', 'prompt' => 'You are an expert Computer Science teacher. Explain [TOPIC] to a [CLASS / LEVEL] student.
1. Simple definition in one line.
2. Real-life analogy or example.
3. Step-by-step explanation (with formulas, dates, diagrams-in-words or rules as Computer Science needs).
4. One worked example or case.
5. 3 common mistakes or misconceptions.
6. A 3-question check — wait for my answers, then correct them.
Use simple English and stay accurate; if something depends on my syllabus or board, say so.', 'output' => 'A layered explanation of your Computer Science topic, from one-line definition to worked example, plus a quick interactive check.', 'image' => '' ),
	array( 'id' => 'computer-science-practice-questions-with-answers', 'title' => 'Computer Science practice questions with answers', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Exam-style Computer Science questions at mixed difficulty, with answers and explanations.', 'prompt' => 'Create practice questions on [CHAPTER / TOPIC] in Computer Science for [CLASS / EXAM].
- 5 multiple-choice, 3 short-answer and 2 long-answer questions, from easy to hard.
- Mark the difficulty of each.
- Put the answer key with step-by-step explanations at the END, after a line "--- ANSWERS ---", so I can attempt first.
- Match the style of [BOARD / EXAM] questions.', 'output' => 'Ten mixed-format questions graded by difficulty, with a separate worked answer key so you can test yourself first.', 'image' => '' ),
	array( 'id' => 'computer-science-one-page-revision-notes', 'title' => 'Computer Science one-page revision notes', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Condense a Computer Science chapter into a one-page sheet with key points, formulas and mnemonics.', 'prompt' => 'Make one-page revision notes for Computer Science: [CHAPTER].
Include: key definitions, must-know facts or formulas, a small comparison table where useful, 2 mnemonics, a "most asked in exams" list, and a 60-second recap at the end.
Keep it scannable: headings, bullets, bold keywords. Be accurate; mark anything that varies by syllabus.', 'output' => 'A scannable single-page sheet with definitions, formulas or facts, a comparison table, mnemonics and an exam-focus list.', 'image' => '' ),
	array( 'id' => 'explain-a-english-grammar-concept-simply', 'title' => 'Explain a English Grammar concept simply', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Understand any English Grammar topic with an analogy, a worked example and a quick check.', 'prompt' => 'You are an expert English Grammar teacher. Explain [TOPIC] to a [CLASS / LEVEL] student.
1. Simple definition in one line.
2. Real-life analogy or example.
3. Step-by-step explanation (with formulas, dates, diagrams-in-words or rules as English Grammar needs).
4. One worked example or case.
5. 3 common mistakes or misconceptions.
6. A 3-question check — wait for my answers, then correct them.
Use simple English and stay accurate; if something depends on my syllabus or board, say so.', 'output' => 'A layered explanation of your English Grammar topic, from one-line definition to worked example, plus a quick interactive check.', 'image' => '' ),
	array( 'id' => 'english-grammar-practice-questions-with-answers', 'title' => 'English Grammar practice questions with answers', 'cat' => 'Study & Exams', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Exam-style English Grammar questions at mixed difficulty, with answers and explanations.', 'prompt' => 'Create practice questions on [CHAPTER / TOPIC] in English Grammar for [CLASS / EXAM].
- 5 multiple-choice, 3 short-answer and 2 long-answer questions, from easy to hard.
- Mark the difficulty of each.
- Put the answer key with step-by-step explanations at the END, after a line "--- ANSWERS ---", so I can attempt first.
- Match the style of [BOARD / EXAM] questions.', 'output' => 'Ten mixed-format questions graded by difficulty, with a separate worked answer key so you can test yourself first.', 'image' => '' ),
	array( 'id' => 'english-grammar-one-page-revision-notes', 'title' => 'English Grammar one-page revision notes', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Condense a English Grammar chapter into a one-page sheet with key points, formulas and mnemonics.', 'prompt' => 'Make one-page revision notes for English Grammar: [CHAPTER].
Include: key definitions, must-know facts or formulas, a small comparison table where useful, 2 mnemonics, a "most asked in exams" list, and a 60-second recap at the end.
Keep it scannable: headings, bullets, bold keywords. Be accurate; mark anything that varies by syllabus.', 'output' => 'A scannable single-page sheet with definitions, formulas or facts, a comparison table, mnemonics and an exam-focus list.', 'image' => '' ),
	array( 'id' => 'explain-a-statistics-concept-simply', 'title' => 'Explain a Statistics concept simply', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Understand any Statistics topic with an analogy, a worked example and a quick check.', 'prompt' => 'You are an expert Statistics teacher. Explain [TOPIC] to a [CLASS / LEVEL] student.
1. Simple definition in one line.
2. Real-life analogy or example.
3. Step-by-step explanation (with formulas, dates, diagrams-in-words or rules as Statistics needs).
4. One worked example or case.
5. 3 common mistakes or misconceptions.
6. A 3-question check — wait for my answers, then correct them.
Use simple English and stay accurate; if something depends on my syllabus or board, say so.', 'output' => 'A layered explanation of your Statistics topic, from one-line definition to worked example, plus a quick interactive check.', 'image' => '' ),
	array( 'id' => 'statistics-practice-questions-with-answers', 'title' => 'Statistics practice questions with answers', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Exam-style Statistics questions at mixed difficulty, with answers and explanations.', 'prompt' => 'Create practice questions on [CHAPTER / TOPIC] in Statistics for [CLASS / EXAM].
- 5 multiple-choice, 3 short-answer and 2 long-answer questions, from easy to hard.
- Mark the difficulty of each.
- Put the answer key with step-by-step explanations at the END, after a line "--- ANSWERS ---", so I can attempt first.
- Match the style of [BOARD / EXAM] questions.', 'output' => 'Ten mixed-format questions graded by difficulty, with a separate worked answer key so you can test yourself first.', 'image' => '' ),
	array( 'id' => 'statistics-one-page-revision-notes', 'title' => 'Statistics one-page revision notes', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Condense a Statistics chapter into a one-page sheet with key points, formulas and mnemonics.', 'prompt' => 'Make one-page revision notes for Statistics: [CHAPTER].
Include: key definitions, must-know facts or formulas, a small comparison table where useful, 2 mnemonics, a "most asked in exams" list, and a 60-second recap at the end.
Keep it scannable: headings, bullets, bold keywords. Be accurate; mark anything that varies by syllabus.', 'output' => 'A scannable single-page sheet with definitions, formulas or facts, a comparison table, mnemonics and an exam-focus list.', 'image' => '' ),
	array( 'id' => 'explain-a-business-studies-concept-simply', 'title' => 'Explain a Business Studies concept simply', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Understand any Business Studies topic with an analogy, a worked example and a quick check.', 'prompt' => 'You are an expert Business Studies teacher. Explain [TOPIC] to a [CLASS / LEVEL] student.
1. Simple definition in one line.
2. Real-life analogy or example.
3. Step-by-step explanation (with formulas, dates, diagrams-in-words or rules as Business Studies needs).
4. One worked example or case.
5. 3 common mistakes or misconceptions.
6. A 3-question check — wait for my answers, then correct them.
Use simple English and stay accurate; if something depends on my syllabus or board, say so.', 'output' => 'A layered explanation of your Business Studies topic, from one-line definition to worked example, plus a quick interactive check.', 'image' => '' ),
	array( 'id' => 'business-studies-practice-questions-with-answers', 'title' => 'Business Studies practice questions with answers', 'cat' => 'Study & Exams', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'Exam-style Business Studies questions at mixed difficulty, with answers and explanations.', 'prompt' => 'Create practice questions on [CHAPTER / TOPIC] in Business Studies for [CLASS / EXAM].
- 5 multiple-choice, 3 short-answer and 2 long-answer questions, from easy to hard.
- Mark the difficulty of each.
- Put the answer key with step-by-step explanations at the END, after a line "--- ANSWERS ---", so I can attempt first.
- Match the style of [BOARD / EXAM] questions.', 'output' => 'Ten mixed-format questions graded by difficulty, with a separate worked answer key so you can test yourself first.', 'image' => '' ),
	array( 'id' => 'business-studies-one-page-revision-notes', 'title' => 'Business Studies one-page revision notes', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Condense a Business Studies chapter into a one-page sheet with key points, formulas and mnemonics.', 'prompt' => 'Make one-page revision notes for Business Studies: [CHAPTER].
Include: key definitions, must-know facts or formulas, a small comparison table where useful, 2 mnemonics, a "most asked in exams" list, and a 60-second recap at the end.
Keep it scannable: headings, bullets, bold keywords. Be accurate; mark anything that varies by syllabus.', 'output' => 'A scannable single-page sheet with definitions, formulas or facts, a comparison table, mnemonics and an exam-focus list.', 'image' => '' ),
	array( 'id' => 'explain-a-environmental-science-concept-simply', 'title' => 'Explain a Environmental Science concept simply', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Understand any Environmental Science topic with an analogy, a worked example and a quick check.', 'prompt' => 'You are an expert Environmental Science teacher. Explain [TOPIC] to a [CLASS / LEVEL] student.
1. Simple definition in one line.
2. Real-life analogy or example.
3. Step-by-step explanation (with formulas, dates, diagrams-in-words or rules as Environmental Science needs).
4. One worked example or case.
5. 3 common mistakes or misconceptions.
6. A 3-question check — wait for my answers, then correct them.
Use simple English and stay accurate; if something depends on my syllabus or board, say so.', 'output' => 'A layered explanation of your Environmental Science topic, from one-line definition to worked example, plus a quick interactive check.', 'image' => '' ),
	array( 'id' => 'environmental-science-practice-questions-with-answers', 'title' => 'Environmental Science practice questions with answers', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Exam-style Environmental Science questions at mixed difficulty, with answers and explanations.', 'prompt' => 'Create practice questions on [CHAPTER / TOPIC] in Environmental Science for [CLASS / EXAM].
- 5 multiple-choice, 3 short-answer and 2 long-answer questions, from easy to hard.
- Mark the difficulty of each.
- Put the answer key with step-by-step explanations at the END, after a line "--- ANSWERS ---", so I can attempt first.
- Match the style of [BOARD / EXAM] questions.', 'output' => 'Ten mixed-format questions graded by difficulty, with a separate worked answer key so you can test yourself first.', 'image' => '' ),
	array( 'id' => 'environmental-science-one-page-revision-notes', 'title' => 'Environmental Science one-page revision notes', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Condense a Environmental Science chapter into a one-page sheet with key points, formulas and mnemonics.', 'prompt' => 'Make one-page revision notes for Environmental Science: [CHAPTER].
Include: key definitions, must-know facts or formulas, a small comparison table where useful, 2 mnemonics, a "most asked in exams" list, and a 60-second recap at the end.
Keep it scannable: headings, bullets, bold keywords. Be accurate; mark anything that varies by syllabus.', 'output' => 'A scannable single-page sheet with definitions, formulas or facts, a comparison table, mnemonics and an exam-focus list.', 'image' => '' ),
	array( 'id' => 'explain-a-psychology-concept-simply', 'title' => 'Explain a Psychology concept simply', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Understand any Psychology topic with an analogy, a worked example and a quick check.', 'prompt' => 'You are an expert Psychology teacher. Explain [TOPIC] to a [CLASS / LEVEL] student.
1. Simple definition in one line.
2. Real-life analogy or example.
3. Step-by-step explanation (with formulas, dates, diagrams-in-words or rules as Psychology needs).
4. One worked example or case.
5. 3 common mistakes or misconceptions.
6. A 3-question check — wait for my answers, then correct them.
Use simple English and stay accurate; if something depends on my syllabus or board, say so.', 'output' => 'A layered explanation of your Psychology topic, from one-line definition to worked example, plus a quick interactive check.', 'image' => '' ),
	array( 'id' => 'psychology-practice-questions-with-answers', 'title' => 'Psychology practice questions with answers', 'cat' => 'Study & Exams', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Exam-style Psychology questions at mixed difficulty, with answers and explanations.', 'prompt' => 'Create practice questions on [CHAPTER / TOPIC] in Psychology for [CLASS / EXAM].
- 5 multiple-choice, 3 short-answer and 2 long-answer questions, from easy to hard.
- Mark the difficulty of each.
- Put the answer key with step-by-step explanations at the END, after a line "--- ANSWERS ---", so I can attempt first.
- Match the style of [BOARD / EXAM] questions.', 'output' => 'Ten mixed-format questions graded by difficulty, with a separate worked answer key so you can test yourself first.', 'image' => '' ),
	array( 'id' => 'psychology-one-page-revision-notes', 'title' => 'Psychology one-page revision notes', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Condense a Psychology chapter into a one-page sheet with key points, formulas and mnemonics.', 'prompt' => 'Make one-page revision notes for Psychology: [CHAPTER].
Include: key definitions, must-know facts or formulas, a small comparison table where useful, 2 mnemonics, a "most asked in exams" list, and a 60-second recap at the end.
Keep it scannable: headings, bullets, bold keywords. Be accurate; mark anything that varies by syllabus.', 'output' => 'A scannable single-page sheet with definitions, formulas or facts, a comparison table, mnemonics and an exam-focus list.', 'image' => '' ),
	array( 'id' => 'jee-main-study-plan', 'title' => 'JEE Main study plan', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A realistic, week-by-week JEE Main plan around your weak areas and free time.', 'prompt' => 'Make a study plan for JEE Main. Sections: Physics, Chemistry and Mathematics.
Exam date: [DATE]. Today: [DATE]. Hours per day: weekdays [X], weekends [Y].
Strong areas: [LIST]. Weak areas: [LIST]. Current mock score: [SCORE].
Include: weekly targets, daily schedule, spaced revision of weak topics, full-length mock tests with analysis days, and a final-week plan.
Output as a table (Week | Focus | Daily tasks | Mock/test) plus 5 rules to stay consistent.
Remind me to check the official JEE Main website for the latest pattern and dates.', 'output' => 'A week-by-week JEE Main table with daily tasks, spaced revision and mock-test days, plus consistency rules and a reminder to confirm the official pattern.', 'image' => '' ),
	array( 'id' => 'jee-main-mock-test-and-analysis', 'title' => 'JEE Main mock test and analysis', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Take a short JEE Main-style mock in chat and get a mistake analysis.', 'prompt' => 'Give me a short JEE Main-style mock test on [SECTION / TOPIC] (Physics, Chemistry and Mathematics).
- 10 questions in the real exam\'s style and difficulty. Ask them one by one and wait for my answer.
- After all 10: score, time-saving tips, and a mistake analysis grouped by concept gap, silly error or time pressure.
- Recommend the next 3 topics to practise.
Do not claim the questions are official.', 'output' => 'An interactive ten-question mock with a score, mistake analysis by type and a short next-steps list.', 'image' => '' ),
	array( 'id' => 'neet-ug-study-plan', 'title' => 'NEET UG study plan', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'A realistic, week-by-week NEET UG plan around your weak areas and free time.', 'prompt' => 'Make a study plan for NEET UG. Sections: Physics, Chemistry, Botany and Zoology.
Exam date: [DATE]. Today: [DATE]. Hours per day: weekdays [X], weekends [Y].
Strong areas: [LIST]. Weak areas: [LIST]. Current mock score: [SCORE].
Include: weekly targets, daily schedule, spaced revision of weak topics, full-length mock tests with analysis days, and a final-week plan.
Output as a table (Week | Focus | Daily tasks | Mock/test) plus 5 rules to stay consistent.
Remind me to check the official NEET UG website for the latest pattern and dates.', 'output' => 'A week-by-week NEET UG table with daily tasks, spaced revision and mock-test days, plus consistency rules and a reminder to confirm the official pattern.', 'image' => '' ),
	array( 'id' => 'neet-ug-mock-test-and-analysis', 'title' => 'NEET UG mock test and analysis', 'cat' => 'Study & Exams', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Take a short NEET UG-style mock in chat and get a mistake analysis.', 'prompt' => 'Give me a short NEET UG-style mock test on [SECTION / TOPIC] (Physics, Chemistry, Botany and Zoology).
- 10 questions in the real exam\'s style and difficulty. Ask them one by one and wait for my answer.
- After all 10: score, time-saving tips, and a mistake analysis grouped by concept gap, silly error or time pressure.
- Recommend the next 3 topics to practise.
Do not claim the questions are official.', 'output' => 'An interactive ten-question mock with a score, mistake analysis by type and a short next-steps list.', 'image' => '' ),
	array( 'id' => 'upsc-cse-prelims-study-plan', 'title' => 'UPSC CSE Prelims study plan', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A realistic, week-by-week UPSC CSE Prelims plan around your weak areas and free time.', 'prompt' => 'Make a study plan for UPSC CSE Prelims. Sections: General Studies Paper I and CSAT (Paper II, qualifying).
Exam date: [DATE]. Today: [DATE]. Hours per day: weekdays [X], weekends [Y].
Strong areas: [LIST]. Weak areas: [LIST]. Current mock score: [SCORE].
Include: weekly targets, daily schedule, spaced revision of weak topics, full-length mock tests with analysis days, and a final-week plan.
Output as a table (Week | Focus | Daily tasks | Mock/test) plus 5 rules to stay consistent.
Remind me to check the official UPSC CSE Prelims website for the latest pattern and dates.', 'output' => 'A week-by-week UPSC CSE Prelims table with daily tasks, spaced revision and mock-test days, plus consistency rules and a reminder to confirm the official pattern.', 'image' => '' ),
	array( 'id' => 'upsc-cse-prelims-mock-test-and-analysis', 'title' => 'UPSC CSE Prelims mock test and analysis', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Take a short UPSC CSE Prelims-style mock in chat and get a mistake analysis.', 'prompt' => 'Give me a short UPSC CSE Prelims-style mock test on [SECTION / TOPIC] (General Studies Paper I and CSAT (Paper II, qualifying)).
- 10 questions in the real exam\'s style and difficulty. Ask them one by one and wait for my answer.
- After all 10: score, time-saving tips, and a mistake analysis grouped by concept gap, silly error or time pressure.
- Recommend the next 3 topics to practise.
Do not claim the questions are official.', 'output' => 'An interactive ten-question mock with a score, mistake analysis by type and a short next-steps list.', 'image' => '' ),
	array( 'id' => 'cat-study-plan', 'title' => 'CAT study plan', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A realistic, week-by-week CAT plan around your weak areas and free time.', 'prompt' => 'Make a study plan for CAT. Sections: VARC (Verbal Ability and Reading Comprehension), DILR (Data Interpretation and Logical Reasoning) and QA (Quantitative Ability).
Exam date: [DATE]. Today: [DATE]. Hours per day: weekdays [X], weekends [Y].
Strong areas: [LIST]. Weak areas: [LIST]. Current mock score: [SCORE].
Include: weekly targets, daily schedule, spaced revision of weak topics, full-length mock tests with analysis days, and a final-week plan.
Output as a table (Week | Focus | Daily tasks | Mock/test) plus 5 rules to stay consistent.
Remind me to check the official CAT website for the latest pattern and dates.', 'output' => 'A week-by-week CAT table with daily tasks, spaced revision and mock-test days, plus consistency rules and a reminder to confirm the official pattern.', 'image' => '' ),
	array( 'id' => 'cat-mock-test-and-analysis', 'title' => 'CAT mock test and analysis', 'cat' => 'Study & Exams', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Take a short CAT-style mock in chat and get a mistake analysis.', 'prompt' => 'Give me a short CAT-style mock test on [SECTION / TOPIC] (VARC (Verbal Ability and Reading Comprehension), DILR (Data Interpretation and Logical Reasoning) and QA (Quantitative Ability)).
- 10 questions in the real exam\'s style and difficulty. Ask them one by one and wait for my answer.
- After all 10: score, time-saving tips, and a mistake analysis grouped by concept gap, silly error or time pressure.
- Recommend the next 3 topics to practise.
Do not claim the questions are official.', 'output' => 'An interactive ten-question mock with a score, mistake analysis by type and a short next-steps list.', 'image' => '' ),
	array( 'id' => 'gate-study-plan', 'title' => 'GATE study plan', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A realistic, week-by-week GATE plan around your weak areas and free time.', 'prompt' => 'Make a study plan for GATE. Sections: General Aptitude and your engineering/science subject paper.
Exam date: [DATE]. Today: [DATE]. Hours per day: weekdays [X], weekends [Y].
Strong areas: [LIST]. Weak areas: [LIST]. Current mock score: [SCORE].
Include: weekly targets, daily schedule, spaced revision of weak topics, full-length mock tests with analysis days, and a final-week plan.
Output as a table (Week | Focus | Daily tasks | Mock/test) plus 5 rules to stay consistent.
Remind me to check the official GATE website for the latest pattern and dates.', 'output' => 'A week-by-week GATE table with daily tasks, spaced revision and mock-test days, plus consistency rules and a reminder to confirm the official pattern.', 'image' => '' ),
	array( 'id' => 'gate-mock-test-and-analysis', 'title' => 'GATE mock test and analysis', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Take a short GATE-style mock in chat and get a mistake analysis.', 'prompt' => 'Give me a short GATE-style mock test on [SECTION / TOPIC] (General Aptitude and your engineering/science subject paper).
- 10 questions in the real exam\'s style and difficulty. Ask them one by one and wait for my answer.
- After all 10: score, time-saving tips, and a mistake analysis grouped by concept gap, silly error or time pressure.
- Recommend the next 3 topics to practise.
Do not claim the questions are official.', 'output' => 'An interactive ten-question mock with a score, mistake analysis by type and a short next-steps list.', 'image' => '' ),
	array( 'id' => 'cbse-class-10-boards-study-plan', 'title' => 'CBSE Class 10 boards study plan', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A realistic, week-by-week CBSE Class 10 boards plan around your weak areas and free time.', 'prompt' => 'Make a study plan for CBSE Class 10 boards. Sections: Mathematics, Science, Social Science, English and your second language.
Exam date: [DATE]. Today: [DATE]. Hours per day: weekdays [X], weekends [Y].
Strong areas: [LIST]. Weak areas: [LIST]. Current mock score: [SCORE].
Include: weekly targets, daily schedule, spaced revision of weak topics, full-length mock tests with analysis days, and a final-week plan.
Output as a table (Week | Focus | Daily tasks | Mock/test) plus 5 rules to stay consistent.
Remind me to check the official CBSE Class 10 boards website for the latest pattern and dates.', 'output' => 'A week-by-week CBSE Class 10 boards table with daily tasks, spaced revision and mock-test days, plus consistency rules and a reminder to confirm the official pattern.', 'image' => '' ),
	array( 'id' => 'cbse-class-10-boards-mock-test-and-analysis', 'title' => 'CBSE Class 10 boards mock test and analysis', 'cat' => 'Study & Exams', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Take a short CBSE Class 10 boards-style mock in chat and get a mistake analysis.', 'prompt' => 'Give me a short CBSE Class 10 boards-style mock test on [SECTION / TOPIC] (Mathematics, Science, Social Science, English and your second language).
- 10 questions in the real exam\'s style and difficulty. Ask them one by one and wait for my answer.
- After all 10: score, time-saving tips, and a mistake analysis grouped by concept gap, silly error or time pressure.
- Recommend the next 3 topics to practise.
Do not claim the questions are official.', 'output' => 'An interactive ten-question mock with a score, mistake analysis by type and a short next-steps list.', 'image' => '' ),
	array( 'id' => 'cbse-class-12-boards-study-plan', 'title' => 'CBSE Class 12 boards study plan', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'A realistic, week-by-week CBSE Class 12 boards plan around your weak areas and free time.', 'prompt' => 'Make a study plan for CBSE Class 12 boards. Sections: your stream subjects and English.
Exam date: [DATE]. Today: [DATE]. Hours per day: weekdays [X], weekends [Y].
Strong areas: [LIST]. Weak areas: [LIST]. Current mock score: [SCORE].
Include: weekly targets, daily schedule, spaced revision of weak topics, full-length mock tests with analysis days, and a final-week plan.
Output as a table (Week | Focus | Daily tasks | Mock/test) plus 5 rules to stay consistent.
Remind me to check the official CBSE Class 12 boards website for the latest pattern and dates.', 'output' => 'A week-by-week CBSE Class 12 boards table with daily tasks, spaced revision and mock-test days, plus consistency rules and a reminder to confirm the official pattern.', 'image' => '' ),
	array( 'id' => 'cbse-class-12-boards-mock-test-and-analysis', 'title' => 'CBSE Class 12 boards mock test and analysis', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Take a short CBSE Class 12 boards-style mock in chat and get a mistake analysis.', 'prompt' => 'Give me a short CBSE Class 12 boards-style mock test on [SECTION / TOPIC] (your stream subjects and English).
- 10 questions in the real exam\'s style and difficulty. Ask them one by one and wait for my answer.
- After all 10: score, time-saving tips, and a mistake analysis grouped by concept gap, silly error or time pressure.
- Recommend the next 3 topics to practise.
Do not claim the questions are official.', 'output' => 'An interactive ten-question mock with a score, mistake analysis by type and a short next-steps list.', 'image' => '' ),
	array( 'id' => 'sat-study-plan', 'title' => 'SAT study plan', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A realistic, week-by-week SAT plan around your weak areas and free time.', 'prompt' => 'Make a study plan for SAT. Sections: Reading and Writing, and Math (digital, adaptive).
Exam date: [DATE]. Today: [DATE]. Hours per day: weekdays [X], weekends [Y].
Strong areas: [LIST]. Weak areas: [LIST]. Current mock score: [SCORE].
Include: weekly targets, daily schedule, spaced revision of weak topics, full-length mock tests with analysis days, and a final-week plan.
Output as a table (Week | Focus | Daily tasks | Mock/test) plus 5 rules to stay consistent.
Remind me to check the official SAT website for the latest pattern and dates.', 'output' => 'A week-by-week SAT table with daily tasks, spaced revision and mock-test days, plus consistency rules and a reminder to confirm the official pattern.', 'image' => '' ),
	array( 'id' => 'sat-mock-test-and-analysis', 'title' => 'SAT mock test and analysis', 'cat' => 'Study & Exams', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'Take a short SAT-style mock in chat and get a mistake analysis.', 'prompt' => 'Give me a short SAT-style mock test on [SECTION / TOPIC] (Reading and Writing, and Math (digital, adaptive)).
- 10 questions in the real exam\'s style and difficulty. Ask them one by one and wait for my answer.
- After all 10: score, time-saving tips, and a mistake analysis grouped by concept gap, silly error or time pressure.
- Recommend the next 3 topics to practise.
Do not claim the questions are official.', 'output' => 'An interactive ten-question mock with a score, mistake analysis by type and a short next-steps list.', 'image' => '' ),
	array( 'id' => 'ielts-academic-study-plan', 'title' => 'IELTS Academic study plan', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A realistic, week-by-week IELTS Academic plan around your weak areas and free time.', 'prompt' => 'Make a study plan for IELTS Academic. Sections: Listening, Reading, Writing and Speaking.
Exam date: [DATE]. Today: [DATE]. Hours per day: weekdays [X], weekends [Y].
Strong areas: [LIST]. Weak areas: [LIST]. Current mock score: [SCORE].
Include: weekly targets, daily schedule, spaced revision of weak topics, full-length mock tests with analysis days, and a final-week plan.
Output as a table (Week | Focus | Daily tasks | Mock/test) plus 5 rules to stay consistent.
Remind me to check the official IELTS Academic website for the latest pattern and dates.', 'output' => 'A week-by-week IELTS Academic table with daily tasks, spaced revision and mock-test days, plus consistency rules and a reminder to confirm the official pattern.', 'image' => '' ),
	array( 'id' => 'ielts-academic-mock-test-and-analysis', 'title' => 'IELTS Academic mock test and analysis', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Take a short IELTS Academic-style mock in chat and get a mistake analysis.', 'prompt' => 'Give me a short IELTS Academic-style mock test on [SECTION / TOPIC] (Listening, Reading, Writing and Speaking).
- 10 questions in the real exam\'s style and difficulty. Ask them one by one and wait for my answer.
- After all 10: score, time-saving tips, and a mistake analysis grouped by concept gap, silly error or time pressure.
- Recommend the next 3 topics to practise.
Do not claim the questions are official.', 'output' => 'An interactive ten-question mock with a score, mistake analysis by type and a short next-steps list.', 'image' => '' ),
	array( 'id' => 'gre-study-plan', 'title' => 'GRE study plan', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A realistic, week-by-week GRE plan around your weak areas and free time.', 'prompt' => 'Make a study plan for GRE. Sections: Verbal Reasoning, Quantitative Reasoning and Analytical Writing.
Exam date: [DATE]. Today: [DATE]. Hours per day: weekdays [X], weekends [Y].
Strong areas: [LIST]. Weak areas: [LIST]. Current mock score: [SCORE].
Include: weekly targets, daily schedule, spaced revision of weak topics, full-length mock tests with analysis days, and a final-week plan.
Output as a table (Week | Focus | Daily tasks | Mock/test) plus 5 rules to stay consistent.
Remind me to check the official GRE website for the latest pattern and dates.', 'output' => 'A week-by-week GRE table with daily tasks, spaced revision and mock-test days, plus consistency rules and a reminder to confirm the official pattern.', 'image' => '' ),
	array( 'id' => 'gre-mock-test-and-analysis', 'title' => 'GRE mock test and analysis', 'cat' => 'Study & Exams', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'Take a short GRE-style mock in chat and get a mistake analysis.', 'prompt' => 'Give me a short GRE-style mock test on [SECTION / TOPIC] (Verbal Reasoning, Quantitative Reasoning and Analytical Writing).
- 10 questions in the real exam\'s style and difficulty. Ask them one by one and wait for my answer.
- After all 10: score, time-saving tips, and a mistake analysis grouped by concept gap, silly error or time pressure.
- Recommend the next 3 topics to practise.
Do not claim the questions are official.', 'output' => 'An interactive ten-question mock with a score, mistake analysis by type and a short next-steps list.', 'image' => '' ),
	array( 'id' => 'gmat-focus-edition-study-plan', 'title' => 'GMAT Focus Edition study plan', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A realistic, week-by-week GMAT Focus Edition plan around your weak areas and free time.', 'prompt' => 'Make a study plan for GMAT Focus Edition. Sections: Quantitative Reasoning, Verbal Reasoning and Data Insights.
Exam date: [DATE]. Today: [DATE]. Hours per day: weekdays [X], weekends [Y].
Strong areas: [LIST]. Weak areas: [LIST]. Current mock score: [SCORE].
Include: weekly targets, daily schedule, spaced revision of weak topics, full-length mock tests with analysis days, and a final-week plan.
Output as a table (Week | Focus | Daily tasks | Mock/test) plus 5 rules to stay consistent.
Remind me to check the official GMAT Focus Edition website for the latest pattern and dates.', 'output' => 'A week-by-week GMAT Focus Edition table with daily tasks, spaced revision and mock-test days, plus consistency rules and a reminder to confirm the official pattern.', 'image' => '' ),
	array( 'id' => 'gmat-focus-edition-mock-test-and-analysis', 'title' => 'GMAT Focus Edition mock test and analysis', 'cat' => 'Study & Exams', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Take a short GMAT Focus Edition-style mock in chat and get a mistake analysis.', 'prompt' => 'Give me a short GMAT Focus Edition-style mock test on [SECTION / TOPIC] (Quantitative Reasoning, Verbal Reasoning and Data Insights).
- 10 questions in the real exam\'s style and difficulty. Ask them one by one and wait for my answer.
- After all 10: score, time-saving tips, and a mistake analysis grouped by concept gap, silly error or time pressure.
- Recommend the next 3 topics to practise.
Do not claim the questions are official.', 'output' => 'An interactive ten-question mock with a score, mistake analysis by type and a short next-steps list.', 'image' => '' ),
	array( 'id' => 'toefl-ibt-study-plan', 'title' => 'TOEFL iBT study plan', 'cat' => 'Study & Exams', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'A realistic, week-by-week TOEFL iBT plan around your weak areas and free time.', 'prompt' => 'Make a study plan for TOEFL iBT. Sections: Reading, Listening, Speaking and Writing.
Exam date: [DATE]. Today: [DATE]. Hours per day: weekdays [X], weekends [Y].
Strong areas: [LIST]. Weak areas: [LIST]. Current mock score: [SCORE].
Include: weekly targets, daily schedule, spaced revision of weak topics, full-length mock tests with analysis days, and a final-week plan.
Output as a table (Week | Focus | Daily tasks | Mock/test) plus 5 rules to stay consistent.
Remind me to check the official TOEFL iBT website for the latest pattern and dates.', 'output' => 'A week-by-week TOEFL iBT table with daily tasks, spaced revision and mock-test days, plus consistency rules and a reminder to confirm the official pattern.', 'image' => '' ),
	array( 'id' => 'toefl-ibt-mock-test-and-analysis', 'title' => 'TOEFL iBT mock test and analysis', 'cat' => 'Study & Exams', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Take a short TOEFL iBT-style mock in chat and get a mistake analysis.', 'prompt' => 'Give me a short TOEFL iBT-style mock test on [SECTION / TOPIC] (Reading, Listening, Speaking and Writing).
- 10 questions in the real exam\'s style and difficulty. Ask them one by one and wait for my answer.
- After all 10: score, time-saving tips, and a mistake analysis grouped by concept gap, silly error or time pressure.
- Recommend the next 3 topics to practise.
Do not claim the questions are official.', 'output' => 'An interactive ten-question mock with a score, mistake analysis by type and a short next-steps list.', 'image' => '' ),
	array( 'id' => '30-day-instagram-content-calendar', 'title' => '30-day Instagram content calendar', 'cat' => 'Social Media', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A month of Instagram post ideas with formats, hooks and a posting rhythm.', 'prompt' => 'Create a 30-day Instagram content calendar for [BRAND / CREATOR] in [NICHE].
Audience: [WHO]. Goal: [FOLLOWERS / LEADS / SALES]. I can post [X] times a week.
Platform notes: Reels, carousels and stories; captions can be long but the first line must hook; use 3–5 relevant hashtags.
Mix content pillars: education, behind the scenes, social proof, engagement, offers (max 20% promotional).
Table: Day | Pillar | Format | Hook / title | Key message | Call to action.', 'output' => 'A 30-row table balanced across content pillars and Instagram formats, each with a hook and call to action, ready to copy into a planner.', 'image' => '' ),
	array( 'id' => 'instagram-captions-that-convert', 'title' => 'Instagram captions that convert', 'cat' => 'Social Media', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'Five Instagram captions in different styles for one post, with a clear call to action.', 'prompt' => 'Write 5 Instagram captions for this post: [DESCRIBE THE POST / PRODUCT / OFFER].
Brand voice: [FUN / PREMIUM / FRIENDLY / EXPERT]. Audience: [WHO].
Styles: 1) story, 2) list, 3) question, 4) bold statement, 5) short and punchy.
Follow Instagram norms: Reels, carousels and stories; captions can be long but the first line must hook; use 3–5 relevant hashtags.
Each caption: hook line, value, call to action. Add suggested hashtags or keywords only where the platform uses them.', 'output' => 'Five distinct captions with hooks and calls to action that fit the platform\'s length and style.', 'image' => '' ),
	array( 'id' => 'instagram-hooks-for-the-first-line', 'title' => 'Instagram hooks for the first line', 'cat' => 'Social Media', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => '20 scroll-stopping Instagram opening lines for your niche.', 'prompt' => 'Give me 20 opening hooks for Instagram posts about [TOPIC] for [AUDIENCE].
Mix: curiosity, contrarian, number/list, mistake, transformation, question.
Max 12 words each. Label each with its type. Avoid clickbait that the post cannot deliver.
Then pick the 3 strongest and explain why.', 'output' => 'Twenty labelled hooks under twelve words, and the top three with reasons, to test against each other.', 'image' => '' ),
	array( 'id' => 'optimise-your-instagram-profile', 'title' => 'Optimise your Instagram profile', 'cat' => 'Social Media', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A Instagram profile audit with a new bio, name keywords and pinned-content plan.', 'prompt' => 'Audit and rewrite my Instagram profile.
Current bio/profile text: [PASTE]. Niche: [NICHE]. Goal: [GOAL]. Link: [LINK].
Give: 3 bio options within the platform\'s character limit, a searchable name/headline field, what to pin or feature first, and 5 quick fixes that build trust.
Platform notes: Reels, carousels and stories; captions can be long but the first line must hook; use 3–5 relevant hashtags.', 'output' => 'Three bio options that fit the character limit, keyword ideas, a pinned-content plan and quick trust fixes.', 'image' => '' ),
	array( 'id' => 'instagram-carousel-outline', 'title' => 'Instagram carousel outline', 'cat' => 'Social Media', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn one idea into a ready-to-design Instagram post sequence.', 'prompt' => 'Turn this idea into a Instagram multi-part post: [IDEA / BLOG LINK / KEY POINTS].
Platform notes: Reels, carousels and stories; captions can be long but the first line must hook; use 3–5 relevant hashtags.
Give: a title/hook part, 6–8 value parts (one idea each, short sentences), and a final part with a call to action.
For each part add a short note on the visual or on-screen text.', 'output' => 'A part-by-part outline with hook, value steps, call to action and visual notes, ready to design or record.', 'image' => '' ),
	array( 'id' => 'instagram-comment-and-dm-replies', 'title' => 'Instagram comment and DM replies', 'cat' => 'Social Media', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Friendly, on-brand replies for common Instagram comments, questions and complaints.', 'prompt' => 'Write reply templates for my Instagram account ([BRAND], voice: [VOICE]).
Cases: thank-you comment, price question, "is this available?", shipping/delivery question, complaint, negative troll comment, collaboration request, and a lead asking for details.
For each: 2 short reply options that sound human, plus when to move the chat to DM or support.
Never promise things I have not listed: [POLICIES].', 'output' => 'Two human-sounding replies for each common case, with guidance on when to move a conversation private.', 'image' => '' ),
	array( 'id' => '30-day-linkedin-content-calendar', 'title' => '30-day LinkedIn content calendar', 'cat' => 'Social Media', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A month of LinkedIn post ideas with formats, hooks and a posting rhythm.', 'prompt' => 'Create a 30-day LinkedIn content calendar for [BRAND / CREATOR] in [NICHE].
Audience: [WHO]. Goal: [FOLLOWERS / LEADS / SALES]. I can post [X] times a week.
Platform notes: text posts, documents (PDF carousels) and short videos; only the first 2–3 lines show before "see more".
Mix content pillars: education, behind the scenes, social proof, engagement, offers (max 20% promotional).
Table: Day | Pillar | Format | Hook / title | Key message | Call to action.', 'output' => 'A 30-row table balanced across content pillars and LinkedIn formats, each with a hook and call to action, ready to copy into a planner.', 'image' => '' ),
	array( 'id' => 'linkedin-captions-that-convert', 'title' => 'LinkedIn captions that convert', 'cat' => 'Social Media', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Five LinkedIn captions in different styles for one post, with a clear call to action.', 'prompt' => 'Write 5 LinkedIn captions for this post: [DESCRIBE THE POST / PRODUCT / OFFER].
Brand voice: [FUN / PREMIUM / FRIENDLY / EXPERT]. Audience: [WHO].
Styles: 1) story, 2) list, 3) question, 4) bold statement, 5) short and punchy.
Follow LinkedIn norms: text posts, documents (PDF carousels) and short videos; only the first 2–3 lines show before "see more".
Each caption: hook line, value, call to action. Add suggested hashtags or keywords only where the platform uses them.', 'output' => 'Five distinct captions with hooks and calls to action that fit the platform\'s length and style.', 'image' => '' ),
	array( 'id' => 'linkedin-hooks-for-the-first-line', 'title' => 'LinkedIn hooks for the first line', 'cat' => 'Social Media', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => '20 scroll-stopping LinkedIn opening lines for your niche.', 'prompt' => 'Give me 20 opening hooks for LinkedIn posts about [TOPIC] for [AUDIENCE].
Mix: curiosity, contrarian, number/list, mistake, transformation, question.
Max 12 words each. Label each with its type. Avoid clickbait that the post cannot deliver.
Then pick the 3 strongest and explain why.', 'output' => 'Twenty labelled hooks under twelve words, and the top three with reasons, to test against each other.', 'image' => '' ),
	array( 'id' => 'optimise-your-linkedin-profile', 'title' => 'Optimise your LinkedIn profile', 'cat' => 'Social Media', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A LinkedIn profile audit with a new bio, name keywords and pinned-content plan.', 'prompt' => 'Audit and rewrite my LinkedIn profile.
Current bio/profile text: [PASTE]. Niche: [NICHE]. Goal: [GOAL]. Link: [LINK].
Give: 3 bio options within the platform\'s character limit, a searchable name/headline field, what to pin or feature first, and 5 quick fixes that build trust.
Platform notes: text posts, documents (PDF carousels) and short videos; only the first 2–3 lines show before "see more".', 'output' => 'Three bio options that fit the character limit, keyword ideas, a pinned-content plan and quick trust fixes.', 'image' => '' ),
	array( 'id' => 'linkedin-carousel-outline', 'title' => 'LinkedIn carousel outline', 'cat' => 'Social Media', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn one idea into a ready-to-design LinkedIn post sequence.', 'prompt' => 'Turn this idea into a LinkedIn multi-part post: [IDEA / BLOG LINK / KEY POINTS].
Platform notes: text posts, documents (PDF carousels) and short videos; only the first 2–3 lines show before "see more".
Give: a title/hook part, 6–8 value parts (one idea each, short sentences), and a final part with a call to action.
For each part add a short note on the visual or on-screen text.', 'output' => 'A part-by-part outline with hook, value steps, call to action and visual notes, ready to design or record.', 'image' => '' ),
	array( 'id' => 'linkedin-comment-and-dm-replies', 'title' => 'LinkedIn comment and DM replies', 'cat' => 'Social Media', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Friendly, on-brand replies for common LinkedIn comments, questions and complaints.', 'prompt' => 'Write reply templates for my LinkedIn account ([BRAND], voice: [VOICE]).
Cases: thank-you comment, price question, "is this available?", shipping/delivery question, complaint, negative troll comment, collaboration request, and a lead asking for details.
For each: 2 short reply options that sound human, plus when to move the chat to DM or support.
Never promise things I have not listed: [POLICIES].', 'output' => 'Two human-sounding replies for each common case, with guidance on when to move a conversation private.', 'image' => '' ),
	array( 'id' => '30-day-x-twitter-content-calendar', 'title' => '30-day X (Twitter) content calendar', 'cat' => 'Social Media', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A month of X (Twitter) post ideas with formats, hooks and a posting rhythm.', 'prompt' => 'Create a 30-day X (Twitter) content calendar for [BRAND / CREATOR] in [NICHE].
Audience: [WHO]. Goal: [FOLLOWERS / LEADS / SALES]. I can post [X] times a week.
Platform notes: posts of up to 280 characters on standard accounts, threads, images and short video.
Mix content pillars: education, behind the scenes, social proof, engagement, offers (max 20% promotional).
Table: Day | Pillar | Format | Hook / title | Key message | Call to action.', 'output' => 'A 30-row table balanced across content pillars and X (Twitter) formats, each with a hook and call to action, ready to copy into a planner.', 'image' => '' ),
	array( 'id' => 'x-twitter-captions-that-convert', 'title' => 'X (Twitter) captions that convert', 'cat' => 'Social Media', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Five X (Twitter) captions in different styles for one post, with a clear call to action.', 'prompt' => 'Write 5 X (Twitter) captions for this post: [DESCRIBE THE POST / PRODUCT / OFFER].
Brand voice: [FUN / PREMIUM / FRIENDLY / EXPERT]. Audience: [WHO].
Styles: 1) story, 2) list, 3) question, 4) bold statement, 5) short and punchy.
Follow X (Twitter) norms: posts of up to 280 characters on standard accounts, threads, images and short video.
Each caption: hook line, value, call to action. Add suggested hashtags or keywords only where the platform uses them.', 'output' => 'Five distinct captions with hooks and calls to action that fit the platform\'s length and style.', 'image' => '' ),
	array( 'id' => 'x-twitter-hooks-for-the-first-line', 'title' => 'X (Twitter) hooks for the first line', 'cat' => 'Social Media', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => '20 scroll-stopping X (Twitter) opening lines for your niche.', 'prompt' => 'Give me 20 opening hooks for X (Twitter) posts about [TOPIC] for [AUDIENCE].
Mix: curiosity, contrarian, number/list, mistake, transformation, question.
Max 12 words each. Label each with its type. Avoid clickbait that the post cannot deliver.
Then pick the 3 strongest and explain why.', 'output' => 'Twenty labelled hooks under twelve words, and the top three with reasons, to test against each other.', 'image' => '' ),
	array( 'id' => 'optimise-your-x-twitter-profile', 'title' => 'Optimise your X (Twitter) profile', 'cat' => 'Social Media', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A X (Twitter) profile audit with a new bio, name keywords and pinned-content plan.', 'prompt' => 'Audit and rewrite my X (Twitter) profile.
Current bio/profile text: [PASTE]. Niche: [NICHE]. Goal: [GOAL]. Link: [LINK].
Give: 3 bio options within the platform\'s character limit, a searchable name/headline field, what to pin or feature first, and 5 quick fixes that build trust.
Platform notes: posts of up to 280 characters on standard accounts, threads, images and short video.', 'output' => 'Three bio options that fit the character limit, keyword ideas, a pinned-content plan and quick trust fixes.', 'image' => '' ),
	array( 'id' => 'x-twitter-thread-outline', 'title' => 'X (Twitter) thread outline', 'cat' => 'Social Media', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn one idea into a ready-to-design X (Twitter) post sequence.', 'prompt' => 'Turn this idea into a X (Twitter) multi-part post: [IDEA / BLOG LINK / KEY POINTS].
Platform notes: posts of up to 280 characters on standard accounts, threads, images and short video.
Give: a title/hook part, 6–8 value parts (one idea each, short sentences), and a final part with a call to action.
For each part add a short note on the visual or on-screen text.', 'output' => 'A part-by-part outline with hook, value steps, call to action and visual notes, ready to design or record.', 'image' => '' ),
	array( 'id' => 'x-twitter-comment-and-dm-replies', 'title' => 'X (Twitter) comment and DM replies', 'cat' => 'Social Media', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Friendly, on-brand replies for common X (Twitter) comments, questions and complaints.', 'prompt' => 'Write reply templates for my X (Twitter) account ([BRAND], voice: [VOICE]).
Cases: thank-you comment, price question, "is this available?", shipping/delivery question, complaint, negative troll comment, collaboration request, and a lead asking for details.
For each: 2 short reply options that sound human, plus when to move the chat to DM or support.
Never promise things I have not listed: [POLICIES].', 'output' => 'Two human-sounding replies for each common case, with guidance on when to move a conversation private.', 'image' => '' ),
	array( 'id' => '30-day-facebook-content-calendar', 'title' => '30-day Facebook content calendar', 'cat' => 'Social Media', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'A month of Facebook post ideas with formats, hooks and a posting rhythm.', 'prompt' => 'Create a 30-day Facebook content calendar for [BRAND / CREATOR] in [NICHE].
Audience: [WHO]. Goal: [FOLLOWERS / LEADS / SALES]. I can post [X] times a week.
Platform notes: posts, Reels, groups and events; community and local audiences respond well.
Mix content pillars: education, behind the scenes, social proof, engagement, offers (max 20% promotional).
Table: Day | Pillar | Format | Hook / title | Key message | Call to action.', 'output' => 'A 30-row table balanced across content pillars and Facebook formats, each with a hook and call to action, ready to copy into a planner.', 'image' => '' ),
	array( 'id' => 'facebook-captions-that-convert', 'title' => 'Facebook captions that convert', 'cat' => 'Social Media', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Five Facebook captions in different styles for one post, with a clear call to action.', 'prompt' => 'Write 5 Facebook captions for this post: [DESCRIBE THE POST / PRODUCT / OFFER].
Brand voice: [FUN / PREMIUM / FRIENDLY / EXPERT]. Audience: [WHO].
Styles: 1) story, 2) list, 3) question, 4) bold statement, 5) short and punchy.
Follow Facebook norms: posts, Reels, groups and events; community and local audiences respond well.
Each caption: hook line, value, call to action. Add suggested hashtags or keywords only where the platform uses them.', 'output' => 'Five distinct captions with hooks and calls to action that fit the platform\'s length and style.', 'image' => '' ),
	array( 'id' => 'facebook-hooks-for-the-first-line', 'title' => 'Facebook hooks for the first line', 'cat' => 'Social Media', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => '20 scroll-stopping Facebook opening lines for your niche.', 'prompt' => 'Give me 20 opening hooks for Facebook posts about [TOPIC] for [AUDIENCE].
Mix: curiosity, contrarian, number/list, mistake, transformation, question.
Max 12 words each. Label each with its type. Avoid clickbait that the post cannot deliver.
Then pick the 3 strongest and explain why.', 'output' => 'Twenty labelled hooks under twelve words, and the top three with reasons, to test against each other.', 'image' => '' ),
	array( 'id' => 'optimise-your-facebook-profile', 'title' => 'Optimise your Facebook profile', 'cat' => 'Social Media', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'A Facebook profile audit with a new bio, name keywords and pinned-content plan.', 'prompt' => 'Audit and rewrite my Facebook profile.
Current bio/profile text: [PASTE]. Niche: [NICHE]. Goal: [GOAL]. Link: [LINK].
Give: 3 bio options within the platform\'s character limit, a searchable name/headline field, what to pin or feature first, and 5 quick fixes that build trust.
Platform notes: posts, Reels, groups and events; community and local audiences respond well.', 'output' => 'Three bio options that fit the character limit, keyword ideas, a pinned-content plan and quick trust fixes.', 'image' => '' ),
	array( 'id' => 'facebook-carousel-outline', 'title' => 'Facebook carousel outline', 'cat' => 'Social Media', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn one idea into a ready-to-design Facebook post sequence.', 'prompt' => 'Turn this idea into a Facebook multi-part post: [IDEA / BLOG LINK / KEY POINTS].
Platform notes: posts, Reels, groups and events; community and local audiences respond well.
Give: a title/hook part, 6–8 value parts (one idea each, short sentences), and a final part with a call to action.
For each part add a short note on the visual or on-screen text.', 'output' => 'A part-by-part outline with hook, value steps, call to action and visual notes, ready to design or record.', 'image' => '' ),
	array( 'id' => 'facebook-comment-and-dm-replies', 'title' => 'Facebook comment and DM replies', 'cat' => 'Social Media', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Friendly, on-brand replies for common Facebook comments, questions and complaints.', 'prompt' => 'Write reply templates for my Facebook account ([BRAND], voice: [VOICE]).
Cases: thank-you comment, price question, "is this available?", shipping/delivery question, complaint, negative troll comment, collaboration request, and a lead asking for details.
For each: 2 short reply options that sound human, plus when to move the chat to DM or support.
Never promise things I have not listed: [POLICIES].', 'output' => 'Two human-sounding replies for each common case, with guidance on when to move a conversation private.', 'image' => '' ),
	array( 'id' => '30-day-pinterest-content-calendar', 'title' => '30-day Pinterest content calendar', 'cat' => 'Social Media', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A month of Pinterest post ideas with formats, hooks and a posting rhythm.', 'prompt' => 'Create a 30-day Pinterest content calendar for [BRAND / CREATOR] in [NICHE].
Audience: [WHO]. Goal: [FOLLOWERS / LEADS / SALES]. I can post [X] times a week.
Platform notes: vertical 2:3 pins with a keyword-rich title (up to 100 characters) and description (up to 500 characters).
Mix content pillars: education, behind the scenes, social proof, engagement, offers (max 20% promotional).
Table: Day | Pillar | Format | Hook / title | Key message | Call to action.', 'output' => 'A 30-row table balanced across content pillars and Pinterest formats, each with a hook and call to action, ready to copy into a planner.', 'image' => '' ),
	array( 'id' => 'pinterest-captions-that-convert', 'title' => 'Pinterest captions that convert', 'cat' => 'Social Media', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Five Pinterest captions in different styles for one post, with a clear call to action.', 'prompt' => 'Write 5 Pinterest captions for this post: [DESCRIBE THE POST / PRODUCT / OFFER].
Brand voice: [FUN / PREMIUM / FRIENDLY / EXPERT]. Audience: [WHO].
Styles: 1) story, 2) list, 3) question, 4) bold statement, 5) short and punchy.
Follow Pinterest norms: vertical 2:3 pins with a keyword-rich title (up to 100 characters) and description (up to 500 characters).
Each caption: hook line, value, call to action. Add suggested hashtags or keywords only where the platform uses them.', 'output' => 'Five distinct captions with hooks and calls to action that fit the platform\'s length and style.', 'image' => '' ),
	array( 'id' => 'pinterest-hooks-for-the-first-line', 'title' => 'Pinterest hooks for the first line', 'cat' => 'Social Media', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => '20 scroll-stopping Pinterest opening lines for your niche.', 'prompt' => 'Give me 20 opening hooks for Pinterest posts about [TOPIC] for [AUDIENCE].
Mix: curiosity, contrarian, number/list, mistake, transformation, question.
Max 12 words each. Label each with its type. Avoid clickbait that the post cannot deliver.
Then pick the 3 strongest and explain why.', 'output' => 'Twenty labelled hooks under twelve words, and the top three with reasons, to test against each other.', 'image' => '' ),
	array( 'id' => 'optimise-your-pinterest-profile', 'title' => 'Optimise your Pinterest profile', 'cat' => 'Social Media', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A Pinterest profile audit with a new bio, name keywords and pinned-content plan.', 'prompt' => 'Audit and rewrite my Pinterest profile.
Current bio/profile text: [PASTE]. Niche: [NICHE]. Goal: [GOAL]. Link: [LINK].
Give: 3 bio options within the platform\'s character limit, a searchable name/headline field, what to pin or feature first, and 5 quick fixes that build trust.
Platform notes: vertical 2:3 pins with a keyword-rich title (up to 100 characters) and description (up to 500 characters).', 'output' => 'Three bio options that fit the character limit, keyword ideas, a pinned-content plan and quick trust fixes.', 'image' => '' ),
	array( 'id' => 'pinterest-pin-set-outline', 'title' => 'Pinterest pin set outline', 'cat' => 'Social Media', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Turn one idea into a ready-to-design Pinterest post sequence.', 'prompt' => 'Turn this idea into a Pinterest multi-part post: [IDEA / BLOG LINK / KEY POINTS].
Platform notes: vertical 2:3 pins with a keyword-rich title (up to 100 characters) and description (up to 500 characters).
Give: a title/hook part, 6–8 value parts (one idea each, short sentences), and a final part with a call to action.
For each part add a short note on the visual or on-screen text.', 'output' => 'A part-by-part outline with hook, value steps, call to action and visual notes, ready to design or record.', 'image' => '' ),
	array( 'id' => 'pinterest-comment-and-dm-replies', 'title' => 'Pinterest comment and DM replies', 'cat' => 'Social Media', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Friendly, on-brand replies for common Pinterest comments, questions and complaints.', 'prompt' => 'Write reply templates for my Pinterest account ([BRAND], voice: [VOICE]).
Cases: thank-you comment, price question, "is this available?", shipping/delivery question, complaint, negative troll comment, collaboration request, and a lead asking for details.
For each: 2 short reply options that sound human, plus when to move the chat to DM or support.
Never promise things I have not listed: [POLICIES].', 'output' => 'Two human-sounding replies for each common case, with guidance on when to move a conversation private.', 'image' => '' ),
	array( 'id' => '30-day-threads-content-calendar', 'title' => '30-day Threads content calendar', 'cat' => 'Social Media', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A month of Threads post ideas with formats, hooks and a posting rhythm.', 'prompt' => 'Create a 30-day Threads content calendar for [BRAND / CREATOR] in [NICHE].
Audience: [WHO]. Goal: [FOLLOWERS / LEADS / SALES]. I can post [X] times a week.
Platform notes: conversational text posts of up to 500 characters, images and short video.
Mix content pillars: education, behind the scenes, social proof, engagement, offers (max 20% promotional).
Table: Day | Pillar | Format | Hook / title | Key message | Call to action.', 'output' => 'A 30-row table balanced across content pillars and Threads formats, each with a hook and call to action, ready to copy into a planner.', 'image' => '' ),
	array( 'id' => 'threads-captions-that-convert', 'title' => 'Threads captions that convert', 'cat' => 'Social Media', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Five Threads captions in different styles for one post, with a clear call to action.', 'prompt' => 'Write 5 Threads captions for this post: [DESCRIBE THE POST / PRODUCT / OFFER].
Brand voice: [FUN / PREMIUM / FRIENDLY / EXPERT]. Audience: [WHO].
Styles: 1) story, 2) list, 3) question, 4) bold statement, 5) short and punchy.
Follow Threads norms: conversational text posts of up to 500 characters, images and short video.
Each caption: hook line, value, call to action. Add suggested hashtags or keywords only where the platform uses them.', 'output' => 'Five distinct captions with hooks and calls to action that fit the platform\'s length and style.', 'image' => '' ),
	array( 'id' => 'threads-hooks-for-the-first-line', 'title' => 'Threads hooks for the first line', 'cat' => 'Social Media', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => '20 scroll-stopping Threads opening lines for your niche.', 'prompt' => 'Give me 20 opening hooks for Threads posts about [TOPIC] for [AUDIENCE].
Mix: curiosity, contrarian, number/list, mistake, transformation, question.
Max 12 words each. Label each with its type. Avoid clickbait that the post cannot deliver.
Then pick the 3 strongest and explain why.', 'output' => 'Twenty labelled hooks under twelve words, and the top three with reasons, to test against each other.', 'image' => '' ),
	array( 'id' => 'optimise-your-threads-profile', 'title' => 'Optimise your Threads profile', 'cat' => 'Social Media', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A Threads profile audit with a new bio, name keywords and pinned-content plan.', 'prompt' => 'Audit and rewrite my Threads profile.
Current bio/profile text: [PASTE]. Niche: [NICHE]. Goal: [GOAL]. Link: [LINK].
Give: 3 bio options within the platform\'s character limit, a searchable name/headline field, what to pin or feature first, and 5 quick fixes that build trust.
Platform notes: conversational text posts of up to 500 characters, images and short video.', 'output' => 'Three bio options that fit the character limit, keyword ideas, a pinned-content plan and quick trust fixes.', 'image' => '' ),
	array( 'id' => 'threads-thread-outline', 'title' => 'Threads thread outline', 'cat' => 'Social Media', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn one idea into a ready-to-design Threads post sequence.', 'prompt' => 'Turn this idea into a Threads multi-part post: [IDEA / BLOG LINK / KEY POINTS].
Platform notes: conversational text posts of up to 500 characters, images and short video.
Give: a title/hook part, 6–8 value parts (one idea each, short sentences), and a final part with a call to action.
For each part add a short note on the visual or on-screen text.', 'output' => 'A part-by-part outline with hook, value steps, call to action and visual notes, ready to design or record.', 'image' => '' ),
	array( 'id' => 'threads-comment-and-dm-replies', 'title' => 'Threads comment and DM replies', 'cat' => 'Social Media', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'Friendly, on-brand replies for common Threads comments, questions and complaints.', 'prompt' => 'Write reply templates for my Threads account ([BRAND], voice: [VOICE]).
Cases: thank-you comment, price question, "is this available?", shipping/delivery question, complaint, negative troll comment, collaboration request, and a lead asking for details.
For each: 2 short reply options that sound human, plus when to move the chat to DM or support.
Never promise things I have not listed: [POLICIES].', 'output' => 'Two human-sounding replies for each common case, with guidance on when to move a conversation private.', 'image' => '' ),
	array( 'id' => '30-day-whatsapp-business-content-calendar', 'title' => '30-day WhatsApp Business content calendar', 'cat' => 'Social Media', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A month of WhatsApp Business post ideas with formats, hooks and a posting rhythm.', 'prompt' => 'Create a 30-day WhatsApp Business content calendar for [BRAND / CREATOR] in [NICHE].
Audience: [WHO]. Goal: [FOLLOWERS / LEADS / SALES]. I can post [X] times a week.
Platform notes: status updates, broadcast lists (only to people who opted in), catalogues and quick replies.
Mix content pillars: education, behind the scenes, social proof, engagement, offers (max 20% promotional).
Table: Day | Pillar | Format | Hook / title | Key message | Call to action.', 'output' => 'A 30-row table balanced across content pillars and WhatsApp Business formats, each with a hook and call to action, ready to copy into a planner.', 'image' => '' ),
	array( 'id' => 'whatsapp-business-captions-that-convert', 'title' => 'WhatsApp Business captions that convert', 'cat' => 'Social Media', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Five WhatsApp Business captions in different styles for one post, with a clear call to action.', 'prompt' => 'Write 5 WhatsApp Business captions for this post: [DESCRIBE THE POST / PRODUCT / OFFER].
Brand voice: [FUN / PREMIUM / FRIENDLY / EXPERT]. Audience: [WHO].
Styles: 1) story, 2) list, 3) question, 4) bold statement, 5) short and punchy.
Follow WhatsApp Business norms: status updates, broadcast lists (only to people who opted in), catalogues and quick replies.
Each caption: hook line, value, call to action. Add suggested hashtags or keywords only where the platform uses them.', 'output' => 'Five distinct captions with hooks and calls to action that fit the platform\'s length and style.', 'image' => '' ),
	array( 'id' => 'whatsapp-business-hooks-for-the-first-line', 'title' => 'WhatsApp Business hooks for the first line', 'cat' => 'Social Media', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => '20 scroll-stopping WhatsApp Business opening lines for your niche.', 'prompt' => 'Give me 20 opening hooks for WhatsApp Business posts about [TOPIC] for [AUDIENCE].
Mix: curiosity, contrarian, number/list, mistake, transformation, question.
Max 12 words each. Label each with its type. Avoid clickbait that the post cannot deliver.
Then pick the 3 strongest and explain why.', 'output' => 'Twenty labelled hooks under twelve words, and the top three with reasons, to test against each other.', 'image' => '' ),
	array( 'id' => 'optimise-your-whatsapp-business-profile', 'title' => 'Optimise your WhatsApp Business profile', 'cat' => 'Social Media', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A WhatsApp Business profile audit with a new bio, name keywords and pinned-content plan.', 'prompt' => 'Audit and rewrite my WhatsApp Business profile.
Current bio/profile text: [PASTE]. Niche: [NICHE]. Goal: [GOAL]. Link: [LINK].
Give: 3 bio options within the platform\'s character limit, a searchable name/headline field, what to pin or feature first, and 5 quick fixes that build trust.
Platform notes: status updates, broadcast lists (only to people who opted in), catalogues and quick replies.', 'output' => 'Three bio options that fit the character limit, keyword ideas, a pinned-content plan and quick trust fixes.', 'image' => '' ),
	array( 'id' => 'whatsapp-business-broadcast-outline', 'title' => 'WhatsApp Business broadcast outline', 'cat' => 'Social Media', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn one idea into a ready-to-design WhatsApp Business post sequence.', 'prompt' => 'Turn this idea into a WhatsApp Business multi-part post: [IDEA / BLOG LINK / KEY POINTS].
Platform notes: status updates, broadcast lists (only to people who opted in), catalogues and quick replies.
Give: a title/hook part, 6–8 value parts (one idea each, short sentences), and a final part with a call to action.
For each part add a short note on the visual or on-screen text.', 'output' => 'A part-by-part outline with hook, value steps, call to action and visual notes, ready to design or record.', 'image' => '' ),
	array( 'id' => 'whatsapp-business-comment-and-dm-replies', 'title' => 'WhatsApp Business comment and DM replies', 'cat' => 'Social Media', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Friendly, on-brand replies for common WhatsApp Business comments, questions and complaints.', 'prompt' => 'Write reply templates for my WhatsApp Business account ([BRAND], voice: [VOICE]).
Cases: thank-you comment, price question, "is this available?", shipping/delivery question, complaint, negative troll comment, collaboration request, and a lead asking for details.
For each: 2 short reply options that sound human, plus when to move the chat to DM or support.
Never promise things I have not listed: [POLICIES].', 'output' => 'Two human-sounding replies for each common case, with guidance on when to move a conversation private.', 'image' => '' ),
	array( 'id' => '30-day-youtube-shorts-content-calendar', 'title' => '30-day YouTube Shorts content calendar', 'cat' => 'Social Media', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A month of YouTube Shorts post ideas with formats, hooks and a posting rhythm.', 'prompt' => 'Create a 30-day YouTube Shorts content calendar for [BRAND / CREATOR] in [NICHE].
Audience: [WHO]. Goal: [FOLLOWERS / LEADS / SALES]. I can post [X] times a week.
Platform notes: vertical short videos; the first 2 seconds decide if people keep watching.
Mix content pillars: education, behind the scenes, social proof, engagement, offers (max 20% promotional).
Table: Day | Pillar | Format | Hook / title | Key message | Call to action.', 'output' => 'A 30-row table balanced across content pillars and YouTube Shorts formats, each with a hook and call to action, ready to copy into a planner.', 'image' => '' ),
	array( 'id' => 'youtube-shorts-captions-that-convert', 'title' => 'YouTube Shorts captions that convert', 'cat' => 'Social Media', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Five YouTube Shorts captions in different styles for one post, with a clear call to action.', 'prompt' => 'Write 5 YouTube Shorts captions for this post: [DESCRIBE THE POST / PRODUCT / OFFER].
Brand voice: [FUN / PREMIUM / FRIENDLY / EXPERT]. Audience: [WHO].
Styles: 1) story, 2) list, 3) question, 4) bold statement, 5) short and punchy.
Follow YouTube Shorts norms: vertical short videos; the first 2 seconds decide if people keep watching.
Each caption: hook line, value, call to action. Add suggested hashtags or keywords only where the platform uses them.', 'output' => 'Five distinct captions with hooks and calls to action that fit the platform\'s length and style.', 'image' => '' ),
	array( 'id' => 'youtube-shorts-hooks-for-the-first-line', 'title' => 'YouTube Shorts hooks for the first line', 'cat' => 'Social Media', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => '20 scroll-stopping YouTube Shorts opening lines for your niche.', 'prompt' => 'Give me 20 opening hooks for YouTube Shorts posts about [TOPIC] for [AUDIENCE].
Mix: curiosity, contrarian, number/list, mistake, transformation, question.
Max 12 words each. Label each with its type. Avoid clickbait that the post cannot deliver.
Then pick the 3 strongest and explain why.', 'output' => 'Twenty labelled hooks under twelve words, and the top three with reasons, to test against each other.', 'image' => '' ),
	array( 'id' => 'optimise-your-youtube-shorts-profile', 'title' => 'Optimise your YouTube Shorts profile', 'cat' => 'Social Media', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'A YouTube Shorts profile audit with a new bio, name keywords and pinned-content plan.', 'prompt' => 'Audit and rewrite my YouTube Shorts profile.
Current bio/profile text: [PASTE]. Niche: [NICHE]. Goal: [GOAL]. Link: [LINK].
Give: 3 bio options within the platform\'s character limit, a searchable name/headline field, what to pin or feature first, and 5 quick fixes that build trust.
Platform notes: vertical short videos; the first 2 seconds decide if people keep watching.', 'output' => 'Three bio options that fit the character limit, keyword ideas, a pinned-content plan and quick trust fixes.', 'image' => '' ),
	array( 'id' => 'youtube-shorts-script-outline', 'title' => 'YouTube Shorts script outline', 'cat' => 'Social Media', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn one idea into a ready-to-design YouTube Shorts post sequence.', 'prompt' => 'Turn this idea into a YouTube Shorts multi-part post: [IDEA / BLOG LINK / KEY POINTS].
Platform notes: vertical short videos; the first 2 seconds decide if people keep watching.
Give: a title/hook part, 6–8 value parts (one idea each, short sentences), and a final part with a call to action.
For each part add a short note on the visual or on-screen text.', 'output' => 'A part-by-part outline with hook, value steps, call to action and visual notes, ready to design or record.', 'image' => '' ),
	array( 'id' => 'youtube-shorts-comment-and-dm-replies', 'title' => 'YouTube Shorts comment and DM replies', 'cat' => 'Social Media', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Friendly, on-brand replies for common YouTube Shorts comments, questions and complaints.', 'prompt' => 'Write reply templates for my YouTube Shorts account ([BRAND], voice: [VOICE]).
Cases: thank-you comment, price question, "is this available?", shipping/delivery question, complaint, negative troll comment, collaboration request, and a lead asking for details.
For each: 2 short reply options that sound human, plus when to move the chat to DM or support.
Never promise things I have not listed: [POLICIES].', 'output' => 'Two human-sounding replies for each common case, with guidance on when to move a conversation private.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-caf', 'title' => 'One-page business plan for a café', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'A lean, one-page plan for a café: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my café in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page café plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'caf-customer-personas', 'title' => 'Café customer personas', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Three detailed buyer personas for a café, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a café in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'caf-competitor-analysis', 'title' => 'Café competitor analysis', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare your café with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my café.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-caf', 'title' => '90-day marketing plan for a café', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => true, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a café.', 'prompt' => 'Create a 90-day marketing plan for my café.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-bakery', 'title' => 'One-page business plan for a bakery', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A lean, one-page plan for a bakery: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my bakery in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page bakery plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'bakery-customer-personas', 'title' => 'Bakery customer personas', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Three detailed buyer personas for a bakery, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a bakery in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'bakery-competitor-analysis', 'title' => 'Bakery competitor analysis', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare your bakery with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my bakery.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-bakery', 'title' => '90-day marketing plan for a bakery', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a bakery.', 'prompt' => 'Create a 90-day marketing plan for my bakery.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-clothing-boutique', 'title' => 'One-page business plan for a clothing boutique', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A lean, one-page plan for a clothing boutique: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my clothing boutique in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page clothing boutique plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'clothing-boutique-customer-personas', 'title' => 'Clothing boutique customer personas', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Three detailed buyer personas for a clothing boutique, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a clothing boutique in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'clothing-boutique-competitor-analysis', 'title' => 'Clothing boutique competitor analysis', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'Compare your clothing boutique with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my clothing boutique.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-clothing-boutique', 'title' => '90-day marketing plan for a clothing boutique', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a clothing boutique.', 'prompt' => 'Create a 90-day marketing plan for my clothing boutique.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-gym', 'title' => 'One-page business plan for a gym', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'A lean, one-page plan for a gym: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my gym in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page gym plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'gym-customer-personas', 'title' => 'Gym customer personas', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'Three detailed buyer personas for a gym, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a gym in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'gym-competitor-analysis', 'title' => 'Gym competitor analysis', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare your gym with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my gym.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-gym', 'title' => '90-day marketing plan for a gym', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a gym.', 'prompt' => 'Create a 90-day marketing plan for my gym.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-beauty-salon', 'title' => 'One-page business plan for a beauty salon', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A lean, one-page plan for a beauty salon: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my beauty salon in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page beauty salon plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'beauty-salon-customer-personas', 'title' => 'Beauty salon customer personas', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Three detailed buyer personas for a beauty salon, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a beauty salon in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'beauty-salon-competitor-analysis', 'title' => 'Beauty salon competitor analysis', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare your beauty salon with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my beauty salon.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-beauty-salon', 'title' => '90-day marketing plan for a beauty salon', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a beauty salon.', 'prompt' => 'Create a 90-day marketing plan for my beauty salon.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-dental-clinic', 'title' => 'One-page business plan for a dental clinic', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'A lean, one-page plan for a dental clinic: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my dental clinic in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page dental clinic plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'dental-clinic-customer-personas', 'title' => 'Dental clinic customer personas', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Three detailed buyer personas for a dental clinic, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a dental clinic in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'dental-clinic-competitor-analysis', 'title' => 'Dental clinic competitor analysis', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare your dental clinic with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my dental clinic.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-dental-clinic', 'title' => '90-day marketing plan for a dental clinic', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a dental clinic.', 'prompt' => 'Create a 90-day marketing plan for my dental clinic.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-real-estate-agency', 'title' => 'One-page business plan for a real estate agency', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A lean, one-page plan for a real estate agency: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my real estate agency in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page real estate agency plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'real-estate-agency-customer-personas', 'title' => 'Real estate agency customer personas', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Three detailed buyer personas for a real estate agency, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a real estate agency in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'real-estate-agency-competitor-analysis', 'title' => 'Real estate agency competitor analysis', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare your real estate agency with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my real estate agency.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-real-estate-agency', 'title' => '90-day marketing plan for a real estate agency', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a real estate agency.', 'prompt' => 'Create a 90-day marketing plan for my real estate agency.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-coaching-institute', 'title' => 'One-page business plan for a coaching institute', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'A lean, one-page plan for a coaching institute: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my coaching institute in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page coaching institute plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'coaching-institute-customer-personas', 'title' => 'Coaching institute customer personas', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Three detailed buyer personas for a coaching institute, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a coaching institute in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'coaching-institute-competitor-analysis', 'title' => 'Coaching institute competitor analysis', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Compare your coaching institute with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my coaching institute.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-coaching-institute', 'title' => '90-day marketing plan for a coaching institute', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a coaching institute.', 'prompt' => 'Create a 90-day marketing plan for my coaching institute.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-saas-startup', 'title' => 'One-page business plan for a SaaS startup', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A lean, one-page plan for a SaaS startup: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my SaaS startup in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page SaaS startup plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'saas-startup-customer-personas', 'title' => 'SaaS startup customer personas', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Three detailed buyer personas for a SaaS startup, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a SaaS startup in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'saas-startup-competitor-analysis', 'title' => 'SaaS startup competitor analysis', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare your SaaS startup with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my SaaS startup.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-saas-startup', 'title' => '90-day marketing plan for a SaaS startup', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a SaaS startup.', 'prompt' => 'Create a 90-day marketing plan for my SaaS startup.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-e-commerce-store', 'title' => 'One-page business plan for a e-commerce store', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A lean, one-page plan for a e-commerce store: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my e-commerce store in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page e-commerce store plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'e-commerce-store-customer-personas', 'title' => 'E-commerce store customer personas', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Three detailed buyer personas for a e-commerce store, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a e-commerce store in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'e-commerce-store-competitor-analysis', 'title' => 'E-commerce store competitor analysis', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare your e-commerce store with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my e-commerce store.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-e-commerce-store', 'title' => '90-day marketing plan for a e-commerce store', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a e-commerce store.', 'prompt' => 'Create a 90-day marketing plan for my e-commerce store.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-restaurant', 'title' => 'One-page business plan for a restaurant', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A lean, one-page plan for a restaurant: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my restaurant in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page restaurant plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'restaurant-customer-personas', 'title' => 'Restaurant customer personas', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Three detailed buyer personas for a restaurant, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a restaurant in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'restaurant-competitor-analysis', 'title' => 'Restaurant competitor analysis', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare your restaurant with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my restaurant.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-restaurant', 'title' => '90-day marketing plan for a restaurant', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a restaurant.', 'prompt' => 'Create a 90-day marketing plan for my restaurant.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-travel-agency', 'title' => 'One-page business plan for a travel agency', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'A lean, one-page plan for a travel agency: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my travel agency in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page travel agency plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'travel-agency-customer-personas', 'title' => 'Travel agency customer personas', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Three detailed buyer personas for a travel agency, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a travel agency in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'travel-agency-competitor-analysis', 'title' => 'Travel agency competitor analysis', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare your travel agency with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my travel agency.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-travel-agency', 'title' => '90-day marketing plan for a travel agency', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a travel agency.', 'prompt' => 'Create a 90-day marketing plan for my travel agency.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-event-management-company', 'title' => 'One-page business plan for a event management company', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A lean, one-page plan for a event management company: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my event management company in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page event management company plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'event-management-company-customer-personas', 'title' => 'Event management company customer personas', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Three detailed buyer personas for a event management company, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a event management company in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'event-management-company-competitor-analysis', 'title' => 'Event management company competitor analysis', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Compare your event management company with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my event management company.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-event-management-company', 'title' => '90-day marketing plan for a event management company', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a event management company.', 'prompt' => 'Create a 90-day marketing plan for my event management company.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-interior-design-studio', 'title' => 'One-page business plan for a interior design studio', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A lean, one-page plan for a interior design studio: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my interior design studio in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page interior design studio plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'interior-design-studio-customer-personas', 'title' => 'Interior design studio customer personas', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Three detailed buyer personas for a interior design studio, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a interior design studio in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'interior-design-studio-competitor-analysis', 'title' => 'Interior design studio competitor analysis', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare your interior design studio with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my interior design studio.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-interior-design-studio', 'title' => '90-day marketing plan for a interior design studio', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a interior design studio.', 'prompt' => 'Create a 90-day marketing plan for my interior design studio.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-digital-marketing-agency', 'title' => 'One-page business plan for a digital marketing agency', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A lean, one-page plan for a digital marketing agency: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my digital marketing agency in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page digital marketing agency plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'digital-marketing-agency-customer-personas', 'title' => 'Digital marketing agency customer personas', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => true, 'summary' => 'Three detailed buyer personas for a digital marketing agency, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a digital marketing agency in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'digital-marketing-agency-competitor-analysis', 'title' => 'Digital marketing agency competitor analysis', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare your digital marketing agency with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my digital marketing agency.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-digital-marketing-agency', 'title' => '90-day marketing plan for a digital marketing agency', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a digital marketing agency.', 'prompt' => 'Create a 90-day marketing plan for my digital marketing agency.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-photography-studio', 'title' => 'One-page business plan for a photography studio', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => true, 'summary' => 'A lean, one-page plan for a photography studio: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my photography studio in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page photography studio plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'photography-studio-customer-personas', 'title' => 'Photography studio customer personas', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Three detailed buyer personas for a photography studio, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a photography studio in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'photography-studio-competitor-analysis', 'title' => 'Photography studio competitor analysis', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare your photography studio with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my photography studio.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-photography-studio', 'title' => '90-day marketing plan for a photography studio', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a photography studio.', 'prompt' => 'Create a 90-day marketing plan for my photography studio.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-organic-farm-brand', 'title' => 'One-page business plan for a organic farm brand', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A lean, one-page plan for a organic farm brand: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my organic farm brand in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page organic farm brand plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'organic-farm-brand-customer-personas', 'title' => 'Organic farm brand customer personas', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Three detailed buyer personas for a organic farm brand, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a organic farm brand in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'organic-farm-brand-competitor-analysis', 'title' => 'Organic farm brand competitor analysis', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare your organic farm brand with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my organic farm brand.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-organic-farm-brand', 'title' => '90-day marketing plan for a organic farm brand', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => true, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a organic farm brand.', 'prompt' => 'Create a 90-day marketing plan for my organic farm brand.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-pet-store', 'title' => 'One-page business plan for a pet store', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A lean, one-page plan for a pet store: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my pet store in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page pet store plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'pet-store-customer-personas', 'title' => 'Pet store customer personas', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Three detailed buyer personas for a pet store, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a pet store in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'pet-store-competitor-analysis', 'title' => 'Pet store competitor analysis', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => true, 'summary' => 'Compare your pet store with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my pet store.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-pet-store', 'title' => '90-day marketing plan for a pet store', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a pet store.', 'prompt' => 'Create a 90-day marketing plan for my pet store.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-mobile-repair-shop', 'title' => 'One-page business plan for a mobile repair shop', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A lean, one-page plan for a mobile repair shop: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my mobile repair shop in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page mobile repair shop plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'mobile-repair-shop-customer-personas', 'title' => 'Mobile repair shop customer personas', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => true, 'summary' => 'Three detailed buyer personas for a mobile repair shop, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a mobile repair shop in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'mobile-repair-shop-competitor-analysis', 'title' => 'Mobile repair shop competitor analysis', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare your mobile repair shop with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my mobile repair shop.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-mobile-repair-shop', 'title' => '90-day marketing plan for a mobile repair shop', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a mobile repair shop.', 'prompt' => 'Create a 90-day marketing plan for my mobile repair shop.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'one-page-business-plan-for-a-courier-and-logistics-busin', 'title' => 'One-page business plan for a courier and logistics business', 'cat' => 'Business', 'tool' => 'Gemini', 'kind' => 'text', 'premium' => false, 'summary' => 'A lean, one-page plan for a courier and logistics business: customers, offer, money and first steps.', 'prompt' => 'Write a one-page business plan for my courier and logistics business in [CITY / ONLINE].
My idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].
Sections: problem, target customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with clearly stated assumptions, break-even, risks, first 30-day actions.
Use tables for costs and revenue. Mark every number as an estimate I must verify locally.', 'output' => 'A lean one-page courier and logistics business plan with cost and revenue tables based on visible assumptions, a break-even estimate and a 30-day action list.', 'image' => '' ),
	array( 'id' => 'courier-and-logistics-business-customer-personas', 'title' => 'Courier and logistics business customer personas', 'cat' => 'Business', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'Three detailed buyer personas for a courier and logistics business, with what makes them buy.', 'prompt' => 'Create 3 customer personas for a courier and logistics business in [LOCATION].
For each: name and age range, job and income band, goals, pain points, buying triggers, objections, where they spend time online and offline, the message that would win them, and the best offer for them.
Base it on these facts about my customers: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag anything that is a guess.', 'output' => 'Three realistic personas with triggers, objections, channels and a winning message for each, with guesses clearly flagged.', 'image' => '' ),
	array( 'id' => 'courier-and-logistics-business-competitor-analysis', 'title' => 'Courier and logistics business competitor analysis', 'cat' => 'Business', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare your courier and logistics business with local or online competitors and find a gap you can own.', 'prompt' => 'Help me analyse competitors for my courier and logistics business.
Competitors (name + what I know: prices, reviews, offers, location, website): [LIST 3–5].
1. Comparison table: price, quality, service, convenience, brand, online presence.
2. SWOT for my business.
3. 3 gaps in the market I could own, with how to test each cheaply.
Only use the facts I gave; list what I should go and check.', 'output' => 'A side-by-side comparison, a SWOT, three testable market gaps and a checklist of facts to verify.', 'image' => '' ),
	array( 'id' => '90-day-marketing-plan-for-a-courier-and-logistics-busine', 'title' => '90-day marketing plan for a courier and logistics business', 'cat' => 'Business', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A practical 90-day plan to get the first or next 100 customers for a courier and logistics business.', 'prompt' => 'Create a 90-day marketing plan for my courier and logistics business.
Current situation: [NEW / EXISTING], monthly budget [AMOUNT], team [WHO], current customers [NUMBER].
Include: goal and KPIs, 3 priority channels (with why), weekly actions per month, a launch or promo idea, a referral idea, local SEO / Google Business Profile steps if relevant, and a simple tracking sheet layout.
Keep it doable by [1 / 2] people.', 'output' => 'A month-by-month plan with KPIs, three channels, weekly actions, promo and referral ideas and a tracking layout.', 'image' => '' ),
	array( 'id' => 'cinematic-photo-product-shot', 'title' => 'Cinematic photo product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready Midjourney prompt for a product shot in cinematic photo style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], cinematic photograph, 35mm lens, shallow depth of field, dramatic rim light, film grain, color graded teal and orange, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A cinematic photo image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'cinematic-photo-portrait', 'title' => 'Cinematic photo portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Stable Diffusion prompt for a portrait in cinematic photo style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], cinematic photograph, 35mm lens, shallow depth of field, dramatic rim light, film grain, color graded teal and orange, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A cinematic photo portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'cinematic-photo-scene-or-poster', 'title' => 'Cinematic photo scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in cinematic photo style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: cinematic photograph, 35mm lens, shallow depth of field, dramatic rim light, film grain, color graded teal and orange.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A cinematic photo scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'studio-product-photo-product-shot', 'title' => 'Studio product photo product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready Midjourney prompt for a product shot in studio product photo style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], professional studio product photography, softbox lighting, seamless backdrop, crisp reflections, ultra sharp focus, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A studio product photo image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'studio-product-photo-portrait', 'title' => 'Studio product photo portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Stable Diffusion prompt for a portrait in studio product photo style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], professional studio product photography, softbox lighting, seamless backdrop, crisp reflections, ultra sharp focus, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A studio product photo portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'studio-product-photo-scene-or-poster', 'title' => 'Studio product photo scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in studio product photo style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: professional studio product photography, softbox lighting, seamless backdrop, crisp reflections, ultra sharp focus.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A studio product photo scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'watercolor-product-shot', 'title' => 'Watercolor product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Midjourney prompt for a product shot in watercolor style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], loose watercolor painting, soft pigment blooms, visible paper texture, gentle washes, white space, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A watercolor image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'watercolor-portrait', 'title' => 'Watercolor portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready Stable Diffusion prompt for a portrait in watercolor style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], loose watercolor painting, soft pigment blooms, visible paper texture, gentle washes, white space, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A watercolor portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'watercolor-scene-or-poster', 'title' => 'Watercolor scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in watercolor style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: loose watercolor painting, soft pigment blooms, visible paper texture, gentle washes, white space.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A watercolor scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'flat-vector-illustration-product-shot', 'title' => 'Flat vector illustration product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Midjourney prompt for a product shot in flat vector illustration style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], flat vector illustration, clean shapes, limited palette, subtle grain, modern editorial style, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A flat vector illustration image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'flat-vector-illustration-portrait', 'title' => 'Flat vector illustration portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready Stable Diffusion prompt for a portrait in flat vector illustration style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], flat vector illustration, clean shapes, limited palette, subtle grain, modern editorial style, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A flat vector illustration portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'flat-vector-illustration-scene-or-poster', 'title' => 'Flat vector illustration scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in flat vector illustration style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: flat vector illustration, clean shapes, limited palette, subtle grain, modern editorial style.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A flat vector illustration scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'isometric-3d-product-shot', 'title' => 'Isometric 3D product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Midjourney prompt for a product shot in isometric 3d style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], isometric 3D illustration, soft global illumination, pastel palette, clean geometry, tiny details, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A isometric 3d image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'isometric-3d-portrait', 'title' => 'Isometric 3D portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready Stable Diffusion prompt for a portrait in isometric 3d style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], isometric 3D illustration, soft global illumination, pastel palette, clean geometry, tiny details, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A isometric 3d portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'isometric-3d-scene-or-poster', 'title' => 'Isometric 3D scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in isometric 3d style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: isometric 3D illustration, soft global illumination, pastel palette, clean geometry, tiny details.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A isometric 3d scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'clay-3d-render-product-shot', 'title' => 'Clay 3D render product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Midjourney prompt for a product shot in clay 3d render style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], cute clay 3D render, soft rounded forms, matte texture, studio lighting, playful, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A clay 3d render image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'clay-3d-render-portrait', 'title' => 'Clay 3D render portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Stable Diffusion prompt for a portrait in clay 3d render style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], cute clay 3D render, soft rounded forms, matte texture, studio lighting, playful, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A clay 3d render portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'clay-3d-render-scene-or-poster', 'title' => 'Clay 3D render scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in clay 3d render style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: cute clay 3D render, soft rounded forms, matte texture, studio lighting, playful.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A clay 3d render scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'anime-product-shot', 'title' => 'Anime product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Midjourney prompt for a product shot in anime style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], anime illustration, cel shading, expressive characters, vibrant colors, detailed background, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A anime image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'anime-portrait', 'title' => 'Anime portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Stable Diffusion prompt for a portrait in anime style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], anime illustration, cel shading, expressive characters, vibrant colors, detailed background, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A anime portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'anime-scene-or-poster', 'title' => 'Anime scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in anime style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: anime illustration, cel shading, expressive characters, vibrant colors, detailed background.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A anime scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'pixel-art-product-shot', 'title' => 'Pixel art product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Midjourney prompt for a product shot in pixel art style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], 16-bit pixel art, limited palette, crisp pixels, retro game style, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A pixel art image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'pixel-art-portrait', 'title' => 'Pixel art portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Stable Diffusion prompt for a portrait in pixel art style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], 16-bit pixel art, limited palette, crisp pixels, retro game style, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A pixel art portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'pixel-art-scene-or-poster', 'title' => 'Pixel art scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in pixel art style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: 16-bit pixel art, limited palette, crisp pixels, retro game style.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A pixel art scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'line-art-product-shot', 'title' => 'Line art product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Midjourney prompt for a product shot in line art style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], minimal black ink line art, single weight lines, lots of negative space, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A line art image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'line-art-portrait', 'title' => 'Line art portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Stable Diffusion prompt for a portrait in line art style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], minimal black ink line art, single weight lines, lots of negative space, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A line art portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'line-art-scene-or-poster', 'title' => 'Line art scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in line art style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: minimal black ink line art, single weight lines, lots of negative space.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A line art scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'oil-painting-product-shot', 'title' => 'Oil painting product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready Midjourney prompt for a product shot in oil painting style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], classical oil painting, rich brushwork, warm chiaroscuro lighting, canvas texture, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A oil painting image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'oil-painting-portrait', 'title' => 'Oil painting portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Stable Diffusion prompt for a portrait in oil painting style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], classical oil painting, rich brushwork, warm chiaroscuro lighting, canvas texture, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A oil painting portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'oil-painting-scene-or-poster', 'title' => 'Oil painting scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in oil painting style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: classical oil painting, rich brushwork, warm chiaroscuro lighting, canvas texture.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A oil painting scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'cyberpunk-neon-product-shot', 'title' => 'Cyberpunk neon product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready Midjourney prompt for a product shot in cyberpunk neon style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], cyberpunk scene, neon signs, rain-soaked streets, magenta and cyan lighting, volumetric fog, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A cyberpunk neon image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'cyberpunk-neon-portrait', 'title' => 'Cyberpunk neon portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Stable Diffusion prompt for a portrait in cyberpunk neon style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], cyberpunk scene, neon signs, rain-soaked streets, magenta and cyan lighting, volumetric fog, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A cyberpunk neon portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'cyberpunk-neon-scene-or-poster', 'title' => 'Cyberpunk neon scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in cyberpunk neon style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: cyberpunk scene, neon signs, rain-soaked streets, magenta and cyan lighting, volumetric fog.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A cyberpunk neon scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'minimalist-poster-product-shot', 'title' => 'Minimalist poster product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready Midjourney prompt for a product shot in minimalist poster style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], minimalist poster design, bold geometric shapes, two-color palette, Swiss grid, lots of negative space, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A minimalist poster image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'minimalist-poster-portrait', 'title' => 'Minimalist poster portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Stable Diffusion prompt for a portrait in minimalist poster style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], minimalist poster design, bold geometric shapes, two-color palette, Swiss grid, lots of negative space, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A minimalist poster portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'minimalist-poster-scene-or-poster', 'title' => 'Minimalist poster scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in minimalist poster style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: minimalist poster design, bold geometric shapes, two-color palette, Swiss grid, lots of negative space.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A minimalist poster scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'vintage-35mm-film-product-shot', 'title' => 'Vintage 35mm film product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Midjourney prompt for a product shot in vintage 35mm film style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], vintage 35mm film photo, natural light, faded colors, light leaks, nostalgic mood, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A vintage 35mm film image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'vintage-35mm-film-portrait', 'title' => 'Vintage 35mm film portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready Stable Diffusion prompt for a portrait in vintage 35mm film style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], vintage 35mm film photo, natural light, faded colors, light leaks, nostalgic mood, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A vintage 35mm film portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'vintage-35mm-film-scene-or-poster', 'title' => 'Vintage 35mm film scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in vintage 35mm film style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: vintage 35mm film photo, natural light, faded colors, light leaks, nostalgic mood.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A vintage 35mm film scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'woodblock-print-product-shot', 'title' => 'Woodblock print product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Midjourney prompt for a product shot in woodblock print style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], Japanese woodblock print inspired, flat colour areas, bold outlines, textured paper, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A woodblock print image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'woodblock-print-portrait', 'title' => 'Woodblock print portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready Stable Diffusion prompt for a portrait in woodblock print style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], Japanese woodblock print inspired, flat colour areas, bold outlines, textured paper, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A woodblock print portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'woodblock-print-scene-or-poster', 'title' => 'Woodblock print scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in woodblock print style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: Japanese woodblock print inspired, flat colour areas, bold outlines, textured paper.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A woodblock print scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'low-poly-product-shot', 'title' => 'Low-poly product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Midjourney prompt for a product shot in low-poly style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], low-poly 3D art, faceted geometry, gradient lighting, clean background, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A low-poly image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'low-poly-portrait', 'title' => 'Low-poly portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready Stable Diffusion prompt for a portrait in low-poly style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], low-poly 3D art, faceted geometry, gradient lighting, clean background, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A low-poly portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'low-poly-scene-or-poster', 'title' => 'Low-poly scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in low-poly style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: low-poly 3D art, faceted geometry, gradient lighting, clean background.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A low-poly scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'paper-cut-out-product-shot', 'title' => 'Paper cut-out product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Midjourney prompt for a product shot in paper cut-out style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], layered paper cut-out art, soft shadows between layers, handcrafted look, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A paper cut-out image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'paper-cut-out-portrait', 'title' => 'Paper cut-out portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Stable Diffusion prompt for a portrait in paper cut-out style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], layered paper cut-out art, soft shadows between layers, handcrafted look, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A paper cut-out portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'paper-cut-out-scene-or-poster', 'title' => 'Paper cut-out scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in paper cut-out style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: layered paper cut-out art, soft shadows between layers, handcrafted look.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A paper cut-out scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'art-deco-product-shot', 'title' => 'Art Deco product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Midjourney prompt for a product shot in art deco style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], Art Deco style, geometric patterns, gold and black, symmetrical composition, 1920s glamour, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A art deco image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'art-deco-portrait', 'title' => 'Art Deco portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Stable Diffusion prompt for a portrait in art deco style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], Art Deco style, geometric patterns, gold and black, symmetrical composition, 1920s glamour, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A art deco portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'art-deco-scene-or-poster', 'title' => 'Art Deco scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in art deco style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: Art Deco style, geometric patterns, gold and black, symmetrical composition, 1920s glamour.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A art deco scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'retro-70s-product-shot', 'title' => 'Retro 70s product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Midjourney prompt for a product shot in retro 70s style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], 1970s retro illustration, warm orange and brown palette, rounded type, sunburst shapes, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A retro 70s image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'retro-70s-portrait', 'title' => 'Retro 70s portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Stable Diffusion prompt for a portrait in retro 70s style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], 1970s retro illustration, warm orange and brown palette, rounded type, sunburst shapes, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A retro 70s portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'retro-70s-scene-or-poster', 'title' => 'Retro 70s scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in retro 70s style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: 1970s retro illustration, warm orange and brown palette, rounded type, sunburst shapes.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A retro 70s scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'double-exposure-product-shot', 'title' => 'Double exposure product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Midjourney prompt for a product shot in double exposure style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], double exposure photograph, silhouette blended with landscape, monochrome with one accent color, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A double exposure image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'double-exposure-portrait', 'title' => 'Double exposure portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Stable Diffusion prompt for a portrait in double exposure style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], double exposure photograph, silhouette blended with landscape, monochrome with one accent color, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A double exposure portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'double-exposure-scene-or-poster', 'title' => 'Double exposure scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in double exposure style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: double exposure photograph, silhouette blended with landscape, monochrome with one accent color.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A double exposure scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'macro-photography-product-shot', 'title' => 'Macro photography product shot', 'cat' => 'Image Generation', 'tool' => 'Midjourney', 'kind' => 'image', 'premium' => true, 'summary' => 'A ready Midjourney prompt for a product shot in macro photography style.', 'prompt' => '[YOUR PRODUCT, e.g. a glass bottle of cold brew coffee] on [SURFACE], [PROPS], macro photograph, extreme close-up, tiny water droplets, creamy bokeh, ultra detailed textures, high detail, balanced composition --ar 4:5 --style raw --stylize 150

Tips: change --ar for the format you need (1:1 feed, 9:16 story, 16:9 banner). Lower --stylize for more literal results, raise it for more artistic ones. Add --no [THING] to remove an element.', 'output' => 'A macro photography image of your product in a 4:5 feed-friendly frame; Midjourney returns a grid of four variations you can upscale or vary.', 'image' => '' ),
	array( 'id' => 'macro-photography-portrait', 'title' => 'Macro photography portrait', 'cat' => 'Image Generation', 'tool' => 'Stable Diffusion', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready Stable Diffusion prompt for a portrait in macro photography style.', 'prompt' => 'Positive prompt:
portrait of [PERSON: age, look, clothing, expression], [SETTING], macro photograph, extreme close-up, tiny water droplets, creamy bokeh, ultra detailed textures, highly detailed, sharp focus, natural skin texture

Negative prompt:
blurry, low quality, extra fingers, deformed hands, distorted face, watermark, text, oversaturated

Suggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 or 832×1216 for portrait.', 'output' => 'A macro photography portrait; the negative prompt reduces common artefacts such as bad hands, and the settings are a reliable SDXL starting point.', 'image' => '' ),
	array( 'id' => 'macro-photography-scene-or-poster', 'title' => 'Macro photography scene or poster', 'cat' => 'Image Generation', 'tool' => 'ChatGPT Images', 'kind' => 'image', 'premium' => false, 'summary' => 'A ready ChatGPT Images prompt for a scene or poster in macro photography style.', 'prompt' => 'Create an image: [SCENE OR POSTER IDEA, e.g. a cozy reading nook in a treehouse at dusk].
Style: macro photograph, extreme close-up, tiny water droplets, creamy bokeh, ultra detailed textures.
Composition: [CLOSE-UP / WIDE / CENTERED], main subject [WHERE], background [WHAT].
Mood: [MOOD]. Colours: [PALETTE].
If there is text, write exactly: "[TEXT]" in [FONT STYLE] — keep it short.
Format: [SQUARE / PORTRAIT / LANDSCAPE].', 'output' => 'A macro photography scene or poster that follows the composition notes; short on-image text usually renders correctly, and you can ask for edits in plain English.', 'image' => '' ),
	array( 'id' => 'cold-email-that-gets-replies', 'title' => 'Cold email that gets replies', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A short, personal cold email with a clear reason to reply.', 'prompt' => 'Write a cold email to [PROSPECT ROLE] at [COMPANY].
What I sell: [PRODUCT] — main result: [RESULT].
Personal hook: [SOMETHING SPECIFIC ABOUT THEM].
Rules: under 120 words, subject line under 6 words, one clear pain point, one proof point, a low-effort call to action (a yes/no question). No buzzwords. Give 3 versions.', 'output' => 'Three sub-120-word emails with short subject lines, a personal opening and an easy yes/no ask.', 'image' => '' ),
	array( 'id' => 'follow-up-sequence-after-no-reply', 'title' => 'Follow-up sequence after no reply', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Four polite follow-ups that add value instead of just "bumping".', 'prompt' => 'Write a 4-email follow-up sequence for a prospect who did not reply to: [PASTE FIRST EMAIL].
Spacing: day 3, 7, 14, 21. Each email adds something new (a case study, a tip, a question, a breakup email). Under 80 words each. Friendly, never guilt-tripping.', 'output' => 'Four short follow-ups, each with a fresh reason to reply, ending with a polite breakup email.', 'image' => '' ),
	array( 'id' => 'handle-sales-objections', 'title' => 'Handle sales objections', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Calm, honest answers to the objections you hear most.', 'prompt' => 'I sell [PRODUCT] at [PRICE] to [CUSTOMER]. Give me responses to these objections: too expensive, need to think about it, we already use [COMPETITOR], not the right time, send me details.
For each: what the objection usually means, a question to uncover the real concern, and a 2–3 sentence answer. Never pressure or make false claims.', 'output' => 'A table of common objections with the hidden concern, a discovery question and an honest response for each.', 'image' => '' ),
	array( 'id' => 'discovery-call-script', 'title' => 'Discovery call script', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Questions that uncover needs, budget and timeline on a first sales call.', 'prompt' => 'Create a 20-minute discovery call script for selling [PRODUCT] to [CUSTOMER TYPE].
Sections: opener and agenda (1 min), situation questions, pain questions, impact questions, decision process and budget, next step. Give 3–4 questions per section and notes on what to listen for.', 'output' => 'A timed call script with open questions per stage and listening notes, ending with a clear next-step ask.', 'image' => '' ),
	array( 'id' => 'sales-proposal-outline', 'title' => 'Sales proposal outline', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A structured proposal that sells outcomes, not features.', 'prompt' => 'Write a proposal for [CLIENT] who needs [PROBLEM SOLVED].
Include: executive summary, their goals in their words, proposed solution, scope and deliverables, timeline, pricing options (3 tiers), why us (proof), risks and how we handle them, next steps.
Facts: [YOUR DETAILS, PRICES, CASE STUDIES].', 'output' => 'A complete proposal draft with three pricing tiers and a client-centred summary you can polish and send.', 'image' => '' ),
	array( 'id' => 'linkedin-connection-and-dm-messages', 'title' => 'LinkedIn connection and DM messages', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Non-spammy connection notes and first messages.', 'prompt' => 'Write 5 LinkedIn connection notes (under 200 characters) and 3 follow-up DMs to [ROLE] in [INDUSTRY].
I offer: [OFFER]. Common ground: [SHARED GROUP / POST / EVENT].
No pitch in the connection note; the DMs start a conversation with a question.', 'output' => 'Short connection notes that fit LinkedIn\'s limit and conversational follow-ups that open with a question, not a pitch.', 'image' => '' ),
	array( 'id' => 'value-proposition-canvas', 'title' => 'Value proposition canvas', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Clarify why customers should buy from you in one sentence.', 'prompt' => 'Help me write a value proposition for [PRODUCT] aimed at [CUSTOMER].
1. Customer jobs, pains and gains.
2. How the product relieves each pain and creates each gain.
3. Five one-sentence value propositions in the form "We help [WHO] [ACHIEVE WHAT] by [HOW], unlike [ALTERNATIVE]."
4. Pick the best and explain why.', 'output' => 'A filled-in value proposition canvas and five one-line value statements, with a recommended winner.', 'image' => '' ),
	array( 'id' => 'price-increase-announcement', 'title' => 'Price increase announcement', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Tell customers about a price rise honestly and keep them.', 'prompt' => 'Write an email announcing a price increase for [PRODUCT] from [OLD] to [NEW], effective [DATE].
Reason (true): [REASON]. What customers get: [IMPROVEMENTS].
Include: clear numbers, notice period, any loyalty offer [OFFER OR NONE], how to ask questions. Warm, direct, no hiding the change.', 'output' => 'A clear, respectful announcement that states the change upfront, explains why and offers a path for questions.', 'image' => '' ),
	array( 'id' => 'win-back-message-for-lost-customers', 'title' => 'Win-back message for lost customers', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Re-engage customers who stopped buying.', 'prompt' => 'Write 3 win-back messages (email, SMS under 160 characters, WhatsApp) for customers who have not bought [PRODUCT] in [X] months.
Offer: [OFFER]. Ask why they left with a one-click question. Respect opt-outs and include how to unsubscribe in the email.', 'output' => 'Three channel-specific messages with a genuine offer, a quick feedback ask and an unsubscribe line.', 'image' => '' ),
	array( 'id' => 'sales-call-summary-and-next-steps', 'title' => 'Sales call summary and next steps', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn messy call notes into a CRM-ready summary and follow-up email.', 'prompt' => 'Here are my notes from a sales call: [PASTE NOTES].
1. Summarise: company, people, needs, budget, timeline, decision process, objections.
2. List agreed next steps with owners and dates.
3. Draft the follow-up email.
4. Give a deal health score out of 10 with reasons.', 'output' => 'A structured CRM summary, action items with owners, a follow-up email and an honest deal-health score.', 'image' => '' ),
	array( 'id' => 'ideal-customer-profile-icp', 'title' => 'Ideal customer profile (ICP)', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Define exactly which companies or people to target first.', 'prompt' => 'Create an ideal customer profile for [PRODUCT].
My best 5 customers: [DESCRIBE THEM].
Output: firmographics or demographics, pains, triggers that make them buy now, where to find them, disqualifiers, and 10 search filters I can use on LinkedIn or Google Maps.', 'output' => 'A sharp ICP with buying triggers, disqualifiers and ready-to-use search filters for prospecting.', 'image' => '' ),
	array( 'id' => 'product-demo-script', 'title' => 'Product demo script', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A demo that tells a story around the customer problem.', 'prompt' => 'Write a 15-minute demo script for [PRODUCT] for a [CUSTOMER TYPE] who cares about [TOP PAIN].
Structure: recap their problem, show the before, 3 key moments (feature → benefit → proof), handle one likely objection, close with next step. Include what to click and what to say.', 'output' => 'A click-by-click, say-by-say demo built around the customer\'s pain, with a planned objection and close.', 'image' => '' ),
	array( 'id' => 'referral-request-message', 'title' => 'Referral request message', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Ask happy customers for referrals without being awkward.', 'prompt' => 'Write a referral request for a happy customer of [PRODUCT/SERVICE].
Context: [WHAT WE DID FOR THEM]. Referral reward (if any): [REWARD].
Give: an email, a WhatsApp message and a short in-person script. Make it easy: include a ready-to-forward blurb they can send to friends.', 'output' => 'Three short referral asks plus a forwardable blurb, so customers can refer you in one tap.', 'image' => '' ),
	array( 'id' => 'sales-battlecard-vs-a-competitor', 'title' => 'Sales battlecard vs a competitor', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A one-page card to win deals against a named competitor.', 'prompt' => 'Create a sales battlecard: [MY PRODUCT] vs [COMPETITOR].
Facts I know: [PRICES, FEATURES, REVIEWS].
Sections: when we win, when we lose, key differentiators, landmines to set (questions that highlight our strengths), how to answer "why not [COMPETITOR]?", proof points. Only use facts I provided; mark gaps.', 'output' => 'A one-page battlecard with honest win/lose scenarios, differentiators and discovery questions, with information gaps flagged.', 'image' => '' ),
	array( 'id' => 'negotiation-preparation', 'title' => 'Negotiation preparation', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Plan your walk-away point, trade-offs and opening offer.', 'prompt' => 'Help me prepare to negotiate [DEAL] with [COUNTERPARTY].
My goal: [GOAL]. My limits: [LIMITS]. What I know about them: [INFO].
Output: my BATNA and theirs, walk-away point, opening position, 5 tradeables (cheap for me, valuable for them), likely tactics and calm responses, and a short script for the first 2 minutes.', 'output' => 'A negotiation plan with BATNA, walk-away point, tradeables, counter-tactics and an opening script.', 'image' => '' ),
	array( 'id' => 'whatsapp-sales-catalogue-copy', 'title' => 'WhatsApp sales catalogue copy', 'cat' => 'Sales', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => false, 'summary' => 'Short product descriptions for a WhatsApp Business catalogue.', 'prompt' => 'Write WhatsApp Business catalogue entries for these products: [LIST: name, price, key features].
For each: a product name under 30 characters, a description under 300 characters with benefit first, and 1 line on delivery or availability. Simple English, a few emojis at most.', 'output' => 'Concise catalogue entries that lead with the benefit and fit WhatsApp\'s short format.', 'image' => '' ),
	array( 'id' => 'upsell-and-cross-sell-ideas', 'title' => 'Upsell and cross-sell ideas', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Relevant add-ons that raise order value without annoying customers.', 'prompt' => 'I sell [PRODUCTS] to [CUSTOMERS]. Average order: [VALUE].
Suggest 10 upsell and cross-sell ideas: the trigger moment, the offer, the message (one line) and the expected benefit for the customer. Rank by ease of setup.', 'output' => 'Ten ranked add-on ideas with timing, offer and one-line message, focused on genuine customer benefit.', 'image' => '' ),
	array( 'id' => 'sales-email-subject-line-tester', 'title' => 'Sales email subject line tester', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Twenty subject lines and how to A/B test them.', 'prompt' => 'Write 20 subject lines for this email: [PASTE EMAIL OR TOPIC]. Mix curiosity, benefit, question, personalised and short (2–4 words) styles. Under 50 characters each. Then pick 2 for an A/B test and explain how to judge the winner (open rate vs reply rate).', 'output' => 'Twenty labelled subject lines under 50 characters and a simple A/B test plan.', 'image' => '' ),
	array( 'id' => 'quarterly-sales-plan', 'title' => 'Quarterly sales plan', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Targets, pipeline maths and weekly actions for a sales quarter.', 'prompt' => 'Make a quarterly sales plan. Target revenue: [AMOUNT]. Average deal size: [AMOUNT]. Win rate: [%]. Sales cycle: [DAYS].
1. Work backwards: deals needed, opportunities needed, meetings needed, outreach needed per week.
2. Weekly activity plan.
3. Leading indicators to track and red flags.', 'output' => 'Reverse-engineered pipeline maths, weekly activity targets and the metrics that warn you early.', 'image' => '' ),
	array( 'id' => 'customer-testimonial-request', 'title' => 'Customer testimonial request', 'cat' => 'Sales', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Get useful, specific testimonials from happy clients.', 'prompt' => 'Write a testimonial request to [CLIENT] for [PROJECT/PRODUCT].
Ask 4 guiding questions (before, the decision, the result with numbers, who they would recommend us to). Offer to draft it for their approval. Under 120 words. Include permission wording for using their name and logo.', 'output' => 'A short request with guiding questions that produce specific, permission-cleared testimonials.', 'image' => '' ),
	array( 'id' => 'google-ads-search-ad-copy', 'title' => 'Google Ads search ad copy', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Responsive search ad headlines and descriptions that fit the limits.', 'prompt' => 'Write Google Ads responsive search ad copy for [PRODUCT/SERVICE] targeting the keyword "[KEYWORD]".
Give 15 headlines (max 30 characters each) and 4 descriptions (max 90 characters each). Include the keyword in some headlines, a clear benefit, proof, an offer [OFFER] and a call to action. Show the character count next to each.', 'output' => 'Fifteen headlines and four descriptions within Google\'s character limits, each with its count, ready to paste.', 'image' => '' ),
	array( 'id' => 'meta-facebook-instagram-ad-variations', 'title' => 'Meta (Facebook/Instagram) ad variations', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Primary text, headline and creative ideas for Meta ads.', 'prompt' => 'Create 5 Meta ad variations for [PRODUCT] aimed at [AUDIENCE].
For each: primary text (first 125 characters must work alone), headline (under 40 characters), description, call-to-action button, and a creative idea (image or 15-second video concept). Angles: pain, benefit, social proof, offer, curiosity. Avoid claims that need proof I have not given.', 'output' => 'Five angle-based ads with short headlines, hooks that work in the preview and a creative concept for each.', 'image' => '' ),
	array( 'id' => 'landing-page-copy-2', 'title' => 'Landing page copy', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'High-converting landing page sections from headline to FAQ.', 'prompt' => 'Write landing page copy for [PRODUCT].
Audience: [WHO]. Main promise: [RESULT]. Proof: [TESTIMONIALS, NUMBERS]. Offer: [OFFER].
Sections: hero (headline, sub-headline, CTA), problem, solution, 3 benefits, how it works (3 steps), social proof, pricing, FAQ (6), final CTA. Give 3 headline options.', 'output' => 'Complete landing-page copy in section order, with three headline options and an FAQ that handles objections.', 'image' => '' ),
	array( 'id' => 'seo-blog-post-brief', 'title' => 'SEO blog post brief', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A writer-ready brief: intent, outline, keywords and internal links.', 'prompt' => 'Create an SEO content brief for the keyword "[KEYWORD]".
Include: search intent, target reader, title options (under 60 characters), meta description (under 155 characters), H2/H3 outline, questions to answer (People Also Ask style), related terms to cover naturally, internal links to [MY PAGES], and a word-count range based on depth needed.', 'output' => 'A complete brief with intent, titles, meta description, outline and questions, so any writer can produce a ranking article.', 'image' => '' ),
	array( 'id' => 'email-newsletter', 'title' => 'Email newsletter', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A newsletter people actually open and read.', 'prompt' => 'Write this week\'s newsletter for [BRAND] to [AUDIENCE].
Topics: [1–3 TOPICS / LINKS]. Goal: [CLICKS / REPLIES / SALES].
Format: subject line (3 options), preview text, short personal intro, main story, 2 quick tips, one CTA, P.S. line. Conversational, under 400 words.', 'output' => 'Three subject lines, preview text and a skimmable sub-400-word newsletter with one clear call to action.', 'image' => '' ),
	array( 'id' => 'brand-voice-guide', 'title' => 'Brand voice guide', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Define how your brand sounds, with do and don’t examples.', 'prompt' => 'Create a brand voice guide for [BRAND] that sells [PRODUCT] to [AUDIENCE].
Our personality in 3 words: [WORDS].
Include: voice principles (3–4), tone shifts by situation (social, support, error messages, sales), vocabulary we use and avoid, 5 do/don\'t example pairs, and a one-paragraph sample.', 'output' => 'A practical voice guide with principles, tone by context, word lists and before/after examples.', 'image' => '' ),
	array( 'id' => 'product-launch-plan', 'title' => 'Product launch plan', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 4-week launch timeline across channels.', 'prompt' => 'Plan the launch of [PRODUCT] on [DATE]. Audience: [WHO]. Budget: [AMOUNT]. Channels I have: [LIST].
Give a 4-week plan: pre-launch teasers, waitlist, launch-day checklist, post-launch follow-ups. Include messages for each channel and the metrics to watch.', 'output' => 'A week-by-week launch timeline with channel messages, a launch-day checklist and success metrics.', 'image' => '' ),
	array( 'id' => 'marketing-campaign-ideas', 'title' => 'Marketing campaign ideas', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Fifteen creative campaign concepts with execution notes.', 'prompt' => 'Brainstorm 15 marketing campaign ideas for [BRAND] in [NICHE] around [OCCASION OR GOAL].
For each: name, big idea in one line, channels, how to execute on a [SMALL / MEDIUM] budget, and how to measure it. Include 3 bold ideas and 3 low-effort quick wins.', 'output' => 'Fifteen named campaign concepts with execution and measurement notes, including bold bets and quick wins.', 'image' => '' ),
	array( 'id' => 'google-business-profile-optimisation', 'title' => 'Google Business Profile optimisation', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Rank higher in local search and on Maps.', 'prompt' => 'Optimise my Google Business Profile for [BUSINESS] in [CITY].
Give: the best primary and secondary categories, a business description (up to 750 characters) with local keywords, 10 services or products to list, a weekly post plan, a review-request message, and 5 Q&A entries to add.', 'output' => 'A ready description within the 750-character limit, categories, services, a post plan and a review-request message.', 'image' => '' ),
	array( 'id' => 'customer-survey-questions', 'title' => 'Customer survey questions', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A short survey that gives you decisions, not just data.', 'prompt' => 'Create a customer survey for [BUSINESS] to learn [GOAL].
Max 10 questions; mix multiple-choice, scale and 2 open questions. Avoid leading questions. Then explain how I will use each answer to make a decision.', 'output' => 'A sub-10-question unbiased survey with a note on which decision each answer informs.', 'image' => '' ),
	array( 'id' => 'influencer-outreach-message', 'title' => 'Influencer outreach message', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Pitch creators for a collaboration that is fair for both sides.', 'prompt' => 'Write an outreach DM and email to [CREATOR TYPE] with [FOLLOWER RANGE] followers about promoting [PRODUCT].
What I offer: [PAYMENT / PRODUCT / COMMISSION]. Show I know their content: [REFERENCE].
Include the deliverables, timeline, disclosure requirement (#ad / paid partnership label) and next step.', 'output' => 'A personalised DM and email with clear deliverables, compensation and a reminder about paid-partnership disclosure.', 'image' => '' ),
	array( 'id' => 'unique-selling-points-finder', 'title' => 'Unique selling points finder', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Find what makes your product different and provable.', 'prompt' => 'Help me find my unique selling points. Product: [PRODUCT]. Competitors: [LIST]. Customer reviews: [PASTE SOME].
List 10 candidate USPs, score each on uniqueness, importance to customers and provability (1–5), and turn the top 3 into one-line claims with the proof to show next to them.', 'output' => 'A scored list of candidate USPs and three strong, provable one-line claims.', 'image' => '' ),
	array( 'id' => 'content-repurposing-plan', 'title' => 'Content repurposing plan', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn one long piece into 10+ posts across platforms.', 'prompt' => 'Repurpose this content: [PASTE BLOG / VIDEO TRANSCRIPT / PODCAST NOTES].
Create: 3 short-video scripts, 1 carousel outline, 3 text posts, 1 newsletter section, 5 quote graphics text, and 1 Pinterest pin title and description. Keep each native to its platform.', 'output' => 'A full repurposing pack from a single source: scripts, carousel, posts, newsletter section, quotes and a pin.', 'image' => '' ),
	array( 'id' => 'email-welcome-sequence', 'title' => 'Email welcome sequence', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A 5-email onboarding series for new subscribers.', 'prompt' => 'Write a 5-email welcome sequence for new subscribers of [BRAND] who signed up for [LEAD MAGNET].
Day 0 deliver + welcome, day 2 story, day 4 best content, day 6 social proof, day 8 soft offer [OFFER]. Each under 200 words with one CTA and an unsubscribe reminder in the footer.', 'output' => 'Five short emails with a clear role each, building trust before a soft offer, footer reminders included.', 'image' => '' ),
	array( 'id' => 'marketing-kpi-dashboard-plan', 'title' => 'Marketing KPI dashboard plan', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Choose the right metrics and how to read them.', 'prompt' => 'Design a simple marketing KPI dashboard for [BUSINESS TYPE] with goal [GOAL].
List 8–10 metrics across awareness, acquisition, conversion and retention: definition, formula, data source, healthy range for a business like mine (say if it is a rough guide), and the action to take if it drops.', 'output' => 'A focused metric set with formulas, sources, rough benchmarks and clear actions for each.', 'image' => '' ),
	array( 'id' => 'tagline-and-slogan-generator', 'title' => 'Tagline and slogan generator', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Memorable taglines in several styles.', 'prompt' => 'Write 25 taglines for [BRAND] that does [WHAT] for [WHO]. Personality: [WORDS].
Group them: benefit-led, emotional, playful, premium, short (2–3 words). Max 8 words each. Then shortlist 3 and say why they work.', 'output' => 'Twenty-five grouped taglines under eight words and a reasoned shortlist of three.', 'image' => '' ),
	array( 'id' => 'affiliate-programme-pitch', 'title' => 'Affiliate programme pitch', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Invite partners to promote your product.', 'prompt' => 'Write an affiliate programme page and recruitment email for [PRODUCT].
Commission: [RATE], cookie window [DAYS], payout [METHOD/THRESHOLD].
Include: who it is for, what they earn (with a worked example), assets we provide, rules (no spam, disclosure required), and how to join.', 'output' => 'A clear affiliate page and email with a worked earnings example, rules and joining steps.', 'image' => '' ),
	array( 'id' => 'seasonal-sale-promotion', 'title' => 'Seasonal sale promotion', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'An honest festive or seasonal campaign across channels.', 'prompt' => 'Plan a [FESTIVAL / SEASON] sale for [BRAND].
Offer: [DISCOUNT / BUNDLE]. Dates: [START–END]. Channels: [LIST].
Give: campaign name, 3 messages (teaser, launch, last day), banner text, email subject lines, and a reminder schedule. Use a real end date only — no fake countdowns or fake scarcity.', 'output' => 'A complete seasonal campaign kit with honest urgency based on the real end date.', 'image' => '' ),
	array( 'id' => 'customer-journey-map', 'title' => 'Customer journey map', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Map every touchpoint from first visit to repeat purchase.', 'prompt' => 'Create a customer journey map for [BUSINESS].
Stages: awareness, consideration, purchase, onboarding, loyalty, advocacy. For each: customer goal, touchpoints, thoughts and feelings, pain points, and one improvement idea. Output as a table.', 'output' => 'A stage-by-stage journey table with touchpoints, emotions, pain points and improvements.', 'image' => '' ),
	array( 'id' => 'press-release', 'title' => 'Press release', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A news-style press release journalists can use.', 'prompt' => 'Write a press release about [NEWS] from [COMPANY] in [CITY], dated [DATE].
Include: headline, sub-headline, dateline and lead paragraph (who, what, when, where, why), 2 quotes (from [PEOPLE]), key facts, boilerplate about the company, and media contact placeholders. Neutral, factual tone; under 450 words.', 'output' => 'A sub-450-word release in standard news format with quotes, facts and a boilerplate.', 'image' => '' ),
	array( 'id' => 'a-b-test-ideas-for-a-web-page', 'title' => 'A/B test ideas for a web page', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Prioritised experiments to lift conversions.', 'prompt' => 'Here is my page and its current conversion rate: [URL OR COPY], [RATE].
Suggest 10 A/B test ideas. For each: hypothesis (because… we expect…), change, metric, and an ICE score (impact, confidence, ease). Rank them and explain how long to run the top test with my traffic of [VISITORS/DAY].', 'output' => 'Ten hypothesis-driven test ideas ranked by ICE score, with guidance on test duration.', 'image' => '' ),
	array( 'id' => 'brand-name-ideas', 'title' => 'Brand name ideas', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Name options with availability checks to do next.', 'prompt' => 'Generate 30 brand name ideas for [BUSINESS] that sells [WHAT] to [WHO]. Feel: [WORDS].
Mix invented words, compound words, metaphors and descriptive names. For each: meaning in one line. Then shortlist 5 and tell me what to check (domain, social handles, trademark search in [COUNTRY]).', 'output' => 'Thirty names with meanings, a shortlist of five and a checklist for domain, handle and trademark checks.', 'image' => '' ),
	array( 'id' => 'lead-magnet-ideas', 'title' => 'Lead magnet ideas', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Free resources that attract the right subscribers.', 'prompt' => 'Suggest 12 lead magnet ideas for [BUSINESS] whose ideal customer is [WHO] with problem [PROBLEM].
Formats: checklist, template, mini-course, quiz, calculator, swipe file. For each: title, promise, format, how long to make, and how it leads to my paid offer [OFFER].', 'output' => 'Twelve lead magnet concepts with titles, effort estimates and a clear path to your paid offer.', 'image' => '' ),
	array( 'id' => 'positioning-statement', 'title' => 'Positioning statement', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A clear statement of who you serve and why you win.', 'prompt' => 'Write a positioning statement for [PRODUCT].
Template: For [TARGET] who [NEED], [PRODUCT] is a [CATEGORY] that [KEY BENEFIT]. Unlike [ALTERNATIVE], we [DIFFERENTIATOR].
Give 3 versions, then a one-line elevator pitch and a 30-second spoken version.', 'output' => 'Three positioning statements, a one-liner and a 30-second pitch, all consistent.', 'image' => '' ),
	array( 'id' => 'marketing-budget-split', 'title' => 'Marketing budget split', 'cat' => 'Marketing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Allocate a small budget across channels with a test-and-learn plan.', 'prompt' => 'I have [AMOUNT] per month for marketing [BUSINESS]. Goal: [GOAL]. Audience: [WHO].
Suggest a channel split with reasons, what to test in month 1, the stop/scale rules for each channel, and the minimum tracking setup I need first.', 'output' => 'A reasoned budget split, month-one tests, clear stop or scale rules and a minimum tracking setup.', 'image' => '' ),
	array( 'id' => 'blog-post-outline-to-full-draft', 'title' => 'Blog post outline to full draft', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Go from a topic to a structured, readable blog draft.', 'prompt' => 'Write a blog post about [TOPIC] for [AUDIENCE].
First give an outline (title, intro hook, H2s with key points, conclusion). Wait for my OK. Then write the full post: short paragraphs, examples, one table or list where useful, and a conclusion with a takeaway. Target [WORD COUNT] words. Do not invent statistics; use [SOURCES] if given.', 'output' => 'An outline to approve first, then a full draft with examples and a clear takeaway, without made-up numbers.', 'image' => '' ),
	array( 'id' => 'proofread-and-explain-the-fixes', 'title' => 'Proofread and explain the fixes', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Correct grammar and style, and learn from each change.', 'prompt' => 'Proofread the text below. Fix grammar, spelling, punctuation and awkward sentences, but keep my voice.
Return: 1) the corrected text, 2) a table of changes: original | corrected | rule or reason.
Text:
"""
[PASTE TEXT]
"""', 'output' => 'A clean version of your text and a change table that explains each fix, so you learn as you go.', 'image' => '' ),
	array( 'id' => 'summarise-a-long-article', 'title' => 'Summarise a long article', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A layered summary: one line, five bullets and key quotes.', 'prompt' => 'Summarise the text below.
1. One-sentence summary.
2. 5 key points.
3. 2–3 notable quotes (exact words).
4. What is missing or debatable.
5. Who should read the full text.
Text:
"""
[PASTE]
"""', 'output' => 'A one-liner, key points, exact quotes and a critical note, so you can decide whether to read the whole thing.', 'image' => '' ),
	array( 'id' => 'write-a-professional-email', 'title' => 'Write a professional email', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Clear, polite emails for any work situation.', 'prompt' => 'Write an email to [RECIPIENT] about [SITUATION].
Goal: [WHAT I NEED]. Tone: [TONE]. Key facts: [FACTS]. Deadline: [DATE].
Keep it under 150 words, subject line included, one clear ask, and a polite close.', 'output' => 'A sub-150-word email with a subject line, the key facts and one clear request.', 'image' => '' ),
	array( 'id' => 'short-story-from-a-prompt', 'title' => 'Short story from a prompt', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'An original short story with a twist ending.', 'prompt' => 'Write an original short story (about [WORD COUNT] words).
Genre: [GENRE]. Main character: [WHO]. Setting: [WHERE/WHEN]. Must include: [ELEMENT].
Structure: hook, rising tension, a turning point, a satisfying twist. Show, don\'t tell; vivid but simple language.', 'output' => 'An original story with a strong hook, tension and a twist, written to your length and genre.', 'image' => '' ),
	array( 'id' => 'speech-for-an-event', 'title' => 'Speech for an event', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A speech that sounds natural when spoken aloud.', 'prompt' => 'Write a [MINUTES]-minute speech for [EVENT] to [AUDIENCE]. I am [ROLE].
Message: [CORE MESSAGE]. Include: an opening story or question, 3 main points with examples, one memorable line, and a close that calls people to [ACTION].
Write for the ear: short sentences, natural pauses marked with (pause).', 'output' => 'A spoken-word script with pause marks, a story opening, three points and a memorable close.', 'image' => '' ),
	array( 'id' => 'paraphrase-without-plagiarism', 'title' => 'Paraphrase without plagiarism', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Rewrite a passage in your own words and cite it properly.', 'prompt' => 'Paraphrase the passage below in my own words for [PURPOSE, e.g. an assignment]. Keep the meaning, change the structure and vocabulary, and do not add claims.
Then give a citation in [APA / MLA] style for: [SOURCE DETAILS].
Passage:
"""
[PASTE]
"""', 'output' => 'A faithful paraphrase with new structure and wording, plus a correctly formatted citation for the source.', 'image' => '' ),
	array( 'id' => 'product-description', 'title' => 'Product description', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Benefit-led product copy for an online store.', 'prompt' => 'Write a product description for [PRODUCT] for my online store.
Features: [LIST]. Customer: [WHO]. Tone: [TONE].
Give: a 1-line hook, a 60–100 word description that turns features into benefits, 5 bullet points, and an SEO title under 60 characters.', 'output' => 'A hook, a benefit-led description, scannable bullets and an SEO title within 60 characters.', 'image' => '' ),
	array( 'id' => 'personal-statement-sop', 'title' => 'Personal statement / SOP', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A statement of purpose that tells your story with evidence.', 'prompt' => 'Help me write a statement of purpose for [PROGRAM] at [UNIVERSITY].
My background: [EDUCATION, PROJECTS, WORK, WHY THIS FIELD, CAREER GOAL].
First ask me 5 questions to find my strongest story. Then draft about [WORD LIMIT] words: a specific opening moment, academic and project evidence, why this program (specific courses or labs), and future goals. No clichés like "since childhood".', 'output' => 'Five probing questions first, then a specific, evidence-backed draft within your word limit.', 'image' => '' ),
	array( 'id' => 'rewrite-for-clarity', 'title' => 'Rewrite for clarity', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn dense text into plain English without losing meaning.', 'prompt' => 'Rewrite the text below in plain English for [AUDIENCE]. Short sentences, active voice, everyday words, technical terms explained once. Keep all facts. Then give a readability comparison (before/after) in one line.
"""
[PASTE]
"""', 'output' => 'A plain-English version with all facts intact and a quick before/after readability note.', 'image' => '' ),
	array( 'id' => 'headline-generator', 'title' => 'Headline generator', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Twenty headlines for an article or video.', 'prompt' => 'Write 20 headlines for an article about [TOPIC] for [AUDIENCE]. Styles: how-to, list, question, curiosity, bold claim, number-led. Under 65 characters each. Mark the 3 best for search and the 3 best for social sharing.', 'output' => 'Twenty headlines under 65 characters, with picks for search and for social.', 'image' => '' ),
	array( 'id' => 'write-a-poem', 'title' => 'Write a poem', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'An original poem in the form and mood you choose.', 'prompt' => 'Write an original poem about [SUBJECT].
Form: [FREE VERSE / SONNET / HAIKU SEQUENCE / RHYMING QUATRAINS]. Mood: [MOOD]. Include one concrete image from [PLACE / MEMORY]. Length: [LINES].
After the poem, explain the form choices in 2 lines.', 'output' => 'An original poem in your chosen form and mood, with a short note on the craft choices.', 'image' => '' ),
	array( 'id' => 'book-chapter-outline', 'title' => 'Book chapter outline', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Plan a non-fiction book chapter by chapter.', 'prompt' => 'Outline a non-fiction book about [TOPIC] for [READER]. Promise of the book: [OUTCOME].
Give: 3 title and subtitle options, 10–12 chapters with a one-line purpose, 3–5 key points and one story or case idea each, and the reader\'s transformation arc.', 'output' => 'Title options and a full chapter plan with purpose, key points and story ideas per chapter.', 'image' => '' ),
	array( 'id' => 'meeting-minutes-from-notes', 'title' => 'Meeting minutes from notes', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Clean minutes with decisions and action items.', 'prompt' => 'Turn these meeting notes into minutes: [PASTE NOTES].
Format: meeting title, date, attendees, agenda items with discussion summary, decisions made, action items (task | owner | due date), open questions. Neutral tone, no invented details.', 'output' => 'Structured minutes with decisions and an action-item table, based only on your notes.', 'image' => '' ),
	array( 'id' => 'apology-message', 'title' => 'Apology message', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A sincere apology that takes responsibility and fixes things.', 'prompt' => 'Write an apology to [PERSON / CUSTOMERS] for [WHAT HAPPENED].
What caused it (true): [CAUSE]. What we are doing to fix it: [FIX]. Compensation (if any): [OFFER].
Rules: no excuses, no "if anyone was offended", take responsibility, explain the fix and how we prevent it. Under 150 words.', 'output' => 'A short, sincere apology that owns the mistake, explains the fix and prevents a repeat.', 'image' => '' ),
	array( 'id' => 'explain-a-complex-idea-with-analogies', 'title' => 'Explain a complex idea with analogies', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Five analogies to make a hard idea click.', 'prompt' => 'Explain [COMPLEX IDEA] using 5 different analogies for [AUDIENCE] (e.g. cooking, sport, traffic, school, nature).
For each: the analogy, where it fits well, and where it breaks down. Then a 3-sentence plain explanation.', 'output' => 'Five analogies with their limits and a short plain explanation, so the idea sticks.', 'image' => '' ),
	array( 'id' => 'write-a-linkedin-article', 'title' => 'Write a LinkedIn article', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A thoughtful long-form post that builds authority.', 'prompt' => 'Write a LinkedIn article on [TOPIC] from my experience as [ROLE].
My key insight: [INSIGHT]. A real example: [EXAMPLE].
Structure: hook, the common belief, what I learned, 3 lessons with examples, a practical takeaway, a question to invite comments. 600–900 words, first person.', 'output' => 'A first-person article with a strong hook, real lessons and a closing question that invites discussion.', 'image' => '' ),
	array( 'id' => 'script-for-an-explainer-video', 'title' => 'Script for an explainer video', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 60–90 second explainer script with visuals.', 'prompt' => 'Write a 60–90 second explainer video script for [PRODUCT / IDEA] for [AUDIENCE].
Two columns: Visual | Voice-over. Structure: problem (10 s), solution (20 s), how it works (30 s), proof (10 s), call to action (10 s). Simple words, about 150–200 spoken words.', 'output' => 'A timed two-column script with visuals and voice-over, sized for a 60–90 second video.', 'image' => '' ),
	array( 'id' => 'write-a-bio-in-three-lengths', 'title' => 'Write a bio in three lengths', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Short, medium and long bios for profiles and events.', 'prompt' => 'Write my professional bio in three lengths: 1 line (under 25 words), short (about 60 words) and long (about 150 words).
About me: [ROLE, EXPERIENCE, ACHIEVEMENTS, PERSONAL TOUCH]. Write in [FIRST / THIRD] person, tone [TONE].', 'output' => 'Three consistent bios of different lengths for social profiles, websites and speaker introductions.', 'image' => '' ),
	array( 'id' => 'edit-for-word-count', 'title' => 'Edit for word count', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Cut text to an exact word limit while keeping the meaning.', 'prompt' => 'Shorten the text below to [N] words (±5%) without losing the key points or my voice. Show the final word count. Then list what you cut and why.
"""
[PASTE]
"""', 'output' => 'A tightened version that hits the word limit, with its count and a list of what was removed.', 'image' => '' ),
	array( 'id' => 'rewrite-in-a-formal-tone', 'title' => 'Rewrite in a formal tone', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Change the tone of any text to formal while keeping the facts.', 'prompt' => 'Rewrite the text below in a formal tone for [AUDIENCE]. Keep every fact and the length roughly the same. Then give one alternative opening line.
"""
[PASTE TEXT]
"""', 'output' => 'The same message rewritten in a formal tone, facts unchanged, with an alternative opening line.', 'image' => '' ),
	array( 'id' => 'rewrite-in-a-friendly-tone', 'title' => 'Rewrite in a friendly tone', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Change the tone of any text to friendly while keeping the facts.', 'prompt' => 'Rewrite the text below in a friendly tone for [AUDIENCE]. Keep every fact and the length roughly the same. Then give one alternative opening line.
"""
[PASTE TEXT]
"""', 'output' => 'The same message rewritten in a friendly tone, facts unchanged, with an alternative opening line.', 'image' => '' ),
	array( 'id' => 'rewrite-in-a-persuasive-tone', 'title' => 'Rewrite in a persuasive tone', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Change the tone of any text to persuasive while keeping the facts.', 'prompt' => 'Rewrite the text below in a persuasive tone for [AUDIENCE]. Keep every fact and the length roughly the same. Then give one alternative opening line.
"""
[PASTE TEXT]
"""', 'output' => 'The same message rewritten in a persuasive tone, facts unchanged, with an alternative opening line.', 'image' => '' ),
	array( 'id' => 'rewrite-in-a-simple-for-a-12-year-old-tone', 'title' => 'Rewrite in a simple (for a 12-year-old) tone', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Change the tone of any text to simple (for a 12-year-old) while keeping the facts.', 'prompt' => 'Rewrite the text below in a simple (for a 12-year-old) tone for [AUDIENCE]. Keep every fact and the length roughly the same. Then give one alternative opening line.
"""
[PASTE TEXT]
"""', 'output' => 'The same message rewritten in a simple tone, facts unchanged, with an alternative opening line.', 'image' => '' ),
	array( 'id' => 'rewrite-in-a-confident-tone', 'title' => 'Rewrite in a confident tone', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Change the tone of any text to confident while keeping the facts.', 'prompt' => 'Rewrite the text below in a confident tone for [AUDIENCE]. Keep every fact and the length roughly the same. Then give one alternative opening line.
"""
[PASTE TEXT]
"""', 'output' => 'The same message rewritten in a confident tone, facts unchanged, with an alternative opening line.', 'image' => '' ),
	array( 'id' => 'rewrite-in-a-empathetic-tone', 'title' => 'Rewrite in a empathetic tone', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Change the tone of any text to empathetic while keeping the facts.', 'prompt' => 'Rewrite the text below in a empathetic tone for [AUDIENCE]. Keep every fact and the length roughly the same. Then give one alternative opening line.
"""
[PASTE TEXT]
"""', 'output' => 'The same message rewritten in a empathetic tone, facts unchanged, with an alternative opening line.', 'image' => '' ),
	array( 'id' => 'rewrite-in-a-humorous-tone', 'title' => 'Rewrite in a humorous tone', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Change the tone of any text to humorous while keeping the facts.', 'prompt' => 'Rewrite the text below in a humorous tone for [AUDIENCE]. Keep every fact and the length roughly the same. Then give one alternative opening line.
"""
[PASTE TEXT]
"""', 'output' => 'The same message rewritten in a humorous tone, facts unchanged, with an alternative opening line.', 'image' => '' ),
	array( 'id' => 'rewrite-in-a-concise-tone', 'title' => 'Rewrite in a concise tone', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Change the tone of any text to concise while keeping the facts.', 'prompt' => 'Rewrite the text below in a concise tone for [AUDIENCE]. Keep every fact and the length roughly the same. Then give one alternative opening line.
"""
[PASTE TEXT]
"""', 'output' => 'The same message rewritten in a concise tone, facts unchanged, with an alternative opening line.', 'image' => '' ),
	array( 'id' => 'rewrite-in-a-storytelling-tone', 'title' => 'Rewrite in a storytelling tone', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Change the tone of any text to storytelling while keeping the facts.', 'prompt' => 'Rewrite the text below in a storytelling tone for [AUDIENCE]. Keep every fact and the length roughly the same. Then give one alternative opening line.
"""
[PASTE TEXT]
"""', 'output' => 'The same message rewritten in a storytelling tone, facts unchanged, with an alternative opening line.', 'image' => '' ),
	array( 'id' => 'rewrite-in-a-professional-academic-tone', 'title' => 'Rewrite in a professional-academic tone', 'cat' => 'Writing', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Change the tone of any text to professional-academic while keeping the facts.', 'prompt' => 'Rewrite the text below in a professional-academic tone for [AUDIENCE]. Keep every fact and the length roughly the same. Then give one alternative opening line.
"""
[PASTE TEXT]
"""', 'output' => 'The same message rewritten in a professional-academic tone, facts unchanged, with an alternative opening line.', 'image' => '' ),
	array( 'id' => 'slow-dolly-in-reveal-video-shot', 'title' => 'Slow dolly-in reveal video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a slow dolly-in reveal: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. The camera slowly dollies in from a wide shot to a close-up. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear slow dolly-in reveal; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'orbit-around-a-product-video-shot', 'title' => 'Orbit around a product video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a orbit around a product: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. The camera orbits 180 degrees around the subject at eye level. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear orbit around a product; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'drone-flyover-video-shot', 'title' => 'Drone flyover video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => true, 'summary' => 'An AI video prompt for a drone flyover: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. An aerial drone shot glides forward over the landscape, slowly tilting down. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear drone flyover; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'tracking-shot-walking-video-shot', 'title' => 'Tracking shot walking video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a tracking shot walking: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. The camera tracks alongside the subject as they walk, steady gimbal movement. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear tracking shot walking; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'macro-detail-shot-video-shot', 'title' => 'Macro detail shot video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a macro detail shot: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. An extreme macro close-up with a slow focus pull across textures. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear macro detail shot; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'time-lapse-sky-video-shot', 'title' => 'Time-lapse sky video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => true, 'summary' => 'An AI video prompt for a time-lapse sky: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. A time-lapse of clouds moving fast over a still scene, day turning to golden hour. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear time-lapse sky; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'whip-pan-transition-video-shot', 'title' => 'Whip-pan transition video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a whip-pan transition: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. A fast whip pan blurs the frame and lands on the next scene. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear whip-pan transition; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'top-down-flat-lay-video-shot', 'title' => 'Top-down flat lay video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a top-down flat lay: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. A top-down overhead shot as hands arrange objects on a table. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear top-down flat lay; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'rack-focus-foreground-to-background-video-shot', 'title' => 'Rack focus foreground to background video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => true, 'summary' => 'An AI video prompt for a rack focus foreground to background: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. Focus shifts from a foreground object to the subject behind it. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear rack focus foreground to background; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'slow-motion-pour-video-shot', 'title' => 'Slow-motion pour video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a slow-motion pour: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. A slow-motion shot of liquid pouring, droplets catching the light. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear slow-motion pour; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'low-angle-hero-shot-video-shot', 'title' => 'Low-angle hero shot video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a low-angle hero shot: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. A low-angle shot looking up at the subject, slow push-in, dramatic sky. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear low-angle hero shot; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'handheld-documentary-video-shot', 'title' => 'Handheld documentary video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a handheld documentary: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. A handheld documentary-style shot with natural light and slight camera shake. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear handheld documentary; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'parallax-still-to-motion-video-shot', 'title' => 'Parallax still to motion video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => true, 'summary' => 'An AI video prompt for a parallax still to motion: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. A still scene comes alive with gentle parallax between foreground and background layers. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear parallax still to motion; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'crane-up-establishing-shot-video-shot', 'title' => 'Crane up establishing shot video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a crane up establishing shot: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. The camera cranes up from street level to reveal the whole location. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear crane up establishing shot; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'product-spin-on-turntable-video-shot', 'title' => 'Product spin on turntable video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a product spin on turntable: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. The product rotates 360 degrees on a turntable against a clean backdrop. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear product spin on turntable; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'through-the-window-push-video-shot', 'title' => 'Through-the-window push video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => true, 'summary' => 'An AI video prompt for a through-the-window push: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. The camera pushes through a window or doorway into the room. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear through-the-window push; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'night-city-bokeh-video-shot', 'title' => 'Night city bokeh video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a night city bokeh: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. A night city street with soft bokeh lights, subject in focus, slow lateral move. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear night city bokeh; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'underwater-glide-video-shot', 'title' => 'Underwater glide video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a underwater glide: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. An underwater shot gliding forward with light rays from the surface. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear underwater glide; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'split-screen-before-and-after-video-shot', 'title' => 'Split-screen before and after video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => true, 'summary' => 'An AI video prompt for a split-screen before and after: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. A split-screen showing the before on the left and after on the right, a wipe reveals the change. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear split-screen before and after; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'pov-first-person-video-shot', 'title' => 'POV first-person video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a pov first-person: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. A first-person point-of-view shot of hands doing the task. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear pov first-person; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'stop-motion-style-video-shot', 'title' => 'Stop-motion style video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a stop-motion style: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. A stop-motion style sequence where objects move in small jumps. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear stop-motion style; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'seasons-transition-video-shot', 'title' => 'Seasons transition video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a seasons transition: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. The same location transitions through spring, summer, autumn and winter. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear seasons transition; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'floating-particles-mood-video-shot', 'title' => 'Floating particles mood video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => true, 'summary' => 'An AI video prompt for a floating particles mood: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. A dark scene with floating dust particles catching a single beam of light. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear floating particles mood; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => 'logo-reveal-video-shot', 'title' => 'Logo reveal video shot', 'cat' => 'Video & Reels', 'tool' => 'AI video (Veo / Sora / Runway)', 'kind' => 'video', 'premium' => false, 'summary' => 'An AI video prompt for a logo reveal: camera move, lighting and mood spelled out.', 'prompt' => 'Video prompt: [SUBJECT AND SETTING]. Abstract shapes swirl and assemble into a logo, then settle with a soft glow. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colour palette: [COLOURS]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No text on screen.

Tips: describe one camera move per clip, keep the subject simple, and generate 3–4 takes to pick the best.', 'output' => 'A short clip with a clear logo reveal; results vary between models, so expect to run a few takes.', 'image' => '' ),
	array( 'id' => '30-second-reel-script-for-a-fitness-coach', 'title' => '30-second Reel script for a fitness coach', 'cat' => 'Video & Reels', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A hook-first short-video script for a fitness coach, with shots and on-screen text.', 'prompt' => 'Write a 30-second Reel/Short script for a fitness coach about [TOPIC].
Format as a table: Time | Shot | Voice-over | On-screen text.
0–2 s: a hook that stops the scroll. 2–25 s: 3 quick value points or a mini story. 25–30 s: call to action [ACTION].
Also give: 3 alternative hooks, a caption and 5 hashtags.', 'output' => 'A timed shot-by-shot script with hook, value and call to action, plus alternative hooks, caption and hashtags.', 'image' => '' ),
	array( 'id' => '30-second-reel-script-for-a-home-cook', 'title' => '30-second Reel script for a home cook', 'cat' => 'Video & Reels', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A hook-first short-video script for a home cook, with shots and on-screen text.', 'prompt' => 'Write a 30-second Reel/Short script for a home cook about [TOPIC].
Format as a table: Time | Shot | Voice-over | On-screen text.
0–2 s: a hook that stops the scroll. 2–25 s: 3 quick value points or a mini story. 25–30 s: call to action [ACTION].
Also give: 3 alternative hooks, a caption and 5 hashtags.', 'output' => 'A timed shot-by-shot script with hook, value and call to action, plus alternative hooks, caption and hashtags.', 'image' => '' ),
	array( 'id' => '30-second-reel-script-for-a-personal-finance-creator', 'title' => '30-second Reel script for a personal finance creator', 'cat' => 'Video & Reels', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A hook-first short-video script for a personal finance creator, with shots and on-screen text.', 'prompt' => 'Write a 30-second Reel/Short script for a personal finance creator about [TOPIC].
Format as a table: Time | Shot | Voice-over | On-screen text.
0–2 s: a hook that stops the scroll. 2–25 s: 3 quick value points or a mini story. 25–30 s: call to action [ACTION].
Also give: 3 alternative hooks, a caption and 5 hashtags.', 'output' => 'A timed shot-by-shot script with hook, value and call to action, plus alternative hooks, caption and hashtags.', 'image' => '' ),
	array( 'id' => '30-second-reel-script-for-a-tech-reviewer', 'title' => '30-second Reel script for a tech reviewer', 'cat' => 'Video & Reels', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A hook-first short-video script for a tech reviewer, with shots and on-screen text.', 'prompt' => 'Write a 30-second Reel/Short script for a tech reviewer about [TOPIC].
Format as a table: Time | Shot | Voice-over | On-screen text.
0–2 s: a hook that stops the scroll. 2–25 s: 3 quick value points or a mini story. 25–30 s: call to action [ACTION].
Also give: 3 alternative hooks, a caption and 5 hashtags.', 'output' => 'A timed shot-by-shot script with hook, value and call to action, plus alternative hooks, caption and hashtags.', 'image' => '' ),
	array( 'id' => '30-second-reel-script-for-a-travel-vlogger', 'title' => '30-second Reel script for a travel vlogger', 'cat' => 'Video & Reels', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A hook-first short-video script for a travel vlogger, with shots and on-screen text.', 'prompt' => 'Write a 30-second Reel/Short script for a travel vlogger about [TOPIC].
Format as a table: Time | Shot | Voice-over | On-screen text.
0–2 s: a hook that stops the scroll. 2–25 s: 3 quick value points or a mini story. 25–30 s: call to action [ACTION].
Also give: 3 alternative hooks, a caption and 5 hashtags.', 'output' => 'A timed shot-by-shot script with hook, value and call to action, plus alternative hooks, caption and hashtags.', 'image' => '' ),
	array( 'id' => '30-second-reel-script-for-a-skincare-brand', 'title' => '30-second Reel script for a skincare brand', 'cat' => 'Video & Reels', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A hook-first short-video script for a skincare brand, with shots and on-screen text.', 'prompt' => 'Write a 30-second Reel/Short script for a skincare brand about [TOPIC].
Format as a table: Time | Shot | Voice-over | On-screen text.
0–2 s: a hook that stops the scroll. 2–25 s: 3 quick value points or a mini story. 25–30 s: call to action [ACTION].
Also give: 3 alternative hooks, a caption and 5 hashtags.', 'output' => 'A timed shot-by-shot script with hook, value and call to action, plus alternative hooks, caption and hashtags.', 'image' => '' ),
	array( 'id' => '30-second-reel-script-for-a-real-estate-agent', 'title' => '30-second Reel script for a real estate agent', 'cat' => 'Video & Reels', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A hook-first short-video script for a real estate agent, with shots and on-screen text.', 'prompt' => 'Write a 30-second Reel/Short script for a real estate agent about [TOPIC].
Format as a table: Time | Shot | Voice-over | On-screen text.
0–2 s: a hook that stops the scroll. 2–25 s: 3 quick value points or a mini story. 25–30 s: call to action [ACTION].
Also give: 3 alternative hooks, a caption and 5 hashtags.', 'output' => 'A timed shot-by-shot script with hook, value and call to action, plus alternative hooks, caption and hashtags.', 'image' => '' ),
	array( 'id' => '30-second-reel-script-for-a-coding-teacher', 'title' => '30-second Reel script for a coding teacher', 'cat' => 'Video & Reels', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A hook-first short-video script for a coding teacher, with shots and on-screen text.', 'prompt' => 'Write a 30-second Reel/Short script for a coding teacher about [TOPIC].
Format as a table: Time | Shot | Voice-over | On-screen text.
0–2 s: a hook that stops the scroll. 2–25 s: 3 quick value points or a mini story. 25–30 s: call to action [ACTION].
Also give: 3 alternative hooks, a caption and 5 hashtags.', 'output' => 'A timed shot-by-shot script with hook, value and call to action, plus alternative hooks, caption and hashtags.', 'image' => '' ),
	array( 'id' => '30-second-reel-script-for-a-caf-owner', 'title' => '30-second Reel script for a café owner', 'cat' => 'Video & Reels', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A hook-first short-video script for a café owner, with shots and on-screen text.', 'prompt' => 'Write a 30-second Reel/Short script for a café owner about [TOPIC].
Format as a table: Time | Shot | Voice-over | On-screen text.
0–2 s: a hook that stops the scroll. 2–25 s: 3 quick value points or a mini story. 25–30 s: call to action [ACTION].
Also give: 3 alternative hooks, a caption and 5 hashtags.', 'output' => 'A timed shot-by-shot script with hook, value and call to action, plus alternative hooks, caption and hashtags.', 'image' => '' ),
	array( 'id' => '30-second-reel-script-for-a-fashion-boutique', 'title' => '30-second Reel script for a fashion boutique', 'cat' => 'Video & Reels', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A hook-first short-video script for a fashion boutique, with shots and on-screen text.', 'prompt' => 'Write a 30-second Reel/Short script for a fashion boutique about [TOPIC].
Format as a table: Time | Shot | Voice-over | On-screen text.
0–2 s: a hook that stops the scroll. 2–25 s: 3 quick value points or a mini story. 25–30 s: call to action [ACTION].
Also give: 3 alternative hooks, a caption and 5 hashtags.', 'output' => 'A timed shot-by-shot script with hook, value and call to action, plus alternative hooks, caption and hashtags.', 'image' => '' ),
	array( 'id' => '30-second-reel-script-for-a-productivity-creator', 'title' => '30-second Reel script for a productivity creator', 'cat' => 'Video & Reels', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A hook-first short-video script for a productivity creator, with shots and on-screen text.', 'prompt' => 'Write a 30-second Reel/Short script for a productivity creator about [TOPIC].
Format as a table: Time | Shot | Voice-over | On-screen text.
0–2 s: a hook that stops the scroll. 2–25 s: 3 quick value points or a mini story. 25–30 s: call to action [ACTION].
Also give: 3 alternative hooks, a caption and 5 hashtags.', 'output' => 'A timed shot-by-shot script with hook, value and call to action, plus alternative hooks, caption and hashtags.', 'image' => '' ),
	array( 'id' => '30-second-reel-script-for-a-language-tutor', 'title' => '30-second Reel script for a language tutor', 'cat' => 'Video & Reels', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A hook-first short-video script for a language tutor, with shots and on-screen text.', 'prompt' => 'Write a 30-second Reel/Short script for a language tutor about [TOPIC].
Format as a table: Time | Shot | Voice-over | On-screen text.
0–2 s: a hook that stops the scroll. 2–25 s: 3 quick value points or a mini story. 25–30 s: call to action [ACTION].
Also give: 3 alternative hooks, a caption and 5 hashtags.', 'output' => 'A timed shot-by-shot script with hook, value and call to action, plus alternative hooks, caption and hashtags.', 'image' => '' ),
	array( 'id' => '30-second-reel-script-for-a-photographer', 'title' => '30-second Reel script for a photographer', 'cat' => 'Video & Reels', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A hook-first short-video script for a photographer, with shots and on-screen text.', 'prompt' => 'Write a 30-second Reel/Short script for a photographer about [TOPIC].
Format as a table: Time | Shot | Voice-over | On-screen text.
0–2 s: a hook that stops the scroll. 2–25 s: 3 quick value points or a mini story. 25–30 s: call to action [ACTION].
Also give: 3 alternative hooks, a caption and 5 hashtags.', 'output' => 'A timed shot-by-shot script with hook, value and call to action, plus alternative hooks, caption and hashtags.', 'image' => '' ),
	array( 'id' => '30-second-reel-script-for-a-gamer', 'title' => '30-second Reel script for a gamer', 'cat' => 'Video & Reels', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A hook-first short-video script for a gamer, with shots and on-screen text.', 'prompt' => 'Write a 30-second Reel/Short script for a gamer about [TOPIC].
Format as a table: Time | Shot | Voice-over | On-screen text.
0–2 s: a hook that stops the scroll. 2–25 s: 3 quick value points or a mini story. 25–30 s: call to action [ACTION].
Also give: 3 alternative hooks, a caption and 5 hashtags.', 'output' => 'A timed shot-by-shot script with hook, value and call to action, plus alternative hooks, caption and hashtags.', 'image' => '' ),
	array( 'id' => '30-second-reel-script-for-a-parenting-creator', 'title' => '30-second Reel script for a parenting creator', 'cat' => 'Video & Reels', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A hook-first short-video script for a parenting creator, with shots and on-screen text.', 'prompt' => 'Write a 30-second Reel/Short script for a parenting creator about [TOPIC].
Format as a table: Time | Shot | Voice-over | On-screen text.
0–2 s: a hook that stops the scroll. 2–25 s: 3 quick value points or a mini story. 25–30 s: call to action [ACTION].
Also give: 3 alternative hooks, a caption and 5 hashtags.', 'output' => 'A timed shot-by-shot script with hook, value and call to action, plus alternative hooks, caption and hashtags.', 'image' => '' ),
	array( 'id' => '30-second-reel-script-for-a-small-business-coach', 'title' => '30-second Reel script for a small business coach', 'cat' => 'Video & Reels', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A hook-first short-video script for a small business coach, with shots and on-screen text.', 'prompt' => 'Write a 30-second Reel/Short script for a small business coach about [TOPIC].
Format as a table: Time | Shot | Voice-over | On-screen text.
0–2 s: a hook that stops the scroll. 2–25 s: 3 quick value points or a mini story. 25–30 s: call to action [ACTION].
Also give: 3 alternative hooks, a caption and 5 hashtags.', 'output' => 'A timed shot-by-shot script with hook, value and call to action, plus alternative hooks, caption and hashtags.', 'image' => '' ),
	array( 'id' => 'lo-fi-hip-hop-song-prompt', 'title' => 'Lo-fi hip hop song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => false, 'summary' => 'A style prompt and lyric outline to make a lo-fi hip hop track with an AI music tool.', 'prompt' => 'Style: mellow, rainy-day study vibe, 75 BPM, dusty drums, warm Rhodes piano, vinyl crackle.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original lo-fi hip hop track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'synthwave-song-prompt', 'title' => 'Synthwave song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => false, 'summary' => 'A style prompt and lyric outline to make a synthwave track with an AI music tool.', 'prompt' => 'Style: retro 80s, driving 100 BPM, analog synth arpeggios, gated reverb drums, neon night drive.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original synthwave track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'acoustic-folk-song-prompt', 'title' => 'Acoustic folk song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => true, 'summary' => 'A style prompt and lyric outline to make a acoustic folk track with an AI music tool.', 'prompt' => 'Style: warm fingerpicked acoustic guitar, soft harmonies, storytelling lyrics, 90 BPM.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original acoustic folk track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'edm-festival-anthem-song-prompt', 'title' => 'Edm festival anthem song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => false, 'summary' => 'A style prompt and lyric outline to make a EDM festival anthem track with an AI music tool.', 'prompt' => 'Style: euphoric big-room build, 128 BPM, supersaw lead, huge drop, hands-in-the-air energy.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original EDM festival anthem track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'bollywood-romantic-song-prompt', 'title' => 'Bollywood romantic song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => false, 'summary' => 'A style prompt and lyric outline to make a Bollywood romantic track with an AI music tool.', 'prompt' => 'Style: soft romantic melody, strings and flute, tabla groove, Hindi lyrics, 85 BPM.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original Bollywood romantic track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'punjabi-bhangra-pop-song-prompt', 'title' => 'Punjabi bhangra pop song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => true, 'summary' => 'A style prompt and lyric outline to make a punjabi bhangra pop track with an AI music tool.', 'prompt' => 'Style: energetic dhol rhythm, tumbi riff, celebratory, 100 BPM, Punjabi lyrics.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original punjabi bhangra pop track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'cinematic-orchestral-song-prompt', 'title' => 'Cinematic orchestral song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => false, 'summary' => 'A style prompt and lyric outline to make a cinematic orchestral track with an AI music tool.', 'prompt' => 'Style: epic trailer score, rising strings, brass hits, taiko drums, no vocals.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original cinematic orchestral track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'jazz-cafe-song-prompt', 'title' => 'Jazz cafe song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => false, 'summary' => 'A style prompt and lyric outline to make a jazz cafe track with an AI music tool.', 'prompt' => 'Style: smooth jazz trio, brushed drums, upright bass, mellow piano, 95 BPM, relaxed.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original jazz cafe track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'indie-pop-song-prompt', 'title' => 'Indie pop song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => true, 'summary' => 'A style prompt and lyric outline to make a indie pop track with an AI music tool.', 'prompt' => 'Style: bright jangly guitars, catchy chorus, upbeat 115 BPM, youthful and hopeful.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original indie pop track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'trap-song-prompt', 'title' => 'Trap song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => false, 'summary' => 'A style prompt and lyric outline to make a trap track with an AI music tool.', 'prompt' => 'Style: dark 140 BPM half-time, rolling hi-hats, heavy 808 bass, confident vocals.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original trap track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'classical-piano-song-prompt', 'title' => 'Classical piano song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => false, 'summary' => 'A style prompt and lyric outline to make a classical piano track with an AI music tool.', 'prompt' => 'Style: solo piano, gentle and reflective, rubato, impressionistic harmony.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original classical piano track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'reggae-song-prompt', 'title' => 'Reggae song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => false, 'summary' => 'A style prompt and lyric outline to make a reggae track with an AI music tool.', 'prompt' => 'Style: laid-back one-drop groove, offbeat guitar skank, warm bass, sunny vibe.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original reggae track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'rock-anthem-song-prompt', 'title' => 'Rock anthem song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => true, 'summary' => 'A style prompt and lyric outline to make a rock anthem track with an AI music tool.', 'prompt' => 'Style: big distorted guitars, stadium drums, powerful chorus, 120 BPM.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original rock anthem track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'ambient-meditation-song-prompt', 'title' => 'Ambient meditation song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => false, 'summary' => 'A style prompt and lyric outline to make a ambient meditation track with an AI music tool.', 'prompt' => 'Style: slow evolving pads, soft drones, no drums, calming, 60 BPM feel.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original ambient meditation track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'kids-song-song-prompt', 'title' => 'Kids song song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => false, 'summary' => 'A style prompt and lyric outline to make a kids song track with an AI music tool.', 'prompt' => 'Style: playful and simple melody, ukulele and claps, easy sing-along lyrics about [THEME].
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original kids song track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'sufi-qawwali-inspired-song-prompt', 'title' => 'Sufi qawwali inspired song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => true, 'summary' => 'A style prompt and lyric outline to make a sufi qawwali inspired track with an AI music tool.', 'prompt' => 'Style: devotional, harmonium and tabla, hand claps, building intensity.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original sufi qawwali inspired track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'k-pop-dance-song-prompt', 'title' => 'K-pop dance song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => false, 'summary' => 'A style prompt and lyric outline to make a k-pop dance track with an AI music tool.', 'prompt' => 'Style: polished dance-pop, 120 BPM, punchy synths, catchy hook, rap verse.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original k-pop dance track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'country-song-prompt', 'title' => 'Country song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => false, 'summary' => 'A style prompt and lyric outline to make a country track with an AI music tool.', 'prompt' => 'Style: twangy electric guitar, steel guitar, heartfelt storytelling, 100 BPM.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original country track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'afrobeats-song-prompt', 'title' => 'Afrobeats song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => true, 'summary' => 'A style prompt and lyric outline to make a afrobeats track with an AI music tool.', 'prompt' => 'Style: groovy syncopated percussion, smooth vocals, 105 BPM, summery.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original afrobeats track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'podcast-intro-jingle-song-prompt', 'title' => 'Podcast intro jingle song prompt', 'cat' => 'Music & Audio', 'tool' => 'Suno', 'kind' => 'audio', 'premium' => false, 'summary' => 'A style prompt and lyric outline to make a podcast intro jingle track with an AI music tool.', 'prompt' => 'Style: short 10-second upbeat jingle, bright synths, confident, instrumental.
Song about: [TOPIC / STORY].
Structure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].
Lyrics: write original lyrics in [LANGUAGE] with a memorable 2-line chorus hook; keep lines short and singable.
Avoid naming real artists; describe the sound instead.', 'output' => 'An original podcast intro jingle track with structure tags the AI follows; regenerate a few times and keep the best take. Check the tool\'s licence before commercial use.', 'image' => '' ),
	array( 'id' => 'podcast-episode-outline', 'title' => 'Podcast episode outline', 'cat' => 'Music & Audio', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A structured episode plan with segments and questions.', 'prompt' => 'Plan a [LENGTH]-minute podcast episode about [TOPIC] for [AUDIENCE]. Guest: [GUEST OR SOLO].
Give: a title, cold-open hook, intro script, 4 segments with timings and key questions, a listener takeaway, outro with call to action, and show notes with timestamps.', 'output' => 'A timed episode plan with hook, segment questions, outro and ready show notes.', 'image' => '' ),
	array( 'id' => 'voice-over-script-with-direction', 'title' => 'Voice-over script with direction', 'cat' => 'Music & Audio', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A voice-over script marked for pace, emphasis and pauses.', 'prompt' => 'Write a [SECONDS]-second voice-over for [VIDEO / AD] about [TOPIC]. Voice: [WARM / ENERGETIC / CALM].
Mark *emphasis*, [pause] and pace notes. About 2.5 words per second. Give 2 versions.', 'output' => 'Two timed voice-over scripts with emphasis and pause marks, ready for a narrator or an AI voice.', 'image' => '' ),
	array( 'id' => 'song-lyrics-with-a-hook', 'title' => 'Song lyrics with a hook', 'cat' => 'Music & Audio', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Original lyrics with verse, chorus and bridge.', 'prompt' => 'Write original song lyrics about [THEME] in [LANGUAGE]. Mood: [MOOD]. Perspective: [FIRST / SECOND PERSON].
Structure: verse, pre-chorus, chorus, verse, chorus, bridge, final chorus. Use concrete images, a repeated hook line and a consistent rhyme scheme. Label each section.', 'output' => 'Original labelled lyrics with a strong repeated hook and consistent rhyme.', 'image' => '' ),
	array( 'id' => 'clean-up-a-transcript', 'title' => 'Clean up a transcript', 'cat' => 'Music & Audio', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn a raw audio transcript into readable text.', 'prompt' => 'Clean up this transcript: remove filler words (um, like, you know), fix punctuation, split into paragraphs by topic, label speakers as [NAMES], and keep the meaning and quotes accurate. Add timestamps every few minutes if present.
"""
[PASTE TRANSCRIPT]
"""', 'output' => 'A readable, speaker-labelled transcript with filler removed and meaning preserved.', 'image' => '' ),
	array( 'id' => 'sound-design-brief', 'title' => 'Sound design brief', 'cat' => 'Music & Audio', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Describe sound effects and music for a video or game scene.', 'prompt' => 'Write a sound design brief for this scene: [DESCRIBE SCENE AND DURATION].
List: ambience layers, key sound effects with timing, music mood and instruments, dynamics (where it gets loud or quiet), and mixing notes. Format as a timeline table.', 'output' => 'A timeline of ambience, effects and music cues with mixing notes.', 'image' => '' ),
	array( 'id' => 'radio-or-audio-ad', 'title' => 'Radio or audio ad', 'cat' => 'Music & Audio', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A 30-second audio ad with sound cues.', 'prompt' => 'Write a 30-second audio ad for [BUSINESS] promoting [OFFER].
Include sound effect and music cues in [brackets], one voice or a 2-voice dialogue, the offer repeated twice, and a clear call to action with [PHONE / WEBSITE] said slowly. About 75 spoken words.', 'output' => 'A timed audio ad script with sound cues, a repeated offer and an easy-to-remember call to action.', 'image' => '' ),
	array( 'id' => 'meditation-script', 'title' => 'Meditation script', 'cat' => 'Music & Audio', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A calm guided meditation for an audio recording.', 'prompt' => 'Write a [MINUTES]-minute guided meditation for [GOAL: sleep / stress / focus].
Include: settling in, breath guidance with counts, body scan, a short visualisation of [PLACE], and a gentle return. Mark pauses like (pause 10 s). Soft, simple language.', 'output' => 'A calming script with timed pauses, breathing counts and a gentle ending, ready to record.', 'image' => '' ),
	array( 'id' => 'audiobook-chapter-narration-notes', 'title' => 'Audiobook chapter narration notes', 'cat' => 'Music & Audio', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Prepare a chapter for narration: voices, pace and tricky words.', 'prompt' => 'Prepare narration notes for this chapter: [PASTE TEXT].
Give: character voice notes (age, accent, energy), pace changes by scene, words with pronunciation guides, emotional beats, and suggested pause points.', 'output' => 'Narration notes covering character voices, pacing, pronunciation and emotional beats.', 'image' => '' ),
	array( 'id' => 'youtube-video-music-cues', 'title' => 'YouTube video music cues', 'cat' => 'Music & Audio', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Pick music moods for each part of a video.', 'prompt' => 'Here is my video outline: [PASTE OUTLINE WITH TIMESTAMPS].
Suggest a music mood, tempo and instrumentation for each section, where to drop the music for emphasis, and search keywords for royalty-free libraries.', 'output' => 'Section-by-section music cues with tempo, instrumentation and search keywords for royalty-free tracks.', 'image' => '' ),
	array( 'id' => 'jingle-lyrics-for-a-brand', 'title' => 'Jingle lyrics for a brand', 'cat' => 'Music & Audio', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A catchy 15-second brand jingle.', 'prompt' => 'Write lyrics for a 15-second jingle for [BRAND] that [WHAT IT DOES]. Include the brand name twice, a simple melody suggestion (rising, playful), and 3 versions: playful, premium, energetic. Max 25 words each.', 'output' => 'Three short jingle versions that repeat the brand name and suit different moods.', 'image' => '' ),
	array( 'id' => 'write-an-excel-or-sheets-formula', 'title' => 'Write an Excel or Sheets formula', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Describe what you need; get the formula and how it works.', 'prompt' => 'I use [EXCEL 365 / GOOGLE SHEETS]. My data: [DESCRIBE COLUMNS, e.g. A=Date, B=Region, C=Sales] in rows [RANGE].
I need a formula that: [WHAT YOU WANT].
Give the formula, explain each part, and give an alternative for older Excel versions if the main one uses newer functions (like XLOOKUP or FILTER).', 'output' => 'A working formula with a part-by-part explanation and a fallback for older versions where needed.', 'image' => '' ),
	array( 'id' => 'xlookup-vs-vlookup-explained-with-your-data', 'title' => 'XLOOKUP vs VLOOKUP explained with your data', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Look up values across sheets the modern way.', 'prompt' => 'Explain how to look up [VALUE] from sheet [SHEET2] into [SHEET1] using XLOOKUP, then the same with VLOOKUP and INDEX/MATCH.
My columns: [DESCRIBE]. Show each formula, when to use which, and how to handle not-found values.', 'output' => 'Three lookup formulas for your exact columns, with not-found handling and when to use each.', 'image' => '' ),
	array( 'id' => 'build-a-pivot-table-summary', 'title' => 'Build a pivot table summary', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Step-by-step pivot table for a quick report.', 'prompt' => 'I have sales data with columns: [LIST]. I want to see [QUESTION, e.g. monthly sales by region and product].
Give step-by-step pivot table instructions for [EXCEL / SHEETS]: rows, columns, values, filters, grouping dates by month, and how to show % of total. Then suggest one chart to add.', 'output' => 'Click-by-click pivot instructions for your question, including date grouping and percentage views.', 'image' => '' ),
	array( 'id' => 'clean-messy-data', 'title' => 'Clean messy data', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A cleaning plan and formulas for duplicates, spaces and formats.', 'prompt' => 'My dataset has these problems: [DUPLICATES / EXTRA SPACES / MIXED DATE FORMATS / TYPOS IN CATEGORIES / BLANKS].
Columns: [LIST]. Tool: [EXCEL / SHEETS / PYTHON PANDAS].
Give a step-by-step cleaning plan with the exact formulas or code for each step, and how to check the result.', 'output' => 'An ordered cleaning plan with exact formulas or pandas code and verification checks.', 'image' => '' ),
	array( 'id' => 'sql-query-from-plain-english', 'title' => 'SQL query from plain English', 'cat' => 'Data & Excel', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Describe the question; get a correct SQL query.', 'prompt' => 'Write a SQL query for [POSTGRESQL / MYSQL / SQL SERVER / SQLITE].
Tables:
[TABLE NAME](columns and types)
[TABLE NAME](columns and types)
Question: [WHAT YOU WANT TO KNOW].
Explain the query step by step, mention indexes that would help, and show the expected output columns.', 'output' => 'A dialect-correct query with an explanation, index suggestions and the expected output shape.', 'image' => '' ),
	array( 'id' => 'explain-a-sql-query', 'title' => 'Explain a SQL query', 'cat' => 'Data & Excel', 'tool' => 'Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'Understand what a long query does, clause by clause.', 'prompt' => 'Explain this SQL query in plain English, clause by clause, in the order the database actually runs it (FROM, WHERE, GROUP BY, HAVING, SELECT, ORDER BY). Point out performance risks and possible bugs (e.g. NULL handling, duplicate rows from joins).
```sql
[PASTE QUERY]
```', 'output' => 'A clause-by-clause explanation in execution order, with performance and correctness warnings.', 'image' => '' ),
	array( 'id' => 'sql-window-functions-tutor', 'title' => 'SQL window functions tutor', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Learn ROW_NUMBER, RANK, LAG and running totals with your tables.', 'prompt' => 'Teach me SQL window functions using my table: [TABLE](columns).
Show examples of ROW_NUMBER, RANK vs DENSE_RANK, LAG/LEAD, running totals with SUM() OVER, and moving averages. For each: the query, a small sample output and a real business question it answers.', 'output' => 'Practical window-function examples on your own table with sample outputs and business uses.', 'image' => '' ),
	array( 'id' => 'analyse-a-dataset-with-pandas', 'title' => 'Analyse a dataset with pandas', 'cat' => 'Data & Excel', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Exploratory analysis code with explanations.', 'prompt' => 'Write Python pandas code to explore this dataset: [FILE NAME, COLUMNS AND TYPES].
Steps: load, shape and types, missing values, summary statistics, value counts for categories, 3 useful group-bys for [GOAL], and 3 charts with matplotlib. Comment every step and tell me what to look for in each output.', 'output' => 'A commented exploratory-analysis script with grouped summaries, charts and notes on what to look for.', 'image' => '' ),
	array( 'id' => 'choose-the-right-chart', 'title' => 'Choose the right chart', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Pick a chart that answers your question clearly.', 'prompt' => 'I want to show [MESSAGE] to [AUDIENCE] using this data: [DESCRIBE COLUMNS AND SIZE].
Recommend the best chart type and 2 alternatives, explain why, and give design tips (sorting, labels, colours, what to remove). Then give the steps to build it in [EXCEL / SHEETS / POWER BI / PYTHON].', 'output' => 'A recommended chart with alternatives, clear reasoning, design tips and build steps for your tool.', 'image' => '' ),
	array( 'id' => 'power-bi-dax-measure', 'title' => 'Power BI DAX measure', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Write and understand DAX measures.', 'prompt' => 'Write a DAX measure for Power BI.
Tables and relationships: [DESCRIBE]. I need: [e.g. year-over-year sales growth %, running total, sales for the same period last year].
Give the measure, explain each function (CALCULATE, filters, time intelligence), and tell me what format to apply.', 'output' => 'A working DAX measure with a function-by-function explanation and formatting tip.', 'image' => '' ),
	array( 'id' => 'interpret-statistical-results', 'title' => 'Interpret statistical results', 'cat' => 'Data & Excel', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Understand p-values, confidence intervals and what to conclude.', 'prompt' => 'Explain these results in plain English: [PASTE OUTPUT, e.g. t-test, regression table].
Context: [WHAT WAS TESTED]. Tell me: what the numbers mean, whether the result is statistically significant at 0.05, whether it is practically meaningful, the assumptions to check, and what I should NOT conclude.', 'output' => 'A plain-English reading of the output covering significance, practical meaning, assumptions and common misinterpretations.', 'image' => '' ),
	array( 'id' => 'a-b-test-result-check', 'title' => 'A/B test result check', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Find out if your test winner is real or noise.', 'prompt' => 'My A/B test: version A [VISITORS] visitors, [CONVERSIONS] conversions; version B [VISITORS] visitors, [CONVERSIONS] conversions.
Calculate conversion rates, relative lift, and test significance (two-proportion z-test) with the formula shown. Tell me if I have enough data and what sample size I would need to detect a [X]% lift.', 'output' => 'Rates, lift, a shown-work significance test and a sample-size estimate, with a clear ship or wait recommendation.', 'image' => '' ),
	array( 'id' => 'monthly-report-narrative', 'title' => 'Monthly report narrative', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Turn numbers into a short management summary.', 'prompt' => 'Here are this month\'s numbers vs last month and target: [PASTE TABLE].
Write a management summary: headline in one sentence, 3 wins, 3 concerns with likely causes, and 3 recommended actions. Use exact numbers; do not invent causes I have not mentioned — label guesses as hypotheses.', 'output' => 'A crisp summary with wins, concerns, hypotheses and actions, grounded in your numbers.', 'image' => '' ),
	array( 'id' => 'design-a-database-schema', 'title' => 'Design a database schema', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Tables, keys and relationships for your app.', 'prompt' => 'Design a relational database schema for [APP, e.g. a library management system].
Features: [LIST].
Give: tables with columns, types, primary and foreign keys, indexes, an ER description, sample CREATE TABLE statements for [POSTGRESQL / MYSQL], and 3 example queries.', 'output' => 'A normalised schema with keys and indexes, CREATE TABLE statements and example queries.', 'image' => '' ),
	array( 'id' => 'excel-dashboard-plan', 'title' => 'Excel dashboard plan', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Plan a one-page interactive dashboard.', 'prompt' => 'Plan a one-page Excel dashboard for [PURPOSE] from data with columns [LIST].
Include: 4–6 KPIs with formulas, 3 charts, slicers or drop-down filters, layout sketch (described in a grid), colour rules, and the steps to build it with pivot tables.', 'output' => 'A dashboard blueprint with KPI formulas, chart picks, filters, a layout grid and build steps.', 'image' => '' ),
	array( 'id' => 'regex-for-data-extraction', 'title' => 'Regex for data extraction', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'Write and understand a regular expression.', 'prompt' => 'Write a regular expression for [LANGUAGE / TOOL] that matches [WHAT, e.g. Indian mobile numbers, emails, dates in DD/MM/YYYY].
Give: the regex, a part-by-part explanation, 5 strings that should match and 5 that should not, and a code example to extract all matches.', 'output' => 'A tested regex with an explanation, match and non-match examples, and extraction code.', 'image' => '' ),
	array( 'id' => 'forecast-with-simple-methods', 'title' => 'Forecast with simple methods', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A basic sales forecast you can explain to anyone.', 'prompt' => 'Here is my monthly data for [METRIC]: [PASTE 12–36 MONTHS].
Create a forecast for the next [N] months using: moving average, linear trend and seasonal naive. Show the Excel formulas (e.g. FORECAST.LINEAR / FORECAST.ETS), compare them, and say which is most reasonable and why. Mention the uncertainty.', 'output' => 'Three simple forecasts with formulas, a comparison and an honest note on uncertainty.', 'image' => '' ),
	array( 'id' => 'google-sheets-automation-with-apps-script', 'title' => 'Google Sheets automation with Apps Script', 'cat' => 'Data & Excel', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Automate a repetitive Sheets task.', 'prompt' => 'Write a Google Apps Script for Google Sheets that [TASK, e.g. emails me a summary every Monday / moves rows marked Done to an Archive sheet].
Sheet names and columns: [DESCRIBE]. Include comments, how to install it, how to set a time-driven trigger, and the permissions it will ask for.', 'output' => 'A commented Apps Script with install and trigger steps and a note on permissions.', 'image' => '' ),
	array( 'id' => 'data-dictionary', 'title' => 'Data dictionary', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Document every column of a dataset.', 'prompt' => 'Create a data dictionary for this dataset: [PASTE HEADERS AND 5 SAMPLE ROWS].
For each column: name, description, data type, allowed values or range, example, whether it can be blank, and quality issues seen in the sample.', 'output' => 'A complete data dictionary table with types, ranges, examples and spotted quality issues.', 'image' => '' ),
	array( 'id' => 'kpi-definition-sheet', 'title' => 'KPI definition sheet', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Define metrics so everyone calculates them the same way.', 'prompt' => 'Define these KPIs for my team: [LIST, e.g. churn rate, CAC, LTV, conversion rate].
For each: plain definition, exact formula, data source, time window, an example calculation with numbers, and common mistakes in calculating it.', 'output' => 'One consistent definition sheet with formulas, worked examples and pitfalls for every KPI.', 'image' => '' ),
	array( 'id' => 'survey-data-analysis-plan', 'title' => 'Survey data analysis plan', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Analyse survey results the right way.', 'prompt' => 'I ran a survey with [N] responses. Questions: [LIST WITH TYPES: scale, multiple choice, open text].
Give an analysis plan: cleaning, summary stats per question type, cross-tabs worth doing for [GOAL], how to code open-text answers into themes, charts to use, and limits of the data (sample bias).', 'output' => 'A step-by-step survey analysis plan, including cross-tabs, open-text coding and honest limitations.', 'image' => '' ),
	array( 'id' => 'conditional-formatting-rules', 'title' => 'Conditional formatting rules', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Highlight what matters in a sheet automatically.', 'prompt' => 'In [EXCEL / SHEETS], my sheet has columns: [LIST]. I want to highlight: [e.g. overdue dates in red, top 10% sales in green, duplicates in yellow, whole row when status = Done].
Give the exact custom formula for each rule and the steps to apply it.', 'output' => 'Exact custom formulas and steps for each highlighting rule, including whole-row formatting.', 'image' => '' ),
	array( 'id' => 'explain-a-machine-learning-model-result', 'title' => 'Explain a machine learning model result', 'cat' => 'Data & Excel', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'Understand accuracy, precision, recall and what to improve.', 'prompt' => 'My model results: [PASTE CONFUSION MATRIX / METRICS]. Task: [WHAT IT PREDICTS]. Class balance: [RATIO].
Explain accuracy, precision, recall, F1 and (if given) ROC-AUC in this context, which metric matters most for my use case and why, and 5 ideas to improve the model.', 'output' => 'Metrics explained in your context, the one that matters most for your goal, and practical improvement ideas.', 'image' => '' ),
	array( 'id' => 'convert-data-between-formats', 'title' => 'Convert data between formats', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Turn CSV, JSON and tables into each other with code.', 'prompt' => 'Convert this data from [CSV / JSON / TABLE] to [JSON / CSV / SQL INSERTS / MARKDOWN TABLE]:
[PASTE SAMPLE]
If it is large, give me a Python script instead that reads [INPUT FILE] and writes [OUTPUT FILE], handling nested fields and special characters.', 'output' => 'Converted data for small samples, or a robust conversion script for big files.', 'image' => '' ),
	array( 'id' => 'excel-vba-macro', 'title' => 'Excel VBA macro', 'cat' => 'Data & Excel', 'tool' => 'ChatGPT', 'kind' => 'code', 'premium' => false, 'summary' => 'Automate a repetitive Excel task with a macro.', 'prompt' => 'Write an Excel VBA macro that [TASK, e.g. splits each region\'s rows into its own sheet / formats a raw export].
Workbook details: [SHEET NAMES, COLUMNS]. Add comments, error handling, and steps to add it in the VBA editor and assign it to a button. Warn me what it changes so I can back up first.', 'output' => 'A commented, error-handled macro with install steps and a clear warning about what it modifies.', 'image' => '' ),
	array( 'id' => 'spot-anomalies-in-numbers', 'title' => 'Spot anomalies in numbers', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Find unusual values and what might explain them.', 'prompt' => 'Here is a table of [METRIC] by [DATE / CATEGORY]: [PASTE].
Find anomalies using simple methods (z-score or IQR), list them with how unusual they are, suggest possible explanations to check (not conclusions), and give the formula so I can repeat this monthly.', 'output' => 'A list of flagged anomalies with severity, things to check, and a repeatable formula.', 'image' => '' ),
	array( 'id' => 'cohort-retention-analysis', 'title' => 'Cohort retention analysis', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Build a retention table to see if customers stick.', 'prompt' => 'I have orders data with columns: customer_id, order_date, amount.
Explain how to build a monthly cohort retention table in [EXCEL / SQL / PANDAS] step by step, with the code or formulas, how to read the triangle, and 3 insights to look for.', 'output' => 'Step-by-step cohort analysis with code or formulas and guidance on reading the retention triangle.', 'image' => '' ),
	array( 'id' => 'create-sample-test-data', 'title' => 'Create sample test data', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Realistic fake data for testing an app or a spreadsheet.', 'prompt' => 'Generate [N] rows of realistic sample data for testing with columns: [LIST WITH TYPES AND RULES].
Follow rules like [e.g. dates in 2025, amounts 100–5000, 10% missing emails]. Output as CSV. Use clearly fake names and contact details (example.com emails), never real people.', 'output' => 'A CSV of realistic but clearly fake rows that follows your column rules.', 'image' => '' ),
	array( 'id' => 'explain-a-dataset-to-a-non-technical-audience', 'title' => 'Explain a dataset to a non-technical audience', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Turn analysis into a story people remember.', 'prompt' => 'I analysed [DATA] and found: [KEY FINDINGS WITH NUMBERS].
Audience: [WHO]. Turn this into a 5-slide data story: the question, the context, 3 insights (one per slide with the chart to use), and the recommendation. Keep each slide to a headline and 2 bullets.', 'output' => 'A five-slide data story with headline-style insights, chart suggestions and a clear recommendation.', 'image' => '' ),
	array( 'id' => 'sheets-query-function-helper', 'title' => 'Sheets QUERY function helper', 'cat' => 'Data & Excel', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Use SQL-like queries inside Google Sheets.', 'prompt' => 'In Google Sheets, my data is in [RANGE] with headers [LIST]. Write a QUERY formula that [WHAT YOU NEED, e.g. totals by month for region = North sorted descending].
Explain the clauses (select, where, group by, pivot, order by, label) and common errors like mixed data types in a column.', 'output' => 'A working QUERY formula with a clause-by-clause explanation and tips for common errors.', 'image' => '' ),
	array( 'id' => 'plan-my-week-with-time-blocks', 'title' => 'Plan my week with time blocks', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A realistic weekly schedule built around your priorities and energy.', 'prompt' => 'Plan my week. Priorities (in order): [LIST]. Fixed commitments: [MEETINGS, CLASSES, COMMUTE]. Best focus hours: [TIME]. Work hours: [START–END].
Create a time-blocked weekly table with deep-work blocks, admin blocks, breaks and buffer time. Keep 20% of time unscheduled. Then list the 3 must-win tasks for the week.', 'output' => 'A time-blocked week with deep work in your best hours, buffers and three must-win tasks.', 'image' => '' ),
	array( 'id' => 'break-a-big-project-into-tasks', 'title' => 'Break a big project into tasks', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn an overwhelming goal into small next actions.', 'prompt' => 'Break this project into tasks: [PROJECT] due [DATE].
Give: milestones, tasks under each (each under 2 hours, starting with a verb), dependencies, an estimated time per task, and the very first 15-minute action I can do today.', 'output' => 'A milestone and task breakdown with estimates, dependencies and an easy first action.', 'image' => '' ),
	array( 'id' => 'eisenhower-matrix-sort', 'title' => 'Eisenhower matrix sort', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Sort your to-do list by urgent vs important.', 'prompt' => 'Sort my to-do list into an Eisenhower matrix (Do now, Schedule, Delegate, Delete): [PASTE LIST].
My role and goals: [CONTEXT]. For each task give one line of reasoning, then a suggested order for today.', 'output' => 'A four-quadrant sorted list with short reasons and a practical order for today.', 'image' => '' ),
	array( 'id' => 'daily-review-and-shutdown-routine', 'title' => 'Daily review and shutdown routine', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'End the day with a short review that clears your head.', 'prompt' => 'Create a 10-minute end-of-day shutdown routine for [ROLE]. Include: review of what got done, capturing loose ends, planning tomorrow\'s top 3, inbox check, and a closing ritual. Then make it a printable checklist and 5 reflection questions I can rotate.', 'output' => 'A 10-minute shutdown checklist and rotating reflection questions.', 'image' => '' ),
	array( 'id' => 'write-smart-goals', 'title' => 'Write SMART goals', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn vague goals into specific, measurable ones.', 'prompt' => 'Turn these goals into SMART goals (specific, measurable, achievable, relevant, time-bound): [LIST VAGUE GOALS].
For each: the SMART version, 3 milestones, the first action, and a weekly metric to track.', 'output' => 'SMART versions of each goal with milestones, a first step and a weekly tracking metric.', 'image' => '' ),
	array( 'id' => 'habit-tracker-plan', 'title' => 'Habit tracker plan', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Build one habit at a time with tiny steps.', 'prompt' => 'Help me build the habit: [HABIT]. My current routine: [DESCRIBE]. Obstacles: [LIST].
Use habit-stacking and a tiny starting version (2 minutes). Give: cue, routine, reward, a plan for missed days, a 30-day tracker layout, and how to scale it up each week.', 'output' => 'A tiny-habit plan with cue, reward, missed-day rules and a 30-day tracker.', 'image' => '' ),
	array( 'id' => 'inbox-zero-email-triage', 'title' => 'Inbox zero email triage', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Process a flooded inbox quickly with rules.', 'prompt' => 'I have [N] unread emails. Help me design a triage system: categories, rules or filters to create in [GMAIL / OUTLOOK], templates for common replies, a daily 2-pass routine, and what to unsubscribe from. Then draft 5 quick reply templates for: [COMMON EMAIL TYPES].', 'output' => 'A triage system with filters, a daily routine and reusable reply templates.', 'image' => '' ),
	array( 'id' => 'meeting-agenda', 'title' => 'Meeting agenda', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A focused agenda that ends on time with decisions.', 'prompt' => 'Create an agenda for a [LENGTH]-minute meeting about [TOPIC] with [ATTENDEES].
Goal: [DECISION / UPDATE / BRAINSTORM]. Include: purpose, pre-read, timed items with owners, decisions needed, and a final 5 minutes for actions. Also suggest whether this could be an email instead.', 'output' => 'A timed agenda with owners and decisions, and an honest check on whether you need the meeting.', 'image' => '' ),
	array( 'id' => 'focus-session-plan-pomodoro', 'title' => 'Focus session plan (Pomodoro)', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Plan a focused work session with breaks.', 'prompt' => 'I have [HOURS] hours to work on [TASK]. Plan Pomodoro sessions (25 min work / 5 min break, longer break every 4).
For each session: the specific sub-task and a done-criteria. Add distraction rules and a 1-line review after each session.', 'output' => 'A session-by-session plan with clear sub-tasks, breaks and quick reviews.', 'image' => '' ),
	array( 'id' => 'decision-making-helper', 'title' => 'Decision-making helper', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Weigh options with a simple scoring matrix.', 'prompt' => 'Help me decide: [DECISION]. Options: [LIST].
Ask me 3 clarifying questions first. Then build a weighted decision matrix (criteria, weights, scores 1–5), show totals, run a quick pre-mortem on the top option, and give a recommendation with what would change it.', 'output' => 'Clarifying questions, a weighted matrix, a pre-mortem and a reasoned recommendation.', 'image' => '' ),
	array( 'id' => 'personal-sop-for-a-repeated-task', 'title' => 'Personal SOP for a repeated task', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Document a recurring task so it takes half the time.', 'prompt' => 'Write a standard operating procedure for a task I repeat: [TASK]. Current steps: [DESCRIBE].
Give: purpose, when to do it, tools, numbered steps, checklist, common mistakes and a template. Suggest 2 steps that could be automated.', 'output' => 'A clear SOP with steps, checklist, template and automation ideas.', 'image' => '' ),
	array( 'id' => 'learn-any-skill-in-30-days', 'title' => 'Learn any skill in 30 days', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A practice-first learning plan for a new skill.', 'prompt' => 'Make a 30-day plan to learn [SKILL] to the level of [GOAL]. Time: [MINUTES] per day.
Use the 80/20 rule: the 20% of sub-skills that give 80% of results first. Weekly milestones, daily practice tasks, free resources (only well-known, reliable ones), and a mini project at the end.', 'output' => 'A practice-first 30-day plan with weekly milestones and a final mini project.', 'image' => '' ),
	array( 'id' => 'reduce-procrastination', 'title' => 'Reduce procrastination', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Find why you are stuck and a tiny way forward.', 'prompt' => 'I keep putting off [TASK]. Ask me 4 short questions to find the real reason (unclear next step, fear, boredom, too big). Then give 3 tailored tactics and a 5-minute starter action.', 'output' => 'Short diagnostic questions, tailored tactics and a tiny starter action.', 'image' => '' ),
	array( 'id' => 'quarterly-personal-review', 'title' => 'Quarterly personal review', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Reflect on the last 3 months and plan the next.', 'prompt' => 'Guide me through a quarterly review. Ask me questions one section at a time: wins, misses, energy givers and drainers, lessons, and what to stop/start/continue. Then summarise and help me set 3 goals for next quarter.', 'output' => 'An interactive review that ends in a summary and three goals for the next quarter.', 'image' => '' ),
	array( 'id' => 'note-taking-system-setup', 'title' => 'Note-taking system setup', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Organise notes so you can find anything later.', 'prompt' => 'Design a simple note-taking system in [NOTION / OBSIDIAN / GOOGLE DOCS / APPLE NOTES] for [STUDENT / PROFESSIONAL / CREATOR].
Include: folder or tag structure, note templates (meeting, idea, learning, project), naming rules, a weekly review routine and how to find notes fast.', 'output' => 'A folder or tag structure, four templates, naming rules and a weekly review routine.', 'image' => '' ),
	array( 'id' => 'turn-notes-into-an-action-plan', 'title' => 'Turn notes into an action plan', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Extract tasks, owners and deadlines from messy notes.', 'prompt' => 'Extract action items from these notes: [PASTE].
Table: action | owner | deadline | priority | status. Flag unclear items as questions. Then list decisions made and open issues.', 'output' => 'A clean action table with owners and deadlines, plus decisions and open questions.', 'image' => '' ),
	array( 'id' => 'delegation-brief', 'title' => 'Delegation brief', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Hand off a task clearly so it gets done right.', 'prompt' => 'Write a delegation brief for [TASK] to [PERSON / ROLE].
Include: the outcome and why it matters, what done looks like, constraints, resources, deadline and check-in points, decisions they can make alone, and when to escalate.', 'output' => 'A complete hand-off brief that defines done, decision rights and check-ins.', 'image' => '' ),
	array( 'id' => 'morning-routine-design', 'title' => 'Morning routine design', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A realistic morning routine for focus and energy.', 'prompt' => 'Design a morning routine for me. Wake time: [TIME]. Must leave or start work by: [TIME]. Goals: [ENERGY / FOCUS / FITNESS / CALM].
Give a minute-by-minute routine, a 15-minute version for busy days, and tips to make it stick.', 'output' => 'A timed routine, a short busy-day version and tips to keep it going.', 'image' => '' ),
	array( 'id' => 'workload-and-boundaries-message', 'title' => 'Workload and boundaries message', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Say no or renegotiate deadlines politely.', 'prompt' => 'Write a message to [MANAGER / CLIENT] to [DECLINE / PUSH BACK ON / RENEGOTIATE] [REQUEST]. My current workload: [LIST].
Offer options (what I can do instead, new timeline, trade-offs). Professional, calm, solution-focused, under 120 words.', 'output' => 'A calm, solution-focused message that sets a boundary and offers options.', 'image' => '' ),
	array( 'id' => 'simplify-a-process', 'title' => 'Simplify a process', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Find waste and shortcuts in a workflow.', 'prompt' => 'Here is my current process for [PROCESS]: [STEPS].
Identify steps that are waste, duplicates or bottlenecks. Suggest a simpler version, what to automate with common tools, and estimate the time saved per week.', 'output' => 'A leaner process, automation ideas and an estimate of time saved.', 'image' => '' ),
	array( 'id' => 'exam-week-schedule', 'title' => 'Exam week schedule', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A day-by-day plan for a busy exam period.', 'prompt' => 'I have these exams: [SUBJECT + DATE LIST]. Topics left per subject: [LIST]. Hours per day: [X].
Make a day-by-day schedule that prioritises the earliest and weakest subjects, includes past-paper practice, sleep of 7+ hours and short breaks.', 'output' => 'A prioritised daily schedule with practice papers, breaks and healthy sleep.', 'image' => '' ),
	array( 'id' => 'remote-work-routine', 'title' => 'Remote work routine', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Structure your work-from-home day.', 'prompt' => 'Design a remote work routine for [ROLE] working from home with [CONTEXT: kids, shared space, time zones].
Include: start and end rituals, focus blocks, communication windows, break ideas, and a workspace checklist.', 'output' => 'A WFH routine with rituals, focus blocks, communication windows and a workspace checklist.', 'image' => '' ),
	array( 'id' => 'project-status-update', 'title' => 'Project status update', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A crisp weekly update stakeholders will read.', 'prompt' => 'Write a weekly status update for [PROJECT].
Notes: [PASTE WHAT HAPPENED]. Format: overall status (green/amber/red with reason), progress this week, next week, risks and help needed, key dates. Under 200 words.', 'output' => 'A sub-200-word RAG-status update with progress, risks and asks.', 'image' => '' ),
	array( 'id' => 'read-a-book-faster-and-remember-it', 'title' => 'Read a book faster and remember it', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A reading plan with notes that stick.', 'prompt' => 'I want to read [BOOK] in [DAYS]. Make a reading schedule, give me 5 questions to keep in mind while reading, a one-page note template, and after each chapter I will paste my notes so you can quiz me.', 'output' => 'A schedule, guiding questions, a note template and chapter quizzes on request.', 'image' => '' ),
	array( 'id' => 'brain-dump-to-priorities', 'title' => 'Brain dump to priorities', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Empty your head and leave with a clear top 3.', 'prompt' => 'Here is everything on my mind: [BRAIN DUMP].
Group it into projects, tasks, worries and ideas. For tasks, pick today\'s top 3 and explain why. Suggest what to drop, defer or delegate.', 'output' => 'Your thoughts grouped into clear buckets with a top three and things to drop or delegate.', 'image' => '' ),
	array( 'id' => 'calendar-audit', 'title' => 'Calendar audit', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Find where your time really goes.', 'prompt' => 'Here is my calendar for last week: [PASTE EVENTS WITH DURATIONS].
Categorise the time (meetings, deep work, admin, personal), show percentages, compare to my goals [GOALS], and suggest 5 changes for next week.', 'output' => 'A time breakdown by category against your goals, with five specific changes.', 'image' => '' ),
	array( 'id' => 'checklist-for-any-process', 'title' => 'Checklist for any process', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A reusable checklist that prevents mistakes.', 'prompt' => 'Create a checklist for [PROCESS, e.g. publishing a blog post / onboarding a client / travelling].
Group into before, during and after. Each item starts with a verb. Mark the 3 items people most often forget.', 'output' => 'A grouped, verb-first checklist with the commonly forgotten items highlighted.', 'image' => '' ),
	array( 'id' => 'goal-to-daily-action-planner', 'title' => 'Goal-to-daily-action planner', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Connect a yearly goal to what you do today.', 'prompt' => 'My 12-month goal: [GOAL]. Break it into quarterly outcomes, monthly targets, weekly actions and a daily minimum. Add a monthly review question set and what to do if I fall behind.', 'output' => 'A cascade from yearly goal to daily minimum, with reviews and catch-up rules.', 'image' => '' ),
	array( 'id' => 'write-clearer-slack-or-teams-messages', 'title' => 'Write clearer Slack or Teams messages', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Messages people can act on without a meeting.', 'prompt' => 'Rewrite this message so it is clear and actionable: [PASTE MESSAGE].
Use: a one-line summary first, context in 2 lines, the specific ask with deadline, and formatting (bullets). Give a short and a detailed version.', 'output' => 'A short and a detailed version, each leading with the ask and a deadline.', 'image' => '' ),
	array( 'id' => 'life-admin-system', 'title' => 'Life admin system', 'cat' => 'Productivity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Keep bills, renewals and documents under control.', 'prompt' => 'Help me set up a life admin system for: [BILLS, INSURANCE, DOCUMENTS, SUBSCRIPTIONS, HEALTH, VEHICLE].
Give: a master list template, reminder schedule, folder structure for documents (digital), and a monthly 30-minute admin routine.', 'output' => 'A master list, reminder schedule, folder structure and a monthly admin routine.', 'image' => '' ),
	array( 'id' => 'monthly-budget-plan', 'title' => 'Monthly budget plan', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A simple budget using the 50/30/20 idea, adapted to you.', 'prompt' => 'Make a monthly budget. Take-home income: [AMOUNT]. Fixed costs: [LIST]. Variable spending last month: [LIST]. Goals: [GOALS].
Start from the 50/30/20 split (needs/wants/savings) and adapt it to my numbers. Give a table, 5 places to cut, and a weekly spending check routine.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'A budget table adapted from 50/30/20, specific cuts and a weekly check routine.', 'image' => '' ),
	array( 'id' => 'emergency-fund-plan', 'title' => 'Emergency fund plan', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'How much to save and how to build it step by step.', 'prompt' => 'Help me plan an emergency fund. Monthly essential expenses: [AMOUNT]. Job stability: [STABLE / VARIABLE]. Dependants: [NUMBER]. Current savings: [AMOUNT].
Suggest a target (in months of expenses) with reasoning, a monthly saving plan, where to keep it for safety and quick access, and milestones.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'A reasoned target, a monthly plan, safe places to hold it and milestones.', 'image' => '' ),
	array( 'id' => 'pay-off-debt-faster', 'title' => 'Pay off debt faster', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Compare avalanche and snowball methods with your debts.', 'prompt' => 'My debts: [NAME, BALANCE, INTEREST RATE, MINIMUM PAYMENT for each]. Extra I can pay monthly: [AMOUNT].
Compare the avalanche (highest interest first) and snowball (smallest balance first) methods: payoff order, months to debt-free and total interest for each. Show your maths in a table.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'Side-by-side payoff schedules with months and total interest, so you can choose a method.', 'image' => '' ),
	array( 'id' => 'explain-an-investment-term', 'title' => 'Explain an investment term', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Understand any finance jargon with an example.', 'prompt' => 'Explain [TERM, e.g. SIP, index fund, expense ratio, CAGR, compounding, diversification] in simple words.
Give: a definition, an example with numbers, why it matters, a common misunderstanding, and 3 questions I should ask before using it.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'A plain-English explanation with a numeric example, pitfalls and smart questions.', 'image' => '' ),
	array( 'id' => 'compound-interest-calculator-walk-through', 'title' => 'Compound interest calculator walk-through', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'See how money grows over time.', 'prompt' => 'Show how [AMOUNT] invested monthly for [YEARS] years grows at an assumed [RATE]% annual return, compounded monthly.
Give the formula, a year-by-year table (contributions, growth, total), the effect of starting 5 years later, and the Excel formula (FV) to recalculate it. Remind me returns are not guaranteed.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'The formula, a growth table, a delay comparison and the Excel FV formula.', 'image' => '' ),
	array( 'id' => 'financial-goals-plan', 'title' => 'Financial goals plan', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Turn money goals into monthly numbers.', 'prompt' => 'My goals: [e.g. house down payment of X in 5 years, car in 2 years, retirement]. Current savings: [AMOUNT]. Monthly surplus: [AMOUNT].
For each goal: target amount, time frame, monthly saving needed at a conservative assumed return, and suggested priority order.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'Each goal translated into a monthly amount, with assumptions shown and a priority order.', 'image' => '' ),
	array( 'id' => 'understand-my-salary-slip', 'title' => 'Understand my salary slip', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Decode each line of a payslip.', 'prompt' => 'Explain each component of my salary slip: [PASTE COMPONENTS, e.g. basic, HRA, special allowance, PF, professional tax, TDS].
What each means, whether it is taxable in general terms, and questions to ask HR. Note that tax rules change every year.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'A line-by-line explanation of your payslip with questions for HR.', 'image' => '' ),
	array( 'id' => 'spending-analysis-from-a-statement', 'title' => 'Spending analysis from a statement', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Find leaks in your spending.', 'prompt' => 'Here are my transactions for last month: [PASTE, remove account numbers].
Categorise them, show totals and % by category, flag subscriptions and impulse purchases, compare to a healthy budget, and suggest 5 changes with the monthly saving for each.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'Categorised spending with flagged leaks and five changes with their monthly savings.', 'image' => '' ),
	array( 'id' => 'insurance-basics-checklist', 'title' => 'Insurance basics checklist', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Understand what cover you might need.', 'prompt' => 'Explain health, term life, and vehicle insurance basics for someone who is [AGE, DEPENDANTS, JOB].
For each: what it covers, key terms (premium, sum insured, deductible, waiting period, exclusions), questions to ask before buying, and red flags in policies.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'Insurance types explained with key terms, questions to ask and red flags.', 'image' => '' ),
	array( 'id' => 'side-income-ideas', 'title' => 'Side income ideas', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Realistic side income options for your skills.', 'prompt' => 'Suggest 10 side income ideas for someone with skills in [SKILLS] and [HOURS] free hours a week.
For each: what it is, startup cost, time to first income, realistic earning range (say it varies), and the first 3 steps. Avoid get-rich-quick schemes.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'Ten realistic ideas with costs, timelines and first steps, no get-rich-quick schemes.', 'image' => '' ),
	array( 'id' => 'rent-vs-buy-a-home', 'title' => 'Rent vs buy a home', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Compare renting and buying with your numbers.', 'prompt' => 'Compare renting vs buying in [CITY]. Rent: [AMOUNT]. Home price: [AMOUNT]. Down payment: [AMOUNT]. Loan rate: [RATE]% for [YEARS] years. Maintenance and taxes: [AMOUNT/YEAR]. Expected years I will stay: [YEARS].
Show the costs over time, the break-even year, and non-money factors.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'A cost comparison over time with a break-even year and the lifestyle factors to weigh.', 'image' => '' ),
	array( 'id' => 'loan-emi-explained', 'title' => 'Loan EMI explained', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Calculate EMI and see the interest you pay.', 'prompt' => 'Calculate the EMI for a loan of [AMOUNT] at [RATE]% for [YEARS] years.
Show the formula, the EMI, total interest, the first 12 months of the amortisation schedule, and the effect of a prepayment of [AMOUNT] in year 2.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'The EMI, total interest, an amortisation table and the impact of a prepayment.', 'image' => '' ),
	array( 'id' => 'money-talk-with-a-partner', 'title' => 'Money talk with a partner', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Plan a calm conversation about shared finances.', 'prompt' => 'Help me plan a money conversation with my partner about [TOPIC: budget, debt, goals, joint account].
Give: a gentle opener, 8 questions to ask each other, how to handle disagreement, and a simple shared-budget structure to propose.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'An opener, questions, conflict tips and a shared-budget structure.', 'image' => '' ),
	array( 'id' => 'teach-kids-about-money', 'title' => 'Teach kids about money', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Age-appropriate money lessons and activities.', 'prompt' => 'Create a money lesson plan for my [AGE]-year-old. Topics: earning, saving, spending choices, needs vs wants. Give 5 short activities, a simple pocket-money system and conversation starters.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'Five age-appropriate activities, a pocket-money system and conversation starters.', 'image' => '' ),
	array( 'id' => 'retirement-savings-estimate', 'title' => 'Retirement savings estimate', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A rough retirement number and monthly saving.', 'prompt' => 'I am [AGE], want to retire at [AGE], current monthly expenses [AMOUNT], current retirement savings [AMOUNT].
Estimate the corpus needed using an assumed inflation [X]% and return [Y]% (state assumptions), the monthly saving needed, and what changes if I retire 5 years later.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'A rough corpus estimate with stated assumptions, the monthly saving needed and a delay comparison.', 'image' => '' ),
	array( 'id' => 'subscription-audit', 'title' => 'Subscription audit', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Cancel what you do not use.', 'prompt' => 'Here are my subscriptions: [LIST: name, cost, billing cycle, how often I use it].
Calculate the yearly total, rank by value for money, suggest which to cancel, pause, downgrade or share, and the yearly saving.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'A yearly cost view, value ranking and a cancel or downgrade list with savings.', 'image' => '' ),
	array( 'id' => 'freelancer-finances-setup', 'title' => 'Freelancer finances setup', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Separate business money, taxes and savings.', 'prompt' => 'I am a freelancer earning about [AMOUNT/MONTH], irregularly. Set up a simple system: separate accounts, a percentage split for tax, savings and salary, invoicing and expense tracking, and a buffer for low months.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'A percentage-based money system for irregular income with tax and buffer pots.', 'image' => '' ),
	array( 'id' => 'understand-a-credit-score', 'title' => 'Understand a credit score', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'What affects it and how to improve it.', 'prompt' => 'Explain how a credit score works in [COUNTRY] in general terms: what affects it, myths, how to check it for free from official sources, and a 6-month plan to improve it from [CURRENT SITUATION].
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'How credit scores generally work, myths, how to check, and a six-month improvement plan.', 'image' => '' ),
	array( 'id' => 'spot-a-financial-scam', 'title' => 'Spot a financial scam', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Red flags to check before you invest or pay.', 'prompt' => 'I got this offer: [PASTE MESSAGE / DESCRIBE]. Check it against common scam red flags (guaranteed returns, urgency, unregistered entities, upfront fees, requests for OTP or passwords). List the flags found, how to verify the company through official regulator websites, and what to do if I already paid.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'A red-flag check of the offer, verification steps with official sources and what to do next.', 'image' => '' ),
	array( 'id' => 'year-end-money-review', 'title' => 'Year-end money review', 'cat' => 'Personal Finance', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Review the year and plan the next.', 'prompt' => 'Guide me through a year-end money review: income, spending, savings rate, debts, investments, goals hit and missed. Ask for my numbers section by section, then summarise and give 5 priorities for next year.
This is for education, not financial advice; check current rules and consult a registered adviser for big decisions.', 'output' => 'An interactive review ending in a summary and five priorities for next year.', 'image' => '' ),
	array( 'id' => 'fat-loss-workout-plan', 'title' => 'Fat loss workout plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 4-week fat loss plan with progression and form cues.', 'prompt' => 'Create a 4-week workout plan for fat loss.
Me: [AGE], [FITNESS LEVEL], [DAYS PER WEEK] days, [MINUTES] per session, equipment: [LIST], injuries or limits: [LIST].
For each day: warm-up, exercises with sets, reps and rest, simple form cues, and a cool-down. Add weekly progression and signs I should rest.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A four-week day-by-day plan with sets, reps, form cues, progression and recovery signals.', 'image' => '' ),
	array( 'id' => 'muscle-gain-workout-plan', 'title' => 'Muscle gain workout plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 4-week muscle gain plan with progression and form cues.', 'prompt' => 'Create a 4-week workout plan for muscle gain.
Me: [AGE], [FITNESS LEVEL], [DAYS PER WEEK] days, [MINUTES] per session, equipment: [LIST], injuries or limits: [LIST].
For each day: warm-up, exercises with sets, reps and rest, simple form cues, and a cool-down. Add weekly progression and signs I should rest.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A four-week day-by-day plan with sets, reps, form cues, progression and recovery signals.', 'image' => '' ),
	array( 'id' => 'beginner-strength-workout-plan', 'title' => 'Beginner strength workout plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A 4-week beginner strength plan with progression and form cues.', 'prompt' => 'Create a 4-week workout plan for beginner strength.
Me: [AGE], [FITNESS LEVEL], [DAYS PER WEEK] days, [MINUTES] per session, equipment: [LIST], injuries or limits: [LIST].
For each day: warm-up, exercises with sets, reps and rest, simple form cues, and a cool-down. Add weekly progression and signs I should rest.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A four-week day-by-day plan with sets, reps, form cues, progression and recovery signals.', 'image' => '' ),
	array( 'id' => 'running-a-first-5k-workout-plan', 'title' => 'Running a first 5k workout plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 4-week running a first 5K plan with progression and form cues.', 'prompt' => 'Create a 4-week workout plan for running a first 5K.
Me: [AGE], [FITNESS LEVEL], [DAYS PER WEEK] days, [MINUTES] per session, equipment: [LIST], injuries or limits: [LIST].
For each day: warm-up, exercises with sets, reps and rest, simple form cues, and a cool-down. Add weekly progression and signs I should rest.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A four-week day-by-day plan with sets, reps, form cues, progression and recovery signals.', 'image' => '' ),
	array( 'id' => 'home-workouts-with-no-equipment-workout-plan', 'title' => 'Home workouts with no equipment workout plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 4-week home workouts with no equipment plan with progression and form cues.', 'prompt' => 'Create a 4-week workout plan for home workouts with no equipment.
Me: [AGE], [FITNESS LEVEL], [DAYS PER WEEK] days, [MINUTES] per session, equipment: [LIST], injuries or limits: [LIST].
For each day: warm-up, exercises with sets, reps and rest, simple form cues, and a cool-down. Add weekly progression and signs I should rest.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A four-week day-by-day plan with sets, reps, form cues, progression and recovery signals.', 'image' => '' ),
	array( 'id' => 'better-flexibility-and-mobility-workout-plan', 'title' => 'Better flexibility and mobility workout plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A 4-week better flexibility and mobility plan with progression and form cues.', 'prompt' => 'Create a 4-week workout plan for better flexibility and mobility.
Me: [AGE], [FITNESS LEVEL], [DAYS PER WEEK] days, [MINUTES] per session, equipment: [LIST], injuries or limits: [LIST].
For each day: warm-up, exercises with sets, reps and rest, simple form cues, and a cool-down. Add weekly progression and signs I should rest.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A four-week day-by-day plan with sets, reps, form cues, progression and recovery signals.', 'image' => '' ),
	array( 'id' => 'back-friendly-core-strength-workout-plan', 'title' => 'Back-friendly core strength workout plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 4-week back-friendly core strength plan with progression and form cues.', 'prompt' => 'Create a 4-week workout plan for back-friendly core strength.
Me: [AGE], [FITNESS LEVEL], [DAYS PER WEEK] days, [MINUTES] per session, equipment: [LIST], injuries or limits: [LIST].
For each day: warm-up, exercises with sets, reps and rest, simple form cues, and a cool-down. Add weekly progression and signs I should rest.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A four-week day-by-day plan with sets, reps, form cues, progression and recovery signals.', 'image' => '' ),
	array( 'id' => 'fitness-after-40-workout-plan', 'title' => 'Fitness after 40 workout plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 4-week fitness after 40 plan with progression and form cues.', 'prompt' => 'Create a 4-week workout plan for fitness after 40.
Me: [AGE], [FITNESS LEVEL], [DAYS PER WEEK] days, [MINUTES] per session, equipment: [LIST], injuries or limits: [LIST].
For each day: warm-up, exercises with sets, reps and rest, simple form cues, and a cool-down. Add weekly progression and signs I should rest.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A four-week day-by-day plan with sets, reps, form cues, progression and recovery signals.', 'image' => '' ),
	array( 'id' => 'busy-schedule-20-minute-workouts-workout-plan', 'title' => 'Busy-schedule 20-minute workouts workout plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A 4-week busy-schedule 20-minute workouts plan with progression and form cues.', 'prompt' => 'Create a 4-week workout plan for busy-schedule 20-minute workouts.
Me: [AGE], [FITNESS LEVEL], [DAYS PER WEEK] days, [MINUTES] per session, equipment: [LIST], injuries or limits: [LIST].
For each day: warm-up, exercises with sets, reps and rest, simple form cues, and a cool-down. Add weekly progression and signs I should rest.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A four-week day-by-day plan with sets, reps, form cues, progression and recovery signals.', 'image' => '' ),
	array( 'id' => 'improving-posture-for-desk-workers-workout-plan', 'title' => 'Improving posture for desk workers workout plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 4-week improving posture for desk workers plan with progression and form cues.', 'prompt' => 'Create a 4-week workout plan for improving posture for desk workers.
Me: [AGE], [FITNESS LEVEL], [DAYS PER WEEK] days, [MINUTES] per session, equipment: [LIST], injuries or limits: [LIST].
For each day: warm-up, exercises with sets, reps and rest, simple form cues, and a cool-down. Add weekly progression and signs I should rest.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A four-week day-by-day plan with sets, reps, form cues, progression and recovery signals.', 'image' => '' ),
	array( 'id' => 'vegetarian-indian-meal-plan', 'title' => 'Vegetarian indian meal plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 7-day vegetarian Indian meal plan with a grocery list.', 'prompt' => 'Create a 7-day vegetarian Indian meal plan.
Me: [AGE], [GOAL], about [CALORIES OR \'estimate for me\'] per day, foods I avoid: [LIST], cooking time: [MINUTES] per meal, budget: [AMOUNT].
Table: Day | Breakfast | Lunch | Snack | Dinner, with rough protein per meal. Then a grocery list grouped by section and 3 prep-ahead tips.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A seven-day table with approximate protein, a grouped grocery list and meal-prep tips.', 'image' => '' ),
	array( 'id' => 'high-protein-vegetarian-meal-plan', 'title' => 'High-protein vegetarian meal plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 7-day high-protein vegetarian meal plan with a grocery list.', 'prompt' => 'Create a 7-day high-protein vegetarian meal plan.
Me: [AGE], [GOAL], about [CALORIES OR \'estimate for me\'] per day, foods I avoid: [LIST], cooking time: [MINUTES] per meal, budget: [AMOUNT].
Table: Day | Breakfast | Lunch | Snack | Dinner, with rough protein per meal. Then a grocery list grouped by section and 3 prep-ahead tips.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A seven-day table with approximate protein, a grouped grocery list and meal-prep tips.', 'image' => '' ),
	array( 'id' => 'vegan-meal-plan', 'title' => 'Vegan meal plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A 7-day vegan meal plan with a grocery list.', 'prompt' => 'Create a 7-day vegan meal plan.
Me: [AGE], [GOAL], about [CALORIES OR \'estimate for me\'] per day, foods I avoid: [LIST], cooking time: [MINUTES] per meal, budget: [AMOUNT].
Table: Day | Breakfast | Lunch | Snack | Dinner, with rough protein per meal. Then a grocery list grouped by section and 3 prep-ahead tips.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A seven-day table with approximate protein, a grouped grocery list and meal-prep tips.', 'image' => '' ),
	array( 'id' => 'budget-friendly-meal-plan', 'title' => 'Budget-friendly meal plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 7-day budget-friendly meal plan with a grocery list.', 'prompt' => 'Create a 7-day budget-friendly meal plan.
Me: [AGE], [GOAL], about [CALORIES OR \'estimate for me\'] per day, foods I avoid: [LIST], cooking time: [MINUTES] per meal, budget: [AMOUNT].
Table: Day | Breakfast | Lunch | Snack | Dinner, with rough protein per meal. Then a grocery list grouped by section and 3 prep-ahead tips.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A seven-day table with approximate protein, a grouped grocery list and meal-prep tips.', 'image' => '' ),
	array( 'id' => 'eggetarian-meal-plan', 'title' => 'Eggetarian meal plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 7-day eggetarian meal plan with a grocery list.', 'prompt' => 'Create a 7-day eggetarian meal plan.
Me: [AGE], [GOAL], about [CALORIES OR \'estimate for me\'] per day, foods I avoid: [LIST], cooking time: [MINUTES] per meal, budget: [AMOUNT].
Table: Day | Breakfast | Lunch | Snack | Dinner, with rough protein per meal. Then a grocery list grouped by section and 3 prep-ahead tips.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A seven-day table with approximate protein, a grouped grocery list and meal-prep tips.', 'image' => '' ),
	array( 'id' => 'mediterranean-style-meal-plan', 'title' => 'Mediterranean-style meal plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A 7-day Mediterranean-style meal plan with a grocery list.', 'prompt' => 'Create a 7-day Mediterranean-style meal plan.
Me: [AGE], [GOAL], about [CALORIES OR \'estimate for me\'] per day, foods I avoid: [LIST], cooking time: [MINUTES] per meal, budget: [AMOUNT].
Table: Day | Breakfast | Lunch | Snack | Dinner, with rough protein per meal. Then a grocery list grouped by section and 3 prep-ahead tips.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A seven-day table with approximate protein, a grouped grocery list and meal-prep tips.', 'image' => '' ),
	array( 'id' => 'diabetes-friendly-meal-plan', 'title' => 'Diabetes-friendly meal plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 7-day diabetes-friendly meal plan with a grocery list.', 'prompt' => 'Create a 7-day diabetes-friendly (doctor-approved plan first) meal plan.
Me: [AGE], [GOAL], about [CALORIES OR \'estimate for me\'] per day, foods I avoid: [LIST], cooking time: [MINUTES] per meal, budget: [AMOUNT].
Table: Day | Breakfast | Lunch | Snack | Dinner, with rough protein per meal. Then a grocery list grouped by section and 3 prep-ahead tips.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A seven-day table with approximate protein, a grouped grocery list and meal-prep tips.', 'image' => '' ),
	array( 'id' => 'student-hostel-meal-plan', 'title' => 'Student hostel meal plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 7-day student hostel meal plan with a grocery list.', 'prompt' => 'Create a 7-day student hostel meal plan.
Me: [AGE], [GOAL], about [CALORIES OR \'estimate for me\'] per day, foods I avoid: [LIST], cooking time: [MINUTES] per meal, budget: [AMOUNT].
Table: Day | Breakfast | Lunch | Snack | Dinner, with rough protein per meal. Then a grocery list grouped by section and 3 prep-ahead tips.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A seven-day table with approximate protein, a grouped grocery list and meal-prep tips.', 'image' => '' ),
	array( 'id' => 'weight-loss-calorie-deficit-meal-plan', 'title' => 'Weight-loss calorie deficit meal plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A 7-day weight-loss calorie deficit meal plan with a grocery list.', 'prompt' => 'Create a 7-day weight-loss calorie deficit meal plan.
Me: [AGE], [GOAL], about [CALORIES OR \'estimate for me\'] per day, foods I avoid: [LIST], cooking time: [MINUTES] per meal, budget: [AMOUNT].
Table: Day | Breakfast | Lunch | Snack | Dinner, with rough protein per meal. Then a grocery list grouped by section and 3 prep-ahead tips.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A seven-day table with approximate protein, a grouped grocery list and meal-prep tips.', 'image' => '' ),
	array( 'id' => 'balanced-family-meal-plan', 'title' => 'Balanced family meal plan', 'cat' => 'Health & Fitness', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 7-day balanced family meal plan with a grocery list.', 'prompt' => 'Create a 7-day balanced family meal plan.
Me: [AGE], [GOAL], about [CALORIES OR \'estimate for me\'] per day, foods I avoid: [LIST], cooking time: [MINUTES] per meal, budget: [AMOUNT].
Table: Day | Breakfast | Lunch | Snack | Dinner, with rough protein per meal. Then a grocery list grouped by section and 3 prep-ahead tips.
General wellness information, not medical advice. If you have a health condition, are pregnant, or take medication, check with a doctor first.', 'output' => 'A seven-day table with approximate protein, a grouped grocery list and meal-prep tips.', 'image' => '' ),
	array( 'id' => 'job-description', 'title' => 'Job description', 'cat' => 'HR & Workplace', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'An inclusive, clear job description that attracts the right people.', 'prompt' => 'Write a job description for [ROLE] at [COMPANY], [LOCATION / REMOTE].
Include: about us (3 lines), the role\'s impact, responsibilities (6–8), must-have vs nice-to-have skills, what we offer, salary range [RANGE] and how to apply. Use inclusive language and avoid unnecessary requirements.', 'output' => 'A clear, inclusive job description with must-haves separated from nice-to-haves.', 'image' => '' ),
	array( 'id' => 'interview-question-bank', 'title' => 'Interview question bank', 'cat' => 'HR & Workplace', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Structured questions and scoring guides for a role.', 'prompt' => 'Create a structured interview kit for [ROLE].
Competencies: [LIST]. For each: 2 behavioural questions, 1 situational question, what a strong vs weak answer looks like, and a 1–5 scoring guide. Add questions that must NOT be asked (personal, discriminatory).', 'output' => 'A structured interview kit with scoring guides and a list of questions to avoid.', 'image' => '' ),
	array( 'id' => 'onboarding-plan-for-a-new-hire', 'title' => 'Onboarding plan for a new hire', 'cat' => 'HR & Workplace', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A first-month plan that makes new people productive and welcome.', 'prompt' => 'Create an onboarding plan for a new [ROLE] joining [TEAM].
Include: before day 1, day 1 schedule, week 1, weeks 2–4, a buddy plan, tools and access checklist, first small win, and 30-day check-in questions.', 'output' => 'A timeline from pre-boarding to day 30 with checklists, a buddy plan and check-in questions.', 'image' => '' ),
	array( 'id' => 'performance-review-feedback', 'title' => 'Performance review feedback', 'cat' => 'HR & Workplace', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Fair, specific feedback using examples.', 'prompt' => 'Help me write a performance review for [EMPLOYEE ROLE].
My notes: [STRENGTHS, ISSUES, EXAMPLES, GOALS MET].
Structure: summary, strengths with examples, growth areas with examples and support offered, goals for next period (SMART). Use the SBI model (situation, behaviour, impact). Balanced and respectful.', 'output' => 'A balanced review using situation-behaviour-impact examples and SMART goals.', 'image' => '' ),
	array( 'id' => 'difficult-conversation-script', 'title' => 'Difficult conversation script', 'cat' => 'HR & Workplace', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Prepare for a tough talk with an employee or colleague.', 'prompt' => 'Help me prepare for a difficult conversation with [PERSON / ROLE] about [ISSUE].
Give: my goal, an opening line, how to describe the issue with facts, questions to understand their side, likely reactions and calm responses, agreed next steps, and how to document it.', 'output' => 'A conversation plan with a factual opening, listening questions, responses and documentation steps.', 'image' => '' ),
	array( 'id' => 'company-policy-draft', 'title' => 'Company policy draft', 'cat' => 'HR & Workplace', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A clear policy draft for your team.', 'prompt' => 'Draft a [POLICY: remote work / leave / expense / code of conduct] policy for a [SIZE]-person company in [COUNTRY].
Sections: purpose, scope, rules, process, responsibilities, exceptions, review date. Plain English. Mark points that need checking against local labour law.', 'output' => 'A plain-English policy draft with legal check points highlighted.', 'image' => '' ),
	array( 'id' => 'employee-engagement-survey', 'title' => 'Employee engagement survey', 'cat' => 'HR & Workplace', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Questions that reveal how people really feel at work.', 'prompt' => 'Create an anonymous employee engagement survey (15 questions max) covering: role clarity, manager support, growth, recognition, workload, belonging. Mix 1–5 scales and 2 open questions. Then explain how to share results and act on them.', 'output' => 'A short anonymous survey and a plan for sharing and acting on the results.', 'image' => '' ),
	array( 'id' => 'recognition-and-appreciation-messages', 'title' => 'Recognition and appreciation messages', 'cat' => 'HR & Workplace', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Specific thank-you messages for great work.', 'prompt' => 'Write 5 recognition messages for [PERSON] who [ACHIEVEMENT].
Versions: team chat shout-out, email to their manager, public all-hands mention, handwritten note, LinkedIn recommendation. Be specific about the impact.', 'output' => 'Five specific recognition messages for different channels.', 'image' => '' ),
	array( 'id' => 'exit-interview-questions', 'title' => 'Exit interview questions', 'cat' => 'HR & Workplace', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Learn why people leave and what to fix.', 'prompt' => 'Write 12 exit interview questions for [COMPANY TYPE]. Cover reasons for leaving, manager, growth, pay, culture and what would have made them stay. Add tips to make the person comfortable being honest and how to analyse answers across exits.', 'output' => 'Twelve exit questions, tips for honest answers and how to spot patterns.', 'image' => '' ),
	array( 'id' => 'offer-letter-draft', 'title' => 'Offer letter draft', 'cat' => 'HR & Workplace', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A clear, friendly offer letter.', 'prompt' => 'Draft an offer letter for [CANDIDATE] for [ROLE] at [COMPANY].
Details: start date [DATE], compensation [AMOUNT AND STRUCTURE], benefits [LIST], probation [PERIOD], location [LOCATION], acceptance deadline [DATE]. Warm tone. Add a note to have it reviewed for local legal requirements.', 'output' => 'A warm, complete offer letter with a reminder to check local legal requirements.', 'image' => '' ),
	array( 'id' => 'team-building-activity-ideas', 'title' => 'Team building activity ideas', 'cat' => 'HR & Workplace', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Activities people actually enjoy, online or in person.', 'prompt' => 'Suggest 12 team-building activities for a [SIZE] team that is [REMOTE / HYBRID / IN OFFICE], budget [AMOUNT].
For each: name, time needed, what it builds (trust, communication, fun), materials and instructions. Include 4 that need zero budget.', 'output' => 'Twelve activities with instructions, including zero-budget options.', 'image' => '' ),
	array( 'id' => 'training-needs-analysis', 'title' => 'Training needs analysis', 'cat' => 'HR & Workplace', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Find skill gaps and plan training.', 'prompt' => 'Help me run a training needs analysis for [TEAM / ROLE].
Current skills: [DESCRIBE]. Business goals: [GOALS]. Give a skills matrix template, questions to ask employees and managers, how to prioritise gaps, and a 3-month training plan with formats (courses, mentoring, projects).', 'output' => 'A skills matrix, interview questions, prioritisation method and a three-month training plan.', 'image' => '' ),
	array( 'id' => 'workplace-announcement', 'title' => 'Workplace announcement', 'cat' => 'HR & Workplace', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Announce a change clearly and kindly.', 'prompt' => 'Write an internal announcement about [CHANGE: reorganisation, new tool, office move, policy update].
Include: what is changing and when, why, what it means for employees, support available, and where to ask questions. Calm, transparent, under 250 words.', 'output' => 'A transparent sub-250-word announcement that answers what, why, when and where to ask.', 'image' => '' ),
	array( 'id' => 'resolve-a-team-conflict', 'title' => 'Resolve a team conflict', 'cat' => 'HR & Workplace', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Mediate a disagreement between colleagues.', 'prompt' => 'Two team members [ROLES] disagree about [ISSUE]. Help me mediate.
Give: a pre-meeting plan, ground rules, a structure for each to share their view, questions to find shared goals, options to agree on, and follow-up steps.', 'output' => 'A mediation plan with ground rules, structured sharing, shared-goal questions and follow-ups.', 'image' => '' ),
	array( 'id' => 'internship-programme-plan', 'title' => 'Internship programme plan', 'cat' => 'HR & Workplace', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A structured internship that is useful for both sides.', 'prompt' => 'Plan an [WEEKS]-week internship programme for [NUMBER] interns in [FIELD].
Include: goals, weekly learning plan, a real project, mentor responsibilities, feedback check-ins, final presentation, and how to evaluate for a full-time offer.', 'output' => 'A week-by-week internship plan with a real project, mentoring and evaluation criteria.', 'image' => '' ),
	array( 'id' => 'mathematics-lesson-plan', 'title' => 'Mathematics lesson plan', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A complete, engaging Mathematics lesson plan with activities and assessment.', 'prompt' => 'Create a [MINUTES]-minute Mathematics lesson plan on [TOPIC] for [CLASS / GRADE].
Include: learning objectives, materials, warm-up hook (5 min), direct teaching with an example, a group activity, a check for understanding, differentiation for slower and faster learners, homework, and an exit ticket question.', 'output' => 'A timed lesson plan with objectives, hook, activity, differentiation, homework and an exit ticket.', 'image' => '' ),
	array( 'id' => 'science-lesson-plan', 'title' => 'Science lesson plan', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A complete, engaging Science lesson plan with activities and assessment.', 'prompt' => 'Create a [MINUTES]-minute Science lesson plan on [TOPIC] for [CLASS / GRADE].
Include: learning objectives, materials, warm-up hook (5 min), direct teaching with an example, a group activity, a check for understanding, differentiation for slower and faster learners, homework, and an exit ticket question.', 'output' => 'A timed lesson plan with objectives, hook, activity, differentiation, homework and an exit ticket.', 'image' => '' ),
	array( 'id' => 'english-lesson-plan', 'title' => 'English lesson plan', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A complete, engaging English lesson plan with activities and assessment.', 'prompt' => 'Create a [MINUTES]-minute English lesson plan on [TOPIC] for [CLASS / GRADE].
Include: learning objectives, materials, warm-up hook (5 min), direct teaching with an example, a group activity, a check for understanding, differentiation for slower and faster learners, homework, and an exit ticket question.', 'output' => 'A timed lesson plan with objectives, hook, activity, differentiation, homework and an exit ticket.', 'image' => '' ),
	array( 'id' => 'hindi-lesson-plan', 'title' => 'Hindi lesson plan', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A complete, engaging Hindi lesson plan with activities and assessment.', 'prompt' => 'Create a [MINUTES]-minute Hindi lesson plan on [TOPIC] for [CLASS / GRADE].
Include: learning objectives, materials, warm-up hook (5 min), direct teaching with an example, a group activity, a check for understanding, differentiation for slower and faster learners, homework, and an exit ticket question.', 'output' => 'A timed lesson plan with objectives, hook, activity, differentiation, homework and an exit ticket.', 'image' => '' ),
	array( 'id' => 'social-studies-lesson-plan', 'title' => 'Social Studies lesson plan', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A complete, engaging Social Studies lesson plan with activities and assessment.', 'prompt' => 'Create a [MINUTES]-minute Social Studies lesson plan on [TOPIC] for [CLASS / GRADE].
Include: learning objectives, materials, warm-up hook (5 min), direct teaching with an example, a group activity, a check for understanding, differentiation for slower and faster learners, homework, and an exit ticket question.', 'output' => 'A timed lesson plan with objectives, hook, activity, differentiation, homework and an exit ticket.', 'image' => '' ),
	array( 'id' => 'computer-science-lesson-plan', 'title' => 'Computer Science lesson plan', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A complete, engaging Computer Science lesson plan with activities and assessment.', 'prompt' => 'Create a [MINUTES]-minute Computer Science lesson plan on [TOPIC] for [CLASS / GRADE].
Include: learning objectives, materials, warm-up hook (5 min), direct teaching with an example, a group activity, a check for understanding, differentiation for slower and faster learners, homework, and an exit ticket question.', 'output' => 'A timed lesson plan with objectives, hook, activity, differentiation, homework and an exit ticket.', 'image' => '' ),
	array( 'id' => 'physics-lesson-plan', 'title' => 'Physics lesson plan', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A complete, engaging Physics lesson plan with activities and assessment.', 'prompt' => 'Create a [MINUTES]-minute Physics lesson plan on [TOPIC] for [CLASS / GRADE].
Include: learning objectives, materials, warm-up hook (5 min), direct teaching with an example, a group activity, a check for understanding, differentiation for slower and faster learners, homework, and an exit ticket question.', 'output' => 'A timed lesson plan with objectives, hook, activity, differentiation, homework and an exit ticket.', 'image' => '' ),
	array( 'id' => 'chemistry-lesson-plan', 'title' => 'Chemistry lesson plan', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A complete, engaging Chemistry lesson plan with activities and assessment.', 'prompt' => 'Create a [MINUTES]-minute Chemistry lesson plan on [TOPIC] for [CLASS / GRADE].
Include: learning objectives, materials, warm-up hook (5 min), direct teaching with an example, a group activity, a check for understanding, differentiation for slower and faster learners, homework, and an exit ticket question.', 'output' => 'A timed lesson plan with objectives, hook, activity, differentiation, homework and an exit ticket.', 'image' => '' ),
	array( 'id' => 'biology-lesson-plan', 'title' => 'Biology lesson plan', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A complete, engaging Biology lesson plan with activities and assessment.', 'prompt' => 'Create a [MINUTES]-minute Biology lesson plan on [TOPIC] for [CLASS / GRADE].
Include: learning objectives, materials, warm-up hook (5 min), direct teaching with an example, a group activity, a check for understanding, differentiation for slower and faster learners, homework, and an exit ticket question.', 'output' => 'A timed lesson plan with objectives, hook, activity, differentiation, homework and an exit ticket.', 'image' => '' ),
	array( 'id' => 'economics-lesson-plan', 'title' => 'Economics lesson plan', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A complete, engaging Economics lesson plan with activities and assessment.', 'prompt' => 'Create a [MINUTES]-minute Economics lesson plan on [TOPIC] for [CLASS / GRADE].
Include: learning objectives, materials, warm-up hook (5 min), direct teaching with an example, a group activity, a check for understanding, differentiation for slower and faster learners, homework, and an exit ticket question.', 'output' => 'A timed lesson plan with objectives, hook, activity, differentiation, homework and an exit ticket.', 'image' => '' ),
	array( 'id' => 'history-lesson-plan', 'title' => 'History lesson plan', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A complete, engaging History lesson plan with activities and assessment.', 'prompt' => 'Create a [MINUTES]-minute History lesson plan on [TOPIC] for [CLASS / GRADE].
Include: learning objectives, materials, warm-up hook (5 min), direct teaching with an example, a group activity, a check for understanding, differentiation for slower and faster learners, homework, and an exit ticket question.', 'output' => 'A timed lesson plan with objectives, hook, activity, differentiation, homework and an exit ticket.', 'image' => '' ),
	array( 'id' => 'geography-lesson-plan', 'title' => 'Geography lesson plan', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A complete, engaging Geography lesson plan with activities and assessment.', 'prompt' => 'Create a [MINUTES]-minute Geography lesson plan on [TOPIC] for [CLASS / GRADE].
Include: learning objectives, materials, warm-up hook (5 min), direct teaching with an example, a group activity, a check for understanding, differentiation for slower and faster learners, homework, and an exit ticket question.', 'output' => 'A timed lesson plan with objectives, hook, activity, differentiation, homework and an exit ticket.', 'image' => '' ),
	array( 'id' => 'art-lesson-plan', 'title' => 'Art lesson plan', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A complete, engaging Art lesson plan with activities and assessment.', 'prompt' => 'Create a [MINUTES]-minute Art lesson plan on [TOPIC] for [CLASS / GRADE].
Include: learning objectives, materials, warm-up hook (5 min), direct teaching with an example, a group activity, a check for understanding, differentiation for slower and faster learners, homework, and an exit ticket question.', 'output' => 'A timed lesson plan with objectives, hook, activity, differentiation, homework and an exit ticket.', 'image' => '' ),
	array( 'id' => 'physical-education-lesson-plan', 'title' => 'Physical Education lesson plan', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A complete, engaging Physical Education lesson plan with activities and assessment.', 'prompt' => 'Create a [MINUTES]-minute Physical Education lesson plan on [TOPIC] for [CLASS / GRADE].
Include: learning objectives, materials, warm-up hook (5 min), direct teaching with an example, a group activity, a check for understanding, differentiation for slower and faster learners, homework, and an exit ticket question.', 'output' => 'A timed lesson plan with objectives, hook, activity, differentiation, homework and an exit ticket.', 'image' => '' ),
	array( 'id' => 'environmental-studies-lesson-plan', 'title' => 'Environmental Studies lesson plan', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A complete, engaging Environmental Studies lesson plan with activities and assessment.', 'prompt' => 'Create a [MINUTES]-minute Environmental Studies lesson plan on [TOPIC] for [CLASS / GRADE].
Include: learning objectives, materials, warm-up hook (5 min), direct teaching with an example, a group activity, a check for understanding, differentiation for slower and faster learners, homework, and an exit ticket question.', 'output' => 'A timed lesson plan with objectives, hook, activity, differentiation, homework and an exit ticket.', 'image' => '' ),
	array( 'id' => 'rubric-for-an-assignment', 'title' => 'Rubric for an assignment', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A clear grading rubric students understand.', 'prompt' => 'Create a grading rubric for [ASSIGNMENT] for [GRADE].
Criteria: [LIST OR \'suggest\']. 4 levels (excellent, good, developing, beginning) with specific descriptions, points per level, and a student-friendly version.', 'output' => 'A four-level rubric with specific descriptors and a student-friendly version.', 'image' => '' ),
	array( 'id' => 'quiz-with-answer-key', 'title' => 'Quiz with answer key', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A quick quiz in any format with answers.', 'prompt' => 'Make a [10]-question quiz on [TOPIC] for [GRADE]: 6 MCQs, 2 true/false, 2 short answers. Mix easy, medium and hard. Answer key with explanations at the end.', 'output' => 'A mixed-format quiz with an explained answer key.', 'image' => '' ),
	array( 'id' => 'parent-teacher-meeting-notes', 'title' => 'Parent-teacher meeting notes', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Prepare for a positive, honest parent meeting.', 'prompt' => 'Help me prepare for a parent meeting about [STUDENT]\'s [PROGRESS / BEHAVIOUR].
Facts: [NOTES]. Give: a positive opener, 2 strengths, concerns with examples, a joint action plan, questions for the parents, and a follow-up email.', 'output' => 'A balanced meeting plan with strengths, concerns, an action plan and a follow-up email.', 'image' => '' ),
	array( 'id' => 'differentiated-worksheet', 'title' => 'Differentiated worksheet', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'One topic, three difficulty levels.', 'prompt' => 'Create a worksheet on [TOPIC] for [GRADE] in 3 levels (support, core, challenge), 6 questions each, same learning goal. Add hints for the support level and an extension task for the challenge level. Answer key at the end.', 'output' => 'Three levelled worksheets with hints, an extension task and answers.', 'image' => '' ),
	array( 'id' => 'project-based-learning-unit', 'title' => 'Project-based learning unit', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A 2-week project students will love.', 'prompt' => 'Design a 2-week project-based learning unit on [TOPIC] for [GRADE].
Include: driving question, final product, daily plan, group roles, resources, milestones, assessment rubric, and a showcase idea.', 'output' => 'A two-week unit with a driving question, daily plan, roles, milestones and rubric.', 'image' => '' ),
	array( 'id' => 'explain-a-topic-at-three-levels', 'title' => 'Explain a topic at three levels', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'The same concept for beginners, average and advanced students.', 'prompt' => 'Explain [CONCEPT] at three levels: for a [YOUNGER STUDENT], for a [TYPICAL GRADE] student, and for an advanced student. Each with an example and a question to check understanding.', 'output' => 'Three explanations of increasing depth, each with an example and a check question.', 'image' => '' ),
	array( 'id' => 'classroom-management-plan', 'title' => 'Classroom management plan', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Routines and rules for a calm classroom.', 'prompt' => 'Create a classroom management plan for [GRADE] with [CLASS SIZE] students.
Include: 5 positive rules, daily routines, attention signals, a reward system, consequences ladder, and scripts for 3 common disruptions.', 'output' => 'A positive classroom plan with routines, signals, rewards, consequences and scripts.', 'image' => '' ),
	array( 'id' => 'report-card-comments', 'title' => 'Report card comments', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Personal, constructive comments in minutes.', 'prompt' => 'Write report card comments for these students: [NAME, STRENGTHS, AREAS TO IMPROVE for each].
2–3 sentences each: strength, area to grow, encouragement. Varied wording; no two comments the same.', 'output' => 'Personal, varied, constructive comments for each student.', 'image' => '' ),
	array( 'id' => 'flipped-classroom-pre-work', 'title' => 'Flipped classroom pre-work', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Pre-class material and in-class activities.', 'prompt' => 'Plan a flipped lesson on [TOPIC] for [GRADE]: a 5-minute video script or reading for homework, 3 check questions, and a 40-minute in-class activity that applies the concept.', 'output' => 'Pre-class content, check questions and an applied in-class activity.', 'image' => '' ),
	array( 'id' => 'debate-topics-and-structure', 'title' => 'Debate topics and structure', 'cat' => 'Teaching', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Debate motions with a fair structure and scoring.', 'prompt' => 'Give 10 age-appropriate debate motions on [THEME] for [GRADE], then a debate format (roles, timings), preparation sheet, and a scoring rubric for argument, evidence, rebuttal and delivery.', 'output' => 'Ten motions, a debate format, prep sheet and scoring rubric.', 'image' => '' ),
	array( 'id' => 'product-requirements-document-prd', 'title' => 'Product requirements document (PRD)', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A clear PRD from a rough feature idea.', 'prompt' => 'Write a PRD for [FEATURE] in [PRODUCT].
Sections: problem and evidence, goals and non-goals, users and use cases, user stories with acceptance criteria, UX notes, success metrics, risks, open questions, release plan. Keep it to the essentials; mark assumptions.', 'output' => 'A concise PRD with user stories, acceptance criteria, metrics and flagged assumptions.', 'image' => '' ),
	array( 'id' => 'user-stories-with-acceptance-criteria', 'title' => 'User stories with acceptance criteria', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn a feature into sprint-ready stories.', 'prompt' => 'Break [FEATURE] into user stories in the format "As a [user], I want [goal] so that [benefit]".
For each: acceptance criteria in Given/When/Then, a story-point guess (1, 2, 3, 5, 8), and dependencies. Order them for an MVP.', 'output' => 'MVP-ordered user stories with Given/When/Then criteria, estimates and dependencies.', 'image' => '' ),
	array( 'id' => 'user-interview-script', 'title' => 'User interview script', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Learn real problems without leading questions.', 'prompt' => 'Write a 30-minute user interview script to understand how [USER TYPE] currently [DOES TASK].
Sections: warm-up, current process, pain points, workarounds, past attempts, wrap-up. Open, non-leading questions only; add follow-up probes and things to avoid saying.', 'output' => 'A non-leading interview script with probes and a list of phrases to avoid.', 'image' => '' ),
	array( 'id' => 'synthesise-user-research', 'title' => 'Synthesise user research', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn interview notes into themes and insights.', 'prompt' => 'Here are notes from [N] user interviews: [PASTE].
Group findings into themes (affinity mapping), give each theme supporting quotes and how many users mentioned it, write 5 insight statements, and suggest opportunities. Do not invent quotes.', 'output' => 'Themes with real supporting quotes and counts, insight statements and opportunity areas.', 'image' => '' ),
	array( 'id' => 'feature-prioritisation-with-rice', 'title' => 'Feature prioritisation with RICE', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Rank your backlog with reach, impact, confidence and effort.', 'prompt' => 'Prioritise these features using RICE (reach × impact × confidence ÷ effort): [LIST WITH WHAT YOU KNOW].
Ask for missing estimates or state assumptions, show a scored table, rank them, and point out where the scores are least certain.', 'output' => 'A RICE-scored, ranked table with assumptions and uncertainty called out.', 'image' => '' ),
	array( 'id' => 'ux-copy-for-an-app-screen', 'title' => 'UX copy for an app screen', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Microcopy for buttons, empty states and errors.', 'prompt' => 'Write UX copy for the [SCREEN] in [APP]. Elements: title, helper text, button labels, empty state, error messages for [CASES], success message, tooltip.
Clear, short, friendly, action-oriented. Give 2 options for each.', 'output' => 'Two options for every piece of microcopy on the screen, short and action-oriented.', 'image' => '' ),
	array( 'id' => 'usability-test-plan', 'title' => 'Usability test plan', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Test a design with 5 users and find the biggest issues.', 'prompt' => 'Create a usability test plan for [PROTOTYPE / FEATURE].
Include: goals, participant criteria, 5 realistic tasks with success criteria, think-aloud instructions, metrics (success rate, time, errors, ease rating), a note-taking template and how to report findings.', 'output' => 'A test plan with tasks, success criteria, metrics, a note template and reporting guide.', 'image' => '' ),
	array( 'id' => 'onboarding-flow-ideas', 'title' => 'Onboarding flow ideas', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Get new users to the aha moment faster.', 'prompt' => 'My product: [PRODUCT]. The aha moment: [WHAT]. Current drop-off: [WHERE].
Design an improved onboarding flow step by step, with copy for each screen, what to remove, progress cues, and 3 experiments to run.', 'output' => 'A step-by-step onboarding flow with screen copy, removals and experiments.', 'image' => '' ),
	array( 'id' => 'competitive-ux-teardown', 'title' => 'Competitive UX teardown', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Learn from how competitors solve the same problem.', 'prompt' => 'Do a UX teardown of how [COMPETITOR] handles [FLOW, e.g. signup, checkout] based on this description or screenshots: [DESCRIBE].
List what works, friction points, patterns worth borrowing, and how we could do better. Be specific to the steps described.', 'output' => 'A step-specific teardown with strengths, friction points and ideas to adopt.', 'image' => '' ),
	array( 'id' => 'north-star-metric', 'title' => 'North star metric', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Pick one metric that reflects real customer value.', 'prompt' => 'Help me choose a north star metric for [PRODUCT] whose value to users is [VALUE].
Suggest 3 candidates, test each against: reflects value, leads to revenue, measurable, team can influence. Pick one and define 3–4 input metrics that drive it.', 'output' => 'Three candidate metrics tested against clear criteria, one winner and its input metrics.', 'image' => '' ),
	array( 'id' => 'product-roadmap', 'title' => 'Product roadmap', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A now/next/later roadmap tied to goals.', 'prompt' => 'Create a now/next/later roadmap for [PRODUCT] with goals [GOALS].
Ideas and requests: [LIST]. Group into themes, place them in now/next/later with the outcome each targets, and note what we are deliberately not doing.', 'output' => 'A theme-based now/next/later roadmap with outcomes and an explicit not-doing list.', 'image' => '' ),
	array( 'id' => 'release-notes', 'title' => 'Release notes', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Release notes users actually read.', 'prompt' => 'Write release notes for version [X] of [PRODUCT].
Changes: [LIST]. Group into new, improved and fixed. Lead with the user benefit, not the technical change. Give an in-app short version and a longer blog version.', 'output' => 'Benefit-led release notes in a short in-app and a longer blog version.', 'image' => '' ),
	array( 'id' => 'design-critique', 'title' => 'Design critique', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Structured feedback on a UI design.', 'prompt' => 'Critique this design: [DESCRIBE OR PASTE SCREENSHOT DESCRIPTION]. Goal of the screen: [GOAL].
Review: visual hierarchy, clarity, consistency, accessibility (contrast, tap targets, labels), and mobile behaviour. Rank issues by severity with a concrete fix for each.', 'output' => 'Severity-ranked design feedback covering hierarchy, accessibility and mobile, with fixes.', 'image' => '' ),
	array( 'id' => 'accessibility-checklist-for-a-feature', 'title' => 'Accessibility checklist for a feature', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Make a feature usable for everyone.', 'prompt' => 'Create a WCAG 2.2 AA accessibility checklist for [FEATURE / PAGE].
Cover: keyboard access, focus order and visibility, labels and alt text, colour contrast, motion, forms and errors, screen reader announcements, and touch target size. Add how to test each item.', 'output' => 'A feature-specific WCAG 2.2 AA checklist with a testing method per item.', 'image' => '' ),
	array( 'id' => 'pricing-page-structure', 'title' => 'Pricing page structure', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A pricing page that helps people choose.', 'prompt' => 'Design the structure of a pricing page for [PRODUCT] with plans [PLANS AND PRICES].
Include: plan names and who each is for, feature comparison, recommended plan highlight, FAQ (6), trust elements and the CTA copy. No fake urgency.', 'output' => 'A clear pricing page layout with plan positioning, comparison, FAQ and honest CTAs.', 'image' => '' ),
	array( 'id' => 'jobs-to-be-done-statements', 'title' => 'Jobs-to-be-done statements', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Understand what customers are really hiring your product for.', 'prompt' => 'Write jobs-to-be-done statements for [PRODUCT]\'s users: [DESCRIBE USERS AND CONTEXT].
Format: When [situation], I want to [motivation], so I can [outcome]. Give 8, grouped into functional, emotional and social jobs, and what each implies for the product.', 'output' => 'Eight grouped JTBD statements with product implications.', 'image' => '' ),
	array( 'id' => 'customer-feedback-categoriser', 'title' => 'Customer feedback categoriser', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn raw feedback into prioritised themes.', 'prompt' => 'Categorise this customer feedback: [PASTE REVIEWS / TICKETS].
Themes with counts, sentiment, severity, example quotes and a suggested action per theme. Then the top 3 to address first and why.', 'output' => 'Feedback grouped into themes with counts, sentiment, quotes and prioritised actions.', 'image' => '' ),
	array( 'id' => 'wireframe-description', 'title' => 'Wireframe description', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A text wireframe you can sketch or give to a designer.', 'prompt' => 'Describe a wireframe for [SCREEN] in [APP] for [USER GOAL].
List sections top to bottom with components (header, cards, filters, buttons), their content, states (loading, empty, error), and mobile vs desktop differences.', 'output' => 'A top-to-bottom text wireframe with components, states and responsive notes.', 'image' => '' ),
	array( 'id' => 'experiment-design', 'title' => 'Experiment design', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Plan a product experiment that gives a clear answer.', 'prompt' => 'Design an experiment to test: [IDEA].
Give: hypothesis, primary metric and guardrail metrics, variants, audience and split, sample size or duration estimate, success threshold, risks, and what we do for each outcome.', 'output' => 'A complete experiment plan with metrics, guardrails, thresholds and decisions per outcome.', 'image' => '' ),
	array( 'id' => 'personas-for-a-digital-product', 'title' => 'Personas for a digital product', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Behaviour-based personas for design decisions.', 'prompt' => 'Create 3 behaviour-based personas for [PRODUCT] using: [RESEARCH NOTES].
Each: goals, frustrations, context of use, tech comfort, key quote (from notes only), and design implications. Flag anything not supported by the notes.', 'output' => 'Three research-grounded personas with design implications and unsupported points flagged.', 'image' => '' ),
	array( 'id' => 'stakeholder-update-on-a-product-decision', 'title' => 'Stakeholder update on a product decision', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Explain a decision and its trade-offs clearly.', 'prompt' => 'Write a stakeholder update explaining why we decided [DECISION].
Include: context, options considered, trade-offs, why this option, risks and mitigations, what changes for each team, and next steps. Under 300 words.', 'output' => 'A sub-300-word decision update with options, trade-offs and next steps.', 'image' => '' ),
	array( 'id' => 'empty-state-ideas', 'title' => 'Empty state ideas', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn blank screens into helpful first steps.', 'prompt' => 'Suggest empty states for these screens in [APP]: [LIST].
For each: illustration idea, headline, one line of guidance, primary action and an optional secondary action or sample data.', 'output' => 'Helpful empty states with copy and actions for each screen.', 'image' => '' ),
	array( 'id' => 'churn-reasons-analysis', 'title' => 'Churn reasons analysis', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Understand why users leave and what to try.', 'prompt' => 'Here is churn data and cancellation reasons: [PASTE].
Group reasons, estimate impact, separate fixable from unfixable, suggest 5 retention experiments and a short cancellation survey to improve the data.', 'output' => 'Grouped churn reasons, fixable vs unfixable, retention experiments and a better exit survey.', 'image' => '' ),
	array( 'id' => 'product-launch-checklist', 'title' => 'Product launch checklist', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Everything to check before shipping a feature.', 'prompt' => 'Create a launch checklist for [FEATURE]: product, engineering (monitoring, rollback), QA, design, docs, support training, marketing, legal/privacy, analytics events, and post-launch review. Assign suggested owners.', 'output' => 'A cross-team launch checklist with suggested owners and a post-launch review.', 'image' => '' ),
	array( 'id' => 'analytics-event-tracking-plan', 'title' => 'Analytics event tracking plan', 'cat' => 'Product & UX', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Decide what to track before you build.', 'prompt' => 'Create an analytics tracking plan for [FEATURE / APP].
Table: event name (object_action format), trigger, properties with types, which question it answers. Include funnel events and avoid collecting personal data that is not needed.', 'output' => 'An event table with consistent names, properties and the question each answers, privacy-minded.', 'image' => '' ),
	array( 'id' => 'write-a-system-prompt-for-an-ai-assistant', 'title' => 'Write a system prompt for an AI assistant', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'A clear, structured system prompt for a custom assistant.', 'prompt' => 'Write a system prompt for an AI assistant that [JOB, e.g. answers customer questions for my bakery].
Include: role and goal, audience, tone, what it knows (I will paste facts: [FACTS]), what it must never do, how to handle unknown answers (say so and offer a handover), output format, and 3 example exchanges.', 'output' => 'A structured system prompt with role, rules, fallback behaviour, format and examples.', 'image' => '' ),
	array( 'id' => 'design-an-ai-agent-workflow', 'title' => 'Design an AI agent workflow', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Plan the steps, tools and checks of an agent.', 'prompt' => 'Design an AI agent that [GOAL, e.g. researches leads and drafts outreach emails].
Give: inputs and outputs, step-by-step workflow, tools it needs (search, spreadsheet, email), where a human must approve, failure cases and how to handle them, and how to measure if it works.', 'output' => 'An agent blueprint with steps, tools, human approval points, failure handling and metrics.', 'image' => '' ),
	array( 'id' => 'zapier-or-make-automation-idea-to-steps', 'title' => 'Zapier or Make automation idea to steps', 'cat' => 'AI Agents & Automation', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn a repetitive task into an automation.', 'prompt' => 'I want to automate: [TASK, e.g. when a form is submitted, add the lead to a sheet, email them and notify me on Slack].
Tool: [ZAPIER / MAKE / N8N]. Give the trigger, each action step with field mapping, filters, error handling and how to test it safely.', 'output' => 'A trigger-to-action recipe with field mapping, filters, error handling and test steps.', 'image' => '' ),
	array( 'id' => 'prompt-improver', 'title' => 'Prompt improver', 'cat' => 'AI Agents & Automation', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Make any prompt clearer and more reliable.', 'prompt' => 'Improve this prompt so an AI gives better, more consistent results:
"""
[PASTE PROMPT]
"""
Check: clear goal, context, constraints, examples, output format, success criteria. Return the improved prompt and a list of what you changed and why.', 'output' => 'An improved prompt plus a change list explaining each improvement.', 'image' => '' ),
	array( 'id' => 'few-shot-examples-builder', 'title' => 'Few-shot examples builder', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Teach an AI a pattern with good examples.', 'prompt' => 'I need an AI to [TASK, e.g. classify support tickets into 5 categories].
Create a prompt with clear instructions, 6 varied few-shot examples (including tricky edge cases), and the output format (JSON with fields [FIELDS]).', 'output' => 'An instruction-plus-examples prompt with edge cases and a strict JSON output format.', 'image' => '' ),
	array( 'id' => 'evaluate-ai-outputs-with-a-rubric', 'title' => 'Evaluate AI outputs with a rubric', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Check AI answers for quality consistently.', 'prompt' => 'Create an evaluation rubric for AI answers to [TASK].
Criteria: accuracy, completeness, format, tone, safety. For each: 1–5 scale descriptions. Then write a grader prompt that applies the rubric and returns JSON scores with a one-line reason per criterion.', 'output' => 'A five-criterion rubric and a grader prompt that returns structured scores.', 'image' => '' ),
	array( 'id' => 'rag-chatbot-plan-for-your-documents', 'title' => 'RAG chatbot plan for your documents', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Plan a chatbot that answers from your own files.', 'prompt' => 'Plan a retrieval-augmented (RAG) chatbot that answers questions from [DOCUMENTS: PDFs, website, FAQs].
Cover: preparing and chunking documents, embeddings and a vector store, retrieval settings, the answer prompt (cite sources, say "I don\'t know" when not found), evaluation questions, and privacy considerations.', 'output' => 'A RAG plan from document prep to evaluation, with citation and don\'t-know behaviour.', 'image' => '' ),
	array( 'id' => 'structured-data-extraction-prompt', 'title' => 'Structured data extraction prompt', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'Pull fields from messy text into clean JSON.', 'prompt' => 'Write a prompt that extracts these fields from [DOCUMENT TYPE, e.g. invoices, resumes]: [FIELDS WITH TYPES].
Rules: return valid JSON only, null for missing fields, no guessing, normalised dates (YYYY-MM-DD). Include a JSON schema and 2 examples.', 'output' => 'An extraction prompt with a JSON schema, strict rules and examples.', 'image' => '' ),
	array( 'id' => 'customer-support-bot-guardrails', 'title' => 'Customer support bot guardrails', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Rules that keep a support bot safe and helpful.', 'prompt' => 'Write guardrails for a customer support AI for [BUSINESS].
Include: topics it can and cannot discuss, how to handle refunds or account changes (always hand over to a human), privacy rules (never ask for passwords or full card numbers), escalation triggers, and polite refusal templates.', 'output' => 'Clear guardrails with handover rules, privacy limits, escalation triggers and refusal templates.', 'image' => '' ),
	array( 'id' => 'automate-a-weekly-report', 'title' => 'Automate a weekly report', 'cat' => 'AI Agents & Automation', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Plan an automation that builds and sends a report.', 'prompt' => 'I make a weekly report from [DATA SOURCES] for [AUDIENCE]. Plan an automation: data pull, cleaning, summary generation with an AI step (prompt included), formatting, review step, and sending. Suggest tools for a non-coder and for a developer.', 'output' => 'An end-to-end report automation with an AI summary prompt and tool options for both skill levels.', 'image' => '' ),
	array( 'id' => 'claude-code-or-cursor-task-brief', 'title' => 'Claude Code or Cursor task brief', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'A brief that gets a coding agent to do the right thing.', 'prompt' => 'Write a task brief for an AI coding agent working in my repository.
Task: [WHAT]. Context: [STACK, KEY FILES, CONVENTIONS]. Constraints: [DON\'T TOUCH X, KEEP API STABLE]. Definition of done: [TESTS PASS, LINT CLEAN, DOCS UPDATED].
Ask it to plan first, then implement in small steps and run the tests.', 'output' => 'A focused agent brief with context, constraints and a clear definition of done.', 'image' => '' ),
	array( 'id' => 'n8n-workflow-design', 'title' => 'n8n workflow design', 'cat' => 'AI Agents & Automation', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Plan a self-hosted automation workflow.', 'prompt' => 'Design an n8n workflow that [GOAL].
List nodes in order (trigger, HTTP request, function, IF, AI node, output), the data passed between them, credentials needed (stored in n8n\'s credential manager, never in plain text), error workflow and a test plan.', 'output' => 'A node-by-node n8n design with data flow, credential handling, error workflow and tests.', 'image' => '' ),
	array( 'id' => 'chain-prompts-for-a-long-task', 'title' => 'Chain prompts for a long task', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Split a complex job into reliable prompt steps.', 'prompt' => 'I want an AI to [COMPLEX TASK, e.g. turn a long report into a slide deck].
Design a prompt chain: each step\'s prompt, its input and output format, checks between steps, and where a human should review. Keep each step simple and testable.', 'output' => 'A multi-step prompt chain with formats, checks and human review points.', 'image' => '' ),
	array( 'id' => 'ai-use-policy-for-a-team', 'title' => 'AI use policy for a team', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'Simple rules for using AI tools safely at work.', 'prompt' => 'Write an AI usage policy for a [SIZE] team in [INDUSTRY].
Cover: approved tools, data that must never be pasted (customer personal data, secrets, confidential code), reviewing outputs, disclosure, copyright, and how to report problems. One page, plain English.', 'output' => 'A one-page AI policy covering approved tools, data rules, review and disclosure.', 'image' => '' ),
	array( 'id' => 'function-calling-tool-definitions', 'title' => 'Function calling tool definitions', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Describe tools so an AI model calls them correctly.', 'prompt' => 'Write tool (function) definitions for an AI assistant that can: [LIST ACTIONS, e.g. check order status, book appointment].
For each: name, clear description of when to use it, JSON schema parameters with types and required fields, and an example call. Add instructions for confirming before actions that change data.', 'output' => 'Clear tool definitions with JSON schemas, examples and confirmation rules.', 'image' => '' ),
	array( 'id' => 'email-triage-assistant', 'title' => 'Email triage assistant', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'An AI step that sorts and drafts replies to emails.', 'prompt' => 'Write a prompt for an AI that triages incoming emails for [ROLE].
Output JSON: category ([LIST]), urgency (high/medium/low), one-line summary, needs_reply (true/false), and a draft reply in my tone for routine emails. It must never send anything without my approval.', 'output' => 'A triage prompt that returns structured categories, urgency, summaries and draft replies.', 'image' => '' ),
	array( 'id' => 'meeting-notes-to-tasks-automation', 'title' => 'Meeting notes to tasks automation', 'cat' => 'AI Agents & Automation', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'From transcript to tasks in your task app.', 'prompt' => 'Design an automation: meeting recording → transcript → AI summary and action items → tasks in [TODOIST / ASANA / NOTION].
Give the AI prompt (output JSON: task, owner, due date, context), tool steps, and a review step before tasks are created.', 'output' => 'An automation recipe with a structured AI prompt and a human review step.', 'image' => '' ),
	array( 'id' => 'agent-memory-and-context-plan', 'title' => 'Agent memory and context plan', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'text', 'premium' => true, 'summary' => 'Decide what an agent should remember.', 'prompt' => 'My AI assistant helps users with [TASK] over many sessions. Plan its memory: what to store (preferences, facts, history), what never to store (sensitive data), how to summarise long histories, how users can view and delete memory, and how to keep context small.', 'output' => 'A memory design covering what to keep, what to avoid, summarisation and user control.', 'image' => '' ),
	array( 'id' => 'test-cases-for-an-ai-feature', 'title' => 'Test cases for an AI feature', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Find where your AI feature breaks before users do.', 'prompt' => 'Write test cases for an AI feature that [DOES WHAT].
Include: 10 normal cases, 10 edge cases (empty, very long, other languages, typos), 5 adversarial inputs (prompt injection attempts, off-topic requests) with the expected safe behaviour, and pass/fail criteria.', 'output' => 'Normal, edge and adversarial test cases with expected behaviour and pass criteria.', 'image' => '' ),
	array( 'id' => 'prompt-injection-defence-checklist', 'title' => 'Prompt injection defence checklist', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Protect an AI app that reads untrusted text.', 'prompt' => 'My AI app reads untrusted content such as [EMAILS / WEB PAGES / USER UPLOADS] and can [ACTIONS].
Give a defence checklist: separating instructions from data, least-privilege tools, human confirmation for risky actions, output filtering, logging, and test prompts to try. Explain each in one line.', 'output' => 'A practical checklist for reducing prompt-injection risk, with test prompts to try.', 'image' => '' ),
	array( 'id' => 'personal-ai-assistant-setup', 'title' => 'Personal AI assistant setup', 'cat' => 'AI Agents & Automation', 'tool' => 'ChatGPT', 'kind' => 'text', 'premium' => true, 'summary' => 'Set up custom instructions for everyday help.', 'prompt' => 'Write custom instructions for my AI assistant.
About me: [ROLE, GOALS, PREFERENCES]. How I want answers: [LENGTH, FORMAT, TONE, LANGUAGE]. Things to always do: [LIST]. Things to avoid: [LIST]. Keep it within about 1,500 characters.', 'output' => 'Compact custom instructions describing you and how you want answers.', 'image' => '' ),
	array( 'id' => 'workflow-audit-for-ai-opportunities', 'title' => 'Workflow audit for AI opportunities', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Find which tasks AI can actually speed up.', 'prompt' => 'Here are my team\'s weekly tasks with time spent: [LIST].
For each: can AI help (yes/partly/no), how, the risk level, a tool type, and estimated hours saved. Rank the top 5 opportunities and suggest a 2-week pilot for the best one.', 'output' => 'A task-by-task AI opportunity assessment with risks, savings and a pilot plan.', 'image' => '' ),
	array( 'id' => 'multi-agent-role-design', 'title' => 'Multi-agent role design', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Split a big job between specialist agents.', 'prompt' => 'Design a multi-agent setup for [GOAL, e.g. producing a weekly market report].
Define 3–5 agents (role, inputs, outputs, tools), the orchestrator\'s logic, handoff formats, quality checks, and when a human reviews. Keep it as simple as possible and say if one agent would be enough.', 'output' => 'A lean multi-agent design with roles, handoffs, checks and an honest simplicity check.', 'image' => '' ),
	array( 'id' => 'chatbot-conversation-flow', 'title' => 'Chatbot conversation flow', 'cat' => 'AI Agents & Automation', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Map a bot conversation for a common task.', 'prompt' => 'Map a chatbot conversation for [TASK, e.g. booking a table].
Give: greeting, questions in order, validation of each answer, confirmation step, handling of changes and cancellations, fallback when the bot does not understand, and handover to a human.', 'output' => 'A complete conversation flow with validation, confirmation, fallbacks and handover.', 'image' => '' ),
	array( 'id' => 'summarise-and-act-on-a-long-document', 'title' => 'Summarise and act on a long document', 'cat' => 'AI Agents & Automation', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'An AI workflow for reports, contracts or research.', 'prompt' => 'Read the document below and give: a 5-line executive summary, key facts table, decisions or actions required, risks, open questions, and quotes supporting each key point. Say clearly if something is not in the document.
"""
[PASTE DOCUMENT]
"""', 'output' => 'A grounded summary with facts, actions, risks and supporting quotes, never inventing content.', 'image' => '' ),
	array( 'id' => 'weekend-getaway-itinerary', 'title' => 'Weekend getaway itinerary', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A day-by-day weekend getaway plan with timings, budget and tips.', 'prompt' => 'Plan a weekend getaway to [DESTINATION] for [DAYS] days in [MONTH].
Travellers: [WHO]. Budget: [AMOUNT] total. Interests: [LIST]. Pace: [RELAXED / PACKED].
Give a day-by-day plan (morning, afternoon, evening) with travel times, a budget table, where to stay (areas, not specific bookings), local food to try, packing list, and safety tips. Remind me to check opening hours and travel rules before booking.', 'output' => 'A day-by-day itinerary with timings, a budget table, area suggestions, food, packing and safety tips.', 'image' => '' ),
	array( 'id' => 'family-holiday-with-kids-itinerary', 'title' => 'Family holiday with kids itinerary', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A day-by-day family holiday with kids plan with timings, budget and tips.', 'prompt' => 'Plan a family holiday with kids to [DESTINATION] for [DAYS] days in [MONTH].
Travellers: [WHO]. Budget: [AMOUNT] total. Interests: [LIST]. Pace: [RELAXED / PACKED].
Give a day-by-day plan (morning, afternoon, evening) with travel times, a budget table, where to stay (areas, not specific bookings), local food to try, packing list, and safety tips. Remind me to check opening hours and travel rules before booking.', 'output' => 'A day-by-day itinerary with timings, a budget table, area suggestions, food, packing and safety tips.', 'image' => '' ),
	array( 'id' => 'solo-backpacking-trip-itinerary', 'title' => 'Solo backpacking trip itinerary', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A day-by-day solo backpacking trip plan with timings, budget and tips.', 'prompt' => 'Plan a solo backpacking trip to [DESTINATION] for [DAYS] days in [MONTH].
Travellers: [WHO]. Budget: [AMOUNT] total. Interests: [LIST]. Pace: [RELAXED / PACKED].
Give a day-by-day plan (morning, afternoon, evening) with travel times, a budget table, where to stay (areas, not specific bookings), local food to try, packing list, and safety tips. Remind me to check opening hours and travel rules before booking.', 'output' => 'A day-by-day itinerary with timings, a budget table, area suggestions, food, packing and safety tips.', 'image' => '' ),
	array( 'id' => 'honeymoon-itinerary', 'title' => 'Honeymoon itinerary', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A day-by-day honeymoon plan with timings, budget and tips.', 'prompt' => 'Plan a honeymoon to [DESTINATION] for [DAYS] days in [MONTH].
Travellers: [WHO]. Budget: [AMOUNT] total. Interests: [LIST]. Pace: [RELAXED / PACKED].
Give a day-by-day plan (morning, afternoon, evening) with travel times, a budget table, where to stay (areas, not specific bookings), local food to try, packing list, and safety tips. Remind me to check opening hours and travel rules before booking.', 'output' => 'A day-by-day itinerary with timings, a budget table, area suggestions, food, packing and safety tips.', 'image' => '' ),
	array( 'id' => 'budget-trip-itinerary', 'title' => 'Budget trip itinerary', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A day-by-day budget trip plan with timings, budget and tips.', 'prompt' => 'Plan a budget trip to [DESTINATION] for [DAYS] days in [MONTH].
Travellers: [WHO]. Budget: [AMOUNT] total. Interests: [LIST]. Pace: [RELAXED / PACKED].
Give a day-by-day plan (morning, afternoon, evening) with travel times, a budget table, where to stay (areas, not specific bookings), local food to try, packing list, and safety tips. Remind me to check opening hours and travel rules before booking.', 'output' => 'A day-by-day itinerary with timings, a budget table, area suggestions, food, packing and safety tips.', 'image' => '' ),
	array( 'id' => 'road-trip-itinerary', 'title' => 'Road trip itinerary', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A day-by-day road trip plan with timings, budget and tips.', 'prompt' => 'Plan a road trip to [DESTINATION] for [DAYS] days in [MONTH].
Travellers: [WHO]. Budget: [AMOUNT] total. Interests: [LIST]. Pace: [RELAXED / PACKED].
Give a day-by-day plan (morning, afternoon, evening) with travel times, a budget table, where to stay (areas, not specific bookings), local food to try, packing list, and safety tips. Remind me to check opening hours and travel rules before booking.', 'output' => 'A day-by-day itinerary with timings, a budget table, area suggestions, food, packing and safety tips.', 'image' => '' ),
	array( 'id' => 'hill-station-trip-itinerary', 'title' => 'Hill station trip itinerary', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A day-by-day hill station trip plan with timings, budget and tips.', 'prompt' => 'Plan a hill station trip to [DESTINATION] for [DAYS] days in [MONTH].
Travellers: [WHO]. Budget: [AMOUNT] total. Interests: [LIST]. Pace: [RELAXED / PACKED].
Give a day-by-day plan (morning, afternoon, evening) with travel times, a budget table, where to stay (areas, not specific bookings), local food to try, packing list, and safety tips. Remind me to check opening hours and travel rules before booking.', 'output' => 'A day-by-day itinerary with timings, a budget table, area suggestions, food, packing and safety tips.', 'image' => '' ),
	array( 'id' => 'beach-holiday-itinerary', 'title' => 'Beach holiday itinerary', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A day-by-day beach holiday plan with timings, budget and tips.', 'prompt' => 'Plan a beach holiday to [DESTINATION] for [DAYS] days in [MONTH].
Travellers: [WHO]. Budget: [AMOUNT] total. Interests: [LIST]. Pace: [RELAXED / PACKED].
Give a day-by-day plan (morning, afternoon, evening) with travel times, a budget table, where to stay (areas, not specific bookings), local food to try, packing list, and safety tips. Remind me to check opening hours and travel rules before booking.', 'output' => 'A day-by-day itinerary with timings, a budget table, area suggestions, food, packing and safety tips.', 'image' => '' ),
	array( 'id' => 'international-first-trip-itinerary', 'title' => 'International first trip itinerary', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'A day-by-day international first trip plan with timings, budget and tips.', 'prompt' => 'Plan a international first trip to [DESTINATION] for [DAYS] days in [MONTH].
Travellers: [WHO]. Budget: [AMOUNT] total. Interests: [LIST]. Pace: [RELAXED / PACKED].
Give a day-by-day plan (morning, afternoon, evening) with travel times, a budget table, where to stay (areas, not specific bookings), local food to try, packing list, and safety tips. Remind me to check opening hours and travel rules before booking.', 'output' => 'A day-by-day itinerary with timings, a budget table, area suggestions, food, packing and safety tips.', 'image' => '' ),
	array( 'id' => 'pilgrimage-and-heritage-trip-itinerary', 'title' => 'Pilgrimage and heritage trip itinerary', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A day-by-day pilgrimage and heritage trip plan with timings, budget and tips.', 'prompt' => 'Plan a pilgrimage and heritage trip to [DESTINATION] for [DAYS] days in [MONTH].
Travellers: [WHO]. Budget: [AMOUNT] total. Interests: [LIST]. Pace: [RELAXED / PACKED].
Give a day-by-day plan (morning, afternoon, evening) with travel times, a budget table, where to stay (areas, not specific bookings), local food to try, packing list, and safety tips. Remind me to check opening hours and travel rules before booking.', 'output' => 'A day-by-day itinerary with timings, a budget table, area suggestions, food, packing and safety tips.', 'image' => '' ),
	array( 'id' => 'packing-list-generator', 'title' => 'Packing list generator', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A complete packing list for any trip.', 'prompt' => 'Make a packing list for [DAYS] days in [DESTINATION] in [MONTH] for [ACTIVITIES]. Traveller: [WHO]. Luggage: [CABIN ONLY / CHECKED].
Group by clothes, toiletries, documents, electronics, health, extras. Mark essentials and items people forget.', 'output' => 'A grouped packing list with essentials and commonly forgotten items marked.', 'image' => '' ),
	array( 'id' => 'visa-and-documents-checklist', 'title' => 'Visa and documents checklist', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'What to prepare before an international trip.', 'prompt' => 'I am a [NATIONALITY] citizen travelling to [COUNTRY] for [PURPOSE] for [DAYS] days.
Give a general checklist of documents usually needed (passport validity, visa type, insurance, bookings, finances), and tell me to confirm current rules on the official embassy or government website, since requirements change.', 'output' => 'A general document checklist with a clear pointer to official sources for current rules.', 'image' => '' ),
	array( 'id' => 'move-to-a-new-city-checklist', 'title' => 'Move to a new city checklist', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Settle in smoothly after relocating.', 'prompt' => 'I am moving from [CITY] to [CITY] for [REASON] in [WEEKS] weeks. Create a checklist: before the move, moving week, first week, first month (address updates, bank, utilities, local registration if needed, healthcare, community). Add a budget table template.', 'output' => 'A phased relocation checklist and a budget template.', 'image' => '' ),
	array( 'id' => 'plan-a-party-or-event', 'title' => 'Plan a party or event', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A stress-free plan for a celebration.', 'prompt' => 'Plan a [EVENT: birthday, anniversary, farewell] for [NUMBER] guests on [DATE], budget [AMOUNT], venue [HOME / HALL / OUTDOOR].
Give: theme ideas, timeline from 4 weeks out, guest list template, menu, decorations, activities, and a day-of schedule.', 'output' => 'Theme ideas, a four-week timeline, menu, activities and a day-of schedule.', 'image' => '' ),
	array( 'id' => 'cook-with-what-is-in-the-fridge', 'title' => 'Cook with what is in the fridge', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Recipes from ingredients you already have.', 'prompt' => 'I have: [INGREDIENTS]. Kitchen tools: [LIST]. Time: [MINUTES]. Diet: [PREFERENCES].
Suggest 3 recipes, each with ingredients (mark anything extra I would need), step-by-step instructions, and one tip.', 'output' => 'Three recipes from your ingredients with steps and any extras clearly marked.', 'image' => '' ),
	array( 'id' => 'gift-ideas', 'title' => 'Gift ideas', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Thoughtful gift ideas for anyone and any budget.', 'prompt' => 'Suggest 15 gift ideas for [PERSON: age, relationship, interests] for [OCCASION], budget [AMOUNT].
Mix: experiences, practical, personalised, DIY. For each: why it suits them and a rough price range.', 'output' => 'Fifteen varied gift ideas with reasons and price ranges.', 'image' => '' ),
	array( 'id' => 'home-organisation-plan', 'title' => 'Home organisation plan', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Declutter and organise one room at a time.', 'prompt' => 'Create a declutter and organisation plan for my [ROOM / HOME]. Problems: [DESCRIBE]. Time: [HOURS PER WEEK].
Give a 4-week room-by-room plan, a keep/donate/sell/discard rule set, storage ideas and a 10-minute daily reset routine.', 'output' => 'A four-week plan with sorting rules, storage ideas and a daily reset routine.', 'image' => '' ),
	array( 'id' => 'write-a-heartfelt-message', 'title' => 'Write a heartfelt message', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Words for birthdays, condolences, thanks and more.', 'prompt' => 'Write a [BIRTHDAY / CONDOLENCE / THANK-YOU / CONGRATULATIONS / FAREWELL] message for [PERSON], my [RELATIONSHIP].
Details: [MEMORIES, QUALITIES]. Tone: [WARM / FUNNY / FORMAL]. Give a short card version and a longer version.', 'output' => 'A short card message and a longer heartfelt version using your details.', 'image' => '' ),
	array( 'id' => 'learn-a-language-for-travel', 'title' => 'Learn a language for travel', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Survival phrases and a 2-week practice plan.', 'prompt' => 'I am travelling to [COUNTRY] in [WEEKS] weeks and want basic [LANGUAGE].
Give 40 survival phrases (greetings, directions, food, shopping, emergencies) with pronunciation guides, and a 15-minute daily practice plan.', 'output' => 'Forty essential phrases with pronunciation and a short daily practice plan.', 'image' => '' ),
	array( 'id' => 'weekly-family-meal-and-chores-planner', 'title' => 'Weekly family meal and chores planner', 'cat' => 'Travel & Life', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Share meals and chores fairly at home.', 'prompt' => 'Create a weekly plan for a family of [NUMBER] with [AGES]. Include a dinner plan (budget [AMOUNT], prep time under [MINUTES]), a grocery list, and a fair chore chart by age with a simple reward idea.', 'output' => 'A weekly dinner plan, grocery list and age-appropriate chore chart.', 'image' => '' ),
	array( 'id' => 'youtube-video-ideas-for-your-niche', 'title' => 'YouTube video ideas for your niche', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Fifty searchable video ideas grouped by type.', 'prompt' => 'Give me 50 YouTube video ideas for a channel about [NICHE] for [AUDIENCE].
Group into: search-based (how to, best X), trending, evergreen, story-based, comparison and series ideas. Mark the 10 easiest to make first.', 'output' => 'Fifty grouped ideas with the ten easiest first picks marked.', 'image' => '' ),
	array( 'id' => 'youtube-script-with-retention-hooks', 'title' => 'YouTube script with retention hooks', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A full script built to keep viewers watching.', 'prompt' => 'Write a [MINUTES]-minute YouTube script about [TOPIC] for [AUDIENCE].
Structure: hook in the first 15 seconds, quick promise, intro, 3–5 sections with pattern interrupts and open loops, a mid-video call to action, a payoff, and an end screen line. Mark B-roll ideas in [brackets].', 'output' => 'A full script with a strong hook, open loops, B-roll notes and calls to action.', 'image' => '' ),
	array( 'id' => 'youtube-titles-and-thumbnails', 'title' => 'YouTube titles and thumbnails', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Clickable but honest titles and thumbnail concepts.', 'prompt' => 'My video: [DESCRIBE]. Give 10 titles under 60 characters (curiosity, benefit, number, question styles) and 5 thumbnail concepts (main image, 3–4 words of text, emotion, colours). The title and thumbnail must match the video\'s real content.', 'output' => 'Ten honest titles under 60 characters and five thumbnail concepts that match the content.', 'image' => '' ),
	array( 'id' => 'youtube-description-and-chapters', 'title' => 'YouTube description and chapters', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'An SEO description with timestamps.', 'prompt' => 'Write a YouTube description for my video [TITLE] about [TOPIC]. Transcript or outline: [PASTE].
Include: first 2 lines with the main keyword, summary, chapters with timestamps (first at 0:00), links section placeholders, and 3–5 relevant hashtags.', 'output' => 'A keyword-first description with valid chapters starting at 0:00, links and hashtags.', 'image' => '' ),
	array( 'id' => 'channel-strategy-for-the-first-90-days', 'title' => 'Channel strategy for the first 90 days', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Plan your first 90 days on YouTube.', 'prompt' => 'I am starting a YouTube channel about [NICHE]. I can make [N] videos a week, equipment: [LIST].
Give a 90-day plan: positioning, content pillars, upload schedule, first 12 video ideas, channel setup checklist, and metrics to watch (CTR, average view duration, returning viewers).', 'output' => 'A 90-day launch plan with pillars, first videos, setup checklist and key metrics.', 'image' => '' ),
	array( 'id' => 'shorts-from-a-long-video', 'title' => 'Shorts from a long video', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Find the best moments to cut into Shorts.', 'prompt' => 'Here is the transcript of my long video with timestamps: [PASTE].
Find 5 moments that work as standalone Shorts. For each: start and end timestamps, the hook line, on-screen text, and a title.', 'output' => 'Five clip suggestions with timestamps, hooks, captions and titles.', 'image' => '' ),
	array( 'id' => 'sponsorship-pitch-to-a-brand', 'title' => 'Sponsorship pitch to a brand', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Pitch brands with a professional media kit summary.', 'prompt' => 'Write a sponsorship pitch email to [BRAND] from my channel about [NICHE].
My stats: [SUBSCRIBERS, AVERAGE VIEWS, AUDIENCE DEMOGRAPHICS]. Include: why my audience fits, integration ideas, deliverables, rates [OR \'ask for their budget\'], and a short media-kit summary.', 'output' => 'A professional pitch with audience fit, integration ideas and a media-kit summary.', 'image' => '' ),
	array( 'id' => 'analyse-channel-analytics', 'title' => 'Analyse channel analytics', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Turn YouTube Studio numbers into actions.', 'prompt' => 'Here are my last 10 videos with views, CTR, average view duration and subscribers gained: [PASTE TABLE].
Find patterns in what works, weak points (packaging vs content), and give 5 specific actions for the next 5 videos.', 'output' => 'Patterns, packaging vs content diagnosis and five specific next actions.', 'image' => '' ),
	array( 'id' => 'podcast-to-youtube-plan', 'title' => 'Podcast to YouTube plan', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Turn a podcast into YouTube content.', 'prompt' => 'I have an audio podcast about [TOPIC]. Plan how to bring it to YouTube: full episodes vs clips, visual style, thumbnails, titles that work for search, and a weekly workflow.', 'output' => 'A plan for full episodes and clips with visuals, packaging and a weekly workflow.', 'image' => '' ),
	array( 'id' => 'community-post-ideas', 'title' => 'Community post ideas', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Engage subscribers between uploads.', 'prompt' => 'Give 20 YouTube community post ideas for [NICHE]: polls, quizzes, behind the scenes, questions, teasers. Write the text for 5 of them.', 'output' => 'Twenty post ideas with ready text for five.', 'image' => '' ),
	array( 'id' => 'video-hook-rewrite', 'title' => 'Video hook rewrite', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Make the first 15 seconds impossible to skip.', 'prompt' => 'Here is the opening of my video: [PASTE FIRST 30 SECONDS]. Rewrite it 5 ways: question, bold claim, story, result-first, problem. Under 40 words each, and keep the promise honest.', 'output' => 'Five rewritten openings under 40 words, each with an honest promise.', 'image' => '' ),
	array( 'id' => 'collaboration-pitch-to-another-creator', 'title' => 'Collaboration pitch to another creator', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Propose a collab that helps both channels.', 'prompt' => 'Write a collaboration pitch to [CREATOR] whose channel is about [THEIR NICHE]. My channel: [MY NICHE AND SIZE].
Propose 3 collab ideas that benefit both audiences, a simple plan, and a short, friendly message.', 'output' => 'A friendly pitch with three mutually beneficial collab ideas.', 'image' => '' ),
	array( 'id' => 'content-batch-production-plan', 'title' => 'Content batch production plan', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Film a month of content in two days.', 'prompt' => 'Plan a batch production schedule to record [N] videos in [DAYS] days. Topics: [LIST].
Give: scripting order, outfit and set changes, shot list per video, equipment checklist, and an editing pipeline.', 'output' => 'A batch filming schedule with shot lists, set changes and an editing pipeline.', 'image' => '' ),
	array( 'id' => 'live-stream-plan', 'title' => 'Live stream plan', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Plan an engaging live stream.', 'prompt' => 'Plan a [MINUTES]-minute live stream about [TOPIC] for my [PLATFORM] audience.
Include: title and thumbnail idea, run of show with timings, interaction moments (polls, Q&A), moderation rules, and a post-stream clip plan.', 'output' => 'A timed run of show with interaction points, moderation rules and clip plan.', 'image' => '' ),
	array( 'id' => 'creator-media-kit', 'title' => 'Creator media kit', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A one-page media kit for brand deals.', 'prompt' => 'Create a one-page media kit for my creator brand. Details: [NAME, NICHE, PLATFORMS, STATS, AUDIENCE, PAST BRANDS, CONTACT].
Sections: about, audience, numbers, content examples, services and packages, contact. Short and confident.', 'output' => 'A one-page media kit layout with all sections filled from your details.', 'image' => '' ),
	array( 'id' => 'instagram-reel-to-youtube-short-adaptation', 'title' => 'Instagram Reel to YouTube Short adaptation', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Adapt short videos for each platform.', 'prompt' => 'I have this Reel script: [PASTE]. Adapt it for YouTube Shorts: hook tweaks, on-screen text, title, description and what to change in pacing. Explain the differences briefly.', 'output' => 'An adapted Shorts version with platform-specific tweaks.', 'image' => '' ),
	array( 'id' => 'comment-reply-templates-for-creators', 'title' => 'Comment reply templates for creators', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Reply faster while staying personal.', 'prompt' => 'Write 15 reply templates for comments on my [NICHE] videos: praise, questions, requests, criticism, spam-like, and trolls. Keep them personal with [PLACEHOLDERS] and suggest which comments to pin.', 'output' => 'Fifteen personalisable replies and guidance on pinning.', 'image' => '' ),
	array( 'id' => 'newsletter-for-creators', 'title' => 'Newsletter for creators', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Grow an email list from your audience.', 'prompt' => 'Plan a weekly newsletter for my audience about [NICHE]. Give: name ideas, sections, a lead magnet to grow sign-ups, a CTA line for videos, and the first issue draft.', 'output' => 'Newsletter structure, sign-up ideas and a first issue.', 'image' => '' ),
	array( 'id' => 'course-or-digital-product-from-your-content', 'title' => 'Course or digital product from your content', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Turn your expertise into a paid product.', 'prompt' => 'My audience asks most about [TOPIC]. Plan a digital product (course, template pack or e-book): outline, price range suggestion with reasoning, launch plan to my audience, and how to validate demand before building.', 'output' => 'A product outline, pricing logic, launch plan and a validation step before you build.', 'image' => '' ),
	array( 'id' => 'faceless-channel-plan', 'title' => 'Faceless channel plan', 'cat' => 'YouTube & Creators', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Start a channel without showing your face.', 'prompt' => 'Plan a faceless YouTube channel about [NICHE]. Include: content format, visuals (stock, screen recording, animation), voice-over approach, tools, a script template, and how to stay original and avoid reused-content problems.', 'output' => 'A faceless channel plan with formats, tools, a script template and originality tips.', 'image' => '' ),
	array( 'id' => 'build-a-saas-landing-page-with-ai', 'title' => 'Build a SaaS landing page with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A detailed prompt to generate a SaaS landing page in an AI website builder.', 'prompt' => 'Build a responsive SaaS landing page for [BRAND] in [NICHE].
Sections: hero, logos, features, how it works, pricing, testimonials, FAQ, footer.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-portfolio-for-a-designer-with-ai', 'title' => 'Build a portfolio for a designer with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A detailed prompt to generate a portfolio for a designer in an AI website builder.', 'prompt' => 'Build a responsive portfolio for a designer for [BRAND] in [NICHE].
Sections: intro, selected work grid, case study pages, about, contact.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-restaurant-website-with-ai', 'title' => 'Build a restaurant website with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'A detailed prompt to generate a restaurant website in an AI website builder.', 'prompt' => 'Build a responsive restaurant website for [BRAND] in [NICHE].
Sections: hero with booking button, menu, gallery, location map placeholder, reviews, hours.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-online-course-landing-page-with-ai', 'title' => 'Build a online course landing page with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A detailed prompt to generate a online course landing page in an AI website builder.', 'prompt' => 'Build a responsive online course landing page for [BRAND] in [NICHE].
Sections: promise, curriculum, instructor, outcomes, pricing, FAQ, guarantee.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-personal-blog-with-ai', 'title' => 'Build a personal blog with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A detailed prompt to generate a personal blog in an AI website builder.', 'prompt' => 'Build a responsive personal blog for [BRAND] in [NICHE].
Sections: home feed, post page, categories, about, newsletter sign-up.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-e-commerce-product-page-with-ai', 'title' => 'Build a e-commerce product page with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'A detailed prompt to generate a e-commerce product page in an AI website builder.', 'prompt' => 'Build a responsive e-commerce product page for [BRAND] in [NICHE].
Sections: gallery, title, price, variants, add to cart, details tabs, reviews, related products.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-real-estate-listings-site-with-ai', 'title' => 'Build a real estate listings site with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A detailed prompt to generate a real estate listings site in an AI website builder.', 'prompt' => 'Build a responsive real estate listings site for [BRAND] in [NICHE].
Sections: search filters, listing cards, property detail page, agent contact.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-gym-website-with-ai', 'title' => 'Build a gym website with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A detailed prompt to generate a gym website in an AI website builder.', 'prompt' => 'Build a responsive gym website for [BRAND] in [NICHE].
Sections: hero, classes timetable, trainers, membership plans, free trial form.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-event-or-conference-site-with-ai', 'title' => 'Build a event or conference site with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'A detailed prompt to generate a event or conference site in an AI website builder.', 'prompt' => 'Build a responsive event or conference site for [BRAND] in [NICHE].
Sections: hero with date, speakers, schedule, venue, tickets, sponsors.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-nonprofit-donation-site-with-ai', 'title' => 'Build a nonprofit donation site with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A detailed prompt to generate a nonprofit donation site in an AI website builder.', 'prompt' => 'Build a responsive nonprofit donation site for [BRAND] in [NICHE].
Sections: mission, impact numbers, stories, donate form with amounts, volunteer.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-mobile-app-landing-page-with-ai', 'title' => 'Build a mobile app landing page with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A detailed prompt to generate a mobile app landing page in an AI website builder.', 'prompt' => 'Build a responsive mobile app landing page for [BRAND] in [NICHE].
Sections: hero with phone mockup, features, screenshots carousel, store badges, FAQ.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-doctor-or-clinic-site-with-ai', 'title' => 'Build a doctor or clinic site with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A detailed prompt to generate a doctor or clinic site in an AI website builder.', 'prompt' => 'Build a responsive doctor or clinic site for [BRAND] in [NICHE].
Sections: services, doctors, appointment form, insurance info, contact.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-photography-portfolio-with-ai', 'title' => 'Build a photography portfolio with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'A detailed prompt to generate a photography portfolio in an AI website builder.', 'prompt' => 'Build a responsive photography portfolio for [BRAND] in [NICHE].
Sections: full-bleed gallery, categories, packages, booking enquiry.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-agency-website-with-ai', 'title' => 'Build a agency website with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A detailed prompt to generate a agency website in an AI website builder.', 'prompt' => 'Build a responsive agency website for [BRAND] in [NICHE].
Sections: hero, services, case studies, process, team, contact.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-wedding-website-with-ai', 'title' => 'Build a wedding website with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A detailed prompt to generate a wedding website in an AI website builder.', 'prompt' => 'Build a responsive wedding website for [BRAND] in [NICHE].
Sections: couple story, event details, RSVP form, gallery, travel info.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-job-board-with-ai', 'title' => 'Build a job board with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'A detailed prompt to generate a job board in an AI website builder.', 'prompt' => 'Build a responsive job board for [BRAND] in [NICHE].
Sections: search, filters, job cards, job detail, post a job form.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-admin-dashboard-with-ai', 'title' => 'Build a admin dashboard with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A detailed prompt to generate a admin dashboard in an AI website builder.', 'prompt' => 'Build a responsive admin dashboard for [BRAND] in [NICHE].
Sections: sidebar, KPI cards, charts, data table with filters, settings.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-coming-soon-page-with-ai', 'title' => 'Build a coming soon page with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A detailed prompt to generate a coming soon page in an AI website builder.', 'prompt' => 'Build a responsive coming soon page for [BRAND] in [NICHE].
Sections: headline, email capture, countdown to a real date, social links.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-resume-cv-website-with-ai', 'title' => 'Build a resume / CV website with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => true, 'summary' => 'A detailed prompt to generate a resume / CV website in an AI website builder.', 'prompt' => 'Build a responsive resume / CV website for [BRAND] in [NICHE].
Sections: header, experience timeline, skills, projects, contact.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'build-a-local-business-site-with-ai', 'title' => 'Build a local business site with AI', 'cat' => 'Website Builders', 'tool' => 'Lovable / v0 / Bolt / Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'A detailed prompt to generate a local business site in an AI website builder.', 'prompt' => 'Build a responsive local business site for [BRAND] in [NICHE].
Sections: hero, services, reviews, service area, contact and WhatsApp button.
Design: [STYLE, e.g. clean and modern], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.
Content: use this copy [PASTE] or realistic placeholders (no lorem ipsum).
Tech: [REACT + TAILWIND / PLAIN HTML-CSS-JS]; accessible (semantic HTML, alt text, keyboard focus, AA contrast); mobile-first; fast (lazy-load images).
After building, list what I should customise and any placeholder to replace.', 'output' => 'A complete first version of the site with every section listed; iterate with short follow-up prompts like "make the hero bolder".', 'image' => '' ),
	array( 'id' => 'security-review-of-my-code', 'title' => 'Security review of my code', 'cat' => 'Cybersecurity', 'tool' => 'Claude', 'kind' => 'code', 'premium' => false, 'summary' => 'Find common vulnerabilities in your own code.', 'prompt' => 'Review my [LANGUAGE] code for security issues: input validation, SQL/command injection, XSS, authentication and session handling, secrets in code, insecure dependencies and error leaks.
For each finding: severity, line, why it is risky, and a safe fix.
```
[PASTE CODE]
```', 'output' => 'Severity-ranked findings with line references and safe fixes.', 'image' => '' ),
	array( 'id' => 'phishing-email-check', 'title' => 'Phishing email check', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Decide if an email is a phishing attempt.', 'prompt' => 'Is this email phishing? [PASTE EMAIL HEADERS SUMMARY AND BODY — remove personal data].
Check sender address vs display name, links, urgency, requests for credentials or payment, grammar and attachments. Give a verdict with reasons and what to do next (report, delete, verify through the official website).', 'output' => 'A verdict with the specific red flags found and safe next steps.', 'image' => '' ),
	array( 'id' => 'password-and-2fa-policy', 'title' => 'Password and 2FA policy', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Simple, modern password rules for a team.', 'prompt' => 'Write a password and multi-factor authentication policy for a [SIZE] team, following current NIST-style guidance: long passphrases, password managers, no forced periodic changes unless compromised, MFA everywhere (prefer authenticator apps or passkeys over SMS), and how to handle a suspected compromise.', 'output' => 'A modern, plain-English password and MFA policy.', 'image' => '' ),
	array( 'id' => 'incident-response-plan', 'title' => 'Incident response plan', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'What to do in the first hours of a security incident.', 'prompt' => 'Create an incident response plan for a [SIZE] company using [SYSTEMS].
Phases: preparation, detection, containment, eradication, recovery, lessons learned. Include roles, a contact list template, first-hour checklist, communication templates and evidence preservation tips.', 'output' => 'A phase-by-phase plan with roles, a first-hour checklist and communication templates.', 'image' => '' ),
	array( 'id' => 'threat-model-a-feature', 'title' => 'Threat model a feature', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Find what could go wrong before you build.', 'prompt' => 'Threat model this feature using STRIDE: [DESCRIBE FEATURE, DATA FLOWS, USERS].
For each STRIDE category: possible threats, likelihood, impact, and mitigations. End with the top 5 risks to fix first.', 'output' => 'A STRIDE table with threats, ratings, mitigations and the top five risks.', 'image' => '' ),
	array( 'id' => 'website-security-checklist', 'title' => 'Website security checklist', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Harden a small website or WordPress site.', 'prompt' => 'Give me a security checklist for my [WORDPRESS / CUSTOM] website on [HOSTING].
Cover: updates, admin access and MFA, plugins and themes, backups, HTTPS and security headers, file permissions, login protection, monitoring, and an incident plan. Mark quick wins.', 'output' => 'A prioritised hardening checklist with quick wins marked.', 'image' => '' ),
	array( 'id' => 'explain-a-vulnerability-simply', 'title' => 'Explain a vulnerability simply', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Understand a CVE or attack type.', 'prompt' => 'Explain [VULNERABILITY OR CVE / ATTACK TYPE] for a [DEVELOPER / MANAGER].
What it is, how it works conceptually, who is affected, the impact, how to detect exposure, and how to fix or mitigate it. No exploit code.', 'output' => 'A clear conceptual explanation with impact, detection and mitigation, without exploit code.', 'image' => '' ),
	array( 'id' => 'security-awareness-training-plan', 'title' => 'Security awareness training plan', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Teach staff to spot and stop attacks.', 'prompt' => 'Create a 6-week security awareness programme for non-technical staff.
Topics: phishing, passwords and MFA, safe browsing, social engineering, device security, reporting incidents. Each week: a 10-minute lesson outline, a quiz and a practical tip.', 'output' => 'A six-week programme with lesson outlines, quizzes and tips.', 'image' => '' ),
	array( 'id' => 'secure-api-design-checklist', 'title' => 'Secure API design checklist', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'Build APIs that resist common attacks.', 'prompt' => 'Give a secure API design checklist based on the OWASP API Security Top 10 for my [REST / GRAPHQL] API in [STACK].
Cover authentication, object-level authorisation, rate limiting, input validation, mass assignment, error handling, logging and secrets. Add a test idea for each.', 'output' => 'An OWASP-aligned API checklist with a test idea per item.', 'image' => '' ),
	array( 'id' => 'log-analysis-helper', 'title' => 'Log analysis helper', 'cat' => 'Cybersecurity', 'tool' => 'Claude', 'kind' => 'text', 'premium' => false, 'summary' => 'Spot suspicious activity in logs.', 'prompt' => 'Here are some server or authentication logs: [PASTE — remove personal data].
Identify suspicious patterns (repeated failed logins, unusual IPs or times, privilege changes), explain why each is suspicious, and suggest alerts or rules to set up.', 'output' => 'Flagged suspicious patterns with explanations and alert rules to add.', 'image' => '' ),
	array( 'id' => 'backup-and-recovery-plan', 'title' => 'Backup and recovery plan', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Protect data with the 3-2-1 rule.', 'prompt' => 'Create a backup and recovery plan for [DATA AND SYSTEMS] using the 3-2-1 rule (3 copies, 2 media types, 1 offsite/offline).
Include: what to back up, frequency, retention, encryption, restore testing schedule, and recovery time goals.', 'output' => 'A 3-2-1 backup plan with schedules, encryption and restore testing.', 'image' => '' ),
	array( 'id' => 'privacy-policy-outline', 'title' => 'Privacy policy outline', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'A clear privacy policy structure for your site or app.', 'prompt' => 'Outline a privacy policy for [APP / WEBSITE] that collects [DATA TYPES] for [PURPOSES], users in [REGIONS].
Sections: data collected, why, legal basis, sharing, retention, security, user rights, cookies, contact. Plain English, with notes on where a lawyer should review (e.g. GDPR, India\'s DPDP Act).', 'output' => 'A plain-English privacy policy outline with legal review notes.', 'image' => '' ),
	array( 'id' => 'secure-home-network-setup', 'title' => 'Secure home network setup', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Lock down your home Wi-Fi and devices.', 'prompt' => 'Give me a step-by-step guide to secure my home network: router admin password, firmware updates, WPA3 or WPA2-AES, guest network for smart devices, disabling WPS and remote admin, DNS filtering, and device updates. Simple language.', 'output' => 'A simple step-by-step home network hardening guide.', 'image' => '' ),
	array( 'id' => 'vendor-security-questionnaire', 'title' => 'Vendor security questionnaire', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Check a supplier before sharing data.', 'prompt' => 'Create a vendor security questionnaire for [SERVICE TYPE] that will handle our [DATA].
Cover: certifications, data storage and encryption, access control, incident history and notification, sub-processors, backups, and offboarding/data deletion. Add what good answers look like.', 'output' => 'A questionnaire with what good answers look like for each area.', 'image' => '' ),
	array( 'id' => 'dependency-vulnerability-triage', 'title' => 'Dependency vulnerability triage', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Decide which security alerts to fix first.', 'prompt' => 'My dependency scanner reports: [PASTE ALERTS]. My app: [DESCRIBE HOW EACH LIBRARY IS USED].
Triage them: is the vulnerable code path reachable, severity in my context, fix (upgrade version), and order of work.', 'output' => 'Context-aware triage with reachability, severity, fixes and an order of work.', 'image' => '' ),
	array( 'id' => 'social-engineering-red-flags', 'title' => 'Social engineering red flags', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Train yourself to spot manipulation tactics.', 'prompt' => 'List 15 social engineering tactics (pretexting, urgency, authority, baiting, tailgating, vishing, etc.) with a real-world style example message or call script for each and the red flag to notice. For training purposes only.', 'output' => 'Fifteen tactics with example scenarios and the red flag to watch for.', 'image' => '' ),
	array( 'id' => 'secure-docker-image-checklist', 'title' => 'Secure Docker image checklist', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Build safer container images.', 'prompt' => 'Review my Dockerfile for security: [PASTE].
Check: minimal base image, pinned versions, non-root user, no secrets in layers, multi-stage builds, unnecessary packages, healthcheck. Give an improved Dockerfile.', 'output' => 'A reviewed Dockerfile with fixes and an improved version.', 'image' => '' ),
	array( 'id' => 'account-takeover-recovery', 'title' => 'Account takeover recovery', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'What to do if your account is hacked.', 'prompt' => 'My [EMAIL / SOCIAL / BANK] account may be hacked: [SYMPTOMS].
Give an ordered recovery checklist: secure email first, use official recovery pages, change passwords, enable MFA, check sessions and connected apps, warn contacts, check financial activity, and report to the right place.', 'output' => 'An ordered recovery checklist starting with the most critical steps.', 'image' => '' ),
	array( 'id' => 'ctf-and-lab-learning-path', 'title' => 'CTF and lab learning path', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Learn ethical hacking legally.', 'prompt' => 'Make a 3-month learning path for ethical hacking and defence for a beginner with [BACKGROUND].
Include: networking and Linux basics, web security (OWASP Top 10), legal practice platforms and labs (only legal, intentionally vulnerable environments), certifications to consider, and weekly goals. Stress only testing systems you have permission to test.', 'output' => 'A three-month legal learning path with labs, weekly goals and a clear permission rule.', 'image' => '' ),
	array( 'id' => 'security-headers-for-my-site', 'title' => 'Security headers for my site', 'cat' => 'Cybersecurity', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Set HTTP security headers correctly.', 'prompt' => 'Explain and give recommended values for these HTTP security headers for my site on [SERVER: Apache / Nginx / Cloudflare]: Content-Security-Policy, Strict-Transport-Security, X-Content-Type-Options, Referrer-Policy, Permissions-Policy, and frame-ancestors. Show config snippets and how to test them without breaking the site.', 'output' => 'Header explanations, config snippets for your server and a safe rollout tip.', 'image' => '' ),
	array( 'id' => 'write-a-dockerfile', 'title' => 'Write a Dockerfile', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'A production-ready Dockerfile for your app.', 'prompt' => 'Write a Dockerfile for my [LANGUAGE/FRAMEWORK] app.
Details: [ENTRY COMMAND, PORT, DEPENDENCY FILE]. Use a multi-stage build, a slim pinned base image, a non-root user, layer caching for dependencies, and a healthcheck. Add a .dockerignore and the build and run commands.', 'output' => 'A multi-stage, non-root Dockerfile with .dockerignore and commands.', 'image' => '' ),
	array( 'id' => 'docker-compose-for-local-development', 'title' => 'docker-compose for local development', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Run your app with its database and cache locally.', 'prompt' => 'Write a docker-compose.yml for local development: app [STACK], database [POSTGRES / MYSQL], cache [REDIS], and [OTHER].
Include volumes, environment variables via an .env file (with an .env.example), health checks, depends_on with conditions, and the commands to start, stop and reset.', 'output' => 'A compose file with health checks, env handling and everyday commands.', 'image' => '' ),
	array( 'id' => 'github-actions-ci-pipeline', 'title' => 'GitHub Actions CI pipeline', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'Lint, test and build on every pull request.', 'prompt' => 'Write a GitHub Actions workflow for my [LANGUAGE] project: on pull request and push to main, set up [VERSION], cache dependencies, run lint, run tests, and build. Use pinned action versions, least-privilege permissions, and explain each step.', 'output' => 'A commented CI workflow with caching, pinned actions and minimal permissions.', 'image' => '' ),
	array( 'id' => 'kubernetes-deployment-manifests', 'title' => 'Kubernetes deployment manifests', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Deploy an app to Kubernetes with best practices.', 'prompt' => 'Write Kubernetes manifests for [APP] (container image [IMAGE], port [PORT]): Deployment with resource requests/limits, liveness and readiness probes, a Service, a ConfigMap, a Secret reference (not real values), and a HorizontalPodAutoscaler. Explain how to apply and verify.', 'output' => 'Deployment, Service, ConfigMap, Secret reference and HPA manifests with apply and verify steps.', 'image' => '' ),
	array( 'id' => 'terraform-module', 'title' => 'Terraform module', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Infrastructure as code for a common setup.', 'prompt' => 'Write Terraform for [CLOUD: AWS / Azure / GCP] to create [RESOURCES, e.g. a VPC, a private database and a container service].
Use variables, outputs, tags, remote state guidance, and no hard-coded secrets. Explain how to plan and apply safely.', 'output' => 'A structured Terraform setup with variables, outputs and safe plan/apply steps.', 'image' => '' ),
	array( 'id' => 'explain-a-cloud-bill', 'title' => 'Explain a cloud bill', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'Find what is costing you money.', 'prompt' => 'Here is a summary of my cloud bill by service: [PASTE]. My architecture: [DESCRIBE].
Explain the top cost drivers, likely waste (idle resources, oversized instances, data transfer, storage tiers), and 10 savings actions with the estimated effort.', 'output' => 'Top cost drivers, likely waste and ten prioritised savings actions.', 'image' => '' ),
	array( 'id' => 'linux-command-helper', 'title' => 'Linux command helper', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'The right command for a task, explained.', 'prompt' => 'I need a Linux command to [TASK, e.g. find files larger than 1GB modified in the last 7 days and show their sizes].
Give the command, explain each part, a safer dry-run version if it changes or deletes anything, and a common mistake to avoid.', 'output' => 'A command with a part-by-part explanation and a dry-run version for risky operations.', 'image' => '' ),
	array( 'id' => 'bash-script-for-automation', 'title' => 'Bash script for automation', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'A safe, readable shell script.', 'prompt' => 'Write a Bash script that [TASK]. Requirements: set -euo pipefail, clear variables at the top, argument parsing with help text, logging, error messages, and a dry-run flag. Explain how to run and schedule it with cron.', 'output' => 'A robust Bash script with strict mode, help text, dry-run and cron instructions.', 'image' => '' ),
	array( 'id' => 'nginx-reverse-proxy-config', 'title' => 'Nginx reverse proxy config', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'Serve your app behind Nginx with HTTPS.', 'prompt' => 'Write an Nginx config to reverse-proxy [DOMAIN] to my app on [PORT], with HTTPS (Let\'s Encrypt via certbot), HTTP to HTTPS redirect, gzip, sensible timeouts, security headers, and WebSocket support if needed. Explain how to test and reload safely.', 'output' => 'An Nginx config with HTTPS, redirects, headers and safe test/reload steps.', 'image' => '' ),
	array( 'id' => 'monitoring-and-alerting-plan', 'title' => 'Monitoring and alerting plan', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Know when things break before users tell you.', 'prompt' => 'Create a monitoring plan for [APP / SERVICE] on [PLATFORM].
Cover the four golden signals (latency, traffic, errors, saturation), logs, uptime checks, SLOs with targets, alert rules that avoid noise, on-call basics and a dashboard layout.', 'output' => 'A monitoring plan around golden signals with SLOs, alert rules and a dashboard layout.', 'image' => '' ),
	array( 'id' => 'debug-a-failing-deployment', 'title' => 'Debug a failing deployment', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Find why a deploy or pod is failing.', 'prompt' => 'My deployment fails. Platform: [KUBERNETES / DOCKER / PAAS]. Error output: [PASTE LOGS, EVENTS, STATUS].
Explain the likely causes in order, the commands to confirm each, and the fix. Ask for more output if needed.', 'output' => 'Likely causes in order, confirmation commands and fixes.', 'image' => '' ),
	array( 'id' => 'git-workflow-for-a-team', 'title' => 'Git workflow for a team', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Branching, reviews and releases that stay simple.', 'prompt' => 'Design a Git workflow for a [SIZE] team shipping [FREQUENCY]. Compare trunk-based development and GitHub Flow for us, then define branch naming, commit message format, pull request rules, review checklist, release tagging and hotfix process.', 'output' => 'A recommended workflow with naming, PR rules, release and hotfix process.', 'image' => '' ),
	array( 'id' => 'fix-a-git-mess', 'title' => 'Fix a Git mess', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'Undo mistakes in Git safely.', 'prompt' => 'I made this Git mistake: [DESCRIBE, e.g. committed to main instead of a branch / pushed a secret / bad merge].
Show the safest commands to fix it, explain what each does, what to do if I already pushed, and how to avoid it next time. If a secret was pushed, remind me to rotate it.', 'output' => 'Safe recovery commands with explanations and prevention tips, including secret rotation.', 'image' => '' ),
	array( 'id' => 'database-backup-automation', 'title' => 'Database backup automation', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => false, 'summary' => 'Automated, tested database backups.', 'prompt' => 'Automate backups for my [POSTGRES / MYSQL] database on [SERVER / CLOUD].
Give: a backup script, encryption, upload to [STORAGE], retention cleanup, a cron or scheduled job, alerting on failure, and a monthly restore test procedure.', 'output' => 'A backup script with encryption, retention, scheduling, alerts and restore testing.', 'image' => '' ),
	array( 'id' => 'ci-cd-pipeline-design', 'title' => 'CI/CD pipeline design', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'From commit to production with safe gates.', 'prompt' => 'Design a CI/CD pipeline for [APP] deployed to [PLATFORM].
Stages: build, test, security scans (dependencies, secrets), artifact storage, deploy to staging, smoke tests, approval, production deploy (rolling or blue-green), rollback. Describe each stage and its tools.', 'output' => 'A staged pipeline design with gates, scans, deployment strategy and rollback.', 'image' => '' ),
	array( 'id' => 'serverless-function', 'title' => 'Serverless function', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'code', 'premium' => true, 'summary' => 'A small serverless API endpoint.', 'prompt' => 'Write a serverless function for [AWS LAMBDA / CLOUDFLARE WORKERS / VERCEL FUNCTIONS] in [LANGUAGE] that [TASK].
Include input validation, error handling, environment variables for config, logging, and deploy steps.', 'output' => 'A validated, error-handled serverless function with deploy steps.', 'image' => '' ),
	array( 'id' => 'explain-kubernetes-concepts', 'title' => 'Explain Kubernetes concepts', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Pods, services and deployments explained with analogies.', 'prompt' => 'Explain these Kubernetes concepts to a beginner with analogies and a tiny YAML example each: Pod, Deployment, Service, Ingress, ConfigMap, Secret, Namespace, and HPA. End with how they fit together in one diagram described in words.', 'output' => 'Beginner-friendly explanations with analogies, small YAML snippets and a combined picture.', 'image' => '' ),
	array( 'id' => 'infrastructure-cost-saving-plan', 'title' => 'Infrastructure cost-saving plan', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Cut cloud spend without hurting reliability.', 'prompt' => 'My setup: [DESCRIBE SERVICES, INSTANCE SIZES, TRAFFIC]. Monthly spend: [AMOUNT].
Suggest: right-sizing, autoscaling, reserved or savings plans, spot instances where safe, storage lifecycle rules, and caching. Estimate savings ranges and risks for each.', 'output' => 'Savings options with estimated ranges, risks and an order to try them.', 'image' => '' ),
	array( 'id' => 'runbook-for-a-common-incident', 'title' => 'Runbook for a common incident', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => true, 'summary' => 'Step-by-step fix for a known problem.', 'prompt' => 'Write a runbook for this incident: [e.g. database CPU at 100%, disk full, site down].
Include: symptoms, impact, diagnosis commands, mitigation steps, verification, escalation contacts, and a post-incident checklist.', 'output' => 'A step-by-step runbook with diagnosis commands, mitigation and escalation.', 'image' => '' ),
	array( 'id' => 'environment-variables-and-secrets-setup', 'title' => 'Environment variables and secrets setup', 'cat' => 'DevOps & Cloud', 'tool' => 'Any chatbot', 'kind' => 'text', 'premium' => false, 'summary' => 'Manage config and secrets safely.', 'prompt' => 'How should I manage configuration and secrets for my [STACK] app across dev, staging and production?
Cover: .env files (never committed), .env.example, a secrets manager on [PLATFORM], CI secrets, rotation, and checking the repo history for leaked secrets.', 'output' => 'A safe config and secrets approach for each environment, including rotation and leak checks.', 'image' => '' ),
);
