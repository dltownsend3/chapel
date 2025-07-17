<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Template
 */

?>

	<header class="entry-header">
		<?php
		if ( !is_singular() ) :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				template_posted_on();
				template_posted_by();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php template_post_thumbnail(); ?>
	<div class="entry-content">
		<?php
		if(is_archive()){
			the_excerpt(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'template' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
		}else{

			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'template' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
		}

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'template' ),
				'after'  => '</div>',
			)
		);
if(is_singular()){
	the_post_navigation(
		array(
			'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'template' ) . '</span> <span class="nav-title">%title</span>',
			'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'template' ) . '</span> <span class="nav-title">%title</span>',
		)
	);
}
		?>
	
<?php if (is_singular()){echo '</div>';} ?>

	<footer class="entry-footer">
		<?php template_entry_footer(); ?>
	</footer><!-- .entry-footer -->
