import java.util.*;

public class Main {
    static void choose(int[] nums, int i, List<Integer> current, List<List<Integer>> out) {
        if (i == nums.length) {
            out.add(new ArrayList<>(current));   // copy! current keeps changing
            return;
        }
        current.add(nums[i]);                    // take nums[i]
        choose(nums, i + 1, current, out);
        current.remove(current.size() - 1);      // undo (backtrack)
        choose(nums, i + 1, current, out);       // skip nums[i]
    }

    static List<List<Integer>> subsets(int[] nums) {
        List<List<Integer>> out = new ArrayList<>();
        choose(nums, 0, new ArrayList<>(), out);
        return out;
    }

    public static void main(String[] args) {
        System.out.println(subsets(new int[] {1, 2, 3}));            // [[1, 2, 3], [1, 2], [1, 3], [1], [2, 3], [2], [3], []]
        System.out.println(subsets(new int[] {1, 2, 3, 4}).size());  // 16
    }
}
