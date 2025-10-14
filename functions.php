<?php
/**
 * Template functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Template
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function template_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Template, use a find and replace
		* to change 'template' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'template', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'template' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'template_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'template_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function template_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'template_content_width', 640 );
}
add_action( 'after_setup_theme', 'template_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function template_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'template' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'template' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'template_widgets_init' );


// Inline fold.css for homepage (ID 6)
function inline_fold_css() {
    if ( !is_page(6) ) return; // Only on homepage

    $css_file = get_template_directory() . '/fold.css';

    if ( file_exists( $css_file ) ) {
        $css_content = file_get_contents( $css_file );

        // Fix relative URLs (images, fonts)
        $css_content = preg_replace_callback(
            '/url\([\'"]?(?!https?:|data:)([^)\'"]+)[\'"]?\)/i',
            function( $matches ) {
                return 'url(' . get_template_directory_uri() . '/' . ltrim( $matches[1], '/' ) . ')';
            },
            $css_content
        );

        echo "<style id='fold-css-inline'>{$css_content}</style>\n";
    }
}
add_action( 'wp_head', 'inline_fold_css', 1 ); // Early in head


// Enqueue home.css deferred
function defer_home_css() {
    if ( !is_page(6) ) return;

    wp_enqueue_style(
        'home-styles',
        get_template_directory_uri() . '/home.css',
        array(),
        filemtime( get_template_directory() . '/home.css' )
    );

    // Make async using media=print trick
    add_filter('style_loader_tag', function($tag, $handle){
        if ($handle === 'home-styles') {
            $tag = str_replace(
                "rel='stylesheet'",
                "rel='stylesheet' media='print' onload=\"this.media='all'\"",
                $tag
            );
        }
        return $tag;
    }, 10, 2);
}
add_action( 'wp_enqueue_scripts', 'defer_home_css', 20 );

/**
 * Enqueue scripts and styles optimized for speed
 */
function template_scripts() {
    wp_style_add_data( 'template-style', 'rtl', 'replace' );

    // jQuery (moved to footer)
    wp_enqueue_script('jquery');

    // Theme scripts
    wp_enqueue_script(
        'template-navigation',
        get_template_directory_uri() . '/js/navigation.js',
        array(),
        filemtime( get_template_directory() . '/js/navigation.js' ),
        true // load in footer
    );

    // Font Awesome in footer
    wp_enqueue_script(
        'fontawesome-kit',
        'https://kit.fontawesome.com/f80f0f2fbe.js',
        array(),
        null,
        true
    );

    // Google Fonts
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=DM+Serif+Display:ital@0;1&family=Outfit:wght@100..900&family=Vollkorn:ital,wght@0,400..900;1,400..900&display=swap',
        array(),
        null
    );

    // Bootstrap (only if NOT homepage, ID 6)
    $bootstrap_deps = [];
    if ( ! is_page(6) ) {
        wp_enqueue_style(
            'bootstrap-css',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
            array(),
            '5.3.3'
        );

        wp_enqueue_script(
            'bootstrap-js',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
            array(),
            '5.3.3',
            true
        );

        $bootstrap_deps[] = 'bootstrap-css';

        // Main stylesheet (interior pages only, depends on Bootstrap if loaded)
        wp_enqueue_style(
            'main-style',
            get_stylesheet_uri(),
            $bootstrap_deps,
            filemtime( get_stylesheet_directory() . '/style.css' )
        );
    }
}
add_action( 'wp_enqueue_scripts', 'template_scripts' );




// -------------------------
// Additional optimizations
// -------------------------

// Remove jQuery Migrate
function remove_jquery_migrate( $scripts ) {
    if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $scripts->registered['jquery']->deps = array_diff(
            $scripts->registered['jquery']->deps,
            ['jquery-migrate']
        );
    }
}
add_action( 'wp_default_scripts', 'remove_jquery_migrate' );

// Move jQuery to footer
function move_jquery_to_footer( $scripts ) {
    if ( ! is_admin() ) {
        if ( isset( $scripts->registered['jquery'] ) ) {
            $scripts->add_data( 'jquery', 'group', 1 );
            $scripts->add_data( 'jquery-core', 'group', 1 );
        }
        if ( isset( $scripts->registered['jquery-migrate'] ) ) {
            $scripts->add_data( 'jquery-migrate', 'group', 1 );
        }
    }
}
add_action( 'wp_default_scripts', 'move_jquery_to_footer' );

