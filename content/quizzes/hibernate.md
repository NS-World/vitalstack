## hibernate-introduction
Q: Hibernate's relationship to JPA is…
- [x] Hibernate implements JPA and adds extra features
- [ ] JPA implements Hibernate
- [ ] They are unrelated
> Session extends EntityManager in Hibernate 6.

Q: What does a Dialect do?
- [x] Generates database-specific SQL
- [ ] Translates languages
- [ ] Caches queries
> MySQL, PostgreSQL and Oracle differ in details.

Q: What replaces the deprecated session.save()?
- [x] persist()
- [ ] store()
- [ ] insert()
> Prefer JPA-style persist/merge/getReference.

## hibernate-lazy-loading-and-proxies
Q: What causes LazyInitializationException?
- [x] Touching a lazy association after the Session closed
- [ ] Too many queries
- [ ] An EAGER relationship
> Load what you need inside the transaction.

Q: Which is NOT a good fix?
- [ ] JOIN FETCH
- [ ] DTO projection
- [x] Switching everything to EAGER
> EAGER loads data everywhere and often causes N+1.

Q: Calling getId() on an uninitialised proxy…
- [x] Doesn't trigger a query
- [ ] Always triggers a query
- [ ] Throws an exception
> The proxy already knows its id.

## hibernate-n-plus-one-problem
Q: Loading 5 authors then their lazy books one by one runs how many queries?
- [ ] 1
- [x] 6
- [ ] 5
> 1 for authors + 1 per author.

Q: Which annotation loads lazy collections in batches?
- [x] @BatchSize
- [ ] @Cacheable
- [ ] @Formula
> Uses IN (...) for many parents at once.

Q: How can tests catch N+1 problems?
- [x] Assert statement counts with Hibernate statistics
- [ ] They can't
- [ ] By using H2
> getPrepareStatementCount() measures queries.

## hibernate-caching
Q: Which cache is always on?
- [x] The first-level (Session) cache
- [ ] The second-level cache
- [ ] The query cache
> L2 and query caches are opt-in.

Q: Good candidates for the second-level cache are…
- [x] Small, read-mostly reference data like countries
- [ ] Frequently updated orders
- [ ] Huge random-access tables
> Volatile data causes constant invalidation.

Q: The query cache stores…
- [x] The ids returned by a query, invalidated when its tables change
- [ ] Whole HTML pages
- [ ] SQL text only
> Entities themselves come from the L2 cache.

## hibernate-batching-and-performance
Q: Which setting enables JDBC batching?
- [x] hibernate.jdbc.batch_size
- [ ] hibernate.show_sql
- [ ] hibernate.cache.use_query_cache
> Statements are sent in groups.

Q: What is a StatelessSession?
- [x] A session with no persistence context or dirty checking, for bulk work
- [ ] A session without a database
- [ ] A cached session
> Ideal for imports and exports.

Q: Bulk JPQL updates…
- [x] Bypass the persistence context
- [ ] Load each entity first
- [ ] Are impossible
> Loaded entities keep old values; clear afterwards.

## hibernate-useful-annotations
Q: What does @SoftDelete change?
- [x] em.remove becomes an UPDATE setting a deleted flag, and queries skip deleted rows
- [ ] Nothing
- [ ] Rows are moved to another table
> Available in Hibernate 6.4+.

Q: @Formula maps a field to…
- [x] A read-only SQL expression evaluated when loading
- [ ] A Java method
- [ ] A JSON column
> Good for computed values like counts.

Q: Which module records the history of entity changes?
- [x] Envers (@Audited)
- [ ] Validator
- [ ] JCache
> It keeps _AUD tables with every version.

## hibernate-best-practices
Q: Which property should be false in Spring Boot?
- [x] spring.jpa.open-in-view
- [ ] spring.jpa.show-sql
- [ ] spring.datasource.url
> Open-in-view hides lazy loading in the web layer.

Q: How should production schemas be managed?
- [x] Versioned migrations with Flyway or Liquibase
- [ ] hbm2ddl.auto=update
- [ ] Manually in production
> Hibernate should only validate.

Q: What should controllers return?
- [x] DTOs, not entities
- [ ] Entities with all relationships
- [ ] Hibernate proxies
> DTOs prevent leaks, loops and lazy errors.
