<?php
/**
 * Template Name: Datasets Hub
 *
 * Free practice datasets for data analysts: download as CSV or JSON, read the
 * column dictionary, preview rows and try the practice questions.
 *
 * @package VitalStack
 */

get_header();
$vs_sets = vitalstack_datasets();
?>
<section class="dsh-hero">
	<div class="wrap dsh-hero-inner">
		<div>
			<p class="eyebrow"><?php esc_html_e( 'Free · No sign-up · Practice-ready', 'vitalstack' ); ?></p>
			<h1><?php the_title(); ?></h1>
			<p class="dsh-sub"><?php echo esc_html( sprintf( /* translators: %d: number of datasets */ __( '%d realistic datasets for data analyst and data science practice: sales, HR attrition, fraud, churn, air quality, deliveries, house prices and student results. Each comes with a column dictionary, a preview, practice questions and one-click CSV or JSON download.', 'vitalstack' ), count( $vs_sets ) ) ); ?></p>
			<ul class="dsh-points">
				<li>🧹 <?php esc_html_e( 'Deliberate missing values, duplicates and outliers to clean', 'vitalstack' ); ?></li>
				<li>🇮🇳 <?php esc_html_e( 'Indian cities, rupees, UPI and real-life patterns', 'vitalstack' ); ?></li>
				<li>🔒 <?php esc_html_e( 'Synthetic data: no real people, safe for portfolios', 'vitalstack' ); ?></li>
			</ul>
		</div>
		<div class="dsh-hero-art" aria-hidden="true"><span>📊</span><span>🧮</span><span>📈</span></div>
	</div>
</section>

<div class="wrap dsh-wrap">
	<nav class="dsh-jump" aria-label="<?php esc_attr_e( 'Datasets', 'vitalstack' ); ?>">
		<?php foreach ( $vs_sets as $vs_slug => $vs_d ) : ?>
			<a href="#ds-<?php echo esc_attr( $vs_slug ); ?>"><span aria-hidden="true"><?php echo esc_html( $vs_d['icon'] ); ?></span> <?php echo esc_html( $vs_d['title'] ); ?></a>
		<?php endforeach; ?>
	</nav>

	<?php
	$vs_i = 0;
	foreach ( $vs_sets as $vs_slug => $vs_d ) :
		$vs_url = vitalstack_dataset_url( $vs_slug );
		if ( 3 === $vs_i ) {
			echo function_exists( 'vitalstack_ad_unit' ) ? vitalstack_ad_unit( 'display', 'inline' ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput -- built with escaping
		}
		$vs_i++;
		?>
		<article class="dsh-card" id="ds-<?php echo esc_attr( $vs_slug ); ?>" data-dataset="<?php echo esc_attr( $vs_slug ); ?>" data-url="<?php echo esc_url( $vs_url ); ?>">
			<header class="dsh-card-head">
				<span class="dsh-icon" aria-hidden="true"><?php echo esc_html( $vs_d['icon'] ); ?></span>
				<div>
					<h2><?php echo esc_html( $vs_d['title'] ); ?></h2>
					<p class="dsh-meta"><?php echo esc_html( sprintf( /* translators: 1: rows, 2: columns, 3: size */ __( '%1$s rows · %2$d columns · %3$s KB CSV', 'vitalstack' ), number_format_i18n( $vs_d['rows'] ), count( $vs_d['cols'] ), number_format_i18n( ceil( $vs_d['bytes'] / 1024 ) ) ) ); ?></p>
				</div>
			</header>
			<p class="dsh-desc"><?php echo esc_html( $vs_d['desc'] ); ?></p>
			<div class="dsh-actions">
				<a class="btn btn-primary btn-sm" href="<?php echo esc_url( $vs_url ); ?>" download><?php esc_html_e( '⬇ Download CSV', 'vitalstack' ); ?></a>
				<button type="button" class="btn btn-ghost btn-sm" data-ds-json><?php esc_html_e( '⬇ Download JSON', 'vitalstack' ); ?></button>
				<button type="button" class="btn btn-ghost btn-sm" data-ds-copy><?php esc_html_e( '📋 Copy pandas code', 'vitalstack' ); ?></button>
			</div>
			<div class="dsh-grid">
				<div>
					<h3><?php esc_html_e( 'Columns', 'vitalstack' ); ?></h3>
					<div class="table-scroll"><table class="dsh-cols">
						<thead><tr><th><?php esc_html_e( 'Column', 'vitalstack' ); ?></th><th><?php esc_html_e( 'Type', 'vitalstack' ); ?></th><th><?php esc_html_e( 'Missing', 'vitalstack' ); ?></th></tr></thead>
						<tbody>
						<?php foreach ( $vs_d['cols'] as $vs_c ) : ?>
							<tr><td><code><?php echo esc_html( $vs_c[0] ); ?></code></td><td><?php echo esc_html( $vs_c[1] ); ?></td><td><?php echo $vs_c[2] ? '<b>' . esc_html( number_format_i18n( $vs_c[2] ) ) . '</b>' : '0'; ?></td></tr>
						<?php endforeach; ?>
						</tbody>
					</table></div>
				</div>
				<div>
					<h3><?php esc_html_e( 'Practice questions', 'vitalstack' ); ?></h3>
					<ol class="dsh-q">
						<?php foreach ( $vs_d['questions'] as $vs_q ) : ?>
							<li><?php echo esc_html( $vs_q ); ?></li>
						<?php endforeach; ?>
					</ol>
				</div>
			</div>
			<details class="dsh-preview">
				<summary><?php esc_html_e( 'Preview the first rows', 'vitalstack' ); ?></summary>
				<div class="table-scroll"><table>
					<thead><tr><?php foreach ( $vs_d['cols'] as $vs_c ) : ?><th><?php echo esc_html( $vs_c[0] ); ?></th><?php endforeach; ?></tr></thead>
					<tbody>
					<?php foreach ( $vs_d['preview'] as $vs_row ) : ?>
						<tr><?php foreach ( $vs_row as $vs_v ) : ?><td><?php echo '' === $vs_v ? '<em class="dsh-na">—</em>' : esc_html( $vs_v ); ?></td><?php endforeach; ?></tr>
					<?php endforeach; ?>
					</tbody>
				</table></div>
			</details>
			<pre class="dsh-code"><code>import pandas as pd

df = pd.read_csv("<?php echo esc_html( $vs_url ); ?>")
print(df.shape)
print(df.isna().sum())
df.head()</code></pre>
		</article>
	<?php endforeach; ?>

	<section class="dsh-howto prose">
		<h2><?php esc_html_e( 'How to use these datasets', 'vitalstack' ); ?></h2>
		<p><?php esc_html_e( 'Start with the questions on each card: they go from simple counts to a full model. Clean first (missing values, duplicates, outliers), then explore with charts, then answer the business question in plain words. That is exactly what data analyst interviews and take-home tasks look like.', 'vitalstack' ); ?></p>
		<p><?php esc_html_e( 'You can open a CSV in Excel or Google Sheets, load it with pandas in Jupyter or Google Colab, or import it into SQL. The data is synthetic, so you can publish your analysis in a portfolio or on GitHub freely.', 'vitalstack' ); ?></p>
	</section>
	<?php
	while ( have_posts() ) :
		the_post();
		if ( trim( get_the_content() ) ) :
			?>
			<div class="entry-content"><?php the_content(); ?></div>
			<?php
		endif;
	endwhile;
	?>
</div>
<?php
get_footer();
