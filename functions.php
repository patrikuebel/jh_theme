<?php
/**
 * Genesis Sample.
 *
 * This file adds functions to the Genesis Sample Theme.
 *
 * @package JarnaHalsan
 * @author  Patrik Uebel
 * @license GPL-2.0+
 * @link    https://patrikuebel.se/
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

// Sets up the Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

add_action( 'after_setup_theme', 'genesis_sample_localization_setup' );
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function genesis_sample_localization_setup() {

	load_child_theme_textdomain( 'genesis-sample', get_stylesheet_directory() . '/languages' );

}

// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color select to Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Adds WooCommerce support.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

// Adds the required WooCommerce styles and Customizer CSS.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php';

// Adds the Genesis Connect WooCommerce notice.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php';

// Defines the child theme (do not remove).
define( 'CHILD_THEME_NAME', 'Genesis Sample' );
define( 'CHILD_THEME_URL', 'https://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '2.6.0' );

add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles' );
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function genesis_sample_enqueue_scripts_styles() {

	wp_enqueue_style(
		'genesis-sample-fonts',
		'//fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,700',
		array(),
		CHILD_THEME_VERSION
	);
	wp_enqueue_style( 'dashicons' );

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script(
		'genesis-sample-responsive-menu',
		get_stylesheet_directory_uri() . "/js/responsive-menus{$suffix}.js",
		array( 'jquery' ),
		CHILD_THEME_VERSION,
		true
	);
	wp_localize_script(
		'genesis-sample-responsive-menu',
		'genesis_responsive_menu',
		genesis_sample_responsive_menu_settings()
	);

	wp_enqueue_script(
		'genesis-sample',
		get_stylesheet_directory_uri() . '/js/genesis-sample.js',
		array( 'jquery' ),
		CHILD_THEME_VERSION,
		true
	);

}

/**
 * Defines responsive menu settings.
 *
 * @since 2.3.0
 */
function genesis_sample_responsive_menu_settings() {

	$settings = array(
		'mainMenu'         => __( 'Menu', 'genesis-sample' ),
		'menuIconClass'    => 'dashicons-before dashicons-menu',
		'subMenu'          => __( 'Submenu', 'genesis-sample' ),
		'subMenuIconClass' => 'dashicons-before dashicons-arrow-down-alt2',
		'menuClasses'      => array(
			'combine' => array(
				'.nav-primary',
			),
			'others'  => array(),
		),
	);

	return $settings;

}

// Sets the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) {
	$content_width = 702; // Pixels.
}

// Adds support for HTML5 markup structure.
add_theme_support(
	'html5', array(
		'caption',
		'comment-form',
		'comment-list',
		'gallery',
		'search-form',
	)
);

// Adds support for accessibility.
add_theme_support(
	'genesis-accessibility', array(
		'404-page',
		'drop-down-menu',
		'headings',
		'rems',
		'search-form',
		'skip-links',
	)
);

// Adds viewport meta tag for mobile browsers.
add_theme_support(
	'genesis-responsive-viewport'
);

// Adds custom logo in Customizer > Site Identity.
add_theme_support(
	'custom-logo', array(
		'height'      => 120,
		'width'       => 700,
		'flex-height' => true,
		'flex-width'  => true,
	)
);

// Renames primary and secondary navigation menus.
add_theme_support(
	'genesis-menus', array(
		'primary'   => __( 'Header Menu', 'genesis-sample' ),
		'secondary' => __( 'Footer Menu', 'genesis-sample' ),
	)
);

// Adds support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Adds support for 3-column footer widgets.
add_theme_support( 'genesis-footer-widgets', 3 );

// Removes header right widget area.
unregister_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Removes output of primary navigation right extras.
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

add_action( 'genesis_theme_settings_metaboxes', 'genesis_sample_remove_metaboxes' );
/**
 * Removes output of unused admin settings metaboxes.
 *
 * @since 2.6.0
 *
 * @param string $_genesis_admin_settings The admin screen to remove meta boxes from.
 */
