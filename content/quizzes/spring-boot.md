## spring-boot-introduction
Q: @SpringBootApplication combines…
- [x] @SpringBootConfiguration, @EnableAutoConfiguration and @ComponentScan
- [ ] @Service and @Repository
- [ ] @RestController and @Bean
> Keep it in the root package so scanning finds everything.

Q: What is a starter?
- [x] A dependency that brings a tested set of libraries for a feature
- [ ] A main method
- [ ] A Maven plugin
> spring-boot-starter-web brings MVC, Jackson and Tomcat.

Q: How do you see why auto-configurations matched?
- [x] Run with --debug to print the conditions report
- [ ] Read the jar
- [ ] It's not possible
> The report lists positive and negative matches.

## spring-boot-first-rest-api
Q: Which status should a successful create return?
- [x] 201 Created with a Location header
- [ ] 200 OK
- [ ] 204 No Content
> Location points to the new resource.

Q: What does ResponseEntity.of(optional) return when empty?
- [x] 404
- [ ] 500
- [ ] 200 with null
> 200 with the body when present.

Q: PUT vs PATCH?
- [x] PUT replaces the resource; PATCH changes part of it
- [ ] They're identical
- [ ] PATCH deletes
> Choose verbs, not URLs, for actions.

## spring-boot-data-jpa
Q: What does `findByTitleContainingIgnoreCase` do?
- [x] Spring derives a case-insensitive LIKE query from the name
- [ ] Nothing until implemented
- [ ] Loads all rows
> Derived queries are checked at startup.

Q: Which annotation is needed for update/delete @Query methods?
- [x] @Modifying (plus a transaction)
- [ ] @Transient
- [ ] @Lob
> Otherwise Spring expects a SELECT.

Q: How do you paginate repository results?
- [x] Pass a Pageable and return Page
- [ ] Use subList
- [ ] Not possible
> PageRequest.of(page, size, sort).

## spring-boot-dto-validation-errors
Q: Why return DTOs instead of entities?
- [x] Stable contract, no over-exposure, no lazy-loading or JSON loops
- [ ] Entities can't be serialised
- [ ] DTOs are faster to write
> Records make great DTOs.

Q: What does @Valid on a @RequestBody do?
- [x] Runs Bean Validation before the method; failures give 400
- [ ] Encrypts the body
- [ ] Nothing
> MethodArgumentNotValidException is raised on failure.

Q: Which status fits "a course with this title already exists"?
- [ ] 400
- [ ] 404
- [x] 409 Conflict
> Conflict with the current state.

## spring-boot-configuration-and-logging
Q: Which logging style is correct?
- [x] `log.info("Enrolled {}", id)`
- [ ] `log.info("Enrolled " + id)` everywhere
- [ ] System.out.println in production
> Placeholders avoid building strings when disabled.

Q: What does @ConfigurationProperties give over @Value?
- [x] Typed, validated groups of settings
- [ ] Encryption
- [ ] Faster startup
> Records with @Validated fail fast on bad config.

Q: Which should never be logged?
- [x] Passwords, OTPs and tokens
- [ ] Request ids
- [ ] Order ids
> Logs travel and are kept for a long time.

## spring-boot-security-jwt
Q: 401 vs 403?
- [x] 401: not authenticated; 403: authenticated but not allowed
- [ ] They're the same
- [ ] 403 means not found
> A student deleting a course gets 403.

Q: Is a JWT payload encrypted?
- [ ] Yes
- [x] No, it's signed but readable
- [ ] Only in Spring
> Never put secrets in it.

Q: How should passwords be stored?
- [x] Hashed with BCrypt or Argon2
- [ ] Encrypted with Base64
- [ ] Plain text
> Base64 is encoding, not security.

## spring-boot-testing
Q: Which test starts only the web layer with MockMvc?
- [x] @WebMvcTest
- [ ] @DataJpaTest
- [ ] @SpringBootTest
> Services are replaced with mocks.

Q: @DataJpaTest tests…
- [x] Repositories with an embedded database, rolling back after each test
- [ ] Controllers
- [ ] The whole app
> Fast and isolated.

Q: Why Testcontainers?
- [x] To test against the same database engine as production
- [ ] To speed up unit tests
- [ ] To mock HTTP
> H2 hides dialect differences.

## spring-boot-relationships-and-pagination
Q: Why use @EntityGraph on a detail query?
- [x] To load the course and its lessons in one query
- [ ] To cache results
- [ ] To sort lessons
> It prevents N+1 and lazy errors.

Q: Why return your own PageResponse instead of Spring's Page?
- [x] A stable, controlled JSON contract
- [ ] Page can't be serialised
- [ ] It's faster
> Page's JSON shape isn't a guaranteed API.

Q: Keyset pagination is better for…
- [x] Deep pages over large tables
- [ ] Small lists
- [ ] Random access to page 1
> Large OFFSETs get slow.

## spring-boot-actuator-and-deployment
Q: What does /actuator/health report?
- [x] Whether the app (and checks like the database) is UP
- [ ] All environment variables
- [ ] The source code
> Load balancers use it.

Q: Why not ddl-auto=update in production?
- [x] Schema changes need versioned, reviewed migrations
- [ ] It's slower
- [ ] It deletes data every start
> Use Flyway or Liquibase and validate.

Q: Can regular shared PHP hosting run a Spring Boot jar?
- [ ] Yes
- [x] No, you need a VPS or a platform that runs Java/containers
- [ ] Only with WordPress
> Render, Railway, Cloud Run or a VPS work.

## spring-boot-project-enrollment-api
Q: Where is "max 3 active courses" enforced?
- [x] In the service layer, returning 409 via a business exception
- [ ] In the controller
- [ ] In the browser
> Rules belong in services and entities.

Q: Why @Version on Enrollment?
- [x] Safe concurrent progress updates (optimistic locking)
- [ ] To store the app version
- [ ] For caching
> Stale updates fail instead of overwriting.

Q: What prevents enrolling twice in the same course at the database level?
- [x] A unique constraint on (student_id, course_id)
- [ ] An index on title
- [ ] Nothing
> Database constraints back up service rules.
