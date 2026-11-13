import java.util.*;

public class Main {
    static int[] dailyTemperatures(int[] temps) {
        int[] out = new int[temps.length];
        Deque<Integer> waiting = new ArrayDeque<>();   // indexes, temps decreasing
        for (int i = 0; i < temps.length; i++) {
            while (!waiting.isEmpty() && temps[waiting.peek()] < temps[i]) {
                int j = waiting.pop();
                out[j] = i - j;                        // day j finally got a warmer day
            }
            waiting.push(i);
        }
        return out;
    }

    public static void main(String[] args) {
        System.out.println(Arrays.toString(dailyTemperatures(new int[] {73, 74, 75, 71, 69, 72, 76, 73})));  // [1, 1, 4, 2, 1, 1, 0, 0]
        System.out.println(Arrays.toString(dailyTemperatures(new int[] {30, 40, 50, 60})));                  // [1, 1, 1, 0]
        System.out.println(Arrays.toString(dailyTemperatures(new int[] {30, 20, 10})));                      // [0, 0, 0]
    }
}
