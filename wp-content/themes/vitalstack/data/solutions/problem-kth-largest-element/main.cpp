#include <iostream>
#include <random>
#include <vector>
using namespace std;

mt19937 rng(random_device{}());

int quickselect(const vector<int>& nums, int k) {
    int pivot = nums[uniform_int_distribution<int>(0, nums.size() - 1)(rng)];
    vector<int> bigger, smaller;
    int equal = 0;
    for (int x : nums) {
        if (x > pivot) bigger.push_back(x);
        else if (x < pivot) smaller.push_back(x);
        else equal++;
    }
    if (k <= (int)bigger.size()) return quickselect(bigger, k);
    if (k <= (int)bigger.size() + equal) return pivot;
    return quickselect(smaller, k - bigger.size() - equal);
}

int main() {
    cout << quickselect({3, 2, 1, 5, 6, 4}, 2) << "\n";           // 5
    cout << quickselect({3, 2, 3, 1, 2, 4, 5, 5, 6}, 4) << "\n";  // 4
}
