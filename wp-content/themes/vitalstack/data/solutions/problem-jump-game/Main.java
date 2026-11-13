public class Main {
    static boolean canJump(int[] nums) {
        int farthest = 0;
        for (int i = 0; i < nums.length; i++) {
            if (i > farthest) return false;            // this tile is in the dark
            farthest = Math.max(farthest, i + nums[i]);
        }
        return true;
    }

    public static void main(String[] args) {
        System.out.println(canJump(new int[] {2, 3, 1, 1, 4}));  // true
        System.out.println(canJump(new int[] {3, 2, 1, 0, 4}));  // false
        System.out.println(canJump(new int[] {0}));              // true
    }
}
