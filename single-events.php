<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Template
 */

get_header();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<main id="primary" class="site-main">
		<?php 
		if(get_field('header_image_type') === 'Url'){
			$header_image = get_field('header_image_url');
		}else{
			$header_image = get_field('header_image_upload');
		}
		if($header_image) : echo '<header class="page-header has-bg" style="background-image:url('.$header_image.')">';
		else : echo '<header class="page-header">';
		endif;
			?>

		<?php 
			the_title( '<h1 class="entry-title">', '</h1>' );
		?>
		</header>

		<?php
		while ( have_posts() ) :
			the_post();
			echo '<div class="the-content"><div class="content-wrap">';
			get_template_part( 'template-parts/content', get_post_type() );
			echo '</div></div>';

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
</article><!-- #post-<?php the_ID(); ?> -->

<?php
get_sidebar();
get_footer();
