#include <iostream>
#include <queue>
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

ListNode* mergeKLists(const vector<ListNode*>& lists) {
    auto bigger = [](ListNode* a, ListNode* b) { return a->val > b->val; };
    priority_queue<ListNode*, vector<ListNode*>, decltype(bigger)> heap(bigger);   // min-heap
    for (ListNode* node : lists) if (node) heap.push(node);
    ListNode dummy(0);
    ListNode* tail = &dummy;
    while (!heap.empty()) {
        ListNode* node = heap.top();              // smallest head of all lists
        heap.pop();
        tail->next = node;
        tail = node;
        if (node->next) heap.push(node->next);
    }
    return dummy.next;
}

int main() {
    printList(mergeKLists({build({1, 4, 5}), build({1, 3, 4}), build({2, 6})}));  // [1, 1, 2, 3, 4, 4, 5, 6]
    printList(mergeKLists({}));                                                   // []
}
