<?php $t =& peTheme(); ?>
<?php get_header(); ?>
<?php get_template_part("tagline"); ?>
<?php $content =& $t->content; ?>

<div class="row-fluid">
	
	<?php get_sidebar(); ?>
	
	<!--main content-->
	<div class="span8">

		<div class="row-fluid">
			<div class="span12">

				<h1><?php e__pe("Page Not Found"); ?></h1>
				<div class="alert alert-block alert-error fade in">
					<?php echo $t->options->get("404content"); ?>
				</div>

			</div>
		</div>

	</div>
	<!--end main content-->

</div>

<?php get_footer(); ?>
