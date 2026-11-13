import java.util.*;

public class Main {
    static int[][] merge(int[][] intervals) {
        Arrays.sort(intervals, (x, y) -> Integer.compare(x[0], y[0]));
        List<int[]> merged = new ArrayList<>();
        for (int[] iv : intervals) {
            if (!merged.isEmpty() && iv[0] <= merged.get(merged.size() - 1)[1]) {
                int[] last = merged.get(merged.size() - 1);
                last[1] = Math.max(last[1], iv[1]);
            } else {
                merged.add(new int[] {iv[0], iv[1]});
            }
        }
        return merged.toArray(new int[0][]);
    }

    public static void main(String[] args) {
        System.out.println(Arrays.deepToString(merge(new int[][] {{1, 3}, {2, 6}, {8, 10}, {15, 18}})));  // [[1, 6], [8, 10], [15, 18]]
        System.out.println(Arrays.deepToString(merge(new int[][] {{1, 4}, {4, 5}})));                     // [[1, 5]]
        System.out.println(Arrays.deepToString(merge(new int[][] {{1, 10}, {2, 3}})));                    // [[1, 10]]
        System.out.println(Arrays.deepToString(merge(new int[][] {{5, 6}, {1, 2}})));                     // [[1, 2], [5, 6]]
    }
}
