## js-introduction
Q: Is JavaScript the same as Java?
- [ ] Yes
- [x] No, they are different languages
- [ ] JavaScript is Java for browsers
> The names are similar for historical marketing reasons only.

Q: Which part of a web page does JavaScript add?
- [ ] Structure
- [ ] Styling
- [x] Behaviour and interactivity
> HTML is structure, CSS is style.

Q: Is JavaScript case-sensitive?
- [x] Yes: `myName` and `myname` are different
- [ ] No
- [ ] Only for keywords
> Most beginner errors come from capital letters.

## js-where-to-put-javascript
Q: What does `defer` do on a script tag?
- [x] Downloads in parallel and runs after the HTML is parsed, in order
- [ ] Never runs the script
- [ ] Runs it before the page
> It's the modern best practice for external scripts.

Q: Why can a script in the head fail to find elements?
- [x] It runs before the body's elements exist
- [ ] Head scripts are disabled
- [ ] Elements are hidden
> Use defer or DOMContentLoaded.

Q: Why avoid `onclick="..."` attributes?
- [x] They mix behaviour into HTML; use addEventListener instead
- [ ] They don't work
- [ ] They're slower
> Keep JavaScript in .js files.

## js-output
Q: What's the best tool for checking values while coding?
- [x] console.log()
- [ ] alert()
- [ ] document.write()
> Open DevTools' Console to see the output.

Q: Which is a template literal?
- [x] `` `Hello ${name}` ``
- [ ] `"Hello ${name}"`
- [ ] `'Hello' + {name}`
> Backticks allow ${} placeholders.

Q: Why avoid document.write()?
- [x] It can wipe out the whole page if run after loading
- [ ] It's too fast
- [ ] It only works in Chrome
> Change the DOM with textContent instead.

## js-variables
Q: Which keyword declares a value that won't be reassigned?
- [x] const
- [ ] let
- [ ] var
> Use const by default, let when it changes.

Q: Why avoid var?
- [x] It's function-scoped and hoisted, causing confusing bugs
- [ ] It's slower
- [ ] It's removed from JavaScript
> let and const are block-scoped.

Q: Which is a valid variable name?
- [x] totalPrice
- [ ] 2ndPrice
- [ ] total-price
> Names can't start with a digit or contain hyphens.

## js-data-types
Q: What does `typeof null` return?
- [ ] "null"
- [x] "object"
- [ ] "undefined"
> A long-standing JavaScript quirk.

Q: Which value is falsy?
- [x] 0
- [ ] "0"
- [ ] []
> 0, "", null, undefined, NaN and false are falsy.

Q: What is `"5" + 3`?
- [x] "53"
- [ ] 8
- [ ] An error
> + with a string joins text.

## js-operators
Q: Why prefer === over ==?
- [x] === compares without type conversion
- [ ] == is deprecated
- [ ] === is faster to type
> `0 == ""` is true; `0 === ""` is false.

Q: What does `null ?? "Guest"` give?
- [x] "Guest"
- [ ] null
- [ ] false
> ?? only replaces null or undefined.

Q: What does `0 || "default"` give?
- [x] "default"
- [ ] 0
- [ ] true
> || treats 0 as falsy; ?? would keep 0.

## js-strings
Q: What does `"Hello".slice(1, 3)` return?
- [x] "el"
- [ ] "Hel"
- [ ] "ell"
> The end index is excluded.

Q: Which method splits "a,b,c" into an array?
- [x] split(",")
- [ ] join(",")
- [ ] slice(",")
> join does the opposite.

Q: Do string methods change the original string?
- [ ] Yes
- [x] No, they return new strings
- [ ] Only toUpperCase
> Strings are immutable.

## js-numbers
Q: What does `0.1 + 0.2 === 0.3` return?
- [ ] true
- [x] false
- [ ] NaN
> Binary floating point can't represent these exactly.

Q: How do you check if a value is a valid number?
- [x] Number.isNaN / Number.isFinite
- [ ] value == NaN
- [ ] typeof value === "NaN"
> NaN is not equal to itself.

Q: How do you format ₹123456 for people?
- [x] `new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(n)`
- [ ] n.toString()
- [ ] Math.round(n)
> It prints ₹1,23,456.00.

## js-if-else
Q: What are guard clauses?
- [x] Early returns that handle special cases first, avoiding deep nesting
- [ ] Security checks
- [ ] Try/catch blocks
> They keep the main logic flat.

