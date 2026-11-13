#include <iostream>
#include <string>
#include <vector>
using namespace std;

void sink(vector<string>& grid, int r, int c) {
    if (r < 0 || c < 0 || r >= (int)grid.size() || c >= (int)grid[0].size() || grid[r][c] != '1') return;
    grid[r][c] = '0';
    sink(grid, r + 1, c);
    sink(grid, r - 1, c);
    sink(grid, r, c + 1);
    sink(grid, r, c - 1);
}

int numIslands(vector<string> grid) {
    int count = 0;
    for (int r = 0; r < (int)grid.size(); r++) {
        for (int c = 0; c < (int)grid[0].size(); c++) {
            if (grid[r][c] == '1') {
                count++;
                sink(grid, r, c);
            }
        }
    }
    return count;
}

int main() {
    cout << numIslands({"11000", "11010", "00100", "00011"}) << "\n";  // 4
    cout << numIslands({"11110", "11010", "11000", "00000"}) << "\n";  // 1
}
