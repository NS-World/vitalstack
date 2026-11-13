#include <algorithm>
#include <iostream>
#include <vector>
using namespace std;

vector<vector<int>> threeSum(vector<int> nums) {
    sort(nums.begin(), nums.end());
    vector<vector<int>> out;
    int n = nums.size();
    for (int i = 0; i < n - 2; i++) {
        if (i > 0 && nums[i] == nums[i - 1]) continue;   // same first number: skip
        if (nums[i] > 0) break;                          // all remaining are positive
        int lo = i + 1, hi = n - 1;
        while (lo < hi) {
            int total = nums[i] + nums[lo] + nums[hi];
            if (total < 0) lo++;
            else if (total > 0) hi--;
            else {
                out.push_back({nums[i], nums[lo], nums[hi]});
                lo++;
                hi--;
                while (lo < hi && nums[lo] == nums[lo - 1]) lo++;   // skip duplicate seconds
            }
        }
    }
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
    print(threeSum({-1, 0, 1, 2, -1, -4}));  // [[-1, -1, 2], [-1, 0, 1]]
    print(threeSum({0, 1, 1}));              // []
    print(threeSum({0, 0, 0, 0}));           // [[0, 0, 0]]
}
