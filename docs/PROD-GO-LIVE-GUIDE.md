# VitalStack PROD go-live guide (4.20.2)

Is guide ko upar se neeche, step by step follow kijiye. Har step ke saath likha hai ki kahan click karna hai.
Bracket mein likhe naam (jaise **Settings → Reading**) WordPress admin ke asli menu ke naam hain.

**Files jo chahiye (sab is release ke saath diye gaye hain):**

| File | Kya hai |
|---|---|
| `vitalstack-theme-v4.20.2.zip` | Theme (upload over 4.12.x – 4.20.1) |
| `vitalstack-growth-1.0.0.zip` | Growth Desk: landing offers + Sales, Marketing, Testing & Security agents |
| `vitalstack-adsense-helper-1.0.1.zip` | AdSense readiness scanner (Tools → AdSense Helper) |
| `vitalstack-marketing-1.0.0.zip` | Offer emails, sirf un users ko jinhone haan kaha |
| `vitalstack-festive-1.0.0.zip` | Festival look, date ke hisaab se apne aap |

---

## Step 0: Backup (5 minute)

1. hPanel → **Websites → Manage → Backups** → "Create backup" (PROD aur UAT dono).
2. Backup complete hone tak aage mat badhiye.

## Step 1: WordPress basic settings (PROD par)

1. **Settings → General**: dono address `https://vitalstack.co.in` (https ke saath).
2. **Settings → Permalinks**: "Post name" chuniye → **Save**. (Isse `/study-plan/`, `/playground/`, `/pricing/` chalte hain.)
3. **Settings → Reading**: "Discourage search engines from indexing this site" **untick** → Save.
   - UAT par ye tick hai, isliye UAT par sitemap nahi dikhta. Ye sahi hai. PROD par untick hona zaroori hai, warna Google aur AdSense site dekh hi nahi paayenge.
4. **Settings → General → Timezone**: "Kolkata" rakhiye.
   - Note: ye setting sirf site ka time (schedules, emails) badalti hai. **₹/$ price isse nahi badalta**: price visitor ke apne device/country se decide hota hai (Step 7 dekhiye).

## Step 2: Theme aur plugins upload

1. **Appearance → Themes → Add New → Upload Theme** → `vitalstack-theme-v4.20.2.zip` → **Replace current with uploaded**.
2. **Plugins → Add New → Upload Plugin**: chaaron plugin zips (Growth Desk, AdSense Helper, Marketing, Festive) upload karke **Activate** kijiye.
3. LiteSpeed Cache → **Purge All**, aur Hostinger CDN cache bhi purge kijiye.

## Step 3: Pages aur content

1. **Appearance → VitalStack Setup** → **Create starter structure**. Ye missing pages aur menus banata hai; jo pehle se hai use nahi chhedta.
2. Usi screen par **Import starter content** → "Publish" mode. Naye lessons judte hain, purane kabhi overwrite nahi hote.
3. **Legal pages publish kijiye (AdSense aur Razorpay dono ke liye zaroori):**
   - **Pages → All Pages** mein ye pages **Draft** mein milenge:
     - About Us;
     - Editorial Policy;
     - Privacy Policy;
     - Terms of Service;
     - Disclaimer;
     - Refund Policy;
     - Cookie Policy.
   - Ye jaan-boojh kar draft banaye jaate hain, taaki aap ek baar padh lein.
   - Har page kholiye, padhiye, **[square bracket]** wali jagah apni sahi baat likhiye, phir **Publish**.
   - **About Us** mein ek line aapke baare mein hai: `[one honest line about you…]`. Ise apni asli line se badliye, jaise "VitalStack is run by … from …". Iske bina AdSense "unfinished site" maan sakta hai.
   - UAT par abhi ye pages draft hain, isliye `/privacy-policy/`, `/about-us/` 404 dete hain. Publish karte hi footer mein link aa jaayenge.

## Step 4: Email inbox mein jaaye, spam mein nahi

**Spam ki asli wajah:**

- UAT `orchid-toad-489394.hostingersite.com` ek **temporary Hostinger domain** hai. Is domain se gaya har email (OTP ho ya offer) Gmail aksar spam mein daalta hai, kyunki domain ka koi email record (SPF/DKIM/DMARC) nahi hota.
- Theme ki taraf se sab kuch pehle se laga hai: plain-text copy, sahi Message-ID, Return-Path, aur offer emails mein one-click unsubscribe.
- Bacha hua kaam domain aur DNS ka hai, jo sirf hPanel mein hota hai.

