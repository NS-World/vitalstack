"""Build the VitalStack 4.8 test plan as HTML (printed to PDF by print.js)."""
import html, pathlib

OUT = pathlib.Path(__file__).with_name("testplan.html")  # then: node print_test_plan.js VitalStack-4.8-Test-Plan.pdf
E = html.escape

# (id, title, steps "a; b; c", expected, priority)
MODULES = [
("SMK", "Install and smoke test", "Do this first, right after uploading the zip. If any P1 here fails, stop and report it before testing anything else.", [
("SMK-01", "Theme uploads and activates", "Appearance → Themes → Add New → Upload; choose vitalstack-theme-v4.8.2.zip; click Replace current with uploaded; open Appearance → Themes", "VitalStack shows version 4.8.2. No 'There has been a critical error' anywhere on the site or in wp-admin.", "P1"),
("SMK-02", "Logo is normal size", "Purge LiteSpeed Cache (Purge All); hard refresh (Ctrl+Shift+R); open homepage on desktop and phone; open the HTML introduction lesson", "Logo sits small in the header. No giant logo covers the page (the 4.8.0 bug).", "P1"),
("SMK-03", "Main pages load cleanly", "Open: homepage, a free lesson, a premium lesson, /pricing/, /account/, a tool page, a quiz; on desktop press F12 → Console", "Every page loads. No sideways scrolling. No red errors in the Console.", "P1"),
("SMK-04", "Setup creates the refund page", "Appearance → VitalStack Setup → Run setup; open Pages", "A draft page 'Refund and Cancellation Policy' exists. Existing pages and menus are unchanged.", "P2"),
("SMK-05", "New files are served", "Right-click → View page source on the homepage; search for ver=4.8.2", "CSS/JS links end with ver=4.8.2 (cache is not serving old files).", "P2"),
]),
("WLK", "Line-by-line code walkthrough (new animation)", "Use the Statistics lessons (as a premium reader or admin) and the demo on /pricing/.", [
("WLK-01", "Player appears", "Open Statistics lesson 'Mean, Median, Spread and the Five-Number Summary'; scroll to the code example", "A '▶ Line by line' player is shown. The old code example and animations on the page are still there.", "P1"),
("WLK-02", "Play runs line by line", "Press Play", "Lines type in one by one, the pointer sits on the current line, and a crew character explains it in a bubble.", "P1"),
("WLK-03", "Next / Back / Reset", "Press Next twice; Back once; Reset", "Moves exactly one step each time; Reset goes back to step 1 and clears the console.", "P1"),
("WLK-04", "Simple / Deep dive", "Switch between Simple and Deep dive on the same step", "Explanation text changes; the step stays the same.", "P2"),
("WLK-05", "Variables panel", "Step through lines that change a variable", "Variables panel shows the new value and the changed one flashes. Values match the code.", "P2"),
("WLK-06", "Console output", "Reset; check console; step to a print line", "Before printing it says 'Nothing printed yet'. At a print line the right output appears.", "P2"),
("WLK-07", "Jump and keyboard", "Click any code line; on desktop press ← and →", "Jumps to that line's step; arrow keys go back/next.", "P3"),
("WLK-08", "Speed", "Change the speed select while playing", "Playback gets faster/slower.", "P3"),
("WLK-09", "Other lessons", "Open Statistics lessons 'Probability … Bayes' Theorem' and 'Hypothesis Tests … p-values'; play each to the end", "Both players work. The p-value walkthrough ends with 364 extreme results and p ≈ 0.0364.", "P2"),
("WLK-10", "Pricing demo", "Open /pricing/; scroll to 'How premium explains code: every line, step by step'; Play", "Demo walkthrough plays the same way.", "P2"),
("WLK-11", "Mobile", "Repeat WLK-02/03 on a phone (or 360px width)", "Player fits the screen, no sideways scroll, buttons are easy to tap, bubble text readable.", "P1"),
]),
("PRC", "Pricing page and plans", "Open /pricing/ as a signed-in reader unless the step says otherwise.", [
("PRC-01", "Four plan cards", "Open /pricing/", "Pro Monthly ₹399 · Career Track ₹1,999 · Pro Yearly ₹2,999 (Best value) · Founder Lifetime ₹6,999.", "P1"),
("PRC-02", "₹ / $ switch", "Switch currency to $ and back", "$9 · $39 · $69 · $149; back to ₹ prices. Pay note changes for India / outside India.", "P1"),
("PRC-03", "Choose a track", "In the Career Track card open 'Choose a track'; pick each option", "Data Scientist, Gen AI Developer and Agentic AI Engineer are listed; the card text changes with the pick.", "P1"),
("PRC-04", "Lifetime places", "Look at the Founder Lifetime card", "Shows 'Founder Lifetime places left: N' (200 minus lifetime plans sold).", "P2"),
("PRC-05", "Signed-out button", "Sign out; open /pricing/; click a plan button", "Button says 'Create free account to buy' and opens the sign-up page.", "P1"),
("PRC-06", "Sales closed", "Admin: Settings → VitalStack Payments → untick 'Open: readers can buy premium plans' → Save; open /pricing/ as a reader; then tick it again", "Shows 'Premium sales open soon…' / 'Join the waitlist' instead of buying. Normal again after re-ticking.", "P2"),
("PRC-07", "Trailer, FAQ, refund link", "Play the premium trailer; open each FAQ; open 'What if it’s not for me?'", "Trailer plays; FAQ explains the 7-day / under-30% refund rule; refund page link opens (after the page is published).", "P3"),
("PRC-08", "Agentic AI track everywhere", "Open 'All courses' in the top bar; check the homepage premium section", "The Agentic AI Engineer track is listed with the other two premium tracks.", "P2"),
]),
("CPN", "Coupons", "Admin: add the test coupons from Setup §2.4 first. Use practice checkout so no money moves.", [
("CPN-01", "Percent coupon", "Pro Yearly; 'Have a coupon code?'; type TEST20; Apply", "Old price struck through; new price about ₹2,399 (20% off). Paying uses the new price.", "P1"),
("CPN-02", "Fixed-price coupon", "Pro Yearly; coupon YEAR1999; then switch to $", "₹1,999. In $ it shows the same share off (about $46).", "P2"),
("CPN-03", "Wrong plan", "Pro Monthly; coupon YEAR1999", "'That coupon doesn’t apply to this plan.' Price unchanged.", "P1"),
("CPN-04", "Expired", "Any plan; coupon OLDCODE", "'That coupon is not valid or has expired.'", "P1"),
("CPN-05", "Unknown code", "Any plan; coupon ABC123", "'That coupon is not valid or has expired.'", "P1"),
("CPN-06", "Max uses", "Reader R1 buys Pro Monthly with ONEUSE (practice pay); reader R2 tries ONEUSE on Pro Monthly", "R1 succeeds. R2 gets 'not valid or has expired'.", "P2"),
("CPN-07", "Lower case", "Type test20 in small letters", "Works the same as TEST20.", "P3"),
("CPN-08", "Price can't be faked", "F12 → change the price text on a card to ₹1; buy with practice checkout", "Charged / receipt amount is the real server price, not ₹1.", "P1"),
]),
("CHK", "Buy checklist ('Before you buy')", "Use a fresh reader account each time you need to see a step.", [
("CHK-01", "Checklist opens", "Fresh reader; click buy on any plan", "'Before you buy' window: 'A few quick steps before you buy.' Missing steps listed; done steps ticked.", "P1"),
("CHK-02", "Email step", "If 'Confirm your email address' is shown, click 'Resend confirmation email'; confirm from inbox", "Email arrives; after confirming, the step is ticked.", "P2"),
("CHK-03", "Subscribe step", "Click 'Subscribe now'", "Step ticks straight away.", "P1"),
("CHK-04", "Security check by email", "Click 'Email me a code'; enter the 6-digit code from email", "Step ticks; then 'All set ✅ Opening checkout…' and checkout opens.", "P1"),
("CHK-05", "Security check by app", "Reader with authenticator app on; buy", "Asks 'Code from your app'; correct code ticks the step.", "P2"),
("CHK-06", "15-minute window", "Buy again within 15 minutes; then again after 16+ minutes", "Inside 15 min: no new security check. After: asked again.", "P2"),
("CHK-07", "Wrong codes", "Enter a wrong code 5 times", "Paused for 15 minutes with a clear message; account owner gets an email.", "P1"),
("CHK-08", "Close and mobile", "Press Esc / close button; repeat on a 320–375px phone", "Window closes. On phone it fits the screen, nothing cut off.", "P2"),
]),
("DEM", "Practice checkout (no Razorpay, no money)", "Admin: tick 'Let me test buying without Razorpay and without real money' and add tester emails in 'Test buyer emails'.", [
("DEM-01", "Who sees practice", "Open /pricing/ as admin, as a listed tester, and as a normal reader", "Admin and tester see '🧪 Practice checkout is ON (only for you and testers).' The normal reader does not get practice checkout.", "P1"),
("DEM-02", "Practice payment works", "Tester buys Pro Monthly; choose UPI; click '✅ Pay (practice)'", "'Payment received 🎉 Taking you to your course…'. Account page shows premium 'Active until' about 31 days ahead.", "P1"),
("DEM-03", "Practice failure", "Buy; click '❌ Make it fail'", "Error message; no premium access; no receipt.", "P1"),
("DEM-04", "Cancel", "Buy; click Cancel", "Window closes, nothing granted, can try again.", "P2"),
("DEM-05", "Receipt", "Check the tester's inbox after DEM-02", "Receipt email with Invoice no. like VS/2026-27/0001 (next number each order), Order ID and Amount.", "P1"),
("DEM-06", "One track only", "Fresh tester buys Career Track → Data Scientist", "Data Scientist lessons open; Gen AI Developer and Agentic AI lessons stay locked; validity 12 months.", "P1"),
("DEM-07", "Lifetime", "Buy Founder Lifetime", "Account shows 'No end date'. Places left goes down by 1.", "P2"),
("DEM-08", "All-access", "Buy Pro Yearly", "All three premium tracks open.", "P1"),
]),
("RZP", "Razorpay test mode (real gateway, test money)", "Needs Razorpay Test Mode keys + webhook (docs/PAYMENTS-SETUP.md). Untick practice checkout for this module. Never use a real card in test mode.", [
("RZP-01", "Successful payment", "Reader buys Pro Monthly; in Razorpay use test card or UPI success@razorpay", "'Payment received 🎉…'; premium active; receipt email; order in Orders list.", "P1"),
("RZP-02", "Failed payment", "Use UPI failure@razorpay", "Payment fails; no access granted.", "P1"),
("RZP-03", "Closed popup", "Open checkout and close it", "No access; buying again works.", "P2"),
("RZP-04", "Refund removes access", "Razorpay Dashboard (test) → Payments → refund RZP-01's payment", "Within a few minutes the reader's premium is removed (back to what they had before); admin gets an email; order shows refunded.", "P1"),
("RZP-05", "Dispute", "If the test dashboard allows, create a dispute", "Access removed; admin email; order shows disputed.", "P3"),
("RZP-06", "Dollar payment", "Switch to $ and buy", "Works if International Payments is enabled; otherwise a friendly error suggesting ₹.", "P3"),
("RZP-07", "No double grant", "After paying, refresh the success page / press back and forward", "Only one order, one invoice number, one receipt.", "P2"),
("RZP-08", "Bad webhook secret", "Change Webhook Secret in WordPress to a wrong value; make a test payment; then fix it", "Security log shows a bad-signature entry. Restore the right secret afterwards.", "P2"),
]),
("ORD", "Orders and manual access (admin)", "", [
("ORD-01", "Orders list", "wp-admin → Orders after a few purchases", "Each order shows reader, plan, amount, invoice no. and status.", "P2"),
("ORD-02", "Lessons opened count", "Reader opens 3 premium lessons; refresh Orders", "Order shows '3 premium lessons opened' (helps the 30% refund rule).", "P2"),
("ORD-03", "Manual access", "Users → Edit user → 'VitalStack premium access' → untick → Update; then tick again", "Access is removed, then given again.", "P2"),
]),
("TFA", "Authenticator app (two-step security)", "Use Google Authenticator or Microsoft Authenticator on a phone. Keep backup codes safe while testing.", [
("TFA-01", "Turn on", "Account → Two-step security → set up; scan QR or 'Copy key'; enter the 6-digit code", "'Authenticator app is on. Save your backup codes now…' with 10 backup codes and 'Copy all'.", "P1"),
("TFA-02", "Wrong setup code", "During setup type 000000", "Error; app not turned on.", "P2"),
("TFA-03", "Sign in with app", "Sign out; sign in with email + password", "Asks 'Type the code from your authenticator app.'; correct code signs in. No email code is sent.", "P1"),
("TFA-04", "Backup code", "Sign in using one backup code; sign out; try the same backup code again", "First time works; second time is rejected.", "P1"),
("TFA-05", "Email instead", "At the app-code step choose 'Email me a code instead'", "Email code arrives and works.", "P2"),
("TFA-06", "Code can't be reused", "Use a code to sign in; sign out; sign in again with the same code within 30 seconds", "Second use rejected.", "P3"),
("TFA-07", "Lockout", "Enter 5 wrong codes", "15-minute pause; owner gets an email.", "P1"),
("TFA-08", "New backup codes", "'Make new backup codes'; try an old code", "'New backup codes made. The old ones no longer work.' Old code rejected.", "P2"),
("TFA-09", "Turn off", "Turn the app off (needs a code)", "'Authenticator app turned off. Sign-in uses email codes again.'", "P2"),
("TFA-10", "Admin wp-login", "Admin turns the app on; sign in at /wp-login.php", "Field 'Authenticator code (if you turned it on)'; blank or wrong code refused; correct code signs in.", "P1"),
]),
("DEV", "My devices and device limit (premium reader)", "Rule: 1 phone/tablet + 1 computer. A different browser on the same laptop counts as a new computer.", [
("DEV-01", "Device listed", "Open a premium lesson on laptop Chrome; Account → My devices", "Laptop listed with 'Last used … ago'.", "P1"),
("DEV-02", "Phone + computer allowed", "Open a premium lesson on a phone", "Opens normally while the laptop keeps working.", "P1"),
("DEV-03", "Third device", "Open a premium lesson in Firefox/Edge on a second computer", "'Premium is open on another device of this kind.' + 'Use this device'; after the security check: 'Premium is now on X. Y was signed out.' The old browser is signed out.", "P1"),
("DEV-04", "Switch limit", "Switch computers 3 times; try a 4th time within 30 days", "4th switch refused with a clear message.", "P2"),
("DEV-05", "Sign out a device", "My devices → sign out one device; 'Sign out everywhere'", "That device is signed out; then all devices are.", "P2"),
("DEV-06", "No limit for free users / staff", "Free reader and admin open lessons on 3+ browsers", "No device message.", "P2"),
("DEV-07", "Staff new-device alert", "Admin signs in from a new browser", "Alert email: 'Not you? Reset your password now: it signs out every device.'", "P2"),
]),
("PRT", "Premium content protection", "Use a paying reader (not admin) unless stated.", [
("PRT-01", "Text not in page source", "Open a premium lesson; Ctrl+U; search for a sentence from the lesson", "'Opening your premium lesson…' then the lesson appears. The sentence is NOT in the page source.", "P1"),
("PRT-02", "Visible watermark", "Look across the lesson", "Faint 'Name · #ID · date' repeated across the lesson. It doesn't block clicks and does not cover the header or logo.", "P1"),
("PRT-03", "Copy and right-click blocked", "Select text; Ctrl+C; right-click; drag text", "Blocked; toast 'Right-click is turned off on premium lessons.'", "P1"),
("PRT-04", "Code Copy still works", "Click Copy on a code block; paste in Notepad", "Code copies correctly.", "P1"),
("PRT-05", "Print blocked", "Ctrl+P on a premium lesson; then on a free lesson", "Premium text hidden in print preview. Free lessons print normally.", "P2"),
("PRT-06", "Non-buyers locked", "Open the premium lesson signed out, and as a free reader", "Locked / pricing prompt as before. No premium text.", "P1"),
("PRT-07", "Staff see directly", "Open as admin", "Lesson shows instantly, no watermark, no limits.", "P2"),
("PRT-08", "Lesson features still work", "In a premium lesson use animations, quiz, walkthrough, Run code", "All work after the lesson loads.", "P1"),
("PRT-09", "Speed limit (optional)", "Open 41 different premium lessons within one hour", "'You opened a lot of lessons very quickly, so premium is paused…'; security check continues; admin alert.", "P3"),
("PRT-10", "Mobile", "Repeat PRT-01/02/03 on a phone", "Loads, fits screen, no sideways scroll.", "P1"),
]),
("SEC", "Admin security page (Tools → VitalStack Security)", "", [
("SEC-01", "Activity log", "Open the page after the tests above; use Filter", "Sign-ins, failed sign-ins, lesson opens, payments, device switches are listed; filter works.", "P2"),
("SEC-02", "Find who leaked a lesson", "As reader R1 open a premium lesson; F12 → Elements → copy a paragraph's text; admin pastes it into 'Find who leaked a lesson' → 'Read watermark'", "Names reader R1's account.", "P2"),
("SEC-03", "No watermark", "Paste ordinary text and read", "No account found.", "P3"),
("SEC-04", "Suspend", "Suspend reader R2; R2 opens a premium lesson; then un-suspend", "R2 signed out everywhere; 'This account is suspended. Please contact us.'; works again after un-suspend.", "P1"),
]),
("ADS", "Ads and popups", "", [
("ADS-01", "No ads for premium", "Open the same lesson as a free reader and as a premium reader", "Free reader sees ads (when AdSense is on); premium reader sees no ads anywhere.", "P2"),
("POP-01", "Cookie then install pill", "Android Chrome incognito: open site", "Cookie notice first; install-app pill only after the cookie notice is answered.", "P2"),
("POP-02", "Popups stack", "Finish a lesson (Mark complete) on a 320–375px phone", "Celebration, toast and nudges stack; none overlap; no text cut off; close buttons work.", "P2"),
]),
]

