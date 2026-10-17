---
title: JSP, JSTL and the MVC Pattern
slug: servlet-jsp-jstl-mvc
subject: servlet
lesson: 7
difficulty: intermediate
excerpt: Build HTML views with JSP, Expression Language and JSTL instead of println, structure apps with Model-View-Controller (servlet + JSP), and learn how this evolved into Spring MVC and Thymeleaf.
---

Writing HTML with `out.println("<tr><td>" + name + "</td></tr>")` gets ugly fast. **JSP (Jakarta Server Pages)** flips it around: you write HTML and insert dynamic values where needed. Combined with servlets, you get the **MVC** pattern, the foundation of Spring MVC.

## MVC: three jobs, three places

```text
Browser ──request──▶ Controller (Servlet) ──uses──▶ Model (Java classes, DB)
                          │
                          └─forward with data──▶ View (JSP) ──HTML──▶ Browser
```

- **Model**: data and business logic (`Course`, `CourseService`).
- **View**: presentation only (JSP, HTML).
- **Controller**: receives the request, calls the model, picks the view.

Each part can change without breaking the others: designers edit JSPs, developers edit Java.

## The controller servlet

```java
import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;
import java.io.IOException;
import java.util.List;

@WebServlet("/courses")
public class CourseController extends HttpServlet {

    public record Course(int id, String title, String level, int lessons) { }

    private static final List<Course> COURSES = List.of(
        new Course(1, "Java", "Beginner", 16),
        new Course(2, "Spring Boot", "Intermediate", 10),
        new Course(3, "Hibernate", "Advanced", 7));

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        String level = req.getParameter("level");
        List<Course> list = (level == null || level.isBlank()) ? COURSES
                : COURSES.stream().filter(c -> c.level().equalsIgnoreCase(level)).toList();

        req.setAttribute("courses", list);          // the model for the view
        req.setAttribute("level", level);
        req.getRequestDispatcher("/WEB-INF/views/courses.jsp").forward(req, resp);
    }
}
```

JSPs inside `WEB-INF` can't be opened directly by the browser: users must go through the controller. That's good MVC hygiene.

## The view: JSP with EL and JSTL

Add JSTL to `pom.xml` (Tomcat doesn't include it):

```xml
<dependency>
  <groupId>jakarta.servlet.jsp.jstl</groupId>
  <artifactId>jakarta.servlet.jsp.jstl-api</artifactId>
  <version>3.0.0</version>
</dependency>
<dependency>
  <groupId>org.glassfish.web</groupId>
  <artifactId>jakarta.servlet.jsp.jstl</artifactId>
  <version>3.0.1</version>
</dependency>
```

`/WEB-INF/views/courses.jsp`:

```html
<%@ page contentType="text/html;charset=UTF-8" %>
<%@ taglib prefix="c" uri="jakarta.tags.core" %>
<%@ taglib prefix="fn" uri="jakarta.tags.functions" %>
<!doctype html>
<html>
<head><title>Courses</title></head>
<body>
  <h1>Courses <c:if test="${not empty level}">(${fn:escapeXml(level)})</c:if></h1>

  <c:choose>
    <c:when test="${empty courses}">
      <p>No courses found.</p>
    </c:when>
    <c:otherwise>
      <table>
        <tr><th>#</th><th>Title</th><th>Level</th><th>Lessons</th></tr>
        <c:forEach var="course" items="${courses}" varStatus="s">
          <tr>
            <td>${s.count}</td>
            <td><c:out value="${course.title}"/></td>
            <td>${course.level}</td>
            <td>${course.lessons}</td>
          </tr>
        </c:forEach>
      </table>
    </c:otherwise>
  </c:choose>
</body>
</html>
```

The `fn` taglib provides helpers like `fn:escapeXml`, `fn:length` and `fn:toUpperCase`.

## Expression Language (EL)

`${...}` reads values from request, session and application attributes:

| EL | Meaning |
|---|---|
| `${courses}` | attribute named "courses" (page → request → session → application) |
| `${course.title}` | calls `getTitle()` or the record accessor `title()` |
| `${sessionScope.user}` | a session attribute explicitly |
| `${param.q}` | request parameter `q` |
| `${empty list}` | true if null or empty |
| `${a > 5 && b}`, `${x == 'Java' ? 'yes' : 'no'}` | operators |

## JSTL core tags

| Tag | Does |
|---|---|
| `<c:out value="${x}"/>` | prints **escaped** text (prevents XSS) |
| `<c:if test="...">` | conditional |
| `<c:choose>/<c:when>/<c:otherwise>` | if / else if / else |
| `<c:forEach items="..." var="...">` | loop |
| `<c:url value="/courses"/>` | adds the context path |
| `<c:set var="..." value="..."/>` | set a variable |

::tip Use `<c:out>` (or `fn:escapeXml`) for any value that came from a user. Plain `${...}` in JSP does **not** escape HTML, so it can open XSS holes.

## Avoid scriptlets

Old JSPs mixed Java code into HTML with `<% ... %>` scriptlets. It's hard to read, test and secure. Modern rule: **no Java code in JSPs**. Prepare everything in the servlet; the JSP only displays it with EL and JSTL.

## How a JSP runs

The first time a JSP is requested, the container **translates it into a servlet** (Java code), compiles it, and runs it. A JSP **is** a servlet behind the scenes. That's why the first request to a new JSP is slower.

## From here to Spring MVC

| Servlet + JSP | Spring MVC |
|---|---|
| one servlet per URL | one `DispatcherServlet` routes to `@Controller` methods |
| `req.getParameter("id")` | `@RequestParam int id` / `@PathVariable` |
| `req.setAttribute("courses", list)` | `model.addAttribute("courses", list)` |
| forward to JSP | return a view name (`"courses"`), often Thymeleaf |
| print JSON by hand | `@RestController` converts objects to JSON |

Same MVC idea, far less boilerplate. Many companies still maintain JSP apps, so knowing both is valuable.

## Exercise

1. Build the courses page with filtering links: All, Beginner, Intermediate, Advanced.
2. Add a `/courses/detail?id=2` controller and JSP; show a 404 if the id doesn't exist.
3. Create a reusable header with `<%@ include file="/WEB-INF/views/header.jspf" %>` and use it on both pages.
4. Show "Welcome, ${sessionScope.user}" in the header when logged in, and a Login link otherwise.

::hint Hint for 1 | Use `<c:url value="/courses"><c:param name="level" value="Beginner"/></c:url>` to build links that include the context path.

## Interview corner

- **What is MVC?** Model-View-Controller: separates data/logic, presentation and request handling.
- **JSP vs servlet?** A JSP is HTML with dynamic parts, compiled into a servlet; servlets are Java classes better suited to control logic.
- **What is EL and JSTL?** EL (`${}`) accesses data in JSPs; JSTL is a tag library for loops, conditions, URLs and formatting.
- **Why put JSPs under WEB-INF?** To force access through controllers so views always get prepared data.

## Summary

- MVC: servlet = controller, Java classes = model, JSP = view.
- JSPs use EL and JSTL (no scriptlets); `<c:out>` escapes output.
- JSPs compile into servlets; Spring MVC is the same pattern with less code.

Next: build a complete to-do app with servlets, JSP and JDBC.
