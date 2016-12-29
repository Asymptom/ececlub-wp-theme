<?php 
/*
 Template Name: ECE Exec Page
*/

    global $wpdb;
    $position_table = $wpdb->prefix . "ececlub_exec_positions";
    $exec_table = $wpdb->prefix . "ececlub_execs";
    $sql = "SELECT MAX(year) as year FROM " . $exec_table;
	$year_query = $wpdb->get_results($sql);
	$latest_year = $year_query[0]->year;
    $sql = "SELECT exec.name, positions.position FROM " . $exec_table . " exec RIGHT JOIN " . $position_table . 
			" positions ON exec.position = positions.position AND positions.active = \"1\" AND exec.year = \"" . $latest_year . "\" ORDER BY positions.id";
    $execs = $wpdb->get_results($sql);

?>
				
<?php get_header(); ?>
	<div class="page-container container-fluid">
		<div class="row-fluid ">
			<div class="col-md-7 col-md-offset-3">
				<?php while ( have_posts() ) : the_post(); ?>
					<section class="page">
						<div class="page-content">
							<h1><?php the_title(); ?></h1>
							<hr class="horizontal-rule">
							<?php the_content(); ?>
							<hr class="horizontal-rule">
								<table class="table table-striped">
									<tr>
								      <th>Position</th>
								      <th>Name</th>
								    </tr>
								    <?php foreach ($execs as $exec) { ?> 
								    <tr>
								    	<th><?php echo $exec->position ?></th>
								    	<th><?php echo (isset($exec->name) ? $exec->name : "Open") ?></th>
								    </tr>
								    <?php } ?>
								</table>
						</div>
					</section>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>
