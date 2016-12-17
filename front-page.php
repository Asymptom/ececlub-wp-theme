<?php get_header(); ?>
	<div id="banner-image-container">
        <?php if ( is_active_sidebar( 'home_banner' ) ) : ?>
            <?php dynamic_sidebar( 'home_banner'); ?>
        <?php else: ?>
            <?php 
            $option_name = 'ececlub_theme_options';
            $options = get_option( $option_name );
            $banner_image = (isset($options['banner_image_upload']) && !empty($options['banner_image_upload']))? $options['banner_image_upload'] : get_template_directory_uri(). "/images/banner.png";
            $banner_link = isset($options['banner_link']) ? $options['banner_link'] : "#";
            ?>
            <a href="<?php echo $banner_link ?>"><img id="banner-image" alt="<?php echo $options['banner_text']?>" src="<?php echo $banner_image?>"></a>
        <?php endif; ?>
    </div>

	<div id="main-container">

		<div id="content-post">
			<?php
            while ( have_posts() ) : the_post(); ?>
				<section class="col-8 col-md-8">
					<div class="post">
                        <div class="post-content">
                            <h1><?php the_title(); ?></h1>
                            <?php the_content(); ?>
                        </div>

                        <div id="subtitle"><p>Submitted by <?php the_author(); ?> on <?php echo get_the_date(); ?> </p></div>

					</div>
				</section>

			<?php endwhile; ?>
		</div>

        <?php if ( is_active_sidebar('home_right_sidebar')) : ?>
    		<div id="sidebar">
                <aside class="col-4 col-md-4 " id="billboard">
                    <?php get_sidebar('home_right_sidebar'); ?> 
                </aside>
    		</div>
        <?php endif; ?>
	</div>
</div>

<?php get_footer(); ?>
