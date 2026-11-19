"""Dimension lists for the 100k Prompt Bazaar generator (gen_prompts_v2.py).

Plain data. Keep entries real, specific and family-friendly.
"""


def L(s):
    return [x.strip() for x in s.strip().split('|') if x.strip()]


# ── Image styles (name: descriptor) ────────────────────────────────────────
STYLES = [x.split(':', 1) for x in L('''
Cinematic photo: cinematic photograph, 35mm lens, shallow depth of field, dramatic rim light, film grain|
Studio product photo: studio product photography, softbox lighting, seamless backdrop, crisp reflections|
Editorial fashion photo: high-fashion editorial photograph, bold styling, magazine lighting|
Documentary photo: candid documentary photograph, natural light, honest moment|
Golden hour photo: golden hour photograph, warm low sun, long soft shadows|
Night street photo: night street photograph, neon reflections, wet pavement, bokeh|
Aerial drone photo: aerial drone photograph, top-down perspective, geometric patterns|
Macro photo: macro photograph, extreme close-up, creamy bokeh, tiny details|
Black and white photo: black and white photograph, high contrast, deep shadows, fine grain|
Polaroid photo: instant film photo, soft colours, white frame, nostalgic|
Vintage 35mm film: vintage 35mm film photo, faded colours, light leaks|
Long exposure photo: long exposure photograph, silky motion trails, tripod-sharp details|
Tilt-shift photo: tilt-shift photograph, miniature effect, selective focus|
Food photography: professional food photography, overhead angle, natural window light, styled props|
Architectural photo: architectural photography, symmetrical lines, clean sky, wide lens|
Watercolor: loose watercolor painting, soft pigment blooms, visible paper texture|
Oil painting: classical oil painting, rich brushwork, warm chiaroscuro lighting|
Impressionist painting: impressionist painting, visible dabs of colour, outdoor light|
Gouache illustration: gouache illustration, matte flat colour, hand-painted edges|
Acrylic pour art: fluid acrylic pour art, cells and swirls, glossy finish|
Ink wash: East Asian ink wash painting, flowing brush strokes, generous white space|
Charcoal sketch: expressive charcoal sketch, smudged shading, textured paper|
Pencil sketch: detailed graphite pencil sketch, cross-hatching, sketchbook feel|
Line art: minimal black ink line art, single-weight lines, lots of negative space|
Colored pencil: soft colored pencil drawing, layered strokes, paper grain|
Pastel drawing: chalk pastel drawing, soft blended colours, dreamy mood|
Flat vector illustration: flat vector illustration, clean shapes, limited palette, subtle grain|
Corporate Memphis: friendly flat illustration, bold shapes, playful proportions|
Isometric 3D: isometric 3D illustration, soft global illumination, pastel palette|
Clay 3D render: cute clay 3D render, soft rounded forms, matte texture, studio lighting|
Glossy 3D render: glossy 3D render, smooth plastic materials, soft reflections|
Low-poly: low-poly 3D art, faceted geometry, gradient lighting|
Voxel art: voxel art, tiny cubes, isometric view, bright colours|
Pixel art: 16-bit pixel art, limited palette, crisp pixels, retro game style|
Anime: anime illustration, cel shading, expressive characters, vibrant colours|
Studio-style anime background: lush anime background painting, detailed clouds, warm light|
Manga ink: black and white manga panel, screentone shading, dynamic lines|
Comic book: American comic book art, bold inks, halftone dots, dramatic angles|
Cartoon: playful cartoon style, thick outlines, bright flat colours|
Children's book: children's book illustration, gentle colours, whimsical details|
Storybook fantasy: storybook fantasy painting, soft glow, magical atmosphere|
Concept art: cinematic concept art, epic scale, atmospheric perspective|
Matte painting: digital matte painting, vast environment, photoreal lighting|
Cyberpunk neon: cyberpunk scene, neon signs, rain-soaked streets, magenta and cyan light|
Solarpunk: solarpunk scene, lush greenery on modern architecture, hopeful daylight|
Steampunk: steampunk style, brass gears, Victorian details, warm lamplight|
Synthwave: synthwave retro-futurism, neon grid, purple and pink sunset|
Vaporwave: vaporwave aesthetic, pastel gradients, retro computer motifs|
Retro 70s: 1970s retro illustration, warm orange and brown palette, sunburst shapes|
Retro 50s ad: 1950s advertising illustration, cheerful characters, halftone print|
Art Deco: Art Deco style, geometric patterns, gold and black, symmetrical composition|
Art Nouveau: Art Nouveau poster style, flowing organic lines, ornate frame|
Bauhaus: Bauhaus design, primary colours, circles squares and triangles, grid layout|
Swiss poster: Swiss international style poster, strong grid, bold sans-serif type|
Minimalist poster: minimalist poster, bold geometric shapes, two-colour palette, negative space|
Risograph print: risograph print, grainy texture, overlapping spot colours|
Screen print: screen print poster, flat ink layers, slight misregistration|
Woodblock print: woodblock print inspired, flat colour areas, bold outlines, textured paper|
Linocut: linocut print, carved lines, high contrast, handmade feel|
Paper cut-out: layered paper cut-out art, soft shadows between layers|
Origami: origami paper sculpture, crisp folds, soft studio light|
Embroidery: embroidery art, stitched threads, fabric texture|
Stained glass: stained glass window style, bold lead lines, glowing colours|
Mosaic: tile mosaic, small square tesserae, ancient Roman style|
Graffiti: street art graffiti mural, spray paint drips, bold lettering shapes|
Pop art: pop art style, bold primary colours, Ben-Day dots|
Surrealism: surrealist painting, dreamlike impossible scene, soft shadows|
Cubism: cubist painting, fragmented geometric planes, multiple viewpoints|
Abstract expressionism: abstract expressionist painting, energetic strokes, bold colour fields|
Double exposure: double exposure, silhouette blended with landscape, monochrome with one accent colour|
Glitch art: glitch art, RGB channel shift, digital scanlines|
Holographic: holographic iridescent materials, rainbow sheen, futuristic|
Neon sign: glowing neon sign style, tube lettering, dark brick wall|
Blueprint: technical blueprint drawing, white lines on blue, measurements|
Infographic: clean infographic illustration, icons, simple labels, flat colours|
Isometric map: isometric illustrated map, tiny buildings, roads and trees|
Botanical illustration: vintage botanical illustration, detailed plant study, labels|
Scientific illustration: precise scientific illustration, cross-section, neutral background|
Fantasy map: hand-drawn fantasy map, parchment texture, compass rose|
Tarot card: tarot card illustration, ornate border, symbolic imagery|
Sticker: die-cut sticker design, thick white border, bold colours|
Emoji 3D: 3D emoji style, glossy, rounded, simple shapes|
App icon: modern app icon, rounded square, soft gradient, single symbol|
Logo concept: simple flat logo concept, single colour, scalable shapes|
Mascot: friendly brand mascot character, expressive pose, clean vector|
Character turnaround: character design sheet, front side and back views, neutral pose|
Chibi: chibi character, big head small body, cute expression|
Kawaii: kawaii style, pastel colours, cute faces on objects|
Gothic: gothic art style, dark romantic mood, ornate details|
Dark fantasy: dark fantasy painting, moody fog, dramatic light|
Ethereal: ethereal dreamy scene, soft glow, pastel haze|
Cottagecore: cottagecore aesthetic, wildflowers, rustic cottage, soft sunlight|
Scandinavian minimal: Scandinavian minimal interior style, light wood, white walls, soft daylight|
Japandi: Japandi style, natural materials, calm neutral palette|
Brutalist: brutalist architecture style, raw concrete, strong shadows|
Bioluminescent: bioluminescent scene, glowing blue organisms, dark surroundings|
Underwater: underwater scene, light rays from the surface, floating particles|
Space art: space art, nebula colours, distant planets, stars|
Retro sci-fi pulp: retro sci-fi pulp cover art, rockets, bold title space|
Ukiyo-e inspired wave: ukiyo-e inspired print, stylised waves, muted indigo palette|
Indian miniature painting: Indian miniature painting style, fine detail, flat perspective, rich borders|
Madhubani: Madhubani folk art style, intricate line patterns, bright natural colours|
Warli: Warli folk art style, white stick figures on earthy background|
Mughal architecture render: Mughal architecture, white marble, intricate jali, symmetrical gardens|
Kerala mural: Kerala mural art style, warm ochre palette, bold outlines|
Chalkboard: chalkboard drawing, white and coloured chalk, dusty texture|
Whiteboard sketch: whiteboard marker sketch, simple diagrams, hand-drawn arrows|
Neumorphism UI: neumorphic UI mockup, soft extruded shapes, light grey|
Glassmorphism UI: glassmorphism UI mockup, frosted glass cards, colourful blurred background|
Dashboard UI mockup: clean SaaS dashboard UI mockup, cards and charts, light theme|
Mobile app mockup: mobile app screen mockup, modern UI, phone frame|
Product packaging: product packaging design mockup, front view, studio shot|
Billboard mockup: outdoor billboard mockup, city street, daylight|
T-shirt print: t-shirt graphic design, centred print, flat colours|
Book cover: book cover design, strong title area, striking central image|
Movie poster: dramatic movie poster composition, title space at bottom|
Album cover: square album cover art, bold concept, minimal text area|
Wallpaper pattern: seamless repeating pattern, balanced motifs|
''')]
STYLES = [(a.strip(), b.strip()) for a, b in STYLES]

