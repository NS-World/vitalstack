#include <algorithm>
#include <iostream>
#include <vector>
using namespace std;

bool canJump(const vector<int>& nums) {
    int farthest = 0;
    for (int i = 0; i < (int)nums.size(); i++) {
        if (i > farthest) return false;            // this tile is in the dark
        farthest = max(farthest, i + nums[i]);
    }
    return true;
}

int main() {
    cout << boolalpha;
    cout << canJump({2, 3, 1, 1, 4}) << "\n";  // true
    cout << canJump({3, 2, 1, 0, 4}) << "\n";  // false
    cout << canJump({0}) << "\n";              // true
}
