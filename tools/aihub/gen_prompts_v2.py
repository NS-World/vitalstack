#!/usr/bin/env python3
"""Prompt Bazaar 2.0: generate the large catalogue (about 99k prompts) as gzipped
JSON-lines parts that the plugin imports into its own database table.

Every prompt = a task template written for one job + a specific subject
(language, industry, platform, destination, topic...). [BRACKETS] are the
reader's details. Output: <plugin>/data/prompts/part-NNN.jsonl.gz
Usage: python3 tools/aihub/gen_prompts_v2.py [plugin_dir] [total_target]
"""
import gzip
import json
import os
import re
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from dims import STYLES, IMAGE_SUBJECTS, NICHES, PLATFORMS, INDUSTRIES, PRODUCT_CATS, DESTINATIONS  # noqa: E402
import dims2 as D  # noqa: E402

HERE = os.path.dirname(os.path.abspath(__file__))
PLUGIN = sys.argv[1] if len(sys.argv) > 1 else os.path.join(HERE, '..', '..', 'plugins', 'vitalstack-ai-hub')
TOTAL = int(sys.argv[2]) if len(sys.argv) > 2 else 100000
OUT = []


def cap(s):
    return s[:1].upper() + s[1:]


def a_an(w):
    return ('an ' if w[:1].lower() in 'aeiou' else 'a ') + w


def add(cat, sub, tool, kind, title, summary, prompt, output):
    OUT.append(dict(cat=cat, sub=sub, tool=tool, kind=kind, title=title, summary=summary, prompt=prompt.strip(), output=output.strip()))


def fam(cat, kind, subjects, tasks, sub_of=lambda s: s, ctx=lambda s: {}):
    """tasks: list of (title, summary, prompt, output, tool) with {s} = subject and ctx keys."""
    for si, s in enumerate(subjects):
        c = dict(s=s, S=cap(s), a_s=a_an(s))
        c.update(ctx(s))
        for ti, t in enumerate(tasks):
            tool = t[4] if len(t) > 4 else 'Any chatbot'
            if isinstance(tool, (list, tuple)):
                tool = tool[(si + ti) % len(tool)]
            add(cat, sub_of(s), tool, kind, t[0].format(**c), t[1].format(**c), t[2].format(**c), t[3].format(**c))


CHATS = ['ChatGPT', 'Claude', 'Gemini', 'Any chatbot']

# ── Image Generation ───────────────────────────────────────────────────────
def images():
    tools = ['Midjourney', 'Stable Diffusion', 'ChatGPT Images', 'Flux']
    for si, (style, desc) in enumerate(STYLES):
        for ji, subj in enumerate(IMAGE_SUBJECTS):
            tool = tools[(si + ji) % 4]
            ar = '2:3' if 'portrait' in subj else ('1:1' if any(k in subj for k in ('logo', 'icon', 'sticker', 'album')) else '3:2')
            if tool == 'Midjourney':
                p = f"{subj}, {desc}, thoughtful composition, high detail --ar {ar} --style raw --stylize 200\n\nTips: change --ar for your format (1:1 feed, 9:16 story, 16:9 banner); lower --stylize for literal results, raise it for artistic ones; add --no [THING] to remove an element."
                o = f"Four {style.lower()} variations of {subj} to upscale or vary."
            elif tool == 'Stable Diffusion':
                p = f"Positive prompt:\n{subj}, {desc}, highly detailed, sharp focus, balanced composition\n\nNegative prompt:\nblurry, low quality, deformed, extra limbs, watermark, text, oversaturated\n\nSuggested settings (SDXL): 30 steps, CFG 6–7, sampler DPM++ 2M Karras, 1024×1024 (or 832×1216 for portrait)."
                o = f"A {style.lower()} image of {subj}; the negative prompt removes common artefacts and the settings are a dependable SDXL start."
            elif tool == 'Flux':
                p = f"{cap(subj)}, rendered as {desc}. Composition: [CLOSE-UP / WIDE / CENTRED]. Lighting and mood: [MOOD]. Colour palette: [COLOURS]. Aspect ratio {ar}.\n\nTip: Flux follows plain descriptive sentences well and does not use a negative prompt — describe what you want instead of what to avoid."
                o = f"A detailed {style.lower()} render of {subj} that follows the written description closely."
            else:
                p = f"Create an image of {subj}.\nStyle: {desc}.\nComposition: [CLOSE-UP / WIDE / CENTRED], main subject [WHERE].\nMood: [MOOD]. Colours: [PALETTE]. Format: {ar}.\nIf there is text, write exactly \"[TEXT]\" and keep it short."
                o = f"A {style.lower()} image of {subj}; ask for edits in plain English, such as \"warmer light\" or \"move the subject left\"."
            title = f"{cap(subj)} — {style.lower()}"
            add('Image Generation', style, tool, 'image', title, f"A ready {tool} prompt: {subj} in {style.lower()} style.", p, o)


# ── Social Media: platforms × niches × 12 tasks ───────────────────────────
SOCIAL_TASKS = [
    ('30-day {P} content calendar for {a_s}', 'A month of {P} post ideas for {a_s}, with formats, hooks and calls to action.',
     "Create a 30-day {P} content calendar for {a_s} named [BRAND] in [CITY / ONLINE].\nAudience: [WHO]. Goal: [FOLLOWERS / LEADS / SALES]. Posting: [X] times a week.\nPlatform notes: {fmt}.\nMix pillars: education, behind the scenes, social proof, engagement, offers (max 20% promotional).\nTable: Day | Pillar | Format | Hook | Key message | Call to action.",
     "A 30-row calendar for {a_s} on {P}, balanced across pillars, with a hook and call to action per post."),
    ('{P} captions for {a_s}', 'Five {P} caption styles for {a_s} post, each with a clear call to action.',
     "Write 5 {P} captions for {a_s} post about [WHAT THE POST SHOWS / OFFER].\nVoice: [FRIENDLY / PREMIUM / FUN / EXPERT].\nStyles: story, list, question, bold statement, short and punchy. Each: hook line, value, call to action.\nFollow: {fmt}.",
     "Five ready captions that fit {P}'s format, each with a hook and a call to action."),
    ('{P} hooks for {a_s}', 'Twenty scroll-stopping first lines for {a_s} on {P}.',
     "Give 20 opening hooks for {P} posts by {a_s} about [TOPIC].\nMix curiosity, mistake, number/list, transformation, question and local angles. Max 12 words each, labelled by type, and never promise what the post does not deliver. Pick the best 3 and say why.",
     "Twenty labelled hooks and the three strongest with reasons, ready to test."),
    ('{P} bio for {a_s}', 'A profile bio and setup checklist for {a_s} on {P}.',
     "Write my {P} profile for {a_s}. Details: [NAME, LOCATION, WHAT MAKES US DIFFERENT, LINK, CONTACT].\nGive 3 bio options within {P}'s limit, a searchable name or headline field, what to pin or feature, and 5 trust-building fixes.",
     "Three bios within the character limit, keyword ideas and a pinned-content plan for {a_s}."),
    ('{P} post series idea for {a_s}', 'A recurring weekly series that keeps {a_s} audience coming back.',
     "Invent 5 recurring weekly {P} series for {a_s} (for example \"Myth Monday\").\nFor each: name, format, what each episode covers, 4 example episode titles, and why the audience will return.",
     "Five named weekly series with example episodes, so planning content gets easy."),
    ('{P} reply templates for {a_s}', 'Friendly replies to common comments and DMs for {a_s}.',
     "Write reply templates for {a_s} on {P} (voice: [VOICE]).\nCases: praise, price question, availability, location/timing, delivery, complaint, rude comment, collaboration request, lead asking for details.\nTwo short, human options each, plus when to move to DM or phone. Do not promise anything beyond: [POLICIES].",
     "Two human-sounding replies for every common case and clear rules for moving a chat private."),
    ('{P} promo post for {a_s} offer', 'An honest promotional post for {a_s} offer on {P}.',
     "Write a {P} promotion for {a_s} offering [OFFER] valid until [REAL END DATE].\nGive: 3 post versions (announcement, reminder, last day), visual idea for each, and the call to action. Use the real end date only — no fake urgency or fake stock counts.",
     "Three honest promo posts with visuals and the real deadline, ready for {P}."),
    ('{P} keyword and hashtag list for {a_s}', 'Discoverable keywords and hashtags for {a_s} on {P}.',
     "Build a keyword and hashtag set for {a_s} posting on {P} in [CITY / COUNTRY].\nGroup into: broad, niche, local, branded, and campaign. 8–10 per group, and explain how many to use per post on {P} and where to put keywords (caption, alt text, title, bio).",
     "Grouped keywords and hashtags with usage rules that fit {P}."),
    ('{P} carousel or thread outline for {a_s}', 'Turn one useful tip from {a_s} into a multi-part {P} post.',
     "Turn this tip into a multi-part {P} post for {a_s}: [TIP / TOPIC].\nPlatform notes: {fmt}.\nGive a hook part, 6–8 value parts (one idea each), and a final call-to-action part, with a visual or on-screen text note for each part.",
     "A part-by-part outline with a hook, value steps and a call to action, ready to design."),
    ('{P} customer story post for {a_s}', 'Turn a customer review into a story post for {a_s}.',
     "Turn this customer review into a {P} story post for {a_s}: [PASTE REVIEW — remove personal details unless the customer agreed].\nStructure: situation, problem, what we did, result, call to action. Give 2 versions and a caption. Ask the customer's permission before naming them.",
     "Two story-style posts built from a real review, with a reminder to get the customer's permission."),
    ('{P} growth plan for {a_s}', 'A 90-day {P} growth plan for {a_s}, with weekly actions.',
     "Make a 90-day {P} growth plan for {a_s}. Current followers: [N]. Time per week: [HOURS]. Budget: [AMOUNT OR ZERO].\nInclude: positioning, content mix, posting rhythm, collaboration ideas, community actions, a small paid test (if budget), and weekly metrics.",
     "A three-month plan with weekly actions and metrics tuned to {P} and {a_s}."),
    ('{P} festive post ideas for {a_s}', 'Festival and seasonal post ideas for {a_s} on {P}.',
     "List 15 festival and seasonal {P} post ideas for {a_s} in [COUNTRY / REGION] for the next 6 months ([LIST FESTIVALS OR 'suggest']).\nFor each: date window, idea, format, caption opener and a respectful note (no stereotypes).",
     "Fifteen dated seasonal ideas with formats and caption openers, respectful of every culture."),
]


