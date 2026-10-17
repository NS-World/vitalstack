---
title: Spring Security with JWT in Spring Boot
slug: spring-boot-security-jwt
subject: spring-boot
lesson: 6
difficulty: advanced
excerpt: Secure a Spring Boot REST API - how the security filter chain works, password hashing with BCrypt, a login endpoint that issues JWT tokens, protecting routes by role, and testing with a Bearer token.
---

Right now anyone can create or delete courses. Let's add **authentication** (who are you?) and **authorisation** (what are you allowed to do?) with **Spring Security** and **JWT** tokens, the standard approach for REST APIs used by React, Angular and mobile apps.

## How Spring Security works

Spring Security is a chain of **servlet filters** (remember the Servlet course) that runs before your controllers:

```text
Request ──▶ [CORS] ──▶ [Bearer token filter] ──▶ [Authorization filter] ──▶ Controller
                              │                          │
                     reads "Authorization:        checks the URL's rules
                      Bearer eyJ…", verifies       (permitAll, hasRole…)
                      the signature, sets the      → 401 / 403 if not allowed
                      current user
```

Just adding `spring-boot-starter-security` locks every endpoint (with a generated password printed in the log). You then configure the rules you want.

## What is a JWT?

A **JSON Web Token** is a signed string with three parts: `header.payload.signature`.

```text
eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJhc2hhIiwicm9sZXMiOiJBRE1JTiIsImV4cCI6MTc2MDAwMDAwMH0.k3V…
```

The payload (base64, **not encrypted**, anyone can read it) holds **claims**: `sub` (username), `roles`, `exp` (expiry). The server signs it with a secret key; if anyone changes the payload, the signature no longer matches. The server doesn't store sessions: each request carries its token. That's **stateless** authentication, ideal for APIs and multiple servers.

## Dependencies

```xml
<dependency>
  <groupId>org.springframework.boot</groupId>
  <artifactId>spring-boot-starter-security</artifactId>
</dependency>
<dependency>
  <groupId>org.springframework.boot</groupId>
  <artifactId>spring-boot-starter-oauth2-resource-server</artifactId>   <!-- JWT encode/decode (Nimbus) -->
</dependency>
```

## Security configuration

```java
package com.vitalstack.secure;

import com.nimbusds.jose.jwk.source.ImmutableSecret;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.context.annotation.*;
import org.springframework.http.HttpMethod;
import org.springframework.security.authentication.*;
import org.springframework.security.authentication.dao.DaoAuthenticationProvider;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.http.SessionCreationPolicy;
import org.springframework.security.core.userdetails.*;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.security.oauth2.jose.jws.MacAlgorithm;
import org.springframework.security.oauth2.jwt.*;
import org.springframework.security.oauth2.server.resource.authentication.*;
import org.springframework.security.provisioning.InMemoryUserDetailsManager;
import org.springframework.security.web.SecurityFilterChain;
import javax.crypto.spec.SecretKeySpec;

@Configuration
public class SecurityConfig {

    @Bean
    SecurityFilterChain api(HttpSecurity http) throws Exception {
        return http
            .csrf(csrf -> csrf.disable())                                    // stateless API with tokens: no CSRF cookie risk
            .sessionManagement(s -> s.sessionCreationPolicy(SessionCreationPolicy.STATELESS))
            .authorizeHttpRequests(auth -> auth
                .requestMatchers(HttpMethod.POST, "/api/auth/login").permitAll()
                .requestMatchers(HttpMethod.GET, "/api/courses/**").permitAll()      // anyone can browse
                .requestMatchers("/api/courses/**").hasRole("ADMIN")                 // only admins change courses
                .anyRequest().authenticated())
            .oauth2ResourceServer(o -> o.jwt(jwt -> jwt.jwtAuthenticationConverter(rolesFromToken())))
            .build();
    }

    // Demo users. In a real app, load users from the database (a UserDetailsService backed by a repository).
    @Bean
    UserDetailsService users(PasswordEncoder encoder) {
        return new InMemoryUserDetailsManager(
            User.withUsername("asha").password(encoder.encode("student123")).roles("STUDENT").build(),
            User.withUsername("admin").password(encoder.encode("admin123")).roles("ADMIN").build());
    }

    @Bean
    PasswordEncoder passwordEncoder() { return new BCryptPasswordEncoder(); }      // never store plain passwords

    @Bean
    AuthenticationManager authManager(UserDetailsService users, PasswordEncoder encoder) {
        DaoAuthenticationProvider provider = new DaoAuthenticationProvider();
        provider.setUserDetailsService(users);
        provider.setPasswordEncoder(encoder);
        return new ProviderManager(provider);
    }

    @Bean
    SecretKeySpec jwtKey(@Value("${app.jwt.secret}") String secret) {
        return new SecretKeySpec(secret.getBytes(), "HmacSHA256");
    }

    @Bean
    JwtEncoder jwtEncoder(SecretKeySpec key) { return new NimbusJwtEncoder(new ImmutableSecret<>(key)); }

    @Bean
    JwtDecoder jwtDecoder(SecretKeySpec key) {
        return NimbusJwtDecoder.withSecretKey(key).macAlgorithm(MacAlgorithm.HS256).build();
    }

    private JwtAuthenticationConverter rolesFromToken() {
        JwtGrantedAuthoritiesConverter roles = new JwtGrantedAuthoritiesConverter();
        roles.setAuthoritiesClaimName("roles");          // read the "roles" claim…
        roles.setAuthorityPrefix("ROLE_");               // …so hasRole("ADMIN") matches "ADMIN"
        JwtAuthenticationConverter converter = new JwtAuthenticationConverter();
        converter.setJwtGrantedAuthoritiesConverter(roles);
        return converter;
    }
}
```

