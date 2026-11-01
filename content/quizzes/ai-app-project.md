## ai-app-plan-and-design
Q: Why keep the quiz logic in core.py, separate from the web page and the provider?
- [x] So it can be tested quickly and cheaply with a fake model
- [ ] Because Streamlit requires it
- [ ] To make the app look nicer
> Separating logic from UI and provider makes testing free and fast.
Q: What is the "quote" field in each question for?
- [ ] Decoration
- [x] Proving the question comes from the notes, so invented questions can be dropped
- [ ] Showing the author's name
> If the quote isn't in the notes, the question is rejected.
Q: Why wrap the notes in <notes> tags in the prompt?
- [x] To mark them as data, not instructions, which defends against prompt injection
- [ ] To make the prompt shorter
- [ ] HTML is required by APIs
> Clear boundaries help the model treat pasted text as material.

## ai-app-build-with-streamlit
Q: What keeps the quiz on screen between Streamlit reruns?
- [ ] A global variable
- [x] st.session_state
- [ ] The API key
> Streamlit reruns the script on every click; session_state persists data.
Q: How can the same OpenAILLM class talk to Grok or a local Ollama model?
- [x] By changing base_url (and the key and model name)
- [ ] It can't
- [ ] By installing a different Python version
> Many services offer OpenAI-compatible endpoints.
Q: Why check that notes have at least 30 words before calling the model?
- [ ] The API refuses short text
- [x] To avoid paying for a request that can't produce a fair quiz
- [ ] To make the page load faster
> Validating input first saves money and gives a better message.

## ai-app-test-secure-deploy
Q: Why include bad model outputs in your tests?
- [x] Real models sometimes return them, and the app must handle them safely
- [ ] To make tests fail on purpose
- [ ] Tests need at least five cases
> Invented quotes, wrong indexes and empty replies all happen.
Q: Where does the API key go when you deploy on Streamlit Community Cloud?
- [ ] In app.py
- [ ] In requirements.txt
- [x] In the app's Secrets settings
> Never commit secrets.toml to Git.
Q: Which two limits protect a public AI app's budget?
- [x] A per-visitor daily limit and a daily spending cap
- [ ] A faster model and more tests
- [ ] A dark theme and a logo
> Plus a monthly limit in the provider's console.
