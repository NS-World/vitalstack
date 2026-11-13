function isValid(s) {
  const pairs = { ")": "(", "]": "[", "}": "{" };
  const stack = [];
  for (const ch of s) {
    if (ch in pairs) {                                  // closing bracket
      if (!stack.length || stack.pop() !== pairs[ch]) return false;
    } else {
      stack.push(ch);                                   // opening bracket
    }
  }
  return stack.length === 0;
}

console.log(isValid("()[]{}"));    // true
console.log(isValid("{[()()]}"));  // true
console.log(isValid("(]"));        // false
console.log(isValid("([)]"));      // false
console.log(isValid("(("));        // false
console.log(isValid(")"));         // false
