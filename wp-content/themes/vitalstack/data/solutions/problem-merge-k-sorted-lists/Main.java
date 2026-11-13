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

    static ListNode mergeKLists(List<ListNode> lists) {
        PriorityQueue<ListNode> heap = new PriorityQueue<>((a, b) -> Integer.compare(a.val, b.val));
        for (ListNode node : lists) if (node != null) heap.add(node);
        ListNode dummy = new ListNode(0, null), tail = dummy;
        while (!heap.isEmpty()) {
            ListNode node = heap.poll();          // smallest head of all lists
            tail.next = node;
            tail = node;
            if (node.next != null) heap.add(node.next);
        }
        return dummy.next;
    }

    public static void main(String[] args) {
        List<ListNode> lists = List.of(build(1, 4, 5), build(1, 3, 4), build(2, 6));
        System.out.println(toList(mergeKLists(lists)));          // [1, 1, 2, 3, 4, 4, 5, 6]
        System.out.println(toList(mergeKLists(List.of())));      // []
    }
}
