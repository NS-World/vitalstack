#include <iostream>
#include <vector>
using namespace std;

int searchRotated(const vector<int>& nums, int target) {
    int lo = 0, hi = (int)nums.size() - 1;
    while (lo <= hi) {
        int mid = lo + (hi - lo) / 2;
        if (nums[mid] == target) return mid;
        if (nums[lo] <= nums[mid]) {                       // left half sorted
            if (nums[lo] <= target && target < nums[mid]) hi = mid - 1;
            else lo = mid + 1;
        } else {                                           // right half sorted
            if (nums[mid] < target && target <= nums[hi]) lo = mid + 1;
            else hi = mid - 1;
        }
    }
    return -1;
}

int main() {
    cout << searchRotated({4, 5, 6, 7, 0, 1, 2}, 0) << "\n";  // 4
    cout << searchRotated({4, 5, 6, 7, 0, 1, 2}, 3) << "\n";  // -1
    cout << searchRotated({1}, 0) << "\n";                    // -1
    cout << searchRotated({3, 1}, 1) << "\n";                 // 1
}
