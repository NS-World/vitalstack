## java-introduction
Q: What does the `javac` command produce from `Hello.java`?
- [ ] Machine code for your CPU
- [x] Bytecode in `Hello.class`
- [ ] A Windows .exe file
> javac compiles source into platform-independent bytecode that the JVM runs.

Q: Which part do you install to write and compile Java programs?
- [ ] JVM only
- [ ] JRE
- [x] JDK
> The JDK includes the compiler and tools, plus the JRE and JVM.

Q: Why is Java called "write once, run anywhere"?
- [x] Bytecode runs on any OS that has a JVM
- [ ] The compiler creates a file for every OS
- [ ] Java runs only in browsers
> Each operating system has its own JVM that executes the same bytecode.

## java-setup-and-first-program
Q: How do you run the compiled class `Greeting`?
- [ ] `java Greeting.class`
- [x] `java Greeting`
- [ ] `javac Greeting`
> Pass the class name without the .class extension.

Q: What is JShell?
- [ ] A Linux terminal
- [x] An interactive tool to try Java code without a class or main method
- [ ] A Java IDE plugin
> JShell (Java 9+) evaluates Java snippets immediately.

Q: Where does `String[] args` get its values?
- [x] Words typed after the class name when running the program
- [ ] The keyboard while the program runs
- [ ] Environment variables
> Command-line arguments are passed into main's args array.

## java-variables-and-data-types
Q: What does `(int) 9.99` give?
- [ ] 10
- [x] 9
- [ ] 9.99
> Casting to int drops the decimal part; it doesn't round.

Q: Which type should store 3,000,000,000?
- [ ] int
- [x] long
- [ ] short
> int only goes up to about 2.1 billion; use long (with an L suffix for literals).

Q: Is `String` a primitive type?
- [ ] Yes
- [x] No, it's a class
- [ ] Only in Java 21
> Java has 8 primitives; String is a class and variables hold references.

## java-operators
Q: What does `7 / 2` evaluate to in Java?
- [x] 3
- [ ] 3.5
- [ ] 4
> int divided by int is integer division.

Q: After `int a = 5; int b = a++;` what is b?
- [x] 5
- [ ] 6
- [ ] 4
> Post-increment returns the old value, then increments a.

Q: What does `"Sum: " + 2 + 3` print?
- [ ] Sum: 5
- [x] Sum: 23
- [ ] An error
> Evaluation goes left to right, joining text first. Use brackets for (2 + 3).

## java-strings
Q: How should you compare the content of two Strings?
- [ ] `a == b`
- [x] `a.equals(b)`
- [ ] `a = b`
> == compares references; equals compares characters.

Q: What does `"hello".toUpperCase();` do to the variable it's called on?
- [ ] Changes it to HELLO
- [x] Nothing: it returns a new String
- [ ] Throws an error
> Strings are immutable; save the result to use it.

Q: Which class is best for building a String inside a loop?
- [x] StringBuilder
- [ ] String with +=
- [ ] Scanner
> StringBuilder is mutable and avoids creating a new String each time.

## java-input-output
Q: Why does `nextLine()` return an empty string right after `nextInt()`?
- [x] nextInt leaves the Enter (newline) behind
- [ ] Scanner is broken
- [ ] The user typed nothing
> Consume the leftover newline, or read lines and parse them.

Q: Which printf format shows a number with 2 decimal places?
- [ ] `%d`
- [x] `%.2f`
- [ ] `%2s`
> %.2f formats a decimal with two digits after the point.

Q: Which method checks if the next input is an integer without reading it?
- [x] `hasNextInt()`
- [ ] `nextInt()`
- [ ] `isInt()`
> hasNextInt lets you validate before reading.

## java-conditions
Q: What happens in a classic switch case without `break`?
- [ ] Compile error
- [x] Execution falls through into the next case
- [ ] The switch ends
> Classic switch falls through; the arrow form (->) never does.

Q: Which is valid in Java?
- [ ] `if (marks) { }` where marks is an int
- [x] `if (marks > 40) { }`
- [ ] `if marks > 40 { }`
> The condition must be a boolean and wrapped in brackets.

Q: In a switch expression block, which keyword returns a value?
- [ ] return
- [x] yield
- [ ] break
> `yield` produces the value from a block inside a switch expression.

## java-loops
Q: Which loop always runs at least once?
- [ ] for
- [ ] while
- [x] do-while
> do-while checks its condition after the body.

Q: What does `continue` do?
- [ ] Exits the loop
- [x] Skips to the next iteration
- [ ] Restarts the program
> continue skips the rest of the current round.

Q: How many times does the inner body run in two nested loops of 10 each?
- [ ] 20
- [x] 100
- [ ] 10
> Nested loops multiply: 10 × 10.

## java-arrays
Q: What is the last valid index of an array of length 5?
- [ ] 5
- [x] 4
- [ ] 6
> Indexes start at 0, so the last one is length - 1.

