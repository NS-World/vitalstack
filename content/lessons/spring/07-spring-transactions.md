---
title: Spring @Transactional - Transactions Made Simple
slug: spring-transactions
subject: spring
lesson: 7
difficulty: advanced
excerpt: See @Transactional commit or roll back a money transfer in an animation, then learn rollback rules for checked exceptions, propagation (REQUIRED, REQUIRES_NEW), readOnly, isolation and the common pitfalls.
---

In the JPA course you wrote `begin()`, `commit()` and `rollback()` by hand. In Spring, you write one annotation: **`@Transactional`**. Spring's AOP proxy opens a transaction before your method and commits or rolls back after it.

## All or nothing, animated

Try a transfer that fits the balance (like 300) and one that doesn't (1500):

::viz transactional {"amount": 1500}

## A real example

```java
package com.vitalstack;

import jakarta.persistence.*;

@Entity
public class Account {
    @Id private String owner;
    private int balance;

    protected Account() { }
    public Account(String owner, int balance) { this.owner = owner; this.balance = balance; }

    public void withdraw(int amount) { balance -= amount; }
    public void deposit(int amount) { balance += amount; }
    public int getBalance() { return balance; }
}
```

```java
package com.vitalstack;

import org.springframework.data.jpa.repository.JpaRepository;

public interface AccountRepository extends JpaRepository<Account, String> { }
```

```java
package com.vitalstack;

import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

@Service
public class BankService {
    private final AccountRepository accounts;

    public BankService(AccountRepository accounts) { this.accounts = accounts; }

    @Transactional
    public void transfer(String from, String to, int amount) {
        Account a = accounts.findById(from).orElseThrow();
        Account b = accounts.findById(to).orElseThrow();
        a.withdraw(amount);                                    // UPDATE pending (dirty checking)
        if (a.getBalance() < 0) {
            throw new IllegalStateException("Insufficient funds");   // → ROLLBACK: the withdraw is undone
        }
        b.deposit(amount);
    }                                                          // no exception → COMMIT

    @Transactional(readOnly = true)                            // a read-only transaction for queries
    public int totalMoney() {
        return accounts.findAll().stream().mapToInt(Account::getBalance).sum();
    }
}
```

```java
package com.vitalstack;

import org.springframework.boot.*;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.context.annotation.Bean;

@SpringBootApplication
public class Main {
    public static void main(String[] args) {
        SpringApplication app = new SpringApplication(Main.class);
        app.setWebApplicationType(WebApplicationType.NONE);
        app.run(args).close();
    }

    @Bean
    CommandLineRunner demo(AccountRepository repo, BankService bank) {
        return args -> {
            repo.save(new Account("A", 1000));
            repo.save(new Account("B", 500));

            bank.transfer("A", "B", 300);
            System.out.println("after 300: A=" + repo.findById("A").get().getBalance()
                    + ", B=" + repo.findById("B").get().getBalance());

            try {
                bank.transfer("A", "B", 1500);
            } catch (IllegalStateException e) {
                System.out.println("failed: " + e.getMessage());
            }
            System.out.println("after 1500: A=" + repo.findById("A").get().getBalance()
                    + ", B=" + repo.findById("B").get().getBalance());
        };
    }
}
```

```ini
# application.properties
spring.main.banner-mode=off
logging.level.root=warn
spring.jpa.open-in-view=false
```

Output:

```text
after 300: A=700, B=800
failed: Insufficient funds
after 1500: A=700, B=800
```

The failed transfer changed nothing: atomic, as promised.

## Rollback rules: the checked-exception surprise

By default Spring rolls back on **unchecked** exceptions (`RuntimeException`, `Error`), but **commits** on checked exceptions (`Exception`, `IOException`)!

```java
@Transactional
public void importCourses(Path file) throws IOException {
    repo.save(new Course("A"));
    throw new IOException("file broken");     // ⚠️ the save is COMMITTED by default
}

@Transactional(rollbackFor = Exception.class) // ✅ roll back on any exception
public void importCoursesSafely(Path file) throws IOException { ... }
```