**PROD par ye kijiye:**

1. **Mailbox banaiye:** hPanel → **Emails** → `vitalstack.co.in` → ek mailbox, jaise `no-reply@vitalstack.co.in` (strong password).
2. **DNS records:** hPanel → **Emails → (domain) → DNS / Email deliverability** (Hostinger isse "Connect domain" bhi kehta hai).
   - **SPF, DKIM aur DMARC** teeno **green / Active** hone chahiye.
   - Agar domain Hostinger nameservers par hai to "Auto-configure" ek click mein kar deta hai.
   - DMARC record na ho to DNS mein ek TXT record jodiye:
     - Name: `_dmarc`
     - Value: `v=DMARC1; p=none; rua=mailto:no-reply@vitalstack.co.in`
3. **SMTP plugin:** **Plugins → Add New** → "WP Mail SMTP" install aur activate kijiye. Settings:

   | Setting | Value |
   |---|---|
   | From Email | `no-reply@vitalstack.co.in` (tick: Force From Email) |
   | From Name | VitalStack |
   | Mailer | Other SMTP |
   | SMTP Host | `smtp.hostinger.com` |
   | Encryption | SSL |
   | Port | `465` |
   | Authentication | On |
   | Username | poora email address |
   | Password | mailbox ka password |

   **Save** kijiye.
4. **Tools → Email check** (theme ka) kholiye: saari lines green honi chahiye. Wahin se test email bhejiye.
5. **Score check:** mail-tester.com kholiye, wahan ka address copy kijiye, aur **Tools → Email check** se us address par test bhejiye. Score **9/10 ya zyada** aana chahiye.
6. **Gmail ke tabs:** offer/marketing emails Gmail ke **Promotions** tab mein jaana normal hai. Ye spam nahi hai, Gmail har offer email ke saath aisa karta hai. OTP aur receipts Primary mein aayenge.
7. **Gmail se mat bhejiye:** `@gmail.com` address ko kabhi "From" mat banaiye. Hamesha apne domain ka address use kijiye.

## Step 5: Razorpay (pehle test, phir live)

### 5a. Account (ek baar)

1. razorpay.com par sign up karke **KYC** poori kijiye: PAN, bank account, aur GST sirf agar registered hain.
2. Razorpay website review karta hai. Usse pehle Step 3 ke legal pages **published** hone chahiye, khaas kar:
   - Privacy;
   - Terms;
   - **Refund Policy**;
   - Contact;
   - `/pricing/`.
3. "Account activated" ka email aane tak sirf Test Mode chalega.

### 5b. Test mode (koi asli paisa nahi)

1. Razorpay Dashboard → upar **Test Mode** on → **Account & Settings → API Keys → Generate Key**. Do cheezein milengi: **Key ID** (`rzp_test_…`) aur **Key Secret**.
2. WordPress → **Settings → VitalStack Payments**:
   - **Sales**: "Open" tick;
   - **Mode**: Test;
   - **Test Key ID** aur **Test Key Secret** paste kijiye;
   - **Currency**: "Automatic: ₹ for readers in India, $ for everyone else (recommended)";
   - **Save**.
3. **Webhook** (payment confirm karne ke liye zaroori):
   - Razorpay → **Account & Settings → Webhooks → Add New Webhook**.
   - **Webhook URL**: Settings → VitalStack Payments page par jo URL likha hai wahi copy kijiye (end mein `/wp-json/vitalstack/v1/pay/webhook` hota hai).
   - **Secret**: koi lamba random password banaiye, aur wahi WordPress ke **Webhook Secret** box mein bhi paste kijiye.
   - **Events** tick kijiye:
     - `payment.captured`;
     - `order.paid`;
     - `payment.failed`;
     - `refund.processed`;
     - `payment.dispute.created`.
4. **Test purchase:** ek normal reader account se (admin se nahi) `/pricing/` kholiye aur sabse sasta plan buy kijiye.
   - UPI: `success@razorpay` (sabse aasaan);
   - ya card: Razorpay docs ka **"Test Card Details"** page jo test card number deta hai, koi bhi future date aur koi bhi CVV.
   - Result: "Payment received" dikhna chahiye, receipt email aana chahiye, aur order **Orders** mein dikhna chahiye.

### 5c. Live