Q: How do you print an int array's contents?
- [ ] `System.out.println(arr)`
- [x] `System.out.println(Arrays.toString(arr))`
- [ ] `arr.print()`
> println(arr) prints a type and hash, not the values.

Q: After `int[] b = a; b[0] = 99;` what is a[0]?
- [x] 99
- [ ] The old value
- [ ] An error
> b and a refer to the same array object.

## java-methods
Q: Is Java pass-by-value or pass-by-reference?
- [x] Always pass-by-value (object references are copied)
- [ ] Always pass-by-reference
- [ ] Depends on the type
> Methods receive copies; for objects, a copy of the reference.

Q: What causes a StackOverflowError?
- [ ] Too many variables
- [x] Recursion that never reaches a base case
- [ ] A missing semicolon
> Each call adds a stack frame until memory runs out.

Q: Which is valid method overloading?
- [x] `add(int a, int b)` and `add(int a, int b, int c)`
- [ ] Two methods differing only in return type
- [ ] Two methods with the same parameters
> Overloads must differ in their parameter lists.

## java-arraylist-and-collections
Q: Why can't you write `List<int>`?
- [x] Collections hold objects; use `List<Integer>`
- [ ] int is too small
- [ ] Generics are not allowed with List
> Wrapper classes and autoboxing bridge primitives and collections.

Q: What happens when an ArrayList is full and you add an item?
- [ ] It throws an exception
- [x] It creates a bigger array and copies the items
- [ ] It deletes the oldest item
> It grows by about 50%, so adding at the end is O(1) on average.

Q: How should you remove items while iterating a list?
- [ ] Inside a for-each loop
- [x] With `removeIf` or an Iterator's `remove()`
- [ ] With `list.clear()`
> Removing in for-each throws ConcurrentModificationException.

## java-hashmap
Q: What does `map.get(key)` return when the key is missing?
- [ ] 0
- [x] null
- [ ] An exception
> Use getOrDefault to supply a fallback.

Q: Which map keeps keys sorted?
- [ ] HashMap
- [ ] LinkedHashMap
- [x] TreeMap
> TreeMap is ordered by key (O(log n) operations).

Q: If you use your own class as a HashMap key, what must you override?
- [ ] toString only
- [x] equals and hashCode
- [ ] compareTo only
> Equal keys must have equal hash codes or lookups fail.

## java-exceptions
Q: When does a finally block run?
- [x] Almost always: after try/catch, even after return
- [ ] Only when an exception occurs
- [ ] Only when no exception occurs
> finally is for cleanup; only System.exit or a crash skips it.

Q: Which exception is checked?
- [ ] NullPointerException
- [x] IOException
- [ ] ArithmeticException
> Checked exceptions must be caught or declared with throws.

Q: What does try-with-resources do?
- [ ] Retries the code
- [x] Closes AutoCloseable resources automatically
- [ ] Ignores exceptions
> Files and connections are closed even when errors happen.

## java-lambdas-and-streams
Q: When does a stream pipeline actually run?
- [ ] When filter is called
- [x] When a terminal operation like collect or toList is called
- [ ] When the stream is created
> Intermediate operations are lazy.

Q: What is a functional interface?
- [x] An interface with exactly one abstract method
- [ ] An interface with only static methods
- [ ] Any interface used in streams
> Lambdas implement functional interfaces like Predicate and Function.

Q: What does `Optional.orElse("none")` do?
- [ ] Always returns "none"
- [x] Returns the value if present, otherwise "none"
- [ ] Throws if empty
> Optional makes "might be missing" explicit and safe.

## java-file-io
Q: Which is better for a 2 GB log file?
- [ ] `Files.readAllLines`
- [x] `Files.lines` in try-with-resources
- [ ] `Files.readString`
> Files.lines streams lazily so memory stays small.

Q: How do you append to a file with Files.writeString?
- [ ] It always appends
- [x] Pass `StandardOpenOption.APPEND`
- [ ] Use `Files.append`
> Without APPEND, writeString overwrites the file.

Q: Relative file paths are resolved from…
- [ ] The folder of the .java file
- [x] The folder where you run the program
- [ ] The user's home folder
> The working directory decides where relative paths point.

## java-project-student-manager
Q: Why is a record a good fit for `Student(name, marks)`?
- [x] It's immutable data with constructor, accessors, equals and toString generated
- [ ] It can extend other classes
- [ ] It allows setters
> Records are concise immutable data carriers.

Q: How does the app avoid crashing on invalid marks?
- [ ] It ignores input
- [x] It catches the exception and shows a message
- [ ] It restarts
> try/catch around parsing keeps the menu running.

Q: Where is the data stored between runs?
- [ ] In memory only
- [x] In a CSV file
- [ ] In a database
> The program loads students.csv at start and saves on exit.
