import java.util.*;

public class Main {
    static String minWindow(String s, String t) {
        Map<Character, Integer> need = new HashMap<>();
        for (char ch : t.toCharArray()) need.merge(ch, 1, Integer::sum);
        int missing = t.length();                       // characters still needed
        int left = 0, bestStart = 0, bestLen = Integer.MAX_VALUE;
        for (int right = 0; right < s.length(); right++) {
            char ch = s.charAt(right);
            if (need.getOrDefault(ch, 0) > 0) missing--;  // this character was useful
            need.put(ch, need.getOrDefault(ch, 0) - 1);
            while (missing == 0) {                        // window is valid: try to shrink
                if (right - left + 1 < bestLen) {
                    bestStart = left;
                    bestLen = right - left + 1;
                }
                char out = s.charAt(left);
                need.put(out, need.get(out) + 1);
                if (need.get(out) > 0) missing++;         // we just dropped something required
                left++;
            }
        }
        return bestLen == Integer.MAX_VALUE ? "" : s.substring(bestStart, bestStart + bestLen);
    }

    public static void main(String[] args) {
        System.out.println(minWindow("ADOBECODEBANC", "ABC"));  // BANC
        System.out.println(minWindow("a", "a"));                // a
        System.out.println(minWindow("a", "aa").equals(""));    // true
    }
}
