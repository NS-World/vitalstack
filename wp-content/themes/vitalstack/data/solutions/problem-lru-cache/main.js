// A JavaScript Map remembers insertion order, so its first key is the oldest
class LRU {
  constructor(capacity) {
    this.cap = capacity;
    this.map = new Map();
  }
  get(key) {
    if (!this.map.has(key)) return -1;
    const value = this.map.get(key);
    this.map.delete(key);                 // re-insert = mark as newest
    this.map.set(key, value);
    return value;
  }
  put(key, value) {
    this.map.delete(key);
    this.map.set(key, value);
    if (this.map.size > this.cap) {
      const oldest = this.map.keys().next().value;
      this.map.delete(oldest);
    }
  }
}

const c = new LRU(2);
c.put(1, 1);
c.put(2, 2);
console.log(c.get(1));  // 1
c.put(3, 3);
console.log(c.get(2));  // -1
console.log(c.get(3));  // 3
