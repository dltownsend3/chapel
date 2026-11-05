<?php
/**
 * RSS Feed Template for Sermons
 */

header('Content-Type: application/rss+xml; charset=' . get_option('blog_charset'), true);

echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?' . '>';
?>

<rss version="2.0"
    xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:atom="http://www.w3.org/2005/Atom">

<channel>
    <title><?php bloginfo_rss('name'); ?> Sermons</title>
    <link><?php bloginfo_rss('url'); ?></link>
    <description><?php bloginfo_rss('description'); ?></description>
    <language>en-us</language>
    <itunes:author><?php bloginfo_rss('name'); ?></itunes:author>
    <itunes:explicit>false</itunes:explicit>
    <itunes:owner>
        <itunes:name><?php bloginfo_rss('name'); ?></itunes:name>
        <itunes:email><?php echo esc_html(get_option('admin_email')); ?></itunes:email>
    </itunes:owner>
    <itunes:image href="<?php echo esc_url(get_site_icon_url()); ?>" />

    <?php
    $args = array(
        'post_type'      => 'sermons',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    );

    $sermons = new WP_Query($args);

    while ($sermons->have_posts()) :
        $sermons->the_post();

        // Audio
        $audio_type = get_field('audio_type');
        $audio_url  = '';

        if ($audio_type === 'Upload here') {
            $audio_upload = get_field('audio_upload');

            if (is_array($audio_upload)) {
                $audio_url = $audio_upload['url'] ?? '';
            } else {
                $audio_url = $audio_upload;
            }

        } elseif ($audio_type === 'Link from elsewhere') {
            $audio_url = get_field('audio_url');
        }

        if (empty($audio_url)) {
            continue;
        }

        // Audio file length
        $audio_path = ABSPATH . str_replace(home_url('/'), '', $audio_url);
        $audio_length = 0;

        if (file_exists($audio_path)) {
            $audio_length = filesize($audio_path);
        }

        // Get taxonomy terms
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

        // Sermon image
        $image_url = '';

        if (get_field('header_image_type') === 'Url') {
            $image_url = get_field('header_image_url');
        } else {
            $header_image_upload = get_field('header_image_upload');

            if (is_array($header_image_upload)) {
                $image_url = $header_image_upload['url'] ?? '';
            } else {
                $image_url = $header_image_upload;
            }
        }

        // Series image fallback
        if (empty($image_url) && $series_terms && !is_wp_error($series_terms)) {

            $series_term = $series_terms[0];
            $acf_term_id = $series_term->taxonomy . '_' . $series_term->term_id;

            $term_image_type = get_field('image_type', $acf_term_id);
            $term_image = '';

            if ($term_image_type === 'Upload') {
                $term_image = get_field('image_upload', $acf_term_id);
            } elseif ($term_image_type === 'Url') {
                $term_image = get_field('image_url', $acf_term_id);
            }

            if (!empty($term_image)) {
                $image_url = is_array($term_image) ? $term_image['url'] : $term_image;
            }
        }

        // Description
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

        // Book and passage take precedence over series
        if ($book && $passage_start) {

            $passage = $book . ' ' . $passage_start;

            if ($passage_end) {
                $passage .= '-' . $passage_end;
            }

            $description = $speaker
                ? $speaker . ' teaches from ' . $passage
                : 'A teaching from ' . $passage;

        } elseif ($series) {

            $description = $speaker
                ? $speaker . ' teaches from the series: ' . $series
                : 'A teaching from the series: ' . $series;

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

        // Append excerpt
        $excerpt = get_the_excerpt();

        if (!empty(get_the_content()) && !empty($excerpt)) {
            $description .= ' | ' . $excerpt;
        }

        $summary = $description;
    ?>

    <item>
        <title><?php the_title_rss(); ?></title>

        <description><![CDATA[<?php echo $description; ?>]]></description>

        <itunes:summary><![CDATA[<?php echo $summary; ?>]]></itunes:summary>

        <link><?php the_permalink_rss(); ?></link>

        <guid isPermaLink="true"><?php the_permalink_rss(); ?></guid>

        <itunes:explicit>false</itunes:explicit>

        <pubDate><?php echo get_post_time('r', true); ?></pubDate>

        <enclosure
            url="<?php echo esc_url($audio_url); ?>"
            length="<?php echo esc_attr($audio_length); ?>"
            type="audio/mpeg" />

        <?php if (!empty($image_url)) : ?>
            <itunes:image href="<?php echo esc_url($image_url); ?>" />
        <?php endif; ?>

    </item>

    <?php endwhile; ?>

    <?php wp_reset_postdata(); ?>

</channel>
</rss>