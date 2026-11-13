#include <iostream>
#include <vector>
using namespace std;

struct ListNode {
    int val;
    ListNode* next;
    ListNode(int v, ListNode* n = nullptr) : val(v), next(n) {}
};

ListNode* build(const vector<int>& values) {
    ListNode* head = nullptr;
    for (int i = (int)values.size() - 1; i >= 0; i--) head = new ListNode(values[i], head);
    return head;
}

void printList(ListNode* head) {
    cout << "[";
    for (bool first = true; head; head = head->next, first = false) cout << (first ? "" : ", ") << head->val;
    cout << "]\n";
}

ListNode* reverseList(ListNode* head) {
    ListNode *prev = nullptr, *cur = head;
    while (cur) {
        ListNode* nxt = cur->next;   // 1. remember the rest
        cur->next = prev;            // 2. flip the arrow
        prev = cur;                  // 3. move prev forward
        cur = nxt;                   // 4. move cur forward
    }
    return prev;                     // prev is the new head
}

int main() {
    printList(reverseList(build({1, 2, 3, 4, 5})));  // [5, 4, 3, 2, 1]
    printList(reverseList(build({1})));              // [1]
    printList(reverseList(nullptr));                 // []
}
