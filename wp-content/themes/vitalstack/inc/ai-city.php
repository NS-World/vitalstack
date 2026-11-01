<?php
/**
 * AI City (4.12): a scroll tour of a 3D miniature city where every district is
 * one Gen AI or agent idea. The words are rendered here as normal HTML (so they
 * are readable without JavaScript and by search engines); assets/js/ai-city.js
 * draws the 3D city behind them and flies the camera to each district.
 *
 * @package VitalStack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The tour stops, in order. x/z place the district on the island; zoom is how
 * close the camera gets. "words" link to the AI Dictionary, "path" to a course.
 */
function vitalstack_ai_city_stops() {
	return array(
		array(
			'id'      => 'data-mines',
			'place'   => __( 'Data Mines', 'vitalstack' ),
			'title'   => __( 'It all starts with data', 'vitalstack' ),
			'simple'  => __( 'An AI model learns the way you learn a language: by reading a huge amount of it. Before training, companies collect trillions of words from books, websites, code and conversations, then clean out spam, repeats and private details.', 'vitalstack' ),
			'example' => __( 'A child who has heard thousands of conversations can guess how a sentence ends. A model has "heard" more text than any human could read in a thousand lifetimes.', 'vitalstack' ),
			'words'   => array( 'training-data', 'dataset', 'parameters' ),
			'path'    => 'ai-models',
			'mood'    => 'explain',
			'x'       => -36,
			'z'       => -26,
			'zoom'    => 2.2,
		),
		array(
			'id'      => 'token-factory',
			'place'   => __( 'Token Factory', 'vitalstack' ),
			'title'   => __( 'Words become tokens', 'vitalstack' ),
			'simple'  => __( 'Computers only understand numbers, so text is cut into small pieces called tokens: whole words, parts of words or punctuation. Every token gets an ID number. "unbelievable" might become "un", "believ", "able".', 'vitalstack' ),
			'example' => __( 'Like a ticket counter that gives every word a token number. AI prices and limits are counted in tokens: roughly 1 token is 3/4 of an English word.', 'vitalstack' ),
			'words'   => array( 'token', 'tokenizer', 'context-window' ),
			'path'    => 'llm-basics',
			'mood'    => 'point',
			'x'       => -12,
			'z'       => -32,
			'zoom'    => 2.4,
		),
		array(
			'id'      => 'embedding-park',
			'place'   => __( 'Embedding Park', 'vitalstack' ),
			'title'   => __( 'Meaning becomes a map', 'vitalstack' ),
			'simple'  => __( 'Each token is turned into a list of numbers called an embedding. Think of it as an address on a giant map of meaning: words with similar meanings live close together, so "king" is near "queen" and far from "pizza".', 'vitalstack' ),
			'example' => __( 'Like Google Maps for words. Search engines and RAG apps use these addresses to find text that means the same thing even when the words are different.', 'vitalstack' ),
			'words'   => array( 'embedding', 'vector', 'vector-database' ),
			'path'    => 'llm-basics',
			'mood'    => 'wow',
			'x'       => 14,
			'z'       => -30,
			'zoom'    => 2.3,
		),
		array(
			'id'      => 'transformer-tower',
			'place'   => __( 'Transformer Tower', 'vitalstack' ),
			'title'   => __( 'Attention: who should listen to whom?', 'vitalstack' ),
			'simple'  => __( 'Inside the model is a tall stack of layers called a transformer. In every layer, each word "pays attention" to the other words that matter for it. In "The cat sat because it was tired", attention links "it" to "cat".', 'vitalstack' ),
			'example' => __( 'Like a classroom where every student can instantly ask any other student for help. After dozens of layers, the model predicts the most likely next token, then the next, and so on.', 'vitalstack' ),
			'words'   => array( 'transformer', 'attention', 'next-token-prediction' ),
			'path'    => 'llm-basics',
			'mood'    => 'think',
			'x'       => 36,
			'z'       => -18,
			'zoom'    => 1.8,
		),
		array(
			'id'      => 'training-gym',
			'place'   => __( 'Training Gym', 'vitalstack' ),
			'title'   => __( 'Practice, mistakes, practice again', 'vitalstack' ),
			'simple'  => __( 'Training means showing the model a sentence, hiding the next word, letting it guess, and nudging billions of numbers (parameters) so the next guess is better. Then fine-tuning and human feedback (RLHF) teach it to be helpful and safe.', 'vitalstack' ),
			'example' => __( 'Like a cricketer in the nets: thousands of balls, small corrections after every miss, then a coach who teaches match manners.', 'vitalstack' ),
			'words'   => array( 'pretraining', 'fine-tuning', 'rlhf' ),
			'path'    => 'ai-models',
			'mood'    => 'cheer',
			'x'       => 34,
			'z'       => 8,
			'zoom'    => 2.2,
		),
		array(
			'id'      => 'prompt-cafe',
			'place'   => __( 'Prompt Café', 'vitalstack' ),
			'title'   => __( 'How you ask decides what you get', 'vitalstack' ),
			'simple'  => __( 'A prompt is your instruction to the AI. Clear prompts give a role, the task, examples and the format you want. Temperature controls creativity: low for facts, higher for ideas.', 'vitalstack' ),
			'example' => __( '"Write something about Python" gets a random essay. "You are a teacher. Explain Python lists to a 12-year-old in 5 lines with one cricket example" gets exactly what you need.', 'vitalstack' ),
			'words'   => array( 'prompt', 'system-prompt', 'temperature', 'hallucination' ),
			'path'    => 'prompt-engineering',
			'mood'    => 'wave',
			'x'       => 12,
			'z'       => 26,
			'zoom'    => 2.4,
		),
		array(
			'id'      => 'rag-library',
			'place'   => __( 'RAG Library', 'vitalstack' ),
			'title'   => __( 'Look it up, then answer', 'vitalstack' ),
			'simple'  => __( 'A model only knows what it saw in training. Retrieval-Augmented Generation (RAG) first searches your own documents, picks the most relevant pieces, and gives them to the model with the question, so answers come from your data with sources.', 'vitalstack' ),
			'example' => __( 'An open-book exam. Instead of answering from memory, the student first finds the right page in the textbook and quotes it.', 'vitalstack' ),
			'words'   => array( 'rag', 'chunking', 'retrieval', 'citation' ),
			'path'    => 'rag',
			'mood'    => 'explain',
			'x'       => -14,
			'z'       => 30,
			'zoom'    => 2.3,
		),
		array(
			'id'      => 'tool-workshop',
			'place'   => __( 'Tool Workshop', 'vitalstack' ),
			'title'   => __( 'Giving AI hands: tools', 'vitalstack' ),
			'simple'  => __( 'On its own a model can only write text. With function calling (tool use), it can ask your program to do things: check the weather, search orders, send an email. The model asks; your code does the work and returns the result.', 'vitalstack' ),
			'example' => __( 'A manager who can\'t leave the office but can phone the right person: "Delivery team, where is order 1042?"', 'vitalstack' ),
			'words'   => array( 'tool-use', 'function-calling', 'api' ),
			'path'    => 'llm-agents',
			'mood'    => 'point',
			'x'       => -38,
			'z'       => 12,
			'zoom'    => 2.3,
		),
		array(
			'id'      => 'agent-hq',
			'place'   => __( 'Agent HQ', 'vitalstack' ),
			'title'   => __( 'Agents: plan, act, observe, repeat', 'vitalstack' ),
			'simple'  => __( 'An agent is an AI that works in a loop: it plans the next step, uses a tool, looks at the result, and decides again, until the job is done. Watch the little bots run between HQ and the workshops.', 'vitalstack' ),
			'example' => __( 'Booking a trip: search flights, check the budget, the first one is too costly, check the next one, it fits, book it. A chatbot just answers; an agent gets it done.', 'vitalstack' ),
			'words'   => array( 'ai-agent', 'agent-loop', 'react', 'multi-agent' ),
			'path'    => 'ai-agents',
			'mood'    => 'cheer',
			'x'       => -2,
			'z'       => 0,
			'zoom'    => 1.9,
		),
		array(
			'id'      => 'memory-vault',
			'place'   => __( 'Memory Vault', 'vitalstack' ),
			'title'   => __( 'Remembering what matters', 'vitalstack' ),
			'simple'  => __( 'A model forgets everything between messages. Apps give it memory: the recent chat (short-term), saved facts and summaries (long-term), and searchable past notes stored as embeddings (vector memory).', 'vitalstack' ),
			'example' => __( 'A good tutor\'s notebook: "Meera prefers Hinglish, is weak in SQL joins, exam in 3 weeks." Next class starts from there.', 'vitalstack' ),
			'words'   => array( 'memory', 'context-window', 'vector-database' ),
			'path'    => 'agent-memory',
			'mood'    => 'think',
			'x'       => -20,
			'z'       => -12,
			'zoom'    => 2.4,
		),
		array(
			'id'      => 'mcp-port',
			'place'   => __( 'MCP Port', 'vitalstack' ),
			'title'   => __( 'One plug for every app', 'vitalstack' ),
			'simple'  => __( 'The Model Context Protocol (MCP) is an open standard for connecting AI apps to tools and data. Build a connector once as an MCP server, and any MCP app can use it: ships from every island dock at the same port.', 'vitalstack' ),
			'example' => __( 'USB-C for AI. One cable type works with every phone and laptop; one protocol works with every AI app and service.', 'vitalstack' ),
			'words'   => array( 'mcp', 'mcp-server', 'api' ),
			'path'    => 'mcp',
			'mood'    => 'wave',
			'x'       => 0,
			'z'       => 48,
			'zoom'    => 1.9,
		),
		array(
			'id'      => 'guardrail-gate',
			'place'   => __( 'Guardrail Gate', 'vitalstack' ),
			'title'   => __( 'Safety first', 'vitalstack' ),
			'simple'  => __( 'Text from the internet can hide instructions that try to trick an AI (prompt injection). Safe apps check inputs and outputs, give agents only the tools they need, and ask a human before anything risky like payments.', 'vitalstack' ),
			'example' => __( 'A bank locker: the staff can open some drawers alone, need a manager\'s key for others, and some doors never open for anyone.', 'vitalstack' ),
			'words'   => array( 'prompt-injection', 'guardrails', 'human-in-the-loop' ),
			'path'    => 'ai-safety',
			'mood'    => 'think',
			'x'       => 40,
			'z'       => 30,
			'zoom'    => 2.3,
		),
		array(
			'id'      => 'eval-lab',
			'place'   => __( 'Eval Lab', 'vitalstack' ),
			'title'   => __( 'Prove it works', 'vitalstack' ),
			'simple'  => __( 'Evals are exams for AI apps: a fixed set of test questions with automatic checks. Run them after every change to know if the app got better or worse, and trace every step to see where it went wrong.', 'vitalstack' ),
			'example' => __( 'A weekly class test. You don\'t trust "I studied a lot"; you trust the marks.', 'vitalstack' ),
			'words'   => array( 'evals', 'llm-as-judge', 'tracing' ),
			'path'    => 'agent-evals',
			'mood'    => 'point',
			'x'       => 24,
			'z'       => 48,
			'zoom'    => 2.4,
		),
		array(
			'id'      => 'launch-pad',
			'place'   => __( 'Launch Pad', 'vitalstack' ),
			'title'   => __( 'Ship it to real users', 'vitalstack' ),
			'simple'  => __( 'Finally, the app goes live: a chat page or an API in the cloud, with limits on cost, caching to save money, and dashboards that watch speed, errors and spending.', 'vitalstack' ),
			'example' => __( 'Opening day of a restaurant: the recipes are tested, the kitchen is ready, and someone watches the till and the queue.', 'vitalstack' ),
			'words'   => array( 'deployment', 'latency', 'prompt-caching' ),
			'path'    => 'deploy-ai-apps',
			'mood'    => 'cheer',
			'x'       => -36,
			'z'       => 38,
			'zoom'    => 2.2,
		),
	);
}

