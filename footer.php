<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Template
 */

$root = get_template_directory_uri();
?>

	<footer id="colophon" class="site-footer">
		<?php echo '<a href="'.get_the_permalink(6).'" class="logo"><img src="'.$root.'/images/logob.svg" alt="Logo"></a>'; ?>
		<div class="social">
			<?php 
			if(get_option('facebook')){echo '<a href="'.get_option('facebook').'" class="fab fa-facebook" target=_></a>';}
			if(get_option('instagram')){echo '<a href="'.get_option('instagram').'" class="fab fa-instagram" target=_></a>';}
			if(get_option('spotify')){echo '<a href="'.get_option('spotify').'" class="fab fa-spotify" target=_></a>';}
			if(get_option('apple')){echo '<a href="'.get_option('apple').'" class="fas fa-podcast" target=_></a>';}
			echo '<a href="'.get_the_permalink(3208).'" class="fas fa-envelope"></a>';
			 ?>
		</div>
		<div class="cta">
			<a href="<?php echo get_the_permalink(3208); ?>"><span>Contact</span> <i class="fas fa-envelope-open"></i></a>
			<a href="<?php echo get_the_permalink(3205); ?>"><span>Give</span> <i class="fas fa-hand-holding-dollar"></i></a>
			<a href="<?php echo get_the_permalink(3181); ?>"><span>Sermons</span> <i class="fas fa-book-open"></i></a>
		</div>
		<div class="site-info">
			<p class="address"><a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode(get_option('address')); ?>" target=_><?php echo get_option('address'); ?></a></p>
			<p class="copyright">&copy;<?php echo date('Y') . ' ' . get_bloginfo('name'); ?></p>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
