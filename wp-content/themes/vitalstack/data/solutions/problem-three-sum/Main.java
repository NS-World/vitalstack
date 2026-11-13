import java.util.*;

public class Main {
    static List<List<Integer>> threeSum(int[] nums) {
        Arrays.sort(nums);
        List<List<Integer>> out = new ArrayList<>();
        for (int i = 0; i < nums.length - 2; i++) {
            if (i > 0 && nums[i] == nums[i - 1]) continue;   // same first number: skip
            if (nums[i] > 0) break;                          // all remaining are positive
            int lo = i + 1, hi = nums.length - 1;
            while (lo < hi) {
                int total = nums[i] + nums[lo] + nums[hi];
                if (total < 0) lo++;
                else if (total > 0) hi--;
                else {
                    out.add(Arrays.asList(nums[i], nums[lo], nums[hi]));
                    lo++;
                    hi--;
                    while (lo < hi && nums[lo] == nums[lo - 1]) lo++;   // skip duplicate seconds
                }
            }
        }
        return out;
    }

    public static void main(String[] args) {
        System.out.println(threeSum(new int[] {-1, 0, 1, 2, -1, -4}));  // [[-1, -1, 2], [-1, 0, 1]]
        System.out.println(threeSum(new int[] {0, 1, 1}));              // []
        System.out.println(threeSum(new int[] {0, 0, 0, 0}));           // [[0, 0, 0]]
    }
}
