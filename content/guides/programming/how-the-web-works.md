---
title: "How the Web Works: What Happens When You Type a URL"
slug: how-the-web-works
category: programming-development
tags: web basics, HTTP, DNS
order: 6
excerpt: Follow a web request step by step: URLs, DNS, IP addresses, TCP and HTTPS, HTTP requests and responses, servers, and how the browser turns HTML, CSS and JavaScript into a page.
---

**Short answer:** when you type a URL, your browser finds the server's address with **DNS**, opens a secure connection (**TCP + TLS/HTTPS**), sends an **HTTP request**, receives an **HTTP response** with HTML, then downloads the CSS, JavaScript and images it references, and finally **renders** the page. All of this usually happens in well under a second.

Understanding this journey makes you a better developer: it explains slow pages, broken sites, security warnings and how frontend and backend fit together.

## Step 1: The URL

```text
https://vitalstack.co.in/tutorials/html-introduction/?ref=home#examples
└─┬──┘   └──────┬───────┘└──────────┬────────────────┘└───┬───┘└───┬───┘
scheme       domain               path                  query   fragment
```

- **Scheme:** `https` means use HTTP over a secure connection.
- **Domain:** the human-friendly name of the server.
- **Path:** which page or resource on that server.
- **Query string:** extra parameters, often for searches and filters.
- **Fragment:** a section within the page (`#examples`); never sent to the server, the browser just scrolls to it.

## Step 2: DNS finds the server's address

Computers on the internet find each other by **IP addresses** like `203.0.113.42` (IPv4) or `2001:db8::42` (IPv6). Names like `vitalstack.co.in` are for humans.

**DNS** (Domain Name System) is the internet's phone book:

1. The browser checks its own cache: "Have I looked this up recently?"
2. If not, it asks the operating system, which asks a **DNS resolver** (usually run by your internet provider, or a public one).
3. The resolver asks the DNS hierarchy: the root servers point to the `.in` servers, which point to the servers responsible for `co.in`, which point to the domain's own DNS servers, which finally answer with the IP address.
4. The answer is cached at each step for a while (its "TTL"), so the next lookup is instant.

This is why DNS changes (like moving a site to new hosting) can take time to "propagate": old answers stay cached until they expire.

## Step 3: Connecting: TCP and TLS

The browser opens a **TCP connection** to the server's IP address on port 443 (the standard HTTPS port). TCP makes sure data arrives complete and in order.

Then comes the **TLS handshake**, which makes the connection secure:

1. The server sends its **certificate**, proving it really is `vitalstack.co.in` (signed by a trusted certificate authority).
2. The browser checks the certificate is valid, unexpired and for the right domain.
3. Both sides agree on encryption keys.

Now everything sent is **encrypted**: nobody on the network (public Wi-Fi, your provider) can read or change it. That's the padlock in the address bar. If the certificate is wrong or expired, browsers show a security warning.

(Newer HTTP/3 connections use a protocol called QUIC instead of TCP, combining these steps to connect faster, but the ideas are the same.)

## Step 4: The HTTP request

The browser sends a request: plain structured text (inside the encrypted connection):

```text
GET /tutorials/html-introduction/?ref=home HTTP/1.1
Host: vitalstack.co.in
User-Agent: Mozilla/5.0 (…)
Accept: text/html
Accept-Language: en-IN,en;q=0.9
Cookie: theme=dark
```

