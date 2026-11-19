#!/usr/bin/env python3
"""Generate the AI Hub Motion Studio catalogue (designs added to the hand-made ones).

Every design is one self-contained HTML file (inline CSS + vanilla JS, no
network, no libraries). A design = one motion engine (34 original engines)
+ a content theme + a colour palette + a type style + engine parameters.

Output (inside the plugin):
  includes/data/motion-gen.php            metadata only (small, loaded on list pages)
  includes/data/motion-gen/<id>.php       code + build prompt (loaded on view/copy;
                                          direct web access exits, so Pro code never leaks)

Usage: python3 tools/aihub/gen_motion.py [plugin_dir] [count]
"""
import hashlib
import json
import os
import random
import re
import sys

PLUGIN = sys.argv[1] if len(sys.argv) > 1 else os.path.join(os.path.dirname(__file__), '..', '..', 'plugins', 'vitalstack-ai-hub')
COUNT = int(sys.argv[2]) if len(sys.argv) > 2 else 477

# ── Palettes: name, bg, surface, ink, muted, a1, a2, a3, dark ──────────────
PALETTES = [
    ('Midnight Violet', '#07061a', '#13112e', '#f4f2ff', '#a7a3c9', '#7c3aed', '#22d3ee', '#f472b6', True),
    ('Deep Ocean', '#03131f', '#0a2233', '#e8f6ff', '#8fb3c9', '#0ea5e9', '#14b8a6', '#a3e635', True),
    ('Ember Night', '#140806', '#24110c', '#fff3ec', '#c9a597', '#f97316', '#ef4444', '#facc15', True),
    ('Forest Mist', '#06140e', '#0d2419', '#ecfff5', '#97c4ad', '#22c55e', '#84cc16', '#2dd4bf', True),
    ('Neon Arcade', '#05010f', '#120726', '#fbf7ff', '#b49fd6', '#e879f9', '#22d3ee', '#fde047', True),
    ('Graphite Gold', '#0d0d0f', '#1a1a1e', '#f6f3ea', '#a8a49a', '#eab308', '#f59e0b', '#e5e7eb', True),
    ('Rose Noir', '#14060c', '#250d18', '#fff0f5', '#cf9fb4', '#fb7185', '#f472b6', '#fda4af', True),
    ('Arctic Blue', '#020617', '#0f1b33', '#eef4ff', '#94a3b8', '#3b82f6', '#60a5fa', '#c084fc', True),
    ('Matrix Green', '#010a04', '#06180c', '#e6ffee', '#7fb592', '#22c55e', '#4ade80', '#bef264', True),
    ('Copper Dusk', '#120c0a', '#221814', '#fbefe7', '#c2a796', '#ea580c', '#d97706', '#fbbf24', True),
    ('Paper Ink', '#f7f5f0', '#ffffff', '#16140f', '#6b675d', '#111827', '#e11d48', '#2563eb', False),
    ('Mint Cream', '#f1fbf6', '#ffffff', '#0b2a1d', '#4f7364', '#059669', '#0ea5e9', '#f59e0b', False),
    ('Peach Sorbet', '#fff5ef', '#ffffff', '#2b1610', '#7d5a4e', '#f97316', '#ec4899', '#8b5cf6', False),
    ('Lavender Air', '#f6f4ff', '#ffffff', '#1b1640', '#625c8a', '#7c3aed', '#06b6d4', '#f43f5e', False),
    ('Sky Daylight', '#f0f7ff', '#ffffff', '#0b1b33', '#53657d', '#2563eb', '#0891b2', '#f97316', False),
    ('Sand Studio', '#f6f1e7', '#fffdf8', '#2a2418', '#7a6f5a', '#b45309', '#0f766e', '#be123c', False),
    ('Mono Contrast', '#0a0a0a', '#171717', '#fafafa', '#a3a3a3', '#fafafa', '#a3a3a3', '#525252', True),
    ('Citrus Pop', '#fffbea', '#ffffff', '#1f1a05', '#6f6640', '#eab308', '#16a34a', '#ef4444', False),
]

FONTS = [
    ('Modern sans', 'system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif', 800, '-.035em'),
    ('Editorial serif', 'Georgia,"Iowan Old Style","Times New Roman",serif', 700, '-.02em'),
    ('Developer mono', 'ui-monospace,SFMono-Regular,Menlo,Consolas,"Liberation Mono",monospace', 700, '-.04em'),
    ('Friendly rounded', 'ui-rounded,"SF Pro Rounded","Nunito","Segoe UI",system-ui,sans-serif', 800, '-.02em'),
    ('Condensed display', '"Arial Narrow","Roboto Condensed","Helvetica Neue",Arial,sans-serif', 800, '-.01em'),
    ('Humanist', '"Trebuchet MS","Gill Sans","Segoe UI",system-ui,sans-serif', 700, '-.025em'),
]

# ── Content themes ─────────────────────────────────────────────────────────
def T(key, label, brand, headline, sub, cta, cta2, items, stats, quote, who, words):
    return dict(key=key, label=label, brand=brand, headline=headline, sub=sub, cta=cta, cta2=cta2,
                items=items, stats=stats, quote=quote, who=who, words=words)

THEMES = [
    T('ai-saas', 'AI SaaS', 'Nimbus AI', 'Ship features at the speed of thought', 'An AI copilot that writes, tests and reviews your code while you focus on the product.', 'Start free', 'Watch demo',
      ['Autocomplete', 'Code review', 'Test writer', 'Docs bot', 'Bug finder', 'Deploy check'], [('10x', 'faster reviews'), ('42k', 'developers'), ('99.9%', 'uptime')],
      'We cut our review time in half in the first week.', 'Priya S., CTO at Loopline', ['faster', 'smarter', 'safer', 'together']),
    T('fintech', 'Fintech', 'Ledgerly', 'Money that moves as fast as you do', 'Instant transfers, smart budgets and a card that pays you back, all in one app.', 'Open account', 'See pricing',
      ['Instant UPI', 'Smart budgets', 'Cashback card', 'Bill reminders', 'Savings pots', 'Tax reports'], [('₹0', 'monthly fee'), ('2M+', 'customers'), ('4.8★', 'app rating')],
      'Finally a bank app that I actually enjoy opening.', 'Arjun M., freelancer', ['simple', 'instant', 'secure', 'yours']),
    T('fitness', 'Fitness', 'PulseFit', 'Train smarter, recover faster', 'Personal workout plans that adapt to your sleep, stress and progress every single day.', 'Start training', 'View plans',
      ['Strength', 'HIIT', 'Mobility', 'Running', 'Yoga flow', 'Recovery'], [('320+', 'workouts'), ('15 min', 'daily plans'), ('1M', 'reps logged')],
      'Down 8 kg and stronger than ever. The plans just work.', 'Neha K., Pune', ['stronger', 'fitter', 'faster', 'calmer']),
    T('coffee', 'Coffee shop', 'Brew & Bloom', 'Slow coffee for fast mornings', 'Single-origin beans roasted every Monday and brewed by people who care.', 'Order ahead', 'Our menu',
      ['Espresso', 'Pour over', 'Cold brew', 'Flat white', 'Matcha', 'Croissants'], [('12', 'origins'), ('48h', 'from roast'), ('3', 'cafés')],
      'The best flat white in the city, no contest.', 'Rahul D., regular since day one', ['bold', 'smooth', 'fresh', 'local']),
    T('travel', 'Travel', 'Wander Atlas', 'Go further than the guidebook', 'Hand-planned trips with local hosts, hidden spots and zero tourist traps.', 'Plan my trip', 'Explore places',
      ['Mountains', 'Islands', 'Old towns', 'Deserts', 'Food trails', 'Road trips'], [('64', 'countries'), ('900+', 'local hosts'), ('4.9★', 'trip rating')],
      'Every day felt like a secret the city told only us.', 'Sara & Tom, Lisbon trip', ['wilder', 'deeper', 'slower', 'brighter']),
    T('real-estate', 'Real estate', 'Keystone Homes', 'Find the home that fits your life', 'Verified listings, honest prices and virtual tours you can take from the sofa.', 'Browse homes', 'Book a visit',
      ['Apartments', 'Villas', 'Studios', 'Rentals', 'New launches', 'Commercial'], [('8,400', 'listings'), ('100%', 'verified'), ('21 days', 'avg. to move')],
      'We saw 30 homes online and only visited the one we bought.', 'The Iyer family', ['brighter', 'bigger', 'closer', 'home']),
    T('music-fest', 'Music festival', 'Solstice Fest', 'Three nights. One sky. Forty artists.', 'A weekend of live music, art and food under the open sky, 14 to 16 March.', 'Get passes', 'Line-up',
      ['Main stage', 'Night garden', 'Silent disco', 'Art walk', 'Food court', 'Camping'], [('40', 'artists'), ('3', 'stages'), ('25k', 'fans')],
      'The sunrise set on day two changed my life.', 'Kabir, festival regular', ['louder', 'brighter', 'freer', 'alive']),
    T('portfolio', 'Designer portfolio', 'Mira Chen', 'I design calm interfaces for busy people', 'Product designer helping startups turn complex tools into simple, lovable products.', 'View work', 'Say hello',
      ['Brand identity', 'Product UI', 'Design systems', 'Prototypes', 'Motion', 'Workshops'], [('9', 'years'), ('60+', 'products'), ('12', 'awards')],
      'Mira turned a 40-screen mess into 9 screens users love.', 'Daniel R., founder', ['calm', 'clear', 'useful', 'human']),
    T('edtech', 'EdTech', 'LearnLoop', 'Learn to code, one small win a day', 'Bite-size lessons, live coding and projects that end up in your portfolio.', 'Start learning', 'See courses',
      ['Python', 'JavaScript', 'SQL', 'Data science', 'AI basics', 'Web design'], [('300+', 'lessons'), ('5 min', 'per lesson'), ('80k', 'learners')],
      'I got my first developer job eight months after lesson one.', 'Ananya P., junior developer', ['easier', 'faster', 'deeper', 'daily']),
    T('clinic', 'Healthcare clinic', 'CareWell Clinic', 'Care that listens first', 'Same-day appointments, kind doctors and your reports on your phone.', 'Book appointment', 'Our doctors',
      ['General care', 'Dental', 'Pediatrics', 'Lab tests', 'Physio', 'Teleconsult'], [('15 min', 'avg. wait'), ('24/7', 'teleconsult'), ('30+', 'doctors')],
      'They explained everything and never rushed us.', 'Meera, mother of two', ['kinder', 'quicker', 'closer', 'healthier']),
    T('fashion', 'Fashion', 'Atelier Nord', 'Made slowly. Worn forever.', 'Small-batch clothing in natural fabrics, cut to last for years, not seasons.', 'Shop collection', 'Lookbook',
      ['Outerwear', 'Knitwear', 'Linen', 'Denim', 'Accessories', 'Footwear'], [('100%', 'natural fibres'), ('12', 'pieces per drop'), ('0', 'fast fashion')],
      'My coat from them is five years old and still looks new.', 'Isha T., Bengaluru', ['timeless', 'honest', 'soft', 'yours']),
    T('gaming', 'Gaming', 'Pixel Forge', 'Enter the arena. Leave a legend.', 'A fast, fair team shooter with ranked seasons, custom maps and zero pay-to-win.', 'Play free', 'Watch trailer',
      ['Ranked', 'Arcade', 'Map editor', 'Clans', 'Tournaments', 'Skins'], [('5M', 'players'), ('60 fps', 'on mobile'), ('S7', 'season live')],
      'The only shooter where skill really decides the match.', 'xNova, pro player', ['faster', 'fairer', 'louder', 'legendary']),
    T('restaurant', 'Restaurant', 'Saffron Table', 'Old recipes, new table', 'Coastal Indian food cooked over fire, with a menu that changes with the market.', 'Reserve a table', 'See menu',
      ['Tasting menu', 'Seafood', 'Tandoor', 'Thalis', 'Desserts', 'Cocktails'], [('18', 'dishes'), ('1', 'wood fire'), ('4.7★', 'on maps')],
      'Every plate tastes like a holiday in Goa.', 'Food & Travel Weekly', ['smoky', 'fresh', 'bold', 'soulful']),
    T('photo', 'Photography', 'Lumen Studio', 'Light, caught honestly', 'Wedding, portrait and product photography with a documentary eye.', 'Check dates', 'Portfolio',
      ['Weddings', 'Portraits', 'Products', 'Events', 'Editorial', 'Prints'], [('400+', 'shoots'), ('2 weeks', 'delivery'), ('11', 'cities')],
      'They captured moments we did not even notice happening.', 'Ritika & Sam', ['honest', 'warm', 'timeless', 'bright']),
    T('architecture', 'Architecture', 'Form & Field', 'Buildings that breathe', 'An architecture studio designing climate-smart homes and public spaces.', 'See projects', 'Contact studio',
      ['Residential', 'Public space', 'Interiors', 'Landscape', 'Restoration', 'Research'], [('70', 'projects'), ('40%', 'less energy'), ('8', 'awards')],
      'Our home stays cool all summer without an AC.', 'Client, Jaipur house', ['lighter', 'cooler', 'calmer', 'greener']),
    T('nonprofit', 'Nonprofit', 'Open Shelf', 'Every child deserves a library', 'We build free community libraries in villages and run reading clubs every week.', 'Donate now', 'Volunteer',
      ['Libraries', 'Reading clubs', 'Teacher kits', 'Digital labs', 'Scholarships', 'Events'], [('230', 'libraries'), ('60k', 'children'), ('₹500', 'buys 10 books')],
      'My daughter reads to me every night now.', 'Parent, Wardha district', ['brighter', 'kinder', 'wiser', 'together']),
    T('podcast', 'Podcast', 'Signal & Noise', 'Big ideas in 30 minutes', 'A weekly podcast about technology, culture and the people building the future.', 'Listen now', 'All episodes',
      ['Latest episode', 'Interviews', 'Deep dives', 'Q&A', 'Newsletter', 'Live shows'], [('180', 'episodes'), ('2M', 'listens'), ('Top 10', 'tech charts')],
      'My favourite 30 minutes of the week.', 'Listener review', ['louder', 'sharper', 'curious', 'weekly']),
    T('bakery', 'Bakery', 'Crumb & Co.', 'Baked at dawn, gone by noon', 'Sourdough, laminated pastries and cakes made from scratch every morning.', 'Order now', 'Today’s bakes',
      ['Sourdough', 'Croissants', 'Cakes', 'Cookies', 'Brioche', 'Custom orders'], [('4am', 'ovens on'), ('36h', 'ferment'), ('0', 'preservatives')],
      'The croissant that ruined all other croissants for me.', 'Aditi, food blogger', ['fresh', 'warm', 'flaky', 'golden']),
    T('agency', 'Digital agency', 'Northbeam', 'We build brands people remember', 'Strategy, design and development for ambitious startups and scale-ups.', 'Start a project', 'Case studies',
      ['Strategy', 'Branding', 'Websites', 'Apps', 'Growth', 'Content'], [('120+', 'launches'), ('3x', 'avg. conversion'), ('14', 'people')],
      'They felt like part of our team from day one.', 'Founder, Kite Health', ['bolder', 'sharper', 'faster', 'memorable']),
    T('ev', 'Electric cars', 'Volt Drive', 'Silent power. Zero excuses.', 'An electric SUV with 520 km range, 18-minute fast charging and room for everyone.', 'Book a test drive', 'Configure',
      ['Range', 'Fast charging', 'Autopilot', 'Interior', 'Safety', 'Warranty'], [('520 km', 'range'), ('18 min', '10–80%'), ('5★', 'safety')],
      'I forgot what a petrol pump looks like.', 'Owner, Hyderabad', ['quieter', 'cleaner', 'quicker', 'electric']),
    T('skincare', 'Skincare', 'Dew Lab', 'Skincare that keeps it simple', 'Three-step routines with clinically tested ingredients and nothing you do not need.', 'Find my routine', 'Ingredients',
      ['Cleanser', 'Serum', 'Moisturiser', 'Sunscreen', 'Night repair', 'Kits'], [('3', 'steps'), ('28 days', 'to results'), ('0%', 'fragrance')],
      'My skin has never been this calm.', 'Tanvi, verified buyer', ['softer', 'calmer', 'brighter', 'simple']),
    T('yoga', 'Yoga studio', 'Still Point Yoga', 'Breathe in. Slow down.', 'Small classes, kind teachers and a quiet space to come back to yourself.', 'Book a class', 'Timetable',
      ['Hatha', 'Vinyasa', 'Yin', 'Pranayama', 'Meditation', 'Workshops'], [('6', 'classes daily'), ('12', 'per class'), ('9', 'teachers')],
      'The only hour of my week with no phone and no stress.', 'Member since 2021', ['calmer', 'stronger', 'lighter', 'present']),
    T('space', 'Space & science', 'Orbit Lab', 'Look up. Then go further.', 'A citizen-science platform where anyone can help discover new planets and asteroids.', 'Join a mission', 'Live sky',
      ['Exoplanets', 'Asteroids', 'Galaxies', 'Satellites', 'Night sky', 'Classes'], [('2.1M', 'classifications'), ('37', 'discoveries'), ('140', 'countries')],
      'A 14-year-old on our platform found a new asteroid.', 'Orbit Lab team', ['further', 'deeper', 'brighter', 'together']),
    T('cyber', 'Cybersecurity', 'Sentinel Shield', 'Stop threats before they start', 'Real-time protection, phishing defence and 24/7 monitoring for growing teams.', 'Get protected', 'Free audit',
      ['Endpoint', 'Phishing', 'Zero trust', 'Backups', 'Monitoring', 'Training'], [('24/7', 'monitoring'), ('3 min', 'avg. response'), ('0', 'breaches')],
      'We blocked a ransomware attempt in the first month.', 'IT lead, Medlink', ['safer', 'faster', 'smarter', 'calmer']),
    T('devtools', 'Developer tools', 'Stackline', 'Deploy on push. Sleep at night.', 'Preview environments, instant rollbacks and logs that make sense.', 'Deploy now', 'Read docs',
      ['Previews', 'Rollbacks', 'Edge cache', 'Logs', 'Cron jobs', 'Secrets'], [('30s', 'deploys'), ('300+', 'edge locations'), ('1-click', 'rollback')],
      'We went from weekly to 40 deploys a day.', 'Lead engineer, Pixelcart', ['faster', 'safer', 'simpler', 'global']),
]

