<?php 
/*
 Template Name: ECE Space Page
*/

	$loop = new WP_Query( array( 'post_type' => 'ececlub_space', 'posts_per_page' => -1 ) );

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
								<h2><?php the_title(); ?></h2>
								<hr class="horizontal-rule">
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