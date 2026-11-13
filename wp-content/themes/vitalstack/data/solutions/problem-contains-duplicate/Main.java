import java.util.*;

public class Main {
    static boolean containsDuplicate(int[] nums) {
        Set<Integer> seen = new HashSet<>();
        for (int x : nums) {
            if (!seen.add(x)) return true;   // add() returns false if x was already there
        }
        return false;
    }

    public static void main(String[] args) {
        System.out.println(containsDuplicate(new int[] {1, 2, 3, 1}));  // true
        System.out.println(containsDuplicate(new int[] {1, 2, 3, 4}));  // false
        System.out.println(containsDuplicate(new int[] {}));            // false
    }
}
