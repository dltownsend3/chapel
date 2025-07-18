<?php
echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?' . '>';
?>
<rss version="2.0"
     xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
     xmlns:content="http://purl.org/rss/1.0/modules/content/">
  <channel>
    <title><?php bloginfo_rss('name'); ?> - Sermons</title>
    <link><?php bloginfo_rss('url'); ?></link>
    <description><?php bloginfo_rss('description'); ?></description>
    <language><?php bloginfo_rss('language'); ?></language>
    <itunes:author>Your Church Name</itunes:author>
    <itunes:explicit>no</itunes:explicit>
    <itunes:category text="Religion & Spirituality"/>
    <itunes:image href="https://thechapelgainesville.com/path-to-artwork.jpg" />

    <?php
    $sermons = new WP_Query([
        'post_type' => 'sermons',
        'posts_per_page' => 20,
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
        ?>
        <item>
            <title><?php the_title_rss(); ?></title>
            <description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
            <link><?php the_permalink_rss(); ?></link>
            <guid><?php the_permalink_rss(); ?></guid>
            <pubDate><?php echo get_the_date('D, d M Y H:i:s O'); ?></pubDate>
            <enclosure url="<?php echo esc_url($audio_url); ?>" type="audio/mpeg" />
            <itunes:duration><?php the_field('duration'); ?></itunes:duration>
        </item>
    <?php endwhile; wp_reset_postdata(); ?>
  </channel>
</rss>
