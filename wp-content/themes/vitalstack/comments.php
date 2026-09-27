<?php
/**
 * Comments.
 *
 * @package VitalStack
 */

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="section-title">
			<?php
			/* translators: %d: number of comments */
			echo esc_html( sprintf( _n( '%d comment', '%d comments', get_comments_number(), 'vitalstack' ), get_comments_number() ) );
			?>
		</h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 0,
				)
			);
			?>
		</ol>
		<?php the_comments_pagination(); ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'        => __( 'Ask a question or leave a comment', 'vitalstack' ),
			'class_submit'       => 'btn btn-primary',
			'comment_notes_before' => '<p class="muted">' . esc_html__( 'Your email is never published.', 'vitalstack' ) . '</p>',
		)
	);
	?>
</section>
