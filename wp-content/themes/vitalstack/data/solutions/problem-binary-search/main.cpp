#include <iostream>
#include <vector>
using namespace std;

int search(const vector<int>& nums, int target) {
    int lo = 0, hi = (int)nums.size() - 1;
    while (lo <= hi) {
        int mid = lo + (hi - lo) / 2;              // avoids int overflow
        if (nums[mid] == target) return mid;
        if (nums[mid] < target) lo = mid + 1;      // target is to the right
        else hi = mid - 1;                         // target is to the left
    }
    return -1;
}

int main() {
    cout << search({-1, 0, 3, 5, 9, 12}, 9) << "\n";  // 4
    cout << search({-1, 0, 3, 5, 9, 12}, 2) << "\n";  // -1
    cout << search({5}, 5) << "\n";                   // 0
}
