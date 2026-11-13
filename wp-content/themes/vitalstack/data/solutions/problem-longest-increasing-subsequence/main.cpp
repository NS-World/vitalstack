#include <algorithm>
#include <iostream>
#include <vector>
using namespace std;

int lisFast(const vector<int>& nums) {
    vector<int> tails;
    for (int x : nums) {
        auto it = lower_bound(tails.begin(), tails.end(), x);   // = bisect_left
        if (it == tails.end()) tails.push_back(x);              // new, longer pile
        else *it = x;                                           // smaller top for that length
    }
    return tails.size();
}

int main() {
    cout << lisFast({10, 9, 2, 5, 3, 7, 101, 18}) << "\n";  // 4
    cout << lisFast({0, 1, 0, 3, 2, 3}) << "\n";            // 4
}
