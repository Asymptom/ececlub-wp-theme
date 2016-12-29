<?php
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
                <a href="<?php echo $current_url . "&offset=" . ($offset - 20)?>" class="button button-primary">Previous</a>
            <?php } ?>
            <a href="<?php echo $current_url . "&offset=" . ($offset + 20) ?>" class="button button-primary">Next</a>
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
?>