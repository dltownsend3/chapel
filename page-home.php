<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Template
 */

get_header();
/* Template Name: Home */
$root = get_template_directory_uri();
?>

	<main id="primary" class="site-main">

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="home-header">
				<h1><?php echo get_the_title(); ?></h1>
				<?php echo get_the_content(); ?>
				<div class="home-cta">
					<a href="<?php echo get_the_permalink(3200); ?>" class="visit">Plan Your <span>Visit&nbsp;<i class="fas fa-handshake-angle"></i></span></a>
					<div>
						<h3>Stream</h3>
						<div>
							<a href="<?php echo get_option('spotify'); ?>" target=_><i class="fab fa-spotify"></i><span>Spotify</span></a>
							<a href="<?php echo get_option('apple'); ?>" target=_><i class="fas fa-podcast"></i><span>Apple</span></a>
						</div>
					</div>
					<div>
						<h3>Follow</h3>
						<div>
							<a href="<?php echo get_option('facebook'); ?>" target=_><i class="fab fa-facebook"></i></a>
							<a href="<?php echo get_option('instagram'); ?>" target=_><i class="fab fa-instagram"></i></a>
						</div>
					</div>
				</div>
			</div>
			<div class="get-involved">
				<h2>Get Involved</h2>
				<div class="involved-btns">
					<a href="<?php echo get_the_permalink(3193); ?>">
						<div><img src="<?php echo $root; ?>/images/lifegroups.jpg" alt="Life Groups"></div>
						<h3><span>Life Groups</span> <i class="fas fa-book-open"></i></h3>
					</a>
					<a href="/events/">
						<div><img src="<?php echo $root; ?>/images/events.jpg" alt="Events"></div>
						<h3><span>Events</span> <i class="fas fa-calendar"></i></h3>
					</a>
					<a href="<?php echo get_the_permalink(3196); ?>">
						<div><img src="<?php echo $root; ?>/images/youthgroup.jpg" alt="Youth Group"></div>
						<h3><span>Youth Group</span> <i class="fas fa-volleyball"></i></h3>
					</a>
				</div>
				<h2>Upcoming Events</h2>
				<div class="event-btns">
<?php 
$event_query = new WP_Query( array(
    'post_type'      => 'events',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
) );
if ( $event_query->have_posts() ) :
	while ( $event_query->have_posts() ) : $event_query->the_post(); ?>
					<a href="<?php echo get_the_permalink(); ?>">
						<h3><?php echo get_the_title(); ?></h3>
						<?php 
						if(get_field('start_date')){echo '<span class="date">'.get_field('start_date');}
						if(get_field('start_time')){echo ' at '.get_field('start_time');}
						if(get_field('end_date')){echo ' &mdash; '.get_field('end_date');}
						if(get_field('end_time')){echo ' at '.get_field('end_time');}
						echo '</span>';
						 ?>
						<?php the_excerpt(); ?>
						<span class="more">Find Out More</span>
					</a>
    <?php endwhile;
	wp_reset_postdata();
else :
    echo '<p>No upcoming events found.</p>';
endif;
 ?>
				</div>
				<a href="/events/" class="calendar">View all Events <i class="fas fa-calendar"></i></a>
			</div>
			<div class="location">
				<h2>Sunday Service Location: Oak Hall School</h2>
				<p>Our main weekly worship service is at Oak Hall School in the fine arts auditorium, Sunday mornings at 10:30 AM. Adult Bible Class is at 9 AM. (See the map below). <br><br><strong>1700 SW 75th Street. Gainesville, FL 32607</strong></p>
				<a href="<?php echo get_the_permalink(3187); ?>">View Map <i class="fas fa-map-location-dot"></i></a>
			</div>
			<div class="sermon">
				<h2>Latest Sermon</h2>
				<?php 
$args = array(
    'post_type'      => 'sermons',
    'posts_per_page' => 1,
    'post_status'    => 'publish'
);
$latest_sermon = new WP_Query( $args );

if ( $latest_sermon->have_posts() ) : ?>
    <?php while ( $latest_sermon->have_posts() ) : $latest_sermon->the_post(); ?>
        <span class="date"><?php echo get_the_date(); ?> by <?php echo get_the_terms( get_the_ID(), 'speaker' )[0]->name; ?></span>
        <a href="<?php echo get_the_permalink(); ?>">
            <h3><?php echo get_the_title(); ?></h3>
            <span>Listen <i class="fas fa-headphones"></i></span>
        </a>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>
<?php else : ?>
    <p>No recent sermons found.</p>
<?php endif; ?>
			</div>
			<div class="word">
				<h2>A Word of Hope</h2>
				<blockquote>
					<p>&ldquo;<?php echo get_field('scripture'); ?>&rdquo;</p>
					<cite>&mdash;<?php echo get_field('reference'); ?></cite>
				</blockquote>
				<a href="<?php echo get_the_permalink(3200); ?>">Searching?</a>
			</div>
		</article>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
