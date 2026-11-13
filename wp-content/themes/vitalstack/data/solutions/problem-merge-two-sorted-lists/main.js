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

function mergeTwo(a, b) {
  const dummy = new ListNode(0);
  let tail = dummy;
  while (a && b) {
    if (a.val <= b.val) { tail.next = a; a = a.next; }
    else { tail.next = b; b = b.next; }
    tail = tail.next;
  }
  tail.next = a || b;              // attach whatever is left
  return dummy.next;
}

console.log(toList(mergeTwo(build([1, 2, 4]), build([1, 3, 4]))));  // [ 1, 1, 2, 3, 4, 4 ]
console.log(toList(mergeTwo(build([]), build([]))));                // []
console.log(toList(mergeTwo(build([]), build([0]))));               // [ 0 ]
