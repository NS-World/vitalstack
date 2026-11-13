#include <algorithm>
#include <climits>
#include <iostream>
#include <vector>
using namespace std;

int coinChange(const vector<int>& coins, int amount) {
    const int INF = INT_MAX;
    vector<int> best(amount + 1, INF);
    best[0] = 0;
    for (int a = 1; a <= amount; a++) {
        for (int c : coins) {
            if (c <= a && best[a - c] != INF) best[a] = min(best[a], best[a - c] + 1);
        }
    }
    return best[amount] == INF ? -1 : best[amount];
}

int main() {
    cout << coinChange({1, 2, 5}, 11) << "\n";  // 3
    cout << coinChange({1, 3, 4}, 6) << "\n";   // 2
    cout << coinChange({2}, 3) << "\n";         // -1
    cout << coinChange({1}, 0) << "\n";         // 0
}
