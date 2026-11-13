import java.util.*;

public class Main {
    static List<Integer> topKBuckets(int[] nums, int k) {
        Map<Integer, Integer> counts = new LinkedHashMap<>();
        for (int x : nums) counts.merge(x, 1, Integer::sum);
        List<List<Integer>> shelves = new ArrayList<>();       // shelves.get(c) = values seen c times
        for (int i = 0; i <= nums.length; i++) shelves.add(new ArrayList<>());
        for (Map.Entry<Integer, Integer> e : counts.entrySet()) shelves.get(e.getValue()).add(e.getKey());
        List<Integer> out = new ArrayList<>();
        for (int c = shelves.size() - 1; c > 0; c--) {
            for (int value : shelves.get(c)) {
                out.add(value);
                if (out.size() == k) return out;
            }
        }
        return out;
    }

    public static void main(String[] args) {
        System.out.println(topKBuckets(new int[] {1, 1, 1, 2, 2, 3}, 2));        // [1, 2]
        System.out.println(topKBuckets(new int[] {5, 5, 6, 6, 6, 7, 5, 5}, 1));  // [5]
    }
}
