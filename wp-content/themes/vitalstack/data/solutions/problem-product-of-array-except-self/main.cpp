#include <iostream>
#include <vector>
using namespace std;

vector<int> productExceptSelf(const vector<int>& nums) {
    int n = nums.size();
    vector<int> out(n);
    int left = 1;
    for (int i = 0; i < n; i++) {        // out[i] = product of everything left of i
        out[i] = left;
        left *= nums[i];
    }
    int right = 1;
    for (int i = n - 1; i >= 0; i--) {   // multiply by everything right of i
        out[i] *= right;
        right *= nums[i];
    }
    return out;
}

void print(const vector<int>& v) {
    cout << "[";
    for (size_t i = 0; i < v.size(); i++) cout << (i ? ", " : "") << v[i];
    cout << "]\n";
}

int main() {
    print(productExceptSelf({1, 2, 3, 4}));       // [24, 12, 8, 6]
    print(productExceptSelf({-1, 1, 0, -3, 3}));  // [0, 0, 9, 0, 0]
}
