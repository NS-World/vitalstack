public class Main {
    static long climbStairs(int n) {
        long a = 1, b = 1;             // ways to reach step i-2 and i-1
        for (int i = 0; i < n - 1; i++) {
            long next = a + b;
            a = b;
            b = next;
        }
        return b;
    }

    public static void main(String[] args) {
        System.out.println(climbStairs(2));   // 2
        System.out.println(climbStairs(3));   // 3
        System.out.println(climbStairs(5));   // 8
        System.out.println(climbStairs(45));  // 1836311903
    }
}
