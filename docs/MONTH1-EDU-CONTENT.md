# Month 1 education content (23 Nov – 22 Dec 2026): 30 posts, English

Images are built from `promo/social-src/month1/posts.py`:
```bash
python3 -I promo/social-src/month1/build.py OUT
node promo/social-src/month1/check.js "$PWD/OUT/month1.html" OUT/png
```
`check.js` scales each slide's content to fill the free space and reports anything that would overlap.
Captions, DM links and rules of thumb: `OUT/CAPTIONS-MONTH1.md`.

## Why people will choose us over the big education pages

The big pages post **topics** ("Python lists"). People scroll past topics. We post **moments**: the
exact second a learner is stuck, nervous or surprised. Six things make every post ours:

| # | What we do | Big pages usually | Why the audience cares |
|---|---|---|---|
| 1 | **Start from a real moment**: an interview question, a bug, a "wait, what?" | Start from the topic name | They see their own problem and stop scrolling |
| 2 | **The crew talks**: Dev asks what the reader is thinking; Chip, Bugsy or Byte answers | Plain text cards | A tiny story makes them swipe to the end |
| 3 | **📌 Rule of thumb** on every last slide: one quotable line | Long explanation, no takeaway | Easy to remember, screenshot and share |
| 4 | **"Say this 🎤" scripts**: the exact words to use in an interview | The answer only | Nerves: they get words, not just facts |
| 5 | **Try it now**: every post points to a free in-browser tool or lesson (compiler, SQL playground, HTML editor) | Link to a long article | "Doing" happens on our site, so visits last longer |
| 6 | **Plain global English**: short sentences, no slang a non-native reader would miss | Varies | Works in India, the US, the Philippines, Nigeria, Europe… |

## The 7 weekly series

| Day | Series | What it is | What it gets |
|---|---|---|---|
| Mon | ⚔️ **This vs That** | Two things people mix up, with a crew debate + table + code | Saves |
| Tue | 🧩 **Predict the Output** | One line of code, 4 options, answer on slide 2 | Comments (A/B/C/D) |
| Wed | 🧸 **Explain Like I'm 5** | A scary concept explained with everyday things | Shares |
| Thu | 🎤 **Interview Cheat Code** | A famous question + the exact 30-second answer | Saves + follows |
| Fri | 🐞 **Bug Hunt** | Real beginner bug → Bugsy finds it → fix | Comments ("found it!") |
| Sat | 📌 **Save This** | One-page cheat sheet | Saves (Instagram's strongest signal) |
| Sun | 🛠️ **Build It** | A tiny real project in 2–8 lines they can run on the site | Site visits |

Languages are mixed on purpose (Python, JavaScript, SQL, Java, HTML/CSS, Git, DSA, AI) so every
learner finds something in any given week.

## The 30 posts

| Day | Date | Series | Post | Keyword → page |
|---|---|---|---|---|
| 1 | Mon 23 Nov | ⚔️ | `==` vs `===` (JavaScript) | JS → JS Operators |
| 2 | Tue 24 Nov | 🧩 | Is `0.1 + 0.2 == 0.3` True? (Python) | PYTHON → Python Numbers |
| 3 | Wed 25 Nov | 🧸 | Big-O without the maths | BIGO → Big-O lesson |
| 4 | Thu 26 Nov | 🎤 | "Explain OOP" in 30 seconds | OOP → Java OOP |
| 5 | Fri 27 Nov | 🐞 | `WHERE email = NULL` returns zero rows | SQL → SQL playground |
| 6 | Sat 28 Nov | 📌 | The 10 Git commands you use daily | GIT → Git cheat sheet |
| 7 | Sun 29 Nov | 🛠️ | Password generator in 4 lines | BUILD → Python compiler |
| 8 | Mon 30 Nov | ⚔️ | `let` vs `const` vs `var` | JS → JS Scope |
| 9 | Tue 1 Dec | 🧩 | `typeof null` | JS → JS Data Types |
| 10 | Wed 2 Dec | 🧸 | A database index is a textbook index | SQL → SQL Indexes |
| 11 | Thu 3 Dec | 🎤 | "What happens when you type a URL?" | WEB → Frontend roadmap |
| 12 | Fri 4 Dec | 🐞 | Python mutable default argument | PYTHON → Python Functions |
| 13 | Sat 5 Dec | 📌 | SQL in one look + real run order | SQL → SQL cheat sheet |
| 14 | Sun 6 Dec | 🛠️ | Dark mode in 2 lines | HTML → HTML editor |
| 15 | Mon 7 Dec | ⚔️ | `WHERE` vs `HAVING` | SQL → GROUP BY lesson |
| 16 | Tue 8 Dec | 🧩 | `bool("False")` | PYTHON → Python Data Types |
| 17 | Wed 9 Dec | 🧸 | Git is save points in a video game | GIT → Git cheat sheet |
| 18 | Thu 10 Dec | 🎤 | "Tell me about your project" | PROJECT → JS to-do project |
| 19 | Fri 11 Dec | 🐞 | Loop prints 3, 3, 3 (`var` + `setTimeout`) | JS → JS Loops |
| 20 | Sat 12 Dec | 📌 | 10 Python string methods | PYTHON → Python Strings |
| 21 | Sun 13 Dec | 🛠️ | Guess-the-number game | GAME → Python compiler |
| 22 | Mon 14 Dec | ⚔️ | Stack vs Queue | DSA → Stacks lesson |
| 23 | Tue 15 Dec | 🧩 | `7 / 2` in Java | JAVA → Java Operators |
| 24 | Wed 16 Dec | 🧸 | How an AI chatbot writes answers | AI → How AI Models Are Built |
| 25 | Thu 17 Dec | 🎤 | "Reverse a string": 3 answers | DSA → Two Pointers |
| 26 | Fri 18 Dec | 🐞 | CSS box wider than 100% | CSS → Box Model |
| 27 | Sat 19 Dec | 📌 | How fast are Python operations? | BIGO → Big-O cheat sheet |
| 28 | Sun 20 Dec | 🛠️ | Most-used words in any text | BUILD → Python compiler |
| 29 | Mon 21 Dec | ⚔️ | GET vs POST (+ the "POST is secure" myth) | WEB → JS Fetch & JSON |
| 30 | Tue 22 Dec | 🏁 | 10 rules of thumb (recap) | RULES → Roadmaps |

**DM keywords to set up in Meta Business Suite:** JS, PYTHON, BIGO, OOP, SQL, GIT, BUILD, WEB, HTML,
PROJECT, GAME, DSA, JAVA, AI, CSS, RULES (links in the captions file).

## Posting routine (fits the 30 minutes a day)
- Sunday: schedule the week's 7 posts in Meta Business Suite (7:00 am IST on Mon/Wed/Fri/Sun,
  7:30 pm IST on Tue/Thu/Sat), with Instagram music from the mood guide in `MUSIC-AND-DM-AUTOMATION.md`.
- Every day: pin the rule of thumb as the first comment, reply to every comment, and share to stories
  with a poll (Predict and Bug Hunt days: "Did you get it right?").
- Tue (Predict) posts: reply to wrong answers kindly, and point them to slide 2.
- Turn any post into a reel with `promo/social-src/make_reel.py` and a track from `promo/music/`.
