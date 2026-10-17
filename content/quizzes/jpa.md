## jpa-introduction
Q: What is JPA?
- [x] A specification (API) for object-relational mapping
- [ ] A database
- [ ] A Spring module
> Hibernate is the most popular implementation.

Q: What does ORM map?
- [x] Classes to tables, objects to rows, fields to columns
- [ ] HTML to CSS
- [ ] JSON to XML
> References become foreign keys.

Q: Spring Data JPA sits…
- [x] On top of JPA, generating repositories
- [ ] Below JDBC
- [ ] Instead of Hibernate
> It removes even more boilerplate.

## jpa-setup-first-entity
Q: Where does persistence.xml live?
- [x] src/main/resources/META-INF/
- [ ] src/main/java
- [ ] The project root
> The unit name must match createEntityManagerFactory.

Q: Why does an entity need a no-argument constructor?
- [x] The provider creates objects by reflection when loading rows
- [ ] For JSON
- [ ] It doesn't
> It can be protected.

Q: How many EntityManagerFactories should an app create?
- [x] One
- [ ] One per request
- [ ] One per entity
> It's heavy; EntityManagers are created per unit of work.

## jpa-entity-mapping
Q: Which enum mapping is safe when constants are reordered?
- [ ] ORDINAL
- [x] STRING
- [ ] Either
> STRING stores the name.

Q: What is @Embeddable for?
- [x] A value object whose fields are stored in the owner's table
- [ ] A separate table
- [ ] A cached entity
> Address embedded in Student, for example.

Q: Which id strategy allows JDBC insert batching?
- [ ] IDENTITY
- [x] SEQUENCE with an allocationSize
- [ ] None
> IDENTITY inserts must run immediately to get the id.

## jpa-entity-states
Q: A managed entity whose field changes inside a transaction…
- [x] Gets an UPDATE at commit automatically
- [ ] Needs em.update()
- [ ] Is ignored
> Dirty checking compares with a snapshot.

Q: What does merge return?
- [x] A managed copy; the argument stays detached
- [ ] Nothing
- [ ] The same object, now managed
> Use the returned instance.

Q: When does an entity become detached?
- [ ] After persist
- [x] When its EntityManager is closed or cleared
- [ ] After find
> Detached entities aren't tracked.

## jpa-persistence-context
Q: Why does a second em.find for the same id run no SQL?
- [x] It's served from the persistence context (first-level cache)
- [ ] The second-level cache is on by default
- [ ] find never runs SQL
> One managed object per row per context.

Q: When is pending SQL flushed?
- [x] At commit, before relevant queries, or on em.flush()
- [ ] Immediately on persist always
- [ ] Never
> Write-behind batches changes.

Q: How do you keep memory flat in a big insert loop?
- [x] flush() and clear() every batch
- [ ] Use more RAM
- [ ] Call persist twice
> clear() detaches processed entities.

## jpa-relationships
Q: Which side owns a bidirectional one-to-many?
- [x] The @ManyToOne side holding the foreign key
- [ ] The @OneToMany side
- [ ] Both
> mappedBy marks the inverse side.

Q: What does orphanRemoval do?
- [x] Deletes children removed from the parent's collection
- [ ] Removes the parent
- [ ] Nothing in Hibernate
> Use it for owned children like order items.

Q: Best practice fetch type for @ManyToOne?
- [ ] EAGER
- [x] LAZY
- [ ] It doesn't matter
> Fetch per use case with JOIN FETCH or entity graphs.

## jpa-jpql-queries
Q: JPQL queries use…
- [x] Entity and field names
- [ ] Table and column names
- [ ] JSON paths
> `select c from Course c where c.price < :max`.

Q: JOIN FETCH differs from JOIN because it…
- [x] Also loads the association in the same query
- [ ] Is faster SQL syntax only
- [ ] Removes duplicates
> It's the main cure for N+1.

Q: How do you paginate a JPQL query?
- [x] setFirstResult and setMaxResults
- [ ] LIMIT in JPQL
- [ ] subList
> Hibernate generates the right SQL per database.

## jpa-inheritance-and-callbacks
Q: Which strategy stores a hierarchy in one table?
- [x] SINGLE_TABLE
- [ ] JOINED
- [ ] TABLE_PER_CLASS
> A discriminator column tells rows apart.

Q: Can you query a @MappedSuperclass?
- [ ] Yes
- [x] No, it only shares mappings
- [ ] Only with native SQL
> Use an entity parent for polymorphic queries.

Q: Which callback runs before INSERT?
- [x] @PrePersist
- [ ] @PostLoad
- [ ] @PreUpdate
> Great for creation timestamps.

## jpa-transactions-and-locking
Q: What does @Version provide?
- [x] Optimistic locking: stale updates fail
- [ ] A database backup
- [ ] Faster reads
> The version is checked in the UPDATE's WHERE clause.

Q: Pessimistic write locks translate to…
- [x] SELECT ... FOR UPDATE
- [ ] A version column
- [ ] A cache
> Others wait until the transaction ends.

Q: What is a lost update?
- [x] A later write silently overwrites an earlier one based on stale data
- [ ] A failed INSERT
- [ ] A deleted row
> Optimistic locking detects it.