// Dequeue Gutenberg block CSS on homepage (ID 6)
function dequeue_block_library_css() {
    if ( is_page(6) && ! is_admin() ) {
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
    }
}
add_action( 'wp_enqueue_scripts', 'dequeue_block_library_css', 100 );

// Google Fonts preconnect
function mytheme_resource_hints($hints, $relation_type) {
    if ('preconnect' === $relation_type) {
        $hints[] = 'https://fonts.googleapis.com';
        $hints[] = [
            'href' => 'https://fonts.gstatic.com',
            'crossorigin' => true
        ];
    }
    return $hints;
}
add_filter('wp_resource_hints', 'mytheme_resource_hints', 10, 2);




/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

add_action('admin_bar_menu', function($wp_admin_bar) {
    if (is_admin()) return;
    $wp_admin_bar->add_node([
        'id'    => 'collapse-admin-bar',
        'title' => 'Collapse',
        'href'  => '#',
        'meta'  => ['class' => 'collapse-admin-bar']
    ]);
}, 999);

add_action('wp_footer', function() {
    if (!is_admin_bar_showing()) return;
    ?>
<style>
    html {
        margin-top: 0 !important;
    }

    #wpadminbar {
        transform: translateY(0);
        transition: transform 0.3s ease;
    }

    #wpadminbar.collapsed {
        transform: translateY(-100%);
    }

    .show-admin-bar-tab {
        display: none;
        position: fixed;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        background: #23282d;
        color: #fff;
        font-size: 13px;
        padding: 5px 12px;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
        z-index: 99999;
        cursor: pointer;
        transition: top 0.3s ease;
    }

    #wpadminbar.collapsed + .show-admin-bar-tab {
        display: block;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const adminBar = document.getElementById('wpadminbar');
  const collapseBtn = document.querySelector('#wp-admin-bar-collapse-admin-bar > .ab-item');
  const html = document.documentElement;

  if (!adminBar || !collapseBtn || !html) return;

  // Create the show-tab element
  const showTab = document.createElement('div');
  showTab.className = 'show-admin-bar-tab';
  showTab.textContent = 'Show Admin Bar';
  adminBar.insertAdjacentElement('afterend', showTab);

  function collapseAdminBar() {
    adminBar.classList.add('collapsed');
    html.style.removeProperty('margin-top');
  }

  function expandAdminBar() {
    adminBar.classList.remove('collapsed');
    html.style.marginTop = adminBar.offsetHeight + 'px';
  }

  collapseBtn.addEventListener('click', function (e) {
    e.preventDefault();
    collapseAdminBar();
  });

  showTab.addEventListener('click', function () {
    expandAdminBar();
  });

  // Start collapsed on page load
  collapseAdminBar();
});
</script>
    <?php
});

// Enable custom post types archive pages in nav menus
function add_post_type_archives_to_menu() {
    add_meta_box(
        'add-post-type-archives',
        'Post Type Archives',
        'post_type_archives_meta_box',
        'nav-menus',
        'side',
        'default'
    );
}

function force_single_book_term( $post_id ) {
    $taxonomy = 'book';
    
    // Get the submitted terms
    if ( isset( $_POST['tax_input'][ $taxonomy ] ) && is_array( $_POST['tax_input'][ $taxonomy ] ) ) {
        // Keep only the first selected term
        $_POST['tax_input'][ $taxonomy ] = [ reset( $_POST['tax_input'][ $taxonomy ] ) ];
    }
}
add_action( 'save_post', 'force_single_book_term', 100 );


function add_flexible_page_slug_to_body_class( $classes ) {
    if ( is_home() ) {
        $title = get_the_title( get_option( 'page_for_posts', true ) );
    } elseif ( is_singular() ) {
        $title = get_the_title();
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() || is_category() || is_tag() ) {
        $title = single_term_title( '', false );
    } elseif ( is_search() ) {
        $title = 'search-results';
    } elseif ( is_404() ) {
        $title = '404';
    } else {
        $title = get_the_title();
    }

    $slug = 'page-' . sanitize_title( $title );
    $classes[] = $slug;
    return $classes;
}
add_filter( 'body_class', 'add_flexible_page_slug_to_body_class' );

// Get content from page by id
function echo_page_content_by_id($page_id) {
    $page = get_post($page_id);
    if ($page) {
        echo apply_filters('the_content', $page->post_content);
    }
}


// Register the menu location
function register_my_menus() {
  register_nav_menus([
    'tab_menu1' => 'Tab Menu 1',
    'tab_menu2' => 'Tab Menu 2',
  ]);
}
add_action('after_setup_theme', 'register_my_menus');

