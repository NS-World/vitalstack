import java.util.*;

public class Main {
    static final Random RNG = new Random();

    static int quickselect(List<Integer> nums, int k) {
        int pivot = nums.get(RNG.nextInt(nums.size()));
        List<Integer> bigger = new ArrayList<>(), smaller = new ArrayList<>();
        int equal = 0;
        for (int x : nums) {
            if (x > pivot) bigger.add(x);
            else if (x < pivot) smaller.add(x);
            else equal++;
        }
        if (k <= bigger.size()) return quickselect(bigger, k);
        if (k <= bigger.size() + equal) return pivot;
        return quickselect(smaller, k - bigger.size() - equal);
    }

    public static void main(String[] args) {
        System.out.println(quickselect(List.of(3, 2, 1, 5, 6, 4), 2));           // 5
        System.out.println(quickselect(List.of(3, 2, 3, 1, 2, 4, 5, 5, 6), 4));  // 4
    }
}