# ── Engines ────────────────────────────────────────────────────────────────
# Each returns (css, body, js, notes) for a context c:
#   c['p'] palette dict, c['f'] font, c['t'] theme, c['r'] random.Random, c['v'] variant int
ENGINES = []


def engine(slug, name, cat, tags, summary, motion):
    def deco(fn):
        ENGINES.append(dict(slug=slug, name=name, cat=cat, tags=tags, summary=summary, motion=motion, fn=fn))
        return fn
    return deco


def esc(s):
    return s.replace('&', '&amp;').replace('<', '&lt;').replace('>', '&gt;').replace('"', '&quot;')


def jsstr(s):
    return json.dumps(s, ensure_ascii=False).replace('</', '<\\/')


def nav(c):
    t = c['t']
    return ('<nav class="nav"><b class="logo">%s</b><span class="links"><a href="#">%s</a><a href="#">%s</a><a href="#">%s</a></span><a class="btn sm" href="#">%s</a></nav>'
            % (esc(t['brand']), esc(t['items'][0]), esc(t['items'][1]), esc(t['items'][2]), esc(t['cta'])))


NAV_CSS = ('.nav{position:relative;z-index:5;display:flex;align-items:center;gap:18px;padding:18px clamp(16px,4vw,48px)}'
           '.logo{font-weight:800;letter-spacing:-.02em;font-size:18px}.links{display:flex;gap:18px;margin-left:auto}'
           '.links a{color:var(--muted);text-decoration:none;font-size:14px}.links a:hover{color:var(--ink)}'
           '@media(max-width:640px){.links{display:none}.nav .btn{margin-left:auto}}')

BTN_CSS = ('.btn{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:12px;background:var(--a1);color:var(--on);'
           'text-decoration:none;font-weight:700;border:0;cursor:pointer;font:inherit;font-weight:700;transition:transform .2s,box-shadow .2s}'
           '.btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px -10px var(--a1)}.btn.ghost{background:transparent;color:var(--ink);box-shadow:inset 0 0 0 1px var(--line)}'
           '.btn.sm{padding:9px 16px;font-size:14px}.btn:focus-visible,a:focus-visible,button:focus-visible{outline:2px solid var(--a2);outline-offset:3px}')


def hero_copy(c, cls='copy'):
    t = c['t']
    return ('<div class="%s"><p class="eyebrow">%s</p><h1>%s</h1><p class="sub">%s</p><div class="row"><a class="btn" href="#">%s</a><a class="btn ghost" href="#">%s</a></div></div>'
            % (cls, esc(t['label']), esc(t['headline']), esc(t['sub']), esc(t['cta']), esc(t['cta2'])))


COPY_CSS = ('.eyebrow{display:inline-block;padding:6px 12px;border-radius:99px;font-size:12.5px;letter-spacing:.08em;text-transform:uppercase;'
            'background:color-mix(in srgb,var(--a1) 16%,transparent);color:var(--ink);box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--a1) 40%,transparent)}'
            'h1{font-size:clamp(34px,6.4vw,76px);line-height:1.02;letter-spacing:var(--ls);font-weight:var(--hw);margin:16px 0 14px;text-wrap:balance}'
            '.sub{color:var(--muted);font-size:clamp(15px,1.9vw,19px);max-width:56ch;line-height:1.55}.row{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}')


def center_hero_css():
    return '.copy{position:relative;z-index:2;max-width:880px;margin:0 auto;text-align:center;padding:clamp(48px,10vh,120px) 20px}.copy .sub{margin:0 auto}.copy .row{justify-content:center}'


def left_hero_css():
    return '.copy{position:relative;z-index:2;max-width:640px;padding:clamp(40px,9vh,110px) clamp(16px,5vw,64px)}'


def pick(r, xs):
    return xs[r.randrange(len(xs))]


# 1. Aurora blobs
@engine('aurora-blobs', 'Aurora Blobs Hero', 'Hero', 'gradient blur aurora blobs hero landing',
        'Soft glowing colour blobs drift and blend behind a bold {label} headline.',
        ['{n} blurred circles (filter: blur({blur}px), mix-blend-mode: {blend}) drift on {dur}s alternate keyframes with different delays.',
         'Each blob moves with translate + scale only, so it stays at 60 fps.',
         'Content sits above the blobs in a glassy eyebrow pill, headline, sub-copy and two buttons.'])
def e_aurora(c):
    r = c['r']
    n = 3 + c['v'] % 3
    blur = pick(r, [60, 80, 100])
    blend = 'screen' if c['p']['dark'] else 'multiply'
    dur = pick(r, [14, 18, 22])
    c['vars'].update(n=n, blur=blur, blend=blend, dur=dur)
    cols = ['var(--a1)', 'var(--a2)', 'var(--a3)']
    blobs, css = '', ''
    for i in range(n):
        x, y, s = r.randint(-15, 70), r.randint(-20, 60), r.randint(30, 50)
        css += '.b%d{left:%d%%;top:%d%%;width:%dvmax;height:%dvmax;background:%s;animation-delay:-%ds}' % (i, x, y, s, s, cols[i % 3], i * 5)
        blobs += '<i class="blob b%d"></i>' % i
    css += ('body{min-height:100vh;overflow:hidden}.blob{position:absolute;border-radius:50%%;filter:blur(%dpx);opacity:%s;mix-blend-mode:%s;animation:drift %ds ease-in-out infinite alternate}'
            '@keyframes drift{50%%{transform:translate(9vmax,-6vmax) scale(1.2)}to{transform:translate(-6vmax,7vmax) scale(.9)}}' % (blur, '.55' if c['p']['dark'] else '.45', blend, dur))
    css += NAV_CSS + COPY_CSS + center_hero_css()
    css += '.bg{position:fixed;inset:0;overflow:hidden}'
    return css, '<div class="bg" aria-hidden="true">' + blobs + '</div>' + nav(c) + hero_copy(c), '', 'Reduced motion: blobs stop.'


# 2. Particle network
@engine('particle-network', 'Particle Network', 'Background', 'canvas particles network lines mouse interactive',
        'Floating dots link up with glowing lines and drift away from your cursor, behind a {label} hero.',
        ['A full-screen <canvas> draws {n} particles moving at random slow velocities and wrapping at the edges.',
         'Any two particles closer than {dist}px are joined with a line whose opacity fades with distance.',
         'Particles within 120px of the pointer are pushed away gently; devicePixelRatio is respected for sharp lines.',
         'The loop uses requestAnimationFrame and pauses when the tab is hidden.'])
def e_particles(c):
    r = c['r']
    n = pick(r, [60, 80, 100])
    dist = pick(r, [110, 130, 150])
    c['vars'].update(n=n, dist=dist)
    css = 'body{min-height:100vh;overflow:hidden}canvas{position:fixed;inset:0;width:100%;height:100%}' + NAV_CSS + COPY_CSS + (center_hero_css() if c['v'] % 2 == 0 else left_hero_css())
    js = ('(function(){var cv=document.getElementById("cv"),x=cv.getContext("2d"),W,H,D=Math.min(devicePixelRatio||1,2),P=[],m={x:-999,y:-999};'
          'var N=Math.round(%d*Math.min(1,innerWidth/1100)+20),R=%d,c1=getComputedStyle(document.documentElement).getPropertyValue("--a1").trim(),c2=getComputedStyle(document.documentElement).getPropertyValue("--a2").trim();'
          'function rs(){W=innerWidth;H=innerHeight;cv.width=W*D;cv.height=H*D;x.setTransform(D,0,0,D,0,0)}rs();addEventListener("resize",rs);'
          'for(var i=0;i<N;i++)P.push({x:Math.random()*W,y:Math.random()*H,vx:(Math.random()-.5)*.6,vy:(Math.random()-.5)*.6});'
          'addEventListener("pointermove",function(e){m.x=e.clientX;m.y=e.clientY});'
          'var red=matchMedia("(prefers-reduced-motion: reduce)").matches;'
          'function f(){x.clearRect(0,0,W,H);for(var i=0;i<N;i++){var p=P[i];if(!red){p.x+=p.vx;p.y+=p.vy}var dx=p.x-m.x,dy=p.y-m.y,d=Math.hypot(dx,dy);if(d<120&&d>0){p.x+=dx/d*1.5;p.y+=dy/d*1.5}'
          'if(p.x<0)p.x+=W;if(p.x>W)p.x-=W;if(p.y<0)p.y+=H;if(p.y>H)p.y-=H;x.fillStyle=c2;x.beginPath();x.arc(p.x,p.y,2,0,7);x.fill();'
          'for(var j=i+1;j<N;j++){var q=P[j],e=Math.hypot(p.x-q.x,p.y-q.y);if(e<R){x.globalAlpha=1-e/R;x.strokeStyle=c1;x.beginPath();x.moveTo(p.x,p.y);x.lineTo(q.x,q.y);x.stroke();x.globalAlpha=1}}}'
          'if(!document.hidden)requestAnimationFrame(f)}f();document.addEventListener("visibilitychange",function(){if(!document.hidden)f()})})();' % (n, dist))
    return css, '<canvas id="cv" aria-hidden="true"></canvas>' + nav(c) + hero_copy(c), js, ''


# 3. Starfield warp
@engine('star-warp', 'Star Warp', 'Background', 'canvas stars warp speed space hero',
        'Stars stream out from the centre like a jump to light speed; hold the button to go faster, for a {label} brand.',
        ['A <canvas> projects {n} stars with a simple 3D perspective (x/z, y/z) and draws each as a short streak from its previous position.',
         'Speed eases between {base} and {boost} while the pointer is pressed.',
         'Stars that pass the camera respawn far away at random positions.'])
def e_warp(c):
    r = c['r']
    n = pick(r, [400, 600, 800])
    base, boost = pick(r, [(2, 18), (3, 24), (1.5, 14)])
    c['vars'].update(n=n, base=base, boost=boost)
    css = 'body{min-height:100vh;overflow:hidden;background:radial-gradient(circle at 50% 50%,var(--surface),var(--bg) 70%)}canvas{position:fixed;inset:0;width:100%;height:100%}' + NAV_CSS + COPY_CSS + center_hero_css()
    js = ('(function(){var cv=document.getElementById("cv"),x=cv.getContext("2d"),W,H,D=Math.min(devicePixelRatio||1,2),S=[],N=%d,v=%s,tv=%s,col=getComputedStyle(document.documentElement).getPropertyValue("--ink").trim();'
          'function rs(){W=innerWidth;H=innerHeight;cv.width=W*D;cv.height=H*D;x.setTransform(D,0,0,D,0,0)}rs();addEventListener("resize",rs);'
          'function mk(s,far){s.x=(Math.random()-.5)*W*2;s.y=(Math.random()-.5)*H*2;s.z=far?W:Math.random()*W;s.pz=s.z;return s}'
          'for(var i=0;i<N;i++)S.push(mk({},0));'
          'addEventListener("pointerdown",function(){tv=%s});addEventListener("pointerup",function(){tv=%s});'
          'if(matchMedia("(prefers-reduced-motion: reduce)").matches){v=tv=.2}'
          'function f(){v+=(tv-v)*.05;x.fillStyle="rgba(0,0,0,0)";x.clearRect(0,0,W,H);x.strokeStyle=col;x.lineCap="round";'
          'for(var i=0;i<N;i++){var s=S[i];s.pz=s.z;s.z-=v;if(s.z<1){mk(s,1);continue}var k=W/2,sx=s.x/s.z*k+W/2,sy=s.y/s.z*k+H/2,px=s.x/s.pz*k+W/2,py=s.y/s.pz*k+H/2;'
          'x.globalAlpha=Math.min(1,(W-s.z)/W+.15);x.lineWidth=Math.max(.6,2.4*(1-s.z/W));x.beginPath();x.moveTo(px,py);x.lineTo(sx,sy);x.stroke()}x.globalAlpha=1;'
          'if(!document.hidden)requestAnimationFrame(f)}f();document.addEventListener("visibilitychange",function(){if(!document.hidden)f()})})();'
          % (n, base, base, boost, base))
    return css, '<canvas id="cv" aria-hidden="true"></canvas>' + nav(c) + hero_copy(c), js, ''


# 4. Wave lines
@engine('wave-lines', 'Flowing Wave Lines', 'Background', 'canvas sine waves lines flowing hero',
        'Layered sine waves flow across the screen in your brand colours behind a {label} message.',
        ['A <canvas> draws {n} wave lines; each line is a sum of two sine waves with its own amplitude, wavelength and phase speed.',
         'Lines are stroked with a horizontal gradient from --a1 to --a2 at {alpha} opacity.',
         'The pointer\'s vertical position subtly raises the amplitude of nearby waves.'])
def e_waves(c):
    r = c['r']
    n = pick(r, [5, 7, 9])
    alpha = pick(r, ['.35', '.5', '.65'])
    c['vars'].update(n=n, alpha=alpha)
    pos = 'bottom:0;height:55vh' if c['v'] % 2 else 'inset:0;height:100%'
    css = 'body{min-height:100vh;overflow:hidden}canvas{position:fixed;left:0;width:100%%;%s}' % pos + NAV_CSS + COPY_CSS + center_hero_css()
    js = ('(function(){var cv=document.getElementById("cv"),x=cv.getContext("2d"),W,H,D=Math.min(devicePixelRatio||1,2),t=0,my=.5,N=%d,cs=getComputedStyle(document.documentElement);'
          'var a1=cs.getPropertyValue("--a1").trim(),a2=cs.getPropertyValue("--a2").trim(),red=matchMedia("(prefers-reduced-motion: reduce)").matches;'
          'function rs(){W=cv.clientWidth;H=cv.clientHeight;cv.width=W*D;cv.height=H*D;x.setTransform(D,0,0,D,0,0)}rs();addEventListener("resize",rs);'
          'addEventListener("pointermove",function(e){my=e.clientY/innerHeight});'
          'function f(){if(!red)t+=.012;x.clearRect(0,0,W,H);var g=x.createLinearGradient(0,0,W,0);g.addColorStop(0,a1);g.addColorStop(1,a2);x.strokeStyle=g;x.globalAlpha=%s;x.lineWidth=2;'
          'for(var i=0;i<N;i++){var A=H*(.06+.02*i)*(1+.6*(1-Math.abs(my-i/N))),L=W/(1.2+i*.25),ph=t*(1+i*.15)+i;x.beginPath();'
          'for(var X=0;X<=W;X+=8){var Y=H*(.35+i*.5/N)+Math.sin(X/L*6.283+ph)*A+Math.sin(X/(L*.37)+ph*1.7)*A*.25;X?x.lineTo(X,Y):x.moveTo(X,Y)}x.stroke()}'
          'if(!document.hidden)requestAnimationFrame(f)}f();document.addEventListener("visibilitychange",function(){if(!document.hidden)f()})})();' % (n, alpha))
    return css, '<canvas id="cv" aria-hidden="true"></canvas>' + nav(c) + hero_copy(c), js, ''


# 5. Typewriter words
@engine('typewriter-words', 'Typewriter Headline', 'Text Effect', 'typewriter typing words rotating headline',
        'The headline types, pauses and deletes a rotating word with a blinking caret, for a {label} brand.',
        ['A fixed headline start is followed by a <span> that types each word from the list [{words}] one letter every {speed}ms.',
         'After a {hold}ms pause it deletes letter by letter and moves to the next word, forever.',
         'A CSS caret blinks with steps(1); screen readers get the full list through aria-label.'])
def e_type(c):
    r = c['r']
    t = c['t']
    speed = pick(r, [70, 90, 110])
    hold = pick(r, [1200, 1600, 2000])
    c['vars'].update(words=', '.join(t['words']), speed=speed, hold=hold)
    first = t['brand'] + ' makes you'
    css = ('body{min-height:100vh;display:flex;flex-direction:column}main{flex:1;display:grid;place-items:center;text-align:center;padding:24px}'
           'h1 .w{color:var(--a1);border-right:.08em solid var(--a2);padding-right:.05em;animation:car 1s steps(1) infinite}@keyframes car{50%{border-color:transparent}}'
           + NAV_CSS + COPY_CSS + '.sub{margin:0 auto}.row{justify-content:center}')
    body = nav(c) + ('<main><div><p class="eyebrow">%s</p><h1 aria-label="%s %s">%s <span class="w" id="w"></span></h1><p class="sub">%s</p><div class="row"><a class="btn" href="#">%s</a><a class="btn ghost" href="#">%s</a></div></div></main>'
                     % (esc(t['label']), esc(first), esc(' / '.join(t['words'])), esc(first), esc(t['sub']), esc(t['cta']), esc(t['cta2'])))
    js = ('(function(){var W=%s,el=document.getElementById("w"),i=0,n=0,del=false;if(matchMedia("(prefers-reduced-motion: reduce)").matches){el.textContent=W[0];return}'
          'function s(){var w=W[i];n+=del?-1:1;el.textContent=w.slice(0,n);var d=del?40:%d;if(!del&&n===w.length){del=true;d=%d}else if(del&&n===0){del=false;i=(i+1)%%W.length;d=300}setTimeout(s,d)}s()})();'
          % (jsstr(t['words']), speed, hold))
    return css, body, js, ''


# 6. Split letters reveal (loops)
@engine('letter-rise', 'Letter Rise Reveal', 'Text Effect', 'split text letters stagger reveal headline',
        'Each letter of the {label} headline rises and fades in one after another, then the buttons slide up.',
        ['JavaScript wraps every letter of the headline in a <span> (spaces kept) and sets a CSS variable --i for its index.',
         'Letters animate from translateY({dy}%) rotate({rot}deg) and opacity 0 with a {stagger}ms stagger and a springy cubic-bezier.',
         'The whole reveal replays every 7 seconds in the preview; in production run it once.'])
