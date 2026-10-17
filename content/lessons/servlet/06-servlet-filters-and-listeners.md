---
title: Servlet Filters and Listeners
slug: servlet-filters-and-listeners
subject: servlet
lesson: 6
difficulty: intermediate
excerpt: Run code before and after every request with filters (authentication, logging, encoding), watch a filter chain block a request in an animation, and react to app and session events with listeners.
---

Some logic applies to **many pages**: checking login, logging requests, setting UTF-8, adding security headers. Copying it into every servlet is a maintenance nightmare. **Filters** wrap requests in one place, and **listeners** react to events like the app starting.

## The airport security analogy

Before boarding, every passenger passes the same checkpoints: ticket check, security scan, passport control. Any checkpoint can stop you; otherwise you move on to the next. Filters are those checkpoints, and the servlet is the plane.

## A filter chain, animated

Switch between "signed in" and "not signed in" and watch `AuthFilter` either pass the request on or stop it:

::viz filter-chain

## Writing a filter

```java
import jakarta.servlet.*;
import jakarta.servlet.annotation.WebFilter;
import jakarta.servlet.http.*;
import java.io.IOException;

@WebFilter(urlPatterns = { "/dashboard/*", "/cart/*", "/api/*" })
public class AuthFilter implements Filter {

    @Override
    public void doFilter(ServletRequest request, ServletResponse response, FilterChain chain)
            throws IOException, ServletException {
        HttpServletRequest req = (HttpServletRequest) request;
        HttpServletResponse resp = (HttpServletResponse) response;

        HttpSession session = req.getSession(false);
        boolean loggedIn = session != null && session.getAttribute("user") != null;

        if (!loggedIn) {
            if (req.getRequestURI().startsWith(req.getContextPath() + "/api/")) {
                resp.sendError(HttpServletResponse.SC_UNAUTHORIZED);        // 401 for APIs
            } else {
                resp.sendRedirect(req.getContextPath() + "/login.html");    // redirect for pages
            }
            return;                                // stop: the servlet never runs
        }
        chain.doFilter(request, response);         // continue to the next filter or the servlet
    }
}
```

The key line is `chain.doFilter(...)`. Call it to continue; skip it to block.

## Code before AND after the servlet

Everything after `chain.doFilter` runs on the way **back**, after the servlet has finished. Perfect for timing:

```java
@WebFilter("/*")
public class TimingFilter implements Filter {
    @Override
    public void doFilter(ServletRequest req, ServletResponse resp, FilterChain chain)
            throws IOException, ServletException {
        long start = System.nanoTime();
        try {
            chain.doFilter(req, resp);                       // the request goes in…
        } finally {
            long ms = (System.nanoTime() - start) / 1_000_000;   // …and comes back out
            HttpServletRequest r = (HttpServletRequest) req;
            r.getServletContext().log(r.getMethod() + " " + r.getRequestURI() + " took " + ms + " ms");
        }
    }
}
```

## Common uses of filters

| Filter | Job |
|---|---|
| Encoding | `req.setCharacterEncoding("UTF-8")` for every request |
| Authentication / authorisation | check login and roles |
| Logging / timing | request logs, slow-request alerts |
| Security headers | `X-Content-Type-Options`, `Content-Security-Policy` |
| CORS | allow other origins to call your API |
| Compression / caching | gzip responses, cache headers |

Spring Security is essentially a long, well-tested chain of servlet filters.

## Filter order

With annotations, the order between filters is **not guaranteed**. When order matters (encoding before auth, logging first), declare them in `web.xml`, where the order of `<filter-mapping>` entries is the order of execution:

```xml
<filter><filter-name>timing</filter-name><filter-class>com.vitalstack.web.TimingFilter</filter-class></filter>
<filter><filter-name>auth</filter-name><filter-class>com.vitalstack.web.AuthFilter</filter-class></filter>
<filter-mapping><filter-name>timing</filter-name><url-pattern>/*</url-pattern></filter-mapping>
<filter-mapping><filter-name>auth</filter-name><url-pattern>/dashboard/*</url-pattern></filter-mapping>
```

## Listeners: react to events

Listeners are notified when something happens in the app's life:

```java
import jakarta.servlet.*;
import jakarta.servlet.annotation.WebListener;
import jakarta.servlet.http.*;
import java.util.concurrent.atomic.AtomicInteger;

@WebListener
public class AppListener implements ServletContextListener, HttpSessionListener {
    private static final AtomicInteger online = new AtomicInteger();

    @Override
    public void contextInitialized(ServletContextEvent e) {
        e.getServletContext().log("App started: create the DB connection pool here");
        e.getServletContext().setAttribute("online", online);
    }

    @Override
    public void contextDestroyed(ServletContextEvent e) {
        e.getServletContext().log("App stopping: close the pool here");
    }

    @Override public void sessionCreated(HttpSessionEvent e) { online.incrementAndGet(); }
    @Override public void sessionDestroyed(HttpSessionEvent e) { online.decrementAndGet(); }
}
```

| Listener | Events |
|---|---|
| `ServletContextListener` | app start / stop |
| `HttpSessionListener` | session created / destroyed |
| `ServletRequestListener` | each request starts / ends |
| `*AttributeListener` | attributes added / removed / replaced |

Spring's `ContextLoaderListener` used exactly this hook to start the Spring container in classic web apps.

## Filter vs servlet vs listener

- **Servlet**: produces the response for specific URLs.
- **Filter**: wraps requests for many URLs; can modify, log or block.
- **Listener**: reacts to lifecycle events; doesn't handle requests directly.

## Common mistakes

- Forgetting `chain.doFilter`: every request hangs or returns an empty page.
- Calling `chain.doFilter` **and** then sending a redirect (response already committed).
- A login filter that also protects `/login.html`, CSS and JS files → infinite redirect loop. Exclude public paths.
- Relying on annotation order between filters.

## Exercise

1. Write an `EncodingFilter` for `/*` that sets UTF-8 on requests and responses.
2. Write a `SecurityHeadersFilter` that adds `X-Content-Type-Options: nosniff` and `Referrer-Policy: same-origin`.
3. Add an admin-only filter for `/admin/*` that checks a `role` session attribute and returns 403 otherwise.
4. Show the number of online users (from the listener) on the dashboard.

::hint Hint for 3 | `if (!"ADMIN".equals(session.getAttribute("role"))) { resp.sendError(403); return; }`

## Interview corner

- **What is a filter?** An object that intercepts requests/responses for URL patterns to perform cross-cutting work (auth, logging, encoding) via `doFilter` and `FilterChain`.
- **How do you stop a request in a filter?** Don't call `chain.doFilter`; send an error or redirect instead.
- **Filter vs interceptor?** Filters are servlet-level (before DispatcherServlet); Spring HandlerInterceptors run inside Spring MVC around controller methods.
- **Name some listeners.** ServletContextListener, HttpSessionListener, ServletRequestListener, attribute listeners.

## Summary

- Filters run before and after servlets; `chain.doFilter` passes control on, skipping it blocks.
- Use filters for authentication, logging, encoding, headers and CORS.
- Listeners react to app, session and request events.

Next: JSP and the MVC pattern for building HTML views.
