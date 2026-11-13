public class Main {
    static long hours(int[] piles, int k) {
        long total = 0;
        for (int p : piles) total += (p + k - 1) / k;   // ceil(p / k)
        return total;
    }

    static int minEatingSpeed(int[] piles, int h) {
        int lo = 1, hi = 0;
        for (int p : piles) hi = Math.max(hi, p);
        while (lo < hi) {
            int mid = lo + (hi - lo) / 2;
            if (hours(piles, mid) <= h) hi = mid;   // fast enough: try slower
            else lo = mid + 1;                      // too slow
        }
        return lo;
    }

    public static void main(String[] args) {
        System.out.println(minEatingSpeed(new int[] {3, 6, 7, 11}, 8));        // 4
        System.out.println(minEatingSpeed(new int[] {30, 11, 23, 4, 20}, 5));  // 30
        System.out.println(minEatingSpeed(new int[] {30, 11, 23, 4, 20}, 6));  // 23
    }
}
