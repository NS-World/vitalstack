class MinStack {
  constructor() {
    this.items = [];                     // [value, min at or below]
  }
  push(x) {
    const currentMin = this.items.length ? Math.min(x, this.items.at(-1)[1]) : x;
    this.items.push([x, currentMin]);
  }
  pop() {
    this.items.pop();
  }
  top() {
    return this.items.at(-1)[0];
  }
  getMin() {
    return this.items.at(-1)[1];
  }
}

const s = new MinStack();
s.push(-2);
s.push(0);
s.push(-3);
console.log(s.getMin());  // -3
s.pop();
console.log(s.top());     // 0
console.log(s.getMin());  // -2
