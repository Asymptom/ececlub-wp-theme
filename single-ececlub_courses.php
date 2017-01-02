<?php get_header(); ?>
	<div class="page-container container-fluid">
		<div class="row-fluid ">
			<div class="col-md-7 col-md-offset-3">
				<section class="page">
					<div class="page-content">
						<h1><?php the_title(); ?></h1>
						<hr class="horizontal-rule">
						<?php the_content(); ?>
						<?php while ( have_posts() ) : the_post(); ?>
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
						<?php comments_template( '', true ); ?>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>