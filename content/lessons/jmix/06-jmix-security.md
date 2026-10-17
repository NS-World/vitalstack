---
title: Jmix Security - Resource Roles and Row-Level Roles
slug: jmix-security
subject: jmix
lesson: 6
difficulty: advanced
excerpt: Control who can do what in a Jmix app - users, resource roles with entity, attribute, view and menu policies, row-level roles with JPQL conditions, assigning roles, and testing permissions.
---

Business apps hold sensitive data: salaries, fees, student records. Jmix security answers two questions for every user: **which operations** can they perform (resource roles), and **which rows** can they see (row-level roles).

## The model

```text
User ──has──▶ Resource roles   (what: entities, attributes, views, menu items, specific actions)
     └─has──▶ Row-level roles  (which rows: JPQL/predicate conditions)
```

Permissions are **additive**: a user gets the union of all their roles. With no roles, a user can do nothing. Every new project includes:

- `FullAccessRole` (`system-full-access`): everything. Only for administrators.
- `UiMinimalRole` (`ui-minimal`): the minimum to log in and see the main view.

## A resource role in code

Roles are interfaces with annotated methods (or designed visually in Studio, or created at runtime in the admin UI):

```java
@ResourceRole(name = "Course manager", code = CourseManagerRole.CODE, scope = SecurityScope.UI)
public interface CourseManagerRole {
    String CODE = "course-manager";

    @EntityPolicy(entityClass = Course.class, actions = EntityPolicyAction.ALL)
    @EntityAttributePolicy(entityClass = Course.class, attributes = "*", action = EntityAttributePolicyAction.MODIFY)
    void course();

    @EntityPolicy(entityClass = Lesson.class, actions = EntityPolicyAction.ALL)
    @EntityAttributePolicy(entityClass = Lesson.class, attributes = "*", action = EntityAttributePolicyAction.MODIFY)
    void lesson();

    @EntityPolicy(entityClass = Category.class, actions = EntityPolicyAction.READ)
    @EntityAttributePolicy(entityClass = Category.class, attributes = "*", action = EntityAttributePolicyAction.VIEW)
    void category();

    @ViewPolicy(viewIds = {"Course.list", "Course.detail", "Lesson.detail"})
    @MenuPolicy(menuIds = {"Course.list"})
    void screens();
}
```

| Policy | Controls |
|---|---|
| `@EntityPolicy` | CRUD operations: `CREATE`, `READ`, `UPDATE`, `DELETE` or `ALL` |
| `@EntityAttributePolicy` | each attribute: `VIEW` (read-only) or `MODIFY`; others hidden |
| `@ViewPolicy` | which views can be opened |
| `@MenuPolicy` | which menu items are visible |
| `@SpecificPolicy` | named custom permissions you check in code (e.g. "course.publish") |

A **read-only auditor** role would grant `READ` on entities and `VIEW` on attributes. To hide salaries, simply don't grant the `salary` attribute: Jmix hides the column in grids and the field in forms automatically, and DataManager doesn't return the value.

## Row-level roles

An instructor should see only **their own** courses:

```java
@RowLevelRole(name = "Own courses only", code = OwnCoursesRole.CODE)
public interface OwnCoursesRole {
    String CODE = "own-courses";

    @JpqlRowLevelPolicy(entityClass = Course.class,
                        where = "{E}.instructor.user.username = :current_user_username")
    void course();
}
```

- `{E}` is replaced by the entity alias in every query on `Course`.
- `:current_user_username` (and other `current_user_...` parameters) are filled with the logged-in user's values.
- The condition is added to **every** DataManager query, including list views, dropdowns and the REST API, so there's no way around it from the UI.

Predicate-based policies (`@PredicateRowLevelPolicy`) can check rows in Java for in-memory checks, for example to block updates to published courses.

## Assigning roles

- **Admin UI**: *Users → select a user → Role assignments*. Assign `ui-minimal`, `course-manager` and `own-courses` to an instructor.
- **Programmatically**: assign roles when creating users through code, or map roles from an external identity provider (LDAP, Keycloak/OIDC) via add-ons.

## Checking permissions in code

Code can check permissions with the `AccessManager` bean. The most common check is a **specific policy**:

```java
SpecificOperationAccessContext ctx = new SpecificOperationAccessContext("course.publish");
accessManager.applyRegisteredConstraints(ctx);
if (!ctx.isPermitted()) throw new AccessDeniedException("specific", "course.publish");
```

and the standard UI actions already respect entity policies (a user without DELETE doesn't see an enabled Remove button).

::tip Design roles around **jobs**, not people: "course manager", "finance viewer", "instructor". Combine small roles per user. And test with a non-admin user before every release: most security bugs are "it worked for admin".

## Common mistakes

- Giving everyone `system-full-access` "for now".
- Forgetting `ui-minimal`, so users can't log in.
- Using `UnconstrainedDataManager` in user-facing services, bypassing row-level rules.
- Not changing the default `admin/admin` password before deployment.

## Exercise

1. Create `CourseManagerRole` and `CourseViewerRole` (read-only) and assign them to two test users.
2. Hide the `price` attribute from viewers and confirm the column disappears.
3. Create the `own-courses` row-level role and check an instructor sees only their courses in the list and in dropdowns.
4. Add a specific policy `course.publish` and enable a Publish button only for users who have it.

::hint Hint for 4 | Declare `@SpecificPolicy(resources = "course.publish")` in a role, then in the view check it with `AccessManager` and `SpecificOperationAccessContext` in `onBeforeShow` and call `publishButton.setEnabled(ctx.isPermitted())`.

## Interview corner

- **Resource vs row-level roles?** Resource roles control operations on entities, attributes, views and menus; row-level roles restrict which records are visible.
- **How do roles combine?** Additively: a user's permissions are the union of their roles.
- **Where are row-level conditions applied?** To every query executed through DataManager for that entity.
- **How do you hide one field?** Don't grant that attribute in an entity attribute policy.

## Summary

- Resource roles: entity, attribute, view, menu and specific policies.
- Row-level roles: JPQL conditions with current-user parameters, applied to every query.
- Assign small job-based roles; always test as a non-admin user.

Next: finish the app and deploy it.
