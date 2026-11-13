#include <climits>
#include <iostream>
using namespace std;

struct TreeNode {
    int val;
    TreeNode *left, *right;
    TreeNode(int v, TreeNode* l = nullptr, TreeNode* r = nullptr) : val(v), left(l), right(r) {}
};

// long long bounds so a node holding INT_MIN or INT_MAX still works
bool isValidBst(TreeNode* root, long long low = LLONG_MIN, long long high = LLONG_MAX) {
    if (!root) return true;
    if (!(low < root->val && root->val < high)) return false;
    return isValidBst(root->left, low, root->val) && isValidBst(root->right, root->val, high);
}

int main() {
    cout << boolalpha;
    cout << isValidBst(new TreeNode(2, new TreeNode(1), new TreeNode(3))) << "\n";  // true
    TreeNode* bad = new TreeNode(5, new TreeNode(1), new TreeNode(6, new TreeNode(3), new TreeNode(7)));
    cout << isValidBst(bad) << "\n";                                                // false
    cout << isValidBst(new TreeNode(2, new TreeNode(2))) << "\n";                   // false
}
