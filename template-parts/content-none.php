<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Template
 */

?>

<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'template' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content">
<div class="the-content">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) :

			printf(
				'<p>' . wp_kses(
					/* translators: 1: link to WP admin new post page. */
					__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'template' ),
					array(
						'a' => array(
							'href' => array(),
						),
					)
				) . '</p>',
				esc_url( admin_url( 'post-new.php' ) )
			);

		elseif ( is_search() ) :
			?>

			<h3 class="text-center">Sorry, but nothing matched your search terms. <a class="search-trigger">Please try again</a> with some different keywords.</h3>
			<?php 

		else :
			?>

			<h3 class="text-center">It seems we can't find what you're looking for. Perhaps <a class="search-trigger">searching can help</a></h3>
			<?php 

		endif;
		?>
		</div>
	</div><!-- .page-content -->
</section><!-- .no-results -->
