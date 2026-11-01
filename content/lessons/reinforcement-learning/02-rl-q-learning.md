---
title: "Q-Learning on a Grid World"
slug: rl-q-learning
subject: reinforcement-learning
lesson: 2
difficulty: intermediate
excerpt: Train an agent to find the exit of a maze with Q-learning: the Q-table, the update rule, epsilon-greedy exploration, and how Deep Q-Networks scale the same idea, all in runnable Python.
---

Q-learning is the classic RL algorithm, and it fits in about 20 lines. The agent keeps a table of **Q-values**: for each state and action, "how much total reward can I expect if I take this action here and act well afterwards?". Then it always picks the action with the highest Q.

::scene Escaping the maze
🧭 | Start | The agent starts in the top-left corner of a 4×4 grid. The exit is bottom-right; one square is a pit. | dev
🎲 | Wander | Early episodes are chaotic: it falls into the pit, bumps walls, wanders in circles. | bugsy
📒 | Q-table | After every move it updates one number in its table: good moves grow, bad ones shrink. | chip
🏁 | Mastered | After 500 episodes it walks straight to the exit, around the pit, every time. | byte
::end

## The Q-learning update

After taking action *a* in state *s*, getting reward *r* and landing in *s′*:

**Q(s, a) ← Q(s, a) + α × [ r + γ × max Q(s′, ·) − Q(s, a) ]**

::walk python | One Q-value update
q_sa = 0.0
reward = -1.0
best_next = 5.0
alpha, gamma = 0.5, 0.9
target = reward + gamma * best_next
q_sa = q_sa + alpha * (target - q_sa)
print(target, q_sa)
::notes
1: Our current estimate for "go right from this square". // vars: q_sa=0.0
2: Taking the step cost −1. // vars: reward=-1.0
3: From the next square, the best action is currently estimated at 5. // vars: best_next=5.0
4: Learning rate α (how fast to update) and discount γ. // vars: alpha=0.5, gamma=0.9
5: The **target**: reward now plus the discounted best future: −1 + 0.9 × 5 = 3.5. // deep: Using max over next actions (not the action actually taken next) makes Q-learning "off-policy": it learns the best policy even while exploring randomly. SARSA uses the actual next action instead. // vars: target=3.5
6: Move the estimate halfway towards the target. // vars: q_sa=1.75
7: Good news flows backwards from the exit, one update at a time. // out: 3.5 1.75
::end

## Full training on a grid world

```python
import numpy as np

rng = np.random.default_rng(1)
SIZE, GOAL, PIT = 4, (3, 3), (1, 2)
MOVES = [(-1, 0), (1, 0), (0, -1), (0, 1)]           # up, down, left, right
Q = np.zeros((SIZE, SIZE, 4))
alpha, gamma = 0.5, 0.95

def step(state, a):
    r, c = state[0] + MOVES[a][0], state[1] + MOVES[a][1]
    if not (0 <= r < SIZE and 0 <= c < SIZE):
        return state, -2, False                       # bumped a wall
    if (r, c) == PIT:
        return (r, c), -10, True
    if (r, c) == GOAL:
        return (r, c), 10, True
    return (r, c), -1, False

for episode in range(500):
    s, done = (0, 0), False
    epsilon = max(0.05, 1 - episode / 300)             # explore a lot early, less later
    while not done:
        a = rng.integers(4) if rng.random() < epsilon else int(np.argmax(Q[s]))
        s2, r, done = step(s, a)
        Q[s][a] += alpha * (r + gamma * (0 if done else Q[s2].max()) - Q[s][a])
        s = s2

s, path = (0, 0), [(0, 0)]
while s != GOAL and len(path) < 20:
    s, _, _ = step(s, int(np.argmax(Q[s])))
    path.append(s)
print("learned path:", path)
print("steps:", len(path) - 1, "| passes the pit?", PIT in path)
```

Output:

```text
learned path: [(0, 0), (0, 1), (1, 1), (2, 1), (3, 1), (3, 2), (3, 3)]
steps: 6 | passes the pit? False
```

Six steps is the shortest possible route, and it avoids the pit. Nobody programmed the route; the agent found it from rewards alone.

## The learned policy as arrows

```python
arrows = "↑↓←→"
for r in range(SIZE):
    print(" ".join("G" if (r, c) == GOAL else "X" if (r, c) == PIT else arrows[int(np.argmax(Q[r, c]))] for c in range(SIZE)))
```

Output:

```text
→ ↓ ← ↓
↓ ↓ X ↓
→ ↓ ↓ ↓
→ → → G
```

(Squares the agent rarely visits may keep odd arrows; they don't matter for the route it actually takes.)

## From tables to deep networks

A table works for 16 squares. A game screen has more possible states than atoms in the universe. **Deep Q-Networks (DQN)** replace the table with a neural network that takes the state (for example, game pixels) and outputs a Q-value per action. Tricks like **experience replay** and a **target network** make training stable. Libraries such as **Gymnasium** (environments like CartPole and MountainCar) and **Stable-Baselines3** (DQN, PPO, A2C) let you train agents without writing everything yourself:

```python
import gymnasium as gym
from stable_baselines3 import DQN

env = gym.make("CartPole-v1")
model = DQN("MlpPolicy", env, verbose=0).learn(total_timesteps=50_000)
```

::tip Reward design is everything. If the robot gets +1 per step "for moving", it learns to walk in circles forever. Reward the outcome you actually want, and test for loopholes.

## Summary

- Q-learning stores expected future reward for each state and action.
- Update: Q ← Q + α (r + γ max Q′ − Q); explore with ε-greedy, decaying over time.
- The agent found the shortest safe path from rewards alone.
- DQN swaps the table for a neural network; Gymnasium and Stable-Baselines3 make it practical.

🎉 You've finished **Reinforcement Learning Basics**.
