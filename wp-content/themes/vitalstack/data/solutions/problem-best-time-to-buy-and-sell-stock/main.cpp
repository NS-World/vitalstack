#include <algorithm>
#include <climits>
#include <iostream>
#include <vector>
using namespace std;

int maxProfit(const vector<int>& prices) {
    int cheapest = INT_MAX, best = 0;
    for (int p : prices) {
        cheapest = min(cheapest, p);
        best = max(best, p - cheapest);
    }
    return best;
}

int main() {
    cout << maxProfit({7, 1, 5, 3, 6, 4}) << "\n";  // 5
    cout << maxProfit({7, 6, 4, 3, 1}) << "\n";     // 0
}
