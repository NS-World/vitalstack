function minDistance(a, b) {
  const dp = Array.from({ length: a.length + 1 }, () => new Array(b.length + 1).fill(0));
  for (let i = 0; i <= a.length; i++) dp[i][0] = i;   // delete everything
  for (let j = 0; j <= b.length; j++) dp[0][j] = j;   // insert everything
  for (let i = 1; i <= a.length; i++) {
    for (let j = 1; j <= b.length; j++) {
      if (a[i - 1] === b[j - 1]) dp[i][j] = dp[i - 1][j - 1];
      else dp[i][j] = 1 + Math.min(dp[i - 1][j - 1],   // replace
                                   dp[i - 1][j],       // delete
                                   dp[i][j - 1]);      // insert
    }
  }
  return dp[a.length][b.length];
}

console.log(minDistance("horse", "ros"));            // 3
console.log(minDistance("intention", "execution"));  // 5
console.log(minDistance("", "abc"));                 // 3
console.log(minDistance("same", "same"));            // 0
