---
title: Spring MVC - Controllers, Requests and Responses
slug: spring-mvc
subject: spring
lesson: 8
difficulty: intermediate
excerpt: Follow a request through DispatcherServlet in an animation, then build controllers with @GetMapping, @PostMapping, @PathVariable, @RequestParam and @RequestBody, return JSON or views, and handle errors with @RestControllerAdvice.
---

Spring MVC is the web layer of Spring. Instead of one servlet per URL (Servlet course), Spring has **one** servlet, the `DispatcherServlet`, that routes every request to the right method in your `@Controller` classes.

## The life of a request

Pick a request for an existing course (`/1`) and a missing one (`/99`):

::viz spring-mvc-flow

1. **DispatcherServlet** receives every request (front controller pattern).
2. **HandlerMapping** finds the controller method whose mapping matches the URL and HTTP method.
3. **Argument resolvers** convert path variables, query parameters and JSON bodies into Java parameters.
4. Your **controller** calls services and returns a value.
5. **Message converters** (Jackson) turn the returned object into JSON, or a **ViewResolver** renders an HTML template.
6. Exceptions go to **@ExceptionHandler** / **@RestControllerAdvice** methods.

## A REST controller

```java
package com.vitalstack;

public record Course(Long id, String title, int price) { }
```

```java
package com.vitalstack;

import jakarta.validation.constraints.*;

public record NewCourse(@NotBlank String title, @Min(0) int price) { }
```

```java
package com.vitalstack;

import jakarta.validation.Valid;
import org.springframework.http.*;
import org.springframework.web.bind.annotation.*;
import java.util.*;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.atomic.AtomicLong;

@RestController                                   // every method returns data (JSON), not views
@RequestMapping("/api/courses")
public class CourseController {
    private final Map<Long, Course> store = new ConcurrentHashMap<>();
    private final AtomicLong ids = new AtomicLong();

    public CourseController() {
        add("Java", 499);
        add("Spring", 799);
    }

    private Course add(String title, int price) {
        long id = ids.incrementAndGet();
        Course c = new Course(id, title, price);
        store.put(id, c);
        return c;
    }

    @GetMapping                                               // GET /api/courses?maxPrice=600
    public List<Course> list(@RequestParam(required = false) Integer maxPrice) {
        return store.values().stream()
                .filter(c -> maxPrice == null || c.price() <= maxPrice)
                .sorted(Comparator.comparing(Course::id))
                .toList();
    }

    @GetMapping("/{id}")                                      // GET /api/courses/1
    public Course get(@PathVariable long id) {
        Course c = store.get(id);
        if (c == null) throw new NoSuchElementException("Course " + id + " not found");
        return c;
    }

    @PostMapping                                              // POST /api/courses  {"title":"React","price":599}
    public ResponseEntity<Course> create(@Valid @RequestBody NewCourse body) {
        Course c = add(body.title(), body.price());
        return ResponseEntity.status(HttpStatus.CREATED).body(c);   // 201 Created
    }

    @DeleteMapping("/{id}")
    @ResponseStatus(HttpStatus.NO_CONTENT)                    // 204
    public void delete(@PathVariable long id) { store.remove(id); }
}
```

## Central error handling

```java
package com.vitalstack;

import org.springframework.http.*;
import org.springframework.web.bind.MethodArgumentNotValidException;
import org.springframework.web.bind.annotation.*;
import java.util.*;

@RestControllerAdvice                              // applies to all controllers
public class ApiErrors {

    @ExceptionHandler(NoSuchElementException.class)
    @ResponseStatus(HttpStatus.NOT_FOUND)
    public Map<String, String> notFound(NoSuchElementException e) {
        return Map.of("error", e.getMessage());
    }

    @ExceptionHandler(MethodArgumentNotValidException.class)
    @ResponseStatus(HttpStatus.BAD_REQUEST)
    public Map<String, String> invalid(MethodArgumentNotValidException e) {
        Map<String, String> errors = new TreeMap<>();
        e.getBindingResult().getFieldErrors().forEach(f -> errors.put(f.getField(), f.getDefaultMessage()));
        return errors;
    }
}
```

## Trying it

