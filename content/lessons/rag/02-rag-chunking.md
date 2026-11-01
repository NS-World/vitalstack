---
title: "Chunking Documents for RAG"
slug: rag-chunking
subject: rag
lesson: 2
difficulty: intermediate
excerpt: Split PDFs and web pages into chunks that retrieve well: fixed-size with overlap, sentence and heading-aware chunking, metadata for citations, and how chunk size affects answers.
---

You can't embed a 200-page PDF as one vector: its meaning gets averaged into mush, and it wouldn't fit in a prompt anyway. So documents are cut into **chunks**. How you cut them decides what the retriever can find. Bad chunking is the number-one reason RAG bots give poor answers.

::scene Cutting the textbook
📘 | The book | A 200-page employee handbook needs to become searchable. | dev
✂️ | Too big | Chunks of 10 pages: every chunk is about everything, so search can't tell them apart. | bugsy
🔪 | Too small | Chunks of one line: "It carries over." What carries over? The context is lost. | byte
🧩 | Just right | Paragraph-sized chunks that follow headings, with a little overlap and a source label. | chip
::end

## Fixed-size chunks with overlap

The simplest method: windows of N words, overlapping so a sentence cut at a boundary still appears whole in one chunk.

```python
def chunk_words(text, size=12, overlap=4):
    words = text.split()
    step = size - overlap
    return [" ".join(words[i:i + size]) for i in range(0, max(len(words) - overlap, 1), step)]

text = ("Employees get 18 days of paid leave per year. Unused leave up to 10 days carries over "
        "to the next year. Sick leave of 12 days is separate and needs a doctor's note after 2 days.")
for c in chunk_words(text):
    print("-", c)
```

Output:

```text
- Employees get 18 days of paid leave per year. Unused leave up
- year. Unused leave up to 10 days carries over to the next
- over to the next year. Sick leave of 12 days is separate
- 12 days is separate and needs a doctor's note after 2 days.
```

Real systems measure size in **tokens** (often 200–800) with 10–20% overlap.

::walk python | How many chunks will a document make?
words = 1000
size, overlap = 200, 40
step = size - overlap
count = len(range(0, words - overlap, step))
print(step, count)
::notes
1: A 1,000-word document. // vars: words=1000
2: Chunks of 200 words, each overlapping the previous by 40. // vars: size=200, overlap=40
3: Each new chunk starts 160 words after the last. // vars: step=160
4: Start positions 0, 160, 320 … up to the end: 6 chunks. // deep: Overlap costs extra storage and embedding calls (here 6 chunks instead of 5), but it stops answers from being split across two chunks where neither scores well. // vars: count=6
5: 6 chunks to embed and store. // out: 160 6
::end

## Structure-aware chunking (better)

Documents have structure: headings, paragraphs, lists, tables. Split on that first, and attach the heading to each chunk so it carries its context:

```python
handbook = """# Leave
## Paid leave
Employees get 18 days of paid leave per year. Up to 10 unused days carry over.
## Sick leave
12 days per year. A doctor's note is needed for more than 2 consecutive days.
# Travel
## Trains
AC 3-tier for journeys under 8 hours."""

chunks, path = [], []
for line in handbook.splitlines():
    if line.startswith("#"):
        level = len(line) - len(line.lstrip("#"))
        path = path[:level - 1] + [line.lstrip("# ").strip()]
    elif line.strip():
        chunks.append({"text": line.strip(), "section": " > ".join(path), "source": "handbook.md"})

for c in chunks:
    print(f"[{c['section']}] {c['text'][:45]}")
```

Output:

```text
[Leave > Paid leave] Employees get 18 days of paid leave per year.
[Leave > Sick leave] 12 days per year. A doctor's note is needed f
[Travel > Trains] AC 3-tier for journeys under 8 hours.
```

"12 days per year." alone is ambiguous; with "Leave > Sick leave" attached, both the retriever and the LLM know what it means.

## Metadata: the key to citations and filters

Store with every chunk: source file, page number, section, date, and access level. Then you can:

- show citations: "[handbook.pdf, p. 3, Sick leave]";
- filter: only search the 2026 policy, only documents this user may see;
- update: delete and re-index one file when it changes.

## Getting text out of files

- **PDFs:** `pypdf` or `pdfplumber` for text PDFs; scanned PDFs need OCR (Tesseract, or a cloud OCR service).
- **Web pages:** strip menus and footers (`BeautifulSoup`, `trafilatura`).
- **Word/Excel:** `python-docx`, `pandas`.
- **Tables:** keep each row with its column headers, or convert the table to Markdown before chunking.

::tip Test chunking by reading 20 random chunks yourself. If a chunk doesn't make sense to you on its own, it won't make sense to the retriever or the LLM either.

## Summary

- Chunk size is a trade-off: too big = vague, too small = context lost.
- Fixed-size windows with overlap are simple; heading/paragraph-aware chunks are better.
- Attach section titles and metadata (source, page, date, access) to every chunk.
- Extract text carefully from PDFs, web pages and tables, then inspect chunks by eye.
