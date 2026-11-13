#include <iostream>
#include <string>
using namespace std;

bool isAnagram(const string& s, const string& t) {
    if (s.size() != t.size()) return false;
    int count[26] = {0};                    // one box per letter a-z
    for (char ch : s) count[ch - 'a']++;
    for (char ch : t) {
        if (count[ch - 'a'] == 0) return false;
        count[ch - 'a']--;
    }
    return true;
}

int main() {
    cout << boolalpha;
    cout << isAnagram("anagram", "nagaram") << "\n";  // true
    cout << isAnagram("rat", "car") << "\n";          // false
    cout << isAnagram("ab", "a") << "\n";             // false
}
