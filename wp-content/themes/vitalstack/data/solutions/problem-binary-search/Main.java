public class Main {
    static int search(int[] nums, int target) {
        int lo = 0, hi = nums.length - 1;
        while (lo <= hi) {
            int mid = lo + (hi - lo) / 2;          // avoids int overflow
            if (nums[mid] == target) return mid;
            if (nums[mid] < target) lo = mid + 1;  // target is to the right
            else hi = mid - 1;                     // target is to the left
        }
        return -1;
    }

    public static void main(String[] args) {
        System.out.println(search(new int[] {-1, 0, 3, 5, 9, 12}, 9));  // 4
        System.out.println(search(new int[] {-1, 0, 3, 5, 9, 12}, 2));  // -1
        System.out.println(search(new int[] {5}, 5));                   // 0
    }
}
