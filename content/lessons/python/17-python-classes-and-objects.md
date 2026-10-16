---
title: Python Classes and Objects
slug: python-classes-and-objects
subject: python
lesson: 17
difficulty: intermediate
excerpt: Object-oriented programming in Python: define classes, create objects, use __init__, attributes and methods, class attributes, special methods, properties, inheritance and dataclasses.
---

So far you've used Python's built-in types: strings, lists, dictionaries. **Classes** let you create your own types that bundle data and behaviour together: a `BankAccount` with a balance and a `deposit()` method, a `Product` with a price and a `discounted()` method. This style is called **object-oriented programming** (OOP).

## Class and object

- A **class** is a blueprint: it describes what data and abilities something has.
- An **object** (or **instance**) is one thing built from that blueprint.

You've already used objects: `"hello"` is an object of class `str`, and `.upper()` is one of its methods.

## Defining a class

```python
class Dog:
    def __init__(self, name, age):
        self.name = name
        self.age = age

    def bark(self):
        return f"{self.name} says woof!"

rex = Dog("Rex", 3)
bella = Dog("Bella", 5)

print(rex.name, rex.age)    # Rex 3
print(bella.bark())         # Bella says woof!
```

- `class Dog:` defines the class. Class names use **PascalCase**.
- `__init__` is the **initialiser**: it runs automatically when you create an object and sets up its data.
- `self` is the object being created or used. `self.name = name` stores the value **on that object**.
- `bark` is a **method**: a function that belongs to the class. Its first parameter is always `self`.
- `Dog("Rex", 3)` creates an object; Python passes the new object as `self` automatically.

Each object has its own data: changing `rex.age` doesn't affect `bella`.

## A more useful example: a bank account

```python
class BankAccount:
    def __init__(self, owner, balance=0):
        self.owner = owner
        self.balance = balance
        self.history = []

    def deposit(self, amount):
        if amount <= 0:
            raise ValueError("Deposit must be positive.")
        self.balance += amount
        self.history.append(("deposit", amount))

    def withdraw(self, amount):
        if amount > self.balance:
            raise ValueError("Insufficient funds.")
        self.balance -= amount
        self.history.append(("withdraw", amount))

account = BankAccount("Asha", 1000)
account.deposit(500)
account.withdraw(200)
print(account.balance)      # 1300
print(account.history)      # [('deposit', 500), ('withdraw', 200)]
```

The object keeps its own state (balance, history), and the methods are the only intended way to change it, with rules built in. That's the main benefit of classes: data and the rules for changing it live together.

## Class attributes

Attributes defined directly in the class are **shared** by all objects:

```python
class Product:
    tax_rate = 0.18             # class attribute: same for every product

    def __init__(self, name, price):
        self.name = name        # instance attributes: different per product
        self.price = price

    def price_with_tax(self):
        return round(self.price * (1 + Product.tax_rate), 2)

pen = Product("Pen set", 250)
bag = Product("Backpack", 1899)
print(pen.price_with_tax(), bag.price_with_tax())   # 295.0 2240.82
```

Use class attributes for constants and settings shared by all instances. Don't use a mutable class attribute (like a list) for per-object data: every object would share the same list.

## Special methods: __str__ and __repr__

Printing an object normally shows something unhelpful like `<__main__.Product object at 0x7f…>`. Special methods (with double underscores, "dunder" methods) customise built-in behaviour:

```python
class Product:
    def __init__(self, name, price):
        self.name = name
        self.price = price

    def __str__(self):
        return f"{self.name} (₹{self.price:,})"

    def __repr__(self):
        return f"Product({self.name!r}, {self.price})"

    def __eq__(self, other):
        return isinstance(other, Product) and (self.name, self.price) == (other.name, other.price)

    def __lt__(self, other):
        return self.price < other.price

items = [Product("Backpack", 1899), Product("Notebook", 120), Product("Pen set", 250)]
print(items[0])                     # Backpack (₹1,899)
print(items)                        # uses __repr__ for items inside a list
print(sorted(items))                # sorting works thanks to __lt__
print(Product("A", 1) == Product("A", 1))   # True thanks to __eq__
```

- `__str__`: friendly text for `print()` and `str()`.
- `__repr__`: unambiguous text for developers, shown in lists and the REPL.
- `__eq__`, `__lt__`: make `==` and sorting work.
- Others include `__len__` (for `len()`), `__add__` (for `+`) and `__contains__` (for `in`).

