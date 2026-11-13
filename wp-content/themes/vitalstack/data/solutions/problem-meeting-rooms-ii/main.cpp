#include <algorithm>
#include <functional>
#include <iostream>
#include <queue>
#include <vector>
using namespace std;

int minMeetingRooms(vector<vector<int>> meetings) {
    sort(meetings.begin(), meetings.end());
    priority_queue<int, vector<int>, greater<int>> freeAt;   // min-heap of end times
    for (const auto& m : meetings) {
        if (!freeAt.empty() && freeAt.top() <= m[0]) freeAt.pop();   // reuse the earliest-free room
        freeAt.push(m[1]);
    }
    return freeAt.size();
}

int main() {
    cout << minMeetingRooms({{0, 30}, {5, 10}, {15, 20}}) << "\n";  // 2
    cout << minMeetingRooms({{7, 10}, {2, 4}}) << "\n";             // 1
    cout << minMeetingRooms({{1, 5}, {2, 6}, {3, 7}}) << "\n";      // 3
    cout << minMeetingRooms({}) << "\n";                            // 0
}