IMAGE_SUBJECTS = L('''
a cozy reading nook by a rainy window|a red vintage car on a coastal road|a lighthouse on a stormy cliff|a bustling night market with lanterns|
a minimalist home office with plants|a cup of masala chai with steam rising|a fox sleeping in a snowy forest|a futuristic city skyline at dusk|
a mountain cabin at sunrise|a koi pond with lotus flowers|an astronaut floating above Earth|a busy Mumbai local train platform|
a quiet library with tall shelves|a bowl of ramen with soft-boiled egg|a hot air balloon over a valley|a tiger walking through tall grass|
a robot barista making coffee|a treehouse village in giant trees|a desert caravan at golden hour|a chessboard mid-game with dramatic light|
a cat wearing a tiny wizard hat|a wedding mandap decorated with marigolds|a mountain lake reflecting peaks|a skateboarder mid-jump at a skatepark|
a flower shop storefront in spring|a medieval castle on a hill|an underwater coral reef with turtles|a farmer harvesting rice terraces|
a pair of running shoes on a track|a laptop on a wooden desk with notes|a perfume bottle on wet black stone|a smartwatch on a wrist outdoors|
a ceramic coffee mug on a linen cloth|a skincare serum bottle with water droplets|a pair of wireless earbuds in a case|a handmade leather wallet|
a stack of pancakes with berries|a chocolate cake slice on a plate|a bowl of fresh fruit salad|a plate of biryani with raita|
a street food cart selling pani puri|a pizza fresh out of a wood-fired oven|a sushi platter on slate|an ice cream cone melting in summer|
a portrait of an elderly fisherman|a portrait of a young software developer|a portrait of a classical dancer|a portrait of a chef in a kitchen|
a portrait of a farmer in a field|a portrait of a student with books|a portrait of a mountaineer with goggles|a portrait of a musician with a guitar|
a portrait of a doctor in a clinic|a portrait of a grandmother smiling|a portrait of a teenager skateboarding|a portrait of an artist in her studio|
a family picnic in a park|friends laughing around a campfire|a child flying a kite on a beach|a couple walking in autumn leaves|
a yoga class at sunrise on a terrace|a marathon finish line moment|a classroom of curious kids|a team brainstorming at a whiteboard|
a startup office with standing desks|a modern kitchen with marble island|a Scandinavian living room|a bedroom with fairy lights|
a rooftop garden in a city|a bathroom with a freestanding tub|a cafe interior with hanging plants|a boutique hotel lobby|
a Himalayan monastery in the clouds|the backwaters of Kerala with a houseboat|a Rajasthan fort at sunset|a tea plantation in Munnar|
the Taj Mahal at dawn|Varanasi ghats with evening lamps|a Goa beach shack at sunset|a snowy road in Ladakh|
the streets of Tokyo in the rain|Santorini white houses and blue domes|the Northern Lights over Iceland|a Venice canal with gondolas|
a New York street at night|a Paris cafe on a corner|a Swiss village in the Alps|the Sahara dunes under stars|
a dragon perched on a mountain|a fairy village in mushrooms|a wizard's tower in a storm|a floating island with waterfalls|
a steampunk airship over clouds|a cyberpunk alley with vending machines|an alien jungle planet|a space station orbiting a gas giant|
a robot and a child holding hands|a giant whale flying through clouds|a portal opening in a forest|a library inside a tree|
a vintage typewriter with paper|an old camera on a map|a stack of books with glasses|a globe on a wooden desk|
a bicycle leaning on a colourful wall|a vespa in a narrow street|a sailboat on calm sea|a steam train crossing a bridge|
a golden retriever puppy in a field|a parrot on a branch|a horse galloping on a beach|an owl in moonlight|
a butterfly on a sunflower|a hummingbird near a flower|a peacock with open feathers|an elephant family at a waterhole|
a sunflower field at sunset|a cherry blossom street|a bamboo forest path|a waterfall in a rainforest|
a thunderstorm over the ocean|a misty pine forest|a meadow full of wildflowers|a frozen lake with ice skaters|
a solar farm in the countryside|a wind turbine on a hill|an electric car charging at night|a greenhouse full of vegetables|
a doctor using a tablet with a patient|a scientist in a lab with glowing samples|a coder at night with multiple screens|a teacher at a chalkboard|
a delivery rider in the rain|a potter shaping clay on a wheel|a baker kneading dough at dawn|a tailor measuring fabric|
a Diwali night with diyas and rangoli|a Holi celebration with colour powder|a Christmas market with lights|an Eid table with festive food|
a birthday party table with balloons|a graduation day with caps in the air|a cricket match in a stadium|a football match under floodlights|
an empty theatre stage with spotlight|a jazz band in a small club|a DJ at a beach festival|a violinist in a concert hall|
a logo for a coffee brand called [BRAND]|a mascot for a coding school|an app icon for a meditation app|a poster for a tech meetup|
a book cover for a mystery novel|an album cover for an indie band|a t-shirt design about chai and code|a sticker of a happy cloud|
a futuristic sneaker concept|a modular sofa concept|a smart home speaker concept|a concept electric motorcycle|
a sleeping baby in a crib|a toddler painting with fingers|a grandfather reading a newspaper|a girl doing a science experiment|
''')

