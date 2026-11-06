---
title: "Signature Project 5: Scam SMS Shield with Claude"
slug: signature-scam-sms-shield
subject: ai-signature
lesson: 1
difficulty: advanced
excerpt: Build a scam-message detector for Indian SMS and WhatsApp texts. Start with fast rules, prove they break on new Hinglish scams, mask personal data, add Claude as a careful second opinion, and measure everything on a held-out test set.
---

India loses thousands of crores a year to message scams: fake KYC links, "digital arrest" calls, task-job traps and lottery fees. A good scam shield must be **fast, private and honest about its mistakes**. In this project you build one in Python, step by step. You also learn the habit that separates real AI engineers from demo builders: **a held-out test set that your rules and prompts have never seen**.

::scene Building a scam shield
📩 | The flood | KYC links, lottery fees, fake jobs, "send money urgently". In English and Hinglish. | dev
📏 | Rules first | Fast and free: suspicious links, pressure words, requests for money. | chip
🧪 | The reality check | On new Hinglish scams, the rules catch nothing. | bugsy
🤖 | Claude's turn | Personal data masked, a clear prompt, a JSON answer with a reason. | byte
::end

## A labelled set of messages

Every AI feature starts with **examples you trust**. Here are 24 messages (1 = scam, 0 = genuine), written in the style of real Indian SMS. Numbers are masked with x.

```python
SMS = [
 ("Dear customer, your YONO account will be blocked today. Update PAN now: http://sbi-kyc-update.online", 1),
 ("Part-time job! Earn Rs 3000/day by liking YouTube videos. WhatsApp 98xxxxxx21 now", 1),
 ("Rs 2,500.00 debited from a/c XX4521 on 03-Nov by UPI. Not you? Call the number on the back of your card.", 0),
 ("Congratulations! You won Rs 25 lakh in KBC lottery. Pay Rs 12,500 processing fee to claim.", 1),
 ("Hi it's me, new number. Phone broke. Send Rs 8,000 on UPI urgently, will return tonight.", 1),
 ("Your order #4821 from Tasty Biryani is out for delivery. Track it in the app.", 0),
 ("Mumbai Cyber Police: a parcel in your name has drugs. Join video call now or face digital arrest.", 1),
 ("Your EPF passbook is updated. Check on the UMANG app or epfindia.gov.in.", 0),
 ("Electricity will be disconnected tonight 9:30 pm as last bill not updated. Call officer 98xxxxxx45 immediately.", 1),
 ("Parcel held due to incomplete address. Pay Rs 25 to reschedule: http://indiapost.track-parcel.top", 1),
 ("G-482913 is your Google verification code. Don't share it with anyone.", 0),
 ("Your PNR 4521873390 is CONFIRMED. Coach B3, berth 42. Happy journey!", 0),
 ("Your account will be deleted for copyright violation. Appeal in 24 hours: meta-support-appeal.com/login", 1),
 ("Your Jio plan expires in 2 days. Recharge in the MyJio app to continue.", 0),
 ("Earn by rating hotels! Rs 150 per review. Deposit Rs 5,000 to unlock premium tasks.", 1),
 ("Exam timetable for Semester 5 is published on the college portal.", 0),
 ("Your Aadhaar is linked to illegal activity. Press 9 to speak to an officer.", 1),
 ("OTP for your Rs 1,299 payment at Amazon is 552901. Valid for 10 minutes. Do not share.", 0),
 ("Get instant loan of Rs 5 lakh without documents! Install app: bit.ly/fastloan-apk", 1),
 ("Reminder: your dentist appointment is tomorrow at 11 am. Reply C to cancel.", 0),
 ("Your credit card reward points of 4,500 expire today. Redeem now: http://hdfc-rewards.click", 1),
 ("Salary of Rs 42,000 credited to your account XX1209. Available balance Rs 58,310.", 0),
 ("Dear user, your SIM will be blocked in 2 hours due to KYC. Call 7xxxxxxx02 to verify.", 1),
 ("Your Swiggy refund of Rs 180 has been processed to the original payment method.", 0),
]
print(len(SMS), "messages,", sum(label for _, label in SMS), "scams")
```

Output:

```text
24 messages, 13 scams
```

## Rules: fast, free and explainable

Each rule looks for one red flag and adds points. A link to a domain that is not an official `.gov.in`, `.co.in` or bank domain is worth 2 points, because it is the strongest sign.

