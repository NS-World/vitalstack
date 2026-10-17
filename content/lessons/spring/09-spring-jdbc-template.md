---
title: Spring JdbcTemplate and Testing Spring Apps
slug: spring-jdbc-template
subject: spring
lesson: 9
difficulty: intermediate
excerpt: Run SQL without JDBC boilerplate using JdbcTemplate and NamedParameterJdbcTemplate, map rows to records, insert with generated keys, and write fast unit tests and Spring integration tests.
---

JPA is great for rich domain models, but sometimes you just want to run SQL: reports, bulk updates, legacy schemas. **JdbcTemplate** gives you plain SQL without the JDBC boilerplate (connections, statements, result sets, exceptions). It's the Template Method pattern in action: Spring does the fixed steps, you supply the SQL and the mapping.

## Plain JDBC vs JdbcTemplate

```java
// Plain JDBC: ~15 lines, easy to leak connections
try (Connection c = ds.getConnection();
     PreparedStatement ps = c.prepareStatement("SELECT id, title, price FROM course WHERE price < ?")) {
    ps.setInt(1, 600);
    try (ResultSet rs = ps.executeQuery()) {
        while (rs.next()) list.add(new Course(rs.getLong("id"), rs.getString("title"), rs.getInt("price")));
    }
} catch (SQLException e) { throw new RuntimeException(e); }

// JdbcTemplate: one statement
List<Course> list = jdbc.query("SELECT id, title, price FROM course WHERE price < ?",
        (rs, n) -> new Course(rs.getLong("id"), rs.getString("title"), rs.getInt("price")), 600);
```

JdbcTemplate also translates `SQLException` into Spring's unchecked `DataAccessException` hierarchy (`DuplicateKeyException`, `EmptyResultDataAccessException`…), and joins Spring transactions automatically.

## A complete repository

```java
package com.vitalstack;

public record Course(long id, String title, int price) { }
```

```java
package com.vitalstack;

import org.springframework.jdbc.core.JdbcTemplate;
import org.springframework.jdbc.core.RowMapper;
import org.springframework.jdbc.core.namedparam.MapSqlParameterSource;
import org.springframework.jdbc.core.namedparam.NamedParameterJdbcTemplate;
import org.springframework.jdbc.support.GeneratedKeyHolder;
import org.springframework.stereotype.Repository;
import java.util.List;
import java.util.Optional;

@Repository
public class CourseDao {
    private static final RowMapper<Course> MAPPER =
            (rs, rowNum) -> new Course(rs.getLong("id"), rs.getString("title"), rs.getInt("price"));

    private final JdbcTemplate jdbc;
    private final NamedParameterJdbcTemplate named;

    public CourseDao(JdbcTemplate jdbc, NamedParameterJdbcTemplate named) {   // auto-configured by Spring Boot
        this.jdbc = jdbc;
        this.named = named;
    }

    public long insert(String title, int price) {
        var keys = new GeneratedKeyHolder();
        named.update("INSERT INTO course(title, price) VALUES (:title, :price)",
                new MapSqlParameterSource().addValue("title", title).addValue("price", price),
                keys, new String[] { "id" });
        return keys.getKey().longValue();
    }

    public Optional<Course> findById(long id) {
        return jdbc.query("SELECT * FROM course WHERE id = ?", MAPPER, id).stream().findFirst();
    }

    public List<Course> cheaperThan(int max) {
        return named.query("SELECT * FROM course WHERE price < :max ORDER BY price",
                new MapSqlParameterSource("max", max), MAPPER);
    }

    public int count() {
        return jdbc.queryForObject("SELECT COUNT(*) FROM course", Integer.class);
    }

    public int raisePrices(int percent) {
        return jdbc.update("UPDATE course SET price = price + price * ? / 100", percent);
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
    CommandLineRunner demo(CourseDao dao) {
        return args -> {
            long id = dao.insert("Java", 499);
            dao.insert("Spring", 799);
            dao.insert("SQL", 299);
            System.out.println("new id: " + id + ", total: " + dao.count());
            System.out.println("cheap: " + dao.cheaperThan(600));
            System.out.println("rows updated: " + dao.raisePrices(10));
            System.out.println("find 1: " + dao.findById(1).orElseThrow());
            System.out.println("find 42: " + dao.findById(42));
        };
    }
}
```

