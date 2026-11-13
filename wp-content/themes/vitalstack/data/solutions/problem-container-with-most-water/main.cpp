#include <algorithm>
#include <iostream>
#include <vector>
using namespace std;

int maxArea(const vector<int>& height) {
    int left = 0, right = (int)height.size() - 1, best = 0;
    while (left < right) {
        int water = (right - left) * min(height[left], height[right]);
        best = max(best, water);
        if (height[left] < height[right]) left++;   // move the shorter wall
        else right--;
    }
    return best;
}

int main() {
    cout << maxArea({1, 8, 6, 2, 5, 4, 8, 3, 7}) << "\n";  // 49
    cout << maxArea({1, 1}) << "\n";                       // 1
    cout << maxArea({4, 3, 2, 1, 4}) << "\n";              // 16
}