Q: Why always use braces with if?
- [x] Adding a second line later won't silently break logic
- [ ] They're required
- [ ] They make code faster
> One-line ifs without braces cause bugs.

Q: Which is a good use of the ternary operator?
- [x] `const label = age >= 18 ? 'Adult' : 'Minor'`
- [ ] Long multi-step logic
- [ ] Loops
> Keep ternaries simple.

## js-switch
Q: What does switch use to compare?
- [x] Strict equality (===)
- [ ] Loose equality (==)
- [ ] Regex
> "1" won't match case 1.

Q: What happens without break?
- [x] Execution falls through to the next case
- [ ] The switch stops
- [ ] Error
> Return or break at the end of each case.

Q: What does `default` handle?
- [x] Values that match no case
- [ ] The first case
- [ ] Errors only
> Like else at the end of an if chain.

## js-loops
Q: Which loop gives array values directly?
- [x] for...of
- [ ] for...in
- [ ] while
> for...in gives keys/indexes.

Q: What does `break` do?
- [x] Exits the loop immediately
- [ ] Skips one iteration
- [ ] Pauses the loop
> continue skips one iteration.

Q: Which loop is for an object's keys?
- [x] for...in (or Object.keys)
- [ ] for...of directly on the object
- [ ] do...while
> Plain objects aren't iterable with for...of.

## js-arrays
Q: What does `arr.push(x)` do?
- [x] Adds x to the end
- [ ] Adds x to the start
- [ ] Removes x
> unshift adds to the start.

Q: What does `[...a, ...b]` create?
- [x] A new array with a's items followed by b's
- [ ] A nested array
- [ ] Changes a
> The spread operator copies items.

Q: The first index of an array is…
- [x] 0
- [ ] 1
- [ ] -1
> The last is length - 1.

## js-array-methods
Q: Which method transforms every item into a new array?
- [x] map
- [ ] forEach
- [ ] find
> forEach returns undefined.

Q: Which returns only matching items?
- [x] filter
- [ ] some
- [ ] reduce
> some answers yes/no.

Q: Which boils a list down to one value, like a total?
- [x] reduce
- [ ] map
- [ ] every
> reduce((sum, x) => sum + x, 0).

## js-objects
Q: How do you read a property whose name is in a variable key?
- [x] `obj[key]`
- [ ] `obj.key`
- [ ] `obj->key`
> Dot notation uses the literal name "key".

Q: What does `const { name, city } = user` do?
- [x] Destructures properties into variables
- [ ] Copies the object
- [ ] Deletes properties
> A short way to read several properties.

Q: How do you safely read a nested property that may be missing?
- [x] `user?.address?.city`
- [ ] `user.address.city!`
- [ ] `user[address][city]`
> Optional chaining returns undefined instead of throwing.

## js-functions
Q: What does a function return without a return statement?
- [x] undefined
- [ ] null
- [ ] 0
> Return values explicitly.

Q: Which is a default parameter?
- [x] `function greet(name = 'friend')`
- [ ] `function greet(name || 'friend')`
- [ ] `function greet(default name)`
> Used when the argument is undefined.

Q: `function sum(...nums)` makes nums…
- [x] An array of all arguments
- [ ] The first argument
- [ ] An object
> Rest parameters collect the rest.

## js-arrow-functions
Q: How do you return an object from a one-line arrow?
- [x] `() => ({ a: 1 })`
- [ ] `() => { a: 1 }`
- [ ] `() => return { a: 1 }`
> Without brackets, braces are a function body.

Q: The big difference of arrow functions is…
- [x] They don't have their own `this`
- [ ] They're faster
- [ ] They can't take parameters
> They use this from the surrounding scope.

Q: `x => x * 2` is equivalent to…
- [x] `function (x) { return x * 2; }`
- [ ] `function (x) { x * 2; }`
- [ ] `(x) => { x * 2 }`
> Single expressions return automatically.

## js-scope
Q: let and const are scoped to…
- [x] The nearest block `{ }`
- [ ] The whole function
- [ ] The global object
> var is function-scoped.

Q: What is a closure?
- [x] A function that remembers variables from where it was created
- [ ] A closed loop
- [ ] A finished promise
> Used for counters, private data and callbacks.

Q: What creates an accidental global?
- [x] Assigning to an undeclared variable outside strict mode
- [ ] Using const
- [ ] Using let in a block
> Always declare variables.

