---
title: Spring @Configuration, @Bean, @Primary and @Qualifier
slug: spring-configuration-and-beans
subject: spring
lesson: 3
difficulty: intermediate
excerpt: Define beans with @Configuration and @Bean for classes you don't own, resolve multiple implementations with @Primary and @Qualifier, inject all beans of a type as a List or Map, and use conditional beans.
---

Component scanning works for **your** classes. But how do you make a bean from a library class (a `RestClient`, an `ObjectMapper`, a payment SDK) that you can't annotate? And what if two beans implement the same interface? This lesson answers both.

## @Configuration and @Bean

A `@Configuration` class contains `@Bean` methods. Each method's return value becomes a bean:

```java
@Configuration
public class AppConfig {

    @Bean
    public Clock clock() {
        return Clock.systemDefaultZone();          // a JDK class: can't put @Component on it
    }

    @Bean
    public PriceCalculator priceCalculator(Clock clock) {   // parameters are injected too
        return new PriceCalculator(clock, new BigDecimal("0.18"));
    }
}
```

- The bean name is the method name (`clock`, `priceCalculator`).
- Method parameters are resolved from the container, just like constructor injection.
- Use `@Bean` for third-party classes and for objects that need custom setup.

## Multiple implementations: the ambiguity problem

```java
package com.vitalstack.pay;

public interface PaymentGateway {
    String name();
    int feeFor(int amount);
}
```

```java
package com.vitalstack.pay;

import org.springframework.context.annotation.Primary;
import org.springframework.stereotype.Component;

@Component
@Primary                                          // the default when someone asks for "a PaymentGateway"
public class UpiGateway implements PaymentGateway {
    public String name() { return "UPI"; }
    public int feeFor(int amount) { return 0; }
}
```

```java
package com.vitalstack.pay;

import org.springframework.stereotype.Component;

@Component("card")                                // explicit bean name
public class CardGateway implements PaymentGateway {
    public String name() { return "Card"; }
    public int feeFor(int amount) { return amount * 2 / 100; }
}
```

```java
package com.vitalstack.pay;

import org.springframework.stereotype.Component;

@Component
public class WalletGateway implements PaymentGateway {
    public String name() { return "Wallet"; }
    public int feeFor(int amount) { return amount / 100; }
}
```

## @Primary, @Qualifier, and injecting them all

```java
package com.vitalstack.pay;

import org.springframework.beans.factory.annotation.Qualifier;
import org.springframework.stereotype.Service;
import java.util.List;
import java.util.Map;

@Service
public class Checkout {
    private final PaymentGateway defaultGateway;
    private final PaymentGateway cardGateway;
    private final List<PaymentGateway> all;
    private final Map<String, PaymentGateway> byName;

    public Checkout(PaymentGateway defaultGateway,                     // @Primary wins
                    @Qualifier("card") PaymentGateway cardGateway,    // pick by name
                    List<PaymentGateway> all,                         // every implementation
                    Map<String, PaymentGateway> byName) {             // bean name → bean
        this.defaultGateway = defaultGateway;
        this.cardGateway = cardGateway;
        this.all = all;
        this.byName = byName;
    }

    public void report(int amount) {
        System.out.println("default: " + defaultGateway.name());
        System.out.println("qualified: " + cardGateway.name());
        all.forEach(g -> System.out.println(g.name() + " fee on ₹" + amount + " = ₹" + g.feeFor(amount)));
        System.out.println("names: " + byName.keySet());
    }
}
```

```java
package com.vitalstack;

import com.vitalstack.pay.Checkout;
import org.springframework.context.annotation.*;

@Configuration
@ComponentScan("com.vitalstack.pay")
public class Main {
    public static void main(String[] args) {
        try (var ctx = new AnnotationConfigApplicationContext(Main.class)) {
            ctx.getBean(Checkout.class).report(1000);
        }
    }
}
```

Output:

```text
default: UPI
qualified: Card
Card fee on ₹1000 = ₹20
UPI fee on ₹1000 = ₹0
Wallet fee on ₹1000 = ₹10
names: [card, upiGateway, walletGateway]
```

| Tool | Meaning |
|---|---|
| `@Primary` | "use me by default when there are several" |
| `@Qualifier("card")` | "inject exactly this bean" |
| `List<T>` | "give me all of them" (order with `@Order` if needed) |
| `Map<String, T>` | "all of them, keyed by bean name": perfect for the **strategy pattern** (`byName.get(userChoice)`) |

## Conditional beans

Create a bean only when something is true. This is exactly how Spring Boot's auto-configuration works:

```java
@Bean
@ConditionalOnProperty(name = "payments.mock", havingValue = "true")   // Spring Boot annotation
public PaymentGateway mockGateway() { return new MockGateway(); }

@Bean
@Profile("dev")                                                       // only in the dev profile
public DataSeeder dataSeeder() { return new DataSeeder(); }
```

## Lite mode vs full @Configuration

Inside a `@Configuration` class, calling another `@Bean` method returns the **same singleton**, because Spring proxies the class:

```java
@Bean public Clock clock() { return Clock.systemUTC(); }
@Bean public Scheduler scheduler() { return new Scheduler(clock()); }   // the same Clock bean, not a new one
```

In a plain `@Component` class, `@Bean` methods are not proxied ("lite mode"), and that call would create a new object. Prefer method parameters for dependencies to avoid the question entirely.

::tip Keep configuration classes small and grouped by topic (`SecurityConfig`, `WebConfig`, `PaymentConfig`). In Spring Boot you'll need far fewer of them, because auto-configuration creates most infrastructure beans for you.

## Exercise

1. Run the example, then remove `@Primary`. What happens at startup?
2. Add a `@Bean` method that creates a `java.util.Random` with a fixed seed and inject it into a `DiceService`.
3. Use the `Map<String, PaymentGateway>` to implement `pay(String method, int amount)`, throwing an error for unknown methods.
4. Annotate the gateways with `@Order(1)`, `@Order(2)`, `@Order(3)` and check the List order.

::hint Hint for 1 | Injecting a single `PaymentGateway` fails with `NoUniqueBeanDefinitionException`, because there are three candidates and no default.

## Interview corner

- **@Component vs @Bean?** @Component goes on your own class (found by scanning); @Bean is a method in a @Configuration class, used for third-party classes or custom creation.
- **@Primary vs @Qualifier?** @Primary sets a default; @Qualifier selects a specific bean at the injection point (and wins over @Primary).
- **How do you inject all implementations?** Inject `List<Interface>` or `Map<String, Interface>`.
- **Why are @Configuration classes proxied?** So inter-bean method calls return container singletons.

## Summary

- `@Configuration` + `@Bean` create beans from any class.
- Resolve ambiguity with `@Primary` or `@Qualifier`; inject `List`/`Map` for all implementations.
- Conditional beans (`@Profile`, `@ConditionalOn...`) power Spring Boot's auto-configuration.

Next: bean scopes and the bean lifecycle.
