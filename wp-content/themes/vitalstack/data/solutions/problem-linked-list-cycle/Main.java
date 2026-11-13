public class Main {
    static class ListNode {
        int val;
        ListNode next;
        ListNode(int val) { this.val = val; }
    }

    static boolean hasCycle(ListNode head) {
        ListNode slow = head, fast = head;
        while (fast != null && fast.next != null) {
            slow = slow.next;
            fast = fast.next.next;
            if (slow == fast) return true;   // same node object
        }
        return false;
    }

    public static void main(String[] args) {
        int[] values = {3, 2, 0, -4};
        ListNode[] nodes = new ListNode[values.length];
        for (int i = 0; i < values.length; i++) nodes[i] = new ListNode(values[i]);
        for (int i = 0; i + 1 < nodes.length; i++) nodes[i].next = nodes[i + 1];
        System.out.println(hasCycle(nodes[0]));  // false
        nodes[3].next = nodes[1];                // -4 points back to 2
        System.out.println(hasCycle(nodes[0]));  // true
        System.out.println(hasCycle(null));      // false
    }
}
