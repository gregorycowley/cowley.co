<?php
/*
Template Name: Right Sidebar
*/
?><?php $t =& peTheme(); ?>
<?php get_header(); ?>
<?php get_template_part("tagline"); ?>
<?php $content =& $t->content; ?>

<div class="row-fluid">

	<?php while ($content->looping()): ?>
	<!--main content-->
	<div class="span7">
		<div class="row-fluid">
			<div class="span12">
				<?php $content->content(); ?>
			</div>
		</div>
	</div>
	<?php endwhile; ?>
	
	<div class="span1 sidebar-spacer-left"></div>
	
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
