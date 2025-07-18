<?php
/**
 * Template Name: Sermon Taxonomies
 */
get_header();
if ( is_tax() ) {
    $term = get_queried_object();

    if ( $term && ! is_wp_error( $term ) ) {
        $taxonomy_object = get_taxonomy( $term->taxonomy );
        $taxonomy_label = $taxonomy_object->labels->singular_name;
        $taxTitle = $taxonomy_label . ': ' . $term->name;
    } else {
        $taxTitle = get_the_title(); // fallback just in case
    }
}
else if ( is_page(3288) ) {
    $taxTitle = 'Sermons by Book of the Bible';
}
else if ( is_page(3301) ) {
    $taxTitle = 'Sermons by Speaker';
}
else if ( is_page(3303) ) {
    $taxTitle = 'Sermons by Series';
}
else if ( is_page(3305) ) {
    $taxTitle = 'Sermons by Topic';
}
else {
    $taxTitle = get_the_title();
}
?>

<!-- Series Thumbnails and default header -->
<main class="site-main">
<?php
if ( is_tax() ) {
    $term = get_queried_object();

    if ( $term && ! is_wp_error( $term ) ) {
        $thumbnail_url = '';

        if ( $term->taxonomy === 'series' ) {
            // For ACF fields on taxonomy terms, use "taxonomy_termID"
            $acf_term_id = $term->taxonomy . '_' . $term->term_id;

            $image = '';
            $image_type = get_field( 'image_type', $acf_term_id );

            if ( $image_type === 'Upload' ) {
                $image = get_field( 'image_upload', $acf_term_id );
            } else if ( $image_type === 'Url' ) {
                $image = get_field( 'image_url', $acf_term_id );
            }

            if ( $image ) {
                $thumbnail_url = is_array( $image ) ? $image['url'] : $image;
            }
        }

        if ( $thumbnail_url ) {
            // Show header with background image
            ?>
            <header class="page-header has-bg" style="background-image:url(<?php echo esc_url( $thumbnail_url ); ?>);">
                <h1><?php echo esc_html( $taxTitle ); ?></h1>
            </header>
            <?php
        } else {
            // No background image — show simple header
            ?>
            <header class="page-header">
                <h1><?php echo esc_html( $taxTitle ); ?></h1>
            </header>
            <?php
        }
    } else {
        // Invalid term — fallback header
        ?>
        <header class="page-header">
            <h1><?php echo esc_html( $taxTitle ); ?></h1>
        </header>
        <?php
    }
} else {
    // Not a taxonomy archive — fallback header (if needed)
    ?>
    <header class="page-header">
        <h1><?php echo esc_html( $taxTitle ); ?></h1>
    </header>
    <?php
}
?>


<div class="the-content">
<?php 
the_content();

?>
<div class="pills-box">
    <strong>Filter By:&nbsp;&nbsp;</strong>
    <div class="btn-group sermon-pills" role="group" aria-label="Sermon Taxonomy Navigation">

    <a href="<?= get_post_type_archive_link( 'sermons' ); ?>"
        class="btn btn-outline-primary">
        All Sermons
    </a>

    <a href="<?= esc_url(get_permalink(3288)); ?>"
        class="btn btn-outline-primary<?= (is_page(3288) OR is_tax('book')) ? ' active' : ''; ?>">
        Books
    </a>

    <a href="<?= esc_url(get_permalink(3301)); ?>"
        class="btn btn-outline-primary<?= (is_page(3301) OR is_tax('speaker')) ? ' active' : ''; ?>">
        Speakers
    </a>

    <a href="<?= esc_url(get_permalink(3303)); ?>"
        class="btn btn-outline-primary<?= (is_page(3303) OR is_tax('series')) ? ' active' : ''; ?>">
        Series
    </a>

    <a href="<?= esc_url(get_permalink(3305)); ?>"
        class="btn btn-outline-primary<?= (is_page(3305) OR is_tax('topic')) ? ' active' : ''; ?>">
        Topics
    </a>

    </div>
</div>
<?php 
if(is_tax()){
    get_template_part('template-parts/sermons', 'none');
}

// Books
if(is_page(3288)){
    $terms = get_terms([
        'taxonomy'   => 'book',
        'hide_empty' => false,
    ]);

    if (!is_wp_error($terms)) {
        $biblical_order = [
            'genesis', 'exodus', 'leviticus', 'numbers', 'deuteronomy',
            'joshua', 'judges', 'ruth', '1-samuel', '2-samuel',
            '1-kings', '2-kings', '1-chronicles', '2-chronicles',
            'ezra', 'nehemiah', 'esther', 'job', 'psalms', 'proverbs',
            'ecclesiastes', 'song-of-solomon', 'isaiah', 'jeremiah',
            'lamentations', 'ezekiel', 'daniel', 'hosea', 'joel',
            'amos', 'obadiah', 'jonah', 'micah', 'nahum', 'habakkuk',
            'zephaniah', 'haggai', 'zechariah', 'malachi',
            'matthew', 'mark', 'luke', 'john', 'acts', 'romans',
            '1-corinthians', '2-corinthians', 'galatians', 'ephesians',
            'philippians', 'colossians', '1-thessalonians', '2-thessalonians',
            '1-timothy', '2-timothy', 'titus', 'philemon', 'hebrews',
            'james', '1-peter', '2-peter', '1-john', '2-john', '3-john',
            'jude', 'revelation',
        ];

        $old_testament_slugs = array_slice($biblical_order, 0, 39);
        $new_testament_slugs = array_slice($biblical_order, 39);

        $terms_by_slug = [];
        foreach ($terms as $term) {
            $terms_by_slug[$term->slug] = $term;
        }

        echo '<div class="bible-columns">';

        function render_column($title, $slugs, $terms_by_slug) {
            echo '<div class="bible-column">';
            echo '<h2>' . esc_html($title) . '</h2>';

            foreach ($slugs as $slug) {
                if (isset($terms_by_slug[$slug])) {
                    $term = $terms_by_slug[$slug];

                    $sermons = get_posts([
                        'post_type'      => 'sermons',
                        'tax_query'      => [[
                            'taxonomy' => 'book',
                            'field'    => 'slug',
                            'terms'    => $slug,
                        ]],
                        'fields'         => 'ids',
                        'posts_per_page' => -1,
                        'no_found_rows'  => true,
                    ]);

                    $sermon_count = count($sermons);

                    echo '<h3><a href="' . esc_url(get_term_link($term)) . '">'
                        . esc_html($term->name) . '</a> '
                        . '<span class="count">(' . intval($sermon_count) . ')</span></h3>';
                }
            }

            echo '</div>';
        }

        render_column('Old Testament', $old_testament_slugs, $terms_by_slug);
        render_column('New Testament', $new_testament_slugs, $terms_by_slug);

        echo '</div>';
    } else {
        echo '<p>Could not load books: ' . esc_html($terms->get_error_message()) . '</p>';
    }
}

