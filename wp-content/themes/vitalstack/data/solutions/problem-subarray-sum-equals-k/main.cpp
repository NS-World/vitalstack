#include <iostream>
#include <unordered_map>
#include <vector>
using namespace std;

int subarraySum(const vector<int>& nums, int k) {
    unordered_map<int, int> seen{{0, 1}};          // prefix sum -> how many times seen
    int total = 0, count = 0;
    for (int x : nums) {
        total += x;
        auto it = seen.find(total - k);
        if (it != seen.end()) count += it->second;
        seen[total]++;
    }
    return count;
}

int main() {
    cout << subarraySum({1, 1, 1}, 2) << "\n";          // 2
    cout << subarraySum({1, 2, 3}, 3) << "\n";          // 2
    cout << subarraySum({3, -1, 2, -2, 4}, 2) << "\n";  // 4
}
