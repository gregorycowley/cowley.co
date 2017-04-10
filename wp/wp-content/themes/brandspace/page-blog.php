<?php
/*
Template Name: Blog
*/
?>
<?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>
<?php get_header(); ?>
<?php get_template_part("tagline"); ?>

<div class="row-fluid">
	
	<?php get_sidebar(); ?>
	
	<!--main content-->
	<div class="span8">
		<?php $content->blog($meta->blog); ?>
	</div>
	<!--end main content-->
</div

<?php get_footer(); ?>
