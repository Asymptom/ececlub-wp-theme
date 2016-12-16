<?php get_header(); ?>
<div class="page-container container-fluid">
		<div class="row-fluid ">
			<div class="col-md-7 col-md-offset-3">
				<?php while ( have_posts() ) : the_post(); ?>
					<section class="blog-container">
						<div class="blog-text-container">
							<h1><?php the_title(); ?></h1>
							<hr class="horizontal-rule">
							<?php the_content(); ?>
						</div>
					</section>
				
				<?php endwhile; ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>