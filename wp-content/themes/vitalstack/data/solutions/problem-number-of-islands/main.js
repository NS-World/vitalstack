function numIslands(grid) {
  const rows = grid.length, cols = grid[0].length;
  const sink = (r, c) => {
    if (r < 0 || c < 0 || r >= rows || c >= cols || grid[r][c] !== "1") return;
    grid[r][c] = "0";
    sink(r + 1, c); sink(r - 1, c); sink(r, c + 1); sink(r, c - 1);
  };
  let count = 0;
  for (let r = 0; r < rows; r++) {
    for (let c = 0; c < cols; c++) {
      if (grid[r][c] === "1") {
        count++;
        sink(r, c);
      }
    }
  }
  return count;
}

const toGrid = (rows) => rows.map((row) => [...row]);
console.log(numIslands(toGrid(["11000", "11010", "00100", "00011"])));  // 4
console.log(numIslands(toGrid(["11110", "11010", "11000", "00000"])));  // 1
