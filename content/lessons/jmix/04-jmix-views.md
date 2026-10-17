---
title: Jmix Views - List and Detail Screens
slug: jmix-views
subject: jmix
lesson: 4
difficulty: intermediate
excerpt: Generate list and detail views, understand view XML descriptors and Java controllers, data containers and loaders, data grids with filters and pagination, forms, actions and event handlers.
---

A Jmix **view** is one screen of your application: a list of courses, a course form, a dashboard. Each view has an **XML descriptor** (layout and data) and a **Java controller** (behaviour).

## Watch a request flow through a Jmix app

Switch between opening the list and saving a new record:

::viz jmix-app

## Generate views

Right-click the `Course` entity → *Views → Create View* → **Entity list and detail views**. Studio asks for the fetch plan (which attributes and references to load) and creates four files:

```text
view/course/CourseListView.java        view/course/course-list-view.xml
view/course/CourseDetailView.java      view/course/course-detail-view.xml
```

It also adds a menu item. Restart, and **Courses** appears in the menu with a working table and form.

## The list view

```xml
<view xmlns="http://jmix.io/schema/flowui/view"
      title="msg://courseListView.title"
      focusComponent="coursesDataGrid">
    <data>
        <collection id="coursesDc" class="com.vitalstack.courseadmin.entity.Course">
            <fetchPlan extends="_base">
                <property name="category" fetchPlan="_base"/>
                <property name="instructor" fetchPlan="_base"/>
            </fetchPlan>
            <loader id="coursesDl" readOnly="true">
                <query>
                    <![CDATA[select e from Course e order by e.title]]>
                </query>
            </loader>
        </collection>
    </data>
    <facets>
        <dataLoadCoordinator auto="true"/>
        <urlQueryParameters>
            <genericFilter component="genericFilter"/>
            <pagination component="pagination"/>
        </urlQueryParameters>
    </facets>
    <layout>
        <genericFilter id="genericFilter" dataLoader="coursesDl"/>
        <hbox id="buttonsPanel" classNames="buttons-panel">
            <button id="createButton" action="coursesDataGrid.create"/>
            <button id="editButton" action="coursesDataGrid.edit"/>
            <button id="removeButton" action="coursesDataGrid.remove"/>
            <simplePagination id="pagination" dataLoader="coursesDl"/>
        </hbox>
        <dataGrid id="coursesDataGrid" width="100%" minHeight="20em" dataContainer="coursesDc">
            <actions>
                <action id="create" type="list_create"/>
                <action id="edit" type="list_edit"/>
                <action id="remove" type="list_remove"/>
            </actions>
            <columns>
                <column property="title"/>
                <column property="level"/>
                <column property="category"/>
                <column property="instructor"/>
                <column property="price"/>
            </columns>
        </dataGrid>
    </layout>
</view>
```

```java
@Route(value = "courses", layout = MainView.class)
@ViewController("Course.list")
@ViewDescriptor("course-list-view.xml")
@LookupComponent("coursesDataGrid")
@DialogMode(width = "64em")
public class CourseListView extends StandardListView<Course> {
}
```

The key ideas:

- **Data container** (`coursesDc`): holds the loaded entities; UI components bind to it.
- **Data loader** (`coursesDl`): runs the JPQL query and fills the container, with paging and the filter's conditions added automatically.
- **Fetch plan**: which attributes and references to load **in one go**. Loading `category` and `instructor` here avoids N+1 queries when the grid shows them (the problem you studied in the Hibernate course).
- **Facets**: `dataLoadCoordinator` loads data when the view opens; `urlQueryParameters` puts filter and page into the URL so users can bookmark and share them.
- **Actions**: standard `list_create`, `list_edit`, `list_remove` open the detail view, ask for confirmation, check security and refresh the grid.

## The detail view

