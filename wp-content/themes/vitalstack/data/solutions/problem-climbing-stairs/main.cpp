#include <iostream>
using namespace std;

long long climbStairs(int n) {
    long long a = 1, b = 1;          // ways to reach step i-2 and i-1
    for (int i = 0; i < n - 1; i++) {
        long long next = a + b;
        a = b;
        b = next;
    }
    return b;
}

int main() {
    cout << climbStairs(2) << "\n";   // 2
    cout << climbStairs(3) << "\n";   // 3
    cout << climbStairs(5) << "\n";   // 8
    cout << climbStairs(45) << "\n";  // 1836311903
}
