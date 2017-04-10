<?php $t =& peTheme(); ?>
<?php get_header(); ?>
<?php get_template_part("tagline"); ?>

<div class="row-fluid">
	
	<?php get_sidebar(); ?>
	
	<!--main content-->
	<div class="span8">
		<?php $t->content->loop(); ?>
	</div>
	<!--end main content-->
</div>

<?php get_footer(); ?>