function genesis_sample_remove_metaboxes( $_genesis_admin_settings ) {

	remove_meta_box( 'genesis-theme-settings-header', $_genesis_admin_settings, 'main' );
	remove_meta_box( 'genesis-theme-settings-nav', $_genesis_admin_settings, 'main' );

}

add_filter( 'genesis_customizer_theme_settings_config', 'genesis_sample_remove_customizer_settings' );
/**
 * Removes output of header settings in the Customizer.
 *
 * @since 2.6.0
 *
 * @param array $config Original Customizer items.
 * @return array Filtered Customizer items.
 */
function genesis_sample_remove_customizer_settings( $config ) {

	unset( $config['genesis']['sections']['genesis_header'] );
	return $config;

}

// Displays custom logo.
add_action( 'genesis_site_title', 'the_custom_logo', 0 );

// Repositions primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Repositions the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 10 );

add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );
/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @since 2.2.3
 *
 * @param array $args Original menu options.
 * @return array Menu options with depth set to 1.
 */
function genesis_sample_secondary_menu_args( $args ) {

	if ( 'secondary' !== $args['theme_location'] ) {
		return $args;
	}

	$args['depth'] = 1;
	return $args;

}

add_filter( 'genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar' );
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @since 2.2.3
 *
 * @param int $size Original icon size.
 * @return int Modified icon size.
 */
function genesis_sample_author_box_gravatar( $size ) {

	return 90;

}

add_filter( 'genesis_comment_list_args', 'genesis_sample_comments_gravatar' );
/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @since 2.2.3
 *
 * @param array $args Gravatar settings.
 * @return array Gravatar settings with modified size.
 */
function genesis_sample_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;
	return $args;

}

// Change the footer text
add_filter('genesis_footer_creds_text', 'sp_footer_creds_filter');
function sp_footer_creds_filter( $creds ) {
	$creds = 'Copyright [footer_copyright] &middot; JärnaHälsan';
	return $creds;
}

// Remove the entry meta in the entry header (requires HTML5 theme support)
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

// Remove the entry meta in the entry footer (requires HTML5 theme support)
//remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

// Code to display custom widget after the entry on the frontpage
genesis_register_sidebar( array(
    'id' => 'frontpage-1',
    'name' => __( 'Frontpage 1', 'genesis-sample' ),
    'description' => __( 'Placed under the page content on the front', 'childtheme' ),
) );

add_action( 'genesis_after_entry', 'front_page_widget' );
function front_page_widget() {
    if ( is_front_page() ) {
    genesis_widget_area( 'frontpage-1', array(
    'before' => '<div class="frontpage-1-widget widget-area">',
    'after'  => '</div>',
    ) );
    }
}

// Code for displaying custom sidebars ---------------------*/

// Register the sidebar
genesis_register_sidebar( array(
'id' => 'courses-sidebar',
'name' => 'Sidofält kurskategorin',
'description' => 'Det här sidofältet visas på kategorisidan för kurser och enskilda kurssidor.'
) );

// Replace primary sidebar with custom sidebar
add_action('get_header','courses_sidebar');
function courses_sidebar() {
	if ( ( is_singular() && in_category('kurser') ) || is_category( 'kurser' ) ){

		// remove the default genesis primary sidebar
		remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );

		// add an action hook to call the function for your custom sidebar
		add_action( 'genesis_sidebar', 'child_do_courses_sidebar' );

	}
}

// Display the custom sidebar
function child_do_courses_sidebar() {
	dynamic_sidebar( 'courses-sidebar' );
}

// Register the sidebar
genesis_register_sidebar( array(
'id' => 'hsl-sidebar',
'name' => 'Sidofält HSL',
'description' => 'Det här sidofältet visas på kategorisidan för HSL och enskilda HSL-sidor.'
) );

