<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Template
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
<?php if ( is_tax() || is_category() || is_tag() ) : ?>
  <h1 class="page-title"><?php single_term_title(); ?></h1>
<?php elseif ( is_post_type_archive() ) : ?>
  <h1 class="page-title"><?php post_type_archive_title(); ?></h1>
<?php endif; ?>

				<?php the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
			echo '<div class="the-content">';
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				
				echo '<article id="post-' . get_the_ID() . '" class="' . implode( ' ', get_post_class( '', get_the_ID() ) ) . '">';
				get_template_part( 'template-parts/content', get_post_type() );
				echo '</article>';

			endwhile;

			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => __('« Prev'),
				'next_text' => __('Next »'),
			) );

			echo '</div>';

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
