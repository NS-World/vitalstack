#!/usr/bin/env python3
"""Build the sign-up gift: "30-Day Java Interview Plan" (HTML, then PDF).

    python3 tools/build_gift.py            # writes content/gifts/java-30-day-interview-plan.html
    node tools/render_pdf.js content/gifts/java-30-day-interview-plan.html \
         wp-content/themes/vitalstack/data/gifts/java-30-day-interview-plan.pdf

Every link points to a lesson, problem or mock test on vitalstack.co.in, and
the script stops if a lesson slug doesn't exist in content/lessons/.
"""
import html
import pathlib
import re
import sys

ROOT = pathlib.Path(__file__).resolve().parent.parent
SITE = "https://vitalstack.co.in"
OUT = ROOT / "content" / "gifts" / "java-30-day-interview-plan.html"
FONT = ROOT / "wp-content" / "themes" / "vitalstack" / "assets" / "fonts" / "inter-latin-var.woff2"
LOGO = ROOT / "wp-content" / "themes" / "vitalstack" / "assets" / "brand" / "logo-mark.svg"


def lesson_titles():
    out = {}
    for md in (ROOT / "content" / "lessons").glob("*/*.md"):
        text = md.read_text(encoding="utf-8")
        slug = re.search(r"^slug:\s*(\S+)", text, re.M)
        title = re.search(r"^title:\s*(.+)$", text, re.M)
        if slug and title:
            out[slug.group(1)] = title.group(1).strip().strip('"')
    return out


TITLES = lesson_titles()


def L(slug):
    """Link to a lesson, checked against the lesson files."""
    if slug not in TITLES:
        sys.exit(f"unknown lesson slug: {slug}")
    return f'<a href="{SITE}/tutorials/{slug}/">{html.escape(TITLES[slug])}</a>'


def T(slug, name):
    return f'<a href="{SITE}/mock-tests/{slug}/">{html.escape(name)}</a>'


