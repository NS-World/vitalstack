"""More dimension lists for gen_prompts_v2.py (education, tech, careers, media, life)."""
from dims import L

# ── Study topics: subject -> (level, topics) ───────────────────────────────
STUDY = {
    'Physics': ('school and college', L('''Newton's laws of motion|friction|work, energy and power|conservation of momentum|circular motion|gravitation|simple harmonic motion|waves and sound|Doppler effect|reflection of light|
refraction and lenses|total internal reflection|optical instruments|wave optics and interference|diffraction|polarisation|electric charge and Coulomb's law|electric field and potential|capacitors|Ohm's law and resistance|
Kirchhoff's laws|magnetic effect of current|electromagnetic induction|alternating current|transformers|electromagnetic waves|photoelectric effect|dual nature of matter|Bohr's atomic model|nuclear fission and fusion|
radioactivity and half-life|semiconductors and diodes|transistors|logic gates|thermodynamics laws|heat transfer|kinetic theory of gases|fluid pressure and Pascal's law|Bernoulli's principle|surface tension|
viscosity|elasticity and Young's modulus|units and dimensions|vectors|projectile motion|relative motion|rotational motion and torque|moment of inertia|special relativity basics|quantum mechanics basics|''')),
    'Chemistry': ('school and college', L('''atomic structure|periodic table trends|chemical bonding|VSEPR theory|hybridisation|states of matter|gas laws|thermochemistry|chemical equilibrium|Le Chatelier's principle|
acids, bases and pH|buffer solutions|redox reactions|electrochemistry|chemical kinetics|surface chemistry|solutions and colligative properties|solid state|s-block elements|p-block elements|
d- and f-block elements|coordination compounds|hydrogen and its compounds|IUPAC naming|isomerism|hydrocarbons|alcohols, phenols and ethers|aldehydes and ketones|carboxylic acids|amines|
biomolecules|polymers|chemistry in everyday life|mole concept and stoichiometry|titration|organic reaction mechanisms|SN1 and SN2 reactions|aromaticity|metallurgy|environmental chemistry|''')),
    'Biology': ('school and college', L('''cell structure|cell division: mitosis and meiosis|biomolecules and enzymes|photosynthesis|cellular respiration|plant hormones|transport in plants|human digestive system|human respiratory system|circulatory system and the heart|
excretory system|nervous system|endocrine system|human reproduction|reproductive health|Mendel's laws of inheritance|molecular basis of inheritance|DNA replication|protein synthesis|evolution|
human health and disease|immunity and vaccines|microbes in human welfare|biotechnology principles|genetic engineering applications|ecosystems|biodiversity and conservation|food chains and webs|population ecology|classification of living things|
plant kingdom|animal kingdom|morphology of flowering plants|anatomy of flowering plants|locomotion and movement|chemical coordination|sexual reproduction in flowering plants|environmental issues|neurons and synapses|homeostasis|''')),
    'Mathematics': ('school and college', L('''real numbers|polynomials|linear equations in two variables|quadratic equations|arithmetic progressions|geometric progressions|triangles and similarity|coordinate geometry|trigonometric ratios|trigonometric identities|
heights and distances|circles|areas and volumes|statistics: mean, median, mode|probability basics|sets|relations and functions|complex numbers|permutations and combinations|binomial theorem|
sequences and series|straight lines|conic sections|limits|continuity and differentiability|derivatives|applications of derivatives|integrals|definite integrals|area under curves|
differential equations|vectors|three-dimensional geometry|matrices|determinants|linear programming|inverse trigonometric functions|mathematical induction|logarithms|exponents|
percentages|ratio and proportion|simple and compound interest|profit and loss|time, speed and distance|mensuration|number systems|Bayes' theorem|probability distributions|graph theory basics|''')),
    'Computer Science': ('school, college and self-learners', L('''variables and data types|loops|functions|recursion|arrays|strings|object-oriented programming|classes and objects|inheritance|polymorphism|
encapsulation|abstraction|exceptions and error handling|file handling|time complexity and Big-O|sorting algorithms|searching algorithms|stacks|queues|linked lists|
trees|binary search trees|heaps|hash tables|graphs|dynamic programming|greedy algorithms|operating system basics|processes and threads|deadlocks|
memory management|paging and virtual memory|CPU scheduling|computer networks basics|OSI model|TCP/IP|DNS|HTTP and HTTPS|IP addressing and subnetting|routing|
database basics|SQL joins|normalisation|indexing|transactions and ACID|NoSQL databases|compilers and interpreters|computer architecture|cache memory|binary and number systems|
Boolean algebra|digital logic circuits|cloud computing|virtualisation|containers|version control with Git|software development life cycle|Agile and Scrum|software testing|design patterns|
REST APIs|web browsers and rendering|HTML basics|CSS layout|JavaScript event loop|cybersecurity basics|encryption|hashing|authentication and authorisation|distributed systems|''')),
    'Artificial Intelligence': ('students and professionals', L('''what is machine learning|supervised learning|unsupervised learning|reinforcement learning|linear regression|logistic regression|decision trees|random forests|gradient boosting|support vector machines|
k-nearest neighbours|k-means clustering|principal component analysis|overfitting and regularisation|cross-validation|bias-variance trade-off|evaluation metrics|neural networks|backpropagation|activation functions|
convolutional neural networks|recurrent neural networks|transformers|attention mechanism|embeddings|large language models|tokenisation|fine-tuning|retrieval-augmented generation|prompt engineering|
AI agents|diffusion models|computer vision basics|natural language processing basics|recommendation systems|time series forecasting|feature engineering|model deployment|MLOps|AI ethics and bias|''')),
    'Data Science': ('students and professionals', L('''data cleaning|exploratory data analysis|descriptive statistics|probability for data science|hypothesis testing|p-values|confidence intervals|correlation vs causation|A/B testing|sampling methods|
data visualisation principles|pandas basics|NumPy arrays|SQL for analysis|data wrangling|outlier detection|missing value treatment|feature scaling|dimensionality reduction|time series basics|
dashboards and storytelling|Excel for analysis|Power BI basics|Tableau basics|ETL pipelines|data warehousing|big data and Spark|web scraping ethics|APIs for data|cohort analysis|''')),
    'History': ('school and competitive exams', L('''Indus Valley Civilisation|Vedic period|Mauryan Empire|Gupta Empire|Delhi Sultanate|Mughal Empire|Vijayanagara Empire|Maratha Empire|arrival of Europeans in India|Revolt of 1857|
Indian National Congress formation|partition of Bengal|non-cooperation movement|civil disobedience movement|Quit India movement|Indian independence and partition|making of the Indian Constitution|French Revolution|Industrial Revolution|American Revolution|
World War I|Russian Revolution|Great Depression|World War II|Cold War|decolonisation|rise of nationalism in Europe|Renaissance|Reformation|ancient Egypt|
ancient Greece|Roman Empire|Chinese dynasties|Ottoman Empire|history of the printing press|history of the internet|space race|fall of the Berlin Wall|United Nations formation|globalisation|''')),
    'Geography': ('school and competitive exams', L('''solar system|Earth's structure|latitudes and longitudes|plate tectonics|earthquakes and volcanoes|weathering and erosion|rocks and minerals|atmosphere layers|weather vs climate|monsoon in India|
climate change|ocean currents|tides|rivers and drainage systems|soil types|natural vegetation|wildlife and national parks|agriculture types|irrigation|mineral resources|
energy resources|industries and location factors|transport and communication|population distribution|urbanisation|migration|map reading|physical features of India|Himalayas|disaster management|''')),
    'Economics': ('school, college and exams', L('''demand and supply|elasticity of demand|consumer equilibrium|production function|cost curves|market structures|perfect competition|monopoly|national income|GDP and GNP|
inflation|money and banking|central bank and monetary policy|fiscal policy|budget and deficits|balance of payments|exchange rates|poverty and unemployment|economic growth vs development|planning in India|
liberalisation, privatisation and globalisation|GST|international trade|opportunity cost|game theory basics|behavioural economics|Keynesian economics|taxation|public goods|market failure|''')),
    'Political Science': ('school and exams', L('''Preamble of the Indian Constitution|fundamental rights|directive principles|fundamental duties|Parliament|President and Prime Minister|judiciary and Supreme Court|federalism|local self-government|elections and Election Commission|
political parties|separation of powers|democracy|secularism|human rights|United Nations|international relations|foreign policy of India|constitutional amendments|emergency provisions|''')),
    'Accountancy': ('commerce students', L('''accounting principles|double-entry system|journal entries|ledger|trial balance|bank reconciliation statement|depreciation|bills of exchange|rectification of errors|final accounts|
partnership accounts|goodwill|admission of a partner|retirement of a partner|company accounts|issue of shares|debentures|cash flow statement|ratio analysis|financial statement analysis|''')),
    'Business Studies': ('commerce students', L('''nature of management|principles of management|business environment|planning|organising|staffing|directing|controlling|financial management|financial markets|
marketing management|marketing mix|consumer protection|entrepreneurship|forms of business organisation|private, public and global enterprises|business services|e-business|social responsibility of business|international business|''')),
    'English': ('students and learners', L('''tenses|subject-verb agreement|active and passive voice|direct and indirect speech|articles|prepositions|conjunctions|modal verbs|conditionals|phrasal verbs|
idioms|punctuation|sentence types|clauses|paragraph writing|essay writing|formal letter writing|email writing|report writing|story writing|
reading comprehension|summarising|figures of speech|poetry analysis|vocabulary building|common grammar mistakes|pronunciation basics|public speaking|debate skills|note making|''')),
    'Statistics': ('students and analysts', L('''measures of central tendency|measures of dispersion|standard deviation|normal distribution|binomial distribution|Poisson distribution|z-scores|central limit theorem|sampling distributions|t-test|
chi-square test|ANOVA|correlation|simple linear regression|multiple regression|non-parametric tests|Bayesian statistics basics|index numbers|time series components|skewness and kurtosis|''')),
    'Environmental Science': ('students', L('''ecosystem services|biodiversity hotspots|air pollution|water pollution|soil pollution|noise pollution|solid waste management|renewable energy|greenhouse effect|ozone depletion|
deforestation|sustainable development|water conservation|rainwater harvesting|environmental laws in India|carbon footprint|plastic pollution|wildlife conservation|disaster risk reduction|circular economy|''')),
    'Psychology': ('students and curious learners', L('''memory|learning and conditioning|motivation|emotions|personality theories|intelligence|perception|attention|cognitive biases|stress and coping|
child development|adolescence|social influence|attitudes|group behaviour|psychological disorders basics|therapy approaches|sleep and dreams|habits|positive psychology|''')),
    'Sociology': ('students', L('''socialisation|social stratification|caste and class|family and kinship|religion and society|social change|urbanisation and society|gender and society|media and society|globalisation and culture|''')),
    'Astronomy': ('curious learners', L('''stars and their life cycle|black holes|galaxies|the Big Bang|exoplanets|the Moon and its phases|eclipses|comets and asteroids|telescopes|constellations|''')),
    'Electronics': ('engineering students', L('''Ohm's and Kirchhoff's laws in circuits|diodes and rectifiers|BJTs|MOSFETs|op-amps|filters|oscillators|digital counters|flip-flops|microcontrollers basics|
Arduino basics|sensors and actuators|PCB design basics|signals and systems|Fourier transform|communication systems basics|modulation|embedded C basics|power electronics basics|IoT basics|''')),
    'Mechanical Engineering': ('engineering students', L('''laws of thermodynamics in engines|refrigeration cycles|fluid mechanics|strength of materials|stress and strain|beams and bending|gears|kinematics of machines|manufacturing processes|CNC basics|
engineering drawing|heat exchangers|internal combustion engines|material science basics|CAD basics|''')),
    'Civil Engineering': ('engineering students', L('''building materials|concrete technology|surveying|soil mechanics|structural analysis|reinforced concrete design|transportation engineering|water supply engineering|environmental engineering|construction management|''')),
    'Electrical Engineering': ('engineering students', L('''circuit theorems|three-phase systems|DC machines|induction motors|transformers in power systems|power generation|transmission and distribution|protection systems|control systems basics|electrical safety|''')),
    'Finance': ('students and professionals', L('''time value of money|compound interest|NPV and IRR|stocks and shares|bonds|mutual funds|index funds|diversification|risk and return|financial ratios|
budgeting|inflation and investing|credit scores|insurance basics|retirement planning|taxes basics|startup funding stages|valuation basics|options basics|cryptocurrency risks|''')),
}

