public class Main {
    static int maxArea(int[] height) {
        int left = 0, right = height.length - 1, best = 0;
        while (left < right) {
            int water = (right - left) * Math.min(height[left], height[right]);
            best = Math.max(best, water);
            if (height[left] < height[right]) left++;   // move the shorter wall
            else right--;
        }
        return best;
    }

    public static void main(String[] args) {
        System.out.println(maxArea(new int[] {1, 8, 6, 2, 5, 4, 8, 3, 7}));  // 49
        System.out.println(maxArea(new int[] {1, 1}));                       // 1
        System.out.println(maxArea(new int[] {4, 3, 2, 1, 4}));              // 16
    }
}