`src/main/resources/schema.sql` (Spring Boot runs it at startup for embedded databases):

```sql
CREATE TABLE course (
  id    BIGINT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  price INT NOT NULL
);
```

```ini
# application.properties
spring.main.banner-mode=off
logging.level.root=warn
spring.datasource.url=jdbc:h2:mem:jdbcdemo
```

Output:

```text
new id: 1, total: 3
cheap: [Course[id=3, title=SQL, price=299], Course[id=1, title=Java, price=499]]
rows updated: 3
find 1: Course[id=1, title=Java, price=548]
find 42: Optional.empty
```

## JdbcTemplate methods

| Method | Use |
|---|---|
| `query(sql, rowMapper, args...)` | many rows → `List<T>` |
| `queryForObject(sql, Class, args...)` | one value (throws if zero rows) |
| `update(sql, args...)` | INSERT/UPDATE/DELETE → affected rows |
| `batchUpdate(sql, List<Object[]>)` | many statements in one batch |
| `NamedParameterJdbcTemplate` | `:name` parameters instead of `?` |

Spring 6.1 also added **`JdbcClient`**, a fluent API: `jdbcClient.sql("SELECT * FROM course WHERE id = :id").param("id", 1).query(Course.class).optional()`.

::tip Use `query(...).stream().findFirst()` (or JdbcClient's `.optional()`) for "maybe one row". `queryForObject` throws `EmptyResultDataAccessException` when nothing matches, which surprises a lot of people.

## When to use what

| Tool | Best for |
|---|---|
| Spring Data JPA | CRUD on a domain model, relationships, most business apps |
| JdbcTemplate / JdbcClient | reports, complex SQL, bulk operations, simple apps, legacy schemas |
| Both together | common: JPA for the domain, JdbcTemplate for heavy reads |

## Testing Spring code

**Unit tests** need no Spring: construct the class with fakes (constructor injection pays off):

```java
class PriceServiceTest {
    @Test
    void appliesGst() {
        PriceService service = new PriceService(new BigDecimal("0.18"));
        assertEquals(new BigDecimal("118.00"), service.withGst(new BigDecimal("100.00")));
    }
}
```

**Integration tests** start (part of) the Spring context:

```java
@JdbcTest                                    // only JDBC beans + an embedded database
@Import(CourseDao.class)
class CourseDaoTest {
    @Autowired CourseDao dao;

    @Test
    void insertsAndFinds() {
        long id = dao.insert("Docker", 399);
        assertEquals("Docker", dao.findById(id).orElseThrow().title());
    }
}
```

Test **slices** (`@JdbcTest`, `@DataJpaTest`, `@WebMvcTest`) start only what's needed, so they're fast. `@SpringBootTest` starts everything. Each test runs in a transaction that's rolled back afterwards, so tests don't affect each other.

## Exercise

1. Add `deleteById(long id)` that returns true if a row was deleted.
2. Write `List<CategoryTotal> totalsByCategory()` with a `GROUP BY` query mapped to a record.
3. Insert 1,000 courses with `batchUpdate` and time it against a loop of `update`.
4. Write a `@JdbcTest` for `cheaperThan`.

::hint Hint for 1 | `return jdbc.update("DELETE FROM course WHERE id = ?", id) == 1;`

## Interview corner

- **What is JdbcTemplate?** A Spring helper that handles JDBC resources, exceptions and transactions so you only write SQL and row mapping.
- **RowMapper?** A function mapping one ResultSet row to an object.
- **JdbcTemplate vs JPA?** SQL-centric and explicit vs object-centric with automatic mapping, caching and dirty checking.
- **@SpringBootTest vs test slices?** Full context vs only the beans for one layer, which is faster.

## Summary

- JdbcTemplate removes JDBC boilerplate; NamedParameterJdbcTemplate and JdbcClient add named parameters and a fluent API.
- RowMappers turn rows into records; exceptions become DataAccessExceptions.
- Unit-test with plain constructors; integration-test with slices like `@JdbcTest`.

**You've finished the Spring course!** Next: **Spring Boot**, where you'll build a production-ready REST API with all of this auto-configured.