STUDY_TASKS_EXTRA_LEVELS = ['beginner', 'exam-focused', 'advanced']

# ── Programming languages with accurate tooling ────────────────────────────
LANGS = [
    # name, test, doc, lint, pkg, general-purpose?
    ('Python', 'pytest', 'Google-style docstrings', 'ruff and black', 'pip / uv', True),
    ('Java', 'JUnit 5 with Mockito', 'Javadoc', 'Checkstyle and SpotBugs', 'Maven or Gradle', True),
    ('JavaScript', 'Jest or Vitest', 'JSDoc', 'ESLint and Prettier', 'npm', True),
    ('TypeScript', 'Vitest', 'TSDoc', 'typescript-eslint and Prettier', 'npm / pnpm', True),
    ('C++', 'GoogleTest', 'Doxygen', 'clang-tidy and clang-format', 'CMake with vcpkg or Conan', True),
    ('C', 'Unity or CMocka', 'Doxygen', 'clang-tidy and cppcheck', 'Make or CMake', True),
    ('C#', 'xUnit with Moq', 'XML doc comments', '.NET analyzers and dotnet format', 'NuGet / dotnet CLI', True),
    ('Go', 'the testing package with table-driven tests', 'Go doc comments', 'gofmt, go vet and staticcheck', 'Go modules', True),
    ('Rust', 'cargo test', 'rustdoc /// comments', 'clippy and rustfmt', 'Cargo', True),
    ('Kotlin', 'JUnit 5 with MockK', 'KDoc', 'ktlint and detekt', 'Gradle (Kotlin DSL)', True),
    ('Swift', 'XCTest or Swift Testing', 'DocC /// comments', 'SwiftLint', 'Swift Package Manager', True),
    ('PHP', 'PHPUnit', 'PHPDoc', 'PHPStan and PHP_CodeSniffer', 'Composer', True),
    ('Ruby', 'RSpec', 'YARD', 'RuboCop', 'Bundler', True),
    ('Dart', 'package:test (flutter_test for Flutter)', 'Dart doc comments', 'dart analyze and dart format', 'pub', True),
    ('Scala', 'ScalaTest or MUnit', 'Scaladoc', 'Scalafmt and Scalafix', 'sbt', True),
    ('R', 'testthat', 'roxygen2', 'lintr and styler', 'CRAN / renv', True),
    ('Julia', 'the Test standard library', 'docstrings with Documenter.jl', 'JuliaFormatter', 'Pkg', True),
    ('Elixir', 'ExUnit', '@doc and @moduledoc', 'Credo and mix format', 'Mix / Hex', True),
    ('Haskell', 'Hspec or QuickCheck', 'Haddock', 'HLint and Ormolu', 'Cabal or Stack', True),
    ('Lua', 'busted', 'LDoc', 'luacheck and StyLua', 'LuaRocks', True),
    ('Perl', 'Test::More', 'POD', 'Perl::Critic and Perl::Tidy', 'CPAN', True),
    ('Objective-C', 'XCTest', 'HeaderDoc / Doxygen-style comments', 'clang-format and OCLint', 'CocoaPods or SPM', True),
    ('F#', 'Expecto or xUnit', 'XML doc comments', 'Fantomas and FSharpLint', 'NuGet / dotnet CLI', True),
    ('Clojure', 'clojure.test', 'docstrings', 'clj-kondo and cljfmt', 'Leiningen or deps.edn', True),
    ('Bash', 'bats-core', 'header comments', 'ShellCheck and shfmt', 'system packages', False),
    ('PowerShell', 'Pester', 'comment-based help', 'PSScriptAnalyzer', 'PowerShell Gallery', False),
    ('SQL', 'tSQLt or pgTAP', 'inline comments', 'SQLFluff', 'migration tools such as Flyway', False),
    ('Solidity', 'Foundry (forge test) or Hardhat', 'NatSpec comments', 'solhint and Slither', 'Foundry or npm', False),
    ('MATLAB', 'the matlab.unittest framework', 'help-text comments', 'the Code Analyzer', 'Add-On Explorer', False),
    ('Zig', 'built-in test blocks (zig test)', '/// doc comments', 'zig fmt', 'Zig build system', True),
]

