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

ListNode* mergeTwo(ListNode* a, ListNode* b) {
    ListNode dummy(0);
    ListNode* tail = &dummy;
    while (a && b) {
        if (a->val <= b->val) { tail->next = a; a = a->next; }
        else { tail->next = b; b = b->next; }
        tail = tail->next;
    }
    tail->next = a ? a : b;          // attach whatever is left
    return dummy.next;
}

int main() {
    printList(mergeTwo(build({1, 2, 4}), build({1, 3, 4})));  // [1, 1, 2, 3, 4, 4]
    printList(mergeTwo(build({}), build({})));                // []
    printList(mergeTwo(build({}), build({0})));               // [0]
}
