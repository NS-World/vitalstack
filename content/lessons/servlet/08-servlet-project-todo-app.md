---
title: "Servlet Project: To-Do App with JDBC"
slug: servlet-project-todo-app
subject: servlet
lesson: 8
difficulty: advanced
excerpt: Build a complete MVC to-do web app with servlets, JSP, sessions, a login filter and a MySQL or H2 database through JDBC with PreparedStatement, a DAO and a connection pool.
---

Let's combine everything: servlets, JSP, sessions, filters and a real database. You'll build a **to-do app** where each user manages their own tasks.

## Architecture

```text
Browser
  │
  ▼
AuthFilter (/tasks/*)  ──not logged in──▶ /login
  │
  ▼
TaskServlet (controller)  ──▶ TaskDao (JDBC) ──▶ MySQL / H2
  │
  ▼
tasks.jsp (view)
```

## Step 1: Database table

```sql
CREATE TABLE tasks (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  owner      VARCHAR(100) NOT NULL,
  title      VARCHAR(200) NOT NULL,
  done       BOOLEAN NOT NULL DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Add the driver to `pom.xml` (MySQL shown; for quick practice use `com.h2database:h2` instead):

```xml
<dependency>
  <groupId>com.mysql</groupId>
  <artifactId>mysql-connector-j</artifactId>
  <version>8.4.0</version>
</dependency>
<dependency>
  <groupId>com.zaxxer</groupId>
  <artifactId>HikariCP</artifactId>
  <version>5.1.0</version>
</dependency>
```

## Step 2: A connection pool on startup

Opening a database connection is slow (~50-100 ms). A **connection pool** opens a few connections once and lends them out. HikariCP is the fastest (Spring Boot uses it by default).

```java
package com.vitalstack.todo;

import com.zaxxer.hikari.HikariConfig;
import com.zaxxer.hikari.HikariDataSource;
import jakarta.servlet.*;
import jakarta.servlet.annotation.WebListener;

@WebListener
public class DbSetup implements ServletContextListener {
    private HikariDataSource ds;

    @Override
    public void contextInitialized(ServletContextEvent e) {
        HikariConfig cfg = new HikariConfig();
        cfg.setJdbcUrl(System.getenv().getOrDefault("DB_URL", "jdbc:mysql://localhost:3306/todo"));
        cfg.setUsername(System.getenv().getOrDefault("DB_USER", "todo"));
        cfg.setPassword(System.getenv("DB_PASSWORD"));      // never hard-code passwords
        cfg.setMaximumPoolSize(10);
        ds = new HikariDataSource(cfg);
        e.getServletContext().setAttribute("taskDao", new TaskDao(ds));
    }

    @Override
    public void contextDestroyed(ServletContextEvent e) {
        if (ds != null) ds.close();
    }
}
```

## Step 3: The model and DAO

A **DAO (Data Access Object)** keeps all SQL in one class:

```java
package com.vitalstack.todo;

import javax.sql.DataSource;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;

public class TaskDao {
    public record Task(int id, String title, boolean done) { }

    private final DataSource ds;
    public TaskDao(DataSource ds) { this.ds = ds; }

    public List<Task> findByOwner(String owner) throws SQLException {
        String sql = "SELECT id, title, done FROM tasks WHERE owner = ? ORDER BY done, created_at DESC";
        try (Connection c = ds.getConnection(); PreparedStatement ps = c.prepareStatement(sql)) {
            ps.setString(1, owner);
            try (ResultSet rs = ps.executeQuery()) {
                List<Task> list = new ArrayList<>();
                while (rs.next()) {
                    list.add(new Task(rs.getInt("id"), rs.getString("title"), rs.getBoolean("done")));
                }
                return list;
            }
        }
    }

    public void add(String owner, String title) throws SQLException {
        try (Connection c = ds.getConnection();
             PreparedStatement ps = c.prepareStatement("INSERT INTO tasks(owner, title) VALUES (?, ?)")) {
            ps.setString(1, owner);
            ps.setString(2, title);
            ps.executeUpdate();
        }
    }

    public void toggle(String owner, int id) throws SQLException {
        try (Connection c = ds.getConnection();
             PreparedStatement ps = c.prepareStatement("UPDATE tasks SET done = NOT done WHERE id = ? AND owner = ?")) {
            ps.setInt(1, id);
            ps.setString(2, owner);       // users can only change their OWN tasks
            ps.executeUpdate();
        }
    }

