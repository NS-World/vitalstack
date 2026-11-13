import java.util.*;

public class Main {
    static int longestConsecutive(int[] nums) {
        Set<Integer> present = new HashSet<>();
        for (int x : nums) present.add(x);
        int best = 0;
        for (int x : present) {
            if (!present.contains(x - 1)) {          // x starts a run
                int length = 1;
                while (present.contains(x + length)) length++;
                best = Math.max(best, length);
            }
        }
        return best;
    }

    public static void main(String[] args) {
        System.out.println(longestConsecutive(new int[] {100, 4, 200, 1, 3, 2}));          // 4
        System.out.println(longestConsecutive(new int[] {0, 3, 7, 2, 5, 8, 4, 6, 0, 1}));  // 9
        System.out.println(longestConsecutive(new int[] {}));                              // 0
    }
}
