#include <iostream>
#include <queue>
#include <vector>
using namespace std;

struct TreeNode {
    int val;
    TreeNode *left, *right;
    TreeNode(int v, TreeNode* l = nullptr, TreeNode* r = nullptr) : val(v), left(l), right(r) {}
};

vector<vector<int>> levelOrder(TreeNode* root) {
    vector<vector<int>> out;
    if (!root) return out;
    queue<TreeNode*> q;
    q.push(root);
    while (!q.empty()) {
        vector<int> level;
        for (int size = q.size(); size > 0; size--) {   // exactly this level's nodes
            TreeNode* node = q.front();
            q.pop();
            level.push_back(node->val);
            if (node->left) q.push(node->left);
            if (node->right) q.push(node->right);
        }
        out.push_back(level);
    }
    return out;
}

void print(const vector<vector<int>>& g) {
    cout << "[";
    for (size_t i = 0; i < g.size(); i++) {
        cout << (i ? ", " : "") << "[";
        for (size_t j = 0; j < g[i].size(); j++) cout << (j ? ", " : "") << g[i][j];
        cout << "]";
    }
    cout << "]\n";
}

int main() {
    TreeNode* root = new TreeNode(3, new TreeNode(9), new TreeNode(20, new TreeNode(15), new TreeNode(7)));
    print(levelOrder(root));     // [[3], [9, 20], [15, 7]]
    print(levelOrder(nullptr));  // []
}