    public void delete(String owner, int id) throws SQLException {
        try (Connection c = ds.getConnection();
             PreparedStatement ps = c.prepareStatement("DELETE FROM tasks WHERE id = ? AND owner = ?")) {
            ps.setInt(1, id);
            ps.setString(2, owner);
            ps.executeUpdate();
        }
    }
}
```

::tip **Always use `PreparedStatement` with `?` placeholders.** Building SQL with `"... WHERE owner = '" + owner + "'"` allows **SQL injection**: a user typing `' OR '1'='1` could read or delete everyone's data.

Notice `try-with-resources` returns every connection to the pool, even when an exception happens.

## Step 4: The controller

```java
package com.vitalstack.todo;

import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;
import java.io.IOException;
import java.sql.SQLException;

@WebServlet("/tasks")
public class TaskServlet extends HttpServlet {
    private TaskDao dao;

    @Override
    public void init() {
        dao = (TaskDao) getServletContext().getAttribute("taskDao");
    }

    private String owner(HttpServletRequest req) {
        return (String) req.getSession(false).getAttribute("user");    // the filter guarantees a session
    }

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        try {
            req.setAttribute("tasks", dao.findByOwner(owner(req)));
            req.getRequestDispatcher("/WEB-INF/views/tasks.jsp").forward(req, resp);
        } catch (SQLException e) {
            throw new ServletException("Could not load tasks", e);
        }
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        req.setCharacterEncoding("UTF-8");
        String action = String.valueOf(req.getParameter("action"));
        try {
            switch (action) {
                case "add" -> {
                    String title = req.getParameter("title");
                    if (title != null && !title.isBlank() && title.length() <= 200) dao.add(owner(req), title.trim());
                }
                case "toggle" -> dao.toggle(owner(req), Integer.parseInt(req.getParameter("id")));
                case "delete" -> dao.delete(owner(req), Integer.parseInt(req.getParameter("id")));
                default -> { resp.sendError(400, "Unknown action"); return; }
            }
        } catch (SQLException | NumberFormatException e) {
            throw new ServletException(e);
        }
        resp.sendRedirect(req.getContextPath() + "/tasks");         // Post/Redirect/Get
    }
}
```

Use the `AuthFilter` and `LoginServlet` from the previous lessons to protect `/tasks`.

## Step 5: The view

`/WEB-INF/views/tasks.jsp`:

```html
<%@ page contentType="text/html;charset=UTF-8" %>
<%@ taglib prefix="c" uri="jakarta.tags.core" %>
<!doctype html>
<html><body>
<h1>My tasks</h1>
<form method="post" action="<c:url value='/tasks'/>">
  <input type="hidden" name="action" value="add">
  <input name="title" placeholder="What needs doing?" maxlength="200" required>
  <button>Add</button>
</form>
<ul>
  <c:forEach var="t" items="${tasks}">
    <li>
      <form method="post" action="<c:url value='/tasks'/>" style="display:inline">
        <input type="hidden" name="id" value="${t.id}">
        <button name="action" value="toggle">${t.done ? '✅' : '⬜'}</button>
        <c:choose>
          <c:when test="${t.done}"><s><c:out value="${t.title}"/></s></c:when>
          <c:otherwise><c:out value="${t.title}"/></c:otherwise>
        </c:choose>
        <button name="action" value="delete">🗑</button>
      </form>
    </li>
  </c:forEach>
</ul>
<form method="post" action="<c:url value='/logout'/>"><button>Log out</button></form>
</body></html>
```

## What you used

| Concept | Where |
|---|---|
| Servlet lifecycle | `init()` gets the DAO once |
| Listener | `DbSetup` creates the pool at startup, closes it on shutdown |
| Filter + session | only logged-in users reach `/tasks`; tasks belong to the session user |
| MVC | servlet (controller), DAO + record (model), JSP (view) |
| Forms + PRG | POST then redirect |
| JDBC best practices | pool, PreparedStatement, try-with-resources, ownership checks |
| XSS protection | `<c:out>` for titles |

## Extend it

1. Add due dates and highlight overdue tasks.
2. Add a filter: All / Active / Done.
3. Replace the hard-coded login with a `users` table and BCrypt password hashes.
4. Add CSRF protection: store a random token in the session and require it as a hidden field in every POST form.
5. Rewrite the DAO with JPA (next course), then the whole app with Spring Boot, and compare the amount of code.

::hint Hint for 4 | Generate the token with `SecureRandom` + Base64 at login, put it in the session and in every form; in a filter for POST requests, compare `req.getParameter("csrf")` with the session value and return 403 if they differ.

## Summary

- A real web app = filter (security) + servlet (controller) + DAO (data) + JSP (view).
- Use a connection pool, PreparedStatement and try-with-resources for JDBC.
- Always scope queries by the current user and escape output.

Next course: **JPA**, to stop writing SQL and mapping code by hand.