CODE_TASKS = L('''explain this code line by line|write unit tests|refactor for readability|debug an error|review code for bugs|add error handling|add logging|
improve performance|write documentation comments|add input validation|convert callbacks or loops to idiomatic style|write a command-line tool|write a REST API client|
parse and validate JSON|read and write CSV files|work with dates and time zones|write a regular expression|handle concurrency safely|write a file watcher script|
create a configuration loader|write a retry with exponential backoff|cache expensive results|paginate API results|write database queries safely|
secure this code against common attacks|add type annotations|split a large function|write integration tests|mock external services in tests|
set up a new project structure|migrate code to the latest version|write a Dockerfile for an app|write a CI workflow|profile memory usage|
write a web scraper (respecting robots.txt)|send emails from code|schedule a recurring job|build a simple game loop|write a rate limiter|implement authentication flow''')

DATA_STRUCTS = L('''dynamic array|singly linked list|doubly linked list|stack|queue|circular buffer|deque|hash map|hash set|binary search tree|
AVL tree|red-black tree (overview + insert)|min-heap|priority queue|trie|graph (adjacency list)|union-find|LRU cache|LFU cache|segment tree|
Fenwick tree|bloom filter|skip list|matrix class|sparse matrix''')

ALGORITHMS = L('''binary search|bubble sort|insertion sort|merge sort|quick sort|heap sort|counting sort|radix sort|two-sum with a hash map|sliding window maximum|
Kadane's maximum subarray|longest common subsequence|edit distance|0/1 knapsack|coin change|longest increasing subsequence|BFS on a grid|DFS with recursion|topological sort|Dijkstra's shortest path|
Bellman-Ford|Floyd-Warshall|Prim's minimum spanning tree|Kruskal's minimum spanning tree|cycle detection in a graph|N-Queens backtracking|Sudoku solver|permutations generator|subsets generator|string reversal and palindrome check|
anagram grouping|KMP string search|Rabin-Karp|matrix multiplication|fast exponentiation|sieve of Eratosthenes|GCD and LCM|reservoir sampling|Fisher-Yates shuffle|flood fill''')

