<!DOCTYPE html>
<!--[if IE 7]><html class="ie ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8]><html class="ie ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!--><html <?php language_attributes(); ?>><!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
	<title><?php wp_title(); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,200,600,700,900' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.png">

	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/style.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/header-style.css" />
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/tabs.js"></script>

	<?php wp_head(); ?>
</head>
<body>

	<!-- Notice the tag below isn't closed it should be closed in your main content to flush footer to the bottom-->
	<div id="wrap">
		<div id="site-title" class="container-head">
			<p><?php bloginfo('name'); ?></p>
		</div>
		<div class="navbar navbar-inverse navbar-static-top" role="navigation">
			<div class="container">
				<div id="logo-wrapper">
					<a id="header-logo" class="navbar-brand hidden-xs" href="<?php echo site_url();?>">
						<img id="logo" src="<?php echo get_template_directory_uri(); ?>/images/bg_logo.png">
						<img id="logo-center" src="<?php echo get_template_directory_uri(); ?>/images/logo.png">
					</a>
				</div>

		        <div class="navbar-header">
		          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
		            <span class="sr-only">Toggle navigation</span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		          </button>
		          <!-- <a class="navbar-brand" href="#">Project name</a> -->
		        </div>

				<?php 

				include 'helpers/custom-nav-menu-walker.php';
				wp_nav_menu( array(
					'theme_location'	=> 'primary',
					'menu' 				=> 'main_nav',
					'container_class'	=> 'navbar-collapse collapse',
					'menu_class' 		=> 'nav navbar-nav',
					'items_wrap'      	=> '<ul class="%2$s">%3$s</ul>',
					'walker' 			=> new Custom_Walker_Nav_Menu()
				)); 
				?>
			</div>

		</div>
