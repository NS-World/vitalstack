#include <iostream>
#include <stack>
#include <string>
#include <unordered_map>
using namespace std;

bool isValid(const string& s) {
    unordered_map<char, char> pairs{{')', '('}, {']', '['}, {'}', '{'}};
    stack<char> st;
    for (char ch : s) {
        if (pairs.count(ch)) {                         // closing bracket
            if (st.empty() || st.top() != pairs[ch]) return false;
            st.pop();
        } else {
            st.push(ch);                               // opening bracket
        }
    }
    return st.empty();
}

int main() {
    cout << boolalpha;
    cout << isValid("()[]{}") << "\n";    // true
    cout << isValid("{[()()]}") << "\n";  // true
    cout << isValid("(]") << "\n";        // false
    cout << isValid("([)]") << "\n";      // false
    cout << isValid("((") << "\n";        // false
    cout << isValid(")") << "\n";         // false
}
