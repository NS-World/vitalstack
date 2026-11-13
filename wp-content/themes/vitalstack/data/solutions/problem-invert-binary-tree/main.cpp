#include <iostream>
#include <queue>
#include <utility>
#include <vector>
using namespace std;

struct TreeNode {
    int val;
    TreeNode *left, *right;
    TreeNode(int v, TreeNode* l = nullptr, TreeNode* r = nullptr) : val(v), left(l), right(r) {}
};

TreeNode* invert(TreeNode* root) {
    if (root) {
        TreeNode* oldLeft = root->left;
        root->left = invert(root->right);
        root->right = invert(oldLeft);
    }
    return root;
}

vector<int> levelValues(TreeNode* root) {
    vector<int> out;
    queue<TreeNode*> q;
    if (root) q.push(root);
    while (!q.empty()) {
        TreeNode* n = q.front();
        q.pop();
        out.push_back(n->val);
        if (n->left) q.push(n->left);
        if (n->right) q.push(n->right);
    }
    return out;
}

void print(const vector<int>& v) {
    cout << "[";
    for (size_t i = 0; i < v.size(); i++) cout << (i ? ", " : "") << v[i];
    cout << "]\n";
}

int main() {
    TreeNode* root = new TreeNode(4, new TreeNode(2, new TreeNode(1), new TreeNode(3)),
                                     new TreeNode(7, new TreeNode(6), new TreeNode(9)));
    print(levelValues(invert(root)));                            // [4, 7, 2, 9, 6, 3, 1]
    cout << (invert(nullptr) == nullptr ? "null" : "?") << "\n";  // null
}