def social():
    for P, fmt in PLATFORMS:
        fam('Social Media', 'text', NICHES, [t + (CHATS,) for t in SOCIAL_TASKS], sub_of=lambda s, P=P: P, ctx=lambda s, P=P, fmt=fmt: dict(P=P, fmt=fmt))


# ── Business / Marketing / Sales / Support per industry ───────────────────
BIZ = [
    ('One-page business plan for {a_s}', 'A lean plan for {a_s}: customers, offer, money and first steps.',
     "Write a one-page business plan for my {s} in [CITY / ONLINE]. Idea: [DESCRIBE]. Budget: [AMOUNT]. Unique angle: [WHAT IS DIFFERENT].\nSections: problem, customers, offer and pricing, competitors, channels, costs (one-time and monthly), revenue estimate with visible assumptions, break-even, risks, first 30 days.\nUse tables; mark every number as an estimate to verify locally.",
     "A lean plan for {a_s} with cost and revenue tables on stated assumptions, break-even and a 30-day action list."),
    ('{S} customer personas', 'Three buyer personas for {a_s}, with what makes them buy.',
     "Create 3 customer personas for {a_s} in [LOCATION]: age band, job, income band, goals, pains, buying triggers, objections, where they spend time, winning message, best offer.\nBase them on: [WHAT YOU KNOW / REVIEWS / SALES DATA]. Flag guesses.",
     "Three realistic personas with triggers, objections, channels and a message for each."),
    ('{S} competitor analysis', 'Compare {a_s} with competitors and find a gap to own.',
     "Analyse competitors for my {s}: [LIST 3–5 WITH PRICES, REVIEWS, OFFERS, LOCATIONS].\n1. Comparison table (price, quality, service, convenience, brand, online presence).\n2. SWOT for my business.\n3. 3 market gaps with cheap ways to test each. Only use the facts I gave; list what to verify.",
     "A comparison table, SWOT and three testable gaps for {a_s}."),
    ('Pricing strategy for {a_s}', 'Set prices for {a_s} with cost, value and competitor checks.',
     "Help me price the offers of my {s}. Offers: [LIST]. Costs per unit or hour: [COSTS]. Competitor prices: [PRICES]. Target customer: [WHO].\nShow cost-plus, competitor-based and value-based prices, suggest good-better-best packages, and the margin for each. No psychological tricks that mislead customers.",
     "Three pricing methods side by side, tiered packages and margins, with honest presentation."),
    ('Startup cost checklist for {a_s}', 'Every cost to plan before opening {a_s}.',
     "List all startup and monthly costs for opening {a_s} in [CITY / COUNTRY] at [SMALL / MEDIUM] scale.\nGroup: licences and registrations (say which to verify locally), space, equipment, stock, technology, marketing, staff, insurance, contingency (10–15%). Give a table with low/high estimate columns for me to fill.",
     "A grouped cost checklist with low/high columns and a reminder to verify licences locally."),
    ('SOPs for {a_s}', 'Standard operating procedures for the daily work of {a_s}.',
     "Write standard operating procedures for my {s}: opening routine, closing routine, handling a customer order or booking, handling a complaint, cash and payments, cleaning and safety, and weekly stock or supplies check.\nNumbered steps, owner and a checklist for each.",
     "Seven ready SOPs with numbered steps, owners and checklists."),
    ('Hiring plan for {a_s}', 'Roles, job ads and interview questions for {a_s}.',
     "Plan hiring for my {s} for the next 6 months. Current team: [TEAM]. Growth target: [TARGET].\nGive: roles in order of need, a short job ad for each, 5 interview questions per role, a simple onboarding checklist, and fair pay research tips.",
     "A phased hiring plan with job ads, interview questions and onboarding for each role."),
    ('Customer experience map for {a_s}', 'Every touchpoint of {a_s} from first search to repeat visit.',
     "Map the customer journey for {a_s}: discover, decide, buy/visit, use, follow-up, return, refer.\nFor each stage: what the customer does, feels, touchpoints, pain points and one improvement. Table format, then the top 5 fixes.",
     "A stage-by-stage journey table and the five improvements that matter most."),
    ('Brand name ideas for {a_s}', 'Thirty name options for {a_s} with checks to do next.',
     "Suggest 30 names for {a_s} that feels [WORDS] and serves [WHO] in [PLACE].\nMix invented, compound, descriptive and local-language names. One-line meaning each. Shortlist 5 and list checks: domain, social handles, trademark search in [COUNTRY], pronunciation.",
     "Thirty names with meanings, a shortlist and a checklist before you decide."),
    ('Grand opening plan for {a_s}', 'A launch-week plan for {a_s} that brings the first customers.',
     "Plan the opening of {a_s} on [DATE] with budget [AMOUNT].\nGive: 3-week pre-launch plan, launch-day schedule, local partnerships, an opening offer (honest and time-bound), press and influencer outreach, and how to collect reviews and contacts with consent.",
     "A three-week runway, launch-day schedule and follow-up plan for {a_s}."),
    ('KPI dashboard for {a_s}', 'The ten numbers {a_s} should check every week.',
     "Choose 8–10 weekly KPIs for my {s}. For each: definition, formula, where the data comes from, a rough healthy range (say it is a guide), and the action when it drops.\nThen a one-page dashboard layout I can build in Google Sheets.",
     "A focused KPI list with formulas, ranges and actions, plus a sheet layout."),
    ('Partnership ideas for {a_s}', 'Local and online partners that can send customers to {a_s}.',
     "Suggest 15 partnership ideas for {a_s} in [CITY]: who to partner with, what each side gets, a pitch line, and how to track results. Include 5 zero-cost ideas.",
     "Fifteen partner ideas with mutual benefits, pitch lines and tracking."),
    ('Financial projection for {a_s}', 'A 12-month revenue and cost projection template for {a_s}.',
     "Build a 12-month financial projection for {a_s}. Inputs: average price [X], expected customers in month 1 [N], monthly growth [%], cost of goods [%], fixed costs [LIST].\nGive a month-by-month table, break-even month, cash needed, and 3 scenarios (cautious, expected, strong). Show formulas so I can copy them into a spreadsheet.",
     "A month-by-month projection with three scenarios and spreadsheet-ready formulas."),
    ('Risk checklist for {a_s}', 'What could go wrong for {a_s} and how to prepare.',
     "List the main risks for {a_s}: market, operations, people, legal and compliance (to verify locally), financial, technology and reputation.\nFor each: likelihood, impact, early warning sign, and prevention. Then a one-page emergency plan.",
     "A rated risk register with warning signs, prevention and a short emergency plan."),
    ('Customer loyalty programme for {a_s}', 'A simple loyalty idea that brings customers of {a_s} back.',
     "Design a loyalty programme for {a_s}. Average order: [VALUE]. Repeat rate now: [%].\nCompare 3 models (points, stamps/visits, tiered or membership), pick one, define rewards that protect margin, how to sign people up with consent, and how to measure success.",
     "Three loyalty models compared and one recommended with rewards, sign-up and metrics."),
    ('Supplier and vendor plan for {a_s}', 'Find, compare and manage suppliers for {a_s}.',
     "Help me set up suppliers for my {s}. What I need: [ITEMS / SERVICES].\nGive: where to find suppliers, a comparison sheet (price, quality, reliability, payment terms, minimum order), questions to ask, a sample enquiry email, and how to negotiate politely.",
     "A supplier comparison sheet, question list, enquiry email and negotiation tips."),
    ('Sustainability plan for {a_s}', 'Practical green steps for {a_s} that also save money.',
     "Suggest a sustainability plan for {a_s}: energy, water, waste, packaging, suppliers and transport.\nFor each idea: cost (low/medium/high), saving or benefit, and how to tell customers honestly without greenwashing.",
     "Practical green actions with cost and benefit, plus honest messaging tips."),
    ('Expansion options for {a_s}', 'Ways to grow {a_s}: new location, online, franchise or new offers.',
     "My {s} makes [REVENUE] per month with [PROFIT] profit. Compare expansion options: second location, online sales, delivery, franchise or licensing, new product line, B2B sales.\nFor each: cost, risk, time, what must be true first. Recommend one with a 6-month plan.",
     "A side-by-side comparison of growth paths and a recommended six-month plan."),
]
MKT = [
    ('Google Ads copy for {a_s}', 'Search ad headlines and descriptions for {a_s}, within limits.',
     "Write Google Ads responsive search ad copy for {a_s} targeting \"[KEYWORD]\" in [CITY].\n15 headlines (max 30 characters) and 4 descriptions (max 90 characters), with the character count next to each. Include benefit, proof, offer [OFFER] and a call to action.",
     "Fifteen headlines and four descriptions within Google's limits, counts shown."),
    ('Meta ads for {a_s}', 'Five Facebook/Instagram ad variations for {a_s}.',
     "Create 5 Meta ad variations for {a_s} aimed at [AUDIENCE] in [LOCATION].\nEach: primary text (first 125 characters work alone), headline under 40 characters, CTA button, and an image or 15-second video idea. Angles: pain, benefit, social proof, offer, curiosity. No claims I cannot prove.",
     "Five angle-based ads with hooks, headlines and creative ideas."),
    ('Landing page copy for {a_s}', 'A full landing page for {a_s}, from headline to FAQ.',
     "Write landing page copy for {a_s}. Audience: [WHO]. Promise: [RESULT]. Proof: [REVIEWS, NUMBERS]. Offer: [OFFER].\nSections: hero, problem, solution, 3 benefits, how it works, social proof, pricing, 6 FAQs, final CTA. Give 3 headline options.",
     "Complete landing page copy with three headline options and objection-handling FAQs."),
    ('SEO keyword plan for {a_s}', 'Keywords and pages {a_s} should rank for.',
     "Create an SEO keyword plan for {a_s} in [CITY / ONLINE].\nGroup keywords by intent (transactional, local, informational), suggest the page each belongs to, title tags (under 60 characters) and meta descriptions (under 155 characters) for 5 key pages. Mark search volumes as unknown unless I provide them.",
     "Intent-grouped keywords mapped to pages, with titles and meta descriptions."),
    ('Blog topic cluster for {a_s}', 'Twenty blog posts that bring the right visitors to {a_s}.',
     "Plan a topic cluster for {a_s}: 1 pillar page and 20 supporting blog posts.\nFor each: title, search intent, 3 key points, and internal link to the pillar. Order them by how close the reader is to buying.",
     "A pillar and twenty linked posts ordered by buyer stage."),
    ('Email newsletter for {a_s}', 'A monthly newsletter template for {a_s}.',
     "Write a monthly newsletter for {a_s} to [AUDIENCE]. News: [UPDATES / OFFERS / TIPS].\nSubject lines (3), preview text, short intro, main story, 2 tips, one offer, P.S. line, and an unsubscribe footer line. Under 400 words.",
     "A skimmable newsletter with subject lines and one clear call to action."),
    ('Welcome email series for {a_s}', 'Five emails that turn new subscribers of {a_s} into customers.',
     "Write a 5-email welcome series for new subscribers of {a_s} who joined for [LEAD MAGNET / OFFER].\nDay 0 welcome, day 2 story, day 4 best tips, day 6 reviews, day 8 soft offer. Under 200 words each, one CTA, unsubscribe line in the footer.",
     "Five short emails building trust before a soft offer."),
    ('Google Business Profile for {a_s}', 'A local SEO setup for {a_s} on Google Maps.',
     "Optimise the Google Business Profile of {a_s} in [CITY].\nGive: primary and secondary categories, a description up to 750 characters with local keywords, 10 services or products, a weekly post plan, a review request message, and 5 Q&A entries.",
     "A ready description within 750 characters, categories, services, posts and a review message."),
    ('Seasonal campaign for {a_s}', 'An honest seasonal sale campaign for {a_s}.',
     "Plan a [SEASON / FESTIVAL] campaign for {a_s}. Offer: [OFFER]. Dates: [START–END]. Channels: [LIST].\nGive a campaign name, 3 messages (teaser, launch, last day), banner text, email subject lines and a reminder schedule. Use the real end date only.",
     "A complete campaign kit with honest urgency based on real dates."),
    ('Referral programme for {a_s}', 'A referral scheme that makes customers of {a_s} bring friends.',
     "Design a referral programme for {a_s}: reward for referrer and friend (protect margin), how sharing works (link, code, card), the invite message, terms in plain English, and how to track it in a spreadsheet.",
     "A complete referral scheme with rewards, sharing method, messages and tracking."),
    ('Brand voice guide for {a_s}', 'How {a_s} should sound everywhere.',
     "Create a brand voice guide for {a_s}. Personality in 3 words: [WORDS]. Audience: [WHO].\nPrinciples, tone by situation (social, support, sales, apology), words to use and avoid, 5 do/don't examples, and a sample paragraph.",
     "A practical voice guide with examples you can hand to anyone writing for the brand."),
    ('Taglines for {a_s}', 'Twenty-five tagline options for {a_s}.',
     "Write 25 taglines for {a_s} serving [WHO]. Feel: [WORDS]. Group: benefit, emotional, playful, premium, ultra-short. Max 8 words. Shortlist 3 and explain why.",
     "Twenty-five grouped taglines and a reasoned shortlist."),
    ('Influencer brief for {a_s}', 'A clear brief and outreach message for creators promoting {a_s}.',
     "Write an influencer outreach message and campaign brief for {a_s}.\nCreator type: [NICHE, FOLLOWER RANGE]. Offer: [PAYMENT / PRODUCT / COMMISSION]. Deliverables, key messages, do's and don'ts, timeline, and the paid-partnership disclosure rule.",
     "An outreach message plus a creator brief with deliverables and disclosure rules."),
    ('Video ad scripts for {a_s}', 'Three short video ad scripts for {a_s}.',
     "Write three 15–30 second video ad scripts for {a_s}: problem-solution, customer testimonial style (use [REAL REVIEW]), and behind the scenes.\nTable: time | shot | voice-over | on-screen text. End each with a clear CTA.",
     "Three timed scripts with shots, voice-over and on-screen text."),
    ('Marketing budget plan for {a_s}', 'Split a small marketing budget for {a_s} with test-and-learn rules.',
     "I have [AMOUNT] per month to market {a_s}. Goal: [GOAL]. Suggest a channel split with reasons, month-1 tests, stop/scale rules, and the minimum tracking to set up first.",
     "A reasoned budget split with tests and clear stop or scale rules."),
    ('Review response templates for {a_s}', 'Replies to 5-star, 3-star and 1-star reviews of {a_s}.',
     "Write review replies for {a_s}: 5-star (3 versions), 4-star, 3-star, 2-star, 1-star complaint, fake-looking review, and a review with a factual error.\nPersonal, short, never defensive, and move problems offline with a contact method.",
     "Calm, personal replies for every kind of review."),
]
SALES = [
    ('Cold email for {a_s}', 'A short cold email that wins meetings for {a_s}.',
     "Write a cold email for {a_s} to [PROSPECT ROLE] at [COMPANY TYPE].\nWhat we offer: [OFFER] — main result: [RESULT]. Personal hook: [DETAIL].\nUnder 120 words, subject under 6 words, one pain point, one proof point, an easy yes/no ask. 3 versions.",
     "Three short cold emails with personal openers and easy asks."),
    ('Follow-up sequence for {a_s}', 'Four follow-ups for {a_s} that add value each time.',
     "Write a 4-message follow-up sequence for {a_s} after no reply to: [FIRST MESSAGE]. Day 3, 7, 14, 21; each adds something new (tip, case, question, polite breakup). Under 80 words each.",
     "Four value-adding follow-ups ending with a polite breakup message."),
    ('Objection handling for {a_s}', 'Honest answers to the objections {a_s} hears most.',
     "List the 8 most common objections customers raise with {a_s} (price, timing, trust, competitor, need to ask someone...).\nFor each: what it usually means, a question to uncover it, and a 2–3 sentence honest answer.",
     "A table of objections with the hidden concern, a question and an honest response."),
    ('Discovery call script for {a_s}', 'Questions that uncover what buyers of {a_s} need.',
     "Write a 15-minute discovery call or visit script for {a_s}: opener, situation, pain, impact, budget and decision, next step. 3–4 questions per stage and what to listen for.",
     "A timed script with open questions and listening notes."),
    ('Proposal or quote template for {a_s}', 'A clear quote that sells outcomes for {a_s}.',
     "Create a proposal/quotation template for {a_s}: client goals in their words, scope, deliverables, timeline, 3 pricing options, terms (payment, cancellations), proof, next steps. Placeholders for details.",
     "A reusable proposal with three options and clear terms."),
    ('WhatsApp sales messages for {a_s}', 'Polite WhatsApp follow-ups and offers for {a_s} (opted-in contacts).',
     "Write WhatsApp Business messages for {a_s}: first reply to an enquiry, price details, follow-up after 2 days, booking confirmation, payment reminder, thank-you with review request, and a re-engagement message. Short, friendly, opt-out respected.",
     "Seven short WhatsApp templates for the whole sales conversation."),
    ('Upsell ideas for {a_s}', 'Add-ons that genuinely help buyers of {a_s}.',
     "Suggest 10 upsell and cross-sell ideas for {a_s}. Average order: [VALUE].\nFor each: the moment to offer it, the offer, a one-line message and the customer benefit. Rank by ease.",
     "Ten ranked add-on ideas with timing and one-line messages."),
    ('Win-back messages for {a_s}', 'Bring lapsed customers back to {a_s}.',
     "Write win-back messages for customers who have not bought from {a_s} in [X] months: email, SMS (under 160 characters) and WhatsApp. Include a genuine offer [OFFER], a one-tap \"why did you stop?\" question, and opt-out wording.",
     "Three channel-specific win-back messages with a feedback question."),
    ('Sales targets plan for {a_s}', 'Work backwards from a revenue goal for {a_s}.',
     "Revenue goal for {a_s}: [AMOUNT] per month. Average sale: [VALUE]. Conversion rate: [%].\nWork backwards: customers, leads and outreach needed per week. Give a weekly activity plan and leading indicators.",
     "Reverse-engineered targets and a weekly activity plan."),
    ('B2B pitch for {a_s}', 'Sell {a_s} services to businesses, not just individuals.',
     "Help {a_s} sell to businesses ([TARGET BUSINESS TYPES]). Give: the B2B offer (packages, bulk or corporate pricing), a one-page pitch, a LinkedIn message, an email, and 5 objection answers.",
     "A B2B offer, one-page pitch and outreach messages."),
]
SUPPORT = [
    ('FAQ page for {a_s}', 'Clear answers to the questions customers ask {a_s} most.',
     "Write 15 FAQs for {a_s}: ordering/booking, prices, payment, timing, delivery or location, cancellation and refunds, safety, accessibility, contact. Short, friendly answers using these facts: [YOUR POLICIES]. Mark anything I must fill in.",
     "Fifteen clear FAQs based on your policies, with gaps marked."),
    ('Support reply templates for {a_s}', 'Ready replies for common support messages at {a_s}.',
     "Write support reply templates for {a_s}: late order or delay, wrong item or service issue, refund request, rude customer, technical/payment problem, thank-you, and follow-up after resolution. Empathetic, short, with next steps.",
     "Seven empathetic replies with clear next steps."),
    ('Refund and cancellation policy for {a_s}', 'A fair, plain-English policy draft for {a_s}.',
     "Draft a refund and cancellation policy for {a_s}. My rules: [TIMEFRAMES, CONDITIONS, EXCEPTIONS].\nPlain English, sections for how to request, timelines, exceptions and contact. Note that it should be checked against consumer law in [COUNTRY].",
     "A clear policy draft with a reminder to check local consumer law."),
    ('Complaint handling process for {a_s}', 'A step-by-step way for {a_s} staff to resolve complaints.',
     "Create a complaint handling process for {a_s}: listen, apologise, investigate, resolve, follow up, learn.\nScripts for each step, escalation rules, what compensation staff may offer up to [LIMIT], and a complaint log template.",
     "A six-step process with scripts, escalation rules and a log template."),
    ('Chatbot script for {a_s}', 'A simple website or WhatsApp bot flow for {a_s}.',
     "Design a chatbot flow for {a_s}: greeting, menu of options, answers for top 8 questions, booking or order capture, handover to a human, and fallback when it does not understand. It must never ask for passwords or full card numbers.",
     "A complete bot flow with answers, handover and safe fallbacks."),
    ('Customer feedback survey for {a_s}', 'A short survey that tells {a_s} what to fix.',
     "Write a customer feedback survey for {a_s}: max 8 questions (NPS, satisfaction, what to improve, open comment), how to send it (QR, email, WhatsApp), and how to turn answers into a monthly action list.",
     "A short survey, sending options and a plan to act on answers."),
    ('Help centre articles for {a_s}', 'Self-service help articles customers of {a_s} actually read.',
     "Outline 10 help-centre articles for {a_s}: titles phrased as customer questions, a short answer first, step-by-step instructions, screenshots or photo notes, and related links. Use these facts: [YOUR PROCESSES]; mark gaps.",
     "Ten customer-question articles with answer-first structure."),
    ('Service recovery plan for {a_s}', 'Turn a bad experience at {a_s} into a loyal customer.',
     "Create a service recovery playbook for {a_s}: the 6 most likely things to go wrong, what staff say in the moment, what they can offer (up to [LIMIT]), follow-up within 48 hours, and how to log and learn from each case.",
     "A playbook for the six most likely failures with scripts and follow-ups."),
]


