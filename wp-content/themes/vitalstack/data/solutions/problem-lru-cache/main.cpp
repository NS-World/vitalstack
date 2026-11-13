#include <iostream>
#include <list>
#include <unordered_map>
#include <utility>
using namespace std;

// std::list is a doubly linked list; the map stores an iterator to each node
class LRU {
    int cap;
    list<pair<int, int>> items;                                 // front = oldest, back = newest
    unordered_map<int, list<pair<int, int>>::iterator> where;
public:
    LRU(int capacity) : cap(capacity) {}

    int get(int key) {
        auto it = where.find(key);
        if (it == where.end()) return -1;
        items.splice(items.end(), items, it->second);           // move to newest in O(1)
        return it->second->second;
    }

    void put(int key, int value) {
        auto it = where.find(key);
        if (it != where.end()) items.erase(it->second);
        items.push_back({key, value});
        where[key] = prev(items.end());
        if ((int)where.size() > cap) {
            where.erase(items.front().first);                   // drop the oldest
            items.pop_front();
        }
    }
};

int main() {
    LRU c(2);
    c.put(1, 1);
    c.put(2, 2);
    cout << c.get(1) << "\n";  // 1
    c.put(3, 3);
    cout << c.get(2) << "\n";  // -1
    cout << c.get(3) << "\n";  // 3
}
