<?php get_header(); ?>
	<div class="page-container container-fluid">
		<div class="row-fluid ">
			<div class="col-md-7 col-md-offset-3">
				<?php while ( have_posts() ) : the_post(); ?>
					<section class="page">
						<div class="page-content">
							<h1><?php the_title(); ?></h1>
							<hr class="horizontal-rule">
							<?php 
							$cc = get_the_content(); 
							if ($cc !=  ''){
				            	the_content();
							
							}else{ ?>
								<h3 class="text-center">This page is currently under construction.</h3>
				           		<img class="img-responsive" style="margin: 0 auto;" src="<?php echo get_template_directory_uri(); ?>/images/under_construction.png">
							<?php }
							?>
						</div>
					</section>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>