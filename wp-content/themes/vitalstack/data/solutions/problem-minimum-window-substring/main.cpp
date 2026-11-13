#include <climits>
#include <iostream>
#include <string>
#include <unordered_map>
using namespace std;

string minWindow(const string& s, const string& t) {
    unordered_map<char, int> need;
    for (char ch : t) need[ch]++;
    int missing = t.size();                          // characters still needed
    int left = 0, bestStart = 0, bestLen = INT_MAX;
    for (int right = 0; right < (int)s.size(); right++) {
        char ch = s[right];
        if (need[ch] > 0) missing--;                 // this character was useful
        need[ch]--;
        while (missing == 0) {                       // window is valid: try to shrink
            if (right - left + 1 < bestLen) {
                bestStart = left;
                bestLen = right - left + 1;
            }
            need[s[left]]++;
            if (need[s[left]] > 0) missing++;        // we just dropped something required
            left++;
        }
    }
    return bestLen == INT_MAX ? "" : s.substr(bestStart, bestLen);
}

int main() {
    cout << boolalpha;
    cout << minWindow("ADOBECODEBANC", "ABC") << "\n";  // BANC
    cout << minWindow("a", "a") << "\n";                // a
    cout << (minWindow("a", "aa") == "") << "\n";       // true
}
