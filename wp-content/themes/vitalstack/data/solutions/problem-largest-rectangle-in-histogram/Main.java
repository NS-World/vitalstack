import java.util.*;

public class Main {
    static int largestRectangle(int[] heights) {
        Deque<Integer> stack = new ArrayDeque<>();     // indexes, heights increasing
        int best = 0, n = heights.length;
        for (int i = 0; i <= n; i++) {
            int h = (i == n) ? 0 : heights[i];         // 0 at the end flushes the stack
            while (!stack.isEmpty() && heights[stack.peek()] > h) {
                int height = heights[stack.pop()];
                int left = stack.isEmpty() ? -1 : stack.peek();   // left blocker
                int width = i - left - 1;                         // i is the right blocker
                best = Math.max(best, height * width);
            }
            stack.push(i);
        }
        return best;
    }

    public static void main(String[] args) {
        System.out.println(largestRectangle(new int[] {2, 1, 5, 6, 2, 3}));  // 10
        System.out.println(largestRectangle(new int[] {2, 4}));              // 4
        System.out.println(largestRectangle(new int[] {1, 1, 1, 1}));        // 4
    }
}