function vitalstack_ai_city_url() {
	$page = get_page_by_path( 'ai-city' );
	return $page ? get_permalink( $page ) : home_url( '/ai-city/' );
}

function vitalstack_ai_city_assets() {
	if ( ! is_page_template( 'page-ai-city.php' ) ) {
		return;
	}
	wp_enqueue_script( 'vitalstack-three', vitalstack_asset_url( 'assets/vendor/three.min.js' ), array(), '0.160.0', array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_enqueue_script( 'vitalstack-nova', vitalstack_asset_url( 'assets/js/nova.js' ), array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_enqueue_script( 'vitalstack-ai-city', vitalstack_asset_url( 'assets/js/ai-city.js' ), array( 'vitalstack-three', 'vitalstack-nova' ), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
}
add_action( 'wp_enqueue_scripts', 'vitalstack_ai_city_assets', 26 );

/**
 * Homepage banner that invites visitors into the AI City tour and the AI Dictionary.
 */
function vitalstack_home_ai_city() {
	$city = get_page_by_path( 'ai-city' );
	if ( ! $city || 'publish' !== $city->post_status ) {
		return;
	}
	$dict = get_page_by_path( 'ai-dictionary' );
	wp_enqueue_script( 'vitalstack-nova', vitalstack_asset_url( 'assets/js/nova.js' ), array(), VITALSTACK_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	?>
	<section class="home-aic">
		<div class="wrap home-aic-inner">
			<div class="home-aic-copy">
				<p class="eyebrow"><?php esc_html_e( 'New · No coding needed', 'vitalstack' ); ?></p>
				<h2><?php esc_html_e( 'Understand Gen AI and AI agents in 5 minutes, in 3D', 'vitalstack' ); ?></h2>
				<p><?php esc_html_e( 'Fly over a tiny AI City where every building is one idea: tokens, embeddings, attention, RAG, tools, agents, MCP and safety, each explained in plain words with a real-life example.', 'vitalstack' ); ?></p>
				<div class="home-aic-cta">
					<a class="btn btn-primary" href="<?php echo esc_url( get_permalink( $city ) ); ?>"><?php esc_html_e( '🏙️ Start the AI City tour', 'vitalstack' ); ?></a>
					<?php if ( $dict && 'publish' === $dict->post_status ) : ?>
						<a class="btn btn-ghost" href="<?php echo esc_url( get_permalink( $dict ) ); ?>"><?php esc_html_e( '📖 AI Dictionary', 'vitalstack' ); ?></a>
					<?php endif; ?>
				</div>
			</div>
			<a class="home-aic-art" href="<?php echo esc_url( get_permalink( $city ) ); ?>" aria-label="<?php esc_attr_e( 'Open the AI City tour', 'vitalstack' ); ?>">
				<span class="home-aic-isle" aria-hidden="true">
					<span class="hai-b hai-1">🏭</span><span class="hai-b hai-2">🏛️</span><span class="hai-b hai-3">🗼</span><span class="hai-b hai-4">🛡️</span><span class="hai-b hai-5">🚀</span><span class="hai-b hai-6">⚓</span>
				</span>
				<span class="home-aic-nova" data-nova="wave" aria-hidden="true"></span>
			</a>
		</div>
		<script>document.addEventListener('DOMContentLoaded',function(){var n=document.querySelector('.home-aic-nova');if(n&&window.VSNova)n.innerHTML=window.VSNova.svg('wave',120);});</script>
	</section>
	<?php
}