# ── Social media niches ────────────────────────────────────────────────────
NICHES = L('''
bakery|café|cloud kitchen|restaurant|food truck|home baker|sweet shop|juice bar|tea brand|organic grocery store|
gym|yoga studio|personal trainer|dance academy|martial arts school|sports academy|physiotherapy clinic|nutritionist|dietitian|swimming school|
beauty salon|barbershop|nail studio|makeup artist|skincare brand|bridal studio|spa|tattoo studio|perfume brand|haircare brand|
clothing boutique|ethnic wear brand|streetwear brand|jewellery store|footwear brand|handbag brand|eyewear store|kids clothing store|saree store|sustainable fashion brand|
dental clinic|eye clinic|skin clinic|pediatric clinic|diagnostic lab|pharmacy|veterinary clinic|mental health counsellor|ayurveda clinic|homeopathy clinic|
real estate agent|interior designer|architect|home builder|furniture store|home decor brand|plant nursery|landscaping company|cleaning service|pest control service|
coaching institute|online tutor|music teacher|language school|coding bootcamp|preschool|art school|exam prep academy|career counsellor|study abroad consultant|
travel agency|homestay|boutique hotel|tour guide|adventure sports company|camping site|wedding planner|event management company|photographer|videographer|
SaaS startup|mobile app startup|web design agency|digital marketing agency|IT services company|cybersecurity firm|AI startup|e-commerce store|dropshipping store|print-on-demand store|
car dealership|bike showroom|car wash|auto repair garage|EV charging business|driving school|electronics store|mobile repair shop|computer store|gaming cafe|
chartered accountant|lawyer|insurance agent|financial advisor|stock market educator|loan agent|tax consultant|business coach|HR consultancy|recruitment agency|
pet store|dog trainer|pet groomer|pet food brand|aquarium shop|
bookstore|stationery store|gift shop|toy store|florist|handicraft brand|candle brand|soap brand|art gallery|music store|
NGO|animal shelter|community library|environmental group|blood donation drive|
fitness creator|food blogger|travel creator|tech reviewer|finance creator|fashion influencer|beauty creator|parenting creator|productivity creator|gaming streamer|
motivational speaker|author|poet|podcaster|stand-up comedian|musician|singer|actor|visual artist|illustrator|
software engineer personal brand|data scientist personal brand|UX designer personal brand|product manager personal brand|startup founder personal brand|freelance writer|freelance developer|virtual assistant|social media manager|career coach|
farm-to-table brand|dairy farm|organic farm|honey brand|spice brand|coffee roaster|chocolate brand|bakery supplies store|meal-prep service|catering service|
co-working space|hostel|PG accommodation|laundry service|tailoring shop|courier service|packers and movers|security agency|solar panel installer|water purifier dealer|
hardware store|paint store|electrical contractor|plumber|carpenter|CCTV installer|AC repair service|appliance store|kitchen appliance brand|mattress brand|
temple trust|church community|school|college|university department|alumni association|hackathon organiser|tech community|startup incubator|science museum|
yoga teacher|meditation app|sleep coach|life coach|relationship coach|astrology-free wellness brand|running club|cycling club|trekking group|chess academy|
handmade jewellery maker|resin art studio|pottery studio|calligraphy artist|crochet business|embroidery business|woodworking shop|3D printing service|drone services company|robotics school|
local newspaper|radio station|YouTube education channel|online magazine|newsletter publisher|
logistics company|manufacturing company|textile exporter|import-export business|wholesale distributor|
''')

