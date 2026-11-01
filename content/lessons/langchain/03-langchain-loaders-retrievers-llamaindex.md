---
title: "Document Loaders, Retrievers and LlamaIndex"
slug: langchain-loaders-retrievers-llamaindex
subject: langchain
lesson: 3
difficulty: intermediate
excerpt: Build RAG with frameworks: LangChain document loaders, text splitters, vector store retrievers and a retrieval chain, then the same app in LlamaIndex, and how to choose between them.
---

The RAG module built every piece by hand. Frameworks package those pieces: **loaders** read files, **splitters** chunk them, **vector stores** index them and **retrievers** fetch the best chunks. LangChain and **LlamaIndex** (which focuses on data and RAG) are the two most popular.

::scene RAG in twenty lines
📂 | Load | A loader reads every PDF in the policies folder, page by page, with page numbers kept as metadata. | dev
✂️ | Split | A text splitter cuts pages into overlapping chunks. | chip
🗄️ | Index | Chunks are embedded and saved in a vector store. | byte
💬 | Ask | A retrieval chain fetches the best chunks and asks the model, with sources. | bugsy
::end

## RAG with LangChain

```python
from langchain_community.document_loaders import PyPDFDirectoryLoader
from langchain_text_splitters import RecursiveCharacterTextSplitter
from langchain_community.vectorstores import Chroma
from langchain_openai import OpenAIEmbeddings            # or any embeddings integration
from langchain_core.prompts import ChatPromptTemplate
from langchain_core.runnables import RunnablePassthrough
from langchain_core.output_parsers import StrOutputParser

docs = PyPDFDirectoryLoader("policies/").load()                       # one Document per page
chunks = RecursiveCharacterTextSplitter(chunk_size=800, chunk_overlap=120).split_documents(docs)
store = Chroma.from_documents(chunks, OpenAIEmbeddings(), persist_directory="db")
retriever = store.as_retriever(search_kwargs={"k": 4})

def format_docs(ds):
    return "\n".join(f"[{d.metadata['source']} p{d.metadata.get('page', 0) + 1}] {d.page_content}" for d in ds)

prompt = ChatPromptTemplate.from_template(
    "Answer only from the context and cite sources in brackets. If unsure, say so.\n\n{context}\n\nQuestion: {question}")
rag = {"context": retriever | format_docs, "question": RunnablePassthrough()} | prompt | model | StrOutputParser()
print(rag.invoke("How many paid leave days do employees get?"))
```

## What a recursive splitter does (runnable)

`RecursiveCharacterTextSplitter` tries to split on paragraphs first, then lines, then sentences, then words, so chunks break at natural places:

```python
def recursive_split(text, size, seps=("\n\n", "\n", ". ", " ")):
    if len(text) <= size:
        return [text]
    for sep in seps:
        if sep in text:
            parts, out, cur = text.split(sep), [], ""
            for p in parts:
                piece = (cur + sep + p) if cur else p
                if len(piece) <= size:
                    cur = piece
                else:
                    if cur:
                        out.append(cur)
                    cur = p
            out.append(cur)
            return [c for part in out for c in recursive_split(part, size, seps)]
    return [text[i:i + size] for i in range(0, len(text), size)]

text = "Leave policy.\n\nPaid leave is 18 days. Up to 10 days carry over.\n\nSick leave is 12 days. A note is needed after 2 days."
for c in recursive_split(text, 45):
    print(repr(c))
```

Output:

```text
'Leave policy.'
'Paid leave is 18 days'
'Up to 10 days carry over.'
'Sick leave is 12 days'
'A note is needed after 2 days.'
```

## The same app in LlamaIndex

```python
from llama_index.core import VectorStoreIndex, SimpleDirectoryReader

documents = SimpleDirectoryReader("policies/").load_data()
index = VectorStoreIndex.from_documents(documents)          # chunks, embeds and stores with defaults
engine = index.as_query_engine(similarity_top_k=4)
response = engine.query("How many paid leave days do employees get?")
print(response)
for node in response.source_nodes:                           # citations
    print(node.metadata.get("file_name"), round(node.score, 2))
```

LlamaIndex's defaults get a working RAG app in five lines; its strengths are data connectors, indexing strategies and query engines.

::walk python | What a retriever returns
results = [("p2 Paid leave is 18 days.", 0.83), ("p5 Holidays list.", 0.41), ("p3 Sick leave is 12 days.", 0.62)]
k = 2
top = sorted(results, key=lambda r: r[1], reverse=True)[:k]
context = "\n".join(text for text, score in top)
print(context)
::notes
1: Scored chunks from the vector store: text with a similarity score. // vars: results=3 scored chunks
2: The retriever's k: how many chunks to pass on. // vars: k=2
3: Sort by score, keep the top k. // deep: Retrievers can also use MMR (maximal marginal relevance) to avoid near-duplicate chunks, metadata filters, or several queries at once ("multi-query"). In LangChain these are options on as_retriever or separate retriever classes. // vars: top=[('p2 Paid leave is 18 days.', 0.83), ('p3 Sick leave is 12 days.', 0.62)]
4: Join them into the context block for the prompt. // vars: context=p2 …\np3 …
5: Exactly what "{context}" receives in the chain. // out: p2 Paid leave is 18 days.\np3 Sick leave is 12 days.
::end

## LangChain vs LlamaIndex

| | LangChain | LlamaIndex |
|---|---|---|
| Focus | General LLM apps: chains, tools, agents (with LangGraph) | Data + retrieval: loaders, indexes, query engines |
| Fastest start | A few lines more | Very fast RAG defaults |
| Agents | LangGraph | LlamaIndex agents and workflows |
| Common choice | Apps with many integrations and agent flows | Document-heavy RAG apps |

They also work together, and both integrate with every major model and vector database.

::tip Whatever framework you use, keep your evaluation set from the RAG module and rerun it. Framework defaults (chunk size, k, prompt) are a starting point, not a tuned system.

## Summary

- Loaders read files with metadata; splitters chunk at natural boundaries; retrievers fetch top-k.
- LangChain: compose `retriever | format | prompt | model | parser`.
- LlamaIndex: `VectorStoreIndex.from_documents(...).as_query_engine()` for quick RAG.
- Choose by focus; evaluate with your own test set either way.

🎉 You've finished **LangChain and LlamaIndex**.
