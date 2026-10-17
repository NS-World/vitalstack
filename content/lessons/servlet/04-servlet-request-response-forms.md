---
title: Servlet Requests, Responses and Forms
slug: servlet-request-response-forms
subject: servlet
lesson: 4
difficulty: intermediate
excerpt: Handle HTML forms with doPost, validate input, set status codes and headers, return JSON, and understand forward vs redirect and the Post/Redirect/Get pattern.
---

Real web apps take input: sign-up forms, search boxes, checkout pages. In this lesson you'll process forms safely and control exactly what goes back to the browser.

## A form and its servlet

`src/main/webapp/register.html`:

```html
<form action="register" method="post">
  <label>Name <input name="name" required></label>
  <label>Email <input name="email" type="email" required></label>
  <label>Course
    <select name="course">
      <option>Java</option><option>Spring Boot</option><option>React</option>
    </select>
  </label>
  <button>Register</button>
</form>
```

The servlet:

```java
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;
import java.io.IOException;
import java.util.*;

@WebServlet("/register")
public class RegisterServlet extends HttpServlet {

    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws IOException {
        req.setCharacterEncoding("UTF-8");                       // before reading parameters
        String name = trim(req.getParameter("name"));
        String email = trim(req.getParameter("email"));
        String course = trim(req.getParameter("course"));

        List<String> errors = new ArrayList<>();
        if (name.isEmpty()) errors.add("Name is required");
        if (!email.matches("^[^@\\s]+@[^@\\s]+\\.[^@\\s]+$")) errors.add("Email looks invalid");
        if (!Set.of("Java", "Spring Boot", "React").contains(course)) errors.add("Unknown course");

        if (!errors.isEmpty()) {
            resp.setStatus(HttpServletResponse.SC_BAD_REQUEST);   // 400
            resp.setContentType("text/plain;charset=UTF-8");
            resp.getWriter().println(String.join("\n", errors));
            return;
        }

        // ... save the registration (JDBC/JPA later) ...
        resp.sendRedirect(req.getContextPath() + "/thanks.html");  // Post/Redirect/Get
    }

    private static String trim(String s) { return s == null ? "" : s.trim(); }
}
```

::tip **Never trust the browser.** HTML `required` and `type="email"` are only for convenience: anyone can send a request with curl or Postman. Always validate on the server.

## Post/Redirect/Get (PRG)

If a POST returns a page directly and the user presses **Refresh**, the browser re-submits the form: duplicate registrations or double payments! The fix:

1. **POST** the form.
2. Server processes it and responds with a **redirect** (302/303) to a result page.
3. Browser makes a fresh **GET** to that page. Refreshing just repeats the harmless GET.

## Redirect vs forward

| | `resp.sendRedirect(url)` | `req.getRequestDispatcher(path).forward(req, resp)` |
|---|---|---|
| Who moves? | the **browser** makes a new request | the **server** passes the same request internally |
| URL in address bar | changes | stays the same |
| Request attributes | lost (new request) | kept |
| Can go to other sites | yes | no, same app only |
| Typical use | after POST (PRG), login → dashboard | servlet → JSP to render a view (MVC) |

```java
req.setAttribute("courses", List.of("Java", "Spring Boot"));
req.getRequestDispatcher("/WEB-INF/views/courses.jsp").forward(req, resp);
```

## Status codes and headers

```java
resp.setStatus(201);                                      // Created
resp.sendError(404, "Course not found");                  // container error page
resp.setHeader("Cache-Control", "no-store");
resp.addHeader("X-App", "VitalStack");
resp.setContentType("application/json;charset=UTF-8");
```

Set the status and headers **before** writing the body. Once the response is "committed" (data sent), you can't change them.

## Returning JSON (a mini REST API)

```java
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;
import java.io.IOException;

@WebServlet("/api/courses")
public class CourseApiServlet extends HttpServlet {
    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws IOException {
        resp.setContentType("application/json;charset=UTF-8");
        resp.getWriter().write("""
            [{"id":1,"title":"Java","lessons":16},
             {"id":2,"title":"Spring Boot","lessons":10}]
            """);
    }
}
```

In real apps, use a library like **Jackson** (`new ObjectMapper().writeValueAsString(list)`) to convert objects to JSON. Spring Boot does this automatically.

## Reading a JSON request body

```java
String body = new String(req.getInputStream().readAllBytes(), java.nio.charset.StandardCharsets.UTF_8);
// then parse with Jackson: mapper.readValue(body, Course.class)
```

## File uploads

```java
import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.MultipartConfig;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.*;
import java.io.IOException;

@WebServlet("/upload")
@MultipartConfig(maxFileSize = 2 * 1024 * 1024)          // 2 MB
public class UploadServlet extends HttpServlet {
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws IOException, ServletException {
        Part file = req.getPart("photo");                 // <input type="file" name="photo">
        String name = java.nio.file.Path.of(file.getSubmittedFileName()).getFileName().toString();
        file.write("/data/uploads/" + System.currentTimeMillis() + "-" + name);
        resp.getWriter().println("Uploaded " + name);
    }
}
```

The form needs `enctype="multipart/form-data"`. Never trust the file name or type: check extensions and sizes, and store uploads outside the web root.

## Common mistakes

- Reading parameters before `setCharacterEncoding("UTF-8")` → garbled Hindi or emoji.
- Writing the body, then calling `sendRedirect` → `IllegalStateException: response already committed`.
- Forgetting `return` after sending an error, so the code continues.
- Returning a page directly after POST (duplicate submissions on refresh).

## Exercise

1. Build a contact form (name, email, message) with server-side validation and PRG to a thank-you page.
2. Make `/api/courses?id=2` return only that course, or a 404 JSON error `{"error":"Not found"}`.
3. Make a BMI calculator form that shows the result on the same page using forward and request attributes.

::hint Hint for 2 | Set `resp.setStatus(404)` and write the JSON error body yourself; `sendError` would show the container's HTML error page.

## Interview corner

- **forward vs sendRedirect?** Forward is server-side within the same request (URL unchanged); redirect tells the browser to make a new request (URL changes).
- **What is the PRG pattern?** Post/Redirect/Get: redirect after a successful POST to prevent duplicate submissions on refresh.
- **GET vs POST for forms?** GET for searches/filters (bookmarkable, in URL); POST for actions that change data or contain sensitive data.
- **What does "response committed" mean?** Headers/body have been sent to the client; status and headers can no longer change.

## Summary

- Read form data with `getParameter` (after setting UTF-8), validate on the server.
- Use PRG after POST; forward to JSPs for views; redirect to change URL.
- Set status/headers before the body; return JSON with the right content type.

Next: sessions and cookies, giving stateless HTTP a memory.