def business():
    fam('Business', 'text', INDUSTRIES, [t + (CHATS,) for t in BIZ], sub_of=cap)
    fam('Marketing', 'text', INDUSTRIES, [t + (CHATS,) for t in MKT], sub_of=cap)
    fam('Sales', 'text', INDUSTRIES, [t + (CHATS,) for t in SALES], sub_of=cap)
    fam('Customer Support', 'text', INDUSTRIES, [t + (CHATS,) for t in SUPPORT], sub_of=cap)


# ── E-commerce per product ─────────────────────────────────────────────────
ECOM = [
    ('Product description for {a_s}', 'Benefit-led copy for {a_s} listing.', "Write a product description for {a_s} called [PRODUCT NAME]. Features: [LIST]. Buyer: [WHO]. Tone: [TONE].\nGive a hook line, a 60–100 word description turning features into benefits, 5 bullet points and an SEO title under 60 characters.", "A hook, benefit-led description, bullets and an SEO title."),
    ('Amazon listing for {a_s}', 'Title, bullets and description for {a_s} on Amazon.', "Write an Amazon listing for {a_s}: [BRAND, KEY FEATURES, SIZE/VARIANTS].\nTitle (keep within the category's character limit; check Seller Central), 5 bullet points starting with a benefit, product description, and backend search terms. No claims I cannot prove.", "A complete listing draft that follows marketplace norms."),
    ('{S} buying guide', 'Help shoppers choose the right {s}.', "Write a buying guide for {s}: what to look for, key specs explained simply, price ranges (say they vary), common mistakes, and a comparison table template for 3 options.", "A helpful buying guide with specs explained and a comparison table."),
    ('{S} FAQ section', 'Pre-sale questions buyers ask about {a_s}.', "Write 12 FAQs for {a_s} product page: sizing/compatibility, materials, care, warranty, delivery, returns, gifting. Use these facts: [FACTS]; mark anything I must fill in.", "Twelve product FAQs based on your facts."),
    ('Abandoned cart emails for {a_s}', 'Three emails that recover carts with {a_s}.', "Write a 3-email abandoned cart sequence for {a_s}: reminder (1 hour), reassurance with reviews and returns info (24 hours), last reminder with a genuine offer [OFFER] (48 hours). Subject lines included; unsubscribe line in footer.", "Three recovery emails with subject lines and honest incentives."),
    ('{S} ad copy', 'Short ad copy for {a_s} on search and social.', "Write ad copy for {a_s}: 5 Google search headlines (max 30 characters), 2 descriptions (max 90), 3 Meta primary texts and 3 short video hooks. Show character counts for Google lines.", "Search and social ad copy with character counts."),
    ('{S} product photo shot list', 'The photos and videos {a_s} listing needs.', "Create a product photo and video shot list for {a_s}: hero shot, angles, scale reference, lifestyle scenes, details, packaging, infographic ideas and a 15-second video. Lighting and background notes for a home setup.", "A complete shot list with home-studio tips."),
    ('{S} comparison table', 'Compare your {s} with alternatives honestly.', "Create a comparison table: my {s} [SPECS] vs [ALTERNATIVE 1] and [ALTERNATIVE 2] [THEIR SPECS]. Rows that matter to buyers, a short honest summary of who each option is best for.", "An honest comparison that helps buyers choose."),
    ('{S} launch plan', 'A 4-week online launch plan for {a_s}.', "Plan an online launch for {a_s}: pre-launch waitlist, content, influencer seeding, launch-day checklist, first-week offers (real end date), and how to gather the first 20 reviews ethically.", "A four-week launch plan with an ethical review strategy."),
    ('{S} size or spec guide', 'A clear guide so buyers pick the right {s} variant.', "Write a size/spec guide for {a_s}: how to measure or choose, a table of variants [VARIANTS], fit or compatibility notes, and what to do if unsure.", "A buyer-friendly guide that reduces returns."),
    ('{S} customer review request', 'Ask buyers of {a_s} for honest reviews.', "Write a post-purchase message sequence for {a_s}: delivery check (day 1), usage tip (day 3), honest review request (day 7). Never offer rewards in exchange for positive reviews.", "Three messages that earn honest reviews."),
    ('{S} bundle ideas', 'Bundles that raise order value for {a_s}.', "Suggest 8 bundle ideas built around {a_s}: what is in each, who it is for, a bundle name and price logic (show margin formula).", "Eight named bundles with price logic."),
    ('{S} returns reduction plan', 'Fewer returns for {a_s}.', "My return rate for {a_s} is [%]. Reasons: [REASONS]. Suggest fixes for listing content, sizing, packaging, quality checks and post-purchase support, with expected impact.", "Practical fixes to cut returns."),
    ('{S} marketplace SEO', 'Rank {a_s} higher inside marketplaces.', "Improve marketplace search ranking for {a_s} on [AMAZON / FLIPKART / ETSY / MEESHO]: keyword research steps, title and attribute optimisation, images, pricing, reviews and ads basics. Follow each marketplace's rules.", "A step-by-step marketplace SEO checklist."),
]


