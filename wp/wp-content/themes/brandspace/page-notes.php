<?php
/*
Template Name: Notes
*/
?><?php $t =& peTheme(); ?>
<?php get_header(); ?>
<?php get_template_part("tagline"); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>

<?php while ($content->looping()): ?>

<div class="row-fluid">
	<!--testimonial sidebar-->
	<div class="span5 sidebar sidebar-notes">
		<div class="inner-spacer-right inner-spacer-left-lrg">
		<?php if (!empty($meta->notes->items)): ?>
		<?php foreach ($meta->notes->items as $note): ?>
		<p class="note hand-written">
			<?php echo do_shortcode($note); ?>
		</p>
		<?php endforeach; ?>
		<?php endif; ?>
		</div>
	</div>
	<!--end sidebar-->
	
	
	<!--main content-->
	<div class="span7">
		<div class="row-fluid">
			<div class="inner-spacer-left">
				<div class="span12">
					<?php $content->content(); ?>
				</div>
			</div>
		</div>
	</div>
	<!--end main content-->
	
</div>


<?php endwhile; ?>

<?php get_footer(); ?>