// Speakers
if(is_page(3301)){
    $speakers = get_terms([
        'taxonomy'   => 'speaker',
        'hide_empty' => false,
    ]);

    if (!is_wp_error($speakers) && !empty($speakers)) {
        echo '<div class="text-center"><div class="min-container">';
        foreach ($speakers as $speaker) {
            // Count sermons with this speaker term
            $sermons = get_posts([
                'post_type'      => 'sermons',
                'tax_query'      => [[
                    'taxonomy' => 'speaker',
                    'field'    => 'slug',
                    'terms'    => $speaker->slug,
                ]],
                'fields'         => 'ids',
                'posts_per_page' => -1,
                'no_found_rows'  => true,
            ]);

            $count = count($sermons);
            echo '<h2><a href="' . esc_url(get_term_link($speaker)) . '">'
                . esc_html($speaker->name) . '</a> '
                . '<span class="count">(' . intval($count) . ')</span></h2>';
        }
        echo '</div></div>';
    } else {
        echo '<p>No speakers found.</p>';
    }
}

// Series
if(is_page(3303)){
    $series = get_terms([
        'taxonomy'   => 'series',
        'hide_empty' => false,
    ]);

    if (!is_wp_error($series) && !empty($series)) {
        echo '<div class="text-center"><div class="min-container">';
        foreach ($series as $series) {
            $sermons = get_posts([
                'post_type'      => 'sermons',
                'tax_query'      => [[
                    'taxonomy' => 'series',
                    'field'    => 'slug',
                    'terms'    => $series->slug,
                ]],
                'fields'         => 'ids',
                'posts_per_page' => -1,
                'no_found_rows'  => true,
            ]);

            $count = count($sermons);
            echo '<h2><a href="' . esc_url(get_term_link($series)) . '">'
                . esc_html($series->name) . '</a> '
                . '<span class="count">(' . intval($count) . ')</span></h2>';
        }
        echo '</div></div>';
    } else {
        echo '<p>No series found.</p>';
    }
}


// Topics
if(is_page(3305)){
    $topics = get_terms([
        'taxonomy'   => 'topic',
        'hide_empty' => false,
    ]);

    if (!is_wp_error($topics) && !empty($topics)) {
        echo '<div class="text-center"><div class="min-container">';
        foreach ($topics as $topic) {
            // Count sermons with this speaker term
            $sermons = get_posts([
                'post_type'      => 'sermons',
                'tax_query'      => [[
                    'taxonomy' => 'topic',
                    'field'    => 'slug',
                    'terms'    => $topic->slug,
                ]],
                'fields'         => 'ids',
                'posts_per_page' => -1,
                'no_found_rows'  => true,
            ]);

            $count = count($sermons);
            echo '<h2><a href="' . esc_url(get_term_link($topic)) . '">'
                . esc_html($topic->name) . '</a> '
                . '<span class="count">(' . intval($count) . ')</span></h2>';
        }
        echo '</div></div>';
    } else {
        echo '<p>No topics found.</p>';
    }
}

if(is_tax()){
$currentTax = get_queried_object()->taxonomy;
// Get current term and taxonomy objects
if ( $currentTax === 'book' ) {
    $taxLink = get_the_permalink(3288);
}
else if ( $currentTax === 'speaker' ) {
    $taxLink = get_the_permalink(3301);
}
else if ( $currentTax === 'series' ) {
    $taxLink = get_the_permalink(3303);
}
else if ( $currentTax === 'topic' ) {
    $taxLink = get_the_permalink(3305);
}
$current_term = get_queried_object();
$current_taxonomy = get_taxonomy( $current_term->taxonomy );

// Labels
$tax_plural_label  = $current_taxonomy->labels->name;
$tax_singular_label = $current_taxonomy->labels->singular_name;

?>

<div class="taxonomy-footer">
    <h3><strong>Current <?php echo esc_html( strtolower( $tax_singular_label ) ); ?>:</strong> <?php echo esc_html( $current_term->name ); ?></h3>
    <h3><a href="<?php echo $taxLink; ?>">
        ← Back to all <?php echo esc_html( strtolower( $tax_plural_label ) ); ?>
    </a></h3>
</div>

<?php }
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => __('« Prev'),
				'next_text' => __('Next »'),
			) );

?>


</div>
</main>

<?php get_footer(); ?>