def ecommerce():
    fam('E-commerce', 'text', PRODUCT_CATS, [t + (CHATS,) for t in ECOM], sub_of=cap)


# ── Travel ─────────────────────────────────────────────────────────────────
TRIPS = D_TRIPS = [
    ('2-day weekend', '2 days'), ('3-day first-timer', '3 days'), ('5-day family', '5 days with kids'), ('budget backpacking', '4 days on a tight budget'),
    ('honeymoon', '5 days for a couple'), ('solo travel', '4 days alone'), ('food lover', '3 days focused on local food'), ('adventure and nature', '4 days outdoors'),
    ('luxury', '4 days of comfort and special experiences'), ('photography', '3 days chasing the best light and views'), ('workation', '7 days working remotely'), ('senior-friendly', '4 relaxed days with easy walking'),
]


def travel():
    for di, d in enumerate(DESTINATIONS):
        for ti, (t, length) in enumerate(TRIPS):
            add('Travel & Life', d, CHATS[(di + ti) % 4], 'text', f"{d}: {t} itinerary", f"A day-by-day {t} plan for {d}, with timings, budget and tips.",
                f"Plan a {t} trip to {d}: {length}, in [MONTH].\nTravellers: [WHO]. Budget: [AMOUNT]. Interests: [LIST]. Pace: [RELAXED / PACKED].\nGive a day-by-day plan (morning, afternoon, evening) with travel times, a budget table, areas to stay (not specific bookings), local food to try, packing list, and safety and etiquette tips.\nRemind me to check opening hours, entry rules, permits and weather before booking.",
                f"A realistic {t} itinerary for {d} with timings, a budget table, food, packing and safety tips.")


