#include <iostream>
#include <vector>
using namespace std;

void choose(const vector<int>& nums, size_t i, vector<int>& current, vector<vector<int>>& out) {
    if (i == nums.size()) {
        out.push_back(current);                  // push_back copies current
        return;
    }
    current.push_back(nums[i]);                  // take nums[i]
    choose(nums, i + 1, current, out);
    current.pop_back();                          // undo (backtrack)
    choose(nums, i + 1, current, out);           // skip nums[i]
}

vector<vector<int>> subsets(const vector<int>& nums) {
    vector<vector<int>> out;
    vector<int> current;
    choose(nums, 0, current, out);
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
    print(subsets({1, 2, 3}));                    // [[1, 2, 3], [1, 2], [1, 3], [1], [2, 3], [2], [3], []]
    cout << subsets({1, 2, 3, 4}).size() << "\n"; // 16
}
