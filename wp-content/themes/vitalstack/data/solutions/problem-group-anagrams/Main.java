import java.util.*;

public class Main {
    static List<List<String>> groupAnagrams(String[] words) {
        Map<String, List<String>> groups = new LinkedHashMap<>();  // keeps first-seen order
        for (String w : words) {
            char[] letters = w.toCharArray();
            Arrays.sort(letters);
            String key = new String(letters);                         // "eat" -> "aet"
            groups.computeIfAbsent(key, k -> new ArrayList<>()).add(w);
        }
        return new ArrayList<>(groups.values());
    }

    // prints [['eat', 'tea', 'ate'], ...] like Python, so empty strings stay visible
    static String show(List<List<String>> groups) {
        StringJoiner outer = new StringJoiner(", ", "[", "]");
        for (List<String> g : groups) {
            StringJoiner inner = new StringJoiner(", ", "[", "]");
            for (String w : g) inner.add("'" + w + "'");
            outer.add(inner.toString());
        }
        return outer.toString();
    }

    public static void main(String[] args) {
        System.out.println(show(groupAnagrams(new String[] {"eat", "tea", "tan", "ate", "nat", "bat"})));
        // [['eat', 'tea', 'ate'], ['tan', 'nat'], ['bat']]
        System.out.println(show(groupAnagrams(new String[] {""})));  // [['']]
    }
}
