import java.util.*;

public class Main {
    static boolean isValid(String s) {
        Map<Character, Character> pairs = Map.of(')', '(', ']', '[', '}', '{');
        Deque<Character> stack = new ArrayDeque<>();
        for (char ch : s.toCharArray()) {
            if (pairs.containsKey(ch)) {                       // closing bracket
                if (stack.isEmpty() || stack.pop() != pairs.get(ch)) return false;
            } else {
                stack.push(ch);                                // opening bracket
            }
        }
        return stack.isEmpty();
    }

    public static void main(String[] args) {
        System.out.println(isValid("()[]{}"));    // true
        System.out.println(isValid("{[()()]}"));  // true
        System.out.println(isValid("(]"));        // false
        System.out.println(isValid("([)]"));      // false
        System.out.println(isValid("(("));        // false
        System.out.println(isValid(")"));         // false
    }
}