PATTERNS = L('''Singleton|Factory Method|Abstract Factory|Builder|Prototype|Adapter|Decorator|Facade|Proxy|Composite|
Observer|Strategy|Command|State|Template Method|Iterator|Chain of Responsibility|Mediator|Repository|Dependency Injection''')

FRAMEWORKS = L('''React|Next.js|Vue|Nuxt|Angular|Svelte|SvelteKit|Astro|Remix|Solid|
Tailwind CSS|Bootstrap|Node.js with Express|NestJS|Fastify|Django|Flask|FastAPI|Spring Boot|Quarkus|
Laravel|Symfony|Ruby on Rails|ASP.NET Core|Phoenix|Flutter|React Native|SwiftUI|Jetpack Compose|Electron|
pandas|NumPy|scikit-learn|PyTorch|TensorFlow|Keras|Hugging Face Transformers|LangChain|LlamaIndex|OpenCV|
PostgreSQL|MySQL|MongoDB|Redis|SQLite|Elasticsearch|Prisma|SQLAlchemy|GraphQL|gRPC|
Docker|Kubernetes|Terraform|Ansible|GitHub Actions|Kafka|RabbitMQ|Supabase|Firebase|WordPress''')

FRAMEWORK_TASKS = L('''beginner project setup|explain the core concepts|build a CRUD feature|add authentication|structure a large project|write tests|optimise performance|
handle errors properly|deploy to production|secure the app|migrate to the latest version|debug a common error|state or data management|
build a dashboard page|integrate a third-party API|add caching|add pagination and filtering|write a cheat sheet|interview questions with answers|
code review checklist|best practices list|build a real-time feature|add file uploads safely|internationalisation|accessibility checklist|
logging and monitoring|environment configuration|database migrations|background jobs|30-day learning plan''')

ROLES = L('''Software Engineer|Senior Software Engineer|Frontend Developer|Backend Developer|Full-Stack Developer|Java Developer|Python Developer|JavaScript Developer|React Developer|Angular Developer|
Node.js Developer|.NET Developer|PHP Developer|Go Developer|Rust Developer|Mobile App Developer|Android Developer|iOS Developer|Flutter Developer|Game Developer|
Embedded Systems Engineer|Firmware Engineer|DevOps Engineer|Site Reliability Engineer|Cloud Engineer|Cloud Architect|Solutions Architect|Platform Engineer|Network Engineer|System Administrator|
Database Administrator|Data Analyst|Business Intelligence Analyst|Data Scientist|Data Engineer|Analytics Engineer|Machine Learning Engineer|AI Engineer|Prompt Engineer|Computer Vision Engineer|
NLP Engineer|MLOps Engineer|Research Scientist|QA Engineer|QA Automation Engineer|SDET|Performance Test Engineer|Security Engineer|Cybersecurity Analyst|Penetration Tester|
SOC Analyst|Cloud Security Engineer|Blockchain Developer|Technical Writer|Developer Advocate|Engineering Manager|Tech Lead|CTO|Scrum Master|Agile Coach|
Product Manager|Associate Product Manager|Product Owner|Program Manager|Project Manager|Business Analyst|Systems Analyst|Salesforce Developer|SAP Consultant|ServiceNow Developer|
UI/UX Designer|Product Designer|UX Researcher|Graphic Designer|Motion Designer|Video Editor|3D Artist|Illustrator|Web Designer|Brand Designer|
Digital Marketer|SEO Specialist|Performance Marketer|Social Media Manager|Content Writer|Copywriter|Content Strategist|Email Marketing Specialist|Growth Marketer|Marketing Manager|
Sales Executive|Business Development Executive|Account Manager|Inside Sales Representative|Sales Manager|Customer Success Manager|Customer Support Executive|Technical Support Engineer|Pre-sales Consultant|Relationship Manager|
HR Executive|Talent Acquisition Specialist|HR Business Partner|Payroll Specialist|Learning and Development Manager|Office Administrator|Executive Assistant|Operations Manager|Supply Chain Analyst|Logistics Coordinator|
Accountant|Chartered Accountant|Financial Analyst|Investment Analyst|Auditor|Tax Consultant|Credit Analyst|Risk Analyst|Actuarial Analyst|Bank Clerk|
Teacher|Lecturer|Online Tutor|Instructional Designer|School Counsellor|Librarian|Research Assistant|Lab Technician|Academic Coordinator|Principal|
Doctor|Nurse|Pharmacist|Physiotherapist|Medical Representative|Dietitian|Psychologist|Lab Scientist|Healthcare Administrator|Medical Coder|
Civil Engineer|Mechanical Engineer|Electrical Engineer|Electronics Engineer|Chemical Engineer|Production Engineer|Quality Engineer|Safety Officer|Site Engineer|Architect|
Interior Designer|Fashion Designer|Photographer|Journalist|Editor|Public Relations Executive|Event Manager|Hotel Manager|Chef|Travel Consultant|
Lawyer|Legal Associate|Company Secretary|Compliance Officer|Paralegal|Police Aspirant|Civil Services Aspirant|Defence Aspirant|Bank PO Aspirant|Entrepreneur|
Freelance Developer|Freelance Designer|Freelance Writer|Virtual Assistant|Data Entry Operator|Call Centre Executive|Retail Store Manager|Delivery Operations Lead|Fresher (any field)|Intern (any field)''')

