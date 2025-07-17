<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Template
 */

get_header();
?>

	<main id="primary" class="site-main">

		<section class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'template' ); ?></h1>
			</header><!-- .page-header -->

			<div class="page-content the-content">
				<h3 class="text-center mb-0"><?php echo 'It looks like nothing was found at this location. Maybe try a <a class="search-trigger">search?</a>'; ?></h3>

			</div><!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
