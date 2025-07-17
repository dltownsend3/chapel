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
			echo '<div class="the-content">';
			echo '<div class="sermon-info">';
			echo '<p class="date">'.get_the_date().'</p>';
			if(get_the_terms( get_the_ID(), 'series' )){
				echo '<p class="series"><span>Series: </span>'.get_the_terms( get_the_ID(), 'series' )[0]->name.'</p>';
			}
			if(get_the_terms( get_the_ID(), 'topic' )){
				echo '<p class="topic"><span>Topic: </span>'.get_the_terms( get_the_ID(), 'topic' )[0]->name.'</p>';
			}
			if(get_the_terms( get_the_ID(), 'book' )){
				echo '<p class="passage"><span>Passage: </span>'.get_the_terms( get_the_ID(), 'book' )[0]->name;
				if(get_field('passage_start')){
					echo ' '.get_field('passage_start');
					if(get_field('passage_end')){echo ' - '.get_field('passage_end');}
					echo '</p>';
				}
			}
			echo '</div>';
			if(get_field('audio_type') === 'Upload here'){
				$audio = get_field('audio_upload');
			}else if(get_field('audio_type') === 'Link from elsewhere'){
				$audio = get_field('audio_url');
			}
			echo '<audio controls><source src="'.$audio.'" type="audio/mpeg"></audio>';
			echo '<div class="download"><a href="'.$audio.'" download><span>Download</span> <i class="fas fa-download"></i></a></div>';
			get_template_part( 'template-parts/content', get_post_type() );
			echo '</div>';

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
</article><!-- #post-<?php the_ID(); ?> -->

<?php
get_sidebar();
get_footer();
