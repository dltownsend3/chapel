<?php
/**
 * The template for displaying all single sermons
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
// Try to get the header image from the sermon itself
if ( get_field('header_image_type') === 'Url' ) {
    $header_image = get_field('header_image_url');
} else {
    $header_image = get_field('header_image_upload');
}
$header_image = is_array($header_image) ? $header_image['url'] : $header_image;

// If no image on the post, try the first "series" term
if (empty($header_image)) {
    $terms = get_the_terms(get_the_ID(), 'series');

    if ($terms && !is_wp_error($terms)) {
        $term = $terms[0]; // just the first series term
        $acf_term_id = $term->taxonomy . '_' . $term->term_id;

        if (get_field('image_type', $acf_term_id) === 'Upload') {
            $image = get_field('image_upload', $acf_term_id);
        } elseif (get_field('image_type', $acf_term_id) === 'Url') {
            $image = get_field('image_url', $acf_term_id);
        }

        if (!empty($image)) {
            $header_image = is_array($image) ? $image['url'] : $image;
        }
    }
}

// Output header tag
if ($header_image) :
    echo '<header class="page-header has-bg" style="background-image:url(' . esc_url($header_image) . ')">';
else :
    echo '<header class="page-header">';
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
			echo '<div class="content-wrap">';
			echo '<div class="sermon-info">';
			echo '<p class="date">'.get_the_date().'</p>';
			if(get_the_terms( get_the_ID(), 'speaker' )){
				echo '<p class="series"><span>Speaker: </span>'.get_the_terms( get_the_ID(), 'speaker' )[0]->name.'</p>';
				$speaker = get_the_terms( get_the_ID(), 'speaker' )[0]->name;
			}
			if(get_the_terms( get_the_ID(), 'series' )){
				echo '<p class="series"><span>Series: </span>'.get_the_terms( get_the_ID(), 'series' )[0]->name.'</p>';
				$series = get_the_terms( get_the_ID(), 'series' )[0]->name;
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
				$book = get_the_terms( get_the_ID(), 'book' )[0]->name;
			}
			echo '</div>';
			if(get_field('audio_type') === 'Upload here'){
				$audio = get_field('audio_upload');
			}else if(get_field('audio_type') === 'Link from elsewhere'){
				$audio = get_field('audio_url');
			}
			echo '<audio controls><source src="'.$audio.'" type="audio/mpeg"></audio>';
			echo '<div class="download"><a href="'.$audio.'" download><span>Download</span> <i class="fas fa-download"></i></a></div>';

			// Description
			$description = '';
			if(get_the_excerpt() !== ''){
				get_template_part( 'template-parts/content', get_post_type() );
			}else{
				if($speaker !== ''){$description .= $speaker;}
				if($series !== ''){$description .= ' teaches from the series: '.$series;}
				if($book !== ''){$description .= ', in the book of '.$book;}
				if(get_field('passage_start') AND get_field('passage_end')){$description .= ', verses '.get_field('passage_start').' - '.get_field('passage_end');}
				else if(get_field('passage_start')){$description .= ', verse '.get_field('passage_start');}
			}
			echo '<p class="text-center">'.$description.'</p>';
			echo '</div>'; // .content-wrap
			echo '</div>'; // .the-content

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
</article><!-- #post-<?php the_ID(); ?> -->

<?php
get_sidebar();
get_footer();
