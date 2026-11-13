#include <algorithm>
#include <functional>
#include <iostream>
#include <queue>
#include <unordered_map>
#include <utility>
#include <vector>
using namespace std;

int networkDelayTime(const vector<vector<int>>& times, int n, int k) {
    unordered_map<int, vector<pair<int, int>>> graph;
    for (const auto& t : times) graph[t[0]].push_back({t[1], t[2]});
    unordered_map<int, int> arrived;                                       // node -> final time
    priority_queue<pair<int, int>, vector<pair<int, int>>, greater<>> heap; // min-heap of {time, node}
    heap.push({0, k});
    while (!heap.empty()) {
        auto [t, node] = heap.top();
        heap.pop();
        if (arrived.count(node)) continue;                                 // already reached faster
        arrived[node] = t;
        for (auto [nb, w] : graph[node]) {
            if (!arrived.count(nb)) heap.push({t + w, nb});
        }
    }
    if ((int)arrived.size() != n) return -1;
    int latest = 0;
    for (auto& [node, t] : arrived) latest = max(latest, t);
    return latest;
}

int main() {
    cout << networkDelayTime({{2, 1, 1}, {2, 3, 1}, {3, 4, 1}}, 4, 2) << "\n";  // 2
    cout << networkDelayTime({{1, 2, 1}}, 2, 1) << "\n";                        // 1
    cout << networkDelayTime({{1, 2, 1}}, 2, 2) << "\n";                        // -1
    cout << networkDelayTime({{1, 2, 4}, {1, 3, 1}, {3, 2, 1}}, 3, 1) << "\n";  // 2
}
