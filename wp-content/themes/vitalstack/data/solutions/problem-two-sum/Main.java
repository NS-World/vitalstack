import java.util.*;

public class Main {
    static int[] twoSum(int[] nums, int target) {
        Map<Integer, Integer> seen = new HashMap<>();   // value -> index
        for (int i = 0; i < nums.length; i++) {
            int partner = target - nums[i];
            if (seen.containsKey(partner)) {
                return new int[] { seen.get(partner), i };
            }
            seen.put(nums[i], i);                        // store AFTER checking
        }
        return new int[0];
    }

    public static void main(String[] args) {
        System.out.println(Arrays.toString(twoSum(new int[] {2, 7, 11, 15}, 9)));  // [0, 1]
        System.out.println(Arrays.toString(twoSum(new int[] {3, 2, 4}, 6)));       // [1, 2]
        System.out.println(Arrays.toString(twoSum(new int[] {3, 3}, 6)));          // [0, 1]
    }
}
