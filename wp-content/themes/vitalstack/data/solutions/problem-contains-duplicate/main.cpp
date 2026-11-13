#include <iostream>
#include <unordered_set>
#include <vector>
using namespace std;

bool containsDuplicate(const vector<int>& nums) {
    unordered_set<int> seen;
    for (int x : nums) {
        if (seen.count(x)) return true;
        seen.insert(x);
    }
    return false;
}

int main() {
    cout << boolalpha;
    cout << containsDuplicate({1, 2, 3, 1}) << "\n";  // true
    cout << containsDuplicate({1, 2, 3, 4}) << "\n";  // false
    cout << containsDuplicate({}) << "\n";            // false
}