# ── Study, teaching, exams ─────────────────────────────────────────────────
STUDY_T = [
    ('{s} explained simply ({sub})', 'Understand {s} with an analogy, worked example and a quick check.', "You are an expert {sub} teacher. Explain {s} to a [CLASS / LEVEL] learner.\n1. One-line definition. 2. Real-life analogy. 3. Step-by-step explanation (formulas, dates or rules as needed). 4. A worked example. 5. Three common mistakes. 6. A 3-question check — wait for my answers, then correct them.\nStay accurate; say if anything depends on my syllabus.", "A layered explanation of {s}, from definition to worked example, plus an interactive check."),
    ('{s}: practice questions', 'Exam-style questions on {s} with answers at the end.', "Create practice questions on {s} ({sub}) for [CLASS / EXAM]: 5 MCQs, 3 short-answer and 2 long-answer, easy to hard, difficulty marked. Put the worked answer key after a line \"--- ANSWERS ---\".", "Ten graded questions on {s} with a separate worked answer key."),
    ('{s}: one-page notes', 'Revision notes on {s} with key points, formulas and mnemonics.', "Make one-page revision notes on {s} ({sub}): definitions, must-know facts or formulas, a small comparison table if useful, 2 mnemonics, \"most asked\" points and a 60-second recap. Scannable and accurate.", "A scannable one-page sheet on {s} for quick revision."),
    ('{s}: mind map', 'A text mind map that connects every idea in {s}.', "Create a text mind map of {s} ({sub}): central idea, 5–7 main branches, sub-branches with keywords, and cross-links between branches. Then a 5-line story that ties it together for memory.", "A branching mind map of {s} with a memory story."),
    ('{s}: real-life applications', 'Where {s} shows up in daily life, jobs and technology.', "Show 8 real-life applications of {s} ({sub}) — at home, in jobs, in technology and in nature — with a one-paragraph explanation each and a simple experiment or observation I can try safely.", "Eight relatable applications of {s} with safe activities to try."),
    ('{s}: common mistakes', 'The mistakes students make in {s} and how to avoid them.', "List the 10 most common mistakes or misconceptions students have about {s} ({sub}). For each: the wrong idea, why it is wrong, the correct idea, and a quick check question.", "Ten misconceptions about {s}, corrected with check questions."),
    ('{s}: flashcards', 'Flashcards on {s} ready for Anki or Quizlet.', "Make 20 flashcards on {s} ({sub}): one fact per card, questions that test understanding, answers under 25 words. Output as a table, then as CSV (Question,Answer).", "Twenty flashcards on {s} as a table and importable CSV."),
    ('{s}: explain with analogies', 'Five analogies that make {s} click.', "Explain {s} ({sub}) with 5 different analogies (cooking, sport, traffic, school, nature). For each: the analogy, where it fits, where it breaks down. End with a 3-sentence plain explanation.", "Five analogies for {s} with their limits and a plain summary."),
]
TEACH_T = [
    ('Lesson plan: {s}', 'A complete, engaging lesson plan on {s} ({sub}).', "Create a [MINUTES]-minute lesson plan on {s} ({sub}) for [GRADE / LEVEL].\nObjectives, materials, a 5-minute hook, direct teaching with an example, group activity, check for understanding, differentiation (support and stretch), homework and an exit ticket.", "A timed lesson plan on {s} with activities, differentiation and an exit ticket."),
    ('Worksheet: {s}', 'A three-level worksheet on {s} with answers.', "Create a worksheet on {s} ({sub}) for [GRADE] in 3 levels (support, core, challenge), 6 questions each, with hints for support and an extension task for challenge. Answer key at the end.", "A levelled worksheet on {s} with hints, extension and answers."),
    ('Quiz: {s}', 'A 10-question quiz on {s} with an answer key.', "Make a 10-question quiz on {s} ({sub}) for [GRADE]: 6 MCQs, 2 true/false, 2 short answers, mixed difficulty, explained answer key at the end.", "A mixed-format quiz on {s} with explanations."),
    ('Classroom activity: {s}', 'A hands-on group activity or game that teaches {s}.', "Design a 20-minute classroom activity or game that teaches {s} ({sub}) to [GRADE] with [CLASS SIZE] students: materials, rules, steps, roles, what students learn, and how to debrief.", "A ready activity with materials, steps and a debrief."),
    ('Project idea: {s}', 'A mini project that applies {s} ({sub}).', "Suggest 3 mini-project ideas that apply {s} ({sub}) for [GRADE]. For one, give a full brief: goal, steps over [DAYS], resources, safety notes, final product and rubric.", "Three project ideas and one complete project brief with rubric."),
    ('Rubric: {s} assignment', 'A grading rubric for an assignment on {s}.', "Create a 4-level grading rubric for an assignment on {s} ({sub}) for [GRADE]: criteria (understanding, application, communication, accuracy), specific descriptors per level, points, and a student-friendly version.", "A four-level rubric with descriptors and a student version."),
]


def study():
    topics = [(t, subj) for subj, (lvl, ts) in D.STUDY.items() for t in ts]
    for i, (t, subj) in enumerate(topics):
        c = dict(s=t, sub=subj)
        for j, tt in enumerate(STUDY_T):
            add('Study & Exams', subj, CHATS[(i + j) % 4], 'text', cap(tt[0].format(**c)), tt[1].format(**c), tt[2].format(**c), tt[3].format(**c))
        for j, tt in enumerate(TEACH_T):
            add('Teaching', subj, CHATS[(i + j + 1) % 4], 'text', tt[0].format(**c).replace(': ' + t, ': ' + cap(t)), tt[1].format(**c), tt[2].format(**c), tt[3].format(**c))
    EX = [
        ('{s} study plan', 'A realistic week-by-week {s} plan around your weak areas.', "Make a study plan for {s}. Exam date: [DATE]. Today: [DATE]. Hours per day: weekdays [X], weekends [Y]. Strong areas: [LIST]. Weak areas: [LIST].\nWeekly targets, daily schedule, spaced revision, full mock tests with analysis days and a final-week plan, as a table. Remind me to confirm the latest pattern on the official website.", "A week-by-week plan for {s} with mocks, revision and a final week."),
        ('{s} mock test in chat', 'A short {s}-style mock with a mistake analysis.', "Give me a 10-question {s}-style mock on [SECTION / TOPIC]. Ask one by one and wait. Then: score, time tips and mistake analysis (concept gap, silly error, time pressure), and 3 topics to practise. Do not claim questions are official.", "An interactive ten-question mock with analysis."),
        ('{s} weak-topic recovery', 'Fix your weakest {s} topics in two weeks.', "My weakest topics for {s}: [LIST] (scores [SCORES]). Make a 14-day recovery plan: concept refresh, practice sets that get harder, mixed revision, and a mini-test every 4 days.", "A two-week plan that rebuilds weak topics step by step."),
        ('{s} last 30 days plan', 'How to use the final month before {s}.', "Plan my last 30 days before {s}: revision cycles, mock frequency, analysis, formula/fact revision, sleep and health, and what to stop doing. Day-by-day table.", "A day-by-day final-month plan."),
        ('{s} formula and fact sheet', 'A compact must-know sheet for {s}.', "Create a compact must-know sheet for {s} section [SECTION]: formulas, facts, shortcuts and traps, grouped by topic. Mark anything that changes year to year to check officially.", "A compact revision sheet for one section of {s}."),
        ('{s} practice questions by topic', 'Exam-style questions for {s} with solutions.', "Write 15 {s}-style practice questions on [TOPIC] with difficulty labels and step-by-step solutions after a \"--- SOLUTIONS ---\" line. Not official questions.", "Fifteen exam-style questions with worked solutions."),
        ('{s} time management strategy', 'Finish {s} sections on time.', "Give me a time strategy for {s}: section order options, time per question type, when to skip, how to use the last minutes, and a practice drill to build speed.", "A section-by-section time strategy and speed drill."),
        ('{s} exam-day checklist', 'Everything to prepare for {s} day.', "Make an exam-day checklist for {s}: the night before, documents and ID (to verify with the official notice), what to carry, travel buffer, calm-down routine and what to do if stuck.", "A calm, complete exam-day checklist."),
    ]
    fam('Study & Exams', 'text', D.EXAMS, [t + (CHATS,) for t in EX], sub_of=lambda s: 'Exams')


# ── Coding ─────────────────────────────────────────────────────────────────
def coding():
    tools = ['Any chatbot', 'Claude', 'ChatGPT', 'GitHub Copilot', 'Cursor', 'Gemini']
    for li, (L, test, doc, lint, pkg, general) in enumerate(D.LANGS):
        lang = L.lower().replace('#', 'sharp').replace('+', 'p')
        for ti, task in enumerate(D.CODE_TASKS):
            add('Coding', L, tools[(li + ti) % 6], 'code', f"{L}: {task}", f"A focused {L} prompt to {task}, following {L} conventions.",
                f"You are a senior {L} developer. Task: {task}.\nContext: [WHAT THE CODE DOES / YOUR GOAL]. {L} version: [VERSION].\nRules: idiomatic {L}; tests with {test}; documentation with {doc}; style checked by {lint}; dependencies via {pkg} (list exact install commands).\nExplain your choices briefly, point out edge cases and security concerns, and keep the change minimal.\n```{lang}\n[PASTE CODE OR DESCRIBE]\n```",
                f"Idiomatic {L} code for the task with short explanations, edge cases and the commands to install and test it.")
        if not general:
            continue
        for ti, ds in enumerate(D.DATA_STRUCTS):
            add('Coding', L, tools[(li + ti + 1) % 6], 'code', f"Implement {a_an(ds)} in {L}", f"Build {a_an(ds)} in {L} from scratch, with tests and complexity.",
                f"Implement {a_an(ds)} in {L} from scratch (no library version).\n1. Explain how it works with a small diagram in text.\n2. Clean, commented {L} code with the main operations.\n3. Time and space complexity of each operation.\n4. Tests with {test} covering edge cases.\n5. When to use it in real projects, and the standard-library alternative.",
                f"A from-scratch {ds} in {L} with complexity, tests and real-world guidance.")
        for ti, al in enumerate(D.ALGORITHMS):
            add('DSA & Interviews', L, tools[(li + ti + 2) % 6], 'code', f"{cap(al)} in {L}", f"Learn {al} in {L}: idea, dry run, code and complexity.",
                f"Teach me {al} in {L}.\n1. The idea in plain words.\n2. A dry run on a small example.\n3. Clean {L} code with comments.\n4. Time and space complexity.\n5. Edge cases and a common interview follow-up question.\n6. Two practice problems that use it (no solutions until I ask).",
                f"An interview-ready explanation of {al} with {L} code, a dry run and practice problems.")
        for ti, pt in enumerate(D.PATTERNS):
            add('Coding', L, tools[(li + ti + 3) % 6], 'code', f"{pt} pattern in {L}", f"When and how to use the {pt} design pattern in {L}.",
                f"Explain the {pt} design pattern in {L}.\n1. The problem it solves, with a real example ([YOUR DOMAIN, e.g. payments]).\n2. Idiomatic {L} implementation (use language features instead of boilerplate where possible).\n3. A test with {test}.\n4. When NOT to use it and simpler alternatives.",
                f"A practical {pt} example in idiomatic {L} with a test and honest trade-offs.")
    for fi, F in enumerate(D.FRAMEWORKS):
        for ti, task in enumerate(D.FRAMEWORK_TASKS):
            add('Frameworks & Tools', F, tools[(fi + ti) % 6], 'code', f"{F}: {task}", f"A step-by-step {F} prompt for: {task}.",
                f"You are an expert in {F}. Help me with: {task}.\nMy setup: [VERSION, LANGUAGE, HOSTING, DATABASE]. Project: [WHAT I AM BUILDING].\nGive step-by-step instructions with code, explain why each step matters, follow current {F} best practices (say which version you assume), include security and testing notes, and list common mistakes.",
                f"Version-aware {F} guidance for {task}, with code, reasoning, security notes and pitfalls.")