## The login endpoint

```java
package com.vitalstack.secure;

import org.springframework.security.authentication.*;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.oauth2.jose.jws.MacAlgorithm;
import org.springframework.security.oauth2.jwt.*;
import org.springframework.web.bind.annotation.*;
import java.time.Instant;
import java.time.temporal.ChronoUnit;
import java.util.Map;

@RestController
@RequestMapping("/api/auth")
public class AuthController {
    public record LoginRequest(String username, String password) { }

    private final AuthenticationManager auth;
    private final JwtEncoder encoder;

    public AuthController(AuthenticationManager auth, JwtEncoder encoder) { this.auth = auth; this.encoder = encoder; }

    @PostMapping("/login")
    public Map<String, String> login(@RequestBody LoginRequest req) {
        Authentication user = auth.authenticate(
                new UsernamePasswordAuthenticationToken(req.username(), req.password()));   // throws if wrong → 401
        String roles = user.getAuthorities().stream()
                .map(GrantedAuthority::getAuthority).map(a -> a.replace("ROLE_", ""))
                .reduce((a, b) -> a + " " + b).orElse("");
        JwtClaimsSet claims = JwtClaimsSet.builder()
                .subject(user.getName())
                .claim("roles", roles)
                .issuedAt(Instant.now())
                .expiresAt(Instant.now().plus(1, ChronoUnit.HOURS))           // short-lived tokens
                .build();
        String token = encoder.encode(JwtEncoderParameters.from(
                JwsHeader.with(MacAlgorithm.HS256).build(), claims)).getTokenValue();
        return Map.of("token", token);
    }
}
```

## Protected endpoints

```java
package com.vitalstack.secure;

import org.springframework.http.HttpStatus;
import org.springframework.security.core.annotation.AuthenticationPrincipal;
import org.springframework.security.oauth2.jwt.Jwt;
import org.springframework.web.bind.annotation.*;
import java.util.List;

@RestController
public class SecureController {

    @GetMapping("/api/courses")
    public List<String> courses() { return List.of("Java Basics", "Spring Boot"); }          // public

    @DeleteMapping("/api/courses/{id}")
    @ResponseStatus(HttpStatus.NO_CONTENT)
    public void delete(@PathVariable long id) { }                                           // ADMIN only

    public record Me(String username, String roles) { }

    @GetMapping("/api/me")
    public Me me(@AuthenticationPrincipal Jwt jwt) {                                       // any logged-in user
        return new Me(jwt.getSubject(), jwt.getClaimAsString("roles"));
    }
}
```