def e_letters(c):
    r = c['r']
    dy = pick(r, [60, 100, 120])
    rot = pick(r, [0, 8, -10])
    stagger = pick(r, [25, 35, 45])
    c['vars'].update(dy=dy, rot=rot, stagger=stagger)
    css = ('body{min-height:100vh;display:flex;flex-direction:column}main{flex:1;display:grid;place-items:center;padding:24px}'
           'h1 .ch{display:inline-block;opacity:0;transform:translateY(%d%%) rotate(%ddeg)}.go h1 .ch{animation:up .8s cubic-bezier(.2,1.4,.4,1) forwards;animation-delay:calc(var(--i)*%dms)}'
           '@keyframes up{to{opacity:1;transform:none}}.sub,.row{opacity:0;transform:translateY(16px);transition:.7s .9s}.go .sub,.go .row{opacity:1;transform:none}'
           '@media(prefers-reduced-motion:reduce){h1 .ch,.sub,.row{opacity:1!important;transform:none!important;animation:none!important}}' % (dy, rot, stagger)
           + NAV_CSS + COPY_CSS + center_hero_css())
    js = ('(function(){var h=document.querySelector("h1"),t=h.textContent,i=0;h.setAttribute("aria-label",t);h.innerHTML=t.split(" ").map(function(w){return \'<span style="white-space:nowrap">\'+w.split("").map(function(ch){return \'<span class="ch" aria-hidden="true" style="--i:\'+(i++)+\'">\'+ch+"</span>"}).join("")+"</span>"}).join(" ");'
          'var m=document.querySelector(".copy");function go(){m.classList.remove("go");void m.offsetWidth;m.classList.add("go")}go();setInterval(go,7000)})();')
    return css, nav(c) + hero_copy(c), js, ''


# 7. Marquee rows
@engine('marquee-rows', 'Infinite Marquee', 'Interactive', 'marquee ticker infinite scroll logos words',
        'Two endless rows of {label} keywords glide in opposite directions and slow down on hover.',
        ['Each row duplicates its content once and animates translateX(0 → -50%) linearly in {dur}s, so the loop is seamless.',
         'The second row runs in reverse; hovering a row sets animation-play-state to paused.',
         'Edges fade out with a CSS mask-image linear-gradient.'])
def e_marquee(c):
    r = c['r']
    t = c['t']
    dur = pick(r, [22, 30, 38])
    c['vars'].update(dur=dur)
    sep = pick(r, ['✦', '●', '◆', '／'])

    def row(words):
        inner = ''.join('<span>%s</span><i>%s</i>' % (esc(w), sep) for w in words)
        return '<div class="track"><div class="belt">%s%s</div></div>' % (inner, inner)
    big = t['items'] + t['words']
    css = ('body{min-height:100vh;display:flex;flex-direction:column}main{flex:1;display:flex;flex-direction:column;justify-content:center;gap:22px;padding:40px 0}'
           '.lead{text-align:center;padding:0 20px;margin-bottom:10px}.lead h2{font-size:clamp(26px,4vw,48px);letter-spacing:var(--ls);font-weight:var(--hw);margin:0 0 8px}.lead p{color:var(--muted)}'
           '.track{overflow:hidden;-webkit-mask-image:linear-gradient(90deg,transparent,#000 12%%,#000 88%%,transparent);mask-image:linear-gradient(90deg,transparent,#000 12%%,#000 88%%,transparent)}'
           '.belt{display:flex;width:max-content;align-items:center;gap:28px;animation:mq %ds linear infinite}.track:hover .belt{animation-play-state:paused}'
           '.track.rev .belt{animation-direction:reverse}.belt span{font-size:clamp(28px,6vw,72px);font-weight:var(--hw);letter-spacing:var(--ls);white-space:nowrap}'
           '.track.rev .belt span{-webkit-text-stroke:1.5px var(--ink);color:transparent}.belt i{font-style:normal;color:var(--a1);font-size:clamp(18px,3vw,32px)}'
           '@keyframes mq{to{transform:translateX(-50%%)}}@media(prefers-reduced-motion:reduce){.belt{animation:none}}' % dur + NAV_CSS + BTN_CSS
           + '.cta{text-align:center;margin-top:18px}')
    body = nav(c) + ('<main><div class="lead"><h2>%s</h2><p>%s</p></div>%s%s<div class="cta"><a class="btn" href="#">%s</a></div></main>'
                     % (esc(t['headline']), esc(t['sub']), row(big[:6]), row(big[4:]).replace('class="track"', 'class="track rev"'), esc(t['cta'])))
    return css, body, '', ''


# 8. 3D tilt cards
@engine('tilt-cards', '3D Tilt Cards', 'Cards', '3d tilt perspective cards hover glare',
        'Feature cards for a {label} brand tilt in 3D toward the pointer with a moving glare highlight.',
        ['Each card listens to pointermove, maps the pointer to rotateX/rotateY of up to {deg}° inside perspective(900px), and moves a radial-gradient glare to the pointer.',
         'On pointerleave the card eases back to flat with a 400ms transition.',
         'Inner layers use translateZ for depth; on touch devices the cards gently auto-tilt.'])
def e_tilt(c):
    r = c['r']
    t = c['t']
    deg = pick(r, [8, 12, 16])
    c['vars'].update(deg=deg)
    cards = ''
    for i, it in enumerate(t['items'][:6]):
        cards += '<article class="card" tabindex="0"><span class="ic">%02d</span><h3>%s</h3><p>%s</p><i class="glare"></i></article>' % (i + 1, esc(it), esc(t['stats'][i % 3][1].capitalize() + ', built in.'))
    css = ('main{max-width:1100px;margin:0 auto;padding:40px 20px 60px}.head{text-align:center;margin-bottom:34px}.head h2{font-size:clamp(28px,4.4vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:10px 0}.head p{color:var(--muted)}'
           '.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,250px),1fr));gap:18px}'
           '.card{position:relative;padding:26px;border-radius:20px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line),0 20px 40px -24px #0009;transform-style:preserve-3d;transition:transform .4s cubic-bezier(.2,.8,.2,1);overflow:hidden;min-height:180px}'
           '.card.on{transition:transform .08s}.card .ic{display:inline-grid;place-items:center;width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,var(--a1),var(--a2));color:var(--on);font-weight:800;transform:translateZ(40px)}'
           '.card h3{margin:18px 0 6px;font-size:20px;transform:translateZ(30px)}.card p{color:var(--muted);margin:0;transform:translateZ(20px)}'
           '.glare{position:absolute;inset:0;pointer-events:none;background:radial-gradient(400px circle at var(--gx,50%) var(--gy,0%),color-mix(in srgb,var(--a2) 28%,transparent),transparent 45%);opacity:0;transition:opacity .3s}.card.on .glare,.card:focus .glare{opacity:1}'
           + NAV_CSS + BTN_CSS + COPY_CSS)
    body = nav(c) + '<main><div class="head"><p class="eyebrow">%s</p><h2>%s</h2><p>%s</p></div><div class="grid">%s</div></main>' % (esc(t['label']), esc(t['headline']), esc(t['sub']), cards)
    js = ('(function(){var D=%d,red=matchMedia("(prefers-reduced-motion: reduce)").matches;if(red)return;document.querySelectorAll(".card").forEach(function(c,i){'
          'function set(px,py){c.style.transform="perspective(900px) rotateX("+((.5-py)*D*2)+"deg) rotateY("+((px-.5)*D*2)+"deg)";c.style.setProperty("--gx",px*100+"%%");c.style.setProperty("--gy",py*100+"%%")}'
          'c.addEventListener("pointermove",function(e){var b=c.getBoundingClientRect();c.classList.add("on");set((e.clientX-b.left)/b.width,(e.clientY-b.top)/b.height)});'
          'c.addEventListener("pointerleave",function(){c.classList.remove("on");c.style.transform=""});'
          'if(!matchMedia("(hover:hover)").matches){var t0=i;setInterval(function(){t0+=.05;set(.5+Math.sin(t0)*.3,.5+Math.cos(t0)*.3)},60)}})})();' % deg)
    return css, body, js, ''


# 9. Flip cards
@engine('flip-cards', 'Flip Cards', 'Cards', 'flip cards 3d backface hover reveal',
        'Cards flip in 3D to reveal the details behind each {label} service.',
        ['Each card has a front and back face with backface-visibility:hidden inside a preserve-3d wrapper.',
         'Hover, focus or tap rotates the wrapper {axis}(180deg) over {dur}ms with a soft overshoot curve.',
         'In the preview one card flips automatically every 2 seconds so the motion is visible.'])
def e_flip(c):
    r = c['r']
    t = c['t']
    axis = pick(r, ['rotateY', 'rotateX'])
    dur = pick(r, [600, 800, 1000])
    c['vars'].update(axis=axis, dur=dur)
    cards = ''
    for i, it in enumerate(t['items'][:6]):
        s = t['stats'][i % 3]
        cards += ('<button class="fc" type="button" aria-label="%s"><span class="in"><span class="f"><b>%02d</b><strong>%s</strong><small>Tap to flip</small></span>'
                  '<span class="bk"><strong>%s</strong><em>%s</em><u>%s →</u></span></span></button>' % (esc(it), i + 1, esc(it), esc(s[0]), esc(s[1]), esc(t['cta'])))
    css = ('main{max-width:1080px;margin:0 auto;padding:40px 20px 60px}h2{text-align:center;font-size:clamp(28px,4.4vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:0 0 30px}'
           '.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%%,220px),1fr));gap:16px}'
           '.fc{all:unset;cursor:pointer;display:block;height:230px;perspective:1000px}.fc:focus-visible{outline:2px solid var(--a2);outline-offset:4px;border-radius:20px}'
           '.in{position:relative;display:block;width:100%%;height:100%%;transform-style:preserve-3d;transition:transform %dms cubic-bezier(.3,1.4,.5,1)}'
           '.fc:hover .in,.fc:focus .in,.fc.on .in{transform:%s(180deg)}'
           '.f,.bk{position:absolute;inset:0;border-radius:20px;padding:22px;display:flex;flex-direction:column;justify-content:space-between;backface-visibility:hidden;-webkit-backface-visibility:hidden}'
           '.f{background:var(--surface);box-shadow:inset 0 0 0 1px var(--line)}.f b{color:var(--a1);font-size:14px}.f strong{font-size:24px}.f small{color:var(--muted)}'
           '.bk{background:linear-gradient(140deg,var(--a1),var(--a2));color:var(--on);transform:%s(180deg)}.bk strong{font-size:44px;letter-spacing:-.03em}.bk em{font-style:normal;opacity:.9}.bk u{text-decoration:none;font-weight:700}'
           '@media(prefers-reduced-motion:reduce){.in{transition:none}}' % (dur, axis, axis) + NAV_CSS + BTN_CSS)
    body = nav(c) + '<main><h2>%s</h2><div class="grid">%s</div></main>' % (esc(t['headline']), cards)
    js = '(function(){var c=document.querySelectorAll(".fc"),i=0;c.forEach(function(b){b.addEventListener("click",function(){b.classList.toggle("on")})});setInterval(function(){c.forEach(function(b){b.classList.remove("on")});c[i%c.length].classList.add("on");i++},2000)})();'
    return css, body, js, ''


# 10. Scroll reveal story
@engine('scroll-reveal', 'Scroll Reveal Story', 'Scroll Story', 'scroll reveal intersection observer fade slide sections',
        'Sections of the {label} story fade, slide and scale into place as you scroll down.',
        ['Each block gets a data-reveal direction ({dirs}); an IntersectionObserver adds .in when 20% of it is visible.',
         'Blocks animate opacity 0→1 plus translate/scale over {dur}ms with a staggered delay for children.',
         'A thin progress bar at the top grows with document scroll.'])
def e_reveal(c):
    r = c['r']
    t = c['t']
    dur = pick(r, [700, 900, 1100])
    dirs = ['up', 'left', 'right', 'zoom']
    r.shuffle(dirs)
    c['vars'].update(dur=dur, dirs=', '.join(dirs))
    blocks = ''
    for i, it in enumerate(t['items'][:5]):
        blocks += ('<section class="blk %s" data-reveal="%s"><div class="num">%02d</div><div><h2>%s</h2><p>%s</p></div><div class="art" style="--h:%d"></div></section>'
                   % ('alt' if i % 2 else '', dirs[i % 4], i + 1, esc(it), esc(t['sub']), (i * 47) % 360))
    css = ('.bar{position:fixed;top:0;left:0;height:3px;width:100%%;transform-origin:0 50%%;transform:scaleX(0);background:linear-gradient(90deg,var(--a1),var(--a2));z-index:9}'
           '.intro{min-height:80vh;display:grid;place-items:center;text-align:center;padding:24px}.intro h1{max-width:16ch;margin:12px auto}'
           '.blk{display:grid;grid-template-columns:auto 1fr 1fr;gap:28px;align-items:center;max-width:1050px;margin:0 auto;padding:12vh 20px}.blk.alt .art{order:-1}'
           '.num{font-size:64px;font-weight:var(--hw);color:transparent;-webkit-text-stroke:1.5px var(--a1)}.blk h2{font-size:clamp(26px,3.6vw,44px);letter-spacing:var(--ls);margin:0 0 10px}.blk p{color:var(--muted);line-height:1.6}'
           '.art{aspect-ratio:4/3;border-radius:22px;background:radial-gradient(circle at 30%% 30%%,var(--a2),transparent 60%%),linear-gradient(135deg,var(--a1),var(--surface));box-shadow:0 30px 60px -30px var(--a1)}'
           '[data-reveal]{opacity:0;transition:opacity %dms ease,transform %dms cubic-bezier(.2,.8,.2,1)}[data-reveal=up]{transform:translateY(60px)}[data-reveal=left]{transform:translateX(-80px)}[data-reveal=right]{transform:translateX(80px)}[data-reveal=zoom]{transform:scale(.85)}'
           '[data-reveal].in{opacity:1;transform:none}.end{text-align:center;padding:16vh 20px}'
           '@media(max-width:760px){.blk{grid-template-columns:1fr}.blk.alt .art{order:0}[data-reveal=left],[data-reveal=right]{transform:translateY(50px)}}@media(prefers-reduced-motion:reduce){[data-reveal]{opacity:1;transform:none;transition:none}}' % (dur, dur)
           + NAV_CSS + BTN_CSS + COPY_CSS)
    body = ('<div class="bar" id="bar"></div>' + nav(c) + '<header class="intro"><div><p class="eyebrow">%s</p><h1>%s</h1><p class="sub" style="margin:0 auto">Scroll to explore ↓</p></div></header>%s'
            '<section class="end" data-reveal="up"><h2>%s</h2><a class="btn" href="#">%s</a></section>' % (esc(t['label']), esc(t['headline']), blocks, esc(t['quote']), esc(t['cta'])))
    js = ('(function(){var io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting)e.target.classList.add("in")})},{threshold:.2});document.querySelectorAll("[data-reveal]").forEach(function(el){io.observe(el)});'
          'var b=document.getElementById("bar");function s(){var m=document.documentElement.scrollHeight-innerHeight;b.style.transform="scaleX("+(m>0?scrollY/m:0)+")"}addEventListener("scroll",s,{passive:true});s()})();')
    return css, body, js, ''


# 11. Horizontal pinned scroll
@engine('horizontal-pin', 'Horizontal Scroll Gallery', 'Scroll Story', 'scroll pinned horizontal sticky gallery panels',
        'Scrolling down slides a row of {label} panels sideways inside a pinned section.',
        ['A tall section ({h}vh) contains a sticky 100vh stage; its track of {n} panels translates on X by scroll progress × (track width − viewport width).',
         'Each panel scales from .9 to 1 as it reaches the centre of the screen.',
         'On phones (under 700px) the same panels become a native swipe row with scroll-snap.'])
def e_hpin(c):
    r = c['r']
    t = c['t']
    n = 5 + c['v'] % 2
    h = 100 * n
    c['vars'].update(n=n, h=h)
    panels = ''
    for i in range(n):
        it = t['items'][i % 6]
        panels += '<article class="pn" style="--h:%d"><b>%02d</b><h3>%s</h3><p>%s</p></article>' % ((i * 53 + r.randint(0, 40)) % 360, i + 1, esc(it), esc(t['stats'][i % 3][0] + ' ' + t['stats'][i % 3][1]))
    css = ('.intro{min-height:70vh;display:grid;place-items:center;text-align:center;padding:24px}'
           '#hs{height:%dvh;position:relative}.stage{position:sticky;top:0;height:100vh;overflow:hidden;display:flex;align-items:center}'
           '.track{display:flex;gap:4vw;padding:0 8vw;will-change:transform}'
           '.pn{flex:0 0 min(70vw,520px);height:min(62vh,440px);border-radius:28px;padding:30px;display:flex;flex-direction:column;justify-content:flex-end;color:#fff;'
           'background:linear-gradient(160deg,hsl(var(--h) 80%% 60%%),hsl(calc(var(--h) + 60) 70%% 30%%));box-shadow:0 40px 80px -40px #000a;transition:transform .2s}'
           '.pn b{font-size:14px;opacity:.8}.pn h3{font-size:clamp(28px,4vw,52px);margin:6px 0;letter-spacing:-.03em}.pn p{margin:0;opacity:.9}'
           '.end{min-height:60vh;display:grid;place-items:center;text-align:center;padding:24px}'
           '@media(max-width:700px){#hs{height:auto}.stage{position:static;height:auto;padding:30px 0}.track{overflow-x:auto;scroll-snap-type:x mandatory;transform:none!important;padding:0 20px}.pn{scroll-snap-align:center;flex-basis:80vw;height:360px}}' % h
           + NAV_CSS + BTN_CSS + COPY_CSS)
    body = (nav(c) + '<header class="intro"><div><p class="eyebrow">%s</p><h1>%s</h1><p class="sub" style="margin:0 auto">%s</p></div></header>'
            '<section id="hs"><div class="stage"><div class="track" id="tr">%s</div></div></section><section class="end"><div><h2>%s</h2><a class="btn" href="#">%s</a></div></section>'
            % (esc(t['label']), esc(t['headline']), esc(t['sub']), panels, esc(t['quote']), esc(t['cta'])))
    js = ('(function(){var s=document.getElementById("hs"),tr=document.getElementById("tr"),P=tr.children;function f(){if(innerWidth<=700)return;var b=s.getBoundingClientRect(),m=s.offsetHeight-innerHeight,p=Math.min(1,Math.max(0,-b.top/m)),dx=tr.scrollWidth-innerWidth;'
          'tr.style.transform="translateX("+(-p*dx)+"px)";for(var i=0;i<P.length;i++){var r=P[i].getBoundingClientRect(),c=1-Math.min(1,Math.abs(r.left+r.width/2-innerWidth/2)/innerWidth);P[i].style.transform="scale("+(.9+.1*c)+")"}}'
          'addEventListener("scroll",f,{passive:true});addEventListener("resize",f);f()})();')
    return css, body, js, ''