REG = [
("REG-01", "Sign up with email code (OTP) and sign in with email code"),
("REG-02", "Forgot password / reset password"),
("REG-03", "Top bar arrows and 'All courses' panel + Find a course filter (desktop + phone)"),
("REG-04", "Course order in 'All courses' and learning paths (web → programming → Java → Data & AI)"),
("REG-05", "Each course on the homepage shows its own example (no repeated HTML example)"),
("REG-06", "Free lessons: text, code Run/Copy, old animations, quizzes"),
("REG-07", "Progress, XP, streak, certificates"),
("REG-08", "Tools: Python compiler (with Pandas/NumPy), SQL playground, A/B test, sample size, CSV explorer, prompt builder"),
("REG-09", "Games, crew characters (Dev, Chip, Bugsy, Byte) and study buddy"),
("REG-10", "Dark / light mode on every page"),
("REG-11", "Search, contact form, newsletter subscribe"),
("REG-12", "Footer links: Privacy, Terms, Contact, Refund policy, Pricing"),
]

SIZES = ["320", "360", "375", "414", "768", "1024", "1366", "1920"]
PAGES = ["Homepage", "All courses panel", "Free lesson", "Premium lesson", "Walkthrough player", "/pricing/ + coupon", "Before-you-buy window", "Practice checkout window", "/account/ (security + devices)", "Sign-in code step", "Tools page", "Popups (cookie, toast, celebration)"]