PLATFORMS = [
    ('Instagram', 'Reels, carousels and stories; the first line of the caption must hook; 3–5 relevant hashtags'),
    ('LinkedIn', 'text posts, PDF carousels and short videos; only the first 2–3 lines show before "see more"'),
    ('X (Twitter)', 'posts up to 280 characters on standard accounts, threads, images and short video'),
    ('Facebook', 'posts, Reels, groups and events; community and local audiences respond well'),
    ('Pinterest', 'vertical 2:3 pins with a keyword-rich title (up to 100 characters) and description (up to 500 characters)'),
    ('Threads', 'conversational posts up to 500 characters, images and short video'),
    ('WhatsApp Business', 'status updates, broadcast lists (opted-in contacts only), catalogue and quick replies'),
    ('YouTube Shorts', 'vertical short videos; the first 2 seconds decide whether people keep watching'),
]

# ── Industries (business, marketing, sales, support) ───────────────────────
INDUSTRIES = L('''
bakery|café|restaurant|cloud kitchen|food truck|catering company|juice bar|ice cream parlour|sweet shop|tea room|
coffee roaster|craft brewery-free beverage brand|organic grocery|supermarket|convenience store|fruit and vegetable shop|meat-free protein brand|spice brand|packaged snacks brand|dairy brand|
gym|yoga studio|pilates studio|CrossFit box|dance studio|martial arts school|swimming academy|sports coaching academy|personal training business|fitness app|
beauty salon|barbershop|nail salon|spa|skincare brand|cosmetics brand|haircare brand|perfume brand|bridal makeup studio|tattoo studio|
clothing boutique|ethnic wear store|streetwear brand|kids clothing brand|lingerie brand|sportswear brand|footwear store|jewellery store|watch store|eyewear store|
dental clinic|eye hospital|dermatology clinic|physiotherapy clinic|diagnostic centre|pharmacy|veterinary clinic|mental health practice|ayurveda wellness centre|home healthcare service|
real estate agency|property developer|interior design studio|architecture firm|furniture store|home decor store|kitchen design company|modular furniture brand|mattress brand|home cleaning service|
coaching institute|online course creator|language school|coding bootcamp|preschool|music school|art academy|test prep company|tuition centre|edtech app|
travel agency|tour operator|homestay|boutique hotel|resort|hostel|adventure tourism company|camping business|cruise booking agency|visa consultancy|
wedding planner|event management company|photography studio|videography company|DJ service|party supplies store|decor rental business|banquet hall|conference venue|entertainment company|
SaaS startup|mobile app company|web development agency|digital marketing agency|SEO agency|IT consulting firm|cybersecurity company|AI startup|data analytics consultancy|cloud services reseller|
e-commerce store|dropshipping business|print-on-demand brand|subscription box company|online marketplace|D2C brand|Amazon seller business|Etsy shop|Shopify store|social commerce brand|
car dealership|used car marketplace|two-wheeler showroom|auto repair garage|car wash and detailing|EV charging network|driving school|car rental company|bike rental startup|tyre shop|
electronics store|mobile phone store|mobile repair shop|computer repair shop|gaming store|smart home installer|CCTV and security installer|solar energy company|inverter and battery dealer|appliance repair service|
accounting firm|law firm|insurance agency|financial planning firm|wealth management firm|tax consultancy|payroll services company|business consulting firm|HR consultancy|recruitment agency|
pet store|pet grooming salon|dog training business|pet food brand|pet boarding service|aquarium store|
bookstore|stationery shop|gift shop|toy store|florist|handicrafts business|candle brand|handmade soap brand|art supplies store|musical instrument store|
NGO|animal welfare organisation|environmental nonprofit|education nonprofit|community health nonprofit|
co-working space|serviced office provider|laundry service|dry cleaning business|tailoring shop|courier company|packers and movers|logistics startup|warehousing company|freight forwarder|
construction company|hardware store|paint dealer|plumbing service|electrical contractor|carpentry business|pest control company|landscaping business|swimming pool maintenance company|roofing company|
manufacturing unit|textile mill|garment exporter|import-export firm|wholesale distributor|packaging company|printing press|signage company|furniture manufacturer|ceramics manufacturer|
private school|college|university|skill development centre|corporate training company|library|museum|science centre|zoo|amusement park|
hospital|nursing home|elder care service|childcare centre|blood bank|medical equipment supplier|telemedicine startup|fitness wearable brand|health insurance broker|wellness retreat|
farm|dairy farm|poultry-free organic farm|agri-tech startup|seed and fertiliser shop|tractor dealer|food processing unit|cold storage business|organic fertiliser brand|plant nursery|
radio station|local newspaper|online news portal|magazine publisher|podcast network|YouTube production house|film production company|animation studio|music label|influencer agency|
gaming studio|esports organisation|board game cafe|escape room|VR arcade|bowling alley|trampoline park|cinema hall|theatre company|comedy club|
salon franchise|food franchise|education franchise|fitness franchise|retail franchise|
freelance graphic designer|freelance web developer|freelance writer|freelance photographer|freelance video editor|virtual assistant business|social media manager business|consulting solopreneur|life coaching business|career coaching business|
cybersecurity training company|cloud kitchen aggregator|recruitment platform|HR software startup|fintech startup|payments startup|lending startup|insurance tech startup|legal tech startup|proptech startup|
climate tech startup|EV startup|battery recycling company|waste management company|water purification company|air purifier brand|sustainable packaging brand|bamboo products brand|upcycled fashion brand|refill store|
3D printing service|drone services company|robotics company|IoT device maker|semiconductor design startup|electronics manufacturer|smart lock brand|home automation startup|AR/VR startup|space tech startup|
temple trust|religious charity|community centre|sports club|residents welfare association|alumni network|professional association|chamber of commerce|cooperative society|self-help group|
mobile game developer|app development studio|no-code agency|UX research agency|branding agency|PR agency|video marketing agency|influencer marketing platform|affiliate marketing business|email marketing agency|
luxury watch boutique|premium furniture brand|designer label|fine dining restaurant|luxury travel agency|yacht charter company|private aviation broker|art auction house|luxury real estate agency|concierge service|
kids activity centre|summer camp|robotics class for kids|abacus classes|chess coaching|swimming classes for kids|kids birthday party venue|children's bookstore|baby products brand|maternity wear brand|
senior living community|hearing aid clinic|mobility equipment store|pharmacy chain|medical tourism facilitator|
coding school for kids|data science institute|digital marketing institute|aviation academy|hotel management institute|nursing college|fashion design institute|film school|music production school|culinary school|
online tutoring marketplace|freelance marketplace|B2B marketplace|second-hand marketplace|rental marketplace|
solar panel manufacturer|wind energy company|green building consultancy|organic cotton brand|eco-tourism resort|
shoe repair shop|watch repair shop|key and lock shop|photocopy and print shop|internet cafe|
''')

