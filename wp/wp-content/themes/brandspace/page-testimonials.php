<?php
/*
Template Name: Testimonials
*/
?><?php $t =& peTheme(); ?>
<?php get_header(); ?>
<?php get_template_part("tagline"); ?>
<?php $content =& $t->content; ?>
<?php $testimonial =& $t->testimonial; ?>
<?php $meta =& $content->meta(); ?>

<?php while ($content->looping()): ?>

<div class="row-fluid">
	
	<!--testimonial sidebar-->
	<div class="span5 sidebar sidebar-testimonial">
		
		<?php if ($testimonial->customLoop($meta->testimonials->items)): ?>
		<div class="quote66"></div>
		<ul class="testimonials">
			<?php while ($content->looping()): ?>
			<?php $info =& $content->meta()->info; ?>
			<li>
				<?php $content->content(); ?>
				<cite class="hand-written"><?php $content->title(); ?></cite>
				<small><?php echo $content->meta()->info->type; ?></small>
			</li>
			<?php endwhile; ?>
		</ul>
		<div class="quote99"></div>
		<?php $content->resetLoop(); ?>
		<?php endif; ?>

			
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
