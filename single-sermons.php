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
while (have_posts()) :
	the_post();

	// ----------------------------------------
	// Get sermon header image
	// ----------------------------------------

	$header_image = '';

	if (get_field('header_image_type') === 'Url') {
		$header_image = get_field('header_image_url');
	} else {
		$header_image = get_field('header_image_upload');
	}

	$header_image = is_array($header_image) ? $header_image['url'] : $header_image;

	// ----------------------------------------
	// Get taxonomy terms
	// ----------------------------------------

	$speaker = '';
	$series  = '';
	$book    = '';
	$topic   = '';

	$speaker_terms = get_the_terms(get_the_ID(), 'speaker');
	$series_terms  = get_the_terms(get_the_ID(), 'series');
	$book_terms    = get_the_terms(get_the_ID(), 'book');
	$topic_terms   = get_the_terms(get_the_ID(), 'topic');

	if ($speaker_terms && !is_wp_error($speaker_terms)) {
		$speaker = $speaker_terms[0]->name;
	}

	if ($series_terms && !is_wp_error($series_terms)) {
		$series = $series_terms[0]->name;
	}

	if ($book_terms && !is_wp_error($book_terms)) {
		$book = $book_terms[0]->name;
	}

	if ($topic_terms && !is_wp_error($topic_terms)) {
		$topic = $topic_terms[0]->name;
	}

	// ----------------------------------------
	// Series image fallback
	// ----------------------------------------

	if (empty($header_image) && $series_terms && !is_wp_error($series_terms)) {

		$term = $series_terms[0];
		$acf_term_id = $term->taxonomy . '_' . $term->term_id;

		$image = '';
		$term_image_type = get_field('image_type', $acf_term_id);

		if ($term_image_type === 'Upload') {
			$image = get_field('image_upload', $acf_term_id);
		} elseif ($term_image_type === 'Url') {
			$image = get_field('image_url', $acf_term_id);
		}

		if (!empty($image)) {
			$header_image = is_array($image) ? $image['url'] : $image;
		}
	}

	// ----------------------------------------
	// Output header
	// ----------------------------------------

	if ($header_image) :
		echo '<header class="page-header has-bg" style="background-image:url(' . esc_url($header_image) . ')">';
	else :
		echo '<header class="page-header">';
	endif;

	the_title('<h1 class="entry-title">', '</h1>');

	echo '</header>';

	// ----------------------------------------
	// Sermon content
	// ----------------------------------------

	echo '<div class="the-content">';
	echo '<div class="content-wrap text-center">';
	echo '<div class="sermon-info">';

	echo '<p class="date">' . esc_html(get_the_date()) . '</p>';

	if ($speaker) {
		echo '<p class="series"><span>Speaker: </span>' . esc_html($speaker) . '</p>';
	}

	if ($series) {
		echo '<p class="series"><span>Series: </span>' . esc_html($series) . '</p>';
	}

	if ($topic) {
		echo '<p class="topic"><span>Topic: </span>' . esc_html($topic) . '</p>';
	}

	// Passage display
	if ($book) {

		$passage_start = get_field('passage_start');
		$passage_end   = get_field('passage_end');

		echo '<p class="passage"><span>Passage: </span>' . esc_html($book);

		if ($passage_start) {
			echo ' ' . esc_html($passage_start);

			if ($passage_end) {
				echo '-' . esc_html($passage_end);
			}
		}

		echo '</p>';
	}

	echo '</div>';

	// ----------------------------------------
	// Audio player
	// ----------------------------------------

	$audio = '';

	if (get_field('audio_type') === 'Upload here') {
		$audio = get_field('audio_upload');
	} elseif (get_field('audio_type') === 'Link from elsewhere') {
		$audio = get_field('audio_url');
	}

	// Handle ACF audio upload fields returning an array
	if (is_array($audio)) {
		$audio = $audio['url'] ?? '';
	}

	if ($audio) {
		echo '<audio controls><source src="' . esc_url($audio) . '" type="audio/mpeg"></audio>';

		echo '<div class="download"><a href="' . esc_url($audio) . '" download><span>Download</span> <i class="fas fa-download"></i></a></div>';
	}

	// ----------------------------------------
	// Description
	// ----------------------------------------

	$description = '';

	// Avoid duplicate taxonomy descriptions
	if ($book === $topic) {
		$topic = '';
	}

	if ($book === $series) {
		$series = '';
	}

	$passage_start = get_field('passage_start');
	$passage_end   = get_field('passage_end');

	// Series takes precedence over book/topic
	if ($series) {

		$description = $speaker
			? $speaker . ' teaches from the series: ' . $series
			: 'A teaching from the series: ' . $series;

	} elseif ($book && $passage_start) {

		$passage = $book . ' ' . $passage_start;

		if ($passage_end) {
			$passage .= '-' . $passage_end;
		}

		$description = $speaker
			? $speaker . ' teaches from ' . $passage
			: 'A teaching from ' . $passage;

	} elseif ($book) {

		$description = $speaker
			? $speaker . ' teaches from the book of ' . $book
			: 'A teaching from the book of ' . $book;

	} elseif ($topic) {

		$description = $speaker
			? $speaker . ' teaches from the topic: ' . $topic
			: 'A teaching about ' . $topic;

	} elseif ($speaker) {

		$description = 'A sermon from ' . $speaker;
	}

	echo '<p class="text-center">' . esc_html($description) . '</p>';

	// ----------------------------------------
	// Sermon content
	// ----------------------------------------

	if (!empty(get_the_content())) {
		the_content();
	}

	// ----------------------------------------
	// Previous / next sermon navigation
	// ----------------------------------------

	if (is_singular()) {

		$post_type = get_post_type_object(get_post_type());
		$singular  = $post_type ? $post_type->labels->singular_name : 'Post';

		the_post_navigation(
			array(
				'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous ' . $singular . ':', 'template') . '</span> <span class="nav-title">%title</span>',
				'next_text' => '<span class="nav-subtitle">' . esc_html__('Next ' . $singular . ':', 'template') . '</span> <span class="nav-title">%title</span>',
			)
		);
	}

	echo '</div>'; // .content-wrap
	echo '</div>'; // .the-content

endwhile;
?>

	</main><!-- #main -->
</article><!-- #post-<?php the_ID(); ?> -->

<?php
get_sidebar();
get_footer();