```java
package com.vitalstack;

import org.springframework.boot.*;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.web.context.WebServerInitializedEvent;
import org.springframework.context.event.EventListener;
import org.springframework.http.MediaType;
import org.springframework.web.client.RestClient;
import org.springframework.web.client.RestClientResponseException;

@SpringBootApplication
public class Main {
    public static void main(String[] args) {
        SpringApplication.run(Main.class, "--server.port=0", "--spring.main.banner-mode=off",
                "--logging.level.root=warn").close();
    }

    @EventListener
    void call(WebServerInitializedEvent e) {
        RestClient http = RestClient.create("http://localhost:" + e.getWebServer().getPort());
        System.out.println(http.get().uri("/api/courses").retrieve().body(String.class));
        System.out.println(http.get().uri("/api/courses?maxPrice=600").retrieve().body(String.class));
        System.out.println(http.post().uri("/api/courses").contentType(MediaType.APPLICATION_JSON)
                .body("{\"title\":\"React\",\"price\":599}").retrieve().toEntity(String.class));
        for (String req : new String[] { "/api/courses/99" }) {
            try { http.get().uri(req).retrieve().body(String.class); }
            catch (RestClientResponseException ex) { System.out.println(ex.getStatusCode() + " " + ex.getResponseBodyAsString()); }
        }
        try {
            http.post().uri("/api/courses").contentType(MediaType.APPLICATION_JSON)
                .body("{\"title\":\"\",\"price\":-5}").retrieve().body(String.class);
        } catch (RestClientResponseException ex) { System.out.println(ex.getStatusCode() + " " + ex.getResponseBodyAsString()); }
    }
}
```

Output:

```text
[{"id":1,"title":"Java","price":499},{"id":2,"title":"Spring","price":799}]
[{"id":1,"title":"Java","price":499}]
<201 CREATED Created,{"id":3,"title":"React","price":599},[...headers...]>
404 NOT_FOUND {"error":"Course 99 not found"}
400 BAD_REQUEST {"price":"must be greater than or equal to 0","title":"must not be blank"}
```

(In your own app, test endpoints with a browser, curl, Postman or IntelliJ's HTTP client instead.)

## Request mapping cheat sheet

| Annotation | Reads |
|---|---|
| `@PathVariable long id` | `/courses/{id}` |
| `@RequestParam String q` | `?q=java` (`required = false`, `defaultValue = "1"`) |
| `@RequestBody NewCourse body` | JSON body → object (Jackson) |
| `@RequestHeader("X-Api-Key") String key` | a header |
| `@CookieValue("theme") String t` | a cookie |
| `@Valid` | run Bean Validation on the argument |

| Return | Response |
|---|---|
| an object / record / List | JSON with 200 |
| `ResponseEntity<T>` | full control: status, headers, body |
| `void` + `@ResponseStatus(NO_CONTENT)` | 204 |

## @Controller with HTML views

For server-rendered pages (often with **Thymeleaf**), use `@Controller` and return a view name:

```java
@Controller
public class PageController {
    @GetMapping("/courses")
    public String courses(Model model) {
        model.addAttribute("courses", courseService.findAll());
        return "courses";                          // renders templates/courses.html
    }
}
```

```html
<li th:each="c : ${courses}" th:text="${c.title}">Course title</li>
```

Same MVC pattern as servlets + JSP, with far less code.

::tip Controllers should be thin: read the request, call a service, return a response. Business rules belong in `@Service` classes, where they're easy to test and reuse.

## Exercise

1. Add `PUT /api/courses/{id}` that replaces a course and returns 404 if it doesn't exist.
2. Add `GET /api/courses/search?q=ja` (case-insensitive title search).
3. Add a validation rule: title at most 60 characters (`@Size(max = 60)`), and check the 400 response.
4. Return a `Location` header for created courses using `ResponseEntity.created(URI)`.

::hint Hint for 4 | `URI uri = URI.create("/api/courses/" + c.id()); return ResponseEntity.created(uri).body(c);`

## Interview corner

- **What is DispatcherServlet?** Spring MVC's front controller that receives all requests and delegates to handlers, converters and view resolvers.
- **@Controller vs @RestController?** @RestController = @Controller + @ResponseBody: return values are written to the response body (JSON).
- **@PathVariable vs @RequestParam?** Path segment vs query string parameter.
- **How do you handle exceptions globally?** `@RestControllerAdvice` with `@ExceptionHandler` methods.

## Summary

- DispatcherServlet routes requests to controller methods by URL and HTTP method.
- `@RestController` + `@GetMapping`/`@PostMapping` + `@PathVariable`/`@RequestParam`/`@RequestBody`.
- Return records or `ResponseEntity`; validate with `@Valid`; handle errors centrally.

Next: Spring JDBC with JdbcTemplate.
