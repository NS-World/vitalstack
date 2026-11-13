#include <iostream>
#include <queue>
#include <string>
#include <unordered_map>
#include <unordered_set>
#include <vector>
using namespace std;

int ladderLength(const string& begin, const string& end, const vector<string>& wordList) {
    unordered_set<string> words(wordList.begin(), wordList.end());
    if (!words.count(end)) return 0;
    queue<string> q;
    unordered_map<string, int> steps{{begin, 1}};   // also works as "seen"
    q.push(begin);
    while (!q.empty()) {
        string word = q.front();
        q.pop();
        if (word == end) return steps[word];
        string nxt = word;
        for (size_t i = 0; i < nxt.size(); i++) {
            char original = nxt[i];
            for (char ch = 'a'; ch <= 'z'; ch++) {
                nxt[i] = ch;
                if (words.count(nxt) && !steps.count(nxt)) {
                    steps[nxt] = steps[word] + 1;
                    q.push(nxt);
                }
            }
            nxt[i] = original;
        }
    }
    return 0;
}

int main() {
    cout << ladderLength("hit", "cog", {"hot", "dot", "dog", "lot", "log", "cog"}) << "\n";  // 5
    cout << ladderLength("hit", "cog", {"hot", "dot", "dog", "lot", "log"}) << "\n";         // 0
}
