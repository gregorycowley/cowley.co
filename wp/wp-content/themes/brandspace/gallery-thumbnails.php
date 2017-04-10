<?php $t =& peTheme(); ?>
<?php list($pid,$conf,$loop) = $t->template->data(); ?>
<?php $cols = 4; ?>

<?php while ($item =& $loop->next()): ?>
<?php $hidden = ($conf->max > 0 && $item->idx >= $conf->max); ?>
<?php if ($cols > 0 && ($item->idx % $cols) == 0): ?>
<?php $lastrow = ($loop->last - $item->idx) < $cols ? "last-row" : ""; ?>
<div class="row-fluid gallery thumbs <?php echo $lastrow ?>">
<?php endif; ?>
<div class="span3<?php (($hidden) ? " hiddenLightboxContent" : "") ?>">
	<a 
		title="<?php echo esc_attr($item->title); ?>"
		class="peOver"
		data-target="flare" 
		data-flare-gallery="galPostThumb<?php echo $pid ?>"
		id="galPostThumb<?php echo "{$pid}_{$item->id}" ?>"
		data-flare-thumb="<?php echo $t->image->resizedImgUrl($item->img,90,74); ?>"
		<?php if ($conf->bw): ?>
		data-flare-bw="<?php echo $t->image->bw($item->img); ?>"
		<?php endif; ?>
		data-flare-plugin="<?php echo $conf->plugin ?>"
		data-flare-scale="<?php echo $conf->scale ?>"
		href="<?php echo $item->img; ?>"
		>
		<?php echo $hidden ? "" : $t->image->resizedImg($item->img,480,396); ?>
	</a>
</div>
<?php if ($cols > 0 && (($item->idx == $loop->last) || ($item->idx % $cols) == ($cols-1))): ?>
</div>
<?php endif; ?>
<?php endwhile; ?>
