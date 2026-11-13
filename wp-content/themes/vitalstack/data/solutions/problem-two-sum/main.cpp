#include <iostream>
#include <unordered_map>
#include <vector>
using namespace std;

vector<int> twoSum(const vector<int>& nums, int target) {
    unordered_map<int, int> seen;                  // value -> index
    for (int i = 0; i < (int)nums.size(); i++) {
        int partner = target - nums[i];
        auto it = seen.find(partner);
        if (it != seen.end()) return {it->second, i};
        seen[nums[i]] = i;                         // store AFTER checking
    }
    return {};
}

void print(const vector<int>& v) {
    cout << "[";
    for (size_t i = 0; i < v.size(); i++) cout << (i ? ", " : "") << v[i];
    cout << "]\n";
}

int main() {
    print(twoSum({2, 7, 11, 15}, 9));  // [0, 1]
    print(twoSum({3, 2, 4}, 6));       // [1, 2]
    print(twoSum({3, 3}, 6));          // [0, 1]
}
