<?php 
/*********************ECE SPACES**************************/
add_action( 'init', 'create_ece_space_post_type' );
function create_ece_space_post_type() {
    register_post_type( 'ececlub_space',
        array(
            'labels' => array(
                'name' => __( 'ECE Spaces', 'ececlub'),
                'singular_name' => __( 'ECE Space', 'ececlub')
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'spaces'),
            'supports' => array('title', 'editor', 'custom-fields', 'revisions')
        )
    );
}

add_action("admin_init", "ececlub_space_admin_init");
 
function ececlub_space_admin_init(){
  add_meta_box("space-meta", __("Location", 'ececlub'), "ececlub_space_meta", "ececlub_space", "side", "low");
}
 
function ececlub_space_meta(){
  global $post;
  $custom = get_post_custom($post->ID);
  $location = $custom["location"][0];
  ?>
  <label>Location:</label>
  <input name="location" value="<?php echo $location; ?>" />
  <?php
}

add_action('save_post', 'save_ececlub_space');

function save_ececlub_space(){
  global $post;
 
  update_post_meta($post->ID, "location", $_POST["location"]);
}

add_action("manage_posts_custom_column",  "ececlub_space_custom_columns");
add_filter("manage_edit-ececlub_space_columns", "ececlub_space_edit_columns");
 
function ececlub_space_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => __("Title", 'ececlub'),
    "description" => __("Description", 'ececlub'),
    "location" => __("Location", 'ececlub'),
  );
 
  return $columns;
}

function ececlub_space_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "description":
      the_excerpt();
      break;
    case "location":
      $custom = get_post_custom();
      echo $custom["location"][0];
      break;
  }
}
?>