#include <algorithm>
#include <iostream>
#include <vector>
using namespace std;

long long hours(const vector<int>& piles, int k) {
    long long total = 0;
    for (int p : piles) total += (p + k - 1) / k;   // ceil(p / k)
    return total;
}

int minEatingSpeed(const vector<int>& piles, int h) {
    int lo = 1, hi = *max_element(piles.begin(), piles.end());
    while (lo < hi) {
        int mid = lo + (hi - lo) / 2;
        if (hours(piles, mid) <= h) hi = mid;      // fast enough: try slower
        else lo = mid + 1;                         // too slow
    }
    return lo;
}

int main() {
    cout << minEatingSpeed({3, 6, 7, 11}, 8) << "\n";        // 4
    cout << minEatingSpeed({30, 11, 23, 4, 20}, 5) << "\n";  // 30
    cout << minEatingSpeed({30, 11, 23, 4, 20}, 6) << "\n";  // 23
}
