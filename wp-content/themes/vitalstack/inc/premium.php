<?php
/**
 * Premium career tracks: Data Scientist and Gen AI Developer.
 *
 * Each track is a full syllabus at /premium/<track>/. Modules that already
 * exist as free courses link straight to them (Python, NumPy, Pandas, …);
 * the premium modules are locked until the reader has access.
 *
 * Payments aren't connected yet, so for now:
 *   - readers can join a waitlist (one click, stored on their account);
 *   - an administrator can give access on the user's profile screen
 *     (Users → Edit → "Premium access").
 * Lessons in a learning path marked premium (term meta "vs_premium" = track
 * slug) show a short preview and a lock to readers without access, and are
 * kept out of search results.
 *
 * @package VitalStack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Track definitions. A module with 'path' links to that free learning path.
 */
function vitalstack_premium_tracks() {
	return array(
		'data-scientist'   => array(
			'title'    => __( 'Data Scientist', 'vitalstack' ),
			'icon'     => '📊',
			'tagline'  => __( 'From your first line of Python to machine learning models and dashboards that answer real business questions.', 'vitalstack' ),
			'for'      => __( 'Students, freshers and working professionals (tech or non-tech) who want a data analyst or data scientist role.', 'vitalstack' ),
			'outcomes' => array(
				__( 'Clean, explore and visualise any dataset with Pandas, Matplotlib and Seaborn', 'vitalstack' ),
				__( 'Use statistics to test ideas instead of guessing', 'vitalstack' ),
				__( 'Build, evaluate and improve machine learning models with scikit-learn', 'vitalstack' ),
				__( 'Query data with SQL and present it in Power BI dashboards', 'vitalstack' ),
				__( 'Finish 6 portfolio projects you can show in interviews', 'vitalstack' ),
			),
			'modules'  => array(
				array( 'title' => __( 'Python for Data Science', 'vitalstack' ), 'path' => 'python', 'about' => __( 'Variables, data types, loops, functions, files and errors: the Python you need every day.', 'vitalstack' ) ),
				array( 'title' => __( 'NumPy: fast numbers', 'vitalstack' ), 'path' => 'numpy', 'about' => __( 'Arrays, shapes, slicing, broadcasting and vectorised maths.', 'vitalstack' ) ),
				array( 'title' => __( 'Pandas: data analysis', 'vitalstack' ), 'path' => 'pandas', 'about' => __( 'Load, filter, clean, group, join and reshape tables of data.', 'vitalstack' ) ),
				array( 'title' => __( 'Data Visualization', 'vitalstack' ), 'path' => 'data-visualization', 'about' => __( 'Charts that tell a story with Matplotlib and Seaborn.', 'vitalstack' ) ),
				array( 'title' => __( 'Machine Learning Basics', 'vitalstack' ), 'path' => 'machine-learning', 'about' => __( 'What a model is, train/test split, your first regression and classifier with scikit-learn.', 'vitalstack' ) ),
				array( 'title' => __( 'SQL for Data Analysis', 'vitalstack' ), 'path' => 'sql', 'about' => __( 'SELECT, JOIN, GROUP BY and window functions for analytics.', 'vitalstack' ) ),
				array( 'title' => __( 'Statistics and Probability', 'vitalstack' ), 'path' => 'statistics', 'premium' => true, 'about' => __( 'Averages and spread, probability and Bayes, the normal curve, confidence intervals, p-values and correlation: 6 animated lessons with tested code.', 'vitalstack' ), 'lessons' => array( __( 'Mean, median, spread and the five-number summary', 'vitalstack' ), __( 'Probability, conditional probability and Bayes’ theorem', 'vitalstack' ), __( 'Distributions: binomial, normal and the central limit theorem', 'vitalstack' ), __( 'Sampling and confidence intervals', 'vitalstack' ), __( 'Hypothesis tests: z, t, chi-square and p-values', 'vitalstack' ), __( 'Correlation vs causation', 'vitalstack' ) ) ),
				array( 'title' => __( 'Exploratory Data Analysis and Feature Engineering', 'vitalstack' ), 'path' => 'eda', 'premium' => true, 'about' => __( 'EDA checklist, missing values and outliers, encoding and scaling, and feature engineering: 4 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'An EDA Checklist for Any New Dataset', 'vitalstack' ), __( 'Missing Values and Outliers: Find, Decide, Fix', 'vitalstack' ), __( 'Encoding Categories and Scaling Numbers', 'vitalstack' ), __( 'Feature Engineering: Creating Features Models Can Learn From', 'vitalstack' ) ) ),
				array( 'title' => __( 'Machine Learning in Depth', 'vitalstack' ), 'path' => 'ml-in-depth', 'premium' => true, 'about' => __( 'Regression, trees and boosting, k-NN, Naive Bayes, SVM, clustering, PCA, metrics, tuning, imbalance and explainability: 7 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Linear and Logistic Regression, Step by Step', 'vitalstack' ), __( 'Decision Trees, Random Forests and Gradient Boosting', 'vitalstack' ), __( 'k-Nearest Neighbours, Naive Bayes and SVM', 'vitalstack' ), __( 'Clustering with k-Means and DBSCAN', 'vitalstack' ), __( 'PCA and Dimensionality Reduction', 'vitalstack' ), __( 'Metrics, Cross-Validation and Hyper-Parameter Tuning', 'vitalstack' ), __( 'Imbalanced Data and Model Explainability', 'vitalstack' ) ) ),
				array( 'title' => __( 'NoSQL with MongoDB', 'vitalstack' ), 'path' => 'mongodb', 'premium' => true, 'about' => __( 'Documents and data modelling, CRUD with PyMongo, indexes and aggregation pipelines: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'NoSQL and MongoDB: Documents, Collections and JSON', 'vitalstack' ), __( 'CRUD and Queries with PyMongo', 'vitalstack' ), __( 'The MongoDB Aggregation Pipeline', 'vitalstack' ) ) ),
				array( 'title' => __( 'Dashboards with Power BI', 'vitalstack' ), 'path' => 'power-bi', 'premium' => true, 'about' => __( 'Data models and Power Query, DAX measures, and interactive reports with slicers and drill-through: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Power BI: Connecting Data and Building the Data Model', 'vitalstack' ), __( 'Power BI Measures and DAX Basics', 'vitalstack' ), __( 'Interactive Power BI Reports: Slicers, Drill-Down and Design', 'vitalstack' ) ) ),
				array( 'title' => __( 'Time Series Forecasting', 'vitalstack' ), 'path' => 'time-series', 'premium' => true, 'about' => __( 'Trend and seasonality, stationarity and differencing, ARIMA and honest forecast evaluation: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Time Series: Trend, Seasonality and Moving Averages', 'vitalstack' ), __( 'Stationarity, Differencing and Autocorrelation', 'vitalstack' ), __( 'ARIMA Forecasting in Python', 'vitalstack' ) ) ),
				array( 'title' => __( 'Big Data with Apache Spark', 'vitalstack' ), 'path' => 'spark', 'premium' => true, 'about' => __( 'Why big data needs Spark, DataFrames and Spark SQL, and machine learning with MLlib: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Big Data: Why One Computer Isn\'t Enough (Hadoop, MapReduce, Spark)', 'vitalstack' ), __( 'PySpark DataFrames and Spark SQL', 'vitalstack' ), __( 'Machine Learning at Scale with Spark MLlib', 'vitalstack' ) ) ),
				array( 'title' => __( 'Deep Learning Foundations', 'vitalstack' ), 'path' => 'deep-learning', 'premium' => true, 'about' => __( 'Neurons and layers, your first Keras network, CNNs for images, RNNs and LSTMs, and PyTorch: 5 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Neurons, Layers and How a Neural Network Learns', 'vitalstack' ), __( 'Your First Neural Network with Keras (TensorFlow)', 'vitalstack' ), __( 'CNNs for Images: Convolution, Pooling and Transfer Learning', 'vitalstack' ), __( 'RNNs and LSTMs for Sequences', 'vitalstack' ), __( 'The Same Models in PyTorch', 'vitalstack' ) ) ),
				array( 'title' => __( 'NLP: Working with Text', 'vitalstack' ), 'path' => 'nlp', 'premium' => true, 'about' => __( 'Cleaning and tokenising, TF-IDF and similarity, sentiment analysis and transformers: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'NLP Basics: Cleaning and Tokenising Text', 'vitalstack' ), __( 'TF-IDF, Text Similarity and Search', 'vitalstack' ), __( 'Sentiment Analysis: From TF-IDF to Transformers', 'vitalstack' ) ) ),
				array( 'title' => __( 'Computer Vision', 'vitalstack' ), 'path' => 'computer-vision', 'premium' => true, 'about' => __( 'Images as arrays, filters, edges and contours, and object detection with YOLO: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Computer Vision Basics: Images as Arrays', 'vitalstack' ), __( 'Filters, Edges and Contours', 'vitalstack' ), __( 'Object Detection with YOLO', 'vitalstack' ) ) ),
				array( 'title' => __( 'Reinforcement Learning Basics', 'vitalstack' ), 'path' => 'reinforcement-learning', 'premium' => true, 'about' => __( 'Agents, rewards and exploration, and Q-learning you can watch learn: 2 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Reinforcement Learning: Agents, Rewards and Environments', 'vitalstack' ), __( 'Q-Learning on a Grid World', 'vitalstack' ) ) ),
				array( 'title' => __( 'MLOps: Deploying Models', 'vitalstack' ), 'path' => 'mlops', 'premium' => true, 'about' => __( 'Model APIs with FastAPI and Docker, cloud ML platforms, and monitoring for drift: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Serving a Model with FastAPI and Docker', 'vitalstack' ), __( 'Cloud ML: AWS SageMaker and Azure Machine Learning', 'vitalstack' ), __( 'Monitoring Models in Production', 'vitalstack' ) ) ),
				array( 'title' => __( 'Portfolio Projects', 'vitalstack' ), 'path' => 'ds-projects', 'premium' => true, 'about' => __( 'Six end-to-end projects: sales analysis, churn, house prices, recommender, forecasting and an image classifier: 6 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Project 1: Sales Performance Analysis and Dashboard', 'vitalstack' ), __( 'Project 2: Customer Churn Prediction', 'vitalstack' ), __( 'Project 3: House Price Prediction', 'vitalstack' ), __( 'Project 4: Movie Recommendation System', 'vitalstack' ), __( 'Project 5: Retail Sales Forecasting', 'vitalstack' ), __( 'Project 6: Image Classifier with Transfer Learning', 'vitalstack' ) ) ),
				array( 'title' => __( 'Signature Projects (India data)', 'vitalstack' ), 'path' => 'ds-signature', 'premium' => true, 'about' => __( 'Four projects you will not find elsewhere, on VitalStack\'s own Indian datasets: a UPI fraud radar priced in rupees, a food-delivery ETA promise, a clean-air alert for eight cities and a fair-pay and attrition audit.', 'vitalstack' ), 'lessons' => array( __( 'Signature Project 1: UPI Fraud Radar', 'vitalstack' ), __( 'Signature Project 2: The 30-Minute Promise (Food Delivery ETA)', 'vitalstack' ), __( 'Signature Project 3: Clean-Air Alert for Indian Cities', 'vitalstack' ), __( 'Signature Project 4: Fair Pay and Attrition Audit', 'vitalstack' ) ) ),
			),
		),
		'gen-ai-developer' => array(
			'title'    => __( 'Gen AI Developer', 'vitalstack' ),
			'icon'     => '🤖',
			'tagline'  => __( 'Build real apps on top of large language models: chatbots, RAG over your own documents and AI agents that use tools.', 'vitalstack' ),
			'for'      => __( 'Developers and students who know some Python and want to build and ship AI-powered products.', 'vitalstack' ),
			'outcomes' => array(
				__( 'Write prompts that give reliable, structured answers', 'vitalstack' ),
				__( 'Understand how LLMs work: tokens, embeddings and attention', 'vitalstack' ),
				__( 'Call LLM APIs safely, with streaming, retries and cost control', 'vitalstack' ),
				__( 'Build RAG apps that answer from your own documents', 'vitalstack' ),
				__( 'Create AI agents that plan and use tools, then deploy them', 'vitalstack' ),
			),
			'modules'  => array(
				array( 'title' => __( 'Python Foundations', 'vitalstack' ), 'path' => 'python', 'about' => __( 'The Python every AI developer needs: functions, dictionaries, files, JSON and errors.', 'vitalstack' ) ),
				array( 'title' => __( 'Prompt Engineering', 'vitalstack' ), 'path' => 'prompt-engineering', 'about' => __( 'Clear instructions, examples, roles, step-by-step reasoning and structured output.', 'vitalstack' ) ),
				array( 'title' => __( 'NumPy and Pandas for AI', 'vitalstack' ), 'path' => 'numpy', 'about' => __( 'Vectors, matrices and data tables, the raw material of every model.', 'vitalstack' ) ),
				array( 'title' => __( 'How AI Models Are Built (free)', 'vitalstack' ), 'path' => 'ai-models', 'about' => __( 'How ChatGPT, Claude, Gemini and Grok are made: data, tokens, pretraining, RLHF and serving, with animated films.', 'vitalstack' ) ),
				array( 'title' => __( 'How LLMs Work', 'vitalstack' ), 'path' => 'llm-basics', 'premium' => true, 'about' => __( 'Tokens, embeddings, attention, temperature and hallucinations: 5 animated lessons with runnable Python.', 'vitalstack' ), 'lessons' => array( __( 'Tokens and next-token prediction', 'vitalstack' ), __( 'Embeddings: meaning as numbers', 'vitalstack' ), __( 'Transformers and attention, visually', 'vitalstack' ), __( 'Context windows, temperature and sampling', 'vitalstack' ), __( 'Why models hallucinate and how to reduce it', 'vitalstack' ) ) ),
				array( 'title' => __( 'Working with LLM APIs', 'vitalstack' ), 'path' => 'llm-apis', 'premium' => true, 'about' => __( 'Buy and use API keys for OpenAI, Claude, Gemini and Grok, then build with chat history, streaming, JSON output, retries, cost control and security: 7 lessons with tested code.', 'vitalstack' ), 'lessons' => array( __( 'Getting API keys: OpenAI, Claude, Gemini, Grok and free options', 'vitalstack' ), __( 'Your first API call (Claude, OpenAI, Gemini, Grok)', 'vitalstack' ), __( 'Chat history and system prompts', 'vitalstack' ), __( 'Streaming responses', 'vitalstack' ), __( 'Structured output with JSON schemas', 'vitalstack' ), __( 'Errors, retries, rate limits and cost', 'vitalstack' ), __( 'Keeping API keys and user data safe', 'vitalstack' ) ) ),
				array( 'title' => __( 'Project: AI Study Buddy App', 'vitalstack' ), 'path' => 'ai-app-project', 'premium' => true, 'about' => __( 'Plan, build, test, secure and deploy a real AI app with Python and Streamlit: notes in, quiz out, plus chat with your notes.', 'vitalstack' ), 'lessons' => array( __( 'Plan the app: architecture, prompts and the quiz format', 'vitalstack' ), __( 'Build it with Python and Streamlit', 'vitalstack' ), __( 'Test, secure and deploy it', 'vitalstack' ) ) ),
				array( 'title' => __( 'Retrieval-Augmented Generation (RAG)', 'vitalstack' ), 'path' => 'rag', 'premium' => true, 'about' => __( 'Why RAG, chunking, embeddings and vector databases, retrieval, re-ranking, citations and evaluation: 5 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Why RAG: Answers from Your Own Data', 'vitalstack' ), __( 'Chunking Documents for RAG', 'vitalstack' ), __( 'Embeddings and Vector Databases', 'vitalstack' ), __( 'Retrieval, Re-ranking and Citations', 'vitalstack' ), __( 'Evaluating a RAG App', 'vitalstack' ) ) ),
				array( 'title' => __( 'LangChain and LlamaIndex', 'vitalstack' ), 'path' => 'langchain', 'premium' => true, 'about' => __( 'Chains, prompts and parsers, chatbot memory, document loaders, retrievers and LlamaIndex: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'LangChain: Chains, Prompt Templates and Output Parsers', 'vitalstack' ), __( 'Memory for Chatbots: Buffers, Windows and Summaries', 'vitalstack' ), __( 'Document Loaders, Retrievers and LlamaIndex', 'vitalstack' ) ) ),
				array( 'title' => __( 'AI Agents and Tool Use', 'vitalstack' ), 'path' => 'llm-agents', 'premium' => true, 'about' => __( 'What makes an agent, function calling, LangGraph, CrewAI teams and MCP: 5 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'What Makes an Agent: Plan, Act, Observe', 'vitalstack' ), __( 'Function Calling and Tools', 'vitalstack' ), __( 'Multi-Step Agents with LangGraph', 'vitalstack' ), __( 'Teams of Agents with CrewAI', 'vitalstack' ), __( 'MCP: Connecting Agents to Apps and Data', 'vitalstack' ) ) ),
				array( 'title' => __( 'Multimodal AI: Images, Voice and Video', 'vitalstack' ), 'path' => 'multimodal-ai', 'premium' => true, 'about' => __( 'Vision with images and PDFs, speech to text and text to speech, and image generation: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Vision: Sending Images and PDFs to a Model', 'vitalstack' ), __( 'Speech to Text and Text to Speech', 'vitalstack' ), __( 'Image Generation APIs', 'vitalstack' ) ) ),
				array( 'title' => __( 'Generative Models: GANs, VAEs and Diffusion', 'vitalstack' ), 'path' => 'generative-models', 'premium' => true, 'about' => __( 'GANs, autoencoders and VAEs, and how diffusion image generators work: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'GANs: A Generator and a Critic', 'vitalstack' ), __( 'Autoencoders and Variational Autoencoders (VAEs)', 'vitalstack' ), __( 'Diffusion Models: How Image Generators Work', 'vitalstack' ) ) ),
				array( 'title' => __( 'Fine-tuning and Hugging Face', 'vitalstack' ), 'path' => 'fine-tuning', 'premium' => true, 'about' => __( 'Open models from the Hub, when to fine-tune, and LoRA and QLoRA on one GPU: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Using Open Models from the Hugging Face Hub', 'vitalstack' ), __( 'When to Fine-Tune (and When Not To)', 'vitalstack' ), __( 'Fine-Tuning a Small Model with LoRA and QLoRA', 'vitalstack' ) ) ),
				array( 'title' => __( 'Safe and Reliable AI Apps', 'vitalstack' ), 'path' => 'ai-safety', 'premium' => true, 'about' => __( 'Prompt injection defence, guardrails, moderation and privacy, and testing LLM output with evals: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Prompt Injection and How to Defend Against It', 'vitalstack' ), __( 'Guardrails, Moderation and Privacy', 'vitalstack' ), __( 'Testing and Evaluating LLM Output', 'vitalstack' ) ) ),
				array( 'title' => __( 'AI Coding Assistants', 'vitalstack' ), 'path' => 'ai-coding-assistants', 'premium' => true, 'about' => __( 'Copilot, Cursor and Claude Code compared, prompting for code with tests, and safe AI-written code: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'GitHub Copilot, Cursor and Claude Code Compared', 'vitalstack' ), __( 'Prompting for Code: Specs, Tests and Reviews', 'vitalstack' ), __( 'Working Safely with AI-Written Code', 'vitalstack' ) ) ),
				array( 'title' => __( 'Deploying AI Apps', 'vitalstack' ), 'path' => 'deploy-ai-apps', 'premium' => true, 'about' => __( 'A Streamlit chat UI, an AI API with FastAPI, and Docker and cloud deployment: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'A Chat UI with Streamlit', 'vitalstack' ), __( 'An AI API with FastAPI', 'vitalstack' ), __( 'Docker and Cloud Deployment', 'vitalstack' ) ) ),
				array( 'title' => __( 'Capstone Projects', 'vitalstack' ), 'path' => 'genai-capstones', 'premium' => true, 'about' => __( 'Support chatbot, chat with your PDFs, research agent and a spaced-repetition study buddy: 4 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Capstone: Customer Support Chatbot over a Company FAQ', 'vitalstack' ), __( 'Capstone: Chat with Your PDFs (RAG)', 'vitalstack' ), __( 'Capstone: Research Agent that Searches and Summarises', 'vitalstack' ), __( 'Capstone: AI Study Buddy that Makes Quizzes from Notes', 'vitalstack' ) ) ),
				array( 'title' => __( 'Signature AI Projects', 'vitalstack' ), 'path' => 'ai-signature', 'premium' => true, 'about' => __( 'A scam-SMS shield for English and Hinglish messages with rules, privacy masking and Claude, and a kirana restock agent with tool use, a budget cap and owner approval.', 'vitalstack' ), 'lessons' => array( __( 'Signature Project 5: Scam SMS Shield with Claude', 'vitalstack' ), __( 'Signature Project 6: Kirana Restock Agent', 'vitalstack' ) ) ),
			),
		),
		'agentic-ai-engineer' => array(
			'title'    => __( 'Agentic AI Engineer', 'vitalstack' ),
			'icon'     => '🦾',
			'tagline'  => __( 'Design, build, test and ship AI agents that plan, use tools, remember and work together, safely.', 'vitalstack' ),
			'for'      => __( 'Developers who know Python and some prompt engineering, and want the most in-demand AI skill of 2026.', 'vitalstack' ),
			'outcomes' => array(
				__( 'Explain the plan → act → observe loop, and know when an agent is the wrong tool', 'vitalstack' ),
				__( 'Give agents tools with function calling, JSON schemas, retries and error handling', 'vitalstack' ),
				__( 'Add short-term, long-term and vector memory', 'vitalstack' ),
				__( 'Build an MCP server and connect it to an agent', 'vitalstack' ),
				__( 'Test agents with evals and tracing, and defend them against prompt injection', 'vitalstack' ),
				__( 'Ship 4 capstone agents: research, support, coding and browser', 'vitalstack' ),
			),
			'modules'  => array(
				array( 'title' => __( 'Python Foundations', 'vitalstack' ), 'path' => 'python', 'about' => __( 'Functions, dictionaries, JSON, files and errors: what every agent is built from.', 'vitalstack' ) ),
				array( 'title' => __( 'Prompt Engineering', 'vitalstack' ), 'path' => 'prompt-engineering', 'about' => __( 'Clear instructions, examples, roles and structured output: the language agents think in.', 'vitalstack' ) ),
				array( 'title' => __( 'Agent Basics', 'vitalstack' ), 'path' => 'ai-agents', 'premium' => true, 'about' => __( 'The plan → act → observe loop, when not to use an agent, and your first agent with tools, errors and a step limit: 3 lessons with runnable Python.', 'vitalstack' ), 'lessons' => array( __( 'What an agent is: the plan → act → observe loop', 'vitalstack' ), __( 'Workflows vs agents: when not to use an agent', 'vitalstack' ), __( 'Your first agent in 40 lines of Python', 'vitalstack' ) ) ),
				array( 'title' => __( 'Tool Use in Depth', 'vitalstack' ), 'path' => 'agent-tools', 'premium' => true, 'about' => __( 'JSON schemas, errors, retries, timeouts and idempotency, and designing tools an LLM uses well: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Function Calling and JSON Schemas in Depth', 'vitalstack' ), __( 'Errors, Retries and Timeouts', 'vitalstack' ), __( 'Designing Tools an LLM Can Use Well', 'vitalstack' ) ) ),
				array( 'title' => __( 'Memory and State', 'vitalstack' ), 'path' => 'agent-memory', 'premium' => true, 'about' => __( 'The context window, long-term memory and summaries, and vector memory with embeddings: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Short-Term Memory: the Context Window', 'vitalstack' ), __( 'Long-Term Memory and Summaries', 'vitalstack' ), __( 'Vector Memory with Embeddings', 'vitalstack' ) ) ),
				array( 'title' => __( 'Reasoning Patterns', 'vitalstack' ), 'path' => 'agent-reasoning', 'premium' => true, 'about' => __( 'ReAct, plan-and-execute and reflection with self-correction: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'ReAct: Reason and Act', 'vitalstack' ), __( 'Plan-and-Execute', 'vitalstack' ), __( 'Reflection and Self-Correction', 'vitalstack' ) ) ),
				array( 'title' => __( 'MCP: Model Context Protocol', 'vitalstack' ), 'path' => 'mcp', 'premium' => true, 'about' => __( 'How MCP works, building an MCP server, and connecting it to an agent: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'What MCP Is and Why It Matters', 'vitalstack' ), __( 'Build an MCP Server', 'vitalstack' ), __( 'Connect an MCP Server to an Agent', 'vitalstack' ) ) ),
				array( 'title' => __( 'Frameworks Compared', 'vitalstack' ), 'path' => 'agent-frameworks', 'premium' => true, 'about' => __( 'LangGraph, CrewAI, the Claude Agent SDK and OpenAI Agents SDK, and how to choose: 4 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'LangGraph in Depth', 'vitalstack' ), __( 'CrewAI in Depth', 'vitalstack' ), __( 'Claude Agent SDK and OpenAI Agents SDK', 'vitalstack' ), __( 'Choosing (or Skipping) a Framework', 'vitalstack' ) ) ),
				array( 'title' => __( 'Multi-Agent Systems', 'vitalstack' ), 'path' => 'multi-agent', 'premium' => true, 'about' => __( 'Orchestrator and workers, hand-offs and shared state: 2 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Orchestrator and Workers', 'vitalstack' ), __( 'Hand-offs and Shared State', 'vitalstack' ) ) ),
				array( 'title' => __( 'Evals and Tracing', 'vitalstack' ), 'path' => 'agent-evals', 'premium' => true, 'about' => __( 'Test sets for agents, LLM-as-judge and tracing: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Test Sets for Agents', 'vitalstack' ), __( 'LLM-as-Judge', 'vitalstack' ), __( 'Tracing and Observability', 'vitalstack' ) ) ),
				array( 'title' => __( 'Safety', 'vitalstack' ), 'path' => 'agent-safety', 'premium' => true, 'about' => __( 'Prompt injection in agents, permissions and human approval, and sandboxes: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Prompt Injection in Agents and How to Defend', 'vitalstack' ), __( 'Permissions and Human Approval', 'vitalstack' ), __( 'Sandboxes for Code-Running Agents', 'vitalstack' ) ) ),
				array( 'title' => __( 'Production', 'vitalstack' ), 'path' => 'agent-production', 'premium' => true, 'about' => __( 'Cost and latency, caching, deployment and monitoring: 3 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Cost and Latency', 'vitalstack' ), __( 'Caching', 'vitalstack' ), __( 'Deployment and Monitoring', 'vitalstack' ) ) ),
				array( 'title' => __( 'Capstone Projects', 'vitalstack' ), 'path' => 'agent-capstones', 'premium' => true, 'about' => __( 'Research, customer support, coding and browser agents: 4 animated lessons with runnable, tested code.', 'vitalstack' ), 'lessons' => array( __( 'Capstone: Research Agent', 'vitalstack' ), __( 'Capstone: Customer Support Agent with Tools', 'vitalstack' ), __( 'Capstone: Coding Agent', 'vitalstack' ), __( 'Capstone: Browser Agent', 'vitalstack' ) ) ),
			),
		),
	);
}

function vitalstack_premium_url( $track ) {
	return home_url( '/premium/' . $track . '/' );
}

/* ── Access ───────────────────────────────────────────────────────────── */

function vitalstack_user_has_premium( $track, $user_id = null ) {
	$user_id = null === $user_id ? get_current_user_id() : (int) $user_id;
	if ( ! $user_id ) {
		return false;
	}
	if ( user_can( $user_id, 'edit_posts' ) ) {
		return true; // site staff can always review the material
	}
	// 1 = access with no end date; a larger number = access until that timestamp.
	$access = (array) get_user_meta( $user_id, 'vs_premium', true );
	$value  = isset( $access[ $track ] ) ? (int) $access[ $track ] : 0;
	return 1 === $value || $value > time();
}

/**
 * Whether a reader has any paid premium access right now (staff not counted).
 */
function vitalstack_reader_has_any_premium( $user_id = null ) {
	$user_id = null === $user_id ? get_current_user_id() : (int) $user_id;
	if ( ! $user_id ) {
		return false;
	}
	$access = get_user_meta( $user_id, 'vs_premium', true );
	foreach ( (array) $access as $v ) {
		if ( 1 === (int) $v || (int) $v > time() ) {
			return true;
		}
	}
	return false;
}

/**
 * The premium track a lesson belongs to ('' for free lessons).
 */
function vitalstack_lesson_track( $post_id = null ) {
	if ( ! function_exists( 'vitalstack_tutorial_path' ) ) {
		return '';
	}
	$path = vitalstack_tutorial_path( $post_id );
	if ( ! $path ) {
		return '';
	}
	$track = (string) get_term_meta( $path->term_id, 'vs_premium', true );
	return isset( vitalstack_premium_tracks()[ $track ] ) ? $track : '';
}

/**
 * The premium track of a whole learning path ('' for free paths).
 */
function vitalstack_path_track( $path_slug ) {
	$term = get_term_by( 'slug', $path_slug, 'tutorial_category' );
	if ( ! $term ) {
		return '';
	}
	$track = (string) get_term_meta( $term->term_id, 'vs_premium', true );
	return isset( vitalstack_premium_tracks()[ $track ] ) ? $track : '';
}

/**
 * Premium lessons: a short preview and a lock for readers without access.
 */
function vitalstack_premium_gate( $content ) {
	if ( 'tutorials' !== get_post_type() ) {
		return $content;
	}
	$track = vitalstack_lesson_track();
	if ( ! $track ) {
		return $content;
	}
	if ( vitalstack_user_has_premium( $track ) ) {
		// Paying readers get a shell; the body comes from the protected lesson API (inc/protect.php).
		if ( function_exists( 'vitalstack_protect_applies' ) && vitalstack_protect_applies() && in_the_loop() && is_main_query() ) {
			return vitalstack_protect_shell( get_the_ID() );
		}
		return $content;
	}
	return vitalstack_premium_preview( $content, $track );
}
add_filter( 'the_content', 'vitalstack_premium_gate', 99 );
add_filter( 'the_content_feed', 'vitalstack_premium_gate', 99 );

// The REST API (used by apps and the block editor) gets the same preview.
add_filter(
	'rest_prepare_tutorials',
	function ( $response, $post ) {
		$track = vitalstack_lesson_track( $post->ID );
		if ( $track && ! vitalstack_user_has_premium( $track ) && isset( $response->data['content']['rendered'] ) ) {
			$response->data['content']['rendered'] = vitalstack_premium_preview( $response->data['content']['rendered'], $track );
			$response->data['content']['protected'] = true;
		}
		return $response;
	},
	10,
	2
);

function vitalstack_premium_preview( $content, $track ) {
	// First two paragraphs as a preview; the rest never leaves the server.
	preg_match_all( '#<p[^>]*>.*?</p>#s', $content, $m );
	$preview = implode( '', array_slice( $m[0], 0, 2 ) );
	$t       = vitalstack_premium_tracks()[ $track ];
	return $preview . '<div class="premium-lock"><p class="premium-lock-title">🔒 ' . esc_html__( 'This is a premium lesson', 'vitalstack' ) . '</p><p>'
		/* translators: %s: track name */
		. esc_html( sprintf( __( 'It is part of the %s career track.', 'vitalstack' ), $t['title'] ) ) . '</p>'
		. '<p class="premium-lock-perks">🎬 ' . esc_html__( 'Live animations', 'vitalstack' ) . ' · 🧪 ' . esc_html__( 'Tested code', 'vitalstack' ) . ' · 💼 ' . esc_html__( 'Projects', 'vitalstack' ) . ' · 🎓 ' . esc_html__( 'Certificate', 'vitalstack' ) . '</p>'
		. ( function_exists( 'vitalstack_pay_ready' ) && vitalstack_pay_ready()
			? '<a class="btn btn-primary" href="' . esc_url( vitalstack_pricing_url() . '#plan-' . $track ) . '">' . esc_html__( 'Unlock this lesson', 'vitalstack' ) . '</a> '
			: '' )
		. '<a class="btn btn-ghost" href="' . esc_url( vitalstack_premium_url( $track ) ) . '">' . esc_html__( 'See the track', 'vitalstack' ) . '</a>'
		. ( is_user_logged_in() ? '' : '<p class="premium-small">' . esc_html__( 'Already a member?', 'vitalstack' ) . ' <a href="' . esc_url( vitalstack_account_url( array( 'redirect_to' => get_permalink() ) ) ) . '">' . esc_html__( 'Sign in', 'vitalstack' ) . '</a></p>' )
		. '</div>';
}

// Locked lessons stay out of search results (their text isn't on the page).
add_filter(
	'wp_robots',
	function ( $robots ) {
		if ( is_singular( 'tutorials' ) && vitalstack_lesson_track() ) {
			$robots['noindex'] = true;
		}
		return $robots;
	}
);

/* ── Waitlist ─────────────────────────────────────────────────────────── */

function vitalstack_handle_waitlist() {
	$track = isset( $_POST['track'] ) ? sanitize_key( wp_unslash( $_POST['track'] ) ) : '';
	if ( ! isset( vitalstack_premium_tracks()[ $track ] ) ) {
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}
	$back = vitalstack_premium_url( $track );
	if ( ! is_user_logged_in() ) {
		// After signing up they land back on the track with a "one last tap" nudge (4.12.3).
		wp_safe_redirect( vitalstack_account_url( array( 'tab' => 'register', 'redirect_to' => add_query_arg( 'vs_join', '1', $back ) ) ) );
		exit;
	}
	if ( ! isset( $_POST['_vsnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_vsnonce'] ), 'vs_waitlist_' . $track ) ) {
		wp_safe_redirect( add_query_arg( 'vs_msg', 'bad_nonce', $back ) );
		exit;
	}
	update_user_meta( get_current_user_id(), 'vs_waitlist_' . $track, time() );
	wp_safe_redirect( add_query_arg( 'vs_msg', 'waitlisted', $back ) );
	exit;
}
add_action( 'admin_post_vs_waitlist', 'vitalstack_handle_waitlist' );
add_action( 'admin_post_nopriv_vs_waitlist', 'vitalstack_handle_waitlist' );

add_filter(
	'vitalstack_account_messages',
	function ( $msgs ) {
		$msgs['waitlisted'] = array( 'success', __( 'You’re on the waitlist! We’ll email you first when this track opens, with the early-bird price.', 'vitalstack' ) );
		return $msgs;
	}
);

/* ── Admin: give access, see the waitlist ─────────────────────────────── */

function vitalstack_premium_profile_fields( $user ) {
	if ( ! current_user_can( 'edit_users' ) ) {
		return;
	}
	$access = (array) get_user_meta( $user->ID, 'vs_premium', true );
	?>
	<h2><?php esc_html_e( 'VitalStack premium access', 'vitalstack' ); ?></h2>
	<table class="form-table" role="presentation">
		<?php foreach ( vitalstack_premium_tracks() as $slug => $t ) : ?>
			<tr>
				<th scope="row"><?php echo esc_html( $t['title'] ); ?></th>
				<td>
					<label><input type="checkbox" name="vs_premium[<?php echo esc_attr( $slug ); ?>]" value="1" <?php checked( ! empty( $access[ $slug ] ) ); ?>> <?php esc_html_e( 'Has access', 'vitalstack' ); ?></label>
					<?php if ( get_user_meta( $user->ID, 'vs_waitlist_' . $slug, true ) ) : ?>
						<span class="description"> · <?php esc_html_e( 'on the waitlist', 'vitalstack' ); ?></span>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php
	wp_nonce_field( 'vs_premium_profile', '_vs_premium_nonce' );
}
add_action( 'show_user_profile', 'vitalstack_premium_profile_fields' );
add_action( 'edit_user_profile', 'vitalstack_premium_profile_fields' );

function vitalstack_premium_profile_save( $user_id ) {
	if ( ! current_user_can( 'edit_users' ) || ! isset( $_POST['_vs_premium_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_vs_premium_nonce'] ), 'vs_premium_profile' ) ) {
		return;
	}
	$given  = isset( $_POST['vs_premium'] ) ? (array) wp_unslash( $_POST['vs_premium'] ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- keys checked below
	$access = array();
	foreach ( array_keys( vitalstack_premium_tracks() ) as $slug ) {
		if ( ! empty( $given[ $slug ] ) ) {
			$access[ $slug ] = 1;
		}
	}
	update_user_meta( $user_id, 'vs_premium', $access );
}
add_action( 'personal_options_update', 'vitalstack_premium_profile_save' );
add_action( 'edit_user_profile_update', 'vitalstack_premium_profile_save' );

add_action(
	'wp_dashboard_setup',
	function () {
		if ( ! current_user_can( 'list_users' ) ) {
			return;
		}
		wp_add_dashboard_widget(
			'vitalstack_waitlist',
			__( 'VitalStack premium waitlist', 'vitalstack' ),
			function () {
				global $wpdb;
				echo '<ul>';
				foreach ( vitalstack_premium_tracks() as $slug => $t ) {
					$n = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->usermeta} WHERE meta_key = %s", 'vs_waitlist_' . $slug ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
					echo '<li><strong>' . esc_html( $t['title'] ) . ':</strong> ' . esc_html( sprintf( /* translators: %d: people */ _n( '%d person', '%d people', $n, 'vitalstack' ), $n ) ) . '</li>';
				}
				echo '</ul>';
			}
		);
	}
);

/* ── /premium/<track>/ page ───────────────────────────────────────────── */

function vitalstack_premium_rewrite() {
	add_rewrite_rule( '^premium/([a-z0-9-]+)/?$', 'index.php?vs_premium=$matches[1]', 'top' );
}
add_action( 'init', 'vitalstack_premium_rewrite' );

add_filter(
	'query_vars',
	function ( $vars ) {
		$vars[] = 'vs_premium';
		return $vars;
	}
);

add_filter(
	'pre_handle_404',
	function ( $handled ) {
		$t = get_query_var( 'vs_premium' );
		return ( $t && isset( vitalstack_premium_tracks()[ $t ] ) ) ? true : $handled;
	}
);

function vitalstack_premium_template() {
	$slug = get_query_var( 'vs_premium' );
	if ( ! $slug ) {
		return;
	}
	$tracks = vitalstack_premium_tracks();
	if ( ! isset( $tracks[ $slug ] ) ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		return;
	}
	$t = $tracks[ $slug ];
	status_header( 200 );
	add_filter(
		'pre_get_document_title',
		function () use ( $t ) {
			/* translators: %s: track name */
			return sprintf( __( '%s Career Track (Premium)', 'vitalstack' ), $t['title'] ) . ' | ' . get_bloginfo( 'name' );
		}
	);
	add_action(
		'wp_head',
		function () use ( $t, $slug ) {
			echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $t['tagline'] ) ) . '">' . "\n";
			echo '<link rel="canonical" href="' . esc_url( vitalstack_premium_url( $slug ) ) . '">' . "\n";
		},
		2
	);
	get_header();
	include VITALSTACK_DIR . '/templates/premium-track.php';
	get_footer();
	exit;
}
add_action( 'template_redirect', 'vitalstack_premium_template', 6 );

