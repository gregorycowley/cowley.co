<?php
/*
Template Name: Slider
*/
?><?php $t =& peTheme(); ?>
<?php get_header(); ?>
<?php get_template_part("tagline"); ?>
<?php $content =& $t->content; ?>
<?php $meta =& $content->meta(); ?>

<section  class="row-fluid banner">
	<?php $t->media->size($meta->gallery,940,350); ?>
	<?php $t->slider->output(); ?>
	<?php $t->media->size(); ?>
</section>

<?php while ($content->looping()): ?>
<div class="row-fluid">
	<div class="span12">
		<?php $content->content(); ?>
	</div>
</div>
<?php endwhile; ?>

<?php get_footer(); ?>