# 12. Parallax layers
@engine('parallax-layers', 'Parallax Depth Layers', 'Scroll Story', 'parallax scroll layers depth shapes landscape',
        'Layered shapes of a stylised landscape move at different speeds as you scroll the {label} page.',
        ['{n} absolutely positioned SVG/CSS layers each carry a data-speed between 0.1 and 0.9.',
         'On scroll, each layer translates on Y by scrollY × speed using requestAnimationFrame batching.',
         'The headline fades and lifts away while the foreground hills cover it.'])
def e_parallax(c):
    r = c['r']
    t = c['t']
    n = 5
    c['vars'].update(n=n)
    shape = pick(r, ['hills', 'peaks'])
    layers = ''
    for i in range(n):
        sp = round(.15 + i * .18, 2)
        y = 40 + i * 9
        if shape == 'hills':
            d = 'M0 %d Q 25 %d 50 %d T 100 %d V 100 H 0 Z' % (y, y - 12 - i * 2, y, y - 4)
        else:
            d = 'M0 %d L 18 %d L 34 %d L 52 %d L 70 %d L 86 %d L 100 %d V 100 H 0 Z' % (y, y - 16, y - 2, y - 20 + i, y - 4, y - 14, y)
        layers += '<svg class="ly" data-speed="%s" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true"><path d="%s" fill="color-mix(in srgb,var(--a1) %d%%,var(--bg))"/></svg>' % (sp, d, 85 - i * 15)
    css = ('.scene{position:relative;height:120vh;overflow:hidden;background:linear-gradient(180deg,var(--surface),color-mix(in srgb,var(--a2) 30%,var(--bg)))}'
           '.sun{position:absolute;left:50%;top:36%;width:22vmin;height:22vmin;margin-left:-11vmin;border-radius:50%;background:radial-gradient(circle,var(--a3),color-mix(in srgb,var(--a3) 20%,transparent) 70%)}'
           '.ly{position:absolute;left:0;bottom:-10vh;width:100%;height:100vh;will-change:transform}.ttl{position:absolute;inset:0;display:grid;place-items:start center;text-align:center;padding:16vh 24px 24px;z-index:1}'
           '.ttl h1{max-width:14ch;margin:10px auto}.after{position:relative;z-index:6;background:color-mix(in srgb,var(--a1) 15%,var(--bg));padding:12vh 20px;text-align:center}.after p{color:var(--muted);max-width:56ch;margin:10px auto 24px}'
           '.nav{position:absolute;left:0;right:0;top:0}' + NAV_CSS + BTN_CSS + COPY_CSS)
    body = ('<section class="scene">' + nav(c) + '<div class="sun" data-speed="0.05"></div><div class="ttl" data-speed="-0.3"><div><p class="eyebrow">%s</p><h1>%s</h1></div></div>%s</section>'
            '<section class="after"><h2>%s</h2><p>%s</p><a class="btn" href="#">%s</a></section>' % (esc(t['label']), esc(t['headline']), layers, esc(t['brand']), esc(t['sub']), esc(t['cta'])))
    # Title must sit under foreground layers: give layers higher z with index.
    css += ''.join('.ly:nth-of-type(%d){z-index:%d}' % (i + 1, 2 + i) for i in range(n))
    js = ('(function(){if(matchMedia("(prefers-reduced-motion: reduce)").matches)return;var L=document.querySelectorAll("[data-speed]"),tk=0;function f(){tk=0;var y=scrollY;L.forEach(function(l){l.style.transform="translateY("+(y*parseFloat(l.dataset.speed))+"px)"})}'
          'addEventListener("scroll",function(){if(!tk){tk=1;requestAnimationFrame(f)}},{passive:true});f()})();')
    return css, body, js, ''


# 13. Counting stats
@engine('counter-stats', 'Counting Stats Band', 'Stats', 'numbers counter count up stats band',
        'Big {label} numbers count up from zero with an easing curve when the band scrolls into view.',
        ['Each stat stores its target in data-to (prefix/suffix kept), and an IntersectionObserver starts the count once.',
         'Values ease with easeOutExpo over {dur}ms using requestAnimationFrame; decimals and separators are preserved.',
         'Below, a thin bar fills to match each stat for extra motion; the preview replays every 6 seconds.'])
def e_counter(c):
    r = c['r']
    t = c['t']
    dur = pick(r, [1400, 1800, 2200])
    c['vars'].update(dur=dur)
    stats = ''
    for i, (val, lab) in enumerate(t['stats']):
        m = re.match(r'^(\D*)([\d,.]+)(.*)$', val)
        if m:
            pre, num, suf = m.group(1), m.group(2), m.group(3)
            stats += '<div class="st"><b data-pre="%s" data-to="%s" data-suf="%s">%s</b><span>%s</span><i style="--w:%d%%"></i></div>' % (esc(pre), num.replace(',', ''), esc(suf), esc(val), esc(lab), 55 + i * 15)
        else:
            stats += '<div class="st"><b>%s</b><span>%s</span><i style="--w:%d%%"></i></div>' % (esc(val), esc(lab), 55 + i * 15)
    css = ('main{min-height:100vh;display:grid;place-items:center;padding:30px 20px}.wrap{max-width:1050px;width:100%%;text-align:center}.wrap h2{font-size:clamp(26px,4vw,48px);letter-spacing:var(--ls);font-weight:var(--hw);margin:12px auto 34px;max-width:20ch}'
           '.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%%,220px),1fr));gap:18px}.st{padding:28px 22px;border-radius:22px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);text-align:left;position:relative;overflow:hidden}'
           '.st b{display:block;font-size:clamp(40px,6vw,64px);letter-spacing:-.04em;background:linear-gradient(90deg,var(--a1),var(--a2));-webkit-background-clip:text;background-clip:text;color:transparent;font-variant-numeric:tabular-nums}'
           '.st span{color:var(--muted)}.st i{position:absolute;left:0;bottom:0;height:4px;width:var(--w);background:var(--a1);transform-origin:0;transform:scaleX(0);transition:transform %dms cubic-bezier(.2,.8,.2,1)}.go .st i{transform:scaleX(1)}' % dur
           + NAV_CSS + BTN_CSS + COPY_CSS + '.wrap .btn{margin-top:30px}')
    body = nav(c) + '<main><div class="wrap" id="wr"><p class="eyebrow">%s</p><h2>%s</h2><div class="grid">%s</div><a class="btn" href="#">%s</a></div></main>' % (esc(t['brand']), esc(t['headline']), stats, esc(t['cta']))
    js = ('(function(){var D=%d,w=document.getElementById("wr"),B=w.querySelectorAll("b[data-to]");function fmt(v,dec){return dec?v.toFixed(dec):Math.round(v).toLocaleString("en-IN")}'
          'function run(){w.classList.remove("go");void w.offsetWidth;w.classList.add("go");var t0=performance.now();B.forEach(function(b){b._to=parseFloat(b.dataset.to);b._dec=(b.dataset.to.split(".")[1]||"").length});'
          '(function f(n){var p=Math.min(1,(n-t0)/D),e=p===1?1:1-Math.pow(2,-10*p);B.forEach(function(b){b.textContent=b.dataset.pre+fmt(b._to*e,b._dec)+b.dataset.suf});if(p<1)requestAnimationFrame(f)})(t0)}'
          'if(matchMedia("(prefers-reduced-motion: reduce)").matches){w.classList.add("go");return}'
          'new IntersectionObserver(function(es){if(es[0].isIntersecting){run();setInterval(run,6000);this.disconnect()}}).observe(w)})();' % dur)
    return css, body, js, ''


# 14. Pricing toggle
@engine('pricing-toggle', 'Pricing Toggle', 'Pricing', 'pricing table toggle monthly yearly animated',
        'A {label} pricing table with a sliding monthly/yearly switch; prices roll to the new value.',
        ['A two-option switch moves a pill with transform; the selected period is stored on the wrapper as data-period.',
         'Each price rolls: the old number slides up and fades while the new one slides in from below ({dur}ms).',
         'The featured plan floats with a soft animated gradient border made with a rotating conic-gradient.'])
def e_pricing(c):
    r = c['r']
    t = c['t']
    dur = pick(r, [300, 400, 500])
    c['vars'].update(dur=dur)
    cur = '₹' if t['key'] in ('fintech', 'edtech', 'restaurant', 'coffee', 'bakery', 'yoga', 'clinic') else '$'
    base = [9, 29, 79] if cur == '$' else [199, 599, 1499]
    names = ['Starter', 'Pro', 'Team']
    plans = ''
    for i, nm in enumerate(names):
        m = base[i]
        y = int(round(m * 10))
        feats = ''.join('<li>%s</li>' % esc(x) for x in t['items'][:3 + i])
        plans += ('<article class="pl%s"><h3>%s</h3><p class="pr"><span class="cur">%s</span><span class="val" data-m="%d" data-y="%d">%d</span><small data-per>/month</small></p><ul>%s</ul><a class="btn%s" href="#">%s</a></article>'
                  % (' hot' if i == 1 else '', nm, cur, m, y, m, feats, '' if i == 1 else ' ghost', esc(t['cta'])))
    css = ('main{max-width:1060px;margin:0 auto;padding:40px 20px 60px;text-align:center}main h2{font-size:clamp(28px,4.4vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:10px 0}main>p{color:var(--muted)}'
           '.sw{position:relative;display:inline-flex;margin:22px 0 34px;padding:4px;border-radius:99px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line)}'
           '.sw button{all:unset;cursor:pointer;position:relative;z-index:1;padding:10px 20px;font-weight:700;font-size:14px;width:110px;text-align:center}.sw button:focus-visible{outline:2px solid var(--a2);border-radius:99px}'
           '.pill{position:absolute;top:4px;left:4px;width:110px;height:calc(100%% - 8px);border-radius:99px;background:var(--a1);transition:transform .35s cubic-bezier(.3,1.3,.5,1)}[data-period=y] .pill{transform:translateX(110px)}'
           '.sw button[aria-pressed=true]{color:var(--on)}.save{display:inline-block;margin-left:6px;font-size:11px;padding:2px 6px;border-radius:6px;background:var(--a3);color:#111}'
           '.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%%,260px),1fr));gap:18px;align-items:stretch}'
           '.pl{position:relative;padding:28px;border-radius:22px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);text-align:left;display:flex;flex-direction:column}'
           '.pl.hot{isolation:isolate;transform:translateY(-8px)}.pl.hot::before{content:"";position:absolute;inset:-2px;border-radius:24px;z-index:-1;background:conic-gradient(from var(--a,0deg),var(--a1),var(--a2),var(--a3),var(--a1));animation:spin 4s linear infinite}'
           '.pl.hot::after{content:"";position:absolute;inset:0;border-radius:22px;z-index:-1;background:var(--surface)}@property --a{syntax:"<angle>";inherits:false;initial-value:0deg}@keyframes spin{to{--a:360deg}}'
           '.pr{display:flex;align-items:baseline;gap:2px;margin:10px 0 16px;font-size:48px;font-weight:800;letter-spacing:-.03em;overflow:hidden;height:62px}.pr small{font-size:14px;color:var(--muted);font-weight:500}'
           '.val{display:inline-block;transition:transform %dms,opacity %dms}.val.out{transform:translateY(-100%%);opacity:0}.val.in{transform:translateY(100%%);opacity:0;transition:none}'
           '.pl ul{list-style:none;padding:0;margin:0 0 22px;color:var(--muted);flex:1}.pl li{padding:6px 0 6px 24px;position:relative}.pl li::before{content:"✓";position:absolute;left:0;color:var(--a1);font-weight:800}.pl .btn{justify-content:center}'
           '@media(prefers-reduced-motion:reduce){.pl.hot::before{animation:none}}' % (dur, dur) + NAV_CSS + BTN_CSS + COPY_CSS)
    body = nav(c) + ('<main id="pm" data-period="m"><p class="eyebrow">%s pricing</p><h2>Simple plans for every stage</h2><p>%s</p>'
                     '<div class="sw" role="group" aria-label="Billing period"><i class="pill"></i><button type="button" aria-pressed="true" data-p="m">Monthly</button><button type="button" aria-pressed="false" data-p="y">Yearly<span class="save">−17%%</span></button></div>'
                     '<div class="grid">%s</div></main>' % (esc(t['brand']), esc(t['sub']), plans))
    js = ('(function(){var m=document.getElementById("pm"),D=%d;function set(p){if(m.dataset.period===p)return;m.dataset.period=p;m.querySelectorAll(".sw button").forEach(function(b){b.setAttribute("aria-pressed",b.dataset.p===p)});'
          'm.querySelectorAll(".val").forEach(function(v){v.classList.add("out");setTimeout(function(){v.textContent=Number(v.dataset[p]).toLocaleString("en-IN");v.classList.remove("out");v.classList.add("in");void v.offsetWidth;v.classList.remove("in")},D)});'
          'm.querySelectorAll("[data-per]").forEach(function(s){s.textContent=p==="y"?"/year":"/month"})}'
          'm.querySelectorAll(".sw button").forEach(function(b){b.addEventListener("click",function(){set(b.dataset.p)})});var k=0;var iv=setInterval(function(){set(++k%%2?"y":"m")},3500);m.addEventListener("pointerdown",function(){clearInterval(iv)},{once:true})})();' % dur)
    return css, body, js, 'Prices are examples; the yearly price is 10 × monthly (two months free).'


# 15. FAQ accordion
@engine('faq-accordion', 'Smooth FAQ Accordion', 'FAQ', 'faq accordion smooth height details summary',
        'A {label} FAQ where answers open with a smooth height animation and a rotating plus icon.',
        ['Built on native <details>/<summary> for accessibility; JavaScript animates height from 0 to scrollHeight over {dur}ms with the Web Animations API.',
         'Only one item stays open (accordion mode); the plus icon rotates 45° into a cross.',
         'The first answer opens by itself in the preview.'])
def e_faq(c):
    r = c['r']
    t = c['t']
    dur = pick(r, [250, 350, 450])
    c['vars'].update(dur=dur)
    qa = [('What is %s?' % t['brand'], t['sub']),
          ('How do I get started?', 'Click “%s”, create your account in under a minute and follow the short guided tour.' % t['cta']),
          ('Is there a free option?', 'Yes. You can try the core features free, and upgrade only when you need more.'),
          ('Which %s options do you offer?' % t['items'][0].lower(), 'We offer %s, %s and %s, with more added every month.' % (t['items'][0], t['items'][1], t['items'][2])),
          ('Can I cancel any time?', 'Yes. There are no lock-ins; you can cancel from your account settings in two clicks.'),
          ('How do I contact support?', 'Write to us from the help centre. A real person replies within one working day.')]
    items = ''.join('<details%s><summary>%s<i></i></summary><div class="a"><p>%s</p></div></details>' % (' open' if i == 0 else '', esc(q), esc(a)) for i, (q, a) in enumerate(qa))
    css = ('main{max-width:760px;margin:0 auto;padding:40px 20px 60px}main h2{font-size:clamp(28px,4.2vw,48px);letter-spacing:var(--ls);font-weight:var(--hw);margin:10px 0 26px}'
           'details{border-bottom:1px solid var(--line)}summary{list-style:none;cursor:pointer;display:flex;justify-content:space-between;align-items:center;gap:16px;padding:20px 4px;font-weight:700;font-size:clamp(16px,2vw,19px)}'
           'summary::-webkit-details-marker{display:none}summary:focus-visible{outline:2px solid var(--a2);border-radius:8px}'
           'summary i{flex:0 0 28px;height:28px;border-radius:50%;position:relative;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);transition:transform .3s,background .3s}'
           'summary i::before,summary i::after{content:"";position:absolute;left:50%;top:50%;width:12px;height:2px;margin:-1px 0 0 -6px;background:var(--ink)}summary i::after{transform:rotate(90deg)}'
           'details[open] summary i{transform:rotate(45deg);background:var(--a1)}details[open] summary i::before,details[open] summary i::after{background:var(--on)}'
           '.a{overflow:hidden}.a p{margin:0 4px 20px;color:var(--muted);line-height:1.6}' + NAV_CSS + BTN_CSS + COPY_CSS)
    body = nav(c) + '<main><p class="eyebrow">FAQ</p><h2>Questions about %s</h2>%s</main>' % (esc(t['brand']), items)
    js = ('(function(){var D=%d,red=matchMedia("(prefers-reduced-motion: reduce)").matches,all=document.querySelectorAll("details");all.forEach(function(d){var s=d.querySelector("summary"),a=d.querySelector(".a");'
          's.addEventListener("click",function(e){e.preventDefault();if(d.open){close(d)}else{all.forEach(function(o){if(o!==d&&o.open)close(o)});d.open=true;if(!red)a.animate([{height:"0px"},{height:a.scrollHeight+"px"}],{duration:D,easing:"ease-out"})}})});'
          'function close(d){var a=d.querySelector(".a");if(red){d.open=false;return}var an=a.animate([{height:a.scrollHeight+"px"},{height:"0px"}],{duration:D,easing:"ease-in"});an.onfinish=function(){d.open=false}}})();' % dur)
    return css, body, js, ''


