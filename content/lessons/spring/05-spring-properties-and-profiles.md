---
title: Spring Properties, @Value, @ConfigurationProperties and Profiles
slug: spring-properties-and-profiles
subject: spring
lesson: 5
difficulty: intermediate
excerpt: Keep settings out of code. Read values with @Value and Environment, bind typed config with @ConfigurationProperties records, switch environments with profiles, and keep secrets out of Git.
---

Database URLs, API keys, feature flags and limits change between your laptop, testing and production. Hard-coding them means rebuilding the app for every environment. Spring reads them from **properties**, so the same build runs everywhere.

## application.properties

```ini
# application.properties
app.name=VitalStack
app.max-cart-items=20
app.support-email=help@example.com
app.payment.provider=razorpay
app.payment.timeout=5s
app.payment.retries=2
```

Or the same in YAML (`application.yml`):

```yaml
app:
  name: VitalStack
  max-cart-items: 20
  payment:
    provider: razorpay
    timeout: 5s
    retries: 2
```

## @Value: one value at a time

```java
@Service
public class CartService {
    private final int maxItems;

    public CartService(@Value("${app.max-cart-items:10}") int maxItems) {   // ":10" = default
        this.maxItems = maxItems;
    }
}
```

Fine for one or two values. For groups of settings, use typed properties.

## @ConfigurationProperties: typed, validated groups

```java
package com.vitalstack;

import org.springframework.boot.context.properties.ConfigurationProperties;
import java.time.Duration;

@ConfigurationProperties(prefix = "app.payment")
public record PaymentProperties(String provider, Duration timeout, int retries) { }
```

```java
package com.vitalstack;

import org.springframework.boot.CommandLineRunner;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.context.properties.EnableConfigurationProperties;
import org.springframework.context.annotation.Bean;
import org.springframework.core.env.Environment;

@SpringBootApplication
@EnableConfigurationProperties(PaymentProperties.class)
public class Main {
    public static void main(String[] args) {
        SpringApplication app = new SpringApplication(Main.class);
        app.setWebApplicationType(org.springframework.boot.WebApplicationType.NONE);
        app.run(args).close();
    }

    @Bean
    CommandLineRunner show(PaymentProperties pay, Environment env) {
        return args -> {
            System.out.println("provider=" + pay.provider() + ", timeout=" + pay.timeout().toMillis()
                    + "ms, retries=" + pay.retries());
            System.out.println("app.name=" + env.getProperty("app.name"));
            System.out.println("profiles=" + String.join(",", env.getActiveProfiles()));
        };
    }
}
```

```ini
# application.properties
spring.main.banner-mode=off
logging.level.root=warn
app.name=VitalStack
app.payment.provider=razorpay
app.payment.timeout=5s
app.payment.retries=2
```

Output:

```text
provider=razorpay, timeout=5000ms, retries=2
app.name=VitalStack
profiles=
```

Benefits over `@Value`: type conversion (`5s` → `Duration`), one place for related settings, IDE autocompletion, and validation with `@Validated` + `@NotBlank`, `@Min`.

## Profiles: different settings per environment

Create files per profile:

```text
application.properties          ← shared defaults
application-dev.properties      ← used when profile "dev" is active
application-prod.properties     ← used when profile "prod" is active
```

```ini
# application-dev.properties
spring.datasource.url=jdbc:h2:mem:devdb
app.payment.provider=mock
logging.level.com.vitalstack=debug
```

Activate a profile:

```bash
java -jar app.jar --spring.profiles.active=prod
# or an environment variable
SPRING_PROFILES_ACTIVE=prod java -jar app.jar
```

Profile-specific values **override** the defaults. Beans can be profile-specific too:

```java
@Bean
@Profile("dev")
CommandLineRunner seedDemoData(CourseRepository repo) { ... }
```

## Where values can come from (highest priority first)

1. Command-line arguments (`--app.payment.retries=5`)
2. Environment variables (`APP_PAYMENT_RETRIES=5`: dots become underscores, upper case)
3. Profile-specific files (`application-prod.properties`)
4. `application.properties` / `application.yml`
5. Defaults in code (`@Value("${x:default}")`)

So the same jar can be configured by your hosting platform or Docker through environment variables, without editing files.

::tip **Never commit secrets** (database passwords, API keys, JWT secrets) to Git. Reference them as `${DB_PASSWORD}` in properties and supply them through environment variables or a secrets manager. If a secret ever lands in Git, rotate it: deleting the commit isn't enough.

```ini
spring.datasource.password=${DB_PASSWORD}
app.jwt.secret=${JWT_SECRET}
```

## Exercise

1. Create `@ConfigurationProperties(prefix = "app.mail")` with `from`, `host` and `port`, and print them at startup.
2. Add `application-dev.properties` and `application-prod.properties` with different `app.name` values and switch between them with `--spring.profiles.active`.
3. Override a property with an environment variable and confirm it wins over the file.
4. Add `@Validated` and `@Min(1)` to `retries`, set it to 0, and read the startup error.

::hint Hint for 3 | `APP_NAME=FromEnv java -jar app.jar` overrides `app.name` thanks to relaxed binding.

## Interview corner

- **@Value vs @ConfigurationProperties?** @Value injects single values; @ConfigurationProperties binds a typed, validated group with relaxed binding.
- **What are profiles?** Named sets of configuration and beans activated per environment (dev, test, prod).
- **Property precedence?** Command line > environment variables > profile files > application.properties > code defaults (simplified).
- **How do you handle secrets?** Environment variables or a vault/secret manager, never in source control.

## Summary

- Externalise settings in `application.properties`/`.yml`.
- `@Value` for single values; `@ConfigurationProperties` records for typed groups.
- Profiles switch config and beans per environment; environment variables override files.
- Keep secrets out of Git.

Next: AOP, adding behaviour around methods.
