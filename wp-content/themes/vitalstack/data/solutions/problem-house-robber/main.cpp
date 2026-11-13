#include <algorithm>
#include <iostream>
#include <vector>
using namespace std;

int rob(const vector<int>& nums) {
    int twoBack = 0, oneBack = 0;
    for (int money : nums) {
        int best = max(oneBack, twoBack + money);   // skip this house, or rob it
        twoBack = oneBack;
        oneBack = best;
    }
    return oneBack;
}

int main() {
    cout << rob({1, 2, 3, 1}) << "\n";     // 4
    cout << rob({2, 7, 9, 3, 1}) << "\n";  // 12
    cout << rob({2, 1, 1, 2}) << "\n";     // 4
    cout << rob({}) << "\n";               // 0
}