// Define the custom walker for Bootstrap-styled tabs
class Bootstrap_Link_Tabs_Walker extends Walker_Nav_Menu {
  function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
    global $post;

    $classes = empty($item->classes) ? [] : (array) $item->classes;

    $active = '';

    if (!empty($post)) {
      $current_id = $post->ID;

      if ((int) $item->object_id === $current_id) {
        $active = ' active';

      } elseif ((int) $post->post_parent === (int) $item->object_id) {
        $active = ' active';

      } elseif (isset($post->ancestors) && in_array((int)$item->object_id, $post->ancestors)) {
        $active = ' active';
      }
    }

    $output .= '<li class="nav-item">';
    $output .= '<a class="nav-link' . $active . '" href="' . esc_url($item->url) . '">';
    $output .= esc_html($item->title);
    $output .= '</a></li>';
  }
}


// Auto-fill select field from URL
add_filter('ninja_forms_render_default_value', function ($default, $field_type, $field_settings) {
    if (!isset($field_settings['key'])) return $default;

    $key = $field_settings['key'];
    if (isset($_GET[$key])) {
        $val = sanitize_text_field($_GET[$key]);
        return $val;
    }

    return $default;
}, 10, 3);


// Map selected person to email address before submission
add_filter('ninja_forms_submit_data', function ($form_data) {
    // Define your name-to-email mappings
    $person_to_email = [
        'chapel' => 'info@thechapelgainesville.com',
        'brad' => 'bwilliams.chapel@gmail.com',
        'maya' => 'mayabethpesek@gmail.com',
        'kirsten' => 'brownoutgville@gmail.com',
        'kevin' => 'switzersknd@hotmail.com',
        'lee' => 'dltownsend3@gmail.com',
        'joel' => 'joliver.parker@gmail.com',
        'danny' => 'daniel.madrid10@gmail.com'
    ];

    $selected_person = '';
    
    // First, find the selected person from the form fields
    foreach ($form_data['fields'] as $field) {
        if ($field['key'] === 'contact_person') {
            $selected_person = trim($field['value']);
            break;
        }
    }

    // Look up the corresponding email
    $selected_email = $person_to_email[$selected_person] ?? '';

    // Populate the hidden contact_email field
    foreach ($form_data['fields'] as &$field) {
        if ($field['key'] === 'contact_email') {
            $field['value'] = $selected_email;
            break;
        }
    }

    // Optional: Log for debugging
    // error_log("Selected person: $selected_person | Email: $selected_email");

    return $form_data;
});


// 1. Register the custom Sermons RSS feed
add_action('init', function () {
    add_feed('sermons', 'thechapel_sermons_feed');
});

// 2. Feed callback: load rss-sermons.php
function thechapel_sermons_feed() {
    // error_log('✅ Sermons feed loaded'); // Debug log
    header('Content-Type: application/rss+xml; charset=' . get_option('blog_charset'), true);
    get_template_part('rss', 'sermons'); // expects theme/rss-sermons.php
    exit;
}

// 3. Disable canonical redirect for this specific feed early
add_filter('redirect_canonical', function ($redirect_url, $requested_url) {
    // Check if it's our custom feed
    if (strpos($_SERVER['REQUEST_URI'], '/feeds/sermons') !== false) {
        // error_log('🛑 Preventing canonical redirect on sermons feed.');
        return false;
    }
    return $redirect_url;
}, 10, 2);

// 4. Absolute fallback (redundant, but ensures it gets served)
add_action('parse_request', function ($wp) {
    if ($wp->request === 'feeds/sermons' || $wp->request === 'feed/sermons') {
        // error_log('🔁 Serving sermons feed via parse_request fallback');
        thechapel_sermons_feed();
        exit;
    }
});

add_action('init', function() {
    register_post_type('site_settings', [
        'public' => false,
        'show_ui' => true,
        'label' => 'Site Settings',
        'supports' => ['title'], // Only show the title field
        'menu_icon' => 'dashicons-admin-generic',
        'show_in_menu' => true,
        'capability_type' => 'page',
    ]);
});

function add_custom_cache_headers() {
    if ( is_admin() ) return;

    $ext = pathinfo($_SERVER['REQUEST_URI'], PATHINFO_EXTENSION);

    switch ($ext) {
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
        case 'svg':
        case 'css':
        case 'js':
        case 'woff':
        case 'woff2':
            header("Cache-Control: max-age=31536000, public");
            break;
    }
}
add_action('send_headers', 'add_custom_cache_headers');