PRODUCT_CATS = L('''
wireless earbuds|smartwatch|fitness band|laptop|gaming mouse|mechanical keyboard|USB-C hub|power bank|phone case|portable speaker|
noise-cancelling headphones|action camera|drone|smart bulb|smart plug|robot vacuum|air fryer|mixer grinder|electric kettle|induction cooktop|
coffee maker|water purifier|air purifier|ceiling fan|room heater|desk lamp|office chair|standing desk|bookshelf|bean bag|
cotton bedsheet|memory foam pillow|mattress protector|blackout curtains|bath towel set|scented candle|wall clock|photo frame|indoor plant|ceramic planter|
cookware set|non-stick pan|pressure cooker|knife set|lunch box|insulated water bottle|glass food containers|spice rack|dinner set|cutlery set|
men's t-shirt|women's kurta|denim jeans|hoodie|saree|running shoes|sneakers|sandals|formal shoes|backpack|
handbag|wallet|sunglasses|analog watch|silver earrings|gold-plated necklace|bracelet|hair clips|belt|cap|
face wash|sunscreen|moisturiser|serum|lip balm|shampoo|hair oil|beard oil|perfume|body lotion|
protein powder|green tea|organic honey|peanut butter|granola|dark chocolate|instant coffee|herbal tea|millet snacks|dry fruits pack|
yoga mat|dumbbells|resistance bands|skipping rope|cycling helmet|football|cricket bat|badminton racket|swimming goggles|trekking poles|
kids' building blocks|board game|jigsaw puzzle|remote control car|soft toy|story book set|kids' art kit|STEM robotics kit|school bag|water colour set|
notebook|fountain pen|planner|desk organiser|sticky notes|sketchbook|calculator|whiteboard|art markers|laptop sleeve|
dog food|cat litter|pet bed|pet leash|aquarium filter|bird feeder|pet shampoo|cat scratching post|dog toy|pet grooming kit|
car phone mount|dash cam|car vacuum cleaner|car seat cover|tyre inflator|bike lock|helmet|car air freshener|jump starter|car cover|
baby diapers|baby stroller|baby carrier|feeding bottle|baby lotion|crib mobile|kids' rain jacket|teething toy|baby monitor|nursing pillow|
electric toothbrush|hair dryer|trimmer|straightener|massage gun|weighing scale|thermometer|blood pressure monitor|pulse oximeter|heating pad|
gaming console|gaming headset|webcam|microphone|ring light|tripod|external SSD|Wi-Fi router|smart TV|projector|
handmade soap|incense sticks|brass diya|handloom stole|block-printed cushion cover|terracotta vase|jute bag|bamboo toothbrush|steel straw set|beeswax wraps|
electric scooter|bicycle|e-bike|camping tent|sleeping bag|travel pillow|luggage trolley|packing cubes|travel adapter|hiking backpack|
gift hamper|personalised mug|customised t-shirt|greeting card set|chocolate gift box|photo album|keychain|engraved pen|terrarium kit|DIY candle kit|
online course|e-book|Notion template|Canva template pack|Lightroom presets|stock photo bundle|icon pack|font license|website theme|mobile app subscription|
solar lantern|inverter battery|LED panel light|extension board|smart door lock|video doorbell|CCTV camera|smoke detector|tool kit|cordless drill|
guitar|keyboard piano|ukulele|tabla set|violin|drum pad|headphone amplifier|studio monitor speakers|audio interface|MIDI controller|
''')

