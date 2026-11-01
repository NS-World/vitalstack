---
title: "Sandboxes for Code-Running Agents"
slug: agent-safety-sandboxes
subject: agent-safety
lesson: 3
difficulty: advanced
excerpt: Agents that write and run code need a cage: run model code in a separate process with a timeout, no secrets, a temporary folder and resource limits; block path tricks like ../; and use containers or hosted sandboxes with no network for real isolation.
---

Data-analysis agents, coding agents and "code interpreter" tools all **run code the model wrote**. That code might be buggy (an infinite loop), wasteful (filling the disk) or, after a prompt injection, hostile (reading your keys). Never run it in your main program. Run it in a **sandbox**: a place where the worst it can do is harmless.

::scene The playpen
🧪 | Model code | Chip writes Python to analyse a CSV. Nobody has checked it. | chip
⏱️ | Runaway | One version loops forever. The sandbox kills it after 1 second. | bugsy
🔑 | No secrets | Another tries to read the API key. The sandbox has no keys to find. | byte
📦 | Stronger walls | Dev moves it into a container with no network and a read-only system. | dev
::end

## Layer 1: a separate process with limits

```python
import resource
import subprocess
import sys
import tempfile

def limits():                                             # Linux/macOS: applied inside the child process
    resource.setrlimit(resource.RLIMIT_CPU, (5, 5))       # max 5 s of CPU
    resource.setrlimit(resource.RLIMIT_AS, (1024 ** 3, 1024 ** 3))   # max 1 GB of memory

def run_untrusted(code, timeout=1):
    with tempfile.TemporaryDirectory() as tmp:            # its own empty folder, deleted afterwards
        try:
            p = subprocess.run([sys.executable, "-I", "-c", code], cwd=tmp, env={},   # -I isolated mode, no env vars
                               capture_output=True, text=True, timeout=timeout, preexec_fn=limits)
            return p.returncode, (p.stdout or p.stderr).strip()[-80:]
        except subprocess.TimeoutExpired:
            return None, f"killed: ran longer than {timeout}s"

print(run_untrusted("print(sum(range(10)))"))
print(run_untrusted("while True: pass"))
print(run_untrusted("import os; print(os.environ.get('ANTHROPIC_API_KEY', 'no key visible'))"))
```

Output:

```text
(0, '45')
(None, 'killed: ran longer than 1s')
(0, 'no key visible')
```

Three protections in a few lines: a **timeout** stopped the infinite loop, an **empty environment** hid secrets, and a **temporary folder** keeps files away from your project. This is a useful first layer, **not** full security: the code can still read other files your user can read and use the network.

## Layer 2: block path tricks in file tools

If your agent has `read_file` and `write_file` tools, make sure paths stay inside the workspace:

::walk python | Stopping ../ escapes
from pathlib import Path
root = Path("/workspace/project")
requested = Path("/workspace/project/../../etc/passwd")
safe = requested.resolve().is_relative_to(root)
print(requested.resolve(), safe)
::notes
1: pathlib handles paths safely across systems.
2: The only folder the agent may touch. // vars: root=/workspace/project
3: The model asks for a path that looks inside the project but climbs out with ../.. // vars: requested=/workspace/project/../../etc/passwd
4: resolve() works out the real path (following .. and symbolic links), then we check it's still under root. // deep: Always check the resolved path, never the string. "startswith('/workspace/project')" is fooled by '/workspace/project-secrets' and by symlinks. Resolve first, then compare. // vars: safe=False
5: The real target is /etc/passwd, outside the project, so the tool refuses. // out: /etc/passwd False
::end

## Layer 3: containers

For real isolation, run code in a container with almost nothing allowed:

```bash
docker run --rm --network none --read-only --memory 512m --cpus 1 --pids-limit 100 \
  --user 1000:1000 -v "$PWD/job:/job" -w /job python:3.12-slim python analysis.py
```

| Flag | Protection |
|---|---|
| `--network none` | No internet: nothing can be sent out |
| `--read-only` | The system can't be modified |
| `--memory`, `--cpus`, `--pids-limit` | No resource hogging, no fork bombs |
| `--user` | Not root inside the container |
| `-v …/job` | Only the job folder is visible |

For stronger isolation still, teams use lightweight virtual machines (such as gVisor or Firecracker) under their containers.

## Layer 4: hosted sandboxes

You don't have to run sandboxes yourself:

- **Provider code-execution tools** (for example, Claude's code execution tool) run code in the provider's sandbox.
- **Sandbox services** (such as E2B or Modal) give each agent run a fresh, disposable environment through an API.
- **Agentic coding tools** often have sandbox modes that restrict file and network access for commands they run.

## A sandbox checklist

- [ ] Separate process or container for every run; destroyed afterwards.
- [ ] Timeout and CPU, memory, disk and process limits.
- [ ] No secrets inside; no access to your project unless mounted on purpose.
- [ ] No network by default; an allow-list if it truly needs some sites.
- [ ] File tools check resolved paths stay inside the workspace.
- [ ] Output size limited before it goes back to the model.

::tip Assume the code is hostile, even when it came from your own agent. A prompt injection three steps earlier can turn "analyse this CSV" into "upload ~/.ssh". The sandbox is what makes that harmless.

## Summary

- Never run model-written code in your main process.
- First layer: subprocess with timeout, empty env, temp folder and resource limits.
- File tools must check resolved paths stay inside the workspace.
- Real isolation: containers with no network, read-only and resource limits, or hosted sandboxes.

🎉 You've finished **Safety**.
