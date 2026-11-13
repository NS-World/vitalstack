import java.util.*;

public class Main {
    static int minMeetingRooms(int[][] meetings) {
        Arrays.sort(meetings, (x, y) -> Integer.compare(x[0], y[0]));
        PriorityQueue<Integer> freeAt = new PriorityQueue<>();   // end times of rooms in use
        for (int[] m : meetings) {
            if (!freeAt.isEmpty() && freeAt.peek() <= m[0]) freeAt.poll();   // reuse the earliest-free room
            freeAt.add(m[1]);
        }
        return freeAt.size();
    }

    public static void main(String[] args) {
        System.out.println(minMeetingRooms(new int[][] {{0, 30}, {5, 10}, {15, 20}}));  // 2
        System.out.println(minMeetingRooms(new int[][] {{7, 10}, {2, 4}}));             // 1
        System.out.println(minMeetingRooms(new int[][] {{1, 5}, {2, 6}, {3, 7}}));      // 3
        System.out.println(minMeetingRooms(new int[][] {}));                            // 0
    }
}