// Display the custom sidebar
function child_do_hsl_sidebar() {
	dynamic_sidebar( 'hsl-sidebar' );
}

// Register the sidebar for kvalitetssystem
genesis_register_sidebar( array(
'id' => 'kvalitets-sidebar',
'name' => 'Sidofält Kvalitetssystem',
'description' => 'Det här sidofältet visas på kategorisidan för Kvalitetssystem och enskilda Kvalitets-sidor.'
) );

// Display the custom sidebar
function child_do_kvalitets_sidebar() {
	dynamic_sidebar( 'kvalitets-sidebar' );
}

// End code for custom sidebars ---------------------------*/

//Removes Title and Description on Archive, Taxonomy, Category, Tag
remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );

//Removes Title and Description on CPT Archive
remove_action( 'genesis_before_loop', 'genesis_do_cpt_archive_title_description' );

// Disable dashboard for subscribers
add_action('admin_init', 'disable_dashboard');

function disable_dashboard() {
    if (!is_user_logged_in()) {
        return null;
    }
    if (!current_user_can('administrator') && is_admin()) {
        wp_redirect(site_url('/hsl_handbok/'));
        exit;
    }
}

add_action('init', 'disable_admin_bar');

function disable_admin_bar() {
    if (current_user_can('subscriber')) {
        show_admin_bar(false);
    }
}

//Menu based on user role
function jh_nav_menu_args( $args = '' ) {
		if (current_user_can('subscriber') ) {
    	$args['menu'] = 'prenumeranter';
		}
		elseif ( current_user_can('editor') || current_user_can('administrator') )  {
    	$args['menu'] = 'medarbetare';
		}
		else {
			$args['menu'] = 'offentlig';
		}
    return $args;
}
add_filter( 'wp_nav_menu_args', 'jh_nav_menu_args' );

// Register two widgetareas, one displaying after site-header on frontpage and the other on all other pages
genesis_register_sidebar( array(
    'id' => 'image-banner',
    'name' => __( 'Bildbanner Front', 'genesis-sample' ),
    'description' => __( 'Visa bild efter header på startsidan.', 'genesis-sample' ),
) );

genesis_register_sidebar( array(
    'id' => 'image-banner-all',
    'name' => __( 'Bildbanner Alla', 'genesis-sample' ),
    'description' => __( 'Visa bild efter header på alla sidor förutom startsidan.', 'genesis-sample' ),
) );

add_action( 'genesis_after_header', 'custom_imagebanner' );
function custom_imagebanner() {
	if ( is_front_page() ) {
		genesis_widget_area( 'image-banner', array(
	  	'before' => '<div class="imagebanner widget-area">',
			'after'  => '</div>',
		) );
    }
	else {
		genesis_widget_area( 'image-banner-all', array(
			'before' => '<div class="imagebanner widget-area">',
			'after'  => '</div>',
			) );
		}
}


//Lägga till inloggningslänk sist i huvudmenyn
add_filter( 'wp_nav_menu_items', 'sp_add_loginout_link', 10, 2 );
function sp_add_loginout_link( $items, $args ) {
	// Change 'primary' to 'secondary' to put the login link in your secondary nav bar.
	if ( $args->theme_location != 'primary' ) {
		return $items;
	}

	if ( is_user_logged_in() ) {
		$items .= '<li class="menu-item"><a href="' . wp_logout_url( home_url() ) . '">Logga ut</a></li>';
	} else {
		$items .= '<li class="menu-item"><a href="' . site_url( 'wp-login.php' ) . '">Logga in</a></li>';
	}
	return $items;
}

//Ändra visningsordning på sidan för kurskategorin
function change_category_order( $query ) {
    if ( $query->is_category('kurser') && $query->is_main_query() ) {
        $query->set( 'order', 'ASC' );
    }
}
add_action( 'pre_get_posts', 'change_category_order' );

remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