# 16. Magnetic buttons + ripple
@engine('magnetic-cta', 'Magnetic CTA', 'CTA', 'magnetic button ripple cursor cta call to action',
        'A {label} call-to-action whose buttons are pulled toward the cursor and ripple on click.',
        ['Each .mag button moves up to {pull}px toward the pointer when it is within 90px, using transform with a spring-back transition.',
         'Clicking spawns a circular ripple <span> at the click point that scales from 0 to 4 and fades over 600ms.',
         'The section background has a slow animated gradient sweep.'])
def e_magnetic(c):
    r = c['r']
    t = c['t']
    pull = pick(r, [10, 16, 22])
    c['vars'].update(pull=pull)
    css = ('body{min-height:100vh;display:flex;flex-direction:column}main{flex:1;display:grid;place-items:center;padding:24px}'
           '.box{position:relative;max-width:900px;width:100%;text-align:center;padding:clamp(40px,8vw,90px) 24px;border-radius:32px;overflow:hidden;'
           'background:linear-gradient(120deg,color-mix(in srgb,var(--a1) 30%,var(--surface)),var(--surface),color-mix(in srgb,var(--a2) 30%,var(--surface)));background-size:200% 200%;animation:sweep 8s ease-in-out infinite alternate;box-shadow:inset 0 0 0 1px var(--line)}'
           '@keyframes sweep{to{background-position:100% 100%}}.box h2{font-size:clamp(30px,5vw,62px);letter-spacing:var(--ls);font-weight:var(--hw);margin:10px auto 12px;max-width:16ch}.box p{color:var(--muted);max-width:52ch;margin:0 auto}'
           '.mag{position:relative;overflow:hidden;transition:transform .35s cubic-bezier(.3,1.6,.5,1)}.rp{position:absolute;width:40px;height:40px;margin:-20px 0 0 -20px;border-radius:50%;background:currentColor;opacity:.35;transform:scale(0);animation:rp .6s ease-out forwards;pointer-events:none}'
           '@keyframes rp{to{transform:scale(6);opacity:0}}.row{justify-content:center}@media(prefers-reduced-motion:reduce){.box{animation:none}}' + NAV_CSS + BTN_CSS + COPY_CSS)
    body = nav(c) + '<main><div class="box"><p class="eyebrow">%s</p><h2>%s</h2><p>%s</p><div class="row"><a class="btn mag" href="#">%s →</a><a class="btn ghost mag" href="#">%s</a></div></div></main>' % (esc(t['brand']), esc(t['headline']), esc(t['sub']), esc(t['cta']), esc(t['cta2']))
    js = ('(function(){var P=%d,red=matchMedia("(prefers-reduced-motion: reduce)").matches;document.querySelectorAll(".mag").forEach(function(b){'
          'b.addEventListener("click",function(e){e.preventDefault();var r=b.getBoundingClientRect(),s=document.createElement("span");s.className="rp";s.style.left=(e.clientX-r.left)+"px";s.style.top=(e.clientY-r.top)+"px";b.appendChild(s);setTimeout(function(){s.remove()},650)})});'
          'if(red)return;addEventListener("pointermove",function(e){document.querySelectorAll(".mag").forEach(function(b){var r=b.getBoundingClientRect(),cx=r.left+r.width/2,cy=r.top+r.height/2,dx=e.clientX-cx,dy=e.clientY-cy,d=Math.hypot(dx,dy),R=Math.max(r.width,r.height)/2+90;'
          'b.style.transform=d<R?"translate("+(dx/R*P)+"px,"+(dy/R*P)+"px)":""})});'
          'var t=0,b0=document.querySelector(".mag");if(!matchMedia("(hover:hover)").matches)setInterval(function(){t+=.1;b0.style.transform="translate("+Math.sin(t)*P*.6+"px,"+Math.cos(t*1.3)*P*.4+"px)"},50)})();' % pull)
    return css, body, js, ''


# 17. Cursor spotlight grid
@engine('spotlight-grid', 'Cursor Spotlight Grid', 'Interactive', 'spotlight cursor grid reveal radial gradient',
        'A dark grid lights up around your cursor like a torch, revealing {label} feature tiles.',
        ['A full-screen grid of tiles is covered by a radial-gradient mask centred on CSS variables --x/--y, updated on pointermove.',
         'Tile borders brighten inside a {radius}px radius; outside it they stay faint.',
         'Without a pointer, the spotlight wanders on a Lissajous path so the effect is always visible.'])
def e_spot(c):
    r = c['r']
    t = c['t']
    radius = pick(r, [220, 280, 340])
    c['vars'].update(radius=radius)
    tiles = ''.join('<div class="tl"><b>%s</b><span>%s</span></div>' % (esc(it), esc(t['stats'][i % 3][0])) for i, it in enumerate((t['items'] * 2)[:12]))
    css = ('body{min-height:100vh}main{position:relative;max-width:1100px;margin:0 auto;padding:30px 20px 60px}.head{text-align:center;margin-bottom:26px}.head h2{font-size:clamp(28px,4.4vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:10px 0}'
           '.grid{position:relative;display:grid;grid-template-columns:repeat(auto-fill,minmax(min(100%%,200px),1fr));gap:10px}'
           '.tl{position:relative;padding:26px 20px;border-radius:16px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);display:flex;flex-direction:column;gap:6px;min-height:120px}'
           '.tl span{color:var(--muted);font-size:13px}.grid::after{content:"";position:absolute;inset:-2px;border-radius:18px;pointer-events:none;'
           'background:radial-gradient(%dpx circle at var(--x,50%%) var(--y,50%%),color-mix(in srgb,var(--a1) 40%%,transparent),transparent 70%%);mix-blend-mode:%s}'
           '.grid .tl{transition:box-shadow .2s}.lit{box-shadow:inset 0 0 0 1px var(--a2),0 0 30px -10px var(--a2)!important}' % (radius, 'screen' if c['p']['dark'] else 'multiply')
           + NAV_CSS + BTN_CSS + COPY_CSS)
    body = nav(c) + '<main><div class="head"><p class="eyebrow">%s</p><h2>%s</h2></div><div class="grid" id="g">%s</div></main>' % (esc(t['label']), esc(t['headline']), tiles)
    js = ('(function(){var g=document.getElementById("g"),T=g.querySelectorAll(".tl"),R=%d,live=0,t=0;function at(x,y){var b=g.getBoundingClientRect();g.style.setProperty("--x",(x-b.left)+"px");g.style.setProperty("--y",(y-b.top)+"px");'
          'T.forEach(function(el){var r=el.getBoundingClientRect();el.classList.toggle("lit",Math.hypot(r.left+r.width/2-x,r.top+r.height/2-y)<R*.6)})}'
          'addEventListener("pointermove",function(e){live=1;at(e.clientX,e.clientY)});'
          'if(!matchMedia("(prefers-reduced-motion: reduce)").matches)(function w(){if(!live){t+=.012;var b=g.getBoundingClientRect();at(b.left+b.width*(.5+.42*Math.sin(t*1.3)),b.top+b.height*(.5+.4*Math.sin(t*2)))}requestAnimationFrame(w)})()})();' % radius)
    return css, body, js, ''


# 18. Bento with animated borders
@engine('bento-borders', 'Bento Grid Glow', 'Cards', 'bento grid conic border glow features',
        'An asymmetric {label} bento grid whose tiles have slowly rotating gradient borders and pop on hover.',
        ['A CSS grid with spans (2×2, 2×1, 1×1) builds the bento layout; it collapses to one column on phones.',
         'Each tile has a ::before layer with a conic-gradient that rotates using a registered @property angle (speed {dur}s).',
         'Hover lifts the tile 4px and brightens its inner gradient.'])
def e_bento(c):
    r = c['r']
    t = c['t']
    dur = pick(r, [5, 7, 9])
    c['vars'].update(dur=dur)
    spans = ['big', 'wide', '', '', 'wide', '']
    tiles = ''
    for i, it in enumerate(t['items'][:6]):
        extra = '<strong>%s</strong>' % esc(t['stats'][0][0]) if i == 0 else ''
        tiles += '<article class="bt %s" style="--d:-%ds"><h3>%s</h3><p>%s</p>%s</article>' % (spans[i], i, esc(it), esc(t['sub'] if i == 0 else t['stats'][i % 3][1].capitalize()), extra)
    css = ('main{max-width:1100px;margin:0 auto;padding:36px 20px 60px}h2{font-size:clamp(28px,4.4vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:0 0 24px;max-width:18ch}'
           '.grid{display:grid;grid-template-columns:repeat(4,1fr);grid-auto-rows:170px;gap:14px}.big{grid-column:span 2;grid-row:span 2}.wide{grid-column:span 2}'
           '@property --ang{syntax:"<angle>";inherits:false;initial-value:0deg}'
           '.bt{position:relative;isolation:isolate;padding:24px;border-radius:22px;display:flex;flex-direction:column;justify-content:flex-end;transition:transform .3s}.bt:hover{transform:translateY(-4px)}'
           '.bt::before{content:"";position:absolute;inset:0;border-radius:22px;padding:1.5px;background:conic-gradient(from var(--ang),transparent 0 60%%,var(--a1),var(--a2),transparent);'
           '-webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);-webkit-mask-composite:xor;mask-composite:exclude;animation:rot %ds linear infinite;animation-delay:var(--d)}'
           '.bt::after{content:"";position:absolute;inset:0;border-radius:22px;z-index:-1;background:radial-gradient(circle at 80%% 0%%,color-mix(in srgb,var(--a1) 22%%,transparent),transparent 60%%),var(--surface);box-shadow:inset 0 0 0 1px var(--line);transition:filter .3s}'
           '.bt:hover::after{filter:brightness(1.15)}@keyframes rot{to{--ang:360deg}}.bt h3{margin:0 0 6px;font-size:20px}.bt p{margin:0;color:var(--muted)}.big strong{font-size:clamp(48px,7vw,88px);letter-spacing:-.05em;order:-1;margin-bottom:auto;color:var(--a1)}'
           '@media(max-width:760px){.grid{grid-template-columns:1fr;grid-auto-rows:auto}.big,.wide{grid-column:auto;grid-row:auto}.bt{min-height:150px}}@media(prefers-reduced-motion:reduce){.bt::before{animation:none}}' % dur
           + NAV_CSS + BTN_CSS + COPY_CSS)
    body = nav(c) + '<main><h2>%s</h2><div class="grid">%s</div></main>' % (esc(t['headline']), tiles)
    return css, body, '', ''


# 19. Orbit rings
@engine('orbit-rings', 'Orbit Rings', 'Hero', 'orbit rings rotate icons circle hero',
        '{label} feature badges orbit a glowing core on tilted rings, behind the headline.',
        ['{rings} concentric rings rotate at different speeds (CSS rotate keyframes, {dur}s base); each ring holds labelled badges.',
         'Badges counter-rotate so their text stays upright while they travel around the ring.',
         'The whole system tilts slightly with the pointer (rotateX/rotateY up to 10°).'])
def e_orbit(c):
    r = c['r']
    t = c['t']
    rings = 2 + c['v'] % 2
    dur = pick(r, [24, 32, 40])
    c['vars'].update(rings=rings, dur=dur)
    html = ''
    labels = t['items'] + t['words']
    k = 0
    for i in range(rings):
        size = 44 + i * 22
        cnt = 3 + i
        badges = ''
        for j in range(cnt):
            badges += '<span class="bd" style="--a:%ddeg"><b>%s</b></span>' % (int(360 / cnt * j), esc(labels[k % len(labels)]))
            k += 1
        html += '<div class="ring" style="--s:%dvmin;--t:%ds;--dir:%s">%s</div>' % (size, dur + i * 10, 'normal' if i % 2 == 0 else 'reverse', badges)
    css = ('body{min-height:100vh;overflow:hidden}.orb{position:absolute;inset:0;overflow:hidden;perspective:1000px}.sys{position:absolute;left:50%;top:56%;width:0;height:0;transform-style:preserve-3d;transition:transform .3s}'
           '.core{position:absolute;width:16vmin;height:16vmin;margin:-8vmin;border-radius:50%;background:radial-gradient(circle at 35% 35%,var(--a2),var(--a1) 60%,transparent 72%);filter:drop-shadow(0 0 40px var(--a1));animation:pulse 4s ease-in-out infinite}'
           '@keyframes pulse{50%{transform:scale(1.08)}}.ring{position:absolute;width:var(--s);height:var(--s);margin:calc(var(--s)/-2);border-radius:50%;border:1px dashed var(--line);animation:spin var(--t) linear infinite;animation-direction:var(--dir)}'
           '.bd{position:absolute;left:50%;top:50%;transform:rotate(var(--a)) translate(calc(var(--s)/2)) rotate(calc(-1*var(--a)))}'
           '.bd b{display:block;transform:translate(-50%,-50%);padding:6px 12px;border-radius:99px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);font-size:13px;white-space:nowrap;animation:spin var(--t) linear infinite;animation-direction:var(--dir);animation-name:unspin}'
           '@keyframes spin{to{transform:rotate(360deg)}}@keyframes unspin{from{transform:translate(-50%,-50%) rotate(0)}to{transform:translate(-50%,-50%) rotate(-360deg)}}'
           '.copy{padding-top:5vh!important}.copy h1{font-size:clamp(30px,5vw,60px)}@media(prefers-reduced-motion:reduce){.ring,.bd b,.core{animation:none}}'
           + NAV_CSS + COPY_CSS + center_hero_css() + '.copy{max-width:760px}.copy .sub,.copy .row{display:none}')
    body = nav(c) + hero_copy(c) + '<div class="orb" aria-hidden="true"><div class="sys" id="sys"><div class="core"></div>%s</div></div>' % html
    js = ('(function(){var s=document.getElementById("sys");if(matchMedia("(prefers-reduced-motion: reduce)").matches)return;addEventListener("pointermove",function(e){var x=e.clientX/innerWidth-.5,y=e.clientY/innerHeight-.5;s.style.transform="rotateX("+(-y*20)+"deg) rotateY("+(x*20)+"deg)"})})();')
    return css, body, js, ''


# 20. Morphing blob
@engine('morph-blob', 'Morphing Blob', 'Hero', 'blob morph border-radius organic shape hero',
        'An organic gradient blob morphs its shape and slowly spins next to a {label} headline.',
        ['The blob is a div whose 8-value border-radius cycles through {k} keyframe shapes over {dur}s while rotating.',
         'A second, blurred copy behind it creates a soft glow; both use the brand gradient.',
         'Layout is two columns on desktop and stacks on phones.'])
def e_morph(c):
    r = c['r']
    k = 4
    dur = pick(r, [8, 10, 14])
    c['vars'].update(k=k, dur=dur)
    frames = ''
    for i in range(k + 1):
        vals = [r.randint(30, 70) for _ in range(4)]
        if i == k:
            frames += 'to{border-radius:60% 40% 30% 70%/60% 30% 70% 40%;transform:rotate(360deg)}'
        else:
            frames += '%d%%{border-radius:%d%% %d%% %d%% %d%%/%d%% %d%% %d%% %d%%}' % (int(100 / k * i), vals[0], 100 - vals[0], vals[1], 100 - vals[1], vals[2], vals[3], 100 - vals[3], 100 - vals[2])
    css = ('body{min-height:100vh}.wrap{display:grid;grid-template-columns:1.1fr 1fr;align-items:center;max-width:1200px;margin:0 auto;min-height:calc(100vh - 80px)}'
           '.art{position:relative;display:grid;place-items:center;height:60vmin}.blob,.glow{position:absolute;width:44vmin;height:44vmin;background:linear-gradient(135deg,var(--a1),var(--a2) 60%%,var(--a3));animation:mo %ds ease-in-out infinite}'
           '.glow{filter:blur(40px);opacity:.6;animation-delay:-2s}@keyframes mo{0%%{border-radius:60%% 40%% 30%% 70%%/60%% 30%% 70%% 40%%}%s}'
           '@media(max-width:820px){.wrap{grid-template-columns:1fr}.art{height:46vmin;order:-1}.blob,.glow{width:38vmin;height:38vmin}}@media(prefers-reduced-motion:reduce){.blob,.glow{animation:none}}' % (dur, frames)
           + NAV_CSS + COPY_CSS + left_hero_css())
    body = nav(c) + '<div class="wrap">%s<div class="art" aria-hidden="true"><i class="glow"></i><i class="blob"></i></div></div>' % hero_copy(c)
    return css, body, '', ''


# 21. Glitch text
@engine('glitch-text', 'Glitch Headline', 'Text Effect', 'glitch text rgb split cyber headline',
        'A {label} headline that glitches with RGB-split slices every few seconds, cyber style.',
        ['Two ::before/::after copies of the headline (via data-text) are tinted --a1 and --a2 and offset a few pixels.',
         'Every {every}s a short burst animates clip-path inset() slices and translate on each copy for about 600ms.',
         'Scanlines are a repeating-linear-gradient overlay; reduced motion disables the bursts.'])
def e_glitch(c):
    r = c['r']
    t = c['t']
    every = pick(r, [3, 4, 5])
    c['vars'].update(every=every)
    css = ('body{min-height:100vh;display:flex;flex-direction:column;position:relative}body::after{content:"";position:fixed;inset:0;pointer-events:none;background:repeating-linear-gradient(0deg,#0000 0 2px,#0000000f 2px 3px)}'
           'main{flex:1;display:grid;place-items:center;text-align:center;padding:24px}.gl{position:relative;display:inline-block}.gl::before,.gl::after{content:attr(data-text);position:absolute;inset:0;opacity:0}'
           '.gl::before{color:var(--a1);transform:translate(-3px,0)}.gl::after{color:var(--a2);transform:translate(3px,0)}'
           '.gl.on::before{opacity:.9;animation:g1 .6s steps(2) both}.gl.on::after{opacity:.9;animation:g2 .6s steps(2) both}'
           '@keyframes g1{0%{clip-path:inset(10% 0 70% 0)}25%{clip-path:inset(60% 0 10% 0);transform:translate(-6px,2px)}50%{clip-path:inset(30% 0 40% 0)}75%{clip-path:inset(80% 0 5% 0);transform:translate(4px,-1px)}100%{clip-path:inset(0 0 100% 0)}}'
           '@keyframes g2{0%{clip-path:inset(70% 0 10% 0)}25%{clip-path:inset(5% 0 75% 0);transform:translate(6px,-2px)}50%{clip-path:inset(45% 0 30% 0)}75%{clip-path:inset(15% 0 60% 0);transform:translate(-4px,1px)}100%{clip-path:inset(100% 0 0 0)}}'
           '.sub{margin:0 auto}.row{justify-content:center}' + NAV_CSS + BTN_CSS + COPY_CSS)
    body = nav(c) + '<main><div><p class="eyebrow">%s</p><h1><span class="gl" data-text="%s">%s</span></h1><p class="sub">%s</p><div class="row"><a class="btn" href="#">%s</a><a class="btn ghost" href="#">%s</a></div></div></main>' % (
        esc(t['label']), esc(t['headline']), esc(t['headline']), esc(t['sub']), esc(t['cta']), esc(t['cta2']))
    js = '(function(){if(matchMedia("(prefers-reduced-motion: reduce)").matches)return;var g=document.querySelector(".gl");function b(){g.classList.remove("on");void g.offsetWidth;g.classList.add("on")}setTimeout(b,600);setInterval(b,%d)})();' % (every * 1000)
    return css, body, js, ''


