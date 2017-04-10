<?php $t =& peTheme(); ?>
<?php get_header(); ?>
<?php get_template_part("tagline"); ?>
<?php $content =& $t->content; ?>

<div class="row-fluid">
	
	<?php get_sidebar(); ?>
	
	<!--main content-->
	<?php while ($content->looping()): ?>
	<div class="span8">

		<div class="row-fluid">
			<div class="span12">
				<?php $content->content(); ?>	
			</div>
		</div>

	</div>
	<?php endwhile; ?>
	<!--end main content-->

</div>

<?php get_footer(); ?>
