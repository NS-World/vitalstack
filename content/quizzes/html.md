## html-introduction
Q: What does HTML stand for?
- [x] HyperText Markup Language
- [ ] High Tech Modern Language
- [ ] Hyperlink and Text Management Logic
> HTML is a markup language: it labels the parts of a page (headings, paragraphs, links) so the browser knows what each part is.

Q: Which tag holds everything visible on the page?
- [ ] `<head>`
- [x] `<body>`
- [ ] `<title>`
> Content inside `<body>` is shown in the browser window; `<head>` holds information about the page.

Q: What does the browser do with `<!DOCTYPE html>`?
- [ ] Shows it as the first line of the page
- [x] Uses it to render the page with modern HTML rules
- [ ] Downloads the HTML specification
> The doctype tells the browser to use standards mode. It is never displayed.

## html-editors
Q: Which editor does the lesson recommend for writing HTML?
- [ ] Microsoft Word
- [x] Visual Studio Code
- [ ] Paint
> VS Code is free, with highlighting, auto-complete and extensions.

Q: How do you see your page after saving `index.html`?
- [x] Open the file in a web browser
- [ ] Compile it first
- [ ] Upload it to a server first
> Browsers read HTML files directly from your computer.

Q: Which file extension should an HTML page use?
- [ ] .txt
- [x] .html
- [ ] .doc
> Saving as .txt makes the browser show plain text.

## html-basic-structure
Q: What goes inside `<head>`?
- [x] Information about the page, like the title and character set
- [ ] All visible content
- [ ] Only images
> Visible content goes in `<body>`.

Q: Where does the `<title>` text appear?
- [ ] As a big heading
- [x] In the browser tab and search results
- [ ] In the footer
> It's not shown inside the page itself.

Q: How do you write an HTML comment?
- [ ] `// comment`
- [x] `<!-- comment -->`
- [ ] `# comment`
> Comments are not displayed by the browser.

## html-elements
Q: Which is an empty (void) element?
- [ ] `<p>`
- [x] `<br>`
- [ ] `<h1>`
> Empty elements have no content and no closing tag.

Q: Which is a block element?
- [x] `<div>`
- [ ] `<a>`
- [ ] `<strong>`
> Block elements start on a new line and take full width.

Q: How should nested elements be closed?
- [x] In reverse order: the last opened closes first
- [ ] In any order
- [ ] They don't need closing
> `<p><strong>Hi</strong></p>` is correct nesting.

## html-attributes
Q: Which attribute gives an image its alternative text?
- [ ] title
- [x] alt
- [ ] src
> alt is read by screen readers and shown if the image fails.

Q: Which attribute sets where a link goes?
- [x] href
- [ ] src
- [ ] link
> `<a href="https://example.com">`.

Q: Why set `lang="en"` on `<html>`?
- [x] Screen readers and search engines know the page language
- [ ] It changes the font
- [ ] It's required for CSS
> Use lang="hi" for Hindi pages.

## html-headings
Q: How many levels of headings does HTML have?
- [ ] 3
- [x] 6
- [ ] 10
> h1 to h6.

Q: How many h1 headings should a normal page have?
- [x] One, describing the page
- [ ] As many as you like
- [ ] None
> Use h2, h3… for sections below it.

Q: Should you use a heading just to make text big?
- [ ] Yes
- [x] No, use CSS for size
- [ ] Only h6
> Headings describe structure, not style.

## html-paragraphs
Q: What happens to extra spaces and line breaks in HTML source?
- [x] The browser collapses them into one space
- [ ] They're shown exactly
- [ ] They cause errors
> Use `<pre>` to keep spacing.

Q: Which element makes a line break inside a paragraph?
- [x] `<br>`
- [ ] `<hr>`
- [ ] `<lb>`
> hr draws a thematic break line.

Q: Which element keeps spaces and line breaks exactly as typed?
- [ ] `<p>`
- [x] `<pre>`
- [ ] `<span>`
> Useful for code and ASCII art.

## html-text-formatting
Q: What's the difference between `<strong>` and `<b>`?
- [x] strong adds importance (meaning); b is only visual
- [ ] No difference
- [ ] b is newer
> Screen readers can convey strong importance.

Q: How do you write H₂O correctly?
- [x] `H<sub>2</sub>O`
- [ ] `H<sup>2</sup>O`
- [ ] `H_2O`
> sub lowers, sup raises (like x²).

Q: Which element shows inline code?
- [x] `<code>`
- [ ] `<pre>` only
- [ ] `<kbd>` only
> kbd is for keyboard keys.

## html-links
Q: Which link opens in a new tab safely?
- [x] `<a href="…" target="_blank" rel="noopener">`
- [ ] `<a href="…" new>`
- [ ] `<a href="…" tab="new">`
> target="_blank" opens a new tab.

Q: How do you link to a section with id="faq" on the same page?
- [x] `<a href="#faq">`
- [ ] `<a href="faq">`
- [ ] `<a id="faq">`
> The hash points to an element id.

Q: Which link text is best?
- [ ] "Click here"
- [x] "Download the Java syllabus (PDF)"
- [ ] "Link"
> Link text should make sense on its own.

## html-images
Q: Why set width and height on images?
- [x] The browser reserves space so the page doesn't jump while loading
- [ ] To make images sharper
- [ ] It's required for alt text
> It improves layout stability.

