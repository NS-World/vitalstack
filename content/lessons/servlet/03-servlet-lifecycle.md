---
title: The Servlet Lifecycle
slug: servlet-lifecycle
subject: servlet
lesson: 3
difficulty: intermediate
excerpt: Watch init, service, doGet/doPost and destroy in an animation, learn why one servlet object serves many threads, load-on-startup, init parameters and thread-safety rules.
---

A servlet is created by the container, used for many requests, and finally destroyed. This **lifecycle** is a classic interview question, and understanding it prevents serious bugs.

## The lifecycle, animated

Change the list of requests (try `GET, POST, GET, GET`) and watch which methods run:

::viz servlet-lifecycle {"reqs": ["GET", "GET", "POST"]}

1. **Load and instantiate:** the container loads the class and creates **one** object (on the first request, or at startup).
2. **`init()`**: called **once**. Open resources, read configuration.
3. **`service()`**: called for **every** request. `HttpServlet.service` looks at the HTTP method and calls `doGet`, `doPost`, `doPut`, `doDelete`…
4. **`destroy()`**: called **once** when the app stops or is redeployed. Release resources.

```java
import jakarta.servlet.ServletConfig;
import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebInitParam;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;
import java.io.IOException;
import java.util.concurrent.atomic.AtomicInteger;

@WebServlet(urlPatterns = "/counter", loadOnStartup = 1,
            initParams = @WebInitParam(name = "greeting", value = "Welcome"))
public class CounterServlet extends HttpServlet {
    private String greeting;
    private final AtomicInteger hits = new AtomicInteger();   // thread-safe counter

    @Override
    public void init(ServletConfig config) throws ServletException {
        super.init(config);                                    // keep the config available
        greeting = config.getInitParameter("greeting");
        log("CounterServlet initialised");
    }

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws IOException {
        resp.setContentType("text/plain;charset=UTF-8");
        resp.getWriter().println(greeting + "! You are visitor #" + hits.incrementAndGet());
    }

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws IOException {
        hits.set(0);
        resp.getWriter().println("Counter reset");
    }

    @Override
    public void destroy() {
        log("CounterServlet destroyed after " + hits.get() + " hits");
    }
}
```

- `loadOnStartup = 1` creates the servlet when the app starts instead of on the first request (no slow first hit). Lower numbers load first.
- Init parameters configure a servlet without changing code.

## One object, many threads

This is the most important point: the container creates **one instance** of your servlet and calls `service()` from **many threads at the same time**, one per concurrent request.

```text
Request A ──thread-1──┐
Request B ──thread-2──┼──▶ the SAME CounterServlet object
Request C ──thread-3──┘
```

So **instance fields are shared by all users**. This is a bug:

```java
@WebServlet("/profile")
public class ProfileServlet extends HttpServlet {
    private String currentUser;     // ❌ shared by everyone!

    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws IOException {
        currentUser = req.getParameter("user");
        // ... another request may overwrite currentUser right here ...
        resp.getWriter().println("Profile of " + currentUser);   // may show someone else's name!
    }
}
```

Rules:

- Keep per-request data in **local variables** (each thread has its own stack).
- Per-user data goes in the **session** (lesson 5).
- Shared data must be thread-safe (`AtomicInteger`, `ConcurrentHashMap`) or immutable.

::tip The same rule applies to Spring: controllers and services are singletons too. Never store request or user data in their fields.

## The ServletContext: app-wide data

Every web app has one `ServletContext`, shared by all servlets:

```java
getServletContext().setAttribute("appName", "VitalStack");
String name = (String) getServletContext().getAttribute("appName");
String version = getServletContext().getInitParameter("version");   // from web.xml <context-param>
```

Use it for app-wide settings and shared resources (like a connection pool).

## Lifecycle order with filters and listeners

When the app starts: `ServletContextListener.contextInitialized` → filters' `init` → servlets with `loadOnStartup`. When it stops, the reverse: servlets' `destroy` → filters' `destroy` → `contextDestroyed`. You'll meet filters and listeners in lesson 6.

## Common mistakes

- Overriding `init(ServletConfig)` without calling `super.init(config)`, then `getServletConfig()` returns null. (Or override the no-argument `init()` instead.)
- Mutable instance fields used per request.
- Heavy work in `doGet` that should be done once in `init`.
- Overriding `service()` and forgetting the `doXxx` dispatch.

## Exercise

1. Add `log()` calls to `init`, `doGet` and `destroy`, deploy, make some requests, then stop Tomcat. Check the order in the logs.
2. Fix `ProfileServlet` above by using a local variable.
3. Create a servlet that counts hits **per page** using a `ConcurrentHashMap<String, AtomicInteger>` keyed by `req.getParameter("page")`.

::hint Hint for 3 | `hits.computeIfAbsent(page, k -> new AtomicInteger()).incrementAndGet()` is atomic and thread-safe.

## Interview corner

- **Explain the servlet lifecycle.** Load → instantiate → `init()` once → `service()` per request (dispatching to doGet/doPost…) → `destroy()` once.
- **How many servlet instances are created?** One per servlet declaration (per container), shared by all threads.
- **Is a servlet thread-safe?** Not automatically. Avoid mutable instance state or synchronise access.
- **What is load-on-startup?** It makes the container initialise the servlet at application startup, in the given order.
- **ServletConfig vs ServletContext?** Config is per servlet (init params); context is per web app (shared attributes).

## Summary

- `init` once, `service`/`doXxx` per request, `destroy` once.
- One servlet object handles many concurrent threads: no per-request data in fields.
- `ServletConfig` holds per-servlet settings; `ServletContext` is app-wide.

Next: requests, responses, forms, redirects and forwards.