def steps(s):
    return "<ol>" + "".join(f"<li>{E(x.strip())}</li>" for x in s.split(";") if x.strip()) + "</ol>"


def module_table(code, name, note, cases):
    rows = "".join(
        f"<tr><td class=id>{E(i)}</td><td><b>{E(t)}</b></td><td>{steps(s)}</td><td>{E(x)}</td><td class='pri {p}'>{p}</td><td class=res><span>☐ Pass</span><span>☐ Fail</span><span>☐ Blocked</span></td><td class=note></td></tr>"
        for i, t, s, x, p in cases)
    return f"""<section class=mod><h2><span class=tag>{code}</span>{E(name)}</h2>{f'<p class=lead>{E(note)}</p>' if note else ''}
<table class=tc><thead><tr><th style="width:6%">ID</th><th style="width:13%">Test case</th><th style="width:30%">Steps</th><th style="width:27%">Expected result</th><th style="width:4%">Pri</th><th style="width:8%">Result</th><th style="width:12%">Bug ID / notes</th></tr></thead><tbody>{rows}</tbody></table></section>"""


total = sum(len(m[3]) for m in MODULES)
p1 = sum(1 for m in MODULES for c in m[3] if c[4] == "P1")

summary_rows = "".join(f"<tr><td>{E(c)}</td><td>{E(n)}</td><td class=c>{len(cs)}</td><td></td><td></td><td></td></tr>" for c, n, _, cs in MODULES)
summary_rows += f"<tr><td>REG</td><td>Regression (old features)</td><td class=c>{len(REG)}</td><td></td><td></td><td></td></tr>"

