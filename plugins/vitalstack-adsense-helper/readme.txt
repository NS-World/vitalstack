=== VitalStack AdSense Helper ===
Requires at least: 6.0
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv2 or later

Publish scheduled lessons in one click and check your site against the usual AdSense rejection reasons.

== Description ==

Tools → AdSense Helper

1. Scheduled content: "Publish all now" publishes every scheduled post, lesson and page immediately, in the same order (dates are set to just now so nothing stays in the future). Or reschedule them to N per day.
2. AdSense readiness check (read-only): search engines allowed, HTTPS and permalinks, About/Contact/Privacy/legal pages published, privacy policy mentions Google advertising cookies, no placeholders such as [your pen name] or "coming soon", no sample content, enough articles, no thin articles under 300 words, no links to unpublished lessons, no empty categories, AdSense code and ads.txt, no maintenance plugin.

Nothing changes until you press a button. Only administrators can use it. Delete the plugin when you are done.

== Changelog ==

= 1.1.0 =
* Quick fixes: "Update thin lessons" replaces short lessons with the longer versions bundled in updates.json (only when the bundled text is longer; the old text stays in Revisions).
* Quick fixes: "Create ads.txt" writes a real ads.txt in the site root with your publisher ID (found automatically from the theme setting or Site Kit).
* Placeholder and link checks ignore code examples, so lesson code like `const [todos, setTodos]` or `<a href="...">` is no longer reported.
* Link check only reports scheduled, draft, pending or private targets (never auto-drafts).