```xml
<view xmlns="http://jmix.io/schema/flowui/view"
      title="msg://courseDetailView.title"
      focusComponent="form">
    <data>
        <instance id="courseDc" class="com.vitalstack.courseadmin.entity.Course">
            <fetchPlan extends="_base">
                <property name="category" fetchPlan="_base"/>
                <property name="instructor" fetchPlan="_base"/>
                <property name="lessons" fetchPlan="_base"/>
            </fetchPlan>
            <loader id="courseDl"/>
            <collection id="lessonsDc" property="lessons"/>
        </instance>
    </data>
    <facets>
        <dataLoadCoordinator auto="true"/>
    </facets>
    <actions>
        <action id="saveAction" type="detail_saveClose"/>
        <action id="closeAction" type="detail_close"/>
    </actions>
    <layout>
        <formLayout id="form" dataContainer="courseDc">
            <textField id="titleField" property="title"/>
            <select id="levelField" property="level"/>
            <entityComboBox id="categoryField" property="category" itemsQuery="select c from Category c order by c.name"/>
            <entityPicker id="instructorField" property="instructor">
                <actions>
                    <action id="entityLookup" type="entity_lookup"/>
                    <action id="entityClear" type="entity_clear"/>
                </actions>
            </entityPicker>
            <bigDecimalField id="priceField" property="price"/>
        </formLayout>
        <h4 text="msg://lessons"/>
        <dataGrid id="lessonsDataGrid" dataContainer="lessonsDc" width="100%" height="15em">
            <actions>
                <action id="create" type="list_create"/>
                <action id="edit" type="list_edit"/>
                <action id="remove" type="list_remove"/>
            </actions>
            <columns>
                <column property="position"/>
                <column property="title"/>
                <column property="durationMinutes"/>
            </columns>
        </dataGrid>
        <hbox id="detailActions">
            <button id="saveAndCloseButton" action="saveAction"/>
            <button id="closeButton" action="closeAction"/>
        </hbox>
    </layout>
</view>
```

```java
@Route(value = "courses/:id", layout = MainView.class)
@ViewController("Course.detail")
@ViewDescriptor("course-detail-view.xml")
@EditedEntityContainer("courseDc")
public class CourseDetailView extends StandardDetailView<Course> {

    @ViewComponent
    private JmixBigDecimalField priceField;

    @Subscribe
    public void onInitEntity(final InitEntityEvent<Course> event) {
        event.getEntity().setLevel(CourseLevel.BEGINNER);          // defaults for NEW courses
    }

    @Subscribe("levelField")
    public void onLevelFieldValueChange(final ComponentValueChangeEvent<JmixSelect<CourseLevel>, CourseLevel> event) {
        if (event.getValue() == CourseLevel.ADVANCED && getEditedEntity().getPrice() == null) {
            priceField.setValue(new BigDecimal("999"));            // suggest a price
        }
    }
}
```

- Form fields bind to entity attributes with `property="..."`; validation annotations on the entity are enforced, and the save button stays safe.
- `@Subscribe` methods handle view lifecycle events (`InitEntityEvent`, `BeforeShowEvent`, `BeforeSaveEvent`) and component events.
- `@ViewComponent` injects components declared in the XML by id.
- On save, the **DataContext** collects every changed entity (the course and its lessons) and commits them in one transaction.

## Customising the UI

- **Studio's view designer**: drag components from the palette (text fields, combo boxes, tabs, charts) and edit properties with a live preview.
- **Navigation**: `ViewNavigators` / `DialogWindows` beans open views from code, e.g. a detail view in a dialog.
- **Notifications**: inject `Notifications` and call `notifications.create("Course published").show()`.
- **Themes**: edit CSS in `frontend/themes/<app>/`.

::tip Prefer XML + standard actions for what they do well (CRUD, filters, paging). Write Java only for real behaviour. Generated views take minutes; your time goes into business logic and good UX.

## Exercise

1. Generate list and detail views for `Course`, `Category` and `Instructor`.
2. Add a `level` column filter to the course list using the generic filter's "Add condition".
3. In the course detail view, show a notification after saving (`@Subscribe AfterSaveEvent`).
4. Add a custom button "Duplicate" on the list that copies the selected course with " (copy)" in the title.

::hint Hint for 4 | Add `<button id="duplicateButton" text="Duplicate"/>`, then in the controller `@Subscribe(id = "duplicateButton", subject = "clickListener")` to create a copy with `dataManager.create(Course.class)`, set its fields, `dataManager.save(copy)` and reload the loader `coursesDl.load()`.

## Interview corner

- **What makes up a Jmix view?** An XML descriptor (data components, layout, actions, facets) and a Java controller.
- **Data container vs data loader?** Containers hold entities for binding; loaders fetch them with JPQL into containers.
- **What is a fetch plan?** A description of which attributes and references to load, preventing lazy-loading problems and N+1 queries.
- **How are changes saved?** The view's DataContext tracks modified entities and commits them together.

## Summary

- Studio generates list and detail views from entities.
- XML defines data (containers, loaders, fetch plans), facets and layout; Java controllers add behaviour with `@Subscribe`.
- Standard actions give CRUD with security checks; the DataContext saves everything in one transaction.

Next: data access in Java with DataManager.
