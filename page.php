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
?>

	<main id="primary" class="site-main">

				<?php 
				$header_image = '';
				if(get_field('header_image_type') === 'Url'){
					if(get_field('header_image_url')){$header_image = get_field('header_image_url');}
				}else{
					if(get_field('header_image_upload')){$header_image = get_field('header_image_upload');}
				}
				if($header_image !== '') : echo '<header class="page-header has-bg" style="background-image:url('.$header_image.')">';
				else : echo '<header class="page-header">';
				endif;
				 ?>

		<?php 
			the_title( '<h1 class="entry-title">', '</h1>' );
		?>
		</header>
		<div class="the-content">

<?php
if (is_page([3182, 3200, 3187, 3188, 3189, 3191, 3199, 3202, 3203, 3206, 3188])) {
  wp_nav_menu([
    'theme_location' => 'tab_menu1',
    'container' => false,
    'menu_class' => 'nav nav-tabs',
    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
    'walker' => new Bootstrap_Link_Tabs_Walker(),
  ]);
}
?>

<?php
if (is_page([3192, 3212, 3193, 3197, 3194, 3196, 3195, 3198])) {
  wp_nav_menu([
    'theme_location' => 'tab_menu2',
    'container' => false,
    'menu_class' => 'nav nav-tabs',
    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
    'walker' => new Bootstrap_Link_Tabs_Walker(),
  ]);
}
?>


		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', 'page' );
		endwhile; // End of the loop.
		?>
		</article>
		</div>

	</main><!-- #main -->

<?php
get_sidebar();
get_footer();
