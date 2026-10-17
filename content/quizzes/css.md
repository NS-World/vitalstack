## css-introduction
Q: In `p { color: blue; }`, what is `p`?
- [x] The selector
- [ ] The property
- [ ] The value
> color is the property, blue the value.

Q: What does "cascading" mean in CSS?
- [x] Rules from several sources combine, with clear rules for which wins
- [ ] Styles flow like water
- [ ] Only the first rule applies
> Specificity, importance and order decide.

Q: Which property is inherited by child elements?
- [x] color
- [ ] border
- [ ] margin
> Text properties like color and font inherit.

## css-how-to-add-css
Q: What is the normal way to add CSS to a website?
- [x] An external stylesheet linked with `<link rel="stylesheet">`
- [ ] Inline style attributes everywhere
- [ ] A `<css>` tag
> One file styles every page.

Q: Which wins: an inline style or an external rule for the same property?
- [x] The inline style (higher specificity)
- [ ] The external rule
- [ ] The first one written
> Avoid inline styles for maintainability.

Q: Where does an internal stylesheet go?
- [x] In a `<style>` element in the head
- [ ] In the body
- [ ] In a comment
> Fine for single-page demos.

## css-selectors
Q: Which selector matches elements with class="card"?
- [x] `.card`
- [ ] `#card`
- [ ] `card`
> # is for ids.

Q: Which has the highest specificity?
- [x] `#menu`
- [ ] `.menu`
- [ ] `ul`
> id > class > element.

Q: What does `a:hover` target?
- [x] Links while the mouse is over them
- [ ] Visited links
- [ ] All anchors always
> :hover is a pseudo-class for state.

## css-colors
Q: Which is a valid hex colour?
- [x] `#0a7d4f`
- [ ] `#zz1122`
- [ ] `0a7d4f#`
> Hex uses 0-9 and a-f.

Q: In `rgba(0, 0, 0, 0.5)`, the 0.5 is…
- [x] Opacity (50% transparent)
- [ ] Brightness
- [ ] Hue
> The alpha channel controls transparency.

Q: Why does contrast matter?
- [x] Low-contrast text is hard to read for many people
- [ ] It changes SEO
- [ ] Browsers require it
> Aim for WCAG contrast ratios.

## css-backgrounds
Q: Which background-size fills the box completely, cropping if needed?
- [x] cover
- [ ] contain
- [ ] auto
> contain shows the whole image without cropping.

Q: How do you stop a background image from tiling?
- [x] `background-repeat: no-repeat`
- [ ] `background-tile: none`
- [ ] `repeat: off`
> Images repeat by default.

Q: Which creates a gradient?
- [x] `background: linear-gradient(#0a7d4f, #34c987)`
- [ ] `gradient: green`
- [ ] `background-color: gradient`
> Gradients are background images.

## css-borders
Q: Which makes a perfect circle from a square?
- [x] `border-radius: 50%`
- [ ] `border-radius: circle`
- [ ] `shape: circle`
> Works when width equals height.

Q: Outline vs border?
- [x] Outline takes no space in the layout
- [ ] Outline is thicker
- [ ] They're identical
> Often used for focus indicators.

Q: Which property adds a shadow around a box?
- [x] box-shadow
- [ ] text-shadow
- [ ] shadow
> text-shadow is for text.

## css-margin-and-padding
Q: Padding vs margin?
- [x] Padding is inside the border; margin is outside
- [ ] They're the same
- [ ] Margin is inside
> Padding gets the background colour.

Q: How do you centre a block with a width?
- [x] `margin: 0 auto`
- [ ] `align: center`
- [ ] `padding: auto`
> Auto side margins split the space.

Q: `margin: 10px 20px` means…
- [x] 10px top/bottom, 20px left/right
- [ ] 10px left, 20px right
- [ ] 10px all sides
> Two values: vertical then horizontal.

## css-box-model
Q: With default box-sizing, a 300px-wide box with 20px padding and 5px border is how wide?
- [x] 350px
- [ ] 300px
- [ ] 325px
> Content-box adds padding and border to the width.

Q: What does `box-sizing: border-box` do?
- [x] Includes padding and border inside the set width
- [ ] Removes borders
- [ ] Adds margin
> Most sites apply it to everything.

Q: Which layers make up the box model (inside out)?
- [x] Content, padding, border, margin
- [ ] Margin, content, border, padding
- [ ] Border, content, margin
> Inspect it in DevTools.

## css-width-and-height
Q: Which is best for responsive images and containers?
- [x] `max-width: 100%`
- [ ] `width: 1200px`
- [ ] `height: 100%`
> They shrink on small screens but don't grow too large.

Q: Why prefer `min-height` over `height` for content boxes?
- [x] The box can grow if content is longer
- [ ] It's faster
- [ ] height doesn't work
> Fixed heights cause overflow.

Q: `rem` units are relative to…
- [x] The root (html) font size
- [ ] The parent's width
- [ ] The screen width
> em is relative to the element's font size.

## css-text-and-fonts
Q: Why list fallbacks in font-family?
- [x] If the first font is unavailable, the next one is used
- [ ] To use all fonts at once
- [ ] For SEO
> End with a generic family like sans-serif.

Q: A comfortable line-height for body text is about…
- [x] 1.5 to 1.7
- [ ] 0.5
- [ ] 4
> Unitless values scale with font size.

Q: How do you show "…" for text that overflows one line?
- [x] `white-space: nowrap; overflow: hidden; text-overflow: ellipsis`
- [ ] `text-overflow: dots`
- [ ] `ellipsis: true`
> All three are needed.

