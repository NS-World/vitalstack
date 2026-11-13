public class Main {
    static int maxSubarray(int[] nums) {
        int current = nums[0], best = nums[0];
        for (int i = 1; i < nums.length; i++) {
            current = Math.max(nums[i], current + nums[i]);   // start fresh or extend
            best = Math.max(best, current);
        }
        return best;
    }

    public static void main(String[] args) {
        System.out.println(maxSubarray(new int[] {-2, 1, -3, 4, -1, 2, 1, -5, 4}));  // 6
        System.out.println(maxSubarray(new int[] {1}));                              // 1
        System.out.println(maxSubarray(new int[] {-3, -1, -2}));                     // -1
    }
}
