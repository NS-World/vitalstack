---
title: Jmix Introduction - Full-Stack Business Apps in Java
slug: jmix-introduction
subject: jmix
lesson: 1
difficulty: intermediate
excerpt: What Jmix is, how it builds on Spring Boot, JPA and Vaadin to create complete business applications in Java only, what you get out of the box, and when Jmix is (and isn't) the right tool.
---

Companies run on internal business apps: CRMs, inventory systems, HR portals, loan processing, admin panels. Building them with Spring Boot plus React or Angular means two codebases, two teams and weeks of CRUD screens. **Jmix** lets one Java developer build the **whole** application, back end and UI, in Java.

## What is Jmix?

Jmix is an open-source (Apache 2.0) **full-stack framework** for data-centric business applications. It's built on technologies you already know:

```text
          ┌─────────────────────────────────────────────┐
  UI      │ Vaadin Flow: web UI written in Java + XML    │
          ├─────────────────────────────────────────────┤
  Jmix    │ data access, security, views, add-ons        │
          ├─────────────────────────────────────────────┤
  Base    │ Spring Boot · Spring Security · JPA/EclipseLink│
          └─────────────────────────────────────────────┘
                         Database (PostgreSQL, MySQL, Oracle…)
```

- **Spring Boot** underneath: everything from the Spring courses applies (beans, DI, transactions, configuration).
- **JPA** entities for the data model (Jmix uses EclipseLink as the JPA provider).
- **Vaadin Flow** for the UI: you write views in XML + Java, and the browser UI is generated. No JavaScript framework to learn.
- **Jmix Studio**, an IntelliJ IDEA plugin, generates entities, views, roles and database migrations visually.

## What you get out of the box

| Feature | Jmix gives you |
|---|---|
| Data model | entities with audit fields, soft delete, UUID ids, Liquibase migrations generated from entity changes |
| UI | list and detail views with data grids, sorting, paging, filters, forms, validation, export to Excel |
| Data access | `DataManager` with fetch plans that avoid N+1 queries, data events, optimistic locking |
| Security | users, resource roles (entity/attribute/view permissions), row-level roles, login, password policies |
| Add-ons | reports (Excel/PDF/Word), BPM (BPMN workflows), multitenancy, email, charts, maps, REST API, audit |

## A taste: an entity becomes a working screen

```java
@JmixEntity
@Table(name = "CUSTOMER")
@Entity
public class Customer {
    @JmixGeneratedValue
    @Id
    @Column(name = "ID", nullable = false)
    private UUID id;

    @InstanceName
    @NotNull
    @Column(name = "NAME", nullable = false)
    private String name;

    @Email
    @Column(name = "EMAIL")
    private String email;

    @Version
    @Column(name = "VERSION", nullable = false)
    private Integer version;

    // getters and setters
}
```

From this entity, Studio generates a **list view** (a searchable, sortable, paginated table with filters and Create/Edit/Remove buttons) and a **detail view** (a validated form). Add a security role, and users only see what they're allowed to. Watch the request flow:

::viz jmix-app {"action": "open the customer list"}

## Jmix vs Spring Boot + React/Angular

| | Jmix | Spring Boot + SPA |
|---|---|---|
| Languages | Java (+ XML layouts) | Java + TypeScript/JavaScript |
| CRUD screens | generated in minutes | hand-built per entity |
| Team | full-stack Java developers | back-end + front-end developers |
| UI flexibility | business-app components, themeable | anything you can design |
| Best for | admin panels, ERP/CRM, back-office, internal tools | public consumer sites, highly custom UIs, mobile-first apps |

Jmix is a strong fit for **enterprise and internal applications** with many entities and forms. For a public marketing site or a pixel-perfect consumer app, a SPA framework (React, Angular) is usually better. Many companies use both: Jmix for back-office, React for the customer-facing site.

## Versions

- **Jmix 2.x** (current): Spring Boot 3, Java 17+, Vaadin Flow; UI units are called **views**.
- **Jmix 1.x** (older): Vaadin 8; UI units were called **screens**. Tutorials mentioning "screens" are for 1.x.

## Licensing in short

The Jmix framework and its core add-ons are open source. **Jmix Studio** has a free plan for learning and smaller projects, and paid subscriptions that unlock all visual designers for larger projects and some commercial add-ons. Check jmix.io for current terms.

::tip If you already know Spring Boot and JPA from this site's courses, you're 70% of the way to Jmix. The new parts are the view XML, data containers and loaders, fetch plans, DataManager and the role-based security model, which is exactly what the next lessons cover.

## Exercise

1. List three internal apps at a company you know (or your college) that would suit Jmix.
2. For one of them, write down five entities and their main attributes.
3. Decide which users need which permissions (who can read, create, edit, delete each entity).

::hint Hint for 1 | Library management, hostel room allocation, placement-cell company tracker, leave management, asset inventory, fee collection.

## Interview corner

- **What is Jmix?** A full-stack Java framework for business apps built on Spring Boot, JPA and Vaadin, with data access, security and UI generation.
- **What's a view in Jmix 2?** A UI unit defined by an XML descriptor and a Java controller (list views, detail views, custom views).
- **Which ORM does Jmix use?** JPA with EclipseLink.
- **When would you not choose Jmix?** Public consumer-facing apps with highly custom UI or mobile-first design.

## Summary

- Jmix = Spring Boot + JPA + Vaadin + ready-made data, UI and security features.
- Write the data model, get working list/detail views, add roles.
- Best for data-heavy business and back-office applications.

Next: install Jmix Studio and create your first project.
