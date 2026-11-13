<?php
/**
 * Module guides for premium modules (4.15.0): a short history, how the tool
 * works step by step, where to run it, and what it is used for at work.
 * Shown at the top of every premium lesson (inc/module-guides.php).
 *
 * Keys are learning-path slugs. "history" is optional (project modules use
 * the project lifecycle instead).
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

return array(

	/* ── Data Scientist ─────────────────────────────────────────────────── */

	'statistics'             => array(
		'history' => array(
			array( '1654', 'Pascal and Fermat solve a gambling puzzle in letters and found probability theory.' ),
			array( '1763', 'Thomas Bayes’s essay on updating beliefs with evidence is published (Bayes’ theorem).' ),
			array( '1809', 'Gauss uses the normal (bell) curve to describe measurement errors.' ),
			array( '1908', 'William Gosset, a brewer at Guinness, publishes the t-test as “Student”.' ),
			array( '1925', 'Ronald Fisher popularises significance tests and p-values.' ),
		),
		'flow'    => array( 'Ask a clear question', 'Collect a sample', 'Describe it: centre and spread', 'Model the uncertainty (a distribution)', 'Estimate or test: confidence interval, p-value', 'Decide, and say how sure you are' ),
		'run'     => 'Python with NumPy, pandas and SciPy, in Jupyter or Google Colab.',
		'used'    => 'A/B tests on apps and websites, surveys, quality control, medical trials, cricket and sports analytics.',
	),
	'eda'                    => array(
		'history' => array(
			array( '1977', 'John Tukey’s book “Exploratory Data Analysis” makes “look at the data first” a method, and spreads the box plot.' ),
			array( '2008', 'Wes McKinney starts pandas to analyse financial data in Python.' ),
			array( 'Early 2010s', 'Seaborn appears, making statistical charts quick to draw.' ),
		),
		'flow'    => array( 'Load the data', 'Check shape, columns and types', 'Find missing values and outliers', 'Look at each variable’s distribution', 'Look at relationships between variables', 'Fix, encode and create features', 'Data ready for a model' ),
		'run'     => 'pandas, Matplotlib and Seaborn in Jupyter or Colab.',
		'used'    => 'The first step of every data project; data scientists often spend most of their time here.',
	),
	'ml-in-depth'            => array(
		'history' => array(
			array( '1805', 'Legendre publishes least squares, the idea behind linear regression.' ),
			array( '1984', 'Breiman and colleagues describe CART decision trees.' ),
			array( '1995', 'Cortes and Vapnik publish support vector machines.' ),
			array( '2001', 'Breiman introduces random forests.' ),
			array( '2007', 'scikit-learn starts as a Google Summer of Code project.' ),
			array( '2014', 'XGBoost appears and soon wins many machine learning competitions.' ),
		),
		'flow'    => array( 'Prepare features and target', 'Split into train and test', 'Choose a model', 'Fit on training data', 'Evaluate with cross-validation', 'Tune hyper-parameters', 'Explain the predictions', 'Use it on new data' ),
		'run'     => 'scikit-learn (plus XGBoost or LightGBM) in Jupyter or Colab.',
		'used'    => 'Loan approval, churn prediction, price estimation, fraud detection, recommendations.',
	),
	'mongodb'                => array(
		'history' => array(
			array( '2001', 'JSON becomes a popular way to send data between programs.' ),
			array( '2009', 'MongoDB is released as open source, storing JSON-like documents.' ),
			array( '2016', 'MongoDB Atlas launches: the database as a cloud service, with a free tier.' ),
		),
		'flow'    => array( 'Your Python app', 'PyMongo driver', 'Database', 'Collection (like a table)', 'Documents (JSON/BSON)', 'Indexes make queries fast', 'Aggregation pipeline summarises data' ),
		'run'     => 'MongoDB Atlas free cluster or MongoDB Community on your laptop, with PyMongo in Jupyter or Colab.',
		'used'    => 'User profiles, product catalogues, app logs, content management, real-time apps.',
	),
	'power-bi'               => array(
		'history' => array(
			array( '2010', 'Power Pivot for Excel brings the DAX formula language and in-memory data models.' ),
			array( '2015', 'Power BI Desktop and the Power BI service launch as one product.' ),
			array( 'Today', 'Power BI is one of the most requested BI tools in data analyst job posts.' ),
		),
		'flow'    => array( 'Get data (Excel, CSV, SQL)', 'Clean it in Power Query', 'Build the model: relationships, star schema', 'Write measures in DAX', 'Design visuals and slicers', 'Publish and share the report' ),
		'run'     => 'Power BI Desktop (free, Windows). On Mac, use a Windows virtual machine or the web service.',
		'used'    => 'Sales dashboards, finance reports, HR analytics, management KPIs.',
	),
	'time-series'            => array(
		'history' => array(
			array( '1927', 'Udny Yule describes autoregressive models.' ),
			array( '1957–60', 'Holt and Winters develop exponential smoothing with trend and seasonality.' ),
			array( '1970', 'Box and Jenkins publish the ARIMA method.' ),
			array( '2017', 'Facebook open-sources Prophet for business forecasting.' ),
		),
		'flow'    => array( 'Data with a date column', 'Plot it over time', 'Split into trend, seasonality and noise', 'Make it stationary (differencing)', 'Fit a model such as ARIMA', 'Back-test on past data', 'Forecast with a likely range' ),
		'run'     => 'pandas and statsmodels in Jupyter or Colab.',
		'used'    => 'Sales and demand forecasting, stock planning, electricity load, website traffic.',
	),
	'spark'                  => array(
		'history' => array(
			array( '2004', 'Google publishes MapReduce: split big jobs across many computers.' ),
			array( '2006', 'Hadoop brings MapReduce to everyone as open source.' ),
			array( '2009', 'Spark starts at UC Berkeley’s AMPLab, keeping data in memory to run much faster.' ),
			array( '2014', 'Spark becomes a top-level Apache project.' ),
		),
		'flow'    => array( 'Data in files or tables (often in the cloud)', 'Driver program plans the job', 'Data split into partitions', 'Transformations are planned lazily', 'An action starts the job', 'Executors work on partitions in parallel', 'Results come back' ),
		'run'     => 'PySpark on your laptop (pip install pyspark), in Colab, or on Databricks’ free edition.',
		'used'    => 'Processing logs, clickstreams and transactions too big for one computer; large-scale ETL and ML.',
	),
	'deep-learning'          => array(
		'history' => array(
			array( '1943', 'McCulloch and Pitts describe an artificial neuron.' ),
			array( '1958', 'Rosenblatt builds the perceptron.' ),
			array( '1986', 'Rumelhart, Hinton and Williams popularise backpropagation for training networks.' ),
			array( '2012', 'AlexNet wins the ImageNet challenge by a wide margin and starts the deep learning boom.' ),
			array( '2015–17', 'TensorFlow and PyTorch make deep learning open to everyone.' ),
		),
		'flow'    => array( 'Input data', 'Layers multiply by weights', 'Prediction', 'Loss: how wrong it was', 'Backpropagation computes gradients', 'Optimiser updates weights', 'Repeat for many epochs' ),
		'run'     => 'Google Colab with a free GPU; Keras/TensorFlow or PyTorch.',
		'used'    => 'Image recognition, speech, translation, recommendations, medical scans.',
	),
	'nlp'                    => array(
		'history' => array(
			array( '1966', 'ELIZA, an early chatbot, imitates a therapist with simple rules.' ),
			array( '1972', 'Karen Spärck Jones introduces inverse document frequency, the “IDF” in TF-IDF.' ),
			array( '2013', 'word2vec turns words into vectors that capture meaning.' ),
			array( '2017', 'The Transformer (“Attention Is All You Need”) changes NLP for good.' ),
			array( '2018', 'BERT shows one pre-trained model can be fine-tuned for many tasks.' ),
		),
		'flow'    => array( 'Raw text', 'Clean and tokenise', 'Turn words into numbers (TF-IDF or embeddings)', 'Train or use a model', 'Prediction: sentiment, topic, answer', 'Evaluate on new text' ),
		'run'     => 'scikit-learn, NLTK or spaCy, and Hugging Face transformers in Colab.',
		'used'    => 'Review sentiment, support ticket routing, search, spam filters, chatbots.',
	),
	'computer-vision'        => array(
		'history' => array(
			array( '1966', 'MIT’s Summer Vision Project tries to make computers “see” in one summer.' ),
			array( '1986', 'John Canny publishes the Canny edge detector.' ),
			array( '2000', 'OpenCV is first released.' ),
			array( '2009', 'The ImageNet dataset is introduced.' ),
			array( '2015', 'YOLO detects objects in real time in a single pass.' ),
		),
		'flow'    => array( 'Image', 'Pixels as a NumPy array', 'Pre-process: resize, normalise', 'Extract features: filters or a CNN', 'Predict: class or boxes', 'Clean up overlapping boxes', 'Result on screen' ),
		'run'     => 'OpenCV and Ultralytics YOLO in Colab (free GPU helps).',
		'used'    => 'Number-plate reading, quality checks in factories, medical imaging, retail shelf monitoring.',
	),
	'reinforcement-learning' => array(
		'history' => array(
			array( '1957', 'Richard Bellman’s dynamic programming gives the Bellman equation.' ),
			array( '1989', 'Chris Watkins introduces Q-learning.' ),
			array( '2015', 'DeepMind’s DQN learns Atari games from pixels.' ),
			array( '2016', 'AlphaGo beats Lee Sedol at Go.' ),
		),
		'flow'    => array( 'Agent sees the state', 'Chooses an action', 'Environment gives a reward and a new state', 'Agent updates its value estimates or policy', 'Repeat over many episodes' ),
		'run'     => 'Gymnasium environments with NumPy or PyTorch, in Colab.',
		'used'    => 'Game AI, robotics, ad bidding, recommendation tuning, and training chat models from feedback.',
	),
	'mlops'                  => array(
		'history' => array(
			array( '2013', 'Docker makes it easy to package software with everything it needs.' ),
			array( '2015', 'Google’s paper “Hidden Technical Debt in Machine Learning Systems” shows that most ML work is outside the model.' ),
			array( '2018', 'MLflow is released for tracking experiments and models.' ),
		),
		'flow'    => array( 'Data', 'Train the model', 'Track experiments', 'Package model + API', 'Put it in a container', 'Deploy', 'Monitor for drift', 'Retrain when needed' ),
		'run'     => 'MLflow, FastAPI and Docker on your laptop; then a cloud host.',
		'used'    => 'Keeping models working and up to date in real products.',
	),
	'ds-projects'            => array(
		'history' => array(
			array( '1996–2000', 'CRISP-DM, the standard data project process, is created by industry teams and is still used today.' ),
		),
		'flow'    => array( 'Understand the business question', 'Understand the data', 'Prepare the data', 'Model', 'Evaluate against the question', 'Present and deploy' ),
		'run'     => 'Jupyter or Colab; publish your notebooks on GitHub for your portfolio.',
		'used'    => 'Portfolio pieces to discuss in interviews: what you did, why, and what you found.',
	),
	'ds-signature'           => array(
		'flow'    => array( 'Indian public dataset', 'Clean and explore', 'Find 3 insights', 'Model or forecast', 'Charts that tell the story', 'Write-up for your portfolio' ),
		'run'     => 'Jupyter or Colab with pandas, Matplotlib, Seaborn and scikit-learn.',
		'used'    => 'Stand-out portfolio projects with Indian data that interviewers remember.',
	),

	/* ── Gen AI Developer ───────────────────────────────────────────────── */

	'llm-basics'             => array(
		'history' => array(
			array( '2017', 'Google researchers publish the Transformer architecture.' ),
			array( '2018', 'GPT-1 and BERT show the power of pre-training on huge amounts of text.' ),
			array( '2020', 'GPT-3, with 175 billion parameters, writes surprisingly fluent text.' ),
			array( '2022', 'ChatGPT launches on 30 November and reaches millions of users within days.' ),
			array( '2023 →', 'Open models (Llama, Mistral and others) and multimodal models spread quickly.' ),
		),
		'flow'    => array( 'Your text', 'Split into tokens', 'Tokens become vectors (embeddings)', 'Transformer layers use attention', 'Probabilities for the next token', 'Pick a token', 'Repeat until done' ),
		'run'     => 'Concepts with live animations here; experiments with tokenizers and small models in Colab.',
		'used'    => 'Chat assistants, writing and coding help, summarising, search and support bots.',
	),
	'llm-apis'               => array(
		'history' => array(
			array( '2020', 'OpenAI opens an API for GPT-3: models become something you call from code.' ),
			array( '2023', 'Function calling (tool use) and chat-style APIs become standard across providers.' ),
			array( '2024', 'Structured outputs (guaranteed JSON) and much cheaper, faster models arrive.' ),
		),
		'flow'    => array( 'Your app', 'Request: model, messages, settings, API key', 'Provider runs the model', 'Response: text, JSON or a tool call', 'Your code checks and uses it', 'User sees the result' ),
		'run'     => 'Python SDKs in Colab or VS Code; keep API keys in environment variables or Colab Secrets, never in code.',
		'used'    => 'Every AI feature in an app: chat, summaries, extraction, classification, translation.',
	),
	'ai-app-project'         => array(
		'flow'    => array( 'Idea and user', 'Screen design', 'Backend calls the LLM API', 'Prompt and guardrails', 'Store history', 'Test with real users', 'Deploy' ),
		'run'     => 'Python, an LLM API and a simple web UI, run locally then deployed.',
		'used'    => 'A complete AI app for your portfolio, built end to end.',
	),
	'rag'                    => array(
		'history' => array(
			array( '2013', 'word2vec shows text can become meaningful vectors.' ),
			array( '2017', 'Facebook open-sources FAISS for fast vector search.' ),
			array( '2020', 'The paper “Retrieval-Augmented Generation” (Lewis et al.) names the method.' ),
			array( '2023', 'RAG becomes the standard way to give chatbots company knowledge.' ),
		),
		'flow'    => array( 'Your documents', 'Split into chunks', 'Embed each chunk', 'Store in a vector database', 'Embed the question', 'Retrieve the closest chunks', 'Add them to the prompt', 'LLM answers with sources' ),
		'run'     => 'Python with an embeddings model and a vector store (Chroma or FAISS) in Colab.',
		'used'    => 'Chat with PDFs, internal help desks, legal and policy search, product support.',
	),
	'langchain'              => array(
		'history' => array(
			array( 'Oct 2022', 'Harrison Chase releases LangChain as open source.' ),
			array( 'Nov 2022', 'Jerry Liu releases GPT Index, later renamed LlamaIndex.' ),
			array( '2024', 'LangGraph adds graph-based control for multi-step agents.' ),
		),
		'flow'    => array( 'Load documents', 'Split text', 'Embed and store', 'Retriever finds context', 'Prompt template', 'LLM', 'Output parser', 'Chain or agent' ),
		'run'     => 'Python in Colab or VS Code with an LLM API key.',
		'used'    => 'Building RAG apps and multi-step LLM workflows faster.',
	),
	'llm-agents'             => array(
		'history' => array(
			array( '2022', 'The ReAct paper shows models can reason and act with tools in a loop.' ),
			array( '2023', 'AutoGPT goes viral; tool calling becomes a standard API feature.' ),
			array( '2024', 'The Model Context Protocol (MCP) standardises how apps give tools to models.' ),
		),
		'flow'    => array( 'Goal', 'Model thinks', 'Chooses a tool', 'Your code runs the tool', 'Result goes back to the model', 'Repeat or answer' ),
		'run'     => 'Python with an LLM API that supports tool calling.',
		'used'    => 'Assistants that search, book, calculate, or work with your apps.',
	),
	'multimodal-ai'          => array(
		'history' => array(
			array( '2021', 'CLIP connects images and text in one shared space.' ),
			array( '2022', 'Whisper brings accurate open speech recognition.' ),
			array( '2023', 'Leading models start accepting images as input, then audio.' ),
		),
		'flow'    => array( 'Image, audio or video', 'Encoder turns it into vectors', 'Shared space with text', 'Language model reasons', 'Text or speech output' ),
		'run'     => 'Multimodal APIs and Hugging Face models in Colab.',
		'used'    => 'Reading invoices and forms, describing images, voice assistants, video search.',
	),
	'generative-models'      => array(
		'history' => array(
			array( '2013', 'Variational autoencoders (VAEs) are introduced.' ),
			array( '2014', 'Ian Goodfellow introduces GANs: two networks competing.' ),
			array( '2020', 'Denoising diffusion models produce high-quality images.' ),
			array( '2022', 'DALL·E 2 and the open Stable Diffusion bring text-to-image to everyone.' ),
		),
		'flow'    => array( 'Start from random noise', 'Model predicts the noise to remove', 'Guided by your text prompt', 'Repeat over many steps', 'A new image appears' ),
		'run'     => 'Hugging Face diffusers in Colab with a GPU.',
		'used'    => 'Design drafts, marketing images, product mock-ups, data augmentation.',
	),
	'fine-tuning'            => array(
		'history' => array(
			array( '2018', 'ULMFiT and BERT show that fine-tuning a pre-trained model beats training from scratch.' ),
			array( '2019', 'Hugging Face’s Transformers library makes pre-trained models easy to use.' ),
			array( '2021', 'LoRA trains small adapter weights instead of the whole model.' ),
			array( '2023', 'QLoRA makes fine-tuning possible on a single GPU.' ),
		),
		'flow'    => array( 'Pick a base model', 'Prepare good examples', 'Tokenise', 'Train small adapters (LoRA)', 'Evaluate against the base', 'Serve the improved model' ),
		'run'     => 'Hugging Face Transformers and PEFT in Colab with a GPU.',
		'used'    => 'Matching a company’s style, specialised classifiers, domain vocabulary.',
	),
	'ai-safety'              => array(
		'history' => array(
			array( '2017', 'Learning from human preferences (the basis of RLHF) is published.' ),
			array( '2022', 'InstructGPT shows RLHF makes models more helpful and safer; “prompt injection” gets its name.' ),
			array( '2023', 'OWASP publishes its Top 10 risks for LLM applications.' ),
		),
		'flow'    => array( 'User input', 'Input checks: personal data, injection', 'Model with clear system rules', 'Output checks: safety, facts, format', 'Logging and review', 'Safe answer to the user' ),
		'run'     => 'Python with your LLM API, plus simple validation and moderation steps.',
		'used'    => 'Any AI feature open to the public, especially in finance, health and education.',
	),
	'ai-coding-assistants'   => array(
		'history' => array(
			array( '2021', 'GitHub Copilot preview suggests code as you type.' ),
			array( '2023', 'Chat assistants arrive inside code editors.' ),
			array( '2025', 'Agentic coding tools edit many files, run tests and explain changes.' ),
		),
		'flow'    => array( 'You describe the task', 'Assistant reads the code', 'Proposes a change', 'You review it', 'Run the tests', 'Commit' ),
		'run'     => 'VS Code or the terminal with an AI coding assistant.',
		'used'    => 'Writing, fixing and explaining code faster, while you stay in control.',
	),
	'deploy-ai-apps'         => array(
		'history' => array(
			array( '2013', 'Docker containers make deployment repeatable.' ),
			array( '2018–19', 'FastAPI and Streamlit make Python web apps quick to build.' ),
		),
		'flow'    => array( 'App code', 'Secrets for API keys', 'Container', 'Host (cloud or Spaces)', 'Monitor errors and cost', 'Improve' ),
		'run'     => 'FastAPI or Streamlit, Docker, and a cloud host or Hugging Face Spaces.',
		'used'    => 'Putting your AI app online for real users.',
	),
	'genai-capstones'        => array(
		'flow'    => array( 'Pick a real problem', 'Plan the AI parts', 'Build the first version', 'Test with users', 'Add safety and evaluation', 'Deploy and present' ),
		'run'     => 'Your full Gen AI toolkit: Python, LLM APIs, RAG and deployment.',
		'used'    => 'Portfolio projects that show you can ship AI products.',
	),
	'ai-signature'           => array(
		'flow'    => array( 'An Indian use case', 'Data and prompts', 'Build the AI feature', 'Evaluate quality', 'Polish the demo', 'Portfolio write-up' ),
		'run'     => 'Python, LLM APIs and Colab or VS Code.',
		'used'    => 'Stand-out AI projects that interviewers remember.',
	),

	/* ── Agentic AI Engineer ────────────────────────────────────────────── */

	'ai-agents'              => array(
		'history' => array(
			array( '1995', 'AI textbooks define an “intelligent agent”: something that perceives and acts.' ),
			array( '2022', 'ReAct shows large language models can plan and use tools.' ),
			array( '2023', 'AutoGPT and BabyAGI spark wide interest in autonomous agents.' ),
			array( '2024–25', 'Agents that use computers, browsers and code move into real products.' ),
		),
		'flow'    => array( 'Goal', 'Observe the situation', 'Think (the model)', 'Act with a tool', 'Observe the result', 'Loop until done' ),
		'run'     => 'Python with an LLM API that supports tool calling.',
		'used'    => 'Research assistants, support agents, data and coding agents.',
	),
	'agent-tools'            => array(
		'history' => array(
			array( '2023', 'Model providers add function calling: models request tools in structured JSON.' ),
			array( '2024', 'Tool use becomes generally available across major model APIs.' ),
		),
		'flow'    => array( 'Describe the tool (name, schema)', 'Model returns a tool call', 'Your code validates the arguments', 'Run the tool', 'Send the result back', 'Model continues' ),
		'run'     => 'Python with your LLM SDK; validate inputs before running any tool.',
		'used'    => 'Letting agents search, query databases, call APIs and send messages safely.',
	),
	'agent-memory'           => array(
		'history' => array(
			array( '2020', 'Retrieval-augmented generation shows models can look things up instead of memorising.' ),
			array( '2023', 'The “Generative Agents” paper gives simulated characters a memory stream with reflection.' ),
		),
		'flow'    => array( 'Conversation so far (context window)', 'Summarise old turns', 'Save key facts to long-term storage', 'Retrieve what is relevant', 'Add it to the prompt' ),
		'run'     => 'Python with a vector store or database and your LLM API.',
		'used'    => 'Assistants that remember preferences, ongoing projects and past decisions.',
	),
	'agent-reasoning'        => array(
		'history' => array(
			array( '2022', 'Chain-of-thought prompting: asking models to show their steps improves reasoning.' ),
			array( '2022', 'ReAct combines reasoning steps with actions.' ),
			array( '2023', 'Reflexion and Tree of Thoughts add self-review and exploring several paths.' ),
		),
		'flow'    => array( 'Understand the goal', 'Make a plan', 'Act on one step', 'Check the result', 'Reflect and revise', 'Final answer' ),
		'run'     => 'Python notebooks with your LLM API.',
		'used'    => 'Multi-step tasks: research, analysis, debugging, planning.',
	),
	'mcp'                    => array(
		'history' => array(
			array( 'Nov 2024', 'Anthropic introduces the Model Context Protocol as an open standard.' ),
			array( '2025', 'Other major AI companies and developer tools adopt MCP.' ),
		),
		'flow'    => array( 'AI app (MCP client)', 'Connects to an MCP server', 'Server lists tools, resources and prompts', 'Model picks a tool', 'Server runs it and returns results', 'Model answers' ),
		'run'     => 'Python or TypeScript MCP SDKs on your laptop, with an MCP-capable AI app.',
		'used'    => 'Plugging AI assistants into files, databases, GitHub, calendars and company systems.',
	),
	'agent-frameworks'       => array(
		'history' => array(
			array( '2022', 'LangChain popularises chains and agents in Python.' ),
			array( '2023', 'Microsoft’s AutoGen and CrewAI focus on teams of agents.' ),
			array( '2024–25', 'LangGraph and official agent SDKs from model providers arrive.' ),
		),
		'flow'    => array( 'Pick a framework', 'Define agents, tools and state', 'Wire the steps (graph or roles)', 'Run', 'Trace and debug', 'Compare with plain code' ),
		'run'     => 'Python in VS Code or Colab with an LLM API key.',
		'used'    => 'Choosing the right level of structure for an agent project.',
	),
	'multi-agent'            => array(
		'history' => array(
			array( '1990s', 'Multi-agent systems become a research field in distributed AI.' ),
			array( '2023', 'AutoGen, MetaGPT and ChatDev show LLM agents working in teams.' ),
		),
		'flow'    => array( 'Orchestrator splits the task', 'Specialist agents work', 'Share messages or state', 'Reviewer checks', 'Combine results' ),
		'run'     => 'Python with an agent framework or plain orchestration code.',
		'used'    => 'Research and writing pipelines, software tasks, data analysis teams.',
	),
	'agent-evals'            => array(
		'history' => array(
			array( '2023', 'Using a strong model as a judge (LLM-as-a-judge) is studied and becomes common.' ),
			array( '2023', 'SWE-bench tests agents on real GitHub issues.' ),
		),
		'flow'    => array( 'Write test tasks and expected results', 'Run the agent', 'Record a trace of every step', 'Score: code checks, model judge, humans', 'Compare versions', 'Catch regressions before release' ),
		'run'     => 'Python test scripts with tracing, in VS Code or Colab.',
		'used'    => 'Knowing whether a change made your agent better or worse.',
	),
	'agent-safety'           => array(
		'history' => array(
			array( '2022', 'Prompt injection is named and demonstrated.' ),
			array( '2023', 'Indirect prompt injection (hidden instructions in web pages and files) is shown in research; OWASP lists LLM risks.' ),
		),
		'flow'    => array( 'Give the least permissions needed', 'Check inputs and outputs', 'Ask a human before risky actions', 'Run code in a sandbox', 'Keep audit logs' ),
		'run'     => 'Python with guard functions around every tool call.',
		'used'    => 'Agents that touch email, money, files or customer data.',
	),
	'agent-production'       => array(
		'flow'    => array( 'Agent behind an API', 'Queue long jobs', 'Timeouts and retries', 'Cost and rate limits', 'Monitoring and traces', 'User feedback loop' ),
		'run'     => 'FastAPI, a task queue and a cloud host, with tracing.',
		'used'    => 'Running agents reliably for real users at a known cost.',
	),
	'agent-capstones'        => array(
		'flow'    => array( 'Pick a real workflow', 'Design tools and safety', 'Build the agent', 'Write evals', 'Deploy', 'Present results' ),
		'run'     => 'Your full agent toolkit: Python, LLM APIs, MCP, evals and deployment.',
		'used'    => 'Portfolio agents that show you can ship reliable automation.',
	),

	/* ── Free data and AI paths (4.16.0) ────────────────────────────────── */

	'ds-setup'               => array(
		'history' => array(
			array( '2001', 'Fernando Pérez creates IPython, a friendlier interactive Python.' ),
			array( '2014', 'The notebook becomes Project Jupyter (Julia, Python, R).' ),
			array( '2017', 'Google opens Colab to everyone: notebooks in the cloud with free GPUs.' ),
		),
		'flow'    => array( 'Install Anaconda (or open Colab)', 'Create an environment', 'Open a notebook', 'Each cell goes to the kernel', 'Output appears under the cell' ),
		'run'     => 'Anaconda + JupyterLab on your laptop, or Google Colab in the browser.',
		'used'    => 'Every data analysis, teaching notebook and machine learning experiment.',
	),
	'python'                 => array(
		'history' => array(
			array( '1989', 'Guido van Rossum starts Python as a hobby project over the Christmas holidays.' ),
			array( '1991', 'The first public version is released.' ),
			array( '2008', 'Python 3 arrives, cleaning up the language.' ),
			array( '2020', 'Python 2 reaches end of life; Python 3 is the only version going forward.' ),
		),
		'flow'    => array( 'You write code (.py file or notebook)', 'The interpreter reads it', 'It is compiled to bytecode', 'The Python virtual machine runs it', 'You see the output' ),
		'run'     => 'Python from python.org or Anaconda, in VS Code, Jupyter or Google Colab.',
		'used'    => 'Data analysis, AI and machine learning, web backends, automation and scripting.',
	),
	'numpy'                  => array(
		'history' => array(
			array( '1995', 'Numeric, the first array package for Python, appears.' ),
			array( '2006', 'Travis Oliphant releases NumPy 1.0, merging Numeric and Numarray.' ),
			array( '2020', 'A paper in Nature describes NumPy as the base of scientific Python.' ),
		),
		'flow'    => array( 'Python list of numbers', 'np.array: one type, one block of memory', 'Vectorised maths runs in fast C code', 'A new array comes back', 'pandas, scikit-learn and others build on it' ),
		'run'     => 'NumPy comes with Anaconda and Colab; otherwise pip install numpy.',
		'used'    => 'The foundation under pandas, scikit-learn, image processing and deep learning.',
	),
	'pandas'                 => array(
		'history' => array(
			array( '2008', 'Wes McKinney starts pandas at a finance firm to analyse data in Python.' ),
			array( '2009', 'pandas is released as open source.' ),
			array( '2020', 'pandas 1.0 is released.' ),
			array( '2023', 'pandas 2.0 adds optional Apache Arrow support for speed and memory.' ),
		),
		'flow'    => array( 'Read CSV, Excel or SQL', 'DataFrame: rows and named columns', 'Select and filter', 'Clean missing and wrong values', 'Group, merge and reshape', 'Result table or chart' ),
		'run'     => 'pandas in Jupyter or Colab (pre-installed in both).',
		'used'    => 'Daily work of data analysts: reports, cleaning, joining and summarising tables.',
	),
	'data-visualization'     => array(
		'history' => array(
			array( '1786', 'William Playfair invents the line chart and the bar chart.' ),
			array( '1858', 'Florence Nightingale’s rose diagram shows soldiers dying of preventable disease.' ),
			array( '1983', 'Edward Tufte’s book on displaying data sets the rules for honest charts.' ),
			array( '2003', 'John Hunter creates Matplotlib.' ),
		),
		'flow'    => array( 'Start from a question', 'Pick the chart that answers it', 'Plot the data', 'Label, highlight and add context', 'Check it is honest', 'Share it' ),
		'run'     => 'Matplotlib, Seaborn and Plotly in Jupyter or Colab.',
		'used'    => 'Reports, dashboards, presentations, and spotting problems in data before modelling.',
	),
	'machine-learning'       => array(
		'history' => array(
			array( '1950', 'Alan Turing asks “Can machines think?” in a famous paper.' ),
			array( '1959', 'Arthur Samuel popularises the term “machine learning” with a program that learns checkers.' ),
			array( '2007', 'scikit-learn starts, making machine learning easy in Python.' ),
		),
		'flow'    => array( 'Examples with known answers', 'Split into train and test', 'Train: the model learns patterns', 'Test on unseen examples', 'Predict on new data' ),
		'run'     => 'scikit-learn in Jupyter or Colab.',
		'used'    => 'Spam filters, price prediction, recommendations, fraud and churn detection.',
	),
	'sql'                    => array(
		'history' => array(
			array( '1970', 'Edgar F. Codd at IBM describes the relational model of data.' ),
			array( '1974', 'Chamberlin and Boyce at IBM design SEQUEL, which becomes SQL.' ),
			array( '1979', 'Oracle releases one of the first commercial SQL databases.' ),
			array( '1986', 'SQL becomes an ANSI standard.' ),
		),
		'flow'    => array( 'You write a query', 'The database checks it', 'The optimiser plans the fastest way', 'The engine reads tables and indexes', 'Result rows come back' ),
		'run'     => 'The SQL playground on this site, SQLite, MySQL or PostgreSQL.',
		'used'    => 'Almost every company’s data lives in SQL databases; analysts query them daily.',
	),
	'prompt-engineering'     => array(
		'history' => array(
			array( '2020', 'GPT-3 shows models can learn a task from a few examples in the prompt.' ),
			array( '2022', 'Chain-of-thought prompting improves reasoning; ChatGPT makes prompting an everyday skill.' ),
		),
		'flow'    => array( 'Decide the goal', 'Give a role and context', 'Write clear instructions', 'Add examples', 'Ask for a format', 'Test and refine' ),
		'run'     => 'Any AI chat app, or the prompt builder tool on this site.',
		'used'    => 'Writing, coding help, research, data extraction and building AI features.',
	),
	'ai-models'              => array(
		'history' => array(
			array( '1956', 'The Dartmouth workshop names the field “artificial intelligence”.' ),
			array( '2012', 'AlexNet starts the deep learning era.' ),
			array( '2017', 'The Transformer architecture appears.' ),
			array( '2022', 'ChatGPT brings AI models to hundreds of millions of people.' ),
		),
		'flow'    => array( 'Collect data', 'Choose an architecture', 'Train: learn the weights', 'Evaluate', 'Deploy', 'Use it (inference)' ),
		'run'     => 'Concepts with animations here; small models in Colab.',
		'used'    => 'Understanding what AI can and can’t do before building with it.',
	),
);
