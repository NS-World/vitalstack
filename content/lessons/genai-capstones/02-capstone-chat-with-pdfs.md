---
title: "Capstone: Chat with Your PDFs (RAG)"
slug: capstone-chat-with-pdfs
subject: genai-capstones
lesson: 2
difficulty: intermediate
excerpt: Build a full RAG app: extract text page by page, chunk with overlap, retrieve the best chunks, answer with page citations, verify every citation, and refuse when the PDFs don't contain the answer.
---

Upload a 40-page college handbook and ask "What's the minimum attendance?" The app answers in one line **and** says "page 12", so you can check. That's **Chat with your PDFs**, the classic RAG project. This capstone builds every stage and adds the step most tutorials skip: verifying the citations.

::scene The handbook detective
📄 | Upload | Dev uploads a college handbook PDF. | dev
✂️ | Chunk | Byte splits each page into small overlapping pieces and remembers the page number of each. | byte
🔎 | Retrieve | "Minimum attendance?" Chip finds the two most relevant chunks. | chip
📌 | Cite | The answer says "75% [p.2]". Bugsy checks that page 2 really was retrieved. | bugsy
::end

## The pipeline

1. **Extract** text per page (`pypdf`, or a vision model for scanned PDFs).
2. **Chunk** each page with overlap, keeping the page number.
3. **Embed / index** the chunks.
4. **Retrieve** the top chunks for a question.
5. **Answer** with citations like `[p.2]`, only from those chunks.
6. **Verify** the citations, or refuse.

## Steps 1–2: pages and chunks

```python
pages = {
    1: "Welcome to the college. Classes start in July. The library is open from 8 am to 8 pm on weekdays.",
    2: "Attendance rules: students need at least 75% attendance in each subject to sit the final exam. "
       "Medical leave needs a certificate within 7 days.",
    3: "Fees can be paid in two instalments. A late fee of Rs 500 applies after the due date.",
}

def chunk(text, size=12, overlap=4):
    words = text.split()
    step = size - overlap
    return [" ".join(words[i:i + size]) for i in range(0, max(len(words) - overlap, 1), step)]

chunks = [{"page": p, "text": c} for p, text in pages.items() for c in chunk(text)]
print(len(chunks), "chunks;", chunks[2])
```

Output:

```text
7 chunks; {'page': 2, 'text': 'Attendance rules: students need at least 75% attendance in each subject to'}
```

In a real app, extraction looks like this:

```text
from pypdf import PdfReader
pages = {i + 1: page.extract_text() for i, page in enumerate(PdfReader("handbook.pdf").pages)}
```

## Steps 3–4: retrieve

```python
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

vec = TfidfVectorizer(stop_words="english").fit([c["text"] for c in chunks])
M = vec.transform([c["text"] for c in chunks])

def retrieve(question, k=2):
    sims = cosine_similarity(vec.transform([question]), M)[0]
    top = sims.argsort()[::-1][:k]
    return [(chunks[i]["page"], round(float(sims[i]), 2)) for i in top if sims[i] > 0]

print(retrieve("minimum attendance for the final exam"))
print(retrieve("late fee amount"))
print(retrieve("hostel curfew time"))
```

Output:

```text
[(2, 0.48), (2, 0.39)]
[(3, 0.52), (3, 0.52)]
[]
```

The hostel question finds nothing, so the app should say "The documents don't mention this" instead of guessing.

## Step 5: answer with citations

```python
import anthropic

client = anthropic.Anthropic()
def answer(question):
    hits = retrieve(question, k=4)
    if not hits:
        return "I couldn't find this in your documents."
    context = "\n".join(f"<chunk page='{c['page']}'>{c['text']}</chunk>"
                        for c in chunks if c["page"] in {p for p, _ in hits})
    r = client.messages.create(model="claude-opus-5-5", max_tokens=300,
        system="Answer only from the chunks. After each fact, cite its page like [p.2]. "
               "If the chunks don't contain the answer, say so.",
        messages=[{"role": "user", "content": f"{context}\n\nQuestion: {question}"}])
    return r.content[0].text
```

## Step 6: verify citations

Models sometimes cite a page they weren't given. Check every citation against the retrieved pages:

::walk python | Checking citations
import re
answer = "You need 75% attendance [p.2]. The late fee is Rs 500 [p.5]."
retrieved_pages = {2, 3}
cited = {int(p) for p in re.findall(r"\[p\.(\d+)\]", answer)}
bad = sorted(cited - retrieved_pages)
print(sorted(cited), bad)
::notes
1: Regular expressions find the citations.
2: A model answer with two citations. // vars: answer=two facts, two citations
3: The pages we actually sent to the model. // vars: retrieved_pages={2, 3}
4: Pull every [p.N] out of the answer. // vars: cited={2, 5}
5: Citations to pages we never retrieved can't be grounded. // deep: When bad is non-empty, you can drop that sentence, ask the model to fix it, or show a warning. Teams often log the rate of bad citations as a key quality metric. // vars: bad=[5]
6: Page 5 was never retrieved, so the late-fee sentence is unverified and should be removed or fixed. // out: [2, 5] [5]
::end

## Make it portfolio-ready

- **Upload UI:** Streamlit `st.file_uploader(accept_multiple_files=True)` and a chat panel.
- **Better retrieval:** embeddings + a vector DB, hybrid search (keywords + meaning), re-ranking.
- **Scanned PDFs and tables:** send page images to a vision model (Multimodal AI module).
- **Clickable citations:** show the chunk text when the user taps `[p.2]`.
- **Evaluation:** 20 questions with known pages; report retrieval hit rate and bad-citation rate.

::tip Delete uploaded files after the session (or let users delete them) and say so on the page. Users trust a "your files are deleted after 1 hour" line, and it keeps you on the right side of privacy rules.

## Summary

- RAG pipeline: extract → chunk with page numbers → index → retrieve → answer with citations.
- Refuse when retrieval finds nothing relevant.
- Verify every citation against the retrieved pages.
- Report retrieval and citation quality as numbers.