# 22. Shimmer gradient text + underline nav
@engine('shimmer-text', 'Shimmer Gradient Text', 'Text Effect', 'gradient text shimmer shine animated underline',
        'A light sweep shimmers across the gradient {label} headline while nav links get animated underlines.',
        ['The headline uses background-clip:text with a 200%-wide gradient (--ink → --a1 → --a2 → --ink) animated across in {dur}s.',
         'Navigation links draw an underline from left to right on hover via a scaleX transform on ::after.',
         'Feature chips below fade in one by one.'])
def e_shimmer(c):
    r = c['r']
    t = c['t']
    dur = pick(r, [3, 4, 6])
    c['vars'].update(dur=dur)
    chips = ''.join('<span style="--i:%d">%s</span>' % (i, esc(x)) for i, x in enumerate(t['items']))
    css = ('body{min-height:100vh;display:flex;flex-direction:column}main{flex:1;display:grid;place-items:center;text-align:center;padding:24px}'
           'h1{background:linear-gradient(100deg,var(--ink) 20%%,var(--a1) 40%%,var(--a2) 50%%,var(--ink) 70%%);background-size:250%% 100%%;-webkit-background-clip:text;background-clip:text;color:transparent;animation:sh %ds linear infinite}'
           '@keyframes sh{from{background-position:120%% 0}to{background-position:-120%% 0}}.links a{position:relative}.links a::after{content:"";position:absolute;left:0;right:0;bottom:-4px;height:2px;background:var(--a1);transform:scaleX(0);transform-origin:left;transition:transform .3s}.links a:hover::after{transform:scaleX(1)}'
           '.chips{display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin-top:28px}.chips span{padding:8px 14px;border-radius:99px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);font-size:14px;opacity:0;animation:in .6s forwards;animation-delay:calc(.4s + var(--i)*.12s)}'
           '@keyframes in{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}.sub{margin:0 auto}.row{justify-content:center}@media(prefers-reduced-motion:reduce){h1{animation:none}.chips span{animation:none;opacity:1}}' % dur
           + NAV_CSS + BTN_CSS + COPY_CSS)
    body = nav(c) + '<main><div><p class="eyebrow">%s</p><h1>%s</h1><p class="sub">%s</p><div class="row"><a class="btn" href="#">%s</a></div><div class="chips">%s</div></div></main>' % (
        esc(t['brand']), esc(t['headline']), esc(t['sub']), esc(t['cta']), chips)
    return css, body, '', ''


# 23. Curtain preloader
@engine('curtain-loader', 'Curtain Preloader', 'Loader', 'preloader loading curtain reveal intro counter',
        'A counter runs to 100%, then curtains slide open to reveal the {label} hero; it replays in the preview.',
        ['A fixed overlay shows the brand and a percentage counting 0→100 over {dur}ms with an ease-out curve.',
         'At 100 the overlay splits into {panels} vertical panels that slide up with a staggered delay.',
         'The hero copy fades in and rises 24px as it is revealed. In production run the loader only on the first visit.'])
def e_curtain(c):
    r = c['r']
    t = c['t']
    dur = pick(r, [1200, 1600, 2000])
    panels = pick(r, [3, 4, 5])
    c['vars'].update(dur=dur, panels=panels)
    pn = ''.join('<i style="--i:%d"></i>' % i for i in range(panels))
    css = ('body{min-height:100vh;overflow:hidden}.ld{position:fixed;inset:0;z-index:20;display:flex}.ld i{flex:1;background:var(--a1);transition:transform .8s cubic-bezier(.7,0,.2,1);transition-delay:calc(var(--i)*80ms)}'
           '.ld.open i{transform:translateY(-101%)}.ld .in{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--on);transition:opacity .3s}'
           '.ld.open .in{opacity:0}.ld b{font-size:clamp(20px,3vw,28px);letter-spacing:.2em;text-transform:uppercase}.ld span{font-size:clamp(60px,14vw,160px);font-weight:900;letter-spacing:-.05em;font-variant-numeric:tabular-nums}'
           '.copy{opacity:0;transform:translateY(24px);transition:opacity 1s .2s,transform 1.4s cubic-bezier(.2,.8,.2,1)}.shown .copy{opacity:1;transform:none}' + NAV_CSS + BTN_CSS + COPY_CSS + center_hero_css())
    body = '<div class="ld" id="ld">%s<div class="in"><b>%s</b><span id="pc">0</span></div></div>' % (pn, esc(t['brand'])) + nav(c) + hero_copy(c)
    js = ('(function(){var L=document.getElementById("ld"),P=document.getElementById("pc"),D=%d;function run(){L.classList.remove("open");document.body.classList.remove("shown");var t0=performance.now();'
          '(function f(n){var p=Math.min(1,(n-t0)/D),e=1-Math.pow(1-p,3);P.textContent=Math.round(e*100);if(p<1)requestAnimationFrame(f);else setTimeout(function(){L.classList.add("open");document.body.classList.add("shown")},200)})(t0)}'
          'if(matchMedia("(prefers-reduced-motion: reduce)").matches){L.classList.add("open");document.body.classList.add("shown");return}run();setInterval(run,D+4500)})();' % dur)
    return css, body, js, ''


# 24. Scroll timeline
@engine('scroll-timeline', 'Scroll Timeline', 'Timeline', 'timeline scroll progress line steps roadmap',
        'A vertical {label} timeline whose line fills as you scroll and whose steps pop in with a dot pulse.',
        ['A central line has a gradient fill whose scaleY follows how far the timeline has scrolled through the viewport.',
         'Each step alternates left/right (single column on phones); when its dot passes the screen middle it gets .on and pulses once.',
         'Steps slide in from their side over {dur}ms.'])
def e_timeline(c):
    r = c['r']
    t = c['t']
    dur = pick(r, [500, 700, 900])
    c['vars'].update(dur=dur)
    years = ['Step 1', 'Step 2', 'Step 3', 'Step 4', 'Step 5', 'Step 6']
    steps = ''.join('<li class="st"><span class="dot"></span><div class="cd"><b>%s</b><h3>%s</h3><p>%s</p></div></li>' % (years[i], esc(it), esc(t['sub'] if i == 0 else '%s: %s.' % (it, t['stats'][i % 3][1]))) for i, it in enumerate(t['items']))
    css = ('.intro{text-align:center;padding:12vh 20px 6vh}.intro h1{max-width:16ch;margin:10px auto}.tl{position:relative;max-width:900px;margin:0 auto 20vh;padding:0 20px;list-style:none}'
           '.tl::before,.fill{content:"";position:absolute;left:50%%;top:0;bottom:0;width:3px;margin-left:-1.5px;background:var(--line)}.fill{background:linear-gradient(var(--a1),var(--a2));transform-origin:top;transform:scaleY(0)}'
           '.st{position:relative;width:50%%;padding:0 40px 60px 0;opacity:0;transform:translateX(-40px);transition:opacity %dms,transform %dms}.st:nth-child(odd){margin-left:50%%;padding:0 0 60px 40px;transform:translateX(40px)}.st.on{opacity:1;transform:none}'
           '.dot{position:absolute;right:-9px;top:6px;width:18px;height:18px;border-radius:50%%;background:var(--bg);box-shadow:inset 0 0 0 3px var(--a1)}.st:nth-child(odd) .dot{right:auto;left:-9px}.st.on .dot{background:var(--a1);animation:pu .8s}'
           '@keyframes pu{0%%{box-shadow:0 0 0 0 var(--a1)}100%%{box-shadow:0 0 0 18px transparent}}.cd{padding:20px;border-radius:18px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line)}.cd b{color:var(--a1);font-size:13px}.cd h3{margin:6px 0}.cd p{margin:0;color:var(--muted)}'
           '@media(max-width:700px){.tl::before,.fill{left:28px}.st,.st:nth-child(odd){width:auto;margin-left:0;padding:0 0 40px 54px;transform:translateY(24px)}.dot,.st:nth-child(odd) .dot{left:0;right:auto}}'
           '@media(prefers-reduced-motion:reduce){.st{opacity:1;transform:none!important;transition:none}}' % (dur, dur) + NAV_CSS + BTN_CSS + COPY_CSS)
    body = nav(c) + '<header class="intro"><p class="eyebrow">How %s works</p><h1>%s</h1><p class="sub" style="margin:0 auto">Scroll ↓</p></header><ol class="tl" id="tl"><i class="fill" id="fl"></i>%s</ol>' % (esc(t['brand']), esc(t['headline']), steps)
    js = ('(function(){var tl=document.getElementById("tl"),fl=document.getElementById("fl"),S=tl.querySelectorAll(".st");function f(){var b=tl.getBoundingClientRect(),mid=innerHeight*.6,p=Math.min(1,Math.max(0,(mid-b.top)/b.height));fl.style.transform="scaleY("+p+")";'
          'S.forEach(function(s){if(s.getBoundingClientRect().top<mid)s.classList.add("on")})}addEventListener("scroll",f,{passive:true});addEventListener("resize",f);f()})();')
    return css, body, js, ''


# 25. Testimonial carousel
@engine('testimonial-carousel', 'Testimonial Carousel', 'Testimonials', 'testimonials carousel slider auto play dots',
        'Customer quotes for a {label} brand slide by automatically with progress dots, swipe and keyboard support.',
        ['Slides sit in a flex track that translates by −100% × index with a {dur}ms ease transition.',
         'Autoplay advances every {every}s; a progress bar in the active dot shows the time left; hovering or focusing pauses it.',
         'Arrow keys and swipe (pointer events with a 40px threshold) change slides.'])
def e_testi(c):
    r = c['r']
    t = c['t']
    dur = pick(r, [500, 700])
    every = pick(r, [4, 5, 6])
    c['vars'].update(dur=dur, every=every)
    quotes = [(t['quote'], t['who']),
              ('Setup took ten minutes and the team adopted it the same day.', 'Operations lead, %s customer' % t['brand']),
              ('Clear, fast and honestly priced. Exactly what we needed.', 'Small business owner'),
              ('Support answered in minutes, by a real person who knew the product.', 'Long-time user')]
    slides = ''.join('<figure class="sl"><blockquote>“%s”</blockquote><figcaption><i style="--h:%d">%s</i>%s</figcaption></figure>' % (esc(q), (i * 80 + 20) % 360, esc(w[0]), esc(w)) for i, (q, w) in enumerate(quotes))
    dots = ''.join('<button type="button" aria-label="Show quote %d"><i></i></button>' % (i + 1) for i in range(len(quotes)))
    css = ('main{max-width:900px;margin:0 auto;padding:40px 20px 60px;text-align:center}main h2{font-size:clamp(26px,4vw,44px);letter-spacing:var(--ls);font-weight:var(--hw);margin:10px 0 26px}'
           '.vp{overflow:hidden;border-radius:26px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line);touch-action:pan-y}.tr{display:flex;transition:transform %dms cubic-bezier(.3,.8,.2,1)}'
           '.sl{flex:0 0 100%%;margin:0;padding:clamp(28px,6vw,60px)}blockquote{margin:0;font-size:clamp(20px,3vw,32px);line-height:1.35;letter-spacing:-.01em}'
           'figcaption{display:flex;align-items:center;justify-content:center;gap:10px;margin-top:22px;color:var(--muted)}figcaption i{display:grid;place-items:center;width:40px;height:40px;border-radius:50%%;font-style:normal;font-weight:800;color:#fff;background:hsl(var(--h) 70%% 50%%)}'
           '.dots{display:flex;gap:8px;justify-content:center;margin-top:18px}.dots button{all:unset;cursor:pointer;width:34px;height:6px;border-radius:9px;background:var(--line);overflow:hidden}.dots button:focus-visible{outline:2px solid var(--a2)}'
           '.dots i{display:block;height:100%%;width:0;background:var(--a1)}.dots .on i{animation:pg %ds linear forwards}.paused .dots .on i{animation-play-state:paused}@keyframes pg{to{width:100%%}}' % (dur, every)
           + NAV_CSS + BTN_CSS + COPY_CSS)
    body = nav(c) + '<main id="tm"><p class="eyebrow">Loved by customers</p><h2>What people say about %s</h2><div class="vp" id="vp" tabindex="0" aria-roledescription="carousel"><div class="tr" id="tr">%s</div></div><div class="dots" id="dt">%s</div></main>' % (esc(t['brand']), slides, dots)
    js = ('(function(){var m=document.getElementById("tm"),tr=document.getElementById("tr"),D=document.querySelectorAll("#dt button"),n=D.length,i=0,E=%d,tm;'
          'function go(k){i=(k+n)%%n;tr.style.transform="translateX("+(-100*i)+"%%)";D.forEach(function(d,j){d.classList.toggle("on",j===i);d.firstChild.style.animation="none";void d.offsetWidth;d.firstChild.style.animation=""});clearTimeout(tm);if(!m.classList.contains("paused"))tm=setTimeout(function(){go(i+1)},E)}'
          'D.forEach(function(d,j){d.addEventListener("click",function(){go(j)})});m.addEventListener("mouseenter",function(){m.classList.add("paused");clearTimeout(tm)});m.addEventListener("mouseleave",function(){m.classList.remove("paused");go(i)});'
          'var vp=document.getElementById("vp");vp.addEventListener("keydown",function(e){if(e.key==="ArrowRight")go(i+1);if(e.key==="ArrowLeft")go(i-1)});var sx=null;vp.addEventListener("pointerdown",function(e){sx=e.clientX});vp.addEventListener("pointerup",function(e){if(sx!==null&&Math.abs(e.clientX-sx)>40)go(i+(e.clientX<sx?1:-1));sx=null});go(0)})();' % (every * 1000))
    return css, body, js, ''


# 26. Gradient gallery with lightbox
@engine('gallery-lightbox', 'Zoom Gallery + Lightbox', 'Gallery', 'gallery masonry hover zoom lightbox grid',
        'A {label} masonry gallery of art tiles that zoom on hover and open in a smooth lightbox.',
        ['CSS columns ({cols} on desktop) create a masonry layout of CSS-art tiles (layered gradients, no images).',
         'Hover scales the art 1.08 inside its frame and reveals a caption bar.',
         'Clicking a tile opens a <dialog> lightbox that animates from the tile size with the View Transitions-style scale trick; Esc or click closes it.'])
def e_gallery(c):
    r = c['r']
    t = c['t']
    cols = pick(r, [3, 4])
    c['vars'].update(cols=cols)
    tiles = ''
    for i in range(10):
        h = r.randint(0, 359)
        hh = pick(r, [180, 240, 300, 360])
        kind = i % 3
        if kind == 0:
            bg = 'radial-gradient(circle at 30%% 30%%,hsl(%d 90%% 70%%),transparent 50%%),linear-gradient(160deg,hsl(%d 70%% 45%%),hsl(%d 60%% 20%%))' % (h, (h + 40) % 360, (h + 90) % 360)
        elif kind == 1:
            bg = 'repeating-linear-gradient(45deg,hsl(%d 70%% 55%%) 0 14px,hsl(%d 70%% 40%%) 14px 28px)' % (h, (h + 30) % 360)
        else:
            bg = 'conic-gradient(from %ddeg,hsl(%d 80%% 60%%),hsl(%d 80%% 50%%),hsl(%d 80%% 60%%))' % (h, h, (h + 120) % 360, h)
        tiles += '<button class="ti" type="button" style="height:%dpx"><span class="art" style="background:%s"></span><span class="cap">%s</span></button>' % (hh, bg, esc(t['items'][i % 6]))
    css = ('main{max-width:1150px;margin:0 auto;padding:30px 20px 60px}main h2{font-size:clamp(28px,4.4vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:0 0 22px}'
           '.ms{columns:%d 220px;column-gap:14px}.ti{all:unset;box-sizing:border-box;cursor:zoom-in;position:relative;display:block;width:100%%;margin:0 0 14px;border-radius:18px;overflow:hidden;break-inside:avoid}.ti:focus-visible{outline:2px solid var(--a2);outline-offset:3px}'
           '.art{position:absolute;inset:0;transition:transform .6s cubic-bezier(.2,.8,.2,1)}.ti:hover .art{transform:scale(1.08)}'
           '.cap{position:absolute;left:10px;right:10px;bottom:10px;padding:8px 12px;border-radius:10px;background:#000a;color:#fff;font-size:14px;transform:translateY(130%%);transition:transform .35s}.ti:hover .cap,.ti:focus .cap{transform:none}'
           'dialog{border:0;padding:0;background:transparent;max-width:min(92vw,900px);width:100%%}dialog::backdrop{background:#000c;backdrop-filter:blur(4px)}dialog .big{height:min(80vh,620px);border-radius:22px;animation:pop .45s cubic-bezier(.2,1.2,.3,1)}'
           'dialog p{color:#fff;text-align:center;font-weight:700}@keyframes pop{from{transform:scale(.6);opacity:0}}' % cols + NAV_CSS + BTN_CSS + COPY_CSS)
    body = nav(c) + '<main><h2>%s</h2><div class="ms">%s</div></main><dialog id="lb"><div class="big" id="bg"></div><p id="lc"></p></dialog>' % (esc(t['headline']), tiles)
    js = ('(function(){var d=document.getElementById("lb"),bg=document.getElementById("bg"),lc=document.getElementById("lc");document.querySelectorAll(".ti").forEach(function(t){t.addEventListener("click",function(){bg.style.background=t.querySelector(".art").style.background;lc.textContent=t.querySelector(".cap").textContent;d.showModal()})});'
          'd.addEventListener("click",function(){d.close()})})();')
    return css, body, js, ''


