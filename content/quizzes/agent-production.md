## production-cost-latency
Q: Model routing means...
- [x] Sending easy steps like classification to a smaller model
- [ ] Using one model for everything
- [ ] Changing internet routers
> In the lesson it cut cost by about half.
Q: Three independent 400, 650 and 300 ms lookups run in parallel take about...
- [x] 650 ms
- [ ] 1,350 ms
- [ ] 300 ms
> You wait for the slowest one.
Q: Perceived speed depends most on...
- [x] Time to first token
- [ ] The model's name
- [ ] The total token count only
> Stream and show progress.

## production-caching
Q: Prompt caching works best when...
- [x] Stable content comes first and stays identical between calls
- [ ] A timestamp is at the top of the prompt
- [ ] Every call is different
> Changes to the prefix break the cache.
Q: A TTL on a tool cache means...
- [x] Entries expire after a set time and are fetched fresh
- [ ] Entries never expire
- [ ] Only one entry is stored
> Match the TTL to how fast the data changes.
Q: A cache key for order lookups must include...
- [x] The user id
- [ ] Nothing but the tool name
- [ ] The current second
> Otherwise one customer may see another's data.

## production-deploy-monitor
Q: Long agent tasks should run...
- [x] As background jobs with a status endpoint
- [ ] Inside a single HTTP request that blocks for minutes
- [ ] Only on a laptop
> Return a job id immediately.
Q: A canary release...
- [x] Sends a small share of users to the new version first
- [ ] Releases to everyone at once
- [ ] Deletes the old version
> Compare metrics, then roll forward or back.
Q: Hashing the user id for canary buckets ensures...
- [x] Each user always gets the same version
- [ ] Users get random versions per message
- [ ] Nobody gets the new version
> No flip-flopping between replies.