Q: What does `loading="lazy"` do?
- [x] Delays loading images until they're near the screen
- [ ] Makes images blurry
- [ ] Compresses images
> It speeds up the initial page load.

Q: Which element pairs an image with a caption?
- [x] `<figure>` with `<figcaption>`
- [ ] `<caption>`
- [ ] `<label>`
> caption is for tables.

## html-lists
Q: Which element creates a numbered list?
- [ ] `<ul>`
- [x] `<ol>`
- [ ] `<dl>`
> ul is for bullet lists.

Q: What may be a direct child of `<ul>`?
- [x] Only `<li>`
- [ ] Any element
- [ ] `<p>`
> Put other content inside the li.

Q: Which list pairs terms with descriptions?
- [ ] `<ol>`
- [x] `<dl>` with `<dt>` and `<dd>`
- [ ] `<table>`
> Great for glossaries and FAQs.

## html-tables
Q: Which element is a header cell?
- [ ] `<td>`
- [x] `<th>`
- [ ] `<tr>`
> td is a normal data cell.

Q: What does `colspan="2"` do?
- [x] Makes a cell span two columns
- [ ] Adds two columns
- [ ] Doubles the font size
> rowspan spans rows.

Q: Should tables be used for page layout?
- [ ] Yes
- [x] No, only for tabular data
- [ ] Only on mobile
> Use CSS (Flexbox/Grid) for layout.

## html-div-and-span
Q: div vs span?
- [x] div is a block container; span is an inline container
- [ ] They're the same
- [ ] span is block
> Both have no meaning of their own.

Q: Before using a div, you should check…
- [x] Whether a meaningful element like `<nav>` or `<article>` fits
- [ ] If CSS is loaded
- [ ] Nothing
> Semantic elements help accessibility and SEO.

Q: Which wraps a few words inside a paragraph for styling?
- [ ] div
- [x] span
- [ ] section
> span stays inline.

## html-classes-and-ids
Q: How many elements can share a class?
- [x] Any number
- [ ] Only one
- [ ] Two
> Classes group elements for styling.

Q: How many elements may share one id?
- [x] Only one per page
- [ ] Any number
- [ ] Up to three
> ids must be unique.

Q: How do you add two classes to one element?
- [x] `class="card featured"`
- [ ] `class="card" class="featured"`
- [ ] `class="card,featured"`
> Separate classes with spaces.

## html-forms
Q: Why is the name attribute important on inputs?
- [x] It's the key sent with the value when the form is submitted
- [ ] It shows a label
- [ ] It sets the id
> Without name, the value isn't sent.

Q: How do you connect a label to an input?
- [x] `<label for="email">` with `<input id="email">`
- [ ] Put them next to each other
- [ ] `<label name="email">`
> Clicking the label then focuses the input.

Q: Which element is for multi-line text?
- [ ] `<input type="text">`
- [x] `<textarea>`
- [ ] `<text>`
> Use rows to set its height.

## html-form-inputs
Q: Radio buttons in one group must share…
- [x] The same name
- [ ] The same id
- [ ] The same value
> Only one in the group can be selected.

Q: Which attribute makes a field mandatory?
- [x] required
- [ ] mandatory
- [ ] must
> The browser blocks submitting empty required fields.

Q: Which input type shows a date picker?
- [ ] text
- [x] date
- [ ] calendar
> Mobile keyboards adapt to types like email and tel too.

## html-semantic-elements
Q: Which element holds the main navigation links?
- [x] `<nav>`
- [ ] `<menu>`
- [ ] `<links>`
> nav is a landmark for screen readers.

Q: When should you use `<article>`?
- [x] For self-contained content like a blog post or news story
- [ ] For any div
- [ ] Only for footers
> section groups related content inside a page.

Q: How many `<main>` elements per page?
- [x] One
- [ ] As many as sections
- [ ] None
> main holds the page's primary content.

## html-media-audio-video
Q: Which attribute shows play/pause buttons on a video?
- [x] controls
- [ ] buttons
- [ ] player
> Without it, users can't control playback.

Q: Why add several `<source>` elements?
- [x] So the browser can pick a format it supports
- [ ] To play videos together
- [ ] For subtitles
> MP4 and WebM are common.

Q: Which element adds captions to a video?
- [x] `<track>`
- [ ] `<caption>`
- [ ] `<subtitle>`
> Captions help deaf users and people watching muted.

## html-accessibility-basics
Q: What is the first rule of accessible HTML?
- [x] Use the right element (button for actions, a for links)
- [ ] Add ARIA everywhere
- [ ] Use only divs
> Native elements come with keyboard and screen reader support.

Q: When should you use ARIA?
- [x] Only when HTML has no suitable element
- [ ] On every element
- [ ] Never
> No ARIA is better than bad ARIA.

Q: Why must you never remove focus outlines without a replacement?
- [x] Keyboard users need to see where they are
- [ ] It breaks CSS
- [ ] It slows the page
> Style :focus-visible instead.

## html-project-profile-page
Q: Which elements give the profile page a meaningful structure?
- [x] header, main, section, footer
- [ ] Only divs
- [ ] Only tables
> Semantic layout elements.

Q: The projects list is best shown as…
- [x] A table with a caption and header cells
- [ ] An image
- [ ] Paragraphs with spaces
> Tabular data belongs in tables.

Q: What should every contact form field have?
- [x] A label and a name
- [ ] A placeholder only
- [ ] An id only
> Labels for accessibility, names for submission.
