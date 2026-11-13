class ListNode {
  constructor(val, next = null) {
    this.val = val;
    this.next = next;
  }
}

const build = (values) => values.reduceRight((head, v) => new ListNode(v, head), null);

function toList(head) {
  const out = [];
  for (; head; head = head.next) out.push(head.val);
  return out;
}

function reverseList(head) {
  let prev = null, cur = head;
  while (cur) {
    const nxt = cur.next;   // 1. remember the rest
    cur.next = prev;        // 2. flip the arrow
    prev = cur;             // 3. move prev forward
    cur = nxt;              // 4. move cur forward
  }
  return prev;              // prev is the new head
}

console.log(toList(reverseList(build([1, 2, 3, 4, 5]))));  // [ 5, 4, 3, 2, 1 ]
console.log(toList(reverseList(build([1]))));              // [ 1 ]
console.log(toList(reverseList(null)));                    // []