# ── Careers, HR, interviews, productivity ──────────────────────────────────
ROLE_T = {
    'mock interview': ("Act as an experienced interviewer hiring a [JUNIOR / MID / SENIOR] {s}. Ask ONE question at a time and wait. Mix technical or role skills, scenarios and behavioural questions (STAR). After each answer: score out of 10, what was good, what was missing, a model answer. After 8 questions: verdict and 3 topics to practise.", "An interactive {s} interview with scores, model answers and a practice list."),
    'resume bullet points': ("Rewrite my experience as resume bullets for a {s} role. Notes: [WHAT YOU DID, TOOLS, RESULTS]. Strong action verbs, one line each, impact with numbers (write [ADD NUMBER] where I gave none — never invent), relevant keywords. 6–8 bullets plus a 2-line summary.", "Metric-driven bullets with honest placeholders and a short summary."),
    'cover letter': ("Write a cover letter for a {s} job. Job post: [PASTE]. My background: [3–5 LINES]. Company and why: [REASON]. Under 250 words, specific hook, 2–3 achievements matched to their needs, clear call to action. Do not invent achievements.", "A specific sub-250-word letter matched to the job."),
    'LinkedIn headline and About': ("Write my LinkedIn copy as a {s}. About me: [EXPERIENCE, SKILLS, RESULTS, NEXT ROLE]. 5 headlines (max 220 characters), a 150–250 word About section with a hook and 3 proof points ([ADD NUMBER] if unknown), and 10 skills to list.", "Five headlines, an About section and a skills list."),
    '30-60-90 day plan': ("Create a 30-60-90 day plan for my new {s} role at [COMPANY / INDUSTRY]. Context: [TEAM, PRODUCT, GOALS]. For each phase: goals, actions, people to meet, success measures. Table plus a 5-line spoken summary.", "A three-phase plan with a short spoken summary for interviews."),
    'salary negotiation script': ("Help me negotiate a {s} offer. Offer: [OFFER]. My research on market range: [RANGE + SOURCES]. Other offers: [ANY]. Priorities: [PAY / ROLE / FLEXIBILITY]. Give a calm script for the call and an email version, counter-offer options, and how to respond to pushback.", "A negotiation script, email and responses to common pushback."),
    'career switch plan': ("I want to switch into a {s} role from [CURRENT ROLE]. Skills I have: [LIST]. Time per week: [HOURS]. Give a 6-month plan: transferable skills, gaps, learning path with free or affordable resources, 3 portfolio projects, networking steps and how to tell my story.", "A six-month switch plan with projects and a career story."),
    'skills roadmap for one year': ("Build a 12-month skills roadmap for a {s}, from [CURRENT LEVEL] to [TARGET LEVEL]. Month-by-month topics, practice tasks, milestones, and how to prove each skill (project, certificate, portfolio piece).", "A month-by-month roadmap with proof points."),
    'portfolio project ideas': ("Suggest 8 portfolio projects for an aspiring {s} that impress hiring managers. For each: problem, scope for 2–4 weeks, skills shown, what to put in the README or case study, and a stretch goal.", "Eight portfolio projects with scope and presentation tips."),
    'top interview questions with answers': ("List 25 likely interview questions for a {s} role (technical/role, scenario and behavioural). For each: what the interviewer checks and a strong sample answer outline. Mark the 5 most common.", "Twenty-five questions with what they test and answer outlines."),
    'STAR stories for behavioural rounds': ("Help me prepare 6 STAR stories for {s} interviews (conflict, failure, leadership, tight deadline, learning fast, customer focus). Ask me questions to draw out real details, then write each story in under 150 words.", "Six concise STAR stories built from your real experience."),
    'thank-you email after interview': ("Write a thank-you email after my {s} interview with [NAME] for [ROLE]. Mention [ONE TOPIC WE DISCUSSED], reaffirm fit in one line, add a helpful follow-up [LINK / IDEA]. Under 120 words. Give 2 versions.", "Two short, specific thank-you emails."),
}
HR_T = [
    ('Job description: {s}', 'An inclusive job description for a {s}.', "Write a job description for a {s} at [COMPANY], [LOCATION / REMOTE]: about us, impact, responsibilities (6–8), must-have vs nice-to-have skills, what we offer, salary range [RANGE], how to apply. Inclusive language, no unnecessary requirements.", "A clear, inclusive job description."),
    ('Interview kit: {s}', 'Structured interview questions and scoring for a {s}.', "Create a structured interview kit for a {s}: 4–5 competencies, 2 behavioural + 1 situational question each, strong vs weak answer signals, a 1–5 scoring guide, and questions that must not be asked.", "A structured kit with scoring guides."),
    ('Onboarding plan: {s}', 'A first-month onboarding plan for a new {s}.', "Create an onboarding plan for a new {s}: before day 1, day 1, week 1, weeks 2–4, buddy, tools and access checklist, first small win, 30-day check-in questions.", "A day-1 to day-30 onboarding plan."),
    ('Skills assessment: {s}', 'A fair practical task to assess a {s} candidate.', "Design a practical skills assessment for a {s} candidate: a realistic 1–2 hour task, instructions, what good looks like, scoring rubric, and how to keep it fair and unpaid-work-free (short and not real client work).", "A fair, short assessment with a rubric."),
]


def careers():
    for ri, r in enumerate(D.ROLES):
        for ti, task in enumerate(D.ROLE_TASKS):
            body, out = ROLE_T[task]
            add('Career & Jobs', r, CHATS[(ri + ti) % 4], 'text', f"{r}: {task}", f"{cap(task)} for a {r} role, tailored and honest.", body.format(s=r), out.format(s=r))
    fam('HR & Workplace', 'text', D.ROLES, [t + (CHATS,) for t in HR_T])
    for ti, top in enumerate(D.INTERVIEW_TOPICS):
        for li, lvl in enumerate(['beginner', 'intermediate', 'advanced']):
            add('DSA & Interviews', top, CHATS[(ti + li) % 4], 'text', f"{top} interview questions ({lvl})", f"{cap(lvl)} {top} interview questions with model answers.",
                f"Give me 15 {lvl}-level interview questions on {top}. For each: a concise model answer, a follow-up the interviewer might ask, and a common mistake. Then quiz me on 5 of them one at a time and grade my answers.",
                f"Fifteen {lvl} {top} questions with answers, follow-ups and an interactive quiz.")
    for ri, r in enumerate(D.PROD_ROLES):
        for ti, task in enumerate(D.PROD_TASKS):
            add('Productivity', r, CHATS[(ri + ti) % 4], 'text', f"{task.capitalize()}: {r}", f"A practical {task} built for a {r}.",
                f"Create a {task} for me as a {r}. My context: [WORK HOURS, MAIN RESPONSIBILITIES, TOOLS, BIGGEST TIME DRAINS].\nMake it realistic, with time blocks or steps, quick wins I can start today, and a short weekly review.",
                f"A realistic {task} for a {r} with quick wins and a weekly review.")


# ── Media: YouTube, reels, video, music, writing ───────────────────────────
YT = [
    ('YouTube video ideas for {a_s}', 'Fifty video ideas for {a_s} channel.', "Give 50 YouTube video ideas for {a_s} channel aimed at [AUDIENCE]: search-based, evergreen, trending, story, comparison and series ideas. Mark the 10 easiest to make first.", "Fifty grouped ideas with ten easy first picks."),
    ('YouTube script for {a_s}', 'A full retention-focused script for {a_s}.', "Write a [MINUTES]-minute YouTube script for {a_s} about [TOPIC]: 15-second hook, promise, sections with open loops, mid-video CTA, payoff and end-screen line. Mark B-roll in [brackets].", "A full script with hook, open loops and B-roll notes."),
    ('YouTube titles and thumbnails for {a_s}', 'Honest, clickable titles and thumbnails for {a_s}.', "My video for {a_s}: [DESCRIBE]. Give 10 titles under 60 characters and 5 thumbnail concepts (image, 3–4 words, emotion, colours). Title and thumbnail must match the video.", "Ten titles and five matching thumbnail concepts."),
    ('YouTube description and chapters for {a_s}', 'SEO description with chapters for {a_s}.', "Write a YouTube description for {a_s} video [TITLE]: keyword-first 2 lines, summary, chapters starting at 0:00 (from [OUTLINE WITH TIMES]), links placeholders, 3–5 hashtags.", "A keyword-first description with valid chapters."),
    ('90-day YouTube plan for {a_s}', 'Launch or grow a channel for {a_s}.', "Make a 90-day YouTube plan for {a_s}: positioning, content pillars, upload schedule, first 12 videos, channel setup checklist and metrics (CTR, average view duration, returning viewers).", "A three-month plan with first videos and key metrics."),
    ('Shorts from a long video for {a_s}', 'Find five Shorts in a long video by {a_s}.', "From this transcript with timestamps [PASTE], find 5 standalone Shorts for {a_s}: start/end times, hook line, on-screen text and title.", "Five clip picks with timestamps, hooks and titles."),
    ('Sponsorship pitch for {a_s} channel', 'Pitch brands as {a_s} creator.', "Write a sponsorship pitch from {a_s} channel to [BRAND]. Stats: [SUBSCRIBERS, AVERAGE VIEWS, AUDIENCE]. Why the audience fits, 3 integration ideas, deliverables, rates or ask for budget, and a media-kit summary.", "A professional pitch with integration ideas and a media-kit summary."),
    ('Community posts for {a_s} channel', 'Engage subscribers of {a_s} between uploads.', "Give 20 YouTube community post ideas for {a_s} channel (polls, quizzes, behind the scenes, questions, teasers) and write 5 in full.", "Twenty post ideas with five ready to publish."),
]
REELS = [
    ('30-second Reel script for {a_s}', 'A hook-first short video script for {a_s}.', "Write a 30-second Reel/Short script for {a_s} about [TOPIC]. Table: time | shot | voice-over | on-screen text. 0–2 s hook, 2–25 s three quick points or a mini story, 25–30 s CTA. Plus 3 alternative hooks, a caption and 5 hashtags.", "A timed script with hook, value and CTA, plus extras."),
    ('Trend adaptation ideas for {a_s}', 'Adapt short-video trend formats for {a_s} without copying.', "Suggest 10 short-video formats {a_s} can adapt (before/after, day in the life, myth vs fact, POV, tutorial, transformation, Q&A, list, challenge, behind the scenes). For each: the idea for my niche and the first 3 seconds. Use only music and sounds I have rights to.", "Ten adaptable formats with niche-specific openings."),
    ('Faceless Reel ideas for {a_s}', 'Short videos for {a_s} without showing a face.', "Give 15 faceless short-video ideas for {a_s}: hands-only, screen recordings, product close-ups, text-on-screen, voice-over b-roll. For each: shot list and on-screen text.", "Fifteen faceless ideas with shot lists."),
    ('Reel series plan for {a_s}', 'A 4-week short-video series for {a_s}.', "Plan a 4-week short-video series for {a_s} (3 per week): series name, episode titles, hooks, recurring visual style and a call to action that builds followers.", "A twelve-episode series plan."),
    ('Short video tutorial for {a_s}', 'A step-by-step short tutorial video for {a_s}.', "Write a 45-second tutorial script for {a_s} teaching [SKILL / TIP] in 3–5 steps: hook, steps with on-screen text, result reveal, CTA. Include a shot list.", "A tight tutorial script with shot list."),
    ('Short video captions and hooks for {a_s}', 'Captions and on-screen hooks for {a_s}.', "Write 10 on-screen hook texts (max 8 words) and 5 captions with CTAs for {a_s} short videos about [TOPIC].", "Ten on-screen hooks and five captions."),
]