# 27. Retro grid floor
@engine('retro-grid', 'Retro Horizon Grid', 'Background', 'synthwave retro grid perspective floor sun 80s',
        'An 80s-style neon grid floor scrolls toward you under a striped sunset, behind a {label} headline.',
        ['The floor is a plane with two repeating-linear-gradients (grid lines) tilted with rotateX({tilt}deg) inside perspective.',
         'background-position animates on Y over {dur}s so the grid flows toward the viewer forever.',
         'The sun is a gradient circle sliced with a repeating mask; a horizon glow blends the two.'])
def e_retro(c):
    r = c['r']
    tilt = pick(r, [70, 75, 80])
    dur = pick(r, [1.2, 2, 3])
    c['vars'].update(tilt=tilt, dur=dur)
    css = ('body{min-height:100vh;overflow:hidden;background:linear-gradient(180deg,var(--bg) 0%%,var(--surface) 55%%,var(--bg) 56%%)}'
           '.sun{position:absolute;left:50%%;top:max(30vh,300px);width:min(40vmin,380px);aspect-ratio:1;transform:translateX(-50%%);border-radius:50%%;background:linear-gradient(180deg,var(--a3),var(--a1));'
           '-webkit-mask:repeating-linear-gradient(180deg,#000 0 14px,transparent 14px 20px);mask:repeating-linear-gradient(180deg,#000 0 14px,transparent 14px 20px);filter:drop-shadow(0 0 40px var(--a1))}'
           '.floor{position:absolute;left:0;right:0;bottom:-10vh;height:56vh;perspective:300px;overflow:hidden}.plane{position:absolute;top:0;bottom:-100%%;left:-50%%;right:-50%%;transform:rotateX(%ddeg);transform-origin:top;'
           'background-image:linear-gradient(var(--a2) 2px,transparent 2px),linear-gradient(90deg,var(--a2) 2px,transparent 2px);background-size:60px 60px;animation:fl %ss linear infinite;opacity:.8}'
           '@keyframes fl{to{background-position:0 60px}}.hz{position:absolute;left:0;right:0;top:55vh;height:2px;background:var(--a2);box-shadow:0 0 30px 6px var(--a2)}'
           '.copy{padding-top:6vh!important}@media(prefers-reduced-motion:reduce){.plane{animation:none}}' % (tilt, dur) + NAV_CSS + COPY_CSS + center_hero_css() + '.copy .sub{display:none}')
    body = '<div class="sun" aria-hidden="true"></div><div class="hz"></div><div class="floor" aria-hidden="true"><div class="plane"></div></div>' + nav(c) + hero_copy(c)
    return css, body, '', ''


# 28. Code rain
@engine('code-rain', 'Code Rain', 'Background', 'matrix code rain characters canvas hacker',
        'Columns of glyphs rain down the screen with glowing heads behind a {label} headline.',
        ['A <canvas> splits the width into {fs}px columns; each column keeps a y position and draws one random glyph per frame.',
         'A translucent background fill each frame leaves fading trails; the leading glyph is brighter.',
         'Frame rate is throttled to about 24 fps for the classic look and low CPU.'])
def e_rain(c):
    r = c['r']
    fs = pick(r, [14, 16, 18])
    glyphs = pick(r, ['01', 'アイウエオカキクケコサシスセソ0123456789', '{}[]<>/=+*;:01', 'ABCDEFGHJKLMNPQRSTUVWXYZ0123456789'])
    c['vars'].update(fs=fs)
    css = 'body{min-height:100vh;overflow:hidden}canvas{position:fixed;inset:0;width:100%;height:100%;opacity:.55}.copy h1{text-shadow:0 0 30px var(--bg)}' + NAV_CSS + COPY_CSS + center_hero_css()
    js = ('(function(){var cv=document.getElementById("cv"),x=cv.getContext("2d"),W,H,F=%d,C,Y,G=%s,cs=getComputedStyle(document.documentElement),a1=cs.getPropertyValue("--a1").trim(),ink=cs.getPropertyValue("--ink").trim(),bg=cs.getPropertyValue("--bg").trim(),last=0;'
          'function rs(){W=cv.width=innerWidth;H=cv.height=innerHeight;C=Math.ceil(W/F);Y=[];for(var i=0;i<C;i++)Y.push(Math.random()*-H/F)}rs();addEventListener("resize",rs);var red=matchMedia("(prefers-reduced-motion: reduce)").matches;'
          'function f(n){if(n-last>42){last=n;x.globalAlpha=.12;x.fillStyle=bg;x.fillRect(0,0,W,H);x.globalAlpha=1;x.font=F+"px monospace";for(var i=0;i<C;i++){var g=G[Math.floor(Math.random()*G.length)];x.fillStyle=Math.random()<.04?ink:a1;x.fillText(g,i*F,Y[i]*F);'
          'if(Y[i]*F>H&&Math.random()>.975)Y[i]=0;Y[i]++}}if(!red&&!document.hidden)requestAnimationFrame(f)}requestAnimationFrame(f);document.addEventListener("visibilitychange",function(){if(!document.hidden&&!red)requestAnimationFrame(f)})})();' % (fs, jsstr(glyphs)))
    return css, '<canvas id="cv" aria-hidden="true"></canvas>' + nav(c) + hero_copy(c), js, ''


# 29. Dot grid wave
@engine('dot-wave', 'Dot Grid Wave', 'Background', 'dots grid wave ripple canvas pointer',
        'A field of dots swells in a travelling wave and ripples outward from your cursor, for a {label} brand.',
        ['A <canvas> draws a grid of dots every {gap}px; each dot\'s radius = base + sin(distance to wave centre − time) × amplitude.',
         'The wave centre follows the pointer smoothly (lerp 0.08) and drifts by itself when there is no pointer.',
         'Dot colour blends from --a1 to --a2 with the wave height.'])
def e_dotwave(c):
    r = c['r']
    gap = pick(r, [22, 28, 34])
    c['vars'].update(gap=gap)
    css = 'body{min-height:100vh;overflow:hidden}canvas{position:fixed;inset:0;width:100%;height:100%}' + NAV_CSS + COPY_CSS + (center_hero_css() if c['v'] % 2 else left_hero_css())
    js = ('(function(){var cv=document.getElementById("cv"),x=cv.getContext("2d"),W,H,D=Math.min(devicePixelRatio||1,2),G=%d,t=0,cx=.5,cy=.5,tx=.5,ty=.5,live=0,cs=getComputedStyle(document.documentElement),a1=cs.getPropertyValue("--a1").trim(),a2=cs.getPropertyValue("--a2").trim();'
          'function rs(){W=innerWidth;H=innerHeight;cv.width=W*D;cv.height=H*D;x.setTransform(D,0,0,D,0,0)}rs();addEventListener("resize",rs);addEventListener("pointermove",function(e){live=1;tx=e.clientX/W;ty=e.clientY/H});var red=matchMedia("(prefers-reduced-motion: reduce)").matches;'
          'function f(){if(!red)t+=.05;if(!live){tx=.5+.3*Math.sin(t*.13);ty=.5+.25*Math.cos(t*.11)}cx+=(tx-cx)*.08;cy+=(ty-cy)*.08;x.clearRect(0,0,W,H);for(var X=G/2;X<W;X+=G)for(var Y=G/2;Y<H;Y+=G){var d=Math.hypot(X-cx*W,Y-cy*H),s=Math.sin(d/40-t);'
          'x.globalAlpha=.25+.6*(s+1)/2;x.fillStyle=s>0?a1:a2;x.beginPath();x.arc(X,Y,1.2+1.8*(s+1)/2,0,7);x.fill()}x.globalAlpha=1;if(!document.hidden)requestAnimationFrame(f)}f();document.addEventListener("visibilitychange",function(){if(!document.hidden)f()})})();' % gap)
    return css, '<canvas id="cv" aria-hidden="true"></canvas>' + nav(c) + hero_copy(c), js, ''


# 30. Confetti CTA
@engine('confetti-cta', 'Confetti Burst CTA', 'CTA', 'confetti burst celebration button canvas particles',
        'Clicking the {label} button fires a burst of colourful confetti with gravity and spin.',
        ['A full-screen pointer-events:none <canvas> spawns {n} paper pieces at the click point with random angle, speed, spin and colour from the palette.',
         'Each piece applies gravity 0.25, air drag 0.99 and wobble, and fades after about 2 seconds.',
         'The preview fires a burst every 3 seconds; reduced motion shows a simple checkmark instead.'])
def e_confetti(c):
    r = c['r']
    t = c['t']
    n = pick(r, [80, 120, 160])
    c['vars'].update(n=n)
    css = ('body{min-height:100vh;display:flex;flex-direction:column}main{flex:1;display:grid;place-items:center;text-align:center;padding:24px}canvas{position:fixed;inset:0;width:100%;height:100%;pointer-events:none;z-index:9}'
           '.card{max-width:640px;padding:clamp(30px,6vw,60px);border-radius:28px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line),0 40px 80px -40px var(--a1)}.card h2{font-size:clamp(28px,4.6vw,52px);letter-spacing:var(--ls);font-weight:var(--hw);margin:10px 0}.card p{color:var(--muted)}'
           '#go{margin-top:24px;font-size:18px;padding:16px 28px}.ok{display:block;margin-top:12px;color:var(--a1);font-weight:700;min-height:1.4em}' + NAV_CSS + BTN_CSS + COPY_CSS)
    body = nav(c) + '<main><div class="card"><p class="eyebrow">%s</p><h2>%s</h2><p>%s</p><button class="btn" id="go" type="button">%s 🎉</button><span class="ok" id="ok" aria-live="polite"></span></div></main><canvas id="cv" aria-hidden="true"></canvas>' % (esc(t['brand']), esc(t['headline']), esc(t['sub']), esc(t['cta']))
    js = ('(function(){var cv=document.getElementById("cv"),x=cv.getContext("2d"),W,H,P=[],N=%d,cs=getComputedStyle(document.documentElement),C=["--a1","--a2","--a3","--ink"].map(function(k){return cs.getPropertyValue(k).trim()}),run=0,red=matchMedia("(prefers-reduced-motion: reduce)").matches,ok=document.getElementById("ok");'
          'function rs(){W=cv.width=innerWidth;H=cv.height=innerHeight}rs();addEventListener("resize",rs);'
          'function burst(px,py){ok.textContent="You are in! ✓";setTimeout(function(){ok.textContent=""},1800);if(red)return;for(var i=0;i<N;i++){var a=Math.random()*6.283,s=4+Math.random()*9;P.push({x:px,y:py,vx:Math.cos(a)*s,vy:Math.sin(a)*s-6,r:Math.random()*6.283,vr:(Math.random()-.5)*.4,w:6+Math.random()*6,h:4+Math.random()*6,c:C[i%%4],l:120})}if(!run){run=1;requestAnimationFrame(f)}}'
          'function f(){x.clearRect(0,0,W,H);P=P.filter(function(p){p.vy+=.25;p.vx*=.99;p.vy*=.99;p.x+=p.vx+Math.sin(p.l/8);p.y+=p.vy;p.r+=p.vr;p.l--;x.save();x.globalAlpha=Math.min(1,p.l/40);x.translate(p.x,p.y);x.rotate(p.r);x.fillStyle=p.c;x.fillRect(-p.w/2,-p.h/2,p.w,p.h);x.restore();return p.l>0&&p.y<H+40});'
          'if(P.length)requestAnimationFrame(f);else run=0}var b=document.getElementById("go");b.addEventListener("click",function(e){burst(e.clientX||W/2,e.clientY||H/2)});'
          'setInterval(function(){var r=b.getBoundingClientRect();burst(r.left+r.width/2,r.top+r.height/2)},3000)})();' % n)
    return css, body, js, ''


# 31. Kinetic scroll type
@engine('kinetic-type', 'Kinetic Scroll Type', 'Scroll Story', 'kinetic typography scroll scale rotate words sticky',
        'Giant {label} words scale, slide and rotate with your scroll inside a pinned stage.',
        ['A {h}vh section holds a sticky stage with {n} huge words; scroll progress p (0–1) drives each word\'s transform.',
         'Word i is active around p = i/n: it scales from 0.6 to 1, slides in from alternating sides and rotates from ±{rot}° to 0, then fades out.',
         'A counter shows "0i / 0n" and the background hue shifts with progress.'])
def e_kinetic(c):
    r = c['r']
    t = c['t']
    words = t['words']
    n = len(words)
    rot = pick(r, [6, 12, 18])
    h = n * 100 + 100
    c['vars'].update(n=n, rot=rot, h=h)
    ws = ''.join('<span class="kw" style="--i:%d">%s</span>' % (i, esc(w)) for i, w in enumerate(words))
    css = ('#ks{height:%dvh;position:relative}.stg{position:sticky;top:0;height:100vh;overflow:hidden;display:grid;place-items:center;background:radial-gradient(circle at 50%% 60%%,color-mix(in srgb,var(--a1) calc(var(--p,0)*40%%),var(--surface)),var(--bg) 70%%)}'
           '.kw{position:absolute;font-size:clamp(56px,16vw,220px);font-weight:900;letter-spacing:-.05em;text-transform:lowercase;opacity:0;will-change:transform,opacity}'
           '.lb{position:absolute;top:12vh;left:0;right:0;text-align:center;color:var(--muted);letter-spacing:.2em;text-transform:uppercase;font-size:13px}.ct{position:absolute;bottom:8vh;right:6vw;font-variant-numeric:tabular-nums;color:var(--muted)}'
           '.intro,.end{min-height:70vh;display:grid;place-items:center;text-align:center;padding:24px}' % h + NAV_CSS + BTN_CSS + COPY_CSS)
    body = (nav(c) + '<header class="intro"><div><p class="eyebrow">%s</p><h1>%s</h1><p class="sub" style="margin:0 auto">Scroll ↓</p></div></header>'
            '<section id="ks"><div class="stg" id="st"><p class="lb">%s is</p>%s<span class="ct" id="ct">01 / %02d</span></div></section><section class="end"><div><h2>%s</h2><a class="btn" href="#">%s</a></div></section>'
            % (esc(t['brand']), esc(t['headline']), esc(t['brand']), ws, n, esc(t['quote']), esc(t['cta'])))
    js = ('(function(){var s=document.getElementById("ks"),st=document.getElementById("st"),W=st.querySelectorAll(".kw"),ct=document.getElementById("ct"),n=W.length,R=%d;function f(){var b=s.getBoundingClientRect(),m=s.offsetHeight-innerHeight,p=Math.min(1,Math.max(0,-b.top/m));st.style.setProperty("--p",p);'
          'var a=Math.min(n-1,Math.floor(p*n));ct.textContent=("0"+(a+1)).slice(-2)+" / "+("0"+n).slice(-2);W.forEach(function(w,i){var l=p*n-i,e=l<0?0:l>1?0:Math.sin(l*Math.PI),dir=i%%2?1:-1;'
          'w.style.opacity=e;w.style.transform="translateX("+((1-e)*dir*30*(l<.5?1:-1))+"vw) scale("+(.6+.4*e)+") rotate("+((1-e)*dir*R)+"deg)"})}addEventListener("scroll",f,{passive:true});addEventListener("resize",f);f()})();' % rot)
    return css, body, js, ''


# 32. Card stack scroll
@engine('stack-cards', 'Stacking Cards Scroll', 'Scroll Story', 'sticky stacking cards scroll scale depth',
        'Feature cards for a {label} brand stick and stack on top of each other as you scroll, each one shrinking back into depth.',
        ['Each card is position:sticky with a slightly larger top offset ({off}px steps), so they pile up.',
         'As the next card arrives, the previous card scales down toward {min} and dims, computed from scroll position.',
         'Cards use the palette with alternating gradient accents.'])
def e_stack(c):
    r = c['r']
    t = c['t']
    off = pick(r, [16, 24, 32])
    mn = pick(r, ['0.88', '0.9', '0.92'])
    c['vars'].update(off=off, min=mn)
    cards = ''
    for i, it in enumerate(t['items'][:5]):
        cards += ('<article class="sc" style="top:calc(10vh + %dpx);--h:%d"><div><b>%02d</b><h3>%s</h3><p>%s</p><a class="btn sm" href="#">%s</a></div><div class="art"></div></article>'
                  % (i * off, (i * 60 + r.randint(0, 50)) % 360, i + 1, esc(it), esc(t['sub']), esc(t['cta'])))
    css = ('.intro{min-height:60vh;display:grid;place-items:center;text-align:center;padding:24px}.stack{max-width:1000px;margin:0 auto;padding:0 20px 30vh}'
           '.sc{position:sticky;display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:center;min-height:60vh;margin-bottom:12vh;padding:clamp(24px,4vw,48px);border-radius:30px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line),0 -20px 60px -30px #0008;transform-origin:center top;will-change:transform}'
           '.sc b{color:var(--a1)}.sc h3{font-size:clamp(28px,4vw,48px);letter-spacing:var(--ls);margin:8px 0}.sc p{color:var(--muted);line-height:1.6}'
           '.art{height:100%;min-height:240px;border-radius:22px;background:radial-gradient(circle at 70% 30%,hsl(var(--h) 90% 70%),transparent 55%),linear-gradient(140deg,hsl(var(--h) 70% 50%),hsl(calc(var(--h) + 50) 60% 25%))}'
           '@media(max-width:760px){.sc{grid-template-columns:1fr}.art{min-height:160px}}' + NAV_CSS + BTN_CSS + COPY_CSS)
    body = nav(c) + '<header class="intro"><div><p class="eyebrow">%s</p><h1>%s</h1></div></header><div class="stack" id="sk">%s</div>' % (esc(t['label']), esc(t['headline']), cards)
    js = ('(function(){var C=document.querySelectorAll(".sc"),M=%s;if(matchMedia("(prefers-reduced-motion: reduce)").matches)return;function f(){for(var i=0;i<C.length-1;i++){var nx=C[i+1].getBoundingClientRect().top,me=C[i].getBoundingClientRect(),d=Math.min(1,Math.max(0,1-(nx-me.top)/me.height));'
          'C[i].style.transform="scale("+(1-(1-M)*d)+")";C[i].style.filter="brightness("+(1-.35*d)+")"}}addEventListener("scroll",f,{passive:true});f()})();' % mn)
    return css, body, js, ''


