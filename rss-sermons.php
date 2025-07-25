<?php
header('Content-Type: application/rss+xml; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<rss version="2.0" 
	xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" 
	xmlns:atom="http://www.w3.org/2005/Atom" 
	xmlns:podcast="https://podcastindex.org/namespace/1.0" 
	xmlns:media="http://search.yahoo.com/mrss/" 
	xmlns:content="http://purl.org/rss/1.0/modules/content/">
	<channel>
	<atom:link href="https://thechapelgainesville.com/feeds/sermons" rel="self" type="application/rss+xml" />
	<title>Sermons from The Chapel Gainesville</title>
	<link><?php bloginfo_rss('url'); ?></link>
	<description>Expositional and topical sermons from Brad Williams, Richard Parker, and the elders at The Chapel, a community church in Gainesville, Florida.</description>
	<language><?php bloginfo_rss('language'); ?></language>
	<itunes:author>The Chapel Gainesville</itunes:author>
	<itunes:owner>
		<itunes:name>The Chapel Gainesville</itunes:name>
		<itunes:email>dltownsend3@gmail.com</itunes:email>
	</itunes:owner>
	<itunes:summary>Expositional and topical sermons from Brad Williams, Richard Parker, and the elders at The Chapel, a community church in Gainesville, Florida.</itunes:summary>
	<podcast:txt purpose="applepodcastsverify">7b10e010-666c-11f0-aa9b-0ffc8476ecda</podcast:txt>
	<itunes:explicit>clean</itunes:explicit>
	<itunes:category text="Religion &amp; Spirituality">
		<itunes:category text="Christianity"/>
	</itunes:category>
	<itunes:image href="https://thechapelgainesville.com/files/chapelpodcastthumbnail2.jpg" />

	<?php
	$sermons = new WP_Query([
		'post_type' => 'sermons',
		'posts_per_page' => -1,
		'post_status' => 'publish'
	]);

	while ($sermons->have_posts()) : $sermons->the_post();
		$audio_url = '';
		$image_url = '';
		$length = 0;
		$audio_path = '';
		if (get_field('audio_type') === 'Upload here') {
			$audio_url = get_field('audio_upload');
		} elseif (get_field('audio_type') === 'Link from elsewhere') {
			$audio_url = get_field('audio_url');
		}
		$audio_path = ABSPATH . str_replace(home_url('/'), '', $audio_url);
		$length = filesize($audio_path);
		if (!$audio_url) continue;

		$series_terms = get_the_terms(get_the_ID(), 'series');

// First: check the sermon post's own image
$header_type = get_field('header_image_type');
if ($header_type === 'Url') {
	$image_url = get_field('header_image_url');
} else {
	$image_url = get_field('header_image_upload');
}
$image_url = is_array($image_url) ? $image_url['url'] : $image_url;

$series = '';

// If no image found, fall back to first "series" term image
if (empty($image_url)) {
	$series_terms = get_the_terms(get_the_ID(), 'series');
	if ($series_terms && !is_wp_error($series_terms)) {
		$term = $series_terms[0];
		$series = $term->name;
		$acf_term_id = $term->taxonomy . '_' . $term->term_id;

		$term_image_type = get_field('image_type', $acf_term_id);
		if ($term_image_type === 'Upload') {
			$term_image = get_field('image_upload', $acf_term_id);
		} elseif ($term_image_type === 'Url') {
			$term_image = get_field('image_url', $acf_term_id);
		}

		if (!empty($term_image)) {
			$image_url = is_array($term_image) ? $term_image['url'] : $term_image;
		}
	}
}

$book = '';
$book_terms = get_the_terms(get_the_ID(), 'book');
if ($book_terms && !is_wp_error($book_terms)) {
	$term = $book_terms[0];
	$book = $term->name;
}

$speaker = '';
$speaker_terms = get_the_terms(get_the_ID(), 'speaker');
if ($speaker_terms && !is_wp_error($speaker_terms)) {
	$term = $speaker_terms[0];
	$speaker = $term->name;
}


$description = '';
if(get_the_excerpt() !== ''){
	$description = get_the_excerpt();
}else{
	if($speaker !== ''){$description .= $speaker;}
	if($series !== ''){$description .= ' teaches from the series: '.$series;}
	if($book !== ''){$description .= ', in the book of '.$book;}
	if(get_field('passage_start') AND get_field('passage_end')){$description .= ', verses '.get_field('passage_start').' - '.get_field('passage_end');}
	else if(get_field('passage_start')){$description .= ', verse '.get_field('passage_start');}
}

$summary = $description; ?>
		<item>
			<title><?php the_title_rss(); ?></title>
			<description><![CDATA[<?php echo $description; ?>]]></description>
			<itunes:summary><![CDATA[<?php echo $summary; ?>]]></itunes:summary>
			<link><?php the_permalink_rss(); ?></link>
			<guid><?php the_permalink_rss(); ?></guid>
			<pubDate><?php echo get_the_date('D, d M Y H:i:s O'); ?></pubDate>
			<enclosure url="<?php echo esc_url($audio_url); ?>" length="<?php echo  esc_attr($length); ?>" type="audio/mpeg" />
	<?php if ($image_url): ?>
		<itunes:image href="<?php echo esc_url($image_url); ?>" />
	<?php endif; ?>
	</item>

<?php endwhile; wp_reset_postdata(); ?>
	</channel>
</rss>