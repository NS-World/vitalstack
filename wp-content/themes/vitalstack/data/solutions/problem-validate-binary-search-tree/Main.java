public class Main {
    static class TreeNode {
        int val;
        TreeNode left, right;
        TreeNode(int val) { this.val = val; }
        TreeNode(int val, TreeNode left, TreeNode right) { this.val = val; this.left = left; this.right = right; }
    }

    // long bounds so a node holding Integer.MIN_VALUE or MAX_VALUE still works
    static boolean isValidBst(TreeNode root, long low, long high) {
        if (root == null) return true;
        if (!(low < root.val && root.val < high)) return false;
        return isValidBst(root.left, low, root.val) && isValidBst(root.right, root.val, high);
    }

    static boolean isValidBst(TreeNode root) {
        return isValidBst(root, Long.MIN_VALUE, Long.MAX_VALUE);
    }

    public static void main(String[] args) {
        System.out.println(isValidBst(new TreeNode(2, new TreeNode(1), new TreeNode(3))));  // true
        TreeNode bad = new TreeNode(5, new TreeNode(1), new TreeNode(6, new TreeNode(3), new TreeNode(7)));
        System.out.println(isValidBst(bad));                                                // false
        System.out.println(isValidBst(new TreeNode(2, new TreeNode(2), null)));             // false
    }
}
