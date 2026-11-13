import java.util.*;

public class Main {
    static int subarraySum(int[] nums, int k) {
        Map<Integer, Integer> seen = new HashMap<>();   // prefix sum -> how many times seen
        seen.put(0, 1);
        int total = 0, count = 0;
        for (int x : nums) {
            total += x;
            count += seen.getOrDefault(total - k, 0);
            seen.merge(total, 1, Integer::sum);
        }
        return count;
    }

    public static void main(String[] args) {
        System.out.println(subarraySum(new int[] {1, 1, 1}, 2));          // 2
        System.out.println(subarraySum(new int[] {1, 2, 3}, 3));          // 2
        System.out.println(subarraySum(new int[] {3, -1, 2, -2, 4}, 2));  // 4
    }
}
