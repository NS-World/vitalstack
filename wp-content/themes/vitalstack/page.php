<?php
/**
 * Default page.
 *
 * @package VitalStack
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<header class="page-hero page-hero-sm">
		<div class="container narrow">
			<h1 class="page-title"><?php the_title(); ?></h1>
		</div>
	</header>
	<section class="section section-tight">
		<div class="container narrow">
			<div class="prose"><?php the_content(); ?></div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
