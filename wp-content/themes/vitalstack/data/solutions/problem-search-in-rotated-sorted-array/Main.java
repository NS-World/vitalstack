public class Main {
    static int searchRotated(int[] nums, int target) {
        int lo = 0, hi = nums.length - 1;
        while (lo <= hi) {
            int mid = lo + (hi - lo) / 2;
            if (nums[mid] == target) return mid;
            if (nums[lo] <= nums[mid]) {                       // left half sorted
                if (nums[lo] <= target && target < nums[mid]) hi = mid - 1;
                else lo = mid + 1;
            } else {                                           // right half sorted
                if (nums[mid] < target && target <= nums[hi]) lo = mid + 1;
                else hi = mid - 1;
            }
        }
        return -1;
    }

    public static void main(String[] args) {
        System.out.println(searchRotated(new int[] {4, 5, 6, 7, 0, 1, 2}, 0));  // 4
        System.out.println(searchRotated(new int[] {4, 5, 6, 7, 0, 1, 2}, 3));  // -1
        System.out.println(searchRotated(new int[] {1}, 0));                    // -1
        System.out.println(searchRotated(new int[] {3, 1}, 1));                 // 1
    }
}