/**
 * Number of free lessons already published in a path.
 */
function vitalstack_premium_free_count( $path_slug ) {
	$term = get_term_by( 'slug', $path_slug, 'tutorial_category' );
	return ( $term && function_exists( 'vitalstack_path_lessons' ) ) ? count( vitalstack_path_lessons( $term ) ) : 0;
}

/**
 * Homepage band linking the two tracks.
 */
function vitalstack_home_premium() {
	?>
	<section class="home-premium">
		<div class="wrap">
			<div class="home-section-head">
				<p class="eyebrow">⭐ <?php esc_html_e( 'Career tracks', 'vitalstack' ); ?></p>
				<h2><?php esc_html_e( 'Become job-ready, step by step', 'vitalstack' ); ?></h2>
				<p><?php esc_html_e( 'Complete roadmaps that start with our free courses and go all the way to job-ready projects.', 'vitalstack' ); ?></p>
			</div>
			<?php if ( function_exists( 'vitalstack_premium_trailer' ) ) : ?>
				<div class="trailer-wrap"><?php vitalstack_premium_trailer( 'home' ); ?></div>
			<?php endif; ?>
			<div class="premium-cards">
				<?php foreach ( vitalstack_premium_tracks() as $slug => $t ) : ?>
					<a class="premium-card" href="<?php echo esc_url( vitalstack_premium_url( $slug ) ); ?>">
						<span class="premium-card-icon" aria-hidden="true"><?php echo esc_html( $t['icon'] ); ?></span>
						<span class="premium-badge"><?php echo function_exists( 'vitalstack_pay_ready' ) && vitalstack_pay_ready() ? esc_html__( 'Premium', 'vitalstack' ) : esc_html__( 'Premium · Coming soon', 'vitalstack' ); ?></span>
						<b><?php echo esc_html( $t['title'] ); ?></b>
						<span class="premium-card-text"><?php echo esc_html( $t['tagline'] ); ?></span>
						<span class="premium-card-meta"><?php echo esc_html( sprintf( /* translators: %d: modules */ __( '%d modules · free modules open now', 'vitalstack' ), count( $t['modules'] ) ) ); ?> ❯</span>
					</a>
				<?php endforeach; ?>
			</div>
			<?php if ( function_exists( 'vitalstack_pricing_url' ) ) : ?>
				<p class="home-premium-more"><a class="btn btn-ghost" href="<?php echo esc_url( vitalstack_pricing_url() ); ?>"><?php esc_html_e( 'See plans & prices (₹ / $)', 'vitalstack' ); ?> ❯</a></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
}
