#include <algorithm>
#include <iostream>
#include <string>
#include <unordered_map>
using namespace std;

int lengthOfLongestSubstring(const string& s) {
    unordered_map<char, int> last;                 // letter -> last index seen
    int left = 0, best = 0;
    for (int right = 0; right < (int)s.size(); right++) {
        char ch = s[right];
        auto it = last.find(ch);
        if (it != last.end() && it->second >= left) left = it->second + 1;   // jump past the old copy
        last[ch] = right;
        best = max(best, right - left + 1);
    }
    return best;
}

int main() {
    cout << lengthOfLongestSubstring("abcabcbb") << "\n";  // 3
    cout << lengthOfLongestSubstring("bbbbb") << "\n";     // 1
    cout << lengthOfLongestSubstring("pwwkew") << "\n";    // 3
    cout << lengthOfLongestSubstring("") << "\n";          // 0
    cout << lengthOfLongestSubstring("abba") << "\n";      // 2
}
