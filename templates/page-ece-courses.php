<?php 
/*
 Template Name: ECE AntiCalendar Page
*/
 	//TODO: Set this so its queryable either through URL or other means
 	$query_year = 1; //using first year as default for now. 

 	//this query gets all first year courses
 	/*
	$loop = new WP_Query( 
			array( 
				'post_type' => 'ececlub_courses',
				'posts_per_page' => -1,
				'orderby' => 'year',
                'order' => 'ASC',
			 	'meta_key' => "year",
                'meta_value' => $query_year,
			) 
		);
	*/

	//this query gets all courses and sorts them by year. You can either use this query and sort them on the front end. Or use the above query and sort them based on query
	$loop = new WP_Query( 
			array( 
				'post_type' => 'ececlub_courses',
				'posts_per_page' => -1,
				'orderby' => 'year',
                'order' => 'ASC'
			) 
		);

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
							<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
								<!-- TODO: Reformat -->
								<?php
									global $post;
									$custom = get_post_custom($post->ID);
									$course = $custom["course"][0];
									$year = $custom["year"][0];
									$lecture = $custom["lecture"][0];
									$tutorial = $custom["tutorial"][0];
									$difficulty = $custom["difficulty"][0];
							  	?>
								<a href="<?php echo get_permalink(); ?>"><h2><?php the_title(); ?></h2></a>
								<table class="rating">
									<tbody>
										<tr>
											<td>Lecture Value: <?php echo $lecture ?>/5</td>
											<td>Tutorial Value: <?php echo $tutorial ?>/5</td>
											<td>Relative Difficulty: <?php echo $difficulty ?>/5</td>
										</tr>
									</tbody>
								</table>
								<?php the_content(); ?>
							<?php endwhile; ?>
						</div>
					</section>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>