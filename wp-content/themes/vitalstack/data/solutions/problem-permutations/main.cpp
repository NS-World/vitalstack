#include <iostream>
#include <vector>
using namespace std;

void fill(const vector<int>& nums, vector<bool>& used, vector<int>& current, vector<vector<int>>& out) {
    if (current.size() == nums.size()) {
        out.push_back(current);
        return;
    }
    for (size_t i = 0; i < nums.size(); i++) {
        if (!used[i]) {
            used[i] = true;
            current.push_back(nums[i]);         // choose
            fill(nums, used, current, out);     // explore
            current.pop_back();                 // un-choose
            used[i] = false;
        }
    }
}

vector<vector<int>> permute(const vector<int>& nums) {
    vector<vector<int>> out;
    vector<int> current;
    vector<bool> used(nums.size(), false);
    fill(nums, used, current, out);
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
    print(permute({1, 2, 3}));                    // [[1, 2, 3], [1, 3, 2], [2, 1, 3], [2, 3, 1], [3, 1, 2], [3, 2, 1]]
    cout << permute({1, 2, 3, 4}).size() << "\n"; // 24
    print(permute({0, 1}));                       // [[0, 1], [1, 0]]
}