- **Method:** `GET` (read a page). Others: `POST` (send data, like a form), `PUT`/`PATCH` (update), `DELETE`.
- **Path** of the resource.
- **Headers:** extra information like the browser type, preferred languages, and **cookies** (small pieces of data the site stored earlier, e.g. that you're logged in).

## Step 5: The server responds

On the server, a **web server** program (like Nginx, Apache or LiteSpeed) receives the request. Then:

- For a **static file** (an image, a CSS file), it sends the file directly.
- For a **dynamic page**, it runs application code (PHP for WordPress, Python, Node.js…), which might query a **database**, build the HTML, and return it.
- Often a **cache** or **CDN** (content delivery network, servers spread around the world) answers instead, with a saved copy, which is much faster.

The response:

```text
HTTP/1.1 200 OK
Content-Type: text/html; charset=UTF-8
Cache-Control: max-age=600
Set-Cookie: session=abc123; Secure; HttpOnly

<!DOCTYPE html>
<html lang="en">
<head>
  <title>HTML Introduction</title>
  <link rel="stylesheet" href="/style.css">
  <script src="/main.js" defer></script>
</head>
<body>…</body>
</html>
```

- **Status code:** `200 OK`. Others you'll see: `301`/`302` (redirect), `304` (not modified, use your cached copy), `404` (not found), `403` (forbidden), `500` (server error).
- **Headers:** the content type, caching rules, cookies to store.
- **Body:** the HTML itself.

## Step 6: The browser renders the page

Now the browser turns text into pixels:

1. **Parse HTML** into the **DOM**, a tree of elements (see our [DOM lesson](/tutorials/js-dom-selecting-elements/)).
2. **Discover resources:** the `<link>` to CSS, `<script>` tags, images and fonts. Each triggers another HTTP request (often over the same connection).
3. **Parse CSS** into rules and match them to elements.
4. **Layout:** calculate the size and position of every box (the CSS box model).
5. **Paint:** draw text, colours, borders and images.
6. **Run JavaScript**, which can change the DOM and trigger layout and paint again.

A typical page needs dozens of requests: HTML, stylesheets, scripts, fonts, images. That's why page speed depends on the **number and size** of resources, not just the HTML.

## Why pages are slow (and how to fix it)

| Cause | Fix |
|---|---|
| Large images | Resize, compress, use modern formats, lazy-load |
| Too much JavaScript | Remove unused scripts, use `defer` |
| Slow server / database | Caching, faster hosting, better queries and indexes |
| Far-away server | A CDN |
| Many separate files | Combine where sensible; HTTP/2 and HTTP/3 help |
| Fonts blocking text | `font-display: swap`, fewer font files |

Browser developer tools (F12 → Network tab) show every request, its size and how long it took. Try it on any website.

## Where frontend and backend fit

- **Frontend** code runs in the browser: HTML, CSS and JavaScript from Step 6.
- **Backend** code runs on the server in Step 5: application logic and databases.
- They talk through HTTP. Modern apps often load a page once, then use JavaScript `fetch()` to exchange JSON with **APIs** without reloading ([our fetch lesson](/tutorials/js-fetch-and-json/)).

See [Frontend vs Backend vs Full-Stack](/frontend-vs-backend-vs-full-stack/) for career paths on each side.

## Frequently asked questions

### What's the difference between the internet and the web?

The internet is the global network connecting computers. The web is one service that runs on it (pages and links over HTTP). Email and video calls also use the internet but aren't "the web".

### Why does HTTPS matter if my site has no passwords?

It prevents others on the network from reading or modifying your pages (for example, injecting ads or malware), browsers warn users about non-HTTPS sites, and search engines prefer secure sites. Certificates are free from services like Let's Encrypt, and most hosting includes them.

### What are cookies used for?

Remembering state between requests, since HTTP itself is "stateless": login sessions, preferences, shopping carts, and also analytics and advertising (which is why many sites ask for consent).

### What is a CDN?

A network of servers around the world that keep copies of your site's files, so visitors download from a server near them. It makes sites faster and absorbs traffic spikes.

## Summary

- A URL has a scheme, domain, path, query and fragment.
- DNS turns the domain into an IP address; TCP + TLS create a secure connection.
- The browser sends an HTTP request; the server (or a cache/CDN) sends a response with a status code, headers and body.
- The browser builds the DOM, loads CSS, JavaScript and images, lays out and paints the page.
- Speed depends on the size and number of resources, server work and distance.