ROLE_TASKS = L('''mock interview|resume bullet points|cover letter|LinkedIn headline and About|30-60-90 day plan|salary negotiation script|career switch plan|
skills roadmap for one year|portfolio project ideas|top interview questions with answers|STAR stories for behavioural rounds|thank-you email after interview''')

INTERVIEW_TOPICS = L('''Java core|Java collections|Java multithreading|Spring Boot|Hibernate|Microservices|Python core|Django|Flask|FastAPI|
JavaScript fundamentals|JavaScript async|TypeScript|React|Redux|Next.js|Angular|Vue|Node.js|Express|
HTML and CSS|web performance|web security|REST API design|GraphQL|system design basics|system design advanced|SQL|PostgreSQL|MySQL|
MongoDB|Redis|Kafka|Docker|Kubernetes|AWS|Azure|Google Cloud|Terraform|Linux|
Git|CI/CD|DevOps practices|SRE practices|networking|operating systems|DBMS|OOP concepts|data structures|algorithms|
dynamic programming|graphs|C language|C++|C#|.NET|Go|Rust|Kotlin|Swift|
Android|iOS|Flutter|React Native|machine learning|deep learning|NLP|computer vision|LLMs and prompt engineering|data analysis|
statistics|Excel|Power BI|Tableau|pandas|Spark|Airflow|data warehousing|ETL|Selenium|
Playwright|API testing|manual testing|performance testing|cybersecurity|ethical hacking concepts|cloud security|cryptography|blockchain|product management|
UX design|digital marketing|SEO|content writing|sales|customer success|HR|accounting|finance|project management''')

VIDEO_SHOTS = L('''slow dolly-in reveal|orbit around the subject|drone flyover|tracking shot alongside|extreme macro detail|time-lapse sky|whip-pan transition|top-down flat lay|rack focus|slow-motion pour|
low-angle hero shot|handheld documentary|parallax still to motion|crane up establishing shot|product spin on a turntable|push through a doorway|night bokeh lateral move|underwater glide|split-screen before and after|first-person POV|
stop-motion style|seasons transition|floating dust in a light beam|logo reveal from abstract shapes|dolly zoom|bullet-time freeze|match cut|silhouette at sunset|reflection shot|rain on glass close-up|
fog rolling over hills|city hyperlapse|steady close-up portrait|over-the-shoulder shot|bird's-eye spiral down|slow pan across a landscape|light painting|neon flicker|candlelight close-up|wave crashing in slow motion''')

VIDEO_SUBJECTS = L('''a cup of coffee on a wooden table|a runner on a mountain trail|a city street at night|a new smartphone|a bowl of steaming noodles|a lighthouse by the sea|a dancer in a studio|a robot assembling parts|a field of sunflowers|a classroom of students|
a skincare bottle on marble|a chef plating a dish|a car on a winding road|a forest waterfall|a yoga pose at sunrise|a laptop with code on screen|a bakery at dawn|a desert road|a snowy mountain peak|a beach at sunset|
a startup team meeting|a rocket launch|a farmer in a green field|a bookshop interior|a market full of spices|a wedding celebration|a concert crowd|a cyclist in the rain|a cat on a windowsill|a puppy playing in grass|
a smartwatch on a wrist|a pair of sneakers|a perfume bottle|a glass of juice|a plate of desserts|a modern living room|an office building|a train through the hills|a hot air balloon|an old fort|
a temple at dawn|a boat on a calm lake|fireworks over a city|a Diwali street with lights|a cricket ball hitting a bat|a football being kicked|a scientist in a lab|a doctor with a patient|a child drawing|a grandmother cooking''')

GENRES = L('''lo-fi hip hop|synthwave|acoustic folk|EDM festival anthem|Bollywood romantic|Punjabi bhangra pop|cinematic orchestral|jazz café|indie pop|trap|
classical piano|reggae|rock anthem|ambient meditation|kids' song|Sufi-inspired devotional|K-pop dance|country|afrobeats|podcast intro jingle|
deep house|techno|drum and bass|dubstep|future bass|chillhop|bossa nova|blues|funk|disco|
soul|R&B|gospel choir|metal|punk rock|grunge|shoegaze|dream pop|city pop|J-pop|
Carnatic fusion|Hindustani classical fusion|ghazal|qawwali-inspired|Tamil folk|Bengali folk|Rajasthani folk|Marathi lavani-inspired|Telugu mass beat|Malayalam melody|
Latin pop|reggaeton|salsa|flamenco|tango|Celtic folk|sea shanty|bluegrass|Americana|singer-songwriter|
epic trailer music|video game chiptune|orchestral fantasy|horror ambience|documentary score|corporate upbeat|motivational workout|study focus music|sleep music|meditation flute|
children's lullaby|birthday song|wedding song|patriotic anthem|festival celebration|cricket anthem|travel vlog background|cooking show background|tech product launch music|news intro theme''')

