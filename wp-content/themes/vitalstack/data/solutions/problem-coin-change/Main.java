import java.util.*;

public class Main {
    static int coinChange(int[] coins, int amount) {
        final int INF = Integer.MAX_VALUE;
        int[] best = new int[amount + 1];
        Arrays.fill(best, INF);
        best[0] = 0;
        for (int a = 1; a <= amount; a++) {
            for (int c : coins) {
                if (c <= a && best[a - c] != INF) best[a] = Math.min(best[a], best[a - c] + 1);
            }
        }
        return best[amount] == INF ? -1 : best[amount];
    }

    public static void main(String[] args) {
        System.out.println(coinChange(new int[] {1, 2, 5}, 11));  // 3
        System.out.println(coinChange(new int[] {1, 3, 4}, 6));   // 2
        System.out.println(coinChange(new int[] {2}, 3));         // -1
        System.out.println(coinChange(new int[] {1}, 0));         // 0
    }
}
