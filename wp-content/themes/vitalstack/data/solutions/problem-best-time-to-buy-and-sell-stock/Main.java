public class Main {
    static int maxProfit(int[] prices) {
        int cheapest = Integer.MAX_VALUE, best = 0;
        for (int p : prices) {
            cheapest = Math.min(cheapest, p);
            best = Math.max(best, p - cheapest);
        }
        return best;
    }

    public static void main(String[] args) {
        System.out.println(maxProfit(new int[] {7, 1, 5, 3, 6, 4}));  // 5
        System.out.println(maxProfit(new int[] {7, 6, 4, 3, 1}));     // 0
    }
}