MUSIC_THEMES = L('''first love|chasing dreams|friendship|monsoon rain|city lights|missing home|new beginnings|overcoming fear|summer road trip|late-night coding|success after struggle|celebration''')

WRITING_TASKS = L('''blog post|product description|newsletter|press release|case study|white paper outline|landing page copy|about us page|FAQ section|how-to guide|
listicle|opinion piece|book review|movie review|travel story|personal essay|short story|poem|speech|toast|
eulogy|obituary|wedding vows|birthday message|apology letter|resignation letter|recommendation letter|complaint letter|thank-you note|invitation|
meeting summary|executive summary|project proposal|grant proposal|research abstract|literature review outline|lab report|business email|cold email|follow-up email|
social media bio|YouTube script|podcast script|explainer video script|ad script|radio jingle|slogan set|brand story|mission statement|values statement|
user manual section|onboarding email|release notes|error messages|chatbot replies|SMS message|push notification copy|app store description|course description|event description|
job description|company announcement|policy summary|newsletter subject lines|headline set|meta descriptions|alt text|caption set|quote collection|interview questions''')

AUDIENCES = L('''complete beginners|students|working professionals|senior executives|small business owners|parents|teenagers|developers|non-technical customers|
international readers|Indian readers|elderly readers''')

FIT_GOALS = L('''fat loss|muscle gain|beginner strength|first 5K run|home workouts with no equipment|flexibility and mobility|back-friendly core|fitness after 40|20-minute busy-day workouts|posture for desk workers|
first 10K run|half-marathon training|pull-up progression|push-up progression|squat technique|kettlebell basics|resistance band training|swimming fitness|cycling endurance|stair-climbing fitness|
knee-friendly workouts|postnatal return to fitness (doctor-cleared)|teen sports conditioning|senior balance and strength|yoga for beginners|stretching routine for runners|HIIT for beginners|calisthenics basics|weekend warrior plan|office desk stretches''')
FIT_LEVELS = ['beginner', 'intermediate', 'advanced']
FIT_EQUIP = ['no equipment', 'dumbbells only', 'full gym']

DIETS = L('''vegetarian Indian|high-protein vegetarian|vegan|budget-friendly|eggetarian|Mediterranean-style|diabetes-friendly (doctor-approved plan first)|student hostel|calorie deficit for weight loss|balanced family|
South Indian|North Indian|Jain (no onion and garlic)|gluten-free|lactose-free|high-fibre|heart-healthy|low-sodium|high-protein non-vegetarian|pescatarian|
PCOS-friendly (doctor-guided)|sports performance|muscle gain|office lunchbox|quick 15-minute meals|travel-friendly snacks|kids' tiffin|pregnancy-safe (doctor-guided)|senior-friendly soft foods|festival season balanced''')
DIET_GOALS = ['weight loss', 'muscle gain', 'maintenance', 'more energy', 'better digestion']

CUISINES = L('''North Indian|South Indian|Bengali|Gujarati|Rajasthani|Punjabi|Kerala|Goan|Hyderabadi|Kashmiri|
Maharashtrian|Chettinad|Mughlai|Indo-Chinese|street food (Indian)|Italian|Mexican|Chinese|Japanese|Korean|
Thai|Vietnamese|Middle Eastern|Lebanese|Turkish|Greek|French|Spanish|Moroccan|Ethiopian|
American|Caribbean|Brazilian|Peruvian|Sri Lankan|Nepali|Tibetan|Burmese|Indonesian|Malaysian|
vegan world cuisine|baking and desserts|breakfast dishes|healthy salads|soups and stews|one-pot meals|air fryer recipes|pressure cooker recipes|meal prep|kids' recipes|
festival sweets|millet recipes|high-protein snacks|chutneys and pickles|tea-time snacks|smoothies and drinks|barbecue and grills|bread making|pasta dishes|rice dishes''')
COOK_TASKS = L('''weekly meal plan|beginner recipe with steps|recipe from leftovers|healthier version of a classic|party menu|shopping list|cooking techniques guide|spice guide|quick weeknight dinners|dessert ideas''')

LANGUAGES = L('''English|Hindi|Spanish|French|German|Japanese|Korean|Mandarin Chinese|Arabic|Russian|
Portuguese|Italian|Turkish|Dutch|Swedish|Bengali|Tamil|Telugu|Marathi|Gujarati|
Kannada|Malayalam|Punjabi|Urdu|Sanskrit|Thai|Vietnamese|Indonesian|Persian|Greek|
Polish|Hebrew|Swahili|Nepali|Sinhala|Tagalog|Malay|Odia|Assamese|Irish''')
LANG_TASKS = L('''30-day beginner plan|daily conversation practice|grammar explained simply|vocabulary builder by topic|pronunciation guide|travel survival phrases|business phrases|writing correction|role-play: ordering food|role-play: job interview|
listening practice script|idioms and expressions|common mistakes for English speakers|flashcards generator|reading practice story|verb conjugation drills|exam preparation plan|culture and etiquette guide|translation with explanations|weekly progress test''')

