## java-oop-introduction
Q: What is the relationship between a class and an object?
- [x] A class is a blueprint; objects are instances built from it
- [ ] They are the same thing
- [ ] An object creates classes
> One Dog class can create many Dog objects with their own data.

Q: Which keyword creates a new object?
- [ ] class
- [x] new
- [ ] this
> `new Dog()` allocates an object and runs its constructor.

Q: Which is NOT one of the four OOP pillars?
- [ ] Encapsulation
- [ ] Polymorphism
- [x] Compilation
> The pillars are encapsulation, inheritance, polymorphism and abstraction.

## java-oop-classes-and-objects
Q: What does `this.name = name;` do in a constructor?
- [x] Sets the object's field to the parameter's value
- [ ] Creates a new variable
- [ ] Calls another constructor
> `this` refers to the current object; it disambiguates field and parameter.

Q: After `Student y = x; y.marks = 50;` what is `x.marks`?
- [x] 50
- [ ] Its old value
- [ ] null
> Both variables reference the same object on the heap.

Q: What happens when you call a method on a null reference?
- [ ] Nothing
- [x] NullPointerException
- [ ] It returns null
> There is no object to run the method on.

## java-oop-static-and-constructors
Q: How many copies of a static field exist?
- [x] One, shared by the whole class
- [ ] One per object
- [ ] None until main runs
> Static members belong to the class, not to instances.

Q: Why can't a static method use `this`?
- [ ] It's a syntax rule only for main
- [x] It isn't called on any particular object
- [ ] this is private
> Static methods have no current object.

Q: Where must `this(...)` appear in a constructor?
- [x] As the first statement
- [ ] As the last statement
- [ ] Anywhere
> Constructor chaining must be the first line.

## java-oop-encapsulation
Q: What is the main idea of encapsulation?
- [x] Keep fields private and change them through methods with rules
- [ ] Put all classes in one file
- [ ] Make every field public for convenience
> Methods like deposit/withdraw validate every change.

Q: Which modifier allows access only inside the same class?
- [x] private
- [ ] protected
- [ ] public
> private is the most restrictive access level.

Q: Which is part of making a class immutable?
- [ ] Adding setters
- [x] private final fields and no setters
- [ ] Using static fields
> Immutable objects return new objects for changes.

## java-oop-inheritance
Q: What does `@Override` give you?
- [x] A compile error if the method doesn't actually override a parent method
- [ ] Faster code
- [ ] Multiple inheritance
> It catches typos like speek() instead of speak().

Q: How does Java find `p.speak()` on a Puppy?
- [ ] It only looks in Puppy
- [x] It starts at Puppy and walks up the hierarchy
- [ ] It picks a random parent
> The first matching method found going up is used.

Q: "A Car has an Engine" should be modelled with…
- [ ] class Car extends Engine
- [x] Composition: Car holds an Engine field
- [ ] An interface Engine
> Inheritance is for is-a; composition is for has-a.

## java-oop-polymorphism
Q: With `Animal a = new Dog(); a.speak();` which speak runs?
- [ ] Animal's
- [x] Dog's
- [ ] Neither
> The object's real class decides at runtime (dynamic dispatch).

Q: Method overloading is resolved…
- [x] At compile time
- [ ] At runtime
- [ ] By the garbage collector
> Overloading picks a method by argument types; overriding is runtime.

Q: What's the safe way to downcast?
- [ ] Always cast directly
- [x] Check with `instanceof` (pattern matching) first
- [ ] Use toString
> A wrong cast throws ClassCastException.

## java-oop-abstraction
Q: Can you create an object of an abstract class with new?
- [ ] Yes
- [x] No
- [ ] Only if it has a constructor
> Abstract classes are partial blueprints; instantiate a concrete subclass.

Q: What must a concrete subclass do with abstract methods?
- [x] Implement all of them
- [ ] Ignore them
- [ ] Make them static
> Otherwise the subclass must be abstract too.

Q: In the template method pattern, the parent class…
- [x] Fixes the steps; subclasses fill in details
- [ ] Contains no methods
- [ ] Must be an interface
> generate() calls header(), body(), footer() in a fixed order.

## java-oop-interfaces
Q: How many interfaces can a class implement?
- [ ] One
- [ ] Two
- [x] Many
> Java allows multiple interface implementation (but one superclass).

Q: Comparable vs Comparator?
- [x] Comparable is natural order inside the class; Comparator is an external ordering
- [ ] They are identical
- [ ] Comparator is only for Strings
> Use Comparator.comparing(...) for extra sort orders.

Q: What can an interface method have since Java 8?
- [ ] Instance fields
- [x] A default implementation
- [ ] A constructor
> default and static methods can have bodies.

## java-oop-equals-hashcode-tostring
Q: What's wrong with overriding equals but not hashCode?
- [x] Equal objects may land in different HashMap/HashSet buckets
- [ ] Nothing
- [ ] toString stops working
> Equal objects must have equal hash codes.

Q: Which signature correctly overrides equals?
- [ ] `equals(Book other)`
- [x] `equals(Object o)`
- [ ] `equals()`
> equals(Book) overloads instead of overriding.

Q: What do records generate automatically?
- [x] equals, hashCode and toString
- [ ] Setters
- [ ] A main method
> Records derive them from their components.

## java-oop-records-enums-sealed
Q: Which statement about records is true?
- [x] Their fields are final and they can't extend other classes
- [ ] They have setters
- [ ] They must be abstract
> Records are immutable data carriers that can implement interfaces.

Q: How should enums be stored with JPA?
- [ ] EnumType.ORDINAL
- [x] EnumType.STRING
- [ ] As a boolean
> ORDINAL breaks when constants are reordered.

Q: What do sealed interfaces enable?
- [x] Exhaustive pattern-matching switches over a closed set of types
- [ ] Faster loops
- [ ] Multiple inheritance of classes
> The compiler knows every permitted subtype.

## java-oop-solid-and-design-patterns
Q: The Open/Closed Principle says code should be…
- [x] Open for extension, closed for modification
- [ ] Open source
- [ ] Closed to new features
> Add new classes instead of editing existing if/else chains.

Q: Which pattern builds complex objects step by step?
- [ ] Singleton
- [x] Builder
- [ ] Observer
> Builder replaces long constructor parameter lists.

Q: Dependency Inversion is what Spring automates via…
- [x] Constructor injection of interfaces
- [ ] Static methods
- [ ] Inheritance
> Classes depend on abstractions supplied from outside.

## java-oop-project-library-system
Q: Why are Student and Faculty subclasses of an abstract Member?
- [x] Each defines its own rules (max books, loan days, fines) used polymorphically
- [ ] To save memory
- [ ] Because records can't be used
> Loan calls member.loanDays() without knowing the type.

Q: Where are the borrowing rules enforced?
- [ ] In main
- [x] In the Library service methods
- [ ] In the Book record
> Encapsulated services keep rules in one place.

Q: What happens when a book has no copies left?
- [ ] It returns null
- [x] A LibraryException with a clear message is thrown
- [ ] The count goes negative
> Custom exceptions communicate business rule violations.
