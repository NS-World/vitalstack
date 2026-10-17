---
title: Installing Jmix Studio and Creating Your First Project
slug: jmix-setup-first-project
subject: jmix
lesson: 2
difficulty: intermediate
excerpt: Install IntelliJ IDEA and the Jmix Studio plugin, create a project from a template, understand the generated structure, run the app, log in as admin and explore the Jmix tool window.
---

Let's set up a Jmix development environment and run your first application.

## Requirements

- **JDK 17 or 21**.
- **IntelliJ IDEA** (Community or Ultimate).
- **Jmix Studio plugin**: in IntelliJ, open *Settings → Plugins → Marketplace*, search **Jmix**, install, and restart.
- Internet access the first time (Gradle downloads dependencies).

## Create a project

1. *File → New → Project*, choose **Jmix Project** on the left.
2. Pick the **Full-Stack Application (Java)** template.
3. Project name: `course-admin`, base package: `com.vitalstack.courseadmin`.
4. JDK 17+, keep the default **HSQLDB** in-memory database for now (switch to PostgreSQL or MySQL later in *Data Stores*).
5. Click **Create** and wait for Gradle to sync. The first time takes a few minutes.

## The generated structure

```text
course-admin/
├── build.gradle                      ← dependencies (Jmix BOM, add-ons)
├── src/main/java/com/vitalstack/courseadmin/
│   ├── CourseAdminApplication.java   ← @SpringBootApplication main class
│   ├── entity/User.java              ← the user entity (login, password, names)
│   ├── security/                     ← FullAccessRole, UiMinimalRole, DatabaseUserRepository
│   └── view/
│       ├── main/MainView.java        ← the app shell: menu + header
│       ├── login/LoginView.java
│       └── user/                     ← user list and detail views
├── src/main/resources/com/vitalstack/courseadmin/
│   ├── view/**/*.xml                 ← view layouts (descriptors)
│   ├── menu.xml                      ← main menu
│   ├── messages_en.properties        ← UI text (i18n)
│   └── liquibase/                    ← database changelogs
├── src/main/resources/application.properties
└── frontend/themes/course-admin/     ← CSS theme
```

It's a normal Spring Boot application with extra Jmix modules.

## Run it

In the **Jmix** tool window (left side), or the run configurations dropdown, choose **Course Admin Jmix Application** and click Run. When the log says the app started, open:

```text
http://localhost:8080
```

Log in with **admin / admin**. You'll see the main view with a menu containing **Users**. Open it: a fully working list view with search, paging, and Create/Edit/Remove. You haven't written any code yet.

## The Jmix tool window

The tool window shows your project the Jmix way:

- **Data Model**: entities and enums. Right-click → New → JPA Entity.
- **User Interface**: views, menu, themes.
- **Security**: resource and row-level roles.
- **Data Stores**: database connections and Liquibase changelogs.
- **Add-ons**: install reports, BPM, email, REST, etc. with one click.

Studio's visual designers (entity designer, view designer with a component palette and preview) write the Java and XML for you; you can always edit the code directly.

## application.properties

```ini
main.datasource.url=jdbc:hsqldb:file:.jmix/hsqldb/courseadmin
main.datasource.username=sa
main.datasource.password=

jmix.ui.login-view-id=LoginView
jmix.ui.main-view-id=MainView
jmix.ui.menu-config=com/vitalstack/courseadmin/menu.xml

# show SQL while learning
logging.level.eclipselink.logging.sql=debug
```

Note the `main.datasource` prefix: Jmix supports multiple data stores, and `main` is the default one.

## Switching to PostgreSQL

In *Data Stores → Main Data Store*, choose PostgreSQL and enter the URL, user and password. Studio updates `build.gradle` (driver) and `application.properties`. Use environment variables for the password in shared or production setups:

```ini
main.datasource.url=jdbc:postgresql://localhost/courseadmin
main.datasource.username=courseadmin
main.datasource.password=${DB_PASSWORD}
```

## Useful Gradle tasks

```bash
./gradlew bootRun                                   # run from the terminal
./gradlew -Pvaadin.productionMode=true bootJar      # build a production jar (lesson 7)
./gradlew test
```

::tip On the first run, Vaadin builds the front-end bundle (with Node.js, downloaded automatically), which takes a while. Later runs are much faster. If the UI looks unstyled after a dependency change, run the **Clean Vaadin frontend** action or delete `node_modules` and restart.

## Common problems

- **Port 8080 already in use**: stop the other app, or set `server.port=8081`.
- **Gradle sync fails behind a proxy**: configure the proxy in `gradle.properties`.
- **Wrong JDK**: check *Project Structure → SDK* and Gradle JVM in settings both point to JDK 17+.

## Exercise

1. Create and run the project, log in as admin, and create a new user.
2. Log in as the new user. What happens? (Hint: roles.)
3. Change the app title shown in the browser tab in `messages_en.properties`.
4. Find the generated Liquibase changelog for the User table.

::hint Hint for 2 | A new user has no roles, so login is refused or they see an empty app. Assign the `UiMinimalRole` and a role with permissions (lesson 6) to let them in.

## Interview corner

- **What is Jmix Studio?** An IntelliJ IDEA plugin with project templates, visual designers and code generation for Jmix apps.
- **Where is the database schema managed?** Liquibase changelogs that Studio generates from entity changes.
- **What's the default admin login?** admin/admin in a new project; change it before deploying!

## Summary

- Install IntelliJ + the Jmix plugin; create a Full-Stack Application project.
- A Jmix app is a Spring Boot app with views (XML + Java), entities, roles and Liquibase changelogs.
- Run it and log in as admin/admin to see a working user management UI.

Next: design the data model with Jmix entities.
