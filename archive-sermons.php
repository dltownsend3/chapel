<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Template
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php if ( have_posts() ) : ?>
			<?php
			$archive_page_id = 3181;
			$header_image_url = get_field('header_image_url', $archive_page_id);
			if($header_image_url) : echo '<div class="page-header has-bg" style="background-image:url('.$header_image_url.')">';
			else : echo '<div class="page-header">';
			endif;
				?>

			<h1 class="page-title"><?php echo post_type_archive_title(); ?></h1>
			</div>
			<?php the_archive_description( '<div class="archive-description">', '</div>' );
			?>
		</header><!-- .page-header -->

		<?php
		echo '<div class="the-content">'; 

echo_page_content_by_id(3181); // Content from Sermons page

 ?>

<div class="pills-box">
    <strong>Filter By:&nbsp;&nbsp;</strong>
	<div class="btn-group sermon-pills" role="group" aria-label="Sermon Taxonomy Navigation">

	<a 
		class="btn btn-outline-primary<?= is_post_type_archive( 'sermons' ) ? ' active' : ''; ?>">
		All Sermons
	</a>

	<a href="<?= esc_url(get_permalink(3288)); ?>"
		class="btn btn-outline-primary">
		Books
	</a>

	<a href="<?= esc_url(get_permalink(3301)); ?>"
		class="btn btn-outline-primary">
		Speakers
	</a>

	<a href="<?= esc_url(get_permalink(3303)); ?>"
		class="btn btn-outline-primary">
		Series
	</a>

	<a href="<?= esc_url(get_permalink(3305)); ?>"
		class="btn btn-outline-primary">
		Topics
	</a>

	</div>
</div>

<?php 
			get_template_part('template-parts/sermons', 'none');
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => __('« Prev'),
				'next_text' => __('Next »'),
			) );
			echo '</div>';

		else :

		endif;
		?>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
