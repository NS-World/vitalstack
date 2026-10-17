## react-introduction
Q: What is React?
- [x] A JavaScript library for building component-based UIs
- [ ] A database
- [ ] A CSS framework
> Routing and data fetching come from the ecosystem.

Q: In React, data flows…
- [x] Down through props; events go up via callbacks
- [ ] Up through props
- [ ] Randomly
> One-way data flow keeps changes traceable.

Q: Why the virtual DOM?
- [x] React diffs UI trees and applies minimal real DOM changes
- [ ] It replaces HTML
- [ ] It stores data
> Real DOM updates are slow.

## react-setup-and-jsx
Q: Which attribute replaces class in JSX?
- [x] className
- [ ] cssClass
- [ ] class
> class is a reserved word in JavaScript.

Q: Why must components start with a capital letter?
- [x] So JSX can tell them from HTML elements
- [ ] Style rule only
- [ ] Vite requires it
> <welcome /> would be an unknown HTML tag.

Q: How do you return two siblings without an extra div?
- [x] Wrap them in a fragment <>…</>
- [ ] Return an array of strings
- [ ] You can't
> Fragments add no DOM node.

## react-components-and-props
Q: Can a component change its props?
- [ ] Yes
- [x] No, props are read-only
- [ ] Only numbers
> Changing data is state's job.

Q: How does a child send data to its parent?
- [x] By calling a function the parent passed as a prop
- [ ] By editing parent state directly
- [ ] With document.querySelector
> Name callbacks onSomething.

Q: What is the children prop?
- [x] The content placed between the component's tags
- [ ] Child components' state
- [ ] A list of keys
> Great for layout wrappers.

## react-state-usestate
Q: With count = 0, `setCount(count + 1); setCount(count + 1);` gives…
- [x] 1
- [ ] 2
- [ ] 0
> Both read the same snapshot. Use setCount(c => c + 1).

Q: How should you add an item to a state array?
- [x] setItems(prev => [...prev, item])
- [ ] items.push(item)
- [ ] items[items.length] = item
> Create a new array; don't mutate.

Q: Where can hooks be called?
- [x] At the top level of components or custom hooks
- [ ] Inside loops
- [ ] Inside if statements
> React tracks hooks by call order.

## react-events-and-forms
Q: What's wrong with `onClick={handleClick()}`?
- [x] It calls the function during render
- [ ] Nothing
- [ ] It's a syntax error
> Pass the function: onClick={handleClick}.

Q: A controlled input has…
- [x] value from state and an onChange that updates it
- [ ] No state
- [ ] Only defaultValue
> State is the single source of truth.

Q: Why call e.preventDefault() in onSubmit?
- [x] To stop the browser reloading the page
- [ ] To validate the form
- [ ] To clear the form
> React handles the submission instead.

## react-lists-and-conditional-rendering
Q: What should you use as a list key?
- [x] A stable unique id from the data
- [ ] Math.random()
- [ ] Always the index
> Index keys break with reordering or filtering.

Q: `{count && <Badge />}` with count = 0 renders…
- [x] 0
- [ ] Nothing
- [ ] The badge
> Use count > 0 && …

Q: Filtered lists should be…
- [x] Computed during render from state
- [ ] Stored in separate state
- [ ] Kept in a ref
> Derived data stays consistent.

## react-useeffect-and-data-fetching
Q: An effect with `[]` dependencies runs…
- [x] Once, after the first render
- [ ] After every render
- [ ] Never
> Add values to the array to re-run on changes.

Q: What is the cleanup function for?
- [x] Undoing the effect before it re-runs and on unmount
- [ ] Clearing state
- [ ] Deleting the component
> Abort fetches, clear timers, remove listeners.

Q: How do you avoid stale responses overwriting newer data?
- [x] Abort or ignore the previous request in cleanup
- [ ] Fetch twice
- [ ] Use var instead of const
> AbortController cancels in-flight requests.

## react-rendering-and-performance
Q: When does a component re-render?
- [x] Its state changes, its parent re-renders, or a context it uses changes
- [ ] Only when props change
- [ ] Every second
> Re-render ≠ DOM update.

Q: Why might React.memo not help?
- [x] Props are new objects/functions each render
- [ ] memo is deprecated
- [ ] It only works with classes
> Use useMemo/useCallback to keep them stable.

Q: useCallback memoises…
- [x] A function
- [ ] A value
- [ ] A component
> useMemo memoises computed values.

## react-context-and-usereducer
Q: What problem does Context solve?
- [x] Prop drilling through many components
- [ ] Slow rendering
- [ ] Routing
> Any descendant can read the value.

Q: A reducer is…
- [x] A pure function (state, action) → new state
- [ ] A hook that fetches data
- [ ] A CSS tool
> Easy to test without rendering.

Q: Downside of putting fast-changing values in Context?
- [x] Every consumer re-renders on each change
- [ ] It can't hold objects
- [ ] It's synchronous
> Split contexts or use a state library.

## react-refs-and-custom-hooks
Q: Changing ref.current…
- [x] Doesn't trigger a re-render
- [ ] Always re-renders
- [ ] Throws an error
> Use refs for DOM nodes and timer ids.

Q: Custom hook names must…
- [x] Start with use
- [ ] End with Hook
- [ ] Be capitalised
> So lint rules can check hook usage.

Q: Do two components using useLocalStorage share state?
- [ ] Yes
- [x] No, each call has its own state
- [ ] Only in StrictMode
> Hooks share logic, not state.

## react-router
Q: Link vs a plain a tag?
- [x] Link navigates without reloading the app
- [ ] They're identical
- [ ] a tags are faster
> A full reload loses React state.

Q: How do you read `/courses/:slug`?
- [x] useParams()
- [ ] useSearchParams()
- [ ] window.location only
> useSearchParams reads ?query values.

Q: Is a protected route enough security?
- [ ] Yes
- [x] No, the API must enforce permissions too
- [ ] Only with HTTPS
> Anyone can call the API directly.

## react-project-course-hub
Q: Why centralise HTTP in api.js?
- [x] One place for URLs, headers and turning ProblemDetail errors into messages
- [ ] React requires it
- [ ] It's faster
> Components call functions, not URLs.

Q: Where does "max 3 active courses" come from?
- [x] The Spring Boot server's business rule
- [ ] A React constant
- [ ] The browser
> The UI shows the server's message.

Q: After completing a lesson, why reload from the server?
- [x] The server owns the rules and the true progress
- [ ] Fetch caches nothing
- [ ] Because of StrictMode
> Optimistic updates are an optional extension.
