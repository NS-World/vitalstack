public class Main {
    static class TreeNode {
        int val;
        TreeNode left, right;
        TreeNode(int val) { this.val = val; }
        TreeNode(int val, TreeNode left, TreeNode right) { this.val = val; this.left = left; this.right = right; }
    }

    static TreeNode lca(TreeNode root, TreeNode p, TreeNode q) {
        if (root == null || root == p || root == q) return root;
        TreeNode left = lca(root.left, p, q);
        TreeNode right = lca(root.right, p, q);
        if (left != null && right != null) return root;   // p and q are on different sides
        return left != null ? left : right;               // pass up whatever was found
    }

    public static void main(String[] args) {
        TreeNode n7 = new TreeNode(7), n4 = new TreeNode(4);
        TreeNode n2 = new TreeNode(2, n7, n4);
        TreeNode n5 = new TreeNode(5, new TreeNode(6), n2);
        TreeNode n1 = new TreeNode(1, new TreeNode(0), new TreeNode(8));
        TreeNode root = new TreeNode(3, n5, n1);
        System.out.println(lca(root, n5, n1).val);  // 3
        System.out.println(lca(root, n5, n4).val);  // 5
        System.out.println(lca(root, n7, n4).val);  // 2
    }
}
