#include <algorithm>
#include <iostream>
#include <utility>
#include <vector>
using namespace std;

class MinStack {
    vector<pair<int, int>> items;                  // (value, min at or below)
public:
    void push(int x) {
        int currentMin = items.empty() ? x : min(x, items.back().second);
        items.push_back({x, currentMin});
    }
    void pop() { items.pop_back(); }
    int top() const { return items.back().first; }
    int getMin() const { return items.back().second; }
};

int main() {
    MinStack s;
    s.push(-2);
    s.push(0);
    s.push(-3);
    cout << s.getMin() << "\n";  // -3
    s.pop();
    cout << s.top() << "\n";     // 0
    cout << s.getMin() << "\n";  // -2
}