EXCEL_FUNCS = L('''XLOOKUP|VLOOKUP|HLOOKUP|INDEX and MATCH|SUMIFS|COUNTIFS|AVERAGEIFS|IF with AND/OR|IFS|IFERROR|
SWITCH|LET|LAMBDA|FILTER|SORT|SORTBY|UNIQUE|SEQUENCE|TEXTSPLIT|TEXTJOIN|
CONCAT|LEFT, RIGHT and MID|FIND and SEARCH|SUBSTITUTE|TRIM and CLEAN|PROPER, UPPER and LOWER|DATE and DATEDIF|EOMONTH|NETWORKDAYS|WORKDAY|
TODAY and NOW|WEEKDAY and WEEKNUM|ROUND, ROUNDUP and ROUNDDOWN|MOD|RANK|LARGE and SMALL|MAX/MIN with criteria|SUMPRODUCT|OFFSET|INDIRECT|
CHOOSE|HYPERLINK|PMT|FV|NPV and IRR|XIRR|FORECAST.LINEAR|TREND|STDEV|CORREL|
Pivot tables|Power Query|conditional formatting|data validation drop-downs|named ranges|charts|slicers|Goal Seek|Solver|protecting sheets|
QUERY (Google Sheets)|IMPORTRANGE (Google Sheets)|ARRAYFORMULA (Google Sheets)|GOOGLEFINANCE (Google Sheets)|REGEXEXTRACT (Google Sheets)|SPLIT (Google Sheets)|Apps Script basics|VBA macros basics|Flash Fill|Remove Duplicates''')

SQL_TASKS = L('''top N per group|running totals|month-over-month growth|find duplicates|delete duplicates safely|pivot rows to columns|unpivot columns to rows|customers with no orders|second highest salary|date range filtering|
cohort retention|funnel conversion|moving averages|percent of total|median calculation|gaps and islands|recursive hierarchy (org chart)|upsert (insert or update)|soft deletes|audit trail table|
pagination|full-text search|JSON column queries|index tuning for a slow query|explain plan reading|transactions and locking|stored procedure|view creation|window functions basics|CTEs explained''')
SQL_DIALECTS = ['PostgreSQL', 'MySQL', 'SQL Server', 'SQLite']

PANDAS_TASKS = L('''load and inspect a CSV|clean missing values|remove duplicates|group by and aggregate|merge two dataframes|pivot tables|melt wide to long|parse dates|resample time series|rolling averages|
filter rows by conditions|apply a custom function|string cleaning|categorical encoding|outlier detection|correlation matrix|value counts and percentages|binning numbers|exporting to Excel|plotting with matplotlib''')

BIZ_FUNCTIONS = L('''customer support|sales prospecting|marketing content|recruitment|employee onboarding|finance and invoicing|bookkeeping|legal document review (with a lawyer)|procurement|inventory management|
logistics tracking|IT helpdesk|software development|QA testing|data reporting|research|meeting management|email management|social media management|SEO|
e-commerce operations|real estate leads|healthcare scheduling|education tutoring|event planning|travel booking|restaurant reservations|content moderation|translation|product feedback analysis''')
AGENT_TASKS = L('''AI agent design|automation workflow (Zapier/Make/n8n)|system prompt|guardrails and escalation rules|evaluation test cases|ROI estimate|tool (function) definitions|human approval points''')

ASSISTANTS = L('''English tutor|maths tutor|coding mentor|interview coach|resume reviewer|writing editor|research assistant|travel planner|meal planner|fitness coach|
budget coach|study buddy|language exchange partner|debate partner|brainstorming partner|customer support bot for a bakery|support bot for a SaaS app|FAQ bot for a clinic|booking assistant for a salon|HR policy assistant|
IT helpdesk assistant|sales assistant for an online store|real estate enquiry bot|school admissions assistant|library assistant|recipe assistant|parenting assistant|productivity coach|meditation guide|book recommender|
movie recommender|gift advisor|wardrobe stylist|interior design advisor|gardening advisor|pet care advisor|car buying advisor|tech support for seniors|accessibility helper|legal information explainer (not advice)|
SQL helper|Excel helper|Git helper|Linux helper|regex helper|API documentation assistant|code reviewer|test writer|data analyst assistant|marketing copy assistant''')

PRODUCT_TYPES = L('''to-do app|note-taking app|habit tracker|fitness app|meditation app|food delivery app|grocery app|ride-hailing app|e-commerce site|marketplace|
online learning platform|job portal|CRM|project management tool|chat app|video calling app|banking app|investment app|budgeting app|insurance app|
healthcare booking app|telemedicine app|travel booking app|hotel booking site|real estate portal|social network|dating-free community app|news app|podcast app|music streaming app|
photo editing app|AI writing tool|AI image tool|developer tool|API platform|analytics dashboard|HR software|accounting software|inventory software|point-of-sale app|
smart home app|EV charging app|parking app|event ticketing app|restaurant reservation app|recipe app|language learning app|kids learning app|library app|government services app''')
PRODUCT_TASKS = L('''PRD|user personas|user stories|onboarding flow|pricing page structure|feature prioritisation|usability test plan|analytics tracking plan|launch checklist|UX copy|competitor teardown|north star metric''')

SITE_TYPES = L('''SaaS landing page|personal portfolio|developer portfolio|designer portfolio|photography portfolio|restaurant website|café website|bakery website|gym website|yoga studio site|
dental clinic site|hospital site|law firm site|accounting firm site|real estate site|interior design site|architecture firm site|construction company site|travel agency site|hotel website|
wedding website|event site|conference site|music band site|podcast site|blog|news site|magazine site|online course site|school website|
coaching institute site|nonprofit site|church or temple community site|e-commerce store|fashion store|jewellery store|electronics store|bookstore site|florist site|pet store site|
mobile app landing page|job board|admin dashboard|resume website|coming soon page|local business site|salon site|car dealership site|startup pitch site|product launch page''')
SITE_STACKS = ['plain HTML, CSS and JavaScript', 'React and Tailwind CSS', 'Next.js', 'WordPress block theme']