```python
import re

RULES = [
    (r"https?://(?![\w.-]*\.(gov\.in|sbi|co\.in)\b)\S+|bit\.ly|\.apk\b", 2, "suspicious link"),
    (r"\b(blocked|disconnected|deleted|suspend\w*|arrest|illegal|expire[sd]? today|within \d+ hours|in \d+ hours|immediately|urgent\w*)\b", 1, "pressure words"),
    (r"\b(won|lottery|prize|reward points|earn rs|earn by|per review|instant loan)\b", 1, "too good to be true"),
    (r"\b(pay|deposit|send) rs [\d,]+", 1, "asks for money"),
    (r"\b(kyc|pan|aadhaar)\b", 1, "ID or KYC bait"),
    (r"\b(whatsapp|call|press \d)\b.*\d{2}x+\d{2}|press \d", 1, "unknown number"),
]

def rule_score(text):
    t = text.lower()
    hits = [(points, why) for pattern, points, why in RULES if re.search(pattern, t)]
    return sum(p for p, _ in hits), [why for _, why in hits]

print(rule_score("Pay Rs 25 to reschedule: http://indiapost.track-parcel.top"))
print(rule_score("Your PNR 4521873390 is CONFIRMED. Coach B3, berth 42."))
```

Output:

```text
(3, ['suspicious link', 'asks for money'])
(0, [])
```

Every decision comes with **reasons**, which matters: a shield that says "scam: suspicious link, asks for money" teaches the user something.

## Measure, don't guess

One function scores any classifier on any labelled set, so rules and Claude are judged the same way.

```python
def evaluate(classify, data):
    tp = fp = fn = 0
    for text, label in data:
        flagged = classify(text)
        tp += flagged and label
        fp += flagged and not label
        fn += (not flagged) and label
    return {"caught": tp, "of": tp + fn, "false_alarms": fp,
            "precision": round(tp / max(tp + fp, 1), 2), "recall": round(tp / max(tp + fn, 1), 2)}

rules_flag = lambda text: rule_score(text)[0] >= 1
print("rules on our 24 messages:", evaluate(rules_flag, SMS))
```

Output:

```text
rules on our 24 messages: {'caught': 13, 'of': 13, 'false_alarms': 0, 'precision': 1.0, 'recall': 1.0}
```

Perfect scores! That should make you **suspicious, not happy**. The rules were written while looking at these same messages, so they are tuned to them. The real test is messages they have never seen.

## The reality check: new Hinglish scams

```python
NEW = [
 ("Aapka bijli connection aaj raat kat jayega. Turant is number pe call karein: 9xxxxxxx11", 1),
 ("Bhai mera account freeze ho gaya, 5000 bhej do Google Pay pe, kal lauta dunga", 1),
 ("Congratulations, aapne iPhone jeeta hai! Delivery charge Rs 99 bharein: tinyurl.com/iph0ne-gift", 1),
 ("Work from home: Telegram pe join karo, roz 2000 kamao. Pehle 1000 invest karna hoga", 1),
 ("As requested, your debit card ending 4421 has been blocked. Call your branch to issue a new one.", 0),
 ("Your electricity bill of Rs 1,240 is due on 15 Nov. Pay in the official app to avoid late fee.", 0),
 ("Aapka OTP 448210 hai. Kisi ke saath share na karein. - SBI", 0),
 ("Class kal 9 baje se online hogi, link teacher WhatsApp group me bhejenge.", 0),
]
print("rules on NEW messages:", evaluate(rules_flag, NEW))
for text, label in NEW:
    print(label, rule_score(text)[0], "|", text[:48])
```

Output:

```text
rules on NEW messages: {'caught': 0, 'of': 4, 'false_alarms': 1, 'precision': 0.0, 'recall': 0.0}
1 0 | Aapka bijli connection aaj raat kat jayega. Tura
1 0 | Bhai mera account freeze ho gaya, 5000 bhej do G
1 0 | Congratulations, aapne iPhone jeeta hai! Deliver
1 0 | Work from home: Telegram pe join karo, roz 2000
0 1 | As requested, your debit card ending 4421 has be
0 0 | Your electricity bill of Rs 1,240 is due on 15 N
0 0 | Aapka OTP 448210 hai. Kisi ke saath share na kar
0 0 | Class kal 9 baje se online hogi, link teacher Wh
```

The rules catch **none of the four** new scams and raise a false alarm on a genuine "card blocked as requested" message. Scammers change wording every week, and Hinglish breaks English keyword lists. This is exactly where a language model helps: it understands *intent* ("pressure + money + unknown contact"), not just keywords.

## Mask personal data before it leaves the phone

Messages contain phone numbers, account digits and OTPs. Before sending anything to an API, mask what the model does not need.

::walk python | Masking numbers before calling an API
text = "OTP 448210 for a/c XX4521, call 9876543210"
masked = re.sub(r"\d{4,}", "[NUM]", text)
print(masked)
::notes
1: A message with an OTP, account digits and a phone number. // vars: text="OTP 448210 for a/c XX4521, call 9876543210"
2: Every run of 4 or more digits becomes [NUM]. // deep: The model only needs to know that a number is there, not which number. Masking first is privacy by design: if logs leak, they contain no OTPs or phone numbers. // vars: masked="OTP [NUM] for a/c XX[NUM], call [NUM]"
3: Safe to send. // out: OTP [NUM] for a/c XX[NUM], call [NUM]
::end

