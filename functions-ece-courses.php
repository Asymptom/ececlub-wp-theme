<?php 
/*********************ECE ANTI-CALENDAR**************************/
add_action( 'init', 'create_ece_course_post_type' );
function create_ece_course_post_type() {
    register_post_type( 'ececlub_courses',
        array(
            'labels' => array(
                'name' => __( 'Courses', 'ececlub'),
                'singular_name' => __( 'Course', 'ececlub')
            ),
            'description' => 'AntiCalendar course entries',
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'courses'),
            'supports' => array('title', 'editor', 'author','excerpt','comments', 'custom-fields', 'revisions')
        )
    );
}

function default_comments_on( $data ) {
    if( $data['post_type'] == 'ececlub_courses' ) {
        $data['comment_status'] = 1;
    }

    return $data;
}
add_filter( 'wp_insert_post_data', 'default_comments_on' );

add_action("admin_init", "ececlub_courses_admin_init");
 
function ececlub_courses_admin_init(){
  add_meta_box("course-meta", __("AntiCalendar Options", 'ececlub'), "ececlub_course_meta", "ececlub_courses", "normal");
}
 
function ececlub_course_meta(){
  global $post;
  $custom = get_post_custom($post->ID);
  $course = $custom["course"][0];
  $year = $custom["year"][0];
  $lecture = $custom["lecture"][0];
  $tutorial = $custom["tutorial"][0];
  $difficulty = $custom["difficulty"][0];
  ?>
  <div>
    <label>Course Code:</label>
    <input name="course" value="<?php echo $course; ?>">
  </div>
  <div>
    <label>Year:</label>
    <input type="number" name="year" min="1" max="4" value="<?php echo $year; ?>">
  </div>
  <div>
    <label>Lecture:</label>
    <input type="number" name="lecture" min="1" max="5" value="<?php echo $lecture; ?>">
  </div>
  <div>
    <label>Tutorial:</label>
    <input type="number" name="tutorial" min="1" max="5" value="<?php echo $tutorial; ?>">
  </div>
  <div>
    <label>Difficulty:</label>
    <input type="number" name="difficulty" min="1" max="5" value="<?php echo $difficulty; ?>">
  </div>
  <?php
}

add_action('save_post', 'save_ececlub_course');

function save_ececlub_course(){
  global $post;
 
  update_post_meta($post->ID, "course", $_POST["course"]);
  update_post_meta($post->ID, "year", $_POST["year"]);
  update_post_meta($post->ID, "lecture", $_POST["lecture"]);
  update_post_meta($post->ID, "tutorial", $_POST["tutorial"]);
  update_post_meta($post->ID, "difficulty", $_POST["difficulty"]);
}

add_action("manage_posts_custom_column",  "ececlub_course_custom_columns");
add_filter("manage_edit-ececlub_courses_columns", "ececlub_course_edit_columns");
 
function ececlub_course_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "title" => __("Title", 'ececlub'),
    "year" => __("Year", 'ececlub'),
    "description" => __("Description", 'ececlub'),
    "lecture" => __("Lecture", 'ececlub'),
    "tutorial" => __("Tutorial", 'ececlub'),
    "difficulty" => __("Difficulty", 'ececlub'),
  );
 
  return $columns;
}

function ececlub_course_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "description":
      the_excerpt();
      break;
    case "course":
      $custom = get_post_custom();
      echo $custom["course"][0];
      break;
    case "year":
      $custom = get_post_custom();
      echo $custom["year"][0];
      break;
    case "lecture":
      $custom = get_post_custom();
      echo $custom["lecture"][0];
      break;
    case "tutorial":
      $custom = get_post_custom();
      echo $custom["tutorial"][0];
      break;
    case "difficulty":
      $custom = get_post_custom();
      echo $custom["difficulty"][0];
      break;
  }
}
?>