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
				array( 'title' => __( 'Exploratory Data Analysis and Feature Engineering', 'vitalstack' ), 'lessons' => array( __( 'An EDA checklist for any new dataset', 'vitalstack' ), __( 'Missing values and outliers: find, decide, fix', 'vitalstack' ), __( 'Encoding categories and scaling numbers', 'vitalstack' ), __( 'Creating features that models can learn from', 'vitalstack' ) ) ),
				array( 'title' => __( 'Machine Learning in Depth', 'vitalstack' ), 'lessons' => array( __( 'Linear and logistic regression, step by step', 'vitalstack' ), __( 'Decision trees, random forests and gradient boosting', 'vitalstack' ), __( 'k-NN, Naive Bayes and SVM', 'vitalstack' ), __( 'Clustering with k-means and DBSCAN', 'vitalstack' ), __( 'PCA and dimensionality reduction', 'vitalstack' ), __( 'Metrics, cross-validation and hyper-parameter tuning', 'vitalstack' ), __( 'Imbalanced data and model explainability', 'vitalstack' ) ) ),
				array( 'title' => __( 'Dashboards with Power BI', 'vitalstack' ), 'lessons' => array( __( 'Connecting data and the data model', 'vitalstack' ), __( 'Measures and DAX basics', 'vitalstack' ), __( 'Interactive reports with slicers and drill-down', 'vitalstack' ) ) ),
				array( 'title' => __( 'Time Series Forecasting', 'vitalstack' ), 'lessons' => array( __( 'Trend, seasonality and moving averages', 'vitalstack' ), __( 'Stationarity and differencing', 'vitalstack' ), __( 'ARIMA forecasting in Python', 'vitalstack' ) ) ),
				array( 'title' => __( 'Deep Learning Foundations', 'vitalstack' ), 'lessons' => array( __( 'Neurons, layers and how a network learns', 'vitalstack' ), __( 'Your first neural network in Keras', 'vitalstack' ), __( 'CNNs for images', 'vitalstack' ), __( 'RNNs and LSTMs for sequences', 'vitalstack' ), __( 'The same models in PyTorch', 'vitalstack' ) ) ),
				array( 'title' => __( 'Portfolio Projects', 'vitalstack' ), 'lessons' => array( __( 'Sales performance analysis and dashboard', 'vitalstack' ), __( 'Customer churn prediction', 'vitalstack' ), __( 'House price prediction', 'vitalstack' ), __( 'Movie recommendation system', 'vitalstack' ), __( 'Retail sales forecasting', 'vitalstack' ), __( 'Image classifier with transfer learning', 'vitalstack' ) ) ),
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
				array( 'title' => __( 'How LLMs Work', 'vitalstack' ), 'lessons' => array( __( 'Tokens and next-token prediction', 'vitalstack' ), __( 'Embeddings: meaning as numbers', 'vitalstack' ), __( 'Transformers and attention, visually', 'vitalstack' ), __( 'Context windows, temperature and sampling', 'vitalstack' ), __( 'Why models hallucinate and how to reduce it', 'vitalstack' ) ) ),
				array( 'title' => __( 'Working with LLM APIs', 'vitalstack' ), 'lessons' => array( __( 'Your first API call (Claude, OpenAI, Gemini)', 'vitalstack' ), __( 'Chat history and system prompts', 'vitalstack' ), __( 'Streaming responses', 'vitalstack' ), __( 'Structured output with JSON schemas', 'vitalstack' ), __( 'Errors, retries, rate limits and cost', 'vitalstack' ), __( 'Keeping API keys and user data safe', 'vitalstack' ) ) ),
				array( 'title' => __( 'Retrieval-Augmented Generation (RAG)', 'vitalstack' ), 'lessons' => array( __( 'Why RAG: answers from your own data', 'vitalstack' ), __( 'Chunking documents', 'vitalstack' ), __( 'Embeddings and vector databases', 'vitalstack' ), __( 'Retrieval, re-ranking and citations', 'vitalstack' ), __( 'Evaluating a RAG app', 'vitalstack' ) ) ),
				array( 'title' => __( 'LangChain and LlamaIndex', 'vitalstack' ), 'lessons' => array( __( 'Chains, prompts and output parsers', 'vitalstack' ), __( 'Memory for chatbots', 'vitalstack' ), __( 'Document loaders and retrievers', 'vitalstack' ) ) ),
				array( 'title' => __( 'AI Agents and Tool Use', 'vitalstack' ), 'lessons' => array( __( 'What makes an agent: plan, act, observe', 'vitalstack' ), __( 'Function calling and tools', 'vitalstack' ), __( 'Multi-step agents with LangGraph', 'vitalstack' ), __( 'Teams of agents with CrewAI', 'vitalstack' ), __( 'MCP: connecting agents to apps and data', 'vitalstack' ) ) ),
				array( 'title' => __( 'Fine-tuning and Hugging Face', 'vitalstack' ), 'lessons' => array( __( 'Using open models from the Hugging Face Hub', 'vitalstack' ), __( 'When to fine-tune (and when not to)', 'vitalstack' ), __( 'Fine-tuning a small model with LoRA', 'vitalstack' ) ) ),
				array( 'title' => __( 'Safe and Reliable AI Apps', 'vitalstack' ), 'lessons' => array( __( 'Prompt injection and how to defend against it', 'vitalstack' ), __( 'Guardrails, moderation and privacy', 'vitalstack' ), __( 'Testing and evaluating LLM output', 'vitalstack' ) ) ),
				array( 'title' => __( 'Deploying AI Apps', 'vitalstack' ), 'lessons' => array( __( 'A chat UI with Streamlit', 'vitalstack' ), __( 'An AI API with FastAPI', 'vitalstack' ), __( 'Docker and cloud deployment', 'vitalstack' ) ) ),
				array( 'title' => __( 'Capstone Projects', 'vitalstack' ), 'lessons' => array( __( 'Customer support chatbot over a company FAQ', 'vitalstack' ), __( 'Chat with your PDFs (RAG)', 'vitalstack' ), __( 'Research agent that searches and summarises', 'vitalstack' ), __( 'AI study buddy that makes quizzes from notes', 'vitalstack' ) ) ),
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
 * Premium lessons: a short preview and a lock for readers without access.
 */
function vitalstack_premium_gate( $content ) {
	if ( 'tutorials' !== get_post_type() ) {
		return $content;
	}
	$track = vitalstack_lesson_track();
	if ( ! $track || vitalstack_user_has_premium( $track ) ) {
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
		wp_safe_redirect( vitalstack_account_url( array( 'tab' => 'register', 'redirect_to' => $back ) ) );
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