# (day, topic, [lessons], practice items (HTML))
WEEKS = [
    ("Week 1 · Core Java", "Get fast and accurate with the language itself.", [
        (1, "Types, variables and operators", ["java-variables-and-data-types", "java-operators"],
         ["Know every primitive type and its size; explain why <code>5 / 2</code> is <code>2</code> and <code>(int) 9.99</code> is <code>9</code>.",
          "Write a program that converts Celsius to Fahrenheit for 0–100 in steps of 10."]),
        (2, "Strings", ["java-strings"],
         ["Explain <code>==</code> vs <code>equals()</code>, why strings are immutable, and when to use <code>StringBuilder</code>.",
          "Reverse a string, count its vowels, and solve " + L("problem-valid-palindrome") + "."]),
        (3, "Conditions and loops", ["java-conditions", "java-loops"],
         ["FizzBuzz, check if a number is prime, and sum the digits of a number.",
          "Rewrite one <code>if</code>/<code>else</code> chain as a <code>switch</code>."]),
        (4, "Arrays and methods", ["java-arrays", "java-methods"],
         ["Find the max, the second max and reverse an array in place.",
          "Be ready for: “Is Java pass-by-value or pass-by-reference?”"]),
        (5, "Collections: ArrayList and HashMap", ["java-arraylist-and-collections", "java-hashmap"],
         ["Explain how a <code>HashMap</code> stores entries (buckets, hashing, collisions).",
          "Solve " + L("problem-two-sum") + " and " + L("problem-contains-duplicate") + " with a map or set."]),
        (6, "Exceptions, lambdas and streams", ["java-exceptions", "java-lambdas-and-streams"],
         ["Checked vs unchecked exceptions, and what <code>finally</code> guarantees.",
          "Use a stream to filter, map and collect a list of students by marks."]),
        (7, "Checkpoint", ["java-project-student-manager"],
         ["Take the " + T("java-mcq-test", "Java MCQ Test") + ". Target: 16/20. Revise every lesson it lists.",
          "Build the Student Grade Manager project and push it to GitHub."]),
    ]),
    ("Week 2 · OOP and SQL", "The two topics asked in almost every Java interview.", [
        (8, "Classes, objects, constructors, static", ["java-oop-classes-and-objects", "java-oop-static-and-constructors"],
         ["Explain constructor overloading and what <code>static</code> belongs to.",
          "Model a <code>BankAccount</code> class with deposit and withdraw."]),
        (9, "Encapsulation and inheritance", ["java-oop-encapsulation", "java-oop-inheritance"],
         ["Why private fields with getters and setters? What does <code>super</code> do?",
          "Extend <code>BankAccount</code> into <code>SavingsAccount</code> with interest."]),
        (10, "Polymorphism and abstraction", ["java-oop-polymorphism", "java-oop-abstraction"],
         ["Overloading vs overriding, with your own example.",
          "Explain runtime (dynamic) method dispatch; watch the lesson's animation."]),
        (11, "Interfaces, equals/hashCode, records", ["java-oop-interfaces", "java-oop-equals-hashcode-tostring", "java-oop-records-enums-sealed"],
         ["Abstract class vs interface (including default methods).",
          "Why must <code>equals()</code> and <code>hashCode()</code> be overridden together?"]),
        (12, "SOLID and design patterns", ["java-oop-solid-and-design-patterns"],
         ["Explain each SOLID letter with one example.",
          "Write Singleton, Builder, Factory and Strategy from memory."]),
        (13, "SQL queries", ["sql-select", "sql-where", "sql-order-by", "sql-functions", "sql-group-by"],
         ["<code>WHERE</code> vs <code>HAVING</code>; what <code>COUNT(*)</code> vs <code>COUNT(col)</code> returns with NULLs.",
          "Write the 2nd highest salary query and the count of employees per department."]),
        (14, "JOINs, keys, indexes + checkpoint", ["sql-inner-join", "sql-left-join", "sql-constraints-and-keys", "sql-indexes"],
         ["<code>INNER JOIN</code> vs <code>LEFT JOIN</code>; primary vs foreign key; when an index helps.",
          "Take the " + T("java-oop-mcq-test", "Java OOP") + " and " + T("sql-mcq-test", "SQL") + " mock tests. Target: 16/20 each."]),
    ]),
    ("Week 3 · Spring and Spring Boot", "What most Java backend jobs actually use.", [
        (15, "Spring core: IoC and dependency injection", ["spring-ioc-and-dependency-injection", "spring-configuration-and-beans", "spring-bean-scopes-and-lifecycle"],
         ["What the IoC container does; why constructor injection is preferred over <code>@Autowired</code> on fields.",
          "Singleton vs prototype scope."]),
        (16, "Your first REST API", ["spring-boot-introduction", "spring-boot-first-rest-api"],
         ["What auto-configuration does; <code>@RestController</code> vs <code>@Controller</code>.",
          "Build a CRUD API for books with GET, POST, PUT and DELETE."]),
        (17, "Spring Data JPA and JPA basics", ["spring-boot-data-jpa", "jpa-entity-states", "jpa-relationships"],
         ["Entity states (new, managed, detached, removed); lazy vs eager fetching.",
          "Connect the books API to a database with a repository."]),
        (18, "Validation, errors and transactions", ["spring-boot-dto-validation-errors", "spring-transactions"],
         ["Why use DTOs; how <code>@Valid</code> and a global exception handler work.",
          "What <code>@Transactional</code> does, and when it silently doesn't."]),
        (19, "Configuration and security", ["spring-boot-configuration-and-logging", "spring-boot-security-jwt"],
         ["Profiles and <code>application.properties</code>; how JWT authentication works step by step.",
          "Secure the books API so only signed-in users can add books."]),
        (20, "Testing and performance", ["spring-boot-testing", "spring-boot-relationships-and-pagination"],
         ["Unit test vs <code>@SpringBootTest</code>; what the N+1 query problem is and how to fix it.",
          "Add pagination to the list endpoint and one test per endpoint."]),
        (21, "Checkpoint", ["spring-boot-project-enrollment-api"],
         ["Take the " + T("spring-boot-mcq-test", "Spring Boot MCQ Test") + ". Target: 15/20.",
          "Build the Course Enrollment API project: your strongest resume project."]),
    ]),
    ("Week 4 · DSA and the interview", "Patterns, not memorising. Then practise like it's real.", [
        (22, "Big O, arrays and hashing", ["dsa-big-o", "dsa-hashing"],
         ["Solve " + L("problem-valid-anagram") + ", " + L("problem-group-anagrams") + " and " + L("problem-top-k-frequent-elements") + "."]),
        (23, "Two pointers and sliding window", ["dsa-two-pointers", "dsa-sliding-window"],
         ["Solve " + L("problem-three-sum") + ", " + L("problem-container-with-most-water") + " and " + L("problem-longest-substring-without-repeating") + "."]),
        (24, "Binary search and sorting", ["dsa-binary-search", "dsa-merge-quick-sort"],
         ["Solve " + L("problem-search-in-rotated-sorted-array") + " and " + L("problem-koko-eating-bananas") + "."]),
        (25, "Stacks and linked lists", ["dsa-stacks", "dsa-linked-lists"],
         ["Solve " + L("problem-valid-parentheses") + ", " + L("problem-reverse-linked-list") + " and " + L("problem-lru-cache") + "."]),
        (26, "Trees and heaps", ["dsa-trees", "dsa-heaps"],
         ["Solve " + L("problem-binary-tree-level-order-traversal") + ", " + L("problem-validate-binary-search-tree") + " and " + L("problem-kth-largest-element") + "."]),
        (27, "Graphs", ["dsa-graphs", "dsa-topological-sort"],
         ["Solve " + L("problem-number-of-islands") + " and " + L("problem-course-schedule") + "."]),
        (28, "Dynamic programming", ["dsa-dynamic-programming"],
         ["Solve " + L("problem-climbing-stairs") + ", " + L("problem-house-robber") + " and " + L("problem-coin-change") + ".",
          "Take the " + T("dsa-mcq-test", "DSA MCQ Test") + "."]),
        (29, "Mock interview day", ["dsa-interview-playbook"],
         ["45 minutes, one medium problem, explain your approach out loud before coding.",
          "Take the " + T("coding-interview-mcq-test", "Coding Interview MCQ Test") + ". Practise “Tell me about yourself” and explain both projects in 2 minutes each."]),
        (30, "Final revision", [],
         ["Retake the Java, Java OOP and Spring Boot tests; revise only what you got wrong.",
          "Skim the " + f'<a href="{SITE}/cheat-sheets/java-cheat-sheet/">Java</a>' + " and " + f'<a href="{SITE}/cheat-sheets/spring-boot-cheat-sheet/">Spring Boot</a>' + " cheat sheets. Then rest: sleep beats one more topic."]),
    ]),
]

