import java.util.*;

public class Main {
    static int lisFast(int[] nums) {
        int[] tails = new int[nums.length];
        int size = 0;
        for (int x : nums) {
            int i = Arrays.binarySearch(tails, 0, size, x);
            if (i < 0) i = -(i + 1);          // insertion point = bisect_left
            tails[i] = x;                     // smaller top for that length
            if (i == size) size++;            // new, longer pile
        }
        return size;
    }

    public static void main(String[] args) {
        System.out.println(lisFast(new int[] {10, 9, 2, 5, 3, 7, 101, 18}));  // 4
        System.out.println(lisFast(new int[] {0, 1, 0, 3, 2, 3}));            // 4
    }
}
