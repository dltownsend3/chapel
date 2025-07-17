<?php $root = get_template_directory_uri(); ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="search-box" style="display:none;">
	<input type="text" class="search-text" placeholder="Search for something">
	<button class="search-submit"><i class="fas fa-paper-plane"></i></button>
	<button class="search-close"><i class="fas fa-xmark"></i></button>
</div>
<header id="masthead" class="site-header">
	<nav id="site-navigation" class="main-navigation">
		<?php echo '<a href="'.get_the_permalink(6).'" id="logo"><img src="'.$root.'/images/logow.svg" alt="Logo"></a>'; ?>
		<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="Navigation Toggle">
			<span class="topline"></span>
			<span class="midline"></span>
			<span class="botline"></span>
		</button>
		<div id="primary-menu">
			<ul id="menu-ul" class="menu nav-menu desktop-nav">
				<?php 
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id' => 'menu-ul',
						'menu' => 'Menu',
						'container' => false,
						'items_wrap' => '%3$s',
						'link_before' => '<span>',
						'link_after'  => '</span>',
					)
				);
				?>
				<li class="menu-item">
					<button class="search-open">
						<i class="fas fa-magnifying-glass"></i>
					</button>
				</li>
			</ul>
			<ul id="menu-ul" class="menu nav-menu mobile-nav">
				<?php 
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id' => 'menu-ul',
						'menu' => 'Menu',
						'container' => false,
						'items_wrap' => '%3$s',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'depth' => 1
					)
				);
				?>
				<li class="menu-item">
					<button class="search-open">
						<i class="fas fa-magnifying-glass"></i>
					</button>
				</li>
			</ul>
			<div class="social">
			<?php if( get_option('facebook') OR get_option('x_twitter') OR get_option('instagram') ){
				if ( get_option('facebook') ) 
					echo '<a href="'.get_option('facebook').'" class="facebook" target="_blank"><i class="fab fa-facebook"></i></a>';
				if ( get_option('x_twitter') ) 
					echo '<a href="'.get_option('x_twitter').'" class="twitter" target="_blank"><i class="fab fa-x-twitter"></i></a>';
				if ( get_option('instagram') ) 
					echo '<a href="'.get_option('instagram').'" class="instagram" target="_blank"><i class="fab fa-instagram"></i></a>';
			} ?>
			</div>
		</div>
	</nav><!-- #site-navigation -->
</header><!-- #masthead -->
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'template' ); ?></a>

