#include <algorithm>
#include <iostream>
#include <vector>
using namespace std;

int maxSubarray(const vector<int>& nums) {
    int current = nums[0], best = nums[0];
    for (size_t i = 1; i < nums.size(); i++) {
        current = max(nums[i], current + nums[i]);   // start fresh or extend
        best = max(best, current);
    }
    return best;
}

int main() {
    cout << maxSubarray({-2, 1, -3, 4, -1, 2, 1, -5, 4}) << "\n";  // 6
    cout << maxSubarray({1}) << "\n";                              // 1
    cout << maxSubarray({-3, -1, -2}) << "\n";                     // -1
}
