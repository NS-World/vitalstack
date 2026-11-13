public class Main {
    static boolean isAnagram(String s, String t) {
        if (s.length() != t.length()) return false;
        int[] count = new int[26];               // one box per letter a-z
        for (char ch : s.toCharArray()) count[ch - 'a']++;
        for (char ch : t.toCharArray()) {
            if (count[ch - 'a'] == 0) return false;
            count[ch - 'a']--;
        }
        return true;
    }

    public static void main(String[] args) {
        System.out.println(isAnagram("anagram", "nagaram"));  // true
        System.out.println(isAnagram("rat", "car"));          // false
        System.out.println(isAnagram("ab", "a"));             // false
    }
}
