import java.util.*;

public class Main {
    static class TreeNode {
        int val;
        TreeNode left, right;
        TreeNode(int val) { this.val = val; }
        TreeNode(int val, TreeNode left, TreeNode right) { this.val = val; this.left = left; this.right = right; }
    }

    static TreeNode invert(TreeNode root) {
        if (root != null) {
            TreeNode oldLeft = root.left;
            root.left = invert(root.right);
            root.right = invert(oldLeft);
        }
        return root;
    }

    static List<Integer> levelValues(TreeNode root) {
        List<Integer> out = new ArrayList<>();
        Deque<TreeNode> q = new ArrayDeque<>();
        if (root != null) q.add(root);
        while (!q.isEmpty()) {
            TreeNode n = q.poll();
            out.add(n.val);
            if (n.left != null) q.add(n.left);
            if (n.right != null) q.add(n.right);
        }
        return out;
    }

    public static void main(String[] args) {
        TreeNode root = new TreeNode(4, new TreeNode(2, new TreeNode(1), new TreeNode(3)),
                                        new TreeNode(7, new TreeNode(6), new TreeNode(9)));
        System.out.println(levelValues(invert(root)));  // [4, 7, 2, 9, 6, 3, 1]
        System.out.println(invert(null));               // null
    }
}
