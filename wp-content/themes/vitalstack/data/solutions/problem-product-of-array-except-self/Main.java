import java.util.*;

public class Main {
    static int[] productExceptSelf(int[] nums) {
        int n = nums.length;
        int[] out = new int[n];
        int left = 1;
        for (int i = 0; i < n; i++) {        // out[i] = product of everything left of i
            out[i] = left;
            left *= nums[i];
        }
        int right = 1;
        for (int i = n - 1; i >= 0; i--) {   // multiply by everything right of i
            out[i] *= right;
            right *= nums[i];
        }
        return out;
    }

    public static void main(String[] args) {
        System.out.println(Arrays.toString(productExceptSelf(new int[] {1, 2, 3, 4})));       // [24, 12, 8, 6]
        System.out.println(Arrays.toString(productExceptSelf(new int[] {-1, 1, 0, -3, 3})));  // [0, 0, 9, 0, 0]
    }
}
