<?php $t =& peTheme(); ?>
<?php list($portfolio) = $t->template->data(); ?>

<?php $content =& $t->content; ?>
<?php $project =& $t->project; ?>

<?php $cols = $portfolio->columns; ?>

<?php $mainClass = array("span6","span4","span3"); ?>
<?php $mainClass = $mainClass[$cols-2]; ?>

<!-- Project Feed -->
<div class="row-fluid cat-wrap single">
	<div class="category span12">

		<?php while ($content->looping()): ?>
		<?php $meta =& $content->meta(); ?>
		<?php $idx = $content->idx(); ?>
		<?php $last = $content->last(); ?>
		
		<?php if ($cols > 0 && ($idx % $cols) == 0): ?>
		<div class="row-fluid">
			<?php endif; ?>

			<div class="<?php echo $mainClass ?> category-thumbs">
				<a href="<?php echo get_permalink(); ?>">
					<?php $t->content->img(460,340) ?>
				</a>
				<h3>
					<a href="<?php echo get_permalink(); ?>"><?php $content->title(); ?></a>
				</h3>
				<span><?php echo $t->utils->truncateString(get_the_excerpt(),30); ?></span>
			</div>

			<?php if ($cols > 0 && (($idx == $last) || ($idx % $cols) == ($cols-1))): ?>
		</div>
		<?php endif; ?>
		<?php endwhile; ?>
	</div>
</div>
<!-- /Project Feed -->
