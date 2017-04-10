<?php
/*
Template Name: Portfolio
*/
?><?php $t =& peTheme(); ?>
<?php $content =& $t->content; ?>
<?php $project =& $t->project; ?>
<?php get_header(); ?>
<?php get_template_part("tagline"); ?>

<?php while ($content->looping() ) : ?>

<?php if (!post_password_required()): ?>

<div class="row-fluid">
	<div class="span12">
		<?php echo $content->content(); ?>
	</div>
</div>

<?php $t->project->portfolio($content->meta()->portfolio) ?>


<?php else: ?>
<p><?php $content->content(); ?></p>
<?php endif; ?>
<?php endwhile; ?>

<?php get_footer(); ?>
