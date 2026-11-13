import java.util.*;

public class Main {
    static class ListNode {
        int val;
        ListNode next;
        ListNode(int val, ListNode next) { this.val = val; this.next = next; }
    }

    static ListNode build(int... values) {
        ListNode head = null;
        for (int i = values.length - 1; i >= 0; i--) head = new ListNode(values[i], head);
        return head;
    }

    static List<Integer> toList(ListNode head) {
        List<Integer> out = new ArrayList<>();
        for (; head != null; head = head.next) out.add(head.val);
        return out;
    }

    static ListNode reverseList(ListNode head) {
        ListNode prev = null, cur = head;
        while (cur != null) {
            ListNode nxt = cur.next;   // 1. remember the rest
            cur.next = prev;           // 2. flip the arrow
            prev = cur;                // 3. move prev forward
            cur = nxt;                 // 4. move cur forward
        }
        return prev;                   // prev is the new head
    }

    public static void main(String[] args) {
        System.out.println(toList(reverseList(build(1, 2, 3, 4, 5))));  // [5, 4, 3, 2, 1]
        System.out.println(toList(reverseList(build(1))));              // [1]
        System.out.println(toList(reverseList(null)));                  // []
    }
}
