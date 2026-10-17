---
title: How Java Web Apps Work (HTTP and Servlets)
slug: servlet-introduction
subject: servlet
lesson: 1
difficulty: beginner
excerpt: Understand HTTP requests and responses, what a web server and servlet container do, where servlets fit under Spring Boot, and why every Java web developer should learn them.
---

When you open a website, your browser sends a **request** and a server sends back a **response**. In Java, the code that answers those requests is a **servlet**. Even if you later use Spring Boot, every request still passes through a servlet under the hood (Spring's `DispatcherServlet`). Understanding servlets makes everything else make sense.

## The restaurant analogy

- **You (the browser)** place an order: "GET me the menu page".
- **The waiter (the web server / servlet container, e.g. Tomcat)** takes the order to the right cook.
- **The cook (your servlet)** prepares the dish (HTML or JSON).
- The waiter brings back the **response** with a status: 200 "here you go", 404 "we don't have that", 500 "the kitchen had a problem".

## HTTP in 2 minutes

A request has a **method**, a **path**, **headers** and sometimes a **body**:

```text
GET /courses?level=beginner HTTP/1.1
Host: vitalstack.co.in
Accept: text/html
Cookie: JSESSIONID=A1B2C3
```

A response has a **status code**, **headers** and a **body**:

```text
HTTP/1.1 200 OK
Content-Type: text/html; charset=UTF-8

<html><body><h1>Beginner courses</h1>...</body></html>
```

### Methods

| Method | Meaning | Example |
|---|---|---|
| `GET` | read something | open a page, list courses |
| `POST` | create / submit | sign-up form, place an order |
| `PUT` | replace / update | update a profile |
| `DELETE` | remove | delete a comment |

### Status codes

| Code | Meaning |
|---|---|
| 200 OK | success |
| 201 Created | something new was created |
| 301/302 | redirect to another URL |
| 400 Bad Request | invalid input |
| 401 / 403 | not signed in / not allowed |
| 404 Not Found | no such page |
| 500 Internal Server Error | bug on the server |

HTTP is **stateless**: each request is independent, and the server doesn't remember you between requests. Sessions and cookies (lesson 5) add memory.

## What is a servlet?

A **servlet** is a Java class that extends `HttpServlet` and handles requests for certain URLs:

```java
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;
import java.io.IOException;

@WebServlet("/hello")
public class HelloServlet extends HttpServlet {
    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws IOException {
        resp.setContentType("text/html;charset=UTF-8");
        resp.getWriter().println("<h1>Hello from a Java servlet!</h1>");
    }
}
```

When a browser visits `/hello`, Tomcat calls `doGet` and sends back whatever you write to the response.

## The servlet container

You don't run servlets with `main`. A **servlet container** does it:

- **Apache Tomcat**: the most popular (Spring Boot embeds it by default).
- **Jetty**, **Undertow**: lighter alternatives.
- Full **Jakarta EE servers**: WildFly, Payara, Open Liberty.

The container listens on a port (8080), parses HTTP, creates `HttpServletRequest`/`HttpServletResponse` objects, picks the servlet by URL, manages threads (one request = one thread from a pool), sessions and security.

## javax vs jakarta

Java EE was handed to the Eclipse Foundation and renamed **Jakarta EE**. From Jakarta EE 9, packages changed from `javax.servlet.*` to `jakarta.servlet.*`.

| Tomcat | Package | Use with |
|---|---|---|
| 9.x | `javax.servlet` | older projects |
| 10.1 / 11 | `jakarta.servlet` | new projects, Spring Boot 3 |

This course uses **Tomcat 10.1+ and `jakarta.servlet`**. If you copy old tutorials that use `javax`, change the imports.

## Where servlets fit today

```text
Browser ──HTTP──▶ Tomcat ──▶ Filters ──▶ Servlet ──▶ your code ──▶ Database
                                         ▲
                     Spring Boot: DispatcherServlet routes to @Controller methods
```

Modern teams rarely write raw servlets for big apps; they use Spring Boot. But interviews ask about the servlet lifecycle, filters, sessions and the request flow, and debugging Spring often means understanding this layer.

::tip Open your browser's DevTools (F12) → Network tab, then reload any page. Click a request to see its method, status, headers and response. Do this on a few sites: it's the fastest way to understand HTTP.

## Exercise

1. In DevTools, find one request with status 200, one 304 and (if you can) a 404. What's in their headers?
2. Write the HTTP request line your browser sends for `https://example.com/search?q=java`.
3. Which HTTP method fits each action: view a product, add to cart, change quantity, remove an item?

::hint Hint for 3 | GET, POST, PUT (or PATCH), DELETE.

## Interview corner

- **What is a servlet?** A Java class that handles HTTP requests inside a servlet container, typically by extending HttpServlet.
- **What is a servlet container?** Software (like Tomcat) that manages servlet lifecycle, maps URLs, handles threads, sessions and the HTTP protocol.
- **Is HTTP stateful?** No, it's stateless; sessions/cookies add state.
- **GET vs POST?** GET reads data, parameters go in the URL, and it should be safe and idempotent; POST sends data in the body to create/change something.

## Summary

- HTTP = request (method, path, headers, body) and response (status, headers, body).
- A servlet handles requests for URLs; a container like Tomcat runs it.
- New projects use `jakarta.servlet` with Tomcat 10.1+.
- Spring Boot's DispatcherServlet is itself a servlet.

Next: set up Tomcat and Maven and run your first servlet.
