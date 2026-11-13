#include <iostream>
#include <queue>
#include <vector>
using namespace std;

bool canFinish(int n, const vector<vector<int>>& prerequisites) {
    vector<vector<int>> unlocks(n);
    vector<int> needs(n, 0);
    for (const auto& p : prerequisites) {
        unlocks[p[1]].push_back(p[0]);   // p = {course, req}
        needs[p[0]]++;
    }
    queue<int> q;
    for (int c = 0; c < n; c++) if (needs[c] == 0) q.push(c);
    int taken = 0;
    while (!q.empty()) {
        int c = q.front();
        q.pop();
        taken++;
        for (int nxt : unlocks[c]) {
            if (--needs[nxt] == 0) q.push(nxt);
        }
    }
    return taken == n;
}

int main() {
    cout << boolalpha;
    cout << canFinish(2, {{1, 0}}) << "\n";                          // true
    cout << canFinish(2, {{1, 0}, {0, 1}}) << "\n";                  // false
    cout << canFinish(4, {{1, 0}, {2, 0}, {3, 1}, {3, 2}}) << "\n";  // true
}
