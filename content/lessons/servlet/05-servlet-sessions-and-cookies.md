---
title: Sessions and Cookies in Servlets
slug: servlet-sessions-and-cookies
subject: servlet
lesson: 5
difficulty: intermediate
excerpt: Remember users across requests with HttpSession and cookies, build a login and logout flow, a shopping cart, and secure sessions with HttpOnly, Secure, SameSite and session-fixation protection.
---

HTTP forgets you after every request. Yet websites keep you signed in and remember your cart. They do it with **cookies** and **sessions**.

## The cloakroom analogy

At a wedding hall cloakroom, you hand over your bag and get a **token number**. The bag stays inside; you only carry the token. Next time you show the token, they find your bag.

- The **session** is your bag, stored on the **server**.
- The **cookie** `JSESSIONID=8F2A…` is the token, stored in your **browser** and sent with every request.

## Cookies

A cookie is a small name=value pair the server asks the browser to store and send back:

```java
// set a cookie
Cookie theme = new Cookie("theme", "dark");
theme.setMaxAge(60 * 60 * 24 * 365);     // seconds: 1 year (0 deletes it, -1 = until browser closes)
theme.setPath("/");
theme.setHttpOnly(true);                 // JavaScript can't read it
resp.addCookie(theme);

// read cookies
String current = "light";
if (req.getCookies() != null) {
    for (Cookie c : req.getCookies()) {
        if ("theme".equals(c.getName())) current = c.getValue();
    }
}
```

Cookies are limited (~4 KB each) and **visible and editable by users**. Never store passwords, roles or prices in cookies. Use them for preferences and the session id.

## HttpSession

The container creates a session on demand and handles the `JSESSIONID` cookie for you:

```java
HttpSession session = req.getSession();          // get, or create if none
session.setAttribute("user", "asha@example.com");
String user = (String) session.getAttribute("user");
session.removeAttribute("user");
session.invalidate();                            // destroy the whole session (logout)

HttpSession existing = req.getSession(false);    // get only; null if there is none
```

Session data lives in server memory, so it can hold objects (a cart, a user object), and users can't tamper with it.

## A login and logout flow

```java
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;
import java.io.IOException;

@WebServlet("/login")
public class LoginServlet extends HttpServlet {

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws IOException {
        String email = req.getParameter("email");
        String password = req.getParameter("password");

        if (!checkPassword(email, password)) {                 // look up the user, compare a password HASH
            resp.sendRedirect(req.getContextPath() + "/login.html?error=1");
            return;
        }
        HttpSession old = req.getSession(false);
        if (old != null) old.invalidate();                     // fresh session after login
        HttpSession session = req.getSession(true);
        session.setAttribute("user", email);
        session.setMaxInactiveInterval(30 * 60);               // 30 minutes idle timeout
        resp.sendRedirect(req.getContextPath() + "/dashboard");
    }

    private boolean checkPassword(String email, String password) {
        // demo only: real apps load the user and verify a BCrypt hash
        return "asha@example.com".equals(email) && "secret123".equals(password);
    }
}

@WebServlet("/logout")
class LogoutServlet extends HttpServlet {
    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws IOException {
        HttpSession s = req.getSession(false);
        if (s != null) s.invalidate();
        resp.sendRedirect(req.getContextPath() + "/login.html");
    }
}
```

Every protected servlet can now check:

```java
HttpSession s = req.getSession(false);
if (s == null || s.getAttribute("user") == null) {
    resp.sendRedirect(req.getContextPath() + "/login.html");
    return;
}
```

Repeating that check in every servlet is messy. Next lesson, a **filter** does it once for all pages.

## A session shopping cart

```java
@SuppressWarnings("unchecked")
List<String> cart = (List<String>) session.getAttribute("cart");
if (cart == null) {
    cart = new ArrayList<>();
    session.setAttribute("cart", cart);
}
cart.add(req.getParameter("product"));
```

## Session security checklist

- **New session on login** (`invalidate` then create, or `req.changeSessionId()`): prevents **session fixation**, where an attacker plants a known session id before you log in.
- **HttpOnly** cookies: JavaScript (and XSS attacks) can't steal them. Tomcat sets HttpOnly on JSESSIONID by default.
- **Secure** cookies: only sent over HTTPS. Always use HTTPS in production.
- **SameSite=Lax/Strict**: reduces CSRF attacks (other sites making requests with your cookies).
- **Timeouts**: expire idle sessions (`setMaxInactiveInterval`, or `<session-timeout>` in web.xml).
- **Logout with POST** and `invalidate()`.
- **Hash passwords** with BCrypt or Argon2. Never store plain text.

```xml
<!-- WEB-INF/web.xml -->
<session-config>
  <session-timeout>30</session-timeout>
  <cookie-config>
    <http-only>true</http-only>
    <secure>true</secure>
  </cookie-config>
  <tracking-mode>COOKIE</tracking-mode>   <!-- never put the session id in URLs -->
</session-config>
```

## Sessions at scale

Sessions live in one server's memory. With several servers behind a load balancer, you need **sticky sessions**, a shared session store (Redis via Spring Session), or **stateless tokens** like JWT (Spring Boot course). Many modern APIs are stateless for this reason.

::tip Keep sessions small: store a user id, not the whole user with their order history. Every session costs server memory.

## Common mistakes

- `req.getSession()` on every request (creates sessions even for anonymous visitors). Use `getSession(false)` to only check.
- Storing non-serialisable or huge objects in the session.
- Logout via a GET link (can be triggered by other sites).
- Keeping the same session id before and after login.

## Exercise

1. Build login, dashboard and logout servlets with the session check.
2. Remember the user's preferred language in a cookie for 30 days and greet them in it.
3. Build a session cart: `/cart/add?product=Pen`, `/cart` (list), `/cart/clear`.
4. Show "You've visited this page N times in this session" using a session attribute.

::hint Hint for 4 | `Integer n = (Integer) session.getAttribute("visits"); n = (n == null) ? 1 : n + 1; session.setAttribute("visits", n);`

## Interview corner

- **Session vs cookie?** Cookies live in the browser and are sent with requests; sessions live on the server, identified by a cookie (JSESSIONID).
- **How does the server identify a session?** By the session id in the JSESSIONID cookie (or URL rewriting if cookies are off).
- **What is session fixation and how do you prevent it?** An attacker forces a known session id on a victim; prevent it by issuing a new session id at login.
- **How do you end a session?** `session.invalidate()`, or let it time out.

## Summary

- Cookies are small browser-side values; sessions are server-side storage keyed by the JSESSIONID cookie.
- `getSession()` creates; `getSession(false)` only checks; `invalidate()` logs out.
- Secure sessions: new id on login, HttpOnly, Secure, SameSite, timeouts, HTTPS.

Next: filters and listeners, code that runs around every request.