DESTINATIONS = L('''
Jaipur|Udaipur|Jodhpur|Jaisalmer|Pushkar|Agra|Varanasi|Rishikesh|Haridwar|Manali|Shimla|Dharamshala|Kasol|Spiti Valley|Leh|Srinagar|Gulmarg|Pahalgam|Amritsar|Chandigarh|
Delhi|Mumbai|Pune|Lonavala|Mahabaleshwar|Goa|Gokarna|Hampi|Mysuru|Coorg|Bengaluru|Chikmagalur|Ooty|Kodaikanal|Munnar|Alleppey|Kochi|Varkala|Thiruvananthapuram|Kanyakumari|
Chennai|Puducherry|Mahabalipuram|Madurai|Rameswaram|Hyderabad|Visakhapatnam|Araku Valley|Kolkata|Darjeeling|Gangtok|Pelling|Shillong|Cherrapunji|Kaziranga|Tawang|Ziro|Andaman Islands|Havelock Island|Lakshadweep|
Rann of Kutch|Ahmedabad|Gir National Park|Dwarka|Somnath|Khajuraho|Orchha|Bhopal|Pachmarhi|Ranthambore|Jim Corbett|Nainital|Mussoorie|Auli|Kedarnath|Badrinath|Valley of Flowers|Bir Billing|Kasauli|Lansdowne|
Bhubaneswar|Puri|Konark|Patna|Bodh Gaya|Ranchi|Raipur|Aurangabad (Ajanta and Ellora)|Nashik|Alibaug|Tarkarli|Diu|Daman|Mount Abu|Bikaner|Ajmer|Chittorgarh|Kumbhalgarh|Bundi|Neemrana|
Tokyo|Kyoto|Osaka|Hokkaido|Seoul|Busan|Jeju Island|Beijing|Shanghai|Hong Kong|Macau|Taipei|Bangkok|Chiang Mai|Phuket|Krabi|Koh Samui|Pattaya|Hanoi|Ha Long Bay|
Ho Chi Minh City|Hoi An|Da Nang|Siem Reap|Phnom Penh|Luang Prabang|Kuala Lumpur|Langkawi|Penang|Singapore|Bali|Jakarta|Yogyakarta|Lombok|Manila|Palawan|Cebu|Boracay|Kathmandu|Pokhara|
Thimphu|Paro|Colombo|Kandy|Ella|Galle|Sigiriya|Maldives|Dhaka|Cox's Bazar|Dubai|Abu Dhabi|Muscat|Doha|Bahrain|Riyadh|AlUla|Jordan (Petra and Wadi Rum)|Istanbul|Cappadocia|
Antalya|Bodrum|Baku|Tbilisi|Yerevan|Almaty|Tashkent|Samarkand|Bukhara|Ulaanbaatar|Cairo|Luxor|Aswan|Sharm El Sheikh|Marrakech|Fes|Chefchaouen|Casablanca|Tunis|Zanzibar|
Nairobi|Masai Mara|Serengeti|Kilimanjaro|Cape Town|Johannesburg|Kruger National Park|Victoria Falls|Mauritius|Seychelles|Madagascar|Namibia|Botswana (Okavango Delta)|Kigali|Addis Ababa|Accra|Lagos|Dakar|Windhoek|Durban|
London|Edinburgh|Scottish Highlands|Lake District|Bath|Oxford|Cambridge|Cornwall|Dublin|Galway|Cliffs of Moher|Belfast|Paris|Nice|Provence|Lyon|Bordeaux|Mont Saint-Michel|Strasbourg|Chamonix|
Amsterdam|Rotterdam|Bruges|Brussels|Luxembourg|Berlin|Munich|Hamburg|Cologne|Black Forest|Bavarian Alps|Vienna|Salzburg|Hallstatt|Innsbruck|Zurich|Lucerne|Interlaken|Zermatt|Geneva|
Rome|Florence|Venice|Milan|Amalfi Coast|Cinque Terre|Lake Como|Sicily|Tuscany|Naples|Dolomites|Sardinia|Barcelona|Madrid|Seville|Granada|Valencia|Mallorca|Ibiza|Canary Islands|
Lisbon|Porto|Algarve|Madeira|Azores|Athens|Santorini|Mykonos|Crete|Rhodes|Dubrovnik|Split|Plitvice Lakes|Ljubljana|Lake Bled|Budapest|Prague|Cesky Krumlov|Krakow|Warsaw|
Copenhagen|Stockholm|Oslo|Bergen|Norwegian fjords|Lofoten Islands|Tromso|Helsinki|Lapland|Reykjavik|Iceland Ring Road|Tallinn|Riga|Vilnius|Bucharest|Transylvania|Sofia|Belgrade|Sarajevo|Kotor|
New York City|Washington DC|Boston|Chicago|Miami|Orlando|New Orleans|Nashville|Austin|San Francisco|Los Angeles|San Diego|Las Vegas|Grand Canyon|Yosemite|Yellowstone|Seattle|Portland|Hawaii (Oahu)|Maui|
Alaska|Utah national parks|Colorado Rockies|Toronto|Montreal|Quebec City|Vancouver|Banff|Jasper|Niagara Falls|Mexico City|Cancun|Tulum|Oaxaca|Guadalajara|Havana|Costa Rica|Panama City|Belize|Guatemala (Antigua)|
Rio de Janeiro|Sao Paulo|Iguazu Falls|Salvador (Brazil)|Buenos Aires|Patagonia|Mendoza|Santiago|Atacama Desert|Easter Island|Lima|Cusco and Machu Picchu|Sacred Valley|Lake Titicaca|La Paz|Uyuni Salt Flats|Quito|Galapagos Islands|Cartagena|Medellin|
Sydney|Melbourne|Great Barrier Reef|Cairns|Uluru|Perth|Tasmania|Gold Coast|Brisbane|Adelaide|Auckland|Queenstown|Rotorua|Milford Sound|Wellington|Fiji|Bora Bora|Tahiti|Samoa|Vanuatu|
Sapa|Mui Ne|Kanchanaburi|Koh Phi Phi|Koh Lanta|Borneo (Sabah)|Cameron Highlands|Ipoh|Nusa Penida|Gili Islands|Siargao|Bohol|Mount Bromo|Komodo Island|Raja Ampat|Bhaktapur|Chitwan|Lumbini|Annapurna region|Everest Base Camp region|
Meghalaya living root bridges|Majuli|Tirthan Valley|Chopta|Sandakphu|Tso Moriri|Nubra Valley|Zanskar|Kutch White Desert|Chettinad|Kumarakom|Wayanad|Kabini|Bandipur|Dandeli|Badami|Pondicherry Auroville|Yercaud|Sakleshpur|Kanha National Park|
Bandhavgarh|Pench|Tadoba|Sundarbans|Valparai|Thekkady|Vagamon|Athirappilly|Bekal|Gokarna Om Beach|Malvan|Matheran|Panchgani|Igatpuri|Saputara|Sasan Gir|Shirdi|Tirupati|Ujjain|Omkareshwar|
''')

TRIP_TYPES = [
    ('2-day weekend', '2 days'), ('3-day first-timer', '3 days'), ('5-day family', '5 days with kids'), ('budget backpacking', '4 days on a tight budget'),
    ('honeymoon', '5 days for a couple'), ('solo travel', '4 days travelling alone'), ('food lover', '3 days focused on local food'), ('adventure and nature', '4 days of outdoor activities'),
]
