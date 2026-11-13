import java.util.*;

public class Main {
    static int networkDelayTime(int[][] times, int n, int k) {
        Map<Integer, List<int[]>> graph = new HashMap<>();
        for (int[] t : times) graph.computeIfAbsent(t[0], x -> new ArrayList<>()).add(new int[] {t[1], t[2]});
        Map<Integer, Integer> arrived = new HashMap<>();                 // node -> final time
        PriorityQueue<int[]> heap = new PriorityQueue<>((a, b) -> Integer.compare(a[0], b[0]));
        heap.add(new int[] {0, k});                                      // {time, node}
        while (!heap.isEmpty()) {
            int[] top = heap.poll();
            int t = top[0], node = top[1];
            if (arrived.containsKey(node)) continue;                     // already reached faster
            arrived.put(node, t);
            for (int[] edge : graph.getOrDefault(node, List.of())) {
                if (!arrived.containsKey(edge[0])) heap.add(new int[] {t + edge[1], edge[0]});
            }
        }
        return arrived.size() == n ? Collections.max(arrived.values()) : -1;
    }

    public static void main(String[] args) {
        System.out.println(networkDelayTime(new int[][] {{2, 1, 1}, {2, 3, 1}, {3, 4, 1}}, 4, 2));  // 2
        System.out.println(networkDelayTime(new int[][] {{1, 2, 1}}, 2, 1));                        // 1
        System.out.println(networkDelayTime(new int[][] {{1, 2, 1}}, 2, 2));                        // -1
        System.out.println(networkDelayTime(new int[][] {{1, 2, 4}, {1, 3, 1}, {3, 2, 1}}, 3, 1));  // 2
    }
}
