#include <algorithm>
#include <iostream>
#include <vector>
using namespace std;

void build(const vector<int>& candidates, size_t start, int remaining, vector<int>& current, vector<vector<int>>& out) {
    if (remaining == 0) {
        out.push_back(current);
        return;
    }
    for (size_t i = start; i < candidates.size(); i++) {
        int c = candidates[i];
        if (c > remaining) break;                              // sorted: nothing later fits
        current.push_back(c);
        build(candidates, i, remaining - c, current, out);     // i, not i + 1: reuse allowed
        current.pop_back();
    }
}

vector<vector<int>> combinationSum(vector<int> candidates, int target) {
    sort(candidates.begin(), candidates.end());
    vector<vector<int>> out;
    vector<int> current;
    build(candidates, 0, target, current, out);
    return out;
}

void print(const vector<vector<int>>& g) {
    cout << "[";
    for (size_t i = 0; i < g.size(); i++) {
        cout << (i ? ", " : "") << "[";
        for (size_t j = 0; j < g[i].size(); j++) cout << (j ? ", " : "") << g[i][j];
        cout << "]";
    }
    cout << "]\n";
}

int main() {
    print(combinationSum({2, 3, 6, 7}, 7));  // [[2, 2, 3], [7]]
    print(combinationSum({2, 3, 5}, 8));     // [[2, 2, 2, 2], [2, 3, 3], [3, 5]]
    print(combinationSum({2}, 1));           // []
}
