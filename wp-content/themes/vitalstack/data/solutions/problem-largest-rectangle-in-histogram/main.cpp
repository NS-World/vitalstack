#include <algorithm>
#include <iostream>
#include <vector>
using namespace std;

int largestRectangle(const vector<int>& heights) {
    vector<int> st;                                // indexes, heights increasing
    int best = 0, n = heights.size();
    for (int i = 0; i <= n; i++) {
        int h = (i == n) ? 0 : heights[i];         // 0 at the end flushes the stack
        while (!st.empty() && heights[st.back()] > h) {
            int height = heights[st.back()];
            st.pop_back();
            int left = st.empty() ? -1 : st.back();   // left blocker
            int width = i - left - 1;                 // i is the right blocker
            best = max(best, height * width);
        }
        st.push_back(i);
    }
    return best;
}

int main() {
    cout << largestRectangle({2, 1, 5, 6, 2, 3}) << "\n";  // 10
    cout << largestRectangle({2, 4}) << "\n";              // 4
    cout << largestRectangle({1, 1, 1, 1}) << "\n";        // 4
}
