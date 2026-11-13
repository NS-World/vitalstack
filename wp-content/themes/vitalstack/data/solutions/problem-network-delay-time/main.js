// Dijkstra with a simple "pick the closest unfinished node" scan: O(V^2), fine for small graphs.
// (For big graphs use a binary heap, as in the Python version.)
function networkDelayTime(times, n, k) {
  const graph = new Map();
  for (const [u, v, w] of times) {
    if (!graph.has(u)) graph.set(u, []);
    graph.get(u).push([v, w]);
  }
  const best = new Map([[k, 0]]);        // node -> best time found so far
  const arrived = new Map();             // node -> final time
  while (best.size > arrived.size) {
    let node = null;
    for (const [x, t] of best) {
      if (!arrived.has(x) && (node === null || t < best.get(node))) node = x;
    }
    const t = best.get(node);
    arrived.set(node, t);
    for (const [nb, w] of graph.get(node) || []) {
      if (!arrived.has(nb) && (!best.has(nb) || t + w < best.get(nb))) best.set(nb, t + w);
    }
  }
  return arrived.size === n ? Math.max(...arrived.values()) : -1;
}

console.log(networkDelayTime([[2, 1, 1], [2, 3, 1], [3, 4, 1]], 4, 2));  // 2
console.log(networkDelayTime([[1, 2, 1]], 2, 1));                        // 1
console.log(networkDelayTime([[1, 2, 1]], 2, 2));                        // -1
console.log(networkDelayTime([[1, 2, 4], [1, 3, 1], [3, 2, 1]], 3, 1));  // 2
