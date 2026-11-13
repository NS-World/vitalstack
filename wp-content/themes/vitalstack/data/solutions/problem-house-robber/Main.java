public class Main {
    static int rob(int[] nums) {
        int twoBack = 0, oneBack = 0;
        for (int money : nums) {
            int best = Math.max(oneBack, twoBack + money);   // skip this house, or rob it
            twoBack = oneBack;
            oneBack = best;
        }
        return oneBack;
    }

    public static void main(String[] args) {
        System.out.println(rob(new int[] {1, 2, 3, 1}));     // 4
        System.out.println(rob(new int[] {2, 7, 9, 3, 1}));  // 12
        System.out.println(rob(new int[] {2, 1, 1, 2}));     // 4
        System.out.println(rob(new int[] {}));               // 0
    }
}
