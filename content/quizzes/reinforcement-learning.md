## rl-agents-rewards
Q: In reinforcement learning, an agent learns from...
- [x] Rewards it receives after actions
- [ ] Labelled examples only
- [ ] Clusters
> It tries actions and learns which lead to reward.
Q: Exploration vs exploitation means balancing...
- [x] Trying new actions vs using the best known one
- [ ] Training vs testing
- [ ] CPU vs GPU
> ε-greedy explores with probability ε.
Q: A discount factor below 1 makes the agent...
- [x] Value near rewards more than distant ones
- [ ] Ignore all rewards
- [ ] Act randomly forever
> Future rewards are multiplied by γ each step.

## rl-q-learning
Q: A Q-table stores...
- [x] The expected value of each action in each state
- [ ] Images
- [ ] Training labels
> Q(s, a) estimates future reward.
Q: The Q-learning update moves Q(s, a) towards...
- [x] Reward + γ × the best Q-value of the next state
- [ ] Zero
- [ ] A random number
> That target is the Bellman equation.
Q: Where is RL used with large language models?
- [x] RLHF, to fine-tune models on human preferences
- [ ] Tokenising text
- [ ] Storing embeddings
> Reward models guide the LLM towards preferred answers.
