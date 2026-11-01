---
title: "Reinforcement Learning: Agents, Rewards and Environments"
slug: rl-agents-rewards
subject: reinforcement-learning
lesson: 1
difficulty: intermediate
excerpt: Learning by trial and error: states, actions, rewards, policies, the explore-exploit trade-off with a multi-armed bandit, and where RL is used, from games to RLHF for chatbots.
---

How do you learn to ride a bicycle? Nobody gives you labelled examples. You try, wobble, fall (a negative reward), adjust, and slowly get better. **Reinforcement learning (RL)** teaches computers the same way, and it's how AlphaGo beat world champions and how chatbots are tuned with human feedback (RLHF).

::scene Learning by trial and error
🤖 | The agent | A delivery robot stands in a warehouse. It doesn't know the layout. | dev
🗺️ | The environment | Each move changes its position (the state) and earns a reward: +10 at the shelf, −1 per step, −5 for a bump. | chip
🎲 | Explore | At first it moves almost randomly, learning what each move leads to. | byte
🏆 | Exploit | Over many runs it learns a policy: the best move from every spot. | bugsy
::end

## The vocabulary

| Term | Meaning | Warehouse robot |
|---|---|---|
| Agent | The learner and decision-maker | The robot |
| Environment | Everything the agent interacts with | The warehouse |
| State | What the agent observes now | Its grid position |
| Action | What it can do | Up, down, left, right |
| Reward | Feedback number after each action | +10 shelf, −1 per step |
| Policy | Its strategy: state → action | "From (2,3), go right" |
| Episode | One run from start to finish | One trip to the shelf |

The goal is to maximise **total future reward**, usually with a **discount** γ (gamma) so rewards sooner count a little more than rewards later.

::walk python | Discounted return
rewards = [-1, -1, -1, 10]
gamma = 0.9
G = 0.0
for r in reversed(rewards):
    G = r + gamma * G
print(round(G, 2))
::notes
1: One episode: three steps costing −1 each, then +10 for reaching the shelf. // vars: rewards=[-1, -1, -1, 10]
2: The discount factor: a reward one step later is worth 90%. // vars: gamma=0.9
3: The return G, built backwards from the end. // vars: G=0.0
4: Walk from the last reward to the first.
5: G = reward now + γ × (return from the next step). Backwards: 10, then −1 + 9 = 8, then −1 + 7.2 = 6.2, then −1 + 5.58 = 4.58. // deep: This recursive form is the Bellman idea behind almost every RL algorithm: the value of now equals the reward now plus the discounted value of what comes next. // vars: G=4.58
6: The total value of this episode from the start. A shorter route would score higher. // out: 4.58
::end

## Explore vs exploit: the multi-armed bandit

Three chai stalls; you don't know which is best. Keep visiting your favourite (exploit) or try others (explore)? The **ε-greedy** strategy explores randomly ε of the time:

```python
import numpy as np

rng = np.random.default_rng(0)
true_quality = [0.3, 0.5, 0.8]               # chance each stall makes great chai (unknown to the agent)
estimates, visits = np.zeros(3), np.zeros(3)
epsilon, total = 0.1, 0

for day in range(1000):
    stall = rng.integers(3) if rng.random() < epsilon else int(np.argmax(estimates))
    reward = float(rng.random() < true_quality[stall])
    visits[stall] += 1
    estimates[stall] += (reward - estimates[stall]) / visits[stall]   # running average
    total += reward

print("visits per stall:", visits.astype(int).tolist())
print("estimated quality:", estimates.round(2).tolist(), "| great cups:", int(total))
```

Output:

```text
visits per stall: [42, 32, 926]
estimated quality: [0.31, 0.41, 0.8] | great cups: 770
```

The agent discovered stall 3 is best and went there most days, while still checking the others now and then.

## Where RL is used

- **Games and simulations:** chess, Go, video games, robot training in simulators.
- **Recommendations and ads:** bandits choose which banner or thumbnail to show.
- **Operations:** traffic signal timing, warehouse robots, data-centre cooling.
- **LLMs:** RLHF and related methods tune chatbots using human (or AI) preferences, covered in "How AI Models Are Built".

::tip RL needs huge numbers of trials. That's why it's mostly trained in simulators or on safe, cheap actions (which ad to show), not directly on expensive real-world systems.

## Summary

- RL learns by trial and error: agent, environment, state, action, reward, policy.
- Maximise discounted future reward: G = r + γ × G_next.
- Balance exploration and exploitation (ε-greedy).
- Used in games, robotics, recommendations and RLHF for LLMs.