```java
package com.vitalstack.secure;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

@SpringBootApplication
public class SecureApiApplication {
    public static void main(String[] args) { SpringApplication.run(SecureApiApplication.class, args); }
}
```

```ini
# application.properties
# at least 32 random characters; in production: app.jwt.secret=${JWT_SECRET}
app.jwt.secret=change-me-to-a-long-random-secret-of-32-chars-or-more
```

## Trying it

```text
GET    /api/courses                                → 200 ["Java Basics","Spring Boot"]
GET    /api/me                                     → 401 (no token)
POST   /api/auth/login {"username":"asha","password":"wrong"}       → 401
POST   /api/auth/login {"username":"asha","password":"student123"}  → 200 {"token":"eyJhbGciOiJIUzI1NiJ9…"}
GET    /api/me   Authorization: Bearer <asha token>                → 200 {"username":"asha","roles":"STUDENT"}
DELETE /api/courses/1   Authorization: Bearer <asha token>         → 403 Forbidden (not an admin)
DELETE /api/courses/1   Authorization: Bearer <admin token>        → 204
```

The front end stores the token after login and sends `Authorization: Bearer <token>` with each request (you'll do this in the React and Angular courses).

## Method-level security

Add `@EnableMethodSecurity` to a configuration class and protect service methods directly:

```java
@PreAuthorize("hasRole('ADMIN')")
public void deleteCourse(long id) { ... }

@PreAuthorize("#studentId == authentication.name or hasRole('ADMIN')")
public Progress progressOf(String studentId) { ... }
```

## Security checklist

- ✅ **BCrypt** (or Argon2) for passwords; never store or log plain passwords.
- ✅ Secret from an **environment variable**, at least 256 bits; rotate if leaked.
- ✅ **Short-lived** access tokens (15-60 min) plus refresh tokens for long sessions.
- ✅ **HTTPS** always: tokens in plain HTTP can be stolen.
- ✅ Configure **CORS** for your front-end origin only (`http.cors(...)` with a `CorsConfigurationSource`).
- ✅ Rate-limit the login endpoint.
- ❌ Don't put sensitive data in the JWT payload: it's readable by anyone.

::tip For bigger systems, don't build login yourself: use an identity provider (Keycloak, Auth0, Okta, AWS Cognito, Google). Your Spring Boot API then only validates their tokens with `spring.security.oauth2.resourceserver.jwt.issuer-uri`, a few lines of configuration.

## Exercise

1. Add an INSTRUCTOR role that can create courses (POST) but not delete them.
2. Change the token lifetime to 15 minutes and add the user's email as a claim.
3. Store users in a database table with a `UserDetailsService` that loads them through a Spring Data repository.
4. Configure CORS to allow `http://localhost:5173` (a Vite React app).

::hint Hint for 1 | `.requestMatchers(HttpMethod.POST, "/api/courses/**").hasAnyRole("ADMIN", "INSTRUCTOR")` placed **before** the general `/api/courses/**` admin rule (rules match top to bottom).

## Interview corner

- **Authentication vs authorisation?** Proving identity vs checking permissions.
- **How does Spring Security work?** A chain of servlet filters (SecurityFilterChain) that authenticate requests and enforce access rules before controllers.
- **What is a JWT and why stateless?** A signed token carrying claims; the server verifies the signature instead of storing sessions.
- **401 vs 403?** 401: not authenticated (missing/invalid token); 403: authenticated but not allowed.
- **Why disable CSRF for token APIs?** CSRF exploits cookies sent automatically; Bearer tokens in headers aren't sent automatically by browsers.

## Summary

- Spring Security is a filter chain; configure it with a `SecurityFilterChain` bean.
- Hash passwords with BCrypt; issue short-lived signed JWTs at login.
- Protect URLs with `permitAll`/`hasRole`, and methods with `@PreAuthorize`.

Next: testing Spring Boot applications.
