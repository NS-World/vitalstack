# Adding content to VitalStack

## Why "HTML" isn't in the top bar yet

A subject (HTML, CSS, JavaScript, SQL, Python) appears in the dark top bar
and on the homepage **only after it has at least one published lesson**.
An empty subject would lead to an empty page, so the theme hides it.
Publish your first HTML lesson and **HTML** appears automatically.

## The three kinds of content

| Kind | Where in WordPress | Use it for | Example |
|---|---|---|---|
| **Lesson** | Tutorials → Add New Tutorial | Step-by-step lessons inside a subject, read in order | "HTML Attributes" |
| **Guide** | Posts → Add New Post | Stand-alone articles on AI, tools, careers | "What are AI agents?" |
| **Page** | Pages | Fixed site pages (already created by VitalStack Setup) | About Us, Contact |

---

## Fastest start: import 5 ready HTML lessons

File: [`content/import/vitalstack-html-lessons-1-5.xml`](../content/import/vitalstack-html-lessons-1-5.xml)
(lessons 1–5: Introduction, Editors, Basic Structure, Elements, Attributes;
about 700 words each, with runnable examples and exercises).

1. WordPress → **Tools → Import**.
2. Under **WordPress**, click **Install Now**, then **Run Importer**.
3. **Choose File** → select `vitalstack-html-lessons-1-5.xml` → **Upload file and import**.
4. *Assign authors*: choose **your own account** in "or assign posts to an existing user".
5. Click **Submit**. The 5 lessons arrive as **drafts**, already in the HTML subject and numbered 1–5.
6. **Tutorials** → open each lesson → read it → **Publish**.

**Before publishing, make them yours.** These lessons are a starting point
I wrote for you. Read each one, run every example, change explanations into
your own words, and add something only you can add: a screenshot of your
own editor, a tip from your own learning, a better example. Google rewards
original, checked content; publishing AI text unchanged is what hurt the
old site.

---

## Writing a lesson yourself (step by step)

![The lesson editor](img/editor-new-lesson.png)

1. **Tutorials → Add New Tutorial**.
2. **Title**: short and specific, subject first: `HTML Links`, `CSS Colors`,
   `JavaScript Arrays`. No year, no "(Complete Guide)".
3. **Write the lesson** (structure below). Use:
   - **Heading** block (type `/heading`): H2 for each section, H3 for sub-points.
     H2/H3 headings automatically build "On this page".
   - **Code** block (type `/code`) for every example. Paste the code as plain
     text. HTML and JavaScript examples get a **Try it Yourself** button automatically.
   - Inline code: select a word → in the block toolbar click **⌄ (More)** → **Inline code**, e.g. `<p>`.
   - **List** block for steps and bullet points.
   - **Image** block for screenshots; always fill in **Alt text**.
4. **Right sidebar → Tutorial tab**:
   - **Learning Paths** → tick the subject (e.g. **HTML**).
   - **Difficulty** → Beginner / Intermediate / Advanced.
   - **Lesson number** → its position (1, 2, 3…). Leave it **empty** and the
     lesson becomes the next number in its subject when you publish.
   - **Excerpt** ("Add an excerpt") → one sentence about the lesson. It's shown
     on cards, in search results and in subscriber emails.
   - **Featured image** is optional (the theme shows a subject icon without one).
5. **Publish**. Subscribers get an email about the new lesson a couple of
   minutes later. To skip that (e.g. for a small lesson), tick *Don't email
   subscribers* in the **Email subscribers** box first.

The lesson now shows in the left sidebar, the Previous / Next buttons link
it to its neighbours, and the subject appears in the top bar.

### Lesson template

Copy this structure for every lesson:

1. **Intro** (2–3 sentences): what the reader will learn and why it matters.
2. **The main idea**, explained in plain words, then a **small example**.
3. **More sections**, each: explanation → example → what the example shows.
4. **Common mistakes**: 2–4 bullet points.
5. **Exercise**: 3–5 small tasks the reader does with Try it Yourself.
6. **Summary**: 3–5 bullet points.
7. One sentence pointing to the next lesson.

