---
title: "Jmix Project: Course Admin App and Deployment"
slug: jmix-project-and-deployment
subject: jmix
lesson: 7
difficulty: advanced
excerpt: Put it all together - a course admin app with dashboards, reports, a REST API for a mobile or React front end, and production deployment as a jar or Docker container with PostgreSQL.
---

Let's turn the pieces into a complete **Course Admin** application that a training institute could actually use, then deploy it.

## Requirements

1. Admins manage **categories**, **instructors** and **courses** with lessons.
2. Instructors manage only **their own** courses.
3. A **dashboard** shows course counts per level and the newest courses.
4. Managers export a **course catalogue report** to Excel/PDF.
5. A public website (React or Angular) reads published courses through a **REST API**.

## 1. Data model and views (lessons 3-4)

- Entities: `Category`, `Instructor` (linked to a `User`), `Course` (with `published` flag, level, price), `Lesson` (composition).
- Generate list and detail views for each; add a `published` filter condition and a **Publish** button.

## 2. Security (lesson 6)

| Role | Grants |
|---|---|
| `admin` | full access |
| `course-manager` | CRUD on courses, lessons, categories, instructors; reports |
| `instructor` | CRUD on Course and Lesson + row-level `own-courses` |
| `ui-minimal` | everyone, to log in |

## 3. A dashboard view

Create a blank view (*User Interface → New → View → Blank view*) and set it as the start page of the main view:

```xml
<view xmlns="http://jmix.io/schema/flowui/view" title="Dashboard">
    <layout>
        <h3 text="Courses by level"/>
        <hbox id="statsBox" spacing="true"/>
        <h3 text="Newest courses"/>
        <dataGrid id="newestGrid" width="100%" height="20em" dataContainer="newestDc">
            <columns>
                <column property="title"/>
                <column property="instructor"/>
                <column property="createdDate"/>
            </columns>
        </dataGrid>
    </layout>
    <data readOnly="true">
        <collection id="newestDc" class="com.vitalstack.courseadmin.entity.Course">
            <fetchPlan extends="_base">
                <property name="instructor" fetchPlan="_instance_name"/>
            </fetchPlan>
            <loader id="newestDl" maxResults="10">
                <query><![CDATA[select c from Course c order by c.createdDate desc]]></query>
            </loader>
        </collection>
    </data>
    <facets>
        <dataLoadCoordinator auto="true"/>
    </facets>
</view>
```

```java
@Route(value = "dashboard", layout = MainView.class)
@ViewController("DashboardView")
@ViewDescriptor("dashboard-view.xml")
public class DashboardView extends StandardView {

    @Autowired private DataManager dataManager;
    @ViewComponent private HorizontalLayout statsBox;

    @Subscribe
    public void onBeforeShow(final BeforeShowEvent event) {
        List<KeyValueEntity> rows = dataManager.loadValues(
                        "select c.level, count(c) from Course c group by c.level")
                .properties("level", "count")
                .list();
        for (KeyValueEntity row : rows) {
            Span card = new Span(row.getValue("level") + ": " + row.getValue("count"));
            card.addClassName("stat-card");
            statsBox.add(card);
        }
    }
}
```

Because it uses `DataManager`, an instructor's dashboard automatically counts only **their** courses (row-level security).

## 4. Reports add-on

Install **Reports** from *Add-ons* in Studio. Admins then design reports in the running app: a band with a JPQL query (`select c.title, c.level, c.price from Course c where c.published = true`), and a template (XLSX, DOCX or HTML → PDF). Add a **Run report** button to the course list. No code needed for most business reports.

Other popular add-ons: **BPM** (approval workflows with BPMN, e.g. "new course → manager approval → publish"), **Email** (templated notifications), **Charts**, **Audit** (entity change history), **Multitenancy**.

## 5. REST API for the public site

