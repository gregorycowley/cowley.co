<!DOCTYPE html>
<?php $t =& peTheme();?>
<?php $skin = $t->options->get("skin"); ?>
<?php $class = "skin_$skin"; ?>
<!--[if IE 7 ]><html class="ie7 no-js <?php echo $class ?>" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8 ]><html class="ie8 no-js <?php echo $class ?>" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 9 ]><html class="ie9 no-js <?php echo $class ?>" <?php language_attributes(); ?>><![endif]--> 
<!--[if (gte IE 9)|!(IE)]><!--><html class="no-js <?php echo $class ?>" <?php language_attributes();?>><!--<![endif]-->
   
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<title><?php $t->header->title(); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
		<meta name="format-detection" content="telephone=no">
		<!-- http://remysharp.com/2009/01/07/html5-enabling-script/ -->
		<!--[if lt IE 9]>
			<script type="text/javascript">/*@cc_on'abbr article aside audio canvas details figcaption figure footer header hgroup mark meter nav output progress section summary subline time video'.replace(/\w+/g,function(n){document.createElement(n)})@*/</script>
			<![endif]-->
		<script type="text/javascript">(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>
		
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<!-- favicon -->
		<link rel="shortcut icon" href="<?php echo $t->options->get("favicon") ?>" />

		<?php $t->font->load(); ?>

		<!-- scripts and wp_head() here -->
		<?php $t->header->wp_head(); ?>
		<?php $t->font->apply(); ?>
		<?php $t->color->apply(); ?>

		<?php if ($customCSS = $t->options->get("customCSS")): ?>
		<style type="text/css"><?php echo stripslashes($customCSS) ?></style>
		<?php endif; ?>
		<?php if ($customJS = $t->options->get("customJS")): ?>
		<script type="text/javascript"><?php echo stripslashes($customJS) ?></script>
		<?php endif; ?>
		

	</head>

	<body <?php $t->content->body_class(); ?>>

		<div class="site-wrapper container"> 
			<header class="row-fluid">
				
				<!--top color bar-->
				<div class="row-fluid top-bar">
					<div class="span5"></div>
					<div class="span7"></div>
				</div>
				
				<div class="row-fluid">
					<div class="span12">
						<div class="row-fluid header-content">
							
							<div class="span5 logo-wrap">
								<!--logo-->
								<a class="logo" href="<?php echo home_url(); ?>" title="Home">
									<img src="<?php echo $t->options->get("logo") ?>" alt="logo" />
								</a>
							</div>
							
							
							<!--main navigation-->
							<div class="span7 full-nav">
								<div class="row-fluid">
										<?php do_action('icl_language_selector'); ?>
								</div>
								<div class="row-fluid">
									<div class="inner-spacer-left">
										<?php $t->menu->show("main"); ?>
									</div>
								</div>
								
							</div>
							
							<!--drop navigation-->
							<div id="dropdown-nav" class="span7 drop-nav" data-label="<?php e__pe("Menu..."); ?>">
							</div>
							
						</div>
					</div>
				</div>
				
			</header>
			<!-- end header  -->