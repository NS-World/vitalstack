#include <algorithm>
#include <iostream>
#include <string>
#include <unordered_map>
#include <vector>
using namespace std;

vector<vector<string>> groupAnagrams(const vector<string>& words) {
    unordered_map<string, int> where;      // sorted key -> group number
    vector<vector<string>> groups;
    for (const string& w : words) {
        string key = w;
        sort(key.begin(), key.end());      // "eat" -> "aet"
        if (!where.count(key)) {
            where[key] = groups.size();
            groups.push_back({});
        }
        groups[where[key]].push_back(w);
    }
    return groups;
}

void print(const vector<vector<string>>& g) {
    cout << "[";
    for (size_t i = 0; i < g.size(); i++) {
        cout << (i ? ", " : "") << "[";
        for (size_t j = 0; j < g[i].size(); j++) cout << (j ? ", " : "") << "'" << g[i][j] << "'";
        cout << "]";
    }
    cout << "]\n";
}

int main() {
    print(groupAnagrams({"eat", "tea", "tan", "ate", "nat", "bat"}));  // [['eat', 'tea', 'ate'], ['tan', 'nat'], ['bat']]
    print(groupAnagrams({""}));                                        // [['']]
}
