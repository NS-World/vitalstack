// Same answer without a heap: sort the starts and the ends separately,
// then walk them like two pointers (a room frees up whenever an end comes first).
function minMeetingRooms(meetings) {
  const starts = meetings.map((m) => m[0]).sort((a, b) => a - b);
  const ends = meetings.map((m) => m[1]).sort((a, b) => a - b);
  let rooms = 0, e = 0;
  for (const s of starts) {
    if (s >= ends[e]) e++;      // a meeting ended: reuse its room
    else rooms++;               // open a new room
  }
  return rooms;
}

console.log(minMeetingRooms([[0, 30], [5, 10], [15, 20]]));  // 2
console.log(minMeetingRooms([[7, 10], [2, 4]]));             // 1
console.log(minMeetingRooms([[1, 5], [2, 6], [3, 7]]));      // 3
console.log(minMeetingRooms([]));                            // 0
