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

// JavaScript has no built-in heap, so here is a small min-heap keyed on node.val
class MinHeap {
  constructor() { this.a = []; }
  get size() { return this.a.length; }
  push(node) {
    const a = this.a;
    a.push(node);
    for (let i = a.length - 1; i > 0; ) {
      const p = (i - 1) >> 1;
      if (a[p].val <= a[i].val) break;
      [a[p], a[i]] = [a[i], a[p]];
      i = p;
    }
  }
  pop() {
    const a = this.a, top = a[0], last = a.pop();
    if (a.length) {
      a[0] = last;
      for (let i = 0; ; ) {
        const l = 2 * i + 1, r = l + 1;
        let m = i;
        if (l < a.length && a[l].val < a[m].val) m = l;
        if (r < a.length && a[r].val < a[m].val) m = r;
        if (m === i) break;
        [a[m], a[i]] = [a[i], a[m]];
        i = m;
      }
    }
    return top;
  }
}

function mergeKLists(lists) {
  const heap = new MinHeap();
  for (const node of lists) if (node) heap.push(node);
  const dummy = new ListNode(0);
  let tail = dummy;
  while (heap.size) {
    const node = heap.pop();                  // smallest head of all lists
    tail.next = node;
    tail = node;
    if (node.next) heap.push(node.next);
  }
  return dummy.next;
}

const lists = [build([1, 4, 5]), build([1, 3, 4]), build([2, 6])];
console.log(toList(mergeKLists(lists)));  // [1, 1, 2, 3, 4, 4, 5, 6]
console.log(toList(mergeKLists([])));     // []
