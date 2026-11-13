#include <iostream>
#include <vector>
using namespace std;

struct ListNode {
    int val;
    ListNode* next = nullptr;
    ListNode(int v) : val(v) {}
};

bool hasCycle(ListNode* head) {
    ListNode *slow = head, *fast = head;
    while (fast && fast->next) {
        slow = slow->next;
        fast = fast->next->next;
        if (slow == fast) return true;   // same node
    }
    return false;
}

int main() {
    cout << boolalpha;
    vector<ListNode> nodes = {ListNode(3), ListNode(2), ListNode(0), ListNode(-4)};
    for (size_t i = 0; i + 1 < nodes.size(); i++) nodes[i].next = &nodes[i + 1];
    cout << hasCycle(&nodes[0]) << "\n";  // false
    nodes[3].next = &nodes[1];            // -4 points back to 2
    cout << hasCycle(&nodes[0]) << "\n";  // true
    cout << hasCycle(nullptr) << "\n";    // false
}