1. Razorpay → **Live Mode** → API Keys → naye live keys (`rzp_live_…`).
2. **Settings → VitalStack Payments** → **Mode: Live** → live Key ID aur Secret paste → Save.
3. Live Mode mein webhook **dobara** add kijiye: test aur live ke webhooks alag hote hain. Same URL, same events, aur naya secret jo WordPress mein bhi save ho.
4. Khud ek baar sabse sasta plan asli paise se lijiye, phir Razorpay Dashboard se refund kar dijiye. Is test se pakka ho jaata hai ki asli payment chal raha hai.
5. **$ payments (international visitors ke liye):** Razorpay → **Account & Settings → International payments** → Enable (Razorpay kuch extra documents maang sakta hai). Jab tak ye on nahi hota, $ wale visitor ko friendly message milta hai ki ₹ mein pay karein.

**Safety (pehle se laga hai):**

- Price hamesha server decide karta hai, browser nahi.
- Access sirf Razorpay ka signature verify hone ke baad milta hai.
- Card/UPI details aapki site par aati hi nahi.

## Step 6: Festive plugin

UAT par Festive plugin abhi **"Force: Diwali"** par set hai, isliye aaj (Diwali ke baad) bhi "Diwali Sale" dikh raha hai. PROD par:

1. **Settings → VitalStack Festive** kholiye.
2. Mode **Auto** chuniye. Festival look apne aap sahi dates par aayega aur jaayega.

## Step 6b: Growth Desk: offer aur agents (10 minute)

1. **Growth Desk → Offer:**
   - **Offer name**: jaise "Launch Sale".
   - **Offer ends**: wahi date daaliye jo aap sach mein nibhayenge. Countdown sirf isi se dikhta hai, aur offer khatam hote hi apne aap hat jaata hai.
   - **Regular prices**: har plan ka wo price jo offer ke baad lagega (jaise Pro Yearly: ₹2,999 / $149). Isse site par ~~purana price~~ aur "Save X%" dikhta hai. Jo price aap baad mein sach mein nahi lenge, wo mat daaliye: fake discount kanoon ke khilaaf hai.
   - **Save** kijiye, phir "Preview the home page as a visitor" se dekh lijiye.
2. **Growth Desk → Testing & Security → Run all checks now**:
   - jo red ho, uska "How to fix" follow kijiye;
   - roz ka check aur Monday report apne aap email aayenge.
3. **Growth Desk → Marketing**:
   - share kit se roz ek message WhatsApp, LinkedIn, Instagram, X ya Threads par daaliye;
   - links tracked hain, isliye Analytics mein dikhega kaunsa post kaam kar raha hai.
4. **Email list:**
   - sign-up ka "Email me new lessons, guides and member offers" box ab pehle se ticked hota hai (Europe/UK ke visitors ke liye nahi, wahan GDPR allow nahi karta);
   - unsubscribe hamesha sach mein kaam karta hai (kanoon, aur Gmail ke rules ke liye zaroori);
   - kisi ek reader ko aap khud **Growth Desk → Marketing → Subscribers** se subscribe ya unsubscribe kar sakte hain.

## Step 7: ₹/$ price kaise test karein

Price visitor ki location se decide hota hai:

1. pehle country header (Cloudflare lagane par);
2. warna device ka timezone;
3. India = ₹, baaki sab = $.

WordPress ka timezone isse koi lena-dena nahi rakhta, isliye usse badalne par price nahi badla.

**$ dekhne ke liye (2 minute):**

1. Chrome mein **Incognito** window kholiye (sign out wali).
2. Site kholiye → **F12** (DevTools) → upar ke ⋮ menu → **More tools → Sensors**.
3. **Location** mein "London" ya "San Francisco" chuniye.
4. Page **reload** kijiye: `/pricing/` par $12 / $39 / $99 / $149 dikhega. "Mumbai" chunne par ₹ wapas aa jaayega.

Maine UAT par ye test kiya: New York aur London ke visitor ko $ aur India wale ko ₹ dikha.

## Step 8: AdSense

1. **Tools → AdSense Helper → Run check** (1.0.1). Saari lines green honi chahiye, sirf "AdSense code and ads.txt" red rahegi jab tak account nahi banta.
   - Is version mein plugin code examples ko galti se "placeholder" ya "broken link" nahi maanta.
   - Lessons mein jo sections theme jodta hai (charts, edge cases), plugin unke words bhi ginta hai.
2. **Google Search Console** (search.google.com/search-console):
   - Domain add karke verify kijiye.
   - **Sitemaps** mein `https://vitalstack.co.in/wp-sitemap.xml` submit kijiye.