def media():
    fam('YouTube & Creators', 'text', NICHES, [t + (CHATS,) for t in YT], sub_of=cap)
    fam('Video & Reels', 'text', NICHES, [t + (CHATS,) for t in REELS], sub_of=cap)
    vtools = ['AI video (Veo / Sora / Runway / Kling)']
    for si, shot in enumerate(D.VIDEO_SHOTS):
        for ji, subj in enumerate(D.VIDEO_SUBJECTS):
            add('Video & Reels', cap(shot), vtools[0], 'video', f"{cap(subj)} — {shot}", f"An AI video prompt: {subj} with a {shot}.",
                f"Video prompt: {subj}. Camera: {shot}. Lighting: [GOLDEN HOUR / SOFT STUDIO / NEON NIGHT]. Mood: [MOOD]. Style: [CINEMATIC / DOCUMENTARY / COMMERCIAL]. Colours: [PALETTE]. Duration: [5–10] seconds. Aspect ratio: [16:9 / 9:16]. No on-screen text.\n\nTips: one camera move per clip; keep the subject simple; generate 3–4 takes and pick the best.",
                f"A short clip of {subj} with a clear {shot}; expect to run a few takes.")
    for gi, g in enumerate(D.GENRES):
        for ti, th in enumerate(D.MUSIC_THEMES):
            add('Music & Audio', cap(g), 'Suno / Udio', 'audio', f"{cap(g)} song about {th}", f"A style prompt and lyric outline for a {g} track about {th}.",
                f"Style: {g}; describe instruments, tempo and mood in your own words: [ADD DETAILS].\nSong about: {th} — [YOUR STORY OR DETAILS].\nStructure: [Intro] [Verse 1] [Chorus] [Verse 2] [Chorus] [Bridge] [Chorus] [Outro].\nLyrics: original lyrics in [LANGUAGE] with a 2-line chorus hook; short, singable lines.\nDo not name real artists; describe the sound instead.",
                f"An original {g} track about {th}; regenerate a few times and check the tool's licence before commercial use.")
    for wi, w in enumerate(D.WRITING_TASKS):
        for ai, aud in enumerate(D.AUDIENCES):
            add('Writing', cap(w), CHATS[(wi + ai) % 4], 'text', f"{cap(w)} for {aud}", f"Write {a_an(w)} that works for {aud}.",
                f"Write {a_an(w)} about [TOPIC] for {aud}.\nGoal: [GOAL]. Tone: [TONE]. Length: [LENGTH]. Key facts: [FACTS].\nAdapt vocabulary, examples and structure to {aud}; do not invent facts or quotes. Give one main version and one shorter alternative.",
                f"{cap(a_an(w))} tuned to {aud}, plus a shorter alternative.")
    for ei, e in enumerate(D.EMAIL_SITUATIONS):
        for ti, tone in enumerate(D.EMAIL_TONES):
            add('Writing', 'Emails', CHATS[(ei + ti) % 4], 'text', f"Email {e} ({tone})", f"A {tone} email for {e}.",
                f"Write a {tone} email {e}.\nTo: [RECIPIENT AND RELATIONSHIP]. Key facts: [FACTS]. What I need: [ASK]. Deadline: [DATE IF ANY].\nSubject line included, one clear ask, under 150 words, polite close. Give a second version that is even shorter.",
                f"A {tone} email with a subject line and one clear ask, plus a shorter version.")


# ── Life: health, food, languages, finance, personal ───────────────────────
HEALTH_NOTE = "\nGeneral wellness information, not medical advice. Check with a doctor first if you have a health condition, are pregnant or take medication."


def life():
    for gi, g in enumerate(D.FIT_GOALS):
        for li, lvl in enumerate(D.FIT_LEVELS):
            for ei, eq in enumerate(D.FIT_EQUIP):
                add('Health & Fitness', cap(g), CHATS[(gi + li + ei) % 4], 'text', f"{cap(g)} plan — {lvl}, {eq}", f"A 4-week {g} plan for a {lvl} with {eq}.",
                    f"Create a 4-week {g} plan for a {lvl} using {eq}.\nMe: [AGE], [DAYS PER WEEK], [MINUTES PER SESSION], injuries or limits: [LIST].\nEach day: warm-up, exercises with sets, reps and rest, form cues, cool-down. Weekly progression and signs I should rest.{HEALTH_NOTE}",
                    f"A four-week {g} plan with sets, reps, form cues and progression.")
    for di, d in enumerate(D.DIETS):
        for gi, goal in enumerate(D.DIET_GOALS):
            add('Health & Fitness', 'Nutrition', CHATS[(di + gi) % 4], 'text', f"{cap(d)} meal plan for {goal}", f"A 7-day {d} meal plan aimed at {goal}, with a grocery list.",
                f"Create a 7-day {d} meal plan for {goal}. Me: [AGE], [ACTIVITY LEVEL], foods I avoid: [LIST], cooking time: [MINUTES], budget: [AMOUNT].\nTable: Day | Breakfast | Lunch | Snack | Dinner with rough protein per meal, then a grouped grocery list and 3 prep tips.{HEALTH_NOTE}",
                "A seven-day table with approximate protein, a grocery list and prep tips.")
    for ci, c in enumerate(D.CUISINES):
        for ti, t in enumerate(D.COOK_TASKS):
            add('Food & Cooking', cap(c), CHATS[(ci + ti) % 4], 'text', f"{cap(c)}: {t}", f"{cap(t)} for {c} food.",
                f"Help me with {t} for {c} cooking. My kitchen: [TOOLS]. Skill: [BEGINNER / HOME COOK]. Diet: [PREFERENCES / ALLERGIES]. Servings: [N]. Time: [MINUTES].\nGive clear steps, ingredient quantities, substitutions for hard-to-find items, and food-safety notes.",
                f"Clear, practical {c} cooking help with quantities and substitutions.")
    for li, lang in enumerate(D.LANGUAGES):
        for ti, t in enumerate(D.LANG_TASKS):
            add('Language Learning', lang, CHATS[(li + ti) % 4], 'text', f"{lang}: {t}", f"{cap(t)} for learning {lang}.",
                f"Be my {lang} tutor. Help me with: {t}.\nMy level: [BEGINNER / A1–C1]. My native language: [LANGUAGE]. Goal: [TRAVEL / EXAM / WORK / FUN]. Time per day: [MINUTES].\nUse simple explanations, include pronunciation help where useful (romanisation if the script is new to me), and correct my mistakes gently with the reason.",
                f"Level-appropriate {lang} practice for {t}, with gentle corrections.")
    for si, st in enumerate(D.FIN_STAGES):
        for ti, t in enumerate(D.FIN_TOPICS):
            add('Personal Finance', cap(st), CHATS[(si + ti) % 4], 'text', f"{cap(t)} for a {st}", f"A {t} built for a {st}.",
                f"Help me with a {t} as a {st}. My numbers: income [AMOUNT], fixed costs [LIST], savings [AMOUNT], debts [LIST], goals [GOALS].\nShow the maths, keep it practical, and list what I should verify with current rules.\nThis is education, not financial advice; consult a registered adviser for big decisions.",
                f"A practical {t} for a {st}, maths shown, with things to verify.")
    for pi, p in enumerate(D.PERSONAL_TOPICS):
        add('Personal Growth', cap(p), CHATS[pi % 4], 'text', f"{cap(p)}: 30-day plan", f"A small-steps 30-day plan for {p}.",
            f"Create a 30-day plan for {p}. My situation: [CONTEXT]. Time per day: [MINUTES]. Obstacles: [LIST].\nWeekly themes, a daily small action, a check-in question per week, and what to do on bad days.",
            f"A gentle 30-day plan for {p} with daily actions and weekly check-ins.")
        add('Personal Growth', cap(p), CHATS[(pi + 1) % 4], 'text', f"{cap(p)}: coach-style conversation", f"A coaching conversation that helps with {p}.",
            f"Act as a supportive coach helping me with {p}. Ask me one question at a time to understand my situation, reflect back what you hear, then help me choose one small next step. Keep it practical and kind. If anything sounds like a mental health crisis, encourage me to contact a professional or local helpline.",
            f"A one-question-at-a-time coaching chat that ends in a small next step.")


