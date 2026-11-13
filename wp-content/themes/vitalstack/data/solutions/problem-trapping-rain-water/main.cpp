#include <algorithm>
#include <iostream>
#include <vector>
using namespace std;

int trap(const vector<int>& height) {
    int left = 0, right = (int)height.size() - 1;
    int leftMax = 0, rightMax = 0, water = 0;
    while (left < right) {
        if (height[left] < height[right]) {
            leftMax = max(leftMax, height[left]);
            water += leftMax - height[left];
            left++;
        } else {
            rightMax = max(rightMax, height[right]);
            water += rightMax - height[right];
            right--;
        }
    }
    return water;
}

int main() {
    cout << trap({0, 1, 0, 2, 1, 0, 1, 3, 2, 1, 2, 1}) << "\n";  // 6
    cout << trap({4, 2, 0, 3, 2, 5}) << "\n";                    // 9
    cout << trap({}) << "\n";                                    // 0
}
