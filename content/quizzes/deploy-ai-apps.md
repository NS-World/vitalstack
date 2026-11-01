## deploy-streamlit-chat-ui
Q: Why does a Streamlit chat app store messages in st.session_state?
- [x] Streamlit reruns the script on every interaction
- [ ] It's faster than a list
- [ ] To share chats between users
> session_state survives reruns.
Q: st.write_stream is used to...
- [x] Show the reply as it arrives
- [ ] Upload files
- [ ] Log in users
> Streaming makes apps feel fast.
Q: A real per-user message limit must be stored...
- [x] On the server, like a database or Redis
- [ ] Only in session_state
- [ ] In the browser's title
> session_state resets on reload.

## deploy-fastapi-ai-api
Q: FastAPI returns 422 when...
- [x] The request data fails validation
- [ ] The server is down
- [ ] Everything worked
> Pydantic checks the body before your code runs.
Q: If the AI provider fails, your API should return...
- [x] 502 with a 'please retry' message
- [ ] 200 with an empty answer
- [ ] A Python traceback
> Catch provider errors; don't crash.
Q: Why normalise questions before caching?
- [x] So 'What is RAG?' and ' what is rag?' share one cached answer
- [ ] To hide the question
- [ ] Caching needs uppercase
> Same meaning, same key, fewer paid calls.

## deploy-docker-cloud
Q: Why add a .dockerignore with .env in it?
- [x] So secrets aren't copied into the image
- [ ] To make Docker faster only
- [ ] Docker needs it to start
> COPY . . would otherwise include your keys.
Q: Secrets should reach the container as...
- [x] Environment variables set by the platform
- [ ] Lines in the Dockerfile
- [ ] Files committed to Git
> The image itself stays secret-free.
Q: For most AI apps the biggest monthly cost is...
- [x] Model API usage
- [ ] The domain name
- [ ] The Dockerfile
> Hosting is small next to tokens.