# ── Tech & work tools: data, agents, product, web, cyber, devops ──────────
def tech():
    for fi, f in enumerate(D.EXCEL_FUNCS):
        tool = 'Google Sheets' if 'Google Sheets' in f or f == 'Apps Script basics' else ('Excel' if f in ('VBA macros basics', 'Power Query', 'Flash Fill') else 'Excel / Google Sheets')
        for ti, (t, body, out) in enumerate([
            ('explained with an example', "Explain {f} in {tool} with my data: [DESCRIBE COLUMNS]. Syntax, each argument, 3 examples from simple to advanced, and when to use something else.", "Syntax, argument-by-argument explanation and three examples for {f}."),
            ('fix my errors', "My {f} formula in {tool} gives [ERROR / WRONG RESULT]: [PASTE FORMULA AND SAMPLE DATA]. Explain the cause, give the fixed formula, and how to avoid it next time.", "The cause, a fixed formula and prevention tips."),
            ('practice exercises', "Give me 5 practice exercises for {f} in {tool}, from easy to hard, with a small sample table for each and solutions at the end after \"--- SOLUTIONS ---\".", "Five graded exercises with sample data and solutions."),
        ]):
            c = dict(f=f, tool=tool)
            add('Data & Excel', tool, CHATS[(fi + ti) % 4], 'text', f"{f}: {t}", f"{f} in {tool} — {t}.", body.format(**c), out.format(**c))
    for qi, q in enumerate(D.SQL_TASKS):
        for di, dialect in enumerate(D.SQL_DIALECTS):
            add('Data & Excel', 'SQL', CHATS[(qi + di) % 4], 'code', f"SQL {q} ({dialect})", f"Write {q} in {dialect}, explained.",
                f"Write a {dialect} query for: {q}.\nMy tables: [TABLE(columns, types)]. Explain the query step by step, mention indexes that help, edge cases (NULLs, ties, duplicates), and show sample output columns. Use {dialect}-specific syntax correctly.",
                f"A correct {dialect} query for {q} with explanation, indexes and edge cases.")
    for pi, pt in enumerate(D.PANDAS_TASKS):
        for ti, t in enumerate(['code with explanation', 'debug my code', 'practice exercise']):
            add('Data & Excel', 'Python pandas', CHATS[(pi + ti) % 4], 'code', f"pandas: {pt} ({t})", f"pandas help to {pt}: {t}.",
                f"Help me {pt} with Python pandas ({t}). My data: [COLUMNS, TYPES, SAMPLE ROWS]. Goal: [GOAL].\nComment every step, show expected output, mention performance tips for large data, and common mistakes.",
                f"Commented pandas code to {pt}, with expected output and pitfalls.")
    for bi, b in enumerate(D.BIZ_FUNCTIONS):
        for ti, t in enumerate(D.AGENT_TASKS):
            add('AI Agents & Automation', cap(b), ['Claude', 'ChatGPT', 'Any chatbot', 'Gemini'][(bi + ti) % 4], 'text', f"{cap(b)}: {t}", f"{cap(t)} for automating {b} with AI.",
                f"Help me with an {t} for {b} at [COMPANY TYPE, SIZE]. Current process: [STEPS, TOOLS, TIME SPENT].\nKeep humans in charge of risky or irreversible actions, never expose personal data or secrets, list failure cases and how to handle them, and say how to measure success.",
                f"A practical {t} for {b} with human checkpoints, failure handling and metrics.")
    for ai, a in enumerate(D.ASSISTANTS):
        for ti, (t, body, out) in enumerate([
            ('system prompt', "Write a system prompt for an AI {a}: role and goal, audience, tone, knowledge it may use ([PASTE FACTS]), what it must never do, how to answer when unsure (say so and offer help), output format, and 3 example exchanges.", "A structured system prompt with rules, fallback behaviour and examples."),
            ('test cases', "Write 20 test conversations for an AI {a}: 10 normal, 6 edge cases (vague, very long, other language, typos), 4 adversarial (prompt injection, off-topic, unsafe request) with the expected safe behaviour.", "Twenty test cases with expected behaviour."),
            ('conversation flow', "Design the conversation flow of an AI {a}: greeting, questions to ask, how to confirm understanding, how to hand over to a human, and how to end. Include example messages.", "A complete conversation flow with example messages."),
        ]):
            add('AI Agents & Automation', 'Assistants', 'Any chatbot', 'text', f"AI {a}: {t}", f"{cap(t)} for building an AI {a}.", body.format(a=a), out)
    for pi, p in enumerate(D.PRODUCT_TYPES):
        for ti, t in enumerate(D.PRODUCT_TASKS):
            add('Product & UX', cap(p), CHATS[(pi + ti) % 4], 'text', f"{cap(p)}: {t}", f"{cap(t) if t.islower() else t} for {a_an(p)}.",
                f"Create a {t} for {a_an(p)} called [PRODUCT] for [TARGET USERS].\nContext: [STAGE, GOALS, CONSTRAINTS, WHAT WE KNOW FROM RESEARCH]. Mark assumptions clearly, keep it actionable, and include how we will measure success.",
                f"A practical {t} for {a_an(p)} with assumptions marked and success measures.")
    for si, s in enumerate(D.SITE_TYPES):
        for ki, stack in enumerate(D.SITE_STACKS):
            add('Website Builders', cap(s), 'Lovable / v0 / Bolt / Claude', 'code', f"Build a {s} ({stack})", f"A detailed prompt to generate a {s} with {stack}.",
                f"Build a responsive {s} for [BRAND] in [NICHE] using {stack}.\nSections: [LIST OR 'suggest the right sections for a {s}'].\nDesign: [STYLE], colours [PRIMARY, SECONDARY], font style [SANS / SERIF], rounded corners, generous spacing.\nContent: [PASTE COPY] or realistic placeholders (no lorem ipsum).\nAccessible (semantic HTML, alt text, keyboard focus, AA contrast), mobile-first and fast (optimised images, lazy loading). After building, list what to customise.",
                f"A complete first version of the {s}; iterate with short follow-ups like \"make the hero bolder\".")
    for ci, c in enumerate(D.CYBER_TOPICS):
        for li, lvl in enumerate(D.CYBER_LEVELS):
            add('Cybersecurity', cap(c), CHATS[(ci + li) % 4], 'text', f"{cap(c)}: {lvl}", f"A defensive {lvl} on {c}.",
                f"Create a {lvl} on {c} for [AUDIENCE: home users / small team / developers].\nFocus on defence and prevention, include practical steps, how to check they worked, and what to do if something goes wrong. No exploit code or instructions for attacking systems you do not own.",
                f"A defensive {lvl} on {c} with practical steps and checks.")
    for di, d in enumerate(D.DEVOPS_TOOLS):
        for ti, t in enumerate(D.DEVOPS_TASKS):
            add('DevOps & Cloud', d, CHATS[(di + ti) % 4], 'code', f"{d}: {t}", f"{cap(t)} for {d}.",
                f"Help me with a {t} for {d}. My environment: [CLOUD / OS / VERSIONS / SCALE].\nGive commands or config with explanations, safe defaults (least privilege, no secrets in files), how to verify each step, and how to roll back.",
                f"A safe, verifiable {t} for {d} with rollback steps.")


def main():
    images(); social(); business(); ecommerce(); travel(); study(); coding(); careers(); media(); life(); tech()
    print('generated', len(OUT))
    # Existing ids (hand-made + 1.2 generated) are reserved.
    seen = set()
    for f in ('prompts.php', 'prompts-gen.php'):
        path = os.path.join(PLUGIN, 'includes', 'data', f)
        if os.path.exists(path):
            seen.update(re.findall(r"'id' => '([a-z0-9-]+)'", open(path, encoding='utf-8').read()))
            seen.update(re.findall(r"\$p\( '([a-z0-9-]+)'", open(path, encoding='utf-8').read()))
    reserved = len(seen)
    target = TOTAL - (1042 if reserved >= 1000 else reserved)
    # Interleave categories so trimming never removes a whole category.
    from collections import defaultdict, OrderedDict
    bycat = OrderedDict()
    for it in OUT:
        bycat.setdefault(it['cat'], []).append(it)
    titles = set()
    final = []
    idx = defaultdict(int)
    cats = list(bycat)
    while len(final) < target and cats:
        for c in list(cats):
            if len(final) >= target:
                break
            lst = bycat[c]
            if idx[c] >= len(lst):
                cats.remove(c)
                continue
            it = lst[idx[c]]
            idx[c] += 1
            key = it['title'].lower()
            if key in titles:
                continue
            titles.add(key)
            final.append(it)
    # ids, premium flag, rank
    for n, it in enumerate(final):
        base = re.sub(r'[^a-z0-9]+', '-', it['title'].lower()).strip('-')[:54].strip('-') or 'prompt'
        sid, k = base, 2
        while sid in seen:
            sid = '%s-%d' % (base[:50], k)
            k += 1
        seen.add(sid)
        it['id'] = sid
        it['premium'] = (n % 10) in (2, 5, 8)
    out_dir = os.path.join(PLUGIN, 'data', 'prompts')
    os.makedirs(out_dir, exist_ok=True)
    with open(os.path.join(out_dir, 'index.php'), 'w') as fh:
        fh.write('<?php\n// Silence is golden.\n')
    # Sort by category for nicer browsing order? Keep interleaved order within each part.
    PART = 5000
    keys = ('id', 'title', 'cat', 'sub', 'tool', 'kind', 'premium', 'summary', 'prompt', 'output')
    for fn in os.listdir(out_dir):
        if fn.startswith('part-'):
            os.remove(os.path.join(out_dir, fn))
    for p in range(0, len(final), PART):
        body = ''.join(json.dumps({k: it[k] for k in keys}, ensure_ascii=False, separators=(',', ':')) + '\n' for it in final[p:p + PART])
        # A PHP file that exits when opened in a browser; the data after __halt_compiler() is gzip.
        with open(os.path.join(out_dir, 'part-%03d.php' % (p // PART)), 'wb') as fh:
            fh.write(b'<?php exit; __halt_compiler();')
            fh.write(gzip.compress(body.encode('utf-8'), 9))
    from collections import Counter
    cnt = Counter(i['cat'] for i in final)
    with open(os.path.join(out_dir, 'manifest.json'), 'w') as fh:
        json.dump({'count': len(final), 'parts': -(-len(final) // PART), 'categories': cnt}, fh, indent=1)
    print('written', len(final), 'pro', sum(i['premium'] for i in final), 'reserved ids', reserved)
    for c, n in cnt.most_common():
        print('  %-24s %d' % (c, n))


if __name__ == '__main__':
    main()
