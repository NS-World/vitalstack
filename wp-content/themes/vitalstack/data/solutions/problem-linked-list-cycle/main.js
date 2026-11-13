class ListNode {
  constructor(val) {
    this.val = val;
    this.next = null;
  }
}

function hasCycle(head) {
  let slow = head, fast = head;
  while (fast && fast.next) {
    slow = slow.next;
    fast = fast.next.next;
    if (slow === fast) return true;   // same node object
  }
  return false;
}

const nodes = [3, 2, 0, -4].map((v) => new ListNode(v));
for (let i = 0; i + 1 < nodes.length; i++) nodes[i].next = nodes[i + 1];
console.log(hasCycle(nodes[0]));   // false
nodes[3].next = nodes[1];          // -4 points back to 2
console.log(hasCycle(nodes[0]));   // true
console.log(hasCycle(null));       // false