matrix = "<table class=mx><thead><tr><th>Page / screen</th>" + "".join(f"<th>{s}px</th>" for s in SIZES) + "</tr></thead><tbody>" + "".join(
    f"<tr><td>{E(p)}</td>" + "<td>☐</td>" * len(SIZES) + "</tr>" for p in PAGES) + "</tbody></table>"

reg = "<table class=tc><thead><tr><th style='width:8%'>ID</th><th>What to check (must work exactly as before 4.8)</th><th style='width:9%'>Desktop</th><th style='width:9%'>Phone</th><th style='width:18%'>Bug ID / notes</th></tr></thead><tbody>" + "".join(
    f"<tr><td class=id>{i}</td><td>{E(t)}</td><td class=c>☐</td><td class=c>☐</td><td></td></tr>" for i, t in REG) + "</tbody></table>"

CSS = """
@page { size: A4 landscape; margin: 14mm 12mm 14mm 12mm; }
* { box-sizing: border-box; }
body { font-family: 'DejaVu Sans', Arial, sans-serif; color: #1d2433; font-size: 9.2pt; line-height: 1.38; margin: 0; }
h1 { font-size: 26pt; margin: 0 0 6px; color: #1b1f3b; }
h2 { font-size: 14pt; margin: 0 0 6px; color: #1b1f3b; display: flex; align-items: center; gap: 8px; }
h3 { font-size: 11pt; margin: 14px 0 6px; color: #3b2fd9; }
.tag { background: #4f46e5; color: #fff; border-radius: 5px; padding: 2px 8px; font-size: 10pt; }
.lead { color: #4a5568; margin: 0 0 8px; }
.cover { height: 178mm; display: flex; flex-direction: column; justify-content: center; padding: 0 20mm; background: linear-gradient(135deg, #eef0ff, #fdf2ff); border-radius: 14px; page-break-after: always; }
.cover .sub { font-size: 13pt; color: #4a5568; margin-bottom: 18px; }
.cover table { width: 60%; border-collapse: collapse; font-size: 10.5pt; }
.cover td { padding: 5px 8px; border-bottom: 1px solid #d6d9f0; }
.cover td:first-child { color: #6b7280; width: 38%; }
.page { page-break-after: always; }
.cols { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
.box { border: 1px solid #dfe3f0; border-radius: 10px; padding: 10px 12px; background: #fafbff; break-inside: avoid; }
.box h3 { margin-top: 0; }
ul, ol { margin: 3px 0; padding-left: 17px; }
li { margin: 1px 0; }
code { font-family: 'DejaVu Sans Mono', monospace; background: #eef0f7; padding: 0 3px; border-radius: 3px; font-size: 8.4pt; }
pre { font-family: 'DejaVu Sans Mono', monospace; background: #1e2235; color: #e7e9ff; padding: 8px 10px; border-radius: 8px; font-size: 8.4pt; margin: 4px 0; white-space: pre-wrap; }
table { border-collapse: collapse; width: 100%; }
.tc th, .mx th, .sum th, .simple th { background: #1b1f3b; color: #fff; text-align: left; padding: 5px 6px; font-size: 8.6pt; }
.tc td, .mx td, .sum td, .simple td { border: 1px solid #d9dcea; padding: 4px 6px; vertical-align: top; }
.tc tr { break-inside: avoid; }
.tc tbody tr:nth-child(even) td { background: #f7f8fd; }
.tc ol { padding-left: 14px; }
td.id { font-family: 'DejaVu Sans Mono', monospace; font-size: 8.2pt; white-space: nowrap; }
td.pri { font-weight: 700; text-align: center; }
.P1 { color: #c0262d; } .P2 { color: #b45309; } .P3 { color: #4b5563; }
td.res span { display: block; font-size: 8pt; white-space: nowrap; }
.c { text-align: center; }
.mod { page-break-before: always; }
.mx td { text-align: center; } .mx td:first-child { text-align: left; }
.flow { display: flex; gap: 6px; align-items: stretch; margin: 8px 0 12px; }
.flow div { flex: 1; background: #eef0ff; border: 1px solid #c9ccf5; border-radius: 9px; padding: 7px 8px; font-size: 8.6pt; }
.flow div b { display: block; color: #3b2fd9; font-size: 9.6pt; margin-bottom: 2px; }
.flow .arrow { flex: 0 0 14px; background: none; border: 0; padding: 0; align-self: center; text-align: center; color: #6366f1; font-size: 13pt; }
.sign td { height: 22px; } .sum td { padding: 3px 6px; }
"""

