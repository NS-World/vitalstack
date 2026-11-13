#include <iostream>
#include <vector>
using namespace std;

vector<int> dailyTemperatures(const vector<int>& temps) {
    vector<int> out(temps.size(), 0);
    vector<int> waiting;                           // indexes, temps decreasing
    for (int i = 0; i < (int)temps.size(); i++) {
        while (!waiting.empty() && temps[waiting.back()] < temps[i]) {
            int j = waiting.back();
            waiting.pop_back();
            out[j] = i - j;                        // day j finally got a warmer day
        }
        waiting.push_back(i);
    }
    return out;
}

void print(const vector<int>& v) {
    cout << "[";
    for (size_t i = 0; i < v.size(); i++) cout << (i ? ", " : "") << v[i];
    cout << "]\n";
}

int main() {
    print(dailyTemperatures({73, 74, 75, 71, 69, 72, 76, 73}));  // [1, 1, 4, 2, 1, 1, 0, 0]
    print(dailyTemperatures({30, 40, 50, 60}));                  // [1, 1, 1, 0]
    print(dailyTemperatures({30, 20, 10}));                      // [0, 0, 0]
}