3. 1–2 hafte mein pages index hone dijiye. Is beech roz 1–2 naye posts ya lessons publish kijiye aur social par share kijiye.
4. **AdSense account:** adsense.google.com → site add kijiye → wahan ka **publisher ID** (`ca-pub-…`) copy kijiye.
5. **Appearance → Customize → VitalStack: Google AdSense** → ID paste → **Publish**. Theme khud har page par AdSense code lagata hai aur `/ads.txt` banata hai.
6. AdSense mein **"Request review"**. Review mein kuch din se 2–4 hafte lagte hain.
7. **Kabhi mat kijiye:**
   - apne ads par khud click;
   - kisi ko click karne ko kehna;
   - copied content;
   - "click here for ads" jaise buttons.

   Inse account band ho jaata hai.

**Seedhi baat:** AdSense approval ki guarantee koi nahi de sakta, ye Google ka faisla hai. Lekin approval ke liye jo cheezein chahiye wo is release mein taiyaar hain:

- original content, 500+ lessons;
- policy pages;
- fast pages (UAT par median 0.7 second);
- mobile-friendly design;
- ads.txt;
- sitemap;
- koi placeholder content nahi.

## Step 9: Traffic aur course sales (pehle mahine ka plan)

- **Roz:**
  - ek lesson ya interview problem ko social par share kijiye (Instagram, LinkedIn, X, Threads; links footer mein hain);
  - chart wale lessons ke screenshots achhe posts bante hain.
- **International traffic:**
  - Search Console mein kaunse pages US/UK se aa rahe hain, wo dekhte rahiye;
  - interview problems (ab 4 languages mein) aur free tools international search mein achhe chalte hain;
  - Reddit (r/learnprogramming, r/datascience) aur dev.to par helpful answers ke saath link dijiye, spam nahi.
- **Sales:**
  - `/pricing/` par offer strip aur launch price pehle se hai;
  - premium lessons ka preview aur Module guide free visitors ko dikhte hain, saath mein plans ka link;
  - Marketing plugin sirf un sign-ups ko offer email bhejta hai jinhone haan kaha hai.
- **Word of mouth:** invite program (account page) se doston ko bulane par reward milta hai. Har khush learner ko ek baar invite link share karne ko kahiye.

## Step 10: Security (zaroor kijiye)

1. Site ke aage **Cloudflare** (free) lagaiye: Bot Fight Mode on, managed WAF rules on. Isse country header bhi milta hai, jisse ₹/$ aur sahi ho jaata hai.
2. Har admin account par **Two-factor login** lagaiye: site par sign in → **Account** page (`/account/`) → two-factor section → Google/Microsoft Authenticator se QR scan kijiye aur backup codes sambhal kar rakhiye.
3. WordPress, PHP (8.1+), plugins aur theme updated rakhiye. Jo plugin use nahi hota use delete kijiye.
4. hPanel → **Backups** daily rakhiye, aur ek copy server ke bahar bhi rakhiye.
5. hPanel → **Security → Malware scanner** on rakhiye.

## Step 11: Live hone ke baad 10-minute check

1. Home page phone aur laptop dono par kholiye.
2. Header mein **🧪 Website Builder** link dikhna chahiye; **Free Tools** page par pehla card bhi wahi hai. `/playground/` par template khulna chahiye aur "Download HTML" kaam karna chahiye.
3. Kisi bhi interview problem (jaise Two Sum) mein solution ke **Python | Java | C++ | JavaScript** tabs.
4. Machine Learning ka koi free lesson (jaise "Train/Test Split") kholiye: "Summary" se pehle **"See it as a chart"** section aur chart.
5. `/pricing/` Incognito mein India location par ₹, aur London par $ (Step 7).
6. Sign up kijiye: OTP email **Inbox** mein aana chahiye (Step 4).
7. Footer mein Privacy, Terms, About links dikhne chahiye (Step 3).
8. **Tools → AdSense Helper → Run check** green ho.
9. **Ek device ka niyam:** ek reader account se phone par login kijiye, phir laptop par. Laptop par "You're signed in on this device" aayega, aur phone par 1–2 minute mein (ya page kholte hi) "You were signed out on this device". Admin account par ye niyam lagu nahi hota.
10. Home page par (Incognito) **deal card**: price, ~~regular price~~, Save %, countdown. **Growth Desk → Testing & Security** green ho.
