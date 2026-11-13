#include <algorithm>
#include <iostream>
#include <unordered_set>
#include <vector>
using namespace std;

int longestConsecutive(const vector<int>& nums) {
    unordered_set<int> present(nums.begin(), nums.end());
    int best = 0;
    for (int x : present) {
        if (!present.count(x - 1)) {               // x starts a run
            int length = 1;
            while (present.count(x + length)) length++;
            best = max(best, length);
        }
    }
    return best;
}

int main() {
    cout << longestConsecutive({100, 4, 200, 1, 3, 2}) << "\n";          // 4
    cout << longestConsecutive({0, 3, 7, 2, 5, 8, 4, 6, 0, 1}) << "\n";  // 9
    cout << longestConsecutive({}) << "\n";                              // 0
}
