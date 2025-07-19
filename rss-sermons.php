<?php
echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?' . '>';
?>
<rss version="2.0"
     xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
     xmlns:content="http://purl.org/rss/1.0/modules/content/">
  <channel>
    <title>Sermons from The Chapel Gainesville</title>
    <link><?php bloginfo_rss('url'); ?></link>
    <description>Expositional and topical sermons from Brad Williams, Richard Parker, and the elders. </description>
    <language><?php bloginfo_rss('language'); ?></language>
    <itunes:author>The Chapel Gainesville</itunes:author>
    <itunes:owner>
      <itunes:name>The Chapel Gainesville</itunes:name>
      <itunes:email>dltownsend3@gmail.com</itunes:email>
    </itunes:owner>
    <itunes:podcast-verify-token>852a9b00-6418-11f0-a106-1755f851bb64</itunes:podcast-verify-token>
    <podcast:locked>yes</podcast:locked>
    <itunes:explicit>no</itunes:explicit>
    <itunes:category text="Religion & Spirituality"/>
    <itunes:image href="https://thechapelgainesville.com/images/chapelpodcastthumbnail.png" />

    <?php
    $sermons = new WP_Query([
        'post_type' => 'sermons',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ]);

    while ($sermons->have_posts()) : $sermons->the_post();
        $audio_url = '';
        if (get_field('audio_type') === 'Upload here') {
            $audio_url = get_field('audio_upload');
        } elseif (get_field('audio_type') === 'Link from elsewhere') {
            $audio_url = get_field('audio_url');
        }

        if (!$audio_url) continue;

        $series_terms = get_the_terms(get_the_ID(), 'series');

$image_url = '';

// First: check the sermon post's own image
$header_type = get_field('header_image_type');
if ($header_type === 'Url') {
    $image_url = get_field('header_image_url');
} else {
    $image_url = get_field('header_image_upload');
}
$image_url = is_array($image_url) ? $image_url['url'] : $image_url;

// If no image found, fall back to first "series" term image
if (empty($image_url)) {
    $series_terms = get_the_terms(get_the_ID(), 'series');
    if ($series_terms && !is_wp_error($series_terms)) {
        $term = $series_terms[0];
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

?>
    <item>
      <title><?php the_title_rss(); ?></title>
      <description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
      <link><?php the_permalink_rss(); ?></link>
      <guid><?php the_permalink_rss(); ?></guid>
      <pubDate><?php echo get_the_date('D, d M Y H:i:s O'); ?></pubDate>
      <enclosure url="<?php echo esc_url($audio_url); ?>" type="audio/mpeg" />
      <?php if ($image_url): ?>
      <itunes:image href="<?php echo esc_url($image_url); ?>" />
      <?php endif; ?>
    </item>

    <?php endwhile; wp_reset_postdata(); ?>
  </channel>
</rss>