# 33. Neon sign
@engine('neon-sign', 'Neon Sign Flicker', 'Text Effect', 'neon glow sign flicker text shadow night',
        'The {label} name glows like a neon sign on a brick wall, flickers to life and hums softly.',
        ['The word uses layered text-shadows in --a1 for the glow; a second line uses --a2.',
         'A flicker keyframe ({flick}s cycle) drops opacity and glow for a few random frames, as real neon does.',
         'The wall is a CSS brick pattern from two linear-gradients; a sign frame casts a soft light on it.'])
def e_neon(c):
    r = c['r']
    t = c['t']
    flick = pick(r, [4, 6, 8])
    c['vars'].update(flick=flick)
    css = ('body{min-height:100vh;display:flex;flex-direction:column;background-color:var(--bg);background-image:linear-gradient(335deg,#00000033 23px,transparent 23px),linear-gradient(155deg,#00000022 23px,transparent 23px),linear-gradient(335deg,#00000033 23px,transparent 23px),linear-gradient(155deg,#00000022 23px,transparent 23px);background-size:58px 58px;background-position:0 2px,4px 35px,29px 31px,34px 6px}'
           'main{flex:1;display:grid;place-items:center;text-align:center;padding:24px}.sign{padding:clamp(24px,5vw,50px) clamp(24px,6vw,70px);border-radius:24px;border:3px solid color-mix(in srgb,var(--a1) 50%%,transparent);box-shadow:0 0 30px -5px var(--a1),inset 0 0 30px -10px var(--a1)}'
           '.n1{font-size:clamp(44px,10vw,120px);font-weight:400;letter-spacing:.02em;margin:0;color:#fff;text-shadow:0 0 4px #fff,0 0 10px var(--a1),0 0 22px var(--a1),0 0 44px var(--a1),0 0 80px var(--a1);animation:fk %ds infinite}'
           '.n2{font-size:clamp(16px,2.4vw,24px);letter-spacing:.3em;text-transform:uppercase;color:#fff;text-shadow:0 0 6px var(--a2),0 0 18px var(--a2);margin:10px 0 0}'
           '@keyframes fk{0%%,18%%,22%%,25%%,53%%,57%%,100%%{opacity:1}20%%,24%%,55%%{opacity:.35;text-shadow:none}}.below{margin-top:34px;color:var(--muted)}.below .btn{margin-top:16px}'
           '@media(prefers-reduced-motion:reduce){.n1{animation:none}}' % flick + NAV_CSS + BTN_CSS + COPY_CSS)
    body = nav(c) + '<main><div><div class="sign"><p class="n1">%s</p><p class="n2">%s</p></div><div class="below"><p>%s</p><a class="btn" href="#">%s</a></div></div></main>' % (esc(t['brand']), esc(t['words'][0] + ' · ' + t['words'][1]), esc(t['sub']), esc(t['cta']))
    return css, body, '', 'For best results use a dark palette.'


# 34. Animated dashboard chart
@engine('chart-dashboard', 'Animated Dashboard Charts', 'Charts', 'dashboard charts bars line svg animate stats',
        'A {label} dashboard card where bars grow, a line chart draws itself and KPIs tick up, every few seconds.',
        ['Bars are divs whose height animates from 0 to their value with a {stagger}ms stagger and a springy curve.',
         'The SVG line chart animates stroke-dashoffset from its full length to 0; the area below fades in.',
         'KPIs count up; everything replays every 6 seconds in the preview.'])
def e_chart(c):
    r = c['r']
    t = c['t']
    stagger = pick(r, [60, 90, 120])
    c['vars'].update(stagger=stagger)
    vals = [r.randint(30, 95) for _ in range(12)]
    bars = ''.join('<i style="--v:%d%%;--i:%d"></i>' % (v, i) for i, v in enumerate(vals))
    pts = [r.randint(20, 90) for _ in range(9)]
    path = ' '.join('%s%d %d' % ('M' if i == 0 else 'L', i * 50, 100 - p) for i, p in enumerate(pts))
    area = path + ' L400 100 L0 100 Z'
    kpis = ''.join('<div class="kp"><span>%s</span><b>%s</b></div>' % (esc(lab.capitalize()), esc(val)) for val, lab in t['stats'])
    css = ('main{max-width:1050px;margin:0 auto;padding:30px 20px 60px}.top{display:flex;justify-content:space-between;align-items:end;gap:16px;flex-wrap:wrap;margin-bottom:20px}.top h2{font-size:clamp(24px,3.4vw,40px);letter-spacing:var(--ls);font-weight:var(--hw);margin:6px 0 0}'
           '.kps{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%%,180px),1fr));gap:14px;margin-bottom:14px}.kp,.pane{padding:20px;border-radius:20px;background:var(--surface);box-shadow:inset 0 0 0 1px var(--line)}'
           '.kp span{color:var(--muted);font-size:13px}.kp b{display:block;font-size:32px;letter-spacing:-.03em;margin-top:4px}.two{display:grid;grid-template-columns:1.2fr 1fr;gap:14px}'
           '.bars{display:flex;align-items:flex-end;gap:6px;height:220px;margin-top:12px}.bars i{flex:1;border-radius:6px 6px 2px 2px;background:linear-gradient(var(--a1),color-mix(in srgb,var(--a1) 40%%,transparent));height:0}'
           '.go .bars i{height:var(--v);transition:height .9s cubic-bezier(.3,1.4,.5,1);transition-delay:calc(var(--i)*%dms)}'
           'svg{width:100%%;height:220px;margin-top:12px;overflow:visible}.ln{fill:none;stroke:var(--a2);stroke-width:3;stroke-linecap:round;stroke-linejoin:round;stroke-dasharray:1000;stroke-dashoffset:1000}.go .ln{stroke-dashoffset:0;transition:stroke-dashoffset 2s ease}'
           '.ar{fill:url(#gd);opacity:0}.go .ar{opacity:1;transition:opacity 1s 1.2s}.pane h3{margin:0;font-size:15px;color:var(--muted);font-weight:600}@media(max-width:760px){.two{grid-template-columns:1fr}}' % stagger
           + NAV_CSS + BTN_CSS + COPY_CSS)
    body = nav(c) + ('<main id="db"><div class="top"><div><p class="eyebrow">%s dashboard</p><h2>%s</h2></div><a class="btn sm" href="#">%s</a></div><div class="kps">%s</div>'
                     '<div class="two"><div class="pane"><h3>Monthly activity</h3><div class="bars">%s</div></div><div class="pane"><h3>Growth trend</h3>'
                     '<svg viewBox="0 0 400 100" preserveAspectRatio="none" aria-hidden="true"><defs><linearGradient id="gd" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="var(--a2)" stop-opacity=".45"/><stop offset="1" stop-color="var(--a2)" stop-opacity="0"/></linearGradient></defs><path class="ar" d="%s"/><path class="ln" d="%s" pathLength="1000"/></svg></div></div></main>'
                     % (esc(t['brand']), esc(t['headline']), esc(t['cta']), kpis, bars, area, path))
    js = ('(function(){var d=document.getElementById("db");function run(){d.classList.remove("go");void d.offsetWidth;requestAnimationFrame(function(){d.classList.add("go")})}if(matchMedia("(prefers-reduced-motion: reduce)").matches){d.classList.add("go");return}setTimeout(run,300);setInterval(run,6000)})();')
    return css, body, js, ''


# ── Document + spec builders ───────────────────────────────────────────────

def doc(title, c, css, body, js):
    p = c['p']
    f = c['f']
    line = 'rgba(255,255,255,.12)' if p['dark'] else 'rgba(0,0,0,.1)'
    on = '#0b0b10' if p['onlight'] else '#ffffff'
    root = (':root{--bg:%s;--surface:%s;--ink:%s;--muted:%s;--a1:%s;--a2:%s;--a3:%s;--line:%s;--on:%s;--font:%s;--hw:%d;--ls:%s;color-scheme:%s}'
            % (p['bg'], p['surface'], p['ink'], p['muted'], p['a1'], p['a2'], p['a3'], line, on, f[1], f[2], f[3], 'dark' if p['dark'] else 'light'))
    base = ('*{box-sizing:border-box}html,body{margin:0}body{background:var(--bg);color:var(--ink);font-family:var(--font);-webkit-font-smoothing:antialiased;line-height:1.5}'
            'h1,h2,h3{font-weight:var(--hw);line-height:1.08}a{color:inherit}p{margin:0}h2,h3{margin-top:0}'
            '@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important}}')
    out = ('<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>%s</title><style>%s%s%s%s</style></head><body>%s%s</body></html>'
           % (esc(title), root, base, BTN_CSS if BTN_CSS not in css else '', css, body, ('<script>%s</script>' % js) if js else ''))
    return out


def luminance(hexc):
    hexc = hexc.lstrip('#')
    rgb = [int(hexc[i:i + 2], 16) / 255 for i in (0, 2, 4)]
    lin = [v / 12.92 if v <= 0.03928 else ((v + 0.055) / 1.055) ** 2.4 for v in rgb]
    return 0.2126 * lin[0] + 0.7152 * lin[1] + 0.0722 * lin[2]


def spec(title, e, c, notes):
    p, f, t, v = c['p'], c['f'], c['t'], c['vars']
    fmt = dict(v)
    fmt['label'] = lab(t['label'])
    motion = '\n'.join('%d. %s' % (i + 1, fix_a(m.format(**fmt))) for i, m in enumerate(e['motion']))
    s = []
    s.append('# %s — build prompt (VitalStack Motion Studio)\n' % title)
    s.append('## Goal\nBuild a %s section for **%s**, a %s brand: %s\n' % (e['cat'].lower(), t['brand'], t['label'].lower(), fix_a(e['summary'].format(label=lab(t['label']))).rstrip('.') + '.'))
    s.append('Deliver ONE self-contained `index.html` with inline CSS and vanilla JavaScript. No libraries, no frameworks, no external images, fonts or network requests, no build step.\n')
    s.append('## Visual style\n- Palette "%s" as CSS custom properties: --bg %s, --surface %s, --ink %s, --muted %s, --a1 %s (primary), --a2 %s (secondary), --a3 %s (highlight). %s theme.'
             % (p['name'], p['bg'], p['surface'], p['ink'], p['muted'], p['a1'], p['a2'], p['a3'], 'Dark' if p['dark'] else 'Light'))
    s.append('- Type: %s system stack (`%s`), headings weight %d, letter-spacing %s; body 16 px, line-height 1.5.' % (f[0], f[1], f[2], f[3]))
    s.append('- Rounded corners 12–28 px, subtle 1 px borders (--line), soft shadows tinted with --a1.\n')
    s.append('## Content (use exactly)\n- Brand: %s\n- Headline: “%s”\n- Sub-copy: “%s”\n- Primary button: “%s”; secondary: “%s”\n- Items: %s\n- Stats: %s\n- Testimonial: “%s” — %s\n'
             % (t['brand'], t['headline'], t['sub'], t['cta'], t['cta2'], ', '.join(t['items']), '; '.join('%s %s' % x for x in t['stats']), t['quote'], t['who']))
    s.append('## Layout\n- Top navigation: brand on the left, 3 links (%s) and a small “%s” button on the right; links hide under 640 px.\n- Main area as described in Motion; centre content max-width about 1100 px, side padding clamp(16px, 4vw, 48px).\n'
             % (', '.join(t['items'][:3]), t['cta']))
    s.append('## Motion & interaction\n%s\n' % motion)
    if notes:
        s.append('Note: %s\n' % notes)
    s.append('## Requirements\n- Responsive from 320 px to 1920 px with no horizontal scroll; test at 375, 768 and 1440 px.\n- 60 fps: animate only transform, opacity and filter; batch JS work in requestAnimationFrame; pause canvas loops when the tab is hidden.\n'
             '- Accessibility: semantic landmarks, real <a>/<button> elements, visible :focus-visible outlines, WCAG AA contrast, decorative layers aria-hidden="true".\n'
             '- `prefers-reduced-motion: reduce` turns the motion off or into a calm static state with all content visible.\n- Keep all colours and sizes in CSS variables so the design can be re-themed in one place.\n')
    s.append('## Acceptance checklist\n- [ ] Looks and moves as described on desktop and phone.\n- [ ] Zero console errors; works offline from the single file.\n- [ ] Keyboard users can reach and use every control.\n- [ ] Reduced-motion mode shows the full content without animation.\n- [ ] Lighthouse accessibility score 95+.\n')
    s.append('Keywords: %s, %s' % (e['tags'], t['label'].lower()))
    return '\n'.join(s)


def fix_a(s):
    s = re.sub(r'\b([Aa]) (?=[AEIOUaeiou])', lambda m: m.group(1) + 'n ', s)
    return s[0].upper() + s[1:]


def lab(label):
    return label if any(len(w) > 1 and w[:2].isupper() for w in label.split()) or 'SaaS' in label or 'EdTech' in label else label.lower()


def slug(s):
    return re.sub(r'[^a-z0-9]+', '-', s.lower()).strip('-')


def php_str(s):
    if "\nVSAI;" in s or s.startswith('VSAI;'):
        raise ValueError('heredoc marker in content')
    return "<<<'VSAI'\n" + s + "\nVSAI"


def php_q(s):
    return "'" + s.replace('\\', '\\\\').replace("'", "\\'") + "'"


def main():
    pal = []
    for x in PALETTES:
        d = dict(name=x[0], bg=x[1], surface=x[2], ink=x[3], muted=x[4], a1=x[5], a2=x[6], a3=x[7], dark=x[8])
        d['onlight'] = luminance(d['a1']) > 0.45
        pal.append(d)
    E = len(ENGINES)
    per = -(-COUNT // E)
    rows = []
    for ei, e in enumerate(ENGINES):
        for j in range(per):
            rows.append((j, ei))
    rows.sort()
    rows = rows[:COUNT]
    out_dir = os.path.join(PLUGIN, 'includes', 'data', 'motion-gen')
    os.makedirs(out_dir, exist_ok=True)
    for fn in os.listdir(out_dir):
        if fn.endswith('.php'):
            os.remove(os.path.join(out_dir, fn))
    with open(os.path.join(out_dir, 'index.php'), 'w') as fh:
        fh.write('<?php\n// Silence is golden.\n')
    meta = []
    seen = set()
    for n, (j, ei) in enumerate(rows):
        e = ENGINES[ei]
        theme = THEMES[(j * 7 + ei * 3) % len(THEMES)]
        # Neon / code rain / warp / retro look best dark.
        dark_only = e['slug'] in ('neon-sign', 'code-rain', 'star-warp', 'retro-grid', 'particle-network')
        candidates = [p for p in pal if p['dark']] if dark_only else pal
        p = candidates[(j * 5 + ei * 11) % len(candidates)]
        f = FONTS[(j + ei * 2) % len(FONTS)]
        seed = int(hashlib.md5(('%s|%s|%d' % (e['slug'], theme['key'], j)).encode()).hexdigest()[:8], 16)
        c = dict(p=p, f=f, t=theme, r=random.Random(seed), v=j, vars={})
        css, body, js, notes = e['fn'](c)
        pal_word = p['name'].split()[0]
        title = '%s %s · %s' % (pal_word, e['name'], theme['label'])
        mid = slug('%s-%s-%s' % (e['slug'], theme['key'], pal_word))
        if mid in seen:
            mid = slug('%s-%d' % (mid, j))
        seen.add(mid)
        html = doc(title, c, css, body, js)
        sp = spec(title, e, c, notes)
        premium = (n % 3) != 0  # one in three free
        summary = fix_a(e['summary'].format(label=lab(theme['label'])))
        meta.append(dict(id=mid, title=title, cat=e['cat'], premium=premium, summary=summary,
                         tags='%s %s %s' % (e['tags'], theme['label'].lower(), p['name'].lower()), engine=e['slug']))
        with open(os.path.join(out_dir, mid + '.php'), 'w', encoding='utf-8') as fh:
            fh.write("<?php\ndefined( 'ABSPATH' ) || exit;\nreturn array(\n\t'code' => %s,\n\t'spec' => %s,\n);\n" % (php_str(html), php_str(sp)))
    lines = ["<?php", "/**", " * Motion Studio: generated designs (tools/aihub/gen_motion.py). Metadata only;", " * code and build prompts live in motion-gen/<id>.php and load on demand.", " *", " * @package VitalStackAIHub", " */", "", "defined( 'ABSPATH' ) || exit;", "", "return array("]
    for m in meta:
        lines.append("\tarray( 'id' => %s, 'title' => %s, 'cat' => %s, 'premium' => %s, 'summary' => %s, 'tags' => %s, 'src' => 'gen' )," % (
            php_q(m['id']), php_q(m['title']), php_q(m['cat']), 'true' if m['premium'] else 'false', php_q(m['summary']), php_q(m['tags'])))
    lines.append(");\n")
    with open(os.path.join(PLUGIN, 'includes', 'data', 'motion-gen.php'), 'w', encoding='utf-8') as fh:
        fh.write('\n'.join(lines))
    with open(os.path.join(os.path.dirname(os.path.abspath(__file__)), 'motion-manifest.json'), 'w') as fh:
        json.dump(meta, fh, indent=0)
    print('engines', E, 'designs', len(meta), 'free', sum(1 for m in meta if not m['premium']))


if __name__ == '__main__':
    main()