## Properties: computed and protected attributes

```python
class Temperature:
    def __init__(self, celsius):
        self.celsius = celsius

    @property
    def fahrenheit(self):
        return self.celsius * 9 / 5 + 32

    @property
    def celsius(self):
        return self._celsius

    @celsius.setter
    def celsius(self, value):
        if value < -273.15:
            raise ValueError("Below absolute zero!")
        self._celsius = value

t = Temperature(25)
print(t.fahrenheit)     # 77.0: computed, used like an attribute
t.celsius = 30          # goes through the setter's check
print(t.fahrenheit)     # 86.0
```

`@property` turns a method into something you read like an attribute. A setter adds validation. A leading underscore (`_celsius`) is a convention meaning "internal: don't touch from outside". Python doesn't enforce privacy; it trusts developers to respect the convention.

## Inheritance

A class can **inherit** from another, reusing its code and adding or changing behaviour:

```python
class Account:
    def __init__(self, owner, balance=0):
        self.owner = owner
        self.balance = balance

    def withdraw(self, amount):
        if amount > self.balance:
            raise ValueError("Insufficient funds.")
        self.balance -= amount

    def describe(self):
        return f"{self.owner}: ₹{self.balance:,}"

class SavingsAccount(Account):
    def __init__(self, owner, balance=0, rate=0.04):
        super().__init__(owner, balance)     # run the parent's __init__
        self.rate = rate

    def add_interest(self):
        self.balance += round(self.balance * self.rate)

    def describe(self):                       # override the parent method
        return super().describe() + f" (savings, {self.rate:.0%})"

s = SavingsAccount("Meera", 10000)
s.add_interest()
s.withdraw(400)                               # inherited method
print(s.describe())                           # Meera: ₹10,000 (savings, 4%)
print(isinstance(s, Account))                 # True
```

- `class SavingsAccount(Account):` inherits everything from `Account`.
- `super()` calls the parent's version of a method.
- Defining a method with the same name **overrides** the parent's.

Use inheritance for genuine "is a" relationships (a savings account **is an** account). For "has a" relationships (a car **has an** engine), store one object inside another instead; this is called **composition** and is often simpler.

## Dataclasses: less boilerplate

For classes that mainly hold data, `dataclass` writes `__init__`, `__repr__` and `__eq__` for you:

```python
from dataclasses import dataclass, field

@dataclass
class Expense:
    title: str
    amount: float
    category: str = "other"
    tags: list = field(default_factory=list)

    def is_large(self):
        return self.amount > 1000

e = Expense("Groceries", 1450.5, "food")
print(e)                 # Expense(title='Groceries', amount=1450.5, category='food', tags=[])
print(e.is_large())      # True
print(e == Expense("Groceries", 1450.5, "food"))   # True
```

`field(default_factory=list)` gives each object its own new list (the safe way to have a mutable default). Dataclasses are used a lot in modern Python, and you'll use one in the project lesson.

## When to use classes

Use a class when you have **data and behaviour that belong together**, or you need many objects of the same kind with their own state. For a simple calculation, a function is enough; for simple records, a dictionary or dataclass often is.

## Common mistakes

- **Forgetting `self`** as the first method parameter, or forgetting `self.` when using attributes (`balance += 1` instead of `self.balance += 1`).
- **Calling the method on the class** instead of an object: `Dog.bark()`.
- **Mutable class attributes** shared by accident.
- **Deep inheritance trees**; prefer composition and keep hierarchies shallow.

## Exercise

1. Create a `Book` class with title, author and pages, and a `__str__` like "Title by Author (300 pages)".
2. Add a method `is_long()` that returns True for books over 400 pages.
3. Create a `Rectangle` class with `area` and `perimeter` properties.
4. Create a `Student` class with a list of marks and methods `add_mark(mark)` and `average()`.
5. Create `Shape` with `area()` returning 0, and `Circle` and `Square` subclasses that override it. Put several shapes in a list and print their areas.

## Summary

- A class is a blueprint; objects are instances with their own data.
- `__init__` sets up attributes on `self`; methods are functions that take `self`.
- Special methods like `__str__`, `__eq__` and `__lt__` hook into Python's built-in behaviour.
- Properties compute or validate attributes; inheritance reuses and extends classes; dataclasses remove boilerplate.

Next, you'll install third-party packages with pip and manage them with virtual environments.
