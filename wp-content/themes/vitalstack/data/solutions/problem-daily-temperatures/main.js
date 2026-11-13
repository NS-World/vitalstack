function dailyTemperatures(temps) {
  const out = new Array(temps.length).fill(0);
  const waiting = [];                         // indexes, temps decreasing
  temps.forEach((t, i) => {
    while (waiting.length && temps[waiting.at(-1)] < t) {
      const j = waiting.pop();
      out[j] = i - j;                         // day j finally got a warmer day
    }
    waiting.push(i);
  });
  return out;
}

console.log(dailyTemperatures([73, 74, 75, 71, 69, 72, 76, 73]));  // [1, 1, 4, 2, 1, 1, 0, 0]
console.log(dailyTemperatures([30, 40, 50, 60]));                  // [1, 1, 1, 0]
console.log(dailyTemperatures([30, 20, 10]));                      // [0, 0, 0]
