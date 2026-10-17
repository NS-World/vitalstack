## jmix-introduction
Q: Jmix is built on…
- [x] Spring Boot, JPA and Vaadin
- [ ] React and Node.js
- [ ] PHP
> One Java codebase for back end and UI.

Q: Jmix is best suited for…
- [x] Data-heavy business and back-office apps
- [ ] Public marketing sites with custom design
- [ ] Mobile games
> CRUD screens are generated in minutes.

Q: In Jmix 2, UI units are called…
- [x] Views
- [ ] Screens
- [ ] Pages only
> Jmix 1.x called them screens.

## jmix-setup-first-project
Q: Jmix Studio is…
- [x] An IntelliJ IDEA plugin with templates and visual designers
- [ ] A separate IDE
- [ ] A database
> Install it from the IntelliJ marketplace.

Q: The default login in a new project is…
- [x] admin / admin
- [ ] root / root
- [ ] There is no login
> Change it before deploying.

Q: Jmix's main datasource property prefix is…
- [x] main.datasource
- [ ] spring.datasource
- [ ] db
> Jmix supports multiple data stores.

## jmix-entities-and-data-model
Q: What does @InstanceName mark?
- [x] The attribute shown as the entity's display name in the UI
- [ ] The primary key
- [ ] The table name
> Used in dropdowns and titles.

Q: A @Composition relationship means children are…
- [x] Parts of the parent, edited and saved with it
- [ ] Independent entities
- [ ] Not stored
> Lessons inside a course, for example.

Q: Who generates schema changes?
- [x] Studio generates Liquibase changelogs
- [ ] You must write SQL by hand
- [ ] Hibernate update mode
> Review and commit the changelogs.

## jmix-views
Q: A data loader…
- [x] Runs a JPQL query and fills a data container
- [ ] Renders buttons
- [ ] Defines security
> Components bind to the container.

Q: What does a fetch plan control?
- [x] Which attributes and references are loaded together
- [ ] The page layout
- [ ] User permissions
> It prevents N+1 and lazy errors.

Q: Standard list actions like list_create…
- [x] Open the detail view and respect security
- [ ] Only work for admins
- [ ] Must be coded by hand
> Remove asks for confirmation too.

## jmix-data-access
Q: DataManager…
- [x] Applies the current user's security and row-level rules
- [ ] Bypasses all security
- [ ] Only works in views
> UnconstrainedDataManager bypasses it deliberately.

Q: Which method returns exactly one result or throws?
- [x] one()
- [ ] list()
- [ ] optional()
> optional() returns an Optional instead.

Q: EntityChangedEvent with @TransactionalEventListener runs…
- [x] After the transaction commits
- [ ] Before saving
- [ ] Never
> Good for emails and integrations.

## jmix-security
Q: Row-level roles restrict…
- [x] Which records a user can see
- [ ] Which menu items show
- [ ] The UI theme
> JPQL conditions are added to every query.

Q: How do multiple roles combine?
- [x] Additively: the union of permissions
- [ ] The most restrictive wins
- [ ] Only the first role applies
> A user with no roles can do nothing.

Q: Which role lets users log in and see the main view?
- [x] ui-minimal
- [ ] system-full-access only
- [ ] none
> Combine it with job-specific roles.

## jmix-project-and-deployment
Q: How do you build a production Jmix jar?
- [x] ./gradlew -Pvaadin.productionMode=true bootJar
- [ ] npm run build
- [ ] mvn war
> Production mode bundles the front end.

Q: Why sticky sessions with several instances?
- [x] Vaadin keeps UI state in the server-side session
- [ ] For caching images
- [ ] For HTTPS
> Each user must return to the same instance.

Q: Which add-on gives Excel/PDF reports?
- [x] Reports
- [ ] BPM
- [ ] Audit
> Report templates are designed in the running app.
