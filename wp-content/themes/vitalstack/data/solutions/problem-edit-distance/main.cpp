#include <algorithm>
#include <iostream>
#include <string>
#include <vector>
using namespace std;

int minDistance(const string& a, const string& b) {
    vector<vector<int>> dp(a.size() + 1, vector<int>(b.size() + 1, 0));
    for (size_t i = 0; i <= a.size(); i++) dp[i][0] = i;   // delete everything
    for (size_t j = 0; j <= b.size(); j++) dp[0][j] = j;   // insert everything
    for (size_t i = 1; i <= a.size(); i++) {
        for (size_t j = 1; j <= b.size(); j++) {
            if (a[i - 1] == b[j - 1]) dp[i][j] = dp[i - 1][j - 1];
            else dp[i][j] = 1 + min({dp[i - 1][j - 1],     // replace
                                     dp[i - 1][j],         // delete
                                     dp[i][j - 1]});       // insert
        }
    }
    return dp[a.size()][b.size()];
}

int main() {
    cout << minDistance("horse", "ros") << "\n";            // 3
    cout << minDistance("intention", "execution") << "\n";  // 5
    cout << minDistance("", "abc") << "\n";                 // 3
    cout << minDistance("same", "same") << "\n";            // 0
}
