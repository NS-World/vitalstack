---
title: Spring Boot Cheat Sheet
slug: spring-boot-cheat-sheet
parent: cheat-sheets
template: page-cheat-sheet.php
icon: 🍃
order: 70
excerpt: Spring Boot on one page - annotations, REST controllers, Spring Data JPA repositories, validation, error handling, configuration, security, testing and Actuator. Printable.
---
## Core annotations

| Annotation | Purpose |
|---|---|
| `@SpringBootApplication` | main class: config + auto-config + component scan |
| `@Component` / `@Service` / `@Repository` | beans found by scanning |
| `@Configuration` + `@Bean` | define beans in code |
| `@RestController` | controller returning JSON |
| `@Transactional` | wrap a method in a transaction |
| `@Value("${x}")` / `@ConfigurationProperties` | read configuration |
| `@Profile("dev")` | bean only in a profile |
| `@Primary` / `@Qualifier("name")` | choose between beans |

Prefer **constructor injection** (no `@Autowired` needed with one constructor).

## REST controller

```java
@RestController
@RequestMapping("/api/courses")
public class CourseController {
    private final CourseService service;
    public CourseController(CourseService service) { this.service = service; }

    @GetMapping
    public List<CourseResponse> list(@RequestParam(required = false) String q) { return service.search(q); }

    @GetMapping("/{id}")
    public CourseResponse get(@PathVariable long id) { return service.get(id); }

    @PostMapping
    public ResponseEntity<CourseResponse> create(@Valid @RequestBody CourseRequest req) {
        CourseResponse c = service.create(req);
        return ResponseEntity.created(URI.create("/api/courses/" + c.id())).body(c);
    }

    @DeleteMapping("/{id}")
    @ResponseStatus(HttpStatus.NO_CONTENT)
    public void delete(@PathVariable long id) { service.delete(id); }
}
```

| Status | When |
|---|---|
| 200 | OK |
| 201 | created (+ Location) |
| 204 | deleted / no body |
| 400 | invalid input |
| 401 / 403 | not logged in / not allowed |
| 404 | not found |
| 409 | conflict / duplicate |

## Entity and repository

```java
@Entity
public class Course {
    @Id @GeneratedValue(strategy = GenerationType.IDENTITY) private Long id;
    @Column(nullable = false, length = 100) private String title;
    @Enumerated(EnumType.STRING) private Level level;
    @ManyToOne(fetch = FetchType.LAZY) private Teacher teacher;
    @OneToMany(mappedBy = "course", cascade = CascadeType.ALL, orphanRemoval = true) private List<Lesson> lessons = new ArrayList<>();
    @Version private int version;
    protected Course() { }
}

public interface CourseRepository extends JpaRepository<Course, Long> {
    List<Course> findByLevelOrderByTitle(Level level);
    boolean existsByTitleIgnoreCase(String title);
    Page<Course> findByTitleContainingIgnoreCase(String q, Pageable pageable);
    @EntityGraph(attributePaths = "lessons") Optional<Course> findWithLessonsById(Long id);
    @Query("select avg(c.price) from Course c where c.level = :level") Double avgPrice(Level level);
    @Modifying @Query("update Course c set c.price = c.price * 0.9") int discountAll();
}
```

## DTOs and validation

```java
public record CourseRequest(@NotBlank @Size(max = 100) String title, @NotNull Level level, @Min(0) int price) { }
public record CourseResponse(Long id, String title, Level level, int price) { }
```

`@NotNull`, `@NotBlank`, `@Size`, `@Min`, `@Max`, `@Email`, `@Pattern`, `@Past`, `@Positive`, `@Valid` (nested).

## Errors

```java
@RestControllerAdvice
public class ApiErrors {
    @ExceptionHandler(NotFoundException.class)
    public ProblemDetail notFound(NotFoundException e) {
        return ProblemDetail.forStatusAndDetail(HttpStatus.NOT_FOUND, e.getMessage());
    }
    @ExceptionHandler(MethodArgumentNotValidException.class)
    public ProblemDetail invalid(MethodArgumentNotValidException e) {
        ProblemDetail p = ProblemDetail.forStatusAndDetail(HttpStatus.BAD_REQUEST, "Invalid request");
        p.setProperty("errors", e.getBindingResult().getFieldErrors().stream()
            .collect(Collectors.toMap(FieldError::getField, FieldError::getDefaultMessage, (a, b) -> a)));
        return p;
    }
}
```

## Configuration

```yaml
spring:
  datasource:
    url: jdbc:postgresql://localhost:5432/courses
    username: courses
    password: ${DB_PASSWORD}
  jpa:
    open-in-view: false
    hibernate.ddl-auto: validate
server.port: 8080
logging.level.com.vitalstack: debug
management.endpoints.web.exposure.include: health,info,metrics
```

Run a profile: `--spring.profiles.active=prod` or `SPRING_PROFILES_ACTIVE=prod`.

## Transactions

```java
@Service @Transactional
public class EnrollmentService {
    @Transactional(readOnly = true) public List<X> list() { … }
    @Transactional(rollbackFor = Exception.class) public void importFile() { … }
    @Transactional(propagation = Propagation.REQUIRES_NEW) public void audit() { … }
}
```

Rolls back on unchecked exceptions by default; doesn't work on private methods or self-calls.

## Security (JWT resource server)

```java
@Bean
SecurityFilterChain api(HttpSecurity http) throws Exception {
    return http.csrf(c -> c.disable())
        .sessionManagement(s -> s.sessionCreationPolicy(SessionCreationPolicy.STATELESS))
        .authorizeHttpRequests(a -> a
            .requestMatchers(HttpMethod.GET, "/api/courses/**").permitAll()
            .requestMatchers("/api/admin/**").hasRole("ADMIN")
            .anyRequest().authenticated())
        .oauth2ResourceServer(o -> o.jwt(Customizer.withDefaults()))
        .build();
}
@Bean PasswordEncoder encoder() { return new BCryptPasswordEncoder(); }
```

## Testing

```java
@WebMvcTest(CourseController.class)          // web layer + MockMvc
@DataJpaTest                                 // JPA + embedded DB, rollback
@SpringBootTest(webEnvironment = RANDOM_PORT) // everything
mvc.perform(get("/api/courses/1")).andExpect(status().isOk()).andExpect(jsonPath("$.title").value("Java"));
```

## Commands

```bash
./mvnw spring-boot:run
./mvnw clean package && java -jar target/app.jar
./mvnw test
./mvnw spring-boot:build-image
```
