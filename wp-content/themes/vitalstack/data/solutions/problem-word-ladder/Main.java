import java.util.*;

public class Main {
    static int ladderLength(String begin, String end, List<String> wordList) {
        Set<String> words = new HashSet<>(wordList);
        if (!words.contains(end)) return 0;
        Deque<String> q = new ArrayDeque<>();
        Map<String, Integer> steps = new HashMap<>();   // also works as "seen"
        q.add(begin);
        steps.put(begin, 1);
        while (!q.isEmpty()) {
            String word = q.poll();
            if (word.equals(end)) return steps.get(word);
            char[] letters = word.toCharArray();
            for (int i = 0; i < letters.length; i++) {
                char original = letters[i];
                for (char ch = 'a'; ch <= 'z'; ch++) {
                    letters[i] = ch;
                    String nxt = new String(letters);
                    if (words.contains(nxt) && !steps.containsKey(nxt)) {
                        steps.put(nxt, steps.get(word) + 1);
                        q.add(nxt);
                    }
                }
                letters[i] = original;
            }
        }
        return 0;
    }

    public static void main(String[] args) {
        System.out.println(ladderLength("hit", "cog", List.of("hot", "dot", "dog", "lot", "log", "cog")));  // 5
        System.out.println(ladderLength("hit", "cog", List.of("hot", "dot", "dog", "lot", "log")));         // 0
    }
}
