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

/****************ECE Execs*******************/
function exec_table_setup(){
    global $wpdb;
    $exec_table = $wpdb->prefix . "ececlub_execs";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $exec_table (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      year smallint NOT NULL,
      name tinytext NOT NULL,
      email text,
      position text NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    $position_table = $wpdb->prefix . "ececlub_exec_positions";
    $sql = "CREATE TABLE $position_table (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      position text NOT NULL,
      active tinyint(1) DEFAULT 1 NOT NULL,
      PRIMARY KEY  (id),
      CONSTRAINT tb_uq UNIQUE (position)
    ) $charset_collate;";

    dbDelta( $sql );
}
add_action('after_switch_theme', 'exec_table_setup'); 

function validate_exec_info(){
    $errors = [];
    //Check to make sure that the year field is not empty
    if(trim($_POST['year']) === '') {
        array_push($errors, 'Please select a year');
    } else if (!is_numeric(trim($_POST['year']))) {
        array_push($errors, 'Year must be a number');
    } 
    
    //Check to make sure that the name field is not empty
    if(trim($_POST['name']) === '') {
        array_push($errors, 'Name is mandatory');
    } 

    if(trim($_POST['email']) != '' && !filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
        array_push($errors, 'The entered email is not a valid email');
    } 

    //Check to make sure comments were entered  
    if(trim($_POST['position']) === '') {
        array_push($errors, 'Position is mandatory');
    } 
    return $errors;
}

function get_all_exec_postions(){
    global $wpdb;
    $table_name = $wpdb->prefix . "ececlub_exec_positions";
    $sql = "SELECT position FROM " . $table_name . " ORDER BY id;";
    return $wpdb->get_results($sql);
}

function update_exec_positions($position){
    global $wpdb;
    $table_name = $wpdb->prefix . "ececlub_exec_positions";
    $sql = "SELECT position FROM " . $table_name . " WHERE position = '" . $position . "'";
    $exec = $wpdb->get_row($sql);
    if ($wpdb->num_rows == 0){
        $wpdb->insert( 
            $table_name, 
            array( 
                'position' => $position
            ), 
            array( 
                '%s'
            ) 
        );
    }
    return $exec;
}

//TODO: use built in wordpress admin functions 
function ececlub_execs_add(){
    //If the form is submitted
    if(isset($_POST['submitted'])) {
        $errors = validate_exec_info();
        //If there is no error, enter the exec into the database
        if(empty($errors)) {
            global $wpdb;
            $table_name = $wpdb->prefix . "ececlub_execs";
            

            $wpdb->insert( 
                $table_name, 
                array( 
                    'year' => trim($_POST['year']), 
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'position' => trim($_POST['position']),
                ), 
                array( 
                    '%d', 
                    '%s',
                    '%s',
                    '%s' 
                ) 
            );

            update_exec_positions(trim($_POST['position']));
            $success = true;
        }
    } 

    $positions = get_all_exec_postions();
    ?>
    <div class="section panel">
        <h1>Add A New Executive</h1>
        <?php if (isset($success)) { ?>
            <p> Successfully added an executive </p>
            <a href="javascript:window.location.href=window.location.href" class="button button-primary" >Add Another</a>
        <?php } else {?>
            <?php if (!empty($errors)) { ?>
                <?php foreach($errors as $error) { ?>
                    <div id="setting-error-invalid_home" class="error settings-error notice is-dismissible"> 
                        <p>
                            <strong><?php echo $error ?></strong>
                        </p>
                        <button type="button" class="notice-dismiss">
                            <span class="screen-reader-text">Dismiss this notice.</span>
                        </button>
                    </div>
                <?php } ?>
            <?php } ?>
            <form role="form" action="<?php the_permalink(); ?>" method="post">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="year">Year</label>
                        </th>
                        <td>
                            <select name="year" id="year">
                            <?php for ($option = date("Y"); $option > date("Y") - 15; $option--) {?>
                                <option value="<?php echo $option ?>"><?php echo $option ?></option>
                            <?php } ?>
                            </select>
                            <p class="description">The starting year of the executive. e.g. an exec for 2016-2017 academic year is 2016</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="name">Name</label>
                        </th>
                        <td>
                            <input type="text" name="name" id="name" value="<?php if(isset($_POST['name'])) echo $_POST['name'];?>" class="regular-text"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="email">Email</label>
                        </th>
                        <td>
                            <input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>" class="regular-text"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="position">Position</label>
                        </th>
                        <td>
                            <input list="positions" name="position" id="position" value="<?php if(isset($_POST['position'])) echo $_POST['position'];?>" class="regular-text">
                            <datalist id="positions">
                                <?php foreach ($positions as $position) { ?>
                                    <option value="<?php echo $position->position?>">
                                <?php } ?>
                            </datalist>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="submitted" id="submitted" value="true" />
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="Add">
                </p>
            </form>
        <?php } ?>
    </div>
    <?php
}