body = f"""
<div class=cover>
  <div style="font-size:11pt;color:#4f46e5;font-weight:700;letter-spacing:.08em">VITALSTACK · QA</div>
  <h1>Release 4.8 Test Plan</h1>
  <div class=sub>Test cycle, setup and {total + len(REG)} test cases for everything added in 4.8 (theme zip 4.8.2)</div>
  <table>
    <tr><td>Build under test</td><td>vitalstack-theme-v4.8.2.zip</td></tr>
    <tr><td>Test site</td><td>Staging first, then live (smoke + practice checkout only)</td></tr>
    <tr><td>Scope</td><td>Code walkthroughs · plans & coupons · buy checklist · practice & Razorpay checkout · refunds · authenticator 2FA · devices · content protection · security page · popups · regression</td></tr>
    <tr><td>Test cases</td><td>{total} new-feature cases ({p1} are P1) + {len(REG)} regression checks + responsive matrix</td></tr>
    <tr><td>Tester</td><td>______________________</td></tr>
    <tr><td>Test dates</td><td>______________________</td></tr>
  </table>
</div>

<div class=page>
<h2><span class=tag>1</span>Test cycle</h2>
<p class=lead>Run the phases in order. A phase starts only when the one before it has no open P1 bug.</p>
<div class=flow>
  <div><b>0 · Setup</b>Accounts, settings, coupons, devices (section 2)</div><div class=arrow>→</div>
  <div><b>1 · Smoke</b>SMK cases. Stop on any P1 fail</div><div class=arrow>→</div>
  <div><b>2 · Functional</b>WLK, PRC, CPN, CHK, DEM, ORD, TFA, DEV, PRT, SEC, ADS</div><div class=arrow>→</div>
  <div><b>3 · Payments</b>RZP with Razorpay Test Mode keys</div><div class=arrow>→</div>
  <div><b>4 · Responsive</b>Matrix: 320px to 1920px, light + dark</div><div class=arrow>→</div>
  <div><b>5 · Regression</b>REG list: old features unchanged</div><div class=arrow>→</div>
  <div><b>6 · Retest & sign-off</b>Re-run failed cases on the fixed zip</div>
</div>
<div class=cols>
 <div class=box><h3>Priority</h3><ul>
  <li><b class=P1>P1</b>: must pass before release (login, payment, access, protection, layout).</li>
  <li><b class=P2>P2</b>: should pass; release only with a known workaround.</li>
  <li><b class=P3>P3</b>: nice to have; can go to the next version.</li></ul>
 <h3>Exit criteria (release OK)</h3><ul>
  <li>100% of P1 cases pass; at least 95% of P2 pass.</li>
  <li>No open Blocker or Critical bug.</li>
  <li>Responsive matrix has no ☒ on P1 screens.</li>
  <li>All regression checks pass on desktop and phone.</li></ul></div>
 <div class=box><h3>How to report a bug</h3><ul>
  <li><b>Bug ID</b>: BUG-001, BUG-002 … (write it in the test case's notes column).</li>
  <li><b>Test case ID</b> and <b>page URL</b>.</li>
  <li><b>Device + browser</b> (e.g. iPhone 13 Safari, Windows Chrome 130) and screen width.</li>
  <li><b>Steps</b>, <b>expected</b>, <b>actual</b>.</li>
  <li><b>Screenshot or screen recording</b>; for errors also F12 → Console screenshot.</li>
  <li><b>Severity</b>: Blocker (site/payment down) · Critical (feature broken, no workaround) · Major · Minor (looks/text).</li></ul>
 <h3>Golden rules</h3><ul>
  <li>Never use a real card while Razorpay is in Test Mode.</li>
  <li>After changing any setting: LiteSpeed → Purge All, then hard refresh.</li>
  <li>Use incognito windows to act as different readers.</li></ul></div>
</div>
</div>

<div class=page>
<h2><span class=tag>2</span>Setup before testing</h2>
<div class=cols>
 <div class=box><h3>2.1 Accounts</h3>
 <table class=simple><tr><th>Name</th><th>Role</th><th>Used for</th></tr>
 <tr><td>A</td><td>Administrator</td><td>Settings, Orders, Security page, wp-login 2FA</td></tr>
 <tr><td>T</td><td>Reader, email in 'Test buyer emails'</td><td>Practice checkout purchases</td></tr>
 <tr><td>R1</td><td>Reader (new, real inbox)</td><td>Checklist, coupons, devices, leak finder</td></tr>
 <tr><td>R2</td><td>Reader (new, real inbox)</td><td>Max-use coupon, suspend</td></tr>
 <tr><td>F</td><td>Free reader (never buys)</td><td>Locked lessons, ads</td></tr></table>
 <h3>2.2 Devices and browsers</h3><ul>
  <li>1 Android phone (Chrome) and 1 iPhone (Safari) if possible.</li>
  <li>1 laptop with Chrome + Firefox (or Edge). The second browser acts as a 'second computer'.</li>
  <li>An authenticator app (Google or Microsoft Authenticator) on the phone.</li>
  <li>Desktop Chrome DevTools device mode for the widths in the matrix.</li></ul>
 </div>
 <div class=box><h3>2.3 Admin settings (Settings → VitalStack Payments)</h3><ul>
  <li>Tick <b>Open: readers can buy premium plans</b>.</li>
  <li>Mode: <b>Test</b>. Test keys only needed for module RZP.</li>
  <li>Tick <b>Let me test buying without Razorpay and without real money</b> (practice checkout).</li>
  <li><b>Test buyer emails</b>: add tester T's email (one per line).</li></ul>
 <h3>2.4 Test coupons (Coupons box, one per line)</h3>
 <pre>TEST20 | * | 20% | 0 |
YEAR1999 | all-year | 1999 | 0 | 2030-12-31
ONEUSE | all-month | 10% | 1 |
OLDCODE | * | 50% | 0 | 2024-01-01</pre>
 <p>Format: <code>CODE | plans | 20% or fixed ₹ | max uses (0 = no limit) | expiry YYYY-MM-DD</code>. Plan names: <code>all-month</code>, <code>all-year</code>, <code>lifetime</code>, <code>data-scientist</code>, <code>gen-ai-developer</code>, <code>agentic-ai-engineer</code>, or <code>*</code> for all.</p>
 <h3>2.5 After testing (clean-up)</h3><ul>
  <li>Untick practice checkout, clear test buyer emails, delete test coupons.</li>
  <li>Remove premium from test accounts (Users → Edit user) or delete them.</li>
  <li>For live sales: Mode Live + live keys + live webhook (docs/PAYMENTS-SETUP.md).</li></ul></div>
</div>
</div>
""" + "".join(module_table(*m) for m in MODULES) + f"""
<section class=mod><h2><span class=tag>RSP</span>Responsive matrix</h2>
<p class=lead>For each box check: no sideways scroll · logo small in header · text not cut off · buttons tappable · popups/windows fit · works in dark mode. Tick ☑ for OK, write the bug ID if not.</p>
{matrix}</section>

<section class=mod><h2><span class=tag>REG</span>Regression: old features must not change</h2>
<p class=lead>4.8 was built as additions only. Any change in how these old features look or work is a bug.</p>
{reg}</section>

<section class=mod><h2><span class=tag>✓</span>Test summary and sign-off</h2>
<table class=sum><thead><tr><th style="width:8%">Module</th><th>Area</th><th style="width:8%">Cases</th><th style="width:10%">Pass</th><th style="width:10%">Fail</th><th style="width:10%">Blocked</th></tr></thead><tbody>{summary_rows}
<tr><td colspan=2><b>Total</b></td><td class=c><b>{total + len(REG)}</b></td><td></td><td></td><td></td></tr></tbody></table>
<div class=cols><div><h3>Open bugs at sign-off</h3>
<table class="simple sign"><tr><th style="width:12%">Bug ID</th><th>Summary</th><th style="width:14%">Severity</th><th style="width:16%">Status</th></tr>{'<tr><td></td><td></td><td></td><td></td></tr>' * 4}</table></div><div><h3>Decision</h3>
<table class="simple sign"><tr><th style="width:25%">Role</th><th>Name</th><th style="width:22%">Release OK? (Yes / No)</th><th style="width:18%">Date</th></tr>
<tr><td>Tester</td><td></td><td></td><td></td></tr><tr><td>Site owner</td><td></td><td></td><td></td></tr></table></div></div>
</section>
"""

OUT.write_text(f"<!doctype html><html><head><meta charset=utf-8><title>VitalStack 4.8 Test Plan</title><style>{CSS}</style></head><body>{body}</body></html>", encoding="utf-8")
print(OUT, total, p1)
