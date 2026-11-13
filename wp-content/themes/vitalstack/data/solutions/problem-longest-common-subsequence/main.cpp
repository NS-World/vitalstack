#include <algorithm>
#include <iostream>
#include <string>
#include <vector>
using namespace std;

int longestCommonSubsequence(const string& a, const string& b) {
    vector<vector<int>> dp(a.size() + 1, vector<int>(b.size() + 1, 0));
    for (size_t i = 1; i <= a.size(); i++) {
        for (size_t j = 1; j <= b.size(); j++) {
            if (a[i - 1] == b[j - 1]) dp[i][j] = dp[i - 1][j - 1] + 1;
            else dp[i][j] = max(dp[i - 1][j], dp[i][j - 1]);
        }
    }
    return dp[a.size()][b.size()];
}

int main() {
    cout << longestCommonSubsequence("abcde", "ace") << "\n";  // 3
    cout << longestCommonSubsequence("abc", "abc") << "\n";    // 3
    cout << longestCommonSubsequence("abc", "def") << "\n";    // 0
}
