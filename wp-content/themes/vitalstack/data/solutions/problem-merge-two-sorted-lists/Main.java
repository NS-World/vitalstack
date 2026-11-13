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

    static ListNode mergeTwo(ListNode a, ListNode b) {
        ListNode dummy = new ListNode(0, null), tail = dummy;
        while (a != null && b != null) {
            if (a.val <= b.val) { tail.next = a; a = a.next; }
            else { tail.next = b; b = b.next; }
            tail = tail.next;
        }
        tail.next = (a != null) ? a : b;   // attach whatever is left
        return dummy.next;
    }

    public static void main(String[] args) {
        System.out.println(toList(mergeTwo(build(1, 2, 4), build(1, 3, 4))));  // [1, 1, 2, 3, 4, 4]
        System.out.println(toList(mergeTwo(build(), build())));                // []
        System.out.println(toList(mergeTwo(build(), build(0))));               // [0]
    }
}
