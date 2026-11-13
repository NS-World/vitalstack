import java.util.*;

public class Main {
    static void fill(int[] nums, boolean[] used, List<Integer> current, List<List<Integer>> out) {
        if (current.size() == nums.length) {
            out.add(new ArrayList<>(current));
            return;
        }
        for (int i = 0; i < nums.length; i++) {
            if (!used[i]) {
                used[i] = true;
                current.add(nums[i]);                 // choose
                fill(nums, used, current, out);       // explore
                current.remove(current.size() - 1);   // un-choose
                used[i] = false;
            }
        }
    }

    static List<List<Integer>> permute(int[] nums) {
        List<List<Integer>> out = new ArrayList<>();
        fill(nums, new boolean[nums.length], new ArrayList<>(), out);
        return out;
    }

    public static void main(String[] args) {
        System.out.println(permute(new int[] {1, 2, 3}));            // [[1, 2, 3], [1, 3, 2], [2, 1, 3], [2, 3, 1], [3, 1, 2], [3, 2, 1]]
        System.out.println(permute(new int[] {1, 2, 3, 4}).size());  // 24
        System.out.println(permute(new int[] {0, 1}));               // [[0, 1], [1, 0]]
    }
}
