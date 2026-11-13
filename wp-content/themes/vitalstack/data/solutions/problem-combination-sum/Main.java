import java.util.*;

public class Main {
    static void build(int[] candidates, int start, int remaining, List<Integer> current, List<List<Integer>> out) {
        if (remaining == 0) {
            out.add(new ArrayList<>(current));
            return;
        }
        for (int i = start; i < candidates.length; i++) {
            int c = candidates[i];
            if (c > remaining) break;                              // sorted: nothing later fits
            current.add(c);
            build(candidates, i, remaining - c, current, out);     // i, not i + 1: reuse allowed
            current.remove(current.size() - 1);
        }
    }

    static List<List<Integer>> combinationSum(int[] candidates, int target) {
        Arrays.sort(candidates);
        List<List<Integer>> out = new ArrayList<>();
        build(candidates, 0, target, new ArrayList<>(), out);
        return out;
    }

    public static void main(String[] args) {
        System.out.println(combinationSum(new int[] {2, 3, 6, 7}, 7));  // [[2, 2, 3], [7]]
        System.out.println(combinationSum(new int[] {2, 3, 5}, 8));     // [[2, 2, 2, 2], [2, 3, 3], [3, 5]]
        System.out.println(combinationSum(new int[] {2}, 1));           // []
    }
}
