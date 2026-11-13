import java.util.*;

public class Main {
    static class Node {
        int key, val;
        Node prev, next;
        Node(int key, int val) { this.key = key; this.val = val; }
    }

    static class LRU {
        private final int cap;
        private final Map<Integer, Node> map = new HashMap<>();
        private final Node head = new Node(0, 0), tail = new Node(0, 0);   // oldest after head, newest before tail

        LRU(int capacity) {
            cap = capacity;
            head.next = tail;
            tail.prev = head;
        }

        private void remove(Node node) {
            node.prev.next = node.next;
            node.next.prev = node.prev;
        }

        private void addNewest(Node node) {
            node.prev = tail.prev;
            node.next = tail;
            tail.prev.next = node;
            tail.prev = node;
        }

        int get(int key) {
            Node node = map.get(key);
            if (node == null) return -1;
            remove(node);
            addNewest(node);
            return node.val;
        }

        void put(int key, int value) {
            if (map.containsKey(key)) remove(map.get(key));
            Node node = new Node(key, value);
            map.put(key, node);
            addNewest(node);
            if (map.size() > cap) {
                Node oldest = head.next;
                remove(oldest);
                map.remove(oldest.key);
            }
        }
    }

    public static void main(String[] args) {
        LRU c = new LRU(2);
        c.put(1, 1);
        c.put(2, 2);
        System.out.println(c.get(1));  // 1
        c.put(3, 3);
        System.out.println(c.get(2));  // -1
        System.out.println(c.get(3));  // 3
    }
}