Install the **REST API** add-on (and authorization server) to expose entities and services over HTTP with OAuth2 tokens, respecting the same roles. For a public, read-only catalogue, a plain Spring MVC controller is often simpler, because Jmix apps are Spring Boot apps:

```java
@RestController
@RequestMapping("/public/courses")
public class PublicCourseController {
    private final UnconstrainedDataManager dataManager;      // public data, filtered explicitly below

    public PublicCourseController(UnconstrainedDataManager dataManager) { this.dataManager = dataManager; }

    public record CourseCard(String title, String level, BigDecimal price) { }

    @GetMapping
    public List<CourseCard> published() {
        return dataManager.load(Course.class)
                .query("select c from Course c where c.published = true order by c.title")
                .list().stream()
                .map(c -> new CourseCard(c.getTitle(), String.valueOf(c.getLevel()), c.getPrice()))
                .toList();
    }
}
```

Permit `/public/**` without login in a security configuration bean, and enable CORS for your website's domain. Your React or Angular course projects can call this endpoint.

## 6. Deployment

### Build a production jar

```bash
./gradlew -Pvaadin.productionMode=true bootJar
java -jar build/libs/course-admin-0.0.1-SNAPSHOT.jar
```

Production mode bundles and minifies the front end for fast loading.

### Configure for production

```ini
main.datasource.url=jdbc:postgresql://db:5432/courseadmin
main.datasource.username=courseadmin
main.datasource.password=${DB_PASSWORD}
jmix.ui.session-timeout=30m
logging.level.root=info
```

Pass secrets as environment variables, exactly like Spring Boot (lesson 9 of the Spring Boot course).

### Docker

```dockerfile
FROM eclipse-temurin:21-jre-alpine
WORKDIR /app
COPY build/libs/*.jar app.jar
EXPOSE 8080
ENTRYPOINT ["java", "-XX:MaxRAMPercentage=75", "-jar", "app.jar"]
```

Or `./gradlew -Pvaadin.productionMode=true bootBuildImage`. Run it next to PostgreSQL with Docker Compose on a VPS, behind Nginx with HTTPS. Vaadin UIs use a server-side session per user, so with several app instances enable **sticky sessions** on the load balancer.

### Before going live

- ✅ Change or disable the default `admin` account.
- ✅ Real database with backups; Liquibase runs migrations on startup.
- ✅ HTTPS; secrets in environment variables.
- ✅ Test every role with a real non-admin user.
- ✅ Production mode build.

::tip A Jmix project like this (entities, role-based security, dashboard, report, REST endpoint, deployed with Docker) is a strong portfolio piece for enterprise Java jobs, and many Indian IT services companies build exactly these kinds of internal systems for clients.

## Exercise

1. Build the Course Admin app with all five requirements.
2. Add a BPM approval: instructors submit a course; a manager approves it before it's published.
3. Add an email notification to the instructor when their course is approved.
4. Deploy it with Docker Compose and PostgreSQL on your machine or a VPS.

::hint Hint for 2 | Without the BPM add-on, a simple version is a `status` enum (DRAFT, SUBMITTED, APPROVED) plus buttons enabled by specific policies. Use BPM when processes have many steps, deadlines or branching.

## Interview corner

- **How do you deploy a Jmix app?** As a Spring Boot jar (production mode) or container image, with an external database and environment-based configuration.
- **How does a Jmix app expose data to other clients?** The REST API add-on (secured, role-aware) or custom Spring MVC controllers.
- **What are Jmix add-ons?** Ready-made modules (reports, BPM, email, charts, audit, multitenancy) installed via Studio.
- **Why sticky sessions?** Vaadin keeps UI state in the server-side session.

## Summary

- A full business app = data model + generated views + roles + a few custom views and services.
- Add-ons cover reports, workflows, email and APIs.
- Deploy as a production-mode jar or Docker image with PostgreSQL, HTTPS and environment secrets.

**You've completed the Jmix course!** Continue with **React** or **Angular** to build public front ends that talk to your Java back ends.