Aim for 600–1,200 words. Short paragraphs (2–4 sentences). One idea per section.

### Quality checklist before you click Publish

- [ ] Every code example runs (test it with Try it Yourself).
- [ ] Explained in your own words; no copied text from other sites.
- [ ] Headings tell the story on their own (read just the H2s).
- [ ] Links to 1–2 related VitalStack lessons and, where useful, official docs (MDN, w3.org, python.org).
- [ ] Excerpt filled in; Learning Path ticked; lesson number right.

---

## Writing a guide (blog post)

**Posts → Add New Post**: same editor. In the sidebar choose **one category**:

| Category | Write about |
|---|---|
| Artificial Intelligence | How AI works: LLMs, agents, machine learning, explained simply |
| AI Tools | Practical, tested how-tos: prompts, workflows, tool comparisons |
| AI Career | Skills, roadmaps, interview prep, first-job advice |
| Programming | Comparisons and projects beyond the lessons ("React vs Vue", "Build a portfolio site") |

Guide template: question in the title → 2-line answer up front → detailed
sections → real examples or screenshots → FAQ (3–5 questions) → summary.
Aim for 1,000–2,000 words.

---

## Content plan: the first 100 pieces

Publish **in order**: finish HTML before starting CSS, so each subject is
complete and readers can follow it to the end.

**HTML (20 lessons)**
1. Introduction ✅ 2. Editors ✅ 3. Basic Structure ✅ 4. Elements ✅ 5. Attributes ✅
6. Headings 7. Paragraphs 8. Text Formatting 9. Links 10. Images
11. Lists 12. Tables 13. Div and Span 14. Classes and IDs 15. Forms
16. Form Inputs 17. Semantic Elements 18. Media (Audio and Video)
19. Accessibility Basics 20. Project: Build a Personal Profile Page

**CSS (20 lessons)**
Introduction, How to Add CSS, Selectors, Colors, Backgrounds, Borders,
Margin and Padding, Box Model, Width and Height, Text and Fonts, Display,
Position, Flexbox Basics, Flexbox Layouts, Grid Basics, Grid Layouts,
Responsive Design and Media Queries, Transitions, Variables,
Project: Style Your Profile Page

**JavaScript (25 lessons)**
Introduction, Where to Put JavaScript, Output (console.log), Variables (let and const),
Data Types, Operators, Strings, Numbers, If/Else, Switch, Loops (for, while),
Arrays, Array Methods, Objects, Functions, Arrow Functions, Scope,
DOM: Selecting Elements, DOM: Changing Content, Events, Forms and Validation,
Local Storage, Fetch and JSON, Async/Await, Project: To-Do List App

**SQL (15 lessons)**
Introduction, Databases and Tables, SELECT, WHERE, ORDER BY, INSERT,
UPDATE, DELETE, Functions (COUNT, SUM, AVG), GROUP BY, JOINs (INNER),
LEFT JOIN, Constraints and Keys, Indexes, Project: Library Database

**Python (20 lessons)**
Introduction, Installing Python, Syntax, Variables, Data Types, Strings,
Numbers, Lists, Tuples and Sets, Dictionaries, If/Else, Loops, Functions,
Modules, Files, Errors and Exceptions, Classes and Objects, pip and Packages,
Working with JSON and APIs, Project: Simple Expense Tracker

*(Python and SQL examples don't get Try it Yourself; that's normal.)*

---

## Rhythm that works

- 2–3 pieces a week, every week. Consistency beats bursts.
- After every 5 lessons: re-read the subject from lesson 1 as a beginner and fix gaps.
- Every month: update your 5 most-visited pages (Search Console shows which).
- Apply for AdSense when you have **30+ published pieces** and every box in
  [ADSENSE-APPROVAL.md](ADSENSE-APPROVAL.md) is ticked.

---

## For later: importing lessons you write as files

Lessons can also be written as Markdown files in `content/lessons/<subject>/`
(see the HTML ones for the format) and converted into an import file:

```
python3 tools/lessons_to_wxr.py content/lessons/html -o content/import/html.xml
```

Then import it with Tools → Import → WordPress, as above.
