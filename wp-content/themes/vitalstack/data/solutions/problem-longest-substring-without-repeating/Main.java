import java.util.*;

public class Main {
    static int lengthOfLongestSubstring(String s) {
        Map<Character, Integer> last = new HashMap<>();   // letter -> last index seen
        int left = 0, best = 0;
        for (int right = 0; right < s.length(); right++) {
            char ch = s.charAt(right);
            if (last.containsKey(ch) && last.get(ch) >= left) {
                left = last.get(ch) + 1;                   // jump past the old copy
            }
            last.put(ch, right);
            best = Math.max(best, right - left + 1);
        }
        return best;
    }

    public static void main(String[] args) {
        System.out.println(lengthOfLongestSubstring("abcabcbb"));  // 3
        System.out.println(lengthOfLongestSubstring("bbbbb"));     // 1
        System.out.println(lengthOfLongestSubstring("pwwkew"));    // 3
        System.out.println(lengthOfLongestSubstring(""));          // 0
        System.out.println(lengthOfLongestSubstring("abba"));      // 2
    }
}