## css-display
Q: inline-block elements…
- [x] Sit in a line but accept width, height and vertical padding
- [ ] Start on a new line
- [ ] Ignore width
> Best of block and inline.

Q: `display: none` vs `visibility: hidden`?
- [x] display:none removes the element from layout; visibility:hidden keeps its space
- [ ] They're identical
- [ ] visibility removes it
> Both hide it visually.

Q: Can you set width on a normal inline element like span?
- [ ] Yes
- [x] No, width is ignored
- [ ] Only in pixels
> Change display to inline-block or block.

## css-position
Q: An absolutely positioned element is positioned relative to…
- [x] Its nearest positioned ancestor
- [ ] The browser window always
- [ ] Its next sibling
> Give the parent position: relative.

Q: Which value sticks an element when you scroll past it?
- [x] sticky
- [ ] fixed
- [ ] static
> fixed stays in the viewport always.

Q: Which property decides what's on top?
- [x] z-index
- [ ] layer
- [ ] top
> Works on positioned elements.

## css-flexbox-basics
Q: Which property spaces items along the main axis?
- [x] justify-content
- [ ] align-items
- [ ] align-content
> align-items works on the cross axis.

Q: How do you perfectly centre one child?
- [x] `display:flex; justify-content:center; align-items:center`
- [ ] `margin: center`
- [ ] `text-align: middle`
> A classic flexbox trick.

Q: What does `gap` do in a flex container?
- [x] Adds space between items
- [ ] Adds padding outside
- [ ] Hides items
> No margin hacks needed.

## css-flexbox-layouts
Q: `flex: 1` on several items makes them…
- [x] Share the available space equally
- [ ] 1px wide
- [ ] Hidden
> flex-grow: 1 shares extra space.

Q: flex-basis sets…
- [x] The starting size before growing or shrinking
- [ ] The maximum size
- [ ] The margin
> Then grow/shrink adjust it.

Q: How do you push card buttons to the bottom?
- [x] Make the card a flex column and give the content flex: 1 (or margin-top: auto on the button)
- [ ] position: fixed
- [ ] float: bottom
> Equal-height cards with aligned buttons.

## css-grid-basics
Q: What does `grid-template-columns: repeat(3, 1fr)` create?
- [x] Three equal columns
- [ ] Three rows
- [ ] A 3px grid
> fr shares the free space.

Q: Grid vs Flexbox?
- [x] Grid for two-dimensional layouts; Flexbox for one direction
- [ ] They're identical
- [ ] Grid is deprecated
> Use them together.

Q: How do you make an item span two columns?
- [x] `grid-column: span 2`
- [ ] `colspan: 2`
- [ ] `width: 2fr`
> Or set start/end lines.

## css-grid-layouts
Q: What does `repeat(auto-fit, minmax(220px, 1fr))` do?
- [x] Fits as many columns of at least 220px as possible, no media queries needed
- [ ] Creates 220 columns
- [ ] Fixes columns at 220px
> A responsive card grid in one line.

Q: Named grid areas are set with…
- [x] grid-template-areas and grid-area
- [ ] grid-name
- [ ] area-template
> Rearrange them in media queries.

Q: Can Grid and Flexbox be used together?
- [x] Yes: grid for the page, flex inside components
- [ ] No
- [ ] Only in Chrome
> Each solves different problems.

## css-responsive-design
Q: Which meta tag is required for responsive pages on phones?
- [x] `<meta name="viewport" content="width=device-width, initial-scale=1">`
- [ ] `<meta name="mobile">`
- [ ] `<meta charset>`
> Otherwise phones render a zoomed-out desktop page.

Q: Mobile-first means…
- [x] Base styles for small screens, then min-width media queries for larger ones
- [ ] Building only for phones
- [ ] Hiding content on desktop
> It usually leads to simpler CSS.

Q: What does `font-size: clamp(1.5rem, 4vw, 2.5rem)` do?
- [x] Scales with the screen between a minimum and maximum
- [ ] Fixes the size at 4vw
- [ ] Clamps the line height
> Fluid typography.

## css-transitions
Q: Which properties are cheapest to animate?
- [x] transform and opacity
- [ ] width and height
- [ ] margin
> They don't trigger layout.

Q: How do you define a multi-step animation?
- [x] @keyframes with animation
- [ ] transition only
- [ ] JavaScript only
> Transitions animate between two states.

Q: Why respect `prefers-reduced-motion`?
- [x] Some users get dizzy or sick from motion
- [ ] It's faster
- [ ] It's required by HTML
> Reduce or remove animations for them.

## css-variables
Q: How do you use a variable named --brand?
- [x] `color: var(--brand)`
- [ ] `color: $brand`
- [ ] `color: --brand`
> Define it like `--brand: #0a7d4f`.

Q: Where are global variables usually defined?
- [x] On `:root`
- [ ] On body only
- [ ] In JavaScript
> They're inherited by everything.

Q: How can variables make dark mode easy?
- [x] Redefine colour variables under a dark theme selector or media query
- [ ] They can't
- [ ] By using filters
> Components keep using the same variable names.

## css-project-style-profile-page
Q: Why start with variables and a reset?
- [x] Consistent colours/spacing and predictable defaults everywhere
- [ ] To make the file longer
- [ ] Browsers require it
> box-sizing: border-box is part of the reset.

Q: Which tool lays out the header's logo and links in a row?
- [x] Flexbox
- [ ] Tables
- [ ] Floats
> justify-content: space-between spreads them.

Q: How are project cards made responsive?
- [x] Grid with auto-fit and minmax
- [ ] Fixed pixel widths
- [ ] One column always
> They wrap automatically.
