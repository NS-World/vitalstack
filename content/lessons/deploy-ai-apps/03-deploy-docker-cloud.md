---
title: "Docker and Cloud Deployment"
slug: deploy-docker-cloud
subject: deploy-ai-apps
lesson: 3
difficulty: intermediate
excerpt: Package your AI app in a Docker container and run it in the cloud: a Dockerfile line by line, keeping secrets out of the image, environment variables, choosing a host, a monthly cost estimate and a launch checklist.
---

"It works on my laptop" isn't a deployment. **Docker** packs your app, its Python version and its libraries into one **image** that runs the same everywhere. Cloud platforms then run that image for you, restart it when it crashes, and give it a public HTTPS address.

::scene The lunchbox
🍱 | Pack | Dev packs the app, Python and every library into one container image: a sealed lunchbox. | dev
🚚 | Ship | The same box runs on Chip's laptop, a test server and the cloud. No "missing library" surprises. | chip
🔐 | No keys inside | Byte checks the box: the API key isn't packed in. The cloud adds it at start-up. | byte
📈 | Live | Bugsy opens the public link and sees the health check say "ok". | bugsy
::end

## The Dockerfile

```dockerfile
FROM python:3.12-slim
WORKDIR /app
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt
COPY . .
RUN useradd --create-home appuser
USER appuser
EXPOSE 8080
CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8080"]
```

| Line | Why |
|---|---|
| `FROM python:3.12-slim` | A small official Python base image |
| `COPY requirements.txt` then `pip install` | Installed before your code, so Docker caches this slow step when only code changes |
| `COPY . .` | Your code |
| `useradd` + `USER` | Don't run as root: a safer default |
| `CMD` | How the container starts |

And a `.dockerignore` so secrets and junk never enter the image:

```text
.env
.git
__pycache__/
*.ipynb
.streamlit/secrets.toml
```

Build and run locally:

```bash
docker build -t study-buddy .
docker run -p 8080:8080 --env-file .env study-buddy
```

## Configuration from the environment

The image has no secrets; the platform passes them in as **environment variables** at start-up:

::walk python | Reading config safely at start-up
env = {"ANTHROPIC_API_KEY": "set", "PORT": "8080"}
port = int(env.get("PORT", "8000"))
missing = [k for k in ("ANTHROPIC_API_KEY", "APP_KEY") if k not in env]
print(port, missing)
::notes
1: What the platform provided (in real code this is os.environ). // vars: env={'ANTHROPIC_API_KEY': 'set', 'PORT': '8080'}
2: Many platforms tell you which port to listen on through PORT; fall back to 8000 locally. // vars: port=8080
3: List every required secret that's missing. // deep: Fail fast: if a required secret is missing, stop at start-up with a clear message. That's far better than starting fine and crashing on the first user's request. // vars: missing=['APP_KEY']
4: APP_KEY wasn't set, so the app should refuse to start and tell you exactly why. // out: 8080 ['APP_KEY']
::end

## Pre-flight check

```python
files = {"Dockerfile", "requirements.txt", "main.py", ".env"}
gitignore = {".env", "__pycache__/"}
checks = {
    "Dockerfile present": "Dockerfile" in files,
    "requirements.txt present": "requirements.txt" in files,
    ".env kept out of git": ".env" in gitignore,
    ".dockerignore present": ".dockerignore" in files,
}
for name, ok in checks.items():
    print("✅" if ok else "❌", name)
```

Output:

```text
✅ Dockerfile present
✅ requirements.txt present
✅ .env kept out of git
❌ .dockerignore present
```

The missing `.dockerignore` matters: without it, `COPY . .` would copy `.env`, and your API key would be inside the image for anyone who gets it.

## Where to run it

| Option | Good for |
|---|---|
| Streamlit Community Cloud, Hugging Face Spaces | Free demos and learning projects |
| Render, Railway, Fly.io | Simple container hosting with a free or cheap tier |
| Google Cloud Run, AWS App Runner, Azure Container Apps | Serverless containers that scale to zero and grow with traffic |
| A VPS (a small Linux server) | Full control; you handle updates and security |

All of them can build from your GitHub repository or a container registry, set environment variables in a dashboard and give you HTTPS.

## What will it cost?

```python
users_per_day, questions_per_user = 300, 5
tokens_in, tokens_out = 1200, 400
price_in, price_out = 3.00, 15.00          # example $ per million tokens
api = users_per_day * questions_per_user * 30 * (tokens_in * price_in + tokens_out * price_out) / 1e6
hosting = 7                                 # example small container, $ per month
print(f"API ${api:,.2f} + hosting ${hosting} = ${api + hosting:,.2f} per month")
```

Output:

```text
API $432.00 + hosting $7 = $439.00 per month
```

For AI apps, the model bill is usually far bigger than hosting. Caching, shorter prompts and a smaller model for easy questions cut it fastest. (Prices are illustrative; check your provider's current pricing.)

## Launch checklist

- [ ] Secrets only in the platform's environment settings, never in Git or the image.
- [ ] A `/health` endpoint the platform can check.
- [ ] Rate limits per user and a spending limit set in your AI provider's console.
- [ ] Logs with request ids and token counts (no personal data).
- [ ] An alert when errors or costs jump.
- [ ] A rollback plan: redeploy the previous image tag.

::tip Set a monthly spending limit (or budget alert) in your AI provider's dashboard before you share the link. It's the cheapest insurance in all of AI engineering.

## Summary

- Docker packages your app and its dependencies into one image that runs anywhere.
- Keep secrets out with `.dockerignore`, and pass them in as environment variables.
- Pick a host from free demo platforms to serverless containers.
- Estimate monthly cost (the model is usually the biggest part) and follow a launch checklist.

🎉 You've finished **Deploying AI Apps**.
