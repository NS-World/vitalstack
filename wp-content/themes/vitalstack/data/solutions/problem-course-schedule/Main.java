import java.util.*;

public class Main {
    static boolean canFinish(int n, int[][] prerequisites) {
        List<List<Integer>> unlocks = new ArrayList<>();
        for (int i = 0; i < n; i++) unlocks.add(new ArrayList<>());
        int[] needs = new int[n];
        for (int[] p : prerequisites) {
            unlocks.get(p[1]).add(p[0]);   // p = {course, req}
            needs[p[0]]++;
        }
        Deque<Integer> q = new ArrayDeque<>();
        for (int c = 0; c < n; c++) if (needs[c] == 0) q.add(c);
        int taken = 0;
        while (!q.isEmpty()) {
            int c = q.poll();
            taken++;
            for (int nxt : unlocks.get(c)) {
                if (--needs[nxt] == 0) q.add(nxt);
            }
        }
        return taken == n;
    }

    public static void main(String[] args) {
        System.out.println(canFinish(2, new int[][] {{1, 0}}));                          // true
        System.out.println(canFinish(2, new int[][] {{1, 0}, {0, 1}}));                  // false
        System.out.println(canFinish(4, new int[][] {{1, 0}, {2, 0}, {3, 1}, {3, 2}}));  // true
    }
}
