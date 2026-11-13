#include <iostream>
using namespace std;

struct TreeNode {
    int val;
    TreeNode *left, *right;
    TreeNode(int v, TreeNode* l = nullptr, TreeNode* r = nullptr) : val(v), left(l), right(r) {}
};

TreeNode* lca(TreeNode* root, TreeNode* p, TreeNode* q) {
    if (!root || root == p || root == q) return root;
    TreeNode* left = lca(root->left, p, q);
    TreeNode* right = lca(root->right, p, q);
    if (left && right) return root;       // p and q are on different sides
    return left ? left : right;           // pass up whatever was found
}

int main() {
    TreeNode *n7 = new TreeNode(7), *n4 = new TreeNode(4);
    TreeNode* n2 = new TreeNode(2, n7, n4);
    TreeNode* n5 = new TreeNode(5, new TreeNode(6), n2);
    TreeNode* n1 = new TreeNode(1, new TreeNode(0), new TreeNode(8));
    TreeNode* root = new TreeNode(3, n5, n1);
    cout << lca(root, n5, n1)->val << "\n";  // 3
    cout << lca(root, n5, n4)->val << "\n";  // 5
    cout << lca(root, n7, n4)->val << "\n";  // 2
}
