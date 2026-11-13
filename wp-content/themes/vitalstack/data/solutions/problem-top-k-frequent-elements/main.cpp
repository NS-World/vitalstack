#include <iostream>
#include <unordered_map>
#include <vector>
using namespace std;

vector<int> topKBuckets(const vector<int>& nums, int k) {
    unordered_map<int, int> counts;
    vector<int> order;                                     // values in first-seen order
    for (int x : nums) if (counts[x]++ == 0) order.push_back(x);
    vector<vector<int>> shelves(nums.size() + 1);          // shelves[c] = values seen c times
    for (int v : order) shelves[counts[v]].push_back(v);
    vector<int> out;
    for (int c = (int)shelves.size() - 1; c > 0; c--) {
        for (int value : shelves[c]) {
            out.push_back(value);
            if ((int)out.size() == k) return out;
        }
    }
    return out;
}

void print(const vector<int>& v) {
    cout << "[";
    for (size_t i = 0; i < v.size(); i++) cout << (i ? ", " : "") << v[i];
    cout << "]\n";
}

int main() {
    print(topKBuckets({1, 1, 1, 2, 2, 3}, 2));        // [1, 2]
    print(topKBuckets({5, 5, 6, 6, 6, 7, 5, 5}, 1));  // [5]
}
