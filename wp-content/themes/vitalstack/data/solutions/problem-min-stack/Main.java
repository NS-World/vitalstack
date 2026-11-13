import java.util.*;

public class Main {
    static class MinStack {
        private final Deque<int[]> items = new ArrayDeque<>();   // {value, min at or below}

        void push(int x) {
            int currentMin = items.isEmpty() ? x : Math.min(x, items.peek()[1]);
            items.push(new int[] {x, currentMin});
        }

        void pop() { items.pop(); }

        int top() { return items.peek()[0]; }

        int getMin() { return items.peek()[1]; }
    }

    public static void main(String[] args) {
        MinStack s = new MinStack();
        s.push(-2);
        s.push(0);
        s.push(-3);
        System.out.println(s.getMin());  // -3
        s.pop();
        System.out.println(s.top());     // 0
        System.out.println(s.getMin());  // -2
    }
}
