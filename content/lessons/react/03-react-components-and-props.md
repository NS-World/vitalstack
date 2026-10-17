---
title: React Components and Props
slug: react-components-and-props
subject: react
lesson: 3
difficulty: beginner
excerpt: Pass data into components with props, destructure them with defaults, use the children prop for layouts, pass functions as props to talk to parents, and split a page into reusable components.
---

Components become powerful when you can **customise** them. **Props** (properties) are the inputs to a component, like arguments to a function.

## Passing and reading props

```jsx
// src/CourseCard.jsx
export default function CourseCard({ title, level, price, lessons = 0, isNew = false }) {
  return (
    <article className="course-card">
      <h3>{title} {isNew && <span className="badge">New</span>}</h3>
      <p>{level} · {lessons} lessons</p>
      <strong>{price === 0 ? 'Free' : `₹${price}`}</strong>
    </article>
  );
}
```

```jsx
// src/App.jsx
import CourseCard from './CourseCard.jsx';

export default function App() {
  return (
    <div className="grid">
      <CourseCard title="Java Basics" level="Beginner" price={499} lessons={16} />
      <CourseCard title="React" level="Beginner" price={0} lessons={12} isNew />
      <CourseCard title="Spring Boot" level="Intermediate" price={799} />
    </div>
  );
}
```

- Strings can be passed as `title="Java"`; anything else needs braces: `price={499}`, `tags={['java', 'oop']}`, `user={{ name: 'Asha' }}`.
- A prop with no value (`isNew`) means `true`.
- Destructuring in the parameter list (`{ title, level }`) is the common style; defaults (`lessons = 0`) handle missing props.

## Props are read-only

A component must **never change its props**. They belong to the parent. If something needs to change over time, it's **state** (next lesson).

```jsx
function Bad({ price }) {
  price = price * 0.9;   // ❌ don't modify props
}
```

Think of a component as a pure function: same props → same output. This makes React apps predictable.

## The children prop

Whatever you put **between** a component's tags arrives as `children`. Perfect for layout and wrapper components:

```jsx
// src/Card.jsx
export default function Card({ title, children }) {
  return (
    <div className="card">
      <h2 className="card-title">{title}</h2>
      <div className="card-body">{children}</div>
    </div>
  );
}
```

```jsx
// src/Profile.jsx
import Card from './Card.jsx';

export default function Profile() {
  return (
    <Card title="Your progress">
      <p>Java: 60% complete</p>
      <button>Continue learning</button>
    </Card>
  );
}
```

## Passing functions: talking to the parent

Data flows down; to send something **up**, the parent passes a function:

```jsx
// src/EnrollButton.jsx
export default function EnrollButton({ course, onEnroll }) {
  return <button onClick={() => onEnroll(course)}>Enroll in {course}</button>;
}
```

```jsx
// src/Catalog.jsx
import EnrollButton from './EnrollButton.jsx';

export default function Catalog({ onMessage }) {
  function handleEnroll(course) {
    onMessage(`Enrolled in ${course}!`);
  }
  return (
    <div>
      <EnrollButton course="Java" onEnroll={handleEnroll} />
      <EnrollButton course="React" onEnroll={handleEnroll} />
    </div>
  );
}
```

Name callback props `onSomething` (`onEnroll`, `onClose`, `onChange`), and handler functions `handleSomething`.

## Splitting a page into components

When should you create a new component?

- A piece is **repeated** (cards, list items, buttons).
- A piece has its **own job** (search box, navbar, modal).
- A file is getting **too long** (more than ~150 lines is a hint).

One component per file, named like the component (`CourseCard.jsx`).

## Passing many props: spread

```jsx
const course = { title: 'SQL', level: 'Beginner', price: 299, lessons: 15 };
<CourseCard {...course} />          // same as title={course.title} level={…} …
```

Handy, but explicit props are easier to read. Use spread sparingly.

::tip If you pass a prop through three or four components that don't use it themselves, just to reach a deep child ("prop drilling"), consider composition with `children`, or React Context (lesson 9).

## Exercise

1. Create a `UserBadge` component with `name`, `role` and `avatarUrl` props, and a default avatar when none is given.
2. Create a `Modal` component that shows `title`, `children` and a close button that calls `onClose`.
3. Build a `ProductCard` that shows a strike-through original price when a `discountPercent` prop is given.
4. Render three `CourseCard`s from an array (preview of lesson 6): `courses.map(c => <CourseCard key={c.title} {...c} />)`.

::hint Hint for 3 | `const finalPrice = discountPercent ? Math.round(price * (100 - discountPercent) / 100) : price;` and show `<s>₹{price}</s>` only when `discountPercent` is set.

## Interview corner

- **What are props?** Read-only inputs passed from a parent to a child component.
- **Props vs state?** Props come from the parent and can't be changed by the child; state is owned and updated by the component itself.
- **What is the children prop?** The content nested between a component's opening and closing tags.
- **How does a child send data to its parent?** By calling a function the parent passed down as a prop.

## Summary

- Props customise components; destructure them with defaults.
- Never mutate props; components should be pure.
- `children` enables wrapper/layout components; callback props send data up.

Next: state, the memory of a component.