## js-dom-selecting-elements
Q: Which method accepts any CSS selector and returns the first match?
- [x] querySelector
- [ ] getElementById
- [ ] getElementsByTagName
> querySelectorAll returns all matches.

Q: What does querySelector return when nothing matches?
- [x] null
- [ ] undefined
- [ ] An empty array
> Check for null before using it.

Q: How do you read `data-course-id="7"` in JS?
- [x] `el.dataset.courseId`
- [ ] `el.data.course-id`
- [ ] `el.courseId`
> Dashes become camelCase.

## js-dom-changing-content
Q: Which is safe for user-provided text?
- [x] textContent
- [ ] innerHTML
- [ ] outerHTML
> innerHTML with user input risks XSS.

Q: The best way to change styles from JS is…
- [x] Toggle classes with classList
- [ ] Set many inline styles
- [ ] Rewrite the stylesheet
> CSS stays in CSS files.

Q: Which adds a new element to the page?
- [x] `parent.append(document.createElement('li'))`
- [ ] `document.add('li')`
- [ ] `parent.innerText = '<li>'`
> createElement + append (or appendChild).

## js-events
Q: Which method attaches an event handler?
- [x] addEventListener
- [ ] onEvent
- [ ] attachHandler
> You can add many listeners to one element.

Q: What is event delegation?
- [x] One listener on a parent handling events from many children
- [ ] Passing events to a server
- [ ] Removing listeners
> Works for items added later too.

Q: What does `event.preventDefault()` do in a submit handler?
- [x] Stops the page from reloading
- [ ] Stops event bubbling
- [ ] Deletes the form
> stopPropagation stops bubbling.

## js-forms-and-validation
Q: What does `new FormData(form)` give you?
- [x] All named field values at once
- [ ] The form's HTML
- [ ] Only the first field
> Read values with get(name).

Q: When should validation messages appear?
- [x] On submit or after leaving a field, not on the first keystroke
- [ ] Before the user types
- [ ] Never
> Validate at the right moment.

Q: Is client-side validation enough?
- [ ] Yes
- [x] No, the server must validate again
- [ ] Only for emails
> Anyone can bypass the browser.

## js-local-storage
Q: What type does localStorage store?
- [x] Strings only
- [ ] Any type
- [ ] Numbers only
> Use JSON.stringify and JSON.parse for objects.

Q: localStorage vs sessionStorage?
- [x] localStorage persists; sessionStorage clears when the tab closes
- [ ] They're identical
- [ ] sessionStorage persists forever
> Both are per-origin.

Q: What should you never store in localStorage?
- [x] Passwords and sensitive data
- [ ] The theme
- [ ] A draft message
> Any script on the page can read it.

## js-fetch-and-json
Q: Does fetch reject on a 404 response?
- [ ] Yes
- [x] No, check `response.ok`
- [ ] Only in Chrome
> fetch only rejects on network failures.

Q: How do you send JSON in a POST?
- [x] `body: JSON.stringify(data)` with a Content-Type: application/json header
- [ ] `body: data`
- [ ] `json: data`
> The server needs the header to parse it.

Q: What is CORS about?
- [x] Browsers blocking requests to other origins unless the server allows them
- [ ] Compressing responses
- [ ] Caching images
> It's configured on the server.

## js-async-await
Q: `await` can be used…
- [x] Inside async functions (or top-level modules)
- [ ] Anywhere
- [ ] Only in loops
> It pauses until the promise settles.

Q: How do you handle errors with await?
- [x] try/catch
- [ ] if/else
- [ ] .then only
> Rejected promises throw at the await.

Q: How do you run two requests in parallel?
- [x] `await Promise.all([a(), b()])`
- [ ] `await a(); await b();`
- [ ] It's not possible
> Sequential awaits run one after another.

## js-project-todo-app
Q: Why keep tasks in an array and re-render?
- [x] One source of truth makes the UI predictable
- [ ] Arrays are faster than the DOM
- [ ] It's required by HTML
> Render the page from state.

Q: How do tasks survive a page reload?
- [x] They're saved to localStorage as JSON
- [ ] Browsers remember them automatically
- [ ] Cookies
> Load them at startup.

Q: Why use event delegation on the list?
- [x] One listener handles toggle/delete for all tasks, including new ones
- [ ] It's required for checkboxes
- [ ] To avoid CSS
> Check event.target to decide what was clicked.
