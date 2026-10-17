## spring-introduction
Q: What does IoC (Inversion of Control) mean in Spring?
- [x] The container, not your code, creates and wires objects
- [ ] Classes create their own dependencies
- [ ] Code runs in reverse
> You declare what you need; Spring supplies it.

Q: Spring Boot compared with Spring Framework…
- [x] Is Spring plus auto-configuration, starters and an embedded server
- [ ] Is a different framework
- [ ] Replaces dependency injection
> Same core concepts, less setup.

Q: What is a bean?
- [x] An object created and managed by the Spring container
- [ ] A Java primitive
- [ ] A JSON file
> Beans are singletons by default.

## spring-ioc-and-dependency-injection
Q: Which injection style is recommended?
- [x] Constructor injection
- [ ] Field injection with @Autowired
- [ ] Static injection
> It allows final fields and easy testing.

Q: What error appears when two beans implement the required interface?
- [ ] NoSuchBeanDefinitionException
- [x] NoUniqueBeanDefinitionException
- [ ] NullPointerException
> Use @Primary or @Qualifier.

Q: What extra does @Repository add?
- [x] Translation of database exceptions to DataAccessException
- [ ] Caching
- [ ] REST endpoints
> Otherwise it's a @Component.

## spring-configuration-and-beans
Q: When do you use @Bean instead of @Component?
- [x] For classes you can't annotate, like library classes, or custom creation
- [ ] Never
- [ ] For controllers only
> @Bean methods live in @Configuration classes.

Q: How do you inject every implementation of an interface?
- [x] Inject a List or Map of that interface
- [ ] Use @Primary
- [ ] It's impossible
> Map keys are bean names.

Q: @Qualifier("card") at an injection point…
- [x] Selects that specific bean, even over @Primary
- [ ] Creates a new bean
- [ ] Is ignored
> Qualifiers are explicit choices.

## spring-bean-scopes-and-lifecycle
Q: Default bean scope?
- [x] singleton
- [ ] prototype
- [ ] request
> One instance per container.

Q: Why must singletons be stateless?
- [x] They're shared by all users and threads
- [ ] They can't have fields
- [ ] Spring forbids state
> Per-user data in fields leaks between users.

Q: How do you get a fresh prototype inside a singleton?
- [x] ObjectProvider.getObject()
- [ ] Constructor injection
- [ ] @Autowired field
> Constructor injection gives one instance forever.

## spring-properties-and-profiles
Q: Which beats application.properties?
- [x] Environment variables
- [ ] Defaults in @Value
- [ ] Nothing
> Command line > env vars > profile files > application.properties.

Q: How do you activate the prod profile?
- [x] --spring.profiles.active=prod or SPRING_PROFILES_ACTIVE=prod
- [ ] Rename the jar
- [ ] @Prod annotation
> Profile files like application-prod.properties then apply.

Q: Where should database passwords live?
- [x] Environment variables or a secret manager
- [ ] In application.properties committed to Git
- [ ] In the code
> Reference them as ${DB_PASSWORD}.

## spring-aop
Q: How does Spring apply aspects?
- [x] By wrapping beans in proxies
- [ ] By editing bytecode at compile time only
- [ ] By copying code
> Calls through the proxy get the advice.

Q: Why doesn't advice run when a method calls another method of the same class?
- [x] Self-invocation bypasses the proxy
- [ ] Aspects only work on interfaces
- [ ] It's a Java bug
> Move the method to another bean.

Q: Which advice wraps the whole call and must call proceed()?
- [x] @Around
- [ ] @Before
- [ ] @AfterReturning
> Used for timing, retries and transactions.

## spring-transactions
Q: By default @Transactional rolls back on…
- [x] Unchecked exceptions (RuntimeException, Error)
- [ ] Every exception
- [ ] Checked exceptions only
> Use rollbackFor = Exception.class for checked ones.

Q: REQUIRES_NEW means…
- [x] Suspend the current transaction and start an independent one
- [ ] Join the current transaction
- [ ] No transaction
> Useful for audit logs that must survive failures.

Q: @Transactional on a private method…
- [x] Has no effect
- [ ] Works normally
- [ ] Throws an error
> Proxies only intercept public calls from outside.

## spring-mvc
Q: What is DispatcherServlet?
- [x] Spring MVC's front controller that routes every request
- [ ] A database driver
- [ ] A filter
> HandlerMapping finds the controller method.

Q: @RestController equals…
- [x] @Controller + @ResponseBody
- [ ] @Service + @Controller
- [ ] @Component only
> Return values are written as JSON.

Q: /courses/{id} is read with…
- [x] @PathVariable
- [ ] @RequestParam
- [ ] @RequestBody
> @RequestParam reads ?query=values.

## spring-jdbc-template
Q: What does a RowMapper do?
- [x] Maps one ResultSet row to an object
- [ ] Creates tables
- [ ] Opens connections
> JdbcTemplate handles the rest.

Q: queryForObject with no matching row…
- [x] Throws EmptyResultDataAccessException
- [ ] Returns null
- [ ] Returns 0
> Use query(...).stream().findFirst() for optional results.

Q: Which test slice starts only JDBC beans and an embedded DB?
- [x] @JdbcTest
- [ ] @SpringBootTest
- [ ] @WebMvcTest
> Slices are faster than full contexts.
