#include <algorithm>
#include <iostream>
#include <vector>
using namespace std;

vector<vector<int>> merge(vector<vector<int>> intervals) {
    sort(intervals.begin(), intervals.end());
    vector<vector<int>> merged;
    for (const auto& iv : intervals) {
        if (!merged.empty() && iv[0] <= merged.back()[1]) merged.back()[1] = max(merged.back()[1], iv[1]);
        else merged.push_back({iv[0], iv[1]});
    }
    return merged;
}

void print(const vector<vector<int>>& g) {
    cout << "[";
    for (size_t i = 0; i < g.size(); i++) cout << (i ? ", " : "") << "[" << g[i][0] << ", " << g[i][1] << "]";
    cout << "]\n";
}

int main() {
    print(merge({{1, 3}, {2, 6}, {8, 10}, {15, 18}}));  // [[1, 6], [8, 10], [15, 18]]
    print(merge({{1, 4}, {4, 5}}));                     // [[1, 5]]
    print(merge({{1, 10}, {2, 3}}));                    // [[1, 10]]
    print(merge({{5, 6}, {1, 2}}));                     // [[1, 2], [5, 6]]
}