QUESTIONS = [
    ("What is the difference between <code>==</code> and <code>equals()</code>?", "java-strings"),
    ("Why are strings immutable, and when do you use <code>StringBuilder</code>?", "java-strings"),
    ("JDK vs JRE vs JVM?", "java-introduction"),
    ("How does a <code>HashMap</code> work internally? What happens on a collision?", "java-hashmap"),
    ("Checked vs unchecked exceptions? When does <code>finally</code> run?", "java-exceptions"),
    ("What are streams and lambdas? <code>map</code> vs <code>filter</code>?", "java-lambdas-and-streams"),
    ("What does <code>static</code> mean on a field and on a method?", "java-oop-static-and-constructors"),
    ("What is encapsulation and why does it matter?", "java-oop-encapsulation"),
    ("Overloading vs overriding?", "java-oop-polymorphism"),
    ("Abstract class vs interface?", "java-oop-interfaces"),
    ("Why override <code>equals()</code> and <code>hashCode()</code> together?", "java-oop-equals-hashcode-tostring"),
    ("Explain SOLID. Implement a Singleton.", "java-oop-solid-and-design-patterns"),
    ("<code>WHERE</code> vs <code>HAVING</code>?", "sql-group-by"),
    ("<code>INNER JOIN</code> vs <code>LEFT JOIN</code>?", "sql-left-join"),
    ("Primary key vs foreign key? What is an index?", "sql-constraints-and-keys"),
    ("What is dependency injection? Why constructor injection?", "spring-ioc-and-dependency-injection"),
    ("Bean scopes: singleton vs prototype?", "spring-bean-scopes-and-lifecycle"),
    ("What does <code>@Transactional</code> do?", "spring-transactions"),
    ("What does Spring Boot auto-configuration do?", "spring-boot-introduction"),
    ("<code>@RestController</code> vs <code>@Controller</code>?", "spring-boot-first-rest-api"),
    ("How do you validate input and return clean error messages?", "spring-boot-dto-validation-errors"),
    ("How does JWT authentication work?", "spring-boot-security-jwt"),
    ("Lazy vs eager loading? What is the N+1 problem?", "spring-boot-relationships-and-pagination"),
    ("What is Big O? Time complexity of HashMap get and of binary search?", "dsa-big-o"),
    ("How do you approach a problem you've never seen?", "dsa-interview-playbook"),
]