function ececlub_execs(){
    global $wpdb;
    $table_name = $wpdb->prefix . "ececlub_execs";
    $current_url="//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                
    //If the form is submitted
    if(isset($_POST['submitted'])) {
        $errors = validate_exec_info();
        //If there is no error, update the exec into the database
        if(empty($errors)) {
            $wpdb->update( 
                $table_name, 
                array( 
                    'year' => trim($_POST['year']), 
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'position' => trim($_POST['position']),
                ), 
                array( 'id' => $_POST['id'] ), 
                array( 
                    '%d', 
                    '%s',
                    '%s',
                    '%s' 
                )
            );

            update_exec_positions(trim($_POST['position']));
            $success = true;
        }
    } 
    ?>
    <div class="section panel">
        <h1>ECE Club Execs</h1>
        <?php if($_GET['id'] && !isset($success)) { 
            $sql = "SELECT id, year, name, email, position FROM " . $table_name . " WHERE id = " . $_GET['id'];
            $exec = $wpdb->get_row($sql);
            $positions = get_all_exec_postions();
            ?>
            <?php if (!empty($errors)) { ?>
                <?php foreach($errors as $error) { ?>
                    <div id="setting-error-invalid_home" class="error settings-error notice is-dismissible"> 
                        <p>
                            <strong><?php echo $error ?></strong>
                        </p>
                        <button type="button" class="notice-dismiss">
                            <span class="screen-reader-text">Dismiss this notice.</span>
                        </button>
                    </div>
                <?php } ?>
            <?php } ?>
            <form role="form" action="<?php the_permalink(); ?>" method="post">
                <input type="hidden" value="<?php echo (isset($_POST['id']) ? $_POST['id'] : $_GET['id']); ?>" name="id" />
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="year">Year</label>
                        </th>
                        <td>
                            <input type="text" name="year" id="year" value="<?php echo (isset($_POST['year']) ? $_POST['name'] : $exec->year);?>" class="regular-text"/>
                            <p class="description">The starting year of the executive. e.g. an exec for 2016-2017 academic year is 2016</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="name">Name</label>
                        </th>
                        <td>
                            <input type="text" name="name" id="name" value="<?php echo (isset($_POST['name']) ? $_POST['name'] : $exec->name);?>" class="regular-text"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="email">Email</label>
                        </th>
                        <td>
                            <input type="text" name="email" id="email" value="<?php echo (isset($_POST['email']) ? $_POST['email'] : $exec->email);?>" class="regular-text"/>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="position">Position</label>
                        </th>
                        <td>
                            <input list="positions" name="position" id="position" value="<?php echo (isset($_POST['position']) ? $_POST['position'] : $exec->position);?>" class="regular-text">
                            <datalist id="positions">
                                <?php foreach ($positions as $position) { ?>
                                    <option value="<?php echo $position->position?>">
                                <?php } ?>
                            </datalist>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="submitted" id="submitted" value="true" />
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="Edit">
                </p>
            </form>
        <?php } else { 
            $offset = (isset($_GET['offset']) ? $_GET['offset'] : 0 );
            $sql = "SELECT id, year, name, email, position FROM " . $table_name . " ORDER BY year DESC LIMIT 20 OFFSET " . $offset;
            $results = $wpdb->get_results($sql);
            ?>
            <?php if (isset($success)) { ?>
                <div class="notice is-dismissible"> 
                    <p>
                        <strong>The record has been successfully updated</strong>
                    </p>
                    <button type="button" class="notice-dismiss">
                        <span class="screen-reader-text">Dismiss this notice.</span>
                    </button>
                </div>
            <?php } ?>
            <table class="wp-list-table widefat fixed striped posts">
                <thead>
                    <tr>
                    <th scope="col" id="year" class="manage-column">Year</th>
                    <th scope="col" id="name" class="manage-column">Name</th>
                    <th scope="col" id="email" class="manage-column">Email</th>
                    <th scope="col" id="position" class="manage-column">Position</th>
                    <th scope="col" id="action" class="manage-column"></th>
                    </tr>
                </thead>

                <tbody id="the-list">
                    <?php foreach ($results as $result) { ?>
                        <tr id="<?php echo $result->id ?>" class="">
                            <td class="year column-year" data-colname="year"><?php echo $result->year ?></td>
                            <td class="name column-name" data-colname="name"><?php echo $result->name ?></td>
                            <td class="email column-email" data-colname="email"><?php echo $result->email ?></td>
                            <td class="position column-author" data-colname="position"><?php echo $result->position ?></td>
                            <td class="action column-action" data-colname="action">
                                <a href="<?php echo $current_url . "&id=" . "$result->id"?>">Edit </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php if ($offset > 0) { ?>
                <a href="<?php echo $current_url . "&offset=" . ($offset - 10)?>" class="button button-primary">Previous</a>
            <?php } ?>
            <a href="<?php echo $current_url . "&offset=" . ($offset + 10) ?>" class="button button-primary">Next</a>
        <?php } ?>
    </div>
    <?php 
}

function exec_menu(){
    add_menu_page( __("Execs", 'ececlub'), __("Execs", 'ececlub'), "edit_posts", "ececlub_execs", "ececlub_execs");
    add_submenu_page( "ececlub_execs", __("All ECE Club Execs", 'ececlub'), __("All Execs", 'ececlub'), "edit_posts", "ececlub_execs", "ececlub_execs");
    add_submenu_page( "ececlub_execs", __("Add New ECE Club Exec", 'ececlub'), __("Add New", 'ececlub'), "edit_posts", "ececlub_execs_add", "ececlub_execs_add");
}
add_action('admin_menu', 'exec_menu');



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