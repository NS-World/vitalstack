---
title: Setting Up Tomcat, Maven and Your First Servlet
slug: servlet-setup-first-servlet
subject: servlet
lesson: 2
difficulty: beginner
excerpt: Create a Maven web project, add the Jakarta Servlet API, write a servlet with @WebServlet, package a WAR, deploy it to Tomcat 10 and read request parameters.
---

Let's build and run a real servlet application. You'll use **Maven** (Java's build tool) and **Apache Tomcat 10.1**.

## Step 1: Install the tools

- **JDK 17 or 21** (from the Java course).
- **Maven**: download from maven.apache.org or `brew install maven` / `sudo apt install maven`. Check with `mvn -v`.
- **Tomcat 10.1**: download the zip from tomcat.apache.org and unzip it anywhere. Start it with `bin/startup.sh` (Mac/Linux) or `bin\startup.bat` (Windows), then open `http://localhost:8080`.

IntelliJ IDEA Ultimate and Eclipse can create and deploy web projects for you, but understanding the manual way helps when things break.

## Step 2: Project structure

```text
hello-web/
├── pom.xml
└── src/main/
    ├── java/com/vitalstack/web/HelloServlet.java
    └── webapp/
        ├── index.html
        └── WEB-INF/          (private: not directly accessible by browsers)
```

## Step 3: pom.xml

```xml
<project xmlns="http://maven.apache.org/POM/4.0.0">
  <modelVersion>4.0.0</modelVersion>
  <groupId>com.vitalstack</groupId>
  <artifactId>hello-web</artifactId>
  <version>1.0</version>
  <packaging>war</packaging>

  <properties>
    <maven.compiler.release>17</maven.compiler.release>
    <failOnMissingWebXml>false</failOnMissingWebXml>
  </properties>

  <dependencies>
    <dependency>
      <groupId>jakarta.servlet</groupId>
      <artifactId>jakarta.servlet-api</artifactId>
      <version>6.0.0</version>
      <scope>provided</scope>   <!-- Tomcat already has it at runtime -->
    </dependency>
  </dependencies>

  <build>
    <finalName>hello</finalName>
    <plugins>
      <plugin>
        <groupId>org.apache.maven.plugins</groupId>
        <artifactId>maven-war-plugin</artifactId>
        <version>3.4.0</version>
      </plugin>
    </plugins>
  </build>
</project>
```

- `packaging war`: a **Web ARchive**, the format Tomcat deploys.
- `scope provided`: compile against the API, but don't bundle it (Tomcat provides it).

## Step 4: The servlet

```java
package com.vitalstack.web;

import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.io.PrintWriter;
import java.time.LocalTime;

@WebServlet("/hello")
public class HelloServlet extends HttpServlet {

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws IOException {
        String name = req.getParameter("name");            // from ?name=Asha
        if (name == null || name.isBlank()) name = "friend";

        resp.setContentType("text/html;charset=UTF-8");
        PrintWriter out = resp.getWriter();
        out.println("<!doctype html><html><body>");
        out.println("<h1>Namaste, " + escape(name) + "!</h1>");
        out.println("<p>Server time: " + LocalTime.now().withNano(0) + "</p>");
        out.println("</body></html>");
    }

    private static String escape(String s) {               // never print user input raw (XSS!)
        return s.replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;").replace("\"", "&quot;");
    }
}
```

`@WebServlet("/hello")` maps the URL. (The old way was a `<servlet-mapping>` in `WEB-INF/web.xml`; both still work.)

## Step 5: Build and deploy

```bash
mvn clean package                  # creates target/hello.war
cp target/hello.war /path/to/tomcat/webapps/
```

Tomcat unpacks it automatically. Open:

```text
http://localhost:8080/hello/hello?name=Asha
```

- The first `/hello` is the **context path** (from `hello.war`).
- The second `/hello` is the **servlet mapping**.

To deploy at the root (`http://localhost:8080/hello?name=Asha`), name the file `ROOT.war`.

## Reading request data

```java
String q = req.getParameter("q");                 // one value
String[] tags = req.getParameterValues("tag");     // ?tag=java&tag=web
String agent = req.getHeader("User-Agent");        // a header
String method = req.getMethod();                   // GET, POST…
String path = req.getRequestURI();                 // /hello/hello
String ip = req.getRemoteAddr();
```

`getParameter` returns `null` when the parameter is missing. Always check.

## Logs and errors

- Tomcat logs are in `tomcat/logs/` (`catalina.out` or `catalina.<date>.log`). Look there first when something fails.
- An uncaught exception in a servlet gives the user a **500** page and a stack trace in the log.
- A 404 usually means a wrong context path or mapping.

::tip Faster development: the **Cargo** or **Jetty Maven plugin** can run your app with `mvn jetty:run` without copying WAR files, and IntelliJ Ultimate can redeploy on save. Spring Boot later removes this step entirely with an embedded server.

## Common mistakes

- Mixing `javax.servlet` code with Tomcat 10 (or `jakarta` with Tomcat 9): you get 404s or ClassNotFound errors.
- Forgetting `scope provided` and bundling a servlet API jar into the WAR.
- Visiting `/hello` instead of `/<context>/hello`.
- Printing user input without escaping it: a security hole called XSS.

## Exercise

1. Create the project and get `Namaste, Asha!` in your browser.
2. Add a `/time` servlet that returns the current date and time as plain text (`text/plain`).
3. Add a `/square?n=12` servlet that returns `144`, and a 400 error (`resp.sendError(400, "...")`) when n is missing or not a number.

::hint Hint for 3 | Wrap `Integer.parseInt(req.getParameter("n"))` in try/catch for `NumberFormatException` (null also throws it) and call `resp.sendError(HttpServletResponse.SC_BAD_REQUEST, "n must be a number")`.

## Interview corner

- **What is a WAR file?** A zip of a web application (classes, libraries, web resources, WEB-INF) deployable to a servlet container.
- **What is WEB-INF?** A protected folder; browsers can't request files in it directly. It holds web.xml, classes and libs, and private JSPs.
- **@WebServlet vs web.xml?** Both map servlets to URLs; annotations are simpler, web.xml centralises configuration.
- **What is the context path?** The URL prefix of a web app inside the container, usually the WAR name.

## Summary

- A Maven project with `packaging war` and the `jakarta.servlet-api` (provided) builds a deployable WAR.
- `@WebServlet("/path")` maps URLs; `doGet` writes the response.
- Read input with `getParameter`, escape it before printing.
- Deploy by copying the WAR into Tomcat's `webapps` folder.

Next: the servlet lifecycle, animated.
