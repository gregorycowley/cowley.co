<?php $t =& peTheme(); ?>
<?php get_header(); ?>
<?php get_template_part("tagline"); ?>
<?php $content =& $t->content; ?>

<?php while ($content->looping()): ?>
<?php $isSlider = ($content->meta()->settings->type === "slider"); ?>

<div class="row-fluid">
	<div class="span12">
		<?php $t->media->size($isSlider ? 560 : 940,353); ?>
		<?php $t->gallery->output(); ?>
		<?php $t->media->size(); ?>
	</div>
</div>
<?php endwhile; ?>

<?php get_footer(); ?>