Many teams use `rollbackFor = Exception.class` everywhere, or only throw unchecked exceptions from services.

## Propagation: what if a transaction is already running?

| Propagation | Behaviour |
|---|---|
| `REQUIRED` (default) | join the existing transaction, or start a new one |
| `REQUIRES_NEW` | suspend the current one and start an independent transaction |
| `SUPPORTS` | join if one exists; otherwise run without |
| `MANDATORY` | must be called inside a transaction, else error |
| `NOT_SUPPORTED` | suspend any transaction and run without |
| `NEVER` | error if a transaction exists |
| `NESTED` | savepoint inside the current transaction (JDBC only) |

Classic `REQUIRES_NEW` use: an **audit log** that must be saved even when the main operation fails:

```java
@Service
public class AuditService {
    @Transactional(propagation = Propagation.REQUIRES_NEW)
    public void record(String event) { auditRepo.save(new AuditEntry(event)); }   // commits on its own
}
```

## readOnly and isolation

```java
@Transactional(readOnly = true)                          // no dirty checking/flush; may route to read replicas
public List<CourseView> listCourses() { ... }

@Transactional(isolation = Isolation.SERIALIZABLE, timeout = 5)
public void bookSeat(...) { ... }
```

## The pitfalls (very common in interviews)

1. **Self-invocation:** calling a `@Transactional` method from another method **in the same class** bypasses the proxy, so no transaction starts. Move it to another bean.
2. **Private methods:** `@Transactional` on a `private` method does nothing.
3. **Catching the exception inside:** if you catch it within the transactional method, Spring never sees it and commits. Re-throw, or call `TransactionAspectSupport.currentTransactionStatus().setRollbackOnly()`.
4. **Checked exceptions** commit by default (see above).
5. **Long transactions:** don't call slow external APIs or send emails inside a transaction: it holds database connections and locks.
6. **Putting `@Transactional` on controllers:** keep it on the service layer, where business operations live.

::tip "UnexpectedRollbackException: Transaction silently rolled back because it has been marked as rollback-only" means an inner `REQUIRED` method threw an exception (marking the shared transaction for rollback) and an outer method caught it and tried to commit. Let the exception propagate, or use `REQUIRES_NEW` for truly independent work.

## Exercise

1. Run the example, then remove `@Transactional` from `transfer`. What are the balances after the failed transfer now?
2. Make `transfer` throw a checked `Exception` instead and observe that the withdrawal is committed. Fix it with `rollbackFor`.
3. Add an `AuditService` with `REQUIRES_NEW` that records every transfer attempt, including failed ones.
4. Move `transfer` into a private helper called from a public non-transactional method in the same class. Does rollback still work?

::hint Hint for 1 | Surprise: even the successful transfer isn't saved (A=1000, B=500). Without a transaction, `findById` returns detached objects, so dirty checking never runs. If you add `accounts.save(a)` and `accounts.save(b)`, each save commits on its own, and a failure between them makes money disappear. That's exactly what a transaction prevents.

## Interview corner

- **How does @Transactional work?** A proxy (AOP) begins a transaction before the method and commits/rolls back after it, using a PlatformTransactionManager.
- **Default rollback behaviour?** Roll back on RuntimeException and Error; commit on checked exceptions unless `rollbackFor` is set.
- **REQUIRED vs REQUIRES_NEW?** Join the current transaction vs suspend it and run an independent one.
- **Why doesn't @Transactional work on internal calls?** Self-invocation bypasses the proxy.

## Summary

- `@Transactional` on service methods gives all-or-nothing behaviour.
- Roll back on checked exceptions with `rollbackFor`.
- Propagation controls joining vs new transactions; readOnly for queries.
- Beware self-invocation, private methods, swallowed exceptions and long transactions.

Next: Spring MVC, the web layer.
