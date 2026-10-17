## servlet-introduction
Q: What is a servlet container?
- [x] Software like Tomcat that runs servlets and handles HTTP
- [ ] A Docker container
- [ ] A Java collection
> It maps URLs, manages threads, sessions and the servlet lifecycle.

Q: Which status code means "not found"?
- [ ] 200
- [ ] 500
- [x] 404
> 2xx success, 4xx client errors, 5xx server errors.

Q: New projects on Tomcat 10+ use which package?
- [ ] javax.servlet
- [x] jakarta.servlet
- [ ] java.servlet
> Jakarta EE 9 renamed the packages.

## servlet-setup-first-servlet
Q: Which packaging does a servlet web app use for Tomcat?
- [ ] jar
- [x] war
- [ ] zip
> A Web ARchive is deployed into Tomcat's webapps folder.

Q: Why is the servlet API dependency `provided`?
- [x] Tomcat already supplies it at runtime
- [ ] It's optional
- [ ] It's only for tests
> Bundling it again can cause conflicts.

Q: What does `req.getParameter("name")` return when missing?
- [ ] ""
- [x] null
- [ ] An exception
> Always check for null before using parameters.

## servlet-lifecycle
Q: How many times is init() called?
- [x] Once
- [ ] Once per request
- [ ] Never
> init runs once; service runs for every request.

Q: Why is storing per-user data in a servlet field a bug?
- [x] One servlet instance is shared by many threads
- [ ] Fields are read-only
- [ ] Servlets have no fields
> Use local variables or the session instead.

Q: What does loadOnStartup do?
- [ ] Loads data from a file
- [x] Creates the servlet when the app starts
- [ ] Runs destroy early
> It avoids a slow first request.

## servlet-request-response-forms
Q: Why redirect after a successful POST (PRG)?
- [x] So refreshing doesn't resubmit the form
- [ ] To make it faster
- [ ] Because POST can't return HTML
> The browser then makes a harmless GET.

Q: forward vs sendRedirect?
- [x] Forward stays on the server with the same request; redirect makes the browser send a new request
- [ ] They are the same
- [ ] Redirect keeps request attributes
> The URL changes only with a redirect.

Q: When must you set status and headers?
- [ ] After writing the body
- [x] Before the response is committed
- [ ] Any time
> Once data is sent, headers can't change.

## servlet-sessions-and-cookies
Q: Where is HttpSession data stored?
- [ ] In the browser
- [x] On the server, identified by the JSESSIONID cookie
- [ ] In the URL
> The cookie is just the token; the data stays server-side.

Q: How do you prevent session fixation?
- [x] Create a new session id at login
- [ ] Use longer cookies
- [ ] Disable cookies
> invalidate the old session or call changeSessionId().

Q: What does HttpOnly on a cookie do?
- [x] Stops JavaScript from reading it
- [ ] Sends it only over HTTP
- [ ] Deletes it on close
> It protects session cookies from XSS theft.

## servlet-filters-and-listeners
Q: How does a filter block a request?
- [x] It doesn't call chain.doFilter and sends an error or redirect
- [ ] It throws away the servlet
- [ ] It calls destroy
> Calling chain.doFilter passes the request on.

Q: Code after chain.doFilter runs…
- [ ] Never
- [x] After the servlet finished (on the way back)
- [ ] Before the servlet
> Useful for timing and logging.

Q: Which listener reacts to the app starting?
- [x] ServletContextListener
- [ ] HttpSessionListener
- [ ] Filter
> contextInitialized runs at startup.

## servlet-jsp-jstl-mvc
Q: In servlet + JSP MVC, the JSP is the…
- [ ] Model
- [x] View
- [ ] Controller
> The servlet controls, Java classes model, JSP displays.

Q: Which tag prints escaped text to prevent XSS?
- [x] `<c:out value="${x}"/>`
- [ ] `<%= x %>`
- [ ] `${x}` alone always escapes
> Plain EL in JSP doesn't escape HTML.

Q: Why place JSPs in WEB-INF?
- [x] So browsers can't open them directly
- [ ] To make them faster
- [ ] Tomcat requires it
> Users must go through controllers.

## servlet-project-todo-app
Q: Why use PreparedStatement with ? placeholders?
- [x] It prevents SQL injection
- [ ] It's required by MySQL
- [ ] It makes queries shorter
> Never concatenate user input into SQL.

Q: Why a connection pool like HikariCP?
- [x] Opening connections is slow; a pool reuses them
- [ ] It encrypts data
- [ ] It's needed for JSP
> Connections are borrowed and returned via try-with-resources.

Q: Why include `AND owner = ?` in update/delete queries?
- [x] So users can only change their own tasks
- [ ] For speed
- [ ] MySQL syntax requires it
> Always scope queries to the current user.
