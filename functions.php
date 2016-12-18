<?php
if ( ! function_exists( 'pc_setup' ) ) :

	function pc_setup() {
		// Enable support for Post Thumbnails, and declare two sizes.
		add_theme_support( 'post-thumbnails' );


		$args = array(
			'width'         => 215,
			'height'        => 214,
			'default-image' => get_template_directory_uri() . '/images/ece_club_logo.png',
			'uploads'       => true,
		);
		add_theme_support( 'custom-header', $args );


		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus( array(
			'primary'   => __( 'Top primary menu', 'twentyfourteen' ),
			'secondary' => __( 'Secondary menu is for responsive', 'twentyfourteen' ),
		) );

		/*
	 	* Switch default core markup for search form, comment form, and comments
	 	* to output valid HTML5.
	 	*/
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list',
		) );

	}

endif;

add_action( 'after_setup_theme', 'pc_setup' );

//REMOVE WP-GENERATOR, RSD AND WLMMANIFEST (WHICH ARE USED ONLY IF USE MICROSOFT WRITER)
function remheadlink() {
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'feed_links_extra', 3 );
	remove_action('wp_head', 'feed_links', 2);
}
add_action('init', 'remheadlink');


/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image.
 * 3. Index views.
 * 4. Full-width content layout.
 * 5. Presence of footer widgets.
 * 6. Single views.
 * 7. Featured content layout.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function twentyfourteen_body_classes( $classes ) {
    if ( !is_front_page() ){
        $classes[] = 'nav-bg';
    }

	if ( is_singular() && ! is_front_page() ) {
		$classes[] = 'singular';
	}

	return $classes;
}
add_filter( 'body_class', 'twentyfourteen_body_classes' );

function new_excerpt_more( $more ) {
	return '';
}
add_filter('excerpt_more', 'new_excerpt_more');

/**
 * Extend the default WordPress post classes.
 *
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
function twentyfourteen_post_classes( $classes ) {
	if ( ! post_password_required() && has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}

	return $classes;
}
add_filter( 'post_class', 'twentyfourteen_post_classes' );

function create_upcoming_events_cat () {
    if (file_exists (ABSPATH.'/wp-admin/includes/taxonomy.php')) {
        require_once (ABSPATH.'/wp-admin/includes/taxonomy.php'); 
        if ( ! get_cat_ID( 'Upcoming Events' ) ) {
            wp_create_category( 'Upcoming Events' );
        }
    }
}

include 'functions-ece-execs.php';
/**************WIDGETS******************************/

// Register widgetized areas
function theme_widgets_init() {
    register_sidebar( array(
		'name' => 'Home Slider',
		'id' => 'home_banner',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );

	register_sidebar( array(
		'name' => 'Home Right Sidebar',
		'id' => 'home_right_sidebar',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	) );
} // end theme_widgets_init
 
add_action( 'init', 'theme_widgets_init' );

// Check for static widgets in widget-ready areas
function is_sidebar_active( $index ){
    global $wp_registered_sidebars;
 
    $widgetcolums = wp_get_sidebars_widgets();
 
    if ( $widgetcolums[$index] ) return true;
 
    return false;
} // end is_sidebar_active

//enable shortcodes
add_filter('widget_text', 'do_shortcode');

function ececlub_customize_register($wp_customize){
    
    //  =============================
    //  = Banner                    =
    //  =============================
    $wp_customize->add_section('ececlub_banner', array(
        'title'    => __('Banner', 'ececlub'),
        'description' => '',
        'priority' => 120,
    ));
 
    $wp_customize->add_setting('ececlub_theme_options[banner_text]', array(
        'default'        => 'Welcome to University of Toronto\'s ECE Club',
        'capability'     => 'edit_theme_options',
        'type'           => 'option',

    ));
 
    $wp_customize->add_control('ececlub_banner_text', array(
        'label'      => __('Banner Text', 'ececlub'),
        'section'    => 'ececlub_banner',
        'settings'   => 'ececlub_theme_options[banner_text]',
    ));

    $wp_customize->add_setting('ececlub_theme_options[banner_link]', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'type'           => 'option',

    ));
 
    $wp_customize->add_control('ececlub_banner_link', array(
        'label'      => __('Banner Link', 'ececlub'),
        'section'    => 'ececlub_banner',
        'settings'   => 'ececlub_theme_options[banner_link]',
    ));
 
    $wp_customize->add_setting('ececlub_theme_options[banner_image_upload]', array(
        'default'           => '',
        'capability'        => 'edit_theme_options',
        'type'           => 'option',
 
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'banner_image_upload', array(
        'label'    => __('Banner Image Upload', 'ececlub'),
        'section'  => 'ececlub_banner',
        'settings' => 'ececlub_theme_options[banner_image_upload]',
    )));

    //  =============================
    //  = Anonymous Feedback        =
    //  =============================
    $wp_customize->add_section('ececlub_feedback', array(
        'title'    => __('Anonymous Feedback', 'ececlub'),
        'description' => '',
        'priority' => 130,
    ));

    $wp_customize->add_setting('ececlub_theme_options[ece_feedback_email]', array(
        'default'        => 'ece@skule.ca',
        'capability'     => 'edit_theme_options',
        'type'           => 'option',
    ));

    $wp_customize->add_control('ececlub_feedback_email', array(
        'label'      => __('Anonymous Feedback Email List (Comma Separated)', 'ececlub'),
        'section'    => 'ececlub_feedback',
        'settings'   => 'ececlub_theme_options[ece_feedback_email]',
    ));

}
 
add_action('customize_register', 'ececlub_customize_register');
?>