```python
def mask(text):
    text = re.sub(r"https?://\S+", lambda m: m.group(0) if len(m.group(0)) < 80 else "[LONG-LINK]", text)
    return re.sub(r"\d{4,}", "[NUM]", text)

print(mask("Rs 2,500.00 debited from a/c XX4521. OTP 552901. Call 9876543210"))
```

Output:

```text
Rs 2,500.00 debited from a/c XX[NUM]. OTP [NUM]. Call [NUM]
```

Links stay (they are the main evidence), and amounts with commas stay readable, but OTPs, account digits and phone numbers are gone.

## Claude as a careful second opinion

Now we ask Claude, with a prompt that explains the job, asks for **JSON only**, and lets it say "unsure". Set your `ANTHROPIC_API_KEY` environment variable first (never paste keys into code).

```python
import json
import anthropic

client = anthropic.Anthropic()          # reads ANTHROPIC_API_KEY from the environment

SYSTEM = """You check SMS and WhatsApp messages sent to people in India for scams.
Messages may be in English, Hindi or Hinglish. Numbers are masked as [NUM].
Common scams: fake KYC or account-block links, lottery or prize fees, task or part-time
job deposits, fake police or "digital arrest", electricity cut threats, relatives asking
for urgent money from new numbers, loan or reward links, and app (.apk) downloads.
Genuine messages: bank alerts that do not ask you to click or call an unknown number,
OTPs that say "do not share", delivery and booking updates.
Reply with JSON only: {"verdict": "scam" | "genuine" | "unsure", "reason": "<one short sentence>"}"""

def claude_check(text):
    response = client.messages.create(
        model="claude-opus-5-5",
        max_tokens=1000,
        system=SYSTEM,
        messages=[{"role": "user", "content": mask(text)}],
    )
    reply = next(b.text for b in response.content if b.type == "text")
    try:
        return json.loads(reply)
    except json.JSONDecodeError:
        return {"verdict": "unsure", "reason": "Could not read the model's answer."}

claude_flag = lambda text: claude_check(text)["verdict"] != "genuine"   # unsure counts as a warning
print("Claude on NEW messages:", evaluate(claude_flag, NEW))
```

Run it and write your numbers in your README. Three habits from this block are worth more than the code:

1. **"Unsure" is allowed** and treated as a warning. A shield that is forced to choose will guess.
2. **Bad JSON never crashes the app**: it falls back to "unsure".
3. **Both test sets, every time.** Re-run `evaluate` on `SMS` and `NEW` whenever you change the prompt. If a prompt change improves NEW but breaks SMS, you have learned something.

## Put it together: rules first, Claude for the rest

Calling a model for every message costs money and time. Obvious scams (a suspicious link plus another flag) are caught by rules instantly. Everything else goes to Claude.

```python
def shield(text, ask_model=None):
    score, reasons = rule_score(text)
    if score >= 3:
        return "scam", "Rules: " + ", ".join(reasons)
    if ask_model is None:
        return ("check", "Rules: " + ", ".join(reasons)) if score else ("genuine", "No red flags found")
    answer = ask_model(text)
    return answer["verdict"], "Model: " + answer["reason"]

for text, _ in SMS[:4]:
    print(shield(text), "|", text[:40])
```

Output:

```text
('scam', 'Rules: suspicious link, pressure words, ID or KYC bait') | Dear customer, your YONO account will be
('check', 'Rules: too good to be true, unknown number') | Part-time job! Earn Rs 3000/day by likin
('genuine', 'No red flags found') | Rs 2,500.00 debited from a/c XX4521 on 0
('check', 'Rules: too good to be true, asks for money') | Congratulations! You won Rs 25 lakh in K
```

Without a model (`ask_model=None`), the shield still works offline and says "check" for anything with a red flag. With Claude plugged in (`shield(text, claude_check)`), the grey cases get a reasoned verdict.

::tip Never auto-delete or auto-report a message. Show the verdict and the reason, and always add the same advice: do not click unknown links, never share an OTP, and in India call **1930** or report at **cybercrime.gov.in** if money is lost.

## Make it yours

- **Grow the test set:** collect 50 real scam texts from news reports and friends (masking all personal data) and keep them out of your prompt.
- **Cost check:** count how many messages reach Claude when rules handle the obvious ones, and estimate the monthly cost for 10,000 users.
- **A Streamlit app:** a text box, the verdict, the reasons and the safety advice.

## Portfolio checklist

- [ ] A labelled message set, plus a separate held-out set of new scam styles
- [ ] Explainable rules with reasons, scored with one evaluation function
- [ ] Proof that rules overfit (perfect on the dev set, weak on new messages)
- [ ] Personal data masked before any API call
- [ ] Claude prompt with JSON output, an "unsure" option and safe fallback
- [ ] A results table for rules and Claude on both sets, with your honest reading