def build():
    font = __import__("base64").b64encode(FONT.read_bytes()).decode()
    logo = LOGO.read_text(encoding="utf-8")
    weeks = []
    for title, sub, days in WEEKS:
        rows = []
        for n, topic, lessons, practice in days:
            read = ", ".join(L(s) for s in lessons) if lessons else "Your notes and the lessons your tests flagged"
            rows.append(
                f'<div class="day"><div class="dnum">Day<b>{n}</b></div><div class="dbody"><h3>{html.escape(topic)}</h3>'
                f'<p class="read"><span>Read</span> {read}</p><ul>' + "".join(f"<li>{p}</li>" for p in practice) + '</ul></div>'
                '<div class="tick"></div></div>'
            )
        weeks.append(f'<section class="week"><h2>{html.escape(title)}</h2><p class="wsub">{html.escape(sub)}</p>{"".join(rows)}</section>')
    qs = "".join(f'<li><span class="box"></span><span>{q} <small>{L(s)}</small></span></li>' for q, s in QUESTIONS)
    return f"""<!doctype html><html><head><meta charset="utf-8"><title>30-Day Java Interview Plan · VitalStack</title><style>
@font-face{{font-family:Inter;font-weight:400 800;src:url(data:font/woff2;base64,{font}) format('woff2')}}
@page{{size:A4;margin:16mm 15mm 18mm}}@page :first{{margin:0}}
*{{box-sizing:border-box}}body{{margin:0;font-family:Inter,sans-serif;color:#1f2937;font-size:10.5pt;line-height:1.5}}
a{{color:#0a7d4f;text-decoration:none;font-weight:600}}code{{font-family:ui-monospace,Menlo,Consolas,monospace;font-size:.92em;background:#eef6f1;padding:0 3px;border-radius:3px}}
.cover{{width:210mm;height:297mm;display:flex;flex-direction:column;justify-content:center;page-break-after:always;background:radial-gradient(120% 90% at 90% 0%,#0f5c3d,#12202b 50%,#0b131a);color:#fff;margin:0;padding:0 24mm;overflow:hidden}}
.brand{{display:flex;align-items:center;gap:10px;font-weight:800;font-size:20pt}}.brand .lg{{width:40px;height:40px}}.mint{{color:#34d399}}
.cover h1{{font-size:40pt;line-height:1.05;margin:26px 0 12px}}.cover p{{font-size:13pt;color:#c5d3dc;max-width:150mm}}
.cover .facts{{display:flex;gap:10px;margin-top:22px;flex-wrap:wrap}}.cover .facts span{{padding:6px 12px;border-radius:999px;border:1px solid rgba(52,211,153,.4);background:rgba(52,211,153,.12);font-weight:600;font-size:10pt}}
.cover .url{{margin-top:40px;font-weight:800;font-size:13pt}}
h2{{font-size:17pt;margin:0 0 2px;color:#0b131a}}.wsub{{margin:0 0 10px;color:#6b7280}}
.how{{page-break-after:always}}.how h2{{margin-top:4px}}.how li{{margin:4px 0}}
.week{{page-break-before:always}}.week:first-of-type{{page-break-before:auto}}
.day{{display:flex;gap:12px;padding:9px 0;border-top:1px solid #e5e7eb;page-break-inside:avoid}}
.dnum{{flex:none;width:46px;text-align:center;font-size:8pt;color:#6b7280;text-transform:uppercase}}.dnum b{{display:block;font-size:17pt;color:#0a7d4f;line-height:1.1}}
.dbody{{flex:1}}.dbody h3{{margin:0 0 2px;font-size:11.5pt}}.read{{margin:0 0 3px}}.read span{{font-size:8pt;font-weight:800;color:#fff;background:#0a7d4f;border-radius:4px;padding:1px 5px;margin-right:4px}}
.dbody ul{{margin:0;padding-left:16px}}.dbody li{{margin:1px 0}}
.tick{{flex:none;width:16px;height:16px;border:2px solid #0a7d4f;border-radius:4px;margin-top:4px}}
.qs{{page-break-before:always}}.qs ol{{list-style:none;padding:0;margin:8px 0 0}}.qs li{{display:flex;gap:10px;padding:5px 0;border-top:1px solid #eef0f2;page-break-inside:avoid}}
.qs .box{{flex:none;width:14px;height:14px;border:2px solid #0a7d4f;border-radius:3px;margin-top:3px}}.qs small{{display:block;font-size:8.5pt}}
.end{{margin-top:16px;padding:12px 14px;border-radius:10px;background:#eef6f1}}
</style></head><body>
<section class="cover">
  <div class="brand"><span class="lg">{logo}</span><span>Vital<span class="mint">Stack</span></span></div>
  <h1>30-Day<br>Java Interview<br><span class="mint">Plan</span></h1>
  <p>A day-by-day plan for Java developer interviews and campus placements: core Java, OOP, SQL, Spring Boot and DSA, with a free lesson, practice and a mock test for every step.</p>
  <div class="facts"><span>30 days</span><span>~2 hours a day</span><span>4 mock-test checkpoints</span><span>25 must-know questions</span></div>
  <div class="url">vitalstack.co.in</div>
</section>
<section class="how">
  <h2>How to use this plan</h2>
  <ul>
    <li><b>Two hours a day:</b> about 1 hour reading the lessons, 1 hour practising. Busy day? Do the practice, skip nothing else.</li>
    <li><b>Every link opens the free lesson, problem or mock test</b> on vitalstack.co.in. Sign in so your progress is saved.</li>
    <li><b>Tick the box</b> at the end of each day. Missed a day? Continue tomorrow; don't try to do two.</li>
    <li><b>Checkpoints (days 7, 14, 21, 28):</b> take the mock test, then revise only the lessons it lists under “Revise these lessons”.</li>
    <li><b>Say answers out loud.</b> Interviews test explaining, not only knowing. After each topic, explain it in one minute without notes.</li>
    <li><b>Already know a week well?</b> Take its mock test first. Scored 18/20 or more? Move on.</li>
  </ul>
  <div class="end">By day 30 you will have three projects on GitHub (Student Grade Manager, Library System or your own OOP project, Course Enrollment API), around 20 classic problems solved, and the 25 questions on the last page answered in your own words.</div>
</section>
{"".join(weeks)}
<section class="qs">
  <h2>25 questions you should answer without notes</h2>
  <p class="wsub">Asked again and again in Java interviews. Tick each one when you can explain it in under a minute. The link is the lesson that answers it.</p>
  <ol>{qs}</ol>
  <div class="end"><b>Good luck!</b> New lessons are added every week on <a href="{SITE}/">vitalstack.co.in</a>. Follow us for daily coding puzzles: Instagram and Threads <b>@vitalstack.ai</b>, X <b>@Vital_Stack</b>.</div>
</section>
</body></html>"""


if __name__ == "__main__":
    OUT.parent.mkdir(parents=True, exist_ok=True)
    OUT.write_text(build(), encoding="utf-8")
    print("wrote", OUT.relative_to(ROOT))