CYBER_TOPICS = L('''phishing awareness|password security|multi-factor authentication|ransomware protection|secure Wi-Fi at home|safe online banking|social engineering|data privacy basics|secure coding|OWASP Top 10|
SQL injection prevention|XSS prevention|CSRF protection|secure authentication design|session management|API security|cloud security basics|container security|Kubernetes security|CI/CD pipeline security|
secrets management|logging and monitoring|incident response|backup and recovery|vulnerability management|threat modelling|security headers|TLS and certificates|zero trust basics|endpoint protection|
email security (SPF, DKIM, DMARC)|WordPress security|mobile app security|IoT security|GDPR and DPDP basics|security awareness training|vendor risk|access control|least privilege|penetration testing scope (authorised only)|
security policies for small teams|child online safety|safe social media use|spotting fake news|UPI and payment fraud awareness|deepfake awareness|browser privacy|VPN basics|safe file sharing|device encryption|
bug bounty basics (legal)|CTF learning path|SOC basics|SIEM basics|malware analysis basics (safe labs)|digital forensics basics|security certifications roadmap|DevSecOps|supply chain security|AI security and prompt injection''')
CYBER_LEVELS = ['beginner explainer', 'practical checklist', 'team training plan']

DEVOPS_TOOLS = L('''Docker|Docker Compose|Kubernetes|Helm|Terraform|Ansible|GitHub Actions|GitLab CI|Jenkins|Argo CD|
Prometheus|Grafana|ELK stack|Nginx|Apache|Linux shell|systemd|cron|AWS EC2|AWS S3|
AWS Lambda|AWS IAM|AWS RDS|Azure App Service|Azure DevOps|Google Cloud Run|Cloudflare|Vercel|Netlify|Heroku-style PaaS|
PostgreSQL operations|MySQL operations|Redis operations|Kafka operations|RabbitMQ|Vault|SSL with Let's Encrypt|load balancers|CDN setup|DNS management''')
DEVOPS_TASKS = L('''beginner tutorial|production setup|troubleshooting guide|security hardening|cost optimisation|monitoring setup|backup plan|CI/CD integration|interview questions|cheat sheet''')

EXAMS = L('''JEE Main|JEE Advanced|NEET UG|NEET PG|UPSC CSE Prelims|UPSC CSE Mains|State PSC exams|SSC CGL|SSC CHSL|IBPS PO|
IBPS Clerk|SBI PO|RBI Grade B|RRB NTPC|CAT|XAT|CMAT|MAT|GATE (Computer Science)|GATE (Mechanical)|
GATE (Electrical)|GATE (Civil)|CUET UG|CLAT|NDA|CDS|AFCAT|CTET|UGC NET|CA Foundation|
CA Intermediate|CS Executive|CMA Inter|NIFT entrance|NID entrance|CBSE Class 10 boards|CBSE Class 12 boards|ICSE Class 10|ISC Class 12|state board Class 10|
state board Class 12|Olympiads (maths and science)|NTSE-style scholarship tests|SAT|ACT|AP exams|IELTS Academic|IELTS General|TOEFL iBT|PTE Academic|
Duolingo English Test|GRE|GMAT Focus Edition|LSAT|MCAT|USMLE Step 1 (overview)|AWS Cloud Practitioner|AWS Solutions Architect Associate|Azure Fundamentals (AZ-900)|Google Data Analytics certificate''')
EXAM_TASKS = L('''study plan|mock test in chat|weak-topic recovery plan|last 30 days revision plan|formula or fact sheet|previous-year style questions|time management strategy|exam-day checklist''')

EMAIL_SITUATIONS = L('''asking for a deadline extension|requesting leave|following up on an invoice|declining a meeting|introducing two people|asking for a referral|asking for feedback|requesting a raise|resigning politely|welcoming a new team member|
apologising for a delay|escalating an issue|confirming a meeting|rescheduling a meeting|thanking a mentor|asking a professor for a recommendation|enquiring about a job opening|following up after an interview|accepting a job offer|declining a job offer|
negotiating an offer|requesting a refund|complaining about a service|responding to a customer complaint|announcing a price change|sending a project update|requesting information from a vendor|chasing an unpaid invoice|cold outreach to a client|pitching a collaboration|
inviting someone to an event|asking for a testimonial|sharing bad news with a team|celebrating a team win|onboarding a client|offboarding a client|reminding about a payment|saying goodbye when leaving a job|asking for help politely|setting boundaries on workload|
requesting a letter of experience|asking HR about benefits|reporting a bug|requesting a feature|answering a sales enquiry|welcoming a new customer|re-engaging an inactive customer|confirming an order|apologising for a mistake in an order|requesting a meeting with a busy person''')
EMAIL_TONES = ['formal', 'friendly', 'short and direct', 'warm and empathetic', 'confident']

PERSONAL_TOPICS = L('''building confidence|public speaking fear|time management for students|beating procrastination|digital detox|better sleep habits|journaling|gratitude practice|setting boundaries|making friends in a new city|
dealing with exam stress|handling criticism|learning faster|reading more books|focus and deep work|morning routine|evening routine|goal setting|decision making|emotional intelligence|
active listening|negotiation in daily life|starting a side project|career clarity|managing money as a student|minimalism|decluttering|habit building|staying motivated|work-life balance|
mindfulness basics|handling loneliness|parenting teenagers|screen time for kids|helping kids study|family budgeting|planning a wedding on a budget|moving out for the first time|living alone|cooking for one|
first job tips|office etiquette|networking for introverts|personal branding|learning to code as a beginner|switching careers after 30|freelancing first steps|retiring well|volunteering|travelling solo safely''')
PERSONAL_TASKS = ['30-day plan', 'coach-style conversation']

FIN_STAGES = L('''college student|first job|freelancer|young couple|new parents|family with school kids|mid-career professional|small business owner|pre-retirement|retired''')
FIN_TOPICS = L('''monthly budget|emergency fund|debt payoff plan|saving goals|investing basics|insurance check|tax-saving basics (check current rules)|retirement planning|spending review|money habits''')

PROD_ROLES = ROLES[:120]
PROD_TASKS = L('''weekly plan for this role|daily routine|focus and deep-work plan|tools and shortcuts list|meeting and email system|90-day self-improvement plan''')
