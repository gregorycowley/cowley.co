<?php $t =& peTheme(); ?>
<?php $meta = $t->content->meta(); ?>

<?php $show = $t->options->get("taglineShow") === "yes" ?>
<?php $show = $show && (empty($meta->tagline->show) || $meta->tagline->show === "yes");  ?>
<?php $borderClass = (empty($meta->tagline->border) || $meta->tagline->border === "no") ? "" : "border-bottom";  ?>

<?php if ($show): ?>
<?php $content = empty($meta->tagline->tagline) ? $t->options->get("taglineContent") : $meta->tagline->tagline; ?>

<!-- tagline-->
<section class="row-fluid tagline <?php echo $borderClass; ?>">
	
	<div class="span12">
		<?php $t->shortcode->run($content); ?>
	</div>
	
</section>
<!--end tagline-->
<?php endif; ?>